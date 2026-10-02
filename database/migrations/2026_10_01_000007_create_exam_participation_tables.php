<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Peserta ujian, jawaban siswa, dan hasil ujian
     */
    public function up(): void
    {
        // Peserta ujian — mencatat status pengerjaan setiap siswa
        Schema::create('exam_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['not_started', 'in_progress', 'submitted', 'timed_out'])
                  ->default('not_started');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->boolean('is_late_submission')->default(false);
            $table->timestamps();

            $table->unique(['exam_id', 'student_id']);
            $table->index('exam_id');
            $table->index('student_id');
            $table->index('status');
        });

        // Jawaban siswa per soal
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_participant_id')->constrained('exam_participants')->cascadeOnDelete();
            $table->foreignId('exam_question_id')->constrained('exam_questions')->cascadeOnDelete();
            $table->foreignId('selected_option_id')->nullable()->constrained('question_options')->nullOnDelete()
                  ->comment('Pilihan yang dipilih siswa (PG/B-S)');
            $table->text('answer_text')->nullable()->comment('Jawaban teks (isian/essay)');
            $table->boolean('is_correct')->nullable()->comment('null jika belum dinilai (essay)');
            $table->decimal('score_obtained', 5, 2)->default(0)->comment('Nilai yang didapat');
            $table->boolean('is_marked')->default(false)->comment('Ditandai untuk review');
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();

            $table->unique(['exam_participant_id', 'exam_question_id']);
            $table->index('exam_participant_id');
            $table->index('is_marked');
        });

        // Ringkasan hasil ujian per siswa
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('exam_participant_id')->constrained('exam_participants')->cascadeOnDelete();
            $table->decimal('total_score', 5, 2)->default(0);
            $table->integer('correct_answers')->default(0);
            $table->integer('wrong_answers')->default(0);
            $table->integer('unanswered')->default(0);
            $table->integer('time_spent_minutes')->default(0)->comment('Waktu pengerjaan dalam menit');
            $table->enum('pass_status', ['pass', 'fail'])->default('fail');
            $table->timestamps();

            $table->unique(['exam_id', 'student_id']);
            $table->index('exam_id');
            $table->index('student_id');
            $table->index('pass_status');
            $table->index(['exam_id', 'pass_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_results');
        Schema::dropIfExists('student_answers');
        Schema::dropIfExists('exam_participants');
    }
};
