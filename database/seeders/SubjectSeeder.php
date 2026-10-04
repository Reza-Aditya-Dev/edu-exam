<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['code' => 'MTK',          'name' => 'Matematika'],
            ['code' => 'IPA',          'name' => 'Ilmu pengetahuan alam'],
            ['code' => 'MAPEL-BIN-02', 'name' => 'Bahasa Indonesia'],
            ['code' => 'MAPEL-ENG-03', 'name' => 'Bahasa Inggris'],
            ['code' => 'MAPEL-FSK-04', 'name' => 'Fisika'],
            ['code' => 'MAPEL-KMA-05', 'name' => 'Kimia'],
            ['code' => 'MAPEL-BIO-06', 'name' => 'Biologi'],
            ['code' => 'MAPEL-SEJ-07', 'name' => 'Sejarah Indonesia'],
            ['code' => 'MAPEL-INF-08', 'name' => 'Informatika'],
            ['code' => 'MAPEL-SOS-09', 'name' => 'Sosiologi'],
            ['code' => 'MAPEL-EKO-10', 'name' => 'Ekonomi'],
            ['code' => 'MAPEL-GEO-11', 'name' => 'Geografi'],
            ['code' => 'MAPEL-PPN-12', 'name' => 'Pendidikan Pancasila'],
            ['code' => 'MAPEL-SBD-13', 'name' => 'Seni Budaya'],
            ['code' => 'MAPEL-PJO-14', 'name' => 'Pendidikan Jasmani & Olahraga'],
            ['code' => 'MAPEL-JPN-15', 'name' => 'Bahasa Jepang'],
            ['code' => 'MAPEL-ARB-16', 'name' => 'Bahasa Arab'],
            ['code' => 'MAPEL-PKW-17', 'name' => 'Prakarya & Kewirausahaan'],
            ['code' => 'MAPEL-MLK-18', 'name' => 'Bahasa Daerah / Sunda'],
        ];

        foreach ($subjects as $s) {
            Subject::updateOrCreate(
                ['code' => $s['code']],
                ['name' => $s['name']]
            );
        }
    }
}