@extends('layouts.student')

@section('title', 'Hasil Ujian — EduExam')

@push('mobile-styles')
<style>
    .result-header { text-align: center; padding: 40px 20px 30px; background: white; border-radius: var(--radius-xl); box-shadow: var(--shadow-sm); margin-bottom: 24px; position: relative; overflow: hidden; }
    .result-header::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 8px; }
    .result-header.pass::before { background: var(--success); }
    .result-header.fail::before { background: var(--danger); }
    
    .status-icon { width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 16px; }
    .status-icon.pass { background: var(--success-light); color: var(--success); }
    .status-icon.fail { background: var(--danger-light); color: var(--danger); }
    
    .score-display { font-size: 4rem; font-weight: 800; line-height: 1; margin-bottom: 8px; }
    .score-display.pass { color: var(--success); }
    .score-display.fail { color: var(--danger); }
    
    .result-title { font-size: 1.25rem; font-weight: 700; color: var(--gray-900); margin-bottom: 4px; }
    .result-subtitle { font-size: 0.875rem; color: var(--gray-500); }
    
    .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 24px; }
    .stat-box { background: white; border-radius: var(--radius-lg); padding: 16px; text-align: center; border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm); }
    .stat-val { font-size: 1.5rem; font-weight: 800; color: var(--gray-900); margin-bottom: 4px; }
    .stat-lbl { font-size: 0.75rem; color: var(--gray-500); font-weight: 600; text-transform: uppercase; }
    
    .stat-box.correct .stat-val { color: var(--success); }
    .stat-box.wrong .stat-val { color: var(--danger); }
    
    .details-card { background: white; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm); overflow: hidden; margin-bottom: 24px; }
    .details-card .card-header { padding: 16px; border-bottom: 1px solid var(--gray-100); font-weight: 700; background: var(--gray-50); }
    .detail-row { display: flex; justify-content: space-between; padding: 12px 16px; border-bottom: 1px solid var(--gray-100); font-size: 0.875rem; }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { color: var(--gray-500); }
    .detail-value { font-weight: 600; color: var(--gray-900); }
</style>
@endpush

@section('student-content')
<div class="result-header {{ $result->pass_status }}">
    <div class="status-icon {{ $result->pass_status }}">
        {{ $result->pass_status === 'pass' ? '🎉' : '😔' }}
    </div>
    
    <div class="score-display {{ $result->pass_status }}">
        {{ round($result->total_score) }}
    </div>
    
    <div class="result-title">{{ $result->pass_label }}</div>
    <div class="result-subtitle">Batas Kelulusan: {{ $exam->passing_grade }}</div>
</div>

<div class="stats-grid">
    <div class="stat-box correct">
        <div class="stat-val">{{ $result->correct_answers }}</div>
        <div class="stat-lbl">Benar</div>
    </div>
    <div class="stat-box wrong">
        <div class="stat-val">{{ $result->wrong_answers }}</div>
        <div class="stat-lbl">Salah</div>
    </div>
    <div class="stat-box">
        <div class="stat-val">{{ $result->unanswered }}</div>
        <div class="stat-lbl">Kosong</div>
    </div>
    <div class="stat-box">
        <div class="stat-val">{{ $result->time_spent_minutes }}'</div>
        <div class="stat-lbl">Waktu (Mnt)</div>
    </div>
</div>

<div class="details-card">
    <div class="card-header">Rincian Ujian</div>
    <div class="detail-row">
        <span class="detail-label">Mata Pelajaran</span>
        <span class="detail-value">{{ $exam->subject->name }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Judul Ujian</span>
        <span class="detail-value">{{ $exam->title }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Tipe Ujian</span>
        <span class="detail-value">{{ $exam->exam_type }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Tanggal Pengumpulan</span>
        <span class="detail-value">{{ $result->created_at->format('d M Y, H:i') }}</span>
    </div>
</div>

<a href="{{ route('student.dashboard') }}" class="btn btn-primary btn-block btn-lg" style="margin-bottom: 24px;">Kembali ke Beranda</a>
@endsection
