<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = ['name', 'start_year', 'end_year', 'semester', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function classrooms() { return $this->hasMany(Classroom::class); }
    public function exams() { return $this->hasMany(Exam::class); }

    public function scopeActive($query) { return $query->where('is_active', true); }

    public static function getActive() { return static::where('is_active', true)->first(); }

    public function getLabelAttribute() { return $this->name . ' Semester ' . $this->semester; }
}
