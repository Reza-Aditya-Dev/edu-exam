<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Subject;
use App\Models\SchoolSetting;

class TeacherExportTest extends TestCase
{
    public function test_admin_can_export_teachers_csv()
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.teachers.export', ['format' => 'csv']));

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('laporan_data_guru_', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('.csv', $response->headers->get('Content-Disposition'));

        // Capture CSV output
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        // Check UTF-8 BOM
        $this->assertTrue(str_starts_with($content, "\xEF\xBB\xBF"));

        // Check Excel separator directive
        $this->assertStringContainsString("sep=,\n", $content);

        // Check report title and metadata
        $this->assertStringContainsString('"LAPORAN DATA GURU"', $content);
        $this->assertStringContainsString('Sekolah', $content);
        $this->assertStringContainsString('"Tanggal Ekspor"', $content);

        // Check header columns
        $this->assertStringContainsString('No,NIP/NUPTK,"Nama Guru","Jenis Kelamin","Mata Pelajaran Diampu","Wali Kelas","Ujian Dibuat",Email,Username,"Status Akun","No Telepon",Alamat', $content);

        // Clean up BOM and sep directive for parsing
        $cleanContent = str_replace(["\xEF\xBB\xBF", "sep=,\r\n", "sep=,\n"], '', $content);
        $allLines = array_values(array_filter(explode("\n", trim($cleanContent))));

        // Find table header row
        $headerIndex = -1;
        foreach ($allLines as $idx => $l) {
            $parsed = str_getcsv($l);
            if (isset($parsed[0]) && $parsed[0] === 'No' && isset($parsed[1]) && $parsed[1] === 'NIP/NUPTK') {
                $headerIndex = $idx;
                break;
            }
        }

        $this->assertNotEquals(-1, $headerIndex, 'Header row with 12 columns must exist in CSV');
        $header = str_getcsv($allLines[$headerIndex]);
        $this->assertCount(12, $header);
        $this->assertEquals(['No', 'NIP/NUPTK', 'Nama Guru', 'Jenis Kelamin', 'Mata Pelajaran Diampu', 'Wali Kelas', 'Ujian Dibuat', 'Email', 'Username', 'Status Akun', 'No Telepon', 'Alamat'], $header);

        // Data lines after header
        $dataLines = array_slice($allLines, $headerIndex + 1);
        $expectedCount = User::teachers()->count();
        $this->assertCount($expectedCount, $dataLines);

        foreach ($dataLines as $line) {
            $row = str_getcsv($line);
            $this->assertCount(12, $row);
        }

        // Verify sample teacher exists
        $sampleTeacher = User::teachers()->first();
        if ($sampleTeacher) {
            $this->assertStringContainsString($sampleTeacher->name, $content);
            if ($sampleTeacher->nip) {
                $this->assertStringContainsString($sampleTeacher->nip, $content);
            }
        }
    }

    public function test_admin_can_export_teachers_excel()
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.teachers.export', ['format' => 'excel']));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/vnd.ms-excel', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('laporan_data_guru_', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('.xls', $response->headers->get('Content-Disposition'));

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        // Check SpreadsheetML XML structure
        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $content);
        $this->assertStringContainsString('<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"', $content);
        $this->assertStringContainsString('ss:Name="Data Guru"', $content);
        $this->assertStringContainsString('LAPORAN DATA GURU', $content);
        $this->assertStringContainsString('DATABASE GURU CBT', $content);
        $this->assertStringContainsString('ss:ID="ThCenter"', $content);
        $this->assertStringContainsString('<AutoFilter', $content);
        $this->assertStringContainsString('<FreezePanes/>', $content);
        $this->assertStringContainsString('Orientation="Landscape"', $content);
        $this->assertStringContainsString('<FitWidth>1</FitWidth>', $content);
    }

    public function test_admin_can_export_teachers_pdf_with_logo()
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.teachers.export', ['format' => 'pdf']));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('laporan-data-guru-eduexam-', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('.pdf', $response->headers->get('Content-Disposition'));

        $pdfContent = $response->getContent();
        // Check PDF markers and image/xobject presence
        $this->assertStringStartsWith('%PDF-', $pdfContent);
        $this->assertStringContainsString('/Image', $pdfContent, 'PDF must contain embedded school logo image');
        $this->assertStringContainsString('/XObject', $pdfContent, 'PDF must contain XObject dictionary');
        // Landscape check: MediaBox 841.89 pt (width) x 595.28 pt (height) for A4 Landscape
        $this->assertStringContainsString('841.89', $pdfContent, 'PDF page width must match A4 landscape');
    }

    public function test_admin_can_export_teachers_filtered_by_search_or_subject()
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);
        $sampleTeacher = User::teachers()->whereNotNull('nip')->first();

        if ($sampleTeacher) {
            $response = $this->actingAs($admin)->get(route('admin.teachers.export', [
                'format' => 'csv',
                'search' => $sampleTeacher->nip
            ]));

            $response->assertStatus(200);

            ob_start();
            $response->sendContent();
            $content = ob_get_clean();

            $cleanContent = str_replace(["\xEF\xBB\xBF", "sep=,\r\n", "sep=,\n"], '', $content);
            $lines = array_filter(explode("\n", trim($cleanContent)));
            $header = array_shift($lines);

            $this->assertGreaterThanOrEqual(1, count($lines));
            $this->assertStringContainsString($sampleTeacher->nip, $content);
        }
    }

    public function test_non_admin_cannot_export_teachers()
    {
        $teacher = User::teachers()->first() ?? User::factory()->create(['role' => 'teacher']);
        if ($teacher) {
            $response = $this->actingAs($teacher)->get(route('admin.teachers.export', ['format' => 'csv']));
            $this->assertTrue(in_array($response->status(), [403, 302]));
        }

        $guestResponse = $this->get(route('admin.teachers.export', ['format' => 'csv']));
        $this->assertTrue(in_array($guestResponse->status(), [403, 302]));
    }
}
