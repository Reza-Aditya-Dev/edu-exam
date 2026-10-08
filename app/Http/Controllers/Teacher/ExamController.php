<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamSetting;
use App\Models\Question;
use App\Models\ExamResult;
use App\Models\ExamParticipant;
use App\Models\StudentAnswer;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user();
        $baseQuery = Exam::forTeacher($teacher->id);

        $stats = [
            'total'     => (clone $baseQuery)->count(),
            'active'    => (clone $baseQuery)->where('status', 'active')->count(),
            'scheduled' => (clone $baseQuery)->where('status', 'scheduled')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'draft'     => (clone $baseQuery)->where('status', 'draft')->count(),
        ];

        $query = (clone $baseQuery)->with(['subject', 'classroom.students', 'participants', 'results', 'academicYear', 'settings']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }
        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        $exams = $query->latest('exam_date')->paginate(10)->withQueryString();
        $academicYears = \App\Models\AcademicYear::orderByDesc('is_active')->get();
        $classrooms = \App\Models\Classroom::orderBy('name')->get();

        return view('teacher.exam.index', compact('exams', 'stats', 'academicYears', 'classrooms'));
    }

    public function create()
    {
        $subjects      = \App\Models\Subject::orderBy('name')->get();
        $classrooms    = \App\Models\Classroom::with(['academicYear', 'students'])->orderBy('name')->get();
        $academicYears = \App\Models\AcademicYear::orderByDesc('is_active')->get();
        return view('teacher.exam.create', compact('subjects', 'classrooms', 'academicYears'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'           => 'required|string|max:255',
            'subject_id'      => 'required|exists:subjects,id',
            'classroom_id'    => 'required|exists:classrooms,id',
            'academic_year_id'=> 'required|exists:academic_years,id',
            'exam_type'       => 'required|in:UTS,UAS,UH,Quiz,Remedial,Lainnya',
            'exam_date'       => 'required|date',
            'start_time'      => 'required',
            'end_time'        => 'required',
            'duration_minutes'=> 'required|integer|min:1|max:480',
            'passing_grade'   => 'required|numeric|min:0|max:100',
            'question_ids'    => 'required|array|min:1',
            'question_ids.*'  => 'exists:questions,id',
        ]);

        DB::transaction(function () use ($request) {
            $exam = Exam::create([
                'subject_id'      => $request->subject_id,
                'classroom_id'    => $request->classroom_id,
                'teacher_id'      => Auth::id(),
                'academic_year_id'=> $request->academic_year_id,
                'title'           => $request->title,
                'exam_type'       => $request->exam_type,
                'description'     => $request->description,
                'instructions'    => $request->instructions,
                'exam_date'       => $request->exam_date,
                'start_time'      => $request->start_time,
                'end_time'        => $request->end_time,
                'duration_minutes'=> $request->duration_minutes,
                'total_questions' => count($request->question_ids),
                'passing_grade'   => $request->passing_grade,
                'status'          => $request->action === 'publish' ? 'scheduled' : 'draft',
            ]);

            foreach ($request->question_ids as $order => $qId) {
                ExamQuestion::create([
                    'exam_id'        => $exam->id,
                    'question_id'    => $qId,
                    'question_order' => $order + 1,
                ]);
            }

            ExamSetting::create([
                'exam_id'                 => $exam->id,
                'shuffle_questions'       => $request->boolean('shuffle_questions'),
                'shuffle_options'         => $request->boolean('shuffle_options'),
                'auto_save'               => $request->boolean('auto_save', true),
                'auto_submit'             => $request->boolean('auto_submit', true),
                'show_result_immediately' => $request->boolean('show_result_immediately', true),
                'allow_review'            => $request->boolean('allow_review'),
                'show_correct_answers'    => $request->boolean('show_correct_answers'),
                'max_attempts'            => $request->max_attempts ?? 1,
            ]);

            ActivityLog::log('exam_created', "Ujian dibuat: {$exam->title}", $exam);
        });

        return redirect()->route('teacher.exams.index')->with('success', 'Ujian berhasil disimpan.');
    }

    public function edit(Exam $exam)
    {
        $this->authorizeExam($exam);
        $subjects   = \App\Models\Subject::orderBy('name')->get();
        $classrooms = \App\Models\Classroom::with('academicYear')->orderBy('name')->get();
        $exam->load(['questions', 'settings', 'classroom', 'subject']);
        return view('teacher.exam.edit', compact('exam', 'subjects', 'classrooms'));
    }

    public function update(Request $request, Exam $exam)
    {
        $this->authorizeExam($exam);
        if (in_array($exam->status, ['active', 'completed'])) {
            return back()->with('error', 'Ujian yang sudah aktif atau selesai tidak dapat diedit.');
        }

        $request->validate([
            'title'           => 'required|string|max:255',
            'exam_date'       => 'required|date',
            'duration_minutes'=> 'required|integer|min:1',
            'passing_grade'   => 'required|numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($request, $exam) {
            $exam->update($request->only([
                'title', 'exam_type', 'description', 'instructions',
                'exam_date', 'start_time', 'end_time', 'duration_minutes', 'passing_grade',
            ]));

            if ($request->question_ids) {
                ExamQuestion::where('exam_id', $exam->id)->delete();
                foreach ($request->question_ids as $order => $qId) {
                    ExamQuestion::create([
                        'exam_id'        => $exam->id,
                        'question_id'    => $qId,
                        'question_order' => $order + 1,
                    ]);
                }
                $exam->update(['total_questions' => count($request->question_ids)]);
            }

            if ($exam->settings) {
                $exam->settings->update([
                    'shuffle_questions'       => $request->boolean('shuffle_questions'),
                    'shuffle_options'         => $request->boolean('shuffle_options'),
                    'auto_save'               => $request->boolean('auto_save', true),
                    'auto_submit'             => $request->boolean('auto_submit', true),
                    'show_result_immediately' => $request->boolean('show_result_immediately', true),
                    'allow_review'            => $request->boolean('allow_review'),
                    'show_correct_answers'    => $request->boolean('show_correct_answers'),
                ]);
            }

            ActivityLog::log('exam_updated', "Ujian diperbarui: {$exam->title}", $exam);
        });

        return redirect()->route('teacher.exams.index')->with('success', 'Ujian berhasil diperbarui.');
    }

    public function publish(Exam $exam)
    {
        $this->authorizeExam($exam);
        $exam->update(['status' => 'scheduled']);

        // Notifikasi ke semua siswa
        $classroom = $exam->classroom()->with('students')->first();
        foreach ($classroom->students as $student) {
            Notification::send($student->id, 'Ujian Dijadwalkan', "Ujian {$exam->title} dijadwalkan pada {$exam->formatted_date}.", 'exam', $exam);
        }

        ActivityLog::log('exam_published', "Ujian diterbitkan: {$exam->title}", $exam);
        return back()->with('success', 'Ujian berhasil diterbitkan.');
    }

    public function activate(Exam $exam)
    {
        $this->authorizeExam($exam);
        $exam->update(['status' => 'active']);
        ActivityLog::log('exam_activated', "Ujian diaktifkan: {$exam->title}", $exam);
        return back()->with('success', 'Ujian sekarang aktif.');
    }

    public function complete(Exam $exam)
    {
        $this->authorizeExam($exam);

        DB::transaction(function () use ($exam) {
            $exam->update(['status' => 'completed']);

            // Selesaikan otomatis seluruh siswa yang masih berstatus in_progress
            $participants = ExamParticipant::where('exam_id', $exam->id)
                ->where('status', 'in_progress')
                ->get();

            foreach ($participants as $participant) {
                $answers = StudentAnswer::where('exam_participant_id', $participant->id)->get();
                $totalScore    = $answers->sum('score_obtained');
                $correctCount  = $answers->where('is_correct', true)->count();
                $wrongCount    = $answers->where('is_correct', false)->whereNotNull('selected_option_id')->count();
                $totalQ        = ExamQuestion::where('exam_id', $exam->id)->count();
                $unanswered    = $totalQ - $answers->whereNotNull('selected_option_id')->count();
                $timeSpent     = $participant->time_spent_minutes;
                $passStatus    = $totalScore >= $exam->passing_grade ? 'pass' : 'fail';

                $participant->update([
                    'status'       => 'submitted',
                    'submitted_at' => now(),
                ]);

                ExamResult::updateOrCreate(
                    ['exam_id' => $exam->id, 'student_id' => $participant->student_id],
                    [
                        'exam_participant_id' => $participant->id,
                        'total_score'         => $totalScore,
                        'correct_answers'     => $correctCount,
                        'wrong_answers'       => $wrongCount,
                        'unanswered'          => $unanswered,
                        'time_spent_minutes'  => $timeSpent,
                        'pass_status'         => $passStatus,
                    ]
                );
            }

            ActivityLog::log('exam_completed', "Ujian diselesaikan oleh guru: {$exam->title}", $exam);
        });

        return back()->with('success', 'Ujian berhasil ditutup dan seluruh pengerjaan siswa telah diselesaikan.');
    }

    public function destroy(Exam $exam)
    {
        $this->authorizeExam($exam);
        $title = $exam->title;

        DB::transaction(function () use ($exam, $title) {
            // Ambil semua ID partisipan ujian
            $participantIds = ExamParticipant::where('exam_id', $exam->id)->pluck('id');

            // Hapus jawaban siswa
            if ($participantIds->isNotEmpty()) {
                StudentAnswer::whereIn('exam_participant_id', $participantIds)->delete();
            }

            // Hapus hasil ujian & peserta
            ExamResult::where('exam_id', $exam->id)->delete();
            ExamParticipant::where('exam_id', $exam->id)->delete();

            // Hapus butir soal ujian
            ExamQuestion::where('exam_id', $exam->id)->delete();

            // Hapus pengaturan ujian jika ada
            if ($exam->settings) {
                $exam->settings()->delete();
            }

            // Hapus ujian
            $exam->delete();

            ActivityLog::log('exam_deleted', "Ujian '{$title}' dan seluruh hasil serta data terkait berhasil dihapus oleh guru.");
        });

        return redirect()->route('teacher.exams.index')->with('success', "Ujian '{$title}' beserta seluruh hasil ujian berhasil dihapus permanen.");
    }

    // Hapus seluruh hasil pengerjaan siswa pada ujian ini (reset hasil ujian)
    public function clearResults(Exam $exam)
    {
        $this->authorizeExam($exam);

        DB::transaction(function () use ($exam) {
            $participantIds = ExamParticipant::where('exam_id', $exam->id)->pluck('id');
            if ($participantIds->isNotEmpty()) {
                StudentAnswer::whereIn('exam_participant_id', $participantIds)->delete();
            }
            ExamResult::where('exam_id', $exam->id)->delete();
            ExamParticipant::where('exam_id', $exam->id)->delete();

            ActivityLog::log('exam_results_cleared', "Seluruh hasil ujian '{$exam->title}' berhasil dihapus/direset oleh guru.");
        });

        return back()->with('success', "Seluruh hasil ujian '{$exam->title}' berhasil dihapus. Ujian kini bersih dan dapat diujikan kembali.");
    }

    // Hapus satu hasil ujian siswa tertentu (reset ujian siswa tersebut)
    public function destroyResult(Exam $exam, $studentId)
    {
        $this->authorizeExam($exam);

        DB::transaction(function () use ($exam, $studentId) {
            $student = \App\Models\User::findOrFail($studentId);
            $participant = ExamParticipant::where('exam_id', $exam->id)->where('student_id', $studentId)->first();
            if ($participant) {
                StudentAnswer::where('exam_participant_id', $participant->id)->delete();
                $participant->delete();
            }
            ExamResult::where('exam_id', $exam->id)->where('student_id', $studentId)->delete();

            ActivityLog::log('student_result_deleted', "Hasil ujian siswa '{$student->name}' pada ujian '{$exam->title}' dihapus oleh guru.");
        });

        return back()->with('success', "Hasil ujian siswa berhasil dihapus.");
    }

    // Hasil ujian semua siswa
    public function results(Exam $exam)
    {
        $this->authorizeExam($exam);
        $results = ExamResult::where('exam_id', $exam->id)
            ->with(['student', 'participant'])
            ->orderByDesc('total_score')
            ->get();

        $stats = [
            'total'     => $results->count(),
            'avg'       => round($results->avg('total_score'), 1),
            'max'       => $results->max('total_score'),
            'min'       => $results->min('total_score'),
            'pass'      => $results->where('pass_status', 'pass')->count(),
            'fail'      => $results->where('pass_status', 'fail')->count(),
        ];

        $exam->load(['subject', 'classroom']);
        return view('teacher.exam.results', compact('exam', 'results', 'stats'));
    }

    // Detail jawaban satu siswa
    public function studentAnswerDetail(Exam $exam, $studentId)
    {
        $this->authorizeExam($exam);
        $exam->load(['subject', 'classroom', 'teacher']);
        $student = \App\Models\User::findOrFail($studentId);
        $participant = \App\Models\ExamParticipant::where('exam_id', $exam->id)
            ->where('student_id', $studentId)->firstOrFail();
        $result = ExamResult::where('exam_id', $exam->id)->where('student_id', $studentId)->firstOrFail();
        $answers = StudentAnswer::where('exam_participant_id', $participant->id)
            ->with(['examQuestion.question.options', 'selectedOption'])
            ->get()
            ->sortBy('examQuestion.question_order');

        return view('teacher.exam.student_detail', compact('exam', 'student', 'participant', 'result', 'answers'));
    }

    // Analitik ujian
    public function analytics(Exam $exam)
    {
        $this->authorizeExam($exam);
        $results = ExamResult::where('exam_id', $exam->id)->with('student')->get();

        // Distribusi nilai (0-100 dibagi 10 bucket)
        $distribution = collect(range(0, 9))->mapWithKeys(function ($i) use ($results) {
            $min = $i * 10;
            $max = $min + 9;
            $label = "{$min}-{$max}";
            $count = $results->whereBetween('total_score', [$min, $max])->count();
            return [$label => $count];
        });

        // Analisis per soal
        $examQuestions = ExamQuestion::where('exam_id', $exam->id)
            ->with('question')->orderBy('question_order')->get();

        $questionStats = $examQuestions->map(function ($eq) use ($exam) {
            $total = ExamParticipant::where('exam_id', $exam->id)->whereIn('status', ['submitted','timed_out'])->count();
            $correct = StudentAnswer::where('exam_question_id', $eq->id)->where('is_correct', true)->count();
            return [
                'order'        => $eq->question_order,
                'question'     => $eq->question->question_text,
                'total'        => $total,
                'correct'      => $correct,
                'percent'      => $total > 0 ? round(($correct / $total) * 100) : 0,
            ];
        });

        $exam->load(['subject', 'classroom']);
        $stats = [
            'total' => $results->count(),
            'avg'   => round($results->avg('total_score'), 1),
            'max'   => $results->max('total_score'),
            'min'   => $results->min('total_score'),
            'pass'  => $results->where('pass_status', 'pass')->count(),
            'fail'  => $results->where('pass_status', 'fail')->count(),
            'pass_rate' => $results->count() > 0
                ? round(($results->where('pass_status', 'pass')->count() / $results->count()) * 100, 1)
                : 0,
        ];

        return view('teacher.exam.analytics', compact('exam', 'results', 'stats', 'distribution', 'questionStats'));
    }

    // Soal dari bank soal (AJAX)
    public function getQuestions(Request $request)
    {
        $teacher = Auth::user();
        $questions = Question::where('created_by', $teacher->id)
            ->where('is_active', true)
            ->when($request->subject_id, fn($q) => $q->where('subject_id', $request->subject_id))
            ->when($request->classroom_id, fn($q) => $q->where('classroom_id', $request->classroom_id))
            ->when($request->type,       fn($q) => $q->where('type', $request->type))
            ->when($request->search,     fn($q) => $q->where(function($sq) use ($request) {
                $sq->where('question_text', 'like', '%'.$request->search.'%')
                   ->orWhere('topic', 'like', '%'.$request->search.'%');
            }))
            ->with(['subject', 'classroom', 'options'])
            ->limit(1000)
            ->get();
        return response()->json($questions);
    }

    private function authorizeExam(Exam $exam)
    {
        if ($exam->teacher_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke ujian ini.');
        }
    }
}
