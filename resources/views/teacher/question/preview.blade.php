@extends('layouts.teacher')

@section('title', 'Pratinjau Impor Butir Soal — EduExam')
@section('page_title', 'Bank Soal')

@section('teacher-content')
<div class="flex flex-col w-full pb-20">

    <!-- Top Breadcrumb & Header -->
    <div class="flex flex-wrap items-center justify-between gap-space-md py-space-sm mb-space-lg">
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-space-xs text-on-surface-variant font-label-md text-label-md">
                <a href="{{ route('teacher.questions.index') }}" class="hover:text-primary transition-colors">Bank Soal</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <a href="{{ route('teacher.questions.import') }}" class="hover:text-primary transition-colors">Impor Soal</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-semibold">Pratinjau Hasil Impor</span>
            </div>
            <div class="flex items-baseline gap-space-sm">
                <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">Pratinjau &amp; Verifikasi Soal</h1>
                <span class="inline-flex items-center gap-1 px-space-sm py-0.5 rounded-full bg-primary/10 text-primary font-label-sm text-label-sm font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary"></span> {{ $originalFilename }}
                </span>
            </div>
        </div>

        <!-- Action Buttons (Konfirmasi & Batal) -->
        <div class="flex items-center gap-space-sm flex-wrap">
            <a href="{{ route('teacher.questions.import', ['subject_id' => $subject->id, 'classroom_id' => $classroom?->id]) }}" class="px-space-md py-2.5 rounded-xl bg-surface-container-high text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest transition-colors font-label-lg text-label-lg">
                Batal / Unggah Ulang
            </a>

            @if($result['valid_count'] > 0)
                <form action="{{ route('teacher.questions.import.confirm') }}" method="POST" id="confirmImportForm" onsubmit="handleConfirmSubmit(this)">
                    @csrf
                    <input type="hidden" name="subject_id" value="{{ $subject->id }}"/>
                    <input type="hidden" name="classroom_id" value="{{ $classroom?->id }}"/>
                    <input type="hidden" name="topic" value="{{ $topic }}"/>
                    <input type="hidden" name="confirm_token" value="{{ $confirmToken }}"/>
                    <input type="hidden" name="payload" value="{{ json_encode($result['questions']) }}"/>

                    <button type="submit" id="btnConfirmSubmit" class="inline-flex items-center gap-2 px-space-lg py-2.5 rounded-xl bg-emerald-600 text-white font-label-lg text-label-lg shadow-md hover:bg-emerald-700 active:scale-[0.98] transition-all">
                        <span class="material-symbols-outlined text-[20px]">check_circle</span>
                        <span>Konfirmasi &amp; Simpan {{ $result['valid_count'] }} Soal Valid</span>
                    </button>
                </form>
            @else
                <button type="button" disabled class="inline-flex items-center gap-2 px-space-lg py-2.5 rounded-xl bg-slate-300 text-slate-500 font-label-lg text-label-lg cursor-not-allowed">
                    <span class="material-symbols-outlined text-[20px]">block</span>
                    <span>Tidak Ada Soal Valid untuk Disimpan</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Ringkasan Bento Card -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-md mb-space-lg">
        <!-- Target Paket -->
        <div class="p-space-md rounded-2xl bg-surface-container-lowest shadow-sm border border-slate-100 flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[24px]">school</span>
            </div>
            <div class="flex flex-col min-w-0">
                <span class="text-xs text-on-surface-variant font-medium">Paket Target</span>
                <span class="font-bold text-on-surface truncate text-sm">{{ $subject->name }}</span>
                <span class="text-[11px] text-on-surface-variant truncate">{{ $classroom ? 'Kelas ' . $classroom->name : 'Semua Kelas (Umum)' }}</span>
            </div>
        </div>

        <!-- Total Terbaca -->
        <div class="p-space-md rounded-2xl bg-surface-container-lowest shadow-sm border border-slate-100 flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[24px]">ballot</span>
            </div>
            <div class="flex flex-col">
                <span class="text-xs text-on-surface-variant font-medium">Total Baris Soal</span>
                <span class="font-headline-sm text-headline-sm font-bold text-on-surface">{{ $result['total_count'] }}</span>
                <span class="text-[11px] text-on-surface-variant">Terbaca dari spreadsheet</span>
            </div>
        </div>

        <!-- Valid -->
        <div class="p-space-md rounded-2xl bg-surface-container-lowest shadow-sm border border-slate-100 flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[24px]">check_circle</span>
            </div>
            <div class="flex flex-col">
                <span class="text-xs text-emerald-800 font-medium">Soal Siap Simpan (Valid)</span>
                <span class="font-headline-sm text-headline-sm font-bold text-emerald-700">{{ $result['valid_count'] }}</span>
                <span class="text-[11px] text-emerald-600">Lolos semua aturan validasi</span>
            </div>
        </div>

        <!-- Tidak Valid -->
        <div class="p-space-md rounded-2xl bg-surface-container-lowest shadow-sm border border-slate-100 flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-700 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[24px]">error</span>
            </div>
            <div class="flex flex-col">
                <span class="text-xs text-red-800 font-medium">Soal Bermasalah (Tidak Valid)</span>
                <span class="font-headline-sm text-headline-sm font-bold text-red-700">{{ $result['invalid_count'] }}</span>
                <span class="text-[11px] text-red-600">Akan dilewati saat konfirmasi</span>
            </div>
        </div>
    </div>

    <!-- Alert jika ada baris tidak valid -->
    @if($result['invalid_count'] > 0)
        <div class="mb-space-lg p-4 rounded-2xl bg-amber-50 text-amber-900 border border-amber-200 text-xs flex items-start gap-3 shadow-sm">
            <span class="material-symbols-outlined text-[22px] text-amber-700 flex-shrink-0 mt-0.5">warning</span>
            <div class="flex flex-col gap-1">
                <span class="font-bold text-sm text-amber-900">Perhatian: Terdeteksi {{ $result['invalid_count'] }} baris soal yang tidak memenuhi syarat format!</span>
                <p class="leading-relaxed">
                    Baris yang ditandai merah di bawah memiliki kesalahan (seperti kunci jawaban tidak cocok, opsi kurang dari 2, atau teks pertanyaan kosong). 
                    Jika Anda menekan tombol <strong>Konfirmasi &amp; Simpan</strong>, hanya <strong>{{ $result['valid_count'] }} soal valid</strong> yang akan dimasukkan ke Bank Soal. Baris yang bermasalah akan otomatis dilewati.
                </p>
            </div>
        </div>
    @endif

    <!-- Filter Tab (Semua, Valid, Bermasalah) -->
    <div class="flex flex-wrap items-center justify-between gap-space-sm mb-space-md">
        <div class="inline-flex rounded-xl p-1 bg-surface-container-low border border-slate-200/80">
            <button type="button" onclick="filterPreviewRows('all')" id="tabAll" class="px-4 py-1.5 rounded-lg font-label-md text-label-md font-bold bg-surface-container-lowest text-on-surface shadow-sm transition-all">
                Semua Soal ({{ $result['total_count'] }})
            </button>
            <button type="button" onclick="filterPreviewRows('valid')" id="tabValid" class="px-4 py-1.5 rounded-lg font-label-md text-label-md text-on-surface-variant hover:text-emerald-700 transition-all">
                Valid ({{ $result['valid_count'] }})
            </button>
            <button type="button" onclick="filterPreviewRows('invalid')" id="tabInvalid" class="px-4 py-1.5 rounded-lg font-label-md text-label-md text-on-surface-variant hover:text-red-700 transition-all">
                Bermasalah ({{ $result['invalid_count'] }})
            </button>
        </div>

        <span class="text-xs text-on-surface-variant">
            Menampilkan data pratinjau sebelum penulisan ke database
        </span>
    </div>

    <!-- Questions Preview List -->
    <div class="flex flex-col gap-space-md" id="previewListContainer">
        @foreach($result['questions'] as $q)
            @php
                $isValid = $q['is_valid'];
                $cardBorder = $isValid ? 'border-slate-200/80 hover:border-emerald-300' : 'border-red-300 bg-red-50/20';
                $badgeBg = $isValid ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200';
            @endphp
            <div class="preview-item rounded-2xl bg-surface-container-lowest p-5 sm:p-6 shadow-sm border {{ $cardBorder }} transition-all flex flex-col gap-space-sm" data-status="{{ $isValid ? 'valid' : 'invalid' }}">
                
                <!-- Card Header -->
                <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="px-2.5 py-0.5 rounded-lg font-mono text-xs font-bold bg-slate-100 text-slate-800">
                            Baris #{{ $q['row_number'] }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeBg }}">
                            {{ $isValid ? 'VALID' : 'TIDAK VALID' }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary/10 text-primary">
                            {{ $q['type_label'] }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-surface-container-low text-on-surface-variant">
                            Tingkat: {{ $q['difficulty_label'] }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-surface-container-low text-on-surface-variant">
                            Bobot: {{ $q['score'] }} Poin
                        </span>
                        @if(!empty($q['topic']) || !empty($topic))
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-800 border border-amber-100">
                                Topik: {{ $q['topic'] ?: $topic }}
                            </span>
                        @endif
                    </div>

                    @if(!$isValid)
                        <div class="flex items-center gap-1 text-red-600 text-xs font-semibold">
                            <span class="material-symbols-outlined text-[16px]">error</span>
                            <span>{{ count($q['errors']) }} Masalah Terdeteksi</span>
                        </div>
                    @endif
                </div>

                <!-- Error Box if invalid -->
                @if(!$isValid)
                    <div class="p-3 rounded-xl bg-red-50 text-red-800 text-xs border border-red-200 flex flex-col gap-1">
                        <span class="font-bold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">cancel</span>
                            Penyebab Baris Tidak Valid:
                        </span>
                        <ul class="list-disc pl-5 space-y-0.5">
                            @foreach($q['errors'] as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Question Text -->
                <div class="text-sm font-semibold text-on-surface leading-relaxed pt-1">
                    {{ $q['question_text'] ?: '(Teks pertanyaan kosong)' }}
                </div>

                <!-- Options / Answers Display -->
                @if(in_array($q['type'], ['multiple_choice', 'true_false']) && !empty($q['options']))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                        @foreach($q['options'] as $opt)
                            @php
                                $isCorrect = $opt['is_correct'];
                                $optBorder = $isCorrect ? 'border-emerald-300 bg-emerald-50/50 text-emerald-950 font-semibold' : 'border-slate-100 bg-surface-container-low/50 text-on-surface-variant';
                            @endphp
                            <div class="flex items-center gap-2.5 p-2.5 rounded-xl border {{ $optBorder }} text-xs">
                                <span class="w-6 h-6 rounded-lg flex items-center justify-center font-bold text-[11px] {{ $isCorrect ? 'bg-emerald-600 text-white' : 'bg-surface-container-high text-on-surface' }}">
                                    {{ $opt['label'] }}
                                </span>
                                <span class="flex-1 truncate">{{ $opt['text'] }}</span>
                                @if($isCorrect)
                                    <span class="material-symbols-outlined text-emerald-600 text-[18px]">check_circle</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @elseif($q['type'] === 'short_answer')
                    <div class="mt-2 p-2.5 rounded-xl bg-surface-container-low border border-slate-100 text-xs flex items-center gap-2">
                        <span class="font-semibold text-on-surface-variant">Kunci Jawaban Isian:</span>
                        <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-900 font-bold font-mono">{{ $q['correct_answer'] ?: '-' }}</span>
                    </div>
                @elseif($q['type'] === 'essay')
                    <div class="mt-2 p-2.5 rounded-xl bg-surface-container-low border border-slate-100 text-xs text-on-surface-variant">
                        <em>Soal bertipe Esai / Uraian. Jawaban siswa akan dinilai secara manual oleh Guru melalui menu Hasil Ujian.</em>
                    </div>
                @endif

                <!-- Explanation (if any) -->
                @if(!empty($q['explanation']))
                    <div class="mt-1 text-xs text-on-surface-variant bg-surface-container-low/40 p-2.5 rounded-xl border border-slate-100">
                        <strong class="text-on-surface">Pembahasan:</strong> {{ $q['explanation'] }}
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Bottom Action Bar (Fixed / Sticky Footer) -->
    <div class="sticky bottom-4 mt-space-xl p-4 rounded-2xl bg-surface-container-lowest/95 backdrop-blur-md shadow-xl border border-slate-200 flex flex-wrap items-center justify-between gap-space-md z-20">
        <div class="flex items-center gap-space-md">
            <span class="text-xs text-on-surface-variant">
                Ringkasan: <strong class="text-emerald-700">{{ $result['valid_count'] }} soal valid</strong> siap diimpor, 
                <strong class="text-red-700">{{ $result['invalid_count'] }} soal bermasalah</strong> akan dilewati.
            </span>
        </div>

        <div class="flex items-center gap-space-sm">
            <a href="{{ route('teacher.questions.import', ['subject_id' => $subject->id, 'classroom_id' => $classroom?->id]) }}" class="px-space-md py-2 rounded-xl bg-surface-container-high text-on-surface-variant hover:text-on-surface font-label-md text-label-md">
                Batal
            </a>
            @if($result['valid_count'] > 0)
                <button type="button" onclick="document.getElementById('confirmImportForm').submit()" class="inline-flex items-center gap-1.5 px-space-lg py-2 rounded-xl bg-emerald-600 text-white font-label-md text-label-md font-bold shadow-md hover:bg-emerald-700 active:scale-95 transition-all">
                    <span class="material-symbols-outlined text-[18px]">check</span>
                    <span>Konfirmasi Simpan {{ $result['valid_count'] }} Soal</span>
                </button>
            @endif
        </div>
    </div>
</div>

<script>
    function filterPreviewRows(type) {
        const tabs = ['tabAll', 'tabValid', 'tabInvalid'];
        tabs.forEach(t => {
            const el = document.getElementById(t);
            el.className = 'px-4 py-1.5 rounded-lg font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-all';
        });

        const activeTab = type === 'all' ? 'tabAll' : (type === 'valid' ? 'tabValid' : 'tabInvalid');
        document.getElementById(activeTab).className = 'px-4 py-1.5 rounded-lg font-label-md text-label-md font-bold bg-surface-container-lowest text-on-surface shadow-sm transition-all';

        const items = document.querySelectorAll('.preview-item');
        items.forEach(item => {
            if (type === 'all') {
                item.classList.remove('hidden');
            } else if (item.getAttribute('data-status') === type) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });
    }

    function handleConfirmSubmit(form) {
        const btn = document.getElementById('btnConfirmSubmit');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined text-[20px] animate-spin">refresh</span><span>Menyimpan ke Bank Soal...</span>';
        }
    }
</script>
@endsection
