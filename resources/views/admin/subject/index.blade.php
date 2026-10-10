@extends('layouts.admin')

@section('title', 'Manajemen Mata Pelajaran & Kurikulum — EduExam')
@section('page_title', 'Mata Pelajaran')

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
    }
    .bg-surface { background-color: #f8f9ff; }
    .bg-surface-container-lowest { background-color: #ffffff; }
    .bg-surface-container-low { background-color: #eff4ff; }
    .bg-surface-container { background-color: #e5eeff; }
    .bg-surface-container-high { background-color: #dce9ff; }
    .bg-surface-container-highest { background-color: #d3e4fe; }
    .bg-primary { background-color: #3525cd; }
    .bg-primary-container { background-color: #4f46e5; }
    .bg-secondary { background-color: #006c49; }
    .bg-secondary-container { background-color: #e6f9f0; }
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
    .space-y-space-lg > :not([hidden]) ~ :not([hidden]) { margin-top: 1.5rem; }
</style>
@endpush

@section('admin-content')
<div class="flex flex-col w-full space-y-space-lg pb-12">

    <!-- Top Navigation & Action Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-space-md">
        <div class="flex flex-col">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 mb-1.5 text-on-surface-variant font-label-sm text-label-sm">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors cursor-pointer flex items-center gap-1">
                    <span class="material-symbols-outlined text-[15px]">home</span>
                    <span>Master Data</span>
                </a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-semibold">Mata Pelajaran</span>
            </nav>
            <!-- Title & Subtitle -->
            <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Manajemen Mata Pelajaran & Kurikulum</h1>
            <p class="font-body-md text-body-md text-on-surface-variant mt-0.5">
                Daftar master mata pelajaran Kurikulum Merdeka {{ $schoolName ?? \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}, penetapan standar KKM sekolah, dan bank soal terdaftar.
            </p>
        </div>
        <!-- Actions -->
        <div class="flex items-center gap-space-sm shrink-0 flex-wrap">
            <button onclick="openSyncModal()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-surface-container-high text-on-surface hover:bg-surface-container-highest transition-all font-label-lg text-label-lg shadow-sm active:scale-95 cursor-pointer" type="button">
                <span class="material-symbols-outlined text-[18px] text-primary">sync</span>
                <span>Sinkronisasi Kurikulum</span>
            </button>
            <button onclick="openCreateSubjectModal()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary-container text-on-primary hover:bg-primary transition-all font-label-lg text-label-lg shadow-sm active:scale-95 cursor-pointer" type="button">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>+ Tambah Mata Pelajaran</span>
            </button>
        </div>
    </div>

    <!-- Metric Badges & KKM Target Highlighting Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-space-md">
        <!-- Metric 1: Total Terdaftar -->
        <div class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex items-center gap-4 border border-slate-100/80">
            <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-primary shrink-0">
                <span class="material-symbols-outlined text-[26px]">menu_book</span>
            </div>
            <div class="flex flex-col min-w-0">
                <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Total Terdaftar</span>
                <div class="flex items-baseline gap-2">
                    <span class="font-headline-md text-headline-md text-on-surface font-bold">{{ $totalSubjects }}</span>
                    <span class="font-label-sm text-label-sm text-secondary font-semibold">{{ $totalActiveSubjects }} Mapel Aktif</span>
                </div>
            </div>
        </div>

        <!-- Metric 2: Bank Soal Terhimpun -->
        <div class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex items-center gap-4 border border-slate-100/80">
            <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-secondary shrink-0">
                <span class="material-symbols-outlined text-[26px]">quiz</span>
            </div>
            <div class="flex flex-col min-w-0">
                <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Bank Soal Terhimpun</span>
                <div class="flex items-baseline gap-2">
                    <span class="font-headline-md text-headline-md text-on-surface font-bold">{{ number_format($totalQuestions) }}</span>
                    <span class="font-label-sm text-label-sm text-outline">Butir Terverifikasi</span>
                </div>
            </div>
        </div>

        <!-- Metric 3: Ujian Berjalan -->
        <div class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex items-center gap-4 border border-slate-100/80">
            <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-primary-container shrink-0">
                <span class="material-symbols-outlined text-[26px]">timer</span>
            </div>
            <div class="flex flex-col min-w-0">
                <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Ujian Berjalan</span>
                <div class="flex items-baseline gap-2">
                    <span class="font-headline-md text-headline-md text-on-surface font-bold">{{ $activeExamsCount }}</span>
                    <span class="font-label-sm text-label-sm text-secondary font-semibold">Sesi Aktif</span>
                </div>
            </div>
        </div>

        <!-- Metric 4: KKM Standar Sekolah -->
        <div class="p-space-md rounded-xl bg-secondary-container/40 shadow-sm flex items-center justify-between border border-emerald-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-secondary text-on-secondary flex items-center justify-center shrink-0 shadow-sm">
                    <span class="material-symbols-outlined text-[24px]">grade</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-label-sm text-label-sm text-on-secondary-container font-semibold uppercase tracking-wider">KKM Standar Sekolah</span>
                    <span class="font-headline-sm text-headline-sm text-on-secondary-container font-bold">{{ number_format($schoolKkm, 1) }} Poin</span>
                </div>
            </div>
            <button type="button" onclick="openKkmModal()" class="px-2.5 py-1 rounded-full bg-surface-container-lowest text-secondary font-label-sm text-label-sm shadow-sm font-semibold hover:bg-surface transition-all cursor-pointer" title="Ubah Standar KKM">
                SK-2026/G1
            </button>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex flex-col md:flex-row items-stretch md:items-center justify-between gap-space-md border border-slate-100/80">
        <!-- Search Bar -->
        <form action="{{ route('admin.subjects') }}" method="GET" class="flex-1 flex items-center bg-surface-container-low rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-primary-container transition-all">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <span class="material-symbols-outlined text-outline text-[20px] mr-2.5">search</span>
            <input name="search" value="{{ $search }}" class="w-full bg-transparent border-none outline-none font-body-sm text-body-sm text-on-surface placeholder:text-outline" placeholder="Cari mata pelajaran, kode mapel, atau guru pengampu..." type="text"/>
            @if($search)
                <a href="{{ route('admin.subjects', ['category' => request('category')]) }}" class="text-outline hover:text-on-surface text-xs font-semibold px-2 py-0.5 rounded hover:bg-surface-container">Reset</a>
            @endif
        </form>

        <!-- Categorical Filter Pills -->
        @php
            $currentCat = request('category', '');
            $categories = ['Semua Kategori', 'Wajib Umum', 'MIPA', 'IPS', 'Bahasa & Seni', 'Muatan Lokal'];
        @endphp
        <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0 no-scrollbar">
            @foreach($categories as $cat)
                @php
                    $isActive = ($cat === 'Semua Kategori' && empty($currentCat)) || ($currentCat === $cat);
                    $targetUrl = route('admin.subjects', array_filter(['search' => request('search'), 'category' => ($cat === 'Semua Kategori' ? null : $cat)]));
                @endphp
                <a href="{{ $targetUrl }}" class="px-3.5 py-1.5 rounded-lg font-label-md text-label-md shrink-0 transition-all no-underline {{ $isActive ? 'bg-primary-container text-on-primary shadow-sm' : 'bg-surface-container-low hover:bg-surface-container text-on-surface-variant' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Subjects Grid Layout (Master Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-space-md">
        @forelse($subjects as $subject)
            @php
                $cat = $subject->category ?? 'Wajib Umum';
                // Color palette according to category
                $iconBg = match($cat) {
                    'MIPA' => 'bg-primary/10 text-primary',
                    'Wajib Umum' => 'bg-secondary/10 text-secondary',
                    'IPS' => 'bg-amber-100 text-amber-900',
                    'Bahasa & Seni' => 'bg-indigo-100 text-indigo-700',
                    'Muatan Lokal' => 'bg-teal-100 text-teal-800',
                    default => 'bg-primary/10 text-primary',
                };
                $teachersList = $subject->teachers;
                $teacherLabel = 'Belum Ditentukan';
                $teacherTooltip = 'Belum ada guru pengampu yang ditugaskan.';
                if ($teachersList->isNotEmpty()) {
                    $teacherNames = $teachersList->pluck('name')->toArray();
                    $teacherTooltip = implode(', ', $teacherNames);
                    $firstTeacher = $teachersList->first()->name;
                    $teacherLabel = count($teacherNames) > 1 ? $firstTeacher . ' +' . (count($teacherNames) - 1) : $firstTeacher;
                }
            @endphp
            <div class="group flex flex-col justify-between p-space-md rounded-xl bg-surface-container-lowest shadow-sm hover:shadow-md transition-all border border-slate-100/90">
                <div>
                    <!-- Card Header: Icon & Badges -->
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div class="w-11 h-11 rounded-lg {{ $iconBg }} flex items-center justify-center shrink-0 shadow-xs">
                            <span class="material-symbols-outlined text-[24px]">{{ $subject->icon }}</span>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">
                                {{ $subject->exams_count }} Sesi Ujian
                            </span>
                            <span class="font-label-sm text-label-sm text-outline mt-1 font-mono uppercase font-bold tracking-wider">
                                {{ $subject->code }}
                            </span>
                        </div>
                    </div>

                    <!-- Subject Title -->
                    <h2 class="font-headline-sm text-headline-sm text-on-surface group-hover:text-primary transition-colors font-bold line-clamp-1" title="{{ $subject->name }}">
                        {{ $subject->name }}
                    </h2>

                    <!-- Badges -->
                    <div class="flex flex-wrap items-center gap-1.5 mt-1.5 mb-4">
                        <span class="px-2 py-0.5 rounded bg-surface-container-high text-on-surface font-label-sm text-label-sm font-semibold">
                            {{ $subject->category ?? 'Wajib Umum' }}
                        </span>
                        <span class="px-2 py-0.5 rounded bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm">
                            {{ $subject->target_grades ?? 'Kelas X, XI, XII' }}
                        </span>
                    </div>

                    <!-- Subject Details Box -->
                    <div class="space-y-2 py-2.5 my-2 bg-surface-container-low/60 rounded-lg px-3 border border-slate-100/60">
                        <div class="flex items-center justify-between font-body-sm text-body-sm">
                            <span class="text-on-surface-variant flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[15px] text-outline">verified</span> KKM Standar:
                            </span>
                            <span class="font-semibold text-on-surface">{{ number_format($subject->passing_grade ?? $schoolKkm, 0) }} Poin</span>
                        </div>
                        <div class="flex items-center justify-between font-body-sm text-body-sm">
                            <span class="text-on-surface-variant flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[15px] text-outline">person</span> Pengampu:
                            </span>
                            <span class="font-semibold text-on-surface truncate max-w-[140px]" title="{{ $teacherTooltip }}">
                                {{ $teacherLabel }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between font-body-sm text-body-sm">
                            <span class="text-on-surface-variant flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[15px] text-outline">storage</span> Bank Soal:
                            </span>
                            <span class="font-semibold text-primary">{{ $subject->questions_count }} Butir</span>
                        </div>
                    </div>
                </div>

                <!-- Card Actions -->
                <div class="pt-3 flex items-center justify-between gap-1.5 mt-2 border-t border-slate-100/80">
                    <button type="button" onclick="openEditSubjectModal({{ json_encode([
                        'id' => $subject->id,
                        'name' => $subject->name,
                        'code' => $subject->code,
                        'category' => $subject->category,
                        'target_grades' => $subject->target_grades,
                        'passing_grade' => $subject->passing_grade,
                        'icon' => $subject->icon,
                        'description' => $subject->description,
                        'is_active' => $subject->is_active,
                        'teacher_ids' => $subject->teachers->pluck('id')->toArray(),
                    ]) }})" class="flex-1 py-1.5 px-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-sm text-label-sm text-center transition-colors font-semibold cursor-pointer">
                        Edit Mapel
                    </button>
                    <button type="button" onclick="openBankSoalModal({{ json_encode([
                        'id' => $subject->id,
                        'name' => $subject->name,
                        'code' => $subject->code,
                        'questions_count' => $subject->questions_count,
                        'exams_count' => $subject->exams_count,
                        'category' => $subject->category,
                        'passing_grade' => $subject->passing_grade,
                        'teachers' => $subject->teachers->pluck('name')->toArray(),
                    ]) }})" class="flex-1 py-1.5 px-2 rounded-lg bg-primary-container text-on-primary font-label-sm text-label-sm text-center transition-colors font-semibold hover:bg-primary cursor-pointer">
                        Bank Soal
                    </button>
                    <button type="button" onclick="confirmDeleteSubject('{{ $subject->id }}', '{{ addslashes($subject->name) }}', {{ $subject->questions_count }}, {{ $subject->exams_count }})" class="p-1.5 rounded-lg text-outline hover:text-error hover:bg-rose-50 transition-colors cursor-pointer" title="Hapus Mapel">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full p-space-md rounded-xl bg-surface-container-lowest shadow-sm text-center py-12 border border-dashed border-slate-200">
                <div class="w-16 h-16 rounded-full bg-surface-container mx-auto flex items-center justify-center text-outline mb-3">
                    <span class="material-symbols-outlined text-[32px]">menu_book</span>
                </div>
                <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Tidak ada mata pelajaran ditemukan</h3>
                <p class="font-body-sm text-body-sm text-on-surface-variant mt-1 max-w-md mx-auto">
                    Kriteria pencarian atau filter kategori saat ini tidak menghasilkan data mapel.
                </p>
                <div class="flex items-center justify-center gap-3 mt-4">
                    <a href="{{ route('admin.subjects') }}" class="px-4 py-2 rounded-lg bg-surface-container text-on-surface font-label-md text-label-md hover:bg-surface-container-high transition-colors no-underline">
                        Reset Filter
                    </a>
                    <button type="button" onclick="openCreateSubjectModal()" class="px-4 py-2 rounded-lg bg-primary-container text-on-primary font-label-md text-label-md hover:bg-primary transition-colors cursor-pointer">
                        + Tambah Mapel Baru
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Detailed Kurikulum & Guru Overview Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-space-md">
        <!-- Left 2 Cols: Distribution Chart & Verification -->
        <div class="lg:col-span-2 p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex flex-col justify-between border border-slate-100/90">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                    <div>
                        <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Kesiapan Bank Soal Berdasarkan Kategori</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Rasio pemenuhan target butir soal asesmen formatif & sumatif semester berjalan.</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-semibold shrink-0">
                        Target: 150 Butir/Mapel
                    </span>
                </div>

                <!-- Compact SVG/HTML Bar Chart -->
                <div class="w-full h-40 flex items-end gap-4 md:gap-6 pt-6 pb-2 px-2 border-b border-slate-100">
                    @foreach($categoryReadiness as $catKey => $readiness)
                        <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end">
                            <div class="text-[11px] font-bold text-on-surface-variant">{{ $readiness['percentage'] }}%</div>
                            <div class="w-full max-w-[48px] {{ $readiness['class'] }} rounded-t-lg transition-all hover:opacity-90 shadow-xs" style="height: {{ max(10, $readiness['percentage']) }}%;"></div>
                            <span class="font-label-sm text-label-sm text-on-surface-variant text-center truncate max-w-full font-medium" title="{{ $catKey }}">
                                {{ $catKey }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between pt-3 mt-3 bg-surface-container-low/50 rounded-lg px-3 py-2 gap-2">
                <span class="font-body-sm text-body-sm text-on-surface-variant">
                    Sinkronisasi terakhir dengan Kurikulum Merdeka Kemdikbud: <strong>{{ $lastSync }}</strong>
                </span>
                <button onclick="openSyncModal()" class="text-primary font-label-md text-label-md hover:underline flex items-center gap-1 cursor-pointer font-semibold" type="button">
                    <span>Sinkronkan Sekarang</span>
                    <span class="material-symbols-outlined text-[16px]">sync</span>
                </button>
            </div>
        </div>

        <!-- Right 1 Col: Quick Notice & Activity Card -->
        <div class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex flex-col justify-between border border-slate-100/90">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="material-symbols-outlined text-secondary text-[22px]" style="font-variation-settings: 'FILL' 1;">policy</span>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Kebijakan KKM Standar Sekolah</h3>
                </div>
                <p class="font-body-sm text-body-sm text-on-surface-variant mb-4 leading-relaxed">
                    Berdasarkan panduan asesmen Kurikulum Merdeka {{ $schoolName ?? \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}, nilai Kriteria Ketercapaian Tujuan Pembelajaran (KKTP) diseragamkan pada batas kelulusan minimal <strong class="text-secondary font-bold">{{ number_format($schoolKkm, 1) }}</strong> untuk seluruh mata pelajaran intra-kurikuler.
                </p>
                <div class="space-y-2.5">
                    <div class="p-2.5 rounded-lg bg-surface-container-low flex items-center justify-between font-label-sm text-label-sm border border-slate-100/60">
                        <span class="text-on-surface-variant">Status Akreditasi Mapel</span>
                        <span class="text-secondary font-bold">Tersertifikasi 'A'</span>
                    </div>
                    <div class="p-2.5 rounded-lg bg-surface-container-low flex items-center justify-between font-label-sm text-label-sm border border-slate-100/60">
                        <span class="text-on-surface-variant">Standar Butir Valid</span>
                        <span class="text-primary font-bold">Sesuai Taksonomi Bloom</span>
                    </div>
                </div>
            </div>
            <div class="pt-4 mt-2 border-t border-slate-100">
                <button onclick="openKkmModal()" class="w-full py-2.5 px-3 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md text-label-md transition-colors flex items-center justify-center gap-2 font-semibold cursor-pointer" type="button">
                    <span class="material-symbols-outlined text-[18px]">tune</span>
                    <span>Atur KKM & Bobot Nilai</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation & Aggregated Counter Footer -->
    <div class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4 border border-slate-100/80">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center font-bold text-label-md">
                <span class="material-symbols-outlined text-[18px]">done_all</span>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center sm:gap-2">
                <span class="font-label-lg text-label-lg text-on-surface font-semibold">Total: {{ $subjects->total() }} Mata Pelajaran Terdata</span>
                <span class="hidden sm:inline text-outline">•</span>
                <span class="font-body-sm text-body-sm text-on-surface-variant">{{ number_format($totalQuestions) }} Total Bank Soal Terhimpun</span>
            </div>
        </div>

        <!-- Custom Pagination Matching Prototype -->
        <div class="flex items-center gap-1 font-label-md text-label-md">
            @if ($subjects->onFirstPage())
                <button class="w-8 h-8 rounded-lg bg-surface-container-low text-on-surface-variant flex items-center justify-center opacity-40 cursor-not-allowed" disabled type="button">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                </button>
            @else
                <a href="{{ $subjects->previousPageUrl() }}" class="w-8 h-8 rounded-lg bg-surface-container-low hover:bg-surface-container text-on-surface-variant flex items-center justify-center transition-colors no-underline">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                </a>
            @endif

            @foreach ($subjects->getUrlRange(max(1, $subjects->currentPage() - 1), min($subjects->lastPage(), $subjects->currentPage() + 2)) as $page => $url)
                @if ($page == $subjects->currentPage())
                    <span class="w-8 h-8 rounded-lg bg-primary-container text-on-primary flex items-center justify-center shadow-sm font-bold">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" class="w-8 h-8 rounded-lg bg-surface-container-low hover:bg-surface-container text-on-surface-variant flex items-center justify-center transition-colors no-underline">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            @if ($subjects->hasMorePages())
                <a href="{{ $subjects->nextPageUrl() }}" class="w-8 h-8 rounded-lg bg-surface-container-low hover:bg-surface-container text-on-surface-variant flex items-center justify-center transition-colors no-underline">
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </a>
            @else
                <button class="w-8 h-8 rounded-lg bg-surface-container-low text-on-surface-variant flex items-center justify-center opacity-40 cursor-not-allowed" disabled type="button">
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </button>
            @endif
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 1: Tambah Mata Pelajaran Baru                           -->
<!-- ============================================================== -->
<div id="createSubjectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-surface-container-lowest rounded-2xl shadow-xl w-full max-w-xl max-h-[90vh] flex flex-col overflow-hidden border border-slate-100">
        <!-- Header -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface-container-low/40">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                </div>
                <div>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Tambah Mata Pelajaran Baru</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Daftarkan mata pelajaran baru ke dalam master kurikulum sekolah.</p>
                </div>
            </div>
            <button type="button" onclick="closeCreateSubjectModal()" class="p-1 rounded-lg text-outline hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Form Body -->
        <form action="{{ route('admin.subjects.store') }}" method="POST" class="flex-1 overflow-y-auto p-6 space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Kode Mapel <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="code" required maxlength="20" placeholder="MAPEL-MTK-01" class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 font-mono font-semibold uppercase text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                    <span class="text-[11px] text-outline mt-1 block">Kode unik singkatan mapel (maks. 20 karakter).</span>
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Kategori Kurikulum <span class="text-rose-500">*</span>
                    </label>
                    <select name="category" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                        <option value="Wajib Umum">Wajib Umum</option>
                        <option value="MIPA">MIPA (Matematika & Sains)</option>
                        <option value="IPS">IPS (Ilmu Pengetahuan Sosial)</option>
                        <option value="Bahasa & Seni">Bahasa & Seni</option>
                        <option value="Muatan Lokal">Muatan Lokal</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                    Nama Mata Pelajaran <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" required maxlength="100" placeholder="Contoh: Matematika Peminatan" class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Standar KKM (Poin) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" step="0.5" min="0" max="100" name="passing_grade" value="{{ $schoolKkm }}" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Sasaran Tingkat Kelas
                    </label>
                    <input type="text" name="target_grades" value="Kelas X, XI, XII" placeholder="Kelas X, XI, XII" class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Ikon Simbol Material
                    </label>
                    <select name="icon" class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                        <option value="menu_book">Buku (menu_book)</option>
                        <option value="calculate">Matematika / Hitung (calculate)</option>
                        <option value="history_edu">Bahasa Indonesia / Sastra (history_edu)</option>
                        <option value="translate">Bahasa Asing / Inggris (translate)</option>
                        <option value="science">Fisika / Eksak (science)</option>
                        <option value="biotech">Kimia / Lab (biotech)</option>
                        <option value="psychology">Biologi / Sains (psychology)</option>
                        <option value="account_balance">Sejarah / Kebangsaan (account_balance)</option>
                        <option value="terminal">Informatika / Coding (terminal)</option>
                        <option value="groups">Sosiologi / Masyarakat (groups)</option>
                        <option value="trending_up">Ekonomi / Akuntansi (trending_up)</option>
                        <option value="public">Geografi / Bumi (public)</option>
                        <option value="policy">Pancasila / PPKn (policy)</option>
                        <option value="palette">Seni Budaya (palette)</option>
                        <option value="sports_basketball">Olahraga / PJOK (sports_basketball)</option>
                        <option value="language">Bahasa Dunia (language)</option>
                        <option value="psychology_alt">Prakarya / PKWU (psychology_alt)</option>
                        <option value="local_library">Muatan Lokal (local_library)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Status Mapel
                    </label>
                    <select name="is_active" class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                        <option value="1">Aktif Diajarkan</option>
                        <option value="0">Non-Aktif / Diarsipkan</option>
                    </select>
                </div>
            </div>

            <!-- Guru Pengampu Assignment -->
            <div>
                <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                    Guru Pengampu (Bisa Lebih Dari 1)
                </label>
                <div class="max-h-36 overflow-y-auto p-2 bg-surface-container-low/60 rounded-lg border border-slate-200/80 space-y-1.5">
                    @forelse($allTeachers as $teacher)
                        <label class="flex items-center gap-2 p-1.5 hover:bg-surface-container rounded cursor-pointer text-body-sm">
                            <input type="checkbox" name="teacher_ids[]" value="{{ $teacher->id }}" class="rounded text-primary focus:ring-primary">
                            <span class="font-medium text-on-surface">{{ $teacher->name }}</span>
                            <span class="text-outline text-xs">({{ $teacher->email }})</span>
                        </label>
                    @empty
                        <span class="text-outline text-xs italic p-1 block">Belum ada data guru terdaftar.</span>
                    @endforelse
                </div>
            </div>

            <div>
                <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                    Deskripsi / Capaian Pembelajaran (Opsional)
                </label>
                <textarea name="description" rows="2" placeholder="Catatan kurikulum, ruang lingkup materi, atau instruksi asesmen..." class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container"></textarea>
            </div>

            <!-- Footer Action -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeCreateSubjectModal()" class="px-4 py-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md text-label-md transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-primary-container text-on-primary hover:bg-primary font-label-md text-label-md transition-colors shadow-sm font-semibold cursor-pointer">
                    Simpan Mata Pelajaran
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 2: Edit Mata Pelajaran                                  -->
<!-- ============================================================== -->
<div id="editSubjectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-surface-container-lowest rounded-2xl shadow-xl w-full max-w-xl max-h-[90vh] flex flex-col overflow-hidden border border-slate-100">
        <!-- Header -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface-container-low/40">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                </div>
                <div>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Edit Mata Pelajaran</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Perbarui informasi, KKM, atau pengampu mapel.</p>
                </div>
            </div>
            <button type="button" onclick="closeEditSubjectModal()" class="p-1 rounded-lg text-outline hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Form Body -->
        <form id="editSubjectForm" action="" method="POST" class="flex-1 overflow-y-auto p-6 space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Kode Mapel <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="edit_code" name="code" required maxlength="20" class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 font-mono font-semibold uppercase text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Kategori Kurikulum <span class="text-rose-500">*</span>
                    </label>
                    <select id="edit_category" name="category" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                        <option value="Wajib Umum">Wajib Umum</option>
                        <option value="MIPA">MIPA (Matematika & Sains)</option>
                        <option value="IPS">IPS (Ilmu Pengetahuan Sosial)</option>
                        <option value="Bahasa & Seni">Bahasa & Seni</option>
                        <option value="Muatan Lokal">Muatan Lokal</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                    Nama Mata Pelajaran <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="edit_name" name="name" required maxlength="100" class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Standar KKM (Poin) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" step="0.5" min="0" max="100" id="edit_passing_grade" name="passing_grade" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Sasaran Tingkat Kelas
                    </label>
                    <input type="text" id="edit_target_grades" name="target_grades" class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Ikon Simbol Material
                    </label>
                    <select id="edit_icon" name="icon" class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                        <option value="menu_book">Buku (menu_book)</option>
                        <option value="calculate">Matematika / Hitung (calculate)</option>
                        <option value="history_edu">Bahasa Indonesia / Sastra (history_edu)</option>
                        <option value="translate">Bahasa Asing / Inggris (translate)</option>
                        <option value="science">Fisika / Eksak (science)</option>
                        <option value="biotech">Kimia / Lab (biotech)</option>
                        <option value="psychology">Biologi / Sains (psychology)</option>
                        <option value="account_balance">Sejarah / Kebangsaan (account_balance)</option>
                        <option value="terminal">Informatika / Coding (terminal)</option>
                        <option value="groups">Sosiologi / Masyarakat (groups)</option>
                        <option value="trending_up">Ekonomi / Akuntansi (trending_up)</option>
                        <option value="public">Geografi / Bumi (public)</option>
                        <option value="policy">Pancasila / PPKn (policy)</option>
                        <option value="palette">Seni Budaya (palette)</option>
                        <option value="sports_basketball">Olahraga / PJOK (sports_basketball)</option>
                        <option value="language">Bahasa Dunia (language)</option>
                        <option value="psychology_alt">Prakarya / PKWU (psychology_alt)</option>
                        <option value="local_library">Muatan Lokal (local_library)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                        Status Keaktifan
                    </label>
                    <select id="edit_is_active" name="is_active" class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container">
                        <option value="1">Aktif Diajarkan</option>
                        <option value="0">Non-Aktif / Diarsipkan</option>
                    </select>
                </div>
            </div>

            <!-- Guru Pengampu Assignment -->
            <div>
                <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                    Guru Pengampu Terpilih
                </label>
                <div class="max-h-36 overflow-y-auto p-2 bg-surface-container-low/60 rounded-lg border border-slate-200/80 space-y-1.5" id="editTeacherCheckboxes">
                    @foreach($allTeachers as $teacher)
                        <label class="flex items-center gap-2 p-1.5 hover:bg-surface-container rounded cursor-pointer text-body-sm">
                            <input type="checkbox" name="teacher_ids[]" value="{{ $teacher->id }}" class="edit-teacher-cb rounded text-primary focus:ring-primary">
                            <span class="font-medium text-on-surface">{{ $teacher->name }}</span>
                            <span class="text-outline text-xs">({{ $teacher->email }})</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                    Deskripsi / Capaian Pembelajaran
                </label>
                <textarea id="edit_description" name="description" rows="2" class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary-container"></textarea>
            </div>

            <!-- Footer Action -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeEditSubjectModal()" class="px-4 py-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md text-label-md transition-colors cursor-pointer">
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
<!-- MODAL 3: Atur KKM & Kebijakan Standar Sekolah                 -->
<!-- ============================================================== -->
<div id="kkmModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-surface-container-lowest rounded-2xl shadow-xl w-full max-w-md overflow-hidden border border-slate-100">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface-container-low/40">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-lg bg-secondary/15 text-secondary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">tune</span>
                </div>
                <div>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Atur Standar KKM Sekolah</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Kebijakan batas KKTP Kurikulum Merdeka.</p>
                </div>
            </div>
            <button type="button" onclick="closeKkmModal()" class="p-1 rounded-lg text-outline hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form action="{{ route('admin.subjects.kkm') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block font-label-md text-label-md text-on-surface mb-1.5 font-semibold">
                    Nilai KKM Minimal Sekolah (Poin) <span class="text-rose-500">*</span>
                </label>
                <input type="number" step="0.5" min="10" max="100" name="passing_grade" value="{{ $schoolKkm }}" required class="w-full bg-surface-container-low border border-slate-200/80 rounded-lg px-3 py-2 text-headline-sm font-bold text-secondary focus:outline-none focus:ring-2 focus:ring-secondary">
                <span class="text-[11px] text-outline mt-1 block">Standar kelulusan minimum peserta didik pada penilaian CBT.</span>
            </div>

            <div class="p-3 bg-secondary-container/20 rounded-xl border border-secondary/20">
                <label class="flex items-start gap-2.5 cursor-pointer">
                    <input type="checkbox" name="apply_to_all" value="1" checked class="mt-0.5 rounded text-secondary focus:ring-secondary">
                    <div class="text-body-sm text-on-surface">
                        <strong class="font-semibold block text-on-surface">Terapkan ke seluruh mata pelajaran aktif</strong>
                        <span class="text-on-surface-variant text-xs">Otomatis memperbarui nilai KKM standar di seluruh master mata pelajaran sekolah yang ada saat ini.</span>
                    </div>
                </label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeKkmModal()" class="px-4 py-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md text-label-md transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-secondary text-on-secondary hover:opacity-90 font-label-md text-label-md transition-colors shadow-sm font-semibold cursor-pointer">
                    Terapkan Kebijakan KKM
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 4: Bank Soal & Analitik Detail                          -->
<!-- ============================================================== -->
<div id="bankSoalModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-surface-container-lowest rounded-2xl shadow-xl w-full max-w-lg overflow-hidden border border-slate-100">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface-container-low/40">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">quiz</span>
                </div>
                <div>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold" id="bankSoalSubjectName">Detail Bank Soal</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant font-mono text-xs uppercase" id="bankSoalSubjectCode">MAPEL</p>
                </div>
            </div>
            <button type="button" onclick="closeBankSoalModal()" class="p-1 rounded-lg text-outline hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 rounded-xl bg-surface-container-low border border-slate-100">
                    <span class="text-outline text-xs block uppercase font-semibold">Total Bank Soal</span>
                    <span class="font-headline-md font-bold text-primary" id="bankSoalCount">0 Butir</span>
                </div>
                <div class="p-3 rounded-xl bg-surface-container-low border border-slate-100">
                    <span class="text-outline text-xs block uppercase font-semibold">Sesi Ujian Digunakan</span>
                    <span class="font-headline-md font-bold text-secondary" id="bankSoalExamsCount">0 Sesi</span>
                </div>
            </div>

            <div class="space-y-2 p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                <div class="flex justify-between text-body-sm">
                    <span class="text-outline">Kategori:</span>
                    <span class="font-semibold text-on-surface" id="bankSoalCategory">-</span>
                </div>
                <div class="flex justify-between text-body-sm">
                    <span class="text-outline">Batas KKM:</span>
                    <span class="font-semibold text-on-surface" id="bankSoalPassingGrade">75 Poin</span>
                </div>
                <div class="flex justify-between text-body-sm">
                    <span class="text-outline">Pengampu:</span>
                    <span class="font-semibold text-on-surface truncate max-w-[200px]" id="bankSoalTeachers">-</span>
                </div>
            </div>

            <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                Bank soal dikelola langsung oleh guru pengampu terdaftar melalui portal guru. Admin sistem dapat memantau butir soal, memeriksa validasi naskah ujian, atau mengatur jadwal pelaksanaan CBT.
            </p>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2.5">
                <a href="{{ route('admin.exams') }}" class="px-4 py-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md text-label-md transition-colors no-underline">
                    Lihat Semua Ujian
                </a>
                <button type="button" onclick="closeBankSoalModal()" class="px-5 py-2 rounded-lg bg-primary-container text-on-primary font-label-md text-label-md hover:bg-primary transition-colors font-semibold cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 5: Konfirmasi Sinkronisasi Kurikulum                    -->
<!-- ============================================================== -->
<div id="syncModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-surface-container-lowest rounded-2xl shadow-xl w-full max-w-md overflow-hidden border border-slate-100">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface-container-low/40">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">sync</span>
                </div>
                <div>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Sinkronisasi Kurikulum Merdeka</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Standarisasi Master Mapel Kemdikbud Ristek.</p>
                </div>
            </div>
            <button type="button" onclick="closeSyncModal()" class="p-1 rounded-lg text-outline hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form action="{{ route('admin.subjects.sync') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                Aksi ini akan menyelaraskan dan melengkapi 18 master mata pelajaran Kurikulum Merdeka (MIPA, Wajib Umum, IPS, Bahasa & Seni, Muatan Lokal) lengkap dengan kode standar, ikon simbol, tingkat kelas sasaran, dan standar KKM 75.0 poin.
            </p>
            <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 text-xs font-medium flex items-start gap-2">
                <span class="material-symbols-outlined text-[18px] text-amber-700 shrink-0">info</span>
                <span>Mata pelajaran dan bank soal yang sudah ada tidak akan terhapus. Kategori dan atribut kurikulum akan diselaraskan secara otomatis.</span>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeSyncModal()" class="px-4 py-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md text-label-md transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-primary-container text-on-primary hover:bg-primary font-label-md text-label-md transition-colors shadow-sm font-semibold flex items-center gap-1.5 cursor-pointer">
                    <span class="material-symbols-outlined text-[16px]">sync</span>
                    <span>Lanjutkan Sinkronisasi</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="deleteSubjectForm" action="" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    // Modal Helpers
    function openCreateSubjectModal() {
        document.getElementById('createSubjectModal').classList.remove('hidden');
    }
    function closeCreateSubjectModal() {
        document.getElementById('createSubjectModal').classList.add('hidden');
    }

    function openEditSubjectModal(subject) {
        const form = document.getElementById('editSubjectForm');
        form.action = "{{ url('admin/mapel') }}/" + subject.id;

        document.getElementById('edit_name').value = subject.name || '';
        document.getElementById('edit_code').value = subject.code || '';
        document.getElementById('edit_category').value = subject.category || 'Wajib Umum';
        document.getElementById('edit_passing_grade').value = subject.passing_grade || 75;
        document.getElementById('edit_target_grades').value = subject.target_grades || 'Kelas X, XI, XII';
        document.getElementById('edit_icon').value = subject.icon || 'menu_book';
        document.getElementById('edit_is_active').value = subject.is_active ? '1' : '0';
        document.getElementById('edit_description').value = subject.description || '';

        // Uncheck all teacher checkboxes, then check assigned ones
        const checkboxes = document.querySelectorAll('.edit-teacher-cb');
        checkboxes.forEach(cb => {
            cb.checked = (subject.teacher_ids && subject.teacher_ids.includes(parseInt(cb.value)));
        });

        document.getElementById('editSubjectModal').classList.remove('hidden');
    }
    function closeEditSubjectModal() {
        document.getElementById('editSubjectModal').classList.add('hidden');
    }

    function openKkmModal() {
        document.getElementById('kkmModal').classList.remove('hidden');
    }
    function closeKkmModal() {
        document.getElementById('kkmModal').classList.add('hidden');
    }

    function openBankSoalModal(data) {
        document.getElementById('bankSoalSubjectName').textContent = data.name;
        document.getElementById('bankSoalSubjectCode').textContent = data.code;
        document.getElementById('bankSoalCount').textContent = data.questions_count + ' Butir';
        document.getElementById('bankSoalExamsCount').textContent = data.exams_count + ' Sesi';
        document.getElementById('bankSoalCategory').textContent = data.category || 'Wajib Umum';
        document.getElementById('bankSoalPassingGrade').textContent = (data.passing_grade || 75) + ' Poin';
        document.getElementById('bankSoalTeachers').textContent = (data.teachers && data.teachers.length > 0) ? data.teachers.join(', ') : 'Belum ditentukan';

        document.getElementById('bankSoalModal').classList.remove('hidden');
    }
    function closeBankSoalModal() {
        document.getElementById('bankSoalModal').classList.add('hidden');
    }

    function openSyncModal() {
        document.getElementById('syncModal').classList.remove('hidden');
    }
    function closeSyncModal() {
        document.getElementById('syncModal').classList.add('hidden');
    }

    function confirmDeleteSubject(id, name, questionsCount, examsCount) {
        if (questionsCount > 0 || examsCount > 0) {
            alert("Mata pelajaran '" + name + "' tidak dapat dihapus karena masih memiliki " + questionsCount + " butir bank soal dan " + examsCount + " sesi ujian terkait.");
            return;
        }

        if (confirm("Apakah Anda yakin ingin menghapus mata pelajaran '" + name + "'? Tindakan ini tidak dapat dibatalkan.")) {
            const form = document.getElementById('deleteSubjectForm');
            form.action = "{{ url('admin/mapel') }}/" + id;
            form.submit();
        }
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateSubjectModal();
            closeEditSubjectModal();
            closeKkmModal();
            closeBankSoalModal();
            closeSyncModal();
        }
    });
</script>
@endsection
