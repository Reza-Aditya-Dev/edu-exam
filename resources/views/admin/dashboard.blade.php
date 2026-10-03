@extends('layouts.admin')

@section('title', 'Admin Dashboard — EduExam')
@section('page_title', 'Dashboard Administrator')

@push('admin-styles')
<style>
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 32px; }
    .stat-card { background: white; border-radius: var(--radius-lg); padding: 24px; border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 20px; }
    .stat-icon { width: 64px; height: 64px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 1.75rem; color: white; }
    .stat-info { flex: 1; }
    .stat-val { font-size: 2rem; font-weight: 800; color: var(--gray-900); line-height: 1.1; margin-bottom: 4px; }
    .stat-lbl { font-size: 0.875rem; color: var(--gray-500); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

    .content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
</style>
@endpush

@section('admin-content')

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);"><i class="bi bi-people-fill"></i></div>
        <div class="stat-info">
            <div class="stat-val">{{ $stats['total_students'] }}</div>
            <div class="stat-lbl">Total Siswa</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);"><i class="bi bi-person-workspace"></i></div>
        <div class="stat-info">
            <div class="stat-val">{{ $stats['total_teachers'] }}</div>
            <div class="stat-lbl">Total Guru</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);"><i class="bi bi-building"></i></div>
        <div class="stat-info">
            <div class="stat-val">{{ $stats['total_classrooms'] }}</div>
            <div class="stat-lbl">Total Kelas</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);"><i class="bi bi-book-half"></i></div>
        <div class="stat-info">
            <div class="stat-val">{{ $stats['total_subjects'] }}</div>
            <div class="stat-lbl">Mata Pelajaran</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);"><i class="bi bi-lightning-charge-fill"></i></div>
        <div class="stat-info">
            <div class="stat-val">{{ $stats['active_exams'] }}</div>
            <div class="stat-lbl">Ujian Berlangsung</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #64748b 0%, #475569 100%);"><i class="bi bi-file-earmark-text-fill"></i></div>
        <div class="stat-info">
            <div class="stat-val">{{ $stats['total_exams'] }}</div>
            <div class="stat-lbl">Total Ujian</div>
        </div>
    </div>
</div>

<div class="content-grid">
    <!-- Kolom Kiri -->
    <div>
        <div class="card mb-6">
            <div class="card-header flex justify-between items-center">
                <h3 class="card-title">Ujian Terbaru (Semua Guru)</h3>
                <a href="{{ route('admin.exams') }}" class="text-sm text-primary font-bold">Lihat Semua</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Ujian</th>
                            <th>Guru</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentExams as $exam)
                        <tr>
                            <td>
                                <div class="font-bold">{{ $exam->title }}</div>
                                <div class="text-xs text-muted">{{ $exam->subject->name }} • {{ $exam->classroom->name }}</div>
                            </td>
                            <td>{{ $exam->teacher->name }}</td>
                            <td><span class="badge badge-{{ $exam->status_color }}">{{ $exam->status_label }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted">Belum ada ujian yang dibuat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Kolom Kanan -->
    <div>
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <h3 class="card-title">Log Sistem</h3>
                <a href="{{ route('admin.logs') }}" class="text-sm text-primary font-bold">Lengkap</a>
            </div>
            <div class="card-body" style="padding: 0;">
                @forelse($recentLogs as $log)
                <div style="padding: 12px 16px; border-bottom: 1px solid var(--gray-100);">
                    <div style="display: flex; gap: 10px;">
                        <div style="font-size: 1.1rem; line-height: 1.2;">
                            @if(str_contains($log->action, 'create')) <i class="bi bi-plus-circle-fill text-success"></i>
                            @elseif(str_contains($log->action, 'update')) <i class="bi bi-pencil-fill text-primary"></i>
                            @elseif(str_contains($log->action, 'delete')) <i class="bi bi-trash-fill text-danger"></i>
                            @else <i class="bi bi-info-circle-fill text-secondary"></i> @endif
                        </div>
                        <div>
                            <div style="font-size: 0.8125rem; color: var(--gray-800); line-height: 1.4;">
                                {{ $log->description }}
                            </div>
                            <div style="font-size: 0.75rem; color: var(--gray-500); margin-top: 4px;">
                                Oleh: {{ $log->user->name }} • {{ $log->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state" style="padding: 20px;">Belum ada log.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
