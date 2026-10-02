<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Subject;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Tahun Ajaran
        $academicYear = AcademicYear::create([
            'name' => '2025/2026',
            'start_year' => 2025,
            'end_year' => 2026,
            'semester' => 1,
            'is_active' => true,
        ]);

        // 2. Buat Kelas
        $classroom = Classroom::create([
            'name' => 'XII IPA 1',
            'academic_year_id' => $academicYear->id,
            'grade' => 12,
            'major' => 'IPA'
        ]);

        // 3. Buat Mata Pelajaran
        $subject = Subject::create([
            'name' => 'Matematika',
            'code' => 'MTK',
        ]);

        // ==========================================
        // AKUN ADMIN
        // ==========================================
        User::create([
            'name' => 'Administrator Utama',
            'email' => 'admin@eduexam.com',
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'gender' => 'L',
            'is_active' => true,
        ]);

        // ==========================================
        // AKUN GURU
        // ==========================================
        $teacher = User::create([
            'name' => 'Budi Santoso, S.Pd',
            'email' => 'guru@eduexam.com',
            'username' => 'guru',
            'nip' => '198001012005011001',
            'password' => Hash::make('password123'),
            'role' => 'teacher',
            'gender' => 'L',
            'is_active' => true,
        ]);
        
        // Relasikan Guru dengan Mata Pelajaran
        $teacher->subjects()->attach($subject->id);

        // ==========================================
        // AKUN SISWA
        // ==========================================
        $student = User::create([
            'name' => 'Andi Siswanto',
            'email' => 'siswa@eduexam.com',
            'username' => 'siswa',
            'nis' => '100200300',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'gender' => 'L',
            'is_active' => true,
        ]);

        // Masukkan Siswa ke dalam Kelas
        $student->classrooms()->attach($classroom->id);
    }
}
