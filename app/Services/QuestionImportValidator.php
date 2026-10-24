<?php

namespace App\Services;

class QuestionImportValidator
{
    /**
     * Header alias map to recognize user variations in Indonesian and English.
     */
    protected array $headerAliases = [
        'no'          => ['no', 'nomor', 'number', '#', 'no.'],
        'type'        => ['jenis soal', 'jenis_soal', 'tipe', 'tipe soal', 'type', 'question_type'],
        'question'    => ['pertanyaan', 'soal', 'isi soal', 'teks soal', 'question', 'question_text'],
        'option_a'    => ['pilihan a', 'opsi a', 'a', 'option a', 'pilihan_a', 'opsi_a'],
        'option_b'    => ['pilihan b', 'opsi b', 'b', 'option b', 'pilihan_b', 'opsi_b'],
        'option_c'    => ['pilihan c', 'opsi c', 'c', 'option c', 'pilihan_c', 'opsi_c'],
        'option_d'    => ['pilihan d', 'opsi d', 'd', 'option d', 'pilihan_d', 'opsi_d'],
        'option_e'    => ['pilihan e', 'opsi e', 'e', 'option e', 'pilihan_e', 'opsi_e'],
        'answer'      => ['kunci jawaban', 'kunci', 'jawaban benar', 'correct_option', 'answer', 'key'],
        'score'       => ['bobot nilai', 'bobot', 'skor', 'nilai', 'score', 'points'],
        'difficulty'  => ['tingkat kesulitan', 'kesulitan', 'level', 'difficulty', 'tingkat'],
        'topic'       => ['topik/materi', 'topik', 'materi', 'bab', 'topic'],
        'explanation' => ['pembahasan', 'penjelasan', 'keterangan', 'explanation'],
    ];

    /**
     * Map raw sheet rows to structured preview data with validation.
     *
     * @param array<int, array<int, string>> $rawRows
     * @return array{
     *    success: bool,
     *    error?: string,
     *    valid_count: int,
     *    invalid_count: int,
     *    total_count: int,
     *    questions: array<int, array{
     *        row_number: int,
     *        is_valid: bool,
     *        errors: array<string>,
     *        type: string,
     *        type_label: string,
     *        question_text: string,
     *        options: array<int, array{label: string, text: string, is_correct: bool}>,
     *        correct_answer: string,
     *        score: float,
     *        difficulty: string,
     *        difficulty_label: string,
     *        topic: ?string,
     *        explanation: ?string
     *    }>
     * }
     */
    public function validateAndMap(array $rawRows): array
    {
        if (empty($rawRows)) {
            return [
                'success'       => false,
                'error'         => 'Berkas yang diunggah kosong atau tidak memiliki data.',
                'valid_count'   => 0,
                'invalid_count' => 0,
                'total_count'   => 0,
                'questions'     => [],
            ];
        }

        // 1. Identifikasi Baris Header
        $headerIndex = -1;
        $headerMap = [];

        foreach ($rawRows as $idx => $row) {
            $matched = $this->matchHeaderRow($row);
            if (isset($matched['question'])) {
                $headerIndex = $idx;
                $headerMap = $matched;
                break;
            }
        }

        if ($headerIndex === -1 || !isset($headerMap['question'])) {
            return [
                'success'       => false,
                'error'         => 'Header berkas tidak sesuai. Pastikan berkas memiliki kolom "Pertanyaan" / "Soal". Unduh template resmi jika ragu.',
                'valid_count'   => 0,
                'invalid_count' => 0,
                'total_count'   => 0,
                'questions'     => [],
            ];
        }

        $parsedQuestions = [];
        $validCount = 0;
        $invalidCount = 0;

        // 2. Loop Baris Data
        for ($i = $headerIndex + 1; $i < count($rawRows); $i++) {
            $row = $rawRows[$i];
            $rowNumber = $i + 1; // 1-indexed (sesuai nomor baris spreadsheet)

            // Cek jika seluruh baris kosong
            $allEmpty = true;
            foreach ($row as $cell) {
                if (trim($cell) !== '') {
                    $allEmpty = false;
                    break;
                }
            }
            if ($allEmpty) {
                continue;
            }

            $questionText = $this->getCellValue($row, $headerMap, 'question');
            $rawType      = $this->getCellValue($row, $headerMap, 'type');
            $optA         = $this->getCellValue($row, $headerMap, 'option_a');
            $optB         = $this->getCellValue($row, $headerMap, 'option_b');
            $optC         = $this->getCellValue($row, $headerMap, 'option_c');
            $optD         = $this->getCellValue($row, $headerMap, 'option_d');
            $optE         = $this->getCellValue($row, $headerMap, 'option_e');
            $rawAnswer    = $this->getCellValue($row, $headerMap, 'answer');
            $rawScore     = $this->getCellValue($row, $headerMap, 'score');
            $rawDiff      = $this->getCellValue($row, $headerMap, 'difficulty');
            $topic        = $this->getCellValue($row, $headerMap, 'topic');
            $explanation  = $this->getCellValue($row, $headerMap, 'explanation');

            $errors = [];

            // Validasi Pertanyaan
            if (empty($questionText)) {
                $errors[] = 'Teks pertanyaan wajib diisi.';
            }

            // Normalisasi & Validasi Tipe Soal
            $type = $this->normalizeType($rawType, $optA, $optB);
            if (!in_array($type, ['multiple_choice', 'true_false', 'short_answer', 'essay'])) {
                $errors[] = "Jenis soal '{$rawType}' tidak dikenali. Pilih: multiple_choice, true_false, short_answer, atau essay.";
            }

            // Normalisasi Bobot Nilai
            $score = 1.0;
            if ($rawScore !== '') {
                $cleanScore = str_replace(',', '.', $rawScore);
                if (!is_numeric($cleanScore) || floatval($cleanScore) < 0) {
                    $errors[] = "Bobot nilai harus berupa angka positif (diisi: '{$rawScore}').";
                } else {
                    $score = round(floatval($cleanScore), 2);
                }
            }

            // Normalisasi Tingkat Kesulitan
            $difficulty = $this->normalizeDifficulty($rawDiff);

            // Validasi Opsi dan Kunci Jawaban berdasarkan Jenis Soal
            $options = [];
            $correctKey = trim($rawAnswer);

            if ($type === 'multiple_choice') {
                $rawOptions = [
                    'A' => $optA,
                    'B' => $optB,
                    'C' => $optC,
                    'D' => $optD,
                    'E' => $optE,
                ];

                // Kumpulkan opsi yang tidak kosong
                $collectedOptions = [];
                foreach ($rawOptions as $lbl => $text) {
                    if ($text !== '') {
                        $collectedOptions[$lbl] = $text;
                    }
                }

                if (count($collectedOptions) < 2) {
                    $errors[] = 'Pilihan ganda minimal memiliki 2 opsi pilihan (misal: Pilihan A dan B).';
                }

                // Normalisasi kunci jawaban: huruf A, B, C, D, E atau teks opsi
                $normalizedKey = strtoupper($correctKey);
                if (empty($normalizedKey)) {
                    $errors[] = 'Kunci jawaban untuk pilihan ganda wajib diisi (misal: A, B, C, dst).';
                } elseif (!isset($collectedOptions[$normalizedKey])) {
                    // Cek jika user mengisi teks opsi secara langsung alih-alih label
                    $foundByText = false;
                    foreach ($collectedOptions as $lbl => $text) {
                        if (strcasecmp($text, $correctKey) === 0) {
                            $normalizedKey = $lbl;
                            $foundByText = true;
                            break;
                        }
                    }
                    if (!$foundByText) {
                        $errors[] = "Kunci jawaban '{$correctKey}' tidak cocok dengan opsi yang tersedia (" . implode(', ', array_keys($collectedOptions)) . ").";
                    }
                }

                $sortOrder = 0;
                foreach ($collectedOptions as $lbl => $text) {
                    $options[] = [
                        'label'      => $lbl,
                        'text'       => $text,
                        'is_correct' => ($lbl === $normalizedKey),
                        'sort_order' => $sortOrder++,
                    ];
                }
                $correctKey = $normalizedKey;

            } elseif ($type === 'true_false') {
                // Standar Benar / Salah
                $labelA = !empty($optA) ? $optA : 'Benar';
                $labelB = !empty($optB) ? $optB : 'Salah';

                $normalizedAnswer = strtoupper($correctKey);
                $isCorrectA = false;
                $isCorrectB = false;

                // Cek kesesuaian: jika A atau cocok dengan teks labelA / Benar / True / 1
                if ($normalizedAnswer === 'A' || strcasecmp($correctKey, $labelA) === 0 || in_array($normalizedAnswer, ['BENAR', 'TRUE', '1'])) {
                    $isCorrectA = true;
                    $correctKey = $labelA . ' (A)';
                } elseif ($normalizedAnswer === 'B' || strcasecmp($correctKey, $labelB) === 0 || in_array($normalizedAnswer, ['SALAH', 'FALSE', '0', 'S'])) {
                    $isCorrectB = true;
                    $correctKey = $labelB . ' (B)';
                } else {
                    $errors[] = "Kunci jawaban benar/salah harus 'Benar'/'A' atau 'Salah'/'B' (diisi: '{$rawAnswer}').";
                }

                $options = [
                    ['label' => 'A', 'text' => $labelA, 'is_correct' => $isCorrectA, 'sort_order' => 0],
                    ['label' => 'B', 'text' => $labelB, 'is_correct' => $isCorrectB, 'sort_order' => 1],
                ];

            } elseif ($type === 'short_answer') {
                // Isian singkat: kunci jawaban diisi di kolom Kunci Jawaban
                if (empty($correctKey)) {
                    $errors[] = 'Kunci jawaban untuk isian singkat wajib diisi.';
                }
            } elseif ($type === 'essay') {
                // Esai: kunci jawaban opsional (bisa berupa rubrik / pembahasan)
            }

            $isValid = empty($errors);
            if ($isValid) {
                $validCount++;
            } else {
                $invalidCount++;
            }

            $parsedQuestions[] = [
                'row_number'       => $rowNumber,
                'is_valid'         => $isValid,
                'errors'           => $errors,
                'type'             => $type,
                'type_label'       => $this->getTypeLabel($type),
                'question_text'    => $questionText,
                'options'          => $options,
                'correct_answer'   => $correctKey,
                'score'            => $score,
                'difficulty'       => $difficulty,
                'difficulty_label' => $this->getDifficultyLabel($difficulty),
                'topic'            => !empty($topic) ? $topic : null,
                'explanation'      => !empty($explanation) ? $explanation : null,
            ];
        }

        return [
            'success'       => true,
            'valid_count'   => $validCount,
            'invalid_count' => $invalidCount,
            'total_count'   => count($parsedQuestions),
            'questions'     => $parsedQuestions,
        ];
    }

    /**
     * Cocokkan baris header dengan alias kolom.
     */
    protected function matchHeaderRow(array $row): array
    {
        $map = [];
        foreach ($row as $colIdx => $rawHeader) {
            $clean = strtolower(trim((string)$rawHeader));
            if ($clean === '') continue;

            foreach ($this->headerAliases as $key => $aliases) {
                if (!isset($map[$key]) && in_array($clean, $aliases, true)) {
                    $map[$key] = $colIdx;
                    break;
                }
            }
        }
        return $map;
    }

    protected function getCellValue(array $row, array $headerMap, string $key): string
    {
        if (!isset($headerMap[$key])) {
            return '';
        }
        $colIdx = $headerMap[$key];
        return isset($row[$colIdx]) ? trim((string)$row[$colIdx]) : '';
    }

    protected function normalizeType(string $rawType, string $optA, string $optB): string
    {
        $clean = strtolower(trim($rawType));
        if (empty($clean)) {
            // Tebak otomatis: jika ada opsi A & B, kemungkinan pilihan ganda
            if (!empty($optA) || !empty($optB)) {
                return 'multiple_choice';
            }
            return 'essay';
        }

        if (str_contains($clean, 'pilihan') || str_contains($clean, 'ganda') || str_contains($clean, 'pg') || $clean === 'multiple_choice') {
            return 'multiple_choice';
        }
        if (str_contains($clean, 'benar') || str_contains($clean, 'salah') || str_contains($clean, 'b/s') || $clean === 'true_false') {
            return 'true_false';
        }
        if (str_contains($clean, 'isian') || str_contains($clean, 'singkat') || $clean === 'short_answer') {
            return 'short_answer';
        }
        if (str_contains($clean, 'esai') || str_contains($clean, 'essay') || str_contains($clean, 'uraian')) {
            return 'essay';
        }

        return $clean;
    }

    protected function normalizeDifficulty(string $rawDiff): string
    {
        $clean = strtolower(trim($rawDiff));
        if (in_array($clean, ['easy', 'mudah', 'gampang', 'rendah', '1'])) {
            return 'easy';
        }
        if (in_array($clean, ['hard', 'sulit', 'sukar', 'tinggi', '3'])) {
            return 'hard';
        }
        return 'medium'; // default sedang
    }

    protected function getTypeLabel(string $type): string
    {
        return match($type) {
            'multiple_choice' => 'Pilihan Ganda',
            'true_false'      => 'Benar/Salah',
            'short_answer'    => 'Isian Singkat',
            'essay'           => 'Esai / Uraian',
            default           => ucfirst($type),
        };
    }

    protected function getDifficultyLabel(string $difficulty): string
    {
        return match($difficulty) {
            'easy'   => 'Mudah',
            'medium' => 'Sedang',
            'hard'   => 'Sulit',
            default  => ucfirst($difficulty),
        };
    }
}
