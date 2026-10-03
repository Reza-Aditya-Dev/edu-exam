<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'subject_id', 'created_by', 'type', 'question_text', 'question_image',
        'topic', 'difficulty', 'score', 'explanation', 'is_active',
    ];

    protected $casts = ['score' => 'float', 'is_active' => 'boolean'];

    public function subject()  { return $this->belongsTo(Subject::class); }
    public function creator()  { return $this->belongsTo(User::class, 'created_by'); }
    public function options()  { return $this->hasMany(QuestionOption::class)->orderBy('sort_order'); }
    public function correctOption() { return $this->hasOne(QuestionOption::class)->where('is_correct', true); }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_questions')->withPivot('question_order')->withTimestamps();
    }

    public function scopeActive($query) { return $query->where('is_active', true); }

    public function getDifficultyLabelAttribute(): string
    {
        return match($this->difficulty) {
            'easy'   => 'Mudah',
            'medium' => 'Sedang',
            'hard'   => 'Sulit',
            default  => '-',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'multiple_choice' => 'Pilihan Ganda',
            'true_false'      => 'Benar/Salah',
            'short_answer'    => 'Isian Singkat',
            'essay'           => 'Esai',
            default           => '-',
        };
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->question_image) {
            return null;
        }

        if (filter_var($this->question_image, FILTER_VALIDATE_URL)) {
            return $this->question_image;
        }

        $cleanPath = ltrim(str_replace('storage/', '', $this->question_image), '/');
        return asset('storage/' . $cleanPath);
    }
}
