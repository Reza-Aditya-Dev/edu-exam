@extends('layouts.teacher')

@section('title', 'Buat Soal Baru — EduExam')
@section('page_title', 'Buat Soal Baru')

@section('teacher-content')
<div class="card" style="max-width: 900px; margin: 0 auto; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.05);">
    <div class="card-header" style="background: var(--white); border-bottom: 1px solid var(--gray-200); padding: 24px 32px;">
        <div>
            <h3 class="card-title" style="font-size: 1.25rem; font-weight: 800; color: var(--gray-900);"><i class="bi bi-file-earmark-plus me-2 text-primary"></i> Formulir Pembuatan Soal Baru</h3>
            <div class="text-sm text-muted mt-1">Lengkapi data soal di bawah ini. Anda bisa langsung membuat soal berikutnya setelah menyimpan.</div>
        </div>
    </div>
    
    <div class="card-body">
        <form action="{{ route('teacher.questions.store') }}" method="POST" enctype="multipart/form-data" id="questionForm">
            @csrf
            
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div class="form-group mb-0">
                    <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                    <select name="subject_id" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group mb-0">
                    <label class="form-label">Tipe Soal <span class="text-danger">*</span></label>
                    <select name="type" id="questionType" class="form-control" required onchange="toggleOptions()">
                        <option value="multiple_choice" {{ old('type') == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                        <option value="true_false" {{ old('type') == 'true_false' ? 'selected' : '' }}>Benar / Salah</option>
                        <option value="short_answer" {{ old('type') == 'short_answer' ? 'selected' : '' }}>Isian Singkat</option>
                        <option value="essay" {{ old('type') == 'essay' ? 'selected' : '' }}>Esai</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                <textarea name="question_text" class="form-control" rows="4" required placeholder="Tuliskan pertanyaan di sini...">{{ old('question_text') }}</textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Gambar Pendukung (Opsional)</label>
                <input type="file" name="question_image" class="form-control" accept="image/*">
            </div>
            
            <div class="grid" style="grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                <div class="form-group mb-0">
                    <label class="form-label">Topik (Opsional)</label>
                    <input type="text" name="topic" class="form-control" value="{{ old('topic') }}" placeholder="Materi / BAB">
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">Tingkat Kesulitan <span class="text-danger">*</span></label>
                    <select name="difficulty" class="form-control" required>
                        <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Mudah</option>
                        <option value="medium" {{ old('difficulty', 'medium') == 'medium' ? 'selected' : '' }}>Sedang</option>
                        <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Sulit</option>
                    </select>
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">Bobot Nilai <span class="text-danger">*</span></label>
                    <input type="number" name="score" class="form-control" value="{{ old('score', 10) }}" min="0" max="100" required>
                </div>
            </div>
            
            <!-- SECTION OPSI JAWABAN (Dinamis) -->
            <div id="optionsSection" style="background: var(--gray-50); padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--gray-200); margin-bottom: 24px;">
                <h4 class="font-bold mb-4">Pilihan Jawaban</h4>
                <p class="text-sm text-muted mb-4">Tandai radio button pada jawaban yang <strong>Benar</strong>.</p>
                
                <div id="multipleChoiceOptions">
                    @for($i = 0; $i < 5; $i++)
                    <div class="flex gap-4 items-start mb-3">
                        <div style="padding-top: 12px;">
                            <input type="radio" name="correct_option" value="{{ $i }}" {{ old('correct_option', 0) == $i ? 'checked' : '' }} required style="width: 20px; height: 20px; accent-color: var(--success);">
                        </div>
                        <div style="padding-top: 10px; font-weight: 700; font-size: 1.1rem; width: 24px;">
                            {{ chr(65 + $i) }}.
                        </div>
                        <div class="flex-1">
                            <input type="text" name="options[{{ $i }}][text]" class="form-control mb-2" placeholder="Teks pilihan {{ chr(65 + $i) }}" {{ $i < 2 ? 'required' : '' }}>
                            <input type="file" name="options[{{ $i }}][image]" class="form-control text-sm" accept="image/*">
                        </div>
                    </div>
                    @endfor
                </div>
                
                <div id="trueFalseOptions" class="hidden">
                    <div class="flex gap-4 items-center mb-3">
                        <input type="radio" name="correct_option" value="0" style="width: 20px; height: 20px; accent-color: var(--success);">
                        <input type="hidden" name="options[0][text]" value="Benar" class="tf-input">
                        <div class="form-control bg-white font-bold text-center">BENAR</div>
                    </div>
                    <div class="flex gap-4 items-center">
                        <input type="radio" name="correct_option" value="1" style="width: 20px; height: 20px; accent-color: var(--success);">
                        <input type="hidden" name="options[1][text]" value="Salah" class="tf-input">
                        <div class="form-control bg-white font-bold text-center">SALAH</div>
                    </div>
                </div>
            </div>
            
            <div class="form-group mb-6">
                <label class="form-label">Pembahasan (Opsional)</label>
                <textarea name="explanation" class="form-control" rows="3" placeholder="Penjelasan jawaban untuk siswa setelah ujian...">{{ old('explanation') }}</textarea>
            </div>
            
            <div class="flex justify-between items-center mt-6">
                <button type="button" class="btn btn-secondary" onclick="window.history.back()" style="padding: 12px 24px;">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </button>
                <div class="flex gap-3">
                    <button type="submit" name="save_and_add_another" value="1" class="btn btn-success" style="padding: 12px 24px; font-weight: 700; background: var(--success); color: white; border: none;">
                        <i class="bi bi-plus-circle me-1"></i> Simpan & Buat Soal Lagi
                    </button>
                    <button type="submit" class="btn btn-primary" style="padding: 12px 32px; font-weight: 700;">
                        <i class="bi bi-check2-circle me-1"></i> Simpan & Selesai
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleOptions() {
        const type = document.getElementById('questionType').value;
        const section = document.getElementById('optionsSection');
        const mc = document.getElementById('multipleChoiceOptions');
        const tf = document.getElementById('trueFalseOptions');
        
        // Disable/enable inputs based on visibility so validation doesn't block submit
        const setRequired = (container, required) => {
            container.querySelectorAll('input[type="text"]').forEach((el, index) => {
                if(index < 2) el.required = required; // Only first two options required for MC
                el.disabled = !required;
            });
            container.querySelectorAll('input[type="radio"]').forEach(el => {
                el.disabled = !required;
            });
            container.querySelectorAll('.tf-input').forEach(el => {
                el.disabled = !required;
            });
        };

        if (type === 'multiple_choice') {
            section.style.display = 'block';
            mc.classList.remove('hidden');
            tf.classList.add('hidden');
            setRequired(mc, true);
            setRequired(tf, false);
        } else if (type === 'true_false') {
            section.style.display = 'block';
            mc.classList.add('hidden');
            tf.classList.remove('hidden');
            setRequired(mc, false);
            setRequired(tf, true);
        } else {
            section.style.display = 'none';
            setRequired(mc, false);
            setRequired(tf, false);
        }
    }
    
    // Init on load
    toggleOptions();
</script>
@endpush
