<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    protected $fillable = ['exam_id', 'question_id', 'question_order'];

    public function exam()     { return $this->belongsTo(Exam::class); }
    public function question() { return $this->belongsTo(Question::class); }
    public function studentAnswers() { return $this->hasMany(StudentAnswer::class); }
}
