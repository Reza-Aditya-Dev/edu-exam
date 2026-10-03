@extends('layouts.teacher')

@section('title', 'Analisis Ujian: ' . $exam->title)
@section('page_title', 'Analisis & Evaluasi Ujian')

@push('teacher-styles')
<style>
    .distribution-chart { 
        display: flex; 
        align-items: flex-end; 
        gap: 8px; 
        height: 200px; 
        padding: 24px 0 0; 
        border-bottom: 2px solid #e2e8f0; 
        margin-bottom: 20px; 
    }
    .bar-group { 
        flex: 1; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: flex-end; 
        height: 100%; 
        position: relative; 
    }
    .bar { 
        width: 80%; 
        border-radius: 6px 6px 0 0; 
        min-height: 4px; 
        transition: height 0.6s ease; 
        position: relative; 
    }
    .bar-val { 
        position: absolute; 
        top: -22px; 
        left: 50%; 
        transform: translateX(-50%); 
        font-size: 0.7rem; 
        font-weight: 700; 
        color: #475569; 
    }
    .bar-lbl { 
        margin-top: 8px; 
        font-size: 0.7rem; 
        font-weight: 600; 
        color: #64748b; 
    }
</style>
@endpush

@section('teacher-content')

<!-- Header Info & Navigation -->
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <span class="badge badge-primary text-xs font-semibold px-2.5 py-0.5">{{ $exam->subject->name }}</span>
            <span class="badge badge-gray text-xs font-semibold px-2.5 py-0.5">{{ $exam->classroom->name }}</span>
        </div>
        <h2 class="font-headline font-bold text-slate-900 text-xl md:text-2xl">{{ $exam->title }}</h2>
        <div class="text-xs text-slate-500 mt-0.5">Analisis Daya Serap Butir Soal & Distribusi Frekuensi Nilai</div>
    </div>
    
    <div class="flex gap-2">
        <a href="{{ route('teacher.exams.results', $exam) }}" class="btn btn-secondary text-xs px-3.5 py-2 flex items-center gap-1.5">
            <span class="material-symbols-outlined" style="font-size: 16px;">format_list_bulleted</span>
            Rekap Nilai Siswa
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
    
    <!-- Executive Stats Summary (4 cols) -->
    <div class="lg:col-span-4">
        <div class="card overflow-hidden h-full flex flex-col justify-between">
            <div class="card-header p-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600" style="font-size: 20px;">query_stats</span>
                <h3 class="font-headline font-bold text-slate-900 text-sm">Ringkasan Statistik</h3>
            </div>
            
            <div class="card-body p-6 flex-1 flex flex-col justify-center">
                <div class="text-center mb-6">
                    <div class="font-headline font-extrabold text-4xl md:text-5xl text-slate-900 leading-tight">
                        {{ $stats['avg'] }}
                    </div>
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Rata-rata Nilai Kelas</div>
                </div>
                
                <div class="space-y-2.5">
                    <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl text-xs">
                        <span class="text-slate-500 font-medium">Total Peserta</span>
                        <span class="font-bold text-slate-900">{{ $stats['total'] }} Siswa</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-emerald-50/70 border border-emerald-100 rounded-xl text-xs">
                        <span class="text-emerald-800 font-medium">Tingkat Kelulusan</span>
                        <span class="font-bold text-emerald-700">{{ $stats['pass_rate'] }}%</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl text-xs">
                        <span class="text-slate-500 font-medium">Nilai Tertinggi</span>
                        <span class="font-bold text-indigo-600">{{ $stats['max'] }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl text-xs">
                        <span class="text-slate-500 font-medium">Nilai Terendah</span>
                        <span class="font-bold text-rose-600">{{ $stats['min'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Distribution Chart (8 cols) -->
    <div class="lg:col-span-8">
        <div class="card overflow-hidden">
            <div class="card-header p-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600" style="font-size: 20px;">bar_chart</span>
                <h3 class="font-headline font-bold text-slate-900 text-sm">Sebaran Frekuensi Nilai Siswa</h3>
            </div>
            
            <div class="card-body p-6">
                @php $maxCount = max(1, $distribution->max()); @endphp
                
                <div class="distribution-chart">
                    @foreach($distribution as $label => $count)
                        @php 
                            $height = ($count / $maxCount) * 100; 
                            $barBg = $loop->index < 6 ? '#ef4444' : ($loop->index < 8 ? '#f59e0b' : '#10b981');
                        @endphp
                        <div class="bar-group">
                            <div class="bar" style="height: {{ max(4, $height) }}%; background: {{ $barBg }};">
                                @if($count > 0)
                                    <div class="bar-val">{{ $count }}</div>
                                @endif
                            </div>
                            <div class="bar-lbl">{{ $label }}</div>
                        </div>
                    @endforeach
                </div>
                
                <div class="flex justify-center items-center gap-6 text-xs text-slate-500 font-medium pt-2">
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-sm bg-rose-500"></span> Di bawah KKM
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-sm bg-amber-500"></span> Cukup
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-sm bg-emerald-500"></span> Baik / Memuaskan
                    </span>
                </div>
            </div>
        </div>
    </div>
    
</div>

<!-- Item Analysis (Daya Serap Butir Soal) -->
<div class="card overflow-hidden">
    <div class="card-header p-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-indigo-600" style="font-size: 20px;">checklist_rtl</span>
            <h3 class="font-headline font-bold text-slate-900 text-sm">Analisis Butir Soal (Daya Serap Siswa)</h3>
        </div>
        <p class="text-xs text-slate-500">Persentase siswa yang berhasil menjawab benar.</p>
    </div>
    
    <div class="divide-y divide-slate-100">
        @foreach($questionStats as $qs)
            @php
                $percent = $qs['percent'];
                $barColor = $percent >= 70 ? 'bg-emerald-500' : ($percent >= 40 ? 'bg-amber-500' : 'bg-rose-500');
                $textColor = $percent >= 70 ? 'text-emerald-700' : ($percent >= 40 ? 'text-amber-700' : 'text-rose-700');
            @endphp
            <div class="p-4 px-6 hover:bg-slate-50/70 transition flex items-center gap-4">
                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center flex-shrink-0">
                    {{ $qs['order'] }}
                </div>
                <div class="flex-1 text-xs text-slate-800 font-medium line-clamp-1">
                    {{ strip_tags($qs['question']) }}
                </div>
                <div class="w-44 flex flex-col gap-1 flex-shrink-0">
                    <div class="flex justify-between items-center text-[11px]">
                        <span class="text-slate-400 font-medium">{{ $qs['correct'] }} / {{ $qs['total'] }} benar</span>
                        <span class="font-bold {{ $textColor }}">{{ $percent }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="{{ $barColor }} h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%;"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
