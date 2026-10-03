@extends('layouts.student')

@section('title', 'Riwayat Ujian — EduExam')

@push('mobile-styles')
<style>
    .history-header { margin: 20px 0; }
    .history-header h1 { font-size: 1.25rem; font-weight: 800; color: var(--gray-900); }
    
    .filter-tabs { display: flex; gap: 8px; margin-bottom: 20px; overflow-x: auto; padding-bottom: 4px; }
    .filter-tab { padding: 6px 14px; border-radius: 100px; font-size: 0.8125rem; font-weight: 600; text-decoration: none; color: var(--gray-600); background: var(--gray-200); white-space: nowrap; transition: all .2s; }
    .filter-tab.active { background: var(--primary); color: white; }
    
    .history-card { background: white; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); padding: 16px; margin-bottom: 12px; box-shadow: var(--shadow-sm); display: flex; align-items: stretch; gap: 16px; }
    .hc-score { width: 64px; border-radius: var(--radius-md); display: flex; flex-direction: column; align-items: center; justify-content: center; }
    .hc-score.pass { background: var(--success-light); color: var(--success); }
    .hc-score.fail { background: var(--danger-light); color: var(--danger); }
    .hc-score .val { font-size: 1.5rem; font-weight: 800; line-height: 1; }
    .hc-score .lbl { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; margin-top: 4px; }
    
    .hc-info { flex: 1; display: flex; flex-direction: column; justify-content: center; }
    .hc-title { font-size: 0.9375rem; font-weight: 700; color: var(--gray-900); margin-bottom: 2px; }
    .hc-subject { font-size: 0.8125rem; color: var(--primary); font-weight: 600; margin-bottom: 6px; }
    .hc-meta { font-size: 0.75rem; color: var(--gray-500); display: flex; align-items: center; gap: 12px; }
    
    .hc-action { display: flex; align-items: center; padding-left: 10px; border-left: 1px solid var(--gray-100); }
    .hc-btn { width: 36px; height: 36px; border-radius: 50%; background: var(--gray-50); display: flex; align-items: center; justify-content: center; color: var(--gray-600); text-decoration: none; transition: all .2s; }
    .hc-btn:hover { background: var(--primary-light); color: var(--primary); }
</style>
@endpush

@section('student-content')
<div class="history-header">
    <h1><i class="bi bi-clock-history me-2 text-primary"></i> Riwayat Ujian</h1>
</div>

<div class="filter-tabs">
    <a href="{{ route('student.history') }}" class="filter-tab {{ !request('filter') ? 'active' : '' }}">
        <i class="bi bi-collection me-1"></i> Semua
    </a>
    <a href="{{ route('student.history', ['filter' => 'pass']) }}" class="filter-tab {{ request('filter') === 'pass' ? 'active' : '' }}">
        <i class="bi bi-check-circle-fill me-1"></i> Lulus
    </a>
    <a href="{{ route('student.history', ['filter' => 'fail']) }}" class="filter-tab {{ request('filter') === 'fail' ? 'active' : '' }}">
        <i class="bi bi-x-circle-fill me-1"></i> Tidak Lulus
    </a>
</div>

@if($results->isEmpty())
    <div class="card">
        <div class="card-body empty-state">
            <div class="empty-icon"><i class="bi bi-folder2-open text-muted"></i></div>
            <h3>Belum Ada Riwayat</h3>
            <p>Anda belum menyelesaikan ujian apapun.</p>
        </div>
    </div>
@else
    @foreach($results as $result)
        <div class="history-card">
            <div class="hc-score {{ $result->pass_status }}">
                <span class="val">{{ round($result->total_score) }}</span>
                <span class="lbl">{{ $result->pass_label }}</span>
            </div>
            
            <div class="hc-info">
                <div class="hc-title">{{ $result->exam->title }}</div>
                <div class="hc-subject">{{ $result->exam->subject->name ?? '-' }}</div>
                <div class="hc-meta">
                    <span><i class="bi bi-calendar3 me-1"></i> {{ $result->created_at->format('d/m/Y') }}</span>
                    <span><i class="bi bi-clock me-1"></i> {{ $result->time_spent_minutes }} mnt</span>
                </div>
            </div>
            
            <div class="hc-action">
                <a href="{{ route('student.exam.result', $result->exam_id) }}" class="hc-btn" title="Lihat Detail">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>
    @endforeach
    
    <div style="margin-top: 20px;">
        {{ $results->links('pagination::bootstrap-4') }}
    </div>
@endif

<div style="height: 16px;"></div>
@endsection
