<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamParticipant extends Model
{
    protected $fillable = ['exam_id', 'student_id', 'status', 'started_at', 'submitted_at', 'is_late_submission'];

    protected $casts = [
        'started_at'        => 'datetime',
        'submitted_at'      => 'datetime',
        'is_late_submission'=> 'boolean',
    ];

    /**
     * Pastikan started_at disimpan dalam zona waktu aplikasi (Asia/Jakarta)
     */
    public function setStartedAtAttribute($value): void
    {
        if ($value instanceof \DateTimeInterface) {
            $this->attributes['started_at'] = \Carbon\Carbon::instance($value)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
        } else {
            $this->attributes['started_at'] = $value;
        }
    }

    /**
     * Pastikan submitted_at disimpan dalam zona waktu aplikasi (Asia/Jakarta)
     */
    public function setSubmittedAtAttribute($value): void
    {
        if ($value instanceof \DateTimeInterface) {
            $this->attributes['submitted_at'] = \Carbon\Carbon::instance($value)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
        } else {
            $this->attributes['submitted_at'] = $value;
        }
    }

    public function exam()       { return $this->belongsTo(Exam::class); }
    public function student()    { return $this->belongsTo(User::class, 'student_id'); }
    public function answers()    { return $this->hasMany(StudentAnswer::class); }
    public function result()     { return $this->hasOne(ExamResult::class); }
    public function violations() { return $this->hasMany(ExamViolation::class); }

    public function getTimeSpentMinutesAttribute(): int
    {
        return $this->calculateTimeSpentMinutes();
    }

    /**
     * Hitung durasi pengerjaan yang valid sesuai aturan ujian (memperhatikan submitted_at/timeout, duration_minutes, dan end_time)
     */
    public function calculateTimeSpentMinutes(?\Carbon\Carbon $effectiveSubmittedAt = null): int
    {
        if (!$this->started_at) {
            return 0;
        }

        $endTime = $effectiveSubmittedAt ?? $this->submitted_at ?? now();

        // Pastikan timezone selaras saat menghitung selisih waktu
        $tz = $this->exam?->timezone ?? config('app.timezone');
        $startInTz = $this->started_at->copy()->setTimezone($tz);
        $endInTz = $endTime->copy()->setTimezone($tz);

        $diffMinutes = max(0, (int) $startInTz->diffInMinutes($endInTz));

        if ($this->relationLoaded('exam') || $this->exam) {
            $exam = $this->exam;
            $maxAllowed = (int) ($exam->duration_minutes ?? $diffMinutes);

            if ($exam->end_carbon) {
                $sessionLimitMinutes = max(0, (int) $startInTz->diffInMinutes($exam->end_carbon, false));
                $maxAllowed = min($maxAllowed, $sessionLimitMinutes);
            }

            return min($diffMinutes, max(0, $maxAllowed));
        }

        return $diffMinutes;
    }

    public function getRemainingSecondsAttribute(): int
    {
        if (!$this->started_at) return 0;

        $tz = $this->exam?->timezone ?? config('app.timezone');
        $startInTz = $this->started_at->copy()->setTimezone($tz);

        // 1. Batas waktu berdasarkan durasi pengerjaan siswa
        $durationEndTime = $startInTz->copy()->addMinutes($this->exam->duration_minutes);
        $remainingByDuration = (int) now()->setTimezone($tz)->diffInSeconds($durationEndTime, false);

        // 2. Batas waktu berdasarkan jam sesi berakhirnya ujian (end_carbon dalam zona waktu ujian)
        $remainingBySession = null;
        if ($this->exam && $this->exam->end_carbon) {
            $endCarbon = $this->exam->end_carbon;
            $nowInTimezone = now()->setTimezone($tz);
            $remainingBySession = (int) $nowInTimezone->diffInSeconds($endCarbon, false);
        }

        if ($remainingBySession !== null) {
            $effectiveRemaining = min($remainingByDuration, $remainingBySession);
        } else {
            $effectiveRemaining = $remainingByDuration;
        }

        return max(0, $effectiveRemaining);
    }
}
