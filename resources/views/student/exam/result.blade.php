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
                                stroke="{{ $result->is_pending_review ? '#d97706' : ($isPassed ? '#006c49' : '#ba1a1a') }}"
                                stroke-dasharray="{{ $circumference }}"
                                stroke-dashoffset="{{ $offset }}"
                                stroke-linecap="round" stroke-width="12"></circle>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-4xl md:text-5xl font-headline-lg {{ $result->is_pending_review ? 'text-amber-600' : ($isPassed ? 'text-secondary' : 'text-error') }} font-extrabold tracking-tight leading-none">
                            {{ $score }}
                        </span>
                        <span class="text-[10px] md:text-xs text-on-surface-variant mt-1 font-semibold uppercase tracking-wider">
                            {{ $result->is_pending_review ? 'Nilai Sementara' : 'Nilai Ujian' }}
                        </span>
                    </div>
                </div>

                <!-- Status Badge "LULUS" or "REMEDIAL" or "MENUNGGU KOREKSI" or "DIDISKUALIFIKASI" -->
                @if($result->is_disqualified)
                    <div class="mt-1 mb-3 inline-flex items-center gap-1.5 px-4 md:px-5 py-1.5 md:py-2 rounded-full shadow-sm text-white bg-rose-700">
                        <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">
                            block
                        </span>
                        <span class="font-label-lg text-xs md:text-sm font-bold tracking-wide">
                            DIDISKUALIFIKASI
                        </span>
                    </div>
                @elseif($result->is_pending_review)
                    <div class="mt-1 mb-3 inline-flex items-center gap-1.5 px-4 md:px-5 py-1.5 md:py-2 rounded-full shadow-sm text-amber-900 bg-amber-100 border border-amber-300">
                        <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">
                            pending_actions
                        </span>
                        <span class="font-label-lg text-xs md:text-sm font-bold tracking-wide">
                            MENUNGGU KOREKSI GURU
                        </span>
                    </div>
                @else
                    <div class="mt-1 mb-3 inline-flex items-center gap-1.5 px-4 md:px-5 py-1.5 md:py-2 rounded-full shadow-sm text-white {{ $isPassed ? 'bg-secondary' : 'bg-error' }}">
                        <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">
                            {{ $isPassed ? 'check_circle' : 'cancel' }}
                        </span>
                        <span class="font-label-lg text-xs md:text-sm font-bold tracking-wide">
                            {{ $isPassed ? 'LULUS' : 'REMEDIAL' }}
                        </span>
                    </div>
                @endif

                <!-- KKM Benchmark Container -->
                <div class="w-full bg-surface-container-low rounded-xl py-2.5 px-3 md:px-4 flex items-center justify-between border border-surface-container">
                    <div class="flex items-center gap-1.5 text-xs md:text-sm text-on-surface-variant">
                        <span class="material-symbols-outlined text-[18px]">flag</span>
                        <span>Target Kelulusan (KKM): <strong class="font-semibold text-on-surface">{{ $kkm }}</strong></span>
                    </div>
                    @if($result->is_pending_review)
                        <span class="text-[11px] md:text-xs font-semibold px-2 py-0.5 rounded-full text-amber-700 bg-amber-100">
                            Evaluasi Esai Berlangsung
                        </span>
                    @else
                        <span class="text-xs md:text-sm font-bold px-2 py-0.5 rounded-full {{ $diff >= 0 ? 'text-secondary bg-secondary-container/60' : 'text-error bg-error-container/60' }}">
                            {{ $diff >= 0 ? "+$diff poin" : "$diff poin" }}
                        </span>
                    @endif
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
                        <span class="font-semibold text-on-surface">{{ ($result->effective_submitted_at ?? $result->created_at)->copy()->setTimezone($exam->timezone ?? 'Asia/Jakarta')->format('d/m/Y H:i') }} {{ $exam->timezone_code ?? 'WIB' }}</span>
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

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- BAGIAN PENINJAUAN SOAL & JAWABAN SISWA -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div class="mt-8 pt-6 border-t border-surface-container" id="review-soal-jawaban">
        @if($allowReview)
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-6 bg-surface-container-lowest p-4 md:p-5 rounded-2xl border border-surface-container shadow-xs">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-primary-fixed text-primary text-xs font-bold mb-1">
                        <span class="material-symbols-outlined text-[15px]">fact_check</span>
                        <span>Lembar Jawaban Siswa</span>
                    </div>
                    <h3 class="font-headline-sm text-base md:text-xl font-bold text-on-surface tracking-tight">
                        Tinjauan Butir Soal &amp; Jawaban Anda
                    </h3>
                    <p class="font-body-sm text-xs md:text-sm text-on-surface-variant mt-0.5">
                        Menampilkan butir soal ujian beserta pilihan dan jawaban yang telah Anda simpan saat mengerjakan ujian.
                    </p>
                </div>

                <!-- Info Badges -->
                <div class="flex items-center gap-2 flex-wrap">
                    @if($showCorrectAnswers)
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-secondary-container text-on-secondary-container font-label-sm text-xs font-semibold shadow-2xs">
                            <span class="material-symbols-outlined text-[16px]">visibility</span>
                            <span>Kunci &amp; Pembahasan Dibuka</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-surface-container text-on-surface-variant font-label-sm text-xs font-semibold">
                            <span class="material-symbols-outlined text-[16px]">visibility_off</span>
                            <span>Kunci Jawaban Ditutup Guru</span>
                        </span>
                    @endif
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-surface-container-high text-on-surface font-label-sm text-xs font-bold">
                        <span class="material-symbols-outlined text-[16px]">quiz</span>
                        <span>{{ count($answers) }} Butir Soal</span>
                    </span>
                </div>
            </div>

            <!-- Questions Stream -->
            @php
                $scorePerQ = count($answers) > 0 ? round(100 / count($answers), 1) : 0;
            @endphp

            <div class="flex flex-col gap-5">
                @forelse($answers as $index => $answer)
                    @php
                        $qNum = $index + 1;
                        $examQuestion = $answer->examQuestion;
                        $q = $examQuestion ? $examQuestion->question : null;
                        if (!$q) continue;

                        $isEssay = $q->type === 'essay';
                        $maxQScore = (float)($q->score ?? $scorePerQ);
                        $obtainedScore = (float)$answer->score_obtained;
                        $hasAnswered = !is_null($answer->selected_option_id) || !empty(trim($answer->answer_text ?? ''));
                        $isCorrect = $answer->is_correct;
                    @endphp

                    <article id="soal-{{ $qNum }}" class="bg-surface-container-lowest rounded-2xl shadow-xs border border-surface-container overflow-hidden flex flex-col transition-all">
                        <!-- Card Header -->
                        <div class="px-4 md:px-5 py-3 bg-surface-container-low/70 border-b border-surface-container flex items-center justify-between flex-wrap gap-2">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2.5 py-1 rounded-lg bg-surface-container-highest text-on-surface font-bold text-xs md:text-sm">
                                    Soal No. {{ $qNum }}
                                </span>
                                <span class="text-xs text-on-surface-variant font-medium">
                                    {{ $q->type === 'multiple_choice' ? 'Pilihan Ganda' : ($q->type === 'true_false' ? 'Benar / Salah' : ($q->type === 'essay' ? 'Esai / Uraian' : 'Isian')) }}
                                </span>
                                <span class="text-xs text-on-surface-variant">• Bobot: <strong class="text-on-surface">{{ $maxQScore }}</strong> poin</span>
                            </div>

                            <!-- Status Badge -->
                            <div>
                                @if($isEssay)
                                    @if($isCorrect === true)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-secondary-container text-on-secondary-container text-xs font-semibold">
                                            <span class="material-symbols-outlined text-[14px]">check</span>
                                            <span>Dinilai Benar ({{ $obtainedScore }}/{{ $maxQScore }})</span>
                                        </span>
                                    @elseif($isCorrect === false && $obtainedScore > 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-300 text-xs font-semibold">
                                            <span class="material-symbols-outlined text-[14px]">tune</span>
                                            <span>Nilai Sebagian ({{ $obtainedScore }}/{{ $maxQScore }})</span>
                                        </span>
                                    @elseif($isCorrect === false)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-error-container text-error text-xs font-semibold">
                                            <span class="material-symbols-outlined text-[14px]">close</span>
                                            <span>Dinilai 0 (0/{{ $maxQScore }})</span>
                                        </span>
                                    @elseif($obtainedScore > 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-300 text-xs font-semibold">
                                            <span class="material-symbols-outlined text-[14px]">tune</span>
                                            <span>Nilai ({{ $obtainedScore }}/{{ $maxQScore }})</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-300 text-xs font-semibold">
                                            <span class="material-symbols-outlined text-[14px]">pending</span>
                                            <span>Menunggu Koreksi Guru</span>
                                        </span>
                                    @endif
                                @elseif($showCorrectAnswers)
                                    @if($isCorrect)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-secondary-container text-on-secondary-container text-xs font-bold">
                                            <span class="material-symbols-outlined text-[14px]">check</span>
                                            <span>Benar (+{{ $obtainedScore }})</span>
                                        </span>
                                    @elseif($hasAnswered)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-error-container text-error text-xs font-bold">
                                            <span class="material-symbols-outlined text-[14px]">close</span>
                                            <span>Salah (0 Poin)</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-surface-container text-on-surface-variant text-xs font-semibold">
                                            <span class="material-symbols-outlined text-[14px]">remove</span>
                                            <span>Tidak Dijawab (0 Poin)</span>
                                        </span>
                                    @endif
                                @else
                                    {{-- Kunci jawaban tidak dibuka oleh guru --}}
                                    @if($hasAnswered)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-surface-container text-on-surface text-xs font-medium border border-surface-container">
                                            <span class="material-symbols-outlined text-[14px] text-primary">task_alt</span>
                                            <span>Tersimpan</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant text-xs font-medium">
                                            <span class="material-symbols-outlined text-[14px]">remove</span>
                                            <span>Tidak Dijawab</span>
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-4 md:p-6 flex flex-col gap-4">
                            <!-- Question Text -->
                            <div class="text-on-surface text-sm md:text-base leading-relaxed font-normal">
                                {!! nl2br(e($q->question_text)) !!}
                            </div>

                            <!-- Question Image -->
                            @if($q->question_image)
                                <div class="p-2 rounded-xl bg-surface-container-low border border-surface-container inline-block self-start max-w-full">
                                    <img src="{{ asset('storage/' . $q->question_image) }}" alt="Gambar Soal No {{ $qNum }}" class="max-h-72 rounded-lg object-contain">
                                </div>
                            @endif

                            <!-- Options View (PG / True False) -->
                            @if(in_array($q->type, ['multiple_choice', 'true_false']) && $q->options)
                                <div class="grid grid-cols-1 gap-2.5 mt-1">
                                    @foreach($q->options as $opt)
                                        @php
                                            $isSelected = ($answer->selected_option_id == $opt->id);
                                            $isCorrectOpt = $showCorrectAnswers && (bool)$opt->is_correct;

                                            // Tentukan gaya styling card opsi
                                            if ($showCorrectAnswers) {
                                                if ($isSelected && $isCorrectOpt) {
                                                    $boxClass = 'bg-secondary-container/30 border-secondary/50 shadow-xs';
                                                    $badgeLetter = 'bg-secondary text-white font-bold';
                                                } elseif ($isSelected && !$isCorrectOpt) {
                                                    $boxClass = 'bg-error-container/30 border-error/50 shadow-xs';
                                                    $badgeLetter = 'bg-error text-white font-bold';
                                                } elseif ($isCorrectOpt) {
                                                    $boxClass = 'bg-secondary-container/20 border-secondary/40';
                                                    $badgeLetter = 'bg-secondary text-white font-bold';
                                                } else {
                                                    $boxClass = 'bg-surface opacity-80 border-surface-container';
                                                    $badgeLetter = 'bg-surface-container text-on-surface-variant';
                                                }
                                            } else {
                                                // Jika kunci tidak ditampilkan: tandai pilihan siswa saja
                                                if ($isSelected) {
                                                    $boxClass = 'bg-primary-fixed/40 border-primary/50 shadow-xs';
                                                    $badgeLetter = 'bg-primary text-white font-bold';
                                                } else {
                                                    $boxClass = 'bg-surface border-surface-container';
                                                    $badgeLetter = 'bg-surface-container text-on-surface-variant';
                                                }
                                            }
                                        @endphp

                                        <div class="flex items-center justify-between p-3.5 rounded-xl border {{ $boxClass }} transition-all">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <span class="w-8 h-8 rounded-full flex items-center justify-center text-xs shrink-0 {{ $badgeLetter }}">
                                                    {{ $opt->label }}
                                                </span>
                                                <span class="text-xs md:text-sm text-on-surface font-medium leading-normal">
                                                    {{ $opt->option_text }}
                                                </span>
                                            </div>

                                            <div class="flex items-center gap-2 shrink-0 ml-3">
                                                @if($showCorrectAnswers)
                                                    @if($isSelected && $isCorrectOpt)
                                                        <span class="px-2.5 py-1 rounded-lg bg-surface-container-lowest text-secondary text-[11px] md:text-xs shadow-2xs flex items-center gap-1 font-bold">
                                                            <span class="material-symbols-outlined text-[15px]">done_all</span>
                                                            <span>Pilihan Anda &amp; Kunci Benar</span>
                                                        </span>
                                                    @elseif($isSelected && !$isCorrectOpt)
                                                        <span class="px-2.5 py-1 rounded-lg bg-error-container text-error text-[11px] md:text-xs flex items-center gap-1 font-bold">
                                                            <span class="material-symbols-outlined text-[15px]">close</span>
                                                            <span>Pilihan Anda (Salah)</span>
                                                        </span>
                                                    @elseif($isCorrectOpt)
                                                        <span class="px-2.5 py-1 rounded-lg bg-surface-container-lowest text-secondary text-[11px] md:text-xs shadow-2xs flex items-center gap-1 font-bold">
                                                            <span class="material-symbols-outlined text-[15px]">check_circle</span>
                                                            <span>Kunci Jawaban</span>
                                                        </span>
                                                    @endif
                                                @else
                                                    @if($isSelected)
                                                        <span class="px-2.5 py-1 rounded-lg bg-primary text-white text-[11px] md:text-xs flex items-center gap-1 font-semibold shadow-2xs">
                                                            <span class="material-symbols-outlined text-[14px]">check</span>
                                                            <span>Jawaban Anda</span>
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif($q->type === 'essay')
                                <!-- Essay Written Answer -->
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center justify-between text-xs text-on-surface-variant font-medium">
                                        <span class="font-semibold text-on-surface flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px] text-primary">edit_note</span>
                                            <span>Jawaban yang Anda Tulis:</span>
                                        </span>
                                        <span>{{ !empty(trim($answer->answer_text ?? '')) ? str_word_count($answer->answer_text) . ' kata' : 'Kosong' }}</span>
                                    </div>
                                    <div class="p-4 rounded-xl bg-surface-container-low border border-surface-container text-on-surface text-xs md:text-sm leading-relaxed whitespace-pre-wrap">
                                        {{ $answer->answer_text ?: '(Anda tidak menuliskan jawaban untuk soal ini)' }}
                                    </div>
                                </div>
                            @else
                                <!-- Short Answer View -->
                                <div class="flex flex-col gap-2">
                                    <span class="text-xs font-semibold text-on-surface flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px] text-primary">edit</span>
                                        <span>Jawaban Isian Singkat Anda:</span>
                                    </span>
                                    <div class="p-3.5 rounded-xl bg-surface-container-low border border-surface-container text-on-surface text-xs md:text-sm font-semibold">
                                        {{ $answer->answer_text ?: '(Kosong / Tidak diisi)' }}
                                    </div>
                                </div>
                            @endif

                            <!-- Explanation / Discussion Box (Jika diizinkan guru) -->
                            @if($showCorrectAnswers && $q->explanation)
                                <div class="mt-2 p-3.5 md:p-4 rounded-xl bg-surface-container-low border border-surface-container flex flex-col gap-1.5">
                                    <div class="flex items-center gap-1.5 text-primary text-xs md:text-sm font-bold">
                                        <span class="material-symbols-outlined text-[18px]">lightbulb</span>
                                        <span>Pembahasan &amp; Penjelasan Soal:</span>
                                    </div>
                                    <p class="text-xs md:text-sm text-on-surface leading-relaxed pl-6">
                                        {{ $q->explanation }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="p-8 text-center bg-surface-container-lowest rounded-2xl border border-surface-container">
                        <span class="material-symbols-outlined text-outline text-4xl mb-2">assignment_late</span>
                        <p class="font-bold text-on-surface text-sm md:text-base">Data soal untuk ujian ini tidak ditemukan.</p>
                    </div>
                @endforelse
            </div>
        @else
            <!-- Notice when Teacher Does NOT Allow Review -->
            <div class="bg-surface-container-lowest rounded-2xl p-6 md:p-8 shadow-xs border border-surface-container text-center flex flex-col items-center max-w-2xl mx-auto w-full">
                <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant mb-3">
                    <span class="material-symbols-outlined text-[28px]">lock</span>
                </div>
                <h3 class="font-headline-sm text-base md:text-lg font-bold text-on-surface">
                    Peninjauan Soal &amp; Jawaban Belum Diizinkan
                </h3>
                <p class="font-body-sm text-xs md:text-sm text-on-surface-variant mt-2 max-w-md leading-relaxed">
                    Guru pengampu belum mengizinkan Anda melihat kembali rincian butir soal dan jawaban untuk ujian ini. Silakan hubungi guru yang bersangkutan jika membutuhkan informasi lebih lanjut mengenai evaluasi hasil ujian.
                </p>
                <div class="mt-4 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container text-on-surface-variant text-xs font-semibold">
                    <span class="material-symbols-outlined text-[14px]">info</span>
                    <span>Status Izin: Nonaktif</span>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
