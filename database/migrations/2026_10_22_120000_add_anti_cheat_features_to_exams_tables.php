<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah pengaturan anti-cheat di exam_settings
        Schema::table('exam_settings', function (Blueprint $table) {
            $table->boolean('enable_anti_cheat')->default(true)->after('show_correct_answers')->comment('Aktifkan perlindungan ujian (fullscreen, deteksi tab/blur)');
            $table->integer('max_violations')->default(3)->after('enable_anti_cheat')->comment('Batas maksimal pelanggaran sebelum auto-submit');
            $table->decimal('violation_penalty_points', 5, 2)->default(0)->after('max_violations')->comment('Poin potongan nilai per pelanggaran (0 jika tidak ada auto-potong)');
        });

        // 2. Tambah data rekapan pelanggaran di exam_results
        Schema::table('exam_results', function (Blueprint $table) {
            $table->decimal('raw_score', 5, 2)->nullable()->after('total_score')->comment('Nilai murni sebelum potongan penalti');
            $table->decimal('penalty_score', 5, 2)->default(0)->after('raw_score')->comment('Total potongan nilai penalti');
            $table->integer('violation_count')->default(0)->after('penalty_score')->comment('Jumlah pelanggaran yang tercatat');
            $table->boolean('is_disqualified')->default(false)->after('violation_count')->comment('Apakah didiskualifikasi oleh guru atau sistem');
            $table->text('disqualification_reason')->nullable()->after('is_disqualified')->comment('Alasan diskualifikasi');
        });

        // 3. Tabel log rincian pelanggaran per peserta ujian
        Schema::create('exam_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('exam_participant_id')->constrained('exam_participants')->cascadeOnDelete();
            $table->string('violation_type', 50)->default('tab_switch')->comment('tab_switch, window_blur, exit_fullscreen, devtools, right_click, dll');
            $table->integer('duration_seconds')->default(0)->comment('Durasi meninggalkan halaman ujian (detik)');
            $table->integer('violation_number')->default(1)->comment('Pelanggaran ke-N');
            $table->text('details')->nullable()->comment('Informasi detail / pesan peringatan');
            $table->timestamp('occurred_at')->useCurrent();
            $table->timestamps();

            $table->index(['exam_id', 'student_id']);
            $table->index('exam_participant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_violations');

        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropColumn([
                'raw_score',
                'penalty_score',
                'violation_count',
                'is_disqualified',
                'disqualification_reason',
            ]);
        });

        Schema::table('exam_settings', function (Blueprint $table) {
            $table->dropColumn([
                'enable_anti_cheat',
                'max_violations',
                'violation_penalty_points',
            ]);
        });
    }
};
