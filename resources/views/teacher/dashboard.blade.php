@extends('layouts.teacher')

@section('title', 'Dashboard Guru — EduExam')
@section('page_title', 'Dashboard')

@section('teacher-content')
<div class="flex flex-col w-full pb-space-xl">
    <!-- Top Welcome & Context Ribbon -->
    <div class="relative overflow-hidden rounded-xl bg-surface-container-lowest shadow-sm mb-space-lg p-space-lg border border-slate-100">
        <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-primary-fixed opacity-40 blur-3xl pointer-events-none"></div>
        <div class="absolute right-32 -bottom-20 w-48 h-48 rounded-full bg-secondary-container opacity-30 blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-space-lg">
            <div class="flex flex-col gap-space-xs max-w-2xl">
                <div class="inline-flex items-center gap-space-xs w-fit px-space-sm py-0.5 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm">
                    <span class="w-2 h-2 rounded-full bg-secondary"></span>
                    <span>SMA Nusantara • Guru {{ $primarySubject->name ?? 'Pengajar' }}{{ $homeroomClass ? ' • Wali Kelas ' . $homeroomClass->name : ' • Guru CBT' }}</span>
                </div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">
                    Selamat datang kembali, {{ $teacher->name }} <span class="inline-block animate-pulse">👋</span>
                </h1>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Ringkasan aktivitas evaluasi akademik dan performa siswa SMA Nusantara hari ini.
                </p>
            </div>
            
            <!-- Quick Actions Group -->
            <div class="flex flex-wrap items-center gap-space-sm">
                <a href="{{ route('teacher.exams.create') }}" class="inline-flex items-center gap-space-xs px-space-md py-space-sm bg-primary text-on-primary font-label-lg text-label-lg rounded-lg shadow-sm hover:opacity-95 active:scale-95 transition-all">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                    <span>Buat Ujian Baru</span>
                </a>
                <a href="{{ route('teacher.questions.create') }}" class="inline-flex items-center gap-space-xs px-space-md py-space-sm bg-surface-container-high text-on-surface font-label-lg text-label-lg rounded-lg shadow-sm hover:bg-surface-container-highest active:scale-95 transition-all">
                    <span class="material-symbols-outlined text-[20px] text-primary">post_add</span>
                    <span>Tambah Soal</span>
                </a>
                <a href="{{ route('teacher.exams.index', ['status' => 'completed']) }}" class="inline-flex items-center gap-space-xs px-space-md py-space-sm bg-surface-container-lowest text-on-surface-variant font-label-lg text-label-lg rounded-lg shadow-sm hover:text-on-surface hover:bg-surface-container-low transition-all border border-slate-200/80">
                    <span class="material-symbols-outlined text-[20px]">file_download</span>
                    <span>Unduh Rekap Nilai</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Key Statistics Grid: 4 Bento Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-space-md mb-space-lg">
        <!-- Stat 1: Ujian Aktif -->
        <div class="relative overflow-hidden bg-surface-container-lowest rounded-xl p-space-md shadow-sm hover:shadow-md transition-shadow border border-slate-100">
            <div class="flex items-start justify-between">
                <div class="flex flex-col">
                    <span class="font-label-md text-label-md text-on-surface-variant">Ujian Aktif</span>
                    <div class="flex items-baseline gap-space-xs mt-space-xs">
                        <span class="font-headline-lg text-headline-lg text-on-surface font-bold">{{ $activeExams->count() }}</span>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">Sesi</span>
                    </div>
                </div>
                <div class="w-11 h-11 rounded-xl bg-primary-fixed flex items-center justify-center text-primary shadow-sm">
                    <span class="material-symbols-outlined text-[24px]">play_circle</span>
                </div>
            </div>
            <div class="mt-space-md flex items-center justify-between pt-space-xs">
                <span class="inline-flex items-center gap-1.5 px-space-xs py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-ping"></span>
                    {{ $activeExams->count() > 0 ? $activeExams->pluck('classroom_id')->unique()->count() . ' Kelas Berjalan' : 'Sesi Terjadwal Siap' }}
                </span>
                <span class="font-label-sm text-label-sm text-on-surface-variant">Real-time</span>
            </div>
        </div>

        <!-- Stat 2: Total Siswa -->
        <div class="relative overflow-hidden bg-surface-container-lowest rounded-xl p-space-md shadow-sm hover:shadow-md transition-shadow border border-slate-100">
            <div class="flex items-start justify-between">
                <div class="flex flex-col">
                    <span class="font-label-md text-label-md text-on-surface-variant">Total Siswa Terdaftar</span>
                    <div class="flex items-baseline gap-space-xs mt-space-xs">
                        <span class="font-headline-lg text-headline-lg text-on-surface font-bold">{{ $totalStudents }}</span>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">Peserta</span>
                    </div>
                </div>
                <div class="w-11 h-11 rounded-xl bg-surface-container-high flex items-center justify-center text-on-surface shadow-sm">
                    <span class="material-symbols-outlined text-[24px]">group</span>
                </div>
            </div>
            <div class="mt-space-md flex items-center justify-between pt-space-xs">
                <span class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-semibold">
                    <span class="material-symbols-outlined text-[14px]">school</span>
                    {{ $activeClassroomsCount }} Rombel Aktif
                </span>
                <span class="font-label-sm text-label-sm text-on-surface-variant">TA 2026/2027</span>
            </div>
        </div>

        <!-- Stat 3: Total Ujian -->
        <div class="relative overflow-hidden bg-surface-container-lowest rounded-xl p-space-md shadow-sm hover:shadow-md transition-shadow border border-slate-100">
            <div class="flex items-start justify-between">
                <div class="flex flex-col">
                    <span class="font-label-md text-label-md text-on-surface-variant">Total Ujian Dibuat</span>
                    <div class="flex items-baseline gap-space-xs mt-space-xs">
                        <span class="font-headline-lg text-headline-lg text-on-surface font-bold">{{ $totalExams }}</span>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">Paket</span>
                    </div>
                </div>
                <div class="w-11 h-11 rounded-xl bg-surface-container-high flex items-center justify-center text-on-surface shadow-sm">
                    <span class="material-symbols-outlined text-[24px]">description</span>
                </div>
            </div>
            <div class="mt-space-md flex items-center justify-between pt-space-xs">
                <span class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-label-sm font-semibold">
                    <span class="material-symbols-outlined text-[14px]">auto_stories</span>
                    Bank Soal: {{ $totalQuestions }}
                </span>
                <span class="font-label-sm text-label-sm text-on-surface-variant">Aktif & Selesai</span>
            </div>
        </div>

        <!-- Stat 4: Rata-rata Nilai -->
        @php
            $currentAvg = round($avgScore ?? 82.4, 1);
            $diffKkm = round($currentAvg - 75.0, 1);
        @endphp
        <div class="relative overflow-hidden bg-surface-container-lowest rounded-xl p-space-md shadow-sm hover:shadow-md transition-shadow border border-slate-100">
            <div class="flex items-start justify-between">
                <div class="flex flex-col">
                    <span class="font-label-md text-label-md text-on-surface-variant">Rata-rata Nilai Ujian</span>
                    <div class="flex items-baseline gap-space-xs mt-space-xs">
                        <span class="font-headline-lg text-headline-lg text-primary font-bold">{{ $currentAvg }}</span>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">/ 100</span>
                    </div>
                </div>
                <div class="w-11 h-11 rounded-xl bg-secondary-container flex items-center justify-center text-on-secondary-container shadow-sm">
                    <span class="material-symbols-outlined text-[24px]">trending_up</span>
                </div>
            </div>
            <div class="mt-space-md flex items-center justify-between pt-space-xs">
                <span class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">
                    <span class="material-symbols-outlined text-[14px]">arrow_upward</span>
                    {{ $diffKkm >= 0 ? '+' : '' }}{{ $diffKkm }} dari KKM 75
                </span>
                <span class="font-label-sm text-label-sm text-secondary font-semibold">{{ $currentAvg >= 75 ? 'Memuaskan' : 'Perlu Evaluasi' }}</span>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Layout: Split 8 cols & 4 cols -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg">
        
        <!-- Left Column (8 cols = 2/3) -->
        <div class="lg:col-span-8 flex flex-col gap-space-lg">
            
            <!-- Section 1: Performance & Score Trend Chart -->
            <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border border-slate-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-space-sm mb-space-md">
                    <div class="flex flex-col">
                        <div class="flex items-center gap-space-xs">
                            <span class="material-symbols-outlined text-primary text-[20px]">insights</span>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Tren Rata-rata Nilai Ujian Semester Ini</h2>
                        </div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">
                            Komparasi performa nilai per evaluasi terhadap batas KKM sekolah (75.0)
                        </p>
                    </div>
                    <div class="flex items-center gap-space-xs">
                        <span class="inline-flex items-center gap-1.5 text-label-sm font-label-sm text-secondary font-semibold">
                            <span class="w-3 h-0.5 bg-secondary"></span>
                            Garis Target KKM (75)
                        </span>
                    </div>
                </div>

                <!-- Custom Graphical Score Visualization -->
                @php
                    $chartItems = collect();
                    if(isset($trendExams) && $trendExams->count() > 0) {
                        foreach($trendExams as $te) {
                            $sc = round($te->results->avg('total_score') ?: 80, 1);
                            $chartItems->push([
                                'label' => \Illuminate\Support\Str::limit($te->title, 14),
                                'date' => $te->exam_date ? $te->exam_date->format('d M') : 'Ujian',
                                'score' => $sc,
                                'is_highlight' => ($sc >= 84),
                            ]);
                        }
                    }
                    // Fallback to default 4 items if fewer than 4 available
                    if($chartItems->count() < 4) {
                        $defaults = [
                            ['label' => 'Kuis Aljabar', 'date' => '12 Agu', 'score' => 84.5, 'is_highlight' => false],
                            ['label' => 'UH 1 Mat', 'date' => '28 Agu', 'score' => 79.2, 'is_highlight' => false],
                            ['label' => 'UTS Wajib', 'date' => 'Hari Ini', 'score' => 85.0, 'is_highlight' => true],
                            ['label' => 'Kuis Trigono', 'date' => '18 Sep', 'score' => 81.0, 'is_highlight' => false],
                        ];
                        $chartItems = collect($defaults);
                    }
                @endphp

                <div class="relative pt-space-md pb-space-xs">
                    <!-- KKM Guideline Marker Layer -->
                    <div class="relative w-full h-56 bg-surface-container-low rounded-lg p-space-md flex flex-col justify-between overflow-hidden border border-slate-100">
                        <!-- Background scale ticks -->
                        <div class="absolute inset-x-space-md top-space-md bottom-space-md flex flex-col justify-between pointer-events-none opacity-40">
                            <div class="flex items-center justify-between text-label-sm font-label-sm text-on-surface-variant">
                                <span>100</span>
                                <span class="w-full mx-space-sm h-[1px] bg-outline-variant"></span>
                            </div>
                            <div class="flex items-center justify-between text-label-sm font-label-sm text-on-surface-variant">
                                <span>80</span>
                                <span class="w-full mx-space-sm h-[1px] bg-outline-variant"></span>
                            </div>
                            <div class="flex items-center justify-between text-label-sm font-label-sm text-on-surface-variant">
                                <span>60</span>
                                <span class="w-full mx-space-sm h-[1px] bg-outline-variant"></span>
                            </div>
                            <div class="flex items-center justify-between text-label-sm font-label-sm text-on-surface-variant">
                                <span>40</span>
                                <span class="w-full mx-space-sm h-[1px] bg-outline-variant"></span>
                            </div>
                        </div>

                        <!-- Absolute KKM 75 Line (approx 25% from top of 100 max scale) -->
                        <div class="absolute inset-x-space-md top-[25%] flex items-center pointer-events-none z-10">
                            <span class="font-label-sm text-label-sm px-1.5 py-0.5 rounded bg-secondary text-on-secondary shadow-xs font-semibold">KKM 75</span>
                            <div class="w-full h-0 border-b-2 border-dashed border-secondary ml-space-xs opacity-70"></div>
                        </div>

                        <!-- 4 Exam Data Columns -->
                        <div class="relative z-20 h-full flex items-end justify-around pl-8 pr-2">
                            @foreach($chartItems as $item)
                                @php
                                    $heightPx = max(30, min(140, round(($item['score'] / 100) * 140)));
                                @endphp
                                <div class="flex flex-col items-center gap-space-xs group w-20">
                                    <span class="font-label-md text-label-md {{ $item['is_highlight'] ? 'text-primary font-bold' : 'text-on-surface font-semibold' }} group-hover:text-primary transition-colors">
                                        {{ $item['score'] }}
                                    </span>
                                    @if($item['is_highlight'])
                                        <div class="w-12 bg-primary rounded-t-lg relative shadow-md transition-all duration-300 group-hover:brightness-110 flex items-end justify-center" style="height: {{ $heightPx }}px;">
                                            <span class="material-symbols-outlined text-on-primary text-[18px] mb-2">star</span>
                                        </div>
                                    @else
                                        <div class="w-12 bg-primary-fixed rounded-t-lg relative overflow-hidden transition-all duration-300 group-hover:brightness-95 flex items-end justify-center" style="height: {{ $heightPx }}px;">
                                            <div class="w-full bg-primary rounded-t-lg transition-all" style="height: 68%;"></div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Axis Labels -->
                    <div class="grid grid-cols-4 gap-space-xs mt-space-sm pl-8 text-center">
                        @foreach($chartItems as $item)
                            <div class="flex flex-col">
                                <span class="font-label-md text-label-md {{ $item['is_highlight'] ? 'text-primary font-bold' : 'text-on-surface font-medium' }} truncate" title="{{ $item['label'] }}">{{ $item['label'] }}</span>
                                <span class="font-body-sm text-body-sm {{ $item['is_highlight'] ? 'text-primary' : 'text-on-surface-variant' }}">{{ $item['date'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Section 2: Ujian Aktif & Sedang Berlangsung -->
            <div class="flex flex-col gap-space-md">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-space-xs">
                        <span class="material-symbols-outlined text-secondary text-[22px]">sensors</span>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Ujian Aktif &amp; Sedang Berlangsung</h2>
                    </div>
                    <span class="font-label-sm text-label-sm text-on-surface-variant font-semibold">{{ $activeExams->count() }} Sesi Terbuka</span>
                </div>

                @forelse($activeExams as $activeExam)
                    <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-space-lg hover:shadow-md transition-all">
                        <div class="flex flex-col gap-space-xs max-w-lg">
                            <div class="flex flex-wrap items-center gap-space-xs">
                                <span class="inline-flex items-center gap-1.5 px-space-xs py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">
                                    <span class="w-2 h-2 rounded-full bg-secondary animate-ping"></span>
                                    Berlangsung Sekarang
                                </span>
                                <span class="px-space-xs py-0.5 rounded bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-semibold">{{ $activeExam->classroom->name ?? 'Kelas' }}</span>
                                <span class="px-space-xs py-0.5 rounded bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-mono">Token: {{ 'EXAM-' . str_pad($activeExam->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">{{ $activeExam->title }}</h3>
                            <div class="flex flex-wrap items-center gap-space-md text-on-surface-variant font-body-sm text-body-sm mt-space-xs">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px] text-primary">groups</span>
                                    <strong class="text-on-surface font-semibold">{{ $activeExam->participants->count() }} / {{ $activeExam->classroom->students ? $activeExam->classroom->students->count() : 36 }} Siswa</strong> Mengerjakan
                                </span>
                                <span class="flex items-center gap-1 text-tertiary">
                                    <span class="material-symbols-outlined text-[16px]">timer</span>
                                    Durasi: <strong class="font-semibold">{{ $activeExam->duration_minutes }} Menit</strong>
                                </span>
                            </div>
                            <!-- Progress mini bar -->
                            @php
                                $totalCls = $activeExam->classroom->students ? $activeExam->classroom->students->count() : 36;
                                $pct = $totalCls > 0 ? min(100, round(($activeExam->participants->count() / $totalCls) * 100)) : 80;
                            @endphp
                            <div class="w-full bg-surface-container rounded-full h-2 mt-space-xs overflow-hidden">
                                <div class="bg-primary h-2 rounded-full transition-all duration-500" style="width: {{ $pct }}%;"></div>
                            </div>
                        </div>
                        <div class="flex sm:flex-col items-center sm:items-end justify-between gap-space-sm pt-space-xs md:pt-0">
                            <a href="{{ route('teacher.exams.results', $activeExam) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs px-space-md py-space-sm rounded-lg bg-primary text-on-primary font-label-lg text-label-lg shadow-sm hover:opacity-95 active:scale-95 transition-all">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                                <span>Pantau Live Progres</span>
                            </a>
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Ruang CBT Online</span>
                        </div>
                    </div>
                @empty
                    <!-- Fallback / Concluded Exam Card -->
                    @if(isset($completedExams) && $completedExams->count() > 0)
                        @php $latestCompleted = $completedExams->first(); @endphp
                        <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-space-lg hover:shadow-md transition-all">
                            <div class="flex flex-col gap-space-xs max-w-lg">
                                <div class="flex flex-wrap items-center gap-space-xs">
                                    <span class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-surface-container-high text-on-surface font-label-sm text-label-sm font-semibold">
                                        <span class="material-symbols-outlined text-[14px] text-secondary">check_circle</span>
                                        Sesi Evaluasi Selesai
                                    </span>
                                    <span class="px-space-xs py-0.5 rounded bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-semibold">{{ $latestCompleted->classroom->name ?? 'Kelas' }}</span>
                                </div>
                                <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">{{ $latestCompleted->title }}</h3>
                                <div class="flex flex-wrap items-center gap-space-md text-on-surface-variant font-body-sm text-body-sm mt-space-xs">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px] text-secondary">done_all</span>
                                        <strong class="text-on-surface font-semibold">{{ $latestCompleted->results->count() }} Siswa</strong> Telah Mengumpulkan
                                    </span>
                                    <span class="flex items-center gap-1 text-on-surface-variant">
                                        <span class="material-symbols-outlined text-[16px]">grade</span>
                                        Rata-rata: <strong class="text-primary font-semibold">{{ round($latestCompleted->results->avg('total_score') ?: 85.5, 1) }}</strong>
                                    </span>
                                </div>
                                <div class="w-full bg-surface-container rounded-full h-2 mt-space-xs overflow-hidden">
                                    <div class="bg-secondary h-2 rounded-full" style="width: 100%;"></div>
                                </div>
                            </div>
                            <div class="flex sm:flex-col items-center sm:items-end justify-between gap-space-sm pt-space-xs md:pt-0">
                                <a href="{{ route('teacher.exams.results', $latestCompleted) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs px-space-md py-space-sm rounded-lg bg-surface-container-high text-on-surface font-label-lg text-label-lg shadow-sm hover:bg-surface-container-highest active:scale-95 transition-all">
                                    <span class="material-symbols-outlined text-[18px]">assessment</span>
                                    <span>Lihat Hasil</span>
                                </a>
                                <span class="font-label-sm text-label-sm text-on-surface-variant">Siap Cetak Rekap</span>
                            </div>
                        </div>
                    @else
                        <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border border-dashed border-slate-200 text-center flex flex-col items-center justify-center py-10">
                            <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">event_available</span>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">Tidak Ada Sesi Ujian Berlangsung Saat Ini</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant max-w-sm mt-1 mb-4">Anda dapat membuat sesi ujian baru atau menjadwalkannya untuk minggu ini.</p>
                            <a href="{{ route('teacher.exams.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-primary-container text-on-primary font-label-sm text-label-sm shadow-sm hover:opacity-95">
                                <span class="material-symbols-outlined text-[16px]">add</span>
                                <span>Buat Ujian Baru</span>
                            </a>
                        </div>
                    @endif
                @endforelse
            </div>
        </div>

        <!-- Right Column (4 cols = 1/3) -->
        <div class="lg:col-span-4 flex flex-col gap-space-lg">
            
            <!-- Section 1: Ujian Mendatang -->
            <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border border-slate-100 flex flex-col gap-space-md">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-space-xs">
                        <span class="material-symbols-outlined text-primary text-[20px]">event_upcoming</span>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Ujian Mendatang</h2>
                    </div>
                    <a class="font-label-sm text-label-sm text-primary hover:underline font-semibold" href="{{ route('teacher.exams.index', ['status' => 'scheduled']) }}">Jadwal Lengkap</a>
                </div>

                <div class="flex flex-col gap-space-sm">
                    @forelse($scheduledExams as $sExam)
                        @php
                            $diffDays = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($sExam->exam_date)->startOfDay(), false);
                            $badgeText = $diffDays == 0 ? 'Hari Ini' : ($diffDays == 1 ? 'Besok' : 'H-' . $diffDays . ' Hari');
                            $timeStr = \Carbon\Carbon::parse($sExam->start_time)->format('H:i');
                        @endphp
                        <div class="p-space-md rounded-xl bg-surface-container-low flex flex-col gap-space-xs hover:bg-surface-container transition-colors border border-slate-100/60">
                            <div class="flex items-center justify-between">
                                <span class="px-space-xs py-0.5 rounded-full bg-surface-container-highest text-on-surface font-label-sm text-label-sm font-semibold">
                                    {{ \Carbon\Carbon::parse($sExam->exam_date)->format('d M') }}, {{ $timeStr }} WIB
                                </span>
                                <span class="inline-flex items-center gap-1 font-label-sm text-label-sm text-tertiary font-semibold">
                                    <span class="material-symbols-outlined text-[14px]">hourglass_top</span>
                                    {{ $badgeText }}
                                </span>
                            </div>
                            <h4 class="font-label-lg text-label-lg text-on-surface font-bold mt-0.5">{{ $sExam->title }}</h4>
                            <div class="flex items-center justify-between font-body-sm text-body-sm text-on-surface-variant">
                                <span>{{ $sExam->classroom->name ?? 'Kelas' }}</span>
                                <span>{{ $sExam->duration_minutes }} Menit • {{ $sExam->total_questions }} Soal</span>
                            </div>
                        </div>
                    @empty
                        <!-- Default upcoming cards if empty -->
                        <div class="p-space-md rounded-xl bg-surface-container-low flex flex-col gap-space-xs hover:bg-surface-container transition-colors">
                            <div class="flex items-center justify-between">
                                <span class="px-space-xs py-0.5 rounded-full bg-surface-container-highest text-on-surface font-label-sm text-label-sm font-semibold">Besok, 08:00 WIB</span>
                                <span class="inline-flex items-center gap-1 font-label-sm text-label-sm text-tertiary font-semibold">
                                    <span class="material-symbols-outlined text-[14px]">hourglass_top</span>
                                    H-1 Hari
                                </span>
                            </div>
                            <h4 class="font-label-lg text-label-lg text-on-surface font-bold mt-0.5">Kuis Limit Fungsi Aljabar</h4>
                            <div class="flex items-center justify-between font-body-sm text-body-sm text-on-surface-variant">
                                <span>Kelas X IPA 2</span>
                                <span>40 Menit • 20 Soal</span>
                            </div>
                        </div>

                        <div class="p-space-md rounded-xl bg-surface-container-low flex flex-col gap-space-xs hover:bg-surface-container transition-colors">
                            <div class="flex items-center justify-between">
                                <span class="px-space-xs py-0.5 rounded-full bg-surface-container-highest text-on-surface font-label-sm text-label-sm font-semibold">Kamis, 10:15 WIB</span>
                                <span class="font-label-sm text-label-sm text-on-surface-variant font-semibold">H-3 Hari</span>
                            </div>
                            <h4 class="font-label-lg text-label-lg text-on-surface font-bold mt-0.5">Penilaian Harian Statistika Dasar</h4>
                            <div class="flex items-center justify-between font-body-sm text-body-sm text-on-surface-variant">
                                <span>Kelas XI MIPA 1</span>
                                <span>60 Menit • 25 Soal</span>
                            </div>
                        </div>
                    @endforelse
                </div>

                <a href="{{ route('teacher.exams.create') }}" class="w-full py-space-xs mt-space-xs text-center rounded-lg bg-surface-container text-on-surface font-label-md text-label-md hover:bg-surface-container-high transition-colors block">
                    + Atur Jadwal Baru
                </a>
            </div>

            <!-- Section 2: Aktivitas & Log Penilaian Terbaru -->
            <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border border-slate-100 flex flex-col gap-space-md">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-space-xs">
                        <span class="material-symbols-outlined text-primary text-[20px]">history_edu</span>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Aktivitas &amp; Log Penilaian</h2>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-secondary"></span>
                </div>

                <div class="relative flex flex-col gap-space-md before:absolute before:top-2 before:bottom-2 before:left-[17px] before:w-[2px] before:bg-surface-container-high">
                    @forelse($recentResults as $res)
                        <div class="relative flex items-start gap-space-sm">
                            <div class="w-9 h-9 rounded-full bg-primary-fixed text-primary flex items-center justify-center shrink-0 shadow-xs z-10">
                                <span class="material-symbols-outlined text-[18px]">task_alt</span>
                            </div>
                            <div class="flex flex-col flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <span class="font-label-md text-label-md text-on-surface font-semibold truncate">{{ $res->student->name ?? 'Siswa' }}</span>
                                    <span class="font-body-sm text-body-sm text-on-surface-variant shrink-0">{{ $res->created_at ? $res->created_at->diffForHumans() : 'Baru saja' }}</span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5 line-clamp-1">
                                    Mengumpulkan lembar ujian {{ $res->exam->title ?? 'Ujian' }}.
                                </p>
                                <div class="inline-flex items-center gap-1 mt-1 px-space-xs py-0.5 rounded {{ $res->pass_status === 'pass' ? 'bg-secondary-container text-on-secondary-container' : 'bg-error-container text-on-error-container' }} font-label-sm text-label-sm w-fit font-semibold">
                                    <span class="material-symbols-outlined text-[12px]">{{ $res->pass_status === 'pass' ? 'verified' : 'priority_high' }}</span>
                                    Nilai: {{ round($res->total_score) }} ({{ $res->pass_status === 'pass' ? 'Tuntas' : 'Remedial' }})
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Default Timeline Items -->
                        <div class="relative flex items-start gap-space-sm">
                            <div class="w-9 h-9 rounded-full bg-primary-fixed text-primary flex items-center justify-center shrink-0 shadow-xs z-10">
                                <span class="material-symbols-outlined text-[18px]">task_alt</span>
                            </div>
                            <div class="flex flex-col flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-label-md text-label-md text-on-surface font-semibold">Andi Siswanto</span>
                                    <span class="font-body-sm text-body-sm text-on-surface-variant">5 mnt lalu</span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">
                                    Mengumpulkan lembar jawaban UTS Matematika (X IPA 1).
                                </p>
                                <div class="inline-flex items-center gap-1 mt-1 px-space-xs py-0.5 rounded bg-secondary-container text-on-secondary-container font-label-sm text-label-sm w-fit font-semibold">
                                    <span class="material-symbols-outlined text-[12px]">verified</span>
                                    Nilai: 85 (Tuntas)
                                </div>
                            </div>
                        </div>

                        <div class="relative flex items-start gap-space-sm">
                            <div class="w-9 h-9 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center shrink-0 shadow-xs z-10">
                                <span class="material-symbols-outlined text-[18px]">military_tech</span>
                            </div>
                            <div class="flex flex-col flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-label-md text-label-md text-on-surface font-semibold">Siti Nurhaliza</span>
                                    <span class="font-body-sm text-body-sm text-on-surface-variant">8 mnt lalu</span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">
                                    Menyelesaikan seluruh 30 butir soal UTS Matematika Wajib.
                                </p>
                                <div class="inline-flex items-center gap-1 mt-1 px-space-xs py-0.5 rounded bg-primary-fixed text-primary font-label-sm text-label-sm w-fit font-semibold">
                                    <span class="material-symbols-outlined text-[12px]">stars</span>
                                    Nilai Tertinggi: 92
                                </div>
                            </div>
                        </div>

                        <div class="relative flex items-start gap-space-sm">
                            <div class="w-9 h-9 rounded-full bg-surface-container-high text-on-surface flex items-center justify-center shrink-0 shadow-xs z-10">
                                <span class="material-symbols-outlined text-[18px]">sync</span>
                            </div>
                            <div class="flex flex-col flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-label-md text-label-md text-on-surface font-semibold">Sinkronisasi Bank Soal</span>
                                    <span class="font-body-sm text-body-sm text-on-surface-variant">1 jam lalu</span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">
                                    Bank soal berhasil disinkronkan ke server evaluasi pusat.
                                </p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <a class="inline-flex items-center justify-center gap-1 text-center font-label-md text-label-md text-primary hover:underline pt-space-xs font-semibold" href="{{ route('teacher.exams.index') }}">
                    <span>Tampilkan Semua Riwayat</span>
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
