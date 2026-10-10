<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamParticipant;
use App\Models\ExamQuestion;
use App\Models\ExamResult;
use App\Models\StudentAnswer;
use App\Models\ActivityLog;
use App\Models\Notification;
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
            return redirect()->route('student.dashboard')->with('error', 'Ujian belum dibuka atau waktu pengerjaan ujian telah berakhir.');
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
        $this->authorizeExam($exam, $student);

        $participant = ExamParticipant::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        if ($participant->status === 'submitted' || $participant->status === 'timed_out') {
            return redirect()->route('student.exam.result', $exam);
        }

        // Cek jika guru sudah menutup ujian
        if ($exam->status !== 'active') {
            return $this->autoSubmit($exam, $participant, 'Ujian telah ditutup oleh guru pengampu.');
        }

        // Cek waktu habis (otomatis berakhir jika melewati batas waktu)
        $remainingSeconds = $participant->remaining_seconds;
        if ($remainingSeconds <= 0) {
            return $this->autoSubmit($exam, $participant, 'Waktu ujian telah berakhir. Jawaban dikumpulkan otomatis.');
        }

        $examQuestions = ExamQuestion::where('exam_id', $exam->id)
            ->with(['question.options'])
            ->orderBy('question_order')
            ->get();

        $settings = $exam->settings;
        $shouldShuffleQuestions = $settings ? (bool)$settings->shuffle_questions : true;
        if ($shouldShuffleQuestions) {
            $examQuestions = $examQuestions->sortBy(function ($eq) use ($participant) {
                return crc32($participant->id . '_' . $eq->id);
            })->values();
        }

        $currentIndex = max(1, min((int)$request->get('q', 1), $examQuestions->count()));
        $currentExamQuestion = $examQuestions[$currentIndex - 1] ?? $examQuestions->first();

        // Urutan pilihan jawaban PG TIDAK diacak (tetap urut A, B, C, D, E sesuai nomor/urutan asli)
        if ($currentExamQuestion && $currentExamQuestion->question && $currentExamQuestion->question->relationLoaded('options')) {
            $currentExamQuestion->question->setRelation(
                'options',
                $currentExamQuestion->question->options->sortBy(function ($opt) {
                    return [$opt->sort_order ?? 0, $opt->label ?? '', $opt->id];
                })->values()
            );
        }

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
        $this->authorizeExam($exam, $student);

        $participant = ExamParticipant::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        // Cek jika guru telah menutup ujian
        if ($exam->status !== 'active') {
            $this->processSubmit($exam, $participant, true);
            return response()->json([
                'success'     => false,
                'status'      => 'closed',
                'exam_closed' => true,
                'redirect'    => route('student.exam.result', $exam),
                'message'     => 'Ujian telah ditutup oleh guru pengampu.',
            ]);
        }

        // Cek jika waktu ujian sudah habis
        if ($participant->remaining_seconds <= 0) {
            $this->processSubmit($exam, $participant, true);
            return response()->json([
                'success'     => false,
                'status'      => 'timeout',
                'timeout'     => true,
                'redirect'    => route('student.exam.result', $exam),
                'message'     => 'Waktu ujian telah berakhir.',
            ]);
        }

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
            'status'  => 'ok',
            'message' => 'Jawaban tersimpan',
            'answer'  => [
                'id'                 => $answer->id,
                'exam_question_id'   => $answer->exam_question_id,
                'selected_option_id' => $answer->selected_option_id,
                'is_marked'          => (bool)$answer->is_marked,
            ],
        ]);
    }

    // Submit / Tutup Ujian oleh Siswa
    public function submit(Request $request, Exam $exam)
    {
        $student = Auth::user();
        $this->authorizeExam($exam, $student);

        $participant = ExamParticipant::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        // 1. Cek jika waktu habis (timeout)
        $isTimeout = $request->boolean('is_timeout') || ($participant->remaining_seconds <= 0);

        // 2. Jika bukan timeout (siswa mencoba menutup ujian secara manual):
        if (!$isTimeout) {
            // Cek jika guru sudah menutup ujian
            if ($exam->status !== 'active') {
                return $this->autoSubmit($exam, $participant, 'Ujian telah ditutup oleh guru pengampu.');
            }

            // Validasi: Siswa TIDAK BISA menutup ujian kecuali seluruh soal SUDAH dijawab!
            $totalQuestions = ExamQuestion::where('exam_id', $exam->id)->count();
            $answers = StudentAnswer::where('exam_participant_id', $participant->id)->get();
            $answeredCount = $answers->filter(function ($a) {
                return $a->selected_option_id !== null || (!empty(trim($a->answer_text ?? '')));
            })->count();

            $unansweredCount = $totalQuestions - $answeredCount;
            if ($unansweredCount > 0) {
                return redirect()->route('student.exam.take', ['exam' => $exam->id, 'q' => $request->get('q', 1)])
                    ->with('error', "Ujian tidak dapat ditutup karena masih ada {$unansweredCount} butir soal yang belum dijawab. Harap selesaikan seluruh butir soal terlebih dahulu.");
            }
        }

        return $this->processSubmit($exam, $participant, $isTimeout);
    }

    // Cek Status Ujian secara Real-time (AJAX Polling)
    public function checkStatus(Exam $exam)
    {
        $student = Auth::user();
        $this->authorizeExam($exam, $student);

        $participant = ExamParticipant::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        if (!$participant || $participant->status !== 'in_progress') {
            return response()->json([
                'active'   => false,
                'redirect' => route('student.exam.result', $exam),
                'message'  => 'Ujian telah selesai.',
            ]);
        }

        // Cek jika guru menutup ujian
        if ($exam->status !== 'active') {
            $this->processSubmit($exam, $participant, true);
            return response()->json([
                'active'   => false,
                'redirect' => route('student.exam.result', $exam),
                'message'  => 'Ujian telah ditutup oleh guru pengampu.',
            ]);
        }

        // Cek jika batas waktu habis
        $remainingSeconds = $participant->remaining_seconds;
        if ($remainingSeconds <= 0) {
            $this->processSubmit($exam, $participant, true);
            return response()->json([
                'active'   => false,
                'redirect' => route('student.exam.result', $exam),
                'message'  => 'Waktu ujian telah berakhir.',
            ]);
        }

        return response()->json([
            'active'           => true,
            'remaining_seconds'=> $remainingSeconds,
        ]);
    }

    // Auto-submit (waktu habis atau ditutup guru)
    private function autoSubmit(Exam $exam, ExamParticipant $participant, string $message = 'Waktu ujian telah berakhir. Jawaban dikumpulkan otomatis.')
    {
        $this->processSubmit($exam, $participant, true);
        return redirect()->route('student.exam.result', $exam)->with('info', $message);
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

            // Notifikasi ke Siswa yang bersangkutan
            Notification::send(
                $participant->student_id,
                'Ujian Berhasil Dikumpulkan',
                "Ujian {$exam->title} berhasil dikumpulkan dengan nilai {$totalScore} (" . ($passStatus === 'pass' ? 'Tuntas' : 'Remedial') . ").",
                'result',
                $exam
            );

            // Notifikasi ke Guru Pengampu ujian
            if ($exam->teacher_id) {
                $studentUser = $participant->student;
                $studentName = $studentUser ? $studentUser->name : 'Siswa';
                Notification::send(
                    $exam->teacher_id,
                    'Jawaban Siswa Masuk',
                    "{$studentName} telah mengumpulkan ujian {$exam->title} (Nilai: {$totalScore}).",
                    'exam',
                    $exam
                );
            }
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
            ->with(['exam.subject', 'exam.teacher', 'exam.classroom']);

        if ($request->filter === 'pass' || $request->filter === 'lulus') {
            $query->where('pass_status', 'pass');
        } elseif ($request->filter === 'fail' || $request->filter === 'remedial') {
            $query->where('pass_status', 'fail');
        }

        if ($request->filled('subject_id')) {
            $query->whereHas('exam', function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        if ($search = trim($request->get('search') ?? $request->get('q') ?? '')) {
            $query->whereHas('exam', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('subject', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $perPage = in_array((int)$request->get('per_page'), [5, 10, 20]) ? (int)$request->get('per_page') : 10;
        $results = $query->latest()->paginate($perPage)->withQueryString();

        $allResults = ExamResult::where('student_id', $student->id)->with('exam.subject')->get();
        $totalCount = $allResults->count();
        $passedCount = $allResults->where('pass_status', 'pass')->count();
        $failedCount = $allResults->where('pass_status', 'fail')->count();
        $avgScore = $totalCount > 0 ? round($allResults->avg('total_score'), 1) : 0;

        $subjects = $allResults->map(fn($r) => $r->exam->subject ?? null)->filter()->unique('id')->values();

        return view('student.exam.history', compact('results', 'totalCount', 'passedCount', 'failedCount', 'avgScore', 'subjects'));
    }

    // Validasi akses ujian
    private function authorizeExam(Exam $exam, $student)
    {
        $isEnrolled = $student->classrooms()->where('classrooms.id', $exam->classroom_id)->exists();
        if (!$isEnrolled) {
            abort(403, 'Anda tidak terdaftar di kelas ujian ini.');
        }
    }
}
