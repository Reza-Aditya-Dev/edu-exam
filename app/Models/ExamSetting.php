<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSetting extends Model
{
    protected $fillable = [
        'exam_id', 'shuffle_questions', 'shuffle_options', 'auto_save',
        'auto_submit', 'show_result_immediately', 'allow_review',
        'show_correct_answers', 'max_attempts',
        'enable_anti_cheat', 'max_violations', 'violation_penalty_points',
    ];

    protected $casts = [
        'shuffle_questions'        => 'boolean',
        'shuffle_options'          => 'boolean',
        'auto_save'                => 'boolean',
        'auto_submit'              => 'boolean',
        'show_result_immediately'  => 'boolean',
        'allow_review'             => 'boolean',
        'show_correct_answers'     => 'boolean',
        'enable_anti_cheat'        => 'boolean',
        'max_violations'           => 'integer',
        'violation_penalty_points' => 'float',
    ];

    public function exam() { return $this->belongsTo(Exam::class); }
}
