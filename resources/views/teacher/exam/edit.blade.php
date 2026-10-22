@extends('layouts.teacher')

@section('title', 'Edit Ujian — EduExam')
@section('page_title', 'Edit Ujian')

@push('teacher-styles')
<style>
    .step-container { 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        margin-bottom: 32px; 
        position: relative; 
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    .step-line {
        position: absolute; 
        top: 20px; 
        left: 15%; 
        right: 15%; 
        height: 2px; 
        background: #e2e8f0; 
        z-index: 1; 
    }
    .step-item { 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        z-index: 2; 
        flex: 1; 
        opacity: 0.5; 
        transition: all .3s; 
    }
    .step-item.active { opacity: 1; }
    .step-circle { 
        width: 40px; 
        height: 40px; 
        border-radius: 50%; 
        background: white; 
        border: 2px solid #cbd5e1; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-weight: 800; 
        font-size: 1rem; 
        color: #64748b; 
        margin-bottom: 8px; 
        transition: all .3s; 
    }
    .step-item.active .step-circle { 
        background: #4f46e5; 
        border-color: #4f46e5; 
        color: white; 
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }
    .step-item.completed .step-circle { 
        background: #059669; 
        border-color: #059669; 
        color: white; 
    }
    .step-label { 
        font-size: 0.8125rem; 
        font-weight: 600; 
        color: #334155; 
    }
    
    .step-content { display: none; }
    .step-content.active { display: block; animation: fadeIn .3s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

    /* Question Selection Bank */
    .q-bank-container { 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 20px; 
        align-items: stretch; 
    }
    @media (max-width: 1024px) { 
        .q-bank-container { grid-template-columns: 1fr; } 
    }
    .q-list-panel, .q-selected-panel { 
        background: white; 
        border-radius: 16px; 
        border: 1px solid #e2e8f0; 
        display: flex; 
        flex-direction: column; 
        height: 600px; 
        overflow: hidden;
    }
    .q-panel-header { 
        padding: 16px 20px; 
        border-bottom: 1px solid #e2e8f0; 
        background: #f8fafc; 
        font-weight: 700; 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
    }
    .q-panel-body { 
        padding: 16px; 
        flex: 1; 
        overflow-y: auto; 
        display: flex; 
        flex-direction: column; 
        gap: 10px; 
        background: #f8fafc; 
    }
    
    .q-item { 
        background: white; 
        border: 1px solid #e2e8f0; 
        border-radius: 12px; 
        padding: 12px 14px; 
        cursor: pointer; 
        transition: all .2s; 
        position: relative; 
    }
    .q-item:hover { 
        border-color: #4f46e5; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.04); 
    }
    .q-item-text { 
        font-size: 0.8125rem; 
        line-height: 1.5; 
        color: #1e293b;
        margin-bottom: 6px; 
        display: -webkit-box; 
        -webkit-line-clamp: 2; 
        -webkit-box-orient: vertical; 
        overflow: hidden; 
        padding-right: 36px;
    }
    .q-item-meta { 
        display: flex; 
        align-items: center;
        gap: 8px; 
        font-size: 0.75rem; 
        color: #64748b; 
    }
    
    .add-btn { 
        position: absolute; 
        right: 12px; 
        top: 50%; 
        transform: translateY(-50%); 
        background: #eef2ff; 
        border: none; 
        width: 32px; 
        height: 32px; 
        border-radius: 50%; 
        font-weight: bold; 
        cursor: pointer; 
        color: #4f46e5; 
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .q-item:hover .add-btn { 
        background: #4f46e5; 
        color: white; 
    }
    
    .remove-btn { 
        background: #fee2e2; 
        border: none; 
        color: #dc2626; 
        width: 28px; 
        height: 28px; 
        border-radius: 8px; 
        font-weight: bold; 
        cursor: pointer; 
        flex-shrink: 0; 
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .remove-btn:hover { 
        background: #dc2626; 
        color: white; 
    }
    
    .selected-q-row { 
        background: white; 
        border: 1px solid #e2e8f0; 
        border-radius: 12px; 
        padding: 10px 14px; 
        display: flex; 
        align-items: center; 
        gap: 12px; 
        box-shadow: 0 1px 2px rgba(0,0,0,.03); 
    }
</style>
@endpush

@section('teacher-content')

<!-- Stepper Bar -->
<div class="step-container" id="stepper">
    <div class="step-line"></div>
    <div class="step-item active" id="step-nav-1">
        <div class="step-circle">1</div>
        <div class="step-label">Pengaturan Dasar</div>
    </div>
    <div class="step-item" id="step-nav-2">
        <div class="step-circle">2</div>
        <div class="step-label">Pilih Butir Soal</div>
    </div>
    <div class="step-item" id="step-nav-3">
        <div class="step-circle">3</div>
        <div class="step-label">Konfirmasi & Simpan</div>
    </div>
</div>

<form action="{{ route('teacher.exams.update', $exam->id) }}" method="POST" id="examForm">
    @csrf
    @method('PUT')
    
    <!-- STEP 1: PENGATURAN DASAR -->
    <div class="step-content active" id="step-1">
        <div class="card max-w-3xl mx-auto overflow-hidden">
            <div class="card-header p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h3 class="font-headline font-bold text-slate-900 text-lg">Edit Informasi Ujian</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Perbarui nama ujian, jadwal waktu pengerjaan, atau batas nilai KKM.</p>
                </div>
                <button type="button" onclick="confirmDeleteExamFromEdit()" class="btn btn-secondary text-xs font-semibold text-rose-600 hover:bg-rose-50 hover:border-rose-300 px-3 py-1.5 flex items-center gap-1.5 shrink-0" title="Hapus Ujian Ini">
                    <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
                    <span>Hapus Ujian Ini</span>
                </button>
            </div>
            
            <div class="card-body p-6 md:p-8">
                <div class="mb-5">
                    <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                        Judul Ujian <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" class="form-control text-sm" required value="{{ old('title', $exam->title) }}">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                            Mata Pelajaran <span class="text-slate-400 font-normal lowercase">(terkunci)</span>
                        </label>
                        <select name="subject_id" id="subjectFilter" class="form-control text-sm bg-slate-100 text-slate-600" disabled>
                            <option value="{{ $exam->subject_id }}">{{ $exam->subject->name }}</option>
                        </select>
                        <input type="hidden" id="hiddenSubjectFilter" value="{{ $exam->subject_id }}">
                    </div>
                    <div>
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                            Tipe Ujian <span class="text-rose-500">*</span>
                        </label>
                        <select name="exam_type" class="form-control text-sm font-medium" required>
                            <option value="UTS" {{ old('exam_type', $exam->exam_type) == 'UTS' ? 'selected' : '' }}>UTS</option>
                            <option value="UAS" {{ old('exam_type', $exam->exam_type) == 'UAS' ? 'selected' : '' }}>UAS</option>
                            <option value="UH" {{ old('exam_type', $exam->exam_type) == 'UH' ? 'selected' : '' }}>Ulangan Harian</option>
                            <option value="Quiz" {{ old('exam_type', $exam->exam_type) == 'Quiz' ? 'selected' : '' }}>Quiz / Latihan</option>
                            <option value="Remedial" {{ old('exam_type', $exam->exam_type) == 'Remedial' ? 'selected' : '' }}>Remedial</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                            Kelas Peserta <span class="text-slate-400 font-normal lowercase">(terkunci)</span>
                        </label>
                        <select name="classroom_id" class="form-control text-sm bg-slate-100 text-slate-600" disabled>
                            <option value="{{ $exam->classroom_id }}">{{ $exam->classroom->name }} ({{ $exam->classroom->academicYear->name ?? '' }})</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                            Tahun Ajaran <span class="text-slate-400 font-normal lowercase">(terkunci)</span>
                        </label>
                        <select name="academic_year_id" class="form-control text-sm bg-slate-100 text-slate-600" disabled>
                            <option value="{{ $exam->academic_year_id }}">{{ $exam->academicYear->name ?? 'Tahun Berjalan' }}</option>
                        </select>
                    </div>
                </div>
                
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl mb-5">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-headline font-bold text-slate-800 text-xs uppercase tracking-wider">Waktu &amp; Nilai Kelulusan</h4>
                        <span id="tzBadge" class="text-[11px] font-bold text-indigo-700 px-2 py-0.5 rounded-full bg-indigo-50 border border-indigo-200">
                            {{ $exam->timezone_code }} ({{ $exam->timezone === 'Asia/Jayapura' ? 'UTC+9' : ($exam->timezone === 'Asia/Makassar' ? 'UTC+8' : 'UTC+7') }})
                        </span>
                    </div>

                    <!-- Pilihan Zona Waktu -->
                    <div class="mb-3 p-3 bg-white border border-slate-200/80 rounded-xl">
                        <label class="text-[11px] font-semibold text-slate-700 block mb-1">
                            Zona Waktu Ujian <span class="text-rose-500">*</span>
                        </label>
                        <select name="timezone" id="examTimezone" required class="form-control text-xs font-semibold" onchange="onTimezoneChanged(); validateDateTimeInputs();">
                            <option value="Asia/Jakarta" {{ old('timezone', $exam->timezone ?? 'Asia/Jakarta') === 'Asia/Jakarta' ? 'selected' : '' }}>WIB — Waktu Indonesia Barat (Asia/Jakarta)</option>
                            <option value="Asia/Makassar" {{ old('timezone', $exam->timezone ?? 'Asia/Jakarta') === 'Asia/Makassar' ? 'selected' : '' }}>WITA — Waktu Indonesia Tengah (Asia/Makassar)</option>
                            <option value="Asia/Jayapura" {{ old('timezone', $exam->timezone ?? 'Asia/Jakarta') === 'Asia/Jayapura' ? 'selected' : '' }}>WIT — Waktu Indonesia Timur (Asia/Jayapura)</option>
                        </select>
                        <p class="text-[11px] text-slate-500 mt-1">
                            Pilih zona waktu lokasi sekolah tempat ujian dilaksanakan agar jadwal buka dan tutup ujian tepat di wilayah Anda.
                        </p>
                        @error('timezone') <span class="text-xs text-rose-500 font-medium block mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-3">
                        <div>
                            <label class="text-[11px] font-semibold text-slate-600 block mb-1">Tanggal Ujian <span class="text-rose-500">*</span></label>
                            <input type="date" name="exam_date" id="examDate" min="{{ date('Y-m-d') }}" class="form-control text-xs" required value="{{ old('exam_date', $exam->exam_date ? $exam->exam_date->format('Y-m-d') : date('Y-m-d')) }}" onchange="validateDateTimeInputs(); calculateDuration();">
                            @error('exam_date') <span class="text-xs text-rose-500 font-medium block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-600 block mb-1">Mulai <span class="text-rose-500">*</span></label>
                            <input type="time" name="start_time" id="startTime" class="form-control text-xs" required value="{{ old('start_time', substr($exam->start_time, 0, 5)) }}" onchange="validateDateTimeInputs(); calculateDuration();">
                            @error('start_time') <span class="text-xs text-rose-500 font-medium block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-600 block mb-1">Selesai <span class="text-rose-500">*</span></label>
                            <input type="time" name="end_time" id="endTime" class="form-control text-xs" required value="{{ old('end_time', substr($exam->end_time, 0, 5)) }}" onchange="validateDateTimeInputs(); calculateDuration();">
                            @error('end_time') <span class="text-xs text-rose-500 font-medium block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-slate-600 block mb-1">Durasi (Menit) <span class="text-rose-500">*</span></label>
                            <input type="number" name="duration_minutes" id="durationMinutes" class="form-control text-xs font-bold text-indigo-700" required value="{{ old('duration_minutes', $exam->duration_minutes) }}" min="5">
                            @error('duration_minutes') <span class="text-xs text-rose-500 font-medium block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div id="clientTimeWarning" class="hidden text-xs text-rose-600 font-medium mb-3 p-2 bg-rose-50 rounded-lg border border-rose-200"></div>
                    
                    <div class="max-w-xs">
                        <label class="text-[11px] font-semibold text-slate-600 block mb-1">Nilai KKM (Batas Lulus) <span class="text-rose-500">*</span></label>
                        <input type="number" name="passing_grade" class="form-control text-xs font-bold text-emerald-700" required value="{{ old('passing_grade', $exam->passing_grade) }}" min="0" max="100">
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                        Instruksi & Tata Tertib <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                    </label>
                    <textarea name="instructions" class="form-control text-sm" rows="3">{{ old('instructions', $exam->instructions) }}</textarea>
                </div>
                
                <div class="flex justify-end pt-4 border-t border-slate-100">
                    <button type="button" class="btn btn-primary text-xs font-bold px-6 py-2.5 shadow-sm flex items-center gap-1.5" onclick="nextStep(1)">
                        Selanjutnya: Pilih Soal
                        <span class="material-symbols-outlined" style="font-size: 16px;">arrow_forward</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- STEP 2: PILIH SOAL -->
    <div class="step-content" id="step-2">
        <div class="q-bank-container">
            
            <!-- PANEL KIRI: BANK SOAL -->
            <div class="q-list-panel">
                <div class="q-panel-header">
                    <span class="font-headline text-slate-800 text-sm">1. Pilih dari Bank Soal</span>
                    <div class="relative">
                        <input type="text" id="qSearch" class="form-control text-xs pl-7 py-1.5" style="width: 180px;" placeholder="Cari soal...">
                        <span class="material-symbols-outlined absolute left-2 top-1/2 -translate-y-1/2 text-slate-400" style="font-size: 14px;">search</span>
                    </div>
                </div>
                <div class="q-panel-body" id="availableQuestions">
                    <div class="text-center text-slate-400 text-sm mt-12">
                        Memuat bank butir soal...
                    </div>
                </div>
            </div>
            
            <!-- PANEL KANAN: SOAL TERPILIH -->
            <div class="q-selected-panel">
                <div class="q-panel-header">
                    <span class="font-headline text-slate-800 text-sm">2. Butir Soal Terpilih</span>
                    <span class="badge badge-primary text-xs font-semibold px-2.5 py-0.5" id="selectedCountBadge">0 Soal</span>
                </div>
                <div class="q-panel-body bg-white" id="selectedQuestions">
                    <div id="emptySelectedMsg" class="text-center text-slate-400 text-sm mt-12">
                        Belum ada butir soal terpilih.<br>Klik tombol <strong>+</strong> pada soal di sebelah kiri.
                    </div>
                </div>
                <div class="p-4 border-t border-slate-200 bg-white flex justify-between items-center">
                    <button type="button" class="btn btn-secondary text-xs px-4 py-2 flex items-center gap-1" onclick="prevStep(2)">
                        <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span>
                        Kembali
                    </button>
                    <button type="button" class="btn btn-primary text-xs font-bold px-6 py-2 shadow-sm flex items-center gap-1.5" onclick="nextStep(2)">
                        Selanjutnya: Konfirmasi
                        <span class="material-symbols-outlined" style="font-size: 16px;">arrow_forward</span>
                    </button>
                </div>
            </div>
            
        </div>
    </div>
    
    <!-- STEP 3: KONFIRMASI -->
    <div class="step-content" id="step-3">
        <div class="card max-w-3xl mx-auto overflow-hidden">
            <div class="card-header p-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-headline font-bold text-slate-900 text-lg">Langkah 3: Konfirmasi & Pengaturan Tambahan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Atur preferensi keamanan CBT sebelum menyimpan perubahan ujian.</p>
            </div>
            
            <div class="card-body p-6 md:p-8">
                
                <div class="p-4 bg-indigo-50 border border-indigo-200 rounded-2xl flex items-center gap-3 text-indigo-900 text-xs mb-6">
                    <span class="material-symbols-outlined text-indigo-600" style="font-size: 22px;">task_alt</span>
                    <div>
                        Ujian Anda saat ini memiliki <strong id="finalQCount" class="font-extrabold text-sm text-indigo-950">0</strong> butir soal terpilih.
                    </div>
                </div>
                
                <h4 class="font-headline font-bold text-slate-800 text-xs uppercase tracking-wider mb-4">Pengaturan Keamanan & Tampilan Soal</h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
                    <label class="p-4 bg-white border border-slate-200 rounded-xl hover:border-indigo-300 transition flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="shuffle_questions" value="1" {{ optional($exam->settings)->shuffle_questions ? 'checked' : '' }} class="mt-0.5 w-4 h-4 rounded text-indigo-600 accent-indigo-600">
                        <div>
                            <div class="font-bold text-xs text-slate-900">Acak Urutan Soal</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Soal akan diacak berbeda untuk setiap siswa.</div>
                        </div>
                    </label>
                    <label class="p-4 bg-white border border-slate-200 rounded-xl hover:border-indigo-300 transition flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="shuffle_options" value="1" {{ optional($exam->settings)->shuffle_options ? 'checked' : '' }} class="mt-0.5 w-4 h-4 rounded text-indigo-600 accent-indigo-600">
                        <div>
                            <div class="font-bold text-xs text-slate-900">Acak Opsi Pilihan Jawaban</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Posisi pilihan A, B, C, D diacak otomatis.</div>
                        </div>
                    </label>
                    <label class="p-4 bg-white border border-slate-200 rounded-xl hover:border-indigo-300 transition flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="show_result_immediately" value="1" {{ optional($exam->settings)->show_result_immediately ? 'checked' : '' }} class="mt-0.5 w-4 h-4 rounded text-indigo-600 accent-indigo-600">
                        <div>
                            <div class="font-bold text-xs text-slate-900">Tampilkan Nilai Langsung</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Siswa dapat langsung melihat skor akhir setelah submit.</div>
                        </div>
                    </label>
                    <label class="p-4 bg-white border border-slate-200 rounded-xl hover:border-indigo-300 transition flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="show_correct_answers" value="1" {{ optional($exam->settings)->show_correct_answers ? 'checked' : '' }} class="mt-0.5 w-4 h-4 rounded text-indigo-600 accent-indigo-600">
                        <div>
                            <div class="font-bold text-xs text-slate-900">Tampilkan Kunci Jawaban</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Hanya tampil setelah seluruh ujian selesai.</div>
                        </div>
                    </label>
                </div>

                <h4 class="font-headline font-bold text-slate-800 text-xs uppercase tracking-wider mb-4 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-rose-600 text-[18px]">security</span>
                    <span>Proteksi Anti-Curang & Integritas Ujian</span>
                </h4>

                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl mb-8 flex flex-col gap-4">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="enable_anti_cheat" value="1" {{ optional($exam->settings)->enable_anti_cheat ?? true ? 'checked' : '' }} class="mt-0.5 w-4 h-4 rounded text-rose-600 accent-rose-600">
                        <div>
                            <div class="font-bold text-xs text-slate-900">Aktifkan Proteksi Layar Penuh & Deteksi Pindah Tab</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Mendeteksi dan mencatat ketika siswa membuka tab browser lain, aplikasi eksternal, atau keluar fullscreen.</div>
                        </div>
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-slate-200/80">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Batas Maksimal Pelanggaran (Auto-Submit):</label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="max_violations" min="1" max="20" value="{{ optional($exam->settings)->max_violations ?? 3 }}" class="w-24 px-3 py-1.5 text-xs font-bold font-mono border rounded-lg bg-white text-slate-800">
                                <span class="text-xs text-slate-500">kali kesempatan</span>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1">Ujian otomatis dikumpulkan jika siswa keluar melebihi batas ini.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Otomatis Potong Nilai per Pelanggaran:</label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="violation_penalty_points" min="0" max="50" step="1" value="{{ round(optional($exam->settings)->violation_penalty_points ?? 0) }}" class="w-24 px-3 py-1.5 text-xs font-bold font-mono border rounded-lg bg-white text-slate-800">
                                <span class="text-xs text-slate-500">poin per pelanggaran</span>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1">Isi 0 jika hanya ingin mencatat ke laporan guru tanpa auto-potong.</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-between items-center pt-6 border-t border-slate-100 flex-wrap gap-3">
                    <button type="button" class="btn btn-secondary text-xs px-4 py-2.5 flex items-center gap-1" onclick="prevStep(3)">
                        <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span>
                        Kembali
                    </button>
                    <div class="flex gap-2.5">
                        <a href="{{ route('teacher.exams.index') }}" class="btn btn-secondary text-xs font-semibold px-4 py-2.5">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary text-xs font-bold px-6 py-2.5 shadow-sm flex items-center gap-1.5">
                            <span class="material-symbols-outlined" style="font-size: 18px;">save</span>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    let selectedQuestions = new Map();
    let availableQuestions = [];

    // INISIALISASI SOAL YANG SUDAH TERPILIH
    @foreach($exam->questions as $q)
    selectedQuestions.set({{ $q->id }}, {
        id: {{ $q->id }},
        question_text: `{!! addslashes(str_replace(["\r", "\n"], '', strip_tags($q->question_text))) !!}`,
        type: '{{ $q->type }}',
        score: {{ $q->score }}
    });
    @endforeach

    document.addEventListener("DOMContentLoaded", function() {
        renderSelectedQuestions();
        document.getElementById('finalQCount').textContent = selectedQuestions.size;
        loadQuestions();
    });

    function onTimezoneChanged() {
        const tzSelect = document.getElementById('examTimezone');
        const tzBadge = document.getElementById('tzBadge');
        if (!tzSelect) return;

        const val = tzSelect.value;
        let code = 'WIB';
        let utc = 'UTC+7';
        if (val === 'Asia/Makassar') {
            code = 'WITA';
            utc = 'UTC+8';
        } else if (val === 'Asia/Jayapura') {
            code = 'WIT';
            utc = 'UTC+9';
        }

        if (tzBadge) {
            tzBadge.textContent = `${code} (${utc})`;
        }
    }

    function getNowInSelectedTimezone(timeZone) {
        const now = new Date();
        const formatter = new Intl.DateTimeFormat('en-CA', {
            timeZone: timeZone,
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });
        const parts = formatter.formatToParts(now);
        let y = '', m = '', d = '', h = '', min = '';
        for (const p of parts) {
            if (p.type === 'year') y = p.value;
            if (p.type === 'month') m = p.value;
            if (p.type === 'day') d = p.value;
            if (p.type === 'hour') h = p.value;
            if (p.type === 'minute') min = p.value;
        }
        if (h === '24') h = '00';
        return {
            todayStr: `${y}-${m}-${d}`,
            currentHm: `${h}:${min}`
        };
    }

    function validateDateTimeInputs() {
        const dateInput = document.getElementById('examDate');
        const startInput = document.getElementById('startTime');
        const endInput = document.getElementById('endTime');
        const tzSelect = document.getElementById('examTimezone');
        const warning = document.getElementById('clientTimeWarning');
        if (!dateInput || !startInput || !endInput || !warning) return true;

        warning.classList.add('hidden');
        warning.textContent = '';

        const selectedDate = dateInput.value;
        const selectedStart = startInput.value;
        const selectedEnd = endInput.value;
        const selectedTz = tzSelect ? tzSelect.value : 'Asia/Jakarta';
        const tzCode = selectedTz === 'Asia/Makassar' ? 'WITA' : (selectedTz === 'Asia/Jayapura' ? 'WIT' : 'WIB');

        const { todayStr, currentHm } = getNowInSelectedTimezone(selectedTz);

        if (selectedDate && selectedDate < todayStr) {
            warning.textContent = `Tanggal pelaksanaan tidak boleh lebih awal dari hari ini di zona ${tzCode} (${todayStr}).`;
            warning.classList.remove('hidden');
            return false;
        }

        if (selectedDate && selectedDate === todayStr && selectedStart && selectedStart < currentHm) {
            warning.textContent = `Waktu mulai ujian (${selectedStart}) tidak boleh lebih awal dari waktu saat ini (${currentHm} ${tzCode}).`;
            warning.classList.remove('hidden');
            return false;
        }

        if (selectedStart && selectedEnd && selectedEnd <= selectedStart) {
            warning.textContent = 'Waktu selesai ujian harus lebih besar (setelah) dari waktu mulai ujian.';
            warning.classList.remove('hidden');
            return false;
        }

        return true;
    }

    function calculateDuration() {
        const start = document.getElementById('startTime').value;
        const end = document.getElementById('endTime').value;
        if(start && end) {
            const [sh, sm] = start.split(':').map(Number);
            const [eh, em] = end.split(':').map(Number);
            const diff = (eh * 60 + em) - (sh * 60 + sm);
            if(diff > 0) {
                document.getElementById('durationMinutes').value = diff;
            } else {
                document.getElementById('durationMinutes').value = 0;
            }
        }
    }

    function nextStep(current) {
        if(current === 1) {
            if(!validateDateTimeInputs()) {
                const warning = document.getElementById('clientTimeWarning');
                alert(warning && warning.textContent ? warning.textContent : 'Jadwal pelaksanaan ujian tidak valid!');
                return;
            }
        }
        if(current === 2) {
            if(selectedQuestions.size === 0) { alert('Silakan pilih minimal 1 butir soal untuk ujian ini!'); return; }
            document.getElementById('finalQCount').textContent = selectedQuestions.size;
        }
        
        document.getElementById(`step-${current}`).classList.remove('active');
        document.getElementById(`step-${current+1}`).classList.add('active');
        
        document.getElementById(`step-nav-${current}`).classList.add('completed');
        document.getElementById(`step-nav-${current+1}`).classList.add('active');
    }
    
    function prevStep(current) {
        document.getElementById(`step-${current}`).classList.remove('active');
        document.getElementById(`step-${current-1}`).classList.add('active');
        
        document.getElementById(`step-nav-${current}`).classList.remove('active');
        document.getElementById(`step-nav-${current-1}`).classList.remove('completed');
    }
    
    function loadQuestions() {
        const subjId = document.getElementById('hiddenSubjectFilter').value;
        const search = document.getElementById('qSearch').value;
        const container = document.getElementById('availableQuestions');
        
        container.innerHTML = '<div class="text-center text-slate-400 text-xs py-8">Memuat bank butir soal...</div>';
        
        fetch(`/guru/api/soal?subject_id=${subjId}&search=${encodeURIComponent(search)}`)
            .then(res => res.json())
            .then(data => {
                availableQuestions = data;
                renderAvailableQuestions();
            })
            .catch(() => {
                container.innerHTML = '<div class="text-center text-rose-500 text-xs py-8">Gagal memuat bank soal.</div>';
            });
    }
    
    document.getElementById('qSearch').addEventListener('input', function() {
        clearTimeout(this.timer);
        this.timer = setTimeout(loadQuestions, 400);
    });
    
    function renderAvailableQuestions() {
        const container = document.getElementById('availableQuestions');
        container.innerHTML = '';
        
        if(availableQuestions.length === 0) {
            container.innerHTML = '<div class="text-center text-slate-400 text-xs py-8">Tidak ada butir soal ditemukan.</div>';
            return;
        }
        
        availableQuestions.forEach(q => {
            if(selectedQuestions.has(q.id)) return;
            
            const div = document.createElement('div');
            div.className = 'q-item';
            div.innerHTML = `
                <div class="q-item-text">${q.question_text.replace(/<[^>]*>/g, '').substring(0, 120)}...</div>
                <div class="q-item-meta">
                    <span class="badge badge-gray text-[10px]">${q.type === 'multiple_choice' ? 'PG' : 'Lainnya'}</span>
                    <span class="font-semibold text-slate-600">Bobot: ${q.score}</span>
                </div>
                <button type="button" class="add-btn" onclick="addQuestion(${q.id})" title="Tambahkan ke Ujian">+</button>
            `;
            container.appendChild(div);
        });
    }
    
    function addQuestion(id) {
        const q = availableQuestions.find(x => x.id === id);
        if(q && !selectedQuestions.has(id)) {
            selectedQuestions.set(id, q);
            renderAvailableQuestions();
            renderSelectedQuestions();
        }
    }
    
    function removeQuestion(id) {
        selectedQuestions.delete(id);
        renderAvailableQuestions();
        renderSelectedQuestions();
    }
    
    function renderSelectedQuestions() {
        const container = document.getElementById('selectedQuestions');
        const badge = document.getElementById('selectedCountBadge');
        container.innerHTML = '';
        
        badge.textContent = `${selectedQuestions.size} Soal`;
        
        if(selectedQuestions.size === 0) {
            container.innerHTML = '<div id="emptySelectedMsg" class="text-center text-slate-400 text-sm mt-12">Belum ada butir soal terpilih.<br>Klik tombol <strong>+</strong> pada soal di sebelah kiri.</div>';
            return;
        }
        
        let i = 1;
        selectedQuestions.forEach((q, id) => {
            const div = document.createElement('div');
            div.className = 'selected-q-row';
            div.innerHTML = `
                <span class="material-symbols-outlined text-slate-400" style="font-size: 16px;">drag_indicator</span>
                <div class="font-headline font-bold text-indigo-600 text-xs w-5">${i}.</div>
                <div class="flex-1 text-xs text-slate-800 font-medium truncate">
                    ${q.question_text.replace(/<[^>]*>/g, '')}
                </div>
                <button type="button" class="remove-btn" onclick="removeQuestion(${id})" title="Hapus dari Ujian">×</button>
                <input type="hidden" name="question_ids[]" value="${id}">
            `;
            container.appendChild(div);
            i++;
        });
    }

    function confirmDeleteExamFromEdit() {
        if (confirm("PERINGATAN: Apakah Anda yakin ingin menghapus ujian '{{ $exam->title }}' ini?\n\nSeluruh konfigurasi soal dan data ujian akan dihapus permanen.")) {
            document.getElementById('deleteExamFormFromEdit').submit();
        }
    }

    document.getElementById('examForm').addEventListener('submit', function(e) {
        if (!validateDateTimeInputs()) {
            e.preventDefault();
            const warning = document.getElementById('clientTimeWarning');
            alert(warning && warning.textContent ? warning.textContent : 'Jadwal pelaksanaan ujian tidak valid!');
            // Switch back to step 1 to let user see and correct the error
            document.querySelectorAll('.step-content').forEach(s => s.classList.remove('active'));
            document.querySelectorAll('.step-item').forEach(s => {
                s.classList.remove('active');
                s.classList.remove('completed');
            });
            document.getElementById('step-1').classList.add('active');
            document.getElementById('step-nav-1').classList.add('active');
            return false;
        }
    });
</script>

<form id="deleteExamFormFromEdit" action="{{ route('teacher.exams.destroy', $exam->id) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endpush
