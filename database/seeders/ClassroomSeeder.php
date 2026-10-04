<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classroom;
use App\Models\AcademicYear;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        $ay = AcademicYear::where('name', '2025/2026')->first() ?? AcademicYear::first();

        $classes = [
            ['name' => 'X IPA 1',   'grade' => 10, 'major' => 'IPA'],
            ['name' => 'X IPA 2',   'grade' => 10, 'major' => 'IPA'],
            ['name' => 'X IPS 1',   'grade' => 10, 'major' => 'IPS'],
            ['name' => 'XI MIPA 1', 'grade' => 11, 'major' => 'MIPA'],
            ['name' => 'XII MIPA 1','grade' => 12, 'major' => 'MIPA'],
            ['name' => 'XII IPA 1', 'grade' => 12, 'major' => 'IPA'],
        ];

        foreach ($classes as $c) {
            Classroom::updateOrCreate(
                ['name' => $c['name'], 'academic_year_id' => $ay?->id],
                [
                    'grade'     => $c['grade'],
                    'major'     => $c['major'],
                    'is_active' => true,
                ]
            );
        }
    }
}