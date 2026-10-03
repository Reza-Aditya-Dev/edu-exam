<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classroom;
use App\Models\AcademicYear;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ==================== STUDENTS ====================

    public function students(Request $request)
    {
        $query = User::students()->with('classrooms.academicYear');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('nis', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->classroom_id) {
            $query->whereHas('classrooms', fn($q) => $q->where('classrooms.id', $request->classroom_id));
        }
        if ($request->status !== null && $request->status !== '') {
            $query->where('is_active', $request->status === '1');
        }

        $students   = $query->latest()->paginate(20);
        $classrooms = Classroom::active()->with('academicYear')->get();
        return view('admin.student.index', compact('students', 'classrooms'));
    }

    public function createStudent()
    {
        $classrooms   = Classroom::active()->with('academicYear')->get();
        $academicYear = AcademicYear::getActive();
        return view('admin.student.create', compact('classrooms', 'academicYear'));
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'nis'         => 'required|string|unique:users,nis',
            'email'       => 'required|email|unique:users,email',
            'username'    => 'required|string|unique:users,username',
            'password'    => 'required|string|min:6',
            'gender'      => 'required|in:L,P',
            'classroom_id'=> 'required|exists:classrooms,id',
        ]);

        $student = User::create([
            'name'      => $request->name,
            'nis'       => $request->nis,
            'email'     => $request->email,
            'username'  => $request->username,
            'password'  => Hash::make($request->password),
            'role'      => 'student',
            'gender'    => $request->gender,
            'phone'     => $request->phone,
            'address'   => $request->address,
            'is_active' => true,
        ]);

        $student->classrooms()->attach($request->classroom_id);

        ActivityLog::log('student_created', "Siswa baru ditambahkan: {$student->name}", $student);
        return redirect()->route('admin.students')->with('success', 'Siswa berhasil ditambahkan.');
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
            'email'        => 'required|email|unique:users,email,'.$student->id,
            'username'     => 'nullable|string|max:100|unique:users,username,'.$student->id,
            'gender'       => 'required|in:L,P',
            'classroom_id' => 'required|exists:classrooms,id',
            'phone'        => 'nullable|string|max:30',
            'address'      => 'nullable|string',
            'password'     => 'nullable|string|min:6',
        ], [
            'name.required'         => 'Nama lengkap siswa wajib diisi.',
            'nis.required'          => 'NIS (Nomor Induk Siswa) wajib diisi.',
            'nis.unique'            => 'NIS ini sudah digunakan oleh siswa lain.',
            'email.required'        => 'Alamat email wajib diisi.',
            'email.unique'          => 'Email sudah terdaftar untuk akun lain.',
            'username.unique'       => 'Username sudah digunakan.',
            'classroom_id.required' => 'Kelas siswa wajib dipilih.',
            'classroom_id.exists'   => 'Kelas yang dipilih tidak ditemukan.',
            'password.min'          => 'Kata sandi baru minimal 6 karakter jika ingin diubah.',
        ]);

        $student->update([
            'name'      => $request->name,
            'nis'       => $request->nis,
            'email'     => $request->email,
            'username'  => $request->filled('username') ? $request->username : $student->username,
            'gender'    => $request->gender,
            'phone'     => $request->phone,
            'address'   => $request->address,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : false,
        ]);

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
