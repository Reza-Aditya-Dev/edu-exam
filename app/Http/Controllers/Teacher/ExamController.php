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
        Exam::syncScheduledAndExpiredExams();
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
            'timezone'        => 'required|in:Asia/Jakarta,Asia/Makassar,Asia/Jayapura',
            'exam_date'       => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    $tz = $request->input('timezone', 'Asia/Jakarta');
                    $todayInTz = now()->setTimezone($tz)->format('Y-m-d');
                    if ($value < $todayInTz) {
                        $fail('Tanggal pelaksanaan ujian tidak boleh lebih awal dari hari ini (' . $todayInTz . ').');
                    }
                },
            ],
            'start_time'      => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('exam_date')) {
                        $tz = $request->input('timezone', 'Asia/Jakarta');
                        $nowInTz = now()->setTimezone($tz);
                        $todayInTz = $nowInTz->format('Y-m-d');
                        if ($request->exam_date === $todayInTz) {
                            $currentHm = $nowInTz->format('H:i');
                            $tzCode = \App\Models\Exam::SUPPORTED_TIMEZONES[$tz]['code'] ?? 'WIB';
                            if ($value < $currentHm) {
                                $fail("Waktu mulai ujian tidak boleh lebih awal dari waktu saat ini ({$currentHm} {$tzCode}).");
                            }
                        }
                    }
                },
            ],
            'end_time'        => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('start_time')) {
                        if ($value <= $request->start_time) {
                            $fail('Waktu selesai ujian harus lebih besar (setelah) dari waktu mulai ujian.');
                        }
                    }
                },
            ],
            'duration_minutes'=> 'required|integer|min:1|max:480',
            'passing_grade'   => 'required|numeric|min:0|max:100',
            'question_ids'    => 'required|array|min:1',
            'question_ids.*'  => 'exists:questions,id',
        ], [
            'timezone.required'        => 'Zona waktu pelaksanaan ujian wajib dipilih.',
            'timezone.in'              => 'Zona waktu tidak valid. Pilih WIB, WITA, atau WIT.',
            'start_time.required'      => 'Jam mulai ujian wajib diisi.',
            'end_time.required'        => 'Jam selesai ujian wajib diisi.',
            'question_ids.required'    => 'Silakan masukkan minimal 1 butir soal ke dalam ujian.',
            'question_ids.min'         => 'Silakan masukkan minimal 1 butir soal ke dalam ujian.',
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
                'timezone'        => $request->input('timezone', 'Asia/Jakarta'),
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

            // Notifikasi ke seluruh siswa kelas jika langsung diterbitkan
            if ($exam->status === 'scheduled') {
                $clsName = $exam->classroom?->name ?? 'Kelas Anda';
                $exam->notifyClassroomStudents(
                    'Ujian Baru Dijadwalkan',
                    "Ujian {$exam->title} ({$exam->subject?->name}) untuk kelas {$clsName} telah dijadwalkan pada {$exam->formatted_date}.",
                    'exam'
                );
            }

            // Notifikasi ke seluruh Admin
            foreach (\App\Models\User::admins()->get() as $admin) {
                Notification::send($admin->id, 'Paket Ujian Baru', "Guru " . auth()->user()->name . " membuat paket ujian: {$exam->title}.", 'exam', $exam);
            }
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
            'timezone'        => 'required|in:Asia/Jakarta,Asia/Makassar,Asia/Jayapura',
            'exam_date'       => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    $tz = $request->input('timezone', 'Asia/Jakarta');
                    $todayInTz = now()->setTimezone($tz)->format('Y-m-d');
                    if ($value < $todayInTz) {
                        $fail('Tanggal pelaksanaan ujian tidak boleh lebih awal dari hari ini (' . $todayInTz . ').');
                    }
                },
            ],
            'start_time'      => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('exam_date')) {
                        $tz = $request->input('timezone', 'Asia/Jakarta');
                        $nowInTz = now()->setTimezone($tz);
                        $todayInTz = $nowInTz->format('Y-m-d');
                        if ($request->exam_date === $todayInTz) {
                            $currentHm = $nowInTz->format('H:i');
                            $tzCode = \App\Models\Exam::SUPPORTED_TIMEZONES[$tz]['code'] ?? 'WIB';
                            if ($value < $currentHm) {
                                $fail("Waktu mulai ujian tidak boleh lebih awal dari waktu saat ini ({$currentHm} {$tzCode}).");
                            }
                        }
                    }
                },
            ],
            'end_time'        => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('start_time')) {
                        if ($value <= $request->start_time) {
                            $fail('Waktu selesai ujian harus lebih besar (setelah) dari waktu mulai ujian.');
                        }
                    }
                },
            ],
            'duration_minutes'=> 'required|integer|min:1|max:480',
            'passing_grade'   => 'required|numeric|min:0|max:100',
        ], [
            'timezone.required'        => 'Zona waktu pelaksanaan ujian wajib dipilih.',
            'timezone.in'              => 'Zona waktu tidak valid. Pilih WIB, WITA, atau WIT.',
            'start_time.required'      => 'Jam mulai ujian wajib diisi.',
            'end_time.required'        => 'Jam selesai ujian wajib diisi.',
        ]);

        DB::transaction(function () use ($request, $exam) {
            $exam->update($request->only([
                'title', 'exam_type', 'description', 'instructions',
                'exam_date', 'start_time', 'end_time', 'timezone', 'duration_minutes', 'passing_grade',
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

        // Notifikasi otomatis ke seluruh siswa rombel kelas ini
        $clsName = $exam->classroom?->name ?? 'Kelas Anda';
        $exam->notifyClassroomStudents(
            'Ujian Baru Dijadwalkan',
            "Ujian {$exam->title} ({$exam->subject?->name}) untuk {$clsName} dijadwalkan pada {$exam->formatted_date}.",
            'exam'
        );

        ActivityLog::log('exam_published', "Ujian diterbitkan: {$exam->title}", $exam);
        return back()->with('success', 'Ujian berhasil diterbitkan.');
    }

    public function activate(Exam $exam)
    {
        $this->authorizeExam($exam);
        $exam->update(['status' => 'active']);

        // Notifikasi ke seluruh siswa kelas bahwa sesi telah dibuka
        $clsName = $exam->classroom?->name ?? 'Kelas Anda';
        $exam->notifyClassroomStudents(
            'Ujian Telah Dimulai!',
            "Sesi ujian {$exam->title} ({$exam->subject?->name}) untuk {$clsName} kini telah aktif. Silakan masuk dan mulai mengerjakan!",
            'exam'
        );

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

            $examQuestions = ExamQuestion::where('exam_id', $exam->id)->with('question')->get();

            foreach ($participants as $participant) {
                $answers = StudentAnswer::where('exam_participant_id', $participant->id)->get()->keyBy('exam_question_id');
                $totalScore = 0;
                $correctCount = 0;
                $wrongCount = 0;
                $unansweredCount = 0;
                $hasPendingEssay = false;

                foreach ($examQuestions as $eq) {
                    $q = $eq->question;
                    $ans = $answers->get($eq->id);
                    $hasAnswer = $ans && ($ans->selected_option_id !== null || !empty(trim($ans->answer_text ?? '')));

                    if (!$hasAnswer) {
                        $unansweredCount++;
                    }

                    if ($q && in_array($q->type, ['multiple_choice', 'true_false'])) {
                        if ($ans && $ans->is_correct) {
                            $correctCount++;
                            $totalScore += (float)$ans->score_obtained;
                        } elseif ($hasAnswer) {
                            $wrongCount++;
                        }
                    } elseif ($q && $q->type === 'essay') {
                        if ($ans && $ans->is_correct === true) {
                            $correctCount++;
                            $totalScore += (float)$ans->score_obtained;
                        } elseif ($ans && $ans->is_correct === false) {
                            $wrongCount++;
                            $totalScore += (float)$ans->score_obtained;
                        } else {
                            $hasPendingEssay = true;
                            if ($ans && $ans->score_obtained > 0) {
                                $totalScore += (float)$ans->score_obtained;
                            }
                        }
                    } else {
                        if ($ans && $ans->is_correct) {
                            $correctCount++;
                        } elseif ($hasAnswer) {
                            $wrongCount++;
                        }
                        if ($ans) {
                            $totalScore += (float)$ans->score_obtained;
                        }
                    }
                }

                $passStatus = $totalScore >= $exam->passing_grade ? 'pass' : 'fail';
                $gradingStatus = $hasPendingEssay ? 'pending_review' : 'graded';

                // Tetapkan waktu pengumpulan satu kali secara konsisten
                $submittedAt = now();

                // Hitung durasi pengerjaan (dalam menit) berdasarkan waktu mulai riil dan waktu pengumpulan
                $timeSpent = 0;
                if ($participant->started_at) {
                    $timeSpent = (int) $participant->started_at->diffInMinutes($submittedAt);
                }

                $participant->update([
                    'status'       => 'submitted',
                    'submitted_at' => $submittedAt,
                ]);

                ExamResult::updateOrCreate(
                    ['exam_id' => $exam->id, 'student_id' => $participant->student_id],
                    [
                        'exam_participant_id' => $participant->id,
                        'total_score'         => $totalScore,
                        'correct_answers'     => $correctCount,
                        'wrong_answers'       => $wrongCount,
                        'unanswered'          => $unansweredCount,
                        'time_spent_minutes'  => $timeSpent,
                        'pass_status'         => $passStatus,
                        'grading_status'      => $gradingStatus,
                    ]
                );
            }

            // Notifikasi ke seluruh siswa kelas
            $clsName = $exam->classroom?->name ?? 'Kelas Anda';
            $exam->notifyClassroomStudents(
                'Sesi Ujian Selesai',
                "Sesi ujian {$exam->title} ({$exam->subject?->name}) untuk {$clsName} telah resmi ditutup oleh guru.",
                'info'
            );

            // Notifikasi ke seluruh Admin
            foreach (\App\Models\User::admins()->get() as $admin) {
                Notification::send($admin->id, 'Sesi Ujian Selesai', "Guru {$exam->teacher?->name} telah menyelesaikan ujian {$exam->title} (" . ($classroom?->name ?? 'Kelas') . ").", 'exam', $exam);
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

    // Ekspor Rekap Nilai Ujian (PDF & Excel)
    public function exportResults(Request $request, Exam $exam)
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

        $exam->load(['subject', 'classroom', 'teacher']);
        $format = strtolower($request->query('format', 'pdf'));

        $safeTitle = \Illuminate\Support\Str::slug($exam->title, '_');
        $timestamp = date('Ymd_His');

        if ($format === 'excel' || $format === 'xlsx') {
            return $this->exportResultsExcel($exam, $results, $stats, $safeTitle, $timestamp);
        }

        // Default: Export PDF (Landscape A4 untuk tabel hasil ujian multi-kolom yang rapi & tidak terpotong)
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('teacher.exam.results_pdf', compact('exam', 'results', 'stats'))
            ->setPaper('a4', 'landscape');

        $filename = "rekap_nilai_{$safeTitle}_{$timestamp}.pdf";
        return $pdf->download($filename);
    }

    // Helper ekspor Excel format XML Spreadsheet (.xls/.xlsx yang kompatibel dan rapi dengan styling)
    protected function exportResultsExcel($exam, $results, $stats, $safeTitle, $timestamp)
    {
        $filename = "rekap_nilai_{$safeTitle}_{$timestamp}.xls";

        $examTitle = htmlspecialchars($exam->title, ENT_QUOTES, 'UTF-8');
        $subjectName = htmlspecialchars($exam->subject->name ?? '-', ENT_QUOTES, 'UTF-8');
        $className = htmlspecialchars($exam->classroom->name ?? '-', ENT_QUOTES, 'UTF-8');
        $examDate = $exam->exam_date ? $exam->exam_date->format('d M Y') : '-';
        $passingGrade = $exam->passing_grade;
        $teacherName = htmlspecialchars($exam->teacher->name ?? '-', ENT_QUOTES, 'UTF-8');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
        $xml .= ' xmlns:o="urn:schemas-microsoft-com:office:office"' . "\n";
        $xml .= ' xmlns:x="urn:schemas-microsoft-com:office:excel"' . "\n";
        $xml .= ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
        $xml .= ' xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";

        // Styles
        $xml .= '<Styles>' . "\n";
        $xml .= ' <Style ss:ID="Default" ss:Name="Normal"><Alignment ss:Vertical="Center"/><Font ss:FontName="Calibri" ss:Size="11" ss:Color="#000000"/></Style>' . "\n";
        $xml .= ' <Style ss:ID="HeaderTitle"><Font ss:FontName="Calibri" ss:Size="14" ss:Bold="1" ss:Color="#1E1B4B"/><Alignment ss:Vertical="Center"/></Style>' . "\n";
        $xml .= ' <Style ss:ID="MetaLabel"><Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1" ss:Color="#475569"/><Alignment ss:Vertical="Center"/></Style>' . "\n";
        $xml .= ' <Style ss:ID="MetaVal"><Font ss:FontName="Calibri" ss:Size="10" ss:Color="#0F172A"/><Alignment ss:Vertical="Center"/></Style>' . "\n";
        
        $xml .= ' <Style ss:ID="Th"><Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#94A3B8"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#94A3B8"/></Borders><Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1" ss:Color="#1E293B"/><Interior ss:Color="#F1F5F9" ss:Pattern="Solid"/></Style>' . "\n";
        $xml .= ' <Style ss:ID="ThLeft"><Alignment ss:Horizontal="Left" ss:Vertical="Center" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#94A3B8"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#94A3B8"/></Borders><Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1" ss:Color="#1E293B"/><Interior ss:Color="#F1F5F9" ss:Pattern="Solid"/></Style>' . "\n";

        $xml .= ' <Style ss:ID="TdCenter"><Alignment ss:Horizontal="Center" ss:Vertical="Center"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/></Borders><Font ss:FontName="Calibri" ss:Size="10" ss:Color="#334155"/></Style>' . "\n";
        $xml .= ' <Style ss:ID="TdLeft"><Alignment ss:Horizontal="Left" ss:Vertical="Center"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/></Borders><Font ss:FontName="Calibri" ss:Size="10" ss:Color="#0F172A"/></Style>' . "\n";
        $xml .= ' <Style ss:ID="TdScorePass"><Alignment ss:Horizontal="Center" ss:Vertical="Center"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/></Borders><Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1" ss:Color="#059669"/></Style>' . "\n";
        $xml .= ' <Style ss:ID="TdScoreFail"><Alignment ss:Horizontal="Center" ss:Vertical="Center"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/></Borders><Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1" ss:Color="#DC2626"/></Style>' . "\n";
        $xml .= ' <Style ss:ID="TdStatusPass"><Alignment ss:Horizontal="Center" ss:Vertical="Center"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/></Borders><Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1" ss:Color="#065F46"/><Interior ss:Color="#D1FAE5" ss:Pattern="Solid"/></Style>' . "\n";
        $xml .= ' <Style ss:ID="TdStatusFail"><Alignment ss:Horizontal="Center" ss:Vertical="Center"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/></Borders><Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1" ss:Color="#991B1B"/><Interior ss:Color="#FEE2E2" ss:Pattern="Solid"/></Style>' . "\n";
        $xml .= ' <Style ss:ID="TdStatusPending"><Alignment ss:Horizontal="Center" ss:Vertical="Center"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E2E8F0"/></Borders><Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1" ss:Color="#92400E"/><Interior ss:Color="#FEF3C7" ss:Pattern="Solid"/></Style>' . "\n";
        $xml .= '</Styles>' . "\n";

        // Worksheet
        $xml .= '<Worksheet ss:Name="Rekap Nilai">' . "\n";
        $xml .= ' <Table x:FullColumns="1" x:FullRows="1" ss:DefaultRowHeight="20">' . "\n";
        $xml .= '  <Column ss:Width="40"/>' . "\n";  // No
        $xml .= '  <Column ss:Width="190"/>' . "\n"; // Nama Siswa
        $xml .= '  <Column ss:Width="90"/>' . "\n";  // NIS
        $xml .= '  <Column ss:Width="170"/>' . "\n"; // Waktu Pengerjaan
        $xml .= '  <Column ss:Width="160"/>' . "\n"; // Benar / Salah / Kosong
        $xml .= '  <Column ss:Width="80"/>' . "\n";  // Nilai Akhir
        $xml .= '  <Column ss:Width="110"/>' . "\n"; // Status

        // Info Ujian
        $xml .= '  <Row ss:Height="24"><Cell ss:StyleID="HeaderTitle" ss:MergeAcross="6"><Data ss:Type="String">REKAP HASIL UJIAN - ' . strtoupper($examTitle) . '</Data></Cell></Row>' . "\n";
        $xml .= '  <Row><Cell ss:StyleID="MetaLabel"><Data ss:Type="String">Mata Pelajaran</Data></Cell><Cell ss:StyleID="MetaVal" ss:MergeAcross="5"><Data ss:Type="String">: ' . $subjectName . '</Data></Cell></Row>' . "\n";
        $xml .= '  <Row><Cell ss:StyleID="MetaLabel"><Data ss:Type="String">Kelas / Rombel</Data></Cell><Cell ss:StyleID="MetaVal" ss:MergeAcross="5"><Data ss:Type="String">: ' . $className . '</Data></Cell></Row>' . "\n";
        $xml .= '  <Row><Cell ss:StyleID="MetaLabel"><Data ss:Type="String">Pelaksanaan</Data></Cell><Cell ss:StyleID="MetaVal" ss:MergeAcross="5"><Data ss:Type="String">: ' . $examDate . '</Data></Cell></Row>' . "\n";
        $xml .= '  <Row><Cell ss:StyleID="MetaLabel"><Data ss:Type="String">KKM / Guru</Data></Cell><Cell ss:StyleID="MetaVal" ss:MergeAcross="5"><Data ss:Type="String">: ' . $passingGrade . ' • ' . $teacherName . '</Data></Cell></Row>' . "\n";
        $xml .= '  <Row><Cell ss:StyleID="MetaLabel"><Data ss:Type="String">Ringkasan Nilai</Data></Cell><Cell ss:StyleID="MetaVal" ss:MergeAcross="5"><Data ss:Type="String">: Peserta: ' . $stats['total'] . ' | Rata-rata: ' . $stats['avg'] . ' | Lulus: ' . $stats['pass'] . ' | Remedial: ' . $stats['fail'] . '</Data></Cell></Row>' . "\n";
        $xml .= '  <Row ss:Height="10"></Row>' . "\n";

        // Table Header: Kolom dan urutan sama persis dengan halaman web
        $xml .= '  <Row ss:Height="24">' . "\n";
        $xml .= '   <Cell ss:StyleID="Th"><Data ss:Type="String">No</Data></Cell>' . "\n";
        $xml .= '   <Cell ss:StyleID="ThLeft"><Data ss:Type="String">Nama Siswa</Data></Cell>' . "\n";
        $xml .= '   <Cell ss:StyleID="Th"><Data ss:Type="String">NIS</Data></Cell>' . "\n";
        $xml .= '   <Cell ss:StyleID="ThLeft"><Data ss:Type="String">Waktu Pengerjaan</Data></Cell>' . "\n";
        $xml .= '   <Cell ss:StyleID="Th"><Data ss:Type="String">Benar / Salah / Kosong</Data></Cell>' . "\n";
        $xml .= '   <Cell ss:StyleID="Th"><Data ss:Type="String">Nilai Akhir</Data></Cell>' . "\n";
        $xml .= '   <Cell ss:StyleID="Th"><Data ss:Type="String">Status</Data></Cell>' . "\n";
        $xml .= '  </Row>' . "\n";

        // Table Rows
        foreach ($results as $index => $result) {
            $studentName = htmlspecialchars($result->student->name ?? '-', ENT_QUOTES, 'UTF-8');
            $nis = htmlspecialchars($result->student->nis ?? '-', ENT_QUOTES, 'UTF-8');
            $waktuFormatted = $result->time_spent_minutes . ' Menit (Dikumpulkan: ' . ($result->created_at ? $result->created_at->format('H:i') . ' WIB' : '-') . ')';
            $bskFormatted = $result->correct_answers . ' B / ' . $result->wrong_answers . ' S / ' . $result->unanswered . ' K';
            $score = round($result->total_score);

            $scoreStyle = ($result->pass_status === 'pass') ? 'TdScorePass' : 'TdScoreFail';
            if ($result->is_pending_review) {
                $statusText = 'Menunggu Koreksi';
                $statusStyle = 'TdStatusPending';
            } elseif ($result->pass_status === 'pass') {
                $statusText = 'Lulus';
                $statusStyle = 'TdStatusPass';
            } else {
                $statusText = 'Tidak Lulus';
                $statusStyle = 'TdStatusFail';
            }

            $xml .= '  <Row ss:Height="22">' . "\n";
            $xml .= '   <Cell ss:StyleID="TdCenter"><Data ss:Type="Number">' . ($index + 1) . '</Data></Cell>' . "\n";
            $xml .= '   <Cell ss:StyleID="TdLeft"><Data ss:Type="String">' . $studentName . '</Data></Cell>' . "\n";
            $xml .= '   <Cell ss:StyleID="TdCenter"><Data ss:Type="String">' . $nis . '</Data></Cell>' . "\n";
            $xml .= '   <Cell ss:StyleID="TdLeft"><Data ss:Type="String">' . htmlspecialchars($waktuFormatted, ENT_QUOTES, 'UTF-8') . '</Data></Cell>' . "\n";
            $xml .= '   <Cell ss:StyleID="TdCenter"><Data ss:Type="String">' . htmlspecialchars($bskFormatted, ENT_QUOTES, 'UTF-8') . '</Data></Cell>' . "\n";
            $xml .= '   <Cell ss:StyleID="' . $scoreStyle . '"><Data ss:Type="Number">' . $score . '</Data></Cell>' . "\n";
            $xml .= '   <Cell ss:StyleID="' . $statusStyle . '"><Data ss:Type="String">' . $statusText . '</Data></Cell>' . "\n";
            $xml .= '  </Row>' . "\n";
        }

        $xml .= ' </Table>' . "\n";
        $xml .= ' <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">' . "\n";
        $xml .= '  <FreezePanes/>' . "\n";
        $xml .= '  <FrozenNoSplit/>' . "\n";
        $xml .= '  <SplitHorizontal>8</SplitHorizontal>' . "\n";
        $xml .= '  <TopRowBottomPane>8</TopRowBottomPane>' . "\n";
        $xml .= '  <ActivePane>2</ActivePane>' . "\n";
        $xml .= '  <Selected/>' . "\n";
        $xml .= '  <Panes><Pane><Number>3</Number></Pane><Pane><Number>2</Number><ActiveRow>8</ActiveRow></Pane></Panes>' . "\n";
        $xml .= '  <ProtectObjects>False</ProtectObjects>' . "\n";
        $xml .= '  <ProtectScenarios>False</ProtectScenarios>' . "\n";
        $xml .= ' </WorksheetOptions>' . "\n";
        $xml .= ' <AutoFilter x:Range="R8C1:R8C7" xmlns="urn:schemas-microsoft-com:office:excel"/>' . "\n";
        $xml .= '</Worksheet>' . "\n";
        $xml .= '</Workbook>';

        return response($xml, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0, no-cache, must-revalidate, proxy-revalidate',
            'Pragma' => 'public',
        ]);
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

    // Penilaian Soal Esai oleh Guru
    public function gradeEssayAnswer(Request $request, Exam $exam, $studentId)
    {
        $this->authorizeExam($exam);

        $request->validate([
            'answer_id' => 'required|exists:student_answers,id',
            'score'     => 'required|numeric|min:0',
        ]);

        $participant = ExamParticipant::where('exam_id', $exam->id)
            ->where('student_id', $studentId)
            ->firstOrFail();

        $answer = StudentAnswer::where('id', $request->answer_id)
            ->where('exam_participant_id', $participant->id)
            ->firstOrFail();

        $examQuestion = ExamQuestion::where('id', $answer->exam_question_id)
            ->where('exam_id', $exam->id)
            ->firstOrFail();

        $question = $examQuestion->question;

        if (!$question || $question->type !== 'essay') {
            return back()->with('error', 'Hanya soal bertipe esai yang dapat dinilai melalui fitur ini.');
        }

        // Bobot maksimal dari pertanyaan
        $maxScore = (float)$question->score;
        $inputScore = (float)$request->score;

        if ($inputScore > $maxScore) {
            return back()->with('error', "Skor yang dimasukkan ({$inputScore}) melebihi bobot maksimal soal ({$maxScore} poin).");
        }

        DB::transaction(function () use ($answer, $inputScore, $maxScore, $exam, $participant, $studentId) {
            // Perbarui skor esai
            $isCorrect = $inputScore >= $maxScore ? true : ($inputScore <= 0 ? false : null);

            $answer->update([
                'score_obtained' => $inputScore,
                'is_correct'     => $isCorrect,
            ]);

            // Hitung ulang seluruh capaian siswa
            $examQuestions = ExamQuestion::where('exam_id', $exam->id)->with('question')->get();
            $answers = StudentAnswer::where('exam_participant_id', $participant->id)->get()->keyBy('exam_question_id');

            $totalScore = 0;
            $correctCount = 0;
            $wrongCount = 0;
            $unansweredCount = 0;
            $hasPendingEssay = false;

            foreach ($examQuestions as $eq) {
                $q = $eq->question;
                $ans = $answers->get($eq->id);
                $hasAnswer = $ans && ($ans->selected_option_id !== null || !empty(trim($ans->answer_text ?? '')));

                if (!$hasAnswer) {
                    $unansweredCount++;
                }

                if ($q && in_array($q->type, ['multiple_choice', 'true_false'])) {
                    if ($ans && $ans->is_correct) {
                        $correctCount++;
                        $totalScore += (float)$ans->score_obtained;
                    } elseif ($hasAnswer) {
                        $wrongCount++;
                    }
                } elseif ($q && $q->type === 'essay') {
                    if ($ans && $ans->is_correct === true) {
                        $correctCount++;
                        $totalScore += (float)$ans->score_obtained;
                    } elseif ($ans && $ans->is_correct === false) {
                        $wrongCount++;
                        $totalScore += (float)$ans->score_obtained;
                    } else {
                        // Jika is_correct === null: Cek apakah sudah pernah dinilai (skor > 0 sebagian atau belum dinilai)
                        // Jika belum ada input nilai sama sekali, dianggap pending review
                        // Catatan: jika guru memberi nilai sebagian misal 5 dari 10, is_correct adalah null namun skor sudah diinput
                        // Kita tentukan status pending jika jawaban belum diisi nilai oleh guru
                        // Untuk memastikan, jika belum disentuh koreksi:
                        if ($ans && $ans->score_obtained !== null && $ans->answered_at !== null) {
                            // Cek apakah guru sudah mengoreksi:
                            // Jika skor sudah diinput, kita tambahkan ke totalScore
                            $totalScore += (float)$ans->score_obtained;
                        } else {
                            $hasPendingEssay = true;
                        }
                    }
                } else {
                    if ($ans && $ans->is_correct) {
                        $correctCount++;
                    } elseif ($hasAnswer) {
                        $wrongCount++;
                    }
                    if ($ans) {
                        $totalScore += (float)$ans->score_obtained;
                    }
                }
            }

            // Cek apakah masih ada esai lain pada ujian ini yang belum dinilai sama sekali
            $essayQuestions = $examQuestions->filter(fn($eq) => $eq->question && $eq->question->type === 'essay');
            $pendingEssaysCount = 0;
            foreach ($essayQuestions as $eq) {
                $ans = $answers->get($eq->id);
                // Belum dinilai jika is_correct null dan score_obtained == 0 serta belum ada input penilaian
                // Untuk konsistensi, jika is_correct === null dan score_obtained == 0 serta teks jawaban ada, itu butuh koreksi
                if (!$ans || ($ans->is_correct === null && (float)$ans->score_obtained == 0 && !empty(trim($ans->answer_text ?? '')))) {
                    $pendingEssaysCount++;
                }
            }

            $passStatus = $totalScore >= $exam->passing_grade ? 'pass' : 'fail';
            $gradingStatus = $pendingEssaysCount > 0 ? 'pending_review' : 'graded';

            ExamResult::updateOrCreate(
                ['exam_id' => $exam->id, 'student_id' => $studentId],
                [
                    'exam_participant_id' => $participant->id,
                    'total_score'         => $totalScore,
                    'correct_answers'     => $correctCount,
                    'wrong_answers'       => $wrongCount,
                    'unanswered'          => $unansweredCount,
                    'pass_status'         => $passStatus,
                    'grading_status'      => $gradingStatus,
                ]
            );

            ActivityLog::log('essay_graded', "Guru menilai jawaban esai siswa untuk ujian {$exam->title} (Skor: {$inputScore})", $exam);
        });

        if ($request->ajax() || $request->wantsJson()) {
            $updatedResult = ExamResult::where('exam_id', $exam->id)->where('student_id', $studentId)->first();
            return response()->json([
                'success'        => true,
                'message'        => 'Nilai esai berhasil disimpan.',
                'score_obtained' => $inputScore,
                'total_score'    => $updatedResult->total_score,
                'pass_status'    => $updatedResult->pass_status,
                'grading_status' => $updatedResult->grading_status,
                'grading_label'  => $updatedResult->grading_label,
            ]);
        }

        return back()->with('success', "Nilai esai sebesar {$inputScore} berhasil disimpan dan nilai akhir siswa telah diperbarui.");
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
