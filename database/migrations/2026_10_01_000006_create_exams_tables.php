<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ujian — dibuat guru, terdiri dari soal-soal dari bank soal
     */
    public function up(): void
    {
        // Tabel ujian utama
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete()
                  ->comment('Guru pembuat ujian');
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->string('title')->comment('Contoh: UTS Matematika');
            $table->enum('exam_type', ['UTS', 'UAS', 'UH', 'Quiz', 'Remedial', 'Lainnya'])
                  ->default('UH')
                  ->comment('Jenis ujian');
            $table->text('description')->nullable();
            $table->text('instructions')->nullable()->comment('Petunjuk ujian untuk siswa');
            $table->date('exam_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration_minutes')->comment('Durasi dalam menit');
            $table->integer('total_questions')->default(0);
            $table->decimal('passing_grade', 5, 2)->default(75.00)->comment('KKM');
            $table->enum('status', ['draft', 'scheduled', 'active', 'completed', 'archived'])
                  ->default('draft');
            $table->timestamps();

            $table->index('status');
            $table->index('exam_date');
            $table->index('teacher_id');
            $table->index('subject_id');
            $table->index('classroom_id');
            $table->index(['teacher_id', 'subject_id']);
            $table->index(['status', 'exam_date']);
        });

        // Pengaturan per ujian
        Schema::create('exam_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->unique()->constrained('exams')->cascadeOnDelete();
            $table->boolean('shuffle_questions')->default(false)->comment('Acak urutan soal');
            $table->boolean('shuffle_options')->default(false)->comment('Acak pilihan jawaban');
            $table->boolean('auto_save')->default(true)->comment('Simpan jawaban otomatis');
            $table->boolean('auto_submit')->default(true)->comment('Auto-submit saat waktu habis');
            $table->boolean('show_result_immediately')->default(true)->comment('Tampilkan nilai setelah submit');
            $table->boolean('allow_review')->default(false)->comment('Boleh review jawaban');
            $table->boolean('show_correct_answers')->default(false)->comment('Tampilkan kunci jawaban');
            $table->integer('max_attempts')->default(1)->comment('Maksimal percobaan');
            $table->timestamps();
        });

        // Soal yang dipakai dalam ujian (pivot)
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->integer('question_order')->default(0)->comment('Urutan soal di ujian');
            $table->timestamps();

            $table->unique(['exam_id', 'question_id']);
            $table->index('exam_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
        Schema::dropIfExists('exam_settings');
        Schema::dropIfExists('exams');
    }
};
