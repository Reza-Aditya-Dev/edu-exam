<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['academic_year_id', 'homeroom_teacher_id', 'name', 'grade', 'major', 'capacity', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function academicYear()     { return $this->belongsTo(AcademicYear::class); }
    public function homeroomTeacher()  { return $this->belongsTo(User::class, 'homeroom_teacher_id'); }

    public function students()
    {
        return $this->belongsToMany(User::class, 'classroom_student', 'classroom_id', 'student_id')
                    ->withTimestamps();
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_classroom', 'classroom_id', 'teacher_id')
                    ->withPivot('subject_id')
                    ->withTimestamps();
    }

    public function exams()  { return $this->hasMany(Exam::class); }

    public function scopeActive($query) { return $query->where('is_active', true); }

    public function getStudentCountAttribute() { return $this->students()->count(); }
}
