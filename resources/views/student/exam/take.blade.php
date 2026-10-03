@php
    $totalQ = $examQuestions->count();
    $answeredCount = $answers->filter(fn($a) => $a->selected_option_id || !empty(trim($a->answer_text ?? '')))->count();
    $markedCount = $answers->where('is_marked', true)->count();
    $currentAnswer = $answers[$currentExamQuestion->id] ?? null;
    $isMarked = $currentAnswer ? (bool)$currentAnswer->is_marked : false;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $exam->title }} — Lembar Ujian EduExam</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin=""/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "surface-container-highest": "#d3e4fe",
            "on-primary-fixed": "#0f0069",
            "secondary": "#006c49",
            "surface-container": "#e5eeff",
            "on-secondary-fixed-variant": "#005236",
            "error-container": "#ffdad6",
            "inverse-primary": "#c3c0ff",
            "on-tertiary-container": "#ffd4a4",
            "outline-variant": "#c7c4d8",
            "surface-container-high": "#dce9ff",
            "primary-container": "#4f46e5",
            "primary": "#3525cd",
            "background": "#f8f9ff",
            "on-secondary-container": "#00714d",
            "tertiary-fixed-dim": "#ffb95f",
            "tertiary": "#684000",
            "primary-fixed": "#e2dfff",
            "on-secondary-fixed": "#002113",
            "on-primary-container": "#dad7ff",
            "secondary-container": "#6cf8bb",
            "surface": "#f8f9ff",
            "surface-bright": "#f8f9ff",
            "on-surface": "#0b1c30",
            "on-primary": "#ffffff",
            "outline": "#777587",
            "on-tertiary-fixed": "#2a1700",
            "error": "#ba1a1a",
            "on-tertiary": "#ffffff",
            "tertiary-container": "#885500",
            "primary-fixed-dim": "#c3c0ff",
            "on-error": "#ffffff",
            "secondary-fixed-dim": "#4edea3",
            "surface-container-lowest": "#ffffff",
            "on-error-container": "#93000a",
            "on-secondary": "#ffffff",
            "surface-dim": "#cbdbf5",
            "on-background": "#0b1c30",
            "surface-variant": "#d3e4fe",
            "on-tertiary-fixed-variant": "#653e00",
            "surface-container-low": "#eff4ff",
            "surface-tint": "#4d44e3",
            "inverse-on-surface": "#eaf1ff",
            "secondary-fixed": "#6ffbbe",
            "tertiary-fixed": "#ffddb8",
            "on-primary-fixed-variant": "#3323cc",
            "on-surface-variant": "#464555",
            "inverse-surface": "#213145"
          },
          borderRadius: {
            "DEFAULT": "0.25rem",
            "lg": "0.5rem",
            "xl": "0.75rem",
            "2xl": "1rem",
            "full": "9999px"
          },
          fontFamily: {
            "headline-lg": ["Plus Jakarta Sans", "sans-serif"],
            "body-lg": ["Inter", "sans-serif"],
            "label-md": ["Inter", "sans-serif"],
            "body-md": ["Inter", "sans-serif"],
            "label-lg": ["Inter", "sans-serif"],
            "headline-md-mobile": ["Plus Jakarta Sans", "sans-serif"],
            "headline-md": ["Plus Jakarta Sans", "sans-serif"],
            "body-sm": ["Inter", "sans-serif"],
            "label-sm": ["Inter", "sans-serif"],
            "headline-sm": ["Plus Jakarta Sans", "sans-serif"],
            "headline-lg-mobile": ["Plus Jakarta Sans", "sans-serif"]
          }
        }
      }
    };
    </script>
    <style>
        @layer base {
            html, body {
                width: 100%;
                margin: 0;
                padding: 0;
                background-color: #f8f9ff;
            }
            .pb-safe { padding-bottom: env(safe-area-inset-bottom, 0px); }
            .pt-safe { padding-top: env(safe-area-inset-top, 0px); }
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
            line-height: 1;
        }
        .material-symbols-outlined.fill-1 {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Rich text question rendering */
        .question-text img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 0.75rem 0;
            display: inline-block;
        }
        .question-text p {
            margin-bottom: 0.5rem;
            line-height: 1.6;
        }
    </style>
</head>
<body class="bg-surface font-body-md text-on-surface antialiased min-h-screen flex flex-col items-center justify-start select-none">
    <div class="w-full max-w-[480px] min-h-screen flex flex-col relative bg-surface">
        
        <!-- Fixed Sticky Header -->
        <header class="fixed top-0 w-full max-w-[480px] z-50 bg-surface/90 backdrop-blur-xl border-b border-surface-container shadow-[0_1px_8px_rgba(0,0,0,0.04)] pt-safe">
            <div class="h-16 px-4 flex items-center justify-between gap-2">
                <div class="flex items-center gap-2 min-w-0">
                    <button type="button" onclick="confirmExitExam()" class="w-9 h-9 rounded-full flex items-center justify-center text-on-surface hover:bg-surface-container-low transition-colors" title="Kembali ke Dashboard">
                        <span class="material-symbols-outlined text-[22px]">arrow_back</span>
                    </button>
                    <div class="flex flex-col min-w-0">
                        <h1 class="font-headline-sm text-sm font-bold text-on-surface truncate leading-tight">
                            {{ $exam->title }}
                        </h1>
                        <span class="text-[11px] text-on-surface-variant leading-none truncate font-medium">
                            {{ $exam->subject->name ?? 'Ujian Digital' }}
                        </span>
                    </div>
                </div>

                <!-- Right Header Badges: Timer & Count -->
                <div class="flex items-center gap-2 shrink-0">
                    <!-- Timer Badge -->
                    <div id="exam-timer-box" class="flex items-center gap-1 bg-surface-container-high px-2.5 py-1 rounded-full text-primary font-headline-sm text-xs shadow-sm">
                        <span class="material-symbols-outlined text-[15px] text-primary" style="font-variation-settings: 'FILL' 1;">timer</span>
                        <span id="time-display" class="font-mono tracking-wider text-on-surface font-bold">--:--</span>
                    </div>

                    <!-- Question Count Badge -->
                    <div class="bg-surface-container-high px-2.5 py-1 rounded-full text-on-surface text-xs font-semibold shrink-0">
                        <span class="text-primary font-bold">{{ $currentIndex }}</span><span class="text-on-surface-variant">/{{ $totalQ }}</span>
                    </div>
                </div>
            </div>

            <!-- Micro Linear Progress Bar -->
            <div class="w-full bg-surface-container-highest h-1 overflow-hidden">
                <div id="progress-bar-fill" class="bg-primary h-full transition-all duration-300 rounded-r-full" style="width: {{ $totalQ > 0 ? round(($answeredCount / $totalQ) * 100) : 0 }}%;"></div>
            </div>
        </header>

        <!-- Main Examination Canvas -->
        <main class="flex-1 flex flex-col w-full pt-20 pb-36 px-4 bg-surface">
            <!-- Meta & Flag Row -->
            <div class="flex items-center justify-between gap-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="bg-primary text-on-primary font-label-md text-xs font-bold px-2.5 py-1 rounded-lg shadow-sm">
                        No. {{ $currentIndex }}
                    </span>
                    <span class="bg-surface-container text-on-surface-variant text-[11px] font-medium px-2 py-1 rounded-full">
                        Bobot: {{ $currentExamQuestion->score_weight ?? 2.5 }} Poin
                    </span>
                </div>

                <!-- Bookmark / Ragu-ragu Toggle Button -->
                <button type="button" id="flag-btn" onclick="toggleFlag()" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg {{ $isMarked ? 'bg-tertiary-container text-on-tertiary font-bold shadow-sm' : 'bg-surface-container-low text-tertiary font-semibold hover:bg-surface-container' }} text-xs transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[17px]" id="flag-icon" style="{{ $isMarked ? "font-variation-settings: 'FILL' 1;" : '' }}">
                        {{ $isMarked ? 'bookmark' : 'bookmark_border' }}
                    </span>
                    <span id="flag-text">{{ $isMarked ? 'Ragu' : 'Ragu-ragu' }}</span>
                </button>
            </div>

            <!-- Live Auto-Save Toastlet -->
            <div class="flex items-center gap-1.5 text-secondary text-xs font-medium mb-3">
                <span class="material-symbols-outlined text-[16px] text-secondary" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                <span id="autosave-status">Jawaban tersimpan otomatis</span>
            </div>

            <!-- Question Prompt Card -->
            <div class="bg-surface-container-lowest p-4 rounded-2xl shadow-sm border border-surface-container flex flex-col gap-3 mb-4">
                <div class="question-text font-body-lg text-sm md:text-base text-on-surface leading-relaxed">
                    {!! $currentExamQuestion->question->question_text !!}
                </div>

                @if($currentExamQuestion->question->question_image)
                    <div class="w-full bg-surface-container-low rounded-xl p-2.5 flex flex-col items-center">
                        <img src="{{ Storage::url($currentExamQuestion->question->question_image) }}" alt="Gambar Soal {{ $currentIndex }}" class="rounded-lg max-h-72 object-contain shadow-sm">
                        <span class="text-[11px] text-on-surface-variant mt-1.5">Gambar Lampiran Soal {{ $currentIndex }}</span>
                    </div>
                @endif
            </div>

            <!-- Answer Options Form -->
            <form id="answer-form" class="flex flex-col gap-2.5">
                <input type="hidden" name="exam_question_id" value="{{ $currentExamQuestion->id }}">

                @if(in_array($currentExamQuestion->question->type, ['multiple_choice', 'true_false']))
                    <div class="flex flex-col gap-2.5" role="radiogroup">
                        @foreach($currentExamQuestion->question->options as $option)
                            @php
                                $isSelected = $currentAnswer && $currentAnswer->selected_option_id == $option->id;
                            @endphp
                            <label for="opt-{{ $option->id }}" class="option-card w-full min-h-[56px] px-3.5 py-3 rounded-xl border {{ $isSelected ? 'border-primary bg-surface-container shadow-sm' : 'border-surface-container bg-surface-container-lowest hover:bg-surface-container-low' }} cursor-pointer flex items-center justify-between transition-all duration-150 active:scale-[0.99]">
                                <input type="radio" name="selected_option_id" id="opt-{{ $option->id }}" value="{{ $option->id }}" class="hidden option-input" {{ $isSelected ? 'checked' : '' }} onchange="selectOptionRadio(this, '{{ $option->label }}')">
                                
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <div class="option-indicator w-8 h-8 rounded-full {{ $isSelected ? 'bg-primary text-on-primary shadow-sm font-bold' : 'bg-surface-container text-on-surface-variant font-semibold' }} flex items-center justify-center text-xs shrink-0 transition-colors">
                                        {{ $option->label }}
                                    </div>
                                    <div class="option-text text-xs md:text-sm {{ $isSelected ? 'text-primary font-bold' : 'text-on-surface' }} leading-relaxed flex-1">
                                        {!! $option->option_text !!}
                                        @if($option->option_image)
                                            <img src="{{ Storage::url($option->option_image) }}" alt="Opsi {{ $option->label }}" class="max-h-36 rounded-md mt-1.5 block">
                                        @endif
                                    </div>
                                </div>

                                <span class="check-icon material-symbols-outlined text-[20px] text-primary shrink-0 transition-opacity {{ $isSelected ? 'opacity-100' : 'opacity-0' }}" style="font-variation-settings: 'FILL' 1;">
                                    check_circle
                                </span>
                            </label>
                        @endforeach
                    </div>
                @else
                    <!-- Essay / Short Answer Field -->
                    <div class="bg-surface-container-lowest p-3.5 rounded-2xl border border-surface-container flex flex-col gap-2">
                        <label for="answer-text-input" class="text-xs font-bold text-on-surface flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-primary text-[18px]">edit_note</span>
                            <span>Tuliskan Jawaban Uraian Anda:</span>
                        </label>
                        <textarea name="answer_text" id="answer-text-input" rows="6" class="w-full p-3 bg-surface-container-low text-on-surface text-sm rounded-xl border border-surface-container focus:outline-none focus:bg-surface-container-lowest focus:border-primary transition-all resize-y placeholder:text-outline" placeholder="Ketik jawaban lengkap di sini...">{{ $currentAnswer ? $currentAnswer->answer_text : '' }}</textarea>
                    </div>
                @endif
            </form>

            <!-- Visual Encouragement Banner -->
            <div class="bg-surface-container-low rounded-xl p-3 flex items-center gap-2.5 mt-4 border border-surface-container">
                <div class="w-8 h-8 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                </div>
                <p class="font-body-sm text-xs text-on-surface-variant leading-snug">
                    Anda sedang mengerjakan nomor <strong>{{ $currentIndex }} dari {{ $totalQ }} soal</strong>. Fokus dan teliti sebelum beralih ke nomor selanjutnya!
                </p>
            </div>
        </main>

        <!-- Persistent Fixed Bottom Footer -->
        <footer class="fixed bottom-0 left-0 right-0 z-40 bg-surface/95 backdrop-blur-md border-t border-surface-container shadow-[0_-4px_16px_rgba(11,28,48,0.06)] pt-2.5 pb-safe">
            <div class="max-w-[480px] mx-auto px-4 flex flex-col gap-2">
                <div class="grid grid-cols-12 gap-2 items-center">
                    <!-- Previous Button -->
                    @if($currentIndex > 1)
                        <a href="{{ route('student.exam.take', ['exam' => $exam->id, 'q' => $currentIndex - 1]) }}" class="col-span-3 h-11 rounded-xl bg-surface-container text-on-surface hover:bg-surface-container-high active:scale-95 flex items-center justify-center gap-1 text-xs font-semibold transition-transform">
                            <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                            <span class="hidden sm:inline">Sebelum</span>
                        </a>
                    @else
                        <button type="button" disabled class="col-span-3 h-11 rounded-xl bg-surface-container/60 text-on-surface-variant/40 flex items-center justify-center gap-1 text-xs font-semibold cursor-not-allowed">
                            <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                            <span class="hidden sm:inline">Sebelum</span>
                        </button>
                    @endif

                    <!-- Center "Daftar Soal" Trigger Button -->
                    <button type="button" onclick="toggleQuestionSheet()" class="col-span-6 h-11 rounded-xl bg-surface-container-high hover:bg-surface-container-highest text-on-surface text-xs font-semibold px-2 flex items-center justify-center gap-1.5 active:scale-95 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[18px] text-primary">apps</span>
                        <span>Daftar Soal</span>
                        <span id="answered-badge" class="bg-surface-container-lowest text-primary font-bold px-1.5 py-0.5 rounded text-[10px]">
                            {{ $answeredCount }}/{{ $totalQ }}
                        </span>
                    </button>

                    <!-- Next Button / Finish on last question -->
                    @if($currentIndex < $totalQ)
                        <a href="{{ route('student.exam.take', ['exam' => $exam->id, 'q' => $currentIndex + 1]) }}" class="col-span-3 h-11 rounded-xl bg-primary hover:bg-primary-container text-on-primary active:scale-95 flex items-center justify-center gap-1 text-xs font-semibold transition-transform shadow-sm">
                            <span class="hidden sm:inline">Berikut</span>
                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                        </a>
                    @else
                        <button type="button" onclick="openSubmitModal()" class="col-span-3 h-11 rounded-xl bg-secondary hover:opacity-90 text-on-secondary active:scale-95 flex items-center justify-center gap-1 text-xs font-bold transition-transform shadow-md">
                            <span>Selesai</span>
                            <span class="material-symbols-outlined text-[18px]">check</span>
                        </button>
                    @endif
                </div>

                <!-- Discreet Finish Exam Link -->
                <div class="flex items-center justify-center pb-1">
                    <button type="button" onclick="openSubmitModal()" class="text-on-surface-variant hover:text-error text-xs transition-colors flex items-center gap-1 py-0.5 px-2">
                        <span class="material-symbols-outlined text-[14px]">flag</span>
                        <span>Selesaikan Ujian Sekarang</span>
                    </button>
                </div>
            </div>
        </footer>

        <!-- Bottom Sheet Drawer: Question Navigator (Screen 5) -->
        <div id="question-sheet" class="fixed inset-0 z-50 bg-inverse-surface/40 backdrop-blur-xs hidden flex-col justify-end transition-opacity">
            <div class="w-full max-w-[480px] mx-auto bg-surface-container-lowest rounded-t-3xl p-4 flex flex-col gap-3 shadow-2xl max-h-[85vh]">
                <!-- Header -->
                <div class="flex items-center justify-between pb-2 border-b border-surface-container">
                    <div>
                        <h3 class="font-headline-sm text-base font-bold text-on-surface">Daftar Nomor Soal</h3>
                        <p class="font-body-sm text-xs text-on-surface-variant">Pilih nomor untuk melompat langsung ke soal</p>
                    </div>
                    <button type="button" onclick="toggleQuestionSheet()" class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-on-surface hover:bg-surface-container-high transition-colors">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>

                <!-- State Legend -->
                <div class="flex items-center justify-between text-xs py-1.5 bg-surface-container-low px-3 rounded-xl border border-surface-container">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded bg-secondary"></span>
                        <span class="text-on-surface text-[11px] font-medium">Dijawab (<span id="legend-answered">{{ $answeredCount }}</span>)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded bg-tertiary-fixed-dim"></span>
                        <span class="text-on-surface text-[11px] font-medium">Ragu (<span id="legend-marked">{{ $markedCount }}</span>)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded bg-surface-container-highest"></span>
                        <span class="text-on-surface text-[11px] font-medium">Belum (<span id="legend-unanswered">{{ $totalQ - $answeredCount }}</span>)</span>
                    </div>
                </div>

                <!-- Question Grid Palette (5 Columns) -->
                <div class="grid grid-cols-5 gap-2 overflow-y-auto max-h-72 py-2 pr-1 no-scrollbar">
                    @foreach($examQuestions as $eq)
                        @php
                            $ans = $answers[$eq->id] ?? null;
                            $hasAns = $ans && ($ans->selected_option_id || !empty(trim($ans->answer_text ?? '')));
                            $isM = $ans && $ans->is_marked;
                            $isActive = $loop->iteration == $currentIndex;
                        @endphp
                        <a href="{{ route('student.exam.take', ['exam' => $exam->id, 'q' => $loop->iteration]) }}" 
                           id="grid-box-{{ $eq->id }}"
                           class="grid-item h-11 rounded-xl flex items-center justify-center font-headline-sm text-xs font-bold transition-all active:scale-95 shadow-sm
                           @if($isActive)
                               bg-primary text-on-primary ring-2 ring-primary ring-offset-2
                           @elseif($isM)
                               bg-tertiary-fixed-dim text-on-tertiary-fixed
                           @elseif($hasAns)
                               bg-secondary text-on-secondary
                           @else
                               bg-surface-container text-on-surface-variant hover:bg-surface-container-high
                           @endif">
                            {{ $loop->iteration }}
                        </a>
                    @endforeach
                </div>

                <button type="button" onclick="toggleQuestionSheet()" class="w-full py-3 rounded-xl bg-surface-container-high font-label-lg text-xs font-semibold text-on-surface text-center hover:bg-surface-container-highest transition-colors">
                    Tutup Lembar Navigasi
                </button>
            </div>
        </div>

        <!-- Submit Confirmation Modal (Screen 6) -->
        <div id="submit-modal" class="fixed inset-0 z-50 bg-inverse-surface/40 backdrop-blur-sm hidden items-center justify-center p-4 transition-opacity">
            <div class="w-full max-w-[390px] bg-surface-container-lowest rounded-2xl shadow-2xl flex flex-col p-5 relative overflow-hidden border border-surface-container animate-[scaleIn_0.2s_ease-out]">
                <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-primary-fixed/30 pointer-events-none blur-xl"></div>
                <div class="absolute -bottom-10 -left-10 w-28 h-28 rounded-full bg-secondary-fixed/20 pointer-events-none blur-lg"></div>
                
                <div class="relative flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-full bg-primary-fixed flex items-center justify-center text-primary mb-3 shadow-sm">
                        <span class="material-symbols-outlined text-[30px]" style="font-variation-settings: 'FILL' 1;">assignment_turned_in</span>
                    </div>
                    <h2 class="font-headline-sm text-base md:text-lg font-bold text-on-surface">
                        Sudah yakin ingin mengakhiri ujian?
                    </h2>
                    <p class="font-body-sm text-xs text-on-surface-variant mt-1">
                        Setelah dikumpulkan, seluruh jawaban Anda akan terkunci dan tidak dapat diubah kembali.
                    </p>
                </div>

                <!-- 2x2 Stats Summary Grid -->
                <div class="grid grid-cols-2 gap-2 mt-4">
                    <div class="bg-surface-container-low rounded-xl p-2.5 flex flex-col items-center justify-center text-center shadow-sm">
                        <span class="text-[10px] text-on-surface-variant">Total Soal</span>
                        <span class="font-headline-sm text-base text-on-surface font-bold mt-0.5">{{ $totalQ }}</span>
                        <div class="mt-1 px-2 py-0.5 rounded-full bg-surface-container-highest text-on-surface text-[10px] font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]">format_list_numbered</span>
                            <span>Soal Ujian</span>
                        </div>
                    </div>
                    <div class="bg-surface-container-low rounded-xl p-2.5 flex flex-col items-center justify-center text-center shadow-sm">
                        <span class="text-[10px] text-secondary">Sudah Dijawab</span>
                        <span id="modal-answered-count" class="font-headline-sm text-base text-secondary font-bold mt-0.5">{{ $answeredCount }}</span>
                        <div class="mt-1 px-2 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed-variant text-[10px] font-semibold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            <span>Selesai</span>
                        </div>
                    </div>
                    <div class="bg-surface-container-low rounded-xl p-2.5 flex flex-col items-center justify-center text-center shadow-sm">
                        <span class="text-[10px] text-error">Belum Dijawab</span>
                        <span id="modal-unanswered-count" class="font-headline-sm text-base text-error font-bold mt-0.5">{{ $totalQ - $answeredCount }}</span>
                        <div class="mt-1 px-2 py-0.5 rounded-full bg-error-container text-on-error-container text-[10px] font-semibold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]">radio_button_unchecked</span>
                            <span>Kosong</span>
                        </div>
                    </div>
                    <div class="bg-surface-container-low rounded-xl p-2.5 flex flex-col items-center justify-center text-center shadow-sm">
                        <span class="text-[10px] text-tertiary">Ditandai / Ragu</span>
                        <span id="modal-marked-count" class="font-headline-sm text-base text-tertiary font-bold mt-0.5">{{ $markedCount }}</span>
                        <div class="mt-1 px-2 py-0.5 rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant text-[10px] font-semibold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]" style="font-variation-settings: 'FILL' 1;">bookmark</span>
                            <span>Perlu Cek</span>
                        </div>
                    </div>
                </div>

                <div class="mt-3 p-2.5 bg-tertiary-fixed/30 rounded-xl flex items-start gap-2 border border-tertiary/20">
                    <span class="material-symbols-outlined text-tertiary shrink-0 text-[18px] mt-0.5" style="font-variation-settings: 'FILL' 1;">info</span>
                    <p class="font-body-sm text-[11px] text-on-surface leading-snug">
                        Pastikan seluruh soal telah Anda jawab dengan baik sebelum konfirmasi pengiriman.
                    </p>
                </div>

                <div class="flex flex-col gap-2 mt-4">
                    <button type="button" onclick="closeSubmitModal()" class="w-full h-11 rounded-xl bg-surface-container-low hover:bg-surface-container text-primary font-label-lg text-xs font-semibold flex items-center justify-center gap-1 transition-colors active:scale-[0.99]">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                        <span>Periksa Kembali</span>
                    </button>
                    <form action="{{ route('student.exam.submit', $exam->id) }}" method="POST" id="submitExamFinalForm">
                        @csrf
                        <button type="submit" id="btnConfirmSubmit" class="w-full h-11 rounded-xl bg-primary hover:bg-primary-container text-on-primary font-label-lg text-xs font-bold flex items-center justify-center gap-1.5 shadow-md transition-all active:scale-[0.99]">
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">check</span>
                            <span>Kumpulkan Jawaban Sekarang</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- Interactive Examination Script -->
    <script>
        const examId = {{ $exam->id }};
        const eqId = {{ $currentExamQuestion->id }};
        const totalQuestions = {{ $totalQ }};
        let remainingSeconds = {{ $remainingSeconds }};
        let isFlagged = {{ $isMarked ? 'true' : 'false' }};
        let saveTimeout = null;

        // Monospace Timer Countdown
        const timeDisplay = document.getElementById('time-display');
        const timerBox = document.getElementById('exam-timer-box');

        function formatTimer(totalSecs) {
            const h = Math.floor(totalSecs / 3600);
            const m = Math.floor((totalSecs % 3600) / 60);
            const s = totalSecs % 60;
            if (h > 0) {
                return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
            }
            return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        }

        const timerInterval = setInterval(() => {
            if (remainingSeconds <= 0) {
                clearInterval(timerInterval);
                timeDisplay.textContent = "00:00";
                alert("Waktu ujian telah berakhir! Jawaban Anda akan otomatis dikumpulkan.");
                document.getElementById('submitExamFinalForm').submit();
                return;
            }

            remainingSeconds--;
            timeDisplay.textContent = formatTimer(remainingSeconds);

            // Warning under 5 minutes
            if (remainingSeconds <= 300) {
                timerBox.classList.remove('bg-surface-container-high', 'text-primary');
                timerBox.classList.add('bg-error-container', 'text-error', 'animate-pulse');
            }
        }, 1000);
        timeDisplay.textContent = formatTimer(remainingSeconds);

        // Auto Save Answer function
        function triggerAutoSave() {
            clearTimeout(saveTimeout);
            const statusEl = document.getElementById('autosave-status');
            statusEl.textContent = 'Menyimpan...';

            saveTimeout = setTimeout(() => {
                const form = document.getElementById('answer-form');
                const formData = new FormData(form);
                formData.append('is_marked', isFlagged ? 1 : 0);

                fetch("{{ route('student.exam.answer', $exam->id) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'ok') {
                        statusEl.textContent = 'Jawaban tersimpan otomatis';
                        updateNavigatorState(data.answer);
                    }
                })
                .catch(err => {
                    statusEl.textContent = 'Gagal menyimpan, periksa koneksi';
                });
            }, 400);
        }

        // Option Selection Radio Handler
        function selectOptionRadio(inputEl, label) {
            // Update styles across options
            document.querySelectorAll('.option-card').forEach(card => {
                card.classList.remove('border-primary', 'bg-surface-container', 'shadow-sm');
                card.classList.add('border-surface-container', 'bg-surface-container-lowest');

                const indicator = card.querySelector('.option-indicator');
                indicator.className = 'option-indicator w-8 h-8 rounded-full bg-surface-container text-on-surface-variant font-semibold flex items-center justify-center text-xs shrink-0 transition-colors';

                const text = card.querySelector('.option-text');
                text.className = 'option-text text-xs md:text-sm text-on-surface leading-relaxed flex-1';

                const check = card.querySelector('.check-icon');
                check.classList.remove('opacity-100');
                check.classList.add('opacity-0');
            });

            const parentCard = inputEl.closest('.option-card');
            parentCard.classList.remove('border-surface-container', 'bg-surface-container-lowest');
            parentCard.classList.add('border-primary', 'bg-surface-container', 'shadow-sm');

            const activeInd = parentCard.querySelector('.option-indicator');
            activeInd.className = 'option-indicator w-8 h-8 rounded-full bg-primary text-on-primary shadow-sm font-bold flex items-center justify-center text-xs shrink-0 transition-colors';

            const activeText = parentCard.querySelector('.option-text');
            activeText.className = 'option-text text-xs md:text-sm text-primary font-bold leading-relaxed flex-1';

            const activeCheck = parentCard.querySelector('.check-icon');
            activeCheck.classList.remove('opacity-0');
            activeCheck.classList.add('opacity-100');

            triggerAutoSave();
        }

        // Essay text input handler
        const essayInput = document.getElementById('answer-text-input');
        if (essayInput) {
            essayInput.addEventListener('input', triggerAutoSave);
        }

        // Flag / Ragu-ragu Toggle
        function toggleFlag() {
            isFlagged = !isFlagged;
            const btn = document.getElementById('flag-btn');
            const icon = document.getElementById('flag-icon');
            const text = document.getElementById('flag-text');

            if (isFlagged) {
                btn.className = 'flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-tertiary-container text-on-tertiary font-bold shadow-sm text-xs transition-all active:scale-95';
                icon.textContent = 'bookmark';
                icon.style.fontVariationSettings = "'FILL' 1";
                text.textContent = 'Ragu';
            } else {
                btn.className = 'flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container-low text-tertiary font-semibold hover:bg-surface-container text-xs transition-all active:scale-95';
                icon.textContent = 'bookmark_border';
                icon.style.fontVariationSettings = '';
                text.textContent = 'Ragu-ragu';
            }

            triggerAutoSave();
        }

        // Update Question Navigator Grid State
        function updateNavigatorState(answer) {
            const gridBox = document.getElementById(`grid-box-${eqId}`);
            if (gridBox) {
                const isCurrent = {{ $currentIndex }} == gridBox.textContent.trim();
                const hasAnswer = answer.selected_option_id || (answer.answer_text && answer.answer_text.trim() !== '');

                gridBox.className = 'grid-item h-11 rounded-xl flex items-center justify-center font-headline-sm text-xs font-bold transition-all active:scale-95 shadow-sm';

                if (isCurrent) {
                    gridBox.classList.add('bg-primary', 'text-on-primary', 'ring-2', 'ring-primary', 'ring-offset-2');
                } else if (answer.is_marked) {
                    gridBox.classList.add('bg-tertiary-fixed-dim', 'text-on-tertiary-fixed');
                } else if (hasAnswer) {
                    gridBox.classList.add('bg-secondary', 'text-on-secondary');
                } else {
                    gridBox.classList.add('bg-surface-container', 'text-on-surface-variant');
                }
            }
        }

        // Question Navigator Bottom Sheet Toggle
        function toggleQuestionSheet() {
            const sheet = document.getElementById('question-sheet');
            if (sheet.classList.contains('hidden')) {
                sheet.classList.remove('hidden');
                sheet.classList.add('flex');
            } else {
                sheet.classList.add('hidden');
                sheet.classList.remove('flex');
            }
        }

        // Submit Confirmation Modal Toggle
        function openSubmitModal() {
            const modal = document.getElementById('submit-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeSubmitModal() {
            const modal = document.getElementById('submit-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function confirmExitExam() {
            if (confirm("Ujian sedang berlangsung! Apakah Anda yakin ingin kembali ke dashboard? Sisa waktu Anda akan terus berjalan.")) {
                window.location.href = "{{ route('student.dashboard') }}";
            }
        }

        // Submit loading indicator
        document.getElementById('submitExamFinalForm').addEventListener('submit', function() {
            const btn = document.getElementById('btnConfirmSubmit');
            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span><span>Mengirim Jawaban...</span>';
        });
    </script>
</body>
</html>
