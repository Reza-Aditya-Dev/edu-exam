<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Exam extends Model
{
    protected $fillable = [
        'subject_id', 'classroom_id', 'teacher_id', 'academic_year_id',
        'title', 'exam_type', 'description', 'instructions',
        'exam_date', 'start_time', 'end_time', 'duration_minutes',
        'total_questions', 'passing_grade', 'status',
    ];

    protected $casts = [
        'exam_date'      => 'date',
        'passing_grade'  => 'float',
        'total_questions'=> 'integer',
        'duration_minutes'=> 'integer',
    ];

    public function subject()      { return $this->belongsTo(Subject::class); }
    public function classroom()    { return $this->belongsTo(Classroom::class); }
    public function teacher()      { return $this->belongsTo(User::class, 'teacher_id'); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
    public function settings()     { return $this->hasOne(ExamSetting::class); }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_questions')
                    ->withPivot('question_order', 'id')
                    ->orderBy('exam_questions.question_order');
    }

    public function examQuestions() { return $this->hasMany(ExamQuestion::class)->orderBy('question_order'); }
    public function participants()  { return $this->hasMany(ExamParticipant::class); }
    public function results()       { return $this->hasMany(ExamResult::class); }

    // Scopes
    public function scopeForTeacher($query, $teacherId) { return $query->where('teacher_id', $teacherId); }
    public function scopeStatus($query, $status)         { return $query->where('status', $status); }
    public function scopeActive($query)                  { return $query->where('status', 'active'); }
    public function scopeScheduled($query)               { return $query->where('status', 'scheduled'); }

    public function getIsActiveAttribute(): bool { return $this->status === 'active'; }
    public function getIsScheduledAttribute(): bool { return $this->status === 'scheduled'; }
    public function getIsCompletedAttribute(): bool { return $this->status === 'completed'; }

    public function getFormattedDateAttribute(): string
    {
        return $this->exam_date ? $this->exam_date->translatedFormat('d F Y') : '-';
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft'     => 'Draft',
            'scheduled' => 'Terjadwal',
            'active'    => 'Berlangsung',
            'completed' => 'Selesai',
            'archived'  => 'Diarsipkan',
            default     => '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft'     => 'gray',
            'scheduled' => 'blue',
            'active'    => 'green',
            'completed' => 'indigo',
            'archived'  => 'orange',
            default     => 'gray',
        };
    }

    public function getParticipantCountAttribute(): int
    {
        return $this->participants()->count();
    }

    public function getSubmittedCountAttribute(): int
    {
        return $this->participants()->where('status', 'submitted')->count();
    }
}
