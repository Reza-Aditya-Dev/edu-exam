<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $fillable = [
        'exam_id', 'student_id', 'exam_participant_id',
        'total_score', 'raw_score', 'penalty_score', 'violation_count',
        'is_disqualified', 'disqualification_reason',
        'correct_answers', 'wrong_answers',
        'unanswered', 'time_spent_minutes', 'pass_status', 'grading_status',
    ];

    protected $casts = [
        'total_score'     => 'float',
        'raw_score'       => 'float',
        'penalty_score'   => 'float',
        'violation_count' => 'integer',
        'is_disqualified' => 'boolean',
        'correct_answers' => 'integer',
        'wrong_answers'   => 'integer',
        'unanswered'      => 'integer',
    ];

    public function exam()        { return $this->belongsTo(Exam::class); }
    public function student()     { return $this->belongsTo(User::class, 'student_id'); }
    public function participant() { return $this->belongsTo(ExamParticipant::class, 'exam_participant_id'); }
    public function violations()  { return $this->hasMany(ExamViolation::class, 'exam_participant_id', 'exam_participant_id'); }

    public function getIsPassAttribute(): bool   { return $this->pass_status === 'pass'; }
    public function getPassLabelAttribute(): string { return $this->pass_status === 'pass' ? 'Lulus' : 'Tidak Lulus'; }
    public function getIsPendingReviewAttribute(): bool { return $this->grading_status === 'pending_review'; }
    public function getGradingLabelAttribute(): string { return $this->grading_status === 'pending_review' ? 'Menunggu Koreksi' : 'Selesai Dikoreksi'; }

    /**
     * Waktu pengumpulan sebenarnya (dari participant submitted_at atau fallback ke created_at)
     */
    public function getEffectiveSubmittedAtAttribute(): ?\Carbon\Carbon
    {
        return $this->participant?->submitted_at ?? $this->created_at;
    }

    /**
     * Waktu pengumpulan terformat sesuai timezone ujian (WIB, WITA, WIT)
     */
    public function getFormattedSubmittedTimeAttribute(): string
    {
        $submittedAt = $this->effective_submitted_at;
        if (!$submittedAt) {
            return '-';
        }

        $tz = $this->exam?->timezone ?? Exam::DEFAULT_TIMEZONE;
        $tzCode = $this->exam?->timezone_code ?? 'WIB';

        return $submittedAt->copy()->setTimezone($tz)->format('H:i') . ' ' . $tzCode;
    }
}
