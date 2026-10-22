<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Exam;
use App\Models\ExamParticipant;
use App\Models\ExamResult;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExamTimeSpentAndDurationTest extends TestCase
{
    protected function createTestExam(User $teacher, array $attributes = []): Exam
    {
        $academicYear = \App\Models\AcademicYear::firstOrCreate(
            ['name' => '2026/2027'],
            ['start_year' => 2026, 'end_year' => 2027, 'semester' => '1', 'is_active' => true]
        );

        $subject = \App\Models\Subject::firstOrCreate(
            ['code' => 'TEST-SUBJ'],
            ['name' => 'Test Subject', 'category' => 'Umum', 'target_grades' => '10', 'passing_grade' => 75]
        );

        $classroom = \App\Models\Classroom::firstOrCreate(
            ['name' => 'Kelas Test'],
            [
                'academic_year_id' => $academicYear->id,
                'grade' => '10',
                'major' => 'IPA',
                'capacity' => 30,
                'is_active' => true,
            ]
        );

        $default = [
            'academic_year_id' => $academicYear->id,
            'subject_id'       => $subject->id,
            'classroom_id'     => $classroom->id,
            'teacher_id'       => $teacher->id,
            'title'            => 'Ujian Matematika WIT',
            'exam_date'        => '2026-10-20',
            'start_time'       => '08:00:00',
            'end_time'         => '09:30:00',
            'timezone'         => 'Asia/Jayapura',
            'duration_minutes' => 90,
            'passing_grade'    => 75,
            'status'           => 'active',
        ];

        return Exam::create(array_merge($default, $attributes));
    }

    /**
     * Ujian dengan timezone Asia/Jayapura (WIT)
     * Siswa menyelesaikan ujian lebih cepat dari jadwal
     */
    public function test_student_finishes_earlier_than_scheduled_in_wit()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);

        // Ujian 08:00 - 09:30 WIT, durasi 90 menit
        $exam = $this->createTestExam($teacher, [
            'title' => 'Ujian Matematika WIT',
        ]);

        // Siswa mulai 08:05 WIT dan mengumpulkan 08:35 WIT (30 menit)
        $startedAt = Carbon::parse('2026-10-20 08:05:00', 'Asia/Jayapura');
        $submittedAt = Carbon::parse('2026-10-20 08:35:00', 'Asia/Jayapura');

        $participant = ExamParticipant::create([
            'exam_id'      => $exam->id,
            'student_id'   => $student->id,
            'status'       => 'submitted',
            'started_at'   => $startedAt,
            'submitted_at' => $submittedAt,
        ]);

        $calculatedMinutes = $participant->calculateTimeSpentMinutes($submittedAt);
        $this->assertEquals(30, $calculatedMinutes);

        $result = ExamResult::create([
            'exam_id'             => $exam->id,
            'student_id'          => $student->id,
            'exam_participant_id' => $participant->id,
            'total_score'         => 80,
            'time_spent_minutes'  => $calculatedMinutes,
            'pass_status'         => 'pass',
            'grading_status'      => 'graded',
        ]);

        $this->assertEquals(30, $result->time_spent_minutes);
        $this->assertEquals('08:35 WIT', $result->formatted_submitted_time);
    }

    /**
     * Siswa mengumpulkan tepat pada batas waktu jadwal ujian (WIT)
     */
    public function test_student_submits_exactly_at_session_end_time()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);

        $exam = $this->createTestExam($teacher, [
            'title' => 'Ujian Bahasa WIT',
        ]);

        // Siswa mulai 08:00 WIT dan submit tepat 09:30 WIT (90 menit)
        $startedAt = Carbon::parse('2026-10-20 08:00:00', 'Asia/Jayapura');
        $submittedAt = Carbon::parse('2026-10-20 09:30:00', 'Asia/Jayapura');

        $participant = ExamParticipant::create([
            'exam_id'      => $exam->id,
            'student_id'   => $student->id,
            'status'       => 'submitted',
            'started_at'   => $startedAt,
            'submitted_at' => $submittedAt,
        ]);

        $calculatedMinutes = $participant->calculateTimeSpentMinutes($submittedAt);
        $this->assertEquals(90, $calculatedMinutes);
    }

    /**
     * Siswa mulai 08:05, jadwal ujian selesai 09:30, ujian ditutup otomatis / timeout
     * Waktu pengerjaan tidak boleh melebihi batas jadwal (08:05 s/d 09:30 = 85 menit)
     */
    public function test_exam_auto_closed_when_time_expires()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);

        // Ujian 08:00 s.d 09:30 WIT, durasi alokasi 120 menit (lebih besar dari sisa sesi)
        $exam = $this->createTestExam($teacher, [
            'title'            => 'Ujian Sains WIT',
            'duration_minutes' => 120,
        ]);

        // Siswa mulai 08:05 WIT
        $startedAt = Carbon::parse('2026-10-20 08:05:00', 'Asia/Jayapura');

        $participant = ExamParticipant::create([
            'exam_id'      => $exam->id,
            'student_id'   => $student->id,
            'status'       => 'in_progress',
            'started_at'   => $startedAt,
        ]);

        // Tutup otomatis ujian saat waktu berakhir
        $exam->closeIfExpired();

        // Atau bila fungsi submit timeout dijalankan dengan submitted_at end_carbon:
        $effectiveSubmittedAt = $exam->end_carbon;
        $calculatedMinutes = $participant->calculateTimeSpentMinutes($effectiveSubmittedAt);

        // 08:05 sampai 09:30 = 85 menit
        $this->assertEquals(85, $calculatedMinutes);
    }

    /**
     * Hasil ujian lama yang sudah tersimpan tetap konsisten dan tidak dihitung ulang saat diakses
     */
    public function test_existing_exam_result_persists_duration_and_renders_in_teacher_results()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);

        $exam = $this->createTestExam($teacher, [
            'title'  => 'Ujian Lama WIT',
            'status' => 'completed',
        ]);

        $participant = ExamParticipant::create([
            'exam_id'      => $exam->id,
            'student_id'   => $student->id,
            'status'       => 'submitted',
            'started_at'   => Carbon::parse('2026-10-20 08:05:00', 'Asia/Jayapura'),
            'submitted_at' => Carbon::parse('2026-10-20 08:35:00', 'Asia/Jayapura'),
        ]);

        $result = ExamResult::create([
            'exam_id'             => $exam->id,
            'student_id'          => $student->id,
            'exam_participant_id' => $participant->id,
            'total_score'         => 90,
            'correct_answers'     => 10,
            'wrong_answers'       => 0,
            'unanswered'          => 0,
            'time_spent_minutes'  => 30,
            'pass_status'         => 'pass',
            'grading_status'      => 'graded',
        ]);

        // Kunjungi halaman hasil guru
        $response = $this->actingAs($teacher)->get(route('teacher.exams.results', $exam));
        $response->assertStatus(200);
        $response->assertSee('30 Menit');
        $response->assertSee('08:35 WIT');

        // Pastikan export PDF dan Excel juga menampilkan WIT
        $pdfResponse = $this->actingAs($teacher)->get(route('teacher.exams.export_results', ['exam' => $exam->id, 'format' => 'pdf']));
        $pdfResponse->assertStatus(200);

        $excelResponse = $this->actingAs($teacher)->get(route('teacher.exams.export_results', ['exam' => $exam->id, 'format' => 'excel']));
        $excelResponse->assertStatus(200);
        $this->assertStringContainsString('30 Menit (Dikumpulkan: 08:35 WIT)', $excelResponse->getContent());
    }

    /**
     * Pengujian durasi yang melewati pergantian hari (misal 23:45 s/d 00:15)
     */
    public function test_duration_across_midnight_if_supported()
    {
        $startedAt = Carbon::parse('2026-10-20 23:45:00', 'Asia/Jayapura');
        $submittedAt = Carbon::parse('2026-10-21 00:15:00', 'Asia/Jayapura');

        $diffMinutes = (int) $startedAt->diffInMinutes($submittedAt);
        $this->assertEquals(30, $diffMinutes);
    }
}
