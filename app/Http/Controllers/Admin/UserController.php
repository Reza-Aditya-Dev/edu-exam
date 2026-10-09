<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classroom;
use App\Models\AcademicYear;
use App\Models\ActivityLog;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ==================== STUDENTS ====================

    public function students(Request $request)
    {
        $query = User::students()->with(['classrooms.academicYear', 'examResults', 'examParticipants']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('nis', 'like', '%'.$search.'%')
                  ->orWhere('nisn', 'like', '%'.$search.'%')
                  ->orWhere('email', 'like', '%'.$search.'%')
                  ->orWhere('username', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('classroom_id') && $request->classroom_id !== 'all') {
            $query->whereHas('classrooms', fn($q) => $q->where('classrooms.id', $request->classroom_id));
        }

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'active' || $request->status === '1') {
                $query->where('is_active', true);
            } elseif ($request->status === 'suspended' || $request->status === '0') {
                $query->where('is_active', false);
            } elseif ($request->status === 'remedial') {
                $query->whereHas('examResults', fn($q) => $q->where('pass_status', 'fail'));
            }
        }

        if ($request->filled('academic_year_id') && $request->academic_year_id !== 'all') {
            $query->whereHas('classrooms', fn($q) => $q->where('academic_year_id', $request->academic_year_id));
        }

        $perPage = in_array((int)$request->get('per_page', 10), [10, 25, 50, 100]) ? (int)$request->get('per_page', 10) : 10;
        $students = $query->latest()->paginate($perPage);

        // Stats calculation for cohort status badges
        $totalStudents = User::students()->count();
        $activeStudents = User::students()->where('is_active', true)->count();
        $activeClassrooms = Classroom::whereHas('students')->count();
        $remedialCount = User::students()->whereHas('examResults', fn($q) => $q->where('pass_status', 'fail'))->count();
        $suspendedCount = User::students()->where('is_active', false)->count();
        $activePct = $totalStudents > 0 ? round(($activeStudents / $totalStudents) * 100, 1) : 100;

        $stats = [
            'total_students'   => $totalStudents,
            'active_classrooms'=> $activeClassrooms,
            'active_students'  => $activeStudents,
            'active_pct'       => $activePct,
            'remedial_count'   => $remedialCount,
            'suspended_count'  => $suspendedCount,
        ];

        $classrooms = Classroom::active()->with('academicYear')->withCount('students')->get();
        $academicYears = AcademicYear::orderByDesc('is_active')->get();

        return view('admin.student.index', compact('students', 'classrooms', 'academicYears', 'stats'));
    }

    public function createStudent()
    {
        $classrooms = Classroom::active()
            ->with(['academicYear', 'homeroomTeacher'])
            ->orderBy('grade')
            ->orderBy('name')
            ->get();
        $academicYears = AcademicYear::orderByDesc('is_active')->get();
        $academicYear = AcademicYear::getActive();

        return view('admin.student.create', compact('classrooms', 'academicYears', 'academicYear'));
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'nis'          => 'required|string|max:50|unique:users,nis',
            'nisn'         => 'nullable|string|max:20|unique:users,nisn',
            'email'        => 'required|email|max:255|unique:users,email',
            'username'     => 'nullable|string|max:100|unique:users,username',
            'password'     => 'required|string|min:6',
            'gender'       => 'required|in:L,P',
            'classroom_id' => 'required|exists:classrooms,id',
            'birth_place'  => 'nullable|string|max:100',
            'birth_date'   => 'nullable|date',
            'religion'     => 'nullable|string|max:50',
            'phone'        => 'nullable|string|max:30',
            'address'      => 'nullable|string',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.required'         => 'Nama lengkap siswa wajib diisi.',
            'nis.required'          => 'Nomor Induk Siswa (NIS) wajib diisi.',
            'nis.unique'            => 'NIS ini sudah terdaftar untuk siswa lain.',
            'nisn.unique'           => 'NISN ini sudah terdaftar untuk siswa lain.',
            'email.required'        => 'Alamat email resmi siswa wajib diisi.',
            'email.email'           => 'Format alamat email tidak valid.',
            'email.unique'          => 'Email ini sudah digunakan oleh akun lain.',
            'username.unique'       => 'Username ini sudah digunakan.',
            'password.required'     => 'Kata sandi akun ujian CBT wajib diisi.',
            'password.min'          => 'Kata sandi minimal 6 karakter.',
            'gender.required'       => 'Jenis kelamin wajib dipilih.',
            'classroom_id.required' => 'Rombel / kelas wajib dipilih.',
            'classroom_id.exists'   => 'Kelas yang dipilih tidak ditemukan.',
            'avatar.image'          => 'Berkas foto harus berupa gambar.',
            'avatar.max'            => 'Ukuran foto maksimal 2MB.',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $username = $request->filled('username')
            ? $request->username
            : ($request->nis ?: strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $request->name)));

        $isActive = true;
        if ($request->input('save_action') === 'draft') {
            $isActive = false;
        } elseif ($request->has('is_active_submitted')) {
            $isActive = $request->has('is_active');
        }

        $student = User::create([
            'name'        => $request->name,
            'nis'         => $request->nis,
            'nisn'        => $request->nisn,
            'email'       => $request->email,
            'username'    => $username,
            'password'    => Hash::make($request->password),
            'role'        => 'student',
            'gender'      => $request->gender,
            'birth_place' => $request->birth_place,
            'birth_date'  => $request->birth_date,
            'religion'    => $request->religion,
            'phone'       => $request->phone,
            'address'     => $request->address,
            'avatar'      => $avatarPath,
            'is_active'   => $isActive,
        ]);

        $student->classrooms()->attach($request->classroom_id);

        $statusText = $isActive ? 'aktif' : 'sebagai draf';
        ActivityLog::log('student_created', "Siswa baru didaftarkan ({$statusText}): {$student->name} (NIS: {$student->nis})", $student);

        return redirect()->route('admin.students')->with('success', "Peserta didik baru {$student->name} berhasil ditambahkan ({$statusText}).");
    }

    public function editStudent(User $student)
    {
        $classrooms = Classroom::active()->with('academicYear')->orderBy('grade')->orderBy('name')->get();
        $student->load(['classrooms.academicYear']);
        return view('admin.student.edit', compact('student', 'classrooms'));
    }

    public function updateStudent(Request $request, User $student)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'nis'          => 'required|string|unique:users,nis,'.$student->id,
            'nisn'         => 'nullable|string|max:20|unique:users,nisn,'.$student->id,
            'email'        => 'required|email|unique:users,email,'.$student->id,
            'username'     => 'nullable|string|max:100|unique:users,username,'.$student->id,
            'gender'       => 'required|in:L,P',
            'classroom_id' => 'required|exists:classrooms,id',
            'phone'        => 'nullable|string|max:30',
            'address'      => 'nullable|string',
            'password'     => 'nullable|string|min:6',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.required'         => 'Nama lengkap siswa wajib diisi.',
            'nis.required'          => 'NIS (Nomor Induk Siswa) wajib diisi.',
            'nis.unique'            => 'NIS ini sudah digunakan oleh siswa lain.',
            'nisn.unique'           => 'NISN ini sudah digunakan oleh siswa lain.',
            'email.required'        => 'Alamat email wajib diisi.',
            'email.unique'          => 'Email sudah terdaftar untuk akun lain.',
            'username.unique'       => 'Username sudah digunakan.',
            'classroom_id.required' => 'Kelas siswa wajib dipilih.',
            'classroom_id.exists'   => 'Kelas yang dipilih tidak ditemukan.',
            'password.min'          => 'Kata sandi baru minimal 6 karakter jika ingin diubah.',
            'avatar.image'          => 'Berkas foto harus berupa gambar valid.',
            'avatar.max'            => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        $student->update([
            'name'        => $request->name,
            'nis'         => $request->nis,
            'nisn'        => $request->nisn,
            'email'       => $request->email,
            'username'    => $request->filled('username') ? $request->username : $student->username,
            'gender'      => $request->gender,
            'birth_place' => $request->birth_place,
            'birth_date'  => $request->birth_date,
            'religion'    => $request->religion,
            'phone'       => $request->phone,
            'address'     => $request->address,
            'is_active'   => $request->has('is_active') ? (bool)$request->is_active : false,
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $student->update(['avatar' => $avatarPath]);
        }

        if ($request->filled('password')) {
            $student->update(['password' => Hash::make($request->password)]);
        }

        // Siswa cuman memiliki satu kelas: sync dengan array tunggal
        if ($request->filled('classroom_id')) {
            $student->classrooms()->sync([$request->classroom_id]);
        }

        $currentClassroomName = $student->classrooms()->first()?->name ?? 'Tanpa Kelas';
        ActivityLog::log('student_updated', "Data siswa diperbarui: {$student->name} (Kelas: {$currentClassroomName})", $student);
        return redirect()->route('admin.students')->with('success', "Data siswa {$student->name} berhasil diperbarui.");
    }

    public function toggleStudent(User $student)
    {
        $student->update(['is_active' => !$student->is_active]);
        $action = $student->is_active ? 'diaktifkan' : 'dinonaktifkan';
        ActivityLog::log('student_toggled', "Akun siswa {$action}: {$student->name}", $student);
        return back()->with('success', "Akun siswa berhasil {$action}.");
    }

    public function destroyStudent(User $student)
    {
        $name = $student->name;
        $student->classrooms()->detach();
        $student->delete();

        ActivityLog::log('student_deleted', "Siswa {$name} berhasil dihapus permanen oleh admin.");
        return back()->with('success', "Data siswa {$name} berhasil dihapus.");
    }

    public function resetStudentPassword(Request $request, User $student)
    {
        $newPassword = $request->input('new_password', '123456');
        $student->update(['password' => Hash::make($newPassword)]);

        ActivityLog::log('student_password_reset', "Password siswa {$student->name} di-reset oleh admin.");
        return back()->with('success', "Kata sandi untuk {$student->name} berhasil di-reset menjadi: {$newPassword}");
    }

    public function bulkActionStudents(Request $request)
    {
        $action = $request->input('action');
        $ids = explode(',', $request->input('student_ids', ''));
        $ids = array_filter(array_map('trim', $ids), fn($id) => is_numeric($id));

        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal satu siswa untuk melakukan aksi massal.');
        }

        $count = count($ids);

        if ($action === 'delete') {
            User::whereIn('id', $ids)->where('role', 'student')->delete();
            ActivityLog::log('student_bulk_deleted', "Sebanyak {$count} siswa dihapus massal.");
            return back()->with('success', "Sebanyak {$count} siswa berhasil dihapus.");
        }

        if ($action === 'change_class') {
            $classroomId = $request->input('target_classroom_id');
            if (!$classroomId || !Classroom::find($classroomId)) {
                return back()->with('error', 'Kelas target wajib dipilih.');
            }
            $targetClass = Classroom::find($classroomId);
            foreach (User::whereIn('id', $ids)->where('role', 'student')->get() as $std) {
                $std->classrooms()->sync([$classroomId]);
            }
            ActivityLog::log('student_bulk_class_changed', "Sebanyak {$count} siswa dipindahkan ke kelas {$targetClass->name}.");
            return back()->with('success', "Sebanyak {$count} siswa berhasil dipindahkan ke kelas {$targetClass->name}.");
        }

        if ($action === 'activate') {
            User::whereIn('id', $ids)->where('role', 'student')->update(['is_active' => true]);
            ActivityLog::log('student_bulk_activated', "Sebanyak {$count} akun siswa diaktifkan.");
            return back()->with('success', "Sebanyak {$count} akun siswa berhasil diaktifkan.");
        }

        if ($action === 'deactivate') {
            User::whereIn('id', $ids)->where('role', 'student')->update(['is_active' => false]);
            ActivityLog::log('student_bulk_deactivated', "Sebanyak {$count} akun siswa dinonaktifkan.");
            return back()->with('success', "Sebanyak {$count} akun siswa berhasil dinonaktifkan.");
        }

        return back()->with('info', 'Aksi massal selesai.');
    }

    public function exportStudents(Request $request)
    {
        $query = User::students()->with(['classrooms.academicYear']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('nis', 'like', '%'.$search.'%')
                  ->orWhere('email', 'like', '%'.$search.'%')
                  ->orWhere('username', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('classroom_id') && $request->classroom_id !== 'all') {
            $query->whereHas('classrooms', fn($q) => $q->where('classrooms.id', $request->classroom_id));
        }

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'active' || $request->status === '1') {
                $query->where('is_active', true);
            } elseif ($request->status === 'suspended' || $request->status === '0') {
                $query->where('is_active', false);
            } elseif ($request->status === 'remedial') {
                $query->whereHas('examResults', fn($q) => $q->where('pass_status', 'fail'));
            }
        }

        if ($request->filled('academic_year_id') && $request->academic_year_id !== 'all') {
            $query->whereHas('classrooms', fn($q) => $q->where('academic_year_id', $request->academic_year_id));
        }

        $students = $query->orderBy('name')->get();
        $filename = 'data_siswa_cbt_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['No', 'NIS', 'Nama Siswa', 'Email', 'Username', 'Jenis Kelamin', 'Kelas Rombel', 'Status Akun', 'No Telepon', 'Alamat']);

            foreach ($students as $idx => $s) {
                fputcsv($file, [
                    $idx + 1,
                    $s->nis,
                    $s->name,
                    $s->email,
                    $s->username,
                    $s->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                    $s->classrooms->pluck('name')->join(', ') ?: 'Tanpa Kelas',
                    $s->is_active ? 'Aktif' : 'Nonaktif',
                    $s->phone ?? '-',
                    $s->address ?? '-',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadImportTemplate()
    {
        $filename = 'template_import_siswa_cbt.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['NIS', 'Nama Lengkap', 'Email', 'Username', 'Jenis Kelamin (L/P)', 'Password']);
            fputcsv($file, ['1002001', 'Ahmad Dani Pratama', 'ahmad.dani@sekolah.id', 'ahmaddani', 'L', '123456']);
            fputcsv($file, ['1002002', 'Siti Nurhaliza', 'siti.nurhaliza@sekolah.id', 'sitinurhaliza', 'P', '123456']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importStudents(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
            'classroom_id' => 'nullable|exists:classrooms,id',
        ], [
            'file.required' => 'Silakan pilih berkas CSV untuk diimpor.',
            'file.mimes'    => 'Berkas yang diunggah harus berekstensi .csv atau .txt.',
            'file.max'      => 'Ukuran berkas maksimal 5MB.',
        ]);

        $file = $request->file('file');
        $realPath = $file->getRealPath();

        // Detect delimiter (semi-colon vs comma)
        $firstLine = fgets(fopen($realPath, 'r'));
        $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';

        $handle = fopen($realPath, 'r');
        // Strip BOM if present
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $header = fgetcsv($handle, 0, $delimiter);
        $importedCount = 0;
        $updatedCount = 0;

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            if (count($row) < 2 || empty(trim($row[0] ?? ''))) continue;

            $nis      = trim($row[0] ?? '');
            $name     = trim($row[1] ?? '');
            $email    = trim($row[2] ?? '');
            $username = trim($row[3] ?? '');
            $gender   = strtoupper(trim($row[4] ?? 'L'));
            $password = trim($row[5] ?? '');

            // Fallback if 5 columns format: NIS, Nama, Email, Gender, Password
            if (in_array(strtoupper($username), ['L', 'P'])) {
                $gender = strtoupper($username);
                $username = $nis;
                $password = trim($row[4] ?? '');
            }

            if (empty($nis) || empty($name)) continue;

            if (!in_array($gender, ['L', 'P'])) $gender = 'L';
            if (empty($username)) $username = $nis;
            if (empty($email)) $email = "siswa_{$nis}@sekolah.id";
            if (empty($password)) $password = '123456';

            $existing = User::where('nis', $nis)->first();

            $user = User::updateOrCreate(
                ['nis' => $nis],
                [
                    'name'      => $name,
                    'email'     => $email,
                    'username'  => $username,
                    'gender'    => $gender,
                    'password'  => Hash::make($password),
                    'role'      => 'student',
                    'is_active' => true,
                ]
            );

            if ($request->filled('classroom_id')) {
                $user->classrooms()->sync([$request->classroom_id]);
            }

            if ($existing) {
                $updatedCount++;
            } else {
                $importedCount++;
            }
        }
        fclose($handle);

        ActivityLog::log('student_imported', "Impor siswa: {$importedCount} data baru, {$updatedCount} data diperbarui.");
        return back()->with('success', "Proses impor selesai: {$importedCount} siswa baru berhasil ditambahkan, {$updatedCount} data siswa diperbarui.");
    }

    public function printExamCards(Request $request)
    {
        $query = User::students()->with(['classrooms.academicYear']);

        if ($request->filled('ids')) {
            $ids = explode(',', $request->ids);
            $ids = array_filter(array_map('trim', $ids), fn($id) => is_numeric($id));
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        } elseif ($request->filled('classroom_id') && $request->classroom_id !== 'all') {
            $query->whereHas('classrooms', fn($q) => $q->where('classrooms.id', $request->classroom_id));
        }

        $students = $query->orderBy('name')->get();
        $schoolName = SchoolSetting::get('school_name', 'SMA Nusantara');
        $schoolNpsn = SchoolSetting::get('school_npsn', '20103482');
        $academicYear = AcademicYear::getActive();

        return view('admin.student.print_cards', compact('students', 'schoolName', 'schoolNpsn', 'academicYear'));
    }

    public function syncAccounts()
    {
        $students = User::students()->get();
        $updated = 0;

        foreach ($students as $student) {
            $dirty = false;
            if (empty($student->username) && !empty($student->nis)) {
                $student->username = $student->nis;
                $dirty = true;
            }
            if (empty($student->email) && !empty($student->nis)) {
                $student->email = "siswa_{$student->nis}@sekolah.id";
                $dirty = true;
            }
            if ($dirty) {
                $student->save();
                $updated++;
            }
        }

        ActivityLog::log('student_synced', "Sinkronisasi akun CBT peserta didik berhasil: {$students->count()} akun diverifikasi.");
        return back()->with('success', "Sinkronisasi akun selesai. Seluruh ({$students->count()}) akun login CBT siswa telah diperiksa & disinkronkan.");
    }

    // ==================== TEACHERS ====================

    public function teachers(Request $request)
    {
        $query = User::teachers()
            ->with(['subjects', 'homeroomClassrooms'])
            ->withCount(['questions', 'createdExams']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('nip', 'like', '%'.$search.'%')
                  ->orWhere('email', 'like', '%'.$search.'%')
                  ->orWhere('username', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('subject_id') && $request->subject_id !== 'all') {
            $query->whereHas('subjects', function($q) use ($request) {
                $q->where('subjects.id', $request->subject_id);
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $isActive = ($request->status === '1' || $request->status === 'active');
            $query->where('is_active', $isActive);
        }

        $perPage = in_array((int)$request->per_page, [6, 10, 20, 50]) ? (int)$request->per_page : 10;
        $teachers = $query->latest()->paginate($perPage)->withQueryString();

        $totalTeachers    = User::teachers()->count();
        $activeTeachers   = User::teachers()->where('is_active', true)->count();
        $inactiveTeachers = User::teachers()->where('is_active', false)->count();
        $teachersWithNip  = User::teachers()->whereNotNull('nip')->where('nip', '!=', '')->count();
        $activeRatio      = $totalTeachers > 0 ? round(($activeTeachers / $totalTeachers) * 100, 1) : 0;
        $totalSubjects    = \App\Models\Subject::count();
        $totalQuestions   = \App\Models\Question::count();
        $allSubjects      = \App\Models\Subject::orderBy('name')->get();

        return view('admin.teacher.index', compact(
            'teachers', 'totalTeachers', 'activeTeachers', 'inactiveTeachers',
            'teachersWithNip', 'activeRatio', 'totalSubjects', 'totalQuestions', 'allSubjects'
        ));
    }

    public function createTeacher()
    {
        $subjects = \App\Models\Subject::active()->orderBy('name')->get();
        $classrooms = \App\Models\Classroom::active()->with('academicYear')->orderBy('grade')->orderBy('name')->get();
        return view('admin.teacher.create', compact('subjects', 'classrooms'));
    }

    public function storeTeacher(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'nip'                   => 'required|string|unique:users,nip',
            'email'                 => 'required|email|unique:users,email',
            'username'              => 'nullable|string|max:100|unique:users,username',
            'password'              => 'required|string|min:6',
            'gender'                => 'required|in:L,P,laki-laki,perempuan',
            'subject_ids'           => 'required|array|min:1',
            'subject_ids.*'         => 'exists:subjects,id',
            'phone'                 => 'nullable|string|max:30',
            'avatar'                => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'classroom_ids'         => 'nullable|array',
            'classroom_ids.*'       => 'exists:classrooms,id',
            'homeroom_classroom_id' => 'nullable|exists:classrooms,id',
        ], [
            'name.required'        => 'Nama lengkap beserta gelar wajib diisi.',
            'nip.required'         => 'Nomor Induk Pegawai (NIP) wajib diisi.',
            'nip.unique'           => 'NIP ini sudah terdaftar di sistem.',
            'email.required'       => 'Email resmi sekolah wajib diisi.',
            'email.unique'         => 'Email ini sudah digunakan oleh akun lain.',
            'username.unique'      => 'Username ini sudah digunakan.',
            'password.required'    => 'Kata sandi akun wajib diisi.',
            'password.min'         => 'Kata sandi minimal 6 karakter.',
            'subject_ids.required' => 'Pilih minimal satu mata pelajaran yang diampu.',
            'avatar.max'           => 'Ukuran pasfoto maksimal 2 MB.',
        ]);

        // Auto-generate username if not provided
        $username = $request->filled('username')
            ? \Illuminate\Support\Str::slug($request->username, '.')
            : \Illuminate\Support\Str::slug(explode('@', $request->email)[0], '.');
        $baseUsername = $username ?: 'guru';
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter++;
        }

        // Normalize gender to 'L' or 'P'
        $gender = in_array(strtoupper(substr($request->gender, 0, 1)), ['L', 'P']) ? strtoupper(substr($request->gender, 0, 1)) : 'L';

        // Avatar upload
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $teacher = User::create([
            'name'      => $request->name,
            'nip'       => $request->nip,
            'email'     => $request->email,
            'username'  => $username,
            'password'  => Hash::make($request->password),
            'role'      => 'teacher',
            'gender'    => $gender,
            'phone'     => $request->phone,
            'avatar'    => $avatarPath,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        // Attach subjects
        $teacher->subjects()->attach($request->subject_ids);

        // Attach teaching classrooms if selected
        if ($request->filled('classroom_ids') && is_array($request->classroom_ids) && !empty($request->subject_ids)) {
            $primarySubjectId = $request->subject_ids[0];
            foreach ($request->classroom_ids as $classroomId) {
                \Illuminate\Support\Facades\DB::table('teacher_classroom')->updateOrInsert(
                    ['teacher_id' => $teacher->id, 'classroom_id' => $classroomId, 'subject_id' => $primarySubjectId],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }

        // Set homeroom teacher if checked
        if ($request->has('is_homeroom') && $request->filled('homeroom_classroom_id')) {
            Classroom::where('id', $request->homeroom_classroom_id)->update([
                'homeroom_teacher_id' => $teacher->id,
            ]);
        }

        ActivityLog::log('teacher_created', "Guru baru ditambahkan: {$teacher->name} (NIP: {$teacher->nip})", $teacher);
        return redirect()->route('admin.teachers')->with('success', "Guru {$teacher->name} berhasil ditambahkan ke sistem.");
    }

    public function editTeacher(User $teacher)
    {
        $subjects = \App\Models\Subject::active()->get();
        $teacher->load('subjects');
        return view('admin.teacher.edit', compact('teacher', 'subjects'));
    }

    public function updateTeacher(Request $request, User $teacher)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'nip'   => 'required|string|unique:users,nip,'.$teacher->id,
            'email' => 'required|email|unique:users,email,'.$teacher->id,
        ]);

        $teacher->update($request->only(['name', 'nip', 'email', 'username', 'gender', 'is_active']));
        if ($request->password) {
            $teacher->update(['password' => Hash::make($request->password)]);
        }
        if ($request->subject_ids) {
            $teacher->subjects()->sync($request->subject_ids);
        }

        ActivityLog::log('teacher_updated', "Data guru diperbarui: {$teacher->name}", $teacher);
        return redirect()->route('admin.teachers')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function toggleTeacher(User $teacher)
    {
        $teacher->update(['is_active' => !$teacher->is_active]);
        $action = $teacher->is_active ? 'diaktifkan' : 'dinonaktifkan';
        ActivityLog::log('teacher_toggled', "Akun guru {$action}: {$teacher->name}", $teacher);
        return back()->with('success', "Akun guru berhasil {$action}.");
    }
}
