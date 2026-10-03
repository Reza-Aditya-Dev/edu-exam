@extends('layouts.admin')

@section('title', 'Dashboard Administrator — EduExam')
@section('page_title', 'Dashboard Administrator')

@section('admin-content')
<div class="flex flex-col w-full">
    <!-- Sub-header / Page Title & Actions -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-space-md mb-space-xl">
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-2 text-on-surface-variant font-label-md text-label-md">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors cursor-pointer">Beranda</a>
                <span class="material-symbols-outlined text-[14px] text-outline">chevron_right</span>
                <span class="text-primary font-semibold">Dashboard Administrator</span>
            </div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">Dashboard Administrator</h1>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">
                Pusat kendali operasional CBT, pemantauan data master sekolah, dan rekapitulasi evaluasi pembelajaran {{ \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}.
            </p>
        </div>
        <!-- Action Buttons -->
        <div class="flex items-center gap-space-sm self-start lg:self-auto flex-wrap">
            <button onclick="openReportModal()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-surface-container text-on-surface font-label-lg text-label-lg shadow-sm hover:bg-surface-container-high transition-all active:scale-[0.98]" type="button">
                <span class="material-symbols-outlined text-[18px] text-primary">picture_as_pdf</span>
                <span>Unduh Rekap Sekolah (PDF)</span>
            </button>
            <a href="{{ route('admin.exams') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg shadow-md hover:bg-primary transition-all active:scale-[0.98]">
                <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                <span>Manajemen Ujian</span>
            </a>
        </div>
    </div>

    <!-- Quick Shortcuts Navigation Strip -->
    <div class="p-space-sm rounded-xl bg-surface-container-low mb-space-xl flex items-center justify-between gap-2 overflow-x-auto border border-slate-200/70">
        <div class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant px-2 shrink-0">
            <span class="material-symbols-outlined text-[18px] text-primary">bolt</span>
            <span>Aksi Cepat:</span>
        </div>
        <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap shrink-0">
            <a href="{{ route('admin.students.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container-lowest hover:bg-surface-container text-on-surface text-xs font-semibold shadow-xs transition-colors border border-slate-200/80">
                <span class="material-symbols-outlined text-[16px] text-secondary">person_add</span>
                <span>Tambah Siswa</span>
            </a>
            <a href="{{ route('admin.teachers.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container-lowest hover:bg-surface-container text-on-surface text-xs font-semibold shadow-xs transition-colors border border-slate-200/80">
                <span class="material-symbols-outlined text-[16px] text-primary">school</span>
                <span>Tambah Guru</span>
            </a>
            <a href="{{ route('admin.classrooms.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container-lowest hover:bg-surface-container text-on-surface text-xs font-semibold shadow-xs transition-colors border border-slate-200/80">
                <span class="material-symbols-outlined text-[16px] text-primary">meeting_room</span>
                <span>Tambah Kelas</span>
            </a>
            <a href="{{ route('admin.subjects') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container-lowest hover:bg-surface-container text-on-surface text-xs font-semibold shadow-xs transition-colors border border-slate-200/80">
                <span class="material-symbols-outlined text-[16px] text-secondary">menu_book</span>
                <span>Kelola Mapel</span>
            </a>
            <a href="{{ route('admin.settings') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container-lowest hover:bg-surface-container text-on-surface text-xs font-semibold shadow-xs transition-colors border border-slate-200/80">
                <span class="material-symbols-outlined text-[16px] text-outline">settings</span>
                <span>Pengaturan CBT</span>
            </a>
        </div>
    </div>

    <!-- Key Metrics Cards (5-Grid) - Fully Clickable & Grounded in Real Data -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-space-md mb-space-xl">
        <!-- 1. Total Siswa -->
        <a href="{{ route('admin.students') }}" class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex flex-col justify-between relative overflow-hidden group hover:shadow-md hover:border-primary/40 transition-all border border-slate-100">
            <div class="absolute -right-3 -top-3 w-16 h-16 bg-surface-container rounded-full opacity-60 pointer-events-none group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center justify-between mb-3">
                <span class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-on-primary transition-colors">
                    <span class="material-symbols-outlined text-[22px]">group</span>
                </span>
                <span class="px-2 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant font-label-sm text-label-sm font-semibold">
                    {{ $stats['active_students'] }} Aktif
                </span>
            </div>
            <div>
                <span class="font-label-md text-label-md text-on-surface-variant font-medium flex items-center justify-between">
                    <span>Total Siswa Terdaftar</span>
                    <span class="material-symbols-outlined text-[16px] text-outline opacity-0 group-hover:opacity-100 transition-opacity">arrow_forward</span>
                </span>
                <div class="flex items-baseline gap-2 mt-0.5">
                    <span class="font-headline-lg text-headline-lg text-on-surface font-bold">{{ number_format($stats['total_students']) }}</span>
                    @if($stats['new_students'] > 0)
                        <span class="font-label-sm text-label-sm text-secondary font-medium flex items-center">
                            <span class="material-symbols-outlined text-[14px]">trending_up</span> +{{ $stats['new_students'] }} baru
                        </span>
                    @else
                        <span class="font-label-sm text-label-sm text-outline font-normal">Siswa terdaftar</span>
                    @endif
                </div>
            </div>
        </a>

        <!-- 2. Total Guru -->
        <a href="{{ route('admin.teachers') }}" class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex flex-col justify-between relative overflow-hidden group hover:shadow-md hover:border-primary/40 transition-all border border-slate-100">
            <div class="absolute -right-3 -top-3 w-16 h-16 bg-surface-container-high rounded-full opacity-60 pointer-events-none group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center justify-between mb-3">
                <span class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center text-primary-container group-hover:bg-primary-container group-hover:text-on-primary transition-colors">
                    <span class="material-symbols-outlined text-[22px]">school</span>
                </span>
                <span class="px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">
                    {{ $stats['active_teachers'] }} Aktif
                </span>
            </div>
            <div>
                <span class="font-label-md text-label-md text-on-surface-variant font-medium flex items-center justify-between">
                    <span>Total Guru Pengajar</span>
                    <span class="material-symbols-outlined text-[16px] text-outline opacity-0 group-hover:opacity-100 transition-opacity">arrow_forward</span>
                </span>
                <div class="flex items-baseline gap-2 mt-0.5">
                    <span class="font-headline-lg text-headline-lg text-on-surface font-bold">{{ number_format($stats['total_teachers']) }}</span>
                    <span class="font-label-sm text-label-sm text-outline font-normal">{{ $stats['teachers_with_nip'] }} Memiliki NIP</span>
                </div>
            </div>
        </a>

        <!-- 3. Total Kelas -->
        <a href="{{ route('admin.classrooms') }}" class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex flex-col justify-between relative overflow-hidden group hover:shadow-md hover:border-primary/40 transition-all border border-slate-100">
            <div class="absolute -right-3 -top-3 w-16 h-16 bg-surface-container rounded-full opacity-60 pointer-events-none group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center justify-between mb-3">
                <span class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-on-primary transition-colors">
                    <span class="material-symbols-outlined text-[22px]">meeting_room</span>
                </span>
                <span class="px-2 py-0.5 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-semibold">
                    {{ $stats['grades_list'] }}
                </span>
            </div>
            <div>
                <span class="font-label-md text-label-md text-on-surface-variant font-medium flex items-center justify-between">
                    <span>Rombongan Belajar</span>
                    <span class="material-symbols-outlined text-[16px] text-outline opacity-0 group-hover:opacity-100 transition-opacity">arrow_forward</span>
                </span>
                <div class="flex items-baseline gap-2 mt-0.5">
                    <span class="font-headline-lg text-headline-lg text-on-surface font-bold">{{ number_format($stats['total_classrooms']) }}</span>
                    <span class="font-label-sm text-label-sm text-outline font-normal">{{ number_format($stats['total_capacity']) }} Kuota Kursi</span>
                </div>
            </div>
        </a>

        <!-- 4. Total Mata Pelajaran -->
        <a href="{{ route('admin.subjects') }}" class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex flex-col justify-between relative overflow-hidden group hover:shadow-md hover:border-primary/40 transition-all border border-slate-100">
            <div class="absolute -right-3 -top-3 w-16 h-16 bg-surface-container-low rounded-full opacity-60 pointer-events-none group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center justify-between mb-3">
                <span class="w-10 h-10 rounded-lg bg-surface-container-low flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-on-primary transition-colors">
                    <span class="material-symbols-outlined text-[22px]">menu_book</span>
                </span>
                <span class="px-2 py-0.5 rounded-full bg-surface-container-high text-primary font-label-sm text-label-sm font-semibold">
                    {{ number_format($stats['total_questions']) }} Soal
                </span>
            </div>
            <div>
                <span class="font-label-md text-label-md text-on-surface-variant font-medium flex items-center justify-between">
                    <span>Mata Pelajaran</span>
                    <span class="material-symbols-outlined text-[16px] text-outline opacity-0 group-hover:opacity-100 transition-opacity">arrow_forward</span>
                </span>
                <div class="flex items-baseline gap-2 mt-0.5">
                    <span class="font-headline-lg text-headline-lg text-on-surface font-bold">{{ number_format($stats['total_subjects']) }}</span>
                    <span class="font-label-sm text-label-sm text-outline font-normal">{{ $stats['total_exams'] }} Sesi Evaluasi</span>
                </div>
            </div>
        </a>

        <!-- 5. Ujian Aktif (Live Pulse) -->
        <a href="{{ route('admin.exams') }}" class="p-space-md rounded-xl bg-gradient-to-br from-primary-container to-primary text-on-primary shadow-md flex flex-col justify-between relative overflow-hidden group hover:shadow-lg transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="w-10 h-10 rounded-lg bg-on-primary/10 backdrop-blur-sm flex items-center justify-center text-on-primary">
                    <span class="material-symbols-outlined text-[22px] {{ $stats['active_exams'] > 0 ? 'animate-pulse' : '' }}">sensors</span>
                </span>
                @if($stats['active_exams'] > 0)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed font-label-sm text-label-sm font-bold shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-secondary animate-ping"></span>
                        Live Sekarang
                    </span>
                @elseif($stats['scheduled_exams'] > 0)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-white/20 text-on-primary font-label-sm text-label-sm font-semibold">
                        {{ $stats['scheduled_exams'] }} Terjadwal
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-white/20 text-on-primary font-label-sm text-label-sm font-semibold">
                        {{ $stats['completed_exams'] }} Selesai
                    </span>
                @endif
            </div>
            <div>
                <span class="font-label-md text-label-md text-on-primary-container flex items-center justify-between">
                    <span>Sesi Ujian Aktif</span>
                    <span class="material-symbols-outlined text-[16px] text-white opacity-0 group-hover:opacity-100 transition-opacity">arrow_forward</span>
                </span>
                <div class="flex items-baseline justify-between gap-1 mt-0.5">
                    <span class="font-headline-lg text-headline-lg text-on-primary font-bold">{{ $stats['active_exams'] }} Sesi</span>
                    <span class="font-label-sm text-label-sm text-on-primary-container font-semibold">
                        @if($stats['active_students_now'] > 0)
                            {{ $stats['active_students_now'] }} siswa daring
                        @else
                            Sistem Standby
                        @endif
                    </span>
                </div>
            </div>
        </a>
    </div>

    <!-- Main Asymmetric Grid Layout -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-space-lg items-start">
        <!-- Left Column (Wide, 8 Cols) -->
        <div class="xl:col-span-8 flex flex-col gap-space-lg">
            
            <!-- Section: Ujian Aktif & Sedang Berlangsung / Terjadwal -->
            <div class="flex flex-col gap-space-md">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-6 rounded-full bg-primary"></span>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">
                            @if($activeExams->count() > 0)
                                Sesi Ujian Sedang Berlangsung (Live)
                            @elseif($upcomingExams->count() > 0)
                                Sesi Ujian Terjadwal Mendatang
                            @else
                                Pemantauan Sesi Ujian
                            @endif
                        </h2>
                    </div>
                    <a class="font-label-md text-label-md text-primary hover:underline flex items-center gap-1 font-semibold" href="{{ route('admin.exams') }}">
                        Kelola Semua Ujian <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>

                @if($activeExams->count() > 0)
                    <!-- Active Exams Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
                        @foreach($activeExams as $exam)
                            @php
                                $totalClassStudents = $exam->classroom ? $exam->classroom->students()->count() : max(1, $stats['total_students']);
                                $examParticipantCount = $exam->participants->count();
                                $presencePct = $totalClassStudents > 0 ? min(100, round(($examParticipantCount / $totalClassStudents) * 100, 1)) : 0;
                            @endphp
                            <div class="p-space-lg rounded-xl bg-surface-container-lowest shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow relative border border-slate-100">
                                <div>
                                    <div class="flex items-start justify-between gap-2 mb-3">
                                        <span class="px-2.5 py-1 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span> Sesi Berjalan
                                        </span>
                                        <div class="px-2.5 py-1 rounded-md bg-surface-container-high text-on-surface font-label-md text-label-md tracking-wider font-mono">
                                            Token: <span class="font-bold text-primary">{{ $exam->token }}</span>
                                        </div>
                                    </div>
                                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold mb-1">
                                        {{ $exam->title }}
                                    </h3>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-2 mb-4">
                                        <span class="material-symbols-outlined text-[16px] text-outline">group</span> Kelas {{ $exam->classroom->name ?? 'Semua Kelas' }}
                                        <span class="w-1 h-1 rounded-full bg-outline"></span>
                                        <span class="material-symbols-outlined text-[16px] text-outline">person</span> Guru: {{ $exam->teacher->name ?? 'Guru Pengampu' }}
                                    </p>
                                    <!-- Progress Bar Participation -->
                                    <div class="bg-surface-container-low p-space-sm rounded-lg mb-4">
                                        <div class="flex justify-between items-center mb-1.5 font-label-sm text-label-sm">
                                            <span class="text-on-surface-variant">Kehadiran Siswa</span>
                                            <span class="font-bold text-on-surface">{{ $examParticipantCount }} / {{ $totalClassStudents }} Siswa ({{ $presencePct }}%)</span>
                                        </div>
                                        <div class="w-full bg-surface-container-highest rounded-full h-2 overflow-hidden">
                                            <div class="bg-secondary h-2 rounded-full transition-all duration-500" style="width: {{ $presencePct }}%;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-surface-container-low">
                                    <span class="font-label-sm text-label-sm text-outline flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">schedule</span> Durasi: {{ $exam->duration_minutes }} Menit
                                    </span>
                                    <a href="{{ route('admin.exams', ['status' => 'active']) }}" class="px-3.5 py-2 rounded-lg bg-surface-container-high text-primary hover:bg-primary hover:text-on-primary transition-all font-label-md text-label-md font-semibold flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[18px]">monitor</span> Live Monitor
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @elseif($upcomingExams->count() > 0)
                    <!-- Upcoming Scheduled Exams Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
                        @foreach($upcomingExams as $exam)
                            <div class="p-space-lg rounded-xl bg-surface-container-lowest shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow relative border border-slate-100">
                                <div>
                                    <div class="flex items-start justify-between gap-2 mb-3">
                                        <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-label-sm text-label-sm font-semibold flex items-center gap-1.5 border border-blue-100">
                                            <span class="material-symbols-outlined text-[15px]">event</span> Terjadwal
                                        </span>
                                        <div class="px-2.5 py-1 rounded-md bg-surface-container-high text-on-surface font-label-md text-label-md tracking-wider font-mono">
                                            Token: <span class="font-bold text-primary">{{ $exam->token }}</span>
                                        </div>
                                    </div>
                                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold mb-1">
                                        {{ $exam->title }}
                                    </h3>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-2 mb-3">
                                        <span class="material-symbols-outlined text-[16px] text-outline">group</span> Kelas {{ $exam->classroom->name ?? 'Semua Kelas' }}
                                        <span class="w-1 h-1 rounded-full bg-outline"></span>
                                        <span class="material-symbols-outlined text-[16px] text-outline">person</span> Guru: {{ $exam->teacher->name ?? 'Guru Pengampu' }}
                                    </p>
                                    <div class="bg-surface-container-low p-space-sm rounded-lg mb-4 text-xs text-on-surface-variant flex items-center justify-between">
                                        <span class="flex items-center gap-1 font-medium">
                                            <span class="material-symbols-outlined text-[16px] text-outline">calendar_today</span>
                                            {{ $exam->exam_date ? $exam->exam_date->translatedFormat('d M Y') : '-' }}
                                        </span>
                                        <span class="font-mono font-semibold text-on-surface">
                                            {{ substr($exam->start_time, 0, 5) }} - {{ substr($exam->end_time, 0, 5) }} WIB
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-surface-container-low">
                                    <span class="font-label-sm text-label-sm text-outline flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">schedule</span> {{ $exam->duration_minutes }} Menit
                                    </span>
                                    <a href="{{ route('admin.exams', ['status' => 'scheduled']) }}" class="px-3.5 py-2 rounded-lg bg-surface-container-high text-primary hover:bg-primary hover:text-on-primary transition-all font-label-md text-label-md font-semibold flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span> Lihat Jadwal
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Standby / Empty State Card with Real Summary -->
                    <div class="p-space-lg rounded-xl bg-surface-container-lowest shadow-sm flex flex-col md:flex-row items-center justify-between gap-space-md border border-dashed border-surface-container">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-surface-container text-primary flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[28px]">verified</span>
                            </div>
                            <div>
                                <h4 class="font-headline-sm text-sm font-bold text-on-surface">Tidak Ada Ujian Aktif Saat Ini</h4>
                                <p class="font-body-sm text-xs text-on-surface-variant mt-0.5">
                                    @if($lastCompletedExam)
                                        Ujian terakhir diselesaikan: <strong>{{ $lastCompletedExam->title }}</strong> (Kelas {{ $lastCompletedExam->classroom->name ?? '-' }}).
                                    @else
                                        Belum ada jadwal sesi ujian yang sedang aktif. Seluruh sistem CBT berstatus siaga.
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('admin.exams') }}" class="px-4 py-2.5 rounded-lg bg-primary-container text-on-primary font-label-md text-label-md font-semibold hover:bg-primary transition-all flex items-center gap-1.5 shadow-sm">
                                <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                                <span>Buka Manajemen Ujian</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Section: Statistik & Tren Evaluasi Belajar (Real Calculations) -->
            <div class="flex flex-col gap-space-md">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-6 rounded-full bg-secondary"></span>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Statistik &amp; Tren Evaluasi Belajar</h2>
                    </div>
                    <span class="font-label-sm text-label-sm text-on-surface-variant bg-surface-container-low px-2.5 py-1 rounded-md border border-surface-container font-semibold">
                        Tahun Ajaran {{ $activeYear ? $activeYear->name : 'Aktif' }}
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-space-md">
                    <!-- Chart 1: Jumlah Ujian Bulanan (Bar Chart SVG Real Data) -->
                    @php
                        $maxCount = max(1, collect($monthlyFrequency)->max('count'));
                        $barXs = [15, 52, 89, 126, 163];
                    @endphp
                    <div class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex flex-col justify-between border border-slate-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-label-md text-label-md font-bold text-on-surface">Frekuensi Ujian</span>
                            <span class="material-symbols-outlined text-[18px] text-outline">bar_chart</span>
                        </div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mb-4">
                            Total {{ $totalEvaluationsPeriod }} evaluasi ({{ $monthlyFrequency[0]['name'] }} - {{ $monthlyFrequency[4]['name'] }})
                        </p>
                        <!-- Inline SVG Bar Chart with Dynamic Heights -->
                        <div class="w-full h-36 flex items-end">
                            <svg class="w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 200 110">
                                <!-- Grid Lines -->
                                <line class="text-surface-container" stroke="currentColor" stroke-dasharray="2,2" stroke-width="1" x1="0" x2="200" y1="20" y2="20"></line>
                                <line class="text-surface-container" stroke="currentColor" stroke-dasharray="2,2" stroke-width="1" x1="0" x2="200" y1="55" y2="55"></line>
                                <line class="text-surface-container" stroke="currentColor" stroke-width="1" x1="0" x2="200" y1="90" y2="90"></line>
                                
                                @foreach($monthlyFrequency as $idx => $mItem)
                                    @php
                                        $x = $barXs[$idx] ?? (15 + $idx * 37);
                                        $barH = $mItem['count'] > 0 ? max(10, round(($mItem['count'] / $maxCount) * 75)) : 5;
                                        $barY = 90 - $barH;
                                        $isPeak = ($mItem['name'] === $peakMonth && $mItem['count'] > 0);
                                    @endphp
                                    <rect class="{{ $isPeak ? 'fill-primary-container hover:fill-primary' : 'fill-surface-container-high transition-all hover:fill-primary-container' }} cursor-pointer" height="{{ $barH }}" rx="3" width="22" x="{{ $x }}" y="{{ $barY }}"></rect>
                                    <text class="{{ $isPeak ? 'fill-primary font-bold' : 'fill-outline' }} text-[9px] font-sans" text-anchor="middle" x="{{ $x + 11 }}" y="{{ max(12, $barY - 5) }}">{{ $mItem['count'] }}</text>
                                    <text class="{{ $isPeak ? 'fill-primary font-bold' : 'fill-outline' }} text-[9px] font-sans" text-anchor="middle" x="{{ $x + 11 }}" y="103">{{ $mItem['name'] }}</text>
                                @endforeach
                            </svg>
                        </div>
                        <div class="mt-3 pt-2 text-center text-on-surface-variant font-label-sm text-label-sm border-t border-surface-container-low">
                            @if(collect($monthlyFrequency)->max('count') > 0)
                                Puncak ujian pada Bulan {{ $peakMonth }}
                            @else
                                Belum ada pembuatan ujian periode ini
                            @endif
                        </div>
                    </div>

                    <!-- Chart 2: Donut / Partisipasi Siswa (Real Data) -->
                    <div class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex flex-col justify-between border border-slate-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-label-md text-label-md font-bold text-on-surface">Tingkat Partisipasi</span>
                            <span class="material-symbols-outlined text-[18px] text-outline">pie_chart</span>
                        </div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mb-2">
                            {{ $totalParticipants > 0 ? "Akumulasi {$totalParticipants} sesi peserta" : 'Belum ada rekaman ujian' }}
                        </p>
                        <div class="flex items-center justify-center my-1 relative">
                            <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 36 36">
                                <!-- Background circle -->
                                <path class="text-surface-container" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-dasharray="100, 100" stroke-width="3.5"></path>
                                <!-- On-time participation rate stroke -->
                                <path class="text-secondary stroke-current" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-dasharray="{{ $onTimeRate }}, 100" stroke-width="3.5"></path>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="font-headline-md text-headline-md text-on-surface font-bold">{{ $onTimeRate }}%</span>
                                <span class="font-label-sm text-label-sm text-secondary font-semibold">Tepat Waktu</span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5 mt-2 border-t border-surface-container-low pt-2">
                            <div class="flex justify-between items-center text-body-sm">
                                <span class="flex items-center gap-1.5 text-on-surface-variant"><span class="w-2.5 h-2.5 rounded-full bg-secondary"></span> Hadir Tepat Sesi</span>
                                <span class="font-semibold text-on-surface">{{ $onTimeRate }}%</span>
                            </div>
                            <div class="flex justify-between items-center text-body-sm">
                                <span class="flex items-center gap-1.5 text-on-surface-variant"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Sesi Susulan / Telat</span>
                                <span class="font-semibold text-on-surface">{{ $lateRate }}%</span>
                            </div>
                            <div class="flex justify-between items-center text-body-sm">
                                <span class="flex items-center gap-1.5 text-on-surface-variant"><span class="w-2.5 h-2.5 rounded-full bg-outline-variant"></span> Belum / Daring</span>
                                <span class="font-semibold text-on-surface">{{ $dispensasiRate }}%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Chart 3: Distribusi Nilai Rata-rata KKM (Real Data) -->
                    @php
                        $isPassingKKM = ($averageScore !== null && $averageScore >= $kkmDefault);
                        $kkmDiff = $averageScore !== null ? round(abs($averageScore - $kkmDefault), 1) : 0;
                    @endphp
                    <div class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex flex-col justify-between border border-slate-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-label-md text-label-md font-bold text-on-surface">Rata-rata Nilai Sekolah</span>
                            <span class="material-symbols-outlined text-[18px] text-outline">analytics</span>
                        </div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mb-1">Ambang Batas KKM: {{ number_format($kkmDefault, 1) }}</p>
                        <div class="my-auto py-2 flex flex-col items-center text-center">
                            @if($totalResults > 0)
                                <span class="text-[44px] leading-tight font-extrabold text-primary font-headline-lg">
                                    {{ number_format($averageScore, 1) }}
                                </span>
                                <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full {{ $isPassingKKM ? 'bg-secondary-container text-on-secondary-container' : 'bg-rose-100 text-rose-700' }} font-label-sm text-label-sm font-semibold mt-1">
                                    <span class="material-symbols-outlined text-[16px]">{{ $isPassingKKM ? 'verified' : 'warning' }}</span>
                                    <span>{{ $isPassingKKM ? "Melampaui KKM (+{$kkmDiff})" : "Di Bawah KKM (-{$kkmDiff})" }}</span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant mt-3 px-2">
                                    Sebanyak <strong>{{ $goodGradePercent }}%</strong> hasil ujian memenuhi batas KKM dari total <strong>{{ $totalResults }}</strong> peserta yang dinilai.
                                </p>
                            @else
                                <span class="text-[40px] leading-tight font-extrabold text-outline font-headline-lg">
                                    0.0
                                </span>
                                <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-semibold mt-1">
                                    <span class="material-symbols-outlined text-[16px]">info</span>
                                    <span>Belum Ada Hasil Ujian</span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant mt-3 px-2">
                                    Hasil evaluasi akan otomatis dihitung saat siswa menyelesaikan sesi ujian.
                                </p>
                            @endif
                        </div>
                        <!-- Mini Summary Data Metrics -->
                        <div class="w-full bg-surface-container-low rounded-lg p-2 flex items-center justify-between border-t border-surface-container-low">
                            <div class="flex flex-col">
                                <span class="font-label-sm text-label-sm text-outline">Nilai Tertinggi</span>
                                <span class="font-label-md text-label-md font-bold text-on-surface truncate max-w-[130px]" title="{{ $topSubject }}">
                                    @if($totalResults > 0)
                                        {{ number_format($highestScore, 1) }} {{ $topSubject ? '(' . $topSubject . ')' : '' }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                            <span class="w-px h-6 bg-surface-container-high"></span>
                            <div class="flex flex-col text-right">
                                <span class="font-label-sm text-label-sm text-outline">Ketuntasan Klasikal</span>
                                <span class="font-label-md text-label-md font-bold {{ $classicalPassRate >= 75 ? 'text-secondary' : 'text-amber-600' }}">
                                    {{ $classicalPassRate }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Central Hub Action Banner -->
            <div class="p-space-lg rounded-xl bg-surface-container flex flex-col md:flex-row items-center justify-between gap-space-md border border-slate-200/60">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary-container text-on-primary flex items-center justify-center shrink-0 shadow-sm">
                        <span class="material-symbols-outlined text-[28px]">lock_reset</span>
                    </div>
                    <div>
                        <h4 class="font-headline-sm text-headline-sm text-on-surface font-semibold">Pusat Pengawasan &amp; Token Ujian</h4>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">
                            Token sesi ujian digenerate otomatis per mata pelajaran dan kelas untuk memastikan integritas pengerjaan siswa.
                        </p>
                    </div>
                </div>
                <a href="{{ route('admin.exams') }}" class="whitespace-nowrap px-4 py-2.5 rounded-lg bg-primary text-on-primary font-label-md text-label-md font-semibold hover:bg-opacity-95 shadow-sm active:scale-95 transition-all text-center">
                    Buka Daftar Ujian
                </a>
            </div>
        </div>

        <!-- Right Column (4 Cols) -->
        <div class="xl:col-span-4 flex flex-col gap-space-lg">
            <!-- Server & CBT Guard Health Card (Real Telemetry) -->
            <div class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[22px]">dns</span>
                        <span class="font-headline-sm text-headline-sm text-on-surface font-bold">Status Server CBT</span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full {{ $dbStatus === 'Terhubung' ? 'bg-secondary-container text-on-secondary-container' : 'bg-rose-100 text-rose-700' }} font-label-sm text-label-sm font-bold flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full {{ $dbStatus === 'Terhubung' ? 'bg-secondary animate-pulse' : 'bg-rose-600' }}"></span>
                        {{ $dbStatus === 'Terhubung' ? 'Normal Aktif' : 'Terkendala' }}
                    </span>
                </div>
                <div class="space-y-3.5">
                    <!-- Database MySQL Status -->
                    <div class="p-2.5 rounded-lg bg-surface-container-low flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-secondary">database</span>
                            <span class="font-label-sm text-label-sm text-on-surface font-medium">Koneksi Database</span>
                        </div>
                        <span class="font-label-md text-label-md font-semibold text-secondary">MySQL ({{ config('database.connections.mysql.database') }})</span>
                    </div>
                    <!-- RAM Load -->
                    <div>
                        <div class="flex justify-between items-center text-label-sm font-label-sm mb-1">
                            <span class="text-on-surface-variant">Pemakaian Memori RAM PHP</span>
                            <span class="font-bold text-on-surface font-mono">{{ $memoryMB }} MB ({{ $memoryPercent }}%)</span>
                        </div>
                        <div class="w-full bg-surface-container-high rounded-full h-1.5 overflow-hidden">
                            <div class="bg-secondary h-1.5 rounded-full" style="width: {{ $memoryPercent }}%;"></div>
                        </div>
                    </div>
                    <!-- Concurrent CBT Connections -->
                    <div class="p-2.5 rounded-lg bg-surface-container-low flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-primary">lan</span>
                            <span class="font-label-sm text-label-sm text-on-surface font-medium">Sesi Pengguna Aktif</span>
                        </div>
                        <span class="font-label-md text-label-md font-mono font-bold text-primary">{{ $activeSockets }} User</span>
                    </div>
                    <!-- Framework & Engine Info -->
                    <div class="flex items-center justify-between text-[11px] text-on-surface-variant pt-1 px-1">
                        <span>Laravel v{{ app()->version() }}</span>
                        <span>PHP v{{ PHP_VERSION }}</span>
                    </div>
                </div>
            </div>

            <!-- Section: Ujian Terbaru Selesai / Terdaftar (Real Data) -->
            <div class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex flex-col gap-space-sm border border-slate-100">
                <div class="flex items-center justify-between mb-1">
                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Ujian Terkini</h3>
                    <a href="{{ route('admin.exams') }}" class="text-xs text-primary hover:underline font-semibold flex items-center gap-0.5">
                        Semua <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>
                </div>
                <p class="font-body-sm text-body-sm text-on-surface-variant -mt-2 mb-2">Daftar evaluasi belajar dalam sistem</p>

                @if($recentCompletedExams->count() > 0)
                    @foreach($recentCompletedExams as $rExam)
                        @php
                            $rAvg = $rExam->results->count() > 0 ? round($rExam->results->avg('total_score'), 1) : null;
                            $rCount = $rExam->results->count() ?: $rExam->participants->count();
                        @endphp
                        <div class="p-space-sm rounded-lg bg-surface-container-low hover:bg-surface-container transition-colors flex items-center justify-between gap-3">
                            <div class="flex flex-col min-w-0">
                                <span class="font-label-md text-label-md font-semibold text-on-surface truncate">{{ $rExam->title }}</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant truncate">
                                    {{ $rAvg !== null ? "Rata-rata: {$rAvg} • " : '' }}{{ $rCount }} Siswa • Kelas {{ $rExam->classroom->name ?? 'Semua' }}
                                </span>
                            </div>
                            <a href="{{ route('admin.exams', ['search' => $rExam->title]) }}" class="p-2 rounded-lg bg-surface-container-lowest text-primary hover:bg-primary hover:text-on-primary transition-colors shadow-sm shrink-0" title="Buka Detail Ujian">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="py-6 text-center text-on-surface-variant text-xs">
                        <span class="material-symbols-outlined text-[24px] text-outline mb-1 block">description</span>
                        Belum ada rekaman ujian di sistem.
                    </div>
                @endif
            </div>

            <!-- Section: Aktivitas Sistem Terkini (Real Timeline) -->
            <div class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex flex-col gap-3 border border-slate-100">
                <div class="flex items-center justify-between">
                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Aktivitas Terkini</h3>
                    <a href="{{ route('admin.logs') }}" class="font-label-sm text-label-sm text-primary hover:underline font-semibold flex items-center gap-0.5">
                        Log Lengkap <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>
                </div>
                <div class="relative pl-6 space-y-4 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-surface-container-high">
                    @if($recentLogs->count() > 0)
                        @foreach($recentLogs as $index => $log)
                            @php
                                $dotColors = ['bg-primary-container', 'bg-secondary', 'bg-tertiary-container'];
                                $dotColor = $dotColors[$index % 3];
                            @endphp
                            <div class="relative">
                                <span class="absolute -left-[23px] top-1 w-3.5 h-3.5 rounded-full {{ $dotColor }} ring-4 ring-surface-container-lowest"></span>
                                <div class="flex items-baseline justify-between gap-2">
                                    <span class="font-label-sm text-label-sm font-bold text-on-surface truncate">{{ $log->user->name ?? 'Administrator' }}</span>
                                    <span class="font-label-sm text-label-sm text-outline font-mono shrink-0">{{ $log->created_at->format('H:i') }}</span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5 leading-snug">
                                    {{ $log->description }}
                                </p>
                            </div>
                        @endforeach
                    @else
                        <div class="relative">
                            <span class="absolute -left-[23px] top-1 w-3.5 h-3.5 rounded-full bg-secondary ring-4 ring-surface-container-lowest"></span>
                            <div class="flex items-baseline justify-between">
                                <span class="font-label-sm text-label-sm font-bold text-on-surface">Sistem CBT</span>
                                <span class="font-label-sm text-label-sm text-outline font-mono">{{ now()->format('H:i') }}</span>
                            </div>
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">
                                Seluruh modul CBT berjalan stabil dan siap digunakan.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Rekapitulasi Sekolah (PDF / Print Ready with Real Data) -->
<div id="schoolReportModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-200">
        <div class="p-space-md border-b border-surface-container flex items-center justify-between bg-surface-container-low">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[22px]">picture_as_pdf</span>
                <span class="font-headline-sm text-headline-sm text-on-surface font-bold">Laporan Eksekutif CBT Sekolah</span>
            </div>
            <button type="button" onclick="closeReportModal()" class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-surface-container-high transition-colors text-on-surface-variant">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        
        <div id="printableReportContent" class="p-space-lg overflow-y-auto flex flex-col gap-4 text-xs font-body-sm">
            <!-- Kop Laporan -->
            <div class="border-b-2 border-slate-900 pb-3 text-center">
                <h2 class="text-base font-bold uppercase tracking-wide text-slate-900">{{ \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}</h2>
                <p class="text-slate-600">NPSN: {{ \App\Models\SchoolSetting::get('school_npsn', '20103482') }} • Sistem Computer Based Test (CBT)</p>
                <p class="text-slate-500 text-[11px] mt-0.5">Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>

            <div class="grid grid-cols-2 gap-3 my-1">
                <div class="p-3 bg-surface-container-low rounded-xl">
                    <span class="text-on-surface-variant font-medium block">Tahun Ajaran Aktif</span>
                    <span class="text-sm font-bold text-on-surface">{{ $activeYear ? $activeYear->name : 'Aktif' }}</span>
                </div>
                <div class="p-3 bg-surface-container-low rounded-xl">
                    <span class="text-on-surface-variant font-medium block">Semester</span>
                    <span class="text-sm font-bold text-on-surface">{{ ($activeYear && $activeYear->semester == 2) ? 'Semester 2 (Genap)' : 'Semester 1 (Ganjil)' }}</span>
                </div>
            </div>

            <!-- Tabel Rekapitulasi Data Master -->
            <div>
                <h4 class="font-bold text-slate-800 mb-2">1. Ringkasan Data Master Sekolah</h4>
                <table class="w-full border border-slate-200 rounded-lg overflow-hidden text-left">
                    <thead class="bg-slate-100 font-semibold text-slate-700">
                        <tr>
                            <th class="p-2 border-b border-slate-200">Indikator</th>
                            <th class="p-2 border-b border-slate-200 text-right">Jumlah Tercatat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr><td class="p-2">Total Siswa Terdaftar</td><td class="p-2 text-right font-bold">{{ $stats['total_students'] }} Siswa ({{ $stats['active_students'] }} Aktif)</td></tr>
                        <tr><td class="p-2">Total Guru Pengajar</td><td class="p-2 text-right font-bold">{{ $stats['total_teachers'] }} Guru ({{ $stats['teachers_with_nip'] }} Ber-NIP)</td></tr>
                        <tr><td class="p-2">Rombongan Belajar (Kelas)</td><td class="p-2 text-right font-bold">{{ $stats['total_classrooms'] }} Rombel (Kapasitas: {{ $stats['total_capacity'] }})</td></tr>
                        <tr><td class="p-2">Mata Pelajaran Ujian</td><td class="p-2 text-right font-bold">{{ $stats['total_subjects'] }} Mapel ({{ $stats['total_questions'] }} Butir Soal)</td></tr>
                        <tr><td class="p-2">Total Sesi Evaluasi Dibuat</td><td class="p-2 text-right font-bold">{{ $stats['total_exams'] }} Sesi ({{ $stats['completed_exams'] }} Selesai)</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Tabel Kinerja Evaluasi -->
            <div>
                <h4 class="font-bold text-slate-800 mb-2">2. Statistik Kinerja Evaluasi Akademik</h4>
                <table class="w-full border border-slate-200 rounded-lg overflow-hidden text-left">
                    <thead class="bg-slate-100 font-semibold text-slate-700">
                        <tr>
                            <th class="p-2 border-b border-slate-200">Metrik Hasil</th>
                            <th class="p-2 border-b border-slate-200 text-right">Nilai / Rasio</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr><td class="p-2">Tingkat Kehadiran Siswa Tepat Waktu</td><td class="p-2 text-right font-bold text-emerald-600">{{ $onTimeRate }}%</td></tr>
                        <tr><td class="p-2">Rata-rata Nilai Sekolah</td><td class="p-2 text-right font-bold text-primary">{{ $totalResults > 0 ? number_format($averageScore, 1) : '-' }} (KKM: {{ number_format($kkmDefault, 1) }})</td></tr>
                        <tr><td class="p-2">Nilai Tertinggi Tercapai</td><td class="p-2 text-right font-bold">{{ $totalResults > 0 ? number_format($highestScore, 1) : '-' }}</td></tr>
                        <tr><td class="p-2">Tingkat Ketuntasan Klasikal</td><td class="p-2 text-right font-bold {{ $classicalPassRate >= 75 ? 'text-emerald-600' : 'text-amber-600' }}">{{ $classicalPassRate }}%</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="p-space-md border-t border-surface-container flex items-center justify-between bg-surface-container-lowest">
            <span class="text-on-surface-variant text-[11px]">Siap dicetak atau disimpan format PDF melalui dialog cetak browser.</span>
            <div class="flex items-center gap-2">
                <button type="button" onclick="closeReportModal()" class="px-4 py-2 rounded-lg bg-surface-container-high text-on-surface font-semibold hover:bg-surface-container transition-colors">
                    Tutup
                </button>
                <button type="button" onclick="printReportModal()" class="px-4 py-2 rounded-lg bg-primary text-on-primary font-semibold hover:bg-opacity-95 transition-colors flex items-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">print</span>
                    <span>Cetak Sekarang</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openReportModal() {
        document.getElementById('schoolReportModal').classList.remove('hidden');
    }

    function closeReportModal() {
        document.getElementById('schoolReportModal').classList.add('hidden');
    }

    function printReportModal() {
        window.print();
    }
</script>
@endsection
