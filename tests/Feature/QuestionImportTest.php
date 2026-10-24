<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\AcademicYear;
use App\Models\Question;
use App\Services\QuestionTemplateGenerator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;

class QuestionImportTest extends TestCase
{
    use DatabaseTransactions;

    protected User $teacher;
    protected User $student;
    protected Subject $subject;
    protected Classroom $classroom;
    protected AcademicYear $academicYear;

    protected function setUp(): void
    {
        parent::setUp();

        $this->academicYear = AcademicYear::create([
            'name'       => '2026/2027',
            'start_year' => 2026,
            'end_year'   => 2027,
            'semester'   => '1',
            'is_active'  => true,
        ]);

        $this->teacher = User::factory()->create([
            'role'      => 'teacher',
            'is_active' => true,
        ]);

        $this->student = User::factory()->create([
            'role'      => 'student',
            'is_active' => true,
        ]);

        $this->subject = Subject::create([
            'name'       => 'Biologi',
            'code'       => 'BIO-10',
            'is_active'  => true,
        ]);

        $this->classroom = Classroom::create([
            'name'             => 'X-MIPA-1',
            'grade'            => '10',
            'academic_year_id' => $this->academicYear->id,
            'is_active'        => true,
        ]);
    }

    /**
     * Test 1: Guru dapat mengunduh template Excel dan CSV.
     */
    public function test_teacher_can_download_excel_and_csv_templates(): void
    {
        $this->actingAs($this->teacher);

        // Download Excel template
        $responseXlsx = $this->get(route('teacher.questions.template', ['format' => 'xlsx']));
        $responseXlsx->assertStatus(200);
        $responseXlsx->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // Download CSV template
        $responseCsv = $this->get(route('teacher.questions.template', ['format' => 'csv']));
        $responseCsv->assertStatus(200);
        $responseCsv->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('multiple_choice', $responseCsv->getContent());
        $this->assertStringContainsString('true_false', $responseCsv->getContent());
    }

    /**
     * Test 2: Siswa atau guest tidak memiliki akses ke fitur import soal.
     */
    public function test_student_and_guest_cannot_access_import_questions(): void
    {
        // Guest
        $this->get(route('teacher.questions.import'))->assertRedirect(route('login'));

        // Student
        $this->actingAs($this->student);
        $this->get(route('teacher.questions.import'))->assertStatus(403);
    }

    /**
     * Test 3: Upload berkas CSV valid menghasilkan pratinjau yang memuat soal valid dan tombol konfirmasi.
     */
    public function test_upload_valid_csv_shows_preview_and_valid_count(): void
    {
        $this->actingAs($this->teacher);

        $csvStream = fopen('php://memory', 'r+');
        fputcsv($csvStream, ['No', 'Jenis Soal', 'Pertanyaan', 'Pilihan A', 'Pilihan B', 'Pilihan C', 'Pilihan D', 'Pilihan E', 'Kunci Jawaban', 'Bobot Nilai', 'Tingkat Kesulitan', 'Topik/Materi', 'Pembahasan']);
        fputcsv($csvStream, ['1', 'multiple_choice', 'Ibu kota Indonesia adalah?', 'Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Semarang', 'A', '2.5', 'easy', 'Geografi', 'Ibukota saat ini']);
        fputcsv($csvStream, ['2', 'true_false', 'Bumi berbentuk datar', 'Benar', 'Salah', '', '', '', 'Salah', '2.0', 'easy', 'Astronomi', 'Bumi bulat oblat']);
        fputcsv($csvStream, ['3', 'short_answer', 'Rumus kimia air adalah?', '', '', '', '', '', 'H2O', '3.0', 'medium', 'Kimia Dasar', 'Dua hidrogen satu oksigen']);
        rewind($csvStream);
        $csvData = stream_get_contents($csvStream);
        fclose($csvStream);

        $file = UploadedFile::fake()->createWithContent('soal_valid.csv', $csvData);

        $response = $this->post(route('teacher.questions.import.preview'), [
            'file'         => $file,
            'subject_id'   => $this->subject->id,
            'classroom_id' => $this->classroom->id,
            'topic'        => 'Ujian Tengah Semester',
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('teacher.question.preview');
        $response->assertSee('Ibu kota Indonesia adalah?');
        $response->assertSee('Bumi berbentuk datar');
        $response->assertSee('Rumus kimia air adalah?');
        $response->assertSee('3'); // valid count
        $response->assertSee('Konfirmasi &amp; Simpan 3 Soal Valid', false);
    }

    /**
     * Test 4: Upload berkas Excel (.xlsx) valid menggunakan generator resmi.
     */
    public function test_upload_valid_xlsx_shows_preview(): void
    {
        $this->actingAs($this->teacher);

        $generator = new QuestionTemplateGenerator();
        $xlsxContent = $generator->generateXlsx();

        $file = UploadedFile::fake()->createWithContent('template_resmi.xlsx', $xlsxContent);

        $response = $this->post(route('teacher.questions.import.preview'), [
            'file'       => $file,
            'subject_id' => $this->subject->id,
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('teacher.question.preview');
        $response->assertSee('Organel sel tumbuhan yang berfungsi sebagai tempat fotosintesis adalah...');
        $response->assertSee('5'); // 5 contoh di template semuanya valid
    }

    /**
     * Test 5: Validasi jika berkas kosong.
     */
    public function test_upload_empty_file_returns_error(): void
    {
        $this->actingAs($this->teacher);

        $file = UploadedFile::fake()->createWithContent('kosong.csv', '');

        $response = $this->from(route('teacher.questions.import'))->post(route('teacher.questions.import.preview'), [
            'file'       => $file,
            'subject_id' => $this->subject->id,
        ]);

        $response->assertRedirect(route('teacher.questions.import'));
        $response->assertSessionHas('error');
    }

    /**
     * Test 6: Validasi jika header spreadsheet salah atau tidak ada kolom Pertanyaan.
     */
    public function test_upload_wrong_header_returns_error(): void
    {
        $this->actingAs($this->teacher);

        $wrongCsv = "Nama,Kelas,Alamat,Telepon\nBudi,10,Jakarta,0812345678\n";
        $file = UploadedFile::fake()->createWithContent('salah_header.csv', $wrongCsv);

        $response = $this->from(route('teacher.questions.import'))->post(route('teacher.questions.import.preview'), [
            'file'       => $file,
            'subject_id' => $this->subject->id,
        ]);

        $response->assertRedirect(route('teacher.questions.import'));
        $response->assertSessionHas('error', 'Header berkas tidak sesuai. Pastikan berkas memiliki kolom "Pertanyaan" / "Soal". Unduh template resmi jika ragu.');
    }

    /**
     * Test 7: Menampilkan baris valid dan tidak valid beserta alasan & nomor baris ketika ada kunci jawaban salah atau opsi kurang.
     */
    public function test_shows_valid_and_invalid_questions_with_row_number_and_reason(): void
    {
        $this->actingAs($this->teacher);

        // Baris 2: valid
        // Baris 3: kunci jawaban tidak cocok (opsi hanya A & B, kunci Z)
        // Baris 4: teks pertanyaan kosong
        $csv = "No,Jenis Soal,Pertanyaan,Pilihan A,Pilihan B,Pilihan C,Kunci Jawaban,Bobot Nilai\n"
            . "1,multiple_choice,Soal yang benar?,Opsi 1,Opsi 2,,A,2.0\n"
            . "2,multiple_choice,Soal dengan kunci salah?,Opsi 1,Opsi 2,,Z,2.0\n"
            . "3,multiple_choice,,Opsi 1,Opsi 2,,A,2.0\n";

        $file = UploadedFile::fake()->createWithContent('campuran.csv', $csv);

        $response = $this->post(route('teacher.questions.import.preview'), [
            'file'       => $file,
            'subject_id' => $this->subject->id,
        ]);

        $response->assertStatus(200);
        $response->assertSee('Baris #2');
        $response->assertSee('Baris #3');
        $response->assertSee('Baris #4');
        $response->assertSee('TIDAK VALID');
        $response->assertSee('Kunci jawaban &#039;Z&#039; tidak cocok dengan opsi yang tersedia', false);
        $response->assertSee('Teks pertanyaan wajib diisi.');
        $response->assertSee('Soal Siap Simpan (Valid)');
        $response->assertSee('Soal Bermasalah (Tidak Valid)');
        $response->assertSee('siap diimpor');
        $response->assertSee('akan dilewati');
    }

    /**
     * Test 8: Validasi ekstensi berkas yang tidak didukung (.pdf / .docx).
     */
    public function test_unsupported_file_extension_is_rejected(): void
    {
        $this->actingAs($this->teacher);

        $file = UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf');

        $response = $this->from(route('teacher.questions.import'))->post(route('teacher.questions.import.preview'), [
            'file'       => $file,
            'subject_id' => $this->subject->id,
        ]);

        $response->assertRedirect(route('teacher.questions.import'));
        $response->assertSessionHasErrors('file');
    }

    /**
     * Test 9: Konfirmasi import menyimpan soal valid ke database menggunakan database transaction.
     */
    public function test_confirm_import_saves_valid_questions_to_bank_soal(): void
    {
        $this->actingAs($this->teacher);

        $payload = [
            [
                'row_number'     => 2,
                'is_valid'       => true,
                'type'           => 'multiple_choice',
                'question_text'  => 'Hasil dari 5 + 7 adalah?',
                'options'        => [
                    ['label' => 'A', 'text' => '10', 'is_correct' => false, 'sort_order' => 0],
                    ['label' => 'B', 'text' => '12', 'is_correct' => true, 'sort_order' => 1],
                ],
                'correct_answer' => 'B',
                'score'          => 2.5,
                'difficulty'     => 'easy',
                'topic'          => 'Aritmatika',
                'explanation'    => '5 ditambah 7 adalah 12',
            ],
            [
                'row_number'     => 3,
                'is_valid'       => false, // Seharusnya dilewati
                'type'           => 'multiple_choice',
                'question_text'  => 'Soal rusak yang tidak valid',
                'options'        => [],
                'correct_answer' => '',
                'score'          => 1.0,
                'difficulty'     => 'medium',
                'topic'          => null,
                'explanation'    => null,
            ],
        ];

        $token = 'test-unique-token-12345';

        $response = $this->post(route('teacher.questions.import.confirm'), [
            'subject_id'    => $this->subject->id,
            'classroom_id'  => $this->classroom->id,
            'topic'         => 'Matematika Dasar',
            'confirm_token' => $token,
            'payload'       => json_encode($payload),
        ]);

        $response->assertRedirect(route('teacher.questions.index', [
            'package' => "pkg_{$this->subject->id}_{$this->classroom->id}",
        ]));
        $response->assertSessionHas('success');

        // Pastikan soal masuk ke database
        $this->assertDatabaseHas('questions', [
            'subject_id'    => $this->subject->id,
            'classroom_id'  => $this->classroom->id,
            'created_by'    => $this->teacher->id,
            'type'          => 'multiple_choice',
            'question_text' => 'Hasil dari 5 + 7 adalah?',
            'score'         => 2.5,
        ]);

        $savedQ = Question::where('question_text', 'Hasil dari 5 + 7 adalah?')->first();
        $this->assertNotNull($savedQ);
        $this->assertEquals(2, $savedQ->options()->count());
        $this->assertEquals('12', $savedQ->correctOption->option_text);

        // Pastikan soal rusak TIDAK masuk
        $this->assertDatabaseMissing('questions', [
            'question_text' => 'Soal rusak yang tidak valid',
        ]);
    }

    /**
     * Test 10: Pencegahan import ganda akibat penekanan tombol konfirmasi berulang (replay attack / double submit).
     */
    public function test_prevents_duplicate_import_on_repeated_confirmation(): void
    {
        $this->actingAs($this->teacher);

        $payload = [
            [
                'row_number'     => 2,
                'is_valid'       => true,
                'type'           => 'short_answer',
                'question_text'  => 'Ibukota Jepang adalah?',
                'options'        => [],
                'correct_answer' => 'Tokyo',
                'score'          => 3.0,
                'difficulty'     => 'easy',
                'topic'          => 'Geografi Dunia',
                'explanation'    => null,
            ],
        ];

        $token = 'token-double-submit-check';

        // Submit Pertama: Berhasil
        $response1 = $this->post(route('teacher.questions.import.confirm'), [
            'subject_id'    => $this->subject->id,
            'classroom_id'  => $this->classroom->id,
            'topic'         => 'Geografi',
            'confirm_token' => $token,
            'payload'       => json_encode($payload),
        ]);
        $response1->assertRedirect();
        $response1->assertSessionHas('success');

        $initialCount = Question::where('question_text', 'Ibukota Jepang adalah?')->count();
        $this->assertEquals(1, $initialCount);

        // Submit Kedua dengan token yang sama: Dicegah!
        $response2 = $this->post(route('teacher.questions.import.confirm'), [
            'subject_id'    => $this->subject->id,
            'classroom_id'  => $this->classroom->id,
            'topic'         => 'Geografi',
            'confirm_token' => $token,
            'payload'       => json_encode($payload),
        ]);
        $response2->assertRedirect(route('teacher.questions.index'));
        $response2->assertSessionHas('warning', 'Data import ini telah diproses sebelumnya untuk mencegah duplikasi data.');

        // Jumlah soal tetap 1, tidak bertambah ganda
        $afterCount = Question::where('question_text', 'Ibukota Jepang adalah?')->count();
        $this->assertEquals(1, $afterCount);
    }

    /**
     * Test 11: Soal hasil import langsung tersedia di API getQuestions untuk pembuatan ujian.
     */
    public function test_imported_questions_are_immediately_available_in_exam_creation_api(): void
    {
        $this->actingAs($this->teacher);

        $question = Question::create([
            'subject_id'    => $this->subject->id,
            'classroom_id'  => $this->classroom->id,
            'created_by'    => $this->teacher->id,
            'type'          => 'multiple_choice',
            'question_text' => 'Soal dari import yang akan dipilih di ujian',
            'score'         => 2.5,
            'difficulty'    => 'medium',
            'is_active'     => true,
        ]);

        $apiResponse = $this->getJson(route('teacher.api.questions', [
            'subject_id'   => $this->subject->id,
            'classroom_id' => $this->classroom->id,
        ]));

        $apiResponse->assertStatus(200);
        $apiResponse->assertJsonFragment([
            'id'            => $question->id,
            'question_text' => 'Soal dari import yang akan dipilih di ujian',
        ]);
    }
}
