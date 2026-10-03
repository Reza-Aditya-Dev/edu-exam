<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Models\ActivityLog;
use App\Models\SchoolSetting;
use App\Models\Exam;
use Illuminate\Http\Request;

class ManagementController extends Controller
{
    // ==================== CLASSROOMS ====================

    public function classrooms(Request $request)
    {
        // Support CSV export if requested
        if ($request->get('export') === 'csv') {
            $rows = Classroom::with(['academicYear', 'homeroomTeacher'])->withCount('students')->orderBy('grade')->orderBy('name')->get();
            $csvFileName = 'rekap_rombel_kelas_' . date('Ymd_His') . '.csv';
            $headers = [
                "Content-type"        => "text/csv; charset=UTF-8",
                "Content-Disposition" => "attachment; filename=$csvFileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];
            return response()->stream(function() use($rows) {
                $file = fopen('php://output', 'w');
                // UTF-8 BOM for Excel compatibility
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                fputcsv($file, ['ID', 'Nama Kelas', 'Tingkat', 'Jurusan', 'Tahun Ajaran', 'Wali Kelas', 'NIP Wali Kelas', 'Jumlah Siswa', 'Kapasitas']);
                foreach ($rows as $c) {
                    fputcsv($file, [
                        $c->id,
                        $c->name,
                        'Kelas ' . $c->grade,
                        $c->major ?? 'Umum',
                        $c->academicYear ? $c->academicYear->name . ' - Smt ' . $c->academicYear->semester : '-',
                        $c->homeroomTeacher?->name ?? 'Belum Ditugaskan',
                        $c->homeroomTeacher?->nip ?? '-',
                        $c->students_count,
                        $c->capacity,
                    ]);
                }
                fclose($file);
            }, 200, $headers);
        }

        $query = Classroom::with(['academicYear', 'homeroomTeacher', 'exams' => function($q) {
                $q->whereIn('status', ['active', 'scheduled'])->latest();
            }])
            ->withCount('students');

        if ($request->filled('academic_year_id') && $request->academic_year_id !== 'all') {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if ($request->filled('grade') && $request->grade !== 'all') {
            $query->where('grade', $request->grade);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('major', 'like', "%{$search}%")
                  ->orWhereHas('homeroomTeacher', function($t) use ($search) {
                      $t->where('name', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%");
                  });
            });
        }

        $perPage = in_array((int)$request->per_page, [6, 9, 12, 24]) ? (int)$request->per_page : 9;
        $classrooms = $query->orderBy('grade')->orderBy('name')->paginate($perPage)->withQueryString();

        $totalClassrooms    = Classroom::count();
        $totalStudents      = \Illuminate\Support\Facades\DB::table('classroom_student')->distinct('student_id')->count();
        $avgPerClassroom    = $totalClassrooms > 0 ? round($totalStudents / $totalClassrooms, 0) : 0;
        $assignedHomerooms  = Classroom::whereNotNull('homeroom_teacher_id')->count();

        $countGrade10       = Classroom::where('grade', 10)->count();
        $countGrade11       = Classroom::where('grade', 11)->count();
        $countGrade12       = Classroom::where('grade', 12)->count();

        $academicYears      = AcademicYear::orderByDesc('is_active')->orderByDesc('start_year')->get();
        $activeAcademicYear = AcademicYear::where('is_active', true)->first() ?? $academicYears->first();
        $teachers           = \App\Models\User::teachers()->active()->orderBy('name')->get();

        return view('admin.classroom.index', compact(
            'classrooms', 'academicYears', 'activeAcademicYear', 'teachers',
            'totalClassrooms', 'totalStudents', 'avgPerClassroom', 'assignedHomerooms',
            'countGrade10', 'countGrade11', 'countGrade12'
        ));
    }

    public function createClassroom()
    {
        $academicYears = AcademicYear::orderByDesc('start_year')->get();
        $teachers      = \App\Models\User::teachers()->active()->get();
        return view('admin.classroom.create', compact('academicYears', 'teachers'));
    }

    public function storeClassroom(Request $request)
    {
        // Fallback default academic year if omitted in quick modal
        if (!$request->filled('academic_year_id')) {
            $activeYear = AcademicYear::where('is_active', true)->first();
            if ($activeYear) {
                $request->merge(['academic_year_id' => $activeYear->id]);
            }
        }

        $request->validate([
            'academic_year_id'    => 'required|exists:academic_years,id',
            'name'                => 'required|string|max:50',
            'grade'               => 'required|in:10,11,12',
            'major'               => 'nullable|string|max:30',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
            'capacity'            => 'nullable|integer|min:1|max:60',
        ], [
            'name.required' => 'Nama rombel/kelas wajib diisi.',
            'grade.required' => 'Tingkat kelas wajib dipilih.',
            'academic_year_id.required' => 'Tahun ajaran wajib ditentukan.',
        ]);

        $classroom = Classroom::create([
            'academic_year_id'    => $request->academic_year_id,
            'homeroom_teacher_id' => $request->homeroom_teacher_id ?: null,
            'name'                => $request->name,
            'grade'               => $request->grade,
            'major'               => $request->major,
            'capacity'            => $request->capacity ?: 36,
            'is_active'           => true,
        ]);

        ActivityLog::log('classroom_created', "Kelas dibuat: {$classroom->name}", $classroom);
        return redirect()->route('admin.classrooms')->with('success', "Rombel kelas {$classroom->name} berhasil ditambahkan.");
    }

    public function editClassroom(Classroom $classroom)
    {
        $academicYears = AcademicYear::orderByDesc('start_year')->get();
        $teachers      = \App\Models\User::teachers()->active()->get();
        return view('admin.classroom.edit', compact('classroom', 'academicYears', 'teachers'));
    }

    public function updateClassroom(Request $request, Classroom $classroom)
    {
        $request->validate([
            'name'  => 'required|string|max:50',
            'grade' => 'required|in:10,11,12',
        ]);
        $classroom->update($request->all());
        ActivityLog::log('classroom_updated', "Kelas diperbarui: {$classroom->name}", $classroom);
        return redirect()->route('admin.classrooms')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroyClassroom(Classroom $classroom)
    {
        if ($classroom->students()->count() > 0 || $classroom->exams()->count() > 0) {
            return back()->with('error', 'Kelas tidak dapat dihapus karena masih memiliki siswa atau ujian.');
        }
        ActivityLog::log('classroom_deleted', "Kelas dihapus: {$classroom->name}");
        $classroom->delete();
        return redirect()->route('admin.classrooms')->with('success', 'Kelas berhasil dihapus.');
    }

    // ==================== SUBJECTS ====================

    public function subjects()
    {
        $subjects = Subject::withCount(['questions', 'exams'])->get();
        return view('admin.subject.index', compact('subjects'));
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:10|unique:subjects,code',
        ]);
        $subject = Subject::create($request->all());
        ActivityLog::log('subject_created', "Mata pelajaran dibuat: {$subject->name}", $subject);
        return redirect()->route('admin.subjects')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function updateSubject(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:10|unique:subjects,code,'.$subject->id,
        ]);
        $subject->update($request->all());
        ActivityLog::log('subject_updated', "Mata pelajaran diperbarui: {$subject->name}", $subject);
        return redirect()->route('admin.subjects')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroySubject(Subject $subject)
    {
        if ($subject->questions()->count() > 0 || $subject->exams()->count() > 0) {
            return back()->with('error', 'Mata pelajaran tidak dapat dihapus karena masih memiliki soal atau ujian.');
        }
        ActivityLog::log('subject_deleted', "Mata pelajaran dihapus: {$subject->name}");
        $subject->delete();
        return redirect()->route('admin.subjects')->with('success', 'Mata pelajaran berhasil dihapus.');
    }

    // ==================== ACADEMIC YEARS ====================

    public function academicYears()
    {
        $years = AcademicYear::withCount(['classrooms', 'exams'])->orderByDesc('start_year')->get();
        return view('admin.academic_year.index', compact('years'));
    }

    public function storeAcademicYear(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:20',
            'start_year' => 'required|integer|min:2020|max:2050',
            'end_year'   => 'required|integer|min:2020|max:2050',
            'semester'   => 'required|in:1,2',
        ]);
        $year = AcademicYear::create($request->all());
        ActivityLog::log('academic_year_created', "Tahun ajaran dibuat: {$year->name}", $year);
        return redirect()->route('admin.academic-years')->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function setActiveYear(AcademicYear $year)
    {
        AcademicYear::query()->update(['is_active' => false]);
        $year->update(['is_active' => true]);
        ActivityLog::log('academic_year_activated', "Tahun ajaran diaktifkan: {$year->name}", $year);
        return back()->with('success', "Tahun ajaran {$year->name} sekarang aktif.");
    }

    // ==================== EXAMS (Admin View) ====================

    public function allExams(Request $request)
    {
        $query = Exam::with(['subject', 'classroom', 'teacher']);
        if ($request->teacher_id)  $query->where('teacher_id', $request->teacher_id);
        if ($request->subject_id)  $query->where('subject_id', $request->subject_id);
        if ($request->classroom_id)$query->where('classroom_id', $request->classroom_id);
        if ($request->status)      $query->where('status', $request->status);
        $exams    = $query->latest()->paginate(20);
        $teachers = \App\Models\User::teachers()->get();
        $subjects = Subject::all();
        $classrooms = Classroom::with('academicYear')->get();
        return view('admin.exam.index', compact('exams', 'teachers', 'subjects', 'classrooms'));
    }

    public function archiveExam(Exam $exam)
    {
        $exam->update(['status' => 'archived']);
        ActivityLog::log('exam_archived', "Ujian diarsipkan: {$exam->title}", $exam);
        return back()->with('success', 'Ujian berhasil diarsipkan.');
    }

    public function destroyExam(Exam $exam)
    {
        ActivityLog::log('exam_deleted_by_admin', "Ujian dihapus paksa oleh Admin: {$exam->title}");
        $exam->delete();
        return back()->with('success', 'Ujian berhasil dihapus permanen dari sistem.');
    }

    // ==================== SETTINGS ====================

    public function settings()
    {
        $settings = SchoolSetting::orderBy('group')->get()->groupBy('group');
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        foreach ($request->except(['_token', '_method']) as $key => $value) {
            SchoolSetting::set($key, $value);
        }
        ActivityLog::log('settings_updated', 'Pengaturan sistem diperbarui.');
        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    // ==================== ACTIVITY LOGS ====================

    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user');
        if ($request->user_id) $query->where('user_id', $request->user_id);
        if ($request->action)  $query->where('action', 'like', '%'.$request->action.'%');
        $logs  = $query->latest()->paginate(30);
        $users = \App\Models\User::select('id', 'name', 'role')->get();
        return view('admin.activity_log', compact('logs', 'users'));
    }
}
