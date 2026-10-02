<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamParticipant;
use App\Models\ExamQuestion;
use App\Models\ExamResult;
use App\Models\StudentAnswer;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    // Halaman detail ujian sebelum mulai
    public function show(Exam $exam)
    {
        $student = Auth::user();
        $this->authorizeExam($exam, $student);

        $participant = ExamParticipant::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        $exam->load(['subject', 'classroom', 'teacher', 'settings']);

        return view('student.exam.show', compact('exam', 'participant'));
    }

    // Mulai ujian
    public function start(Exam $exam)
    {
        $student = Auth::user();
        $this->authorizeExam($exam, $student);

        if ($exam->status !== 'active') {
            return redirect()->route('student.dashboard')->with('error', 'Ujian belum dibuka atau sudah berakhir.');
        }

        // Cek sudah ada participant
        $participant = ExamParticipant::firstOrCreate(
            ['exam_id' => $exam->id, 'student_id' => $student->id],
            ['status' => 'in_progress', 'started_at' => now()]
        );

        if ($participant->status === 'submitted' || $participant->status === 'timed_out') {
            return redirect()->route('student.exam.result', $exam)->with('info', 'Anda sudah mengumpulkan ujian ini.');
        }

        if (!$participant->started_at) {
            $participant->update(['status' => 'in_progress', 'started_at' => now()]);
        }

        ActivityLog::log('exam_started', "Siswa {$student->name} mulai mengerjakan ujian: {$exam->title}", $exam);

        return redirect()->route('student.exam.take', ['exam' => $exam->id, 'q' => 1]);
    }

    // Halaman pengerjaan ujian
    public function take(Exam $exam, Request $request)
    {
        $student = Auth::user();
        $participant = ExamParticipant::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        if ($participant->status === 'submitted' || $participant->status === 'timed_out') {
            return redirect()->route('student.exam.result', $exam);
        }

        // Cek waktu habis
        $remainingSeconds = $participant->remaining_seconds;
        if ($remainingSeconds <= 0 && $exam->settings?->auto_submit) {
            return $this->autoSubmit($exam, $participant);
        }

        $examQuestions = ExamQuestion::where('exam_id', $exam->id)
            ->with(['question.options'])
            ->orderBy('question_order')
            ->get();

        $settings = $exam->settings;
        if ($settings?->shuffle_questions && !$participant->started_at->eq($participant->started_at)) {
            // Shuffle seeded by participant ID for consistency
        }

        $currentIndex = max(1, min((int)$request->get('q', 1), $examQuestions->count()));
        $currentExamQuestion = $examQuestions[$currentIndex - 1] ?? $examQuestions->first();

        // Ambil semua jawaban siswa
        $answers = StudentAnswer::where('exam_participant_id', $participant->id)
            ->get()
            ->keyBy('exam_question_id');

        $exam->load(['subject']);

        return view('student.exam.take', compact(
            'exam', 'participant', 'examQuestions', 'currentExamQuestion',
            'currentIndex', 'answers', 'remainingSeconds'
        ));
    }

    // Simpan jawaban (AJAX)
    public function saveAnswer(Request $request, Exam $exam)
    {
        $student = Auth::user();
        $participant = ExamParticipant::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        $request->validate([
            'exam_question_id'  => 'required|exists:exam_questions,id',
            'selected_option_id'=> 'nullable|exists:question_options,id',
            'answer_text'       => 'nullable|string|max:5000',
            'is_marked'         => 'boolean',
        ]);

        $examQuestion = ExamQuestion::where('id', $request->exam_question_id)
            ->where('exam_id', $exam->id)
            ->firstOrFail();

        $question = $examQuestion->question;
        $isCorrect = null;
        $scoreObtained = 0;

        if ($request->selected_option_id && in_array($question->type, ['multiple_choice', 'true_false'])) {
            $option = $question->options->firstWhere('id', $request->selected_option_id);
            $isCorrect = $option?->is_correct;
            $scoreObtained = $isCorrect ? $question->score : 0;
        }

        $answer = StudentAnswer::updateOrCreate(
            ['exam_participant_id' => $participant->id, 'exam_question_id' => $request->exam_question_id],
            [
                'selected_option_id' => $request->selected_option_id,
                'answer_text'        => $request->answer_text,
                'is_correct'         => $isCorrect,
                'score_obtained'     => $scoreObtained,
                'is_marked'          => $request->boolean('is_marked', false),
                'answered_at'        => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Jawaban tersimpan',
            'answer'  => $answer,
        ]);
    }

    // Submit ujian
    public function submit(Request $request, Exam $exam)
    {
        $student = Auth::user();
        $participant = ExamParticipant::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        return $this->processSubmit($exam, $participant);
    }

    // Auto-submit (waktu habis)
    private function autoSubmit(Exam $exam, ExamParticipant $participant)
    {
        $this->processSubmit($exam, $participant, true);
        return redirect()->route('student.exam.result', $exam)->with('info', 'Waktu ujian habis. Jawaban dikumpulkan otomatis.');
    }

    // Proses kalkulasi hasil
    private function processSubmit(Exam $exam, ExamParticipant $participant, bool $isTimeout = false)
    {
        DB::transaction(function () use ($exam, $participant, $isTimeout) {
            $answers = StudentAnswer::where('exam_participant_id', $participant->id)->get();
            $totalScore    = $answers->sum('score_obtained');
            $correctCount  = $answers->where('is_correct', true)->count();
            $wrongCount    = $answers->where('is_correct', false)->whereNotNull('selected_option_id')->count();
            $totalQ        = ExamQuestion::where('exam_id', $exam->id)->count();
            $unanswered    = $totalQ - $answers->whereNotNull('selected_option_id')->count();
            $timeSpent     = $participant->time_spent_minutes;
            $passStatus    = $totalScore >= $exam->passing_grade ? 'pass' : 'fail';

            $participant->update([
                'status'       => $isTimeout ? 'timed_out' : 'submitted',
                'submitted_at' => now(),
                'is_late_submission' => $isTimeout,
            ]);

            ExamResult::updateOrCreate(
                ['exam_id' => $exam->id, 'student_id' => $participant->student_id],
                [
                    'exam_participant_id' => $participant->id,
                    'total_score'    => $totalScore,
                    'correct_answers'=> $correctCount,
                    'wrong_answers'  => $wrongCount,
                    'unanswered'     => $unanswered,
                    'time_spent_minutes' => $timeSpent,
                    'pass_status'    => $passStatus,
                ]
            );

            ActivityLog::log('exam_submitted', "Siswa mengumpulkan ujian: {$exam->title}", $exam);
        });

        return redirect()->route('student.exam.result', $exam);
    }

    // Halaman hasil ujian
    public function result(Exam $exam)
    {
        $student = Auth::user();
        $result = ExamResult::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->with(['exam.subject'])
            ->firstOrFail();

        $exam->load(['subject', 'settings']);

        return view('student.exam.result', compact('exam', 'result'));
    }

    // Riwayat ujian
    public function history(Request $request)
    {
        $student = Auth::user();
        $query = ExamResult::where('student_id', $student->id)
            ->with(['exam.subject', 'exam']);

        if ($request->filter === 'pass') {
            $query->where('pass_status', 'pass');
        } elseif ($request->filter === 'fail') {
            $query->where('pass_status', 'fail');
        }

        $results = $query->latest()->paginate(10);

        return view('student.exam.history', compact('results'));
    }

    // Validasi akses ujian
    private function authorizeExam(Exam $exam, $student)
    {
        $classroom = $student->currentClassroom();
        if (!$classroom || $classroom->id !== $exam->classroom_id) {
            abort(403, 'Anda tidak terdaftar di kelas ujian ini.');
        }
    }
}
