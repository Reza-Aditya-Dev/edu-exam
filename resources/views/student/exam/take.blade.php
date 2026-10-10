@php
    $totalQ = $examQuestions->count();
    $answeredCount = $answers->filter(fn($a) => $a->selected_option_id || !empty(trim($a->answer_text ?? '')))->count();
    $markedCount = $answers->where('is_marked', true)->count();
    $currentAnswer = $answers[$currentExamQuestion->id] ?? null;
    $isMarked = $currentAnswer ? (bool)$currentAnswer->is_marked : false;
    $questionsData = $examQuestions->values()->map(function($eq, $idx) use ($answers) {
        $ans = $answers[$eq->id] ?? null;
        $hasAns = $ans && ($ans->selected_option_id || !empty(trim($ans->answer_text ?? '')));
        return [
            'id' => $eq->id,
            'number' => $idx + 1,
            'has_answer' => (bool)$hasAns,
            'is_marked' => $ans ? (bool)$ans->is_marked : false,
        ];
    });
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $exam->title }} — Lembar Ujian EduExam</title>
    
    <!-- Dark Mode Init (Anti-FOUC) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    
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
            html.dark, html.dark body {
                background-color: #0b1329 !important;
                color: #f1f5f9 !important;
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

        /* ════════════ DARK MODE REFINEMENTS FOR EXAM ════════════ */
        html.dark .bg-surface {
            background-color: #0b1329 !important;
        }
        html.dark .bg-surface\/90,
        html.dark .bg-surface\/95 {
            background-color: rgba(11, 19, 41, 0.92) !important;
        }
        html.dark .bg-surface-container-lowest {
            background-color: #111c38 !important;
        }
        html.dark .bg-surface-container-low {
            background-color: #162447 !important;
        }
        html.dark .bg-surface-container {
            background-color: #1a2b54 !important;
        }
        html.dark .bg-surface-container-high {
            background-color: #213564 !important;
        }
        html.dark .bg-surface-container-highest {
            background-color: #283e74 !important;
        }
        html.dark .text-on-surface {
            color: #f8fafc !important;
        }
        html.dark .text-on-surface-variant {
            color: #94a3b8 !important;
        }
        html.dark .text-primary {
            color: #818cf8 !important;
        }
        html.dark .border-surface-container {
            border-color: #1e293b !important;
        }
        html.dark header,
        html.dark footer {
            border-color: #1e293b !important;
        }
        html.dark textarea {
            color: #f1f5f9;
        }
        html.dark textarea::placeholder {
            color: #64748b;
        }
    </style>
</head>
<body class="bg-surface font-body-md text-on-surface antialiased min-h-screen flex flex-col items-center justify-start select-none">
    <div class="w-full min-h-screen flex flex-col relative bg-surface">
        
        <!-- Fixed Sticky Header -->
        <header class="fixed top-0 left-0 right-0 w-full z-50 bg-surface/90 backdrop-blur-xl border-b border-surface-container shadow-[0_1px_8px_rgba(0,0,0,0.04)] pt-safe">
            <div class="h-16 max-w-[480px] md:max-w-4xl lg:max-w-6xl xl:max-w-7xl mx-auto px-4 md:px-6 flex items-center justify-between gap-3 md:gap-4">
                <div class="flex items-center gap-2 md:gap-3 min-w-0">
                    <button type="button" onclick="confirmExitExam()" class="w-9 h-9 md:w-10 md:h-10 rounded-full flex items-center justify-center text-on-surface hover:bg-surface-container-low transition-colors shrink-0" title="Kembali ke Dashboard">
                        <span class="material-symbols-outlined text-[22px] md:text-[24px]">arrow_back</span>
                    </button>
                    <div class="flex flex-col min-w-0">
                        <h1 class="font-headline-sm text-sm md:text-base font-bold text-on-surface truncate leading-tight">
                            {{ $exam->title }}
                        </h1>
                        <span class="text-[11px] md:text-xs text-on-surface-variant leading-none truncate font-medium">
                            {{ $exam->subject->name ?? 'Ujian Digital' }}
                        </span>
                    </div>
                </div>

                <!-- Right Header Badges: Timer & Count -->
                <div class="flex items-center gap-2 md:gap-3 shrink-0">
                    <!-- Timer Badge -->
                    <div id="exam-timer-box" class="flex items-center gap-1.5 bg-surface-container-high px-2.5 md:px-3.5 py-1 md:py-1.5 rounded-full text-primary font-headline-sm text-xs md:text-sm shadow-sm">
                        <span class="material-symbols-outlined text-[15px] md:text-[18px] text-primary" style="font-variation-settings: 'FILL' 1;">timer</span>
                        <span id="time-display" class="font-mono tracking-wider text-on-surface font-bold">--:--</span>
                    </div>

                    <!-- Question Count Badge -->
                    <div class="bg-surface-container-high px-2.5 md:px-3.5 py-1 md:py-1.5 rounded-full text-on-surface text-xs md:text-sm font-semibold shrink-0">
                        <span class="text-primary font-bold">{{ $currentIndex }}</span><span class="text-on-surface-variant">/{{ $totalQ }}</span>
                    </div>

                    <!-- Theme Toggle Button -->
                    <button type="button" 
                            id="themeToggleBtn" 
                            onclick="toggleExamTheme()" 
                            class="w-8 h-8 md:w-9 md:h-9 rounded-full bg-surface-container-high hover:bg-surface-container-highest text-on-surface-variant hover:text-on-surface flex items-center justify-center transition-colors cursor-pointer focus:outline-none shrink-0" 
                            aria-label="Alihkan tema gelap/terang"
                            title="Alihkan mode tema">
                        <span id="themeMoonIcon" class="material-symbols-outlined text-[18px] md:text-[20px]">dark_mode</span>
                        <span id="themeSunIcon" class="material-symbols-outlined text-[18px] md:text-[20px] text-amber-400 hidden">light_mode</span>
                    </button>
                </div>
            </div>

            <!-- Micro Linear Progress Bar -->
            <div class="w-full bg-surface-container-highest h-1 overflow-hidden">
                <div id="progress-bar-fill" class="bg-primary h-full transition-all duration-300 rounded-r-full" style="width: {{ $totalQ > 0 ? round(($answeredCount / $totalQ) * 100) : 0 }}%;"></div>
            </div>
        </header>

        <!-- Main Examination Canvas -->
        <main class="flex-1 flex flex-col w-full max-w-[480px] md:max-w-3xl lg:max-w-4xl xl:max-w-5xl mx-auto pt-20 md:pt-24 pb-36 md:pb-36 px-4 md:px-6 bg-surface">
            @if(session('error'))
                <div class="mb-3 p-3 bg-error-container text-on-error-container rounded-xl flex items-start gap-2 border border-error/20 text-xs shadow-sm animate-pulse">
                    <span class="material-symbols-outlined text-error shrink-0 text-[18px]">error</span>
                    <div class="font-medium leading-relaxed">{{ session('error') }}</div>
                </div>
            @endif

            <!-- Meta & Flag Row -->
            <div class="flex items-center justify-between gap-2 mb-3 md:mb-4">
                <div class="flex items-center gap-2">
                    <span class="bg-primary text-on-primary font-label-md text-xs md:text-sm font-bold px-2.5 md:px-3 py-1 md:py-1.5 rounded-lg shadow-sm">
                        No. {{ $currentIndex }}
                    </span>
                    <span class="bg-surface-container text-on-surface-variant text-[11px] md:text-xs font-medium px-2.5 py-1 md:py-1.5 rounded-full">
                        Bobot: {{ $currentExamQuestion->score_weight ?? 2.5 }} Poin
                    </span>
                </div>

                <!-- Bookmark / Ragu-ragu Toggle Button -->
                <button type="button" id="flag-btn" onclick="toggleFlag()" class="flex items-center gap-1.5 px-3 md:px-4 py-1.5 md:py-2 rounded-lg {{ $isMarked ? 'bg-tertiary-container text-on-tertiary font-bold shadow-sm' : 'bg-surface-container-low text-tertiary font-semibold hover:bg-surface-container' }} text-xs md:text-sm transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[17px] md:text-[19px]" id="flag-icon" style="{{ $isMarked ? "font-variation-settings: 'FILL' 1;" : '' }}">
                        {{ $isMarked ? 'bookmark' : 'bookmark_border' }}
                    </span>
                    <span id="flag-text">{{ $isMarked ? 'Ragu' : 'Ragu-ragu' }}</span>
                </button>
            </div>

            <!-- Live Auto-Save Toastlet -->
            <div class="flex items-center gap-1.5 text-secondary text-xs md:text-sm font-medium mb-3 md:mb-4">
                <span class="material-symbols-outlined text-[16px] md:text-[18px] text-secondary" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                <span id="autosave-status">Jawaban tersimpan otomatis</span>
            </div>

            <!-- Question Prompt Card -->
            <div class="bg-surface-container-lowest p-4 md:p-6 rounded-2xl shadow-sm border border-surface-container flex flex-col gap-3 md:gap-4 mb-4 md:mb-5">
                <div class="question-text font-body-lg text-sm md:text-base lg:text-lg text-on-surface leading-relaxed">
                    {!! $currentExamQuestion->question->question_text !!}
                </div>

                @if($currentExamQuestion->question->image_url)
                    <div class="w-full bg-surface-container-low rounded-xl p-2.5 md:p-4 flex flex-col items-center border border-surface-container/80 shadow-xs">
                        <div class="relative group cursor-pointer max-w-full flex items-center justify-center" onclick="openImageModal('{{ $currentExamQuestion->question->image_url }}', 'Gambar Soal No. {{ $currentIndex }}')">
                            <img src="{{ $currentExamQuestion->question->image_url }}" 
                                 alt="Gambar Lampiran Soal No. {{ $currentIndex }}" 
                                 class="rounded-lg max-h-72 md:max-h-96 object-contain shadow-sm bg-white hover:opacity-95 transition-opacity"
                                 loading="lazy">
                            <div class="absolute bottom-2 right-2 px-2.5 py-1 bg-inverse-surface/80 text-inverse-on-surface rounded-lg text-[10px] md:text-xs font-semibold flex items-center gap-1 backdrop-blur-xs opacity-90 group-hover:opacity-100 transition-opacity shadow-sm">
                                <span class="material-symbols-outlined text-[14px]">zoom_in</span>
                                <span>Klik untuk Perbesar</span>
                            </div>
                        </div>
                        <div class="w-full mt-2 flex items-center justify-between text-[11px] md:text-xs text-on-surface-variant px-1">
                            <span class="flex items-center gap-1 font-medium">
                                <span class="material-symbols-outlined text-[14px]">image</span>
                                <span>Lampiran Gambar Soal {{ $currentIndex }}</span>
                            </span>
                            <a href="{{ $currentExamQuestion->question->image_url }}" target="_blank" class="text-primary hover:underline font-semibold flex items-center gap-0.5">
                                <span>Buka Ukuran Asli</span>
                                <span class="material-symbols-outlined text-[12px]">open_in_new</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Answer Options Form -->
            <form id="answer-form" class="flex flex-col gap-2.5 md:gap-3">
                <input type="hidden" name="exam_question_id" value="{{ $currentExamQuestion->id }}">

                @if(in_array($currentExamQuestion->question->type, ['multiple_choice', 'true_false']))
                    <div class="flex flex-col gap-2.5 md:gap-3" role="radiogroup">
                        @foreach($currentExamQuestion->question->options as $option)
                            @php
                                $isSelected = $currentAnswer && $currentAnswer->selected_option_id == $option->id;
                            @endphp
                            <label for="opt-{{ $option->id }}" class="option-card w-full min-h-[56px] md:min-h-[64px] px-3.5 md:px-5 py-3 md:py-4 rounded-xl md:rounded-2xl border {{ $isSelected ? 'border-primary bg-surface-container shadow-sm' : 'border-surface-container bg-surface-container-lowest hover:bg-surface-container-low' }} cursor-pointer flex items-center justify-between transition-all duration-150 active:scale-[0.99]">
                                <input type="radio" name="selected_option_id" id="opt-{{ $option->id }}" value="{{ $option->id }}" class="hidden option-input" {{ $isSelected ? 'checked' : '' }} onchange="selectOptionRadio(this, '{{ $option->label }}')">
                                
                                <div class="flex items-center gap-3 md:gap-4 min-w-0 flex-1">
                                    <div class="option-indicator w-8 h-8 md:w-9 md:h-9 rounded-full {{ $isSelected ? 'bg-primary text-on-primary shadow-sm font-bold' : 'bg-surface-container text-on-surface-variant font-semibold' }} flex items-center justify-center text-xs md:text-sm shrink-0 transition-colors">
                                        {{ $option->label }}
                                    </div>
                                    <div class="option-text text-xs md:text-sm lg:text-base {{ $isSelected ? 'text-primary font-bold' : 'text-on-surface' }} leading-relaxed flex-1">
                                        {!! $option->option_text !!}
                                        @if($option->image_url)
                                            <div class="mt-2 inline-block max-w-full">
                                                <img src="{{ $option->image_url }}" 
                                                     alt="Opsi {{ $option->label }}" 
                                                     class="max-h-36 md:max-h-48 rounded-lg object-contain bg-white border border-surface-container shadow-xs cursor-pointer hover:opacity-95 transition-opacity"
                                                     onclick="event.stopPropagation(); openImageModal('{{ $option->image_url }}', 'Gambar Opsi {{ $option->label }} - Soal No. {{ $currentIndex }}')"
                                                     title="Klik untuk memperbesar gambar opsi">
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <span class="check-icon material-symbols-outlined text-[20px] md:text-[22px] text-primary shrink-0 transition-opacity {{ $isSelected ? 'opacity-100' : 'opacity-0' }}" style="font-variation-settings: 'FILL' 1;">
                                    check_circle
                                </span>
                            </label>
                        @endforeach
                    </div>
                @else
                    <!-- Essay / Short Answer Field -->
                    <div class="bg-surface-container-lowest p-3.5 md:p-5 rounded-2xl border border-surface-container flex flex-col gap-2 md:gap-3">
                        <label for="answer-text-input" class="text-xs md:text-sm font-bold text-on-surface flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-primary text-[18px] md:text-[20px]">edit_note</span>
                            <span>Tuliskan Jawaban Uraian Anda:</span>
                        </label>
                        <textarea name="answer_text" id="answer-text-input" rows="6" class="w-full p-3 md:p-4 bg-surface-container-low text-on-surface text-sm md:text-base rounded-xl border border-surface-container focus:outline-none focus:bg-surface-container-lowest focus:border-primary transition-all resize-y placeholder:text-outline" placeholder="Ketik jawaban lengkap di sini...">{{ $currentAnswer ? $currentAnswer->answer_text : '' }}</textarea>
                    </div>
                @endif
            </form>

            <!-- Visual Encouragement Banner -->
            <div class="bg-surface-container-low rounded-xl md:rounded-2xl p-3 md:p-4 flex items-center gap-2.5 md:gap-3 mt-4 md:mt-6 border border-surface-container">
                <div class="w-8 h-8 md:w-9 md:h-9 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[18px] md:text-[20px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                </div>
                <p class="font-body-sm text-xs md:text-sm text-on-surface-variant leading-snug">
                    Anda sedang mengerjakan nomor <strong>{{ $currentIndex }} dari {{ $totalQ }} soal</strong>. Fokus dan teliti sebelum beralih ke nomor selanjutnya!
                </p>
            </div>
        </main>

        <!-- Persistent Fixed Bottom Footer -->
        <footer class="fixed bottom-0 left-0 right-0 z-40 bg-surface/95 backdrop-blur-md border-t border-surface-container shadow-[0_-4px_16px_rgba(11,28,48,0.06)] pt-2.5 pb-safe">
            <div class="max-w-[480px] md:max-w-3xl lg:max-w-4xl xl:max-w-5xl mx-auto px-4 md:px-6 flex flex-col gap-2 md:gap-2.5">
                <div class="grid grid-cols-12 gap-2 md:gap-3 items-center">
                    <!-- Previous Button -->
                    @if($currentIndex > 1)
                        <a href="{{ route('student.exam.take', ['exam' => $exam->id, 'q' => $currentIndex - 1]) }}" class="col-span-3 h-11 md:h-12 rounded-xl bg-surface-container text-on-surface hover:bg-surface-container-high active:scale-95 flex items-center justify-center gap-1 text-xs md:text-sm font-semibold transition-transform">
                            <span class="material-symbols-outlined text-[18px] md:text-[20px]">chevron_left</span>
                            <span class="hidden sm:inline">Sebelum</span>
                        </a>
                    @else
                        <button type="button" disabled class="col-span-3 h-11 md:h-12 rounded-xl bg-surface-container/60 text-on-surface-variant/40 flex items-center justify-center gap-1 text-xs md:text-sm font-semibold cursor-not-allowed">
                            <span class="material-symbols-outlined text-[18px] md:text-[20px]">chevron_left</span>
                            <span class="hidden sm:inline">Sebelum</span>
                        </button>
                    @endif

                    <!-- Center "Daftar Soal" Trigger Button -->
                    <button type="button" onclick="toggleQuestionSheet()" class="col-span-6 h-11 md:h-12 rounded-xl bg-surface-container-high hover:bg-surface-container-highest text-on-surface text-xs md:text-sm font-semibold px-2 md:px-4 flex items-center justify-center gap-1.5 md:gap-2 active:scale-95 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[18px] md:text-[20px] text-primary">apps</span>
                        <span>Daftar Soal</span>
                        <span id="answered-badge" class="bg-surface-container-lowest text-primary font-bold px-1.5 md:px-2 py-0.5 rounded text-[10px] md:text-xs">
                            {{ $answeredCount }}/{{ $totalQ }}
                        </span>
                    </button>

                    <!-- Next Button / Finish on last question -->
                    @if($currentIndex < $totalQ)
                        <a href="{{ route('student.exam.take', ['exam' => $exam->id, 'q' => $currentIndex + 1]) }}" class="col-span-3 h-11 md:h-12 rounded-xl bg-primary hover:bg-primary-container text-on-primary active:scale-95 flex items-center justify-center gap-1 text-xs md:text-sm font-semibold transition-transform shadow-sm">
                            <span class="hidden sm:inline">Berikut</span>
                            <span class="material-symbols-outlined text-[18px] md:text-[20px]">chevron_right</span>
                        </a>
                    @else
                        <button type="button" onclick="openSubmitModal()" class="col-span-3 h-11 md:h-12 rounded-xl bg-secondary hover:opacity-90 text-on-secondary active:scale-95 flex items-center justify-center gap-1 text-xs md:text-sm font-bold transition-transform shadow-md">
                            <span>Selesai</span>
                            <span class="material-symbols-outlined text-[18px] md:text-[20px]">check</span>
                        </button>
                    @endif
                </div>

                <!-- Discreet Finish Exam Link -->
                <div class="flex items-center justify-center pb-1">
                    <button type="button" onclick="openSubmitModal()" class="text-on-surface-variant hover:text-error text-xs md:text-sm transition-colors flex items-center gap-1 py-0.5 px-2">
                        <span class="material-symbols-outlined text-[14px] md:text-[16px]">flag</span>
                        <span>Selesaikan Ujian Sekarang</span>
                    </button>
                </div>
            </div>
        </footer>

        <!-- Bottom Sheet / Modal Drawer: Question Navigator (Screen 5) -->
        <div id="question-sheet" class="fixed inset-0 z-50 bg-inverse-surface/40 backdrop-blur-xs hidden flex-col justify-end md:justify-center md:items-center p-0 md:p-4 transition-opacity">
            <div class="w-full max-w-[480px] md:max-w-2xl lg:max-w-3xl mx-auto bg-surface-container-lowest rounded-t-3xl md:rounded-3xl p-4 md:p-6 flex flex-col gap-3 md:gap-4 shadow-2xl max-h-[85vh] md:max-h-[80vh]">
                <!-- Header -->
                <div class="flex items-center justify-between pb-2 border-b border-surface-container">
                    <div>
                        <h3 class="font-headline-sm text-base md:text-lg font-bold text-on-surface">Daftar Nomor Soal</h3>
                        <p class="font-body-sm text-xs md:text-sm text-on-surface-variant">Pilih nomor untuk melompat langsung ke soal</p>
                    </div>
                    <button type="button" onclick="toggleQuestionSheet()" class="w-8 h-8 md:w-9 md:h-9 rounded-full bg-surface-container flex items-center justify-center text-on-surface hover:bg-surface-container-high transition-colors">
                        <span class="material-symbols-outlined text-[18px] md:text-[20px]">close</span>
                    </button>
                </div>

                <!-- State Legend -->
                <div class="flex items-center justify-between text-xs py-2 bg-surface-container-low px-3 md:px-4 rounded-xl border border-surface-container">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded bg-secondary"></span>
                        <span class="text-on-surface text-[11px] md:text-xs font-medium">Dijawab (<span id="legend-answered">{{ $answeredCount }}</span>)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded bg-tertiary-fixed-dim"></span>
                        <span class="text-on-surface text-[11px] md:text-xs font-medium">Ragu (<span id="legend-marked">{{ $markedCount }}</span>)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded bg-surface-container-highest"></span>
                        <span class="text-on-surface text-[11px] md:text-xs font-medium">Belum (<span id="legend-unanswered">{{ $totalQ - $answeredCount }}</span>)</span>
                    </div>
                </div>

                <!-- Question Grid Palette (5 Columns on Mobile, 8 on Tablet, 10 on Desktop) -->
                <div class="grid grid-cols-5 sm:grid-cols-8 md:grid-cols-10 gap-2 md:gap-2.5 overflow-y-auto max-h-72 md:max-h-96 py-2 pr-1 no-scrollbar">
                    @foreach($examQuestions as $eq)
                        @php
                            $ans = $answers[$eq->id] ?? null;
                            $hasAns = $ans && ($ans->selected_option_id || !empty(trim($ans->answer_text ?? '')));
                            $isM = $ans && $ans->is_marked;
                            $isActive = $loop->iteration == $currentIndex;
                        @endphp
                        <a href="{{ route('student.exam.take', ['exam' => $exam->id, 'q' => $loop->iteration]) }}" 
                           id="grid-box-{{ $eq->id }}"
                           class="grid-item h-11 md:h-12 rounded-xl flex items-center justify-center font-headline-sm text-xs md:text-sm font-bold transition-all active:scale-95 shadow-sm
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

                <button type="button" onclick="toggleQuestionSheet()" class="w-full py-3 rounded-xl bg-surface-container-high font-label-lg text-xs md:text-sm font-semibold text-on-surface text-center hover:bg-surface-container-highest transition-colors">
                    Tutup Lembar Navigasi
                </button>
            </div>
        </div>

        <!-- Submit Confirmation Modal (Screen 6) -->
        <div id="submit-modal" class="fixed inset-0 z-50 bg-inverse-surface/40 backdrop-blur-sm hidden items-center justify-center p-4 transition-opacity">
            <div class="w-full max-w-[400px] md:max-w-xl bg-surface-container-lowest rounded-2xl md:rounded-3xl shadow-2xl flex flex-col p-5 md:p-7 relative overflow-hidden border border-surface-container animate-[scaleIn_0.2s_ease-out]">
                <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-primary-fixed/30 pointer-events-none blur-xl"></div>
                <div class="absolute -bottom-10 -left-10 w-28 h-28 rounded-full bg-secondary-fixed/20 pointer-events-none blur-lg"></div>
                
                <!-- Dynamic Header (Incomplete vs Complete) -->
                <div class="relative flex flex-col items-center text-center">
                    <div id="modal-icon-container" class="w-14 h-14 md:w-16 md:h-16 rounded-full bg-error-container text-error flex items-center justify-center mb-3 shadow-sm transition-colors">
                        <span id="modal-icon" class="material-symbols-outlined text-[30px] md:text-[34px]" style="font-variation-settings: 'FILL' 1;">lock</span>
                    </div>
                    <h2 id="modal-title" class="font-headline-sm text-base md:text-xl font-bold text-on-surface">
                        Ujian Belum Dapat Ditutup!
                    </h2>
                    <p id="modal-desc" class="font-body-sm text-xs md:text-sm text-on-surface-variant mt-1 leading-relaxed">
                        Anda belum menjawab seluruh soal. Semua soal wajib dijawab sebelum Anda dapat mengakhiri ujian ini.
                    </p>
                </div>

                <!-- 2x2 / 4-Col Stats Summary Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 md:gap-3 mt-4 md:mt-5">
                    <div class="bg-surface-container-low rounded-xl p-2.5 md:p-3 flex flex-col items-center justify-center text-center shadow-sm">
                        <span class="text-[10px] md:text-xs text-on-surface-variant">Total Soal</span>
                        <span class="font-headline-sm text-base md:text-lg text-on-surface font-bold mt-0.5">{{ $totalQ }}</span>
                        <div class="mt-1 px-2 py-0.5 rounded-full bg-surface-container-highest text-on-surface text-[10px] font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]">format_list_numbered</span>
                            <span>Soal Ujian</span>
                        </div>
                    </div>
                    <div class="bg-surface-container-low rounded-xl p-2.5 md:p-3 flex flex-col items-center justify-center text-center shadow-sm">
                        <span class="text-[10px] md:text-xs text-secondary">Sudah Dijawab</span>
                        <span id="modal-answered-count" class="font-headline-sm text-base md:text-lg text-secondary font-bold mt-0.5">{{ $answeredCount }}</span>
                        <div class="mt-1 px-2 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed-variant text-[10px] font-semibold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            <span>Selesai</span>
                        </div>
                    </div>
                    <div class="bg-surface-container-low rounded-xl p-2.5 md:p-3 flex flex-col items-center justify-center text-center shadow-sm">
                        <span class="text-[10px] md:text-xs text-error">Belum Dijawab</span>
                        <span id="modal-unanswered-count" class="font-headline-sm text-base md:text-lg text-error font-bold mt-0.5">{{ $totalQ - $answeredCount }}</span>
                        <div class="mt-1 px-2 py-0.5 rounded-full bg-error-container text-on-error-container text-[10px] font-semibold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]">radio_button_unchecked</span>
                            <span>Kosong</span>
                        </div>
                    </div>
                    <div class="bg-surface-container-low rounded-xl p-2.5 md:p-3 flex flex-col items-center justify-center text-center shadow-sm">
                        <span class="text-[10px] md:text-xs text-tertiary">Ditandai / Ragu</span>
                        <span id="modal-marked-count" class="font-headline-sm text-base md:text-lg text-tertiary font-bold mt-0.5">{{ $markedCount }}</span>
                        <div class="mt-1 px-2 py-0.5 rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant text-[10px] font-semibold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]" style="font-variation-settings: 'FILL' 1;">bookmark</span>
                            <span>Perlu Cek</span>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Notice Box -->
                <div id="modal-notice-box" class="mt-3 md:mt-4 p-2.5 md:p-3.5 bg-error-container/40 rounded-xl flex items-start gap-2 border border-error/20 transition-all">
                    <span id="modal-notice-icon" class="material-symbols-outlined text-error shrink-0 text-[18px] md:text-[20px] mt-0.5" style="font-variation-settings: 'FILL' 1;">lock</span>
                    <p id="modal-notice-text" class="font-body-sm text-[11px] md:text-xs text-on-surface leading-snug">
                        Siswa tidak dapat menutup ujian sebelum seluruh soal dijawab, kecuali waktu habis atau guru menutup ujian.
                    </p>
                </div>

                <!-- Actions Button Group -->
                <div class="flex flex-col gap-2 md:gap-3 mt-4 md:mt-5">
                    <button type="button" id="btn-jump-unanswered" onclick="jumpToFirstUnanswered()" class="w-full h-11 md:h-12 rounded-xl bg-primary text-on-primary hover:bg-primary-container font-label-lg text-xs md:text-sm font-bold flex items-center justify-center gap-1.5 transition-colors active:scale-[0.99] shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">play_circle</span>
                        <span id="btn-jump-text">Lanjutkan Jawab Soal yang Kosong</span>
                    </button>

                    <button type="button" onclick="closeSubmitModal()" class="w-full h-10 md:h-11 rounded-xl bg-surface-container-low hover:bg-surface-container text-on-surface font-label-lg text-xs md:text-sm font-semibold flex items-center justify-center gap-1 transition-colors active:scale-[0.99]">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                        <span>Periksa Kembali Lembar Soal</span>
                    </button>

                    <form action="{{ route('student.exam.submit', $exam->id) }}" method="POST" id="submitExamFinalForm" class="mt-1">
                        @csrf
                        <input type="hidden" name="is_timeout" id="isTimeoutInput" value="0">
                        <button type="submit" id="btnConfirmSubmit" class="w-full h-11 md:h-12 rounded-xl bg-surface-container-highest text-outline font-label-lg text-xs md:text-sm font-bold flex items-center justify-center gap-1.5 transition-all cursor-not-allowed" disabled>
                            <span id="btnConfirmIcon" class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">lock</span>
                            <span id="btnConfirmText">Kumpulkan Ujian (Terkunci)</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Notification Modal: Guru Menutup Ujian / Waktu Habis -->
        <div id="notice-modal" class="fixed inset-0 z-50 bg-inverse-surface/60 backdrop-blur-md hidden items-center justify-center p-4">
            <div class="w-full max-w-[380px] md:max-w-md bg-surface-container-lowest rounded-2xl md:rounded-3xl shadow-2xl flex flex-col p-6 md:p-8 items-center text-center border border-surface-container animate-[scaleIn_0.2s_ease-out]">
                <div id="notice-modal-icon-wrap" class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-secondary-fixed flex items-center justify-center text-secondary mb-4 shadow-sm">
                    <span id="notice-modal-icon" class="material-symbols-outlined text-[36px] md:text-[42px]" style="font-variation-settings: 'FILL' 1;">campaign</span>
                </div>
                <h3 id="notice-modal-title" class="font-headline-sm text-base md:text-xl font-bold text-on-surface">Ujian Ditutup oleh Guru</h3>
                <p id="notice-modal-desc" class="font-body-sm text-xs md:text-sm text-on-surface-variant mt-2 leading-relaxed">
                    Sesi ujian ini telah diakhiri oleh guru pengawas. Seluruh jawaban Anda telah disimpan otomatis.
                </p>
                <div class="mt-5 md:mt-6 w-full">
                    <a id="notice-modal-redirect-btn" href="{{ route('student.exam.result', $exam->id) }}" class="w-full h-11 md:h-12 rounded-xl bg-primary text-on-primary font-label-lg text-xs md:text-sm font-bold flex items-center justify-center gap-1.5 shadow-md hover:bg-primary-container transition-colors">
                        <span>Lihat Hasil Ujian</span>
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Lightbox Image Zoom Modal -->
        <div id="image-zoom-modal" class="fixed inset-0 z-50 bg-inverse-surface/85 backdrop-blur-sm hidden items-center justify-center p-3 md:p-6 transition-opacity" onclick="closeImageModal()">
            <div class="relative w-full max-w-2xl md:max-w-4xl bg-surface-container-lowest rounded-2xl md:rounded-3xl p-4 md:p-6 shadow-2xl flex flex-col items-center border border-surface-container animate-[scaleIn_0.2s_ease-out]" onclick="event.stopPropagation()">
                <div class="w-full flex items-center justify-between pb-2 mb-2 border-b border-surface-container">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-primary text-[18px]">image</span>
                        <span id="image-zoom-title" class="font-headline-sm text-xs md:text-sm font-bold text-on-surface truncate">Pratinjau Gambar</span>
                    </div>
                    <button type="button" onclick="closeImageModal()" class="w-8 h-8 rounded-full bg-surface-container hover:bg-surface-container-high flex items-center justify-center text-on-surface transition-colors">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
                <div class="overflow-auto max-h-[70vh] flex items-center justify-center w-full p-2 bg-surface-container-low rounded-xl">
                    <img id="image-zoom-img" src="" alt="Pratinjau Gambar Penuh" class="max-w-full max-h-[65vh] object-contain rounded-lg shadow-sm">
                </div>
                <div class="w-full mt-3 flex items-center justify-end gap-2 text-xs md:text-sm">
                    <a id="image-zoom-link" href="" target="_blank" class="px-3.5 py-2 rounded-xl bg-surface-container-high text-primary hover:bg-surface-container-highest font-semibold flex items-center gap-1 transition-colors">
                        <span>Buka di Tab Baru</span>
                        <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                    </a>
                    <button type="button" onclick="closeImageModal()" class="px-4 py-2 rounded-xl bg-primary text-on-primary font-bold shadow-sm hover:bg-primary-container transition-all">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Interactive Examination Script -->
    <script>
        const examId = {{ $exam->id }};
        const eqId = {{ $currentExamQuestion->id }};
        const currentIndex = {{ $currentIndex }};
        const totalQuestions = {{ $totalQ }};
        let remainingSeconds = {{ $remainingSeconds }};
        let isFlagged = {{ $isMarked ? 'true' : 'false' }};
        let saveTimeout = null;
        let isExamClosedLocally = false;

        // In-memory Questions Status Tracker
        let examQuestionsData = @json($questionsData);

        // Monospace Timer Countdown
        const timeDisplay = document.getElementById('time-display');
        const timerBox = document.getElementById('exam-timer-box');

        function formatTimer(totalSecs) {
            if (totalSecs < 0) totalSecs = 0;
            const h = Math.floor(totalSecs / 3600);
            const m = Math.floor((totalSecs % 3600) / 60);
            const s = totalSecs % 60;
            if (h > 0) {
                return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
            }
            return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        }

        // Timer interval with automatic timeout submission
        const timerInterval = setInterval(() => {
            if (isExamClosedLocally) {
                clearInterval(timerInterval);
                return;
            }

            if (remainingSeconds <= 0) {
                clearInterval(timerInterval);
                isExamClosedLocally = true;
                timeDisplay.textContent = "00:00";
                
                showNoticeModal(
                    "timer_off",
                    "bg-error-container text-error",
                    "Waktu Ujian Telah Habis!",
                    "Batas waktu yang ditentukan telah selesai. Jawaban Anda otomatis dikumpulkan dan ujian diakhiri.",
                    true
                );

                // Auto submit form with is_timeout flag
                document.getElementById('isTimeoutInput').value = '1';
                setTimeout(() => {
                    document.getElementById('submitExamFinalForm').submit();
                }, 1200);
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

        // Calculate and synchronize answered/unanswered counts across the UI
        function calculateAndSyncStatus() {
            const answered = examQuestionsData.filter(q => q.has_answer).length;
            const unanswered = totalQuestions - answered;
            const marked = examQuestionsData.filter(q => q.is_marked).length;

            // Update top progress bar
            const progressBar = document.getElementById('progress-bar-fill');
            if (progressBar && totalQuestions > 0) {
                const percent = Math.round((answered / totalQuestions) * 100);
                progressBar.style.width = percent + '%';
            }

            // Update footer badge
            const answeredBadge = document.getElementById('answered-badge');
            if (answeredBadge) {
                answeredBadge.textContent = `${answered}/${totalQuestions}`;
            }

            // Update bottom sheet legends
            const legendAnswered = document.getElementById('legend-answered');
            if (legendAnswered) legendAnswered.textContent = answered;
            const legendUnanswered = document.getElementById('legend-unanswered');
            if (legendUnanswered) legendUnanswered.textContent = unanswered;
            const legendMarked = document.getElementById('legend-marked');
            if (legendMarked) legendMarked.textContent = marked;

            // Update Submit Modal Elements
            const modalAns = document.getElementById('modal-answered-count');
            if (modalAns) modalAns.textContent = answered;
            const modalUnans = document.getElementById('modal-unanswered-count');
            if (modalUnans) modalUnans.textContent = unanswered;
            const modalMarked = document.getElementById('modal-marked-count');
            if (modalMarked) modalMarked.textContent = marked;

            const iconContainer = document.getElementById('modal-icon-container');
            const iconEl = document.getElementById('modal-icon');
            const titleEl = document.getElementById('modal-title');
            const descEl = document.getElementById('modal-desc');
            const noticeBox = document.getElementById('modal-notice-box');
            const noticeIcon = document.getElementById('modal-notice-icon');
            const noticeText = document.getElementById('modal-notice-text');
            const btnJump = document.getElementById('btn-jump-unanswered');
            const btnJumpText = document.getElementById('btn-jump-text');
            const btnConfirm = document.getElementById('btnConfirmSubmit');
            const btnConfirmIcon = document.getElementById('btnConfirmIcon');
            const btnConfirmText = document.getElementById('btnConfirmText');

            if (unanswered > 0) {
                // Not all questions answered -> Cannot close exam!
                if (iconContainer) iconContainer.className = "w-14 h-14 rounded-full bg-error-container text-error flex items-center justify-center mb-3 shadow-sm";
                if (iconEl) iconEl.textContent = "lock";
                if (titleEl) titleEl.textContent = "Ujian Belum Dapat Ditutup!";
                if (descEl) descEl.textContent = `Anda masih memiliki ${unanswered} soal yang belum dijawab. Seluruh soal wajib dijawab sebelum Anda dapat mengakhiri ujian ini.`;

                if (noticeBox) noticeBox.className = "mt-3 p-2.5 bg-error-container/40 rounded-xl flex items-start gap-2 border border-error/20";
                if (noticeIcon) {
                    noticeIcon.textContent = "lock";
                    noticeIcon.className = "material-symbols-outlined text-error shrink-0 text-[18px] mt-0.5";
                }
                if (noticeText) noticeText.textContent = `Tombol kumpulkan ujian terkunci. Masih ada ${unanswered} soal belum diisi.`;

                if (btnJump) {
                    btnJump.style.display = "flex";
                    const firstUnans = examQuestionsData.find(q => !q.has_answer);
                    if (firstUnans && btnJumpText) {
                        btnJumpText.textContent = `Lanjutkan Jawab Soal Kosong (No. ${firstUnans.number})`;
                    }
                }

                if (btnConfirm) {
                    btnConfirm.disabled = true;
                    btnConfirm.className = "w-full h-11 rounded-xl bg-surface-container-highest text-outline font-label-lg text-xs font-bold flex items-center justify-center gap-1.5 cursor-not-allowed";
                }
                if (btnConfirmIcon) btnConfirmIcon.textContent = "lock";
                if (btnConfirmText) btnConfirmText.textContent = "Kumpulkan Ujian (Terkunci)";
            } else {
                // All questions answered -> Can close exam!
                if (iconContainer) iconContainer.className = "w-14 h-14 rounded-full bg-secondary-fixed text-secondary flex items-center justify-center mb-3 shadow-sm";
                if (iconEl) iconEl.textContent = "task_alt";
                if (titleEl) titleEl.textContent = "Seluruh Soal Sudah Dijawab!";
                if (descEl) descEl.textContent = "Luar biasa! Seluruh soal telah Anda jawab dengan lengkap. Anda dapat mengakhiri dan mengumpulkan ujian sekarang.";

                if (noticeBox) noticeBox.className = "mt-3 p-2.5 bg-secondary-fixed/30 rounded-xl flex items-start gap-2 border border-secondary/20";
                if (noticeIcon) {
                    noticeIcon.textContent = "check_circle";
                    noticeIcon.className = "material-symbols-outlined text-secondary shrink-0 text-[18px] mt-0.5";
                }
                if (noticeText) noticeText.textContent = "Jawaban Anda sudah 100% lengkap. Tekan tombol di bawah untuk mengumpulkan.";

                if (btnJump) {
                    btnJump.style.display = "none";
                }

                if (btnConfirm) {
                    btnConfirm.disabled = false;
                    btnConfirm.className = "w-full h-11 rounded-xl bg-primary hover:bg-primary-container text-on-primary font-label-lg text-xs font-bold flex items-center justify-center gap-1.5 shadow-md transition-all active:scale-[0.99]";
                }
                if (btnConfirmIcon) btnConfirmIcon.textContent = "check";
                if (btnConfirmText) btnConfirmText.textContent = "Kumpulkan Jawaban Sekarang";
            }
        }

        // Jump directly to first unanswered question
        function jumpToFirstUnanswered() {
            const firstUnans = examQuestionsData.find(q => !q.has_answer);
            if (firstUnans) {
                closeSubmitModal();
                if (firstUnans.number === currentIndex) {
                    return;
                }
                window.location.href = "{{ route('student.exam.take', ['exam' => $exam->id]) }}?q=" + firstUnans.number;
            } else {
                closeSubmitModal();
            }
        }

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
                    if (data.exam_closed) {
                        handleTeacherClosedExam();
                        return;
                    }
                    if (data.timeout) {
                        handleTimeoutExam();
                        return;
                    }
                    if (data.status === 'ok') {
                        statusEl.textContent = 'Jawaban tersimpan otomatis';
                        updateNavigatorState(data.answer);
                    }
                })
                .catch(err => {
                    statusEl.textContent = 'Gagal menyimpan, periksa koneksi';
                });
            }, 350);
        }

        // Option Selection Radio Handler
        function selectOptionRadio(inputEl, label) {
            // Mark current question as answered in memory
            const currentItem = examQuestionsData.find(q => q.id === eqId);
            if (currentItem) {
                currentItem.has_answer = true;
            }
            calculateAndSyncStatus();

            // Update styles across options
            document.querySelectorAll('.option-card').forEach(card => {
                card.classList.remove('border-primary', 'bg-surface-container', 'shadow-sm');
                card.classList.add('border-surface-container', 'bg-surface-container-lowest');

                const indicator = card.querySelector('.option-indicator');
                if (indicator) indicator.className = 'option-indicator w-8 h-8 rounded-full bg-surface-container text-on-surface-variant font-semibold flex items-center justify-center text-xs shrink-0 transition-colors';

                const text = card.querySelector('.option-text');
                if (text) text.className = 'option-text text-xs md:text-sm text-on-surface leading-relaxed flex-1';

                const check = card.querySelector('.check-icon');
                if (check) {
                    check.classList.remove('opacity-100');
                    check.classList.add('opacity-0');
                }
            });

            const parentCard = inputEl.closest('.option-card');
            if (parentCard) {
                parentCard.classList.remove('border-surface-container', 'bg-surface-container-lowest');
                parentCard.classList.add('border-primary', 'bg-surface-container', 'shadow-sm');

                const activeInd = parentCard.querySelector('.option-indicator');
                if (activeInd) activeInd.className = 'option-indicator w-8 h-8 rounded-full bg-primary text-on-primary shadow-sm font-bold flex items-center justify-center text-xs shrink-0 transition-colors';

                const activeText = parentCard.querySelector('.option-text');
                if (activeText) activeText.className = 'option-text text-xs md:text-sm text-primary font-bold leading-relaxed flex-1';

                const activeCheck = parentCard.querySelector('.check-icon');
                if (activeCheck) {
                    activeCheck.classList.remove('opacity-0');
                    activeCheck.classList.add('opacity-100');
                }
            }

            // Update navigator grid box styling
            const gridBox = document.getElementById(`grid-box-${eqId}`);
            if (gridBox) {
                gridBox.className = 'grid-item h-11 rounded-xl flex items-center justify-center font-headline-sm text-xs font-bold transition-all active:scale-95 shadow-sm bg-primary text-on-primary ring-2 ring-primary ring-offset-2';
            }

            triggerAutoSave();
        }

        // Essay text input handler
        const essayInput = document.getElementById('answer-text-input');
        if (essayInput) {
            essayInput.addEventListener('input', function() {
                const currentItem = examQuestionsData.find(q => q.id === eqId);
                if (currentItem) {
                    currentItem.has_answer = this.value.trim().length > 0;
                }
                calculateAndSyncStatus();
                triggerAutoSave();
            });
        }

        // Flag / Ragu-ragu Toggle
        function toggleFlag() {
            isFlagged = !isFlagged;
            const btn = document.getElementById('flag-btn');
            const icon = document.getElementById('flag-icon');
            const text = document.getElementById('flag-text');

            const currentItem = examQuestionsData.find(q => q.id === eqId);
            if (currentItem) {
                currentItem.is_marked = isFlagged;
            }
            calculateAndSyncStatus();

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
                const isCurrent = currentIndex == gridBox.textContent.trim();
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
            calculateAndSyncStatus();
            const modal = document.getElementById('submit-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeSubmitModal() {
            const modal = document.getElementById('submit-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Prevent Premature Exit Confirmation
        function confirmExitExam() {
            const unanswered = examQuestionsData.filter(q => !q.has_answer).length;
            if (unanswered > 0) {
                if (confirm(`Peringatan: Anda belum menyelesaikan seluruh soal ujian (masih ada ${unanswered} soal kosong).\n\nUjian TIDAK BISA ditutup sebelum semua soal dijawab, kecuali waktu habis atau guru menutup ujian.\n\nApakah Anda hanya ingin kembali sementara ke dashboard? Waktu ujian Anda akan tetap berjalan.`)) {
                    window.location.href = "{{ route('student.dashboard') }}";
                }
            } else {
                if (confirm("Ujian sedang berlangsung dan seluruh soal telah Anda jawab. Apakah Anda ingin kembali ke dashboard tanpa mengumpulkan sekarang? Waktu ujian tetap berjalan.")) {
                    window.location.href = "{{ route('student.dashboard') }}";
                }
            }
        }

        // Form Submit handler
        document.getElementById('submitExamFinalForm').addEventListener('submit', function(e) {
            const isTimeout = document.getElementById('isTimeoutInput').value === '1';
            const unanswered = examQuestionsData.filter(q => !q.has_answer).length;

            if (!isTimeout && unanswered > 0) {
                e.preventDefault();
                alert(`Anda tidak dapat menutup ujian karena masih ada ${unanswered} butir soal yang belum dijawab! Harap jawab seluruh soal terlebih dahulu.`);
                return false;
            }

            const btn = document.getElementById('btnConfirmSubmit');
            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span><span>Mengumpulkan Jawaban...</span>';
        });

        // Modal for external events (Teacher Closed / Timeout)
        function showNoticeModal(icon, iconWrapClasses, title, desc, autoRedirect = false) {
            const modal = document.getElementById('notice-modal');
            const iconWrap = document.getElementById('notice-modal-icon-wrap');
            const iconEl = document.getElementById('notice-modal-icon');
            const titleEl = document.getElementById('notice-modal-title');
            const descEl = document.getElementById('notice-modal-desc');

            if (iconWrap) iconWrap.className = `w-16 h-16 rounded-full flex items-center justify-center mb-4 shadow-sm ${iconWrapClasses}`;
            if (iconEl) iconEl.textContent = icon;
            if (titleEl) titleEl.textContent = title;
            if (descEl) descEl.textContent = desc;

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            if (autoRedirect) {
                setTimeout(() => {
                    window.location.href = "{{ route('student.exam.result', $exam->id) }}";
                }, 2000);
            }
        }

        function handleTeacherClosedExam() {
            if (isExamClosedLocally) return;
            isExamClosedLocally = true;
            clearInterval(timerInterval);
            showNoticeModal(
                "campaign",
                "bg-secondary-fixed text-secondary",
                "Ujian Telah Ditutup oleh Guru",
                "Guru/Pengawas telah mengakhiri sesi ujian ini. Seluruh jawaban Anda telah disimpan otomatis dan Anda dialihkan ke halaman hasil.",
                true
            );
        }

        function handleTimeoutExam() {
            if (isExamClosedLocally) return;
            isExamClosedLocally = true;
            clearInterval(timerInterval);
            showNoticeModal(
                "timer_off",
                "bg-error-container text-error",
                "Waktu Ujian Telah Berakhir",
                "Batas waktu yang diberikan sudah habis. Ujian otomatis berakhir dan jawaban Anda dikumpulkan.",
                true
            );
        }

        // Background Polling to check if Teacher Closed the Exam or Time is Up
        const pollStatusInterval = setInterval(() => {
            if (isExamClosedLocally) {
                clearInterval(pollStatusInterval);
                return;
            }

            fetch("{{ route('student.exam.status', $exam->id) }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.is_closed) {
                    clearInterval(pollStatusInterval);
                    handleTeacherClosedExam();
                } else if (data.is_timeout || (data.remaining_seconds !== undefined && data.remaining_seconds <= 0)) {
                    clearInterval(pollStatusInterval);
                    handleTimeoutExam();
                } else if (data.remaining_seconds !== undefined && Math.abs(remainingSeconds - data.remaining_seconds) > 15) {
                    // Sync remaining seconds if slight discrepancy
                    remainingSeconds = data.remaining_seconds;
                }
            })
            .catch(err => {
                // Ignore transient network errors during poll
            });
        }, 10000);

        // Lightbox Image Zoom handlers
        function openImageModal(imgSrc, title) {
            const modal = document.getElementById('image-zoom-modal');
            const img = document.getElementById('image-zoom-img');
            const titleEl = document.getElementById('image-zoom-title');
            const linkEl = document.getElementById('image-zoom-link');
            if (img) img.src = imgSrc;
            if (titleEl) titleEl.textContent = title || 'Pratinjau Gambar';
            if (linkEl) linkEl.href = imgSrc;
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeImageModal() {
            const modal = document.getElementById('image-zoom-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // Theme mode controller for exam interface
        function updateExamThemeUI(isDark) {
            const moon = document.getElementById('themeMoonIcon');
            const sun = document.getElementById('themeSunIcon');
            const btn = document.getElementById('themeToggleBtn');
            if (isDark) {
                if (moon) moon.classList.add('hidden');
                if (sun) sun.classList.remove('hidden');
                if (btn) {
                    btn.setAttribute('title', 'Beralih ke mode terang');
                    btn.setAttribute('aria-label', 'Beralih ke mode terang');
                }
            } else {
                if (sun) sun.classList.add('hidden');
                if (moon) moon.classList.remove('hidden');
                if (btn) {
                    btn.setAttribute('title', 'Beralih ke mode gelap');
                    btn.setAttribute('aria-label', 'Beralih ke mode gelap');
                }
            }
        }

        function toggleExamTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateExamThemeUI(isDark);
        }

        // Initial setup
        calculateAndSyncStatus();
        updateExamThemeUI(document.documentElement.classList.contains('dark'));
    </script>
</body>
</html>
