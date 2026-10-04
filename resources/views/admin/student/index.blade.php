@extends('layouts.admin')

@section('title', 'Manajemen Data Siswa — EduExam')
@section('page_title', 'Manajemen Data Siswa')

@section('admin-content')
<div class="flex flex-col w-full gap-space-lg pb-16">
    <!-- Breadcrumb and Page Header -->
    <div class="flex flex-col gap-3">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-on-surface-variant font-label-sm text-label-sm">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors cursor-pointer flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">home</span>
                <span>Master Data</span>
            </a>
            <span class="material-symbols-outlined text-[14px] text-outline">chevron_right</span>
            <span class="text-primary font-semibold">Data Siswa</span>
        </div>

        <!-- Header Actions Stack -->
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-space-md">
            <div class="flex flex-col">
                <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">Manajemen Data Siswa</h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-0.5 max-w-3xl">
                    Kelola data identitas peserta didik, penempatan rombel kelas, akun login CBT, dan status keaktifan.
                </p>
            </div>
            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-2.5">
                <button type="button" onclick="openImportModal()" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-lg bg-surface-container-lowest text-on-surface font-label-lg text-label-lg shadow-sm hover:bg-surface-container-high transition-all active:scale-[0.98] border border-slate-200/60 cursor-pointer">
                    <span class="material-symbols-outlined text-[19px] text-secondary">file_upload</span>
                    <span>Impor Data (.csv/.xlsx)</span>
                </button>
                <a href="{{ route('admin.students.export', request()->query()) }}" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-lg bg-surface-container-lowest text-on-surface font-label-lg text-label-lg shadow-sm hover:bg-surface-container-high transition-all active:scale-[0.98] border border-slate-200/60 no-underline">
                    <span class="material-symbols-outlined text-[19px] text-outline">file_download</span>
                    <span>Ekspor Data</span>
                </a>
                <a href="{{ route('admin.students.print-cards', request()->query()) }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-lg bg-surface-container-lowest text-on-surface font-label-lg text-label-lg shadow-sm hover:bg-surface-container-high transition-all active:scale-[0.98] border border-slate-200/60 no-underline">
                    <span class="material-symbols-outlined text-[19px] text-primary">print</span>
                    <span>Cetak Kartu CBT</span>
                </a>
                <a href="{{ route('admin.students.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg shadow-md hover:bg-primary transition-all active:scale-[0.98] no-underline">
                    <span class="material-symbols-outlined text-[20px]">person_add</span>
                    <span>+ Tambah Siswa</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Metric Badges Row (Overview of Cohort Status) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-md">
        <!-- Metric 1: Total Siswa -->
        <div class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex items-center justify-between border border-slate-100">
            <div class="flex flex-col">
                <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider font-semibold">Total Siswa Terdaftar</span>
                <span class="font-headline-md text-headline-md text-on-surface font-bold mt-1">{{ number_format($stats['total_students']) }}</span>
                <span class="font-label-sm text-label-sm text-secondary flex items-center gap-1 mt-0.5 font-medium">
                    <span class="material-symbols-outlined text-[14px]">check_circle</span> {{ $stats['active_classrooms'] }} Rombel Aktif
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-surface-container-low flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-[26px]">groups</span>
            </div>
        </div>

        <!-- Metric 2: Akun CBT Siap Ujian -->
        <div class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex items-center justify-between border border-slate-100">
            <div class="flex flex-col">
                <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider font-semibold">Akun CBT Siap Ujian</span>
                <span class="font-headline-md text-headline-md text-secondary font-bold mt-1">{{ number_format($stats['active_students']) }}</span>
                <span class="font-label-sm text-label-sm text-on-surface-variant flex items-center gap-1 mt-0.5 font-medium">
                    <span class="material-symbols-outlined text-[14px]">devices</span> {{ $stats['active_pct'] }}% Terverifikasi
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-secondary-container/40 flex items-center justify-center text-secondary">
                <span class="material-symbols-outlined text-[26px]">devices</span>
            </div>
        </div>

        <!-- Metric 3: Perlu Remedial -->
        <div class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex items-center justify-between border border-slate-100">
            <div class="flex flex-col">
                <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider font-semibold">Perlu Tindakan / Remedial</span>
                <span class="font-headline-md text-headline-md text-tertiary-container font-bold mt-1">{{ number_format($stats['remedial_count']) }}</span>
                <span class="font-label-sm text-label-sm text-tertiary-container flex items-center gap-1 mt-0.5 font-medium">
                    <span class="material-symbols-outlined text-[14px]">warning</span> Nilai KKM belum tuntas
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-tertiary-fixed/40 flex items-center justify-center text-tertiary-container">
                <span class="material-symbols-outlined text-[26px]">assignment_late</span>
            </div>
        </div>

        <!-- Metric 4: Akun Ditangguhkan -->
        <div class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm flex items-center justify-between border border-slate-100">
            <div class="flex flex-col">
                <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider font-semibold">Akun Ditangguhkan</span>
                <span class="font-headline-md text-headline-md text-error font-bold mt-1">{{ number_format($stats['suspended_count']) }}</span>
                <span class="font-label-sm text-label-sm text-error flex items-center gap-1 mt-0.5 font-medium">
                    <span class="material-symbols-outlined text-[14px]">lock</span> Pelanggaran integritas CBT
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-error-container/60 flex items-center justify-center text-error">
                <span class="material-symbols-outlined text-[26px]">no_accounts</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar (Single Unified Form) -->
    <div class="p-space-md rounded-xl bg-surface-container-lowest shadow-sm border border-slate-100">
        <form action="{{ route('admin.students') }}" method="GET" class="flex flex-col xl:flex-row items-stretch xl:items-center justify-between gap-space-sm">
            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">

            <!-- Left: Search Box -->
            <div class="flex-1 max-w-xl">
                <div class="relative w-full flex items-center bg-surface-container-low rounded-lg px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-primary-container transition-all border border-slate-200/50">
                    <span class="material-symbols-outlined text-outline text-[20px] mr-2.5 select-none">search</span>
                    <input class="w-full bg-transparent border-none outline-none font-body-sm text-body-sm text-on-surface placeholder:text-outline" name="search" placeholder="Cari berdasarkan nama siswa, NISN, email, atau username..." type="text" value="{{ request('search') }}" />
                    @if(request('search'))
                        <a href="{{ route('admin.students', request()->except('search')) }}" class="text-outline hover:text-on-surface p-0.5 rounded transition-colors" title="Bersihkan kata kunci">
                            <span class="material-symbols-outlined text-[18px]">close</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Right: Filter Dropdowns Stack -->
            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Dropdown Kelas -->
                <div class="relative flex items-center bg-surface-container-low rounded-lg px-3 py-2 border border-slate-200/50">
                    <span class="material-symbols-outlined text-outline text-[18px] mr-2">meeting_room</span>
                    <select name="classroom_id" onchange="this.form.submit()" class="bg-transparent border-none outline-none font-label-md text-label-md text-on-surface pr-6 appearance-none cursor-pointer">
                        <option value="">Semua Kelas ({{ $classrooms->count() }} Rombel)</option>
                        @foreach($classrooms as $c)
                            <option value="{{ $c->id }}" {{ request('classroom_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }} ({{ $c->students_count }} Siswa)
                            </option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined text-outline text-[16px] absolute right-2.5 pointer-events-none">expand_more</span>
                </div>

                <!-- Dropdown Status -->
                <div class="relative flex items-center bg-surface-container-low rounded-lg px-3 py-2 border border-slate-200/50">
                    <span class="material-symbols-outlined text-outline text-[18px] mr-2">verified_user</span>
                    <select name="status" onchange="this.form.submit()" class="bg-transparent border-none outline-none font-label-md text-label-md text-on-surface pr-6 appearance-none cursor-pointer">
                        <option value="">Semua Status Akun</option>
                        <option value="active" {{ request('status') === 'active' || request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="remedial" {{ request('status') === 'remedial' ? 'selected' : '' }}>Perlu Remedial</option>
                        <option value="suspended" {{ request('status') === 'suspended' || request('status') === '0' ? 'selected' : '' }}>Ditangguhkan (Nonaktif)</option>
                    </select>
                    <span class="material-symbols-outlined text-outline text-[16px] absolute right-2.5 pointer-events-none">expand_more</span>
                </div>

                <!-- Dropdown Tahun Ajaran -->
                <div class="relative flex items-center bg-surface-container-low rounded-lg px-3 py-2 border border-slate-200/50">
                    <span class="material-symbols-outlined text-outline text-[18px] mr-2">calendar_month</span>
                    <select name="academic_year_id" onchange="this.form.submit()" class="bg-transparent border-none outline-none font-label-md text-label-md text-on-surface pr-6 appearance-none cursor-pointer">
                        <option value="">Semua Tahun Ajaran</option>
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" {{ request('academic_year_id') == $ay->id ? 'selected' : '' }}>
                                {{ $ay->name }} {{ $ay->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined text-outline text-[16px] absolute right-2.5 pointer-events-none">expand_more</span>
                </div>

                <!-- Submit / Terapkan -->
                <button type="submit" class="px-3.5 py-2 rounded-lg bg-primary-container text-on-primary font-label-md text-label-md font-semibold hover:bg-primary transition-colors shadow-xs cursor-pointer">
                    Filter
                </button>

                <!-- Reset Filter Button -->
                @if(request('search') || request('classroom_id') || request('status') || request('academic_year_id'))
                    <a href="{{ route('admin.students') }}" class="p-2 rounded-lg bg-surface-container-low text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors border border-slate-200/50 flex items-center justify-center" title="Reset Semua Filter">
                        <span class="material-symbols-outlined text-[20px]">restart_alt</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Bulk Action Bar (Revealed dynamically when 1 or more checkboxes selected) -->
    <div class="hidden p-space-sm px-space-md rounded-xl bg-primary text-on-primary shadow-md flex-wrap items-center justify-between gap-space-sm transition-all duration-300" id="bulkActionBar">
        <div class="flex items-center gap-3">
            <div class="w-7 h-7 rounded-md bg-on-primary/15 flex items-center justify-center font-label-md text-label-md font-bold text-on-primary">
                <span id="selectedCount">0</span>
            </div>
            <span class="font-label-md text-label-md font-semibold">Siswa dipilih untuk tindakan massal:</span>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <button type="button" onclick="printBulkCards()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-on-primary/15 hover:bg-on-primary/25 text-on-primary font-label-sm text-label-sm transition-colors active:scale-95 cursor-pointer">
                <span class="material-symbols-outlined text-[17px]">print</span>
                <span>Cetak Kartu CBT</span>
            </button>
            <button type="button" onclick="openBulkClassModal()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-on-primary/15 hover:bg-on-primary/25 text-on-primary font-label-sm text-label-sm transition-colors active:scale-95 cursor-pointer">
                <span class="material-symbols-outlined text-[17px]">swap_horiz</span>
                <span>Ubah Kelas Massal</span>
            </button>
            <button type="button" onclick="confirmBulkAction('activate')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-secondary text-on-secondary hover:opacity-90 font-label-sm text-label-sm transition-colors active:scale-95 cursor-pointer">
                <span class="material-symbols-outlined text-[17px]">lock_open</span>
                <span>Aktifkan</span>
            </button>
            <button type="button" onclick="confirmBulkAction('deactivate')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-tertiary-container text-on-primary hover:opacity-90 font-label-sm text-label-sm transition-colors active:scale-95 cursor-pointer">
                <span class="material-symbols-outlined text-[17px]">block</span>
                <span>Nonaktifkan</span>
            </button>
            <button type="button" onclick="confirmBulkAction('delete')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-error text-on-error hover:opacity-90 font-label-sm text-label-sm transition-colors active:scale-95 cursor-pointer">
                <span class="material-symbols-outlined text-[17px]">delete</span>
                <span>Hapus Terpilih</span>
            </button>
        </div>
    </div>

    <!-- Hidden form to process bulk actions -->
    <form id="bulkActionForm" action="{{ route('admin.students.bulk-action') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="action" id="bulkFormAction" value="">
        <input type="hidden" name="student_ids" id="bulkFormIds" value="">
        <input type="hidden" name="target_classroom_id" id="bulkTargetClassId" value="">
    </form>

    <!-- Data Table Container -->
    <div class="rounded-xl bg-surface-container-lowest shadow-sm overflow-hidden flex flex-col border border-slate-100">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase tracking-wider border-b border-slate-200/60 select-none">
                        <th class="py-3.5 pl-space-md pr-2 w-12 text-center">
                            <input class="w-4 h-4 rounded text-primary-container focus:ring-primary-container cursor-pointer accent-primary-container" id="selectAllCheckbox" type="checkbox" title="Pilih Semua Siswa"/>
                        </th>
                        <th class="py-3.5 px-2 w-12 text-center">No</th>
                        <th class="py-3.5 px-space-md min-w-[240px]">Nama Siswa &amp; Identitas</th>
                        <th class="py-3.5 px-space-md min-w-[170px]">NISN / NIS</th>
                        <th class="py-3.5 px-space-md min-w-[140px]">Kelas Rombel</th>
                        <th class="py-3.5 px-space-md min-w-[210px]">Email Sekolah</th>
                        <th class="py-3.5 px-space-md min-w-[140px]">Status Akun</th>
                        <th class="py-3.5 px-space-md min-w-[130px] text-center">Sesi Ujian</th>
                        <th class="py-3.5 pr-space-md pl-space-md text-right min-w-[190px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container">
                    @forelse($students as $student)
                        @php
                            $initials = collect(explode(' ', $student->name))->map(fn($part) => strtoupper(substr($part, 0, 1)))->take(2)->join('');
                            $primaryClass = $student->classrooms->first();
                            $examCount = $student->examParticipants->count();
                            $hasFailedExam = $student->examResults->contains('pass_status', 'fail');

                            // Status style logic
                            if (!$student->is_active) {
                                $statusBadgeClass = 'bg-error-container text-on-error-container';
                                $statusDotClass = 'bg-error';
                                $statusText = 'Ditangguhkan';
                            } elseif ($hasFailedExam) {
                                $statusBadgeClass = 'bg-tertiary-fixed text-on-tertiary-fixed-variant';
                                $statusDotClass = 'bg-tertiary-container';
                                $statusText = 'Perlu Remedial';
                            } else {
                                $statusBadgeClass = 'bg-secondary-container/50 text-on-secondary-container';
                                $statusDotClass = 'bg-secondary';
                                $statusText = 'Aktif';
                            }

                            // Dynamic Avatar color
                            $colorIndex = $student->id % 4;
                            $avatarPalette = [
                                ['bg' => 'bg-primary-fixed', 'text' => 'text-primary'],
                                ['bg' => 'bg-secondary/15', 'text' => 'text-secondary'],
                                ['bg' => 'bg-tertiary-fixed', 'text' => 'text-on-tertiary-fixed'],
                                ['bg' => 'bg-surface-container-high', 'text' => 'text-on-surface'],
                            ][$colorIndex];
                        @endphp
                        <tr class="hover:bg-surface-container-low/60 transition-colors group" data-student-id="{{ $student->id }}">
                            <!-- Checkbox -->
                            <td class="py-3.5 pl-space-md pr-2 text-center">
                                <input class="student-checkbox w-4 h-4 rounded text-primary-container focus:ring-primary-container cursor-pointer accent-primary-container" type="checkbox" value="{{ $student->id }}"/>
                            </td>

                            <!-- Row Number -->
                            <td class="py-3.5 px-2 text-center font-label-md text-label-md text-outline">
                                {{ str_pad($loop->iteration + ($students->firstItem() - 1), 2, '0', STR_PAD_LEFT) }}
                            </td>

                            <!-- Name & Identity -->
                            <td class="py-3.5 px-space-md">
                                <div class="flex items-center gap-3">
                                    @if($student->avatar)
                                        <img alt="{{ $student->name }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-primary-fixed-dim shrink-0 shadow-sm" src="{{ $student->avatar_url }}" />
                                    @else
                                        <div class="w-10 h-10 rounded-full {{ $avatarPalette['bg'] }} {{ $avatarPalette['text'] }} flex items-center justify-center font-headline-sm text-[15px] font-bold shrink-0 shadow-xs">
                                            {{ $initials ?: 'ST' }}
                                        </div>
                                    @endif
                                    <div class="flex flex-col min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <a href="{{ route('admin.students.edit', $student) }}" class="font-label-lg text-label-lg text-on-surface font-semibold truncate hover:text-primary transition-colors no-underline">
                                                {{ $student->name }}
                                            </a>
                                            @if($student->is_active)
                                                <span class="material-symbols-outlined text-[16px] text-secondary" title="Akun Terverifikasi">check_circle</span>
                                            @else
                                                <span class="material-symbols-outlined text-[16px] text-error" title="Akun Terkunci / Ditangguhkan">lock</span>
                                            @endif
                                        </div>
                                        <span class="font-label-sm text-label-sm text-outline truncate">
                                            {{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }} • Siswa Reguler
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- NISN / NIS -->
                            <td class="py-3.5 px-space-md">
                                <div class="flex flex-col">
                                    <span class="font-label-md text-label-md text-on-surface font-mono font-medium">{{ $student->nis ?: '-' }}</span>
                                    <span class="font-label-sm text-label-sm text-outline font-mono">ID: {{ $student->username }}</span>
                                </div>
                            </td>

                            <!-- Kelas Rombel -->
                            <td class="py-3.5 px-space-md">
                                @if($primaryClass)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-surface-container-high text-on-surface font-label-md text-label-md font-medium">
                                        <span class="material-symbols-outlined text-[15px] text-primary">groups</span>
                                        {{ $primaryClass->name }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs text-outline italic bg-surface-container-low">
                                        Belum di-set
                                    </span>
                                @endif
                            </td>

                            <!-- Email -->
                            <td class="py-3.5 px-space-md">
                                <span class="font-body-sm text-body-sm text-on-surface-variant font-mono truncate block max-w-[200px]" title="{{ $student->email }}">
                                    {{ $student->email }}
                                </span>
                            </td>

                            <!-- Status Akun -->
                            <td class="py-3.5 px-space-md">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full {{ $statusBadgeClass }} font-label-sm text-label-sm font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusDotClass }}"></span>
                                    {{ $statusText }}
                                </span>
                            </td>

                            <!-- Sesi Ujian -->
                            <td class="py-3.5 px-space-md text-center">
                                <div class="inline-flex flex-col items-center">
                                    <span class="font-label-lg text-label-lg font-bold text-on-surface">{{ $examCount }} Ujian</span>
                                    @if($examCount > 0)
                                        @if($hasFailedExam)
                                            <span class="font-label-sm text-label-sm text-tertiary-container font-semibold">Ada remedial</span>
                                        @else
                                            <span class="font-label-sm text-label-sm text-secondary font-semibold">100% tuntas</span>
                                        @endif
                                    @else
                                        <span class="font-label-sm text-label-sm text-outline">Belum ujian</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Aksi -->
                            <td class="py-3.5 pr-space-md pl-space-md text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <!-- View Details Modal Trigger -->
                                    <button onclick="showStudentDetail({{ json_encode([
                                        'id' => $student->id,
                                        'name' => $student->name,
                                        'nis' => $student->nis,
                                        'nisn' => $student->nisn ?: '-',
                                        'birth' => ($student->birth_place ? $student->birth_place . ', ' : '') . ($student->birth_date ? $student->birth_date->format('d/m/Y') : '-'),
                                        'religion' => $student->religion ?: '-',
                                        'email' => $student->email,
                                        'username' => $student->username,
                                        'gender' => $student->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                                        'phone' => $student->phone ?? '-',
                                        'address' => $student->address ?? '-',
                                        'classroom' => $primaryClass ? $primaryClass->name : 'Belum Terdaftar Kelas',
                                        'status' => $student->is_active ? 'Aktif' : 'Ditangguhkan',
                                        'exams' => $examCount,
                                        'avatar' => $student->avatar_url,
                                        'edit_url' => route('admin.students.edit', $student),
                                    ]) }})" class="p-1.5 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container-high transition-colors cursor-pointer" title="Lihat Detail Profil Siswa" type="button">
                                        <span class="material-symbols-outlined text-[19px]">visibility</span>
                                    </button>

                                    <!-- Edit Link -->
                                    <a href="{{ route('admin.students.edit', $student) }}" class="p-1.5 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container-high transition-colors no-underline inline-flex" title="Edit Data Siswa">
                                        <span class="material-symbols-outlined text-[19px]">edit</span>
                                    </a>

                                    <!-- Reset Password Modal Trigger -->
                                    <button onclick="openResetPasswordModal({{ $student->id }}, '{{ addslashes($student->name) }}')" class="p-1.5 rounded-lg text-on-surface-variant hover:text-tertiary-container hover:bg-surface-container-high transition-colors cursor-pointer" title="Reset Kata Sandi CBT" type="button">
                                        <span class="material-symbols-outlined text-[19px]">key</span>
                                    </button>

                                    <!-- Toggle Status (Activate / Suspend) -->
                                    <form action="{{ route('admin.students.toggle', $student) }}" method="POST" class="inline m-0">
                                        @csrf
                                        @if($student->is_active)
                                            <button class="p-1.5 rounded-lg text-on-surface-variant hover:text-error hover:bg-surface-container-high transition-colors cursor-pointer" title="Nonaktifkan Akun Siswa" type="submit" onclick="return confirm('Nonaktifkan akun siswa {{ addslashes($student->name) }}?')">
                                                <span class="material-symbols-outlined text-[19px]">block</span>
                                            </button>
                                        @else
                                            <button class="p-1.5 rounded-lg text-secondary hover:bg-secondary-container/40 transition-colors cursor-pointer" title="Aktifkan Kembali Akun Siswa" type="submit">
                                                <span class="material-symbols-outlined text-[19px]">lock_open</span>
                                            </button>
                                        @endif
                                    </form>

                                    <!-- Delete Single Student -->
                                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="inline m-0" onsubmit="return confirm('Hapus siswa {{ addslashes($student->name) }} secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="p-1.5 rounded-lg text-on-surface-variant hover:text-error hover:bg-error-container/40 transition-colors cursor-pointer" title="Hapus Permanen" type="submit">
                                            <span class="material-symbols-outlined text-[19px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-16 text-on-surface-variant">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center text-outline">
                                        <span class="material-symbols-outlined text-[36px]">person_off</span>
                                    </div>
                                    <span class="font-headline-sm text-base font-bold text-on-surface mt-2">Tidak Ada Data Siswa Ditemukan</span>
                                    <p class="font-body-sm text-sm text-on-surface-variant max-w-md">
                                        Tidak ada data yang cocok dengan kriteria pencarian atau filter saat ini.
                                    </p>
                                    <div class="flex items-center gap-2 mt-3">
                                        <a href="{{ route('admin.students') }}" class="px-4 py-2 rounded-lg bg-surface-container text-primary font-label-md text-label-md font-semibold hover:bg-surface-container-high transition-colors no-underline">
                                            Reset Semua Filter
                                        </a>
                                        <a href="{{ route('admin.students.create') }}" class="px-4 py-2 rounded-lg bg-primary-container text-on-primary font-label-md text-label-md font-semibold hover:bg-primary transition-colors no-underline">
                                            + Tambah Siswa Baru
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer with Pagination & Per Page Selector -->
        <div class="px-space-md py-3.5 bg-surface-container-low flex flex-col md:flex-row md:items-center justify-between gap-space-sm border-t border-slate-200/60">
            <div class="flex items-center gap-4 flex-wrap">
                <span class="font-body-sm text-body-sm text-on-surface-variant">
                    Menampilkan <span class="font-semibold text-on-surface">{{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-on-surface">{{ $students->total() }}</span> data siswa
                </span>
                <div class="h-4 w-px bg-outline-variant hidden sm:block"></div>
                <div class="flex items-center gap-1.5">
                    <span class="font-body-sm text-body-sm text-on-surface-variant hidden sm:inline">Tampilkan:</span>
                    <form action="{{ route('admin.students') }}" method="GET" class="inline m-0">
                        @foreach(request()->except('per_page', 'page') as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <div class="relative inline-flex items-center">
                            <select name="per_page" onchange="this.form.submit()" class="bg-surface-container-lowest font-label-sm text-label-sm text-on-surface py-1 pl-2.5 pr-6 rounded-md border-none outline-none cursor-pointer shadow-sm">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 per halaman</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per halaman</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per halaman</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per halaman</option>
                            </select>
                            <span class="material-symbols-outlined text-[14px] text-outline absolute right-1.5 pointer-events-none">expand_more</span>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Pagination Elements -->
            <div class="flex items-center gap-1">
                @if ($students->hasPages())
                    {{-- Previous Page Link --}}
                    @if ($students->onFirstPage())
                        <button class="w-8 h-8 rounded-lg flex items-center justify-center text-outline hover:bg-surface-container-high opacity-40 pointer-events-none transition-colors" disabled type="button">
                            <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                        </button>
                    @else
                        <a href="{{ $students->previousPageUrl() }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface hover:bg-surface-container-high transition-colors no-underline">
                            <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($students->getUrlRange(max(1, $students->currentPage() - 2), min($students->lastPage(), $students->currentPage() + 2)) as $page => $url)
                        @if ($page == $students->currentPage())
                            <button class="w-8 h-8 rounded-lg flex items-center justify-center font-label-md text-label-md bg-primary-container text-on-primary shadow-sm font-bold" type="button">
                                {{ $page }}
                            </button>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 rounded-lg flex items-center justify-center font-label-md text-label-md text-on-surface hover:bg-surface-container-high transition-colors no-underline">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($students->hasMorePages())
                        <a href="{{ $students->nextPageUrl() }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface hover:bg-surface-container-high transition-colors no-underline">
                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                        </a>
                    @else
                        <button class="w-8 h-8 rounded-lg flex items-center justify-center text-outline hover:bg-surface-container-high opacity-40 pointer-events-none transition-colors" disabled type="button">
                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Systematic Notice Box: Real Account Credential Sync -->
    <div class="p-space-md rounded-xl bg-surface-container-low flex flex-col sm:flex-row items-start sm:items-center justify-between gap-space-md shadow-sm border border-slate-200/50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary shrink-0">
                <span class="material-symbols-outlined text-[22px]">fingerprint</span>
            </div>
            <div class="flex flex-col">
                <span class="font-label-lg text-label-lg text-on-surface font-semibold">Sinkronisasi Kredensial CBT Siswa</span>
                <span class="font-body-sm text-body-sm text-on-surface-variant">
                    Verifikasi seluruh username login CBT dan email siswa secara menyeluruh untuk kelancaran sesi ujian.
                </span>
            </div>
        </div>
        <form action="{{ route('admin.students.sync') }}" method="POST" class="m-0 shrink-0" onsubmit="return confirm('Jalankan sinkronisasi akun CBT seluruh peserta didik?')">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-surface-container-lowest text-on-surface font-label-sm text-label-sm shadow-sm hover:bg-surface-container-high transition-colors whitespace-nowrap border border-slate-200/60 active:scale-95 cursor-pointer">
                <span class="material-symbols-outlined text-[18px] text-secondary">sync</span>
                <span>Sinkronkan Akun Sekarang</span>
            </button>
        </form>
    </div>
</div>

<!-- ==================== REAL FUNCTIONAL MODALS ==================== -->

<!-- 1. Modal Detail Siswa -->
<div id="studentDetailModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 flex flex-col gap-4 transform transition-all">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[24px]">badge</span>
                <h3 class="font-headline-sm text-lg font-bold text-on-surface">Detail Profil Peserta Didik</h3>
            </div>
            <button onclick="closeModal('studentDetailModal')" class="p-1 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container cursor-pointer">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-4 p-4 rounded-xl bg-surface-container-low">
                <img id="detailAvatar" src="" alt="Avatar" class="w-16 h-16 rounded-full object-cover ring-2 ring-primary-fixed-dim shadow-sm">
                <div class="flex flex-col">
                    <span id="detailName" class="font-headline-sm text-lg font-bold text-on-surface"></span>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span id="detailNis" class="font-mono text-xs text-primary font-semibold"></span>
                        <span class="text-outline text-xs">•</span>
                        <span id="detailNisn" class="font-mono text-xs text-outline"></span>
                    </div>
                    <span id="detailGender" class="font-body-sm text-xs text-outline mt-0.5"></span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="flex flex-col p-3 rounded-lg bg-surface-container-low">
                    <span class="text-xs text-outline font-medium">Kelas / Rombel</span>
                    <span id="detailClassroom" class="font-semibold text-on-surface mt-0.5"></span>
                </div>
                <div class="flex flex-col p-3 rounded-lg bg-surface-container-low">
                    <span class="text-xs text-outline font-medium">Status Akun CBT</span>
                    <span id="detailStatus" class="font-semibold text-secondary mt-0.5"></span>
                </div>
                <div class="flex flex-col p-3 rounded-lg bg-surface-container-low">
                    <span class="text-xs text-outline font-medium">Tempat, Tgl Lahir</span>
                    <span id="detailBirth" class="font-semibold text-on-surface mt-0.5 text-xs truncate"></span>
                </div>
                <div class="flex flex-col p-3 rounded-lg bg-surface-container-low">
                    <span class="text-xs text-outline font-medium">Agama</span>
                    <span id="detailReligion" class="font-semibold text-on-surface mt-0.5 text-xs"></span>
                </div>
                <div class="flex flex-col p-3 rounded-lg bg-surface-container-low">
                    <span class="text-xs text-outline font-medium">Email Terdaftar</span>
                    <span id="detailEmail" class="font-mono text-xs text-on-surface mt-0.5 truncate"></span>
                </div>
                <div class="flex flex-col p-3 rounded-lg bg-surface-container-low">
                    <span class="text-xs text-outline font-medium">Username Login</span>
                    <span id="detailUsername" class="font-mono text-xs text-on-surface mt-0.5"></span>
                </div>
                <div class="flex flex-col p-3 rounded-lg bg-surface-container-low col-span-2">
                    <span class="text-xs text-outline font-medium">Nomor Telepon / WhatsApp</span>
                    <span id="detailPhone" class="font-body-sm text-on-surface mt-0.5"></span>
                </div>
                <div class="flex flex-col p-3 rounded-lg bg-surface-container-low col-span-2">
                    <span class="text-xs text-outline font-medium">Alamat Domisili</span>
                    <span id="detailAddress" class="font-body-sm text-on-surface mt-0.5"></span>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center pt-3 border-t border-slate-100">
            <a id="detailEditBtn" href="#" class="px-4 py-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary font-label-md font-semibold no-underline inline-flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">edit</span>
                <span>Edit Biodata</span>
            </a>
            <button onclick="closeModal('studentDetailModal')" type="button" class="px-5 py-2 rounded-lg bg-primary-container text-on-primary font-label-md font-bold shadow-sm cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- 2. Modal Reset Kata Sandi -->
<div id="resetPasswordModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 flex flex-col gap-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-tertiary-container text-[24px]">key</span>
                <h3 class="font-headline-sm text-lg font-bold text-on-surface">Reset Kata Sandi CBT</h3>
            </div>
            <button onclick="closeModal('resetPasswordModal')" class="p-1 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container cursor-pointer">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form id="resetPasswordForm" action="" method="POST" class="flex flex-col gap-4">
            @csrf
            <p class="font-body-sm text-sm text-on-surface-variant">
                Atur ulang kata sandi login CBT untuk siswa <strong id="resetStudentName" class="text-on-surface"></strong>.
            </p>

            <div class="flex flex-col gap-1.5">
                <label class="font-label-md text-label-md text-on-surface font-semibold">Kata Sandi Baru</label>
                <div class="flex items-center bg-surface-container-low rounded-lg px-3 py-2 border border-slate-200">
                    <span class="material-symbols-outlined text-outline text-[18px] mr-2">lock</span>
                    <input type="text" name="new_password" value="123456" class="w-full bg-transparent border-none outline-none font-mono text-sm text-on-surface" required minlength="6">
                </div>
                <span class="text-xs text-outline">Bawaan standar: <strong>123456</strong> (siswa disarankan mengganti setelah login).</span>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('resetPasswordModal')" class="px-4 py-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md font-medium cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-primary hover:bg-primary-container text-on-primary font-label-md font-bold shadow-sm cursor-pointer">
                    Simpan Kata Sandi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 3. Modal Impor Data Excel / CSV -->
<div id="importModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 flex flex-col gap-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[24px]">file_upload</span>
                <h3 class="font-headline-sm text-lg font-bold text-on-surface">Impor Data Siswa (.csv)</h3>
            </div>
            <button onclick="closeModal('importModal')" class="p-1 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container cursor-pointer">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
            @csrf
            
            <div class="p-3.5 rounded-xl bg-surface-container-low text-xs text-on-surface-variant flex flex-col gap-2 border border-slate-200/50">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-on-surface">Format Kolom Berkas CSV:</span>
                    <a href="{{ route('admin.students.template') }}" class="inline-flex items-center gap-1 text-primary hover:underline font-semibold">
                        <span class="material-symbols-outlined text-[15px]">download</span>
                        <span>Unduh Template CSV</span>
                    </a>
                </div>
                <code class="p-2 rounded bg-surface-container-lowest font-mono text-[11px] block overflow-x-auto text-primary">
                    NIS, Nama Lengkap, Email, Username, Jenis Kelamin (L/P), Password
                </code>
                <span class="text-outline">Mendukung pemisah koma (,) maupun titik-koma (;) standar Excel Windows.</span>
            </div>

            <!-- Target Rombel Kelas -->
            <div class="flex flex-col gap-1.5">
                <label class="font-label-md text-label-md text-on-surface font-semibold">Tentukan Rombel Kelas (Opsional)</label>
                <div class="relative flex items-center bg-surface-container-low rounded-lg px-3 py-2 border border-slate-200">
                    <span class="material-symbols-outlined text-outline text-[18px] mr-2">meeting_room</span>
                    <select name="classroom_id" class="w-full bg-transparent border-none outline-none font-label-md text-label-md text-on-surface appearance-none cursor-pointer">
                        <option value="">-- Biarkan Kosong / Sesuai Pengaturan Nanti --</option>
                        @foreach($classrooms as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->academicYear->name ?? 'Tahun Berjalan' }})</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined text-outline text-[16px] absolute right-2.5 pointer-events-none">expand_more</span>
                </div>
            </div>

            <!-- File Input -->
            <div class="flex flex-col gap-1.5">
                <label class="font-label-md text-label-md text-on-surface font-semibold">Pilih Berkas CSV / Excel</label>
                <input type="file" name="file" accept=".csv, .txt" required class="block w-full text-xs text-on-surface-variant file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-container file:text-on-primary hover:file:bg-primary cursor-pointer border border-slate-200 rounded-lg p-2 bg-surface-container-low">
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('importModal')" class="px-4 py-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md font-medium cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-primary-container hover:bg-primary text-on-primary font-label-md font-bold shadow-sm cursor-pointer">
                    Mulai Impor Siswa
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 4. Modal Ubah Kelas Massal -->
<div id="bulkClassModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 flex flex-col gap-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[24px]">swap_horiz</span>
                <h3 class="font-headline-sm text-lg font-bold text-on-surface">Pindahkan Rombel Siswa Terpilih</h3>
            </div>
            <button onclick="closeModal('bulkClassModal')" class="p-1 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container cursor-pointer">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <p class="font-body-sm text-sm text-on-surface-variant">
            Pindahkan <strong id="bulkClassCount">0</strong> siswa yang dipilih ke rombongan belajar kelas baru.
        </p>

        <div class="flex flex-col gap-1.5">
            <label class="font-label-md text-label-md text-on-surface font-semibold">Kelas Rombel Target</label>
            <div class="relative flex items-center bg-surface-container-low rounded-lg px-3 py-2 border border-slate-200">
                <span class="material-symbols-outlined text-outline text-[18px] mr-2">meeting_room</span>
                <select id="modalTargetClassSelect" class="w-full bg-transparent border-none outline-none font-label-md text-label-md text-on-surface appearance-none cursor-pointer">
                    <option value="">-- Pilih Kelas Target --</option>
                    @foreach($classrooms as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->academicYear->name ?? 'Tahun Berjalan' }})</option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined text-outline text-[16px] absolute right-2.5 pointer-events-none">expand_more</span>
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
            <button type="button" onclick="closeModal('bulkClassModal')" class="px-4 py-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md font-medium cursor-pointer">
                Batal
            </button>
            <button type="button" onclick="submitBulkChangeClass()" class="px-5 py-2 rounded-lg bg-primary-container hover:bg-primary text-on-primary font-label-md font-bold shadow-sm cursor-pointer">
                Pindahkan Sekarang
            </button>
        </div>
    </div>
</div>

<script>
    // Modal Helpers
    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) modal.classList.add('hidden');
    }

    function openImportModal() {
        document.getElementById('importModal').classList.remove('hidden');
    }

    function showStudentDetail(data) {
        document.getElementById('detailAvatar').src = data.avatar;
        document.getElementById('detailName').textContent = data.name;
        document.getElementById('detailNis').textContent = 'NIS: ' + (data.nis || '-');
        document.getElementById('detailNisn').textContent = 'NISN: ' + (data.nisn || '-');
        document.getElementById('detailGender').textContent = data.gender;
        document.getElementById('detailBirth').textContent = data.birth || '-';
        document.getElementById('detailReligion').textContent = data.religion || '-';
        document.getElementById('detailClassroom').textContent = data.classroom;
        document.getElementById('detailStatus').textContent = data.status;
        document.getElementById('detailEmail').textContent = data.email;
        document.getElementById('detailUsername').textContent = data.username;
        document.getElementById('detailPhone').textContent = data.phone;
        document.getElementById('detailAddress').textContent = data.address;
        document.getElementById('detailEditBtn').href = data.edit_url;

        document.getElementById('studentDetailModal').classList.remove('hidden');
    }

    function openResetPasswordModal(id, name) {
        document.getElementById('resetStudentName').textContent = name;
        document.getElementById('resetPasswordForm').action = '/admin/siswa/' + id + '/reset-password';
        document.getElementById('resetPasswordModal').classList.remove('hidden');
    }

    // Bulk Actions Script
    (function () {
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const studentCheckboxes = document.querySelectorAll('.student-checkbox');
        const bulkActionBar = document.getElementById('bulkActionBar');
        const selectedCount = document.getElementById('selectedCount');

        function getCheckedIds() {
            const checkedBoxes = document.querySelectorAll('.student-checkbox:checked');
            return Array.from(checkedBoxes).map(cb => cb.value);
        }

        function updateBulkState() {
            const checkedBoxes = document.querySelectorAll('.student-checkbox:checked');
            const count = checkedBoxes.length;
            if (selectedCount) {
                selectedCount.textContent = count;
            }
            if (count > 0) {
                bulkActionBar.classList.remove('hidden');
                bulkActionBar.classList.add('flex');
            } else {
                bulkActionBar.classList.add('hidden');
                bulkActionBar.classList.remove('flex');
            }

            if (selectAllCheckbox) {
                selectAllCheckbox.checked = (count === studentCheckboxes.length && count > 0);
                selectAllCheckbox.indeterminate = (count > 0 && count < studentCheckboxes.length);
            }
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function () {
                studentCheckboxes.forEach(cb => {
                    cb.checked = selectAllCheckbox.checked;
                    const tr = cb.closest('tr');
                    if (tr) {
                        if (cb.checked) {
                            tr.classList.add('bg-primary-fixed/20');
                        } else {
                            tr.classList.remove('bg-primary-fixed/20');
                        }
                    }
                });
                updateBulkState();
            });
        }

        studentCheckboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                const tr = cb.closest('tr');
                if (tr) {
                    if (cb.checked) {
                        tr.classList.add('bg-primary-fixed/20');
                    } else {
                        tr.classList.remove('bg-primary-fixed/20');
                    }
                }
                updateBulkState();
            });
        });

        // Global functions for bulk action buttons
        window.confirmBulkAction = function(action) {
            const ids = getCheckedIds();
            if (ids.length === 0) {
                alert('Pilih minimal satu siswa.');
                return;
            }

            const prompts = {
                'delete': `Apakah Anda yakin ingin menghapus ${ids.length} siswa terpilih secara permanen?`,
                'activate': `Aktifkan ${ids.length} akun siswa terpilih?`,
                'deactivate': `Nonaktifkan ${ids.length} akun siswa terpilih?`
            };

            if (confirm(prompts[action])) {
                document.getElementById('bulkFormAction').value = action;
                document.getElementById('bulkFormIds').value = ids.join(',');
                document.getElementById('bulkActionForm').submit();
            }
        };

        window.openBulkClassModal = function() {
            const ids = getCheckedIds();
            if (ids.length === 0) {
                alert('Pilih minimal satu siswa.');
                return;
            }
            document.getElementById('bulkClassCount').textContent = ids.length;
            document.getElementById('bulkClassModal').classList.remove('hidden');
        };

        window.submitBulkChangeClass = function() {
            const ids = getCheckedIds();
            const targetClassId = document.getElementById('modalTargetClassSelect').value;
            if (!targetClassId) {
                alert('Silakan pilih kelas target terlebih dahulu.');
                return;
            }

            document.getElementById('bulkFormAction').value = 'change_class';
            document.getElementById('bulkFormIds').value = ids.join(',');
            document.getElementById('bulkTargetClassId').value = targetClassId;
            document.getElementById('bulkActionForm').submit();
        };

        window.printBulkCards = function() {
            const ids = getCheckedIds();
            if (ids.length === 0) {
                alert('Pilih minimal satu siswa untuk mencetak kartu.');
                return;
            }

            const url = "{{ route('admin.students.print-cards') }}?ids=" + ids.join(',');
            window.open(url, '_blank');
        };

        updateBulkState();
    })();
</script>
@endsection
