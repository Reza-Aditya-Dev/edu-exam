@extends('layouts.teacher')

@section('title', 'Dashboard Guru — EduExam')
@section('page_title', 'Dashboard')

@section('teacher-content')
<div class="flex flex-col w-full pb-space-xl">
    
    <!-- Top Welcome & Context Ribbon -->
    <div class="relative overflow-hidden rounded-2xl bg-surface-container-lowest shadow-sm mb-space-lg p-space-lg border border-slate-100">
        <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-primary-fixed opacity-40 blur-3xl pointer-events-none"></div>
        <div class="absolute right-32 -bottom-20 w-48 h-48 rounded-full bg-secondary-container opacity-30 blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-space-lg">
            <div class="flex flex-col gap-space-xs max-w-2xl">
                <div class="inline-flex items-center gap-space-xs w-fit px-3 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm">
                    <span class="w-2 h-2 rounded-full bg-secondary"></span>
                    <span>SMA Nusantara • Guru {{ $primarySubject->name ?? 'Pengampu' }}{{ $homeroomClass ? ' • Wali Kelas ' . $homeroomClass->name : ' • CBT Online' }}</span>
                </div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">
                    Selamat datang, {{ $teacher->name }} <span class="inline-block animate-pulse">👋</span>
                </h1>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Pantau evaluasi akademik siswa secara real-time, tren nilai ujian, dan aktivitas penilaian CBT SMA Nusantara.
                </p>
            </div>
            
            <!-- Quick Actions Group -->
            <div class="flex flex-wrap items-center gap-space-sm">
                <a href="{{ route('teacher.exams.create') }}" class="inline-flex items-center gap-space-xs px-space-md py-space-sm bg-primary text-on-primary font-label-lg text-label-lg rounded-xl shadow-sm hover:opacity-95 active:scale-95 transition-all">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                    <span>Buat Ujian Baru</span>
                </a>
                <a href="{{ route('teacher.questions.create') }}" class="inline-flex items-center gap-space-xs px-space-md py-space-sm bg-surface-container-high text-on-surface font-label-lg text-label-lg rounded-xl shadow-sm hover:bg-surface-container-highest active:scale-95 transition-all">
                    <span class="material-symbols-outlined text-[20px] text-primary">post_add</span>
                    <span>Tambah Soal</span>
                </a>
                <a href="{{ route('teacher.exams.index', ['status' => 'completed']) }}" class="inline-flex items-center gap-space-xs px-space-md py-space-sm bg-surface-container-lowest text-on-surface-variant font-label-lg text-label-lg rounded-xl shadow-sm hover:text-on-surface hover:bg-surface-container-low transition-all border border-slate-200">
                    <span class="material-symbols-outlined text-[20px]">assessment</span>
                    <span>Rekap Hasil Nilai</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Key Statistics Grid: 4 Bento Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-space-md mb-space-lg">
        <!-- Stat 1: Ujian Aktif -->
        <div class="relative overflow-hidden bg-surface-container-lowest rounded-2xl p-space-md shadow-sm hover:shadow-md transition-shadow border border-slate-100 flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="flex flex-col">
                    <span class="font-label-md text-label-md text-on-surface-variant">Ujian Aktif Berjalan</span>
                    <div class="flex items-baseline gap-space-xs mt-space-xs">
                        <span class="font-headline-lg text-headline-lg text-on-surface font-bold">{{ $activeExams->count() }}</span>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">Sesi</span>
                    </div>
                </div>
                <div class="w-11 h-11 rounded-xl bg-primary-fixed flex items-center justify-center text-primary shadow-sm">
                    <span class="material-symbols-outlined text-[24px]">play_circle</span>
                </div>
            </div>
            <div class="mt-space-md flex items-center justify-between pt-space-xs border-t border-slate-50">
                <span class="inline-flex items-center gap-1.5 px-space-xs py-0.5 rounded-full {{ $activeExams->count() > 0 ? 'bg-secondary-container text-on-secondary-container' : 'bg-surface-container text-on-surface-variant' }} font-label-sm text-label-sm font-semibold">
                    @if($activeExams->count() > 0)
                        <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-ping"></span>
                        <span>{{ $activeExams->pluck('classroom_id')->unique()->count() }} Kelas Aktif</span>
                    @else
                        <span>Tidak Ada Sesi</span>
                    @endif
                </span>
                <span class="font-label-sm text-label-sm text-on-surface-variant">Live Monitor</span>
            </div>
        </div>

        <!-- Stat 2: Total Siswa -->
        <div class="relative overflow-hidden bg-surface-container-lowest rounded-2xl p-space-md shadow-sm hover:shadow-md transition-shadow border border-slate-100 flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="flex flex-col">
                    <span class="font-label-md text-label-md text-on-surface-variant">Total Siswa Terdaftar</span>
                    <div class="flex items-baseline gap-space-xs mt-space-xs">
                        <span class="font-headline-lg text-headline-lg text-on-surface font-bold">{{ $totalStudents }}</span>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">Siswa</span>
                    </div>
                </div>
                <div class="w-11 h-11 rounded-xl bg-surface-container-high flex items-center justify-center text-on-surface shadow-sm">
                    <span class="material-symbols-outlined text-[24px]">group</span>
                </div>
            </div>
            <div class="mt-space-md flex items-center justify-between pt-space-xs border-t border-slate-50">
                <span class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-semibold">
                    <span class="material-symbols-outlined text-[14px]">school</span>
                    <span>{{ $activeClassroomsCount }} Rombel Diampu</span>
                </span>
                <span class="font-label-sm text-label-sm text-on-surface-variant">TA Aktif</span>
            </div>
        </div>

        <!-- Stat 3: Total Paket Ujian -->
        <div class="relative overflow-hidden bg-surface-container-lowest rounded-2xl p-space-md shadow-sm hover:shadow-md transition-shadow border border-slate-100 flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="flex flex-col">
                    <span class="font-label-md text-label-md text-on-surface-variant">Total Paket Ujian</span>
                    <div class="flex items-baseline gap-space-xs mt-space-xs">
                        <span class="font-headline-lg text-headline-lg text-on-surface font-bold">{{ $totalExams }}</span>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">Paket</span>
                    </div>
                </div>
                <div class="w-11 h-11 rounded-xl bg-surface-container-high flex items-center justify-center text-on-surface shadow-sm">
                    <span class="material-symbols-outlined text-[24px]">description</span>
                </div>
            </div>
            <div class="mt-space-md flex items-center justify-between pt-space-xs border-t border-slate-50">
                <span class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-label-sm font-semibold">
                    <span class="material-symbols-outlined text-[14px]">quiz</span>
                    <span>{{ $totalQuestions }} Butir Soal</span>
                </span>
                <span class="font-label-sm text-label-sm text-on-surface-variant">{{ $examStatusCounts['completed'] }} Selesai</span>
            </div>
        </div>

        <!-- Stat 4: Rata-rata Nilai & Kelulusan -->
        @php
            $displayAvg = $avgScore !== null ? $avgScore : 0;
            $diffKkm = $avgScore !== null ? round($avgScore - 75.0, 1) : 0;
        @endphp
        <div class="relative overflow-hidden bg-surface-container-lowest rounded-2xl p-space-md shadow-sm hover:shadow-md transition-shadow border border-slate-100 flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="flex flex-col">
                    <span class="font-label-md text-label-md text-on-surface-variant">Rata-rata Nilai Evaluasi</span>
                    <div class="flex items-baseline gap-space-xs mt-space-xs">
                        <span class="font-headline-lg text-headline-lg {{ $displayAvg >= 75 ? 'text-secondary' : ($displayAvg > 0 ? 'text-primary' : 'text-on-surface-variant') }} font-bold">
                            {{ $avgScore !== null ? $displayAvg : '-' }}
                        </span>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">/ 100</span>
                    </div>
                </div>
                <div class="w-11 h-11 rounded-xl {{ $overallPassRate >= 70 ? 'bg-secondary-container text-on-secondary-container' : 'bg-primary-fixed text-primary' }} flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-[24px]">trending_up</span>
                </div>
            </div>
            <div class="mt-space-md flex items-center justify-between pt-space-xs border-t border-slate-50">
                @if($avgScore !== null)
                    <span class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full {{ $diffKkm >= 0 ? 'bg-secondary-container text-on-secondary-container' : 'bg-error-container text-error' }} font-label-sm text-label-sm font-semibold">
                        <span class="material-symbols-outlined text-[14px]">{{ $diffKkm >= 0 ? 'arrow_upward' : 'arrow_downward' }}</span>
                        <span>{{ $diffKkm >= 0 ? '+' : '' }}{{ $diffKkm }} dari KKM 75</span>
                    </span>
                    <span class="font-label-sm text-label-sm text-secondary font-semibold">{{ $overallPassRate }}% Tuntas</span>
                @else
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Menunggu Evaluasi Ujian</span>
                    <span class="font-label-sm text-label-sm text-outline">Target: 75.0</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Dashboard Layout: Split 8 cols & 4 cols -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg">
        
        <!-- Left Column (8 cols = 2/3) -->
        <div class="lg:col-span-8 flex flex-col gap-space-lg">
            
            <!-- Section 1: Interactive Performance & Score Trend Chart (Chart.js) -->
            <div class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-sm border border-slate-100 flex flex-col gap-space-md">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-space-sm pb-space-xs border-b border-slate-100">
                    <div class="flex flex-col">
                        <div class="flex items-center gap-space-xs">
                            <span class="material-symbols-outlined text-primary text-[22px]">insights</span>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Grafik Tren &amp; Analisis Hasil Ujian</h2>
                        </div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">
                            Visualisasi interaktif performa nilai rata-rata dan tingkat kelulusan per paket ujian
                        </p>
                    </div>

                    <!-- Chart Mode Switcher Buttons -->
                    <div class="inline-flex p-1 bg-surface-container rounded-xl shadow-xs self-start sm:self-auto">
                        <button type="button" id="btnChartModeScore" onclick="switchChartMode('score')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-surface-container-lowest text-primary shadow-xs">
                            Rata-rata Nilai
                        </button>
                        <button type="button" id="btnChartModePassRate" onclick="switchChartMode('pass_rate')" class="px-3 py-1.5 rounded-lg text-xs font-medium transition-all text-on-surface-variant hover:text-on-surface">
                            Kelulusan (%)
                        </button>
                    </div>
                </div>

                @if(!$hasRealData)
                    <div class="p-3 bg-primary-fixed/40 rounded-xl border border-primary/20 flex items-start gap-2.5 text-xs text-on-surface">
                        <span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">info</span>
                        <div>
                            <strong>Pratinjau Simulasi Data:</strong> Grafik saat ini menampilkan data contoh simulasi. Begitu ujian pertama Anda selesai dikerjakan oleh siswa, grafik ini otomatis menampilkan data riil secara akurat.
                        </div>
                    </div>
                @endif

                <!-- Dynamic Chart Container -->
                <div class="relative w-full h-72 sm:h-80">
                    <canvas id="teacherTrendChart"></canvas>
                </div>

                <!-- Footer Summary Metric Indicators -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-space-xs pt-space-xs border-t border-slate-100 text-center">
                    <div class="p-2 rounded-xl bg-surface-container-low flex flex-col">
                        <span class="text-[11px] text-on-surface-variant font-medium">Paket Diplot</span>
                        <span class="font-headline-sm text-sm font-bold text-on-surface mt-0.5">{{ $trendData->count() }} Ujian</span>
                    </div>
                    <div class="p-2 rounded-xl bg-surface-container-low flex flex-col">
                        <span class="text-[11px] text-on-surface-variant font-medium">Batas KKM Standar</span>
                        <span class="font-headline-sm text-sm font-bold text-secondary mt-0.5">75.0 Poin</span>
                    </div>
                    <div class="p-2 rounded-xl bg-surface-container-low flex flex-col">
                        <span class="text-[11px] text-on-surface-variant font-medium">Kelulusan Rata-rata</span>
                        <span class="font-headline-sm text-sm font-bold text-primary mt-0.5">{{ $overallPassRate }}%</span>
                    </div>
                    <div class="p-2 rounded-xl bg-surface-container-low flex flex-col">
                        <span class="text-[11px] text-on-surface-variant font-medium">Status Evaluasi</span>
                        <span class="font-headline-sm text-sm font-bold {{ $overallPassRate >= 75 ? 'text-secondary' : 'text-tertiary' }} mt-0.5">
                            {{ $overallPassRate >= 75 ? 'Optimal' : ($overallPassRate > 0 ? 'Remedial' : 'Aktif') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Section 2: Ujian Aktif & Sedang Berlangsung (Live Monitor) -->
            <div class="flex flex-col gap-space-md">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-space-xs">
                        <span class="material-symbols-outlined text-secondary text-[22px]">sensors</span>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Ujian Aktif &amp; Sedang Berlangsung</h2>
                    </div>
                    <span class="font-label-sm text-label-sm text-on-surface-variant font-semibold">
                        {{ $activeExams->count() }} Sesi Dibuka
                    </span>
                </div>

                @forelse($activeExams as $activeExam)
                    @php
                        $classroomStudentsCount = $activeExam->classroom?->students ? $activeExam->classroom->students->count() : 0;
                        $participantsCount = $activeExam->participants->count();
                        $submittedCount = $activeExam->participants->whereIn('status', ['submitted', 'timed_out'])->count();
                        $inProgressCount = $activeExam->participants->where('status', 'in_progress')->count();
                        $pctSubmitted = $classroomStudentsCount > 0 ? min(100, round(($submittedCount / $classroomStudentsCount) * 100)) : 0;
                        $examToken = $activeExam->token ?: ('EXAM-' . str_pad($activeExam->id, 3, '0', STR_PAD_LEFT));
                    @endphp
                    <div class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-sm border border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-space-lg hover:shadow-md transition-all">
                        <div class="flex flex-col gap-space-xs max-w-lg flex-1">
                            <div class="flex flex-wrap items-center gap-space-xs">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">
                                    <span class="w-2 h-2 rounded-full bg-secondary animate-ping"></span>
                                    <span>Berlangsung Sekarang</span>
                                </span>
                                <span class="px-2 py-0.5 rounded-lg bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-semibold">
                                    {{ $activeExam->classroom->name ?? 'Kelas' }}
                                </span>
                                <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-mono">
                                    <span>Token: <strong>{{ $examToken }}</strong></span>
                                    <button type="button" onclick="copyToken('{{ $examToken }}')" class="hover:text-primary transition-colors" title="Salin Token">
                                        <span class="material-symbols-outlined text-[14px]">content_copy</span>
                                    </button>
                                </div>
                            </div>

                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold mt-1">
                                {{ $activeExam->title }}
                            </h3>

                            <div class="flex flex-wrap items-center gap-x-space-md gap-y-1 text-on-surface-variant font-body-sm text-body-sm mt-1">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px] text-primary">groups</span>
                                    <strong class="text-on-surface font-semibold">{{ $participantsCount }} / {{ $classroomStudentsCount }} Siswa</strong> Mulai Mengerjakan
                                </span>
                                <span class="flex items-center gap-1 text-secondary">
                                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                    <strong class="font-semibold">{{ $submittedCount }}</strong> Terkumpul
                                </span>
                                <span class="flex items-center gap-1 text-tertiary">
                                    <span class="material-symbols-outlined text-[16px]">timer</span>
                                    Durasi: <strong class="font-semibold">{{ $activeExam->duration_minutes }} Menit</strong>
                                </span>
                            </div>

                            <!-- Progress mini bar -->
                            <div class="w-full bg-surface-container rounded-full h-2.5 mt-space-xs overflow-hidden flex">
                                <div class="bg-secondary h-2.5 transition-all duration-500" style="width: {{ $pctSubmitted }}%;" title="{{ $submittedCount }} Selesai"></div>
                                <div class="bg-primary h-2.5 transition-all duration-500 opacity-60" style="width: {{ $classroomStudentsCount > 0 ? min(100 - $pctSubmitted, round(($inProgressCount / $classroomStudentsCount) * 100)) : 0 }}%;" title="{{ $inProgressCount }} Sedang Mengerjakan"></div>
                            </div>
                            <div class="flex justify-between text-[11px] text-on-surface-variant font-medium mt-0.5">
                                <span>{{ $submittedCount }} Selesai Mengumpulkan</span>
                                <span>{{ $pctSubmitted }}% Progres Rombel</span>
                            </div>
                        </div>

                        <!-- Action Buttons Group -->
                        <div class="flex sm:flex-col items-center sm:items-end justify-between gap-space-sm pt-space-xs md:pt-0 shrink-0">
                            <a href="{{ route('teacher.exams.results', $activeExam) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs px-space-md py-space-sm rounded-xl bg-primary text-on-primary font-label-lg text-label-lg shadow-sm hover:opacity-95 active:scale-95 transition-all">
                                <span class="material-symbols-outlined text-[18px]">monitoring</span>
                                <span>Pantau Live Progres</span>
                            </a>

                            <button type="button" onclick="confirmCompleteExam({{ $activeExam->id }}, '{{ addslashes($activeExam->title) }}')" class="w-full sm:w-auto inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-lg bg-surface-container text-on-surface-variant hover:text-error hover:bg-error-container text-xs font-semibold transition-all">
                                <span class="material-symbols-outlined text-[16px]">stop_circle</span>
                                <span>Tutup &amp; Selesaikan</span>
                            </button>
                        </div>
                    </div>
                @empty
                    <!-- Fallback / Concluded Exam Card -->
                    @if(isset($completedExams) && $completedExams->count() > 0)
                        @php $latestCompleted = $completedExams->first(); @endphp
                        <div class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-sm border border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-space-lg hover:shadow-md transition-all">
                            <div class="flex flex-col gap-space-xs max-w-lg">
                                <div class="flex flex-wrap items-center gap-space-xs">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-surface-container-high text-on-surface font-label-sm text-label-sm font-semibold">
                                        <span class="material-symbols-outlined text-[14px] text-secondary">check_circle</span>
                                        <span>Evaluasi Terbaru Telah Selesai</span>
                                    </span>
                                    <span class="px-2 py-0.5 rounded-lg bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-semibold">
                                        {{ $latestCompleted->classroom->name ?? 'Kelas' }}
                                    </span>
                                </div>
                                <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold mt-1">
                                    {{ $latestCompleted->title }}
                                </h3>
                                <div class="flex flex-wrap items-center gap-space-md text-on-surface-variant font-body-sm text-body-sm mt-space-xs">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px] text-secondary">done_all</span>
                                        <strong class="text-on-surface font-semibold">{{ $latestCompleted->results->count() }} Siswa</strong> Telah Dinilai
                                    </span>
                                    <span class="flex items-center gap-1 text-on-surface-variant">
                                        <span class="material-symbols-outlined text-[16px] text-primary">grade</span>
                                        Rata-rata: <strong class="text-primary font-semibold">{{ round($latestCompleted->results->avg('total_score') ?? 0, 1) }}</strong>
                                    </span>
                                </div>
                                <div class="w-full bg-surface-container rounded-full h-2 mt-space-xs overflow-hidden">
                                    <div class="bg-secondary h-2 rounded-full" style="width: 100%;"></div>
                                </div>
                            </div>
                            <div class="flex sm:flex-col items-center sm:items-end justify-between gap-space-sm pt-space-xs md:pt-0">
                                <a href="{{ route('teacher.exams.results', $latestCompleted) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs px-space-md py-space-sm rounded-xl bg-surface-container-high text-on-surface font-label-lg text-label-lg shadow-sm hover:bg-surface-container-highest active:scale-95 transition-all">
                                    <span class="material-symbols-outlined text-[18px]">assessment</span>
                                    <span>Lihat Rekap Nilai</span>
                                </a>
                                <a href="{{ route('teacher.exams.analytics', $latestCompleted) }}" class="text-xs text-primary font-semibold hover:underline flex items-center gap-1">
                                    <span>Analitik Butir Soal</span>
                                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-sm border border-dashed border-slate-200 text-center flex flex-col items-center justify-center py-10">
                            <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">event_available</span>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">Tidak Ada Sesi Ujian Aktif Saat Ini</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant max-w-sm mt-1 mb-4">Anda dapat membuat sesi ujian baru atau mengaktifkan jadwal yang telah dibuat.</p>
                            <a href="{{ route('teacher.exams.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary text-on-primary font-label-sm text-label-sm shadow-sm hover:opacity-95">
                                <span class="material-symbols-outlined text-[16px]">add</span>
                                <span>Buat Paket Ujian Baru</span>
                            </a>
                        </div>
                    @endif
                @endforelse
            </div>
        </div>

        <!-- Right Column (4 cols = 1/3) -->
        <div class="lg:col-span-4 flex flex-col gap-space-lg">
            
            <!-- Section 3: Distribusi Hasil Evaluasi Siswa (Doughnut Chart) -->
            <div class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-sm border border-slate-100 flex flex-col gap-space-md">
                <div class="flex items-center justify-between pb-space-xs border-b border-slate-100">
                    <div class="flex items-center gap-space-xs">
                        <span class="material-symbols-outlined text-primary text-[20px]">pie_chart</span>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Distribusi Kelulusan</h2>
                    </div>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-surface-container text-on-surface-variant">
                        {{ $passCount + $failCount }} Hasil
                    </span>
                </div>

                <!-- Doughnut Canvas -->
                <div class="relative w-full h-48 flex items-center justify-center">
                    <canvas id="teacherDistributionChart"></canvas>
                </div>

                <!-- Rentang Nilai (Grade Bands) -->
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="p-2.5 rounded-xl bg-surface-container-low flex items-center justify-between border border-slate-100">
                        <span class="text-on-surface-variant font-medium">Predikat A (&ge;85)</span>
                        <span class="font-bold text-secondary">{{ $gradeDistribution['A'] }} Siswa</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-surface-container-low flex items-center justify-between border border-slate-100">
                        <span class="text-on-surface-variant font-medium">Predikat B (75-84)</span>
                        <span class="font-bold text-primary">{{ $gradeDistribution['B'] }} Siswa</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-surface-container-low flex items-center justify-between border border-slate-100">
                        <span class="text-on-surface-variant font-medium">Predikat C (60-74)</span>
                        <span class="font-bold text-tertiary">{{ $gradeDistribution['C'] }} Siswa</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-surface-container-low flex items-center justify-between border border-slate-100">
                        <span class="text-on-surface-variant font-medium">Remedial (&lt;60)</span>
                        <span class="font-bold text-error">{{ $gradeDistribution['D'] }} Siswa</span>
                    </div>
                </div>
            </div>

            <!-- Section 4: Ujian Mendatang (Scheduled Exams) -->
            <div class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-sm border border-slate-100 flex flex-col gap-space-md">
                <div class="flex items-center justify-between pb-space-xs border-b border-slate-100">
                    <div class="flex items-center gap-space-xs">
                        <span class="material-symbols-outlined text-primary text-[20px]">event_upcoming</span>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Ujian Mendatang</h2>
                    </div>
                    <a class="font-label-sm text-label-sm text-primary hover:underline font-semibold" href="{{ route('teacher.exams.index', ['status' => 'scheduled']) }}">Semua Jadwal</a>
                </div>

                <div class="flex flex-col gap-space-sm">
                    @forelse($scheduledExams as $sExam)
                        @php
                            $diffDays = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($sExam->exam_date)->startOfDay(), false);
                            $badgeText = $diffDays == 0 ? 'Hari Ini' : ($diffDays == 1 ? 'Besok' : 'H-' . $diffDays . ' Hari');
                            $timeStr = \Carbon\Carbon::parse($sExam->start_time)->format('H:i');
                        @endphp
                        <div class="p-space-md rounded-xl bg-surface-container-low flex flex-col gap-space-xs hover:bg-surface-container transition-colors border border-slate-100">
                            <div class="flex items-center justify-between">
                                <span class="px-2 py-0.5 rounded-full bg-surface-container-highest text-on-surface font-label-sm text-label-sm font-semibold">
                                    {{ \Carbon\Carbon::parse($sExam->exam_date)->translatedFormat('d M') }}, {{ $timeStr }} WIB
                                </span>
                                <span class="inline-flex items-center gap-1 font-label-sm text-label-sm {{ $diffDays <= 1 ? 'text-primary' : 'text-tertiary' }} font-bold">
                                    <span class="material-symbols-outlined text-[14px]">hourglass_top</span>
                                    <span>{{ $badgeText }}</span>
                                </span>
                            </div>
                            <h4 class="font-label-lg text-label-lg text-on-surface font-bold mt-0.5">{{ $sExam->title }}</h4>
                            <div class="flex items-center justify-between font-body-sm text-body-sm text-on-surface-variant">
                                <span>{{ $sExam->classroom->name ?? 'Kelas' }}</span>
                                <span>{{ $sExam->duration_minutes }} Mnt • {{ $sExam->total_questions }} Soal</span>
                            </div>
                            <div class="pt-2 flex justify-end gap-2 border-t border-slate-200/60 mt-1">
                                <form action="{{ route('teacher.exams.activate', $sExam) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-primary text-on-primary text-xs font-semibold hover:opacity-95 shadow-xs">
                                        <span class="material-symbols-outlined text-[14px]">play_arrow</span>
                                        <span>Buka Ujian Sekarang</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-space-md rounded-xl bg-surface-container-low text-center text-xs text-on-surface-variant flex flex-col items-center py-6">
                            <span class="material-symbols-outlined text-slate-300 text-3xl mb-1">calendar_today</span>
                            <span>Belum ada jadwal ujian mendatang.</span>
                            <a href="{{ route('teacher.exams.create') }}" class="mt-2 text-primary font-semibold hover:underline">+ Jadwalkan Ujian Baru</a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Section 5: Aktivitas & Log Penilaian Terbaru -->
            <div class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-sm border border-slate-100 flex flex-col gap-space-md">
                <div class="flex items-center justify-between pb-space-xs border-b border-slate-100">
                    <div class="flex items-center gap-space-xs">
                        <span class="material-symbols-outlined text-primary text-[20px]">history_edu</span>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Aktivitas Submisi Siswa</h2>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-secondary"></span>
                </div>

                <div class="relative flex flex-col gap-space-md before:absolute before:top-2 before:bottom-2 before:left-[17px] before:w-[2px] before:bg-surface-container-high">
                    @forelse($recentResults as $res)
                        <div class="relative flex items-start gap-space-sm">
                            <div class="w-9 h-9 rounded-full bg-primary-fixed text-primary flex items-center justify-center shrink-0 shadow-xs z-10 font-bold text-xs">
                                {{ strtoupper(substr($res->student->name ?? 'S', 0, 1)) }}
                            </div>
                            <div class="flex flex-col flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <span class="font-label-md text-label-md text-on-surface font-semibold truncate">{{ $res->student->name ?? 'Siswa' }}</span>
                                    <span class="font-body-sm text-[11px] text-on-surface-variant shrink-0">{{ $res->created_at ? $res->created_at->diffForHumans() : 'Baru saja' }}</span>
                                </div>
                                <p class="font-body-sm text-[11px] text-on-surface-variant mt-0.5 line-clamp-1">
                                    Menyelesaikan {{ $res->exam->title ?? 'Ujian' }}.
                                </p>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg {{ $res->pass_status === 'pass' ? 'bg-secondary-container text-on-secondary-container' : 'bg-error-container text-on-error-container' }} font-label-sm text-[10px] font-bold">
                                        <span class="material-symbols-outlined text-[12px]">{{ $res->pass_status === 'pass' ? 'verified' : 'priority_high' }}</span>
                                        <span>Nilai: {{ round($res->total_score) }} ({{ $res->pass_status === 'pass' ? 'Tuntas' : 'Remedial' }})</span>
                                    </span>
                                    <a href="{{ route('teacher.exams.student_detail', ['exam' => $res->exam_id, 'studentId' => $res->student_id]) }}" class="text-[11px] text-primary font-semibold hover:underline">
                                        Lihat Lembar &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-xs text-on-surface-variant">
                            Belum ada submisi ujian dari siswa.
                        </div>
                    @endforelse
                </div>

                <a class="inline-flex items-center justify-center gap-1 text-center font-label-md text-label-md text-primary hover:underline pt-space-xs font-semibold" href="{{ route('teacher.exams.index') }}">
                    <span>Tampilkan Semua Riwayat Ujian</span>
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Tutup Ujian -->
<div id="completeModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="w-full max-w-md bg-surface-container-lowest rounded-2xl shadow-xl border border-slate-100 p-6 flex flex-col gap-4 animate-[scaleIn_0.15s_ease-out]">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-error-container text-error flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[26px]">stop_circle</span>
            </div>
            <div>
                <h3 class="font-headline-sm text-base font-bold text-on-surface">Selesaikan &amp; Tutup Ujian?</h3>
                <p class="font-body-sm text-xs text-on-surface-variant mt-0.5">Sesi pengerjaan siswa akan diakhiri permanen.</p>
            </div>
        </div>
        <p class="text-xs text-on-surface-variant leading-relaxed">
            Apakah Anda yakin ingin menyelesaikan ujian <strong id="completeExamTitle" class="text-on-surface"></strong>? Seluruh siswa yang masih berstatus pengerjaan akan otomatis dikumpulkan dan dinilai sekarang.
        </p>
        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
            <button type="button" onclick="closeCompleteModal()" class="px-4 py-2 rounded-xl bg-surface-container hover:bg-surface-container-high text-xs font-semibold text-on-surface transition-colors">
                Batal
            </button>
            <form id="completeExamForm" method="POST" action="">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl bg-error text-white text-xs font-bold shadow-sm hover:opacity-95 transition-opacity">
                    Ya, Selesaikan Sekarang
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Toast Notifikasi Ringan -->
<div id="dashboardToast" class="fixed bottom-6 right-6 z-50 px-4 py-2.5 rounded-xl bg-on-surface text-surface shadow-xl text-xs font-semibold hidden items-center gap-2 animate-[slideUp_0.2s_ease-out]">
    <span class="material-symbols-outlined text-[18px] text-secondary">check_circle</span>
    <span id="dashboardToastMsg">Teks notifikasi</span>
</div>
@endsection

@push('teacher-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // Data Backend yang Siap Pakai
    const rawTrendData = @json($trendData);
    const gradeDistribution = @json($gradeDistribution);
    const passCount = {{ $passCount }};
    const failCount = {{ $failCount }};

    let trendChartInstance = null;
    let distributionChartInstance = null;
    let currentChartMode = 'score'; // 'score' | 'pass_rate'

    // Format Data untuk Grafik Tren
    const trendLabels = rawTrendData.map(d => d.short_title || d.title);
    const trendDates = rawTrendData.map(d => d.date);
    const scoreValues = rawTrendData.map(d => d.avg_score);
    const kkmValues = rawTrendData.map(d => d.kkm);
    const passRateValues = rawTrendData.map(d => d.pass_rate);

    document.addEventListener('DOMContentLoaded', function () {
        initTrendChart();
        initDistributionChart();
    });

    // Inisialisasi Grafik Tren Rata-rata Nilai
    function initTrendChart() {
        const ctx = document.getElementById('teacherTrendChart');
        if (!ctx) return;

        trendChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: trendLabels,
                datasets: [
                    {
                        type: 'bar',
                        label: 'Nilai Rata-rata Siswa',
                        data: scoreValues,
                        backgroundColor: function(context) {
                            const val = context.raw;
                            return val >= 75 ? 'rgba(79, 70, 229, 0.85)' : 'rgba(239, 68, 68, 0.75)';
                        },
                        hoverBackgroundColor: function(context) {
                            const val = context.raw;
                            return val >= 75 ? 'rgba(67, 56, 202, 1)' : 'rgba(220, 38, 38, 1)';
                        },
                        borderRadius: 8,
                        barPercentage: 0.5,
                        categoryPercentage: 0.7,
                        order: 2
                    },
                    {
                        type: 'line',
                        label: 'Batas KKM Sekolah (75)',
                        data: kkmValues,
                        borderColor: '#059669',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointBackgroundColor: '#059669',
                        pointBorderColor: '#ffffff',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: false,
                        tension: 0.1,
                        order: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: '600' },
                            boxWidth: 12,
                            boxHeight: 12,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0b1c30',
                        titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: 'bold' },
                        bodyFont: { family: "'Inter', sans-serif", size: 11 },
                        padding: 12,
                        cornerRadius: 10,
                        callbacks: {
                            title: function(items) {
                                const idx = items[0].dataIndex;
                                return rawTrendData[idx].title + ' (' + trendDates[idx] + ')';
                            },
                            afterTitle: function(items) {
                                const idx = items[0].dataIndex;
                                return 'Rombel: ' + rawTrendData[idx].classroom;
                            },
                            label: function(item) {
                                if (currentChartMode === 'score') {
                                    if (item.datasetIndex === 0) {
                                        return 'Rata-rata: ' + item.raw + ' Poin';
                                    }
                                    return 'Batas KKM: ' + item.raw;
                                } else {
                                    return 'Tingkat Kelulusan: ' + item.raw + '%';
                                }
                            },
                            footer: function(items) {
                                const idx = items[0].dataIndex;
                                const item = rawTrendData[idx];
                                return 'Total Peserta: ' + item.total_students + ' Siswa (' + item.passed + ' Tuntas, ' + item.failed + ' Remedial)';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: '600' },
                            color: '#464555'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: { color: 'rgba(226, 232, 240, 0.7)' },
                        ticks: {
                            font: { family: "'Inter', sans-serif", size: 11 },
                            color: '#777587',
                            stepSize: 20
                        }
                    }
                },
                onClick: function(e, elements) {
                    if (elements.length > 0) {
                        const idx = elements[0].index;
                        const exam = rawTrendData[idx];
                        if (exam && exam.id > 0) {
                            window.location.href = "{{ url('/guru/ujian') }}/" + exam.id + "/hasil";
                        }
                    }
                }
            }
        });
    }

    // Toggle Mode Grafik antara Skor vs Persentase Kelulusan
    function switchChartMode(mode) {
        if (!trendChartInstance || currentChartMode === mode) return;

        currentChartMode = mode;
        const btnScore = document.getElementById('btnChartModeScore');
        const btnPass = document.getElementById('btnChartModePassRate');

        if (mode === 'score') {
            btnScore.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-surface-container-lowest text-primary shadow-xs";
            btnPass.className = "px-3 py-1.5 rounded-lg text-xs font-medium transition-all text-on-surface-variant hover:text-on-surface";

            trendChartInstance.data.datasets[0].label = 'Nilai Rata-rata Siswa';
            trendChartInstance.data.datasets[0].data = scoreValues;
            trendChartInstance.data.datasets[0].backgroundColor = function(context) {
                const val = context.raw;
                return val >= 75 ? 'rgba(79, 70, 229, 0.85)' : 'rgba(239, 68, 68, 0.75)';
            };
            trendChartInstance.data.datasets[1].hidden = false;
        } else {
            btnPass.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-surface-container-lowest text-primary shadow-xs";
            btnScore.className = "px-3 py-1.5 rounded-lg text-xs font-medium transition-all text-on-surface-variant hover:text-on-surface";

            trendChartInstance.data.datasets[0].label = 'Persentase Kelulusan (%)';
            trendChartInstance.data.datasets[0].data = passRateValues;
            trendChartInstance.data.datasets[0].backgroundColor = function(context) {
                const val = context.raw;
                return val >= 75 ? 'rgba(0, 108, 73, 0.85)' : 'rgba(234, 88, 12, 0.8)';
            };
            trendChartInstance.data.datasets[1].hidden = true; // Sembunyikan garis KKM saat mode persen
        }

        trendChartInstance.update();
    }

    // Inisialisasi Grafik Donat Distribusi Kelulusan
    function initDistributionChart() {
        const ctx = document.getElementById('teacherDistributionChart');
        if (!ctx) return;

        const totalResults = passCount + failCount;
        const hasData = totalResults > 0;

        const dataVals = hasData ? [passCount, failCount] : [1, 0];
        const dataLabels = hasData ? ['Tuntas (≥ KKM)', 'Remedial (< KKM)'] : ['Belum Ada Data Siswa', ''];
        const dataColors = hasData ? ['#006c49', '#ba1a1a'] : ['#e2e8f0', '#ffffff'];

        distributionChartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: dataLabels,
                datasets: [{
                    data: dataVals,
                    backgroundColor: dataColors,
                    hoverBackgroundColor: hasData ? ['#005236', '#93000a'] : ['#cbd5e1', '#ffffff'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: '600' },
                            boxWidth: 10,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        enabled: hasData,
                        callbacks: {
                            label: function(item) {
                                const count = item.raw;
                                const pct = totalResults > 0 ? Math.round((count / totalResults) * 100) : 0;
                                return ' ' + item.label + ': ' + count + ' Siswa (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Salin Token Ujian ke Clipboard
    function copyToken(token) {
        navigator.clipboard.writeText(token).then(() => {
            showToast('Token ' + token + ' berhasil disalin!');
        }).catch(() => {
            showToast('Gagal menyalin token.');
        });
    }

    // Tampilkan Toast Ringan
    function showToast(msg) {
        const toast = document.getElementById('dashboardToast');
        const toastMsg = document.getElementById('dashboardToastMsg');
        if (toast && toastMsg) {
            toastMsg.textContent = msg;
            toast.classList.remove('hidden');
            toast.classList.add('flex');
            setTimeout(() => {
                toast.classList.add('hidden');
                toast.classList.remove('flex');
            }, 2500);
        }
    }

    // Modal Konfirmasi Selesaikan Ujian
    function confirmCompleteExam(examId, title) {
        const modal = document.getElementById('completeModal');
        const form = document.getElementById('completeExamForm');
        const titleEl = document.getElementById('completeExamTitle');

        if (modal && form && titleEl) {
            titleEl.textContent = title;
            form.action = "{{ url('/guru/ujian') }}/" + examId + "/selesai";
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeCompleteModal() {
        const modal = document.getElementById('completeModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
</script>
@endpush
