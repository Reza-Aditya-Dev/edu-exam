@extends('layouts.student')

@section('title', 'Detail Ujian — EduExam')

@push('mobile-styles')
<style>
    .exam-header {
        background: linear-gradient(135deg, var(--primary) 0%, #6366f1 100%);
        border-radius: var(--radius-xl); padding: 24px; margin-bottom: 20px;
        color: white; position: relative; overflow: hidden;
    }
    .exam-header::after {
        content: '📝'; position: absolute; right: -10px; bottom: -20px;
        font-size: 8rem; opacity: 0.1; transform: rotate(-15deg); pointer-events: none;
    }
    .exam-header h1 { font-size: 1.25rem; font-weight: 800; margin-bottom: 8px; line-height: 1.3; }
    .exam-badge { display: inline-block; background: rgba(255,255,255,.2); padding: 4px 12px; border-radius: 100px; font-size: 0.8125rem; font-weight: 600; margin-bottom: 12px; }

    .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 24px; }
    .info-box { background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--gray-200); padding: 16px; display: flex; flex-direction: column; gap: 4px; box-shadow: var(--shadow-sm); }
    .info-icon { width: 32px; height: 32px; border-radius: 8px; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1rem; margin-bottom: 4px; }
    .info-label { font-size: 0.75rem; color: var(--gray-500); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    .info-value { font-size: 0.9375rem; font-weight: 700; color: var(--gray-900); }

    .instructions-card { background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--warning-border); padding: 20px; margin-bottom: 24px; box-shadow: var(--shadow-sm); }
    .instructions-card h3 { font-size: 1rem; font-weight: 700; color: var(--warning); margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
    .instructions-content { font-size: 0.875rem; color: var(--gray-700); line-height: 1.6; }
    .instructions-content ul { padding-left: 20px; margin-top: 8px; }
    .instructions-content li { margin-bottom: 6px; }

    .action-bar { background: var(--white); border-top: 1px solid var(--gray-200); padding: 16px; position: fixed; bottom: 0; left: 0; right: 0; display: flex; justify-content: center; box-shadow: 0 -4px 20px rgba(0,0,0,.08); z-index: 100; }
    .action-container { max-width: 520px; width: 100%; }
    .btn-start { width: 100%; padding: 16px; font-size: 1.125rem; border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; gap: 10px; }
</style>
@endpush

@section('student-content')
<div class="exam-header">
    <span class="exam-badge">{{ $exam->subject->name }}</span>
    <h1>{{ $exam->title }}</h1>
    <div style="font-size: 0.875rem; opacity: 0.9;">Oleh: {{ $exam->teacher->name }}</div>
</div>

<div class="info-grid">
    <div class="info-box">
        <div class="info-icon">📅</div>
        <div class="info-label">Tanggal</div>
        <div class="info-value">{{ $exam->exam_date->format('d M Y') }}</div>
    </div>
    <div class="info-box">
        <div class="info-icon">⏱</div>
        <div class="info-label">Durasi</div>
        <div class="info-value">{{ $exam->duration_minutes }} Menit</div>
    </div>
    <div class="info-box">
        <div class="info-icon">📋</div>
        <div class="info-label">Soal</div>
        <div class="info-value">{{ $exam->total_questions }} Butir</div>
    </div>
    <div class="info-box">
        <div class="info-icon">🎯</div>
        <div class="info-label">KKM</div>
        <div class="info-value">{{ $exam->passing_grade }}</div>
    </div>
</div>

<div class="instructions-card">
    <h3>⚠️ Peraturan & Instruksi</h3>
    <div class="instructions-content">
        @if($exam->instructions)
            {!! nl2br(e($exam->instructions)) !!}
        @else
            <ul>
                <li>Pastikan koneksi internet Anda stabil sebelum memulai ujian.</li>
                <li>Waktu akan terus berjalan meskipun Anda menutup aplikasi.</li>
                <li>Jawaban akan tersimpan secara otomatis.</li>
                <li>Ujian akan dikumpulkan secara otomatis saat waktu habis.</li>
                <li>Dilarang membuka tab/aplikasi lain selama ujian berlangsung.</li>
            </ul>
        @endif
    </div>
</div>

<div style="height: 80px;"></div> <!-- Spacer for action bar -->

<div class="action-bar">
    <div class="action-container">
        @if($exam->status === 'active')
            @if($participant && $participant->status === 'in_progress')
                <a href="{{ route('student.exam.take', $exam->id) }}" class="btn btn-primary btn-start">Lanjutkan Ujian →</a>
            @elseif(!$participant || $participant->status === 'not_started')
                <form action="{{ route('student.exam.start', $exam->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-start" onclick="return confirm('Apakah Anda yakin sudah siap untuk memulai ujian sekarang?')">Mulai Ujian Sekarang 🚀</button>
                </form>
            @else
                <a href="{{ route('student.exam.result', $exam->id) }}" class="btn btn-success btn-start">Lihat Hasil Ujian ✓</a>
            @endif
        @elseif($exam->status === 'scheduled')
            <button class="btn btn-secondary btn-start" disabled>Ujian Belum Dimulai</button>
        @else
            <button class="btn btn-secondary btn-start" disabled>Ujian Selesai</button>
        @endif
    </div>
</div>
@endsection
