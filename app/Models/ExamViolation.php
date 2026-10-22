<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamViolation extends Model
{
    protected $fillable = [
        'exam_id',
        'student_id',
        'exam_participant_id',
        'violation_type',
        'duration_seconds',
        'violation_number',
        'details',
        'occurred_at',
    ];

    protected $casts = [
        'occurred_at'      => 'datetime',
        'duration_seconds' => 'integer',
        'violation_number' => 'integer',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function participant()
    {
        return $this->belongsTo(ExamParticipant::class, 'exam_participant_id');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->violation_type) {
            'tab_switch'      => 'Berpindah Tab Browser',
            'window_blur'     => 'Keluar / Minimize Jendela Ujian',
            'exit_fullscreen' => 'Keluar dari Layar Penuh (Fullscreen)',
            'devtools'        => 'Mencoba Buka Developer Tools',
            'copy_paste'      => 'Mencoba Salin / Tempel Konten',
            default           => 'Meninggalkan Halaman Ujian',
        };
    }
}
