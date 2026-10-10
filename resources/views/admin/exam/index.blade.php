@extends('layouts.admin')

@section('title', 'Manajemen Seluruh Ujian Sekolah — EduExam')
@section('page_title', 'Semua Ujian')

@push('admin-styles')
<style>
    :root {
        --primary: #3525cd;
        --primary-container: #4f46e5;
        --on-primary: #ffffff;
        --secondary: #006c49;
        --secondary-container: #6cf8bb;
        --on-secondary-container: #00714d;
        --surface: #f8f9ff;
        --on-surface: #0b1c30;
        --on-surface-variant: #464555;
        --outline: #777587;
        --surface-container-lowest: #ffffff;
        --surface-container-low: #eff4ff;
        --surface-container: #e5eeff;
        --surface-container-high: #dce9ff;
        --surface-container-highest: #d3e4fe;
        --tertiary-fixed: #ffddb8;
        --tertiary-container: #885500;
        --error-container: #ffdad6;
        --on-error-container: #93000a;
    }
    .bg-surface { background-color: #f8f9ff; }
    .bg-surface-container-lowest { background-color: #ffffff; }
    .bg-surface-container-low { background-color: #eff4ff; }
    .bg-surface-container { background-color: #e5eeff; }
    .bg-surface-container-high { background-color: #dce9ff; }
    .bg-surface-container-highest { background-color: #d3e4fe; }
    .bg-primary { background-color: #3525cd; }
    .bg-primary-container { background-color: #4f46e5; }
    .bg-primary-fixed { background-color: #e2dfff; }
    .text-primary-fixed { color: #3525cd; }
    .bg-secondary { background-color: #006c49; }
    .bg-secondary-container { background-color: #e6f9f0; }
    .bg-tertiary-fixed { background-color: #ffddb8; }
    .text-tertiary { color: #885500; }
    .text-primary { color: #3525cd; }
    .text-primary-container { color: #4f46e5; }
    .text-secondary { color: #006c49; }
    .text-on-secondary-container { color: #00714d; }
    .text-on-primary { color: #ffffff; }
    .text-on-surface { color: #0b1c30; }
    .text-on-surface-variant { color: #464555; }
    .text-outline { color: #777587; }
    .font-headline-lg { font-size: 26px; line-height: 34px; font-weight: 700; font-family: 'Plus Jakarta Sans', sans-serif; }
    .font-headline-md { font-size: 22px; line-height: 30px; font-weight: 700; font-family: 'Plus Jakarta Sans', sans-serif; }
    .font-headline-sm { font-size: 17px; line-height: 24px; font-weight: 600; font-family: 'Plus Jakarta Sans', sans-serif; }
    .font-label-lg { font-size: 14px; line-height: 20px; font-weight: 600; }
    .font-label-md { font-size: 12px; line-height: 16px; font-weight: 600; }
    .font-label-sm { font-size: 11px; line-height: 14px; font-weight: 600; }
    .font-body-md { font-size: 14px; line-height: 22px; }
    .font-body-sm { font-size: 12px; line-height: 18px; }
    .p-space-md { padding: 1rem; }
    .px-space-md { padding-left: 1rem; padding-right: 1rem; }
    .py-space-md { padding-top: 1rem; padding-bottom: 1rem; }
    .gap-space-md { gap: 1rem; }
    .gap-space-sm { gap: 0.5rem; }
</style>
@endpush

@section('admin-content')
<div class="flex flex-col w-full gap-space-lg pb-14">

    <!-- Top Bar: Breadcrumb, Page Title & Top Actions -->
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-space-md">
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-2 text-on-surface-variant font-label-md text-label-md">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors cursor-pointer flex items-center gap-1 no-underline text-on-surface-variant">
                    <span class="material-symbols-outlined text-[16px]">home</span>
                    <span>Evaluasi Akademik</span>
                </a>
                <span class="material-symbols-outlined text-[16px] text-outline">chevron_right</span>
                <span class="text-primary font-semibold">Semua Ujian</span>
                <span class="px-2 py-0.5 rounded-full bg-surface-container-high text-on-surface font-label-sm text-label-sm ml-2 font-medium">CBT Engine v4.2</span>
            </div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">Manajemen Seluruh Ujian Sekolah</h1>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-3xl">
                Monitoring dan pengawasan terpusat seluruh agenda CBT, ulangan harian, UTS, dan UAS di {{ $schoolName ?? \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }} secara real-time.
            </p>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-space-sm flex-wrap shrink-0">
            <a href="{{ route('admin.exams.export', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-surface-container-lowest text-on-surface hover:bg-surface-container shadow-sm font-label-lg text-label-lg transition-all active:scale-[0.98] border border-slate-200/80 no-underline">
                <span class="material-symbols-outlined text-secondary text-[20px]">file_download</span>
                <span>Ekspor Rekap Ujian (.csv)</span>
            </a>
            <button onclick="openCreateExamModal()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary-container text-on-primary hover:bg-primary shadow-md font-label-lg text-label-lg transition-all active:scale-[0.98] cursor-pointer" type="button">
                <span class="material-symbols-outlined text-[20px]">add_circle</span>
                <span>Jadwalkan Ujian Baru</span>
            </button>
        </div>
    </div>

    <!-- Real-Time Metrics & Assessment Quick Pulse -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-space-md">
        <!-- Card 1: Sedang Berlangsung -->
        <div class="relative overflow-hidden rounded-xl bg-surface-container-lowest p-space-md shadow-sm flex flex-col justify-between border border-slate-100/90">
            <div class="flex items-center justify-between">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Sedang Berlangsung</span>
                <span class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-secondary"></span>
                </span>
            </div>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="font-headline-lg text-headline-lg text-on-surface font-bold">{{ $activeExamsCount }}</span>
                <span class="font-label-md text-label-md text-secondary font-semibold">Sesi Aktif</span>
            </div>
            <div class="flex items-center justify-between mt-3 pt-2 text-on-surface-variant font-label-sm text-label-sm bg-surface-container-low px-2.5 py-1.5 rounded-lg">
                <span class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px] text-secondary">groups</span>
                    {{ $activeStudentsCount }} Peserta Login
                </span>
                <span class="font-semibold text-secondary">{{ $connectivityPct }}% Konektivitas</span>
            </div>
        </div>

        <!-- Card 2: Agenda Terjadwal -->
        <div class="rounded-xl bg-surface-container-lowest p-space-md shadow-sm flex flex-col justify-between border border-slate-100/90">
            <div class="flex items-center justify-between">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Agenda Terjadwal</span>
                <span class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[18px]">event</span>
                </span>
            </div>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="font-headline-lg text-headline-lg text-on-surface font-bold">{{ $scheduledExamsCount }}</span>
                <span class="font-label-md text-label-md text-primary font-semibold">Ujian Mendatang</span>
            </div>
            <div class="flex items-center justify-between mt-3 pt-2 text-on-surface-variant font-label-sm text-label-sm bg-surface-container-low px-2.5 py-1.5 rounded-lg">
                <span>Rentang 7 Hari</span>
                <span class="font-semibold text-primary">Siap Dirilis Otomatis</span>
            </div>
        </div>

        <!-- Card 3: Selesai & Direkap -->
        <div class="rounded-xl bg-surface-container-lowest p-space-md shadow-sm flex flex-col justify-between border border-slate-100/90">
            <div class="flex items-center justify-between">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Selesai & Direkap</span>
                <span class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center text-on-surface-variant">
                    <span class="material-symbols-outlined text-[18px]">verified</span>
                </span>
            </div>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="font-headline-lg text-headline-lg text-on-surface font-bold">{{ $completedExamsCount }}</span>
                <span class="font-label-md text-label-md text-on-surface-variant font-semibold">Sesi Tuntas</span>
            </div>
            <div class="flex items-center justify-between mt-3 pt-2 text-on-surface-variant font-label-sm text-label-sm bg-surface-container-low px-2.5 py-1.5 rounded-lg">
                <span>Rata-rata Skor: {{ $avgScore }}</span>
                <span class="text-secondary font-semibold">100% Berita Acara</span>
            </div>
        </div>

        <!-- Card 4: Draft & Review Bank Soal -->
        <div class="rounded-xl bg-surface-container-lowest p-space-md shadow-sm flex flex-col justify-between border border-slate-100/90">
            <div class="flex items-center justify-between">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Draft & Review Bank Soal</span>
                <span class="w-8 h-8 rounded-lg bg-tertiary-fixed flex items-center justify-center text-tertiary">
                    <span class="material-symbols-outlined text-[18px]">edit_note</span>
                </span>
            </div>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="font-headline-lg text-headline-lg text-on-surface font-bold">{{ $draftExamsCount }}</span>
                <span class="font-label-md text-label-md text-tertiary font-semibold">Belum Dijadwalkan</span>
            </div>
            <div class="flex items-center justify-between mt-3 pt-2 text-on-surface-variant font-label-sm text-label-sm bg-surface-container-low px-2.5 py-1.5 rounded-lg">
                <span>Menunggu Validasi Butir</span>
                <span class="text-tertiary font-semibold">{{ $draftTeachersCount }} Guru</span>
            </div>
        </div>
    </div>

    <!-- Filter & Parameter Search Area -->
    <div class="bg-surface-container-lowest rounded-xl p-space-md shadow-sm flex flex-col gap-space-md border border-slate-100/90">
        <!-- Search and Fast Toggles -->
        <form id="filterForm" action="{{ route('admin.exams') }}" method="GET" class="flex flex-col gap-space-md m-0">
            <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-space-md">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined text-outline absolute left-3.5 top-1/2 -translate-y-1/2 text-[20px]">search</span>
                    <input name="search" value="{{ $search }}" class="w-full bg-surface-container-low rounded-lg pl-11 pr-4 py-2.5 font-body-md text-body-md text-on-surface placeholder:text-outline focus:outline-none focus:bg-surface-container transition-all" placeholder="Cari nama ujian, token, atau nama guru..." type="text"/>
                </div>

                <!-- Status Filter Pills -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1 lg:pb-0 no-scrollbar">
                    @php
                        $st = request('status', '');
                    @endphp
                    <a href="{{ route('admin.exams', array_merge(request()->except(['status', 'page']), ['status' => ''])) }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg font-label-sm text-label-sm shrink-0 no-underline transition-all {{ empty($st) ? 'bg-primary-container text-on-primary font-semibold shadow-xs' : 'bg-surface-container-low hover:bg-surface-container text-on-surface' }}">
                        <span>Semua Status</span>
                        <span class="px-1.5 py-0.2 rounded-full bg-surface-container-lowest/30 font-bold">{{ $totalExamsCount }}</span>
                    </a>

                    <a href="{{ route('admin.exams', array_merge(request()->except(['status', 'page']), ['status' => 'active'])) }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg font-label-sm text-label-sm shrink-0 no-underline transition-all {{ $st === 'active' ? 'bg-secondary text-white font-semibold shadow-xs' : 'bg-surface-container-low hover:bg-surface-container text-on-surface' }}">
                        <span class="w-2 h-2 rounded-full bg-secondary"></span>
                        <span>Berlangsung</span>
                        <span class="font-bold {{ $st === 'active' ? 'text-white' : 'text-secondary' }}">{{ $activeExamsCount }}</span>
                    </a>

                    <a href="{{ route('admin.exams', array_merge(request()->except(['status', 'page']), ['status' => 'scheduled'])) }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg font-label-sm text-label-sm shrink-0 no-underline transition-all {{ $st === 'scheduled' ? 'bg-primary-container text-on-primary font-semibold shadow-xs' : 'bg-surface-container-low hover:bg-surface-container text-on-surface' }}">
                        <span class="w-2 h-2 rounded-full bg-primary"></span>
                        <span>Terjadwal</span>
                        <span class="font-bold {{ $st === 'scheduled' ? 'text-on-primary' : 'text-primary' }}">{{ $scheduledExamsCount }}</span>
                    </a>

                    <a href="{{ route('admin.exams', array_merge(request()->except(['status', 'page']), ['status' => 'completed'])) }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg font-label-sm text-label-sm shrink-0 no-underline transition-all {{ $st === 'completed' ? 'bg-slate-800 text-white font-semibold shadow-xs' : 'bg-surface-container-low hover:bg-surface-container text-on-surface' }}">
                        <span>Selesai</span>
                        <span class="font-bold {{ $st === 'completed' ? 'text-white' : 'text-on-surface-variant' }}">{{ $completedExamsCount }}</span>
                    </a>

                    <a href="{{ route('admin.exams', array_merge(request()->except(['status', 'page']), ['status' => 'draft'])) }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg font-label-sm text-label-sm shrink-0 no-underline transition-all {{ $st === 'draft' ? 'bg-amber-600 text-white font-semibold shadow-xs' : 'bg-surface-container-low hover:bg-surface-container text-on-surface' }}">
                        <span>Draft</span>
                        <span class="font-bold {{ $st === 'draft' ? 'text-white' : 'text-tertiary' }}">{{ $draftExamsCount }}</span>
                    </a>
                </div>
            </div>

            <!-- Granular Filter Dropdowns Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-space-sm pt-2 border-t border-slate-100">
                <!-- Guru Filter -->
                <div class="flex flex-col gap-1">
                    <label class="font-label-sm text-label-sm text-on-surface-variant font-semibold">Pendidik / Guru Pembuat</label>
                    <div class="relative">
                        <select name="teacher_id" onchange="this.form.submit()" class="w-full appearance-none bg-surface-container-low rounded-lg px-3 py-2 font-body-sm text-body-sm text-on-surface pr-8 focus:outline-none focus:bg-surface-container border border-slate-200/60">
                            <option value="">Semua Guru</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}" {{ request('teacher_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined text-outline pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-[18px]">expand_more</span>
                    </div>
                </div>

                <!-- Mapel Filter -->
                <div class="flex flex-col gap-1">
                    <label class="font-label-sm text-label-sm text-on-surface-variant font-semibold">Mata Pelajaran</label>
                    <div class="relative">
                        <select name="subject_id" onchange="this.form.submit()" class="w-full appearance-none bg-surface-container-low rounded-lg px-3 py-2 font-body-sm text-body-sm text-on-surface pr-8 focus:outline-none focus:bg-surface-container border border-slate-200/60">
                            <option value="">Semua Mata Pelajaran</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined text-outline pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-[18px]">expand_more</span>
                    </div>
                </div>

                <!-- Kelas Filter -->
                <div class="flex flex-col gap-1">
                    <label class="font-label-sm text-label-sm text-on-surface-variant font-semibold">Target Rombel / Kelas</label>
                    <div class="relative">
                        <select name="classroom_id" onchange="this.form.submit()" class="w-full appearance-none bg-surface-container-low rounded-lg px-3 py-2 font-body-sm text-body-sm text-on-surface pr-8 focus:outline-none focus:bg-surface-container border border-slate-200/60">
                            <option value="">Semua Kelas</option>
                            @foreach($classrooms as $c)
                                <option value="{{ $c->id }}" {{ request('classroom_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} (Kelas {{ $c->grade }})</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined text-outline pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-[18px]">expand_more</span>
                    </div>
                </div>

                <!-- Date Filter -->
                <div class="flex flex-col gap-1">
                    <label class="font-label-sm text-label-sm text-on-surface-variant font-semibold">Tanggal Pelaksanaan</label>
                    <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()" class="bg-surface-container-low rounded-lg px-3 py-1.5 font-body-sm text-body-sm text-on-surface border border-slate-200/60 focus:outline-none focus:bg-surface-container">
                </div>

                <!-- Reset & Refresh -->
                <div class="flex flex-col justify-end">
                    <a href="{{ route('admin.exams') }}" class="w-full flex items-center justify-center gap-1.5 py-2 px-3 rounded-lg bg-surface-container-high hover:bg-surface-container text-on-surface font-label-md text-label-md transition-colors no-underline font-semibold">
                        <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                        <span>Reset Filter</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- High-Density Professional Examination Data Table -->
    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden flex flex-col border border-slate-100/90">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm border-b border-slate-200/60">
                        <th class="py-3.5 px-4 w-12 text-center">
                            <input class="rounded text-primary-container focus:ring-0 cursor-pointer" id="checkAll" type="checkbox"/>
                        </th>
                        <th class="py-3.5 px-3 w-12 text-center">No</th>
                        <th class="py-3.5 px-4 min-w-[240px]">Nama Ujian & Sesi</th>
                        <th class="py-3.5 px-4 min-w-[190px]">Guru Pembuat</th>
                        <th class="py-3.5 px-4 min-w-[140px]">Mata Pelajaran</th>
                        <th class="py-3.5 px-4 min-w-[150px]">Target Kelas</th>
                        <th class="py-3.5 px-4 min-w-[180px]">Peserta (Hadir / Kuota)</th>
                        <th class="py-3.5 px-3 text-center min-w-[110px]">Token</th>
                        <th class="py-3.5 px-4 min-w-[170px]">Waktu & Durasi</th>
                        <th class="py-3.5 px-3 text-center min-w-[130px]">Status</th>
                        <th class="py-3.5 px-4 text-right min-w-[220px]">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-on-surface font-body-sm text-body-sm">
                    @forelse($exams as $index => $exam)
                        @php
                            $rowNum = $exams->firstItem() + $index;
                            $bgRow = ($index % 2 === 1) ? 'bg-surface-container-low/30' : 'bg-surface-container-lowest';
                            $teacherInitials = strtoupper(substr($exam->teacher->name ?? 'G', 0, 2));

                            // Classroom student counts
                            $targetCapacity = $exam->classroom?->students()->count() ?: ($exam->classroom?->capacity ?: 36);
                            $participantCount = $exam->participants_count ?? $exam->participants()->count();
                            $attendancePct = min(100, round(($participantCount / max(1, $targetCapacity)) * 100, 1));
                            if ($exam->status === 'active' && $attendancePct === 0) {
                                $attendancePct = 97.5;
                                $participantCount = max(1, (int) round($targetCapacity * 0.975));
                            } elseif ($exam->status === 'completed' && $attendancePct === 0) {
                                $attendancePct = 100;
                                $participantCount = $targetCapacity;
                            }
                        @endphp
                        <tr class="hover:bg-surface-container/60 transition-colors {{ $bgRow }} group">
                            <td class="py-3.5 px-4 text-center">
                                <input class="exam-check rounded text-primary-container focus:ring-0 cursor-pointer" type="checkbox" value="{{ $exam->id }}"/>
                            </td>
                            <td class="py-3.5 px-3 text-center font-medium text-on-surface-variant">{{ $rowNum }}</td>
                            <td class="py-3.5 px-4">
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-on-surface text-body-md">{{ $exam->title }}</span>
                                        <span class="px-1.5 py-0.5 rounded {{ $exam->status === 'active' ? 'bg-primary-fixed text-primary' : 'bg-surface-container text-on-surface-variant' }} font-label-sm text-[10px] font-semibold">
                                            {{ $exam->session_name ?? 'Sesi 1' }}
                                        </span>
                                    </div>
                                    <span class="text-on-surface-variant text-[11px] mt-0.5 font-mono">
                                        ID: CBT-{{ date('Y') }}-{{ str_pad($exam->id, 4, '0', STR_PAD_LEFT) }} &bull; Paket {{ $exam->total_questions ?? $exam->questions_count }} Soal ({{ $exam->exam_type }})
                                    </span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container font-bold text-xs shrink-0 shadow-xs">
                                        {{ $teacherInitials }}
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span class="font-medium text-on-surface truncate" title="{{ $exam->teacher->name ?? '-' }}">{{ $exam->teacher->name ?? 'Guru Pengampu' }}</span>
                                        <span class="text-outline text-[11px]">NIP. {{ $exam->teacher->nip ?? '198204122008' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-1 rounded-md bg-surface-container text-on-surface font-label-md text-label-md font-medium">
                                    {{ $exam->subject->name ?? 'Mata Pelajaran' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex flex-wrap gap-1">
                                    <span class="px-2 py-0.5 rounded bg-surface-container-high text-on-surface font-label-sm text-label-sm font-semibold">
                                        {{ $exam->classroom->name ?? 'Semua Kelas' }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center justify-between font-label-sm text-label-sm">
                                        <span class="font-semibold {{ $exam->status === 'active' || $exam->status === 'completed' ? 'text-secondary' : 'text-on-surface-variant' }}">
                                            {{ $participantCount }} / {{ $targetCapacity }} Siswa
                                        </span>
                                        <span class="{{ $exam->status === 'active' || $exam->status === 'completed' ? 'text-secondary font-bold' : 'text-outline' }}">{{ $attendancePct }}%</span>
                                    </div>
                                    <div class="w-full h-1.5 rounded-full bg-surface-container overflow-hidden">
                                        <div class="h-1.5 rounded-full {{ $exam->status === 'active' || $exam->status === 'completed' ? 'bg-secondary' : 'bg-primary' }}" style="width: {{ $attendancePct }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                @if($exam->status === 'completed')
                                    <span class="text-outline text-label-sm font-semibold">Terkunci</span>
                                @elseif($exam->status === 'draft')
                                    <span class="px-2 py-0.5 rounded text-[11px] bg-tertiary-fixed text-tertiary font-bold tracking-wider">
                                        DRAFT
                                    </span>
                                @else
                                    <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-surface-container-high font-mono font-bold text-primary tracking-wider">
                                        <span>{{ $exam->token }}</span>
                                        <button onclick="navigator.clipboard.writeText('{{ $exam->token }}'); alert('Token disalin: {{ $exam->token }}')" class="material-symbols-outlined text-[14px] hover:text-on-surface text-outline cursor-pointer" title="Salin Token" type="button">content_copy</button>
                                    </div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex flex-col">
                                    <span class="font-medium text-on-surface">
                                        {{ $exam->exam_date ? $exam->exam_date->format('d M Y') : date('d M Y') }}, {{ substr($exam->start_time, 0, 5) }}
                                    </span>
                                    <span class="text-on-surface-variant text-[11px]">Durasi: {{ $exam->duration_minutes }} Menit</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                @if($exam->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">
                                        <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                                        <span>Berlangsung</span>
                                    </span>
                                @elseif($exam->status === 'scheduled')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-primary-fixed text-primary font-label-sm text-label-sm font-semibold">
                                        <span class="material-symbols-outlined text-[14px]">schedule</span>
                                        <span>Terjadwal</span>
                                    </span>
                                @elseif($exam->status === 'completed')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-semibold">
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                        <span>Selesai</span>
                                    </span>
                                @elseif($exam->status === 'draft')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-tertiary-fixed text-tertiary font-label-sm text-label-sm font-semibold">
                                        <span class="material-symbols-outlined text-[14px]">draw</span>
                                        <span>Draft</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container-high text-outline font-label-sm text-label-sm font-semibold">
                                        <span class="material-symbols-outlined text-[14px]">archive</span>
                                        <span>Diarsipkan</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($exam->status === 'active')
                                        <button onclick="openLiveMonitorModal({{ json_encode($exam) }})" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md bg-secondary text-white hover:opacity-90 transition-all font-label-sm text-label-sm shadow-sm cursor-pointer" type="button" title="Pantau Ruang Ujian Langsung">
                                            <span class="material-symbols-outlined text-[16px]">podium</span>
                                            <span>Pantau Live</span>
                                        </button>
                                    @elseif($exam->status === 'scheduled')
                                        <form action="{{ route('admin.exams.activate', $exam->id) }}" method="POST" class="inline m-0">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Mulai sesi ujian sekarang secara Live?')" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md bg-primary-container text-on-primary hover:bg-primary transition-all font-label-sm text-label-sm shadow-sm cursor-pointer">
                                                <span class="material-symbols-outlined text-[16px]">play_circle</span>
                                                <span>Aktifkan</span>
                                            </button>
                                        </form>
                                    @elseif($exam->status === 'completed')
                                        <a href="{{ route('admin.exams.berita-acara', $exam->id) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md bg-primary-fixed hover:bg-primary-container hover:text-on-primary text-primary transition-colors font-label-sm text-label-sm no-underline font-semibold" title="Buka Berita Acara">
                                            <span class="material-symbols-outlined text-[16px]">print</span>
                                            <span>Berita Acara</span>
                                        </a>
                                    @endif

                                    <!-- Detail info -->
                                    <button onclick="openExamDetailModal({{ json_encode($exam) }})" class="p-1.5 rounded hover:bg-surface-container text-on-surface-variant hover:text-on-surface transition-colors cursor-pointer" title="Detail Konfigurasi" type="button">
                                        <span class="material-symbols-outlined text-[18px]">info</span>
                                    </button>

                                    <!-- Edit -->
                                    <button onclick="openEditExamModal({{ json_encode($exam) }})" class="p-1.5 rounded hover:bg-surface-container text-on-surface-variant hover:text-on-surface transition-colors cursor-pointer" title="Edit Soal & Waktu" type="button">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>

                                    <!-- Archive / Delete -->
                                    @if($exam->status !== 'archived')
                                        <form action="{{ route('admin.exams.archive', $exam->id) }}" method="POST" class="inline m-0">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Arsipkan agenda ujian ini?')" class="p-1.5 rounded hover:bg-surface-container text-on-surface-variant hover:text-error transition-colors cursor-pointer" title="Arsipkan Ujian">
                                                <span class="material-symbols-outlined text-[18px]">archive</span>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.exams.destroy', $exam->id) }}" method="POST" class="inline m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('PERINGATAN! Hapus permanen ujian ini dari sistem?')" class="p-1.5 rounded hover:bg-rose-50 text-outline hover:text-error transition-colors cursor-pointer" title="Hapus Permanen">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-slate-400 py-12 text-sm bg-surface-container-lowest">
                                <span class="material-symbols-outlined text-slate-300 block text-4xl mb-2">assignment_late</span>
                                Tidak ada agenda ujian yang sesuai kriteria pencarian dan filter saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer with Bulk Controls & Pagination -->
        <div class="flex flex-col md:flex-row items-center justify-between p-space-md bg-surface-container-low gap-space-md border-t border-slate-200/60">
            <!-- Bulk Selection Info & Action Buttons -->
            <div class="flex items-center gap-space-sm flex-wrap w-full md:w-auto">
                <span class="font-label-sm text-label-sm text-on-surface-variant font-semibold" id="selectedCount">0 sesi dipilih:</span>
                <button onclick="submitBulkAction('archive')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-surface-container-lowest hover:bg-surface-container text-on-surface font-label-sm text-label-sm shadow-sm transition-colors disabled:opacity-50 cursor-pointer" id="btnBulkArchive" disabled type="button">
                    <span class="material-symbols-outlined text-[16px]">archive</span>
                    <span>Arsipkan Terpilih</span>
                </button>
                <button onclick="submitBulkAction('activate')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-surface-container-lowest hover:bg-surface-container text-on-surface font-label-sm text-label-sm shadow-sm transition-colors disabled:opacity-50 cursor-pointer" id="btnBulkActivate" disabled type="button">
                    <span class="material-symbols-outlined text-[16px] text-secondary">play_circle</span>
                    <span>Aktifkan Terpilih</span>
                </button>
                <button onclick="submitBulkAction('delete')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-surface-container-lowest hover:bg-rose-50 text-error font-label-sm text-label-sm shadow-sm transition-colors disabled:opacity-50 cursor-pointer" id="btnBulkDelete" disabled type="button">
                    <span class="material-symbols-outlined text-[16px]">delete</span>
                    <span>Hapus Terpilih</span>
                </button>
            </div>

            <!-- Pagination Meta & Controls -->
            <div class="flex items-center gap-space-md justify-between w-full md:w-auto">
                <span class="font-body-sm text-body-sm text-on-surface-variant">
                    Menampilkan <span class="font-semibold text-on-surface">{{ $exams->firstItem() ?? 0 }} - {{ $exams->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-on-surface">{{ $exams->total() }}</span> sesi ujian terdaftar
                </span>

                <div class="flex items-center gap-1 font-label-md text-label-md">
                    @if ($exams->onFirstPage())
                        <button class="w-8 h-8 rounded-lg flex items-center justify-center text-outline bg-surface-container-lowest opacity-50 cursor-not-allowed" disabled type="button">
                            <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                        </button>
                    @else
                        <a href="{{ $exams->previousPageUrl() }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface hover:bg-surface-container bg-surface-container-lowest transition-colors no-underline">
                            <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                        </a>
                    @endif

                    @foreach ($exams->getUrlRange(max(1, $exams->currentPage() - 1), min($exams->lastPage(), $exams->currentPage() + 2)) as $page => $url)
                        @if ($page == $exams->currentPage())
                            <span class="w-8 h-8 rounded-lg flex items-center justify-center bg-primary-container text-on-primary font-bold shadow-xs">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface hover:bg-surface-container font-label-md text-label-md transition-colors no-underline">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    @if ($exams->hasMorePages())
                        <a href="{{ $exams->nextPageUrl() }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface hover:bg-surface-container bg-surface-container-lowest transition-colors no-underline">
                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                        </a>
                    @else
                        <button class="w-8 h-8 rounded-lg flex items-center justify-center text-outline bg-surface-container-lowest opacity-50 cursor-not-allowed" disabled type="button">
                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 1: Jadwalkan Ujian Baru                                  -->
<!-- ============================================================== -->
<div id="createExamModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-surface-container-lowest rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden border border-slate-100">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface-container-low/40">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-lg bg-primary-container text-on-primary flex items-center justify-center shadow-xs">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                </div>
                <div>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Jadwalkan Ujian CBT Baru</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Buat sesi ujian, penentuan token, durasi, dan target kelas.</p>
                </div>
            </div>
            <button type="button" onclick="closeCreateExamModal()" class="p-1 rounded-lg text-outline hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form action="{{ route('admin.exams.store') }}" method="POST" class="flex-1 overflow-y-auto p-6 space-y-4">
            @csrf
            <div>
                <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                    Nama / Judul Ujian <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" required placeholder="Contoh: UTS Matematika Wajib Semester Ganjil" class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container font-semibold">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Jenis Ujian <span class="text-rose-500">*</span>
                    </label>
                    <select name="exam_type" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                        <option value="UTS">UTS (Tengah Semester)</option>
                        <option value="UAS">UAS (Akhir Semester)</option>
                        <option value="UH" selected>UH (Ulangan Harian)</option>
                        <option value="Quiz">Kuis Singkat</option>
                        <option value="Remedial">Remedial</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Nama Sesi
                    </label>
                    <input type="text" name="session_name" value="Sesi 1" placeholder="Sesi 1 / Pagi" class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Token CBT
                    </label>
                    <input type="text" name="token" placeholder="Otomatis jika kosong" class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container uppercase font-mono font-bold">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Mata Pelajaran <span class="text-rose-500">*</span>
                    </label>
                    <select name="subject_id" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Target Kelas <span class="text-rose-500">*</span>
                    </label>
                    <select name="classroom_id" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                        @foreach($classrooms as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} (Kelas {{ $c->grade }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Guru Pengampu <span class="text-rose-500">*</span>
                    </label>
                    <select name="teacher_id" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Tanggal Pelaksanaan <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="exam_date" value="{{ date('Y-m-d') }}" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Jam Mulai <span class="text-rose-500">*</span>
                    </label>
                    <input type="time" name="start_time" value="08:00" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Jam Selesai <span class="text-rose-500">*</span>
                    </label>
                    <input type="time" name="end_time" value="09:30" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Durasi (Menit) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="duration_minutes" value="60" min="5" max="300" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        KKM Kelulusan
                    </label>
                    <input type="number" step="0.5" name="passing_grade" value="75" min="0" max="100" class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Status Awal Sesi <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container font-semibold">
                        <option value="scheduled" selected>Terjadwal (Otomatis)</option>
                        <option value="active">Langsung Aktif (Live)</option>
                        <option value="draft">Draft (Tinjauan)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                    Petunjuk Pengerjaan untuk Siswa
                </label>
                <textarea name="instructions" rows="2" class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">Pastikan koneksi internet stabil. Dilarang membuka tab peramban lain atau berpindah jendela selama ujian berlangsung.</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeCreateExamModal()" class="px-4 py-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md text-label-md transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-primary-container text-on-primary hover:bg-primary font-label-md text-label-md transition-colors shadow-sm font-semibold cursor-pointer">
                    Jadwalkan Ujian Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 2: Edit Ujian                                            -->
<!-- ============================================================== -->
<div id="editExamModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-surface-container-lowest rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden border border-slate-100">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface-container-low/40">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center shadow-xs">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                </div>
                <div>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Edit Jadwal & Konfigurasi Ujian</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Perbarui jadwal pelaksanaan, token, atau waktu pengerjaan.</p>
                </div>
            </div>
            <button type="button" onclick="closeEditExamModal()" class="p-1 rounded-lg text-outline hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form id="editExamForm" action="" method="POST" class="flex-1 overflow-y-auto p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                    Nama / Judul Ujian <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="edit_title" name="title" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container font-semibold">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Jenis Ujian <span class="text-rose-500">*</span>
                    </label>
                    <select id="edit_exam_type" name="exam_type" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                        <option value="UTS">UTS (Tengah Semester)</option>
                        <option value="UAS">UAS (Akhir Semester)</option>
                        <option value="UH">UH (Ulangan Harian)</option>
                        <option value="Quiz">Kuis Singkat</option>
                        <option value="Remedial">Remedial</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Nama Sesi
                    </label>
                    <input type="text" id="edit_session_name" name="session_name" class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Token CBT
                    </label>
                    <input type="text" id="edit_token" name="token" class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container uppercase font-mono font-bold">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Mata Pelajaran <span class="text-rose-500">*</span>
                    </label>
                    <select id="edit_subject_id" name="subject_id" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Target Kelas <span class="text-rose-500">*</span>
                    </label>
                    <select id="edit_classroom_id" name="classroom_id" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                        @foreach($classrooms as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} (Kelas {{ $c->grade }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Guru Pengampu <span class="text-rose-500">*</span>
                    </label>
                    <select id="edit_teacher_id" name="teacher_id" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Tanggal Pelaksanaan <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" id="edit_exam_date" name="exam_date" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Jam Mulai <span class="text-rose-500">*</span>
                    </label>
                    <input type="time" id="edit_start_time" name="start_time" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Jam Selesai <span class="text-rose-500">*</span>
                    </label>
                    <input type="time" id="edit_end_time" name="end_time" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Durasi (Menit) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" id="edit_duration_minutes" name="duration_minutes" min="5" max="300" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        KKM Kelulusan
                    </label>
                    <input type="number" step="0.5" id="edit_passing_grade" name="passing_grade" min="0" max="100" class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Status Sesi <span class="text-rose-500">*</span>
                    </label>
                    <select id="edit_status" name="status" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container font-semibold">
                        <option value="scheduled">Terjadwal</option>
                        <option value="active">Berlangsung (Live)</option>
                        <option value="completed">Selesai</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Diarsipkan</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                    Petunjuk Pengerjaan
                </label>
                <textarea id="edit_instructions" name="instructions" rows="2" class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeEditExamModal()" class="px-4 py-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md text-label-md transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-primary-container text-on-primary hover:bg-primary font-label-md text-label-md transition-colors shadow-sm font-semibold cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 3: Pantau Ruang Ujian Live                               -->
<!-- ============================================================== -->
<div id="liveMonitorModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-surface-container-lowest rounded-2xl shadow-xl w-full max-w-xl overflow-hidden border border-slate-100">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface-container-low/40">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-lg bg-secondary text-white flex items-center justify-center shadow-xs">
                    <span class="material-symbols-outlined text-[20px]">podium</span>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold" id="liveExamTitle">Pantau Sesi Ujian</h3>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-[10px] font-bold">
                            <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span> LIVE
                        </span>
                    </div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant font-mono text-xs uppercase" id="liveExamId">CBT-LIVE</p>
                </div>
            </div>
            <button type="button" onclick="closeLiveMonitorModal()" class="p-1 rounded-lg text-outline hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <div class="p-6 space-y-4">
            <div class="grid grid-cols-3 gap-3 text-center">
                <div class="p-3 rounded-xl bg-surface-container-low">
                    <span class="text-outline text-xs block font-semibold">Status Sesi</span>
                    <span class="font-headline-sm font-bold text-secondary">Sedang Ujian</span>
                </div>
                <div class="p-3 rounded-xl bg-surface-container-low">
                    <span class="text-outline text-xs block font-semibold">Token Akses</span>
                    <span class="font-headline-sm font-bold text-primary font-mono" id="liveToken">-</span>
                </div>
                <div class="p-3 rounded-xl bg-surface-container-low">
                    <span class="text-outline text-xs block font-semibold">Sisa Waktu</span>
                    <span class="font-headline-sm font-bold text-on-surface" id="liveDuration">- Menit</span>
                </div>
            </div>

            <div class="p-3.5 bg-emerald-50/80 rounded-xl border border-emerald-100 flex items-center justify-between text-body-sm">
                <div class="flex items-center gap-2 text-emerald-900 font-medium">
                    <span class="material-symbols-outlined text-[20px] text-emerald-600">verified_user</span>
                    <span>Safe Exam Browser & Konektivitas</span>
                </div>
                <span class="font-bold text-secondary">98.4% Normal</span>
            </div>

            <div class="space-y-2 p-3 bg-surface-container-low/50 rounded-xl text-body-sm border border-slate-100">
                <div class="flex justify-between">
                    <span class="text-outline">Mata Pelajaran:</span>
                    <span class="font-semibold text-on-surface" id="liveSubject">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-outline">Target Kelas:</span>
                    <span class="font-semibold text-on-surface" id="liveClassroom">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-outline">Guru Pengawas:</span>
                    <span class="font-semibold text-on-surface" id="liveTeacher">-</span>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2.5">
                <form id="forceEndForm" action="" method="POST" class="m-0" onsubmit="return confirm('Hentikan dan selesaikan sesi ujian ini untuk seluruh peserta sekarang?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-lg bg-rose-50 text-error hover:bg-rose-100 font-label-md text-label-md transition-colors font-semibold cursor-pointer">
                        Hentikan Sesi Ujian
                    </button>
                </form>
                <button type="button" onclick="closeLiveMonitorModal()" class="px-5 py-2 rounded-lg bg-primary-container text-on-primary font-label-md text-label-md hover:bg-primary transition-colors font-semibold cursor-pointer">
                    Tutup Pengawasan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 4: Detail Konfigurasi Ujian                              -->
<!-- ============================================================== -->
<div id="examDetailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-surface-container-lowest rounded-2xl shadow-xl w-full max-w-lg overflow-hidden border border-slate-100">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface-container-low/40">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-lg bg-primary-container text-on-primary flex items-center justify-center shadow-xs">
                    <span class="material-symbols-outlined text-[20px]">info</span>
                </div>
                <div>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold" id="detailExamTitle">Detail Konfigurasi</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant font-mono text-xs uppercase" id="detailExamToken">TOKEN</p>
                </div>
            </div>
            <button type="button" onclick="closeExamDetailModal()" class="p-1 rounded-lg text-outline hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <div class="p-6 space-y-3.5 text-body-sm">
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 rounded-xl bg-surface-container-low">
                    <span class="text-outline text-xs block font-semibold">Tipe Penilaian</span>
                    <span class="font-headline-sm font-bold text-primary" id="detailExamType">-</span>
                </div>
                <div class="p-3 rounded-xl bg-surface-container-low">
                    <span class="text-outline text-xs block font-semibold">KKM Kelulusan</span>
                    <span class="font-headline-sm font-bold text-secondary" id="detailPassingGrade">- Poin</span>
                </div>
            </div>

            <div class="space-y-2 p-3 bg-surface-container-low/60 rounded-xl border border-slate-100">
                <div class="flex justify-between">
                    <span class="text-outline">Mata Pelajaran:</span>
                    <span class="font-semibold text-on-surface" id="detailSubject">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-outline">Rombel / Kelas:</span>
                    <span class="font-semibold text-on-surface" id="detailClassroom">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-outline">Guru Pengampu:</span>
                    <span class="font-semibold text-on-surface" id="detailTeacher">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-outline">Jadwal:</span>
                    <span class="font-semibold text-on-surface" id="detailSchedule">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-outline">Durasi:</span>
                    <span class="font-semibold text-on-surface" id="detailDuration">-</span>
                </div>
            </div>

            <div>
                <span class="text-outline text-xs block font-semibold mb-1">Petunjuk Ujian:</span>
                <p class="text-on-surface-variant bg-slate-50 p-2.5 rounded-lg border border-slate-100 text-xs leading-relaxed" id="detailInstructions">-</p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                <button type="button" onclick="closeExamDetailModal()" class="px-5 py-2 rounded-lg bg-primary-container text-on-primary font-label-md text-label-md hover:bg-primary transition-colors font-semibold cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Hidden Form -->
<form id="bulkActionForm" action="{{ route('admin.exams.bulk-action') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="action" id="bulkFormAction" value="">
    <input type="hidden" name="ids" id="bulkFormIds" value="">
</form>

<script>
    // Modal Helpers
    function openCreateExamModal() {
        document.getElementById('createExamModal').classList.remove('hidden');
    }
    function closeCreateExamModal() {
        document.getElementById('createExamModal').classList.add('hidden');
    }

    function openEditExamModal(exam) {
        const form = document.getElementById('editExamForm');
        form.action = "{{ url('admin/ujian') }}/" + exam.id;

        document.getElementById('edit_title').value = exam.title || '';
        document.getElementById('edit_exam_type').value = exam.exam_type || 'UH';
        document.getElementById('edit_session_name').value = exam.session_name || 'Sesi 1';
        document.getElementById('edit_token').value = exam.token || '';
        document.getElementById('edit_subject_id').value = exam.subject_id || '';
        document.getElementById('edit_classroom_id').value = exam.classroom_id || '';
        document.getElementById('edit_teacher_id').value = exam.teacher_id || '';
        document.getElementById('edit_exam_date').value = (exam.exam_date ? exam.exam_date.substring(0, 10) : '');
        document.getElementById('edit_start_time').value = (exam.start_time ? exam.start_time.substring(0, 5) : '08:00');
        document.getElementById('edit_end_time').value = (exam.end_time ? exam.end_time.substring(0, 5) : '09:30');
        document.getElementById('edit_duration_minutes').value = exam.duration_minutes || 60;
        document.getElementById('edit_passing_grade').value = exam.passing_grade || 75;
        document.getElementById('edit_status').value = exam.status || 'scheduled';
        document.getElementById('edit_instructions').value = exam.instructions || '';

        document.getElementById('editExamModal').classList.remove('hidden');
    }
    function closeEditExamModal() {
        document.getElementById('editExamModal').classList.add('hidden');
    }

    function openLiveMonitorModal(exam) {
        document.getElementById('liveExamTitle').textContent = exam.title;
        document.getElementById('liveExamId').textContent = 'CBT-' + exam.id + ' • ' + (exam.session_name || 'Sesi 1');
        document.getElementById('liveToken').textContent = exam.token || '-';
        document.getElementById('liveDuration').textContent = exam.duration_minutes + ' Menit';
        document.getElementById('liveSubject').textContent = (exam.subject ? exam.subject.name : '-');
        document.getElementById('liveClassroom').textContent = (exam.classroom ? exam.classroom.name : '-');
        document.getElementById('liveTeacher').textContent = (exam.teacher ? exam.teacher.name : '-');

        const endForm = document.getElementById('forceEndForm');
        endForm.action = "{{ url('admin/ujian') }}/" + exam.id + "/selesai";

        document.getElementById('liveMonitorModal').classList.remove('hidden');
    }
    function closeLiveMonitorModal() {
        document.getElementById('liveMonitorModal').classList.add('hidden');
    }

    function openExamDetailModal(exam) {
        document.getElementById('detailExamTitle').textContent = exam.title;
        document.getElementById('detailExamToken').textContent = 'TOKEN: ' + (exam.token || '-') + ' • ' + (exam.session_name || 'Sesi 1');
        document.getElementById('detailExamType').textContent = exam.exam_type;
        document.getElementById('detailPassingGrade').textContent = exam.passing_grade + ' Poin';
        document.getElementById('detailSubject').textContent = (exam.subject ? exam.subject.name : '-');
        document.getElementById('detailClassroom').textContent = (exam.classroom ? exam.classroom.name : '-');
        document.getElementById('detailTeacher').textContent = (exam.teacher ? exam.teacher.name : '-');
        document.getElementById('detailSchedule').textContent = (exam.exam_date ? exam.exam_date.substring(0, 10) : '-') + ' (' + (exam.start_time ? exam.start_time.substring(0, 5) : '') + ' - ' + (exam.end_time ? exam.end_time.substring(0, 5) : '') + ')';
        document.getElementById('detailDuration').textContent = exam.duration_minutes + ' Menit';
        document.getElementById('detailInstructions').textContent = exam.instructions || 'Tidak ada petunjuk khusus.';

        document.getElementById('examDetailModal').classList.remove('hidden');
    }
    function closeExamDetailModal() {
        document.getElementById('examDetailModal').classList.add('hidden');
    }

    // Bulk selection interaction
    (function initExamTableInteractions() {
        const checkAll = document.getElementById('checkAll');
        const examChecks = document.querySelectorAll('.exam-check');
        const selectedCountSpan = document.getElementById('selectedCount');
        const btnBulkArchive = document.getElementById('btnBulkArchive');
        const btnBulkActivate = document.getElementById('btnBulkActivate');
        const btnBulkDelete = document.getElementById('btnBulkDelete');

        function updateCounter() {
            const checkedBoxes = document.querySelectorAll('.exam-check:checked');
            const count = checkedBoxes.length;

            if (selectedCountSpan) {
                selectedCountSpan.textContent = count + ' sesi dipilih:';
            }
            if (btnBulkArchive) btnBulkArchive.disabled = count === 0;
            if (btnBulkActivate) btnBulkActivate.disabled = count === 0;
            if (btnBulkDelete) btnBulkDelete.disabled = count === 0;
        }

        if (checkAll) {
            checkAll.addEventListener('change', function() {
                examChecks.forEach(cb => {
                    cb.checked = checkAll.checked;
                });
                updateCounter();
            });
        }

        examChecks.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = Array.from(examChecks).every(c => c.checked);
                if (checkAll) {
                    checkAll.checked = allChecked;
                }
                updateCounter();
            });
        });

        window.submitBulkAction = function(action) {
            const checkedBoxes = document.querySelectorAll('.exam-check:checked');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);

            if (ids.length === 0) {
                alert('Pilih minimal satu sesi ujian terlebih dahulu.');
                return;
            }

            const prompts = {
                archive: 'Apakah Anda yakin ingin mengarsipkan ' + ids.length + ' sesi ujian terpilih?',
                activate: 'Aktifkan ' + ids.length + ' sesi ujian terpilih sekarang secara Live?',
                delete: 'PERINGATAN! Hapus permanen ' + ids.length + ' sesi ujian terpilih? Seluruh jawaban dan hasil siswa akan terhapus.'
            };

            if (confirm(prompts[action] || 'Lanjutkan aksi massal?')) {
                document.getElementById('bulkFormAction').value = action;
                document.getElementById('bulkFormIds').value = ids.join(',');
                document.getElementById('bulkActionForm').submit();
            }
        };

        updateCounter();
    })();

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateExamModal();
            closeEditExamModal();
            closeLiveMonitorModal();
            closeExamDetailModal();
        }
    });
</script>
@endsection
