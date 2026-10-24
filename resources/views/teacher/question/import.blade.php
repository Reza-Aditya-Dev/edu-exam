@extends('layouts.teacher')

@section('title', 'Impor Butir Soal Excel / CSV — EduExam')
@section('page_title', 'Bank Soal')

@section('teacher-content')
<div class="flex flex-col w-full pb-20">

    <!-- Top Breadcrumb & Header -->
    <div class="flex flex-wrap items-center justify-between gap-space-md py-space-sm mb-space-lg">
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-space-xs text-on-surface-variant font-label-md text-label-md">
                <a href="{{ route('teacher.questions.index') }}" class="hover:text-primary transition-colors">Bank Soal</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface-variant">Modul Evaluasi Guru</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-semibold">Impor Soal Excel / CSV</span>
            </div>
            <div class="flex items-baseline gap-space-sm">
                <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">Impor Butir Soal Massal</h1>
                <span class="inline-flex items-center gap-1 px-space-sm py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-label-sm text-label-sm font-semibold border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Format .xlsx &amp; .csv
                </span>
            </div>
        </div>

        <!-- Action / Download Template Buttons -->
        <div class="flex items-center gap-space-sm flex-wrap">
            <a href="{{ route('teacher.questions.index') }}" class="px-space-md py-2.5 rounded-xl bg-surface-container-high text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest transition-colors font-label-lg text-label-lg">
                Kembali ke Bank Soal
            </a>
            <div class="inline-flex rounded-xl shadow-sm border border-slate-200 bg-surface-container-lowest p-0.5">
                <a href="{{ route('teacher.questions.template', ['format' => 'xlsx']) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-emerald-700 hover:bg-emerald-50 font-label-md text-label-md font-semibold transition-all" title="Unduh template berformat Microsoft Excel">
                    <span class="material-symbols-outlined text-[18px]">table_view</span>
                    <span>Download Template Excel</span>
                </a>
                <span class="w-[1px] bg-slate-200 my-1"></span>
                <a href="{{ route('teacher.questions.template', ['format' => 'csv']) }}" class="inline-flex items-center gap-1 px-3 py-2 rounded-lg text-on-surface-variant hover:bg-slate-100 font-label-md text-label-md transition-all" title="Unduh template berformat CSV">
                    <span>.CSV</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="mb-space-lg p-space-md rounded-2xl bg-error-container text-on-error-container text-xs flex items-center gap-3 shadow-sm border border-red-200">
            <span class="material-symbols-outlined text-[22px] flex-shrink-0">error</span>
            <div class="flex-1 font-medium">{{ session('error') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-space-lg p-space-md rounded-2xl bg-error-container text-on-error-container text-xs shadow-sm border border-red-200">
            <div class="font-bold mb-1 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">warning</span>
                Terdapat kesalahan pada berkas / input:
            </div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg">
        <!-- Form Upload Card (Left / Main) -->
        <div class="lg:col-span-8 flex flex-col gap-space-lg">
            <form action="{{ route('teacher.questions.import.preview') }}" method="POST" enctype="multipart/form-data" id="importForm" class="flex flex-col gap-space-lg bg-surface-container-lowest p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100">
                @csrf

                <!-- Destination Settings -->
                <div class="flex flex-col gap-space-sm pb-space-md border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-xs">1</span>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Penempatan Paket &amp; Target Soal</h2>
                    </div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Tentukan Mata Pelajaran dan target Kelas untuk butir soal yang diimpor ke Bank Soal.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
                    <!-- Mata Pelajaran -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold">
                            Mata Pelajaran <span class="text-error">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <select name="subject_id" id="subjectSelect" class="w-full h-11 pl-3 pr-8 rounded-xl bg-surface-container-low font-body-sm text-body-sm text-on-surface appearance-none focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm cursor-pointer" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($subjects as $sub)
                                    <option value="{{ $sub->id }}" {{ (old('subject_id', $selectedSubjectId) == $sub->id) ? 'selected' : '' }}>
                                        {{ $sub->name }} ({{ $sub->code ?? 'MAPEL' }})
                                    </option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-3 pointer-events-none text-on-surface-variant text-[18px]">expand_more</span>
                        </div>
                    </div>

                    <!-- Target Kelas -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold">
                            Target Kelas / Rombel <span class="font-normal text-on-surface-variant">(Opsional)</span>
                        </label>
                        <div class="relative flex items-center">
                            <select name="classroom_id" id="classroomSelect" class="w-full h-11 pl-3 pr-8 rounded-xl bg-surface-container-low font-body-sm text-body-sm text-on-surface appearance-none focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm cursor-pointer">
                                <option value="">Semua Kelas (Umum)</option>
                                @foreach($classrooms as $cls)
                                    <option value="{{ $cls->id }}" {{ (old('classroom_id', $selectedClassroomId) == $cls->id) ? 'selected' : '' }}>
                                        Kelas {{ $cls->name }} (Tingkat {{ $cls->grade }})
                                    </option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-3 pointer-events-none text-on-surface-variant text-[18px]">expand_more</span>
                        </div>
                    </div>

                    <!-- Topik / Materi Default -->
                    <div class="md:col-span-2 flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold">
                            Topik / Materi Default <span class="font-normal text-on-surface-variant">(Digunakan jika kolom Topik di berkas kosong)</span>
                        </label>
                        <input type="text" name="topic" value="{{ old('topic') }}" placeholder="Contoh: Asesmen Sumatif Bab 2 Sistem Ekskresi" class="w-full h-11 px-3 rounded-xl bg-surface-container-low font-body-sm text-body-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:bg-surface-container-lowest border border-transparent focus:border-indigo-300 shadow-sm transition-all"/>
                    </div>
                </div>

                <!-- File Dropzone -->
                <div class="flex flex-col gap-space-sm pt-space-md border-t border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-xs">2</span>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Unggah Berkas Spreadsheet (.xlsx / .csv)</h2>
                    </div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Pilih atau seret berkas spreadsheet yang berisi butir-butir soal sesuai template resmi.</p>
                </div>

                <div id="dropzoneArea" class="relative flex flex-col items-center justify-center border-2 border-dashed border-slate-300 hover:border-primary/60 rounded-2xl p-8 bg-surface-container-low/40 hover:bg-surface-container-low transition-all cursor-pointer group text-center">
                    <input type="file" name="file" id="fileInput" accept=".xlsx,.csv,.txt" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required onchange="handleFileSelected(this)"/>

                    <div class="w-16 h-16 rounded-2xl bg-primary-fixed/60 text-primary flex items-center justify-center mb-space-sm group-hover:scale-110 transition-transform shadow-sm">
                        <span class="material-symbols-outlined text-[32px]">upload_file</span>
                    </div>

                    <div class="flex flex-col gap-1 items-center">
                        <span class="font-label-lg text-label-lg font-bold text-on-surface group-hover:text-primary transition-colors">
                            Pilih berkas Excel (.xlsx) atau CSV
                        </span>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">
                            atau seret &amp; letakkan berkas di area ini
                        </span>
                        <span class="text-[11px] text-on-surface-variant/80 mt-1">
                            Maksimum ukuran berkas: <strong>5 MB</strong>
                        </span>
                    </div>

                    <!-- File selected preview badge -->
                    <div id="fileSelectedBadge" class="hidden mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-surface-container-lowest text-emerald-700 font-label-md text-label-md shadow-sm border border-emerald-200">
                        <span class="material-symbols-outlined text-[20px] text-emerald-600">check_circle</span>
                        <span id="selectedFileName" class="font-semibold max-w-xs truncate">nama_file.xlsx</span>
                        <span id="selectedFileSize" class="text-xs text-on-surface-variant font-normal"></span>
                    </div>
                </div>

                <!-- Submit / Preview Button -->
                <div class="flex items-center justify-end gap-space-sm pt-space-md border-t border-slate-100">
                    <a href="{{ route('teacher.questions.index') }}" class="px-space-md py-2.5 rounded-xl bg-surface-container-high text-on-surface-variant hover:text-on-surface transition-colors font-label-md text-label-md">
                        Batal
                    </a>
                    <button type="submit" id="btnPreview" class="inline-flex items-center gap-2 px-space-lg py-2.5 rounded-xl bg-primary text-white font-label-lg text-label-lg shadow-md hover:bg-primary-dark active:scale-[0.98] transition-all">
                        <span class="material-symbols-outlined text-[20px]">preview</span>
                        <span>Pratinjau Soal &amp; Validasi</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Sidebar Guidelines (Right) -->
        <div class="lg:col-span-4 flex flex-col gap-space-md">
            <!-- Download Template Banner Card -->
            <div class="p-5 rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-800 text-white shadow-md relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-white/10 blur-xl pointer-events-none"></div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-[22px]">download</span>
                    <h3 class="font-headline-sm text-headline-sm font-bold">Template Resmi</h3>
                </div>
                <p class="text-xs text-emerald-100 leading-relaxed mb-4">
                    Gunakan template resmi EduExam agar kolom dan format pilihan ganda, benar/salah, isian, dan esai terdeteksi otomatis dengan akurat.
                </p>
                <div class="flex flex-col sm:flex-row gap-2">
                    <a href="{{ route('teacher.questions.template', ['format' => 'xlsx']) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-white text-emerald-900 font-label-sm text-label-sm font-bold hover:bg-emerald-50 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">table_view</span>
                        <span>Unduh .XLSX</span>
                    </a>
                    <a href="{{ route('teacher.questions.template', ['format' => 'csv']) }}" class="inline-flex items-center justify-center gap-1 py-2 px-3 rounded-xl bg-emerald-700/60 text-white font-label-sm text-label-sm hover:bg-emerald-700 transition-all border border-emerald-500/40">
                        <span>.CSV</span>
                    </a>
                </div>
            </div>

            <!-- Petunjuk Format Kolom -->
            <div class="p-5 rounded-2xl bg-surface-container-lowest shadow-sm border border-slate-100 flex flex-col gap-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="material-symbols-outlined text-[20px] text-primary">info</span>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Aturan Format Kolom</h3>
                </div>

                <div class="space-y-3 text-xs text-on-surface-variant">
                    <div>
                        <span class="font-bold text-on-surface block mb-0.5">1. Jenis Soal (`type`):</span>
                        <ul class="list-disc pl-4 space-y-0.5 text-[11.5px]">
                            <li><code>multiple_choice</code>: Pilihan Ganda (opsi A s/d E)</li>
                            <li><code>true_false</code>: Benar / Salah (A=Benar, B=Salah)</li>
                            <li><code>short_answer</code>: Isian Singkat</li>
                            <li><code>essay</code>: Esai / Uraian Panjang</li>
                        </ul>
                    </div>

                    <div>
                        <span class="font-bold text-on-surface block mb-0.5">2. Kunci Jawaban:</span>
                        <p class="text-[11.5px] leading-relaxed">
                            Untuk Pilihan Ganda, isi huruf pilihan (misal: <strong>A</strong>, <strong>B</strong>, <strong>C</strong>, dst). Untuk Benar/Salah, isi <strong>Benar</strong> atau <strong>Salah</strong>. Untuk Isian Singkat, isi kata kunci jawaban.
                        </p>
                    </div>

                    <div>
                        <span class="font-bold text-on-surface block mb-0.5">3. Bobot Nilai &amp; Kesulitan:</span>
                        <p class="text-[11.5px] leading-relaxed">
                            Bobot nilai berupa angka (default 1.00 atau 2.5). Tingkat kesulitan diisi <code>easy</code> (mudah), <code>medium</code> (sedang), atau <code>hard</code> (sulit).
                        </p>
                    </div>

                    <div class="p-3 rounded-xl bg-indigo-50/70 text-indigo-900 border border-indigo-100 text-[11px] leading-relaxed">
                        <strong class="font-bold">Keamanan Data:</strong> Butir soal tidak langsung disimpan ke database. Anda dapat meninjau pratinjau, memeriksa soal valid dan tidak valid, sebelum menekan tombol konfirmasi.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function handleFileSelected(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const badge = document.getElementById('fileSelectedBadge');
            const nameEl = document.getElementById('selectedFileName');
            const sizeEl = document.getElementById('selectedFileSize');

            nameEl.textContent = file.name;
            const sizeKb = (file.size / 1024).toFixed(1);
            sizeEl.textContent = `(${sizeKb} KB)`;
            badge.classList.remove('hidden');
        }
    }
</script>
@endsection
