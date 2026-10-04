<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'code',
        'category',
        'target_grades',
        'icon',
        'passing_grade',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'passing_grade' => 'float',
    ];

    public function questions() { return $this->hasMany(Question::class); }
    public function exams()     { return $this->hasMany(Exam::class); }
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_subject', 'subject_id', 'teacher_id')
                    ->withTimestamps();
    }

    public function scopeActive($query) { return $query->where('is_active', true); }

    /**
     * Get icon or fallback based on name/category
     */
    public function getIconAttribute($value)
    {
        if ($value) {
            return $value;
        }

        $lower = strtolower($this->name ?? '');
        if (str_contains($lower, 'matematika') || str_contains($lower, 'kalkulus')) return 'calculate';
        if (str_contains($lower, 'indonesia')) return 'history_edu';
        if (str_contains($lower, 'inggris') || str_contains($lower, 'jepang') || str_contains($lower, 'arab') || str_contains($lower, 'bahasa')) return 'translate';
        if (str_contains($lower, 'fisika')) return 'science';
        if (str_contains($lower, 'kimia')) return 'biotech';
        if (str_contains($lower, 'biologi') || str_contains($lower, 'ipa')) return 'psychology';
        if (str_contains($lower, 'sejarah')) return 'account_balance';
        if (str_contains($lower, 'informatika') || str_contains($lower, 'komputer') || str_contains($lower, 'tik')) return 'terminal';
        if (str_contains($lower, 'sosiologi')) return 'groups';
        if (str_contains($lower, 'ekonomi') || str_contains($lower, 'akuntansi')) return 'trending_up';
        if (str_contains($lower, 'geografi')) return 'public';
        if (str_contains($lower, 'pancasila') || str_contains($lower, 'pkn')) return 'policy';
        if (str_contains($lower, 'seni') || str_contains($lower, 'musik')) return 'palette';
        if (str_contains($lower, 'jasmani') || str_contains($lower, 'olahraga') || str_contains($lower, 'pjok')) return 'sports_basketball';
        if (str_contains($lower, 'agama')) return 'menu_book';
        if (str_contains($lower, 'prakarya') || str_contains($lower, 'kewirausahaan')) return 'psychology_alt';

        return 'menu_book';
    }
}
