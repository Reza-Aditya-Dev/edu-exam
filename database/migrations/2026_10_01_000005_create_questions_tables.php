<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bank soal — soal yang dibuat guru, termasuk pilihan jawaban
     */
    public function up(): void
    {
        // Tabel soal utama
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete()
                  ->comment('Guru yang membuat soal');
            $table->enum('type', ['multiple_choice', 'true_false', 'short_answer', 'essay'])
                  ->default('multiple_choice');
            $table->text('question_text');
            $table->string('question_image')->nullable()->comment('Path gambar soal');
            $table->string('topic')->nullable()->comment('Materi/topik soal');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->decimal('score', 5, 2)->default(1.00)->comment('Bobot nilai soal');
            $table->text('explanation')->nullable()->comment('Pembahasan jawaban');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('subject_id');
            $table->index('created_by');
            $table->index('type');
            $table->index('difficulty');
            $table->index(['subject_id', 'type', 'difficulty']);
        });

        // Pilihan jawaban untuk soal pilihan ganda / benar-salah
        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->string('label', 5)->comment('A, B, C, D, E atau Benar/Salah');
            $table->text('option_text');
            $table->string('option_image')->nullable()->comment('Path gambar pilihan');
            $table->boolean('is_correct')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('question_id');
            $table->index(['question_id', 'is_correct']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_options');
        Schema::dropIfExists('questions');
    }
};
