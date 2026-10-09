<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $fillable = [
        'exam_participant_id', 'exam_question_id', 'selected_option_id',
        'answer_text', 'is_correct', 'score_obtained', 'is_marked', 'answered_at',
    ];

    protected $casts = [
        'is_correct'    => 'boolean',
        'is_marked'     => 'boolean',
        'score_obtained'=> 'float',
        'answered_at'   => 'datetime',
    ];

    protected $hidden = [
        'is_correct',
        'score_obtained',
    ];

    public function participant()    { return $this->belongsTo(ExamParticipant::class, 'exam_participant_id'); }
    public function examQuestion()   { return $this->belongsTo(ExamQuestion::class); }
    public function selectedOption() { return $this->belongsTo(QuestionOption::class, 'selected_option_id'); }
}
