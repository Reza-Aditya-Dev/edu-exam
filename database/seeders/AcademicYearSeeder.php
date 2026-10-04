<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        AcademicYear::updateOrCreate(
            ['name' => '2025/2026'],
            [
                'start_year' => 2025,
                'end_year'   => 2026,
                'semester'   => 1,
                'is_active'  => true,
            ]
        );

        AcademicYear::updateOrCreate(
            ['name' => '2026/2027'],
            [
                'start_year' => 2026,
                'end_year'   => 2027,
                'semester'   => 2,
                'is_active'  => false,
            ]
        );
    }
}