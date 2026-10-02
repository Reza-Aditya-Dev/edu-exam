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
        $classrooms = Classroom::active()->with('academicYear')->get();
        $student->load('classrooms');
        return view('admin.student.edit', compact('student', 'classrooms'));
    }

    public function updateStudent(Request $request, User $student)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'nis'   => 'required|string|unique:users,nis,'.$student->id,
            'email' => 'required|email|unique:users,email,'.$student->id,
        ]);

        $student->update($request->only(['name', 'nis', 'email', 'username', 'gender', 'phone', 'address', 'is_active']));

        if ($request->password) {
            $student->update(['password' => Hash::make($request->password)]);
        }
        if ($request->classroom_id) {
            $student->classrooms()->sync([$request->classroom_id]);
        }

        ActivityLog::log('student_updated', "Data siswa diperbarui: {$student->name}", $student);
        return redirect()->route('admin.students')->with('success', 'Data siswa berhasil diperbarui.');
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
        $query = User::teachers()->with('subjects');
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('nip', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }
        $teachers = $query->latest()->paginate(20);
        return view('admin.teacher.index', compact('teachers'));
    }

    public function createTeacher()
    {
        $subjects = \App\Models\Subject::active()->get();
        return view('admin.teacher.create', compact('subjects'));
    }

    public function storeTeacher(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'nip'        => 'required|string|unique:users,nip',
            'email'      => 'required|email|unique:users,email',
            'username'   => 'required|string|unique:users,username',
            'password'   => 'required|string|min:6',
            'gender'     => 'required|in:L,P',
            'subject_ids'=> 'required|array',
        ]);

        $teacher = User::create([
            'name'      => $request->name,
            'nip'       => $request->nip,
            'email'     => $request->email,
            'username'  => $request->username,
            'password'  => Hash::make($request->password),
            'role'      => 'teacher',
            'gender'    => $request->gender,
            'is_active' => true,
        ]);

        $teacher->subjects()->attach($request->subject_ids);

        ActivityLog::log('teacher_created', "Guru baru ditambahkan: {$teacher->name}", $teacher);
        return redirect()->route('admin.teachers')->with('success', 'Guru berhasil ditambahkan.');
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
