@extends('layouts.student')

@section('title', 'Dashboard Siswa — EduExam')
@section('page_title', 'Beranda')

@section('student-content')
<div class="flex flex-col w-full pb-10">

    <!-- ═══ 1. WELCOME SECTION ═══ -->
    <section class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border border-[#E3E8F5] mb-space-lg flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="relative shrink-0">
                @if($student->avatar_url)
                    <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}" class="w-14 h-14 rounded-full object-cover shadow-sm ring-1 ring-primary-container">
                @else
                    <div class="w-14 h-14 rounded-full bg-primary-container text-on-primary font-headline-md text-headline-md flex items-center justify-center shadow-sm select-none">
                        {{ strtoupper(substr($student->name, 0, 2)) }}
                    </div>
                @endif
                <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-500 rounded-full border-2 border-surface-container-lowest" title="Online"></span>
            </div>
            <div class="flex flex-col">
                <div class="flex items-center gap-2">
                    <h1 class="font-headline-lg text-headline-lg text-on-surface">Halo, {{ explode(' ', $student->name)[0] }} 👋</h1>
                </div>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="font-body-md text-body-md text-on-surface-variant font-medium">{{ $classroom ? $classroom->name : 'Siswa' }} • {{ $schoolName ?? \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-outline-variant"></span>
                    <span class="font-label-sm text-label-sm text-primary font-medium tracking-wide">NISN {{ $student->nisn ?? '-' }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#ECFDF5] text-[#047857] text-label-md font-label-md border border-[#A7F3D0]/60 shadow-[0_1px_2px_rgba(0,0,0,0.03)]">
                <span class="material-symbols-outlined text-[18px] text-[#059669]" style="font-variation-settings: 'FILL' 1;">verified</span>
                <span>Terdaftar Ujian Semester (UTS/UAS)</span>
            </div>
        </div>
    </section>

    <!-- ═══ 2. ANNOUNCEMENT BANNER ═══ -->
    @if($activeExams->isNotEmpty())
        @php $highlightExam = $activeExams->first(); @endphp
        <section class="bg-[#EEF2FF] border border-[#C7D2FE] rounded-xl p-space-md mb-space-lg flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
            <div class="flex items-start sm:items-center gap-3.5 min-w-0">
                <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary shrink-0 shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">notifications_active</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="font-headline-sm text-headline-sm text-[#1E1B4B]">Ujian Telah Dimulai!</span>
                        <span class="w-2 h-2 rounded-full bg-primary-container animate-pulse"></span>
                    </div>
                    <p class="font-body-sm text-body-sm text-[#3730A3] mt-0.5 truncate max-w-2xl">
                        Sesi ujian {{ $highlightExam->title }} untuk {{ $classroom ? $classroom->name : 'Siswa' }} kini telah aktif. Silakan masuk dan mulai mengerjakan!
                    </p>
                </div>
            </div>
            <div class="flex items-center self-end sm:self-center shrink-0">
                <a href="{{ $highlightExam->my_participant ? route('student.exam.take', ['exam' => $highlightExam->id, 'q' => 1]) : route('student.exam.show', $highlightExam->id) }}" class="px-5 py-2.5 rounded-lg bg-primary-container hover:bg-[#3730A3] text-on-primary font-label-lg text-label-lg shadow-sm transition-all duration-150 flex items-center gap-1.5 active:scale-95">
                    <span>Lihat</span>
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>
        </section>
    @elseif(isset($unreadNotificationCount) && $unreadNotificationCount > 0 && isset($latestNotifications) && $latestNotifications->isNotEmpty())
        @php $latestUnread = $latestNotifications->firstWhere('is_read', false) ?? $latestNotifications->first(); @endphp
        @if($latestUnread)
            <section class="bg-[#EEF2FF] border border-[#C7D2FE] rounded-xl p-space-md mb-space-lg flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-start sm:items-center gap-3.5 min-w-0">
                    <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary shrink-0 shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">notifications_active</span>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-headline-sm text-headline-sm text-[#1E1B4B]">{{ $latestUnread->title }}</span>
                            <span class="w-2 h-2 rounded-full bg-primary-container animate-pulse"></span>
                        </div>
                        <p class="font-body-sm text-body-sm text-[#3730A3] mt-0.5 truncate max-w-2xl">
                            {{ $latestUnread->message }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center self-end sm:self-center shrink-0">
                    <button type="button" onclick="toggleNotificationDropdown()" class="px-5 py-2.5 rounded-lg bg-primary-container hover:bg-[#3730A3] text-on-primary font-label-lg text-label-lg shadow-sm transition-all duration-150 flex items-center gap-1.5 active:scale-95">
                        <span>Lihat</span>
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </button>
                </div>
            </section>
        @endif
    @endif

    <!-- ═══ SEARCH STATUS BANNER ═══ -->
    @if(!empty($search))
        <div class="bg-primary/5 border border-primary/20 rounded-xl p-3.5 mb-space-lg flex items-center justify-between gap-3 shadow-sm">
            <div class="flex items-center gap-2.5 text-body-sm text-on-surface">
                <span class="material-symbols-outlined text-[20px] text-primary" style="font-variation-settings: 'FILL' 1;">manage_search</span>
                <span>Menampilkan hasil pencarian untuk: <strong class="text-primary font-semibold">"{{ $search }}"</strong></span>
            </div>
            <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[14px]">close</span>
                <span>Reset Pencarian</span>
            </a>
        </div>
    @endif

    <!-- ═══ 3. MAIN DASHBOARD GRID (66% : 34%) ═══ -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-start">
        
        <!-- ── KOLOM KIRI (Ujian Aktif & Mendatang - ~66%) ── -->
        <div class="lg:col-span-8 flex flex-col">
            
            <!-- A. Bagian Ujian Aktif -->
            <div id="daftar-ujian" class="flex flex-col mb-space-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-primary-container text-[24px]">play_circle</span>
                        <h2 class="font-headline-md text-headline-md text-on-surface">Ujian Aktif</h2>
                        <span class="px-2.5 py-0.5 rounded-full bg-[#EEF2FF] text-[#4338CA] font-label-sm text-label-sm font-semibold border border-[#E0E7FF]">
                            {{ $activeExams->count() }} Siap Dikerjakan
                        </span>
                    </div>
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Sesi Semester Ganjil</span>
                </div>

                @if($activeExams->isNotEmpty())
                    @foreach($activeExams as $activeExam)
                        <div class="exam-item-card bg-surface-container-lowest rounded-xl p-5 border border-[#E3E8F5] shadow-sm hover:shadow-md transition-all duration-200 flex flex-col mb-4">
                            <div class="flex items-center justify-between mb-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-label-sm font-label-sm font-bold">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                    SEDANG BERLANGSUNG
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 font-label-sm text-label-sm font-medium">
                                    <span class="material-symbols-outlined text-[14px]">timer</span>
                                    {{ $activeExam->duration_minutes }} Menit
                                </span>
                            </div>

                            <h3 class="font-headline-md text-headline-md text-slate-900 capitalize">
                                {{ $activeExam->title }}
                            </h3>

                            <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg p-3 my-3 text-body-sm font-body-sm flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div class="flex items-center gap-2 text-slate-700">
                                    <span class="font-semibold text-slate-900">{{ $activeExam->subject->name ?? 'Mata Pelajaran' }}</span>
                                    <span class="text-slate-300">•</span>
                                    <span class="text-slate-600">Guru: {{ $activeExam->teacher->name ?? 'Guru Pengampu' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-slate-500 font-medium">
                                    <span>Hari ini</span>
                                    <span class="text-slate-300">•</span>
                                    <span>{{ $activeExam->total_questions ?? $activeExam->questions()->count() }} Soal</span>
                                    <span class="text-slate-300">•</span>
                                    <span>{{ $activeExam->duration_minutes }} Mnt</span>
                                </div>
                            </div>

                            <!-- Tombol Aksi Ujian Aktif -->
                            @if($activeExam->my_participant && in_array($activeExam->my_participant->status, ['submitted', 'timed_out']))
                                <a href="{{ route('student.exam.result', $activeExam->id) }}" class="w-full py-2.5 border border-indigo-300 text-indigo-700 font-label-lg text-label-lg rounded-lg hover:bg-indigo-50 transition-colors flex items-center justify-center gap-2 active:bg-indigo-100">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    <span>Lihat Hasil Ujian</span>
                                </a>
                            @elseif($activeExam->my_participant)
                                <a href="{{ route('student.exam.take', ['exam' => $activeExam->id, 'q' => 1]) }}" class="w-full py-3 bg-primary-container hover:bg-[#3730A3] text-on-primary font-label-lg text-label-lg rounded-lg shadow transition-colors flex items-center justify-center gap-2 active:scale-[0.99]">
                                    <span>Lanjutkan Ujian Sekarang</span>
                                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                </a>
                            @else
                                <a href="{{ route('student.exam.show', $activeExam->id) }}" class="w-full py-3 bg-primary-container hover:bg-[#3730A3] text-on-primary font-label-lg text-label-lg rounded-lg shadow transition-colors flex items-center justify-center gap-2 active:scale-[0.99]">
                                    <span>Mulai Kerjakan Ujian</span>
                                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                </a>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="bg-surface-container-lowest border border-dashed border-slate-200 rounded-xl p-8 text-center flex flex-col items-center justify-center shadow-[0_1px_2px_rgba(0,0,0,0.02)] mb-4">
                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-[24px]">{{ !empty($search) ? 'search_off' : 'event_available' }}</span>
                        </div>
                        <span class="font-headline-sm text-headline-sm text-slate-700 font-semibold">
                            {{ !empty($search) ? 'Ujian aktif tidak ditemukan' : 'Tidak Ada Ujian Aktif' }}
                        </span>
                        <p class="font-body-sm text-body-sm text-slate-400 mt-1">
                            {{ !empty($search) ? 'Tidak ada ujian aktif yang cocok dengan kata kunci "' . $search . '".' : 'Belum ada sesi ujian yang sedang dibuka saat ini.' }}
                        </p>
                        @if(!empty($search))
                            <a href="{{ route('student.dashboard') }}" class="mt-3 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-surface-container text-primary font-label-md text-xs font-semibold hover:bg-surface-container-high transition-colors">
                                <span class="material-symbols-outlined text-[16px]">refresh</span>
                                <span>Tampilkan Semua Ujian</span>
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- B. Bagian Ujian Mendatang -->
            <div class="flex flex-col">
                <div class="flex items-center gap-2.5 mb-4">
                    <span class="material-symbols-outlined text-primary-container text-[22px]">calendar_clock</span>
                    <h2 class="font-headline-sm text-headline-sm text-slate-800">Ujian Mendatang</h2>
                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500 font-label-sm text-label-sm font-medium">
                        {{ $upcomingExams->count() }} Terjadwal
                    </span>
                </div>

                @if($upcomingExams->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($upcomingExams as $upcomingExam)
                            <div class="exam-item-card bg-surface-container-lowest rounded-xl p-4 border border-[#E3E8F5] shadow-sm flex flex-col justify-between gap-3">
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center justify-between">
                                        <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 font-label-sm text-xs font-semibold">
                                             {{ \Carbon\Carbon::parse($upcomingExam->exam_date)->translatedFormat('d M Y') }}
                                        </span>
                                        <span class="material-symbols-outlined text-[18px] text-slate-400">lock</span>
                                    </div>
                                    <h4 class="font-headline-sm text-sm font-bold text-slate-900 mt-1">
                                        {{ $upcomingExam->title }}
                                    </h4>
                                    <p class="font-body-sm text-xs text-slate-500">
                                        {{ $upcomingExam->subject->name ?? 'Mata Pelajaran' }} • {{ $upcomingExam->duration_minutes }} Menit
                                    </p>
                                </div>
                                <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between">
                                    <span class="text-[11px] text-slate-400 font-medium">KKM: {{ $upcomingExam->passing_grade ?? 75 }}</span>
                                    <a href="{{ route('student.exam.show', $upcomingExam->id) }}" class="px-3 py-1 bg-surface-container-low hover:bg-surface-container text-primary font-semibold text-xs rounded-lg transition-colors">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-surface-container-lowest border border-dashed border-slate-200 rounded-xl p-8 text-center flex flex-col items-center justify-center shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-[24px]">{{ !empty($search) ? 'search_off' : 'event_busy' }}</span>
                        </div>
                        <span class="font-headline-sm text-headline-sm text-slate-700 font-semibold">
                            {{ !empty($search) ? 'Ujian terjadwal tidak ditemukan' : 'Belum ada ujian terjadwal' }}
                        </span>
                        <p class="font-body-sm text-body-sm text-slate-400 mt-1">
                            {{ !empty($search) ? 'Tidak ada ujian terjadwal yang cocok dengan kata kunci "' . $search . '".' : 'Jadwal ujian berikutnya akan muncul di sini.' }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- ── KOLOM KANAN (Hasil Terbaru, Statistik, Tips - ~34%) ── -->
        <div class="lg:col-span-4 flex flex-col">
            
            <!-- A. Bagian Hasil Terbaru -->
            <div class="flex flex-col mb-6">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-container text-[20px]">workspace_premium</span>
                        <h2 class="font-headline-sm text-headline-sm text-slate-800">Hasil Terbaru</h2>
                    </div>
                    <a class="text-primary-container hover:text-[#3730A3] font-label-sm text-label-sm font-semibold hover:underline" href="{{ route('student.history') }}">Semua Hasil</a>
                </div>

                @if($recentResults->isNotEmpty())
                    @foreach($recentResults->take(1) as $recent)
                        <div class="bg-surface-container-lowest border-l-4 {{ $recent->pass_status === 'pass' ? 'border-l-emerald-500' : 'border-l-rose-500' }} border border-slate-200 rounded-xl p-4 shadow-sm flex flex-col">
                            <div class="flex items-center justify-between">
                                <span class="{{ $recent->pass_status === 'pass' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200' }} font-label-sm text-label-sm font-bold px-2.5 py-0.5 rounded-md border">
                                    {{ $recent->pass_status === 'pass' ? 'Lulus' : 'Remedial' }}
                                </span>
                                <span class="font-label-sm text-label-sm text-slate-400">{{ $recent->created_at->translatedFormat('d M Y') }}</span>
                            </div>
                            <h4 class="font-headline-sm text-headline-sm text-slate-800 mt-2 capitalize">
                                {{ $recent->exam->title }}
                            </h4>
                            <span class="font-body-sm text-body-sm text-slate-500 mt-0.5">{{ $recent->exam->subject->name ?? 'Mata Pelajaran' }}</span>
                            
                            <div class="flex items-baseline gap-2 mt-3 py-1.5 px-3 bg-slate-50 rounded-lg border border-slate-100">
                                <span class="{{ $recent->pass_status === 'pass' ? 'text-emerald-700' : 'text-rose-600' }} font-headline-lg text-headline-lg font-bold">Nilai: {{ round($recent->total_score) }}</span>
                                <span class="text-slate-500 font-label-sm text-label-sm font-medium">(KKM: {{ $recent->exam->passing_grade ?? 75 }})</span>
                            </div>
                            
                            <div class="flex items-center gap-1.5 font-label-md text-label-md font-semibold text-slate-600 mt-2.5">
                                <span class="material-symbols-outlined text-[16px] text-emerald-600">check_circle</span>
                                <span>Jawaban: {{ $recent->correct_answers }} Benar / {{ $recent->wrong_answers }} Salah</span>
                            </div>
                            
                            <a class="inline-flex items-center gap-1 text-primary-container font-label-lg text-label-lg font-semibold hover:underline mt-3 pt-2.5 border-t border-slate-100" href="{{ route('student.exam.result', $recent->exam_id) }}">
                                <span>Ulasan</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="bg-surface-container-lowest border border-slate-200 rounded-xl p-4 text-center font-body-sm text-body-sm text-slate-400 shadow-sm">
                        Belum ada hasil ujian yang diselesaikan.
                    </div>
                @endif
            </div>

            <!-- B. Bagian Statistik Belajar -->
            <div class="flex flex-col mb-6">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-container text-[20px]">trending_up</span>
                        <h2 class="font-headline-sm text-headline-sm text-slate-800">Statistik Belajar</h2>
                    </div>
                    <a class="text-primary-container hover:text-[#3730A3] font-label-sm text-label-sm font-semibold hover:underline" href="{{ route('student.history') }}">Lihat Semua</a>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <!-- Mini Card 1 -->
                    <div class="bg-surface-container-lowest p-3.5 rounded-xl border border-slate-200 shadow-sm flex flex-col">
                        <div class="w-8 h-8 rounded-lg bg-[#EEF2FF] text-primary-container flex items-center justify-center mb-2">
                            <span class="material-symbols-outlined text-[18px]">assignment_turned_in</span>
                        </div>
                        <span class="font-label-sm text-label-sm text-slate-500 font-medium">Total Selesai</span>
                        <span class="font-headline-sm text-headline-sm text-slate-800 font-bold mt-0.5">{{ $recentHistory->count() }} Ujian</span>
                    </div>
                    <!-- Mini Card 2 -->
                    <div class="bg-surface-container-lowest p-3.5 rounded-xl border border-slate-200 shadow-sm flex flex-col">
                        <div class="w-8 h-8 rounded-lg bg-[#ECFDF5] text-emerald-600 flex items-center justify-center mb-2">
                            <span class="material-symbols-outlined text-[18px]">query_stats</span>
                        </div>
                        <span class="font-label-sm text-label-sm text-slate-500 font-medium">Rata-rata Nilai</span>
                        <span class="font-headline-sm text-headline-sm text-slate-800 font-bold mt-0.5">
                            {{ $recentHistory->count() > 0 ? round($recentHistory->avg('total_score'), 1) : '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- C. Bagian Tips Hari Ini -->
            <div class="bg-indigo-50/70 border border-indigo-100 rounded-xl p-4 shadow-sm flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                    <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">lightbulb</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-label-lg text-label-lg font-bold text-indigo-950 mb-0.5">Tips Hari Ini</span>
                    <p class="font-body-sm text-body-sm text-indigo-900/80 leading-relaxed">
                        Periksa kembali jawaban yang masih ragu-ragu sebelum mengumpulkan ujian.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const desktopSearch = document.getElementById('student-search-input');
        const mobileSearch = document.getElementById('mobile-search-input');

        function setupLiveFilter(input) {
            if (!input) return;
            input.addEventListener('input', function() {
                const query = this.value.trim().toLowerCase();
                const cards = document.querySelectorAll('.exam-item-card');
                
                cards.forEach(card => {
                    const text = card.textContent.toLowerCase();
                    if (!query || text.includes(query)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }

        setupLiveFilter(desktopSearch);
        setupLiveFilter(mobileSearch);
    });
</script>
@endpush
@endsection
