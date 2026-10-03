@extends('layouts.student')

@section('title', 'Dashboard Siswa — EduExam')

@section('student-content')
<div class="flex flex-col w-full gap-5 pb-6">
    <!-- Greeting Card & Profile Bar -->
    <div class="w-full bg-surface-container-lowest p-4 rounded-2xl shadow-sm flex flex-col gap-3 relative overflow-hidden mt-1">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <div class="relative shrink-0">
                    <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}" class="w-12 h-12 rounded-full object-cover shadow-sm ring-2 ring-primary-fixed">
                    <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-secondary rounded-full ring-2 ring-surface-container-lowest"></span>
                </div>
                <div class="flex flex-col min-w-0">
                    <h1 class="font-headline-sm text-base md:text-lg text-on-surface flex items-center gap-1.5 truncate font-bold">
                        Halo, {{ explode(' ', $student->name)[0] }} <span class="animate-bounce inline-block text-base">👋</span>
                    </h1>
                    <span class="font-label-sm text-xs text-on-surface-variant truncate">
                        {{ $classroom ? $classroom->name : 'Siswa' }} • SMA Nusantara
                    </span>
                </div>
            </div>
            <a href="#notifications" class="relative w-10 h-10 rounded-full bg-surface-container-low flex items-center justify-center text-on-surface transition-transform active:scale-95 shadow-sm">
                <span class="material-symbols-outlined text-[22px]">notifications</span>
                @if(isset($unreadCount) && $unreadCount > 0)
                    <span class="absolute top-1.5 right-1.5 min-w-[16px] h-[16px] px-1 bg-error text-white font-label-sm text-[10px] flex items-center justify-center font-bold rounded-full">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>
        </div>
        
        <!-- Quick Status Banner -->
        <div class="w-full bg-surface-container-low p-2.5 rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-primary shrink-0" style="font-variation-settings: 'FILL' 1;">verified</span>
            <p class="font-label-sm text-xs text-on-surface-variant truncate">
                Status: <strong class="text-on-surface font-semibold">Terdaftar Ujian Semester (UTS/UAS)</strong>
            </p>
        </div>
    </div>

    <!-- Section A: Ujian Aktif (Urgent / Highlight) -->
    <div id="daftar-ujian" class="w-full flex flex-col gap-2.5">
        <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[20px] text-primary" style="font-variation-settings: 'FILL' 1;">play_circle</span>
                <h2 class="font-headline-sm text-sm md:text-base font-bold text-on-surface">Ujian Aktif</h2>
            </div>
            <span class="font-label-sm text-xs text-primary font-semibold">
                {{ $activeExams->count() }} Siap Dikerjakan
            </span>
        </div>

        @if($activeExams->isNotEmpty())
            @foreach($activeExams as $activeExam)
                <div class="w-full bg-surface-container-lowest rounded-2xl p-4 shadow-md relative overflow-hidden flex flex-col gap-3 border border-surface-container-high">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-primary to-primary-container"></div>
                    <div class="flex items-center justify-between pt-1">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-secondary-container text-on-secondary-container text-[11px] font-semibold tracking-wide">
                            <span class="w-2 h-2 rounded-full bg-secondary animate-ping"></span>
                            SEDANG BERLANGSUNG
                        </span>
                        <div class="flex items-center gap-1 text-error bg-error-container/60 px-2.5 py-0.5 rounded-full text-xs font-bold">
                            <span class="material-symbols-outlined text-[14px]">timer</span>
                            <span>{{ $activeExam->duration_minutes }} Menit</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col">
                        <h3 class="font-headline-sm text-base md:text-lg font-bold text-on-surface leading-snug">
                            {{ $activeExam->title }}
                        </h3>
                        <p class="font-body-sm text-xs text-on-surface-variant flex items-center gap-1 mt-0.5">
                            <span class="material-symbols-outlined text-[15px] text-outline">person</span> 
                            {{ $activeExam->subject->name ?? 'Mata Pelajaran' }} • {{ $activeExam->teacher->name ?? 'Guru Pengampu' }}
                        </p>
                    </div>

                    <!-- Exam Meta Grid -->
                    <div class="grid grid-cols-2 gap-2 bg-surface-container-low p-2.5 rounded-xl">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-primary shrink-0">
                                <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span class="text-[10px] text-on-surface-variant leading-none">Jadwal</span>
                                <span class="text-xs text-on-surface font-semibold truncate">Hari ini</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-primary shrink-0">
                                <span class="material-symbols-outlined text-[18px]">quiz</span>
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span class="text-[10px] text-on-surface-variant leading-none">Format</span>
                                <span class="text-xs text-on-surface font-semibold truncate">
                                    {{ $activeExam->total_questions ?? $activeExam->questions()->count() }} Soal • {{ $activeExam->duration_minutes }} Mnt
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    @if($activeExam->my_participant && in_array($activeExam->my_participant->status, ['submitted', 'timed_out']))
                        <a href="{{ route('student.exam.result', $activeExam->id) }}" class="w-full h-11 bg-surface-container-high text-primary hover:bg-surface-container-highest rounded-xl flex items-center justify-center font-label-lg text-sm font-semibold shadow-sm active:scale-[0.98] transition-transform gap-1.5 mt-0.5">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                            <span>Lihat Hasil Ujian</span>
                        </a>
                    @elseif($activeExam->my_participant)
                        <a href="{{ route('student.exam.take', ['exam' => $activeExam->id, 'q' => 1]) }}" class="w-full h-12 bg-primary hover:bg-primary-container text-on-primary rounded-xl flex items-center justify-center font-label-lg text-sm font-semibold shadow-sm active:scale-[0.98] transition-transform gap-2 mt-0.5">
                            <span>Lanjutkan Ujian Sekarang</span>
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                    @else
                        <a href="{{ route('student.exam.show', $activeExam->id) }}" class="w-full h-12 bg-primary hover:bg-primary-container text-on-primary rounded-xl flex items-center justify-center font-label-lg text-sm font-semibold shadow-sm active:scale-[0.98] transition-transform gap-2 mt-0.5">
                            <span>Mulai Kerjakan Ujian</span>
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                    @endif
                </div>
            @endforeach
        @else
            <div class="w-full bg-surface-container-lowest rounded-2xl p-5 shadow-sm text-center flex flex-col items-center justify-center gap-2 border border-surface-container">
                <div class="w-12 h-12 rounded-full bg-surface-container-low flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[24px]">event_available</span>
                </div>
                <h3 class="font-headline-sm text-sm font-bold text-on-surface">Tidak Ada Ujian Aktif</h3>
                <p class="font-body-sm text-xs text-on-surface-variant max-w-[280px]">
                    Belum ada sesi ujian yang sedang dibuka saat ini. Silakan periksa ujian mendatang di bawah.
                </p>
            </div>
        @endif
    </div>

    <!-- Section B: Ujian Mendatang -->
    <div class="w-full flex flex-col gap-2.5">
        <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[20px] text-on-surface-variant">schedule</span>
                <h2 class="font-headline-sm text-sm md:text-base font-bold text-on-surface">Ujian Mendatang</h2>
            </div>
            <span class="font-label-sm text-xs text-on-surface-variant">
                {{ $upcomingExams->count() }} Terjadwal
            </span>
        </div>

        @if($upcomingExams->isNotEmpty())
            @foreach($upcomingExams as $upcomingExam)
                <div class="w-full bg-surface-container-lowest rounded-2xl p-4 shadow-sm flex flex-col gap-2.5 border border-surface-container">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-surface-container text-on-surface font-label-sm text-xs font-medium">
                                {{ \Carbon\Carbon::parse($upcomingExam->exam_date)->translatedFormat('d M Y') }}
                            </span>
                            <span class="font-label-sm text-xs text-on-surface-variant font-medium">
                                {{ $upcomingExam->start_time ?? '08:00' }} - {{ $upcomingExam->end_time ?? '10:00' }}
                            </span>
                        </div>
                        <span class="material-symbols-outlined text-[18px] text-outline">lock</span>
                    </div>
                    
                    <div class="flex flex-col">
                        <h3 class="font-headline-sm text-sm md:text-base font-bold text-on-surface">
                            {{ $upcomingExam->title }}
                        </h3>
                        <p class="font-body-sm text-xs text-on-surface-variant mt-0.5">
                            {{ $upcomingExam->subject->name ?? 'Mata Pelajaran' }} • {{ $upcomingExam->duration_minutes }} Menit • KKM {{ $upcomingExam->passing_grade ?? 75 }}
                        </p>
                    </div>

                    <div class="pt-1 flex items-center justify-between border-t border-surface-container-low">
                        <div class="flex items-center gap-1.5 text-on-surface-variant font-label-sm text-xs">
                            <span class="material-symbols-outlined text-[16px] text-primary">info</span>
                            <span>Siapkan diri sebelum jadwal mulai</span>
                        </div>
                        <a href="{{ route('student.exam.show', $upcomingExam->id) }}" class="h-8 px-3 rounded-lg bg-surface-container-low hover:bg-surface-container text-primary font-label-md text-xs font-semibold flex items-center active:scale-95 transition-all">
                            Detail Ujian
                        </a>
                    </div>
                </div>
            @endforeach
        @else
            <div class="w-full bg-surface-container-lowest rounded-2xl p-4 shadow-sm text-center text-xs text-on-surface-variant border border-surface-container">
                Tidak ada ujian terjadwal dalam waktu dekat.
            </div>
        @endif
    </div>

    <!-- Section C: Hasil Terbaru -->
    <div class="w-full flex flex-col gap-2.5">
        <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[20px] text-secondary" style="font-variation-settings: 'FILL' 1;">workspace_premium</span>
                <h2 class="font-headline-sm text-sm md:text-base font-bold text-on-surface">Hasil Terbaru</h2>
            </div>
            <a href="{{ route('student.history') }}" class="font-label-sm text-xs text-primary font-semibold hover:underline">
                Semua Hasil
            </a>
        </div>

        @if($recentResults->isNotEmpty())
            @foreach($recentResults as $recent)
                <div class="w-full bg-surface-container-lowest rounded-2xl p-4 shadow-sm flex flex-col gap-3 relative overflow-hidden border border-surface-container">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $recent->pass_status === 'pass' ? 'bg-secondary' : 'bg-error' }}"></div>
                    <div class="flex items-start justify-between pl-1">
                        <div class="flex flex-col min-w-0">
                            <div class="flex items-center gap-1.5">
                                @if($recent->pass_status === 'pass')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary-container text-[11px] font-semibold">
                                        <span class="material-symbols-outlined text-[13px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                        Lulus KKM
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-error-container text-error text-[11px] font-semibold">
                                        <span class="material-symbols-outlined text-[13px]" style="font-variation-settings: 'FILL' 1;">cancel</span>
                                        Remedial
                                    </span>
                                @endif
                            </div>
                            <h3 class="font-headline-sm text-sm md:text-base font-bold text-on-surface mt-1 truncate">
                                {{ $recent->exam->title }}
                            </h3>
                            <p class="font-body-sm text-xs text-on-surface-variant">
                                {{ $recent->created_at->translatedFormat('d M Y') }} • {{ $recent->exam->subject->name ?? '-' }}
                            </p>
                        </div>
                        <div class="flex flex-col items-end shrink-0 bg-surface-container-low px-3 py-1.5 rounded-xl">
                            <span class="text-[10px] text-on-surface-variant font-medium">Nilai Akhir</span>
                            <span class="font-headline-sm text-xl font-bold {{ $recent->pass_status === 'pass' ? 'text-secondary' : 'text-error' }} leading-none mt-0.5">
                                {{ round($recent->total_score) }}
                            </span>
                            <span class="text-[10px] text-on-surface-variant mt-0.5">KKM: {{ $recent->exam->passing_grade ?? 75 }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-surface-container-low px-1">
                        <span class="text-xs text-on-surface">
                            Jawaban: <strong class="text-secondary font-semibold">{{ $recent->correct_answers }} Benar</strong>
                            <span class="text-on-surface-variant"> / {{ $recent->wrong_answers }} Salah</span>
                        </span>
                        <a href="{{ route('student.exam.result', $recent->exam_id) }}" class="text-xs text-primary font-semibold flex items-center hover:underline">
                            Ulasan <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                        </a>
                    </div>
                </div>
            @endforeach
        @else
            <div class="w-full bg-surface-container-lowest rounded-2xl p-4 shadow-sm text-center text-xs text-on-surface-variant border border-surface-container">
                Belum ada hasil ujian yang diselesaikan.
            </div>
        @endif
    </div>

    <!-- Section D: Riwayat Singkat / Statistik -->
    <div class="w-full flex flex-col gap-2.5">
        <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[20px] text-on-surface-variant">insights</span>
                <h2 class="font-headline-sm text-sm md:text-base font-bold text-on-surface">Statistik Belajar</h2>
            </div>
            <a href="{{ route('student.history') }}" class="font-label-sm text-xs text-primary font-semibold flex items-center hover:underline">
                Lihat Semua <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-2 gap-2.5 w-full">
            <div class="bg-surface-container-lowest p-3.5 rounded-2xl shadow-sm flex items-center gap-2.5 border border-surface-container">
                <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center text-primary shrink-0">
                    <span class="material-symbols-outlined text-[22px]">assignment_turned_in</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-[11px] text-on-surface-variant truncate">Total Selesai</span>
                    <span class="font-headline-sm text-base text-on-surface font-bold">
                        {{ $recentHistory->count() }} Ujian
                    </span>
                </div>
            </div>
            
            <div class="bg-surface-container-lowest p-3.5 rounded-2xl shadow-sm flex items-center gap-2.5 border border-surface-container">
                <div class="w-10 h-10 rounded-xl bg-secondary-container flex items-center justify-center text-secondary shrink-0">
                    <span class="material-symbols-outlined text-[22px]">trending_up</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-[11px] text-on-surface-variant truncate">Rata-rata Nilai</span>
                    <span class="font-headline-sm text-base text-on-surface font-bold">
                        {{ $recentHistory->count() > 0 ? round($recentHistory->avg('total_score'), 1) : '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Motivation / Tips Footer Banner -->
    <div class="w-full bg-surface-container-low rounded-2xl p-3.5 flex items-center justify-between gap-3 shadow-sm border border-surface-container">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-9 h-9 rounded-full bg-primary-fixed flex items-center justify-center text-primary shrink-0">
                <span class="material-symbols-outlined text-[20px]">lightbulb</span>
            </div>
            <div class="flex flex-col min-w-0">
                <span class="text-xs text-on-surface font-semibold truncate">Tips Hari Ini</span>
                <span class="text-[11px] text-on-surface-variant truncate">Periksa kembali opsi ragu-ragu sebelum mengumpulkan!</span>
            </div>
        </div>
    </div>
</div>
@endsection
