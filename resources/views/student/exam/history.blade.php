@extends('layouts.student')

@section('title', 'Riwayat Ujian — EduExam')
@section('page_title', 'Riwayat')

@section('student-content')
<div class="flex flex-col w-full pb-16 space-y-6">

    <!-- ═══ 1. BREADCRUMB & PAGE ACTION HEADER ═══ -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-col">
            <div class="flex items-center gap-2 text-on-surface-variant font-label-sm text-label-sm mb-1">
                <a class="hover:text-primary transition-colors" href="{{ route('student.dashboard') }}">Beranda</a>
                <span class="text-outline-variant">/</span>
                <span class="text-primary-container font-label-md">Riwayat</span>
            </div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Riwayat Ujian</h1>
            <p class="font-body-md text-body-md text-on-surface-variant mt-0.5">
                Lihat seluruh perjalanan akademis dan evaluasi hasil ujian yang telah kamu kerjakan.
            </p>
        </div>

        <!-- Right Header Actions (Tanpa Tombol Grafik Perkembangan) -->
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-surface-container-lowest text-on-surface font-label-lg text-label-lg shadow-sm hover:bg-surface-container transition-all active:scale-[0.98] cursor-pointer" type="button">
                <span class="material-symbols-outlined text-[18px] text-primary-container">file_download</span>
                <span>Unduh Rekap Nilai</span>
            </button>
        </div>
    </div>

    <!-- ═══ 2. KPI / METRIC CARDS GRID ═══ -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Ujian -->
        <div class="p-6 rounded-xl bg-surface-container-lowest shadow-sm flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-shadow border border-[#E3E8F5]">
            <div class="flex items-center justify-between">
                <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Total Ujian</span>
                <div class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center text-primary-container">
                    <span class="material-symbols-outlined text-[20px]">assignment_turned_in</span>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="font-display-lg text-display-lg text-on-surface font-bold">{{ $totalCount }}</span>
                <span class="font-label-sm text-label-sm text-on-surface-variant">Sesi</span>
            </div>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-primary-container"></span>
                Seluruh ujian yang diikuti
            </p>
        </div>

        <!-- Card 2: Lulus KKM -->
        <div class="p-6 rounded-xl bg-surface-container-lowest shadow-sm flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-shadow border border-[#E3E8F5]">
            <div class="flex items-center justify-between">
                <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Lulus</span>
                <div class="w-10 h-10 rounded-lg bg-[#ECFDF5] flex items-center justify-center text-[#10B981]">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="font-display-lg text-display-lg text-on-surface font-bold">{{ $passedCount }}</span>
                <span class="font-label-sm text-label-sm text-[#10B981] font-semibold bg-[#ECFDF5] px-2 py-0.5 rounded-full">
                    {{ $totalCount > 0 ? round(($passedCount / $totalCount) * 100, 1) : 0 }}%
                </span>
            </div>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-[#10B981]"></span>
                Memenuhi standar KKM
            </p>
        </div>

        <!-- Card 3: Remedial -->
        <div class="p-6 rounded-xl bg-surface-container-lowest shadow-sm flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-shadow border border-[#E3E8F5]">
            <div class="flex items-center justify-between">
                <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Remedial</span>
                <div class="w-10 h-10 rounded-lg bg-[#FEF2F2] flex items-center justify-center text-error">
                    <span class="material-symbols-outlined text-[20px]">error_circle_rounded</span>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="font-display-lg text-display-lg text-on-surface font-bold">{{ $failedCount }}</span>
                <span class="font-label-sm text-label-sm text-error font-semibold bg-error-container/40 px-2 py-0.5 rounded-full">
                    {{ $failedCount > 0 ? 'Perlu Ujian Ulang' : 'Tidak Ada Remedial' }}
                </span>
            </div>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-error"></span>
                Perlu perbaikan nilai terdaftar
            </p>
        </div>

        <!-- Card 4: Rata-Rata Nilai -->
        <div class="p-6 rounded-xl bg-surface-container-lowest shadow-sm flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-shadow border border-[#E3E8F5]">
            <div class="flex items-center justify-between">
                <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Rata-rata Nilai</span>
                <div class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center text-primary-container">
                    <span class="material-symbols-outlined text-[20px]">trending_up</span>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="font-display-lg text-display-lg text-on-surface font-bold">{{ $avgScore }}</span>
                <div class="flex items-center text-xs font-semibold {{ $avgScore >= 75 ? 'text-[#10B981] bg-[#ECFDF5]' : 'text-error bg-error-container/40' }} px-1.5 py-0.5 rounded">
                    <span class="material-symbols-outlined text-[14px]">{{ $avgScore >= 75 ? 'arrow_upward' : 'arrow_downward' }}</span>
                    <span>{{ $avgScore >= 75 ? 'Tuntas KKM' : 'Perlu Belajar' }}</span>
                </div>
            </div>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-primary-container"></span>
                Standar KKM 75
            </p>
        </div>
    </div>

    <!-- ═══ 3. SEARCH & FILTER BAR ═══ -->
    <form method="GET" action="{{ route('student.history') }}" id="historyFilterForm" class="p-4 rounded-xl bg-surface-container-lowest shadow-sm border border-[#E3E8F5]">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-center">
            <!-- Search Field -->
            <div class="lg:col-span-5 relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                <input 
                    name="search"
                    value="{{ request('search') }}"
                    class="w-full pl-9 pr-3 py-2 bg-surface-container-low rounded-lg text-on-surface font-body-sm text-body-sm placeholder:text-on-surface-variant focus:outline-none focus:bg-surface-container transition-all" 
                    id="searchInput" 
                    placeholder="Cari nama ujian atau mata pelajaran..." 
                    type="text"
                    autocomplete="off"
                />
            </div>

            <!-- Subject Filter -->
            <div class="lg:col-span-3">
                <div class="relative">
                    <select name="subject_id" onchange="this.form.submit()" class="w-full appearance-none pl-3 pr-8 py-2 bg-surface-container-low rounded-lg text-on-surface font-body-sm text-body-sm focus:outline-none focus:bg-surface-container cursor-pointer transition-all">
                        <option value="">Semua Mata Pelajaran</option>
                        @foreach($subjects as $subj)
                            <option value="{{ $subj->id }}" {{ request('subject_id') == $subj->id ? 'selected' : '' }}>
                                {{ $subj->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-[18px]">expand_more</span>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="lg:col-span-3">
                <div class="relative">
                    <select name="filter" onchange="this.form.submit()" class="w-full appearance-none pl-3 pr-8 py-2 bg-surface-container-low rounded-lg text-on-surface font-body-sm text-body-sm focus:outline-none focus:bg-surface-container cursor-pointer transition-all">
                        <option value="">Semua Status</option>
                        <option value="pass" {{ request('filter') === 'pass' || request('filter') === 'lulus' ? 'selected' : '' }}>Lulus</option>
                        <option value="fail" {{ request('filter') === 'fail' || request('filter') === 'remedial' ? 'selected' : '' }}>Remedial</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-[18px]">expand_more</span>
                </div>
            </div>

            <!-- Reset Filter Button -->
            <div class="lg:col-span-1 flex justify-end">
                <a href="{{ route('student.history') }}" class="p-2 rounded-lg text-on-surface-variant hover:text-primary-container hover:bg-surface-container transition-all flex items-center justify-center cursor-pointer" title="Reset Filter">
                    <span class="material-symbols-outlined text-[20px]">rotate_left</span>
                </a>
            </div>
        </div>
    </form>

    <!-- ═══ 4. EXAM RECORDS TABLE CARD ═══ -->
    <div class="rounded-xl bg-surface-container-lowest shadow-sm overflow-hidden flex flex-col border border-[#E3E8F5]">
        <!-- Table Header Info -->
        <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-surface-container-lowest border-b border-[#E3E8F5]">
            <div class="flex items-center gap-3">
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-semibold">Daftar Riwayat Ujian</h2>
                <span class="px-2.5 py-0.5 rounded-full bg-surface-container text-primary-container font-label-sm text-label-sm font-medium">
                    Menampilkan {{ $results->firstItem() ?? 0 }}–{{ $results->lastItem() ?? 0 }} dari {{ $results->total() }} data
                </span>
            </div>
        </div>

        <!-- Table Responsive Scroller -->
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse" id="historyTable">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase tracking-wider">
                        <th class="py-3 px-6 font-semibold">Nama Ujian</th>
                        <th class="py-3 px-4 font-semibold">Tanggal</th>
                        <th class="py-3 px-4 font-semibold">Durasi</th>
                        <th class="py-3 px-4 font-semibold">Nilai</th>
                        <th class="py-3 px-4 font-semibold">KKM</th>
                        <th class="py-3 px-4 font-semibold">Status</th>
                        <th class="py-3 px-6 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-high/40 text-on-surface font-body-md text-body-md">
                    @forelse($results as $result)
                        @php
                            $isPass = $result->pass_status === 'pass';
                            $kkm = $result->exam->passing_grade ?? 75;
                            $score = round($result->total_score, 1);
                            
                            $subjectName = strtolower($result->exam->subject->name ?? '');
                            $icon = 'assignment';
                            if (str_contains($subjectName, 'matematika')) $icon = 'calculate';
                            elseif (str_contains($subjectName, 'inggris') || str_contains($subjectName, 'bahasa')) $icon = 'translate';
                            elseif (str_contains($subjectName, 'fisika')) $icon = 'science';
                            elseif (str_contains($subjectName, 'biologi')) $icon = 'biotech';
                            elseif (str_contains($subjectName, 'kimia')) $icon = 'experiment';
                            elseif (str_contains($subjectName, 'komputer') || str_contains($subjectName, 'informatika')) $icon = 'computer';
                            elseif (str_contains($subjectName, 'sejarah') || str_contains($subjectName, 'ips')) $icon = 'history_edu';

                            $modalData = [
                                'title' => $result->exam->title,
                                'subject' => $result->exam->subject->name ?? 'Mata Pelajaran',
                                'teacher' => $result->exam->teacher->name ?? 'Guru Pengampu',
                                'date' => $result->created_at->translatedFormat('d M Y'),
                                'duration' => ($result->time_spent_minutes ?? $result->exam->duration_minutes) . ' / ' . $result->exam->duration_minutes . ' Menit',
                                'score' => $score,
                                'kkm' => $kkm,
                                'is_pass' => $isPass,
                                'correct' => $result->correct_answers ?? 0,
                                'wrong' => $result->wrong_answers ?? 0,
                                'unanswered' => $result->unanswered ?? 0,
                                'exam_type' => $result->exam->exam_type ?? 'Ujian',
                                'classroom' => $result->exam->classroom->name ?? 'Kelas',
                                'result_url' => route('student.exam.result', $result->exam_id),
                            ];
                        @endphp
                        <tr class="history-row hover:bg-surface-container-low/60 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-surface-container-high flex items-center justify-center text-primary-container flex-shrink-0">
                                        <span class="material-symbols-outlined text-[18px]">{{ $icon }}</span>
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span class="font-headline-sm text-[14px] text-on-surface font-semibold truncate">{{ $result->exam->title }}</span>
                                        <span class="font-label-sm text-label-sm text-on-surface-variant truncate">
                                            {{ $result->exam->subject->name ?? 'Mata Pelajaran' }} • {{ $result->exam->classroom->name ?? 'Kelas' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-on-surface font-medium whitespace-nowrap">
                                {{ $result->created_at->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-4 px-4 text-on-surface-variant whitespace-nowrap">
                                {{ $result->time_spent_minutes ?? $result->exam->duration_minutes }} Menit
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="font-headline-sm {{ $isPass ? 'text-[#10B981]' : 'text-error' }} font-bold">
                                    {{ $score }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-on-surface-variant whitespace-nowrap font-medium">
                                {{ $kkm }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($isPass)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#10B981]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#10B981]"></span>
                                        Lulus
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#FEF2F2] text-error">
                                        <span class="w-1.5 h-1.5 rounded-full bg-error"></span>
                                        Remedial
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <button 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container text-primary-container font-label-md text-label-md hover:bg-primary-container hover:text-on-primary transition-colors active:scale-95 cursor-pointer" 
                                    onclick='openDetailModal(@json($modalData))' 
                                    type="button"
                                >
                                    <span>Lihat Detail</span>
                                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 px-6 text-center text-on-surface-variant">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-12 h-12 rounded-full bg-surface-container-low flex items-center justify-center text-primary-container mb-1">
                                        <span class="material-symbols-outlined text-[24px]">folder_open</span>
                                    </div>
                                    <span class="font-headline-sm text-base font-semibold text-on-surface">Belum Ada Riwayat Ujian</span>
                                    <p class="text-body-sm text-on-surface-variant max-w-sm">
                                        Tidak ada catatan hasil ujian yang sesuai dengan filter atau kata kunci pencarian.
                                    </p>
                                    @if(request()->hasAny(['search', 'subject_id', 'filter']))
                                        <a href="{{ route('student.history') }}" class="mt-2 inline-flex items-center gap-1.5 px-3 py-1.5 bg-surface-container hover:bg-surface-container-high rounded-lg text-primary text-xs font-semibold transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">refresh</span>
                                            <span>Reset Filter</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Pagination Footer -->
        <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-surface-container-low/40 border-t border-[#E3E8F5]">
            <div class="flex items-center gap-3 text-on-surface-variant font-body-sm text-body-sm">
                <span>Menampilkan {{ $results->firstItem() ?? 0 }}–{{ $results->lastItem() ?? 0 }} dari {{ $results->total() }} data</span>
                <span class="text-outline-variant">•</span>
                <form method="GET" action="{{ route('student.history') }}" class="flex items-center gap-1.5">
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    @if(request('subject_id')) <input type="hidden" name="subject_id" value="{{ request('subject_id') }}"> @endif
                    @if(request('filter')) <input type="hidden" name="filter" value="{{ request('filter') }}"> @endif
                    <span>Baris per halaman:</span>
                    <select name="per_page" onchange="this.form.submit()" class="bg-surface-container-lowest px-2 py-1 rounded text-on-surface font-medium border border-surface-container-high focus:outline-none cursor-pointer">
                        <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ !request('per_page') || request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                    </select>
                </form>
            </div>

            <!-- Pagination Buttons -->
            <div class="flex items-center gap-1 self-center sm:self-auto">
                {{ $results->links() }}
            </div>
        </div>
    </div>

</div>

<!-- ═══ 5. INTERACTIVE DETAIL MODAL ═══ -->
<div aria-labelledby="modalTitle" aria-modal="true" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-on-surface/40 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-200" id="detailModal" role="dialog">
    <div class="bg-surface-container-lowest rounded-xl max-w-2xl w-full p-6 shadow-2xl scale-95 transition-all duration-200 flex flex-col gap-6 max-h-[90vh] overflow-y-auto border border-[#E3E8F5]" id="modalContainer">
        <!-- Modal Header -->
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-lg bg-surface-container-high flex items-center justify-center text-primary-container flex-shrink-0">
                    <span class="material-symbols-outlined text-[22px]">assignment</span>
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center gap-2">
                        <h3 class="font-headline-md text-headline-md text-on-surface font-semibold" id="modalTitle">Detail Hasil Ujian</h3>
                        <span id="modalStatusBadge" class="px-2 py-0.5 rounded-full text-xs font-semibold bg-[#FEF2F2] text-error">Remedial</span>
                    </div>
                    <span id="modalSubtitle" class="font-body-sm text-body-sm text-on-surface-variant">UTS Bahasa Inggris • Sesi Ganjil</span>
                </div>
            </div>
            <button aria-label="Tutup Modal" class="p-1 rounded-lg text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors cursor-pointer" onclick="closeDetailModal()">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Information Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 p-4 rounded-xl bg-surface-container-low">
            <div class="flex flex-col">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase">Mata Pelajaran</span>
                <span id="modalSubject" class="font-headline-sm text-body-md text-on-surface font-semibold mt-0.5">Bahasa Inggris</span>
            </div>
            <div class="flex flex-col">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase">Guru Pengampu</span>
                <span id="modalTeacher" class="font-headline-sm text-body-md text-on-surface font-semibold mt-0.5 truncate">-</span>
            </div>
            <div class="flex flex-col">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase">Tanggal Pengerjaan</span>
                <span id="modalDate" class="font-headline-sm text-body-md text-on-surface font-semibold mt-0.5">-</span>
            </div>
            <div class="flex flex-col">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase">Durasi Dipakai</span>
                <span id="modalDuration" class="font-headline-sm text-body-md text-on-surface font-semibold mt-0.5">-</span>
            </div>
        </div>

        <!-- Score Breakdown Showcase -->
        <div class="p-5 rounded-xl bg-surface-container-lowest shadow-sm border border-[#E3E8F5] flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div id="modalScoreBox" class="flex flex-col items-center justify-center w-24 h-24 rounded-2xl bg-[#FEF2F2]">
                    <span id="modalScore" class="font-display-lg text-[44px] leading-tight text-error font-extrabold">3</span>
                    <span id="modalScoreLabel" class="font-label-sm text-label-sm text-error/80 uppercase font-semibold">Nilai Akhir</span>
                </div>
                <div class="flex flex-col">
                    <span id="modalKkmText" class="font-headline-sm text-headline-sm text-on-surface font-semibold">Kriteria Ketuntasan Minimal: 75</span>
                    <p id="modalKkmDesc" class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Nilai belum mencapai standar kompetensi kelulusan minimum materi.</p>
                    <div class="flex items-center gap-3 mt-3 flex-wrap">
                        <span class="inline-flex items-center gap-1 font-label-md text-label-md text-[#10B981]">
                            <span class="material-symbols-outlined text-[16px]">check_circle</span> <span id="modalCorrect">0</span> Benar
                        </span>
                        <span class="inline-flex items-center gap-1 font-label-md text-label-md text-error">
                            <span class="material-symbols-outlined text-[16px]">cancel</span> <span id="modalWrong">0</span> Salah
                        </span>
                        <span class="inline-flex items-center gap-1 font-label-md text-label-md text-on-surface-variant">
                            <span class="material-symbols-outlined text-[16px]">help</span> <span id="modalUnanswered">0</span> Belum Terjawab
                        </span>
                    </div>
                </div>
            </div>

            <!-- Progress Circular Visual -->
            <div class="flex flex-col items-center flex-shrink-0">
                <div class="relative w-16 h-16 flex items-center justify-center">
                    <svg class="w-full h-full transform -rotate-90" viewbox="0 0 36 36">
                        <path class="text-surface-container-high" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3.5"></path>
                        <path id="modalAccuracyCircle" class="text-error transition-all duration-500" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-dasharray="0, 100" stroke-linecap="round" stroke-width="3.5"></path>
                    </svg>
                    <span id="modalAccuracyText" class="absolute font-label-md text-label-md font-bold text-on-surface">0%</span>
                </div>
                <span class="font-label-sm text-[10px] text-on-surface-variant mt-1">Akurasi Jawaban</span>
            </div>
        </div>

        <!-- Notes / Feedback -->
        <div id="modalEvaluationNote" class="p-4 rounded-xl bg-[#FFFBEB] flex items-start gap-3 border border-amber-200/50">
            <span class="material-symbols-outlined text-[#B45309] text-[20px] flex-shrink-0 mt-0.5">announcement</span>
            <div class="flex flex-col">
                <span class="font-headline-sm text-body-md text-[#92400E] font-semibold">Catatan Evaluasi:</span>
                <p id="modalNoteText" class="font-body-sm text-body-sm text-[#B45309] mt-0.5">
                    Periksa kembali hasil jawaban untuk materi yang belum tuntas agar siap mengikuti ujian berikutnya.
                </p>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-between pt-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-surface-container text-on-surface font-label-md text-label-md hover:bg-surface-container-high transition-colors cursor-pointer" type="button">
                <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                <span>Unduh Lembar Hasil (PDF)</span>
            </button>
            <div class="flex items-center gap-2">
                <button class="px-4 py-2 rounded-lg bg-surface-container text-on-surface font-label-md text-label-md hover:bg-surface-container-high transition-colors cursor-pointer" onclick="closeDetailModal()" type="button">
                    Tutup
                </button>
                <a id="modalDetailLink" href="#" class="px-4 py-2 rounded-lg bg-primary-container text-on-primary font-label-md text-label-md hover:opacity-90 transition-opacity flex items-center gap-1.5 shadow-sm">
                    <span>Lihat Evaluasi Jawaban</span>
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
  // Modal interactions
  const modal = document.getElementById('detailModal');
  const modalContainer = document.getElementById('modalContainer');

  function openDetailModal(data) {
    if (!data) return;
    
    document.getElementById('modalTitle').textContent = data.title || 'Detail Hasil Ujian';
    document.getElementById('modalSubtitle').textContent = (data.subject || '') + ' • ' + (data.classroom || 'Siswa');
    document.getElementById('modalSubject').textContent = data.subject || '-';
    document.getElementById('modalTeacher').textContent = data.teacher || '-';
    document.getElementById('modalDate').textContent = data.date || '-';
    document.getElementById('modalDuration').textContent = data.duration || '-';
    
    const score = data.score !== undefined ? data.score : 0;
    const kkm = data.kkm || 75;
    const isPass = data.is_pass;
    
    document.getElementById('modalScore').textContent = score;
    document.getElementById('modalKkmText').textContent = 'Kriteria Ketuntasan Minimal: ' + kkm;
    
    const scoreBox = document.getElementById('modalScoreBox');
    const statusBadge = document.getElementById('modalStatusBadge');
    const accuracyCircle = document.getElementById('modalAccuracyCircle');
    const kkmDesc = document.getElementById('modalKkmDesc');
    const noteText = document.getElementById('modalNoteText');

    if (isPass) {
        statusBadge.className = 'px-2 py-0.5 rounded-full text-xs font-semibold bg-[#ECFDF5] text-[#10B981]';
        statusBadge.textContent = 'Lulus';
        scoreBox.className = 'flex flex-col items-center justify-center w-24 h-24 rounded-2xl bg-[#ECFDF5]';
        document.getElementById('modalScore').className = 'font-display-lg text-[44px] leading-tight text-[#10B981] font-extrabold';
        accuracyCircle.setAttribute('class', 'text-[#10B981] transition-all duration-500');
        kkmDesc.textContent = 'Selamat! Nilai telah mencapai standar kelulusan minimal.';
        noteText.textContent = 'Hasil memuaskan dan telah mencapai standar kelulusan KKM (' + kkm + '). Pertahankan prestasi ini pada ujian berikutnya!';
    } else {
        statusBadge.className = 'px-2 py-0.5 rounded-full text-xs font-semibold bg-[#FEF2F2] text-error';
        statusBadge.textContent = 'Remedial';
        scoreBox.className = 'flex flex-col items-center justify-center w-24 h-24 rounded-2xl bg-[#FEF2F2]';
        document.getElementById('modalScore').className = 'font-display-lg text-[44px] leading-tight text-error font-extrabold';
        accuracyCircle.setAttribute('class', 'text-error transition-all duration-500');
        kkmDesc.textContent = 'Nilai belum mencapai standar kompetensi kelulusan minimum materi (' + kkm + ').';
        noteText.textContent = 'Nilai belum mencapai KKM (' + kkm + '). Silakan pelajari kembali materi yang belum dikuasai dan hubungi guru pengampu untuk jadwal remedial.';
    }

    const correct = data.correct || 0;
    const wrong = data.wrong || 0;
    const unanswered = data.unanswered || 0;
    const total = correct + wrong + unanswered;
    const accuracy = total > 0 ? Math.round((correct / total) * 100) : (score > 100 ? 100 : Math.round(score));

    document.getElementById('modalCorrect').textContent = correct;
    document.getElementById('modalWrong').textContent = wrong;
    document.getElementById('modalUnanswered').textContent = unanswered;
    document.getElementById('modalAccuracyText').textContent = accuracy + '%';
    accuracyCircle.setAttribute('stroke-dasharray', `${accuracy}, 100`);

    document.getElementById('modalDetailLink').href = data.result_url || '#';

    modal.classList.remove('opacity-0', 'pointer-events-none');
    modal.classList.add('opacity-100', 'pointer-events-auto');
    modalContainer.classList.remove('scale-95');
    modalContainer.classList.add('scale-100');
  }

  function closeDetailModal() {
    modal.classList.add('opacity-0', 'pointer-events-none');
    modal.classList.remove('opacity-100', 'pointer-events-auto');
    modalContainer.classList.add('scale-95');
    modalContainer.classList.remove('scale-100');
  }

  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      closeDetailModal();
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeDetailModal();
    }
  });

  // Client-side instant filter on table while typing
  const searchInput = document.getElementById('searchInput');
  if (searchInput) {
    searchInput.addEventListener('input', function() {
      const q = this.value.trim().toLowerCase();
      const rows = document.querySelectorAll('.history-row');
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = (!q || text.includes(q)) ? '' : 'none';
      });
    });
  }
</script>
@endpush
@endsection
