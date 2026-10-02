<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pivot: siswa ↔ kelas, guru ↔ mapel, guru ↔ kelas
     */
    public function up(): void
    {
        // Siswa terdaftar di kelas
        Schema::create('classroom_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['classroom_id', 'student_id']);
            $table->index('student_id');
        });

        // Guru mengajar mata pelajaran
        Schema::create('teacher_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['teacher_id', 'subject_id']);
            $table->index('subject_id');
        });

        // Guru mengajar di kelas
        Schema::create('teacher_classroom', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete()
                  ->comment('Mapel yang diajarkan di kelas ini');
            $table->timestamps();

            $table->unique(['teacher_id', 'classroom_id', 'subject_id']);
            $table->index('classroom_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_classroom');
        Schema::dropIfExists('teacher_subject');
        Schema::dropIfExists('classroom_student');
    }
};
