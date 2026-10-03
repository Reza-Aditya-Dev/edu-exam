@extends('layouts.student')

@section('title', 'Dashboard Siswa — EduExam')

@push('mobile-styles')
<style>
    .student-header {
        background: linear-gradient(135deg, var(--primary) 0%, #6366f1 100%);
        border-radius: var(--radius-xl); padding: 20px; margin-bottom: 20px;
        color: white; display: flex; align-items: center; gap: 14px;
    }
    .student-avatar {
        width: 52px; height: 52px; border-radius: 50%;
        border: 3px solid rgba(255,255,255,.4); object-fit: cover; flex-shrink: 0;
    }
    .student-info h2 { font-size: 1.125rem; font-weight: 700; margin-bottom: 2px; }
    .student-info .class-badge {
        display: inline-flex; align-items: center; gap: 4px;
        background: rgba(255,255,255,.2); border-radius: 100px;
        padding: 3px 10px; font-size: 0.75rem; font-weight: 600;
    }

    /* ACTIVE EXAM BANNER */
    .active-exam-banner {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        border-radius: var(--radius-lg); padding: 16px; margin-bottom: 20px;
        color: white; display: flex; align-items: center; justify-content: space-between;
        border: 2px solid #22c55e; animation: pulse-border 2s ease-in-out infinite;
    }
    @keyframes pulse-border { 0%, 100% { border-color: #22c55e; } 50% { border-color: #86efac; } }
    .active-exam-banner .exam-live { display: flex; align-items: center; gap: 8px; }
    .live-dot { width: 8px; height: 8px; background: #86efac; border-radius: 50%; animation: blink 1.2s ease-in-out infinite; }
    @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: .3; } }
    .active-exam-banner .exam-info h3 { font-size: 0.9375rem; font-weight: 700; }
    .active-exam-banner .exam-info p  { font-size: 0.8125rem; opacity: .85; }
    .active-exam-banner .btn-masuk {
        background: rgba(255,255,255,.2); color: white; border: 1px solid rgba(255,255,255,.4);
        padding: 8px 16px; border-radius: var(--radius-md); font-weight: 700; font-size: 0.875rem;
        text-decoration: none; white-space: nowrap; transition: background .15s;
    }
    .active-exam-banner .btn-masuk:hover { background: rgba(255,255,255,.3); text-decoration: none; }

    /* EXAM CARD */
    .exam-card {
        background: var(--white); border-radius: var(--radius-lg);
        border: 1px solid var(--gray-200); padding: 16px; margin-bottom: 12px;
        box-shadow: var(--shadow-sm); transition: transform .15s, box-shadow .15s;
    }
    .exam-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
    .exam-card .exam-top { display: flex; align-items: flex-start; gap: 12px; }
    .exam-icon {
        width: 44px; height: 44px; border-radius: var(--radius-md);
        background: var(--primary-light); color: var(--primary);
        display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;
    }
    .exam-card .exam-meta { flex: 1; }
    .exam-card .exam-title { font-size: 0.9375rem; font-weight: 700; color: var(--gray-900); margin-bottom: 2px; }
    .exam-card .exam-type  { font-size: 0.8125rem; color: var(--primary); font-weight: 500; margin-bottom: 8px; }
    .exam-card .exam-details { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px; font-size: 0.8125rem; color: var(--gray-500); }
    .exam-card .exam-details span { display: flex; align-items: center; gap: 4px; }
    .exam-card .exam-footer { margin-top: 14px; padding-top: 12px; border-top: 1px solid var(--gray-100); display: flex; align-items: center; justify-content: space-between; }
    .exam-date { font-size: 0.8125rem; font-weight: 600; color: var(--gray-600); }

    /* RESULT CARD */
    .result-card {
        background: var(--white); border-radius: var(--radius-lg);
        border: 1px solid var(--gray-200); padding: 14px; margin-bottom: 10px;
        box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 14px;
    }
    .result-score {
        min-width: 52px; height: 52px; border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem; font-weight: 800;
    }
    .result-score.pass  { background: var(--success-light); color: var(--success); }
    .result-score.fail  { background: var(--danger-light);  color: var(--danger); }
    .result-info { flex: 1; }
    .result-info .result-title { font-weight: 700; color: var(--gray-800); font-size: 0.9375rem; }
    .result-info .result-sub   { font-size: 0.8125rem; color: var(--gray-500); margin-top: 2px; }
    .result-status { font-size: 0.75rem; font-weight: 700; padding: 3px 10px; border-radius: 100px; }
    .result-status.pass { background: var(--success-light); color: var(--success); }
    .result-status.fail { background: var(--danger-light);  color: var(--danger); }
</style>
@endpush

@section('student-content')
<!-- Student Greeting Card -->
<div class="student-header">
    <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}" class="student-avatar">
    <div class="student-info">
        <h2>Halo, {{ explode(' ', $student->name)[0] }}</h2>
        <span class="class-badge"><i class="bi bi-mortarboard-fill me-1"></i> {{ $classroom ? $classroom->name : 'Belum ada kelas' }}</span>
    </div>
</div>

{{-- UJIAN AKTIF (jika ada) --}}
@if($activeExams->isNotEmpty())
    @foreach($activeExams as $activeExam)
    <div class="active-exam-banner">
        <div class="exam-live">
            <span class="live-dot"></span>
            <div class="exam-info">
                <h3>{{ $activeExam->title }}</h3>
                <p><i class="bi bi-clock-history me-1"></i> Sedang berlangsung — {{ $activeExam->duration_minutes }} menit</p>
            </div>
        </div>
        @if($activeExam->my_participant && $activeExam->my_participant->status === 'in_progress')
            <a href="{{ route('student.exam.take', ['exam' => $activeExam->id]) }}" class="btn-masuk">
                <i class="bi bi-lightning-charge-fill me-1"></i> Lanjutkan →
            </a>
        @elseif(!$activeExam->my_participant || $activeExam->my_participant->status === 'not_started')
            <a href="{{ route('student.exam.show', $activeExam) }}" class="btn-masuk">
                <i class="bi bi-play-circle-fill me-1"></i> Mulai →
            </a>
        @else
            <span style="font-size:.8125rem; opacity:.9;"><i class="bi bi-check-circle-fill text-white me-1"></i> Selesai</span>
        @endif
    </div>
    @endforeach
@endif

{{-- UJIAN MENDATANG --}}
<div class="section-title">
    <span><i class="bi bi-calendar-event me-2 text-primary"></i> Ujian Mendatang</span>
</div>

@if($upcomingExams->isEmpty())
    <div class="card" style="margin-bottom:12px;">
        <div class="card-body empty-state" style="padding: 24px;">
            <div class="empty-icon"><i class="bi bi-inbox text-muted"></i></div>
            <h3>Belum ada ujian</h3>
            <p>Ujian mendatang akan muncul di sini.</p>
        </div>
    </div>
@else
    @foreach($upcomingExams as $exam)
    <div class="exam-card">
        <div class="exam-top">
            <div class="exam-icon"><i class="bi bi-journal-text fs-4"></i></div>
            <div class="exam-meta">
                <div class="exam-title">{{ $exam->title }}</div>
                <div class="exam-type">{{ $exam->exam_type }} • {{ $exam->subject->name }}</div>
                <div class="exam-details">
                    <span><i class="bi bi-calendar3 me-1"></i> {{ $exam->exam_date->format('d M Y') }}</span>
                    <span><i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}</span>
                    <span><i class="bi bi-list-check me-1"></i> {{ $exam->total_questions }} Soal</span>
                    <span><i class="bi bi-stopwatch me-1"></i> {{ $exam->duration_minutes }} Menit</span>
                </div>
            </div>
        </div>
        <div class="exam-footer">
            <span class="exam-date"><i class="bi bi-award-fill text-warning me-1"></i> KKM: {{ $exam->passing_grade }}</span>
            <a href="{{ route('student.exam.show', $exam) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-eye me-1"></i> Detail Ujian
            </a>
        </div>
    </div>
    @endforeach
@endif

{{-- HASIL TERBARU --}}
@if($recentResults->isNotEmpty())
<div class="section-title">
    <span><i class="bi bi-trophy-fill text-warning me-2"></i> Hasil Terbaru</span>
    <a href="{{ route('student.history') }}">Lihat Semua</a>
</div>

@foreach($recentResults as $result)
<div class="result-card">
    <div class="result-score {{ $result->pass_status }}">{{ round($result->total_score) }}</div>
    <div class="result-info">
        <div class="result-title">{{ $result->exam->subject->name ?? '-' }}</div>
        <div class="result-sub">{{ $result->exam->title }} • {{ $result->exam->exam_date?->format('d M Y') ?? '-' }}</div>
    </div>
    <span class="result-status {{ $result->pass_status }}">
        <i class="bi {{ $result->pass_status === 'pass' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-1"></i>
        {{ $result->pass_label }}
    </span>
</div>
@endforeach
@endif

<div style="height: 16px;"></div>
@endsection
