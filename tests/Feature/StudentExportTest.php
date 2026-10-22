<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Classroom;

class StudentExportTest extends TestCase
{
    public function test_admin_can_export_students_csv()
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.students.export', ['format' => 'csv']));

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('data-siswa-eduexam-', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('.csv', $response->headers->get('Content-Disposition'));

        // Capture CSV output
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        // Check UTF-8 BOM
        $this->assertTrue(str_starts_with($content, "\xEF\xBB\xBF"));

        // Check Excel separator directive
        $this->assertStringContainsString("sep=,\n", $content);

        // Check header columns
        $this->assertStringContainsString('No,NIS,NISN,"Nama Siswa",Email,Username,"Jenis Kelamin","Kelas Rombel","Status Akun","No Telepon",Alamat', $content);

        // Verify CSV structure by parsing with str_getcsv
        $cleanContent = str_replace(["\xEF\xBB\xBF", "sep=,\r\n", "sep=,\n"], '', $content);
        $lines = array_filter(explode("\n", trim($cleanContent)));
        $header = str_getcsv(array_shift($lines));
        $this->assertCount(11, $header);
        $this->assertEquals(['No', 'NIS', 'NISN', 'Nama Siswa', 'Email', 'Username', 'Jenis Kelamin', 'Kelas Rombel', 'Status Akun', 'No Telepon', 'Alamat'], $header);

        // Check each student row has exactly 11 columns
        foreach ($lines as $line) {
            $row = str_getcsv($line);
            $this->assertCount(11, $row);
        }

        // Check sample student exists
        $sampleStudent = User::students()->first();
        if ($sampleStudent) {
            $this->assertStringContainsString($sampleStudent->name, $content);
            if ($sampleStudent->nis) {
                $this->assertStringContainsString($sampleStudent->nis, $content);
            }
        }
    }

    public function test_admin_can_export_students_excel()
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.students.export', ['format' => 'excel']));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/vnd.ms-excel', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('data-siswa-eduexam-', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('.xls', $response->headers->get('Content-Disposition'));

        // Capture SpreadsheetML content
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        // Check XML tags and essential visual report elements
        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $content);
        $this->assertStringContainsString('<Worksheet ss:Name="Data Siswa">', $content);
        $this->assertStringContainsString('SMA NUSANTARA', $content);
        $this->assertStringContainsString('DATABASE SISWA CBT', $content);
        $this->assertStringContainsString('LAPORAN DATA SISWA', $content);
        $this->assertStringContainsString('Daftar induk identitas peserta didik, penempatan kelas rombel, dan status akun', $content);
        $this->assertStringContainsString('Waktu Cetak:', $content);
        $this->assertStringContainsString('Total Siswa:', $content);
        $this->assertStringContainsString('DividerBlue', $content);

        // Check header style with dark blue background #1E3A8A and white text
        $this->assertStringContainsString('ss:Color="#1E3A8A" ss:Pattern="Solid"', $content);
        $this->assertStringContainsString('ss:Color="#FFFFFF"', $content);

        // Check 11 columns in header
        $this->assertStringContainsString('<Data ss:Type="String">No</Data>', $content);
        $this->assertStringContainsString('<Data ss:Type="String">NIS</Data>', $content);
        $this->assertStringContainsString('<Data ss:Type="String">NISN</Data>', $content);
        $this->assertStringContainsString('<Data ss:Type="String">Nama Siswa</Data>', $content);
        $this->assertStringContainsString('<Data ss:Type="String">Email</Data>', $content);
        $this->assertStringContainsString('<Data ss:Type="String">Username</Data>', $content);
        $this->assertStringContainsString('<Data ss:Type="String">Jenis Kelamin</Data>', $content);
        $this->assertStringContainsString('<Data ss:Type="String">Kelas Rombel</Data>', $content);
        $this->assertStringContainsString('<Data ss:Type="String">Status Akun</Data>', $content);
        $this->assertStringContainsString('<Data ss:Type="String">No Telepon</Data>', $content);
        $this->assertStringContainsString('<Data ss:Type="String">Alamat</Data>', $content);

        // Check Freeze Panes and AutoFilter
        $this->assertStringContainsString('<FreezePanes/>', $content);
        $this->assertStringContainsString('<AutoFilter', $content);
    }

    public function test_admin_can_export_students_pdf()
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.students.export', ['format' => 'pdf']));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('data-siswa-eduexam-', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('.pdf', $response->headers->get('Content-Disposition'));
    }

    public function test_admin_can_export_students_filtered_by_search_or_classroom()
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $sampleStudent = User::students()->first();
        if ($sampleStudent) {
            $response = $this->actingAs($admin)->get(route('admin.students.export', [
                'format' => 'csv',
                'search' => $sampleStudent->name
            ]));

            $response->assertStatus(200);
            ob_start();
            $response->sendContent();
            $content = ob_get_clean();

            $this->assertStringContainsString($sampleStudent->name, $content);
        }
    }

    public function test_non_admin_cannot_export_students()
    {
        $student = User::students()->first();
        if ($student) {
            $response = $this->actingAs($student)->get(route('admin.students.export', ['format' => 'csv']));
            $this->assertTrue(in_array($response->status(), [403, 302]));
        }

        $guestResponse = $this->get(route('admin.students.export', ['format' => 'csv']));
        $this->assertTrue(in_array($guestResponse->status(), [403, 302]));
    }
}
