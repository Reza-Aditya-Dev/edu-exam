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
        Schema::table('exams', function (Blueprint $table) {
            if (!Schema::hasColumn('exams', 'token')) {
                $table->string('token', 20)->nullable()->after('title');
            }
            if (!Schema::hasColumn('exams', 'session_name')) {
                $table->string('session_name', 50)->default('Sesi 1')->after('title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('exams', 'token')) $columns[] = 'token';
            if (Schema::hasColumn('exams', 'session_name')) $columns[] = 'session_name';
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
