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

    public function exam()    { return $this->belongsTo(Exam::class); }
    public function student() { return $this->belongsTo(User::class, 'student_id'); }
    public function answers() { return $this->hasMany(StudentAnswer::class); }
    public function result()  { return $this->hasOne(ExamResult::class); }

    public function getTimeSpentMinutesAttribute(): int
    {
        if (!$this->started_at || !$this->submitted_at) return 0;
        return (int) $this->started_at->diffInMinutes($this->submitted_at);
    }

    public function getRemainingSecondsAttribute(): int
    {
        if (!$this->started_at) return 0;

        // 1. Batas waktu berdasarkan durasi pengerjaan siswa
        $durationEndTime = $this->started_at->copy()->addMinutes($this->exam->duration_minutes);
        $remainingByDuration = (int) now()->diffInSeconds($durationEndTime, false);

        // 2. Batas waktu berdasarkan jam sesi berakhirnya ujian (end_carbon dalam zona waktu ujian)
        $remainingBySession = null;
        if ($this->exam && $this->exam->end_carbon) {
            $endCarbon = $this->exam->end_carbon;
            $nowInTimezone = now()->setTimezone($this->exam->timezone);
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
