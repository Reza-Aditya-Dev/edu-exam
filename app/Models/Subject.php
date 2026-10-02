<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'code', 'description', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function questions() { return $this->hasMany(Question::class); }
    public function exams()     { return $this->hasMany(Exam::class); }
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_subject', 'subject_id', 'teacher_id')
                    ->withTimestamps();
    }

    public function scopeActive($query) { return $query->where('is_active', true); }
}
