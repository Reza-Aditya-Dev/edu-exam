<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Question;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();

        // 1. Ujian Aktif (Live Ongoing)
        $activeExams = Exam::forTeacher($teacher->id)
            ->where('status', 'active')
            ->with(['subject', 'classroom.students', 'participants.student', 'settings'])
            ->get();

        // 2. Ujian Terjadwal Mendatang
        $scheduledExams = Exam::forTeacher($teacher->id)
            ->where('status', 'scheduled')
            ->where('exam_date', '>=', now()->toDateString())
            ->orderBy('exam_date')
            ->with(['subject', 'classroom.students'])
            ->limit(5)
            ->get();

        // 3. Ujian yang Telah Selesai
        $completedExams = Exam::forTeacher($teacher->id)
            ->where('status', 'completed')
            ->with(['subject', 'classroom.students', 'results.student'])
            ->latest()
            ->limit(5)
            ->get();

        // 4. Hitung Peserta Didik
        $totalStudents = $teacher->teachingClassrooms()
            ->with('students')->get()
            ->pluck('students')->flatten()->unique('id')->count();

        if ($totalStudents === 0) {
            $totalStudents = DB::table('classroom_student')->distinct('student_id')->count('student_id') ?: \App\Models\User::students()->count();
        }

        // 5. Metrik Umum
        $totalExams     = Exam::forTeacher($teacher->id)->count();
        $totalQuestions = Question::where('created_by', $teacher->id)->count();

        $allTeacherResults = ExamResult::whereHas('exam', fn($q) => $q->where('teacher_id', $teacher->id))->get();
        $totalResultsCount = $allTeacherResults->count();

        $avgScore = $totalResultsCount > 0 ? round($allTeacherResults->avg('total_score'), 1) : null;

        $activeClassroomsCount = $teacher->teachingClassrooms()->count();
        if ($activeClassroomsCount === 0) {
            $activeClassroomsCount = \App\Models\Classroom::count();
        }

        // 6. Data Riwayat Ujian untuk Grafik Tren (Chronological)
        $examsWithResults = Exam::forTeacher($teacher->id)
            ->whereHas('results')
            ->with(['subject', 'classroom', 'results'])
            ->orderBy('exam_date')
            ->orderBy('id')
            ->limit(8)
            ->get();

        $hasRealData = $examsWithResults->isNotEmpty();

        if ($hasRealData) {
            $trendData = $examsWithResults->map(function ($ex) {
                $results = $ex->results;
                $count = $results->count();
                $avg = $count > 0 ? round($results->avg('total_score'), 1) : 0;
                $kkm = (float) ($ex->passing_grade ?? 75);
                $passed = $results->where('pass_status', 'pass')->count();
                $passRate = $count > 0 ? round(($passed / $count) * 100, 1) : 0;

                return [
                    'id'             => $ex->id,
                    'title'          => $ex->title,
                    'short_title'    => \Illuminate\Support\Str::limit($ex->title, 15),
                    'date'           => $ex->exam_date ? \Carbon\Carbon::parse($ex->exam_date)->translatedFormat('d M') : 'Ujian',
                    'classroom'      => $ex->classroom?->name ?? 'Semua',
                    'avg_score'      => $avg,
                    'kkm'            => $kkm,
                    'total_students' => $count,
                    'pass_rate'      => $passRate,
                    'passed'         => $passed,
                    'failed'         => $count - $passed,
                ];
            })->values();
        } else {
            // Contoh baseline jika guru baru pertama kali masuk dan belum memiliki data ujian selesai
            $trendData = collect([
                [
                    'id' => 0, 'title' => 'Simulasi Kuis Aljabar', 'short_title' => 'Kuis Aljabar',
                    'date' => '12 Agu', 'classroom' => 'X IPA 1', 'avg_score' => 84.5, 'kkm' => 75,
                    'total_students' => 32, 'pass_rate' => 87.5, 'passed' => 28, 'failed' => 4,
                ],
                [
                    'id' => 0, 'title' => 'Simulasi UH 1 Matematika', 'short_title' => 'UH 1 Mat',
                    'date' => '28 Agu', 'classroom' => 'X IPA 2', 'avg_score' => 79.2, 'kkm' => 75,
                    'total_students' => 34, 'pass_rate' => 76.5, 'passed' => 26, 'failed' => 8,
                ],
                [
                    'id' => 0, 'title' => 'Simulasi UTS Matematika Wajib', 'short_title' => 'UTS Wajib',
                    'date' => '15 Sep', 'classroom' => 'X IPA 1', 'avg_score' => 85.0, 'kkm' => 75,
                    'total_students' => 32, 'pass_rate' => 90.6, 'passed' => 29, 'failed' => 3,
                ],
                [
                    'id' => 0, 'title' => 'Simulasi Kuis Trigonometri', 'short_title' => 'Kuis Trigono',
                    'date' => '28 Sep', 'classroom' => 'X IPA 2', 'avg_score' => 81.0, 'kkm' => 75,
                    'total_students' => 33, 'pass_rate' => 81.8, 'passed' => 27, 'failed' => 6,
                ],
            ]);
        }

        // 7. Distribusi Kelulusan & Nilai (Pass / Remedial / Grade breakdown)
        $passCount = $allTeacherResults->where('pass_status', 'pass')->count();
        $failCount = $totalResultsCount - $passCount;
        $overallPassRate = $totalResultsCount > 0 ? round(($passCount / $totalResultsCount) * 100, 1) : 0;

        $gradeDistribution = [
            'A' => $allTeacherResults->where('total_score', '>=', 85)->count(),
            'B' => $allTeacherResults->filter(fn($r) => $r->total_score >= 75 && $r->total_score < 85)->count(),
            'C' => $allTeacherResults->filter(fn($r) => $r->total_score >= 60 && $r->total_score < 75)->count(),
            'D' => $allTeacherResults->where('total_score', '<', 60)->count(),
        ];

        // 8. Ringkasan Status Ujian
        $examStatusCounts = [
            'active'    => $activeExams->count(),
            'scheduled' => Exam::forTeacher($teacher->id)->where('status', 'scheduled')->count(),
            'completed' => Exam::forTeacher($teacher->id)->where('status', 'completed')->count(),
            'draft'     => Exam::forTeacher($teacher->id)->where('status', 'draft')->count(),
        ];

        // 9. Aktivitas Submisi Terbaru Siswa
        $recentResults = ExamResult::whereHas('exam', fn($q) => $q->where('teacher_id', $teacher->id))
            ->with(['exam.subject', 'student'])
            ->latest()
            ->limit(6)
            ->get();

        $homeroomClass = $teacher->homeroomClassrooms()->first();
        $primarySubject = $teacher->subjects()->first();

        return view('teacher.dashboard', compact(
            'teacher', 'activeExams', 'scheduledExams', 'completedExams',
            'totalStudents', 'totalExams', 'totalQuestions', 'avgScore',
            'activeClassroomsCount', 'trendData', 'hasRealData',
            'gradeDistribution', 'passCount', 'failCount', 'overallPassRate',
            'examStatusCounts', 'recentResults',
            'homeroomClass', 'primarySubject'
        ));
    }
}
