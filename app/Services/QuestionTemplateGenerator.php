<?php

namespace App\Services;

use ZipArchive;

class QuestionTemplateGenerator
{
    /**
     * Generate standard CSV template for Question import.
     */
    public function generateCsv(): string
    {
        $rows = $this->getTemplateRows();

        $output = "\xEF\xBB\xBF"; // UTF-8 BOM
        $stream = fopen('php://temp', 'r+');
        foreach ($rows as $row) {
            fputcsv($stream, $row, ',', '"', "\\");
        }
        rewind($stream);
        $output .= stream_get_contents($stream);
        fclose($stream);

        return $output;
    }

    /**
     * Generate standard OpenXML XLSX template.
     */
    public function generateXlsx(): string
    {
        $rows = $this->getTemplateRows();

        // Siapkan zip binary di memory / temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_tpl_');
        $zip = new ZipArchive();
        $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // 1. [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // 2. _rels/.rels
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';
        $zip->addFromString('_rels/.rels', $rels);

        // 3. xl/_rels/workbook.xml.rels
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);

        // 4. xl/workbook.xml
        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets>'
            . '<sheet name="Template Soal" sheetId="1" r:id="rId1"/>'
            . '</sheets>'
            . '</workbook>';
        $zip->addFromString('xl/workbook.xml', $workbook);

        // 5. xl/styles.xml
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="2">'
            . '<font><sz val="11"/><name val="Calibri"/></font>'
            . '<font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>'
            . '</fonts>'
            . '<fills count="3">'
            . '<fill><patternFill patternType="none"/></fill>'
            . '<fill><patternFill patternType="gray125"/></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FF1E3A8A"/></patternFill></fill>'
            . '</fills>'
            . '<borders count="1"><border><left/><right/><top/><bottom/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="2">'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            . '<xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1"/>'
            . '</cellXfs>'
            . '</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styles);

        // 6. xl/worksheets/sheet1.xml
        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<cols>'
            . '<col min="1" max="1" width="16" customWidth="1"/>' // No
            . '<col min="2" max="2" width="18" customWidth="1"/>' // Tipe Soal
            . '<col min="3" max="3" width="45" customWidth="1"/>' // Pertanyaan
            . '<col min="4" max="4" width="25" customWidth="1"/>' // Opsi A
            . '<col min="5" max="5" width="25" customWidth="1"/>' // Opsi B
            . '<col min="6" max="6" width="25" customWidth="1"/>' // Opsi C
            . '<col min="7" max="7" width="25" customWidth="1"/>' // Opsi D
            . '<col min="8" max="8" width="25" customWidth="1"/>' // Opsi E
            . '<col min="9" max="9" width="16" customWidth="1"/>' // Kunci
            . '<col min="10" max="10" width="12" customWidth="1"/>' // Bobot
            . '<col min="11" max="11" width="14" customWidth="1"/>' // Tingkat
            . '<col min="12" max="12" width="22" customWidth="1"/>' // Topik
            . '<col min="13" max="13" width="35" customWidth="1"/>' // Pembahasan
            . '</cols>'
            . '<sheetData>';

        foreach ($rows as $rowIndex => $rowCells) {
            $rNum = $rowIndex + 1;
            $styleAttr = ($rowIndex === 0) ? ' s="1"' : '';
            $sheetXml .= '<row r="' . $rNum . '">';

            foreach ($rowCells as $colIndex => $val) {
                $colLetter = $this->indexToColumnLetter($colIndex);
                $cellRef = $colLetter . $rNum;
                $escaped = htmlspecialchars((string)$val, ENT_XML1, 'UTF-8');
                $sheetXml .= '<c r="' . $cellRef . '" t="inlineStr"' . $styleAttr . '><is><t>' . $escaped . '</t></is></c>';
            }

            $sheetXml .= '</row>';
        }

        $sheetXml .= '</sheetData></worksheet>';
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);

        $zip->close();
        $content = file_get_contents($tempFile);
        @unlink($tempFile);

        return $content;
    }

    /**
     * Konversi index 0-based ke huruf kolom Excel (0 => A, 1 => B, dst).
     */
    private function indexToColumnLetter(int $index): string
    {
        $col = '';
        $index++;
        while ($index > 0) {
            $remainder = ($index - 1) % 26;
            $col = chr(65 + $remainder) . $col;
            $index = intval(($index - $remainder) / 26);
        }
        return $col;
    }

    /**
     * Data baris template standar EduExam.
     */
    public function getTemplateRows(): array
    {
        return [
            // Header kolom
            [
                'No',
                'Jenis Soal',
                'Pertanyaan',
                'Pilihan A',
                'Pilihan B',
                'Pilihan C',
                'Pilihan D',
                'Pilihan E',
                'Kunci Jawaban',
                'Bobot Nilai',
                'Tingkat Kesulitan',
                'Topik/Materi',
                'Pembahasan',
            ],
            // Contoh 1: Pilihan Ganda 5 opsi
            [
                '1',
                'multiple_choice',
                'Organel sel tumbuhan yang berfungsi sebagai tempat fotosintesis adalah...',
                'Mitokondria',
                'Kloroplas',
                'Ribosom',
                'Badan Golgi',
                'Retikulum Endoplasma',
                'B',
                '2.5',
                'medium',
                'Biologi Sel',
                'Kloroplas mengandung klorofil untuk menangkap energi cahaya matahari.',
            ],
            // Contoh 2: Pilihan Ganda 4 opsi
            [
                '2',
                'multiple_choice',
                'Berikut yang termasuk besaran pokok dalam Sistem Internasional (SI) adalah...',
                'Kecepatan',
                'Massa jenis',
                'Panjang',
                'Gaya',
                '',
                'C',
                '2.5',
                'easy',
                'Besaran dan Satuan',
                'Panjang adalah besaran pokok dengan satuan meter (m).',
            ],
            // Contoh 3: Benar / Salah
            [
                '3',
                'true_false',
                'Matahari merupakan pusat dari tata surya dalam galaksi Bima Sakti.',
                'Benar',
                'Salah',
                '',
                '',
                '',
                'Benar',
                '2.0',
                'easy',
                'Tata Surya',
                'Model heliosentris menempatkan Matahari di pusat tata surya.',
            ],
            // Contoh 4: Isian Singkat
            [
                '4',
                'short_answer',
                'Ibukota negara Indonesia yang baru di Kalimantan Timur bernama...',
                '',
                '',
                '',
                '',
                '',
                'Nusantara',
                '5.0',
                'easy',
                'Geografi Nasional',
                'Ibu Kota Nusantara (IKN) terletak di Kalimantan Timur.',
            ],
            // Contoh 5: Esai
            [
                '5',
                'essay',
                'Jelaskan siklus air (hidrologi) secara singkat beserta tahap-tahap utamanya!',
                '',
                '',
                '',
                '',
                '',
                '',
                '10.0',
                'hard',
                'Siklus Hidrologi',
                'Tahap utama meliputi evaporasi/transpirasi, kondensasi, presipitasi, dan infiltrasi.',
            ],
        ];
    }
}
