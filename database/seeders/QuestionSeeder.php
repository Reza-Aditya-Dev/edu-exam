<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\User;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::where('role', 'teacher')->where('username', 'guru')->first() ?? User::where('role', 'teacher')->first();
        if (!$teacher) return;

        $questionsData = array (
  0 => 
  array (
    'subject_code' => 'IPA',
    'classroom_name' => NULL,
    'type' => 'multiple_choice',
    'question_text' => 'apa yg di maksud dengan  alam',
    'question_image' => NULL,
    'topic' => NULL,
    'difficulty' => 'medium',
    'score' => 10,
    'explanation' => 'harus di isi',
    'options' => 
    array (
      0 => 
      array (
        'label' => 'A',
        'text' => 'ya alam semesta',
        'is_correct' => true,
      ),
      1 => 
      array (
        'label' => 'B',
        'text' => 'apa saja',
        'is_correct' => false,
      ),
      2 => 
      array (
        'label' => 'C',
        'text' => 'ya begitu la',
        'is_correct' => false,
      ),
      3 => 
      array (
        'label' => 'D',
        'text' => 'emang iya',
        'is_correct' => false,
      ),
      4 => 
      array (
        'label' => 'E',
        'text' => 'emang itu',
        'is_correct' => false,
      ),
    ),
  ),
  1 => 
  array (
    'subject_code' => 'IPA',
    'classroom_name' => NULL,
    'type' => 'multiple_choice',
    'question_text' => 'ya begitu la',
    'question_image' => NULL,
    'topic' => NULL,
    'difficulty' => 'medium',
    'score' => 10,
    'explanation' => NULL,
    'options' => 
    array (
      0 => 
      array (
        'label' => 'A',
        'text' => 'bebek',
        'is_correct' => true,
      ),
      1 => 
      array (
        'label' => 'B',
        'text' => 'apa saja',
        'is_correct' => false,
      ),
      2 => 
      array (
        'label' => 'C',
        'text' => 'ya begitu la',
        'is_correct' => false,
      ),
      3 => 
      array (
        'label' => 'D',
        'text' => 'emang iya',
        'is_correct' => false,
      ),
      4 => 
      array (
        'label' => 'E',
        'text' => 'emang itu',
        'is_correct' => false,
      ),
    ),
  ),
  2 => 
  array (
    'subject_code' => 'IPA',
    'classroom_name' => NULL,
    'type' => 'multiple_choice',
    'question_text' => 'ayam bebek',
    'question_image' => 'questions/DXgHgu5x8Z8Y7STQmAAg1smZ9sx0AxIfFHGd4SBh.jpg',
    'topic' => NULL,
    'difficulty' => 'medium',
    'score' => 10,
    'explanation' => NULL,
    'options' => 
    array (
      0 => 
      array (
        'label' => 'A',
        'text' => 'ayam',
        'is_correct' => true,
      ),
      1 => 
      array (
        'label' => 'B',
        'text' => 'anjing',
        'is_correct' => false,
      ),
      2 => 
      array (
        'label' => 'C',
        'text' => 'babi',
        'is_correct' => false,
      ),
      3 => 
      array (
        'label' => 'D',
        'text' => 'yaaa',
        'is_correct' => false,
      ),
      4 => 
      array (
        'label' => 'E',
        'text' => 'apa saja',
        'is_correct' => false,
      ),
    ),
  ),
  3 => 
  array (
    'subject_code' => 'MTK',
    'classroom_name' => 'X IPA 1',
    'type' => 'multiple_choice',
    'question_text' => 'Operasi Aljabar

Hasil dari (3x + 5x - 7) adalah ....',
    'question_image' => NULL,
    'topic' => 'SOAL MATEMATIKA KELAS 10 SMA',
    'difficulty' => 'medium',
    'score' => 2.5,
    'explanation' => NULL,
    'options' => 
    array (
      0 => 
      array (
        'label' => 'A',
        'text' => 'A. (8x - 7)',
        'is_correct' => false,
      ),
      1 => 
      array (
        'label' => 'B',
        'text' => 'B. (8x + 7)',
        'is_correct' => true,
      ),
      2 => 
      array (
        'label' => 'C',
        'text' => 'C. (2x - 7)',
        'is_correct' => false,
      ),
      3 => 
      array (
        'label' => 'D',
        'text' => 'D. (2x + 7)',
        'is_correct' => false,
      ),
      4 => 
      array (
        'label' => 'E',
        'text' => 'E. (15x - 7)',
        'is_correct' => false,
      ),
    ),
  ),
  4 => 
  array (
    'subject_code' => 'MTK',
    'classroom_name' => 'X IPA 1',
    'type' => 'multiple_choice',
    'question_text' => 'Persamaan Linear

Nilai (x) yang memenuhi persamaan (3x + 6 = 21) adalah ....',
    'question_image' => NULL,
    'topic' => 'SOAL MATEMATIKA KELAS 10 SMA',
    'difficulty' => 'medium',
    'score' => 2.5,
    'explanation' => NULL,
    'options' => 
    array (
      0 => 
      array (
        'label' => 'A',
        'text' => 'A. 3',
        'is_correct' => true,
      ),
      1 => 
      array (
        'label' => 'B',
        'text' => 'B. 4',
        'is_correct' => false,
      ),
      2 => 
      array (
        'label' => 'C',
        'text' => 'C. 5',
        'is_correct' => false,
      ),
      3 => 
      array (
        'label' => 'D',
        'text' => 'D. 6',
        'is_correct' => false,
      ),
      4 => 
      array (
        'label' => 'E',
        'text' => 'E. 7',
        'is_correct' => false,
      ),
    ),
  ),
  5 => 
  array (
    'subject_code' => 'MTK',
    'classroom_name' => 'X IPA 1',
    'type' => 'multiple_choice',
    'question_text' => 'Persamaan Kuadrat

Akar-akar persamaan (x^2 - 5x + 6 = 0) adalah ....',
    'question_image' => NULL,
    'topic' => 'SOAL MATEMATIKA KELAS 10 SMA',
    'difficulty' => 'medium',
    'score' => 2.5,
    'explanation' => NULL,
    'options' => 
    array (
      0 => 
      array (
        'label' => 'A',
        'text' => 'A. 1 dan 6',
        'is_correct' => true,
      ),
      1 => 
      array (
        'label' => 'B',
        'text' => 'B. 2 dan 3',
        'is_correct' => false,
      ),
      2 => 
      array (
        'label' => 'C',
        'text' => 'C. -2 dan -3',
        'is_correct' => false,
      ),
      3 => 
      array (
        'label' => 'D',
        'text' => 'D. 2 dan -3',
        'is_correct' => false,
      ),
      4 => 
      array (
        'label' => 'E',
        'text' => 'E. -2 dan 3',
        'is_correct' => false,
      ),
    ),
  ),
  6 => 
  array (
    'subject_code' => 'MTK',
    'classroom_name' => 'X IPA 1',
    'type' => 'multiple_choice',
    'question_text' => 'Faktorisasi

Bentuk faktor dari (x^2 + 7x + 12) adalah ....',
    'question_image' => NULL,
    'topic' => 'SOAL MATEMATIKA KELAS 10 SMA',
    'difficulty' => 'medium',
    'score' => 2.5,
    'explanation' => NULL,
    'options' => 
    array (
      0 => 
      array (
        'label' => 'A',
        'text' => 'A. ((x+2)(x+6))',
        'is_correct' => true,
      ),
      1 => 
      array (
        'label' => 'B',
        'text' => 'B. ((x+3)(x+4))',
        'is_correct' => false,
      ),
      2 => 
      array (
        'label' => 'C',
        'text' => 'C. ((x-3)(x-4))',
        'is_correct' => false,
      ),
      3 => 
      array (
        'label' => 'D',
        'text' => 'D. ((x+1)(x+12))',
        'is_correct' => false,
      ),
      4 => 
      array (
        'label' => 'E',
        'text' => 'E. ((x-2)(x-6))',
        'is_correct' => false,
      ),
    ),
  ),
  7 => 
  array (
    'subject_code' => 'MTK',
    'classroom_name' => 'X IPA 1',
    'type' => 'multiple_choice',
    'question_text' => 'Eksponen

Hasil dari (2^3 \\times 2^4) adalah ....',
    'question_image' => NULL,
    'topic' => 'SOAL MATEMATIKA KELAS 10 SMA',
    'difficulty' => 'medium',
    'score' => 2.5,
    'explanation' => NULL,
    'options' => 
    array (
      0 => 
      array (
        'label' => 'A',
        'text' => 'A. 16',
        'is_correct' => true,
      ),
      1 => 
      array (
        'label' => 'B',
        'text' => 'B. 32',
        'is_correct' => false,
      ),
      2 => 
      array (
        'label' => 'C',
        'text' => 'C. 64',
        'is_correct' => false,
      ),
      3 => 
      array (
        'label' => 'D',
        'text' => 'D. 128',
        'is_correct' => false,
      ),
      4 => 
      array (
        'label' => 'E',
        'text' => 'E. 256',
        'is_correct' => false,
      ),
    ),
  ),
  8 => 
  array (
    'subject_code' => 'MTK',
    'classroom_name' => 'X IPA 1',
    'type' => 'multiple_choice',
    'question_text' => 'Eksponen

Nilai dari (5^0 + 2^3) adalah ....',
    'question_image' => NULL,
    'topic' => 'SOAL MATEMATIKA KELAS 10 SMA',
    'difficulty' => 'medium',
    'score' => 2.5,
    'explanation' => NULL,
    'options' => 
    array (
      0 => 
      array (
        'label' => 'A',
        'text' => 'A. 7',
        'is_correct' => true,
      ),
      1 => 
      array (
        'label' => 'B',
        'text' => 'B. 8',
        'is_correct' => false,
      ),
      2 => 
      array (
        'label' => 'C',
        'text' => 'C. 9',
        'is_correct' => false,
      ),
      3 => 
      array (
        'label' => 'D',
        'text' => 'D. 10',
        'is_correct' => false,
      ),
      4 => 
      array (
        'label' => 'E',
        'text' => 'E. 11',
        'is_correct' => false,
      ),
    ),
  ),
  9 => 
  array (
    'subject_code' => 'MTK',
    'classroom_name' => 'X IPA 1',
    'type' => 'multiple_choice',
    'question_text' => 'Bentuk Akar

Hasil dari (\\sqrt{25} + \\sqrt{16}) adalah ....',
    'question_image' => NULL,
    'topic' => 'SOAL MATEMATIKA KELAS 10 SMA',
    'difficulty' => 'medium',
    'score' => 2.5,
    'explanation' => NULL,
    'options' => 
    array (
      0 => 
      array (
        'label' => 'A',
        'text' => 'A. 7',
        'is_correct' => true,
      ),
      1 => 
      array (
        'label' => 'B',
        'text' => 'B. 8',
        'is_correct' => false,
      ),
      2 => 
      array (
        'label' => 'C',
        'text' => 'C. 9',
        'is_correct' => false,
      ),
      3 => 
      array (
        'label' => 'D',
        'text' => 'D. 10',
        'is_correct' => false,
      ),
      4 => 
      array (
        'label' => 'E',
        'text' => 'E. 11',
        'is_correct' => false,
      ),
    ),
  ),
  10 => 
  array (
    'subject_code' => 'MTK',
    'classroom_name' => 'X IPA 1',
    'type' => 'multiple_choice',
    'question_text' => 'Bentuk Akar

Bentuk sederhana dari (\\sqrt{72}) adalah ....',
    'question_image' => NULL,
    'topic' => 'SOAL MATEMATIKA KELAS 10 SMA',
    'difficulty' => 'medium',
    'score' => 2.5,
    'explanation' => NULL,
    'options' => 
    array (
      0 => 
      array (
        'label' => 'A',
        'text' => 'A. (2\\sqrt{18})',
        'is_correct' => true,
      ),
      1 => 
      array (
        'label' => 'B',
        'text' => 'B. (3\\sqrt{8})',
        'is_correct' => false,
      ),
      2 => 
      array (
        'label' => 'C',
        'text' => 'C. (4\\sqrt{6})',
        'is_correct' => false,
      ),
      3 => 
      array (
        'label' => 'D',
        'text' => 'D. (6\\sqrt{2})',
        'is_correct' => false,
      ),
      4 => 
      array (
        'label' => 'E',
        'text' => 'E. (8\\sqrt{2})',
        'is_correct' => false,
      ),
    ),
  ),
  11 => 
  array (
    'subject_code' => 'MTK',
    'classroom_name' => 'X IPA 1',
    'type' => 'multiple_choice',
    'question_text' => 'Logaritma

Nilai dari (\\log_2 8) adalah ....',
    'question_image' => NULL,
    'topic' => 'SOAL MATEMATIKA KELAS 10 SMA',
    'difficulty' => 'medium',
    'score' => 2.5,
    'explanation' => NULL,
    'options' => 
    array (
      0 => 
      array (
        'label' => 'A',
        'text' => 'A. 2',
        'is_correct' => true,
      ),
      1 => 
      array (
        'label' => 'B',
        'text' => 'B. 3',
        'is_correct' => false,
      ),
      2 => 
      array (
        'label' => 'C',
        'text' => 'C. 4',
        'is_correct' => false,
      ),
      3 => 
      array (
        'label' => 'D',
        'text' => 'D. 6',
        'is_correct' => false,
      ),
      4 => 
      array (
        'label' => 'E',
        'text' => 'E. 8',
        'is_correct' => false,
      ),
    ),
  ),
  12 => 
  array (
    'subject_code' => 'MTK',
    'classroom_name' => 'X IPA 1',
    'type' => 'multiple_choice',
    'question_text' => 'Logaritma

Nilai dari (\\log_3 81) adalah ....',
    'question_image' => NULL,
    'topic' => 'SOAL MATEMATIKA KELAS 10 SMA',
    'difficulty' => 'medium',
    'score' => 2.5,
    'explanation' => NULL,
    'options' => 
    array (
      0 => 
      array (
        'label' => 'A',
        'text' => 'A. 2',
        'is_correct' => true,
      ),
      1 => 
      array (
        'label' => 'B',
        'text' => 'B. 3',
        'is_correct' => false,
      ),
      2 => 
      array (
        'label' => 'C',
        'text' => 'C. 4',
        'is_correct' => false,
      ),
      3 => 
      array (
        'label' => 'D',
        'text' => 'D. 5',
        'is_correct' => false,
      ),
      4 => 
      array (
        'label' => 'E',
        'text' => 'E. 6',
        'is_correct' => false,
      ),
    ),
  ),
  13 => 
  array (
    'subject_code' => 'MTK',
    'classroom_name' => 'X IPA 1',
    'type' => 'multiple_choice',
    'question_text' => 'Sistem Persamaan Linear

Diketahui:
[
x+y=10
]
[
x-y=4
]

Nilai (x) adalah ....',
    'question_image' => NULL,
    'topic' => 'SOAL MATEMATIKA KELAS 10 SMA',
    'difficulty' => 'medium',
    'score' => 2.5,
    'explanation' => NULL,
    'options' => 
    array (
      0 => 
      array (
        'label' => 'A',
        'text' => 'A. 3',
        'is_correct' => true,
      ),
      1 => 
      array (
        'label' => 'B',
        'text' => 'B. 5',
        'is_correct' => false,
      ),
      2 => 
      array (
        'label' => 'C',
        'text' => 'C. 6',
        'is_correct' => false,
      ),
      3 => 
      array (
        'label' => 'D',
        'text' => 'D. 7',
        'is_correct' => false,
      ),
      4 => 
      array (
        'label' => 'E',
        'text' => 'E. 8',
        'is_correct' => false,
      ),
    ),
  ),
  14 => 
  array (
    'subject_code' => 'MTK',
    'classroom_name' => 'X IPA 1',
    'type' => 'multiple_choice',
    'question_text' => 'Sistem Persamaan Linear

Jika (2x+y=11) dan (x+y=7), maka nilai (x) adalah ....',
    'question_image' => NULL,
    'topic' => 'SOAL MATEMATIKA KELAS 10 SMA',
    'difficulty' => 'medium',
    'score' => 2.5,
    'explanation' => NULL,
    'options' => 
    array (
      0 => 
      array (
        'label' => 'A',
        'text' => '2',
        'is_correct' => true,
      ),
      1 => 
      array (
        'label' => 'B',
        'text' => '3',
        'is_correct' => false,
      ),
      2 => 
      array (
        'label' => 'C',
        'text' => '4',
        'is_correct' => false,
      ),
      3 => 
      array (
        'label' => 'D',
        'text' => '5',
        'is_correct' => false,
      ),
      4 => 
      array (
        'label' => 'E',
        'text' => '6',
        'is_correct' => false,
      ),
    ),
  ),
  15 => 
  array (
    'subject_code' => 'MTK',
    'classroom_name' => 'X IPA 1',
    'type' => 'multiple_choice',
    'question_text' => 'Fungsi

Diketahui (f(x)=2x+3). Nilai (f(4)) adalah ....',
    'question_image' => 'questions/ZJlY1m4xCfdVXF4ig28i5xudfdhrU6MdeEIXzRmH.jpg',
    'topic' => 'SOAL MATEMATIKA KELAS 10 SMA',
    'difficulty' => 'medium',
    'score' => 10,
    'explanation' => NULL,
    'options' => 
    array (
      0 => 
      array (
        'label' => 'A',
        'text' => 'A. 7',
        'is_correct' => true,
      ),
      1 => 
      array (
        'label' => 'B',
        'text' => 'B. 9',
        'is_correct' => false,
      ),
      2 => 
      array (
        'label' => 'C',
        'text' => 'C. 10',
        'is_correct' => false,
      ),
      3 => 
      array (
        'label' => 'D',
        'text' => 'D. 11',
        'is_correct' => false,
      ),
      4 => 
      array (
        'label' => 'E',
        'text' => 'E. 12',
        'is_correct' => false,
      ),
    ),
  ),
  16 => 
  array (
    'subject_code' => 'MTK',
    'classroom_name' => 'X IPA 1',
    'type' => 'multiple_choice',
    'question_text' => 'Fungsi

Jika (f(x)=3x-5), maka (f(5)) adalah ....',
    'question_image' => NULL,
    'topic' => 'SOAL MATEMATIKA KELAS 10 SMA',
    'difficulty' => 'medium',
    'score' => 2.5,
    'explanation' => NULL,
    'options' => 
    array (
      0 => 
      array (
        'label' => 'A',
        'text' => 'A. 5',
        'is_correct' => true,
      ),
      1 => 
      array (
        'label' => 'B',
        'text' => 'B. 8',
        'is_correct' => false,
      ),
      2 => 
      array (
        'label' => 'C',
        'text' => 'C. 10',
        'is_correct' => false,
      ),
      3 => 
      array (
        'label' => 'D',
        'text' => 'D. 12',
        'is_correct' => false,
      ),
      4 => 
      array (
        'label' => 'E',
        'text' => 'E. 15',
        'is_correct' => false,
      ),
    ),
  ),
  17 => 
  array (
    'subject_code' => 'MTK',
    'classroom_name' => 'X IPA 1',
    'type' => 'multiple_choice',
    'question_text' => 'Persamaan Garis

Gradien garis (y=3x+5) adalah ....',
    'question_image' => NULL,
    'topic' => 'SOAL MATEMATIKA KELAS 10 SMA',
    'difficulty' => 'medium',
    'score' => 2.5,
    'explanation' => NULL,
    'options' => 
    array (
      0 => 
      array (
        'label' => 'A',
        'text' => 'A. -5',
        'is_correct' => true,
      ),
      1 => 
      array (
        'label' => 'B',
        'text' => 'B. -3',
        'is_correct' => false,
      ),
      2 => 
      array (
        'label' => 'C',
        'text' => 'C. 3',
        'is_correct' => false,
      ),
      3 => 
      array (
        'label' => 'D',
        'text' => 'D. 5',
        'is_correct' => false,
      ),
      4 => 
      array (
        'label' => 'E',
        'text' => 'E. 8',
        'is_correct' => false,
      ),
    ),
  ),
  18 => 
  array (
    'subject_code' => 'MTK',
    'classroom_name' => 'X IPA 1',
    'type' => 'multiple_choice',
    'question_text' => 'matematika saja',
    'question_image' => NULL,
    'topic' => 'SOAL MATEMATIKA KELAS 10 SMA',
    'difficulty' => 'medium',
    'score' => 2.5,
    'explanation' => NULL,
    'options' => 
    array (
      0 => 
      array (
        'label' => 'A',
        'text' => 'ya',
        'is_correct' => false,
      ),
      1 => 
      array (
        'label' => 'B',
        'text' => 'y9',
        'is_correct' => true,
      ),
      2 => 
      array (
        'label' => 'C',
        'text' => '80',
        'is_correct' => false,
      ),
      3 => 
      array (
        'label' => 'D',
        'text' => '19',
        'is_correct' => false,
      ),
      4 => 
      array (
        'label' => 'E',
        'text' => 'E. 8',
        'is_correct' => false,
      ),
    ),
  ),
  19 => 
  array (
    'subject_code' => 'MAPEL-BIN-02',
    'classroom_name' => 'X IPA 2',
    'type' => 'multiple_choice',
    'question_text' => 'bahasa indonesia diii',
    'question_image' => NULL,
    'topic' => 'bahasa indonesia',
    'difficulty' => 'medium',
    'score' => 2.5,
    'explanation' => NULL,
    'options' => 
    array (
      0 => 
      array (
        'label' => 'A',
        'text' => 'indo',
        'is_correct' => false,
      ),
      1 => 
      array (
        'label' => 'B',
        'text' => 'belanda',
        'is_correct' => true,
      ),
      2 => 
      array (
        'label' => 'C',
        'text' => 'belgia',
        'is_correct' => false,
      ),
      3 => 
      array (
        'label' => 'D',
        'text' => 'berunei',
        'is_correct' => false,
      ),
      4 => 
      array (
        'label' => 'E',
        'text' => 'amerika',
        'is_correct' => false,
      ),
    ),
  ),
);

        foreach ($questionsData as $q) {
            $subject = Subject::where('code', $q['subject_code'])->first();
            if (!$subject) continue;

            $classroom = !empty($q['classroom_name']) ? Classroom::where('name', $q['classroom_name'])->first() : null;

            $question = Question::updateOrCreate(
                [
                    'subject_id'    => $subject->id,
                    'created_by'    => $teacher->id,
                    'question_text' => $q['question_text'],
                ],
                [
                    'classroom_id'  => $classroom?->id,
                    'type'          => $q['type'],
                    'question_image'=> $q['question_image'],
                    'topic'         => $q['topic'],
                    'difficulty'    => $q['difficulty'],
                    'score'         => $q['score'],
                    'explanation'   => $q['explanation'],
                    'is_active'     => true,
                ]
            );

            if (!empty($q['options'])) {
                $question->options()->delete();
                foreach ($q['options'] as $index => $opt) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'label'       => $opt['label'] ?? chr(65 + $index),
                        'option_text' => $opt['text'],
                        'is_correct'  => (bool)$opt['is_correct'],
                        'sort_order'  => $index,
                    ]);
                }
            }
        }
    }
}