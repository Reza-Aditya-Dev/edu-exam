<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $fillable = [
        'exam_id', 'student_id', 'exam_participant_id',
        'total_score', 'correct_answers', 'wrong_answers',
        'unanswered', 'time_spent_minutes', 'pass_status',
    ];

    protected $casts = [
        'total_score'     => 'float',
        'correct_answers' => 'integer',
        'wrong_answers'   => 'integer',
        'unanswered'      => 'integer',
    ];

    public function exam()        { return $this->belongsTo(Exam::class); }
    public function student()     { return $this->belongsTo(User::class, 'student_id'); }
    public function participant() { return $this->belongsTo(ExamParticipant::class, 'exam_participant_id'); }

    public function getIsPassAttribute(): bool   { return $this->pass_status === 'pass'; }
    public function getPassLabelAttribute(): string { return $this->pass_status === 'pass' ? 'Lulus' : 'Tidak Lulus'; }
}
