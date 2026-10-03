<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    protected $fillable = ['question_id', 'label', 'option_text', 'option_image', 'is_correct', 'sort_order'];
    protected $casts = ['is_correct' => 'boolean'];

    public function question() { return $this->belongsTo(Question::class); }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->option_image) {
            return null;
        }

        if (filter_var($this->option_image, FILTER_VALIDATE_URL)) {
            return $this->option_image;
        }

        $cleanPath = ltrim(str_replace('storage/', '', $this->option_image), '/');
        return asset('storage/' . $cleanPath);
    }
}
