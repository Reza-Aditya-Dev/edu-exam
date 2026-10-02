<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Notifikasi, log aktivitas, dan pengaturan sekolah
     */
    public function up(): void
    {
        // Notifikasi untuk semua pengguna
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['info', 'exam', 'result', 'warning', 'system'])->default('info');
            $table->string('related_type')->nullable()->comment('Model terkait: App\\Models\\Exam');
            $table->unsignedBigInteger('related_id')->nullable()->comment('ID record terkait');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index(['user_id', 'is_read']);
            $table->index(['user_id', 'type']);
            $table->index('created_at');
        });

        // Log aktivitas (audit trail)
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action')->comment('Contoh: created, updated, deleted, login');
            $table->text('description')->comment('Deskripsi aktivitas');
            $table->string('loggable_type')->nullable()->comment('Model: App\\Models\\Exam');
            $table->unsignedBigInteger('loggable_id')->nullable()->comment('ID record');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('properties')->nullable()->comment('Data tambahan dalam JSON');
            $table->timestamps();

            $table->index('user_id');
            $table->index('action');
            $table->index(['user_id', 'created_at']);
            $table->index(['loggable_type', 'loggable_id']);
        });

        // Pengaturan sekolah (key-value store)
        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general')
                  ->comment('Grup: general, exam, notification, account');
            $table->string('type')->default('string')
                  ->comment('Tipe data: string, integer, boolean, json');
            $table->text('description')->nullable()->comment('Penjelasan pengaturan');
            $table->timestamps();

            $table->index('group');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_settings');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('notifications');
    }
};
