<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tahun ajaran sekolah (misal: 2026/2027 Semester 1)
     */
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Contoh: 2026/2027');
            $table->integer('start_year');
            $table->integer('end_year');
            $table->enum('semester', ['1', '2'])->default('1');
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->unique(['start_year', 'end_year', 'semester']);
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
