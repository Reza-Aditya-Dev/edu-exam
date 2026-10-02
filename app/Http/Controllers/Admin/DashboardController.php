<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Exam;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students' => User::students()->count(),
            'total_teachers' => User::teachers()->count(),
            'total_classrooms'=> Classroom::count(),
            'total_subjects'  => Subject::count(),
            'active_exams'    => Exam::where('status', 'active')->count(),
            'total_exams'     => Exam::count(),
        ];

        $activeExams = Exam::where('status', 'active')
            ->with(['subject', 'classroom', 'teacher'])
            ->latest()->limit(5)->get();

        $recentExams = Exam::with(['subject', 'classroom', 'teacher'])
            ->latest()->limit(8)->get();

        $recentLogs = ActivityLog::with('user')
            ->latest()->limit(10)->get();

        return view('admin.dashboard', compact('stats', 'activeExams', 'recentExams', 'recentLogs'));
    }
}
