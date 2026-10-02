<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        $classroom = $student->currentClassroom();

        // Ujian aktif (berlangsung sekarang)
        $activeExams = collect();
        if ($classroom) {
            $activeExams = Exam::where('classroom_id', $classroom->id)
                ->where('status', 'active')
                ->with(['subject', 'settings'])
                ->get()
                ->map(function ($exam) use ($student) {
                    $exam->my_participant = $exam->participants()
                        ->where('student_id', $student->id)
                        ->first();
                    return $exam;
                });
        }

        // Ujian mendatang
        $upcomingExams = collect();
        if ($classroom) {
            $upcomingExams = Exam::where('classroom_id', $classroom->id)
                ->where('status', 'scheduled')
                ->where('exam_date', '>=', now()->toDateString())
                ->orderBy('exam_date')
                ->with('subject')
                ->limit(5)
                ->get();
        }

        // Hasil terbaru
        $recentResults = ExamResult::where('student_id', $student->id)
            ->with(['exam.subject'])
            ->latest()
            ->limit(3)
            ->get();

        // Riwayat ujian
        $recentHistory = ExamResult::where('student_id', $student->id)
            ->with(['exam.subject', 'exam'])
            ->latest()
            ->limit(5)
            ->get();

        // Notifikasi belum dibaca
        $unreadNotifications = Notification::where('user_id', $student->id)
            ->where('is_read', false)
            ->latest()
            ->limit(5)
            ->get();

        $unreadCount = Notification::where('user_id', $student->id)
            ->where('is_read', false)
            ->count();

        return view('student.dashboard', compact(
            'student', 'classroom', 'activeExams', 'upcomingExams',
            'recentResults', 'recentHistory', 'unreadNotifications', 'unreadCount'
        ));
    }
}
