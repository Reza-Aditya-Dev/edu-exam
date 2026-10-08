@extends('layouts.teacher')

@section('title', 'Buat & Jadwalkan Sesi Ujian — EduExam')
@section('page_title', 'Manajemen Ujian')

@section('teacher-content')
<div class="flex flex-col w-full pb-28">

    <!-- Top Breadcrumb & Page Actions Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-space-md mb-space-lg">
        <div class="flex flex-col">
            <div class="flex items-center gap-space-xs text-on-surface-variant font-label-sm text-label-sm mb-1">
                <a class="hover:text-primary transition-colors" href="{{ route('teacher.exams.index') }}">Manajemen Ujian</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-semibold">Buat Ujian Baru</span>
            </div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight flex items-center gap-space-sm font-bold">
                <span>Buat &amp; Jadwalkan Sesi Ujian</span>
                <span class="px-space-sm py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed font-label-sm text-label-sm font-semibold">Mode Draf Aktif</span>
            </h1>
        </div>
        <!-- Top Action Buttons -->
        <div class="flex items-center gap-space-sm self-start md:self-auto">
            <button type="button" onclick="submitExam('draft')" class="inline-flex items-center gap-space-xs h-10 px-space-md rounded-lg bg-surface-container-lowest text-on-surface font-label-lg text-label-lg shadow-sm border border-slate-200/80 hover:bg-surface-container-high transition-all">
                <span class="material-symbols-outlined text-[18px]">bookmark_border</span>
                <span>Simpan Draft</span>
            </button>
            <button type="button" onclick="submitExam('publish')" class="inline-flex items-center gap-space-xs h-10 px-space-lg rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg shadow-md hover:opacity-95 transition-all">
                <span class="material-symbols-outlined text-[18px]">rocket_launch</span>
                <span>Terbitkan Ujian</span>
            </button>
        </div>
    </div>

    <!-- Main Bento Form Layout: 12 Columns -->
    <form action="{{ route('teacher.exams.store') }}" method="POST" id="examForm" class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-start">
        @csrf
        <input type="hidden" name="action" id="formAction" value="draft">

        <!-- LEFT 8 COLUMNS: Informasi Utama & Konfigurasi Soal -->
        <div class="lg:col-span-8 flex flex-col gap-space-lg">

            <!-- 1. INFORMASI & JADWAL UJIAN -->
            <section class="rounded-xl bg-surface-container-lowest p-space-lg shadow-sm border border-slate-100 flex flex-col gap-space-md relative overflow-hidden">
                <div class="absolute -right-12 -top-12 w-44 h-44 rounded-full bg-primary-fixed/20 blur-2xl pointer-events-none"></div>
                <div class="flex items-center justify-between pb-space-xs relative z-10">
                    <div class="flex items-center gap-space-sm">
                        <span class="w-8 h-8 rounded-lg bg-primary-fixed text-on-primary-fixed flex items-center justify-center font-label-lg text-label-lg font-bold">1</span>
                        <div class="flex flex-col">
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Informasi &amp; Jadwal Ujian</h2>
                            <span class="font-body-sm text-body-sm text-on-surface-variant">Lengkapi metadata dasar mata pelajaran dan alokasi waktu</span>
                        </div>
                    </div>
                    <span class="px-space-sm py-1 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm font-semibold">Wajib Lengkap</span>
                </div>

                <!-- Form fields grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md pt-space-xs relative z-10">
                    <!-- Nama Ujian (Full Width) -->
                    <div class="md:col-span-2 flex flex-col gap-1.5">
                        <label class="font-label-lg text-label-lg text-on-surface flex items-center justify-between">
                            <span>Nama Ujian <span class="text-error">*</span></span>
                            <span class="font-label-sm text-label-sm text-on-surface-variant font-normal">Maks. 80 Karakter</span>
                        </label>
                        <input name="title" id="examTitle" class="h-12 px-space-md rounded-lg bg-surface-container-low font-body-md text-body-md text-on-surface focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm transition-all" type="text" placeholder="Contoh: UTS Matematika Wajib Semester Ganjil 2026" value="{{ old('title') }}" required oninput="updateSummary()"/>
                        @error('title') <span class="text-xs text-error font-medium mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <!-- Mata Pelajaran -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-lg text-label-lg text-on-surface">Mata Pelajaran <span class="text-error">*</span></label>
                        <div class="relative flex items-center">
                            <select name="subject_id" id="subjectFilter" class="w-full h-12 pl-space-md pr-10 rounded-lg bg-surface-container-low font-body-md text-body-md text-on-surface appearance-none focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm transition-all cursor-pointer" required onchange="handleSubjectChange()">
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-space-md pointer-events-none text-on-surface-variant text-[20px]">expand_more</span>
                        </div>
                        @error('subject_id') <span class="text-xs text-error font-medium mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tipe Ujian -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-lg text-label-lg text-on-surface">Tipe Ujian <span class="text-error">*</span></label>
                        <div class="relative flex items-center">
                            <select name="exam_type" id="examType" class="w-full h-12 pl-space-md pr-10 rounded-lg bg-surface-container-low font-body-md text-body-md text-on-surface appearance-none focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm transition-all cursor-pointer" required>
                                <option value="UTS" {{ old('exam_type', 'UTS') == 'UTS' ? 'selected' : '' }}>UTS (Ujian Tengah Semester)</option>
                                <option value="UAS" {{ old('exam_type') == 'UAS' ? 'selected' : '' }}>UAS (Ujian Akhir Semester)</option>
                                <option value="UH" {{ old('exam_type') == 'UH' ? 'selected' : '' }}>UH (Ulangan Harian)</option>
                                <option value="Quiz" {{ old('exam_type') == 'Quiz' ? 'selected' : '' }}>Quiz / Latihan Interaktif</option>
                                <option value="Remedial" {{ old('exam_type') == 'Remedial' ? 'selected' : '' }}>Remedial</option>
                                <option value="Lainnya" {{ old('exam_type') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-space-md pointer-events-none text-on-surface-variant text-[20px]">expand_more</span>
                        </div>
                        @error('exam_type') <span class="text-xs text-error font-medium mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nilai KKM -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-lg text-label-lg text-on-surface flex items-center justify-between">
                            <span>Nilai KKM Kelulusan <span class="text-error">*</span></span>
                            <span class="font-label-sm text-label-sm text-secondary font-semibold">Skala 0 - 100</span>
                        </label>
                        <div class="relative flex items-center">
                            <input name="passing_grade" id="passingGrade" class="w-full h-12 pl-space-md pr-12 rounded-lg bg-surface-container-low font-body-md text-body-md text-on-surface focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm transition-all" max="100" min="0" type="number" value="{{ old('passing_grade', 75) }}" required/>
                            <span class="absolute right-space-md font-label-md text-label-md text-on-surface-variant font-semibold">Poin</span>
                        </div>
                        @error('passing_grade') <span class="text-xs text-error font-medium mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tahun Ajaran -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-lg text-label-lg text-on-surface">Tahun Ajaran <span class="text-error">*</span></label>
                        <div class="relative flex items-center">
                            <select name="academic_year_id" id="academicYear" class="w-full h-12 pl-space-md pr-10 rounded-lg bg-surface-container-low font-body-md text-body-md text-on-surface appearance-none focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm transition-all cursor-pointer" required>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ (old('academic_year_id') == $ay->id || (empty(old('academic_year_id')) && $ay->is_active)) ? 'selected' : '' }}>
                                        {{ $ay->name }} - Semester {{ $ay->semester }} {{ $ay->is_active ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-space-md pointer-events-none text-on-surface-variant text-[20px]">expand_more</span>
                        </div>
                        @error('academic_year_id') <span class="text-xs text-error font-medium mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <!-- Target Kelas / Rombongan Belajar (Full Width) -->
                    <div class="md:col-span-2 flex flex-col gap-2">
                        <label class="font-label-lg text-label-lg text-on-surface flex items-center justify-between">
                            <span>Target Kelas &amp; Rombel Terdaftar <span class="text-error">*</span></span>
                            <span class="font-label-sm text-label-sm text-on-surface-variant font-normal">Pilih kelas yang akan mengerjakan ujian</span>
                        </label>
                        <div class="relative flex items-center">
                            <select name="classroom_id" id="classroomSelect" class="w-full h-12 pl-space-md pr-10 rounded-lg bg-surface-container-low font-body-md text-body-md text-on-surface appearance-none focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm transition-all cursor-pointer" required onchange="handleClassroomChange()">
                                <option value="">-- Pilih Kelas Target --</option>
                                @foreach($classrooms as $cls)
                                    <option value="{{ $cls->id }}" data-students="{{ $cls->students ? $cls->students->count() : 0 }}" {{ old('classroom_id') == $cls->id ? 'selected' : '' }}>
                                        {{ $cls->name }} ({{ $cls->academicYear->name ?? 'T.A' }}) - {{ $cls->students ? $cls->students->count() : 0 }} Siswa
                                    </option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-space-md pointer-events-none text-on-surface-variant text-[20px]">expand_more</span>
                        </div>
                        <!-- Selected classroom pills preview -->
                        <div id="classroomBadgeRow" class="flex flex-wrap items-center gap-space-xs p-space-sm rounded-lg bg-surface-container-low min-h-12 border border-slate-100">
                            <span id="noClassroomSelected" class="text-xs text-on-surface-variant italic px-2">Belum ada kelas yang dipilih. Silakan pilih kelas di atas.</span>
                            <span id="selectedClassroomBadge" class="hidden inline-flex items-center gap-1.5 py-1 px-space-sm rounded-md bg-surface-container-lowest text-on-surface font-label-sm text-label-sm shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-secondary"></span>
                                <span id="selectedClassroomText">X IPA 1 (36 Siswa)</span>
                            </span>
                        </div>
                        @error('classroom_id') <span class="text-xs text-error font-medium mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Pelaksanaan -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-lg text-label-lg text-on-surface">Tanggal Pelaksanaan <span class="text-error">*</span></label>
                        <div class="relative flex items-center">
                            <input name="exam_date" id="examDate" class="w-full h-12 pl-11 pr-space-md rounded-lg bg-surface-container-low font-body-md text-body-md text-on-surface focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm transition-all cursor-pointer" type="date" value="{{ old('exam_date', date('Y-m-d')) }}" required/>
                            <span class="material-symbols-outlined absolute left-space-md text-on-surface-variant text-[20px]">calendar_month</span>
                        </div>
                        @error('exam_date') <span class="text-xs text-error font-medium mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <!-- Waktu Pelaksanaan & Durasi -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-lg text-label-lg text-on-surface">Waktu Sesi &amp; Durasi Efektif <span class="text-error">*</span></label>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="relative flex flex-col">
                                <input name="start_time" id="startTime" class="w-full h-12 px-2.5 rounded-lg bg-surface-container-low font-body-md text-body-md text-center text-on-surface focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm" type="time" value="{{ old('start_time', '08:00') }}" required onchange="calculateDuration()"/>
                                <span class="text-[10px] text-on-surface-variant text-center mt-1">Mulai</span>
                            </div>
                            <div class="relative flex flex-col">
                                <input name="end_time" id="endTime" class="w-full h-12 px-2.5 rounded-lg bg-surface-container-low font-body-md text-body-md text-center text-on-surface focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm" type="time" value="{{ old('end_time', '09:30') }}" required onchange="calculateDuration()"/>
                                <span class="text-[10px] text-on-surface-variant text-center mt-1">Selesai</span>
                            </div>
                            <div class="relative flex flex-col">
                                <div class="h-12 flex items-center justify-center rounded-lg bg-primary-fixed/60 text-on-primary-fixed font-label-sm text-label-sm px-2 gap-1 border border-primary-fixed">
                                    <span class="material-symbols-outlined text-[16px]">timer</span>
                                    <span id="durationDisplay">90 Menit</span>
                                </div>
                                <input type="hidden" name="duration_minutes" id="durationMinutes" value="{{ old('duration_minutes', 90) }}">
                                <span class="text-[10px] text-on-surface-variant text-center mt-1">Durasi</span>
                            </div>
                        </div>
                    </div>

                    <!-- Instruksi & Tata Tertib Siswa (Full Width) -->
                    <div class="md:col-span-2 flex flex-col gap-1.5 mt-2">
                        <label class="font-label-lg text-label-lg text-on-surface flex items-center justify-between">
                            <span>Instruksi &amp; Tata Tertib Siswa</span>
                            <span class="font-label-sm text-label-sm text-on-surface-variant font-normal">Tampil di layar konfirmasi siswa</span>
                        </label>
                        <textarea name="instructions" rows="3" class="w-full p-space-md rounded-lg bg-surface-container-low font-body-md text-body-md text-on-surface focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm transition-all resize-none" placeholder="Pastikan koneksi internet stabil. Dilarang membuka tab peramban lain atau menggunakan aplikasi pihak ketiga selama ujian berlangsung. Soal akan otomatis terkirim begitu durasi waktu habis.">{{ old('instructions', 'Pastikan koneksi internet stabil. Dilarang membuka tab peramban lain atau berpindah jendela selama ujian berlangsung. Jawaban akan tersimpan secara berkala dan sesi otomatis selesai begitu durasi waktu habis.') }}</textarea>
                    </div>
                </div>
            </section>

            <!-- 2. KONFIGURASI BUTIR SOAL -->
            <section class="rounded-xl bg-surface-container-lowest p-space-lg shadow-sm border border-slate-100 flex flex-col gap-space-md">
                <!-- Section Header with Metrics Counter -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-space-sm">
                    <div class="flex items-center gap-space-sm">
                        <span class="w-8 h-8 rounded-lg bg-primary-fixed text-on-primary-fixed flex items-center justify-center font-label-lg text-label-lg font-bold">2</span>
                        <div>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Konfigurasi Butir Soal</h2>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span id="selectedCountDisplay" class="font-label-sm text-label-sm font-semibold text-secondary">0 Soal Terpilih</span>
                                <span class="text-on-surface-variant">•</span>
                                <span id="totalScoreDisplay" class="font-label-sm text-label-sm text-on-surface-variant">Total Bobot: 0 Poin</span>
                                <span class="text-on-surface-variant">•</span>
                                <span id="weightStatusBadge" class="px-2 py-0.5 rounded-full bg-surface-container text-on-surface font-label-sm text-[11px]">Belum Ada Soal</span>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="openQuestionModal()" class="inline-flex items-center gap-space-xs h-10 px-space-md rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg shadow-sm hover:opacity-95 transition-all self-start sm:self-auto">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        <span>+ Tambah dari Bank Soal</span>
                    </button>
                </div>

                <!-- Filter bar inside questions table -->
                <div class="flex items-center justify-between p-space-sm rounded-lg bg-surface-container-low gap-space-sm">
                    <div class="flex items-center gap-space-xs overflow-x-auto" id="selectedFilterTabs">
                        <button type="button" onclick="filterSelectedQuestions('all')" class="q-tab active px-space-sm py-1 rounded bg-surface-container-lowest text-on-surface font-label-sm text-label-sm shadow-sm transition-all" data-type="all">Semua (<span id="tabCountAll">0</span>)</button>
                        <button type="button" onclick="filterSelectedQuestions('multiple_choice')" class="q-tab px-space-sm py-1 rounded text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm transition-all" data-type="multiple_choice">Pilihan Ganda (<span id="tabCountMC">0</span>)</button>
                        <button type="button" onclick="filterSelectedQuestions('short_answer')" class="q-tab px-space-sm py-1 rounded text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm transition-all" data-type="short_answer">Isian Singkat (<span id="tabCountSA">0</span>)</button>
                        <button type="button" onclick="filterSelectedQuestions('essay')" class="q-tab px-space-sm py-1 rounded text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm transition-all" data-type="essay">Esai (<span id="tabCountEssay">0</span>)</button>
                    </div>
                    <span class="font-label-sm text-label-sm text-on-surface-variant whitespace-nowrap hidden sm:inline-block">Urutan: Sesuai Urutan Pilihan</span>
                </div>

                <!-- Selected Questions List Container -->
                <div id="selectedQuestionsList" class="flex flex-col gap-space-sm min-h-[140px]">
                    <!-- Empty Placeholder -->
                    <div id="emptyQuestionPlaceholder" class="p-8 rounded-lg bg-surface-container-low/60 border border-dashed border-slate-300 text-center flex flex-col items-center justify-center">
                        <div class="w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center text-primary mb-2">
                            <span class="material-symbols-outlined text-2xl">quiz</span>
                        </div>
                        <span class="font-label-md text-label-md text-on-surface font-semibold">Belum Ada Butir Soal Terpilih</span>
                        <p class="font-body-sm text-body-sm text-on-surface-variant max-w-sm mt-1 mb-3">Pilih mata pelajaran di atas lalu klik tombol di bawah untuk memasukkan butir soal ke dalam ujian ini.</p>
                        <button type="button" onclick="openQuestionModal()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-container text-on-primary font-label-sm text-label-sm shadow-sm hover:opacity-95">
                            <span class="material-symbols-outlined text-[16px]">add</span>
                            <span>Buka Bank Soal</span>
                        </button>
                    </div>
                </div>
                @error('question_ids') <span class="text-xs text-error font-medium mt-1">{{ $message }}</span> @enderror
            </section>
        </div>

        <!-- RIGHT 4 COLUMNS: PENGATURAN INTEGRITAS & UJIAN -->
        <div class="lg:col-span-4 flex flex-col gap-space-lg">
            <!-- 3. PENGATURAN INTEGRITAS & KEAMANAN -->
            <section class="rounded-xl bg-surface-container-lowest p-space-lg shadow-sm border border-slate-100 flex flex-col gap-space-md sticky top-24">
                <div class="flex items-center justify-between pb-space-xs">
                    <div class="flex items-center gap-space-sm">
                        <span class="w-8 h-8 rounded-lg bg-primary-fixed text-on-primary-fixed flex items-center justify-center font-label-lg text-label-lg font-bold">3</span>
                        <div>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Integritas &amp; Ujian</h2>
                            <span class="font-body-sm text-body-sm text-on-surface-variant">Sistem anti-curang &amp; scoring</span>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-primary text-[24px]">verified_user</span>
                </div>

                <!-- Checklist of Settings with Custom Styled Checkbox Row Cards -->
                <div class="flex flex-col gap-space-xs">
                    <!-- Option 1: Acak urutan butir soal -->
                    <label class="flex items-start gap-space-sm p-space-sm rounded-lg bg-surface-container-low hover:bg-surface-container cursor-pointer transition-colors">
                        <input name="shuffle_questions" value="1" {{ old('shuffle_questions', '1') == '1' ? 'checked' : '' }} class="mt-1 w-4 h-4 rounded text-primary-container focus:ring-0 focus:outline-none accent-primary" type="checkbox"/>
                        <div class="flex flex-col">
                            <span class="font-label-lg text-label-lg text-on-surface font-semibold">Acak urutan butir soal</span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant">Nomor soal setiap siswa berbeda secara acak</span>
                        </div>
                    </label>

                    <!-- Option 2: Acak pilihan opsi jawaban -->
                    <label class="flex items-start gap-space-sm p-space-sm rounded-lg bg-surface-container-low hover:bg-surface-container cursor-pointer transition-colors">
                        <input name="shuffle_options" value="1" {{ old('shuffle_options', '1') == '1' ? 'checked' : '' }} class="mt-1 w-4 h-4 rounded text-primary-container focus:ring-0 focus:outline-none accent-primary" type="checkbox"/>
                        <div class="flex flex-col">
                            <span class="font-label-lg text-label-lg text-on-surface font-semibold">Acak pilihan opsi jawaban</span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant">Posisi A, B, C, D berputar otomatis</span>
                        </div>
                    </label>

                    <!-- Option 3: Simpan otomatis berkala -->
                    <label class="flex items-start gap-space-sm p-space-sm rounded-lg bg-surface-container-low hover:bg-surface-container cursor-pointer transition-colors">
                        <input name="auto_save" value="1" checked class="mt-1 w-4 h-4 rounded text-primary-container focus:ring-0 focus:outline-none accent-primary" type="checkbox"/>
                        <div class="flex flex-col">
                            <span class="font-label-lg text-label-lg text-on-surface font-semibold">Simpan otomatis berkala</span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant">Sinkronisasi jawaban cloud setiap klik</span>
                        </div>
                    </label>

                    <!-- Option 4: Auto-submit saat waktu habis -->
                    <label class="flex items-start gap-space-sm p-space-sm rounded-lg bg-surface-container-low hover:bg-surface-container cursor-pointer transition-colors">
                        <input name="auto_submit" value="1" checked class="mt-1 w-4 h-4 rounded text-primary-container focus:ring-0 focus:outline-none accent-primary" type="checkbox"/>
                        <div class="flex flex-col">
                            <span class="font-label-lg text-label-lg text-on-surface font-semibold">Auto-submit saat waktu habis</span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant">Sesi otomatis ditutup paksa begitu waktu usai</span>
                        </div>
                    </label>

                    <!-- Option 5: CBT Guard Proctoring -->
                    <label class="flex items-start gap-space-sm p-space-sm rounded-lg bg-primary-fixed/40 hover:bg-primary-fixed/60 cursor-pointer transition-colors border border-primary-200">
                        <input name="allow_review" value="1" checked class="mt-1 w-4 h-4 rounded text-primary-container focus:ring-0 focus:outline-none accent-primary" type="checkbox"/>
                        <div class="flex flex-col">
                            <span class="font-label-lg text-label-lg text-on-surface font-semibold flex items-center gap-1">
                                <span>Kunci browser &amp; Proctoring</span>
                                <span class="px-1.5 py-0.2 rounded bg-primary-container text-on-primary font-label-sm text-[10px]">CBT Guard</span>
                            </span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant">Peringatan otomatis saat siswa beralih tab</span>
                        </div>
                    </label>

                    <!-- Option 6: Rilis skor otomatis -->
                    <label class="flex items-start gap-space-sm p-space-sm rounded-lg bg-surface-container-low hover:bg-surface-container cursor-pointer transition-colors">
                        <input name="show_result_immediately" value="1" {{ old('show_result_immediately', '1') == '1' ? 'checked' : '' }} class="mt-1 w-4 h-4 rounded text-primary-container focus:ring-0 focus:outline-none accent-primary" type="checkbox"/>
                        <div class="flex flex-col">
                            <span class="font-label-lg text-label-lg text-on-surface font-semibold">Rilis skor otomatis</span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant">Nilai langsung tampil setelah tombol selesai ditekan</span>
                        </div>
                    </label>

                    <!-- Option 7: Kunci jawaban setelah selesai -->
                    <label class="flex items-start gap-space-sm p-space-sm rounded-lg bg-surface-container-low hover:bg-surface-container cursor-pointer transition-colors">
                        <input name="show_correct_answers" value="1" {{ old('show_correct_answers') == '1' ? 'checked' : '' }} class="mt-1 w-4 h-4 rounded text-primary-container focus:ring-0 focus:outline-none accent-primary" type="checkbox"/>
                        <div class="flex flex-col">
                            <span class="font-label-lg text-label-lg text-on-surface font-semibold">Tampilkan kunci pembahasan</span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant">Kunci dan penjelasan dapat ditinjau siswa</span>
                        </div>
                    </label>
                </div>

                <!-- Ringkasan Kesiapan Ujian Panel -->
                <div class="p-space-md rounded-xl bg-surface-container-high/40 flex flex-col gap-space-xs mt-space-xs border border-indigo-100">
                    <div class="flex items-center justify-between text-on-surface">
                        <span class="font-label-md text-label-md">Status Validasi</span>
                        <span id="validationStatusBadge" class="inline-flex items-center gap-1 text-on-surface-variant font-label-sm text-label-sm font-semibold">
                            <span class="material-symbols-outlined text-[16px]">pending</span>
                            <span id="validationStatusText">Menunggu Kelengkapan</span>
                        </span>
                    </div>
                    <div class="w-full bg-surface-container-highest h-2 rounded-full overflow-hidden">
                        <div id="validationProgressBar" class="bg-secondary h-full rounded-full transition-all duration-300" style="width: 25%;"></div>
                    </div>
                    <div class="flex items-center justify-between text-on-surface-variant font-label-sm text-label-sm pt-1">
                        <span id="studentCountSummary">Peserta: 0 Siswa</span>
                        <span id="serverLoadSummary">Status: Draf</span>
                    </div>
                </div>
            </section>
        </div>
    </form>

    <!-- STICKY BOTTOM ACTION TOOLBAR -->
    <aside class="fixed bottom-0 left-0 lg:left-68 right-0 bg-surface-container-lowest/95 backdrop-blur-md shadow-xl py-space-sm px-space-xl z-30 flex items-center justify-between border-t border-slate-200">
        <div class="flex items-center gap-space-md">
            <a href="{{ route('teacher.exams.index') }}" class="inline-flex items-center gap-space-xs h-12 px-space-md rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low font-label-lg text-label-lg transition-colors">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                <span>Kembali</span>
            </a>
            <div class="hidden sm:flex items-center gap-space-xs text-on-surface-variant font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-[18px] text-secondary">cloud_done</span>
                <span>Perubahan tersimpan dalam sesi ini</span>
            </div>
        </div>
        <div class="flex items-center gap-space-sm">
            <button type="button" onclick="submitExam('draft')" class="inline-flex items-center gap-space-xs h-12 px-space-lg rounded-lg bg-surface-container-low text-on-surface font-label-lg text-label-lg hover:bg-surface-container-high transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">save</span>
                <span>Simpan Draft</span>
            </button>
            <button type="button" onclick="submitExam('publish')" class="inline-flex items-center gap-space-xs h-12 px-space-xl rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg shadow-lg hover:opacity-95 transition-all">
                <span>Terbitkan Ujian Sekarang</span>
                <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
            </button>
        </div>
    </aside>

<!-- MODAL PILIH BANK SOAL (BERBASIS KARTU MAPEL + KELAS) -->
<div id="questionModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-200">
        
        <!-- Modal Dynamic Header -->
        <div class="p-space-lg border-b border-surface-container flex items-center justify-between bg-surface-container-low/50">
            <div class="flex items-center gap-space-sm">
                <!-- Back Button (tampil hanya saat membuka butir soal dalam kartu) -->
                <button type="button" id="modalBackToCardsBtn" onclick="backToCardsView()" class="hidden w-10 h-10 rounded-xl bg-surface-container-highest hover:bg-surface-container flex items-center justify-center text-on-surface transition-colors mr-1" title="Kembali ke Daftar Kartu Bank Soal">
                    <span class="material-symbols-outlined text-[22px]">arrow_back</span>
                </button>
                <div id="modalHeaderIconWrapper" class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[22px]" id="modalHeaderIcon">library_books</span>
                </div>
                <div>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold" id="modalHeaderTitle">Pilih Butir Soal dari Bank Soal</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant" id="modalHeaderSubtitle">Pilih kartu Bank Soal berdasarkan Mata Pelajaran &amp; Kelas untuk melihat butir soal</p>
                </div>
            </div>
            <button type="button" onclick="closeQuestionModal()" class="w-9 h-9 rounded-lg flex items-center justify-center text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>
        </div>

        <!-- ================= LEVEL 1: VIEW KARTU BANK SOAL ================= -->
        <div id="modalCardsView" class="flex-1 flex flex-col overflow-hidden">
            <!-- Filter Bar Kartu -->
            <div class="p-space-md border-b border-surface-container bg-surface-container-lowest flex flex-wrap items-center gap-space-sm">
                <div class="relative flex-1 min-w-[200px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                    <input type="text" id="cardSearchInput" oninput="filterCards()" class="w-full h-10 pl-9 pr-3 rounded-lg bg-surface-container-low font-body-sm text-body-sm text-on-surface focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300" placeholder="Cari mata pelajaran atau kelas..."/>
                </div>
                <select id="cardSubjectFilter" onchange="filterCards()" class="h-10 px-3 rounded-lg bg-surface-container-low font-body-sm text-body-sm text-on-surface focus:outline-none border border-transparent focus:border-indigo-300">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($subjects as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
                <div class="text-xs text-on-surface-variant hidden md:block">
                    Pilih kartu untuk melihat butir soal
                </div>
            </div>

            <!-- Cards Grid Container -->
            <div id="modalCardsGrid" class="p-space-lg flex-1 overflow-y-auto grid grid-cols-1 md:grid-cols-2 gap-space-md bg-surface-container-low/30 min-h-[350px]">
                <div class="col-span-full text-center py-12 text-on-surface-variant font-body-md text-body-md">
                    Memuat daftar kartu Bank Soal...
                </div>
            </div>

            <!-- Cards View Footer -->
            <div class="p-space-md border-t border-surface-container bg-surface-container-lowest flex items-center justify-between">
                <div class="flex items-center gap-space-xs font-label-md text-label-md text-on-surface font-semibold">
                    <span class="w-6 h-6 rounded-full bg-secondary text-on-secondary flex items-center justify-center text-xs" id="modalCardsSelectedCounter">0</span>
                    <span>Total Soal Terpilih untuk Ujian</span>
                </div>
                <div class="flex items-center gap-space-xs">
                    <button type="button" onclick="closeQuestionModal()" class="h-10 px-space-md rounded-lg bg-surface-container-low text-on-surface font-label-md text-label-md hover:bg-surface-container-high transition-colors">
                        Tutup
                    </button>
                    <button type="button" onclick="applyModalQuestions()" class="h-10 px-space-lg rounded-lg bg-primary-container text-on-primary font-label-md text-label-md shadow-md hover:opacity-95 transition-all">
                        Terapkan ke Ujian
                    </button>
                </div>
            </div>
        </div>

        <!-- ================= LEVEL 2: VIEW BUTIR SOAL DALAM KARTU ================= -->
        <div id="modalDetailView" class="hidden flex-1 flex flex-col overflow-hidden">
            <!-- Filter Bar Butir Soal -->
            <div class="p-space-md border-b border-surface-container bg-surface-container-lowest flex flex-wrap items-center justify-between gap-space-sm">
                <div class="flex flex-wrap items-center gap-space-sm flex-1 min-w-[280px]">
                    <!-- Checkbox Pilih Semua -->
                    <label class="inline-flex items-center gap-2 h-10 px-3.5 rounded-lg bg-surface-container-high hover:bg-surface-container text-on-surface font-label-sm text-label-sm font-semibold transition-colors cursor-pointer select-none">
                        <input type="checkbox" id="selectAllCardCheckbox" onchange="toggleSelectAllInActiveCard()" class="w-4 h-4 rounded text-secondary accent-secondary cursor-pointer"/>
                        <span>Pilih Semua (<span id="selectAllCardTotalText">0</span> Soal)</span>
                    </label>

                    <div class="relative flex-1 min-w-[180px]">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                        <input type="text" id="detailSearchInput" oninput="filterActiveCardQuestions()" class="w-full h-10 pl-9 pr-3 rounded-lg bg-surface-container-low font-body-sm text-body-sm text-on-surface focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300" placeholder="Cari isi pertanyaan / topik..."/>
                    </div>

                    <select id="detailTypeSelect" onchange="filterActiveCardQuestions()" class="h-10 px-3 rounded-lg bg-surface-container-low font-body-sm text-body-sm text-on-surface focus:outline-none border border-transparent focus:border-indigo-300">
                        <option value="">Semua Bentuk Soal</option>
                        <option value="multiple_choice">Pilihan Ganda</option>
                        <option value="true_false">Benar / Salah</option>
                        <option value="short_answer">Isian Singkat</option>
                        <option value="essay">Uraian / Esai</option>
                    </select>
                </div>

                <div class="font-label-sm text-label-sm font-semibold text-secondary" id="detailHeaderSelectionCount">
                    0 Soal Dipilih
                </div>
            </div>

            <!-- Question Items List -->
            <div id="modalDetailQuestionList" class="p-space-md flex-1 overflow-y-auto flex flex-col gap-space-sm bg-surface-container-low/30 min-h-[350px]">
                <!-- Question cards populated via JS -->
            </div>

            <!-- Detail View Footer -->
            <div class="p-space-md border-t border-surface-container bg-surface-container-lowest flex items-center justify-between">
                <button type="button" onclick="backToCardsView()" class="inline-flex items-center gap-1.5 h-10 px-space-md rounded-lg bg-surface-container-low text-on-surface font-label-md text-label-md hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    <span>Kembali ke Bank Soal</span>
                </button>
                <div class="flex items-center gap-space-md">
                    <span class="font-label-sm text-label-sm text-on-surface-variant hidden sm:inline" id="detailFooterStatusText">
                        0 dari 0 soal dipilih
                    </span>
                    <button type="button" onclick="applyModalQuestions()" class="inline-flex items-center gap-1.5 h-10 px-space-lg rounded-lg bg-primary-container text-on-primary font-label-md text-label-md shadow-md hover:opacity-95 transition-all">
                        <span class="material-symbols-outlined text-[18px]">add_task</span>
                        <span>Tambahkan Soal ke Ujian</span>
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    let selectedQuestions = new Map();
    let allServerQuestions = [];
    let bankPackages = new Map();
    let activeCardKey = null;
    let currentFilterType = 'all';

    document.addEventListener('DOMContentLoaded', function() {
        calculateDuration();
        handleClassroomChange();
        updateSummary();

        const oldSubjectId = document.getElementById('subjectFilter').value;
        if(oldSubjectId) {
            const cardSubj = document.getElementById('cardSubjectFilter');
            if(cardSubj) cardSubj.value = oldSubjectId;
        }
    });

    function handleSubjectChange() {
        const subjId = document.getElementById('subjectFilter').value;
        const cardSubj = document.getElementById('cardSubjectFilter');
        if(cardSubj && subjId) {
            cardSubj.value = subjId;
        }
        updateSummary();
    }

    function handleClassroomChange() {
        const select = document.getElementById('classroomSelect');
        const badgeRow = document.getElementById('classroomBadgeRow');
        const noBadge = document.getElementById('noClassroomSelected');
        const selectedBadge = document.getElementById('selectedClassroomBadge');
        const textSpan = document.getElementById('selectedClassroomText');
        const studentSummary = document.getElementById('studentCountSummary');

        if(select.value) {
            const opt = select.options[select.selectedIndex];
            const studentCount = opt.getAttribute('data-students') || 0;
            textSpan.textContent = `${opt.text.split(' - ')[0]} (${studentCount} Siswa)`;
            noBadge.classList.add('hidden');
            selectedBadge.classList.remove('hidden');
            studentSummary.textContent = `Peserta: ${studentCount} Siswa`;
        } else {
            noBadge.classList.remove('hidden');
            selectedBadge.classList.add('hidden');
            studentSummary.textContent = `Peserta: 0 Siswa`;
        }
        updateSummary();
    }

    function calculateDuration() {
        const start = document.getElementById('startTime').value;
        const end = document.getElementById('endTime').value;
        if(start && end) {
            const [sh, sm] = start.split(':').map(Number);
            const [eh, em] = end.split(':').map(Number);
            let diff = (eh * 60 + em) - (sh * 60 + sm);
            if(diff <= 0) diff += 24 * 60; // Cross midnight handler
            document.getElementById('durationMinutes').value = diff;
            document.getElementById('durationDisplay').textContent = `${diff} Menit`;
        }
    }

    function openQuestionModal() {
        document.getElementById('questionModal').classList.remove('hidden');
        backToCardsView();
        loadBankQuestions();
    }

    function closeQuestionModal() {
        document.getElementById('questionModal').classList.add('hidden');
    }

    function loadBankQuestions() {
        const grid = document.getElementById('modalCardsGrid');
        grid.innerHTML = '<div class="col-span-full text-center py-12 text-on-surface-variant font-body-md text-body-md">Memuat daftar kartu Bank Soal...</div>';

        fetch('/guru/api/soal')
            .then(res => res.json())
            .then(data => {
                allServerQuestions = data;
                groupQuestionsIntoPackages();
                renderModalCards();
            })
            .catch(() => {
                grid.innerHTML = '<div class="col-span-full text-center py-12 text-error font-body-md text-body-md">Gagal memuat bank butir soal dari server.</div>';
            });
    }

    function formatClassroomLabel(classroom) {
        if (!classroom) return 'Semua Kelas (Umum)';
        const cName = classroom.name || '';
        const grade = classroom.grade;
        const roman = grade == 10 ? 'Kelas X' : (grade == 11 ? 'Kelas XI' : (grade == 12 ? 'Kelas XII' : (grade ? 'Kelas ' + grade : '')));
        
        if (roman) {
            if (cName.toUpperCase().startsWith('X') || cName.toUpperCase().startsWith('XI') || cName.toUpperCase().startsWith('XII')) {
                return 'Kelas ' + cName;
            }
            return roman + ' (' + cName + ')';
        }
        return cName.toLowerCase().startsWith('kelas') ? cName : 'Kelas ' + cName;
    }

    function groupQuestionsIntoPackages() {
        bankPackages.clear();
        allServerQuestions.forEach(q => {
            const subId = q.subject_id || 0;
            const classId = q.classroom_id || 'all';
            const key = `pkg_${subId}_${classId}`;

            if (!bankPackages.has(key)) {
                bankPackages.set(key, {
                    key: key,
                    subject_id: q.subject_id,
                    subject_name: q.subject?.name || 'Mata Pelajaran',
                    subject_icon: q.subject?.icon || 'menu_book',
                    classroom_id: q.classroom_id,
                    classroom_name: q.classroom?.name || '',
                    classroom_grade: q.classroom?.grade || '',
                    classroom_label: formatClassroomLabel(q.classroom),
                    questions: []
                });
            }

            bankPackages.get(key).questions.push(q);
        });
    }

    function filterCards() {
        renderModalCards();
    }

    function renderModalCards() {
        const grid = document.getElementById('modalCardsGrid');
        grid.innerHTML = '';

        const searchQuery = (document.getElementById('cardSearchInput').value || '').trim().toLowerCase();
        const subjectFilter = document.getElementById('cardSubjectFilter').value;

        let packagesList = Array.from(bankPackages.values());

        if (subjectFilter) {
            packagesList = packagesList.filter(p => String(p.subject_id) === String(subjectFilter));
        }

        if (searchQuery) {
            packagesList = packagesList.filter(p => {
                const subMatch = (p.subject_name || '').toLowerCase().includes(searchQuery);
                const classMatch = (p.classroom_label || '').toLowerCase().includes(searchQuery);
                const qMatch = p.questions.some(q => 
                    (q.topic || '').toLowerCase().includes(searchQuery) || 
                    (q.question_text || '').toLowerCase().includes(searchQuery)
                );
                return subMatch || classMatch || qMatch;
            });
        }

        if (packagesList.length === 0) {
            grid.innerHTML = `
                <div class="col-span-full p-8 text-center bg-surface-container-low rounded-xl border border-dashed border-slate-300 flex flex-col items-center justify-center">
                    <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-2">library_books</span>
                    <p class="font-headline-sm text-sm font-semibold text-on-surface">Belum ada soal pada Bank Soal ini.</p>
                    <p class="text-xs text-on-surface-variant mt-1">Pastikan sudah ada soal yang dibuat untuk kriteria ini.</p>
                </div>
            `;
            updateModalGlobalCounter();
            return;
        }

        packagesList.forEach(pkg => {
            const totalQuestions = pkg.questions.length;
            const selectedInPkg = pkg.questions.filter(q => selectedQuestions.has(q.id)).length;

            let statusBadge = '';
            let cardBorder = 'border-slate-200 hover:border-primary-300';
            let cardBg = 'bg-surface-container-lowest hover:bg-surface-container-low/50';

            if (selectedInPkg === 0) {
                statusBadge = `
                    <div class="flex items-center gap-1.5 text-xs text-on-surface-variant font-medium">
                        <span class="material-symbols-outlined text-[16px]">radio_button_unchecked</span>
                        <span>${totalQuestions} Soal</span>
                    </div>
                `;
            } else if (selectedInPkg === totalQuestions) {
                cardBorder = 'border-primary ring-1 ring-primary/30';
                cardBg = 'bg-primary-container/10 hover:bg-primary-container/15';
                statusBadge = `
                    <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed text-xs font-semibold">
                        <span class="material-symbols-outlined text-[14px]">done_all</span>
                        <span>${totalQuestions} Soal tersedia • ${totalQuestions} soal dipilih</span>
                    </div>
                `;
            } else {
                cardBorder = 'border-secondary ring-1 ring-secondary/30';
                cardBg = 'bg-secondary-container/10 hover:bg-secondary-container/15';
                statusBadge = `
                    <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed text-xs font-semibold">
                        <span class="material-symbols-outlined text-[14px]">check</span>
                        <span>${totalQuestions} Soal tersedia • ${selectedInPkg} soal dipilih</span>
                    </div>
                `;
            }

            const card = document.createElement('div');
            card.className = `p-5 rounded-2xl border ${cardBorder} ${cardBg} transition-all cursor-pointer shadow-sm flex flex-col justify-between gap-4 group`;
            card.onclick = () => openCardDetail(pkg.key);

            card.innerHTML = `
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <div class="w-11 h-11 rounded-xl bg-primary-fixed/60 text-primary flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-outlined text-[24px]">${escapeHtml(pkg.subject_icon)}</span>
                        </div>
                        <div>
                            <h4 class="font-headline-sm text-base font-bold text-on-surface group-hover:text-primary transition-colors leading-snug">
                                ${escapeHtml(pkg.subject_name)}
                            </h4>
                            <span class="inline-block mt-0.5 px-2 py-0.5 rounded-md bg-surface-container text-on-surface font-label-sm text-xs font-semibold">
                                ${escapeHtml(pkg.classroom_label)}
                            </span>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary group-hover:translate-x-1 transition-all text-[22px]">
                        chevron_right
                    </span>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                    <div>
                        ${statusBadge}
                    </div>
                    <span class="text-xs font-semibold text-primary inline-flex items-center gap-0.5 group-hover:underline">
                        <span>Pilih Soal</span>
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </span>
                </div>
            `;

            grid.appendChild(card);
        });

        updateModalGlobalCounter();
    }

    function openCardDetail(cardKey) {
        activeCardKey = cardKey;
        const pkg = bankPackages.get(cardKey);
        if (!pkg) return;

        // Beralih tampilan ke Detail View
        document.getElementById('modalCardsView').classList.add('hidden');
        document.getElementById('modalDetailView').classList.remove('hidden');

        // Perbarui Header Modal
        document.getElementById('modalBackToCardsBtn').classList.remove('hidden');
        document.getElementById('modalHeaderTitle').textContent = `${pkg.subject_name} — ${pkg.classroom_label}`;
        document.getElementById('modalHeaderSubtitle').textContent = `${pkg.questions.length} Soal Tersedia dalam Bank Soal ini`;
        document.getElementById('modalHeaderIcon').textContent = pkg.subject_icon || 'menu_book';

        // Reset filter pencarian butir soal
        document.getElementById('detailSearchInput').value = '';
        document.getElementById('detailTypeSelect').value = '';

        renderActiveCardQuestions();
    }

    function backToCardsView() {
        activeCardKey = null;

        // Beralih kembali ke tampilan Kartu
        document.getElementById('modalDetailView').classList.add('hidden');
        document.getElementById('modalCardsView').classList.remove('hidden');

        // Kembalikan Header Modal
        document.getElementById('modalBackToCardsBtn').classList.add('hidden');
        document.getElementById('modalHeaderTitle').textContent = 'Pilih Butir Soal dari Bank Soal';
        document.getElementById('modalHeaderSubtitle').textContent = 'Pilih kartu Bank Soal berdasarkan Mata Pelajaran & Kelas untuk melihat butir soal';
        document.getElementById('modalHeaderIcon').textContent = 'library_books';

        renderModalCards();
    }

    function filterActiveCardQuestions() {
        renderActiveCardQuestions();
    }

    function renderActiveCardQuestions() {
        if (!activeCardKey) return;
        const pkg = bankPackages.get(activeCardKey);
        if (!pkg) return;

        const container = document.getElementById('modalDetailQuestionList');
        container.innerHTML = '';

        const searchQuery = (document.getElementById('detailSearchInput').value || '').trim().toLowerCase();
        const typeFilter = document.getElementById('detailTypeSelect').value;

        let filtered = pkg.questions;

        if (typeFilter) {
            filtered = filtered.filter(q => q.type === typeFilter);
        }

        if (searchQuery) {
            filtered = filtered.filter(q => {
                const textMatch = (q.question_text || '').toLowerCase().includes(searchQuery);
                const topicMatch = (q.topic || '').toLowerCase().includes(searchQuery);
                return textMatch || topicMatch;
            });
        }

        const totalInPkg = pkg.questions.length;
        const selectedInPkg = pkg.questions.filter(q => selectedQuestions.has(q.id)).length;

        // Sinkronisasi status checkbox "Pilih Semua"
        const selectAllCb = document.getElementById('selectAllCardCheckbox');
        const selectAllText = document.getElementById('selectAllCardTotalText');
        selectAllText.textContent = totalInPkg;
        selectAllCb.checked = (totalInPkg > 0 && selectedInPkg === totalInPkg);
        selectAllCb.indeterminate = (selectedInPkg > 0 && selectedInPkg < totalInPkg);

        // Perbarui teks status di header & footer
        document.getElementById('detailHeaderSelectionCount').textContent = `${selectedInPkg} Soal Dipilih`;
        document.getElementById('detailFooterStatusText').textContent = `${selectedInPkg} dari ${totalInPkg} soal dipilih`;

        if (filtered.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12 text-on-surface-variant flex flex-col items-center">
                    <span class="material-symbols-outlined text-4xl mb-1 text-slate-300">search_off</span>
                    <p class="font-body-md text-body-md">Belum ada butir soal yang sesuai kriteria pada Bank Soal ini.</p>
                </div>
            `;
            return;
        }

        filtered.forEach((q, idx) => {
            const isSelected = selectedQuestions.has(q.id);
            const card = document.createElement('div');
            card.className = `p-4 rounded-xl border transition-all cursor-pointer flex items-start gap-3 ${isSelected ? 'bg-secondary-container/20 border-secondary' : 'bg-surface-container-lowest border-slate-200 hover:border-primary-300'}`;
            card.onclick = (e) => {
                if(e.target.tagName !== 'INPUT') {
                    toggleQuestionInActiveCard(q.id);
                }
            };

            const typeLabel = q.type === 'multiple_choice' ? 'Pilihan Ganda' : (q.type === 'short_answer' ? 'Isian Singkat' : (q.type === 'essay' ? 'Esai' : 'Benar/Salah'));
            let keyPreview = '-';
            if(q.options && q.options.length > 0) {
                const correct = q.options.find(o => o.is_correct);
                if(correct) keyPreview = `${correct.label ?? ''}. ${correct.option_text}`;
            } else if(q.explanation) {
                keyPreview = q.explanation;
            }

            card.innerHTML = `
                <input type="checkbox" ${isSelected ? 'checked' : ''} onchange="toggleQuestionInActiveCard(${q.id})" class="mt-1 w-4 h-4 rounded text-secondary accent-secondary cursor-pointer"/>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span class="px-2 py-0.5 rounded bg-surface-container text-on-surface font-label-sm text-label-sm font-semibold">Soal #${padZero(idx + 1)}</span>
                        <span class="px-2 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-label-sm">${typeLabel}</span>
                        <span class="font-label-sm text-label-sm text-secondary font-semibold">Bobot: ${q.score} Poin</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant truncate">Topik: ${escapeHtml(q.topic || 'Umum')}</span>
                    </div>
                    <p class="font-body-md text-body-md text-on-surface font-medium line-clamp-2">
                        ${escapeHtml(q.question_text)}
                    </p>
                    <div class="text-xs text-on-surface-variant mt-1.5 flex items-center gap-2">
                        <span>Kunci: <strong class="text-secondary">${escapeHtml(keyPreview)}</strong></span>
                        <span>•</span>
                        <span class="capitalize">Kesulitan: ${q.difficulty ?? 'Sedang'}</span>
                    </div>
                </div>
            `;

            container.appendChild(card);
        });
    }

    function toggleQuestionInActiveCard(id) {
        if(selectedQuestions.has(id)) {
            selectedQuestions.delete(id);
        } else {
            const q = allServerQuestions.find(x => x.id === id);
            if(q) selectedQuestions.set(id, q);
        }
        renderActiveCardQuestions();
        updateModalGlobalCounter();
    }

    function toggleSelectAllInActiveCard() {
        if (!activeCardKey) return;
        const pkg = bankPackages.get(activeCardKey);
        if (!pkg) return;

        const totalInPkg = pkg.questions.length;
        const selectedInPkg = pkg.questions.filter(q => selectedQuestions.has(q.id)).length;

        if (selectedInPkg === totalInPkg) {
            // Semua sudah terpilih -> batalkan semua di kartu ini
            pkg.questions.forEach(q => selectedQuestions.delete(q.id));
        } else {
            // Pilih semua di kartu ini
            pkg.questions.forEach(q => selectedQuestions.set(q.id, q));
        }

        renderActiveCardQuestions();
        updateModalGlobalCounter();
    }

    function updateModalGlobalCounter() {
        const count = selectedQuestions.size;
        const el = document.getElementById('modalCardsSelectedCounter');
        if (el) el.textContent = count;
    }

    function applyModalQuestions() {
        closeQuestionModal();
        renderSelectedQuestionsList();
        updateSummary();
    }

    function removeQuestion(id) {
        selectedQuestions.delete(id);
        renderSelectedQuestionsList();
        updateSummary();
    }

    function renderSelectedQuestionsList() {
        const container = document.getElementById('selectedQuestionsList');
        const emptyPlaceholder = document.getElementById('emptyQuestionPlaceholder');

        // Clear existing question cards
        const existingCards = container.querySelectorAll('.selected-q-card');
        existingCards.forEach(c => c.remove());

        if(selectedQuestions.size === 0) {
            emptyPlaceholder.classList.remove('hidden');
        } else {
            emptyPlaceholder.classList.add('hidden');

            let index = 0;
            let totalScore = 0;
            let mcCount = 0;
            let saCount = 0;
            let essayCount = 0;

            selectedQuestions.forEach((q, id) => {
                totalScore += parseFloat(q.score || 0);
                if(q.type === 'multiple_choice') mcCount++;
                else if(q.type === 'short_answer') saCount++;
                else if(q.type === 'essay') essayCount++;

                const isHidden = (currentFilterType !== 'all' && q.type !== currentFilterType);

                const typeLabel = q.type === 'multiple_choice' ? 'Pilihan Ganda' : (q.type === 'short_answer' ? 'Isian Singkat' : (q.type === 'essay' ? 'Esai' : 'Benar / Salah'));
                const diffLabel = q.difficulty === 'easy' ? 'Mudah' : (q.difficulty === 'hard' ? 'Sulit (HOTS)' : 'Sedang');

                let keyPreview = '-';
                if(q.options && q.options.length > 0) {
                    const correct = q.options.find(o => o.is_correct);
                    if(correct) keyPreview = `${correct.label ?? ''}. ${correct.option_text}`;
                } else if(q.explanation) {
                    keyPreview = q.explanation;
                }

                const card = document.createElement('div');
                card.className = `p-space-md rounded-lg bg-surface-container-low flex items-start gap-space-sm hover:shadow-md transition-shadow selected-q-card ${isHidden ? 'hidden' : ''}`;
                card.setAttribute('data-id', id);
                card.setAttribute('data-type', q.type);

                card.innerHTML = `
                    <span class="material-symbols-outlined text-outline cursor-grab mt-1 select-none text-[20px]">drag_indicator</span>
                    <div class="w-7 h-7 rounded-md bg-surface-container-highest text-on-surface flex items-center justify-center font-label-sm text-label-sm font-semibold shrink-0">
                        ${padZero(index + 1)}
                    </div>
                    <div class="flex-1 flex flex-col gap-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="px-2 py-0.5 rounded bg-surface-container-highest text-on-surface font-label-sm text-label-sm font-semibold">${typeLabel}</span>
                            <span class="font-label-sm text-label-sm text-secondary font-semibold">Bobot: ${q.score} Poin</span>
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Topik: ${escapeHtml(q.topic || 'Umum')}</span>
                        </div>
                        <p class="font-body-md text-body-md text-on-surface font-medium truncate">
                            ${escapeHtml(q.question_text)}
                        </p>
                        <div class="flex flex-wrap items-center gap-space-md text-on-surface-variant font-label-sm text-label-sm">
                            <span>Kunci Jawaban: <strong class="text-secondary">${escapeHtml(keyPreview)}</strong></span>
                            <span>•</span>
                            <span>Tingkat Kesulitan: ${diffLabel}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                        <button type="button" onclick="removeQuestion(${id})" class="p-1.5 rounded hover:bg-surface-container-highest text-on-surface-variant hover:text-error transition-colors" title="Hapus dari Ujian">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                        </button>
                    </div>
                    <input type="hidden" name="question_ids[]" value="${id}">
                `;
                container.appendChild(card);
                index++;
            });

            // Update Tab counts
            document.getElementById('tabCountAll').textContent = selectedQuestions.size;
            document.getElementById('tabCountMC').textContent = mcCount;
            document.getElementById('tabCountSA').textContent = saCount;
            document.getElementById('tabCountEssay').textContent = essayCount;

            // Update Section header badges
            document.getElementById('selectedCountDisplay').textContent = `${selectedQuestions.size} Soal Terpilih`;
            document.getElementById('totalScoreDisplay').textContent = `Total Bobot: ${totalScore} Poin`;

            const weightBadge = document.getElementById('weightStatusBadge');
            if(totalScore === 100) {
                weightBadge.className = 'px-2 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed font-label-sm text-[11px] font-semibold';
                weightBadge.textContent = 'Bobot Pas (100)';
            } else {
                weightBadge.className = 'px-2 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-[11px] font-semibold';
                weightBadge.textContent = `Bobot: ${totalScore} Poin`;
            }
        }
    }

    function filterSelectedQuestions(type) {
        currentFilterType = type;
        const tabs = document.querySelectorAll('#selectedFilterTabs .q-tab');
        tabs.forEach(tab => {
            if(tab.getAttribute('data-type') === type) {
                tab.className = 'q-tab active px-space-sm py-1 rounded bg-surface-container-lowest text-on-surface font-label-sm text-label-sm shadow-sm transition-all';
            } else {
                tab.className = 'q-tab px-space-sm py-1 rounded text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm transition-all';
            }
        });

        const cards = document.querySelectorAll('.selected-q-card');
        cards.forEach(card => {
            const cardType = card.getAttribute('data-type');
            if(type === 'all' || cardType === type) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    }

    function updateSummary() {
        const title = document.getElementById('examTitle').value;
        const subject = document.getElementById('subjectFilter').value;
        const classroom = document.getElementById('classroomSelect').value;
        const questionCount = selectedQuestions.size;

        let completed = 0;
        if(title) completed += 25;
        if(subject) completed += 25;
        if(classroom) completed += 25;
        if(questionCount > 0) completed += 25;

        const progressBar = document.getElementById('validationProgressBar');
        const statusBadge = document.getElementById('validationStatusBadge');
        const statusText = document.getElementById('validationStatusText');

        progressBar.style.width = `${completed}%`;

        if(completed === 100) {
            progressBar.className = 'bg-secondary h-full rounded-full transition-all duration-300';
            statusBadge.className = 'inline-flex items-center gap-1 text-secondary font-label-sm text-label-sm font-semibold';
            statusBadge.querySelector('.material-symbols-outlined').textContent = 'check_circle';
            statusText.textContent = 'Siap Dijadwalkan';
        } else {
            progressBar.className = 'bg-primary h-full rounded-full transition-all duration-300';
            statusBadge.className = 'inline-flex items-center gap-1 text-on-surface-variant font-label-sm text-label-sm font-semibold';
            statusBadge.querySelector('.material-symbols-outlined').textContent = 'pending';
            statusText.textContent = `Kelengkapan: ${completed}%`;
        }
    }

    function submitExam(action) {
        const title = document.getElementById('examTitle').value.trim();
        const subject = document.getElementById('subjectFilter').value;
        const classroom = document.getElementById('classroomSelect').value;

        if(!title) {
            alert('Silakan isi Nama Ujian terlebih dahulu!');
            document.getElementById('examTitle').focus();
            return;
        }
        if(!subject) {
            alert('Silakan pilih Mata Pelajaran terlebih dahulu!');
            document.getElementById('subjectFilter').focus();
            return;
        }
        if(!classroom) {
            alert('Silakan pilih Target Kelas peserta ujian!');
            document.getElementById('classroomSelect').focus();
            return;
        }
        if(selectedQuestions.size === 0) {
            alert('Silakan masukkan minimal 1 butir soal ke dalam ujian dengan menekan tombol "+ Tambah dari Bank Soal"!');
            openQuestionModal();
            return;
        }

        document.getElementById('formAction').value = action;
        document.getElementById('examForm').submit();
    }

    function padZero(num) {
        return num < 10 ? '0' + num : num;
    }

    function escapeHtml(text) {
        if(!text) return '';
        const stripped = text.replace(/<[^>]*>/g, '');
        const div = document.createElement('div');
        div.textContent = stripped;
        return div.innerHTML;
    }
</script>
@endpush
@endsection
