@extends('layouts.teacher')

@section('title', 'Edit Soal #' . $question->id . ' — EduExam')
@section('page_title', 'Edit Bank Soal')

@section('teacher-content')
<div class="flex flex-col w-full pb-20">

    <!-- Top Breadcrumb & Title Bar -->
    <div class="flex flex-wrap items-center justify-between gap-space-md py-space-sm mb-space-md">
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-space-xs text-on-surface-variant font-label-md text-label-md">
                <a href="{{ route('teacher.questions.index') }}" class="hover:text-primary transition-colors">Bank Soal</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-surface-variant">{{ $question->subject->name ?? 'Mata Pelajaran' }}</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-semibold">Edit Soal #{{ $question->id }}</span>
            </div>
            <div class="flex items-baseline gap-space-sm">
                <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">Edit Butir Soal #{{ $question->id }}</h1>
                <span class="inline-flex items-center gap-1 px-space-sm py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-label-sm font-semibold">
                    {{ $question->type_label }}
                </span>
            </div>
        </div>

        <!-- Quick Back Button -->
        <div class="flex items-center gap-2">
            <a href="{{ route('teacher.questions.index', ['package' => 'pkg_' . ($question->subject_id ?? 0) . '_' . ($question->classroom_id ?? 'all')]) }}" 
               class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-surface-container-high text-on-surface hover:bg-slate-200 transition-colors font-label-md text-label-md font-semibold">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                <span>Kembali ke Bank Soal</span>
            </a>
        </div>
    </div>

    @if(isset($errors) && $errors->any())
        <div class="mb-space-md p-space-md rounded-2xl bg-error-container text-on-error-container text-xs shadow-xs">
            <div class="font-bold mb-1 flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">error</span>
                <span>Terdapat kesalahan pada input form:</span>
            </div>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('teacher.questions.update', $question->id) }}" method="POST" enctype="multipart/form-data" id="editQuestionForm">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-start">
            
            <!-- Left Column: Content & Options (8 cols) -->
            <div class="lg:col-span-8 flex flex-col gap-space-lg">
                
                <!-- Main Question Editor Card -->
                <div class="bg-surface-container-lowest rounded-2xl p-space-lg border border-slate-200 shadow-sm flex flex-col gap-space-md">
                    <div class="flex items-center justify-between pb-space-xs border-b border-slate-100">
                        <div class="flex items-center gap-2 text-primary font-label-lg text-label-lg font-bold">
                            <span class="material-symbols-outlined text-[20px]">edit_note</span>
                            <span>Isi Pertanyaan &amp; Topik</span>
                        </div>
                        <span class="font-label-sm text-label-sm text-on-surface-variant bg-surface-container px-2.5 py-0.5 rounded-md">ID Soal #{{ $question->id }}</span>
                    </div>

                    <!-- Topik / Materi Pokok -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-sm text-label-sm text-on-surface-variant font-semibold">
                            Topik / Nama Materi Soal <span class="text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <input type="text" name="topic" value="{{ old('topic', $question->topic) }}" 
                               placeholder="Contoh: SOAL MATEMATIKA KELAS 10 SMA atau Trigonometri" 
                               class="w-full h-11 px-3 bg-surface-container-low rounded-xl text-body-md text-on-surface border border-transparent focus:border-indigo-300 focus:bg-white outline-none transition-all shadow-xs">
                    </div>

                    <!-- Teks Pertanyaan -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-sm text-label-sm text-on-surface-variant font-semibold">
                            Teks Pertanyaan Soal <span class="text-error">*</span>
                        </label>
                        <textarea name="question_text" rows="5" required 
                                  placeholder="Tuliskan isi pertanyaan soal di sini..." 
                                  class="w-full p-3.5 bg-surface-container-low rounded-xl text-body-md text-on-surface border border-transparent focus:border-indigo-300 focus:bg-white outline-none transition-all shadow-xs leading-relaxed">{{ old('question_text', $question->question_text) }}</textarea>
                    </div>

                    <!-- Lampiran Gambar Soal -->
                    <div class="p-4 bg-surface-container-low/70 border border-slate-200/80 rounded-2xl flex flex-col gap-2">
                        <label class="font-label-sm text-label-sm text-on-surface-variant font-semibold flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-slate-500 text-[18px]">image</span>
                            <span>Lampiran Gambar Soal (Opsional)</span>
                        </label>

                        @if($question->question_image)
                            <div class="p-3 bg-white border border-slate-200 rounded-xl flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                <img src="{{ $question->image_url }}" alt="Gambar Soal" class="max-h-36 max-w-xs rounded-lg border border-slate-200 object-contain shadow-xs bg-slate-50">
                                <div class="flex flex-col gap-1">
                                    <span class="text-xs font-semibold text-slate-700">Gambar yang saat ini terpasang</span>
                                    <label class="flex items-center gap-2 text-xs text-error font-semibold cursor-pointer mt-1 hover:opacity-80">
                                        <input type="checkbox" name="remove_image" value="1" class="rounded text-error focus:ring-error">
                                        <span>Centang untuk menghapus gambar ini</span>
                                    </label>
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-col gap-1 mt-1">
                            <input type="file" name="question_image" accept="image/*" class="text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-fixed file:text-on-primary-fixed hover:file:opacity-90 cursor-pointer">
                            <p class="text-[11px] text-slate-400">Format: JPG, PNG, WEBP. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</p>
                        </div>
                    </div>
                </div>

                <!-- Section: Options & Answer Key (Pilihan Jawaban & Kunci Jawaban) -->
                <div id="optionsSection" class="bg-surface-container-lowest rounded-2xl p-space-lg border border-slate-200 shadow-sm flex flex-col gap-space-md {{ in_array(old('type', $question->type), ['multiple_choice', 'true_false']) ? '' : 'hidden' }}">
                    <div class="flex items-center justify-between pb-space-xs border-b border-slate-100 flex-wrap gap-2">
                        <div class="flex items-center gap-2 text-primary font-label-lg text-label-lg font-bold">
                            <span class="material-symbols-outlined text-[20px]">fact_check</span>
                            <span>Pilihan Jawaban &amp; Kunci Jawaban</span>
                        </div>
                        <span class="text-xs text-slate-500 font-medium bg-emerald-50 text-emerald-800 border border-emerald-200 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px] text-emerald-600">check_circle</span>
                            <span>Pilih radio lingkaran hijau untuk menentukan Kunci</span>
                        </span>
                    </div>

                    <!-- Dynamic Options Container -->
                    <div id="optionsList" class="flex flex-col gap-3">
                        @php
                            $currentOptions = $question->options->count() > 0 
                                ? $question->options 
                                : collect([
                                    (object)['label' => 'A', 'option_text' => '', 'is_correct' => true],
                                    (object)['label' => 'B', 'option_text' => '', 'is_correct' => false],
                                    (object)['label' => 'C', 'option_text' => '', 'is_correct' => false],
                                    (object)['label' => 'D', 'option_text' => '', 'is_correct' => false],
                                    (object)['label' => 'E', 'option_text' => '', 'is_correct' => false],
                                ]);
                            $correctIndex = old('correct_option', $currentOptions->search(fn($o) => $o->is_correct !== false && $o->is_correct !== 0));
                            if ($correctIndex === false) $correctIndex = 0;
                        @endphp

                        @foreach($currentOptions as $idx => $opt)
                            <div class="option-row p-3 rounded-2xl border transition-all flex flex-col sm:flex-row sm:items-center gap-3 {{ (string)$correctIndex === (string)$idx ? 'bg-emerald-50/70 border-emerald-300 ring-1 ring-emerald-300' : 'bg-surface-container-low border-slate-200' }}" id="option-row-{{ $idx }}">
                                <input type="hidden" name="options[{{ $idx }}][label]" value="{{ $opt->label ?? chr(65 + $idx) }}" class="option-label-input">
                                
                                <!-- Radio Selection for Correct Key -->
                                <label class="flex items-center gap-2 cursor-pointer shrink-0 select-none">
                                    <input type="radio" 
                                           name="correct_option" 
                                           value="{{ $idx }}" 
                                           {{ (string)$correctIndex === (string)$idx ? 'checked' : '' }}
                                           onchange="highlightCorrectOption({{ $idx }})"
                                           class="w-5 h-5 text-emerald-600 border-slate-300 focus:ring-emerald-500 cursor-pointer">
                                    <span class="w-8 h-8 rounded-xl font-bold flex items-center justify-center text-sm shadow-xs {{ (string)$correctIndex === (string)$idx ? 'bg-emerald-600 text-white' : 'bg-surface-container-high text-on-surface' }}" id="label-badge-{{ $idx }}">
                                        {{ $opt->label ?? chr(65 + $idx) }}
                                    </span>
                                    <span class="text-xs font-bold {{ (string)$correctIndex === (string)$idx ? 'text-emerald-700' : 'text-slate-500' }}" id="kunci-text-{{ $idx }}">
                                        {{ (string)$correctIndex === (string)$idx ? 'KUNCI JAWABAN' : 'Tandai Kunci' }}
                                    </span>
                                </label>

                                <!-- Text Input for this Option -->
                                <div class="flex-1 flex items-center gap-2 min-w-0">
                                    <input type="text" 
                                           name="options[{{ $idx }}][text]" 
                                           value="{{ old("options.{$idx}.text", $opt->option_text) }}" 
                                           placeholder="Tuliskan pilihan {{ $opt->label ?? chr(65 + $idx) }}..." 
                                           class="w-full h-11 px-3.5 rounded-xl bg-white text-body-md text-on-surface border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition-all shadow-xs font-medium">
                                    
                                    @if($loop->count > 2)
                                        <button type="button" onclick="removeOptionRow({{ $idx }})" class="p-2 rounded-lg text-slate-400 hover:text-error hover:bg-error-container transition-colors shrink-0" title="Hapus opsi ini">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Add Option Button -->
                    <div class="pt-2 flex items-center justify-between">
                        <button type="button" onclick="addOptionRow()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-surface-container hover:bg-surface-container-high text-primary font-label-md text-label-md font-semibold transition-all border border-slate-200">
                            <span class="material-symbols-outlined text-[18px]">add_circle</span>
                            <span>+ Tambah Opsi Pilihan</span>
                        </button>
                        <span class="text-xs text-slate-400">Maksimal 5 pilihan (A - E)</span>
                    </div>
                </div>

                <!-- Explanation / Pembahasan Card -->
                <div class="bg-surface-container-lowest rounded-2xl p-space-lg border border-slate-200 shadow-sm flex flex-col gap-space-md">
                    <div class="flex items-center gap-2 text-primary font-label-lg text-label-lg font-bold pb-space-xs border-b border-slate-100">
                        <span class="material-symbols-outlined text-[20px]">lightbulb</span>
                        <span>Penjelasan / Kunci Pembahasan Evaluasi</span>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-sm text-label-sm text-on-surface-variant font-semibold">
                            Pembahasan / Kunci Definitif Isian <span class="text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <textarea name="explanation" rows="3" 
                                  placeholder="Tuliskan pembahasan langkah pengerjaan atau rubrik penilaian siswa..." 
                                  class="w-full p-3.5 bg-surface-container-low rounded-xl text-body-md text-on-surface border border-transparent focus:border-indigo-300 focus:bg-white outline-none transition-all shadow-xs leading-relaxed">{{ old('explanation', $question->explanation) }}</textarea>
                    </div>
                </div>

            </div>

            <!-- Right Column: Settings & Submit (4 cols) -->
            <div class="lg:col-span-4 sticky top-6 flex flex-col gap-space-lg">
                <div class="bg-surface-container-lowest rounded-2xl p-space-lg border border-slate-200 shadow-sm flex flex-col gap-4">
                    <div class="flex items-center gap-2 text-primary font-label-lg text-label-lg font-bold pb-2 border-b border-slate-100">
                        <span class="material-symbols-outlined text-[20px]">tune</span>
                        <span>Konfigurasi &amp; Klasifikasi</span>
                    </div>

                    <!-- Mata Pelajaran -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-sm text-label-sm text-on-surface-variant font-semibold">Mata Pelajaran <span class="text-error">*</span></label>
                        <div class="relative">
                            <select name="subject_id" required class="w-full h-11 pl-3 pr-8 bg-surface-container-low rounded-xl text-body-md text-on-surface appearance-none focus:bg-white border border-transparent focus:border-indigo-300 outline-none transition-all cursor-pointer">
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($subjects as $sub)
                                    <option value="{{ $sub->id }}" {{ (string)old('subject_id', $question->subject_id) === (string)$sub->id ? 'selected' : '' }}>
                                        {{ $sub->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-2.5 top-3 text-[18px] text-on-surface-variant pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    <!-- Target Kelas -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-sm text-label-sm text-on-surface-variant font-semibold">Target Kelas <span class="text-slate-400 font-normal">(opsional)</span></label>
                        <div class="relative">
                            <select name="classroom_id" class="w-full h-11 pl-3 pr-8 bg-surface-container-low rounded-xl text-body-md text-on-surface appearance-none focus:bg-white border border-transparent focus:border-indigo-300 outline-none transition-all cursor-pointer">
                                <option value="">-- Semua Kelas (Umum) --</option>
                                @foreach($classrooms as $cls)
                                    <option value="{{ $cls->id }}" {{ (string)old('classroom_id', $question->classroom_id) === (string)$cls->id ? 'selected' : '' }}>
                                        Kelas {{ $cls->name }} (Tingkat {{ $cls->grade }})
                                    </option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-2.5 top-3 text-[18px] text-on-surface-variant pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    <!-- Tipe Soal -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-sm text-label-sm text-on-surface-variant font-semibold">Bentuk / Tipe Soal <span class="text-error">*</span></label>
                        <div class="relative">
                            <select name="type" id="typeSelector" required onchange="handleTypeChange(this.value)" class="w-full h-11 pl-3 pr-8 bg-surface-container-low rounded-xl text-body-md text-on-surface appearance-none focus:bg-white border border-transparent focus:border-indigo-300 outline-none transition-all cursor-pointer font-semibold">
                                <option value="multiple_choice" {{ old('type', $question->type) == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda (PG)</option>
                                <option value="true_false" {{ old('type', $question->type) == 'true_false' ? 'selected' : '' }}>Benar / Salah</option>
                                <option value="short_answer" {{ old('type', $question->type) == 'short_answer' ? 'selected' : '' }}>Isian Singkat</option>
                                <option value="essay" {{ old('type', $question->type) == 'essay' ? 'selected' : '' }}>Uraian / Esai</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-2.5 top-3 text-[18px] text-on-surface-variant pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    <!-- Tingkat Kesulitan & Bobot Nilai -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-sm text-label-sm text-on-surface-variant font-semibold">Kesulitan <span class="text-error">*</span></label>
                            <div class="relative">
                                <select name="difficulty" required class="w-full h-11 pl-2.5 pr-7 bg-surface-container-low rounded-xl text-body-sm text-on-surface appearance-none focus:bg-white border border-transparent focus:border-indigo-300 outline-none transition-all cursor-pointer">
                                    <option value="easy" {{ old('difficulty', $question->difficulty) == 'easy' ? 'selected' : '' }}>Mudah</option>
                                    <option value="medium" {{ old('difficulty', $question->difficulty) == 'medium' ? 'selected' : '' }}>Sedang</option>
                                    <option value="hard" {{ old('difficulty', $question->difficulty) == 'hard' ? 'selected' : '' }}>Sulit (HOTS)</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-2 top-3 text-[16px] text-on-surface-variant pointer-events-none">expand_more</span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-sm text-label-sm text-on-surface-variant font-semibold">Bobot Poin <span class="text-error">*</span></label>
                            <input type="number" step="0.5" name="score" min="0" max="100" value="{{ old('score', $question->score) }}" required class="w-full h-11 px-3 text-center bg-surface-container-low rounded-xl text-body-md font-bold text-on-surface border border-transparent focus:border-indigo-300 focus:bg-white outline-none transition-all shadow-xs">
                        </div>
                    </div>

                    <!-- Submit & Cancel Buttons -->
                    <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
                        <button type="submit" class="w-full py-3 px-4 rounded-xl bg-primary text-white font-label-lg text-label-lg font-bold hover:bg-primary-dark shadow-md transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">save</span>
                            <span>Simpan Perubahan Soal</span>
                        </button>
                        <a href="{{ route('teacher.questions.index', ['package' => 'pkg_' . ($question->subject_id ?? 0) . '_' . ($question->classroom_id ?? 'all')]) }}" 
                           class="w-full py-2.5 px-4 rounded-xl bg-surface-container-high text-on-surface hover:bg-slate-200 transition-colors text-center text-xs font-semibold">
                            Batal
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    function highlightCorrectOption(selectedIndex) {
        document.querySelectorAll('.option-row').forEach((row, i) => {
            const badge = document.getElementById('label-badge-' + i);
            const text = document.getElementById('kunci-text-' + i);

            if (i === selectedIndex) {
                row.classList.add('bg-emerald-50/70', 'border-emerald-300', 'ring-1', 'ring-emerald-300');
                row.classList.remove('bg-surface-container-low', 'border-slate-200');
                if (badge) {
                    badge.classList.add('bg-emerald-600', 'text-white');
                    badge.classList.remove('bg-surface-container-high', 'text-on-surface');
                }
                if (text) {
                    text.classList.add('text-emerald-700');
                    text.classList.remove('text-slate-500');
                    text.innerText = 'KUNCI JAWABAN';
                }
            } else {
                row.classList.remove('bg-emerald-50/70', 'border-emerald-300', 'ring-1', 'ring-emerald-300');
                row.classList.add('bg-surface-container-low', 'border-slate-200');
                if (badge) {
                    badge.classList.remove('bg-emerald-600', 'text-white');
                    badge.classList.add('bg-surface-container-high', 'text-on-surface');
                }
                if (text) {
                    text.classList.remove('text-emerald-700');
                    text.classList.add('text-slate-500');
                    text.innerText = 'Tandai Kunci';
                }
            }
        });
    }

    function handleTypeChange(val) {
        const optSec = document.getElementById('optionsSection');
        if (val === 'multiple_choice' || val === 'true_false') {
            optSec.classList.remove('hidden');
        } else {
            optSec.classList.add('hidden');
        }
    }

    function addOptionRow() {
        const list = document.getElementById('optionsList');
        const count = list.querySelectorAll('.option-row').length;
        if (count >= 5) {
            alert('Maksimal 5 pilihan jawaban (A sampai E).');
            return;
        }

        const nextLetter = String.fromCharCode(65 + count);
        const div = document.createElement('div');
        div.className = 'option-row p-3 rounded-2xl border transition-all flex flex-col sm:flex-row sm:items-center gap-3 bg-surface-container-low border-slate-200';
        div.id = 'option-row-' + count;
        div.innerHTML = `
            <input type="hidden" name="options[${count}][label]" value="${nextLetter}" class="option-label-input">
            <label class="flex items-center gap-2 cursor-pointer shrink-0 select-none">
                <input type="radio" 
                       name="correct_option" 
                       value="${count}" 
                       onchange="highlightCorrectOption(${count})"
                       class="w-5 h-5 text-emerald-600 border-slate-300 focus:ring-emerald-500 cursor-pointer">
                <span class="w-8 h-8 rounded-xl font-bold flex items-center justify-center text-sm shadow-xs bg-surface-container-high text-on-surface" id="label-badge-${count}">
                    ${nextLetter}
                </span>
                <span class="text-xs font-bold text-slate-500" id="kunci-text-${count}">
                    Tandai Kunci
                </span>
            </label>
            <div class="flex-1 flex items-center gap-2 min-w-0">
                <input type="text" 
                       name="options[${count}][text]" 
                       value="" 
                       placeholder="Tuliskan pilihan ${nextLetter}..." 
                       class="w-full h-11 px-3.5 rounded-xl bg-white text-body-md text-on-surface border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition-all shadow-xs font-medium">
                <button type="button" onclick="removeOptionRow(${count})" class="p-2 rounded-lg text-slate-400 hover:text-error hover:bg-error-container transition-colors shrink-0" title="Hapus opsi ini">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                </button>
            </div>
        `;
        list.appendChild(div);
    }

    function removeOptionRow(index) {
        const row = document.getElementById('option-row-' + index);
        if (row) {
            row.remove();
            reindexOptions();
        }
    }

    function reindexOptions() {
        const rows = document.querySelectorAll('.option-row');
        rows.forEach((row, i) => {
            const letter = String.fromCharCode(65 + i);
            row.id = 'option-row-' + i;

            const hiddenLabel = row.querySelector('.option-label-input');
            if (hiddenLabel) {
                hiddenLabel.name = `options[${i}][label]`;
                hiddenLabel.value = letter;
            }

            const radio = row.querySelector('input[type="radio"]');
            if (radio) {
                radio.value = i;
                radio.setAttribute('onchange', `highlightCorrectOption(${i})`);
            }

            const badge = row.querySelector('[id^="label-badge-"]');
            if (badge) {
                badge.id = 'label-badge-' + i;
                badge.innerText = letter;
            }

            const kunciText = row.querySelector('[id^="kunci-text-"]');
            if (kunciText) {
                kunciText.id = 'kunci-text-' + i;
            }

            const textInput = row.querySelector('input[type="text"]');
            if (textInput) {
                textInput.name = `options[${i}][text]`;
                textInput.placeholder = `Tuliskan pilihan ${letter}...`;
            }
        });
    }
</script>
@endsection
