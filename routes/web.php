<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Student;
use App\Http\Controllers\Teacher;
use App\Http\Controllers\Admin;

// ═══════════════════════════════════════════════════
// PUBLIC ROUTES
// ═══════════════════════════════════════════════════

Route::get('/', function () { return view('welcome'); })->name('home');

// ═══════════════════════════════════════════════════
// AUTHENTICATION
// ═══════════════════════════════════════════════════

Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ═══════════════════════════════════════════════════
// STUDENT ROUTES
// ═══════════════════════════════════════════════════

Route::middleware(['auth', 'role:student'])->prefix('siswa')->name('student.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [Student\DashboardController::class, 'index'])->name('dashboard');

    // Profil siswa
    Route::get('/profil', function() { return view('student.profile'); })->name('profile');

    // Ujian
    Route::prefix('ujian')->name('exam.')->group(function () {
        Route::get('/{exam}',          [Student\ExamController::class, 'show'])->name('show');
        Route::post('/{exam}/mulai',   [Student\ExamController::class, 'start'])->name('start');
        Route::get('/{exam}/kerjakan', [Student\ExamController::class, 'take'])->name('take');
        Route::post('/{exam}/jawab',   [Student\ExamController::class, 'saveAnswer'])->name('answer');
        Route::post('/{exam}/kumpul',  [Student\ExamController::class, 'submit'])->name('submit');
        Route::get('/{exam}/hasil',    [Student\ExamController::class, 'result'])->name('result');
    });

    // Riwayat
    Route::get('/riwayat', [Student\ExamController::class, 'history'])->name('history');
});

// ═══════════════════════════════════════════════════
// TEACHER ROUTES
// ═══════════════════════════════════════════════════

Route::middleware(['auth', 'role:teacher'])->prefix('guru')->name('teacher.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [Teacher\DashboardController::class, 'index'])->name('dashboard');

    // Bank Soal
    Route::prefix('soal')->name('questions.')->group(function () {
        Route::get('/',                [Teacher\QuestionController::class, 'index'])->name('index');
        Route::get('/buat',            [Teacher\QuestionController::class, 'create'])->name('create');
        Route::post('/',               [Teacher\QuestionController::class, 'store'])->name('store');
        Route::get('/{question}/edit', [Teacher\QuestionController::class, 'edit'])->name('edit');
        Route::put('/{question}',      [Teacher\QuestionController::class, 'update'])->name('update');
        Route::delete('/{question}',   [Teacher\QuestionController::class, 'destroy'])->name('destroy');
        Route::post('/{question}/duplikat', [Teacher\QuestionController::class, 'duplicate'])->name('duplicate');
    });

    // Ujian
    Route::prefix('ujian')->name('exams.')->group(function () {
        Route::get('/',                [Teacher\ExamController::class, 'index'])->name('index');
        Route::get('/buat',            [Teacher\ExamController::class, 'create'])->name('create');
        Route::post('/',               [Teacher\ExamController::class, 'store'])->name('store');
        Route::get('/{exam}/edit',     [Teacher\ExamController::class, 'edit'])->name('edit');
        Route::put('/{exam}',          [Teacher\ExamController::class, 'update'])->name('update');
        Route::delete('/{exam}',       [Teacher\ExamController::class, 'destroy'])->name('destroy');
        Route::post('/{exam}/terbitkan', [Teacher\ExamController::class, 'publish'])->name('publish');
        Route::post('/{exam}/aktifkan',  [Teacher\ExamController::class, 'activate'])->name('activate');
        Route::post('/{exam}/selesai',   [Teacher\ExamController::class, 'complete'])->name('complete');
        Route::get('/{exam}/hasil',      [Teacher\ExamController::class, 'results'])->name('results');
        Route::get('/{exam}/siswa/{studentId}', [Teacher\ExamController::class, 'studentAnswerDetail'])->name('student_detail');
        Route::get('/{exam}/analitik',   [Teacher\ExamController::class, 'analytics'])->name('analytics');
    });

    // API internal untuk ambil soal (AJAX)
    Route::get('/api/soal', [Teacher\ExamController::class, 'getQuestions'])->name('api.questions');

    // Profil guru
    Route::get('/profil', function() { return view('teacher.profile'); })->name('profile');
});

// ═══════════════════════════════════════════════════
// ADMIN ROUTES
// ═══════════════════════════════════════════════════

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Siswa
    Route::get('/siswa',                    [Admin\UserController::class, 'students'])->name('students');
    Route::get('/siswa/tambah',             [Admin\UserController::class, 'createStudent'])->name('students.create');
    Route::post('/siswa',                   [Admin\UserController::class, 'storeStudent'])->name('students.store');
    Route::get('/siswa/{student}/edit',     [Admin\UserController::class, 'editStudent'])->name('students.edit');
    Route::put('/siswa/{student}',          [Admin\UserController::class, 'updateStudent'])->name('students.update');
    Route::post('/siswa/{student}/toggle',  [Admin\UserController::class, 'toggleStudent'])->name('students.toggle');

    // Manajemen Guru
    Route::get('/guru',                     [Admin\UserController::class, 'teachers'])->name('teachers');
    Route::get('/guru/tambah',              [Admin\UserController::class, 'createTeacher'])->name('teachers.create');
    Route::post('/guru',                    [Admin\UserController::class, 'storeTeacher'])->name('teachers.store');
    Route::get('/guru/{teacher}/edit',      [Admin\UserController::class, 'editTeacher'])->name('teachers.edit');
    Route::put('/guru/{teacher}',           [Admin\UserController::class, 'updateTeacher'])->name('teachers.update');
    Route::post('/guru/{teacher}/toggle',   [Admin\UserController::class, 'toggleTeacher'])->name('teachers.toggle');

    // Manajemen Kelas
    Route::get('/kelas',                    [Admin\ManagementController::class, 'classrooms'])->name('classrooms');
    Route::get('/kelas/tambah',             [Admin\ManagementController::class, 'createClassroom'])->name('classrooms.create');
    Route::post('/kelas',                   [Admin\ManagementController::class, 'storeClassroom'])->name('classrooms.store');
    Route::get('/kelas/{classroom}/edit',   [Admin\ManagementController::class, 'editClassroom'])->name('classrooms.edit');
    Route::put('/kelas/{classroom}',        [Admin\ManagementController::class, 'updateClassroom'])->name('classrooms.update');
    Route::delete('/kelas/{classroom}',     [Admin\ManagementController::class, 'destroyClassroom'])->name('classrooms.destroy');

    // Mata Pelajaran
    Route::get('/mapel',                    [Admin\ManagementController::class, 'subjects'])->name('subjects');
    Route::post('/mapel',                   [Admin\ManagementController::class, 'storeSubject'])->name('subjects.store');
    Route::put('/mapel/{subject}',          [Admin\ManagementController::class, 'updateSubject'])->name('subjects.update');
    Route::delete('/mapel/{subject}',       [Admin\ManagementController::class, 'destroySubject'])->name('subjects.destroy');

    // Tahun Ajaran
    Route::get('/tahun-ajaran',             [Admin\ManagementController::class, 'academicYears'])->name('academic-years');
    Route::post('/tahun-ajaran',            [Admin\ManagementController::class, 'storeAcademicYear'])->name('academic-years.store');
    Route::post('/tahun-ajaran/{year}/aktif', [Admin\ManagementController::class, 'setActiveYear'])->name('academic-years.activate');

    // Semua Ujian
    Route::get('/ujian',                    [Admin\ManagementController::class, 'allExams'])->name('exams');
    Route::post('/ujian/{exam}/arsip',      [Admin\ManagementController::class, 'archiveExam'])->name('exams.archive');
    Route::delete('/ujian/{exam}',          [Admin\ManagementController::class, 'destroyExam'])->name('exams.destroy');

    // Pengaturan
    Route::get('/pengaturan',               [Admin\ManagementController::class, 'settings'])->name('settings');
    Route::post('/pengaturan',              [Admin\ManagementController::class, 'updateSettings'])->name('settings.update');

    // Log Aktivitas
    Route::get('/log',                      [Admin\ManagementController::class, 'activityLogs'])->name('logs');
});
