@extends('layouts.student')

@section('title', 'Detail Ujian — EduExam')

@push('mobile-styles')
<style>
    /* Sembunyikan bottom navigation bar agar tidak menutupi tombol pengerjaan */
    .bottom-nav {
        display: none !important;
    }

    body {
        padding-bottom: 40px !important;
    }

    .exam-header {
        background: linear-gradient(135deg, #3b82f6 0%, #4f46e5 50%, #6366f1 100%);
        border-radius: var(--radius-xl);
        padding: 24px;
        margin-bottom: 20px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.3);
    }
    .exam-header::after {
        content: '\F3EE';
        font-family: 'bootstrap-icons';
        position: absolute;
        right: 10px;
        bottom: -20px;
        font-size: 7rem;
        opacity: 0.12;
        transform: rotate(-15deg);
        pointer-events: none;
    }
    .exam-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.22);
        backdrop-filter: blur(8px);
        padding: 4px 14px;
        border-radius: 100px;
        font-size: 0.8125rem;
        font-weight: 700;
        margin-bottom: 12px;
        letter-spacing: 0.3px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .exam-header h1 {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 6px;
        line-height: 1.3;
        color: #ffffff;
    }
    .exam-teacher {
        font-size: 0.875rem;
        opacity: 0.92;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }
    .info-box {
        background: var(--white);
        border-radius: var(--radius-lg);
        border: 1px solid var(--gray-200);
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 4px;
        box-shadow: var(--shadow-sm);
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .info-box:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    .info-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: var(--primary-light);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-bottom: 4px;
    }
    .info-label {
        font-size: 0.75rem;
        color: var(--gray-500);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .info-value {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--gray-900);
    }

    .instructions-card {
        background: #fffbeb;
        border-radius: var(--radius-lg);
        border: 1px solid #fde68a;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-sm);
    }
    .instructions-card h3 {
        font-size: 1rem;
        font-weight: 800;
        color: #b45309;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .instructions-content {
        font-size: 0.875rem;
        color: #78350f;
        line-height: 1.65;
    }
    .instructions-content ul {
        padding-left: 20px;
        margin-top: 8px;
    }
    .instructions-content li {
        margin-bottom: 6px;
    }

    /* ACTION CARD & BUTTONS */
    .action-section {
        background: var(--white);
        border-radius: var(--radius-xl);
        border: 1px solid var(--gray-200);
        padding: 24px;
        box-shadow: var(--shadow-md);
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 32px;
    }
    .action-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--gray-100);
    }
    .action-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 100px;
        font-size: 0.8125rem;
        font-weight: 700;
    }
    .status-badge-active {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #86efac;
    }
    .status-badge-scheduled {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }
    .status-badge-ended {
        background: #f3f4f6;
        color: #4b5563;
        border: 1px solid #e5e7eb;
    }
    .status-pulse {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22c55e;
        display: inline-block;
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(0.85); }
    }

    .btn-start {
        width: 100%;
        padding: 16px 24px;
        font-size: 1.125rem;
        font-weight: 700;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: all .2s ease;
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
    }
    .btn-start:hover {
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(79, 70, 229, 0.4);
    }
    .btn-start:active {
        transform: translateY(0);
    }
    .btn-start-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        color: #ffffff !important;
    }
    .btn-start-success {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(22, 163, 74, 0.3);
    }
    .btn-start-success:hover {
        box-shadow: 0 8px 24px rgba(22, 163, 74, 0.4);
    }
    .btn-start-disabled {
        background: var(--gray-200);
        color: var(--gray-500) !important;
        cursor: not-allowed;
        box-shadow: none !important;
        transform: none !important;
    }

    .status-notice {
        padding: 14px 16px;
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-weight: 600;
        line-height: 1.5;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .status-notice.warning {
        background: #fff7ed;
        color: #c2410c;
        border: 1px solid #ffedd5;
    }
    .status-notice.danger {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fee2e2;
    }

    .btn-back-dashboard {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 18px;
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--gray-600);
        background: var(--gray-100);
        border: 1px solid var(--gray-200);
        text-decoration: none;
        transition: all .15s ease;
    }
    .btn-back-dashboard:hover {
        background: var(--gray-200);
        color: var(--gray-800);
        text-decoration: none;
    }
</style>
@endpush

@section('student-content')
<div class="exam-header">
    <span class="exam-badge">{{ $exam->subject->name ?? 'Mata Pelajaran' }}</span>
    <h1>{{ $exam->title }}</h1>
    <div class="exam-teacher">
        <i class="bi bi-person-workspace me-1"></i>
        <span>Guru Pengampu: {{ $exam->teacher->name ?? '-' }}</span>
    </div>
</div>

<div class="info-grid">
    <div class="info-box">
        <div class="info-icon"><i class="bi bi-calendar3"></i></div>
        <div class="info-label">Tanggal Pelaksanaan</div>
        <div class="info-value">{{ $exam->exam_date ? $exam->exam_date->format('d M Y') : '-' }}</div>
    </div>
    <div class="info-box">
        <div class="info-icon"><i class="bi bi-stopwatch"></i></div>
        <div class="info-label">Durasi Ujian</div>
        <div class="info-value">{{ $exam->duration_minutes }} Menit</div>
    </div>
    <div class="info-box">
        <div class="info-icon"><i class="bi bi-list-check"></i></div>
        <div class="info-label">Jumlah Soal</div>
        <div class="info-value">{{ $exam->total_questions ?? $exam->questions()->count() }} Butir</div>
    </div>
    <div class="info-box">
        <div class="info-icon"><i class="bi bi-award-fill"></i></div>
        <div class="info-label">Standar KKM</div>
        <div class="info-value">{{ $exam->passing_grade }}</div>
    </div>
</div>

<div class="instructions-card">
    <h3><i class="bi bi-exclamation-triangle-fill me-2"></i> Peraturan & Petunjuk Pengerjaan</h3>
    <div class="instructions-content">
        @if($exam->instructions)
            {!! nl2br(e($exam->instructions)) !!}
        @else
            <ul>
                <li>Pastikan koneksi internet Anda stabil sebelum mengklik tombol mulai.</li>
                <li>Waktu ujian akan otomatis berjalan sejak Anda menekan tombol <strong>Mulai Ujian</strong>.</li>
                <li>Setiap jawaban yang Anda pilih akan tersimpan otomatis oleh sistem CBT.</li>
                <li>Jika waktu habis, semua jawaban akan terkumpul secara otomatis.</li>
                <li>Dilarang berpindah tab browser atau keluar dari aplikasi selama ujian berlangsung.</li>
            </ul>
        @endif
    </div>
</div>

{{-- SECTION AKSI MULAI / LANJUTKAN UJIAN --}}
<div class="action-section">
    <div class="action-header">
        <span style="font-weight: 700; color: var(--gray-700); font-size: 0.9375rem;">Status Ujian:</span>
        @if($exam->status === 'active')
            <span class="action-status-badge status-badge-active">
                <span class="status-pulse"></span> Sedang Dibuka (Aktif)
            </span>
        @elseif($exam->status === 'scheduled')
            <span class="action-status-badge status-badge-scheduled">
                <i class="bi bi-hourglass-split me-1"></i> Belum Dibuka (Terjadwal)
            </span>
        @else
            <span class="action-status-badge status-badge-ended">
                <i class="bi bi-lock-fill me-1"></i> Telah Berakhir
            </span>
        @endif
    </div>

    @if($exam->status === 'active')
        @if($participant && $participant->status === 'in_progress')
            <a href="{{ route('student.exam.take', $exam->id) }}" class="btn-start btn-start-primary">
                <i class="bi bi-lightning-charge-fill me-1"></i> Lanjutkan Pengerjaan Ujian Sekarang →
            </a>
        @elseif(!$participant || $participant->status === 'not_started')
            <form action="{{ route('student.exam.start', $exam->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-start btn-start-primary" onclick="return confirm('Apakah Anda yakin sudah siap untuk memulai ujian ini sekarang?\n\nWaktu pengerjaan akan langsung dihitung mundur.')">
                    <i class="bi bi-rocket-takeoff-fill me-2"></i> Mulai Ujian Sekarang
                </button>
            </form>
        @else
            <a href="{{ route('student.exam.result', $exam->id) }}" class="btn-start btn-start-success">
                <i class="bi bi-check-circle-fill me-2"></i> Anda Telah Mengumpulkan Ujian (Lihat Hasil)
            </a>
        @endif
    @elseif($exam->status === 'scheduled')
        <div class="status-notice warning">
            <span><i class="bi bi-info-circle-fill me-1"></i> Ujian belum dimulai oleh guru. Tombol mulai akan aktif ketika guru membuka ujian.</span>
        </div>
        <button class="btn-start btn-start-disabled" disabled>
            Ujian Belum Dibuka
        </button>
    @else
        <div class="status-notice danger">
            <span><i class="bi bi-slash-circle me-1"></i> Ujian ini telah berakhir atau ditutup oleh guru pengampu.</span>
        </div>
        <button class="btn-start btn-start-disabled" disabled>
            Ujian Sudah Selesai
        </button>
    @endif

    <a href="{{ route('student.dashboard') }}" class="btn-back-dashboard">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Dashboard
    </a>
</div>
@endsection
