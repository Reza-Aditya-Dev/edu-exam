@extends('layouts.admin')

@section('title', 'Manajemen Data Kelas & Rombongan Belajar — EduExam')
@section('page_title', 'Data Kelas & Rombel')

@section('admin-content')
<div class="flex flex-col w-full gap-space-lg pb-16">

    <!-- Breadcrumb & Top Bar Navigation -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex flex-col">
            <div class="flex items-center gap-2 mb-1.5">
                <a href="{{ route('admin.dashboard') }}" class="font-label-sm text-label-sm text-outline hover:text-primary transition-colors uppercase tracking-wider flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">home</span>
                    <span>Master Data</span>
                </a>
                <span class="material-symbols-outlined text-outline text-[14px]">chevron_right</span>
                <span class="font-label-sm text-label-sm text-primary font-semibold">Data Kelas</span>
            </div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">Manajemen Data Kelas &amp; Rombongan Belajar</h1>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-3xl mt-1">
                Kelola rombel siswa kelas X, XI, dan XII {{ \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}, penetapan wali kelas, dan pembagian kuota ujian.
            </p>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3 self-start md:self-auto shrink-0 flex-wrap">
            <a href="{{ route('admin.classrooms', array_merge(request()->query(), ['export' => 'csv'])) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-surface-container-lowest text-on-surface shadow-sm hover:bg-surface-container-high transition-all font-label-lg text-label-lg active:scale-95 border border-slate-200/80 no-underline" title="Unduh data rombel ke format CSV/Excel">
                <span class="material-symbols-outlined text-[18px] text-secondary">table_view</span>
                <span>Unduh Rekap Kelas (Excel)</span>
            </a>
            <button onclick="document.getElementById('modalTambahKelas').classList.remove('hidden')" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary-container text-on-primary shadow-sm hover:opacity-90 transition-all font-label-lg text-label-lg active:scale-95 cursor-pointer font-bold" type="button">
                <span class="material-symbols-outlined text-[20px]">add</span>
                <span>Tambah Kelas Baru</span>
            </button>
        </div>
    </div>

    <!-- Key Metrics Row (Asymmetric Bento Surface) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Metric 1: Total Rombel -->
        <div class="p-5 rounded-xl bg-surface-container-lowest shadow-sm flex items-center justify-between relative overflow-hidden group border border-slate-100">
            <div class="flex flex-col z-10">
                <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider font-semibold">Total Rombel</span>
                <span class="font-headline-lg text-headline-lg text-on-surface mt-1 font-bold">{{ $totalClassrooms }}</span>
                <span class="font-body-sm text-body-sm text-secondary flex items-center gap-1 mt-0.5 font-medium">
                    <span class="material-symbols-outlined text-[14px]">check_circle</span> 100% Terverifikasi
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[26px]">meeting_room</span>
            </div>
        </div>

        <!-- Metric 2: Total Siswa Terdistribusi -->
        <div class="p-5 rounded-xl bg-surface-container-lowest shadow-sm flex items-center justify-between relative overflow-hidden group border border-slate-100">
            <div class="flex flex-col z-10">
                <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider font-semibold">Total Siswa Terdistribusi</span>
                <span class="font-headline-lg text-headline-lg text-on-surface mt-1 font-bold">{{ $totalStudents }}</span>
                <span class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-1 mt-0.5">
                    <span>Semua Kuota Terpenuhi</span>
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-secondary group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[26px]">groups</span>
            </div>
        </div>

        <!-- Metric 3: Rata-rata / Rombel -->
        <div class="p-5 rounded-xl bg-surface-container-lowest shadow-sm flex items-center justify-between relative overflow-hidden group border border-slate-100">
            <div class="flex flex-col z-10">
                <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider font-semibold">Rata-rata / Rombel</span>
                <span class="font-headline-lg text-headline-lg text-on-surface mt-1 font-bold">{{ $avgPerClassroom }}</span>
                <span class="font-body-sm text-body-sm text-outline flex items-center gap-1 mt-0.5">
                    <span>Target Ideal: 32–36</span>
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-primary-container group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[26px]">tune</span>
            </div>
        </div>

        <!-- Metric 4: Wali Kelas Terhubung -->
        <div class="p-5 rounded-xl bg-surface-container-lowest shadow-sm flex items-center justify-between relative overflow-hidden group border border-slate-100">
            <div class="flex flex-col z-10">
                <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider font-semibold">Wali Kelas Terhubung</span>
                <span class="font-headline-lg text-headline-lg text-on-surface mt-1 font-bold">{{ $assignedHomerooms }}</span>
                <span class="font-body-sm text-body-sm text-secondary flex items-center gap-1 mt-0.5 font-medium">
                    <span class="material-symbols-outlined text-[14px]">done_all</span> {{ $assignedHomerooms }}/{{ $totalClassrooms }} Terisi
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-amber-700 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[26px]">badge</span>
            </div>
        </div>
    </div>

    <!-- Filter & Navigation Bar -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 p-4 rounded-xl bg-surface-container-lowest shadow-sm border border-slate-100">
        <!-- Search Form -->
        <form action="{{ route('admin.classrooms') }}" method="GET" class="flex items-center flex-1 max-w-md bg-surface-container-low rounded-lg px-3 py-2 focus-within:bg-surface-container-high transition-colors">
            @if(request('grade'))
                <input type="hidden" name="grade" value="{{ request('grade') }}">
            @endif
            @if(request('academic_year_id'))
                <input type="hidden" name="academic_year_id" value="{{ request('academic_year_id') }}">
            @endif
            <span class="material-symbols-outlined text-outline text-[20px] mr-2.5">search</span>
            <input 
                name="search" 
                class="w-full bg-transparent border-none outline-none font-body-sm text-body-sm text-on-surface placeholder:text-outline" 
                placeholder="Cari nama kelas atau wali kelas..." 
                type="text" 
                value="{{ request('search') }}"
            />
            @if(request('search'))
                <a href="{{ route('admin.classrooms', ['grade' => request('grade'), 'academic_year_id' => request('academic_year_id')]) }}" class="text-outline hover:text-on-surface ml-2">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </a>
            @endif
        </form>

        <!-- Segmented Level Tabs -->
        @php
            $currentGrade = request('grade', 'all');
            $searchParam = request('search') ? ['search' => request('search')] : [];
            $yearParam = request('academic_year_id') ? ['academic_year_id' => request('academic_year_id')] : [];
            $baseParams = array_merge($searchParam, $yearParam);
        @endphp
        <div class="flex items-center gap-1 overflow-x-auto pb-1 lg:pb-0 scrollbar-none">
            <a href="{{ route('admin.classrooms', array_merge($baseParams, ['grade' => 'all'])) }}" class="px-3.5 py-1.5 rounded-lg font-label-md text-label-md transition-all shrink-0 no-underline {{ ($currentGrade === 'all' || !$currentGrade) ? 'bg-primary text-on-primary font-semibold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                Semua Tingkat ({{ $totalClassrooms }} Kelas)
            </a>
            <a href="{{ route('admin.classrooms', array_merge($baseParams, ['grade' => '10'])) }}" class="px-3.5 py-1.5 rounded-lg font-label-md text-label-md transition-all shrink-0 no-underline {{ $currentGrade === '10' ? 'bg-primary text-on-primary font-semibold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                Kelas X ({{ $countGrade10 }} Kelas)
            </a>
            <a href="{{ route('admin.classrooms', array_merge($baseParams, ['grade' => '11'])) }}" class="px-3.5 py-1.5 rounded-lg font-label-md text-label-md transition-all shrink-0 no-underline {{ $currentGrade === '11' ? 'bg-primary text-on-primary font-semibold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                Kelas XI ({{ $countGrade11 }} Kelas)
            </a>
            <a href="{{ route('admin.classrooms', array_merge($baseParams, ['grade' => '12'])) }}" class="px-3.5 py-1.5 rounded-lg font-label-md text-label-md transition-all shrink-0 no-underline {{ $currentGrade === '12' ? 'bg-primary text-on-primary font-semibold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                Kelas XII ({{ $countGrade12 }} Kelas)
            </a>
        </div>
    </div>

    <!-- Class Grid Layout (3 Columns on large screens) -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($classrooms as $c)
            @php
                // Detect icon based on major/name
                $majorUpper = strtoupper($c->major . ' ' . $c->name);
                $cardIcon = 'meeting_room';
                if (str_contains($majorUpper, 'IPA') || str_contains($majorUpper, 'MIPA') || str_contains($majorUpper, 'SAINS')) {
                    $cardIcon = 'biotech';
                } elseif (str_contains($majorUpper, 'IPS') || str_contains($majorUpper, 'SOSIAL')) {
                    $cardIcon = 'public';
                } elseif (str_contains($majorUpper, 'BAHASA')) {
                    $cardIcon = 'translate';
                } elseif (str_contains($majorUpper, 'KOMPUTER') || str_contains($majorUpper, 'RPL') || str_contains($majorUpper, 'TKJ')) {
                    $cardIcon = 'computer';
                }

                // Phase naming in Kurikulum Merdeka
                $phase = $c->grade == 10 ? 'Fase E' : 'Fase F';
                $majorLabel = $c->major ?: ($c->grade == 10 ? 'Umum / Fondasi' : 'Peminatan');

                // Active exam for this class
                $activeExam = $c->exams->first();
            @endphp
            <div class="rounded-xl bg-surface-container-lowest shadow-sm p-5 flex flex-col justify-between hover:shadow-md transition-all group border border-slate-100">
                <div>
                    <!-- Header Card -->
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 class="font-headline-md text-headline-md text-on-surface group-hover:text-primary transition-colors font-bold">{{ $c->name }}</h2>
                                <span class="px-2.5 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm flex items-center gap-1 font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                    {{ $c->academicYear ? $c->academicYear->name : 'Aktif' }}
                                </span>
                            </div>
                            <span class="font-body-sm text-body-sm text-outline mt-0.5 block font-medium">
                                {{ $phase }} ({{ $majorLabel }})
                            </span>
                        </div>
                        <div class="w-9 h-9 rounded-lg bg-surface-container-low flex items-center justify-center text-outline group-hover:bg-primary-fixed group-hover:text-primary transition-colors shrink-0">
                            <span class="material-symbols-outlined text-[20px]">{{ $cardIcon }}</span>
                        </div>
                    </div>

                    <!-- Teacher Assigned (Wali Kelas) -->
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-surface-container-low mb-4">
                        @if($c->homeroomTeacher && $c->homeroomTeacher->avatar)
                            <img alt="{{ $c->homeroomTeacher->name }}" class="w-11 h-11 rounded-full object-cover shrink-0 shadow-sm border border-white" src="{{ $c->homeroomTeacher->avatar_url }}"/>
                        @elseif($c->homeroomTeacher)
                            <div class="w-11 h-11 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-bold text-sm shrink-0 shadow-sm">
                                {{ strtoupper(substr($c->homeroomTeacher->name, 0, 2)) }}
                            </div>
                        @else
                            <div class="w-11 h-11 rounded-full bg-surface-container text-outline flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[20px]">person_off</span>
                            </div>
                        @endif
                        <div class="flex flex-col min-w-0">
                            <span class="font-label-sm text-label-sm text-outline">Wali Kelas</span>
                            <span class="font-label-lg text-label-lg text-on-surface truncate font-semibold">
                                {{ $c->homeroomTeacher?->name ?? 'Belum Ditugaskan' }}
                            </span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant text-xs truncate">
                                {{ $c->homeroomTeacher?->nip ? 'NIP: ' . $c->homeroomTeacher->nip : 'Belum ada NIP' }}
                            </span>
                        </div>
                    </div>

                    <!-- Class Metadata Chips -->
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div class="px-3 py-2 rounded-lg bg-surface flex flex-col">
                            <span class="font-label-sm text-label-sm text-outline">Jumlah Siswa</span>
                            <span class="font-label-md text-label-md text-on-surface font-semibold flex items-center gap-1 mt-0.5">
                                <span class="material-symbols-outlined text-[16px] text-primary">group</span>
                                {{ $c->students_count }} Siswa
                            </span>
                        </div>
                        <div class="px-3 py-2 rounded-lg bg-surface flex flex-col">
                            <span class="font-label-sm text-label-sm text-outline">Kapasitas Maksimal</span>
                            <span class="font-label-md text-label-md text-on-surface font-semibold flex items-center gap-1 mt-0.5">
                                <span class="material-symbols-outlined text-[16px] text-secondary">domain</span>
                                {{ $c->capacity }} Siswa
                            </span>
                        </div>
                        <div class="px-3 py-2 rounded-lg bg-surface flex flex-col col-span-2">
                            <span class="font-label-sm text-label-sm text-outline">Tahun Ajaran &amp; Semester</span>
                            <span class="font-body-md text-body-md text-on-surface font-medium flex items-center gap-1 mt-0.5 text-xs truncate">
                                <span class="material-symbols-outlined text-[16px] text-outline">calendar_today</span>
                                {{ $c->academicYear ? $c->academicYear->name . ' • Semester ' . ($c->academicYear->semester == 1 ? 'Ganjil' : 'Genap') : 'Tahun Ajaran Aktif' }}
                            </span>
                        </div>
                    </div>

                    <!-- Exam State Banner -->
                    @if($activeExam)
                        <div class="p-3 rounded-lg bg-primary-fixed text-on-primary-fixed mb-5 flex items-center justify-between">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="material-symbols-outlined text-[18px] animate-pulse text-primary shrink-0">radio_button_checked</span>
                                <span class="font-label-sm text-label-sm font-semibold truncate">1 Ujian Aktif: {{ $activeExam->title }}</span>
                            </div>
                            <a href="{{ route('admin.exams') }}" class="font-label-sm text-label-sm text-primary underline cursor-pointer shrink-0 ml-2">Pantau</a>
                        </div>
                    @else
                        <div class="p-3 rounded-lg bg-surface-container text-on-surface-variant mb-5 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px] text-outline">check_circle</span>
                                <span class="font-label-sm text-label-sm">Tidak Ada Ujian Aktif Saat Ini</span>
                            </div>
                            <span class="font-label-sm text-label-sm text-secondary font-semibold">Siap</span>
                        </div>
                    @endif
                </div>

                <!-- Footer Card Actions -->
                <div class="flex items-center justify-between gap-2 pt-3 border-none bg-surface-container-low -mx-5 -mb-5 px-5 py-3 rounded-b-xl">
                    <a href="{{ route('admin.students', ['classroom_id' => $c->id]) }}" class="inline-flex items-center gap-1.5 text-primary font-label-md text-label-md hover:underline no-underline font-semibold" title="Buka daftar siswa di kelas ini">
                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                        <span>Lihat Siswa ({{ $c->students_count }})</span>
                    </a>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.classrooms.edit', $c->id) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface-variant hover:bg-surface-container-high transition-colors" title="Edit Rombel Kelas">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                        </a>
                        <button class="w-8 h-8 rounded-lg flex items-center justify-center text-error hover:bg-error-container hover:text-on-error-container transition-colors cursor-pointer" onclick="openDeleteModal('{{ $c->id }}', '{{ $c->name }}', {{ $c->students_count }})" title="Hapus Kelas" type="button">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full p-12 text-center rounded-xl bg-surface-container-lowest border border-slate-200">
                <span class="material-symbols-outlined text-outline text-5xl mb-2">meeting_room</span>
                <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Tidak ada data rombel kelas</h3>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1 max-w-md mx-auto">
                    Tidak ditemukan data rombongan belajar sesuai kata kunci pencarian atau filter tingkat yang dipilih.
                </p>
                <div class="mt-4 flex items-center justify-center gap-3">
                    <a href="{{ route('admin.classrooms') }}" class="px-4 py-2 rounded-lg bg-surface-container-low text-on-surface text-xs font-semibold hover:bg-surface-container transition-colors">
                        Reset Filter
                    </a>
                    <button onclick="document.getElementById('modalTambahKelas').classList.remove('hidden')" class="px-4 py-2 rounded-lg bg-primary-container text-on-primary text-xs font-bold hover:bg-primary transition-colors cursor-pointer">
                        + Tambah Kelas Baru
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination Bottom Bar -->
    @if($classrooms->hasPages())
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 rounded-xl bg-surface-container-lowest shadow-sm border border-slate-100">
            <span class="font-body-sm text-body-sm text-on-surface-variant">
                Menampilkan <span class="font-semibold text-on-surface">{{ $classrooms->firstItem() }}</span> - <span class="font-semibold text-on-surface">{{ $classrooms->lastItem() }}</span> dari <span class="font-semibold text-on-surface">{{ $classrooms->total() }}</span> rombel kelas
            </span>
            <div class="flex items-center gap-1">
                {{ $classrooms->links() }}
            </div>
        </div>
    @endif

</div>

<!-- Modal Konfirmasi Hapus Kelas -->
<div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs hidden" id="deleteModal">
    <div class="w-full max-w-md bg-surface-container-lowest p-6 rounded-2xl shadow-xl flex flex-col gap-4 m-4 border border-slate-100 animate-in fade-in zoom-in duration-200">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-error-container text-on-error-container flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[26px]">warning</span>
            </div>
            <div class="flex flex-col">
                <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Hapus Rombel Kelas?</h3>
                <span class="font-body-sm text-body-sm text-outline">Tindakan ini tidak dapat dibatalkan</span>
            </div>
        </div>

        <p class="font-body-md text-body-md text-on-surface-variant">
            Apakah Anda yakin ingin menghapus kelas <span class="font-bold text-on-surface" id="targetClassName"></span>?
        </p>

        <div id="deleteWarningStudents" class="p-3 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-xs hidden">
            <span class="font-bold block mb-0.5">Peringatan:</span>
            Kelas ini masih memiliki <span id="targetClassStudentCount" class="font-bold"></span> siswa terdaftar. Hapus atau pindahkan siswa terlebih dahulu sebelum menghapus rombel.
        </div>

        <form id="deleteClassForm" action="" method="POST" class="m-0">
            @csrf
            @method('DELETE')
            <div class="flex items-center justify-end gap-3 pt-2">
                <button class="px-4 py-2 rounded-lg bg-surface-container text-on-surface font-label-lg text-label-lg hover:bg-surface-container-high transition-colors cursor-pointer" onclick="closeDeleteModal()" type="button">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-error text-on-error font-label-lg text-label-lg hover:opacity-90 transition-opacity font-bold cursor-pointer">
                    Ya, Hapus Kelas
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Kelas Baru -->
<div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs hidden" id="modalTambahKelas">
    <div class="w-full max-w-lg bg-surface-container-lowest p-6 rounded-2xl shadow-xl flex flex-col gap-5 m-4 border border-slate-100 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-primary-container text-on-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">add_box</span>
                </div>
                <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Tambah Rombongan Belajar</h3>
            </div>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center text-outline hover:bg-surface-container-high cursor-pointer" onclick="document.getElementById('modalTambahKelas').classList.add('hidden')" type="button">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form action="{{ route('admin.classrooms.store') }}" method="POST" class="flex flex-col gap-4">
            @csrf

            <!-- Nama Rombel / Kelas -->
            <div>
                <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">
                    Nama Rombel / Kelas <span class="text-error font-bold">*</span>
                </label>
                <input 
                    name="name" 
                    class="w-full px-3.5 py-2.5 rounded-lg bg-surface-container-low font-body-sm text-body-sm text-on-surface border border-slate-200 outline-none focus:ring-2 focus:ring-primary-container focus:bg-surface-container-lowest transition-all" 
                    placeholder="Contoh: X IPA 3 atau XI IPS 2" 
                    type="text" 
                    required
                />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <!-- Tingkat / Grade -->
                <div>
                    <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">
                        Tingkat / Fase <span class="text-error font-bold">*</span>
                    </label>
                    <select name="grade" class="w-full px-3 py-2.5 rounded-lg bg-surface-container-low font-body-sm text-body-sm text-on-surface border border-slate-200 outline-none focus:ring-2 focus:ring-primary-container cursor-pointer" required>
                        <option value="10">Fase E (Kelas X)</option>
                        <option value="11">Fase F (Kelas XI)</option>
                        <option value="12">Fase F (Kelas XII)</option>
                    </select>
                </div>

                <!-- Jurusan / Major -->
                <div>
                    <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">
                        Jurusan / Peminatan
                    </label>
                    <input 
                        name="major" 
                        class="w-full px-3.5 py-2.5 rounded-lg bg-surface-container-low font-body-sm text-body-sm text-on-surface border border-slate-200 outline-none focus:ring-2 focus:ring-primary-container" 
                        placeholder="Contoh: MIPA, IPS, Umum" 
                        type="text" 
                        value="MIPA"
                    />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <!-- Kapasitas Maksimal -->
                <div>
                    <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">
                        Kapasitas Maksimal
                    </label>
                    <input 
                        name="capacity" 
                        class="w-full px-3.5 py-2.5 rounded-lg bg-surface-container-low font-body-sm text-body-sm text-on-surface border border-slate-200 outline-none focus:ring-2 focus:ring-primary-container" 
                        type="number" 
                        value="36" 
                        min="1" 
                        max="60"
                    />
                </div>

                <!-- Tahun Ajaran -->
                <div>
                    <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">
                        Tahun Ajaran <span class="text-error font-bold">*</span>
                    </label>
                    <select name="academic_year_id" class="w-full px-3 py-2.5 rounded-lg bg-surface-container-low font-body-sm text-body-sm text-on-surface border border-slate-200 outline-none focus:ring-2 focus:ring-primary-container cursor-pointer" required>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $year->is_active ? 'selected' : '' }}>
                                {{ $year->name }} - Smt {{ $year->semester == 1 ? 'Ganjil' : 'Genap' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Wali Kelas Terpilih -->
            <div>
                <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">
                    Wali Kelas Pengampu
                </label>
                <select name="homeroom_teacher_id" class="w-full px-3 py-2.5 rounded-lg bg-surface-container-low font-body-sm text-body-sm text-on-surface border border-slate-200 outline-none focus:ring-2 focus:ring-primary-container cursor-pointer">
                    <option value="">-- Pilih Guru Pengampu (Opsional) --</option>
                    @foreach($teachers as $t)
                        <option value="{{ $t->id }}">{{ $t->name }} (NIP: {{ $t->nip ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button class="px-4 py-2 rounded-lg bg-surface-container text-on-surface font-label-lg text-label-lg hover:bg-surface-container-high transition-colors cursor-pointer" onclick="document.getElementById('modalTambahKelas').classList.add('hidden')" type="button">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 rounded-lg bg-primary-container hover:bg-primary text-on-primary font-label-lg text-label-lg shadow-sm font-bold transition-all cursor-pointer">
                    Simpan Kelas
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDeleteModal(classId, className, studentCount) {
        document.getElementById('targetClassName').innerText = className;
        const deleteForm = document.getElementById('deleteClassForm');
        deleteForm.action = "{{ url('admin/kelas') }}/" + classId;

        const warningEl = document.getElementById('deleteWarningStudents');
        if (studentCount > 0) {
            document.getElementById('targetClassStudentCount').innerText = studentCount;
            warningEl.classList.remove('hidden');
        } else {
            warningEl.classList.add('hidden');
        }

        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection
