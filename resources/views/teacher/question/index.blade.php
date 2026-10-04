@extends('layouts.teacher')

@section('title', 'Bank Soal Pembelajaran — EduExam')
@section('page_title', 'Bank Soal Pembelajaran')

@section('teacher-content')
<div class="flex flex-col w-full pb-space-xl">
    <!-- Top Banner / Header -->
    <div class="relative overflow-hidden rounded-2xl bg-surface-container-lowest p-space-lg mb-space-lg shadow-sm border border-slate-100">
        <div class="absolute -top-12 -right-12 w-64 h-64 bg-primary/5 rounded-full pointer-events-none blur-2xl"></div>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-space-md relative z-10">
            <div class="flex flex-col gap-space-xs">
                <div class="flex items-center gap-space-xs">
                    <span class="px-space-xs py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-label-sm font-semibold">Modul Evaluasi Guru</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant">• Standar Kurikulum Merdeka</span>
                </div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">Bank Soal Pembelajaran</h1>
                <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">Koleksi paket dan butir soal terstruktur berdasarkan Mata Pelajaran dan Kelas Sasaran untuk evaluasi siswa.</p>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-space-sm">
                <button type="button" onclick="alert('Fitur Impor Soal format Excel/Word template sedang disiapkan. Gunakan Tambah Soal Baru untuk saat ini.')" class="inline-flex items-center gap-space-xs px-space-md py-2.5 rounded-xl bg-surface-container-low text-on-surface font-label-lg text-label-lg hover:bg-surface-container-high transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[20px] text-secondary">upload_file</span>
                    <span>Impor Soal Excel / Word</span>
                </button>
                <a href="{{ route('teacher.questions.create') }}" class="inline-flex items-center gap-space-xs px-space-md py-2.5 rounded-xl bg-primary text-white font-label-lg text-label-lg hover:bg-primary-dark active:scale-95 transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                    <span>+ Tambah Soal Baru</span>
                </a>
            </div>
        </div>

        <!-- Quick Stats Bento Overview -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-space-md mt-space-lg pt-space-md bg-surface-container-low/60 p-space-md rounded-xl">
            <div class="flex items-center gap-space-sm">
                <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[22px]">folder_special</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-headline-sm text-headline-sm text-on-surface font-bold">{{ $packages->count() }} Paket</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Kelompok Mapel &amp; Kelas</span>
                </div>
            </div>
            <div class="flex items-center gap-space-sm">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-700">
                    <span class="material-symbols-outlined text-[22px]">quiz</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-headline-sm text-headline-sm text-on-surface font-bold">{{ $stats['total'] ?? $allQuestions->count() }}</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Total Butir Soal</span>
                </div>
            </div>
            <div class="flex items-center gap-space-sm">
                <div class="w-10 h-10 rounded-xl bg-secondary-container flex items-center justify-center text-on-secondary-container">
                    <span class="material-symbols-outlined text-[22px]">check_circle</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-headline-sm text-headline-sm text-on-surface font-bold">{{ $stats['multiple_choice'] ?? 0 }}</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Pilihan Ganda</span>
                </div>
            </div>
            <div class="flex items-center gap-space-sm">
                <div class="w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center text-on-surface">
                    <span class="material-symbols-outlined text-[22px]">auto_stories</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-headline-sm text-headline-sm text-on-surface font-bold">{{ $stats['active_in_exams'] ?? 0 }} Paket</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Aktif di Ujian</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Toolbar -->
    <div class="flex flex-col gap-space-md p-space-md rounded-2xl bg-surface-container-lowest shadow-sm mb-space-lg border border-slate-100">
        <form action="{{ route('teacher.questions.index') }}" method="GET" class="flex flex-col gap-space-md" id="filterForm">
            <!-- Main Search -->
            <div class="relative w-full">
                <span class="material-symbols-outlined absolute left-space-md top-1/2 -translate-y-1/2 text-[22px] text-on-surface-variant">search</span>
                <input name="search" value="{{ request('search') }}" class="w-full h-12 pl-11 pr-space-md bg-surface-container-low rounded-xl font-body-md text-body-md text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm transition-all" placeholder="Cari nama soal, materi/topik, mata pelajaran, atau kelas sasaran..." type="text"/>
            </div>

            <!-- Filter Multi-Select Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-sm items-end">
                <!-- Mata Pelajaran -->
                <div class="flex flex-col gap-1">
                    <label class="font-label-sm text-label-sm text-on-surface-variant font-medium">Mata Pelajaran</label>
                    <div class="relative">
                        <select name="subject_id" onchange="this.form.submit()" class="w-full h-10 px-space-sm pr-8 bg-surface-container-low rounded-xl font-body-sm text-body-sm text-on-surface appearance-none focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm cursor-pointer">
                            <option value="">Semua Mata Pelajaran</option>
                            @foreach($subjects as $sub)
                                <option value="{{ $sub->id }}" {{ request('subject_id') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-[18px] text-on-surface-variant">expand_more</span>
                    </div>
                </div>

                <!-- Sasaran Kelas -->
                <div class="flex flex-col gap-1">
                    <label class="font-label-sm text-label-sm text-on-surface-variant font-medium">Target Kelas</label>
                    <div class="relative">
                        <select name="classroom_id" onchange="this.form.submit()" class="w-full h-10 px-space-sm pr-8 bg-surface-container-low rounded-xl font-body-sm text-body-sm text-on-surface appearance-none focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm cursor-pointer">
                            <option value="">Semua Kelas</option>
                            <option value="all" {{ request('classroom_id') === 'all' ? 'selected' : '' }}>Umum (Lintas Kelas)</option>
                            @foreach($classrooms as $cls)
                                <option value="{{ $cls->id }}" {{ request('classroom_id') == $cls->id ? 'selected' : '' }}>Kelas {{ $cls->name }} (Tingkat {{ $cls->grade }})</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-[18px] text-on-surface-variant">expand_more</span>
                    </div>
                </div>

                <!-- Tingkat Kesulitan -->
                <div class="flex flex-col gap-1">
                    <label class="font-label-sm text-label-sm text-on-surface-variant font-medium">Tingkat Kesulitan</label>
                    <div class="relative">
                        <select name="difficulty" onchange="this.form.submit()" class="w-full h-10 px-space-sm pr-8 bg-surface-container-low rounded-xl font-body-sm text-body-sm text-on-surface appearance-none focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm cursor-pointer">
                            <option value="">Semua Kesulitan</option>
                            <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>Mudah</option>
                            <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>Sedang</option>
                            <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>Sulit / HOTS</option>
                        </select>
                        <span class="material-symbols-outlined pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-[18px] text-on-surface-variant">expand_more</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-space-xs">
                    <button type="submit" class="flex-1 h-10 inline-flex items-center justify-center gap-1.5 px-space-md bg-primary text-white font-label-md text-label-md rounded-xl shadow-sm hover:bg-primary-dark transition-all">
                        <span class="material-symbols-outlined text-[18px]">filter_list</span>
                        <span>Terapkan</span>
                    </button>
                    @if(request('search') || request('subject_id') || request('classroom_id') || request('type') || request('difficulty'))
                        <a href="{{ route('teacher.questions.index') }}" class="h-10 px-space-md inline-flex items-center justify-center text-on-surface-variant hover:text-error bg-surface-container-low rounded-xl font-label-md text-label-md transition-colors" title="Reset Filter">
                            <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- ════════════════════════════════════════════════════════════════════════ -->
    <!-- VIEW 1: DAFTAR KARTU PAKET SOAL (Tampilan Utama: Mapel & Kelas yang Dituju) -->
    <!-- ════════════════════════════════════════════════════════════════════════ -->
    <div id="packagesGridView" class="flex flex-col gap-space-md transition-opacity duration-200">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-2">
            <div>
                <h2 class="font-headline-sm text-headline-sm font-bold text-on-surface">Katalog Paket Soal Pembelajaran</h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Klik kartu paket atau tombol <b>Lihat Detail Soal</b> untuk membuka butir-butir soal lengkap beserta kunci jawabannya.</p>
            </div>
            <span class="self-start sm:self-auto px-3 py-1 rounded-full bg-surface-container-high text-on-surface font-label-sm text-label-sm font-semibold">
                {{ $packages->count() }} Paket Soal Tersedia
            </span>
        </div>

        @if($packages->isEmpty())
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center p-12 bg-surface-container-lowest rounded-2xl border border-slate-100 text-center shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-surface-container-low flex items-center justify-center text-primary mb-3">
                    <span class="material-symbols-outlined text-4xl">folder_off</span>
                </div>
                <h3 class="font-headline-sm text-headline-sm text-on-surface mb-1 font-bold">Tidak Ada Paket Soal Ditemukan</h3>
                <p class="font-body-md text-body-md text-on-surface-variant max-w-md mb-4">
                    @if(request('search') || request('subject_id') || request('classroom_id') || request('difficulty'))
                        Tidak ada butir soal yang sesuai dengan filter pencarian Anda. Silakan coba atur ulang filter pencarian.
                    @else
                        Bank soal Anda masih kosong. Silakan buat butir soal pertama untuk mata pelajaran dan kelas Anda.
                    @endif
                </p>
                <div class="flex items-center gap-2">
                    @if(request('search') || request('subject_id') || request('classroom_id') || request('difficulty'))
                        <a href="{{ route('teacher.questions.index') }}" class="px-space-md py-2.5 rounded-xl bg-surface-container-low text-on-surface font-label-lg text-label-lg hover:bg-surface-container-high transition-colors">
                            Reset Filter
                        </a>
                    @endif
                    <a href="{{ route('teacher.questions.create') }}" class="inline-flex items-center gap-1.5 px-space-md py-2.5 rounded-xl bg-primary text-white font-label-lg text-label-lg hover:bg-primary-dark shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        <span>Buat Soal Baru</span>
                    </a>
                </div>
            </div>
        @else
            <!-- Grid of Cards (Menampilkan Nama Soal/Topik, Mapel, dan Kelas yang Dituju) -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-space-lg">
                @foreach($packages as $pkg)
                    <div onclick="openPackageDetail('{{ $pkg->key }}')" 
                         id="card-{{ $pkg->key }}"
                         class="group relative bg-surface-container-lowest rounded-2xl p-6 border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-primary/40 hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between cursor-pointer">
                        
                        <!-- Top Accent Bar -->
                        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-primary to-indigo-400 rounded-t-2xl"></div>

                        <!-- Card Header -->
                        <div class="flex flex-col gap-3">
                            <div class="flex items-start justify-between gap-2">
                                <!-- Subject Badge & Icon -->
                                <div class="flex items-center gap-2">
                                    <div class="w-10 h-10 rounded-xl bg-primary-fixed/40 text-primary flex items-center justify-center font-bold">
                                        @if(str_contains(strtolower($pkg->subject_name), 'matematika'))
                                            <span class="material-symbols-outlined text-[22px]">calculate</span>
                                        @elseif(str_contains(strtolower($pkg->subject_name), 'ipa') || str_contains(strtolower($pkg->subject_name), 'alam'))
                                            <span class="material-symbols-outlined text-[22px]">science</span>
                                        @elseif(str_contains(strtolower($pkg->subject_name), 'indonesia') || str_contains(strtolower($pkg->subject_name), 'inggris'))
                                            <span class="material-symbols-outlined text-[22px]">translate</span>
                                        @else
                                            <span class="material-symbols-outlined text-[22px]">menu_book</span>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-label-sm text-label-sm text-on-surface-variant font-medium">Mata Pelajaran</span>
                                        <span class="font-label-lg text-label-lg font-bold text-primary">{{ $pkg->subject_name }}</span>
                                    </div>
                                </div>

                                <!-- Target Classroom Badge (Kelas yang Dituju) -->
                                <div class="flex items-center gap-1 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200/80 font-label-sm text-label-sm font-bold shadow-xs">
                                    <span class="material-symbols-outlined text-[16px] text-emerald-600">school</span>
                                    <span>{{ $pkg->classroom_name }}</span>
                                </div>
                            </div>

                            <!-- Package Title (Nama Soal / Topik Paket) -->
                            <div class="mt-2">
                                <span class="text-[11px] font-semibold tracking-wider uppercase text-on-surface-variant">Topik / Nama Soal:</span>
                                <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface group-hover:text-primary transition-colors line-clamp-2 mt-0.5 leading-snug">
                                    {{ $pkg->title }}
                                </h3>
                            </div>

                            <!-- Summary Info Bento Box -->
                            <div class="grid grid-cols-2 gap-2 mt-2 pt-2 border-t border-slate-100">
                                <div class="flex items-center gap-2 p-2 rounded-xl bg-surface-container-low/70">
                                    <span class="material-symbols-outlined text-[18px] text-primary">quiz</span>
                                    <div class="flex flex-col min-w-0">
                                        <span class="font-label-sm text-label-sm text-on-surface-variant leading-none">Jumlah Soal</span>
                                        <span class="font-label-md text-label-md font-bold text-on-surface mt-0.5">{{ $pkg->total_questions }} Butir</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 p-2 rounded-xl bg-surface-container-low/70">
                                    <span class="material-symbols-outlined text-[18px] text-amber-600">award_star</span>
                                    <div class="flex flex-col min-w-0">
                                        <span class="font-label-sm text-label-sm text-on-surface-variant leading-none">Total Bobot</span>
                                        <span class="font-label-md text-label-md font-bold text-on-surface mt-0.5">{{ $pkg->total_score }} Poin</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Detail Chips -->
                            <div class="flex flex-wrap items-center gap-1.5 text-xs text-on-surface-variant">
                                <span class="px-2 py-0.5 rounded-md bg-surface-container text-on-surface-variant font-medium">
                                    {{ $pkg->multiple_choice_count }} Pilihan Ganda
                                </span>
                                @if($pkg->essay_count > 0)
                                    <span class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-800 font-medium">
                                        {{ $pkg->essay_count }} Isian/Esai
                                    </span>
                                @endif
                                @if($pkg->exams_count > 0)
                                    <span class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 font-medium truncate max-w-full" title="{{ $pkg->exams->pluck('title')->join(', ') }}">
                                        Dipakai: {{ $pkg->exams->first()->title }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer / Action Button -->
                        <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                            <button type="button" 
                                    onclick="event.stopPropagation(); openPackageDetail('{{ $pkg->key }}')"
                                    class="w-full py-2.5 px-4 rounded-xl bg-primary text-white font-label-md text-label-md font-semibold flex items-center justify-center gap-2 shadow-sm group-hover:bg-primary-dark group-hover:shadow transition-all active:scale-[0.98]">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                                <span>Lihat Detail Soal ({{ $pkg->total_questions }})</span>
                                <span class="material-symbols-outlined text-[18px] transition-transform group-hover:translate-x-1">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- ════════════════════════════════════════════════════════════════════════ -->
    <!-- VIEW 2: TAMPILAN SOAL-SOAL LENGKAP MAPEL TERSEBUT (Detail View)          -->
    <!-- ════════════════════════════════════════════════════════════════════════ -->
    <div id="packageDetailView" class="hidden flex-col gap-space-md transition-opacity duration-200">
        
        @foreach($packages as $pkg)
            <div id="detail-{{ $pkg->key }}" class="package-detail-container hidden flex-col gap-space-md">
                
                <!-- Sticky / Top Navigation Bar for Selected Package -->
                <div class="rounded-2xl bg-surface-container-lowest p-space-md shadow-sm border border-slate-200 flex flex-col lg:flex-row lg:items-center justify-between gap-space-md sticky top-4 z-20">
                    <div class="flex items-center gap-space-sm flex-wrap">
                        <!-- Tombol Kembali -->
                        <button type="button" onclick="closePackageDetail()" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-surface-container text-on-surface hover:bg-surface-container-high transition-colors font-label-md text-label-md font-semibold border border-slate-200 shadow-xs active:scale-95">
                            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                            <span>Kembali ke Daftar Paket</span>
                        </button>

                        <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

                        <!-- Current Package Badges -->
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl bg-primary-fixed/40 text-primary font-label-md text-label-md font-bold">
                                <span class="material-symbols-outlined text-[18px]">menu_book</span>
                                <span>{{ $pkg->subject_name }}</span>
                            </span>
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 font-label-md text-label-md font-bold">
                                <span class="material-symbols-outlined text-[18px]">school</span>
                                <span>{{ $pkg->classroom_name }}</span>
                            </span>
                            <span class="px-2.5 py-1 rounded-lg bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-semibold">
                                {{ $pkg->total_questions }} Butir Soal Lengkap
                            </span>
                        </div>
                    </div>

                    <!-- Right Search & Add Question in this Package -->
                    <div class="flex items-center gap-space-xs flex-wrap">
                        <div class="relative w-full sm:w-64">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-on-surface-variant">search</span>
                            <input type="text" 
                                   oninput="filterQuestionsInPackage('{{ $pkg->key }}', this.value)" 
                                   placeholder="Cari butir soal di paket ini..." 
                                   class="w-full h-10 pl-9 pr-3 rounded-xl bg-surface-container-low text-body-sm text-on-surface placeholder:text-on-surface-variant border border-transparent focus:border-indigo-300 focus:bg-surface-container-lowest outline-none transition-all shadow-xs">
                        </div>

                        <a href="{{ route('teacher.questions.create', ['subject_id' => $pkg->subject_id, 'classroom_id' => $pkg->classroom_id, 'topic' => $pkg->topic]) }}" 
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-primary text-white font-label-md text-label-md font-semibold hover:bg-primary-dark transition-all shadow-xs shrink-0">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                            <span>+ Tambah Soal</span>
                        </a>
                    </div>
                </div>

                <!-- Package Info Card Overview -->
                <div class="rounded-2xl bg-gradient-to-r from-primary-fixed/20 via-surface-container-lowest to-surface-container-lowest p-space-md border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-space-md">
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-primary text-white font-label-sm text-label-sm font-semibold">Daftar Soal Aktif</span>
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Topik Pembelajaran:</span>
                        </div>
                        <h2 class="font-headline-sm text-headline-sm font-bold text-on-surface">{{ $pkg->title }}</h2>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">
                            Mata Pelajaran: <b class="text-on-surface">{{ $pkg->subject_name }}</b> &bull; 
                            Target Kelas: <b class="text-on-surface">{{ $pkg->classroom_name }}</b> &bull; 
                            Total Nilai Maksimal: <b class="text-secondary">{{ $pkg->total_score }} Poin</b>
                        </p>
                    </div>
                    <div class="flex items-center gap-2 self-start md:self-auto">
                        <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-surface-container-low text-on-surface hover:bg-surface-container transition-colors text-xs font-semibold border border-slate-200">
                            <span class="material-symbols-outlined text-[16px]">print</span>
                            <span>Cetak Soal</span>
                        </button>
                        <button type="button" onclick="closePackageDetail()" class="inline-flex items-center gap-1 px-3 py-2 rounded-xl bg-surface-container-high text-on-surface hover:bg-slate-300 transition-colors text-xs font-semibold">
                            <span class="material-symbols-outlined text-[16px]">close</span>
                            <span>Tutup Detail</span>
                        </button>
                    </div>
                </div>

                <!-- LIST OF QUESTIONS (IDENTIK SEPERTI GAMBAR USER LENGKAP KUNCI JAWABAN) -->
                <div class="flex flex-col gap-space-md" id="question-list-{{ $pkg->key }}">
                    @foreach($pkg->questions as $index => $question)
                        <div class="question-item-card flex flex-col rounded-2xl bg-surface-container-lowest p-space-md shadow-sm border border-slate-200/90 transition-all hover:shadow-md"
                             data-text="{{ strtolower($question->question_text . ' ' . $question->topic . ' ' . ($question->options->pluck('option_text')->join(' '))) }}">
                            
                            <!-- Card Header (Soal #, Badges, Action Icons) -->
                            <div class="flex flex-wrap items-center justify-between gap-space-xs pb-space-sm border-b border-surface-container">
                                <div class="flex flex-wrap items-center gap-space-xs">
                                    <span class="px-2.5 py-1 rounded-lg bg-surface-container-high font-label-md text-label-md text-on-surface font-bold">
                                        Soal #{{ $question->id }}
                                    </span>
                                    
                                    @if($question->type === 'multiple_choice')
                                        <span class="px-2.5 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-label-sm font-semibold">Pilihan Ganda</span>
                                    @elseif($question->type === 'true_false')
                                        <span class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800 font-label-sm text-label-sm font-semibold">Benar / Salah</span>
                                    @elseif($question->type === 'short_answer')
                                        <span class="px-2.5 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">Isian Singkat</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full bg-tertiary-fixed text-on-tertiary-fixed font-label-sm text-label-sm font-semibold">Uraian / Esai</span>
                                    @endif

                                    @if($question->difficulty === 'easy')
                                        <span class="px-2.5 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed font-label-sm text-label-sm font-semibold">Kesulitan: Mudah</span>
                                    @elseif($question->difficulty === 'medium')
                                        <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 font-label-sm text-label-sm font-semibold">Kesulitan: Sedang</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full bg-error-container text-on-error-container font-label-sm text-label-sm font-semibold">Kesulitan: Sulit (HOTS)</span>
                                    @endif

                                    <span class="px-2.5 py-0.5 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-semibold">
                                        Bobot: {{ $question->score }} Poin
                                    </span>
                                </div>

                                <!-- Action Buttons (Edit, Duplicate, Delete) -->
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('teacher.questions.edit', $question) }}" class="p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-high hover:text-primary transition-colors" title="Edit Soal">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                    <form action="{{ route('teacher.questions.duplicate', $question) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-high hover:text-secondary transition-colors" title="Duplikat Soal">
                                            <span class="material-symbols-outlined text-[20px]">content_copy</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('teacher.questions.destroy', $question) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus butir soal ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-on-surface-variant hover:bg-error-container hover:text-error transition-colors" title="Hapus Soal">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Question Meta & Topic -->
                            <div class="flex flex-wrap items-center gap-space-xs pt-space-sm text-on-surface-variant">
                                <span class="font-label-sm text-label-sm font-bold text-primary">{{ $pkg->subject_name }}</span>
                                <span>•</span>
                                <span class="font-label-sm text-label-sm">Topik: {{ $question->topic ?: $pkg->title }}</span>
                                <span>•</span>
                                <span class="font-label-sm text-label-sm text-emerald-700 font-semibold">{{ $pkg->classroom_name }}</span>
                            </div>

                            <!-- Question Body -->
                            <div class="py-space-sm">
                                <div class="font-body-lg text-body-lg text-on-surface font-semibold leading-relaxed">
                                    {!! nl2br(e(strip_tags($question->question_text))) !!}
                                </div>
                                @if($question->question_image)
                                    <div class="my-3 p-2 bg-slate-50 border border-slate-200/80 rounded-2xl inline-block max-w-full shadow-xs">
                                        <img src="{{ $question->image_url }}" 
                                             alt="Lampiran Soal #{{ $question->id }}" 
                                             class="max-h-80 max-w-full rounded-xl object-contain bg-white cursor-pointer hover:opacity-95 transition-opacity"
                                             onclick="window.open(this.src, '_blank')"
                                             title="Klik untuk memperbesar gambar">
                                        <div class="mt-1.5 flex items-center justify-between text-[11px] text-slate-500 px-1 gap-3">
                                            <span class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">image</span>
                                                <span>Lampiran Gambar Soal</span>
                                            </span>
                                            <a href="{{ $question->image_url }}" target="_blank" class="text-primary hover:underline font-semibold flex items-center gap-0.5">
                                                <span>Buka Ukuran Asli</span>
                                                <span class="material-symbols-outlined text-[13px]">open_in_new</span>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Options / Answers Preview (Pilihan Jawaban + Kunci) -->
                            @if(in_array($question->type, ['multiple_choice', 'true_false']) && $question->options->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-sm pt-space-xs">
                                    @foreach($question->options as $opt)
                                        @if($opt->is_correct)
                                            <!-- Pilihan Jawaban KUNCI (Hijau sesuai screenshot) -->
                                            <div class="flex items-center justify-between gap-space-xs p-2.5 rounded-xl bg-emerald-50 text-emerald-950 font-semibold border border-emerald-300 shadow-xs">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center font-label-sm text-label-sm font-bold shrink-0">
                                                        {{ $opt->label ?? chr(65 + $loop->index) }}
                                                    </span>
                                                    <span class="font-body-md text-body-md truncate">{{ $opt->option_text }}</span>
                                                </div>
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-700 text-white font-label-sm text-label-sm font-bold shrink-0 shadow-xs">
                                                    <span class="material-symbols-outlined text-[14px]">check</span>
                                                    <span>Kunci</span>
                                                </span>
                                            </div>
                                        @else
                                            <!-- Pilihan Jawaban Reguler -->
                                            <div class="flex items-center gap-2 p-2.5 rounded-xl bg-surface-container-low text-on-surface border border-slate-100">
                                                <span class="w-6 h-6 rounded-full bg-surface-container-high text-on-surface-variant flex items-center justify-center font-label-sm text-label-sm font-bold shrink-0">
                                                    {{ $opt->label ?? chr(65 + $loop->index) }}
                                                </span>
                                                <span class="font-body-md text-body-md truncate">{{ $opt->option_text }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @elseif($question->type === 'short_answer')
                                <div class="flex flex-wrap items-center gap-space-sm pt-space-xs">
                                    <div class="flex items-center gap-space-xs px-space-md py-2 rounded-xl bg-surface-container-low border border-slate-100">
                                        <span class="font-label-sm text-label-sm text-on-surface-variant">Kunci Jawaban Definitif:</span>
                                        <span class="font-label-lg text-label-lg font-bold text-secondary">{{ $question->explanation ?: '(Tersimpan di sistem penilaian otomatis)' }}</span>
                                    </div>
                                </div>
                            @elseif($question->type === 'essay')
                                <div class="flex flex-wrap items-center gap-space-sm pt-space-xs">
                                    <div class="flex items-center gap-space-xs px-space-md py-2 rounded-xl bg-surface-container-low border border-slate-100">
                                        <span class="material-symbols-outlined text-[18px] text-primary">rule</span>
                                        <span class="font-label-sm text-label-sm text-on-surface-variant">Panduan Rubrik Penilaian: {{ Str::limit($question->explanation ?: 'Penilaian kualitatif berjenjang oleh guru pengampu', 90) }}</span>
                                    </div>
                                </div>
                            @endif

                            <!-- Footer Usage Info -->
                            <div class="mt-space-md pt-space-xs flex flex-wrap items-center justify-between gap-2 text-on-surface-variant border-t border-slate-100">
                                <div class="flex items-center gap-1.5">
                                    @if($question->exams && $question->exams->count() > 0)
                                        <span class="material-symbols-outlined text-[16px] text-primary">history_edu</span>
                                        <span class="font-label-sm text-label-sm">Digunakan di {{ $question->exams->count() }} Ujian ({{ $question->exams->pluck('title')->take(2)->join(', ') }}{{ $question->exams->count() > 2 ? ' ...' : '' }})</span>
                                    @else
                                        <span class="material-symbols-outlined text-[16px] text-amber-500">warning</span>
                                        <span class="font-label-sm text-label-sm">Belum pernah dipakai pada paket ujian manapun</span>
                                    @endif
                                </div>
                                <span class="font-label-sm text-label-sm">Terakhir diperbarui: {{ $question->updated_at ? $question->updated_at->diffForHumans() : 'Baru saja' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Bottom Back Button -->
                <div class="flex items-center justify-between p-space-md rounded-2xl bg-surface-container-lowest border border-slate-200 mt-space-md">
                    <button type="button" onclick="closePackageDetail()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-surface-container-high text-on-surface hover:bg-slate-200 transition-colors font-label-md text-label-md font-semibold">
                        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                        <span>Kembali ke Daftar Paket Soal</span>
                    </button>
                    <button type="button" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="inline-flex items-center gap-1 text-primary hover:underline text-xs font-semibold">
                        <span class="material-symbols-outlined text-[16px]">arrow_upward</span>
                        <span>Kembali ke Atas</span>
                    </button>
                </div>

            </div>
        @endforeach

    </div>
</div>

<!-- Interactive Client-side Script for Seamless Card to Detail Reveal -->
<script>
    function openPackageDetail(pkgKey) {
        const gridView = document.getElementById('packagesGridView');
        const detailView = document.getElementById('packageDetailView');
        
        // Sembunyikan semua container detail lain
        document.querySelectorAll('.package-detail-container').forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('flex');
        });

        // Buka container spesifik untuk paket ini
        const targetContainer = document.getElementById('detail-' + pkgKey);
        if (targetContainer) {
            gridView.classList.add('hidden');
            detailView.classList.remove('hidden');
            detailView.classList.add('flex');
            targetContainer.classList.remove('hidden');
            targetContainer.classList.add('flex');

            // Update URL hash
            window.location.hash = pkgKey;

            // Scroll ke atas dengan halus
            window.scrollTo({ top: 180, behavior: 'smooth' });
        }
    }

    function closePackageDetail() {
        const gridView = document.getElementById('packagesGridView');
        const detailView = document.getElementById('packageDetailView');

        detailView.classList.add('hidden');
        detailView.classList.remove('flex');
        gridView.classList.remove('hidden');

        // Bersihkan hash di URL tanpa reload
        history.pushState("", document.title, window.location.pathname + window.location.search);
        
        // Scroll kembali ke katalog kartu
        window.scrollTo({ top: 180, behavior: 'smooth' });
    }

    function filterQuestionsInPackage(pkgKey, query) {
        const list = document.getElementById('question-list-' + pkgKey);
        if (!list) return;

        const q = query.toLowerCase().trim();
        const cards = list.querySelectorAll('.question-item-card');

        cards.forEach(card => {
            const dataText = card.getAttribute('data-text') || '';
            if (q === '' || dataText.includes(q)) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    }

    function findMatchingPackageKey(key) {
        if (!key) return null;
        if (document.getElementById('detail-' + key)) return key;

        // Cari dengan prefix matching (misal pkg_1_3_2dd6f37f cocok dengan pkg_1_3 atau sebaliknya)
        const containers = document.querySelectorAll('.package-detail-container');
        for (const c of containers) {
            const cKey = c.id.replace('detail-', '');
            if (key.startsWith(cKey) || cKey.startsWith(key)) {
                return cKey;
            }
        }
        return null;
    }

    // Auto-buka paket jika ada hash di URL atau query parameter 'openPackageKey'
    document.addEventListener('DOMContentLoaded', () => {
        const initialPkgKey = '{{ $openPackageKey ?? "" }}';
        const hash = window.location.hash ? window.location.hash.replace('#', '') : '';

        const matched = findMatchingPackageKey(hash) || findMatchingPackageKey(initialPkgKey);
        if (matched) {
            openPackageDetail(matched);
        }
    });

    // Dukungan tombol Back pada browser
    window.addEventListener('popstate', () => {
        const hash = window.location.hash ? window.location.hash.replace('#', '') : '';
        const matched = findMatchingPackageKey(hash);
        if (matched) {
            openPackageDetail(matched);
        } else {
            closePackageDetail();
        }
    });
</script>
@endsection
