<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\ExamSetting;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\AcademicYear;
use App\Models\User;
use App\Models\Question;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::where('role', 'teacher')->where('username', 'guru')->first() ?? User::where('role', 'teacher')->first();
        $ay = AcademicYear::where('is_active', true)->first() ?? AcademicYear::first();
        if (!$teacher || !$ay) return;

        $mtk = Subject::where('code', 'MTK')->first();
        $ipa = Subject::where('code', 'IPA')->first();
        $xIpa1 = Classroom::where('name', 'X IPA 1')->first();
        $xIpa2 = Classroom::where('name', 'X IPA 2')->first();

        // 1. quis matematika (15 butir soal MTK terhubung)
        if ($mtk && $xIpa1) {
            $exam1 = Exam::updateOrCreate(
                ['title' => 'quis matematika', 'teacher_id' => $teacher->id],
                [
                    'subject_id'        => $mtk->id,
                    'classroom_id'      => $xIpa1->id,
                    'academic_year_id'  => $ay->id,
                    'exam_type'         => 'Quiz',
                    'token'             => 'MTK-017',
                    'description'       => 'Kuis Evaluasi Matematika Kelas 10',
                    'instructions'      => 'Kerjakan butir soal secara teliti dan mandiri.',
                    'exam_date'         => now()->toDateString(),
                    'start_time'        => '08:00:00',
                    'end_time'          => '10:00:00',
                    'duration_minutes'  => 90,
                    'total_questions'   => 15,
                    'passing_grade'     => 75.00,
                    'status'            => 'scheduled',
                ]
            );

            ExamSetting::updateOrCreate(
                ['exam_id' => $exam1->id],
                [
                    'shuffle_questions'         => true,
                    'shuffle_options'           => true,
                    'auto_save'                 => true,
                    'auto_submit'               => true,
                    'show_result_immediately'   => true,
                    'allow_review'              => true,
                    'show_correct_answers'      => true,
                ]
            );

            // Hubungkan soal-soal matematika
            $mtkQuestions = Question::where('subject_id', $mtk->id)->take(15)->get();
            $attachData = [];
            foreach ($mtkQuestions as $idx => $q) {
                $attachData[$q->id] = ['question_order' => $idx + 1];
            }
            $exam1->questions()->sync($attachData);
        }

        // 2. ujian per cobaan (IPA)
        if ($ipa && $xIpa1) {
            $exam2 = Exam::updateOrCreate(
                ['title' => 'ujian per cobaan', 'teacher_id' => $teacher->id],
                [
                    'subject_id'        => $ipa->id,
                    'classroom_id'      => $xIpa1->id,
                    'academic_year_id'  => $ay->id,
                    'exam_type'         => 'Quiz',
                    'token'             => 'IPA-008',
                    'description'       => 'Ujian Percobaan IPA',
                    'instructions'      => 'Pilih jawaban yang paling tepat.',
                    'exam_date'         => now()->toDateString(),
                    'start_time'        => '08:00:00',
                    'end_time'          => '10:00:00',
                    'duration_minutes'  => 90,
                    'total_questions'   => 3,
                    'passing_grade'     => 75.00,
                    'status'            => 'completed',
                ]
            );

            ExamSetting::updateOrCreate(
                ['exam_id' => $exam2->id],
                [
                    'shuffle_questions'         => true,
                    'shuffle_options'           => true,
                    'auto_save'                 => true,
                    'auto_submit'               => true,
                    'show_result_immediately'   => true,
                ]
            );

            $ipaQuestions = Question::where('subject_id', $ipa->id)->take(3)->get();
            $attachData = [];
            foreach ($ipaQuestions as $idx => $q) {
                $attachData[$q->id] = ['question_order' => $idx + 1];
            }
            $exam2->questions()->sync($attachData);
        }

        // 3. UTS Matematika Wajib
        if ($mtk && $xIpa1) {
            $exam3 = Exam::updateOrCreate(
                ['title' => 'UTS Matematika Wajib', 'teacher_id' => $teacher->id],
                [
                    'subject_id'        => $mtk->id,
                    'classroom_id'      => $xIpa1->id,
                    'academic_year_id'  => $ay->id,
                    'exam_type'         => 'UTS',
                    'token'             => 'MTK-2026',
                    'description'       => 'Penilaian Tengah Semester Matematika Wajib',
                    'instructions'      => 'Periksa kembali seluruh lembar jawaban sebelum submit.',
                    'exam_date'         => now()->toDateString(),
                    'start_time'        => '07:30:00',
                    'end_time'          => '09:00:00',
                    'duration_minutes'  => 60,
                    'total_questions'   => 0,
                    'passing_grade'     => 75.00,
                    'status'            => 'active',
                ]
            );

            ExamSetting::updateOrCreate(
                ['exam_id' => $exam3->id],
                [
                    'shuffle_questions'         => true,
                    'shuffle_options'           => true,
                    'auto_save'                 => true,
                    'auto_submit'               => true,
                    'show_result_immediately'   => true,
                ]
            );
        }
    }
}