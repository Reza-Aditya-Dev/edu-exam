<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with complete, production-ready school exam data.
     */
    public function run(): void
    {
        $this->call([
            SchoolSettingSeeder::class,
            AcademicYearSeeder::class,
            ClassroomSeeder::class,
            SubjectSeeder::class,
            UserSeeder::class,
            QuestionSeeder::class,
            ExamSeeder::class,
        ]);
    }
}