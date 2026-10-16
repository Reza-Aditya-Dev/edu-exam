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
        Schema::table('exam_results', function (Blueprint $table) {
            $table->enum('grading_status', ['graded', 'pending_review'])
                  ->default('graded')
                  ->after('pass_status')
                  ->comment('Status penilaian: graded (selesai dikoreksi) atau pending_review (menunggu koreksi esai)');
            $table->index('grading_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropIndex(['grading_status']);
            $table->dropColumn('grading_status');
        });
    }
};
