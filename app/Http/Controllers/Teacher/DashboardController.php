<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Question;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();

        $activeExams = Exam::forTeacher($teacher->id)->where('status', 'active')
            ->with(['subject', 'classroom'])->get();

        $scheduledExams = Exam::forTeacher($teacher->id)->where('status', 'scheduled')
            ->where('exam_date', '>=', now()->toDateString())
            ->orderBy('exam_date')->with(['subject', 'classroom'])->limit(5)->get();

        $completedExams = Exam::forTeacher($teacher->id)->where('status', 'completed')
            ->with(['subject', 'classroom'])->latest()->limit(5)->get();

        $totalStudents = $teacher->teachingClassrooms()
            ->with('students')->get()
            ->pluck('students')->flatten()->unique('id')->count();

        $totalExams   = Exam::forTeacher($teacher->id)->count();
        $totalQuestions = Question::where('created_by', $teacher->id)->count();

        $avgScore = ExamResult::whereHas('exam', fn($q) => $q->where('teacher_id', $teacher->id))
            ->avg('total_score');

        $unreadCount = Notification::where('user_id', $teacher->id)->where('is_read', false)->count();

        // Aktivitas terbaru (hasil ujian terbaru)
        $recentResults = ExamResult::whereHas('exam', fn($q) => $q->where('teacher_id', $teacher->id))
            ->with(['exam.subject', 'student'])->latest()->limit(5)->get();

        return view('teacher.dashboard', compact(
            'teacher', 'activeExams', 'scheduledExams', 'completedExams',
            'totalStudents', 'totalExams', 'totalQuestions', 'avgScore',
            'unreadCount', 'recentResults'
        ));
    }
}
