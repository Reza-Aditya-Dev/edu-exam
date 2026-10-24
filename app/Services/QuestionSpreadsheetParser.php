<?php

namespace App\Services;

use ZipArchive;
use SimpleXMLElement;
use Exception;

class QuestionSpreadsheetParser
{
    /**
     * Parse uploaded file (xlsx or csv) into rows of string arrays.
     *
     * @param string $filePath
     * @param string $extension
     * @return array<int, array<int, string>>
     */
    public function parseFile(string $filePath, string $extension): array
    {
        $ext = strtolower($extension);

        if ($ext === 'csv' || $ext === 'txt') {
            return $this->parseCsv($filePath);
        }

        if ($ext === 'xlsx') {
            return $this->parseXlsx($filePath);
        }

        throw new Exception("Format berkas tidak didukung. Harap unggah file .xlsx atau .csv.");
    }

    /**
     * Parse CSV file with auto delimiter detection and BOM removal.
     */
    public function parseCsv(string $filePath): array
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new Exception("Berkas CSV tidak ditemukan atau tidak dapat dibaca.");
        }

        $content = file_get_contents($filePath);
        if ($content === false || strlen(trim($content)) === 0) {
            return [];
        }

        // Hapus UTF-8 BOM jika ada
        if (str_starts_with($content, "\xEF\xBB\xBF")) {
            $content = substr($content, 3);
        }

        // Hapus directive "sep=," atau "sep=;" khas Excel jika ada di baris pertama
        $lines = preg_split('/\r\n|\r|\n/', $content);
        if (!empty($lines) && preg_match('/^sep=([;,|\t])/i', trim($lines[0]), $sepMatch)) {
            array_shift($lines);
            $content = implode("\n", $lines);
        }

        // Deteksi delimiter: koma, titik koma, tab, atau pipe
        $firstLine = '';
        foreach (explode("\n", $content) as $line) {
            if (trim($line) !== '') {
                $firstLine = $line;
                break;
            }
        }

        $delimiters = [',', ';', "\t", '|'];
        $bestDelimiter = ',';
        $maxCount = 0;
        foreach ($delimiters as $delim) {
            $count = substr_count($firstLine, $delim);
            if ($count > $maxCount) {
                $maxCount = $count;
                $bestDelimiter = $delim;
            }
        }

        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $content);
        rewind($stream);

        $rows = [];
        while (($row = fgetcsv($stream, 0, $bestDelimiter)) !== false) {
            // Trim setiap cell
            $cleanRow = array_map(function ($val) {
                return is_string($val) ? trim($val) : (string)$val;
            }, $row);

            // Lewati jika seluruh kolom dalam baris kosong
            $allEmpty = true;
            foreach ($cleanRow as $cell) {
                if ($cell !== '') {
                    $allEmpty = false;
                    break;
                }
            }
            if (!$allEmpty) {
                $rows[] = $cleanRow;
            }
        }
        fclose($stream);

        return $rows;
    }

    /**
     * Parse modern XLSX file (OpenXML) natively using ZipArchive + SimpleXML.
     */
    public function parseXlsx(string $filePath): array
    {
        if (!class_exists('ZipArchive')) {
            throw new Exception("Ekstensi PHP ZipArchive tidak aktif pada server.");
        }

        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new Exception("Tidak dapat membuka file Excel (.xlsx). Pastikan berkas valid dan tidak rusak.");
        }

        // 1. Baca shared strings jika ada
        $sharedStrings = [];
        $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedStringsXml !== false) {
            $xmlObj = @simplexml_load_string($sharedStringsXml);
            if ($xmlObj && isset($xmlObj->si)) {
                foreach ($xmlObj->si as $si) {
                    $text = '';
                    if (isset($si->t)) {
                        $text = (string)$si->t;
                    } elseif (isset($si->r)) {
                        foreach ($si->r as $run) {
                            $text .= (string)$run->t;
                        }
                    }
                    $sharedStrings[] = $text;
                }
            }
        }

        // 2. Baca worksheet pertama (default: xl/worksheets/sheet1.xml)
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        if ($sheetXml === false) {
            // Coba cari sheet pertama dari relasi atau nama lain
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                if (preg_match('#^xl/worksheets/sheet[0-9]+\.xml$#i', $stat['name'])) {
                    $sheetXml = $zip->getFromIndex($i);
                    break;
                }
            }
        }

        $zip->close();

        if ($sheetXml === false) {
            throw new Exception("Lembar kerja (sheet) tidak ditemukan di dalam berkas Excel.");
        }

        $sheet = @simplexml_load_string($sheetXml);
        if (!$sheet || !isset($sheet->sheetData)) {
            return [];
        }

        $rows = [];
        foreach ($sheet->sheetData->row as $rowNode) {
            $rowCells = [];
            $maxColIdx = 0;

            foreach ($rowNode->c as $cell) {
                $cellRef = (string)$cell['r']; // misal "A1", "C2"
                $colLetters = preg_replace('/[0-9]/', '', $cellRef);
                $colIndex = $this->columnLetterToIndex($colLetters);

                $cellType = (string)$cell['t'];
                $val = '';

                if ($cellType === 's') {
                    // Shared string index
                    $sIndex = (int)(string)$cell->v;
                    $val = $sharedStrings[$sIndex] ?? '';
                } elseif ($cellType === 'inlineStr' && isset($cell->is->t)) {
                    $val = (string)$cell->is->t;
                } elseif (isset($cell->v)) {
                    $val = (string)$cell->v;
                }

                $rowCells[$colIndex] = trim($val);
                if ($colIndex > $maxColIdx) {
                    $maxColIdx = $colIndex;
                }
            }

            // Normalisasi array agar berurutan dari kolom 0 hingga maxColIdx
            $indexedRow = [];
            for ($i = 0; $i <= $maxColIdx; $i++) {
                $indexedRow[$i] = $rowCells[$i] ?? '';
            }

            // Lewati baris jika seluruhnya kosong
            $allEmpty = true;
            foreach ($indexedRow as $cell) {
                if ($cell !== '') {
                    $allEmpty = false;
                    break;
                }
            }
            if (!$allEmpty) {
                $rows[] = $indexedRow;
            }
        }

        return $rows;
    }

    /**
     * Konversi huruf kolom Excel (A, B, ..., Z, AA, AB) menjadi index 0-based.
     */
    private function columnLetterToIndex(string $letters): int
    {
        $letters = strtoupper(trim($letters));
        $index = 0;
        $len = strlen($letters);
        for ($i = 0; $i < $len; $i++) {
            $index = $index * 26 + (ord($letters[$i]) - 64);
        }
        return max(0, $index - 1);
    }
}
