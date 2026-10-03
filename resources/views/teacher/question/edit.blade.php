@extends('layouts.teacher')

@section('title', 'Edit Soal — EduExam')
@section('page_title', 'Edit Bank Soal')

@section('teacher-content')
<form action="{{ route('teacher.questions.update', $question->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 28px; align-items: start;">
        
        <!-- Kolom Kiri: Konten Utama Soal -->
        <div class="card" style="box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.05);">
            <div class="card-header" style="background: var(--white); border-bottom: 1px solid var(--gray-200); padding: 24px 28px;">
                <h3 class="card-title flex items-center gap-2" style="font-weight: 800; color: var(--gray-900); font-size: 1.2rem;">
                    <i class="bi bi-file-earmark-text text-primary"></i> Detail Pertanyaan
                </h3>
            </div>
            
            <div class="card-body" style="padding: 28px;">
                <div class="form-group mb-6">
                    <label class="form-label font-bold text-gray-800" style="font-size: 0.95rem;">
                        Teks Pertanyaan <span class="text-danger">*</span>
                    </label>
                    <textarea name="question_text" class="form-control" rows="6" style="padding: 16px; font-size: 1.05rem; line-height: 1.6; border-radius: var(--radius-md);" required placeholder="Tuliskan isi pertanyaan untuk siswa di sini...">{{ old('question_text', $question->question_text) }}</textarea>
                    @error('question_text') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group mb-6" style="background: var(--primary-light); padding: 16px; border-radius: var(--radius-md); border: 1px dashed var(--primary-border);">
                    <label class="form-label font-bold text-primary-dark" style="font-size: 0.95rem;">
                        Gambar Soal <span class="text-muted text-sm font-normal ml-1">(Opsional)</span>
                    </label>
                    
                    @if($question->question_image)
                        <div class="mb-3 mt-2" style="position: relative; display: inline-block;">
                            <img src="{{ asset('storage/' . $question->question_image) }}" alt="Gambar Soal" style="max-height: 150px; border-radius: var(--radius-sm); border: 1px solid var(--gray-300);">
                            <div style="margin-top: 8px;">
                                <label class="flex items-center gap-2 text-sm text-danger cursor-pointer font-medium">
                                    <input type="checkbox" name="remove_image" value="1"> Hapus gambar ini
                                </label>
                            </div>
                        </div>
                    @endif
                    
                    <input type="file" name="question_image" class="form-control" accept="image/*" style="background: var(--white);">
                    <div class="text-xs text-muted mt-2">Format: JPG, PNG. Ukuran maksimal: 2MB. Kosongkan jika tidak ingin mengubah gambar.</div>
                    @error('question_image') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group mb-0" style="background: var(--gray-50); padding: 20px; border-radius: var(--radius-md); border: 1px dashed var(--gray-300);">
                    <label class="form-label font-bold text-gray-800" style="font-size: 0.95rem;">
                        Penjelasan / Pembahasan <span class="text-muted text-sm font-normal ml-1">(Opsional)</span>
                    </label>
                    <textarea name="explanation" class="form-control" rows="4" style="padding: 16px; margin-top: 8px;" placeholder="Penjelasan jawaban yang benar. Akan ditampilkan kepada siswa setelah ujian selesai...">{{ old('explanation', $question->explanation) }}</textarea>
                    <div class="text-xs text-muted mt-2">Memberikan penjelasan sangat disarankan untuk bahan evaluasi siswa.</div>
                    @error('explanation') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Pengaturan Soal -->
        <div class="flex flex-col gap-6" style="position: sticky; top: 90px;">
            <div class="card" style="box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.05);">
                <div class="card-header" style="background: var(--gray-50); border-bottom: 1px solid var(--gray-200); padding: 18px 24px;">
                    <h3 class="card-title flex items-center gap-2" style="font-size: 1.05rem; font-weight: 800; color: var(--gray-800);">
                        <i class="bi bi-gear-fill text-primary"></i> Konfigurasi Soal
                    </h3>
                </div>
                
                <div class="card-body" style="padding: 24px;">
                    <div class="form-group mb-5">
                        <label class="form-label font-bold text-gray-700 text-sm">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select name="subject_id" class="form-control" required style="padding: 12px; font-weight: 500;">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id', $question->subject_id) == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group mb-5">
                        <label class="form-label font-bold text-gray-700 text-sm">Tipe Soal <span class="text-danger">*</span></label>
                        <select name="type" class="form-control" required style="padding: 12px; font-weight: 500; background: var(--white);">
                            <option value="multiple_choice" {{ old('type', $question->type) == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                            <option value="true_false" {{ old('type', $question->type) == 'true_false' ? 'selected' : '' }}>Benar / Salah</option>
                            <option value="short_answer" {{ old('type', $question->type) == 'short_answer' ? 'selected' : '' }}>Jawaban Singkat</option>
                            <option value="essay" {{ old('type', $question->type) == 'essay' ? 'selected' : '' }}>Esai</option>
                        </select>
                        @error('type') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                        <div class="form-group mb-0">
                            <label class="form-label font-bold text-gray-700 text-sm">Tingkat <span class="text-danger">*</span></label>
                            <select name="difficulty" class="form-control" required style="padding: 12px; font-weight: 500;">
                                <option value="easy" {{ old('difficulty', $question->difficulty) == 'easy' ? 'selected' : '' }}>Mudah</option>
                                <option value="medium" {{ old('difficulty', $question->difficulty) == 'medium' ? 'selected' : '' }}>Sedang</option>
                                <option value="hard" {{ old('difficulty', $question->difficulty) == 'hard' ? 'selected' : '' }}>Sulit</option>
                            </select>
                            @error('difficulty') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label font-bold text-gray-700 text-sm">Bobot Skor <span class="text-danger">*</span></label>
                            <input type="number" name="score" class="form-control" min="1" max="100" value="{{ old('score', $question->score) }}" required style="padding: 12px; font-weight: 600; text-align: center; color: var(--primary-dark);">
                            @error('score') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <hr style="border: 0; border-top: 1px solid var(--gray-200); margin: 24px 0;">
                    
                    <button type="submit" class="btn btn-primary btn-block" style="padding: 14px; font-size: 1rem; font-weight: 700; height: auto;">
                        <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan
                    </button>
                    <!-- Menggunakan url yang standar untuk fallback -->
                    <a href="{{ url('guru/soal') }}" class="btn btn-secondary btn-block mt-3 text-center" style="padding: 14px; height: auto;">
                        Batal
                    </a>
                </div>
            </div>
        </div>
        
    </div>
</form>

@push('teacher-styles')
<style>
    @media (max-width: 1024px) {
        .grid {
            grid-template-columns: 1fr !important;
        }
        .flex-col[style*="position: sticky"] {
            position: relative !important;
            top: 0 !important;
        }
    }
</style>
@endpush
@endsection
