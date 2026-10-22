<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\SchoolSetting;

class TeacherExamResultsExportTest extends TestCase
{
    public function test_teacher_can_view_results_page_with_school_logo_and_name()
    {
        $teacher = User::where('role', 'teacher')->first() ?? User::factory()->create(['role' => 'teacher']);
        $exam = Exam::where('teacher_id', $teacher->id)->first();
        
        if (!$exam) {
            $exam = Exam::factory()->create(['teacher_id' => $teacher->id]);
        }

        $response = $this->actingAs($teacher)->get(route('teacher.exams.results', $exam));

        $response->assertStatus(200);
        $response->assertViewHas('schoolName');
        $response->assertViewHas('schoolLogoUrl');
        $response->assertSee('print-school-header', false);
        $response->assertSee(SchoolSetting::get('school_name', 'SMA Nusantara'));
    }

    public function test_teacher_can_export_results_pdf_with_logo()
    {
        $teacher = User::where('role', 'teacher')->first() ?? User::factory()->create(['role' => 'teacher']);
        $exam = Exam::where('teacher_id', $teacher->id)->first();
        
        if (!$exam) {
            $exam = Exam::factory()->create(['teacher_id' => $teacher->id]);
        }

        $response = $this->actingAs($teacher)->get(route('teacher.exams.export_results', [
            'exam' => $exam->id,
            'format' => 'pdf'
        ]));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('rekap_nilai_', $response->headers->get('Content-Disposition'));

        $pdfContent = $response->getContent();
        $this->assertStringContainsString('/Image', $pdfContent, 'PDF must contain embedded school logo image');
        $this->assertStringContainsString('/XObject', $pdfContent, 'PDF must contain XObject dictionary');
    }

    public function test_teacher_can_export_results_excel_with_school_branding()
    {
        $teacher = User::where('role', 'teacher')->first() ?? User::factory()->create(['role' => 'teacher']);
        $exam = Exam::where('teacher_id', $teacher->id)->first();
        
        if (!$exam) {
            $exam = Exam::factory()->create(['teacher_id' => $teacher->id]);
        }

        $response = $this->actingAs($teacher)->get(route('teacher.exams.export_results', [
            'exam' => $exam->id,
            'format' => 'excel'
        ]));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/vnd.ms-excel', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('rekap_nilai_', $response->headers->get('Content-Disposition'));

        $content = $response->getContent();
        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $content);
        $this->assertStringContainsString('<Worksheet ss:Name="Rekap Nilai">', $content);
        $this->assertStringContainsString(strtoupper(SchoolSetting::get('school_name', 'SMA Nusantara')), $content);
        $this->assertStringContainsString('EDUXEAM CBT PLATFORM', $content);
        $this->assertStringContainsString('REKAP HASIL UJIAN -', $content);
        $this->assertStringContainsString('<Data ss:Type="String">Nama Siswa</Data>', $content);
        $this->assertStringContainsString('<Data ss:Type="String">Nilai Akhir</Data>', $content);
        $this->assertStringContainsString('<SplitHorizontal>13</SplitHorizontal>', $content);
    }
}
