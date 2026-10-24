<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamParticipant;
use App\Models\ExamQuestion;
use App\Models\ExamResult;
use App\Models\StudentAnswer;
use App\Models\ExamViolation;
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

        // Otomatis sinkronisasi status ujian (buka jika jadwal mulai telah tiba, tutup jika habis waktu)
        $exam->activateIfDue();
        $exam->closeIfExpired();

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

        // Coba aktifkan jika waktu ujian telah tiba
        $exam->activateIfDue();

        if ($exam->status === 'scheduled' && !$exam->is_started) {
            $jamMulai = substr($exam->start_time, 0, 5) . ' ' . $exam->timezone_code;
            return redirect()->route('student.exam.show', $exam)->with('warning', "Ujian belum dimulai. Silakan kembali sesuai jadwal ({$jamMulai}).");
        }

        if ($exam->is_expired || $exam->status === 'completed') {
            $exam->closeIfExpired();
            return redirect()->route('student.exam.show', $exam)->with('error', 'Waktu pelaksanaan ujian telah berakhir. Anda tidak dapat memulai ujian ini.');
        }

        if ($exam->status !== 'active') {
            return redirect()->route('student.dashboard')->with('error', 'Ujian belum dibuka untuk dikerjakan.');
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

        // Cek jika guru sudah menutup ujian atau jadwal ujian sudah habis
        if ($exam->status !== 'active' || $exam->is_expired) {
            $exam->closeIfExpired();
            return $this->autoSubmit($exam, $participant, 'Waktu sesi ujian telah berakhir. Jawaban dikumpulkan otomatis.');
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
        foreach ($examQuestions as $eq) {
            if ($eq && $eq->question && $eq->question->relationLoaded('options')) {
                $eq->question->setRelation(
                    'options',
                    $eq->question->options->sortBy(function ($opt) {
                        return [$opt->sort_order ?? 0, $opt->label ?? '', $opt->id];
                    })->values()
                );
            }
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

        // Cek jika guru telah menutup ujian atau sesi ujian sudah berakhir
        if ($exam->status !== 'active' || $exam->is_expired) {
            $exam->closeIfExpired();
            $this->processSubmit($exam, $participant, true);
            return response()->json([
                'success'     => false,
                'status'      => 'closed',
                'exam_closed' => true,
                'redirect'    => route('student.exam.result', $exam),
                'message'     => 'Waktu sesi ujian telah berakhir.',
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

        $existingAnswer = StudentAnswer::where('exam_participant_id', $participant->id)
            ->where('exam_question_id', $request->exam_question_id)
            ->first();

        // Jangan timpa skor esai yang sudah dinilai guru jika siswa hanya menyimpan ulang
        if ($existingAnswer && $question->type === 'essay' && $existingAnswer->is_correct !== null) {
            $isCorrect = $existingAnswer->is_correct;
            $scoreObtained = $existingAnswer->score_obtained;
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

    // Catat Pelanggaran Anti-Cheat (AJAX)
    public function logViolation(Request $request, Exam $exam)
    {
        $student = Auth::user();
        $this->authorizeExam($exam, $student);

        $participant = ExamParticipant::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$participant) {
            return response()->json(['success' => false, 'message' => 'Sesi ujian tidak aktif'], 400);
        }

        $request->validate([
            'violation_type'   => 'required|string|max:50',
            'duration_seconds' => 'nullable|integer|min:0',
            'details'          => 'nullable|string|max:500',
        ]);

        $settings = $exam->settings;
        $maxViolations = $settings ? ($settings->max_violations ?? 3) : 3;

        // Hitung nomor pelanggaran berikutnya
        $currentCount = ExamViolation::where('exam_participant_id', $participant->id)->count();
        $violationNumber = $currentCount + 1;

        $violation = ExamViolation::create([
            'exam_id'             => $exam->id,
            'student_id'          => $student->id,
            'exam_participant_id' => $participant->id,
            'violation_type'      => $request->input('violation_type', 'tab_switch'),
            'duration_seconds'    => (int)$request->input('duration_seconds', 0),
            'violation_number'    => $violationNumber,
            'details'             => $request->input('details'),
            'occurred_at'         => now(),
        ]);

        ActivityLog::log(
            'exam_violation',
            "Siswa {$student->name} terdeteksi melanggar ({$violation->type_label}, ke-{$violationNumber}) pada ujian: {$exam->title}",
            $exam
        );

        $isExceeded = $violationNumber >= $maxViolations;

        return response()->json([
            'success'          => true,
            'violation_number' => $violationNumber,
            'max_violations'   => $maxViolations,
            'is_exceeded'      => $isExceeded,
            'type_label'       => $violation->type_label,
            'message'          => $isExceeded
                ? "Batas maksimal pelanggaran ({$maxViolations}x) telah tercapai. Ujian Anda otomatis dikumpulkan."
                : "Peringatan ke-{$violationNumber} dari batas {$maxViolations}x: Terdeteksi meninggalkan halaman ujian!",
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

        // Cek jika guru menutup ujian atau sesi ujian sudah berakhir
        if ($exam->status !== 'active' || $exam->is_expired) {
            $exam->closeIfExpired();
            $this->processSubmit($exam, $participant, true);
            return response()->json([
                'active'   => false,
                'redirect' => route('student.exam.result', $exam),
                'message'  => 'Waktu sesi ujian telah berakhir.',
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
            $examQuestions = ExamQuestion::where('exam_id', $exam->id)
                ->with('question')
                ->get();
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
                        // is_correct === null: Belum dikoreksi oleh guru
                        $hasPendingEssay = true;
                        if ($ans && $ans->score_obtained > 0) {
                            $totalScore += (float)$ans->score_obtained;
                        }
                    }
                } else {
                    // Tipe lain (jika ada seperti short_answer)
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

            // Kalkulasi skor murni (raw score) dan penalti pelanggaran
            $rawScore = $totalScore;
            $violationCount = ExamViolation::where('exam_participant_id', $participant->id)->count();

            // Hitung penalti otomatis berdasarkan pengaturan ujian
            $settings = $exam->settings;
            $penaltyPerViolation = (float)($settings?->violation_penalty_points ?? 0);
            $penaltyScore = round($violationCount * $penaltyPerViolation, 2);

            // Cek apakah sebelumnya sudah ada status diskualifikasi manual dari guru
            $existingResult = ExamResult::where('exam_id', $exam->id)
                ->where('student_id', $participant->student_id)
                ->first();
            $isDisqualified = $existingResult?->is_disqualified ?? false;
            $disqualificationReason = $existingResult?->disqualification_reason;

            // Jika didiskualifikasi, total skor adalah 0
            if ($isDisqualified) {
                $finalScore = 0;
            } else {
                $finalScore = max(0, round($rawScore - $penaltyScore, 2));
            }

            $passStatus = $finalScore >= $exam->passing_grade ? 'pass' : 'fail';
            $gradingStatus = $hasPendingEssay ? 'pending_review' : 'graded';

            // Tetapkan waktu pengumpulan satu kali secara konsisten
            // Jika status timeout atau ujian ditutup karena lewat batas jadwal, batasi ke batas waktu yang berlaku
            $nowMoment = now();
            if ($isTimeout && $exam->end_carbon && $nowMoment->greaterThan($exam->end_carbon)) {
                $submittedAt = $exam->end_carbon;
            } else {
                $submittedAt = $nowMoment;
            }

            $participant->update([
                'status'             => $isTimeout ? 'timed_out' : 'submitted',
                'submitted_at'       => $submittedAt,
                'is_late_submission' => $isTimeout,
            ]);

            // Hitung durasi pengerjaan (dalam menit) setelah submitted_at tersimpan
            $timeSpent = $participant->calculateTimeSpentMinutes($submittedAt);

            ExamResult::updateOrCreate(
                ['exam_id' => $exam->id, 'student_id' => $participant->student_id],
                [
                    'exam_participant_id'    => $participant->id,
                    'total_score'            => $finalScore,
                    'raw_score'              => $rawScore,
                    'penalty_score'          => $penaltyScore,
                    'violation_count'        => $violationCount,
                    'is_disqualified'        => $isDisqualified,
                    'disqualification_reason'=> $disqualificationReason,
                    'correct_answers'        => $correctCount,
                    'wrong_answers'          => $wrongCount,
                    'unanswered'             => $unansweredCount,
                    'time_spent_minutes'     => $timeSpent,
                    'pass_status'            => $passStatus,
                    'grading_status'         => $gradingStatus,
                ]
            );

            ActivityLog::log('exam_submitted', "Siswa mengumpulkan ujian: {$exam->title}", $exam);

            // Notifikasi ke Siswa yang bersangkutan
            $statusNote = $hasPendingEssay ? 'Menunggu koreksi esai oleh guru' : ($passStatus === 'pass' ? 'Tuntas' : 'Remedial');
            Notification::send(
                $participant->student_id,
                'Ujian Berhasil Dikumpulkan',
                "Ujian {$exam->title} berhasil dikumpulkan dengan nilai sementara {$totalScore} ({$statusNote}).",
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
                    "{$studentName} telah mengumpulkan ujian {$exam->title}." . ($hasPendingEssay ? ' Terdapat jawaban esai yang perlu dikoreksi.' : " (Nilai: {$totalScore})."),
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

        // 1. Validasi hak akses siswa ke kelas ujian
        $this->authorizeExam($exam, $student);

        // 2. Ambil hasil ujian siswa yang sedang login (siswa hanya boleh melihat miliknya sendiri)
        $result = ExamResult::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->with(['exam.subject', 'participant'])
            ->firstOrFail();

        $exam->load(['subject', 'classroom', 'teacher', 'settings']);

        // 3. Pengecekan izin dari guru
        $settings = $exam->settings;
        $allowReview = $settings ? (bool)$settings->allow_review : false;
        $showCorrectAnswers = $settings ? (bool)$settings->show_correct_answers : false;

        $answers = collect();
        if ($allowReview) {
            $participant = ExamParticipant::where('exam_id', $exam->id)
                ->where('student_id', $student->id)
                ->first();

            if ($participant) {
                $rawAnswers = StudentAnswer::where('exam_participant_id', $participant->id)
                    ->with([
                        'examQuestion.question.options' => function ($q) {
                            $q->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
                        },
                        'selectedOption'
                    ])
                    ->get()
                    ->sortBy(function ($ans) {
                        return $ans->examQuestion->question_order ?? 0;
                    })
                    ->values();

                // Keamanan data: Jika guru TIDAK mengizinkan melihat kunci jawaban,
                // bersihkan atribut is_correct dan explanation dari opsi/soal agar tidak bocor ke frontend/DOM
                if (!$showCorrectAnswers) {
                    $rawAnswers->each(function ($ans) {
                        $q = $ans->examQuestion?->question;
                        if ($q) {
                            $q->explanation = null;
                            if ($q->options) {
                                foreach ($q->options as $opt) {
                                    $opt->is_correct = false;
                                }
                            }
                        }
                    });
                }

                $answers = $rawAnswers;
            }
        }

        return view('student.exam.result', compact('exam', 'result', 'allowReview', 'showCorrectAnswers', 'answers'));
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
