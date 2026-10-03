@extends('layouts.student')

@section('title', 'Riwayat Ujian — EduExam')

@section('student-content')
<div class="flex flex-col w-full pb-8">
    <!-- Header -->
    <div class="pt-2 pb-3 flex flex-col">
        <div class="flex items-center justify-between mb-1">
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-primary-container animate-pulse"></span>
                <span class="text-[11px] font-semibold text-primary tracking-wider uppercase">Evaluasi Terpadu</span>
            </div>
            <span class="text-[11px] font-medium text-on-surface-variant bg-surface-container px-2 py-0.5 rounded-full">
                Tahun Ajaran 2026/2027
            </span>
        </div>
        <h1 class="font-headline-sm text-lg md:text-xl font-bold text-on-surface tracking-tight">Riwayat Ujian</h1>
        <p class="font-body-sm text-xs text-on-surface-variant mt-0.5">Daftar evaluasi dan hasil ujian Anda di SMA Nusantara</p>
    </div>

    <!-- Average Score Summary Card -->
    <div class="my-1.5">
        <div class="bg-surface-container-low rounded-2xl p-4 shadow-sm border border-surface-container relative overflow-hidden flex items-center justify-between">
            <div class="flex flex-col z-10">
                <span class="text-[10px] text-on-surface-variant uppercase tracking-wider font-semibold">Rata-Rata Nilai</span>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="font-headline-lg text-2xl font-bold text-primary">{{ $avgScore }}</span>
                    <span class="text-xs text-on-surface-variant">/ 100</span>
                </div>
                <span class="text-[11px] {{ $avgScore >= 75 ? 'text-secondary' : 'text-error' }} flex items-center gap-0.5 mt-1 font-semibold">
                    <span class="material-symbols-outlined text-[14px]">
                        {{ $avgScore >= 75 ? 'trending_up' : 'trending_down' }}
                    </span> 
                    {{ $avgScore >= 75 ? 'Di atas KKM sekolah (75)' : 'Perlu ditingkatkan lagi' }}
                </span>
            </div>

            <div class="flex items-center gap-2 z-10">
                <div class="flex flex-col items-center bg-surface-container-lowest px-3 py-1.5 rounded-xl shadow-sm border border-surface-container">
                    <span class="font-headline-sm text-base text-secondary font-bold">{{ $passedCount }}</span>
                    <span class="text-[10px] text-on-surface-variant font-medium">Lulus</span>
                </div>
                <div class="flex flex-col items-center bg-surface-container-lowest px-3 py-1.5 rounded-xl shadow-sm border border-surface-container">
                    <span class="font-headline-sm text-base text-error font-bold">{{ $failedCount }}</span>
                    <span class="text-[10px] text-on-surface-variant font-medium">Remedial</span>
                </div>
            </div>

            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-primary-fixed/30 rounded-full blur-xl pointer-events-none"></div>
        </div>
    </div>

    <!-- Filter Chips -->
    <div class="w-full overflow-x-auto no-scrollbar py-2.5 flex items-center gap-1.5">
        <a href="{{ route('student.history') }}" class="shrink-0 px-3.5 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1.5 shadow-sm active:scale-95 transition-all {{ !request('filter') ? 'bg-primary text-on-primary' : 'bg-surface-container-lowest text-on-surface-variant hover:bg-surface-container border border-surface-container' }}">
            <span>Semua</span>
            <span class="rounded-full px-1.5 py-0.2 text-[10px] {{ !request('filter') ? 'bg-white/20 text-white' : 'bg-surface-container text-on-surface' }}">{{ $totalCount }}</span>
        </a>
        <a href="{{ route('student.history', ['filter' => 'pass']) }}" class="shrink-0 px-3.5 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1.5 shadow-sm active:scale-95 transition-all {{ request('filter') === 'pass' ? 'bg-secondary text-on-secondary' : 'bg-surface-container-lowest text-on-surface-variant hover:bg-surface-container border border-surface-container' }}">
            <span>Lulus</span>
            <span class="rounded-full px-1.5 py-0.2 text-[10px] font-bold {{ request('filter') === 'pass' ? 'bg-white/20 text-white' : 'bg-secondary-fixed text-on-secondary-fixed' }}">{{ $passedCount }}</span>
        </a>
        <a href="{{ route('student.history', ['filter' => 'fail']) }}" class="shrink-0 px-3.5 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1.5 shadow-sm active:scale-95 transition-all {{ request('filter') === 'fail' ? 'bg-error text-white' : 'bg-surface-container-lowest text-on-surface-variant hover:bg-surface-container border border-surface-container' }}">
            <span>Remedial</span>
            <span class="rounded-full px-1.5 py-0.2 text-[10px] font-bold {{ request('filter') === 'fail' ? 'bg-white/20 text-white' : 'bg-error-container text-error' }}">{{ $failedCount }}</span>
        </a>
    </div>

    <!-- Exam Results List -->
    <div class="flex flex-col gap-2.5 mt-1">
        @if($results->isEmpty())
            <div class="w-full bg-surface-container-lowest rounded-2xl p-6 shadow-sm border border-surface-container text-center flex flex-col items-center justify-center gap-2">
                <div class="w-12 h-12 rounded-full bg-surface-container-low flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[24px]">folder_open</span>
                </div>
                <h3 class="font-headline-sm text-sm font-bold text-on-surface">Belum Ada Riwayat</h3>
                <p class="font-body-sm text-xs text-on-surface-variant max-w-[280px]">
                    Belum ada catatan hasil ujian yang sesuai dengan filter ini.
                </p>
            </div>
        @else
            @foreach($results as $result)
                @php
                    $isPass = $result->pass_status === 'pass';
                @endphp
                <div class="bg-surface-container-lowest rounded-2xl p-3.5 shadow-sm border border-surface-container active:scale-[0.99] transition-all relative overflow-hidden flex flex-col gap-2.5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex flex-col min-w-0 flex-1">
                            <div class="flex items-center gap-1.5 mb-1">
                                <span class="px-2 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed-variant text-[10px] font-bold truncate">
                                    {{ $result->exam->exam_type ?? 'UTS Semester 1' }}
                                </span>
                                @if($loop->first && !request('page'))
                                    <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                    <span class="text-[10px] text-secondary font-semibold">Terbaru</span>
                                @endif
                            </div>
                            <h2 class="font-headline-sm text-sm md:text-base font-bold text-on-surface leading-snug">
                                {{ $result->exam->title }}
                            </h2>
                            <div class="flex items-center gap-1 text-on-surface-variant text-xs mt-0.5">
                                <span class="material-symbols-outlined text-[14px]">event</span>
                                <span class="truncate">{{ $result->created_at->translatedFormat('d F Y') }} • {{ $result->time_spent_minutes }} Menit</span>
                            </div>
                        </div>

                        <!-- Score Badge -->
                        <div class="flex flex-col items-center justify-center shrink-0 w-13 h-13 px-3 py-1.5 rounded-xl shadow-sm {{ $isPass ? 'bg-secondary-container text-on-secondary-container' : 'bg-error-container text-error' }}">
                            <span class="font-headline-sm text-lg font-bold leading-none">{{ round($result->total_score) }}</span>
                            <span class="text-[9px] font-semibold opacity-80 mt-0.5 uppercase tracking-wider">Nilai</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-surface-container-low">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <div class="w-6 h-6 rounded-full bg-surface-container flex items-center justify-center text-primary shrink-0">
                                <span class="material-symbols-outlined text-[14px]">school</span>
                            </div>
                            <span class="text-xs text-on-surface-variant truncate">
                                {{ $result->exam->subject->name ?? 'Mata Pelajaran' }} • {{ $result->exam->teacher->name ?? 'Guru Pengampu' }}
                            </span>
                        </div>
                        <a href="{{ route('student.exam.result', $result->exam_id) }}" class="shrink-0 h-7 px-2.5 rounded-lg bg-surface-container-low hover:bg-surface-container text-primary text-xs font-semibold flex items-center gap-0.5 active:scale-95 transition-all">
                            <span>Detail</span>
                            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                        </a>
                    </div>
                </div>
            @endforeach

            <div class="mt-3">
                {{ $results->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
