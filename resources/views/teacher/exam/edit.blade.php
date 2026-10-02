@extends('layouts.teacher')

@section('title', 'Edit Ujian — EduExam')
@section('page_title', 'Edit Ujian')

@push('teacher-styles')
<style>
    .step-container { display: flex; align-items: center; justify-content: center; margin-bottom: 32px; position: relative; }
    .step-container::before { content: ''; position: absolute; top: 18px; left: 20%; right: 20%; height: 2px; background: var(--gray-200); z-index: 1; }
    .step-item { display: flex; flex-direction: column; align-items: center; z-index: 2; flex: 1; opacity: 0.5; transition: all .3s; }
    .step-item.active { opacity: 1; }
    .step-circle { width: 38px; height: 38px; border-radius: 50%; background: white; border: 2px solid var(--gray-300); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.125rem; color: var(--gray-500); margin-bottom: 8px; transition: all .3s; }
    .step-item.active .step-circle { background: var(--primary); border-color: var(--primary); color: white; }
    .step-item.completed .step-circle { background: var(--success); border-color: var(--success); color: white; }
    .step-label { font-size: 0.875rem; font-weight: 600; color: var(--gray-700); }
    
    .step-content { display: none; }
    .step-content.active { display: block; animation: fadeIn .3s; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* Question Selection Bank */
    .q-bank-container { display: flex; gap: 24px; align-items: flex-start; }
    @media (max-width: 1024px) { .q-bank-container { flex-direction: column; } }
    .q-list-panel, .q-selected-panel { flex: 1; background: white; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); display: flex; flex-direction: column; height: 600px; }
    .q-panel-header { padding: 16px; border-bottom: 1px solid var(--gray-200); background: var(--gray-50); font-weight: 700; display: flex; justify-content: space-between; align-items: center; }
    .q-panel-body { padding: 16px; flex: 1; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; background: var(--gray-50); }
    
    .q-item { background: white; border: 1px solid var(--gray-200); border-radius: var(--radius-md); padding: 12px 16px; cursor: pointer; transition: all .2s; position: relative; }
    .q-item:hover { border-color: var(--primary); box-shadow: var(--shadow-sm); }
    .q-item.selected { border-color: var(--success); background: var(--success-light); }
    .q-item-text { font-size: 0.875rem; line-height: 1.5; margin-bottom: 8px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .q-item-meta { display: flex; gap: 8px; font-size: 0.75rem; color: var(--gray-500); }
    
    .add-btn { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: var(--gray-100); border: none; width: 30px; height: 30px; border-radius: 50%; font-weight: bold; cursor: pointer; color: var(--primary); }
    .q-item:hover .add-btn { background: var(--primary); color: white; }
    
    .remove-btn { background: #fee2e2; border: none; color: var(--danger); width: 28px; height: 28px; border-radius: 4px; font-weight: bold; cursor: pointer; flex-shrink: 0; }
    .remove-btn:hover { background: var(--danger); color: white; }
    
    .selected-q-row { background: white; border: 1px solid var(--gray-200); border-radius: var(--radius-md); padding: 10px; display: flex; align-items: center; gap: 12px; box-shadow: 0 1px 2px rgba(0,0,0,.05); cursor: grab; }
    .selected-q-row.dragging { opacity: 0.5; }
    
    .drag-handle { color: var(--gray-400); cursor: grab; padding: 4px; }
</style>
@endpush

@section('teacher-content')
<div class="step-container" id="stepper">
    <div class="step-item active" id="step-nav-1">
        <div class="step-circle">1</div>
        <div class="step-label">Pengaturan Dasar</div>
    </div>
    <div class="step-item" id="step-nav-2">
        <div class="step-circle">2</div>
        <div class="step-label">Pilih Soal</div>
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
        <div class="card" style="max-width: 800px; margin: 0 auto;">
            <div class="card-header"><h3 class="card-title">Informasi Ujian (Mode Edit)</h3></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Judul Ujian <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" required value="{{ old('title', $exam->title) }}">
                </div>
                
                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                    <div class="form-group mb-0">
                        <!-- Mata Pelajaran dikunci saat edit agar bank soal tidak bentrok -->
                        <label class="form-label">Mata Pelajaran <span class="text-muted text-xs">(Tidak dapat diubah)</span></label>
                        <select name="subject_id" id="subjectFilter" class="form-control" disabled style="background: var(--gray-100);">
                            <option value="{{ $exam->subject_id }}">{{ $exam->subject->name }}</option>
                        </select>
                        <input type="hidden" id="hiddenSubjectFilter" value="{{ $exam->subject_id }}">
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Tipe Ujian <span class="text-danger">*</span></label>
                        <select name="exam_type" class="form-control" required>
                            <option value="UTS" {{ old('exam_type', $exam->exam_type) == 'UTS' ? 'selected' : '' }}>UTS</option>
                            <option value="UAS" {{ old('exam_type', $exam->exam_type) == 'UAS' ? 'selected' : '' }}>UAS</option>
                            <option value="UH" {{ old('exam_type', $exam->exam_type) == 'UH' ? 'selected' : '' }}>Ulangan Harian</option>
                            <option value="Quiz" {{ old('exam_type', $exam->exam_type) == 'Quiz' ? 'selected' : '' }}>Kuis / Latihan</option>
                            <option value="Remedial" {{ old('exam_type', $exam->exam_type) == 'Remedial' ? 'selected' : '' }}>Remedial</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                    <div class="form-group mb-0">
                        <label class="form-label">Kelas Peserta <span class="text-muted text-xs">(Tidak dapat diubah)</span></label>
                        <select name="classroom_id" class="form-control" disabled style="background: var(--gray-100);">
                            <option value="{{ $exam->classroom_id }}">{{ $exam->classroom->name }} ({{ $exam->classroom->academicYear->name ?? '' }})</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Tahun Ajaran <span class="text-muted text-xs">(Tidak dapat diubah)</span></label>
                        <select name="academic_year_id" class="form-control" disabled style="background: var(--gray-100);">
                            <option value="{{ $exam->academic_year_id }}">{{ $exam->academicYear->name ?? 'Tahun Berjalan' }}</option>
                        </select>
                    </div>
                </div>
                
                <hr style="margin: 24px 0; border: none; border-top: 1px solid var(--gray-200);">
                
                <h4 class="font-bold mb-4">Pengaturan Waktu & Nilai</h4>
                
                <div class="grid" style="grid-template-columns: 1fr 1fr 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                    <div class="form-group mb-0">
                        <label class="form-label">Tanggal Ujian <span class="text-danger">*</span></label>
                        <input type="date" name="exam_date" class="form-control" required value="{{ old('exam_date', $exam->exam_date ? $exam->exam_date->format('Y-m-d') : date('Y-m-d')) }}">
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                        <input type="time" name="start_time" class="form-control" required value="{{ old('start_time', $exam->start_time) }}">
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                        <input type="time" name="end_time" class="form-control" required value="{{ old('end_time', $exam->end_time) }}">
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Durasi (Menit) <span class="text-danger">*</span></label>
                        <input type="number" name="duration_minutes" class="form-control" required value="{{ old('duration_minutes', $exam->duration_minutes) }}" min="5">
                    </div>
                </div>
                
                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                    <div class="form-group mb-0">
                        <label class="form-label">Nilai KKM (Batas Lulus) <span class="text-danger">*</span></label>
                        <input type="number" name="passing_grade" class="form-control" required value="{{ old('passing_grade', $exam->passing_grade) }}" min="0" max="100">
                    </div>
                </div>
                
                <div class="form-group mt-4">
                    <label class="form-label">Instruksi / Peraturan (Opsional)</label>
                    <textarea name="instructions" class="form-control" rows="3">{{ old('instructions', $exam->instructions) }}</textarea>
                </div>
                
                <div class="flex justify-end mt-6">
                    <button type="button" class="btn btn-primary" onclick="nextStep(1)">Selanjutnya: Pilih Soal ➔</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- STEP 2: PILIH SOAL -->
    <div class="step-content" id="step-2">
        <div class="q-bank-container">
            
            <!-- PANEL KIRI: BANK SOAL -->
            <div class="q-list-panel w-full">
                <div class="q-panel-header">
                    <span>1. Cari Soal di Bank Soal</span>
                    <input type="text" id="qSearch" class="form-control" style="width:200px; padding:6px 12px; font-size:0.875rem;" placeholder="Cari soal...">
                </div>
                <div class="q-panel-body" id="availableQuestions">
                    <div class="text-center text-muted" style="margin-top:40px;">⏳ Memuat soal...</div>
                </div>
            </div>
            
            <!-- PANEL KANAN: SOAL TERPILIH -->
            <div class="q-selected-panel w-full">
                <div class="q-panel-header">
                    <span>2. Soal Terpilih untuk Ujian Ini</span>
                    <span class="badge badge-primary" id="selectedCountBadge">0 Soal</span>
                </div>
                <div class="q-panel-body" id="selectedQuestions" style="background: white;">
                    <div id="emptySelectedMsg" class="text-center text-muted" style="margin-top:40px;">Belum ada soal terpilih. Klik + pada daftar soal di sebelah kiri.</div>
                </div>
                <div style="padding: 16px; border-top: 1px solid var(--gray-200); background: white; display: flex; justify-content: space-between;">
                    <button type="button" class="btn btn-secondary" onclick="prevStep(2)">← Kembali</button>
                    <button type="button" class="btn btn-primary" onclick="nextStep(2)">Selanjutnya: Konfirmasi ➔</button>
                </div>
            </div>
            
        </div>
    </div>
    
    <!-- STEP 3: KONFIRMASI -->
    <div class="step-content" id="step-3">
        <div class="card" style="max-width: 800px; margin: 0 auto;">
            <div class="card-header"><h3 class="card-title">Konfirmasi & Pengaturan Tambahan</h3></div>
            <div class="card-body">
                
                <div class="alert alert-info">
                    ℹ️ Ujian Anda telah memiliki <strong id="finalQCount">0</strong> butir soal. Silakan atur preferensi di bawah sebelum menyimpan.
                </div>
                
                <h4 class="font-bold mb-4 mt-6">Pengaturan Tambahan Ujian</h4>
                
                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 16px;">
                    <label class="form-control flex gap-3 items-center" style="cursor: pointer;">
                        <input type="checkbox" name="shuffle_questions" value="1" {{ optional($exam->settings)->shuffle_questions ? 'checked' : '' }} style="width:20px;height:20px;accent-color:var(--primary);">
                        <div>
                            <div class="font-bold text-sm">Acak Urutan Soal</div>
                            <div class="text-xs text-muted">Soal akan tampil dengan urutan berbeda tiap siswa</div>
                        </div>
                    </label>
                    <label class="form-control flex gap-3 items-center" style="cursor: pointer;">
                        <input type="checkbox" name="shuffle_options" value="1" {{ optional($exam->settings)->shuffle_options ? 'checked' : '' }} style="width:20px;height:20px;accent-color:var(--primary);">
                        <div>
                            <div class="font-bold text-sm">Acak Opsi Jawaban</div>
                            <div class="text-xs text-muted">Pilihan A,B,C,D akan diacak posisinya</div>
                        </div>
                    </label>
                    <label class="form-control flex gap-3 items-center" style="cursor: pointer;">
                        <input type="checkbox" name="show_result_immediately" value="1" {{ optional($exam->settings)->show_result_immediately ? 'checked' : '' }} style="width:20px;height:20px;accent-color:var(--primary);">
                        <div>
                            <div class="font-bold text-sm">Tampilkan Nilai Otomatis</div>
                            <div class="text-xs text-muted">Siswa bisa melihat nilai setelah submit</div>
                        </div>
                    </label>
                    <label class="form-control flex gap-3 items-center" style="cursor: pointer;">
                        <input type="checkbox" name="show_correct_answers" value="1" {{ optional($exam->settings)->show_correct_answers ? 'checked' : '' }} style="width:20px;height:20px;accent-color:var(--primary);">
                        <div>
                            <div class="font-bold text-sm">Tampilkan Kunci Jawaban</div>
                            <div class="text-xs text-muted">Hanya tampil setelah ujian selesai (semua siswa)</div>
                        </div>
                    </label>
                </div>
                
                <div class="flex justify-between items-center mt-8 pt-6" style="border-top: 1px solid var(--gray-200);">
                    <button type="button" class="btn btn-secondary" onclick="prevStep(3)">← Kembali</button>
                    <div class="flex gap-4">
                        <button type="button" class="btn btn-outline" onclick="window.location='{{ route('teacher.exams.index') }}'">Batalkan Edit</button>
                        <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
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

    // INISIALISASI SOAL YANG SUDAH TERPILIH SEBELUMNYA
    @foreach($exam->questions as $q)
    selectedQuestions.set({{ $q->id }}, {
        id: {{ $q->id }},
        question_text: `{!! addslashes(str_replace(["\r", "\n"], '', strip_tags($q->question_text))) !!}`,
        type: '{{ $q->type }}',
        score: {{ $q->score }}
    });
    @endforeach

    // Call render once on load
    document.addEventListener("DOMContentLoaded", function() {
        renderSelectedQuestions();
        document.getElementById('finalQCount').textContent = selectedQuestions.size;
        loadQuestions();
    });

    // STEP NAVIGATION
    function nextStep(current) {
        if(current === 1) {
            // Validasi diabaikan karena subject disabled, tapi kita panggil loadQuestions jika belum terisi
        }
        if(current === 2) {
            if(selectedQuestions.size === 0) { alert('Silakan pilih minimal 1 soal untuk ujian ini!'); return; }
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
    
    // AJAX LOAD QUESTIONS
    function loadQuestions() {
        const subjId = document.getElementById('hiddenSubjectFilter').value;
        const search = document.getElementById('qSearch').value;
        const container = document.getElementById('availableQuestions');
        
        container.innerHTML = '<div class="text-center text-muted" style="margin-top:40px;">⏳ Memuat soal...</div>';
        
        fetch(`/guru/api/soal?subject_id=${subjId}&search=${search}`)
            .then(res => res.json())
            .then(data => {
                availableQuestions = data;
                renderAvailableQuestions();
            });
    }
    
    document.getElementById('qSearch').addEventListener('input', function() {
        // debounce search
        clearTimeout(this.timer);
        this.timer = setTimeout(loadQuestions, 500);
    });
    
    function renderAvailableQuestions() {
        const container = document.getElementById('availableQuestions');
        container.innerHTML = '';
        
        if(availableQuestions.length === 0) {
            container.innerHTML = '<div class="text-center text-muted" style="margin-top:40px;">Tidak ada soal ditemukan.</div>';
            return;
        }
        
        availableQuestions.forEach(q => {
            // Skip if already selected
            if(selectedQuestions.has(q.id)) return;
            
            const div = document.createElement('div');
            div.className = 'q-item';
            div.innerHTML = `
                <div class="q-item-text">${q.question_text.substring(0, 150)}...</div>
                <div class="q-item-meta">
                    <span class="badge badge-gray">${q.type === 'multiple_choice' ? 'PG' : 'Lainnya'}</span>
                    <span class="text-muted">Bobot: ${q.score}</span>
                </div>
                <button type="button" class="add-btn" onclick="addQuestion(${q.id})">+</button>
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
            container.innerHTML = '<div id="emptySelectedMsg" class="text-center text-muted" style="margin-top:40px;">Belum ada soal terpilih. Klik + pada daftar soal di sebelah kiri.</div>';
            return;
        }
        
        let i = 1;
        selectedQuestions.forEach((q, id) => {
            const div = document.createElement('div');
            div.className = 'selected-q-row';
            div.innerHTML = `
                <div class="drag-handle">☰</div>
                <div style="font-weight: 800; color: var(--primary); width: 24px;">${i}.</div>
                <div style="flex:1; font-size: 0.875rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    ${q.question_text}
                </div>
                <button type="button" class="remove-btn" onclick="removeQuestion(${id})">×</button>
                <input type="hidden" name="question_ids[]" value="${id}">
            `;
            container.appendChild(div);
            i++;
        });
    }
</script>
@endpush
