<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'username', 'password', 'role',
        'avatar', 'nip', 'nis', 'nisn', 'gender', 'birth_place', 'birth_date', 'religion',
        'phone', 'address', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date'        => 'date',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // Scopes
    public function scopeStudents($query) { return $query->where('role', 'student'); }
    public function scopeTeachers($query) { return $query->where('role', 'teacher'); }
    public function scopeAdmins($query)   { return $query->where('role', 'admin'); }
    public function scopeActive($query)   { return $query->where('is_active', true); }

    // Role checks
    public function isAdmin()   { return $this->role === 'admin'; }
    public function isTeacher() { return $this->role === 'teacher'; }
    public function isStudent() { return $this->role === 'student'; }

    // Relationships — Student
    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_student', 'student_id', 'classroom_id')
                    ->withTimestamps();
    }

    public function currentClassroom()
    {
        return $this->classrooms()
                    ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
                    ->first() ?: $this->classrooms()->first();
    }

    public function examParticipants()
    {
        return $this->hasMany(ExamParticipant::class, 'student_id');
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class, 'student_id');
    }

    public function studentAnswers()
    {
        return $this->hasManyThrough(StudentAnswer::class, ExamParticipant::class, 'student_id', 'exam_participant_id');
    }

    // Relationships — Teacher
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject', 'teacher_id', 'subject_id')
                    ->withTimestamps();
    }

    public function teachingClassrooms()
    {
        return $this->belongsToMany(Classroom::class, 'teacher_classroom', 'teacher_id', 'classroom_id')
                    ->withPivot('subject_id')
                    ->withTimestamps();
    }

    public function homeroomClassrooms()
    {
        return $this->hasMany(Classroom::class, 'homeroom_teacher_id');
    }

    public function createdExams()
    {
        return $this->hasMany(Exam::class, 'teacher_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'created_by');
    }

    // Relationships — Shared
    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        $initial = strtoupper(substr($this->name, 0, 1));
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=4f46e5&color=fff&size=128";
    }
}
