<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Exam;
use App\Models\SchoolSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExamExportTest extends TestCase
{
    public function test_admin_can_export_exams_excel()
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.exams.export', ['format' => 'excel']));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/vnd.ms-excel', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment; filename="laporan_data_ujian_', $response->headers->get('Content-Disposition'));

        // Capture streamed content
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        // Check XML tags and essential columns
        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $content);
        $this->assertStringContainsString('<Worksheet ss:Name="Data Ujian">', $content);
        $this->assertStringContainsString('LAPORAN DATA UJIAN SISWA', $content);
        $this->assertStringContainsString('<Data ss:Type="String">ID Sistem</Data>', $content);
        $this->assertStringContainsString('<Data ss:Type="String">Judul Ujian</Data>', $content);
        $this->assertStringContainsString('<Data ss:Type="String">Token CBT</Data>', $content);
        $this->assertStringContainsString('<Data ss:Type="String">Status Ujian</Data>', $content);
        $this->assertStringContainsString('<Data ss:Type="String">NIP Guru</Data>', $content);
        $this->assertStringContainsString('<FreezePanes/>', $content);
        $this->assertStringContainsString('<AutoFilter', $content);
    }

    public function test_admin_can_export_exams_pdf()
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.exams.export', ['format' => 'pdf']));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('laporan_data_ujian_', $response->headers->get('Content-Disposition'));
    }

    public function test_admin_can_export_exams_filtered()
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $sampleExam = Exam::first();
        if ($sampleExam) {
            $response = $this->actingAs($admin)->get(route('admin.exams.export', [
                'format' => 'excel',
                'search' => $sampleExam->title
            ]));

            $response->assertStatus(200);
            ob_start();
            $response->sendContent();
            $content = ob_get_clean();

            $this->assertStringContainsString(htmlspecialchars($sampleExam->title, ENT_QUOTES, 'UTF-8'), $content);
        }
    }
}
