<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::user();
        $classroom = $student->currentClassroom();
        $search = trim($request->get('search') ?? $request->get('q') ?? '');

        // Ujian aktif (berlangsung sekarang)
        $activeExams = collect();
        if ($classroom) {
            $activeExamsQuery = Exam::where('classroom_id', $classroom->id)
                ->where('status', 'active')
                ->with(['subject', 'settings', 'teacher']);

            if ($search !== '') {
                $activeExamsQuery->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhereHas('subject', function ($sq) use ($search) {
                          $sq->where('name', 'like', "%{$search}%");
                      });
                });
            }

            $activeExams = $activeExamsQuery->get()
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
            $upcomingExamsQuery = Exam::where('classroom_id', $classroom->id)
                ->where('status', 'scheduled')
                ->where('exam_date', '>=', now()->toDateString())
                ->orderBy('exam_date')
                ->with('subject');

            if ($search !== '') {
                $upcomingExamsQuery->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhereHas('subject', function ($sq) use ($search) {
                          $sq->where('name', 'like', "%{$search}%");
                      });
                });
            }

            $upcomingExams = $upcomingExamsQuery->limit(10)->get();
        }

        // Hasil terbaru
        $recentResultsQuery = ExamResult::where('student_id', $student->id)
            ->with(['exam.subject']);
        if ($search !== '') {
            $recentResultsQuery->whereHas('exam', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('subject', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        $recentResults = $recentResultsQuery->latest()->limit(3)->get();

        // Riwayat ujian
        $recentHistoryQuery = ExamResult::where('student_id', $student->id)
            ->with(['exam.subject', 'exam']);
        if ($search !== '') {
            $recentHistoryQuery->whereHas('exam', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('subject', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        $recentHistory = $recentHistoryQuery->latest()->limit(5)->get();

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
            'recentResults', 'recentHistory', 'unreadNotifications', 'unreadCount', 'search'
        ));
    }
}
