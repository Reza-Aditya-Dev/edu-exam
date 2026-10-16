<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Exam extends Model
{
    protected $fillable = [
        'subject_id', 'classroom_id', 'teacher_id', 'academic_year_id',
        'title', 'session_name', 'token', 'exam_type', 'description', 'instructions',
        'exam_date', 'start_time', 'end_time', 'timezone', 'duration_minutes',
        'total_questions', 'passing_grade', 'status',
    ];

    protected $casts = [
        'exam_date'      => 'date',
        'passing_grade'  => 'float',
        'total_questions'=> 'integer',
        'duration_minutes'=> 'integer',
    ];

    /**
     * Zona waktu default ujian jika tidak diset (WIB).
     */
    public const DEFAULT_TIMEZONE = 'Asia/Jakarta';

    /**
     * Daftar zona waktu resmi Indonesia yang didukung.
     */
    public const SUPPORTED_TIMEZONES = [
        'Asia/Jakarta'  => ['code' => 'WIB',  'label' => 'WIB — Waktu Indonesia Barat (Jakarta)'],
        'Asia/Makassar' => ['code' => 'WITA', 'label' => 'WITA — Waktu Indonesia Tengah (Makassar)'],
        'Asia/Jayapura' => ['code' => 'WIT',  'label' => 'WIT — Waktu Indonesia Timur (Jayapura)'],
    ];

    public function getTimezoneAttribute(?string $value): string
    {
        return $value ?: self::DEFAULT_TIMEZONE;
    }

    /**
     * Singkatan zona waktu (WIB, WITA, WIT).
     */
    public function getTimezoneCodeAttribute(): string
    {
        $tz = $this->timezone;
        return self::SUPPORTED_TIMEZONES[$tz]['code'] ?? 'WIB';
    }

    /**
     * Label lengkap zona waktu.
     */
    public function getTimezoneLabelAttribute(): string
    {
        $tz = $this->timezone;
        return self::SUPPORTED_TIMEZONES[$tz]['label'] ?? ($tz . ' (' . $this->timezone_code . ')');
    }

    /**
     * Mendapatkan objek Carbon awal sesi ujian dalam zona waktu ujian.
     */
    public function getStartCarbonAttribute(): ?Carbon
    {
        if (!$this->exam_date || !$this->start_time) {
            return null;
        }

        $dateStr = $this->exam_date->format('Y-m-d');
        $startHms = strlen($this->start_time) === 5 ? $this->start_time . ':00' : $this->start_time;
        return Carbon::parse("{$dateStr} {$startHms}", $this->timezone);
    }

    /**
     * Mendapatkan objek Carbon akhir sesi ujian dalam zona waktu ujian.
     */
    public function getEndCarbonAttribute(): ?Carbon
    {
        if (!$this->exam_date || !$this->end_time) {
            return null;
        }

        $dateStr = $this->exam_date->format('Y-m-d');
        $endHms = strlen($this->end_time) === 5 ? $this->end_time . ':00' : $this->end_time;
        return Carbon::parse("{$dateStr} {$endHms}", $this->timezone);
    }

    public function subject()      { return $this->belongsTo(Subject::class); }
    public function classroom()    { return $this->belongsTo(Classroom::class); }
    public function teacher()      { return $this->belongsTo(User::class, 'teacher_id'); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
    public function settings()     { return $this->hasOne(ExamSetting::class); }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_questions')
                    ->withPivot('question_order', 'id')
                    ->orderBy('exam_questions.question_order');
    }

    public function examQuestions() { return $this->hasMany(ExamQuestion::class)->orderBy('question_order'); }
    public function participants()  { return $this->hasMany(ExamParticipant::class); }
    public function results()       { return $this->hasMany(ExamResult::class); }

    // Scopes
    public function scopeForTeacher($query, $teacherId) { return $query->where('teacher_id', $teacherId); }
    public function scopeStatus($query, $status)         { return $query->where('status', $status); }
    public function scopeActive($query)                  { return $query->where('status', 'active'); }
    public function scopeScheduled($query)               { return $query->where('status', 'scheduled'); }

    public function getIsActiveAttribute(): bool { return $this->status === 'active'; }
    public function getIsScheduledAttribute(): bool { return $this->status === 'scheduled'; }
    public function getIsCompletedAttribute(): bool { return $this->status === 'completed'; }

    public function getFormattedDateAttribute(): string
    {
        return $this->exam_date ? $this->exam_date->translatedFormat('d F Y') : '-';
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft'     => 'Draft',
            'scheduled' => 'Terjadwal',
            'active'    => 'Berlangsung',
            'completed' => 'Selesai',
            'archived'  => 'Diarsipkan',
            default     => '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft'     => 'gray',
            'scheduled' => 'blue',
            'active'    => 'green',
            'completed' => 'indigo',
            'archived'  => 'orange',
            default     => 'gray',
        };
    }

    public function getParticipantCountAttribute(): int
    {
        return $this->participants()->count();
    }

    public function getSubmittedCountAttribute(): int
    {
        return $this->participants()->where('status', 'submitted')->count();
    }

    public function getTokenAttribute(): string
    {
        if (!empty($this->attributes['token'] ?? null)) {
            return $this->attributes['token'];
        }
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $this->subject->code ?? $this->subject->name ?? $this->title ?? 'CBT'), 0, 3));
        return ($prefix ?: 'CBT') . '-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Cek apakah waktu mulai ujian sudah tiba (berdasarkan zona waktu ujian).
     */
    public function getIsStartedAttribute(): bool
    {
        $start = $this->start_carbon;
        if (!$start) {
            return true;
        }

        // Konversikan waktu server sekarang ke zona waktu ujian
        $nowInTimezone = now()->setTimezone($this->timezone);
        return $nowInTimezone->greaterThanOrEqualTo($start);
    }

    /**
     * Cek apakah sesi ujian saat ini terbuka (masuk dalam rentang start_time s/d end_time dalam zona waktu ujian).
     */
    public function getIsOpenAttribute(): bool
    {
        return $this->is_started && !$this->is_expired;
    }

    /**
     * Cek apakah jadwal sesi ujian ini sudah melewati batas waktu selesai (berdasarkan zona waktu ujian).
     */
    public function getIsExpiredAttribute(): bool
    {
        $end = $this->end_carbon;
        if (!$end) {
            return false;
        }

        $nowInTimezone = now()->setTimezone($this->timezone);
        return $nowInTimezone->greaterThanOrEqualTo($end);
    }

    /**
     * Aktifkan ujian secara otomatis jika statusnya 'scheduled' dan waktunya sudah tiba.
     */
    public function activateIfDue(): bool
    {
        if ($this->status !== 'scheduled') {
            return false;
        }

        if ($this->is_open) {
            $this->update(['status' => 'active']);
            ActivityLog::log('exam_auto_activated', "Ujian diaktifkan otomatis sesuai jadwal ({$this->timezone_code}): {$this->title}", $this);
            return true;
        }

        return false;
    }

    /**
     * Tutup ujian secara otomatis jika status masih 'active' namun jadwal sudah melewati end_time.
     */
    public function closeIfExpired(): bool
    {
        if ($this->status !== 'active' || !$this->is_expired) {
            return false;
        }

        \Illuminate\Support\Facades\DB::transaction(function () {
            $this->update(['status' => 'completed']);

            // Selesaikan otomatis seluruh siswa yang masih berstatus in_progress
            $participants = $this->participants()->where('status', 'in_progress')->get();
            $examQuestions = $this->examQuestions()->with('question')->get();

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

                $passStatus = $totalScore >= $this->passing_grade ? 'pass' : 'fail';
                $gradingStatus = $hasPendingEssay ? 'pending_review' : 'graded';

                $submittedAt = now();
                $timeSpent = 0;
                if ($participant->started_at) {
                    $timeSpent = (int) $participant->started_at->diffInMinutes($submittedAt);
                }

                $participant->update([
                    'status'             => 'timed_out',
                    'submitted_at'       => $submittedAt,
                    'is_late_submission' => true,
                ]);

                ExamResult::updateOrCreate(
                    ['exam_id' => $this->id, 'student_id' => $participant->student_id],
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
        });

        return true;
    }

    /**
     * Periksa dan tutup massal semua ujian yang masih berstatus 'active' tetapi jadwalnya sudah lewat.
     */
    public static function closeAllExpiredActiveExams(): int
    {
        // Ambil semua ujian active
        $activeExams = static::where('status', 'active')->get();

        $closedCount = 0;
        foreach ($activeExams as $exam) {
            if ($exam->closeIfExpired()) {
                $closedCount++;
            }
        }

        return $closedCount;
    }

    /**
     * Otomatis aktifkan seluruh ujian yang statusnya 'scheduled' jika jadwal mulai ujian sudah tiba dan belum kedaluwarsa.
     */
    public static function autoActivateScheduledExams(): int
    {
        // Ambil ujian scheduled
        $scheduledExams = static::where('status', 'scheduled')->get();

        $activatedCount = 0;
        foreach ($scheduledExams as $exam) {
            if ($exam->activateIfDue()) {
                $activatedCount++;
            }
        }

        return $activatedCount;
    }

    /**
     * Sinkronisasi otomatis: buka ujian yang waktunya sudah tiba, dan tutup ujian yang waktunya sudah berakhir.
     */
    public static function syncScheduledAndExpiredExams(): void
    {
        static::autoActivateScheduledExams();
        static::closeAllExpiredActiveExams();
    }


    /**
     * Kirim notifikasi otomatis khusus ke seluruh siswa di rombel/kelas ujian ini.
     */
    public function notifyClassroomStudents(string $title, string $message, string $type = 'exam'): void
    {
        $classroom = $this->classroom()->with('students')->first();
        if ($classroom && $classroom->students) {
            foreach ($classroom->students as $student) {
                Notification::send($student->id, $title, $message, $type, $this);
            }
        }
    }
}
