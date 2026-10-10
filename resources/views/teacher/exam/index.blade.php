@extends('layouts.teacher')

@php
    $isCompletedView = request('status') === 'completed';
@endphp

@section('title', ($isCompletedView ? 'Hasil & Rekap Nilai Ujian' : 'Manajemen Jadwal & Sesi Ujian') . ' — EduExam')
@section('page_title', $isCompletedView ? 'Hasil Ujian' : 'Manajemen Ujian')

@section('teacher-content')
<div class="flex flex-col w-full pb-space-xl">
    <!-- Top Banner / Header Ribbon -->
    <div class="relative overflow-hidden rounded-xl bg-surface-container-lowest p-space-lg shadow-sm mb-space-lg border border-slate-100">
        <div class="absolute -right-16 -top-16 w-80 h-80 rounded-full bg-primary-fixed opacity-40 blur-3xl pointer-events-none"></div>
        <div class="absolute right-40 -bottom-20 w-60 h-60 rounded-full bg-secondary-container opacity-30 blur-2xl pointer-events-none"></div>
        
        <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-space-md z-10">
            <div class="flex flex-col gap-1 max-w-2xl">
                <div class="flex items-center gap-space-xs text-on-surface-variant font-label-sm text-label-sm tracking-wide uppercase">
                    <span class="inline-block w-2 h-2 rounded-full {{ $isCompletedView ? 'bg-secondary' : 'bg-primary' }}"></span>
                    <span>{{ $isCompletedView ? 'Rekapitulasi Nilai & Evaluasi Siswa' : 'Sistem Evaluasi Terpadu' }}</span>
                    <span class="text-outline-variant">•</span>
                    <span>Semester Ganjil 2026/2027</span>
                </div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">
                    {{ $isCompletedView ? 'Hasil & Rekapitulasi Nilai Ujian' : 'Manajemen Jadwal & Sesi Ujian' }}
                </h1>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    {{ $isCompletedView ? 'Rekapitulasi perolehan nilai, statistik ketuntasan KKM, dan evaluasi hasil pengerjaan ujian siswa.' : 'Atur jadwal pelaksanaan, pantau sesi aktif, dan kelola arsip ujian ' . ($schoolName ?? \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara')) . '.' }}
                </p>
            </div>
            
            <div class="flex items-center gap-space-sm self-start lg:self-center">
                <a href="{{ route('teacher.dashboard') }}" class="inline-flex items-center gap-space-xs px-space-md py-2.5 rounded-lg bg-surface-container-low text-on-surface font-label-lg text-label-lg hover:bg-surface-container transition-all">
                    <span class="material-symbols-outlined text-[20px] text-primary">dashboard</span>
                    <span>Dashboard Guru</span>
                </a>
                <a href="{{ route('teacher.exams.create') }}" class="inline-flex items-center gap-space-xs px-space-lg py-2.5 rounded-lg bg-primary text-on-primary font-label-lg text-label-lg shadow-md hover:bg-primary-container hover:shadow-lg transition-all active:scale-[0.98]">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                    <span>+ Buat Ujian Baru</span>
                </a>
            </div>
        </div>

        <!-- Bento Metrik Sesi Overview (4 cards) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-space-md mt-space-lg pt-space-md border-t-0 z-10 relative">
            <div class="flex items-center gap-space-sm p-space-sm rounded-lg bg-surface-container-low/70">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-secondary-fixed text-on-secondary-fixed">
                    <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' 1;">sensors</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="font-headline-sm text-headline-sm text-on-surface font-bold leading-tight">{{ $stats['active'] }} Sesi</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant truncate">Sedang Berlangsung</span>
                </div>
            </div>

            <div class="flex items-center gap-space-sm p-space-sm rounded-lg bg-surface-container-low/70">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-fixed text-primary">
                    <span class="material-symbols-outlined text-[22px]">event_upcoming</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="font-headline-sm text-headline-sm text-on-surface font-bold leading-tight">{{ $stats['scheduled'] }} Sesi</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant truncate">Terjadwal Mendatang</span>
                </div>
            </div>

            <div class="flex items-center gap-space-sm p-space-sm rounded-lg bg-surface-container-low/70">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-surface-container-high text-on-surface-variant">
                    <span class="material-symbols-outlined text-[22px]">task_alt</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="font-headline-sm text-headline-sm text-on-surface font-bold leading-tight">{{ $stats['completed'] }} Sesi</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant truncate">Telah Selesai</span>
                </div>
            </div>

            <div class="flex items-center gap-space-sm p-space-sm rounded-lg bg-surface-container-low/70">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-tertiary-fixed text-on-tertiary-fixed">
                    <span class="material-symbols-outlined text-[22px]">edit_note</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="font-headline-sm text-headline-sm text-on-surface font-bold leading-tight">{{ $stats['draft'] }} Draft</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant truncate">Perlu Dilengkapi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Pills Row & Search Toolbar -->
    <div class="flex flex-col gap-space-md mb-space-lg">
        <!-- Status Filter Tabs -->
        <div class="flex flex-wrap items-center justify-between gap-space-sm p-1.5 rounded-xl bg-surface-container-low border border-slate-100">
            <div class="flex flex-wrap items-center gap-1.5">
                <a href="{{ route('teacher.exams.index', array_merge(request()->except('status', 'page'))) }}" class="inline-flex items-center gap-space-xs px-space-md py-2 rounded-lg {{ !request('status') ? 'bg-surface-container-lowest text-on-surface font-bold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }} font-label-md text-label-md transition-colors">
                    <span>Semua Sesi</span>
                    <span class="px-1.5 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-semibold">{{ $stats['total'] }}</span>
                </a>

                <a href="{{ route('teacher.exams.index', array_merge(request()->except('status', 'page'), ['status' => 'active'])) }}" class="inline-flex items-center gap-space-xs px-space-md py-2 rounded-lg {{ request('status') === 'active' ? 'bg-secondary text-on-secondary font-bold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }} font-label-md text-label-md transition-colors">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary-fixed-dim opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-on-secondary"></span>
                    </span>
                    <span>Sedang Berlangsung</span>
                    <span class="px-1.5 py-0.5 rounded-full {{ request('status') === 'active' ? 'bg-secondary-container text-on-secondary-container' : 'bg-surface-container-high text-on-surface-variant' }} text-[10px] font-bold">{{ $stats['active'] }}</span>
                </a>

                <a href="{{ route('teacher.exams.index', array_merge(request()->except('status', 'page'), ['status' => 'scheduled'])) }}" class="inline-flex items-center gap-space-xs px-space-md py-2 rounded-lg {{ request('status') === 'scheduled' ? 'bg-primary-container text-on-primary font-bold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }} font-label-md text-label-md transition-colors">
                    <span>Terjadwal</span>
                    <span class="px-1.5 py-0.5 rounded-full {{ request('status') === 'scheduled' ? 'bg-primary-fixed text-on-primary-fixed' : 'bg-surface-container-high text-on-surface-variant' }} text-[10px] font-semibold">{{ $stats['scheduled'] }}</span>
                </a>

                <a href="{{ route('teacher.exams.index', array_merge(request()->except('status', 'page'), ['status' => 'draft'])) }}" class="inline-flex items-center gap-space-xs px-space-md py-2 rounded-lg {{ request('status') === 'draft' ? 'bg-tertiary-container text-on-primary font-bold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }} font-label-md text-label-md transition-colors">
                    <span>Draft</span>
                    <span class="px-1.5 py-0.5 rounded-full {{ request('status') === 'draft' ? 'bg-tertiary-fixed text-on-tertiary-fixed' : 'bg-surface-container-high text-on-surface-variant' }} text-[10px] font-semibold">{{ $stats['draft'] }}</span>
                </a>

                <a href="{{ route('teacher.exams.index', array_merge(request()->except('status', 'page'), ['status' => 'completed'])) }}" class="inline-flex items-center gap-space-xs px-space-md py-2 rounded-lg {{ request('status') === 'completed' ? 'bg-surface-variant text-on-surface font-bold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }} font-label-md text-label-md transition-colors">
                    <span>Selesai</span>
                    <span class="px-1.5 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-semibold">{{ $stats['completed'] }}</span>
                </a>
            </div>

            <div class="flex items-center gap-1 text-on-surface-variant text-label-sm font-label-sm pr-space-sm">
                <span class="material-symbols-outlined text-[16px]">tune</span>
                <span>Diurutkan: Terdekat</span>
            </div>
        </div>

        <!-- Search & Filter Controls -->
        <form action="{{ route('teacher.exams.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-space-sm p-space-md rounded-xl bg-surface-container-lowest shadow-sm border border-slate-100">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            @if(request('view'))
                <input type="hidden" name="view" value="{{ request('view') }}">
            @endif

            <div class="md:col-span-6 relative flex items-center">
                <span class="material-symbols-outlined absolute left-space-md text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input name="search" value="{{ request('search') }}" class="w-full h-11 pl-10 pr-space-md bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:bg-surface-container border border-transparent focus:border-indigo-300 transition-all" placeholder="Cari nama ujian, mata pelajaran, atau topik..." type="text"/>
            </div>

            <div class="md:col-span-3 relative flex items-center">
                <span class="material-symbols-outlined absolute left-space-md text-[18px] text-on-surface-variant pointer-events-none">school</span>
                <select name="academic_year_id" onchange="this.form.submit()" class="w-full h-11 pl-10 pr-space-lg bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface appearance-none focus:outline-none focus:bg-surface-container border border-transparent focus:border-indigo-300 transition-all cursor-pointer">
                    <option value="">Semua Tahun Ajaran</option>
                    @foreach($academicYears as $ay)
                        <option value="{{ $ay->id }}" {{ request('academic_year_id') == $ay->id ? 'selected' : '' }}>
                            T.A {{ $ay->name }} {{ $ay->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-space-md text-[18px] text-on-surface-variant pointer-events-none">expand_more</span>
            </div>

            <div class="md:col-span-3 relative flex items-center">
                <span class="material-symbols-outlined absolute left-space-md text-[18px] text-on-surface-variant pointer-events-none">groups</span>
                <select name="classroom_id" onchange="this.form.submit()" class="w-full h-11 pl-10 pr-space-lg bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface appearance-none focus:outline-none focus:bg-surface-container border border-transparent focus:border-indigo-300 transition-all cursor-pointer">
                    <option value="">Semua Kelas</option>
                    @foreach($classrooms as $cls)
                        <option value="{{ $cls->id }}" {{ request('classroom_id') == $cls->id ? 'selected' : '' }}>
                            {{ $cls->name }}
                        </option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-space-md text-[18px] text-on-surface-variant pointer-events-none">expand_more</span>
            </div>
        </form>
    </div>

    <!-- Exam Cards List -->
    <div class="flex flex-col gap-space-md">
        @forelse($exams as $exam)
            @if($exam->status === 'active')
                <!-- 1. ACTIVE EXAM CARD -->
                <div class="relative overflow-hidden rounded-xl bg-surface-container-lowest shadow-md transition-all hover:shadow-lg border border-slate-100">
                    <div class="h-1.5 w-full bg-secondary"></div>
                    <div class="p-space-lg">
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-space-md">
                            <div class="flex items-start gap-space-md min-w-0">
                                <div class="relative flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden shadow-sm bg-secondary-fixed/50 flex items-center justify-center text-on-secondary-fixed">
                                    <span class="material-symbols-outlined text-3xl">sensors</span>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <div class="flex flex-wrap items-center gap-space-xs mb-1">
                                        <span class="inline-flex items-center gap-1.5 px-space-sm py-0.5 rounded-full bg-secondary text-on-secondary font-label-sm text-label-sm font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-secondary-fixed-dim animate-ping"></span>
                                            Sedang Berlangsung
                                        </span>
                                        <span class="px-space-sm py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-label-sm font-semibold">
                                            {{ $exam->subject->name ?? 'Mata Pelajaran' }}
                                        </span>
                                        <span class="text-on-surface-variant font-label-sm text-label-sm">ID: SES-{{ str_pad($exam->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <h2 class="font-headline-md text-headline-md text-on-surface font-bold truncate">{{ $exam->title }}</h2>
                                    <div class="flex flex-wrap items-center gap-x-space-md gap-y-1 text-on-surface-variant font-body-sm text-body-sm mt-1">
                                        <div class="flex items-center gap-1 text-on-surface font-label-md text-label-md font-semibold">
                                            <span class="material-symbols-outlined text-[18px] text-primary">groups</span>
                                            <span>Kelas {{ $exam->classroom->name ?? '-' }}</span>
                                        </div>
                                        <span>•</span>
                                        <div class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">help_center</span>
                                            <span>{{ $exam->total_questions }} Soal</span>
                                        </div>
                                        <span>•</span>
                                        <div class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">schedule</span>
                                            <span>{{ $exam->duration_minutes }} Menit</span>
                                        </div>
                                        <span>•</span>
                                        <div class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">grade</span>
                                            <span>KKM {{ $exam->passing_grade }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row lg:flex-col items-start lg:items-end gap-space-sm flex-shrink-0">
                                <div class="flex items-center gap-space-xs px-space-md py-1.5 rounded-lg bg-surface-container-high/80 border border-indigo-100">
                                    <span class="material-symbols-outlined text-[16px] text-primary">key</span>
                                    <span class="font-label-sm text-label-sm text-on-surface-variant">Token Ujian:</span>
                                    <span class="font-mono font-bold text-on-surface tracking-wider bg-surface-container-lowest px-2 py-0.5 rounded shadow-sm">EXAM-{{ str_pad($exam->id, 3, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-on-surface-variant font-body-sm text-body-sm">
                                    <span class="material-symbols-outlined text-[16px] text-secondary">calendar_today</span>
                                    <span>{{ $exam->exam_date->format('d M Y') }} • {{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }} WIB</span>
                                </div>
                            </div>
                        </div>

                        <!-- Monitor Progress Row -->
                        @php
                            $totalClassStudents = $exam->classroom->students ? $exam->classroom->students->count() : 36;
                            $participantCount = $exam->participants->count();
                            $onlinePercent = $totalClassStudents > 0 ? min(100, round(($participantCount / $totalClassStudents) * 100)) : 100;
                        @endphp
                        <div class="mt-space-md p-space-md rounded-xl bg-surface-container-low flex flex-col md:flex-row items-start md:items-center justify-between gap-space-md border border-slate-100">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-space-md w-full md:w-auto">
                                <div class="flex items-center gap-space-sm">
                                    <div class="relative w-12 h-12 flex-shrink-0">
                                        <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                                            <path class="text-surface-container-high" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3.5"></path>
                                            <path class="text-secondary" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-dasharray="{{ $onlinePercent }}, 100" stroke-linecap="round" stroke-width="3.5"></path>
                                        </svg>
                                        <div class="absolute inset-0 flex items-center justify-center font-label-sm text-label-sm font-bold text-on-surface">
                                            {{ $onlinePercent }}%
                                        </div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-label-lg text-label-lg text-on-surface font-semibold">Live Monitor Siswa</span>
                                        <span class="font-body-sm text-body-sm text-secondary font-medium">{{ $participantCount }} dari {{ $totalClassStudents }} Siswa Mengerjakan</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-space-xs w-full sm:w-auto justify-end">
                                <a href="{{ route('teacher.exams.results', $exam) }}" class="inline-flex items-center gap-1.5 px-space-md py-2 rounded-lg bg-secondary text-on-secondary font-label-md text-label-md shadow-sm hover:opacity-95 transition-all">
                                    <span class="material-symbols-outlined text-[18px]">videocam</span>
                                    <span>Pantau Live</span>
                                </a>
                                <a href="{{ route('teacher.exams.results', $exam) }}" class="inline-flex items-center gap-1.5 px-space-md py-2 rounded-lg bg-surface-container-lowest text-on-surface font-label-md text-label-md shadow-sm hover:bg-surface-container transition-colors border border-slate-200">
                                    <span class="material-symbols-outlined text-[18px] text-primary">assessment</span>
                                    <span>Lihat Hasil</span>
                                </a>
                                <form action="{{ route('teacher.exams.complete', $exam) }}" method="POST" class="inline" onsubmit="return confirm('Tutup sesi ujian ini sekarang?');">
                                    @csrf
                                    <button type="submit" class="p-2 rounded-lg bg-surface-container-lowest text-on-surface-variant hover:bg-surface-container transition-colors border border-slate-200" title="Tutup Sesi Ujian">
                                        <span class="material-symbols-outlined text-[20px]">stop_circle</span>
                                    </button>
                                </form>
                                <form action="{{ route('teacher.exams.destroy', $exam) }}" method="POST" class="inline" onsubmit="return confirm('PERINGATAN: Sesi ujian ini sedang berlangsung!\n\nMenghapus ujian ini akan menghapus seluruh data butir soal, peserta, dan hasil jawaban siswa secara permanen.\n\nApakah Anda yakin ingin menghapus ujian ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-surface-container-lowest text-error hover:bg-error-container hover:text-on-error-container transition-colors border border-slate-200" title="Hapus Ujian Permanen">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($exam->status === 'scheduled')
                <!-- 2. SCHEDULED EXAM CARD -->
                <div class="relative overflow-hidden rounded-xl bg-surface-container-lowest shadow-sm hover:shadow-md transition-all border border-slate-100">
                    <div class="h-1.5 w-full bg-primary-container"></div>
                    <div class="p-space-lg">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-space-md">
                            <div class="flex items-start gap-space-md min-w-0">
                                <div class="relative flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden shadow-sm bg-primary-fixed flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined text-3xl">event_upcoming</span>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <div class="flex flex-wrap items-center gap-space-xs mb-1">
                                        <span class="inline-flex items-center gap-1 px-space-sm py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-label-sm font-semibold">
                                            <span class="material-symbols-outlined text-[14px]">schedule</span>
                                            Terjadwal
                                        </span>
                                        <span class="px-space-sm py-0.5 rounded-full bg-surface-container-high text-on-surface-variant font-label-sm text-label-sm font-semibold">
                                            {{ $exam->subject->name ?? 'Mata Pelajaran' }}
                                        </span>
                                        @php
                                            $daysLeft = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($exam->exam_date)->startOfDay(), false);
                                            $timeLabel = $daysLeft <= 0 ? 'Hari Ini' : ($daysLeft == 1 ? 'Mulai Besok' : 'Mulai dalam ' . $daysLeft . ' Hari');
                                        @endphp
                                        <span class="text-on-surface-variant font-label-sm text-label-sm font-semibold">{{ $timeLabel }}</span>
                                    </div>
                                    <h2 class="font-headline-md text-headline-md text-on-surface font-bold truncate">{{ $exam->title }}</h2>
                                    <div class="flex flex-wrap items-center gap-x-space-md gap-y-1 text-on-surface-variant font-body-sm text-body-sm mt-1">
                                        <div class="flex items-center gap-1 text-on-surface font-label-md text-label-md font-semibold">
                                            <span class="material-symbols-outlined text-[18px] text-primary">groups</span>
                                            <span>Kelas {{ $exam->classroom->name ?? '-' }} ({{ $exam->classroom->students ? $exam->classroom->students->count() : 36 }} Siswa)</span>
                                        </div>
                                        <span>•</span>
                                        <div class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">quiz</span>
                                            <span>{{ $exam->total_questions }} Soal</span>
                                        </div>
                                        <span>•</span>
                                        <div class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">timer</span>
                                            <span>{{ $exam->duration_minutes }} Menit</span>
                                        </div>
                                        <span>•</span>
                                        <div class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">verified</span>
                                            <span>KKM {{ $exam->passing_grade }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row lg:flex-col items-start lg:items-end gap-space-sm flex-shrink-0">
                                <div class="flex items-center gap-1.5 px-space-md py-1 rounded-lg bg-surface-container-low text-on-surface font-label-sm text-label-sm font-semibold border border-slate-100">
                                    <span class="material-symbols-outlined text-[16px] text-primary-container">event</span>
                                    <span>{{ $exam->exam_date->format('d M Y') }} • {{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} WIB</span>
                                </div>
                                <span class="font-body-sm text-body-sm text-on-surface-variant">Sistem acak soal &amp; Safe Exam Browser aktif</span>
                            </div>
                        </div>

                        <!-- Footer Actions Row -->
                        <div class="mt-space-md pt-space-md flex flex-col sm:flex-row items-center justify-between gap-space-sm border-t border-slate-100 bg-surface-container-low/40 p-space-sm rounded-lg">
                            <div class="flex items-center gap-space-sm text-body-sm text-on-surface-variant">
                                <span class="material-symbols-outlined text-[18px] text-primary">lock_clock</span>
                                <span>Token ujian otomatis aktif saat sesi dimulai</span>
                            </div>
                            <div class="flex items-center gap-space-xs self-end sm:self-auto">
                                <form action="{{ route('teacher.exams.activate', $exam) }}" method="POST" class="inline" onsubmit="return confirm('Mulai sesi ujian sekarang? Siswa akan dapat mengerjakan.')">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 px-space-md py-1.5 rounded-lg bg-secondary text-on-secondary font-label-md text-label-md shadow-sm hover:opacity-95 font-semibold">
                                        <span class="material-symbols-outlined text-[16px]">play_arrow</span>
                                        <span>Mulai Sekarang</span>
                                    </button>
                                </form>
                                <a href="{{ route('teacher.exams.edit', $exam) }}" class="inline-flex items-center gap-1 px-space-md py-1.5 rounded-lg bg-surface-container-lowest text-on-surface font-label-md text-label-md shadow-sm hover:bg-surface-container transition-colors border border-slate-200">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                    <span>Edit</span>
                                </a>
                                <form action="{{ route('teacher.exams.destroy', $exam) }}" method="POST" class="inline" onsubmit="return confirm('Hapus jadwal ujian ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg bg-surface-container-lowest text-error hover:bg-error-container transition-colors border border-slate-200" title="Hapus Ujian">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($exam->status === 'completed')
                <!-- 3. COMPLETED EXAM CARD -->
                <div class="relative overflow-hidden rounded-xl bg-surface-container-lowest shadow-sm hover:shadow-md transition-all border border-slate-100">
                    <div class="h-1.5 w-full bg-outline-variant"></div>
                    <div class="p-space-lg">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-space-md">
                            <div class="flex items-start gap-space-md min-w-0">
                                <div class="relative flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden shadow-sm bg-surface-container-high flex items-center justify-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-3xl">task_alt</span>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <div class="flex flex-wrap items-center gap-space-xs mb-1">
                                        <span class="inline-flex items-center gap-1 px-space-sm py-0.5 rounded-full bg-surface-container-high text-on-surface font-label-sm text-label-sm font-semibold">
                                            <span class="material-symbols-outlined text-[14px] text-secondary">check_circle</span>
                                            Selesai
                                        </span>
                                        <span class="px-space-sm py-0.5 rounded-full bg-secondary-container/50 text-on-secondary-container font-label-sm text-label-sm font-semibold">
                                            {{ $exam->results->count() }} Lembar Terkoreksi
                                        </span>
                                        <span class="text-on-surface-variant font-label-sm text-label-sm">Selesai: {{ $exam->exam_date->format('d M Y') }}</span>
                                    </div>
                                    <h2 class="font-headline-md text-headline-md text-on-surface font-bold truncate">{{ $exam->title }}</h2>
                                    <div class="flex flex-wrap items-center gap-x-space-md gap-y-1 text-on-surface-variant font-body-sm text-body-sm mt-1">
                                        <div class="flex items-center gap-1 text-on-surface font-label-md text-label-md font-semibold">
                                            <span class="material-symbols-outlined text-[18px] text-primary">groups</span>
                                            <span>Kelas {{ $exam->classroom->name ?? '-' }}</span>
                                        </div>
                                        <span>•</span>
                                        <div class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">quiz</span>
                                            <span>{{ $exam->total_questions }} Soal</span>
                                        </div>
                                        <span>•</span>
                                        <div class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">timer</span>
                                            <span>{{ $exam->duration_minutes }} Menit</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @php
                                $examAvg = round($exam->results->avg('total_score') ?: 0, 1);
                                $passCount = $exam->results->where('pass_status', 'pass')->count();
                                $passRate = $exam->results->count() > 0 ? round(($passCount / $exam->results->count()) * 100, 1) : 0;
                            @endphp
                            <div class="flex items-center gap-space-lg p-space-sm px-space-md rounded-xl bg-surface-container-low flex-shrink-0 border border-slate-100">
                                <div class="flex flex-col items-center">
                                    <span class="font-label-sm text-label-sm text-on-surface-variant">Rata-rata Nilai</span>
                                    <span class="font-headline-sm text-headline-sm text-secondary font-bold">{{ $examAvg }}</span>
                                </div>
                                <div class="w-px h-8 bg-outline-variant/40"></div>
                                <div class="flex flex-col items-center">
                                    <span class="font-label-sm text-label-sm text-on-surface-variant">Kelulusan KKM</span>
                                    <span class="font-headline-sm text-headline-sm text-on-surface font-bold">{{ $passRate }}%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions Row -->
                        <div class="mt-space-md pt-space-md flex flex-col sm:flex-row items-center justify-between gap-space-sm bg-surface-container-low/40 p-space-sm rounded-lg border-t border-slate-100">
                            <div class="flex items-center gap-space-xs text-body-sm text-on-surface-variant">
                                <span class="material-symbols-outlined text-[18px] text-secondary">fact_check</span>
                                <span>Semua koreksi otomatis dan esai telah dinilai sepenuhnya.</span>
                            </div>
                            <div class="flex items-center gap-space-xs self-end sm:self-auto">
                                <a href="{{ route('teacher.exams.results', $exam) }}" class="inline-flex items-center gap-1 px-space-md py-1.5 rounded-lg bg-surface-container-lowest text-on-surface font-label-md text-label-md shadow-sm hover:bg-surface-container transition-colors border border-slate-200">
                                    <span class="material-symbols-outlined text-[16px] text-primary">download</span>
                                    <span>Rekap Nilai</span>
                                </a>
                                <a href="{{ route('teacher.exams.analytics', $exam) }}" class="inline-flex items-center gap-1 px-space-md py-1.5 rounded-lg bg-primary text-on-primary font-label-md text-label-md shadow-sm hover:bg-primary-container transition-colors font-semibold">
                                    <span class="material-symbols-outlined text-[16px]">analytics</span>
                                    <span>Hasil &amp; Analisis</span>
                                </a>
                                <form action="{{ route('teacher.exams.destroy', $exam) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ujian yang sudah selesai ini beserta seluruh riwayat hasil nilainya?\n\nData yang dihapus tidak dapat dipulihkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg bg-surface-container-lowest text-error hover:bg-error-container hover:text-on-error-container transition-colors border border-slate-200" title="Hapus Ujian & Hasil">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- 4. DRAFT EXAM CARD -->
                <div class="relative overflow-hidden rounded-xl bg-surface-container-lowest shadow-sm hover:shadow-md transition-all border border-slate-100">
                    <div class="h-1.5 w-full bg-tertiary-container"></div>
                    <div class="p-space-lg">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-space-md">
                            <div class="flex items-start gap-space-md min-w-0">
                                <div class="flex-shrink-0 w-16 h-16 rounded-xl bg-tertiary-fixed flex items-center justify-center text-tertiary shadow-sm">
                                    <span class="material-symbols-outlined text-[32px]">draft</span>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <div class="flex flex-wrap items-center gap-space-xs mb-1">
                                        <span class="inline-flex items-center gap-1 px-space-sm py-0.5 rounded-full bg-tertiary-fixed text-on-tertiary-fixed font-label-sm text-label-sm font-semibold">
                                            <span class="material-symbols-outlined text-[14px]">edit_document</span>
                                            Draft (Belum Diterbitkan)
                                        </span>
                                        <span class="text-on-surface-variant font-label-sm text-label-sm">Terakhir diubah: {{ $exam->updated_at->diffForHumans() }}</span>
                                    </div>
                                    <h2 class="font-headline-md text-headline-md text-on-surface font-bold truncate">{{ $exam->title }}</h2>
                                    <div class="flex flex-wrap items-center gap-x-space-md gap-y-1 text-on-surface-variant font-body-sm text-body-sm mt-1">
                                        <div class="flex items-center gap-1 text-on-surface font-label-md text-label-md font-semibold">
                                            <span class="material-symbols-outlined text-[18px] text-tertiary">groups</span>
                                            <span>Kelas {{ $exam->classroom->name ?? 'Belum dipilih' }}</span>
                                        </div>
                                        <span>•</span>
                                        <div class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">tune</span>
                                            <span>{{ $exam->total_questions }} Soal Terlampir</span>
                                        </div>
                                        <span>•</span>
                                        <div class="flex items-center gap-1 text-tertiary font-semibold">
                                            <span class="material-symbols-outlined text-[16px]">schedule</span>
                                            <span>{{ $exam->duration_minutes }} Menit</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-space-xs self-start lg:self-center flex-shrink-0">
                                <form action="{{ route('teacher.exams.destroy', $exam) }}" method="POST" class="inline" onsubmit="return confirm('Hapus draf ujian ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-space-md py-2 rounded-lg bg-surface-container-low text-error font-label-md text-label-md hover:bg-error-container hover:text-on-error-container transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                                <a href="{{ route('teacher.exams.edit', $exam) }}" class="inline-flex items-center gap-1 px-space-lg py-2 rounded-lg bg-tertiary text-on-tertiary font-label-md text-label-md shadow-sm hover:opacity-90 transition-all font-semibold">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                    <span>Lanjutkan Edit</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="rounded-xl bg-surface-container-lowest p-12 text-center border border-dashed border-slate-200 shadow-sm">
                <span class="material-symbols-outlined text-5xl text-slate-300 mb-2">assignment</span>
                <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Tidak Ada Sesi Ujian Ditemukan</h3>
                <p class="font-body-md text-body-md text-on-surface-variant max-w-sm mx-auto mt-1 mb-4">
                    @if(request('status') || request('search') || request('academic_year_id') || request('classroom_id'))
                        Tidak ada ujian yang cocok dengan filter kriteria yang dipilih. Coba reset filter pencarian.
                    @else
                        Belum ada jadwal atau draf ujian yang tersimpan. Mulai buat sesi ujian pertama Anda.
                    @endif
                </p>
                <div class="flex items-center justify-center gap-2">
                    @if(request('status') || request('search') || request('academic_year_id') || request('classroom_id'))
                        <a href="{{ route('teacher.exams.index') }}" class="px-space-md py-2 rounded-lg bg-surface-container-low text-on-surface font-label-md text-label-md hover:bg-surface-container transition-colors">
                            Reset Filter
                        </a>
                    @endif
                    <a href="{{ route('teacher.exams.create') }}" class="inline-flex items-center gap-1.5 px-space-lg py-2 rounded-lg bg-primary text-on-primary font-label-md text-label-md shadow-sm hover:bg-primary-container transition-all">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        <span>Buat Ujian Baru</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination Bar -->
    @if($exams->hasPages())
        <div class="flex flex-col sm:flex-row items-center justify-between gap-space-md mt-space-lg p-space-md rounded-xl bg-surface-container-lowest shadow-sm border border-slate-100">
            <span class="font-body-sm text-body-sm text-on-surface-variant">
                Menampilkan <strong class="text-on-surface">{{ $exams->firstItem() }} - {{ $exams->lastItem() }}</strong> dari total <strong class="text-on-surface">{{ $exams->total() }}</strong> sesi ujian
            </span>
            <div>
                {{ $exams->links('vendor.pagination.tailwind-custom') }}
            </div>
        </div>
    @endif
</div>
@endsection
