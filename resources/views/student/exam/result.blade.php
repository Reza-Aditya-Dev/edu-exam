@extends('layouts.student')

@section('title', 'Hasil Ujian — EduExam')
@section('page_title', 'Hasil Ujian')

@section('student-content')
@php
    $showResult = $exam->settings ? (bool)$exam->settings->show_result_immediately : true;
    $score = round($result->total_score);
    $kkm = $exam->passing_grade ?? 75;
    $diff = $score - $kkm;
    $isPassed = $result->pass_status === 'pass';
    // Circumference for r=68 is 2 * pi * 68 = 427.25
    $circumference = 427.25;
    $offset = $circumference * (1 - min(1, max(0, $score / 100)));
@endphp

<div class="flex flex-col w-full pb-8">
    <!-- Celebration Top Hero Section -->
    <div class="relative pt-2 md:pt-4 pb-5 md:pb-7 flex flex-col items-center text-center overflow-hidden max-w-2xl mx-auto w-full">
        <div class="absolute -top-12 left-1/2 -translate-x-1/2 w-72 md:w-96 h-72 md:h-96 {{ $isPassed ? 'bg-secondary-container/30' : 'bg-error-container/30' }} rounded-full blur-3xl pointer-events-none -z-10"></div>
        
        <!-- School Badge & Status Pill Row -->
        <div class="flex items-center justify-between w-full mb-3 px-1">
            <div class="flex items-center gap-1.5 bg-surface-container-low px-2.5 py-1 rounded-full shadow-sm border border-surface-container">
                <span class="w-2 h-2 rounded-full bg-primary"></span>
                <span class="text-[11px] font-semibold text-on-surface-variant uppercase tracking-wider">{{ $schoolName ?? \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}</span>
            </div>
            <div class="flex items-center gap-1 bg-secondary-container px-2.5 py-1 rounded-full shadow-sm text-on-secondary-container text-xs font-semibold">
                <span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                <span>Terverifikasi</span>
            </div>
        </div>

        <!-- Celebration Chip & Title -->
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $isPassed ? 'bg-secondary-fixed text-on-secondary-fixed' : 'bg-error-container text-error' }} mb-2.5 shadow-sm text-xs font-semibold">
            <span class="text-sm leading-none">{{ $isPassed ? '🎉' : '📋' }}</span>
            <span>{{ $isPassed ? 'Ujian Berhasil Diselesaikan' : 'Ujian Telah Selesai' }}</span>
        </div>

        <h2 class="font-headline-sm text-lg md:text-2xl text-on-surface font-bold tracking-tight mb-0.5 md:mb-1">
            {{ $exam->title }}
        </h2>
        <p class="font-body-sm text-xs md:text-sm text-on-surface-variant mb-1">
            Kelas {{ $exam->classroom->name ?? '-' }} • {{ $exam->subject->name ?? 'Mata Pelajaran' }}
        </p>
        <div class="inline-flex items-center gap-1 text-on-surface-variant text-[11px] md:text-xs">
            <span class="material-symbols-outlined text-[14px]">schedule</span>
            <span>{{ $result->created_at->translatedFormat('d F Y • H:i') }} WIB</span>
        </div>
    </div>

    @if($showResult)
    <!-- Two Column Layout on Desktop/Laptop (lg+), Stacked on Mobile/Tablet -->
    <div class="lg:grid lg:grid-cols-12 lg:gap-6 items-start">
        <!-- Left Column: Score Showcase Card with Circular Gauge -->
        <div class="lg:col-span-5 mb-5 lg:mb-0">
            <div class="bg-surface-container-lowest rounded-2xl p-5 md:p-6 shadow-sm border border-surface-container flex flex-col items-center text-center relative overflow-hidden">
                <div class="absolute -top-16 -right-16 w-36 h-36 {{ $isPassed ? 'bg-secondary-container/20' : 'bg-error-container/20' }} rounded-full blur-2xl pointer-events-none"></div>

                <!-- Circular Score Ring SVG -->
                <div class="relative w-40 h-40 md:w-48 md:h-48 flex items-center justify-center my-2 md:my-4">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 160 160">
                        <circle cx="80" cy="80" fill="none" r="68" stroke="#E5EEFF" stroke-width="12"></circle>
                        <circle class="transition-all duration-1000 ease-out" cx="80" cy="80" fill="none" r="68"
                                stroke="{{ $isPassed ? '#006c49' : '#ba1a1a' }}"
                                stroke-dasharray="{{ $circumference }}"
                                stroke-dashoffset="{{ $offset }}"
                                stroke-linecap="round" stroke-width="12"></circle>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-4xl md:text-5xl font-headline-lg {{ $isPassed ? 'text-secondary' : 'text-error' }} font-extrabold tracking-tight leading-none">
                            {{ $score }}
                        </span>
                        <span class="text-[10px] md:text-xs text-on-surface-variant mt-1 font-semibold uppercase tracking-wider">
                            Nilai Ujian
                        </span>
                    </div>
                </div>

                <!-- Status Badge "LULUS" or "REMEDIAL" -->
                <div class="mt-1 mb-3 inline-flex items-center gap-1.5 px-4 md:px-5 py-1.5 md:py-2 rounded-full shadow-sm text-white {{ $isPassed ? 'bg-secondary' : 'bg-error' }}">
                    <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">
                        {{ $isPassed ? 'check_circle' : 'cancel' }}
                    </span>
                    <span class="font-label-lg text-xs md:text-sm font-bold tracking-wide">
                        {{ $isPassed ? 'LULUS' : 'REMEDIAL' }}
                    </span>
                </div>

                <!-- KKM Benchmark Container -->
                <div class="w-full bg-surface-container-low rounded-xl py-2.5 px-3 md:px-4 flex items-center justify-between border border-surface-container">
                    <div class="flex items-center gap-1.5 text-xs md:text-sm text-on-surface-variant">
                        <span class="material-symbols-outlined text-[18px]">flag</span>
                        <span>Target Kelulusan (KKM): <strong class="font-semibold text-on-surface">{{ $kkm }}</strong></span>
                    </div>
                    <span class="text-xs md:text-sm font-bold px-2 py-0.5 rounded-full {{ $diff >= 0 ? 'text-secondary bg-secondary-container/60' : 'text-error bg-error-container/60' }}">
                        {{ $diff >= 0 ? "+$diff poin" : "$diff poin" }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Right Column: Performance Breakdown Grid + Details Card + Action Buttons -->
        <div class="lg:col-span-7 flex flex-col">
            <!-- Performance Breakdown Grid (2x2 / 4-col on tablet) -->
            <div class="mb-5">
                <h3 class="font-headline-sm text-sm md:text-base font-bold text-on-surface mb-2.5 px-1">
                    Ringkasan Jawaban
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-2 xl:grid-cols-4 gap-2.5 md:gap-3">
                    <!-- Card 1: Benar -->
                    <div class="bg-surface-container-lowest rounded-xl p-3 md:p-3.5 shadow-sm border border-surface-container flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-on-surface-variant font-medium">Jawaban Benar</span>
                            <div class="w-7 h-7 rounded-full bg-secondary-container flex items-center justify-center">
                                <span class="material-symbols-outlined text-[16px] text-on-secondary-container" style="font-variation-settings: 'FILL' 1;">check</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-1">
                            <span class="font-headline-sm text-xl md:text-2xl font-bold text-secondary">{{ $result->correct_answers }}</span>
                            <span class="text-xs text-on-surface-variant font-medium">soal</span>
                        </div>
                    </div>

                    <!-- Card 2: Salah -->
                    <div class="bg-surface-container-lowest rounded-xl p-3 md:p-3.5 shadow-sm border border-surface-container flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-on-surface-variant font-medium">Jawaban Salah</span>
                            <div class="w-7 h-7 rounded-full bg-error-container flex items-center justify-center">
                                <span class="material-symbols-outlined text-[16px] text-error" style="font-variation-settings: 'FILL' 1;">close</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-1">
                            <span class="font-headline-sm text-xl md:text-2xl font-bold text-error">{{ $result->wrong_answers }}</span>
                            <span class="text-xs text-on-surface-variant font-medium">soal</span>
                        </div>
                    </div>

                    <!-- Card 3: Kosong -->
                    <div class="bg-surface-container-lowest rounded-xl p-3 md:p-3.5 shadow-sm border border-surface-container flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-on-surface-variant font-medium">Tidak Dijawab</span>
                            <div class="w-7 h-7 rounded-full bg-surface-container-high flex items-center justify-center">
                                <span class="material-symbols-outlined text-[16px] text-on-surface-variant">remove</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-1">
                            <span class="font-headline-sm text-xl md:text-2xl font-bold text-on-surface">{{ $result->unanswered }}</span>
                            <span class="text-xs text-on-surface-variant font-medium">soal</span>
                        </div>
                    </div>

                    <!-- Card 4: Waktu -->
                    <div class="bg-surface-container-lowest rounded-xl p-3 md:p-3.5 shadow-sm border border-surface-container flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-on-surface-variant font-medium">Waktu Pengerjaan</span>
                            <div class="w-7 h-7 rounded-full bg-primary-fixed flex items-center justify-center">
                                <span class="material-symbols-outlined text-[16px] text-primary">timer</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-1">
                            <span class="font-headline-sm text-xl md:text-2xl font-bold text-primary">{{ $result->time_spent_minutes }}'</span>
                            <span class="text-xs text-on-surface-variant font-medium">menit</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Card -->
            <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-surface-container p-4 md:p-5 mb-5">
                <h3 class="font-headline-sm text-sm md:text-base font-bold text-on-surface mb-3 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-primary text-[18px] md:text-[20px]">receipt_long</span>
                    <span>Rincian Evaluasi Ujian</span>
                </h3>
                <div class="flex flex-col divide-y divide-surface-container text-xs md:text-sm">
                    <div class="py-2.5 flex justify-between items-center">
                        <span class="text-on-surface-variant">Mata Pelajaran</span>
                        <span class="font-semibold text-on-surface">{{ $exam->subject->name }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between items-center">
                        <span class="text-on-surface-variant">Guru Pengampu</span>
                        <span class="font-semibold text-on-surface">{{ $exam->teacher->name ?? 'Guru Pengampu' }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between items-center">
                        <span class="text-on-surface-variant">Tipe Ujian</span>
                        <span class="font-semibold text-on-surface">{{ $exam->exam_type ?? 'Ujian Digital' }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between items-center">
                        <span class="text-on-surface-variant">Waktu Pengumpulan</span>
                        <span class="font-semibold text-on-surface">{{ $result->created_at->format('d/m/Y H:i') }} WIB</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-2.5 md:gap-3">
                <a href="{{ route('student.dashboard') }}" class="flex-1 h-12 bg-primary hover:bg-primary-container text-on-primary rounded-xl flex items-center justify-center font-label-lg text-sm font-bold shadow-md active:scale-[0.98] transition-all gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">home</span>
                    <span>Kembali ke Beranda</span>
                </a>
                <a href="{{ route('student.history') }}" class="flex-1 h-12 bg-surface-container-low hover:bg-surface-container text-primary rounded-xl flex items-center justify-center font-label-lg text-xs md:text-sm font-semibold shadow-sm active:scale-[0.98] transition-all gap-1.5 border border-surface-container">
                    <span class="material-symbols-outlined text-[18px]">history_edu</span>
                    <span>Lihat Riwayat Nilai</span>
                </a>
            </div>
        </div>
    </div>
    @else
    <!-- Hidden Score Card Notice -->
    <div class="mb-5 bg-surface-container-lowest rounded-2xl p-6 md:p-8 shadow-sm border border-surface-container text-center flex flex-col items-center max-w-xl mx-auto w-full">
        <div class="w-14 h-14 rounded-full bg-primary-fixed flex items-center justify-center text-primary mb-3">
            <span class="material-symbols-outlined text-[28px]">lock_clock</span>
        </div>
        <h3 class="font-headline-sm text-base md:text-lg font-bold text-on-surface">Hasil Disimpan &amp; Terkunci</h3>
        <p class="font-body-sm text-xs md:text-sm text-on-surface-variant mt-2 max-w-sm leading-relaxed">
            Jawaban Anda telah berhasil tersimpan di sistem. Sesuai pengaturan pengawas, rincian skor dan hasil akhir akan diumumkan setelah seluruh sesi ujian selesai dinilai.
        </p>

        <!-- Action Buttons when hidden -->
        <div class="flex flex-col sm:flex-row gap-2.5 md:gap-3 w-full mt-6">
            <a href="{{ route('student.dashboard') }}" class="flex-1 h-12 bg-primary hover:bg-primary-container text-on-primary rounded-xl flex items-center justify-center font-label-lg text-sm font-bold shadow-md active:scale-[0.98] transition-all gap-1.5">
                <span class="material-symbols-outlined text-[18px]">home</span>
                <span>Kembali ke Beranda</span>
            </a>
            <a href="{{ route('student.history') }}" class="flex-1 h-12 bg-surface-container-low hover:bg-surface-container text-primary rounded-xl flex items-center justify-center font-label-lg text-xs md:text-sm font-semibold shadow-sm active:scale-[0.98] transition-all gap-1.5 border border-surface-container">
                <span class="material-symbols-outlined text-[18px]">history_edu</span>
                <span>Lihat Riwayat Nilai</span>
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
