@extends('layouts.teacher')

@section('title', 'Edit Soal — EduExam')
@section('page_title', 'Edit Bank Soal')

@section('teacher-content')
<form action="{{ route('teacher.questions.update', $question->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left Column: Question Details (8 cols) -->
        <div class="lg:col-span-8">
            <div class="card overflow-hidden">
                <div class="card-header p-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                    <span class="material-symbols-outlined text-indigo-600" style="font-size: 20px;">edit_note</span>
                    <h3 class="font-headline font-bold text-slate-900 text-base">Detail Pertanyaan Soal</h3>
                </div>
                
                <div class="card-body p-6 md:p-8">
                    <div class="mb-5">
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                            Teks Pertanyaan <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="question_text" class="form-control text-sm" rows="6" required placeholder="Tuliskan isi pertanyaan untuk siswa di sini...">{{ old('question_text', $question->question_text) }}</textarea>
                        @error('question_text') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-5 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-slate-500" style="font-size: 16px;">image</span>
                            Gambar Soal <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                        </label>
                        
                        @if($question->question_image)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $question->question_image) }}" alt="Gambar Soal" class="max-h-36 rounded-xl border border-slate-200 object-cover shadow-sm">
                                <label class="flex items-center gap-2 text-xs text-rose-600 font-semibold cursor-pointer mt-2">
                                    <input type="checkbox" name="remove_image" value="1" class="rounded border-slate-300 text-rose-600">
                                    <span>Hapus gambar ini</span>
                                </label>
                            </div>
                        @endif
                        
                        <input type="file" name="question_image" class="form-control text-xs bg-white" accept="image/*">
                        <p class="text-[11px] text-slate-400 mt-1.5">Format: JPG, PNG. Ukuran maksimal: 2MB. Kosongkan jika tidak ingin mengubah gambar.</p>
                        @error('question_image') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>
                    
                    <div>
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                            Penjelasan / Pembahasan <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                        </label>
                        <textarea name="explanation" class="form-control text-sm" rows="4" placeholder="Penjelasan cara pengerjaan untuk evaluasi siswa setelah ujian selesai...">{{ old('explanation', $question->explanation) }}</textarea>
                        @error('explanation') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Settings & Actions (4 cols) -->
        <div class="lg:col-span-4 sticky top-24">
            <div class="card overflow-hidden">
                <div class="card-header p-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                    <span class="material-symbols-outlined text-indigo-600" style="font-size: 20px;">settings</span>
                    <h3 class="font-headline font-bold text-slate-900 text-sm">Konfigurasi Soal</h3>
                </div>
                
                <div class="card-body p-5 md:p-6">
                    <div class="mb-4">
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                            Mata Pelajaran <span class="text-rose-500">*</span>
                        </label>
                        <select name="subject_id" class="form-control text-sm" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id', $question->subject_id) == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                            Tipe Soal <span class="text-rose-500">*</span>
                        </label>
                        <select name="type" class="form-control text-sm font-medium" required>
                            <option value="multiple_choice" {{ old('type', $question->type) == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                            <option value="true_false" {{ old('type', $question->type) == 'true_false' ? 'selected' : '' }}>Benar / Salah</option>
                            <option value="short_answer" {{ old('type', $question->type) == 'short_answer' ? 'selected' : '' }}>Jawaban Singkat</option>
                            <option value="essay" {{ old('type', $question->type) == 'essay' ? 'selected' : '' }}>Esai</option>
                        </select>
                        @error('type') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div>
                            <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                                Tingkat <span class="text-rose-500">*</span>
                            </label>
                            <select name="difficulty" class="form-control text-sm" required>
                                <option value="easy" {{ old('difficulty', $question->difficulty) == 'easy' ? 'selected' : '' }}>Mudah</option>
                                <option value="medium" {{ old('difficulty', $question->difficulty) == 'medium' ? 'selected' : '' }}>Sedang</option>
                                <option value="hard" {{ old('difficulty', $question->difficulty) == 'hard' ? 'selected' : '' }}>Sulit</option>
                            </select>
                            @error('difficulty') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="form-label font-semibold text-slate-700 text-xs uppercase tracking-wider mb-1.5 block">
                                Bobot Nilai <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" name="score" class="form-control text-sm font-bold text-center" min="1" max="100" value="{{ old('score', $question->score) }}" required>
                            @error('score') <div class="text-xs text-rose-500 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-slate-100 space-y-2">
                        <button type="submit" class="btn btn-primary w-full text-xs font-bold py-3 flex items-center justify-center gap-1.5 shadow-sm">
                            <span class="material-symbols-outlined" style="font-size: 18px;">save</span>
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('teacher.questions.index') }}" class="btn btn-secondary w-full text-xs font-semibold py-2.5 text-center block">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</form>
@endsection
