<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $exam->title }} — EduExam</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb; --primary-dark: #1d4ed8; --primary-light: #eff6ff;
            --secondary: #0f172a; --secondary-light: #1e293b;
            --success: #10b981; --warning: #f59e0b; --danger: #ef4444;
            --surface: #ffffff; --background: #f8fafc;
            --border: #e2e8f0; --text-main: #0f172a; --text-muted: #64748b;
            --radius-sm: 8px; --radius-md: 16px; --radius-lg: 24px; --radius-full: 9999px;
            --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--background); color: var(--text-main); -webkit-font-smoothing: antialiased; padding-top: 80px; padding-bottom: 100px; }
        
        /* HEADER GLASSMORPHISM */
        .header { position: fixed; top: 0; left: 0; right: 0; height: 72px; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; padding: 0 5%; z-index: 100; box-shadow: var(--shadow-sm); }
        .brand { display: flex; align-items: center; gap: 12px; font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.25rem; color: var(--primary); }
        .exam-info { text-align: center; display: none; }
        @media (min-width: 768px) { .exam-info { display: block; } }
        .exam-title { font-weight: 700; font-size: 1rem; color: var(--secondary); }
        .exam-meta { font-size: 0.75rem; color: var(--text-muted); font-weight: 600; }
        
        .timer-badge { display: flex; align-items: center; gap: 8px; background: var(--primary-light); padding: 8px 16px; border-radius: var(--radius-full); font-weight: 700; font-size: 1rem; color: var(--primary-dark); font-variant-numeric: tabular-nums; border: 1px solid rgba(37, 99, 235, 0.1); }
        .timer-badge.warning { background: #fef2f2; color: var(--danger); border-color: rgba(239, 68, 68, 0.2); animation: pulse 1.5s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }

        /* MAIN CONTENT */
        .container { max-width: 800px; margin: 0 auto; padding: 0 20px; }
        
        /* PROGRESS BAR */
        .progress-wrapper { background: white; padding: 16px 24px; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); margin-bottom: 24px; border: 1px solid var(--border); display: flex; align-items: center; gap: 16px; }
        .progress-text { font-weight: 700; font-size: 0.875rem; color: var(--secondary); white-space: nowrap; }
        .progress-bar { flex: 1; height: 8px; background: var(--border); border-radius: var(--radius-full); overflow: hidden; }
        .progress-fill { height: 100%; background: var(--primary); border-radius: var(--radius-full); transition: width 0.3s ease; }
        
        /* QUESTION CARD */
        .question-card { background: var(--surface); border-radius: var(--radius-lg); padding: 32px; box-shadow: var(--shadow-md); border: 1px solid var(--border); position: relative; overflow: hidden; }
        .question-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, var(--primary), #60a5fa); }
        
        .q-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .q-number { font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 800; color: var(--secondary); display: flex; align-items: center; gap: 8px; }
        .q-badge { font-size: 0.75rem; font-weight: 700; background: var(--primary-light); color: var(--primary-dark); padding: 4px 10px; border-radius: var(--radius-full); }
        
        .q-mark { display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.875rem; color: var(--text-muted); cursor: pointer; padding: 8px 16px; border-radius: var(--radius-full); background: var(--background); transition: all .2s; }
        .q-mark:hover { background: #fef3c7; color: var(--warning); }
        .q-mark.marked { background: var(--warning); color: white; }
        .q-mark input { display: none; }

        .q-content { font-size: 1.125rem; line-height: 1.7; color: var(--text-main); margin-bottom: 32px; }
        .q-content img { max-width: 100%; border-radius: var(--radius-sm); border: 1px solid var(--border); margin: 16px 0; }
        
        /* OPTIONS */
        .options-grid { display: flex; flex-direction: column; gap: 16px; }
        .option-item { position: relative; }
        .option-input { position: absolute; opacity: 0; }
        .option-label { display: flex; padding: 20px; border: 2px solid var(--border); border-radius: var(--radius-md); cursor: pointer; transition: all .2s cubic-bezier(0.4, 0, 0.2, 1); background: var(--surface); align-items: flex-start; gap: 16px; }
        .option-label:hover { border-color: #cbd5e1; background: #f8fafc; transform: translateY(-2px); box-shadow: var(--shadow-sm); }
        
        .option-char { width: 32px; height: 32px; border-radius: var(--radius-sm); background: var(--background); display: flex; align-items: center; justify-content: center; font-weight: 800; font-family: 'Outfit', sans-serif; color: var(--text-muted); flex-shrink: 0; font-size: 1rem; transition: all .2s; }
        .option-text { flex: 1; font-size: 1rem; line-height: 1.6; font-weight: 500; color: var(--secondary); padding-top: 2px; }
        .option-text img { max-height: 150px; border-radius: 8px; margin-top: 8px; display: block; }
        
        .option-input:checked + .option-label { border-color: var(--primary); background: var(--primary-light); box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); }
        .option-input:checked + .option-label .option-char { background: var(--primary); color: white; }

        /* BOTTOM NAVIGATION */
        .nav-bottom { position: fixed; bottom: 0; left: 0; right: 0; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(12px); border-top: 1px solid var(--border); padding: 16px 5%; display: flex; justify-content: center; z-index: 100; box-shadow: 0 -4px 20px rgba(0,0,0,0.05); }
        .nav-wrapper { max-width: 800px; width: 100%; display: flex; justify-content: space-between; align-items: center; gap: 16px; }
        
        .btn { padding: 14px 24px; border-radius: var(--radius-full); font-weight: 700; font-size: 1rem; cursor: pointer; text-decoration: none; text-align: center; border: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all .2s; font-family: 'Outfit', sans-serif; }
        .btn-outline { border: 2px solid var(--border); background: var(--surface); color: var(--secondary); }
        .btn-outline:hover { background: var(--background); border-color: #cbd5e1; }
        .btn-primary { background: var(--primary); color: white; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-2px); }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }
        
        .btn-nav-grid { background: var(--secondary); color: white; flex: 1; max-width: 200px; }
        .btn-nav-grid:hover { background: var(--secondary-light); }

        /* GRID MODAL */
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 200; display: none; align-items: center; justify-content: center; opacity: 0; transition: opacity .3s; }
        .modal-overlay.show { display: flex; opacity: 1; }
        .modal-content { background: var(--surface); width: 90%; max-width: 600px; border-radius: var(--radius-lg); padding: 32px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); transform: translateY(20px); transition: transform .3s; max-height: 85vh; overflow-y: auto; }
        .modal-overlay.show .modal-content { transform: translateY(0); }
        
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid var(--border); padding-bottom: 16px; }
        .modal-title { font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.5rem; color: var(--secondary); }
        .btn-close { background: var(--background); border: none; width: 40px; height: 40px; border-radius: 50%; font-size: 1.25rem; color: var(--text-muted); cursor: pointer; transition: all .2s; }
        .btn-close:hover { background: var(--border); color: var(--secondary); }
        
        .grid-legend { display: flex; gap: 16px; margin-bottom: 24px; font-size: 0.875rem; font-weight: 600; color: var(--text-muted); }
        .legend-item { display: flex; align-items: center; gap: 8px; }
        .legend-box { width: 16px; height: 16px; border-radius: 4px; border: 1px solid var(--border); }
        .legend-box.ans { background: var(--primary); border-color: var(--primary); }
        .legend-box.mark { background: var(--warning); border-color: var(--warning); }
        
        .number-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(48px, 1fr)); gap: 12px; }
        .num-box { height: 48px; display: flex; align-items: center; justify-content: center; background: var(--surface); border: 2px solid var(--border); border-radius: var(--radius-sm); font-weight: 700; font-family: 'Outfit', sans-serif; font-size: 1.125rem; text-decoration: none; color: var(--secondary); position: relative; transition: all .2s; }
        .num-box:hover { border-color: var(--primary); color: var(--primary); }
        .num-box.active { border-color: var(--secondary); border-width: 3px; }
        .num-box.answered { background: var(--primary-light); color: var(--primary-dark); border-color: var(--primary); }
        .num-box.marked { background: #fef3c7; border-color: var(--warning); color: #b45309; }
        
        .submit-section { margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border); }
        
        /* TOAST NOTIFICATION */
        .toast { position: fixed; top: 90px; right: 5%; background: var(--secondary); color: white; padding: 12px 24px; border-radius: var(--radius-full); font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 8px; box-shadow: var(--shadow-md); transform: translateY(-20px); opacity: 0; transition: all .3s cubic-bezier(0.4, 0, 0.2, 1); z-index: 300; pointer-events: none; }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast-icon { width: 20px; height: 20px; background: var(--success); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px; }
    </style>
</head>
<body>

<header class="header">
    <div class="brand">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
        EduExam
    </div>
    <div class="exam-info">
        <div class="exam-title">{{ $exam->title }}</div>
        <div class="exam-meta">{{ $exam->subject->name ?? 'Mata Pelajaran' }} • Guru: {{ $exam->teacher->name ?? '-' }}</div>
    </div>
    <div class="timer-badge" id="timer">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <span id="time-display">--:--:--</span>
    </div>
</header>

<div class="container">
    @php
        $answeredCount = $answers->whereNotNull('selected_option_id')->count();
        $totalQ = $examQuestions->count();
        $progress = $totalQ > 0 ? round(($answeredCount / $totalQ) * 100) : 0;
    @endphp
    
    <div class="progress-wrapper">
        <div class="progress-text">Progres: {{ $progress }}%</div>
        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ $progress }}%" id="progress-bar-fill"></div>
        </div>
        <div class="progress-text" style="color: var(--text-muted);"><span id="answered-count-top">{{ $answeredCount }}</span> / {{ $totalQ }}</div>
    </div>

    @if(!$currentExamQuestion)
        <div class="question-card" style="text-align: center; padding: 60px 20px;">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="1.5" style="margin: 0 auto 16px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <h2 style="font-family: 'Outfit'; font-size: 1.5rem; margin-bottom: 8px;">Ujian Belum Memiliki Soal</h2>
            <p style="color: var(--text-muted);">Silakan hubungi guru yang bersangkutan karena soal ujian belum ditambahkan.</p>
        </div>
    @else
        <div class="question-card">
            <div class="q-header">
                <div class="q-number">
                    Soal {{ $currentIndex }}
                    <span class="q-badge">Bobot: {{ $currentExamQuestion->question->score }}</span>
                </div>
                <label class="q-mark {{ isset($answers[$currentExamQuestion->id]) && $answers[$currentExamQuestion->id]->is_marked ? 'marked' : '' }}">
                    <input type="checkbox" id="mark-btn" {{ isset($answers[$currentExamQuestion->id]) && $answers[$currentExamQuestion->id]->is_marked ? 'checked' : '' }}>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                    Tandai Ragu
                </label>
            </div>

            @if($currentExamQuestion->question->question_image)
                <div style="text-align: center;">
                    <img src="{{ Storage::url($currentExamQuestion->question->question_image) }}" alt="Gambar Soal">
                </div>
            @endif

            <div class="q-content">
                {{-- Raw HTML dari Summernote editor --}}
                {!! $currentExamQuestion->question->question_text !!}
            </div>

            <form id="answer-form">
                <input type="hidden" name="exam_question_id" value="{{ $currentExamQuestion->id }}">
                
                @if(in_array($currentExamQuestion->question->type, ['multiple_choice', 'true_false']))
                    <div class="options-grid">
                        @foreach($currentExamQuestion->question->options as $option)
                            <div class="option-item">
                                <input type="radio" name="selected_option_id" id="opt-{{ $option->id }}" value="{{ $option->id }}" class="option-input"
                                    {{ (isset($answers[$currentExamQuestion->id]) && $answers[$currentExamQuestion->id]->selected_option_id == $option->id) ? 'checked' : '' }}>
                                <label for="opt-{{ $option->id }}" class="option-label">
                                    <span class="option-char">{{ $option->label }}</span>
                                    <div class="option-text">
                                        {!! $option->option_text !!}
                                        @if($option->option_image)
                                            <img src="{{ Storage::url($option->option_image) }}" alt="Gambar Opsi">
                                        @endif
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="essay-box" style="margin-top: 16px;">
                        <label style="font-weight: 700; margin-bottom: 8px; display: block; color: var(--secondary);">Tuliskan Jawaban Anda:</label>
                        <textarea name="answer_text" id="answer-text-input" rows="6" style="width: 100%; padding: 16px; border: 2px solid var(--border); border-radius: var(--radius-md); font-family: inherit; font-size: 1rem; line-height: 1.6; resize: vertical;" placeholder="Ketik jawaban lengkap di sini...">{{ isset($answers[$currentExamQuestion->id]) ? $answers[$currentExamQuestion->id]->answer_text : '' }}</textarea>
                    </div>
                @endif
            </form>
        </div>
    @endif
</div>

<nav class="nav-bottom">
    <div class="nav-wrapper">
        @if($currentIndex > 1)
            <a href="{{ route('student.exam.take', ['exam' => $exam->id, 'q' => $currentIndex - 1]) }}" class="btn btn-outline" style="min-width: 120px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                Kembali
            </a>
        @else
            <button class="btn btn-outline" disabled style="min-width: 120px; opacity: 0.4; cursor: not-allowed;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                Kembali
            </button>
        @endif
        
        <button class="btn btn-nav-grid" onclick="toggleGrid()">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Daftar Soal (<span id="answered-count-btn">{{ $answeredCount }}</span>/{{ $totalQ }})
        </button>
        
        @if($currentIndex < $totalQ)
            <a href="{{ route('student.exam.take', ['exam' => $exam->id, 'q' => $currentIndex + 1]) }}" class="btn btn-primary" style="min-width: 120px;">
                Lanjut
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        @else
            <button type="button" class="btn btn-primary" style="min-width: 140px; background: #16a34a; border-color: #16a34a; color: white;" onclick="confirmSubmit()">
                Kumpulkan ✓
            </button>
        @endif
    </div>
</nav>

<!-- Nav Grid Modal -->
<div class="modal-overlay" id="grid-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Navigasi Soal</h2>
            <button class="btn-close" onclick="toggleGrid()">×</button>
        </div>
        
        <div class="grid-legend">
            <div class="legend-item"><div class="legend-box ans"></div> Sudah Dijawab</div>
            <div class="legend-item"><div class="legend-box mark"></div> Ragu-ragu</div>
            <div class="legend-item"><div class="legend-box"></div> Belum Dijawab</div>
        </div>
        
        <div class="number-grid">
            @foreach($examQuestions as $index => $eq)
                @php
                    $isAns = isset($answers[$eq->id]) && $answers[$eq->id]->selected_option_id;
                    $isMark = isset($answers[$eq->id]) && $answers[$eq->id]->is_marked;
                    $isAct = ($index + 1) == $currentIndex;
                @endphp
                <a href="{{ route('student.exam.take', ['exam' => $exam->id, 'q' => $index + 1]) }}" 
                   class="num-box {{ $isAns ? 'answered' : '' }} {{ $isMark ? 'marked' : '' }} {{ $isAct ? 'active' : '' }}"
                   id="grid-box-{{ $eq->id }}">
                    {{ $index + 1 }}
                </a>
            @endforeach
        </div>

        <div class="submit-section">
            <form action="{{ route('student.exam.submit', $exam->id) }}" method="POST" id="submit-form">
                @csrf
                <button type="button" class="btn btn-primary" style="width: 100%; background: var(--secondary); padding: 16px; font-size: 1.125rem;" onclick="confirmSubmit()">
                    Kumpulkan Ujian Sekarang
                </button>
            </form>
        </div>
    </div>
</div>

<div class="toast" id="toast">
    <div class="toast-icon">✓</div>
    <span>Jawaban tersimpan otomatis</span>
</div>

<script>
    const examId = {{ $exam->id }};
    const eqId = {{ $currentExamQuestion ? $currentExamQuestion->id : 0 }};
    let remainingSeconds = {{ $remainingSeconds }};
    let saveTimeout = null;
    let totalQuestions = {{ $totalQ }};
    
    // TIMER
    function updateTimer() {
        if(remainingSeconds <= 0) {
            document.getElementById('submit-form').submit();
            return;
        }
        
        const h = Math.floor(remainingSeconds / 3600);
        const m = Math.floor((remainingSeconds % 3600) / 60);
        const s = remainingSeconds % 60;
        
        document.getElementById('time-display').textContent = 
            (h > 0 ? String(h).padStart(2, '0') + ':' : '') + 
            String(m).padStart(2, '0') + ':' + 
            String(s).padStart(2, '0');
            
        if(remainingSeconds <= 300) { // 5 menit terakhir
            document.getElementById('timer').classList.add('warning');
        }
        
        remainingSeconds--;
        setTimeout(updateTimer, 1000);
    }
    updateTimer();

    // AUTO SAVE
    if (eqId > 0) {
        const radios = document.querySelectorAll('.option-input');
        const markBtn = document.getElementById('mark-btn');
        
        function saveAnswer() {
            const formData = new FormData(document.getElementById('answer-form'));
            formData.append('is_marked', markBtn.checked ? 1 : 0);
            
            fetch("{{ route('student.exam.answer', $exam->id) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            }).then(response => response.json())
              .then(data => {
                  if(data.success) {
                      showToast();
                      updateGridState(data.answer);
                  }
              });
        }

        radios.forEach(radio => {
            radio.addEventListener('change', () => {
                clearTimeout(saveTimeout);
                saveTimeout = setTimeout(saveAnswer, 300);
            });
        });

        const essayInput = document.getElementById('answer-text-input');
        if (essayInput) {
            essayInput.addEventListener('input', () => {
                clearTimeout(saveTimeout);
                saveTimeout = setTimeout(saveAnswer, 600);
            });
        }

        markBtn.addEventListener('change', function() {
            this.parentElement.classList.toggle('marked', this.checked);
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(saveAnswer, 300);
        });
    }

    function updateGridState(answer) {
        const box = document.getElementById(`grid-box-${eqId}`);
        if(box) {
            const hasAnswer = answer.selected_option_id || (answer.answer_text && answer.answer_text.trim() !== '');
            if(hasAnswer) box.classList.add('answered');
            else box.classList.remove('answered');
            
            if(answer.is_marked) box.classList.add('marked');
            else box.classList.remove('marked');
        }
        
        // Update answered count
        const answeredCount = document.querySelectorAll('.num-box.answered').length;
        document.getElementById('answered-count-top').textContent = answeredCount;
        document.getElementById('answered-count-btn').textContent = answeredCount;
        
        // Update progress bar
        if (totalQuestions > 0) {
            const percent = Math.round((answeredCount / totalQuestions) * 100);
            document.getElementById('progress-bar-fill').style.width = percent + '%';
            document.querySelector('.progress-text').textContent = 'Progres: ' + percent + '%';
        }
    }

    function showToast() {
        const toast = document.getElementById('toast');
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 2500);
    }

    // MODAL GRID
    function toggleGrid() {
        document.getElementById('grid-modal').classList.toggle('show');
    }
    
    document.getElementById('grid-modal').addEventListener('click', function(e) {
        if(e.target === this) toggleGrid();
    });

    function confirmSubmit() {
        const answered = document.querySelectorAll('.num-box.answered').length;
        
        let msg = 'Apakah Anda yakin ingin mengumpulkan ujian ini sekarang?';
        if(answered < totalQuestions) {
            msg = `PERINGATAN!\nAnda baru menjawab ${answered} dari ${totalQuestions} soal.\nMasih ada soal yang KOSONG.\n\nYakin ingin tetap mengumpulkan ujian ini?`;
        }
        
        if(confirm(msg)) {
            document.getElementById('submit-form').submit();
        }
    }
</script>
</body>
</html>
