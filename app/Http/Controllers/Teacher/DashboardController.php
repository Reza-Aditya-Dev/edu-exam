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

        $activeExams = Exam::forTeacher($teacher->id)
            ->where('status', 'active')
            ->with(['subject', 'classroom.students', 'participants', 'settings'])
            ->get();

        $scheduledExams = Exam::forTeacher($teacher->id)
            ->where('status', 'scheduled')
            ->where('exam_date', '>=', now()->toDateString())
            ->orderBy('exam_date')
            ->with(['subject', 'classroom.students'])
            ->limit(5)
            ->get();

        $completedExams = Exam::forTeacher($teacher->id)
            ->where('status', 'completed')
            ->with(['subject', 'classroom.students', 'results'])
            ->latest()
            ->limit(5)
            ->get();

        $totalStudents = $teacher->teachingClassrooms()
            ->with('students')->get()
            ->pluck('students')->flatten()->unique('id')->count();

        if ($totalStudents === 0) {
            $totalStudents = DB::table('classroom_student')->distinct('student_id')->count('student_id') ?: \App\Models\User::students()->count();
        }

        $totalExams     = Exam::forTeacher($teacher->id)->count();
        $totalQuestions = Question::where('created_by', $teacher->id)->count();

        $avgScore = ExamResult::whereHas('exam', fn($q) => $q->where('teacher_id', $teacher->id))
            ->avg('total_score');

        $activeClassroomsCount = $teacher->teachingClassrooms()->count();
        if ($activeClassroomsCount === 0) {
            $activeClassroomsCount = \App\Models\Classroom::count();
        }

        // 4 trend exams for the score chart
        $trendExams = Exam::forTeacher($teacher->id)
            ->whereHas('results')
            ->with(['subject', 'results'])
            ->latest()
            ->limit(4)
            ->get();

        // Recent student submission results
        $recentResults = ExamResult::whereHas('exam', fn($q) => $q->where('teacher_id', $teacher->id))
            ->with(['exam.subject', 'student'])->latest()->limit(5)->get();

        $homeroomClass = $teacher->homeroomClassrooms()->first();
        $primarySubject = $teacher->subjects()->first();

        return view('teacher.dashboard', compact(
            'teacher', 'activeExams', 'scheduledExams', 'completedExams',
            'totalStudents', 'totalExams', 'totalQuestions', 'avgScore',
            'activeClassroomsCount', 'trendExams', 'recentResults',
            'homeroomClass', 'primarySubject'
        ));
    }
}
