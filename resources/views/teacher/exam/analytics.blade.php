@extends('layouts.teacher')

@section('title', 'Analisis Ujian: ' . $exam->title)
@section('page_title', 'Analisis & Evaluasi Ujian')

@push('teacher-styles')
<style>
    .distribution-chart { display: flex; align-items: flex-end; gap: 8px; height: 200px; padding: 20px 0 0; border-bottom: 2px solid var(--gray-200); margin-bottom: 24px; }
    .bar-group { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; height: 100%; position: relative; }
    .bar { width: 80%; background: var(--primary); border-radius: 4px 4px 0 0; min-height: 2px; transition: height 1s ease-out; position: relative; }
    .bar:hover { background: var(--primary-dark); }
    .bar-val { position: absolute; top: -24px; left: 50%; transform: translateX(-50%); font-size: 0.75rem; font-weight: 700; color: var(--gray-600); }
    .bar-lbl { margin-top: 8px; font-size: 0.75rem; font-weight: 600; color: var(--gray-500); }
    
    .q-analysis-row { display: flex; align-items: center; gap: 16px; padding: 12px 16px; border-bottom: 1px solid var(--gray-100); }
    .q-num { width: 32px; height: 32px; border-radius: 50%; background: var(--gray-100); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.875rem; color: var(--gray-600); flex-shrink: 0; }
    .q-text { flex: 1; font-size: 0.875rem; line-height: 1.4; color: var(--gray-800); }
    .q-meter-wrap { width: 150px; display: flex; flex-direction: column; gap: 4px; }
    .q-meter-bg { height: 8px; background: var(--gray-200); border-radius: 4px; overflow: hidden; }
    .q-meter-fill { height: 100%; background: var(--success); border-radius: 4px; }
    .q-meter-lbl { font-size: 0.75rem; font-weight: 600; color: var(--gray-500); text-align: right; }
    
    /* Low passing rate gets red */
    .q-meter-fill.low { background: var(--danger); }
    .q-meter-fill.medium { background: var(--warning); }
</style>
@endpush

@section('teacher-content')

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="font-bold text-xl">{{ $exam->title }}</h2>
        <div class="text-sm text-muted mt-1">Analisis Butir Soal & Sebaran Nilai</div>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('teacher.exams.results', $exam) }}" class="btn btn-secondary">Daftar Nilai</a>
    </div>
</div>

<div class="grid" style="grid-template-columns: 1fr 2fr; gap: 24px; margin-bottom: 24px;">
    
    <!-- STATS -->
    <div class="card">
        <div class="card-header"><h3 class="card-title">Ringkasan Eksekutif</h3></div>
        <div class="card-body">
            <div style="font-size: 3rem; font-weight: 800; color: var(--gray-900); text-align: center; margin-bottom: 8px;">
                {{ $stats['avg'] }}
            </div>
            <div class="text-center text-muted font-bold mb-6">NILAI RATA-RATA KELAS</div>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div class="flex justify-between p-3" style="background: var(--gray-50); border-radius: var(--radius-md);">
                    <span class="text-muted font-semibold">Total Peserta Ujian</span>
                    <span class="font-bold">{{ $stats['total'] }} Siswa</span>
                </div>
                <div class="flex justify-between p-3" style="background: var(--gray-50); border-radius: var(--radius-md);">
                    <span class="text-muted font-semibold">Tingkat Kelulusan</span>
                    <span class="font-bold text-success">{{ $stats['pass_rate'] }}%</span>
                </div>
                <div class="flex justify-between p-3" style="background: var(--gray-50); border-radius: var(--radius-md);">
                    <span class="text-muted font-semibold">Nilai Tertinggi</span>
                    <span class="font-bold text-primary">{{ $stats['max'] }}</span>
                </div>
                <div class="flex justify-between p-3" style="background: var(--gray-50); border-radius: var(--radius-md);">
                    <span class="text-muted font-semibold">Nilai Terendah</span>
                    <span class="font-bold text-danger">{{ $stats['min'] }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- DISTRIBUTION CHART -->
    <div class="card">
        <div class="card-header"><h3 class="card-title">Sebaran Nilai (Distribusi Frekuensi)</h3></div>
        <div class="card-body pb-2">
            @php $maxCount = max(1, $distribution->max()); @endphp
            
            <div class="distribution-chart">
                @foreach($distribution as $label => $count)
                    @php $height = ($count / $maxCount) * 100; @endphp
                    <div class="bar-group">
                        <div class="bar" style="height: {{ $height }}%; background: {{ $loop->index < 7 ? 'var(--danger)' : ($loop->index == 7 ? 'var(--warning)' : 'var(--success)') }};">
                            @if($count > 0)
                                <div class="bar-val">{{ $count }}</div>
                            @endif
                        </div>
                        <div class="bar-lbl">{{ $label }}</div>
                    </div>
                @endforeach
            </div>
            <div class="text-center text-xs text-muted mt-4">Rentang Nilai (0-100)</div>
        </div>
    </div>
    
</div>

<!-- ITEM ANALYSIS -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Analisis Butir Soal (Daya Serap)</h3>
        <p class="text-sm text-muted mt-1">Persentase siswa yang menjawab benar pada setiap butir soal.</p>
    </div>
    <div style="padding-bottom: 8px;">
        @foreach($questionStats as $qs)
            @php
                $percent = $qs['percent'];
                $colorClass = $percent >= 70 ? '' : ($percent >= 40 ? 'medium' : 'low');
            @endphp
            <div class="q-analysis-row">
                <div class="q-num">{{ $qs['order'] }}</div>
                <div class="q-text">{{ strip_tags($qs['question']) }}</div>
                <div class="q-meter-wrap">
                    <div class="flex justify-between">
                        <span class="text-xs text-muted">{{ $qs['correct'] }} / {{ $qs['total'] }} benar</span>
                        <span class="q-meter-lbl">{{ $percent }}%</span>
                    </div>
                    <div class="q-meter-bg">
                        <div class="q-meter-fill {{ $colorClass }}" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
