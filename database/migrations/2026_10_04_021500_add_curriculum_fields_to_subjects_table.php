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
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('code', 30)->change();
            if (!Schema::hasColumn('subjects', 'category')) {
                $table->string('category', 50)->default('Wajib Umum')->after('name');
            }
            if (!Schema::hasColumn('subjects', 'target_grades')) {
                $table->string('target_grades', 100)->default('Kelas X, XI, XII')->after('category');
            }
            if (!Schema::hasColumn('subjects', 'icon')) {
                $table->string('icon', 50)->nullable()->after('target_grades');
            }
            if (!Schema::hasColumn('subjects', 'passing_grade')) {
                $table->decimal('passing_grade', 5, 2)->default(75.00)->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('subjects', 'category')) $columns[] = 'category';
            if (Schema::hasColumn('subjects', 'target_grades')) $columns[] = 'target_grades';
            if (Schema::hasColumn('subjects', 'icon')) $columns[] = 'icon';
            if (Schema::hasColumn('subjects', 'passing_grade')) $columns[] = 'passing_grade';
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
