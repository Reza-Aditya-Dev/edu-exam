@extends('layouts.admin')

@section('title', 'Manajemen Data Guru & Pendidik — EduExam')
@section('page_title', 'Manajemen Data Guru')

@section('admin-content')
<div class="flex flex-col w-full">
    <!-- Breadcrumbs & Context Badges -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-space-sm">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="font-label-sm text-label-sm text-outline hover:text-primary transition-colors uppercase tracking-wider">Master Data</a>
            <span class="material-symbols-outlined text-outline text-[16px]">chevron_right</span>
            <span class="font-label-md text-label-md text-primary font-semibold">Data Guru &amp; Pendidik</span>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container-high text-on-surface-variant font-label-sm text-label-sm">
                <span class="w-2 h-2 rounded-full bg-secondary"></span>
                Database MySQL: Terkoneksi
            </span>
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-label-sm font-semibold">
                <span class="material-symbols-outlined text-[15px]">verified_user</span>
                Hak Akses Bank Soal &amp; CBT
            </span>
        </div>
    </div>

    <!-- Hero Header Panel -->
    <div class="relative overflow-hidden rounded-xl bg-surface-container-lowest p-space-lg shadow-sm mb-space-lg border border-slate-100">
        <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-primary-fixed opacity-40 blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-space-md">
            <div class="max-w-3xl">
                <div class="flex items-center gap-2.5 mb-1.5">
                    <div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary flex items-center justify-center shadow-sm">
                        <span class="material-symbols-outlined text-[24px]">school</span>
                    </div>
                    <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">Manajemen Data Guru &amp; Pendidik</h1>
                </div>
                <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                    Kelola akun pengajar {{ \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}, NIP, penugasan mata pelajaran, serta hak pembuatan bank soal dan aktivasi ujian berbasis CBT untuk semester berjalan.
                </p>
            </div>
            <!-- Action Toolbar -->
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-lg bg-surface-container-low hover:bg-surface-container text-on-surface font-label-lg text-label-lg transition-all shadow-sm active:scale-95" type="button">
                    <span class="material-symbols-outlined text-[19px] text-secondary">print</span>
                    <span>Cetak Data Guru</span>
                </button>
                <a href="{{ route('admin.teachers.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-primary-container hover:bg-primary text-on-primary font-label-lg text-label-lg shadow-sm hover:shadow-md transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[20px]">person_add</span>
                    <span>+ Tambah Guru Baru</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Metric Cards (4-Grid) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-md mb-space-lg">
        <!-- Card 1: Total Guru -->
        <div class="relative overflow-hidden rounded-xl bg-surface-container-lowest p-space-md shadow-sm flex items-center justify-between border border-slate-100">
            <div class="flex flex-col">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Total Guru Terdaftar</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="font-headline-lg text-headline-lg text-on-surface font-bold">{{ number_format($totalTeachers) }}</span>
                    <span class="font-label-sm text-label-sm text-secondary font-medium">100% Rasio</span>
                </div>
                <span class="font-body-sm text-body-sm text-on-surface-variant mt-1">
                    {{ $teachersWithNip }} PNS / Memiliki NIP
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-primary-fixed flex items-center justify-center text-primary shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-[26px]">groups</span>
            </div>
        </div>

        <!-- Card 2: Guru Aktif -->
        <div class="relative overflow-hidden rounded-xl bg-surface-container-lowest p-space-md shadow-sm flex items-center justify-between border border-slate-100">
            <div class="flex flex-col">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Guru Aktif Mengajar</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="font-headline-lg text-headline-lg text-secondary font-bold">{{ number_format($activeTeachers) }}</span>
                    <span class="font-label-sm text-label-sm bg-secondary text-on-secondary px-2 py-0.5 rounded-full font-semibold">{{ $activeRatio }}%</span>
                </div>
                <span class="font-body-sm text-body-sm text-on-surface-variant mt-1">Siap menyusun bank soal &amp; CBT</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-secondary-container flex items-center justify-center text-on-secondary-container shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-[26px]">check_circle</span>
            </div>
        </div>

        <!-- Card 3: Guru Nonaktif -->
        <div class="relative overflow-hidden rounded-xl bg-surface-container-lowest p-space-md shadow-sm flex items-center justify-between border border-slate-100">
            <div class="flex flex-col">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Guru Nonaktif / Terkendala</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="font-headline-lg text-headline-lg {{ $inactiveTeachers > 0 ? 'text-amber-600' : 'text-on-surface' }} font-bold">{{ number_format($inactiveTeachers) }}</span>
                    <span class="font-label-sm text-label-sm {{ $inactiveTeachers > 0 ? 'bg-amber-100 text-amber-800' : 'bg-surface-container text-on-surface-variant' }} px-2 py-0.5 rounded-full font-semibold">
                        {{ $inactiveTeachers > 0 ? 'Nonaktif' : 'Nihil' }}
                    </span>
                </div>
                <span class="font-body-sm text-body-sm text-on-surface-variant mt-1">Akun dibekukan atau nonaktif</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center text-on-surface-variant shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-[26px]">person_off</span>
            </div>
        </div>

        <!-- Card 4: Mata Pelajaran Tercover -->
        <div class="relative overflow-hidden rounded-xl bg-surface-container-lowest p-space-md shadow-sm flex items-center justify-between border border-slate-100">
            <div class="flex flex-col">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Mata Pelajaran Tercover</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="font-headline-lg text-headline-lg text-primary font-bold">{{ number_format($totalSubjects) }}</span>
                    <span class="font-label-sm text-label-sm bg-primary-fixed text-on-primary-fixed px-2 py-0.5 rounded-full font-semibold">{{ $totalQuestions }} Soal</span>
                </div>
                <span class="font-body-sm text-body-sm text-on-surface-variant mt-1">Kurikulum &amp; Bank Soal CBT</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-primary-fixed flex items-center justify-center text-primary shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-[26px]">auto_stories</span>
            </div>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="rounded-xl bg-surface-container-lowest p-space-md shadow-sm mb-space-md border border-slate-100">
        <form action="{{ route('admin.teachers') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <!-- Search Input -->
            <div class="md:col-span-6 lg:col-span-5 flex items-center bg-surface-container-low rounded-lg px-3 py-2 focus-within:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-outline text-[20px] mr-2">search</span>
                <input name="search" value="{{ request('search') }}" class="w-full bg-transparent border-none outline-none font-body-sm text-body-sm text-on-surface placeholder:text-outline" placeholder="Cari nama guru, NIP, email, atau username..." type="text"/>
            </div>

            <!-- Mapel Dropdown Filter -->
            <div class="md:col-span-3 lg:col-span-3">
                <div class="relative">
                    <select name="subject_id" onchange="this.form.submit()" class="w-full appearance-none bg-surface-container-low hover:bg-surface-container text-on-surface font-body-sm text-body-sm rounded-lg px-3.5 py-2.5 pr-9 outline-none cursor-pointer transition-colors border border-transparent focus:border-primary">
                        <option value="">Semua Mapel ({{ $totalSubjects }} Mapel)</option>
                        @foreach($allSubjects as $sub)
                            <option value="{{ $sub->id }}" {{ request('subject_id') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-outline text-[20px]">expand_more</span>
                </div>
            </div>

            <!-- Status Dropdown Filter -->
            <div class="md:col-span-3 lg:col-span-2">
                <div class="relative">
                    <select name="status" onchange="this.form.submit()" class="w-full appearance-none bg-surface-container-low hover:bg-surface-container text-on-surface font-body-sm text-body-sm rounded-lg px-3.5 py-2.5 pr-9 outline-none cursor-pointer transition-colors border border-transparent focus:border-primary">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <span class="material-symbols-outlined pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-outline text-[20px]">filter_alt</span>
                </div>
            </div>

            <!-- Action Filter Triggers -->
            <div class="md:col-span-12 lg:col-span-2 flex items-center justify-end gap-2">
                <button type="submit" class="inline-flex items-center justify-center gap-1 px-3.5 py-2.5 rounded-lg bg-primary text-on-primary font-label-md text-label-md transition-colors shadow-xs">
                    <span>Terapkan</span>
                </button>
                @if(request('search') || request('subject_id') || request('status') !== null)
                    <a href="{{ route('admin.teachers') }}" class="inline-flex items-center justify-center gap-1 px-3 py-2.5 rounded-lg bg-surface-container-low hover:bg-surface-container text-on-surface-variant font-label-md text-label-md transition-colors" title="Reset filter">
                        <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table Card Container -->
    <div class="rounded-xl bg-surface-container-lowest shadow-sm overflow-hidden mb-space-md border border-slate-100">
        <!-- Top Table Ribbon: Fast batch info -->
        <div class="px-space-md py-3 bg-surface-container-low flex flex-wrap items-center justify-between gap-3 text-on-surface-variant border-b border-surface-container">
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input class="w-4 h-4 rounded text-primary accent-primary cursor-pointer" id="select-all-teachers" type="checkbox"/>
                    <span class="font-label-md text-label-md text-on-surface font-semibold">Pilih Semua ({{ $teachers->total() }})</span>
                </label>
                <span class="h-4 w-px bg-outline-variant"></span>
                <span class="font-body-sm text-body-sm text-on-surface-variant">
                    Menampilkan {{ $teachers->count() }} baris di halaman ini
                </span>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.teachers.create') }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-surface-container hover:bg-surface-container-high text-primary font-label-sm text-label-sm font-semibold transition-colors">
                    <span class="material-symbols-outlined text-[16px]">add</span>
                    <span>Tambah Guru</span>
                </a>
            </div>
        </div>

        <!-- Table Responsive Viewport -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-lowest text-on-surface-variant font-label-sm text-label-sm uppercase tracking-wider select-none border-b border-surface-container-low">
                        <th class="py-3.5 pl-space-md pr-2 w-10">
                            <span class="sr-only">Pilih</span>
                        </th>
                        <th class="py-3.5 px-3 w-12 text-center">No</th>
                        <th class="py-3.5 px-3 min-w-[240px]">Nama Pendidik &amp; Akun</th>
                        <th class="py-3.5 px-3 min-w-[180px]">NIP / NUPTK</th>
                        <th class="py-3.5 px-3 min-w-[210px]">Email Resmi</th>
                        <th class="py-3.5 px-3 min-w-[200px]">Mata Pelajaran Diampu</th>
                        <th class="py-3.5 px-3 min-w-[180px]">Penugasan Kelas</th>
                        <th class="py-3.5 px-3 min-w-[120px] text-center">Status Akun</th>
                        <th class="py-3.5 pr-space-md pl-3 text-right min-w-[170px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-low font-body-sm text-body-sm text-on-surface">
                    @forelse($teachers as $index => $teacher)
                        @php
                            $rowNum = $teachers->firstItem() ? ($teachers->firstItem() + $index) : ($index + 1);
                            $initials = collect(explode(' ', $teacher->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('');
                        @endphp
                        <tr class="hover:bg-surface-container-low/70 transition-colors group">
                            <td class="py-3.5 pl-space-md pr-2">
                                <input class="teacher-checkbox w-4 h-4 rounded text-primary accent-primary cursor-pointer" type="checkbox"/>
                            </td>
                            <td class="py-3.5 px-3 text-center font-label-md text-label-md text-on-surface-variant">
                                {{ str_pad($rowNum, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="py-3.5 px-3">
                                <div class="flex items-center gap-3">
                                    @if($teacher->avatar)
                                        <img alt="{{ $teacher->name }}" class="w-10 h-10 rounded-full object-cover shadow-sm ring-2 ring-primary-fixed" src="{{ $teacher->avatar_url }}"/>
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center justify-center font-headline-sm text-headline-sm font-bold shadow-sm">
                                            {{ $initials ?: 'GR' }}
                                        </div>
                                    @endif
                                    <div class="flex flex-col min-w-0">
                                        <span class="font-label-lg text-label-lg text-on-surface font-semibold truncate group-hover:text-primary transition-colors">
                                            {{ $teacher->name }}
                                        </span>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="text-outline text-[11px] font-mono">&#64;{{ $teacher->username }}</span>
                                            <span class="text-outline text-[11px]">•</span>
                                            <span class="text-outline text-[11px]">{{ $teacher->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-3">
                                <div class="flex flex-col">
                                    <span class="font-mono text-[13px] text-on-surface font-semibold tracking-tight">
                                        {{ $teacher->nip ?: '-' }}
                                    </span>
                                    <span class="font-label-sm text-label-sm text-outline">
                                        {{ $teacher->nip ? 'NIP Resmi' : 'Belum diisi' }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-3.5 px-3">
                                <div class="flex items-center gap-1.5 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[16px] text-outline">mail</span>
                                    <span class="truncate font-mono text-[12px]">{{ $teacher->email }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-3">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($teacher->subjects as $subject)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-label-sm font-semibold">
                                            {{ $subject->name }}
                                        </span>
                                    @empty
                                        <span class="text-outline text-xs italic">Belum ditentukan</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="py-3.5 px-3">
                                <div class="flex flex-wrap items-center gap-1">
                                    @if($teacher->homeroomClassrooms && $teacher->homeroomClassrooms->count() > 0)
                                        @foreach($teacher->homeroomClassrooms as $hrClass)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">
                                                {{ $hrClass->name }} (Wali)
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm">
                                            {{ $teacher->created_exams_count }} Ujian Dibuat
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                @if($teacher->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-100 text-rose-700 font-label-sm text-label-sm font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 pr-space-md pl-3 text-right">
                                <div class="inline-flex items-center justify-end gap-1">
                                    <!-- Detail Modal Trigger -->
                                    <button onclick="showTeacherDetail({{ json_encode([
                                        'name' => $teacher->name,
                                        'nip' => $teacher->nip ?: '-',
                                        'email' => $teacher->email,
                                        'username' => $teacher->username,
                                        'gender' => $teacher->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                                        'status' => $teacher->is_active ? 'Aktif' : 'Nonaktif',
                                        'subjects' => $teacher->subjects->pluck('name')->toArray(),
                                        'questions_count' => $teacher->questions_count,
                                        'exams_count' => $teacher->created_exams_count,
                                        'avatar' => $teacher->avatar_url,
                                    ]) }})" class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface-variant hover:bg-surface-container-high hover:text-primary transition-colors" title="Lihat Detail Profil" type="button">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </button>

                                    <!-- Edit Link -->
                                    <a href="{{ route('admin.teachers.edit', $teacher) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface-variant hover:bg-surface-container-high hover:text-primary transition-colors" title="Ubah Data Guru">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>

                                    <!-- Toggle Status Form -->
                                    <form action="{{ route('admin.teachers.toggle', $teacher) }}" method="POST" class="inline m-0" onsubmit="return confirm('Apakah Anda yakin ingin {{ $teacher->is_active ? 'menonaktifkan' : 'mengaktifkan' }} akun guru {{ $teacher->name }}?')">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center {{ $teacher->is_active ? 'text-on-surface-variant hover:bg-error-container hover:text-on-error-container' : 'text-secondary hover:bg-secondary-container' }} transition-colors" title="{{ $teacher->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                            <span class="material-symbols-outlined text-[18px]">{{ $teacher->is_active ? 'person_off' : 'how_to_reg' }}</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-12 text-on-surface-variant">
                                <span class="material-symbols-outlined text-[36px] text-outline mb-2 block">person_off</span>
                                <span class="font-headline-sm text-sm font-bold text-on-surface block">Data Guru Tidak Ditemukan</span>
                                <span class="text-xs text-outline mt-0.5 block">Silakan sesuaikan kata kunci pencarian atau reset filter.</span>
                                <div class="mt-4">
                                    <a href="{{ route('admin.teachers.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-primary-container text-on-primary font-label-md text-label-md font-semibold shadow-sm">
                                        <span class="material-symbols-outlined text-[18px]">add</span>
                                        <span>Tambah Guru Pertama</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Pagination Footer -->
        <div class="px-space-md py-3.5 bg-surface-container-lowest border-t border-surface-container-low flex flex-col sm:flex-row items-center justify-between gap-3 select-none">
            <div class="flex items-center gap-2">
                <span class="font-body-sm text-body-sm text-on-surface-variant">
                    Menampilkan <span class="font-semibold text-on-surface">{{ $teachers->firstItem() ?? 0 }} - {{ $teachers->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-on-surface">{{ $teachers->total() }}</span> data guru
                </span>
            </div>
            <div>
                {{ $teachers->links() }}
            </div>
        </div>
    </div>

    <!-- Bottom Quick Assist & Information Bento (3-Grid) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-space-md">
        <!-- Bento 1: Otorisasi Bank Soal -->
        <div class="rounded-xl bg-surface-container-lowest p-space-md shadow-sm flex flex-col justify-between border border-slate-100">
            <div>
                <div class="flex items-center gap-2 text-primary mb-2">
                    <span class="material-symbols-outlined text-[22px]">quiz</span>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">Otorisasi Bank Soal CBT</h3>
                </div>
                <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                    Setiap guru yang berstatus <span class="font-semibold text-secondary">Aktif</span> otomatis memiliki otorisasi penuh untuk menyusun butir soal pilihan ganda, isian, dan esai serta mengaktifkan sesi ujian pada rombel masing-masing.
                </p>
            </div>
            <div class="mt-4 pt-3 flex items-center justify-between border-t border-surface-container-low">
                <a class="font-label-sm text-label-sm text-primary hover:underline inline-flex items-center gap-1 font-semibold" href="{{ route('admin.subjects') }}">
                    <span>Kelola Mata Pelajaran</span>
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
                <span class="text-outline text-label-sm font-label-sm">Versi CBT 3.4.2</span>
            </div>
        </div>

        <!-- Bento 2: Alokasi Rombel & Pengampu -->
        <div class="rounded-xl bg-surface-container-lowest p-space-md shadow-sm flex flex-col justify-between border border-slate-100">
            <div>
                <div class="flex items-center gap-2 text-secondary mb-2">
                    <span class="material-symbols-outlined text-[22px]">hub</span>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">Pemerataan Pengampu Mapel</h3>
                </div>
                <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                    Sebanyak <span class="font-semibold text-on-surface">{{ $totalSubjects }} Mata Pelajaran</span> telah terdaftar dalam sistem kurikulum sekolah untuk mendukung evaluasi berkala dan ujian akhir semester.
                </p>
            </div>
            <div class="mt-4 pt-3 flex items-center gap-3 border-t border-surface-container-low">
                <div class="flex-1 bg-surface-container-low rounded-full h-2.5 overflow-hidden">
                    <div class="bg-secondary h-2.5 rounded-full" style="width: {{ $activeRatio }}%"></div>
                </div>
                <span class="font-label-sm text-label-sm text-on-surface font-semibold shrink-0">{{ $activeRatio }}% Guru Aktif</span>
            </div>
        </div>

        <!-- Bento 3: Tata Kelola Akun & Keamanan -->
        <div class="rounded-xl bg-surface-container-lowest p-space-md shadow-sm flex flex-col justify-between border border-slate-100">
            <div>
                <div class="flex items-center gap-2 text-primary mb-2">
                    <span class="material-symbols-outlined text-[22px]">shield_person</span>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">Keamanan &amp; Akun CBT</h3>
                </div>
                <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                    Penggantian kata sandi akun pendidik dapat dilakukan secara mandiri oleh Admin melalui tombol <strong>Ubah Data</strong> pada masing-masing baris guru.
                </p>
            </div>
            <div class="mt-4 pt-3 flex items-center justify-between border-t border-surface-container-low">
                <a href="{{ route('admin.teachers.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container-low hover:bg-surface-container text-on-surface font-label-sm text-label-sm transition-colors font-semibold">
                    <span class="material-symbols-outlined text-[16px] text-primary">person_add</span>
                    <span>Tambah Akun Guru</span>
                </a>
                <span class="font-label-sm text-label-sm text-outline">Modul Admin</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Guru Interaktif -->
<div id="teacherDetailModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200">
        <div class="p-space-md border-b border-surface-container flex items-center justify-between bg-surface-container-low">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[22px]">school</span>
                <span class="font-headline-sm text-headline-sm text-on-surface font-bold">Profil Guru &amp; Pendidik</span>
            </div>
            <button type="button" onclick="closeTeacherDetail()" class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-surface-container-high transition-colors text-on-surface-variant">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <div class="p-space-lg flex flex-col gap-4 text-xs font-body-sm">
            <div class="flex items-center gap-4">
                <div id="detailAvatarContainer" class="w-14 h-14 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center justify-center text-xl font-bold shadow-sm shrink-0">
                    GR
                </div>
                <div class="flex flex-col min-w-0">
                    <h3 id="detailName" class="font-headline-sm text-base font-bold text-on-surface truncate">-</h3>
                    <span id="detailNip" class="font-mono text-outline text-xs mt-0.5 block">-</span>
                    <span id="detailStatus" class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold mt-1 w-max bg-secondary-container text-on-secondary-container">Aktif</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 bg-surface-container-low p-3 rounded-xl">
                <div>
                    <span class="text-outline text-[11px] block">Username CBT:</span>
                    <span id="detailUsername" class="font-mono font-semibold text-on-surface text-xs">-</span>
                </div>
                <div>
                    <span class="text-outline text-[11px] block">Jenis Kelamin:</span>
                    <span id="detailGender" class="font-semibold text-on-surface text-xs">-</span>
                </div>
                <div class="col-span-2">
                    <span class="text-outline text-[11px] block">Email Resmi:</span>
                    <span id="detailEmail" class="font-mono font-semibold text-on-surface text-xs">-</span>
                </div>
            </div>

            <div>
                <span class="font-semibold text-on-surface block mb-1.5">Mata Pelajaran yang Diampu:</span>
                <div id="detailSubjects" class="flex flex-wrap gap-1">
                    <span class="text-outline italic">Tidak ada</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-2 border-t border-surface-container">
                <div class="p-2.5 rounded-lg bg-surface-container-low flex flex-col items-center text-center">
                    <span class="text-outline text-[11px]">Soal Disusun</span>
                    <span id="detailQuestions" class="font-bold text-base text-primary">0</span>
                </div>
                <div class="p-2.5 rounded-lg bg-surface-container-low flex flex-col items-center text-center">
                    <span class="text-outline text-[11px]">Sesi Ujian</span>
                    <span id="detailExams" class="font-bold text-base text-secondary">0</span>
                </div>
            </div>
        </div>
        <div class="p-space-md border-t border-surface-container flex items-center justify-end bg-surface-container-lowest">
            <button type="button" onclick="closeTeacherDetail()" class="px-4 py-2 rounded-lg bg-surface-container-high text-on-surface font-semibold hover:bg-surface-container transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    // Micro-interaction Checkbox Select-All
    document.addEventListener('DOMContentLoaded', () => {
        const selectAll = document.getElementById('select-all-teachers');
        const rowCheckboxes = document.querySelectorAll('.teacher-checkbox');

        if (selectAll && rowCheckboxes.length > 0) {
            selectAll.addEventListener('change', (e) => {
                rowCheckboxes.forEach((cb) => {
                    cb.checked = e.target.checked;
                });
            });

            rowCheckboxes.forEach((cb) => {
                cb.addEventListener('change', () => {
                    const allChecked = Array.from(rowCheckboxes).every((item) => item.checked);
                    const someChecked = Array.from(rowCheckboxes).some((item) => item.checked);
                    selectAll.checked = allChecked;
                    selectAll.indeterminate = !allChecked && someChecked;
                });
            });
        }
    });

    // Detail Modal Functionality
    function showTeacherDetail(data) {
        document.getElementById('detailName').textContent = data.name;
        document.getElementById('detailNip').textContent = 'NIP: ' + data.nip;
        document.getElementById('detailUsername').textContent = '@' + data.username;
        document.getElementById('detailEmail').textContent = data.email;
        document.getElementById('detailGender').textContent = data.gender;
        document.getElementById('detailQuestions').textContent = data.questions_count;
        document.getElementById('detailExams').textContent = data.exams_count;

        const statusBadge = document.getElementById('detailStatus');
        if (data.status === 'Aktif') {
            statusBadge.className = 'inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold mt-1 w-max bg-secondary-container text-on-secondary-container';
            statusBadge.textContent = 'Aktif';
        } else {
            statusBadge.className = 'inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold mt-1 w-max bg-rose-100 text-rose-700';
            statusBadge.textContent = 'Nonaktif';
        }

        const subjectsContainer = document.getElementById('detailSubjects');
        subjectsContainer.innerHTML = '';
        if (data.subjects && data.subjects.length > 0) {
            data.subjects.forEach(sub => {
                const badge = document.createElement('span');
                badge.className = 'inline-flex items-center px-2 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-label-sm font-semibold';
                badge.textContent = sub;
                subjectsContainer.appendChild(badge);
            });
        } else {
            subjectsContainer.innerHTML = '<span class="text-outline italic">Belum diatur</span>';
        }

        const avatarContainer = document.getElementById('detailAvatarContainer');
        if (data.avatar) {
            avatarContainer.innerHTML = `<img src="${data.avatar}" class="w-14 h-14 rounded-full object-cover shadow-sm ring-2 ring-primary-fixed" alt="${data.name}">`;
        } else {
            const initials = data.name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();
            avatarContainer.textContent = initials || 'GR';
        }

        document.getElementById('teacherDetailModal').classList.remove('hidden');
    }

    function closeTeacherDetail() {
        document.getElementById('teacherDetailModal').classList.add('hidden');
    }
</script>
@endsection
