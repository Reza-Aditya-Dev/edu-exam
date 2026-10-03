<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Exam;
use App\Models\ExamParticipant;
use App\Models\ExamResult;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Question;
use App\Models\ActivityLog;
use App\Models\AcademicYear;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Core Master Data Statistics
        $totalStudents      = User::students()->count();
        $activeStudents     = User::students()->where('is_active', true)->count();
        $newStudents        = User::students()->where('created_at', '>=', now()->subDays(30))->count();
        
        $totalTeachers      = User::teachers()->count();
        $activeTeachers     = User::teachers()->where('is_active', true)->count();
        $teachersWithNip    = User::teachers()->whereNotNull('nip')->where('nip', '!=', '')->count();
        
        $totalClassrooms    = Classroom::count();
        $totalCapacity      = Classroom::sum('capacity') ?: 0;
        $gradesList         = Classroom::distinct()->pluck('grade')->sort()->map(fn($g) => 'Kelas ' . $g)->implode(', ');
        
        $totalSubjects      = Subject::count();
        $totalQuestions     = Question::count();
        
        $activeExamsCount   = Exam::where('status', 'active')->count();
        $scheduledExamsCount= Exam::where('status', 'scheduled')->count();
        $completedExamsCount= Exam::where('status', 'completed')->count();
        $totalExamsCount    = Exam::count();
        $inProgressCount    = ExamParticipant::where('status', 'in_progress')->count();

        $stats = [
            'total_students'      => $totalStudents,
            'active_students'     => $activeStudents,
            'new_students'        => $newStudents,
            'total_teachers'      => $totalTeachers,
            'active_teachers'     => $activeTeachers,
            'teachers_with_nip'   => $teachersWithNip,
            'total_classrooms'    => $totalClassrooms,
            'total_capacity'      => $totalCapacity,
            'grades_list'         => $gradesList ?: 'Semua Tingkat',
            'total_subjects'      => $totalSubjects,
            'total_questions'     => $totalQuestions,
            'active_exams'        => $activeExamsCount,
            'scheduled_exams'     => $scheduledExamsCount,
            'completed_exams'     => $completedExamsCount,
            'total_exams'         => $totalExamsCount,
            'active_students_now' => $inProgressCount,
        ];

        // 2. Active & Upcoming Exams
        $activeExams = Exam::where('status', 'active')
            ->with(['subject', 'classroom.students', 'teacher', 'participants'])
            ->latest()
            ->limit(4)
            ->get();

        $upcomingExams = Exam::where('status', 'scheduled')
            ->with(['subject', 'classroom.students', 'teacher'])
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->limit(4)
            ->get();

        $lastCompletedExam = Exam::where('status', 'completed')
            ->with(['subject', 'classroom', 'teacher', 'results'])
            ->latest('updated_at')
            ->first();

        // 3. Monthly Exam Frequency Trend (last 5 months)
        $monthNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];
        $monthlyFrequency = [];
        $totalEvaluationsPeriod = 0;
        $now = Carbon::now();
        for ($i = 4; $i >= 0; $i--) {
            $monthDate = (clone $now)->subMonths($i);
            $m = (int)$monthDate->format('n');
            $y = (int)$monthDate->format('Y');
            $count = Exam::whereYear('created_at', $y)->whereMonth('created_at', $m)->count();
            $monthlyFrequency[] = [
                'name'       => $monthNames[$m],
                'count'      => $count,
                'is_current' => ($i === 0),
            ];
            $totalEvaluationsPeriod += $count;
        }

        $peakMonthItem = collect($monthlyFrequency)->sortByDesc('count')->first();
        $peakMonth = ($peakMonthItem && $peakMonthItem['count'] > 0) ? $peakMonthItem['name'] : 'Bulan Ini';

        // 4. Student Participation Metrics
        $totalParticipants = ExamParticipant::count();
        $submittedCount    = ExamParticipant::where('status', 'submitted')->count();
        $onTimeCount       = ExamParticipant::where('status', 'submitted')->where('is_late_submission', false)->count();
        $lateCount         = ExamParticipant::where('is_late_submission', true)->count();

        if ($totalParticipants > 0) {
            $participationRate = round(($submittedCount / $totalParticipants) * 100, 1);
            $onTimeRate        = round(($onTimeCount / $totalParticipants) * 100, 1);
            $lateRate          = round(($lateCount / $totalParticipants) * 100, 1);
            $dispensasiRate    = round(max(0, 100 - $onTimeRate - $lateRate), 1);
        } else {
            $participationRate = 0;
            $onTimeRate        = 0;
            $lateRate          = 0;
            $dispensasiRate    = 0;
        }

        // 5. School Grade Distribution & KKM Metrics
        $totalResults = ExamResult::count();
        $averageScore = $totalResults > 0 ? round(ExamResult::avg('total_score'), 1) : null;
        $kkmDefault   = round(Exam::avg('passing_grade') ?: 75.0, 1);
        $highestScore = $totalResults > 0 ? round(ExamResult::max('total_score'), 1) : null;
        
        $topSubject = null;
        if ($totalResults > 0) {
            $topResult = ExamResult::with('exam.subject')->orderByDesc('total_score')->first();
            if ($topResult && $topResult->exam && $topResult->exam->subject) {
                $topSubject = $topResult->exam->subject->name;
            }
        }

        $passedCount = ExamResult::where('pass_status', 'pass')->count();
        $classicalPassRate = $totalResults > 0 ? round(($passedCount / $totalResults) * 100, 1) : 0;
        $goodGradeCount = ExamResult::where('total_score', '>=', $kkmDefault)->count();
        $goodGradePercent = $totalResults > 0 ? round(($goodGradeCount / $totalResults) * 100, 1) : 0;

        // 6. CBT Server & Environment Telemetry
        $memoryBytes    = memory_get_usage(true);
        $memoryMB       = round($memoryBytes / (1024 * 1024), 1);
        $memoryLimitStr = ini_get('memory_limit');
        $memoryLimitMB  = (int)$memoryLimitStr ?: 512;
        $memoryPercent  = min(100, max(2, round(($memoryMB / $memoryLimitMB) * 100)));
        $activeSockets  = $inProgressCount + max(1, User::where('updated_at', '>=', now()->subMinutes(15))->count());
        $dbStatus       = 'Terhubung';
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $dbStatus = 'Terkendala';
        }

        // 7. Recent Completed / Evaluated Exams
        $recentCompletedExams = Exam::with(['subject', 'classroom', 'results', 'participants'])
            ->whereIn('status', ['completed', 'archived', 'active'])
            ->latest()
            ->limit(5)
            ->get();

        if ($recentCompletedExams->isEmpty()) {
            $recentCompletedExams = Exam::with(['subject', 'classroom', 'results', 'participants'])
                ->latest()
                ->limit(5)
                ->get();
        }

        // 8. Recent Activity Logs
        $recentLogs = ActivityLog::with('user')
            ->latest()
            ->limit(6)
            ->get();

        // 9. Active Academic Year
        $activeYear = AcademicYear::where('is_active', true)->first();

        return view('admin.dashboard', compact(
            'stats', 'activeExams', 'upcomingExams', 'lastCompletedExam',
            'monthlyFrequency', 'totalEvaluationsPeriod', 'peakMonth',
            'totalParticipants', 'participationRate', 'onTimeRate', 'lateRate', 'dispensasiRate',
            'totalResults', 'averageScore', 'kkmDefault', 'highestScore', 'topSubject', 'classicalPassRate', 'goodGradePercent',
            'memoryMB', 'memoryPercent', 'activeSockets', 'dbStatus',
            'recentCompletedExams', 'recentLogs', 'activeYear'
        ));
    }
}

