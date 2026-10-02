@extends('layouts.teacher')

@section('title', 'Dashboard Guru — EduExam')
@section('page_title', 'Dashboard')

@push('teacher-styles')
<style>
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 32px; }
    .stat-card { background: white; border-radius: var(--radius-lg); padding: 24px; border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 16px; }
    .stat-icon { width: 56px; height: 56px; border-radius: var(--radius-md); background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .stat-icon.green { background: var(--success-light); color: var(--success); }
    .stat-icon.orange { background: var(--warning-light); color: var(--warning); }
    .stat-info { flex: 1; }
    .stat-val { font-size: 1.75rem; font-weight: 800; color: var(--gray-900); line-height: 1.2; }
    .stat-lbl { font-size: 0.875rem; color: var(--gray-500); font-weight: 500; }

    .content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
    
    .card-header-actions { display: flex; gap: 12px; }
    
    @media (max-width: 1024px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .content-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('teacher-content')

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-info">
            <div class="stat-val">{{ $totalStudents }}</div>
            <div class="stat-lbl">Total Siswa</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">📝</div>
        <div class="stat-info">
            <div class="stat-val">{{ $totalExams }}</div>
            <div class="stat-lbl">Ujian Dibuat</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">📚</div>
        <div class="stat-info">
            <div class="stat-val">{{ $totalQuestions }}</div>
            <div class="stat-lbl">Bank Soal</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📈</div>
        <div class="stat-info">
            <div class="stat-val">{{ round($avgScore ?? 0, 1) }}</div>
            <div class="stat-lbl">Rata-rata Nilai</div>
        </div>
    </div>
</div>

<div class="content-grid">
    
    <!-- Bagian Kiri: Ujian Aktif & Terjadwal -->
    <div class="main-column">
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">Ujian Aktif & Terjadwal</h3>
                <a href="{{ route('teacher.exams.create') }}" class="btn btn-primary btn-sm">+ Buat Ujian</a>
            </div>
            
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Judul Ujian</th>
                            <th>Kelas</th>
                            <th>Jadwal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeExams->concat($scheduledExams) as $exam)
                        <tr>
                            <td>
                                <div class="font-bold">{{ $exam->title }}</div>
                                <div class="text-sm text-muted">{{ $exam->subject->name }}</div>
                            </td>
                            <td>{{ $exam->classroom->name }}</td>
                            <td>
                                <div>{{ $exam->exam_date->format('d M Y') }}</div>
                                <div class="text-xs text-muted">{{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}</div>
                            </td>
                            <td>
                                <span class="badge badge-{{ $exam->status_color }}">{{ $exam->status_label }}</span>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('teacher.exams.edit', $exam) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    @if($exam->status === 'scheduled')
                                    <form action="{{ route('teacher.exams.activate', $exam) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Mulai</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding: 30px;">
                                Belum ada ujian yang aktif atau terjadwal.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Bagian Kanan: Aktivitas Terbaru -->
    <div class="side-column">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Hasil Ujian Terbaru</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                @forelse($recentResults as $result)
                <div style="padding: 16px; border-bottom: 1px solid var(--gray-100); display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div class="font-bold" style="font-size: 0.875rem;">{{ $result->student->name }}</div>
                        <div class="text-xs text-muted">{{ $result->exam->title }}</div>
                    </div>
                    <div style="font-size: 1.125rem; font-weight: 800; color: {{ $result->pass_status === 'pass' ? 'var(--success)' : 'var(--danger)' }}">
                        {{ round($result->total_score) }}
                    </div>
                </div>
                @empty
                <div class="empty-state" style="padding: 30px;">
                    Belum ada hasil ujian.
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
</div>
@endsection
