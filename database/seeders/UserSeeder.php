<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Subject;
use App\Models\Classroom;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name'      => 'Administrator Utama',
                'email'     => 'admin@eduexam.com',
                'password'  => Hash::make('password123'),
                'role'      => 'admin',
                'gender'    => 'L',
                'is_active' => true,
            ]
        );

        // 2. Guru Budi Santoso (Matematika & IPA)
        $teacherBudi = User::updateOrCreate(
            ['username' => 'guru'],
            [
                'name'      => 'Budi Santoso, S.Pd',
                'email'     => 'guru@eduexam.com',
                'nip'       => '198001012005011001',
                'password'  => Hash::make('password123'),
                'role'      => 'teacher',
                'gender'    => 'L',
                'is_active' => true,
            ]
        );

        // 3. Guru Fadila Nur (Bahasa Indonesia & Inggris)
        $teacherFadila = User::updateOrCreate(
            ['username' => 'fadila.nur'],
            [
                'name'      => 'Fadila Nur S.Pd.',
                'email'     => 'fadila@gmail.com',
                'nip'       => '198505122010012003',
                'password'  => Hash::make('password123'),
                'role'      => 'teacher',
                'gender'    => 'P',
                'is_active' => true,
            ]
        );

        // Hubungkan Guru ke Mapel
        $mtk = Subject::where('code', 'MTK')->first();
        $ipa = Subject::where('code', 'IPA')->first();
        $bin = Subject::where('code', 'MAPEL-BIN-02')->first();
        $eng = Subject::where('code', 'MAPEL-ENG-03')->first();

        if ($mtk) $teacherBudi->subjects()->syncWithoutDetaching([$mtk->id]);
        if ($ipa) $teacherBudi->subjects()->syncWithoutDetaching([$ipa->id]);
        if ($bin) $teacherFadila->subjects()->syncWithoutDetaching([$bin->id]);
        if ($eng) $teacherFadila->subjects()->syncWithoutDetaching([$eng->id]);

        // 4. Siswa
        $studentsData = [
            ['name' => 'Andi Siswanto',   'username' => 'siswa',          'email' => 'siswa@eduexam.com',           'nis' => '100200300', 'gender' => 'L', 'class' => 'X IPA 1'],
            ['name' => 'Reza aditya',     'username' => 'rezaaditya',     'email' => 'rezaadityathohir30@gmail.com','nis' => '100200301', 'gender' => 'L', 'class' => 'X IPA 1'],
            ['name' => 'Andi Pratama',    'username' => 'andipratama',    'email' => 'andi.p@smanusantara.sch.id',   'nis' => '100200302', 'gender' => 'L', 'class' => 'X IPA 1'],
            ['name' => 'Siti Nurhaliza',  'username' => 'sitinurhaliza',  'email' => 'siti.n@smanusantara.sch.id',  'nis' => '100200303', 'gender' => 'P', 'class' => 'X IPA 2'],
            ['name' => 'Dewi Anggraini',  'username' => 'dewianggraini',  'email' => 'dewi.a@smanusantara.sch.id',  'nis' => '100200304', 'gender' => 'P', 'class' => 'X IPS 1'],
            ['name' => 'Fajar Ramadhan',  'username' => 'fajarramadhan',  'email' => 'fajar.r@smanusantara.sch.id',  'nis' => '100200305', 'gender' => 'L', 'class' => 'XI MIPA 1'],
            ['name' => 'Budi Santoso',    'username' => 'budisantoso',    'email' => 'budi.s@smanusantara.sch.id',  'nis' => '100200306', 'gender' => 'L', 'class' => 'XII MIPA 1'],
            ['name' => 'Rian Hidayat',    'username' => 'rianhidayat',    'email' => 'rian.h@smanusantara.sch.id',  'nis' => '100200307', 'gender' => 'L', 'class' => 'XII MIPA 1'],
        ];

        foreach ($studentsData as $st) {
            $student = User::updateOrCreate(
                ['username' => $st['username']],
                [
                    'name'      => $st['name'],
                    'email'     => $st['email'],
                    'nis'       => $st['nis'],
                    'password'  => Hash::make('password123'),
                    'role'      => 'student',
                    'gender'    => $st['gender'],
                    'is_active' => true,
                ]
            );

            $cls = Classroom::where('name', $st['class'])->first();
            if ($cls) {
                $student->classrooms()->syncWithoutDetaching([$cls->id]);
            }
        }
    }
}