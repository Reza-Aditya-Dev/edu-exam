@extends('layouts.teacher')

@section('title', 'Pemeriksaan Lembar Jawaban — ' . $student->name . ' — ' . $exam->title)
@section('page_title', 'Pemeriksaan Lembar Jawaban')

@section('teacher-content')
<div class="flex flex-col w-full pb-16">

    <!-- Top Navigation & Action Row -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-space-md mb-space-lg">
        <div class="flex flex-col gap-1">
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-on-surface-variant font-label-sm text-label-sm">
                <a class="hover:text-primary transition-colors no-underline text-on-surface-variant flex items-center gap-1" href="{{ route('teacher.exams.results', $exam) }}">
                    <span class="material-symbols-outlined text-[16px]">fact_check</span>
                    <span>Hasil Ujian</span>
                </a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <a class="hover:text-primary transition-colors no-underline text-on-surface-variant truncate max-w-[200px]" href="{{ route('teacher.exams.results', $exam) }}">
                    {{ $exam->title }}
                </a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-semibold truncate max-w-[220px]">Detail: {{ $student->name }}</span>
            </nav>
            <h1 class="font-headline-md text-headline-md text-on-surface tracking-tight font-bold">Pemeriksaan Lembar Jawaban Siswa</h1>
        </div>

        <div class="flex items-center gap-space-sm flex-wrap">
            <a href="{{ route('teacher.exams.results', $exam) }}" class="inline-flex items-center gap-2 px-space-md py-2.5 rounded-lg bg-surface-container text-on-surface font-label-lg text-label-lg hover:bg-surface-container-high transition-all active:scale-[0.98] no-underline">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                <span>Kembali ke Daftar Hasil</span>
            </a>
            <button class="inline-flex items-center gap-2 px-space-md py-2.5 rounded-lg bg-surface-container-lowest shadow-sm text-on-surface font-label-lg text-label-lg hover:bg-surface-container-high transition-all active:scale-[0.98] border border-slate-200/80 cursor-pointer" onclick="window.print()" type="button">
                <span class="material-symbols-outlined text-[18px] text-primary">print</span>
                <span>Cetak Lembar Jawaban</span>
            </button>
            <a class="inline-flex items-center gap-2 px-space-md py-2.5 rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg shadow-sm hover:opacity-95 transition-all active:scale-[0.98] no-underline font-semibold" href="#catatan-guru">
                <span class="material-symbols-outlined text-[18px]">edit_note</span>
                <span>Beri Catatan Guru</span>
            </a>
        </div>
    </div>

    <!-- Hero Identity & Score Summary Bento -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg mb-space-xl">
        <!-- Student Profile Card (7 cols) -->
        <div class="lg:col-span-7 bg-surface-container-lowest rounded-xl p-4 sm:p-space-lg shadow-sm flex flex-col justify-between relative overflow-hidden border border-slate-100">
            <div class="absolute -right-8 -top-8 w-40 h-40 bg-surface-container rounded-full opacity-60 pointer-events-none"></div>
            
            <div class="flex items-start gap-3 sm:gap-space-md relative z-10">
                <div class="relative shrink-0">
                    <img alt="{{ $student->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl object-cover shadow-sm ring-4 ring-surface-container-low" src="{{ $student->avatar_url }}"/>
                    <span class="absolute -bottom-1 -right-1 p-1 bg-secondary rounded-full text-on-secondary flex items-center justify-center shadow-sm">
                        <span class="material-symbols-outlined text-[14px]">check</span>
                    </span>
                </div>
                <div class="flex flex-col min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="px-2.5 py-0.5 rounded-full bg-surface-container-high text-primary font-label-sm text-label-sm font-semibold">Siswa Terverifikasi</span>
                        <span class="text-on-surface-variant font-label-sm text-label-sm text-xs">• ID #EXAM-{{ $participant->id }}</span>
                    </div>
                    <h2 class="font-headline-sm text-base sm:text-headline-sm text-on-surface mt-1 truncate font-bold">{{ $student->name }}</h2>
                    <div class="flex items-center gap-2 mt-0.5 text-on-surface-variant font-body-sm text-body-sm flex-wrap text-xs">
                        <span class="font-semibold text-on-surface">NIS: {{ $student->nis ?? '-' }}</span>
                        <span>|</span>
                        <span class="px-2 py-0.5 rounded bg-surface-container text-on-surface font-label-sm text-label-sm font-medium">
                            Kelas {{ $exam->classroom->name ?? 'Rombel Siswa' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-space-sm pt-3 sm:pt-space-lg mt-3 sm:mt-space-md bg-surface-container-low/60 rounded-lg p-3 sm:p-space-md relative z-10 border border-slate-200/60">
                <div class="flex flex-col">
                    <span class="font-label-sm text-[11px] sm:text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Mata Ujian</span>
                    <span class="font-label-lg text-xs sm:text-label-lg text-on-surface mt-0.5 truncate font-bold">{{ $exam->subject->name ?? 'Mata Pelajaran' }}</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant text-[11px] sm:text-xs">{{ $exam->formatted_date }}</span>
                </div>
                <div class="flex flex-col border-t sm:border-t-0 pt-2 sm:pt-0 border-slate-200/40">
                    <span class="font-label-sm text-[11px] sm:text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Durasi Selesai</span>
                    <span class="font-label-lg text-xs sm:text-label-lg text-on-surface mt-0.5 font-bold">{{ $participant->time_spent_minutes }} dari {{ $exam->duration_minutes }} Menit</span>
                    @php
                        $durationDiff = $exam->duration_minutes - $participant->time_spent_minutes;
                    @endphp
                    @if($durationDiff > 0)
                        <span class="font-body-sm text-body-sm text-secondary font-medium text-[11px] sm:text-xs">{{ $durationDiff }} Menit Lebih Cepat</span>
                    @else
                        <span class="font-body-sm text-body-sm text-on-surface-variant font-medium text-[11px] sm:text-xs">Sesuai Alokasi Waktu</span>
                    @endif
                </div>
                <div class="flex flex-col border-t sm:border-t-0 pt-2 sm:pt-0 border-slate-200/40">
                    <span class="font-label-sm text-[11px] sm:text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Waktu Submit</span>
                    <span class="font-label-lg text-xs sm:text-label-lg text-on-surface mt-0.5 font-bold">{{ $participant->submitted_at ? $participant->submitted_at->copy()->setTimezone($exam->timezone)->format('H:i:s') . ' ' . $exam->timezone_code : '-' }}</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant text-[11px] sm:text-xs">{{ $participant->is_late_submission ? 'Terlambat Submit' : 'Tepat Waktu' }}</span>
                </div>
            </div>
        </div>

        <!-- Score Summary Card (5 cols) -->
        <div class="lg:col-span-5 bg-surface-container-lowest rounded-xl p-4 sm:p-space-lg shadow-sm flex flex-col justify-between relative overflow-hidden border border-slate-100">
            <div class="flex items-start justify-between flex-wrap gap-2">
                <div class="flex flex-col">
                    <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Pencapaian Siswa</span>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="font-headline-lg text-3xl sm:text-headline-lg text-on-surface font-extrabold tracking-tight">
                            {{ round($result->total_score) }}
                        </span>
                        <span class="text-on-surface-variant font-label-lg text-label-lg">/ 100</span>
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    @if($result->is_disqualified)
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-100 text-rose-800 border border-rose-300 font-label-sm text-label-sm font-bold shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">block</span>
                            <span>DIDISKUALIFIKASI</span>
                        </div>
                    @elseif($result->is_pending_review)
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-800 border border-amber-300 font-label-sm text-label-sm font-bold shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">pending_actions</span>
                            <span>MENUNGGU KOREKSI ESAI</span>
                        </div>
                    @elseif($result->pass_status === 'pass')
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">verified</span>
                            <span>LULUS KKM (Target {{ round($exam->passing_grade) }})</span>
                        </div>
                    @else
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-error-container text-on-error-container font-label-sm text-label-sm font-semibold shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">cancel</span>
                            <span>BELUM LULUS (Target {{ round($exam->passing_grade) }})</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Metrics Breakdown -->
            @php
                $totalQ = count($answers);
                $correctCount = $result->correct_answers;
                $wrongCount = $result->wrong_answers;
                $emptyCount = $result->unanswered ?? max(0, $totalQ - $correctCount - $wrongCount);
                $accuracy = $totalQ > 0 ? round(($correctCount / $totalQ) * 100, 1) : 0;
                $correctPct = $totalQ > 0 ? round(($correctCount / $totalQ) * 100) : 0;
                $wrongPct = $totalQ > 0 ? round(($wrongCount / $totalQ) * 100) : 0;
            @endphp
            <div class="grid grid-cols-3 gap-2 my-space-md">
                <div class="bg-surface-container-low rounded-lg p-2.5 text-center flex flex-col items-center border border-slate-200/50">
                    <div class="flex items-center gap-1 text-secondary mb-0.5">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                        <span class="font-headline-sm text-headline-sm font-bold">{{ $correctCount }}</span>
                    </div>
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Benar</span>
                </div>
                <div class="bg-surface-container-low rounded-lg p-2.5 text-center flex flex-col items-center border border-slate-200/50">
                    <div class="flex items-center gap-1 text-error mb-0.5">
                        <span class="material-symbols-outlined text-[16px]">cancel</span>
                        <span class="font-headline-sm text-headline-sm font-bold">{{ $wrongCount }}</span>
                    </div>
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Salah</span>
                </div>
                <div class="bg-surface-container-low rounded-lg p-2.5 text-center flex flex-col items-center border border-slate-200/50">
                    <div class="flex items-center gap-1 text-on-surface-variant mb-0.5">
                        <span class="material-symbols-outlined text-[16px]">remove_circle_outline</span>
                        <span class="font-headline-sm text-headline-sm font-bold">{{ $emptyCount }}</span>
                    </div>
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Kosong</span>
                </div>
            </div>

            <!-- Progress bar split visualization -->
            <div class="flex flex-col gap-1.5">
                <div class="w-full h-3 rounded-full bg-surface-container-high overflow-hidden flex">
                    <div class="h-full bg-secondary transition-all" style="width: {{ $correctPct }}%" title="Benar {{ $correctPct }}%"></div>
                    <div class="h-full bg-error transition-all" style="width: {{ $wrongPct }}%" title="Salah {{ $wrongPct }}%"></div>
                </div>
                <div class="flex justify-between items-center text-on-surface-variant font-label-sm text-label-sm">
                    <span class="font-semibold text-secondary">Akurasi: {{ $accuracy }}%</span>
                    <span>{{ $totalQ }} Total Soal Ujian</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel Integritas & Tindakan Guru (Anti-Cheat & Penalti) -->
    <div class="bg-surface-container-lowest rounded-xl p-4 sm:p-space-lg shadow-sm border {{ $result->is_disqualified ? 'border-rose-300 bg-rose-50/20' : (($result->violation_count ?? 0) > 0 ? 'border-amber-300 bg-amber-50/20' : 'border-slate-100') }} mb-space-lg">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div class="flex items-start sm:items-center gap-3">
                <div class="w-10 h-10 rounded-xl shrink-0 flex items-center justify-center {{ $result->is_disqualified ? 'bg-rose-100 text-rose-700' : (($result->violation_count ?? 0) > 0 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                    <span class="material-symbols-outlined text-[24px]">
                        {{ $result->is_disqualified ? 'gavel' : (($result->violation_count ?? 0) > 0 ? 'warning' : 'verified_user') }}
                    </span>
                </div>
                <div class="min-w-0">
                    <h3 class="font-headline-sm text-sm sm:text-base font-bold text-on-surface flex items-center gap-2 flex-wrap">
                        <span>Laporan Integritas &amp; Rekam Jejak Pelanggaran</span>
                        @if($result->is_disqualified)
                            <span class="px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[11px] font-bold border border-rose-300">
                                DIDISKUALIFIKASI
                            </span>
                        @elseif(($result->violation_count ?? 0) > 0)
                            <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[11px] font-bold border border-amber-300">
                                {{ $result->violation_count }}x Pelanggaran Terdeteksi
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold border border-emerald-300">
                                Ujian Tertib (0 Pelanggaran)
                            </span>
                        @endif
                    </h3>
                    <p class="font-body-sm text-xs text-on-surface-variant mt-0.5 leading-relaxed">
                        Pencatatan otomatis aktivitas meninggalkan layar ujian, perpindahan tab browser, atau aplikasi eksternal.
                    </p>
                </div>
            </div>

            <!-- Ringkasan Angka Penalti -->
            <div class="grid grid-cols-3 gap-2 w-full lg:w-auto">
                <div class="bg-surface-container-low px-2.5 sm:px-3 py-1.5 rounded-lg border border-slate-200/60 text-center sm:text-right">
                    <span class="text-[10px] uppercase text-on-surface-variant font-semibold block">Nilai Murni</span>
                    <span class="font-bold font-mono text-xs sm:text-sm text-on-surface">{{ round($result->raw_score ?? $result->total_score) }}</span>
                </div>
                <div class="bg-surface-container-low px-2.5 sm:px-3 py-1.5 rounded-lg border border-slate-200/60 text-center sm:text-right">
                    <span class="text-[10px] uppercase text-rose-600 font-semibold block">Penalti</span>
                    <span class="font-bold font-mono text-xs sm:text-sm text-rose-600">-{{ round($result->penalty_score ?? 0) }}</span>
                </div>
                <div class="bg-surface-container px-2.5 sm:px-3.5 py-1.5 rounded-lg border border-slate-200 text-center sm:text-right">
                    <span class="text-[10px] uppercase text-on-surface font-semibold block">Nilai Akhir</span>
                    <span class="font-bold font-mono text-sm sm:text-base {{ $result->is_disqualified ? 'text-rose-700' : 'text-primary' }}">{{ round($result->total_score) }}</span>
                </div>
            </div>
        </div>

        @if($result->is_disqualified)
            <div class="mt-4 p-3.5 rounded-xl bg-rose-100/70 border border-rose-300 text-rose-900 text-xs flex items-start gap-2.5">
                <span class="material-symbols-outlined text-rose-600 shrink-0 text-[18px]">info</span>
                <div class="min-w-0">
                    <span class="font-bold">Alasan Diskualifikasi:</span> {{ $result->disqualification_reason }}
                </div>
            </div>
        @endif

        <!-- Daftar Log Riwayat Pelanggaran -->
        <div class="mt-4">
            <h4 class="font-label-lg text-xs font-bold text-on-surface mb-2 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px] text-slate-500">list_alt</span>
                <span>Riwayat Log Aktivitas Pelanggaran ({{ count($violations) }} Kejadian)</span>
            </h4>

            @if(count($violations) > 0)
                <div class="overflow-x-auto rounded-xl border border-slate-200/70 -mx-1 sm:mx-0">
                    <table class="w-full text-left text-xs min-w-[500px]">
                        <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                            <tr>
                                <th class="p-2.5 pl-3">No</th>
                                <th class="p-2.5">Jenis Kejadian</th>
                                <th class="p-2.5">Waktu ({{ $exam->timezone_code }})</th>
                                <th class="p-2.5">Lama Keluar</th>
                                <th class="p-2.5">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($violations as $vIndex => $violation)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="p-2.5 pl-3 font-mono text-slate-400">{{ $vIndex + 1 }}</td>
                                    <td class="p-2.5">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded font-medium bg-amber-50 text-amber-800 border border-amber-200 text-[11px]">
                                            <span class="material-symbols-outlined text-[13px]">warning</span>
                                            <span>{{ $violation->type_label }}</span>
                                        </span>
                                    </td>
                                    <td class="p-2.5 font-mono text-slate-700">
                                        {{ $violation->occurred_at ? $violation->occurred_at->copy()->setTimezone($exam->timezone)->format('H:i:s') : '-' }}
                                    </td>
                                    <td class="p-2.5 font-mono text-slate-600">
                                        {{ $violation->duration_seconds > 0 ? $violation->duration_seconds . ' detik' : '< 1 detik' }}
                                    </td>
                                    <td class="p-2.5 text-slate-500">
                                        {{ $violation->details ?: 'Terdeteksi beralih dari jendela ujian' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-center text-xs text-slate-500 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-emerald-600 text-[18px]">check_circle</span>
                    <span>Tidak ditemukan catatan pelanggaran. Siswa mengerjakan ujian secara tertib di layar penuh.</span>
                </div>
            @endif
        </div>

        <!-- Tombol Tindakan Guru (Opsi A & Opsi B) -->
        <div class="mt-4 pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <span class="font-label-sm text-xs text-slate-500 font-semibold">Tindakan Khusus Guru untuk Siswa Ini:</span>
            <div class="flex items-center gap-2 flex-wrap">
                <!-- Form Sesuaikan Penalti Nilai -->
                <form action="{{ route('teacher.exams.adjust_penalty', ['exam' => $exam->id, 'studentId' => $student->id]) }}" method="POST" class="inline-flex items-center gap-1.5 sm:gap-2 flex-wrap">
                    @csrf
                    <input type="hidden" name="action_type" value="apply">
                    <div class="flex items-center gap-1 bg-surface-container-low px-2 py-1 rounded-lg border border-slate-200">
                        <span class="text-xs text-slate-500 font-medium">Potong Nilai:</span>
                        <input type="number" name="penalty_score" min="0" max="100" step="1" value="{{ round($result->penalty_score ?? 0) }}" class="w-12 sm:w-14 px-1 py-0.5 text-xs text-center font-bold font-mono border rounded bg-white text-slate-800" required>
                        <span class="text-xs text-slate-500">poin</span>
                    </div>
                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold shadow-sm transition active:scale-95 cursor-pointer">
                        Terapkan
                    </button>
                </form>

                <!-- Tombol Diskualifikasi / Batalkan Diskualifikasi -->
                @if($result->is_disqualified)
                    <form action="{{ route('teacher.exams.adjust_penalty', ['exam' => $exam->id, 'studentId' => $student->id]) }}" method="POST" onsubmit="return confirm('Batalkan status diskualifikasi untuk siswa {{ $student->name }}? Nilai murni akan dikembalikan.');">
                        @csrf
                        <input type="hidden" name="action_type" value="cancel_disqualification">
                        <input type="hidden" name="penalty_score" value="{{ round($result->penalty_score ?? 0) }}">
                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-sm transition active:scale-95 cursor-pointer flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                            <span>Batalkan Diskualifikasi</span>
                        </button>
                    </form>
                @else
                    <button type="button" onclick="openDisqualifyModal()" class="px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold shadow-sm transition active:scale-95 cursor-pointer flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">gavel</span>
                        <span>Diskualifikasi Siswa</span>
                    </button>
                @endif

                <!-- Reset & Izinkan Ujian Kembali -->
                <form action="{{ route('teacher.exams.destroy_result', ['exam' => $exam->id, 'studentId' => $student->id]) }}" method="POST" onsubmit="return confirm('Hapus hasil pengerjaan siswa {{ $student->name }}?\n\nNilai, jawaban, dan rekaman sesi ujian siswa ini akan dihapus bersih sehingga siswa dapat langsung masuk dan mengikuti ujian kembali.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-surface-container-high hover:bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold shadow-sm transition active:scale-95 cursor-pointer flex items-center gap-1" title="Hapus nilai & izinkan ujian ulang">
                        <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                        <span>Reset &amp; Izinkan Ujian Kembali</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Diskualifikasi Siswa -->
    <div id="disqualify-modal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-6 border border-slate-100 flex flex-col">
            <div class="flex items-center gap-3 text-rose-600 mb-3">
                <span class="material-symbols-outlined text-[28px]">gavel</span>
                <h3 class="font-headline font-bold text-base text-slate-900">Diskualifikasi Siswa</h3>
            </div>
            <p class="text-xs text-slate-600 leading-relaxed">
                Tindakan ini akan menetapkan nilai siswa <strong>{{ $student->name }}</strong> menjadi <strong>0 (NOL)</strong> dan mencatat alasan kecurangan ke dalam arsip rapor.
            </p>
            <form action="{{ route('teacher.exams.disqualify_student', ['exam' => $exam->id, 'studentId' => $student->id]) }}" method="POST" class="mt-4 flex flex-col gap-3">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alasan Diskualifikasi:</label>
                    <textarea name="reason" rows="3" required class="w-full text-xs p-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500" placeholder="Contoh: Terbukti berulang kali membuka tab baru dan mencari kunci jawaban saat ujian."></textarea>
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="closeDisqualifyModal()" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow transition active:scale-95">
                        Konfirmasi Diskualifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openDisqualifyModal() {
            const m = document.getElementById('disqualify-modal');
            if (m) m.classList.remove('hidden'), m.classList.add('flex');
        }
        function closeDisqualifyModal() {
            const m = document.getElementById('disqualify-modal');
            if (m) m.classList.add('hidden'), m.classList.remove('flex');
        }
    </script>

    <!-- Main Content Layout: Question Detail Feed + Fast Navigator Palette -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-start">

        <!-- Question Stream (8 cols) -->
        <div class="lg:col-span-8 flex flex-col gap-space-lg">

            <!-- Filter and Tab Bar inside stream -->
            <div class="flex items-center justify-between bg-surface-container-lowest p-space-sm px-space-md rounded-xl shadow-sm border border-slate-100 flex-wrap gap-2">
                <div class="flex items-center gap-2">
                    <button id="filterAllBtn" onclick="filterQuestions('all')" class="px-3 py-1.5 rounded-lg bg-primary-container text-on-primary font-label-sm text-label-sm font-semibold transition-all cursor-pointer shadow-2xs" type="button">
                        Semua Soal ({{ $totalQ }})
                    </button>
                    <button id="filterWrongBtn" onclick="filterQuestions('salah')" class="px-3 py-1.5 rounded-lg bg-surface text-on-surface-variant hover:bg-surface-container font-label-sm text-label-sm font-semibold transition-colors cursor-pointer" type="button">
                        Hanya Salah ({{ $wrongCount }})
                    </button>
                    <button id="filterCorrectBtn" onclick="filterQuestions('benar')" class="px-3 py-1.5 rounded-lg bg-surface text-on-surface-variant hover:bg-surface-container font-label-sm text-label-sm font-semibold transition-colors cursor-pointer" type="button">
                        Hanya Benar ({{ $correctCount }})
                    </button>
                </div>
                <span class="font-label-sm text-label-sm text-on-surface-variant hidden sm:inline">Lembar Jawaban Resmi Siswa</span>
            </div>

            <!-- QUESTIONS LOOP -->
            @php
                $wrongIndices = [];
                $scorePerQ = $totalQ > 0 ? round(100 / $totalQ, 1) : 0;
            @endphp

            @forelse($answers as $index => $answer)
                @php
                    $qNum = $index + 1;
                    $q = $answer->examQuestion->question;
                    $isCorrect = $answer->is_correct;
                    $hasAnswered = !is_null($answer->selected_option_id) || !empty($answer->answer_text);

                    if (!$isCorrect && $hasAnswered) {
                        $wrongIndices[] = $qNum;
                    }

                    $statusType = $isCorrect ? 'benar' : ($hasAnswered ? 'salah' : 'kosong');
                @endphp

                <article 
                    id="soal-{{ $qNum }}" 
                    data-status="{{ $statusType }}" 
                    class="question-item bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden flex flex-col border border-slate-100 transition-all scroll-mt-28"
                >
                    @php
                        $isEssay = $q->type === 'essay';
                        $maxQScore = (float)($q->score ?? $scorePerQ);
                        $obtainedScore = (float)$answer->score_obtained;
                    @endphp

                    <!-- Banner Header -->
                    @if($isEssay)
                        @if($isCorrect === true)
                            <div class="bg-surface-container-low px-space-lg py-3 flex items-center justify-between border-b border-slate-100 flex-wrap gap-2">
                                <div class="flex items-center gap-space-sm flex-wrap">
                                    <span class="px-2.5 py-1 rounded bg-surface-container-highest text-on-surface font-label-lg text-label-lg font-bold">
                                        Soal No. {{ $qNum }} (Esai)
                                    </span>
                                    <span class="font-label-sm text-label-sm text-on-surface-variant">Bobot: {{ $maxQScore }} Poin</span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">
                                        <span class="material-symbols-outlined text-[14px]">check</span>
                                        <span>SELESAI DINILAI ({{ $obtainedScore }}/{{ $maxQScore }})</span>
                                    </span>
                                </div>
                                <div class="flex items-center gap-1 text-secondary font-label-sm text-label-sm font-semibold">
                                    <span class="material-symbols-outlined text-[16px]">verified</span>
                                    <span>Poin Penuh</span>
                                </div>
                            </div>
                        @elseif($isCorrect === false)
                            <div class="bg-error-container/40 px-space-lg py-3 flex items-center justify-between border-b border-rose-100 flex-wrap gap-2">
                                <div class="flex items-center gap-space-sm flex-wrap">
                                    <span class="px-2.5 py-1 rounded bg-surface-container-lowest text-on-surface font-label-lg text-label-lg font-bold">
                                        Soal No. {{ $qNum }} (Esai)
                                    </span>
                                    <span class="font-label-sm text-label-sm text-on-surface-variant">Bobot: {{ $maxQScore }} Poin</span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-error-container text-on-error-container font-label-sm text-label-sm font-semibold">
                                        <span class="material-symbols-outlined text-[14px]">close</span>
                                        <span>DINILAI 0 (0/{{ $maxQScore }})</span>
                                    </span>
                                </div>
                                <div class="flex items-center gap-1 text-error font-label-sm text-label-sm font-semibold">
                                    <span class="material-symbols-outlined text-[16px]">cancel</span>
                                    <span>Tidak Mendapat Poin</span>
                                </div>
                            </div>
                        @elseif($obtainedScore > 0)
                            <div class="bg-amber-50 px-space-lg py-3 flex items-center justify-between border-b border-amber-200 flex-wrap gap-2">
                                <div class="flex items-center gap-space-sm flex-wrap">
                                    <span class="px-2.5 py-1 rounded bg-surface-container-highest text-on-surface font-label-lg text-label-lg font-bold">
                                        Soal No. {{ $qNum }} (Esai)
                                    </span>
                                    <span class="font-label-sm text-label-sm text-on-surface-variant">Bobot: {{ $maxQScore }} Poin</span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-300 font-label-sm text-label-sm font-semibold">
                                        <span class="material-symbols-outlined text-[14px]">edit</span>
                                        <span>SEBAGIAN ({{ $obtainedScore }}/{{ $maxQScore }})</span>
                                    </span>
                                </div>
                                <div class="flex items-center gap-1 text-amber-700 font-label-sm text-label-sm font-semibold">
                                    <span class="material-symbols-outlined text-[16px]">tune</span>
                                    <span>Nilai Sebagian</span>
                                </div>
                            </div>
                        @else
                            <div class="bg-amber-50/70 px-space-lg py-3 flex items-center justify-between border-b border-amber-200 flex-wrap gap-2">
                                <div class="flex items-center gap-space-sm flex-wrap">
                                    <span class="px-2.5 py-1 rounded bg-surface-container-lowest text-on-surface font-label-lg text-label-lg font-bold">
                                        Soal No. {{ $qNum }} (Esai)
                                    </span>
                                    <span class="font-label-sm text-label-sm text-on-surface-variant">Bobot: {{ $maxQScore }} Poin</span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-300 font-label-sm text-label-sm font-bold">
                                        <span class="material-symbols-outlined text-[14px]">pending</span>
                                        <span>PERLU KOREKSI GURU</span>
                                    </span>
                                </div>
                                <div class="flex items-center gap-1 text-amber-700 font-label-sm text-label-sm font-medium">
                                    <span>Belum Dinilai</span>
                                </div>
                            </div>
                        @endif
                    @elseif($isCorrect)
                        <div class="bg-surface-container-low px-space-lg py-3 flex items-center justify-between border-b border-slate-100">
                            <div class="flex items-center gap-space-sm flex-wrap">
                                <span class="px-2.5 py-1 rounded bg-surface-container-highest text-on-surface font-label-lg text-label-lg font-bold">
                                    Soal No. {{ $qNum }}
                                </span>
                                <span class="font-label-sm text-label-sm text-on-surface-variant">Bobot: {{ $maxQScore }} Poin</span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">
                                    <span class="material-symbols-outlined text-[14px]">check</span>
                                    <span>BENAR (+{{ $obtainedScore }})</span>
                                </span>
                            </div>
                            <div class="flex items-center gap-1 text-secondary font-label-sm text-label-sm font-semibold">
                                <span class="material-symbols-outlined text-[16px]">verified</span>
                                <span>Poin Penuh</span>
                            </div>
                        </div>
                    @elseif($hasAnswered)
                        <div class="bg-error-container/40 px-space-lg py-3 flex items-center justify-between border-b border-rose-100">
                            <div class="flex items-center gap-space-sm flex-wrap">
                                <span class="px-2.5 py-1 rounded bg-surface-container-lowest text-on-surface font-label-lg text-label-lg font-bold">
                                    Soal No. {{ $qNum }}
                                </span>
                                <span class="font-label-sm text-label-sm text-on-surface-variant">Bobot: {{ $maxQScore }} Poin</span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-error-container text-on-error-container font-label-sm text-label-sm font-semibold">
                                    <span class="material-symbols-outlined text-[14px]">close</span>
                                    <span>SALAH (0 Poin)</span>
                                </span>
                            </div>
                            <div class="flex items-center gap-1 text-error font-label-sm text-label-sm font-semibold">
                                <span class="material-symbols-outlined text-[16px]">cancel</span>
                                <span>Perlu Evaluasi</span>
                            </div>
                        </div>
                    @else
                        <div class="bg-surface-container px-space-lg py-3 flex items-center justify-between border-b border-slate-200">
                            <div class="flex items-center gap-space-sm flex-wrap">
                                <span class="px-2.5 py-1 rounded bg-surface-container-lowest text-on-surface font-label-lg text-label-lg font-bold">
                                    Soal No. {{ $qNum }}
                                </span>
                                <span class="font-label-sm text-label-sm text-on-surface-variant">Bobot: {{ $maxQScore }} Poin</span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant font-label-sm text-label-sm font-semibold">
                                    <span class="material-symbols-outlined text-[14px]">remove</span>
                                    <span>KOSONG (0 Poin)</span>
                                </span>
                            </div>
                            <div class="flex items-center gap-1 text-outline font-label-sm text-label-sm">
                                <span>Tidak Dijawab</span>
                            </div>
                        </div>
                    @endif

                    <div class="p-space-lg flex flex-col gap-space-md">
                        <!-- Question Text -->
                        <div class="text-on-surface font-body-lg text-body-lg leading-relaxed">
                            {!! nl2br(e($q->question_text)) !!}
                        </div>

                        <!-- Question Image -->
                        @if($q->question_image)
                            <div class="p-2 rounded-xl bg-surface-container-low border border-slate-200/80 inline-block self-start">
                                <img src="{{ asset('storage/' . $q->question_image) }}" alt="Gambar Soal No {{ $qNum }}" class="max-h-72 rounded-lg object-contain">
                            </div>
                        @endif

                        <!-- Options Set (Multiple Choice / True False) -->
                        @if(in_array($q->type, ['multiple_choice', 'true_false']) && $q->options)
                            <div class="grid grid-cols-1 gap-space-sm mt-space-xs">
                                @foreach($q->options as $opt)
                                    @php
                                        $isSelected = $answer->selected_option_id == $opt->id;
                                        $isCorrectOpt = $opt->is_correct;

                                        $boxClass = 'bg-surface opacity-80 border-slate-100';
                                        $badgeLetter = 'bg-surface-container text-on-surface-variant';

                                        if ($isSelected && $isCorrectOpt) {
                                             $boxClass = 'bg-secondary-container/30 border-secondary/40 shadow-xs';
                                            $badgeLetter = 'bg-secondary text-on-secondary font-bold';
                                        } elseif ($isSelected && !$isCorrectOpt) {
                                            $boxClass = 'bg-error-container/25 border-error/40 shadow-xs';
                                            $badgeLetter = 'bg-error text-on-error font-bold';
                                        } elseif ($isCorrectOpt) {
                                            $boxClass = 'bg-secondary-container/20 border-secondary/30';
                                            $badgeLetter = 'bg-secondary text-on-secondary font-bold';
                                        }
                                    @endphp

                                    <div class="flex items-center justify-between p-3.5 rounded-xl border {{ $boxClass }} transition-all">
                                        <div class="flex items-center gap-space-md min-w-0">
                                            <span class="w-8 h-8 rounded-full flex items-center justify-center font-label-md text-label-md shrink-0 {{ $badgeLetter }}">
                                                {{ $opt->label }}
                                            </span>
                                            <span class="font-body-md text-body-md text-on-surface font-medium">
                                                {{ $opt->option_text }}
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-2 shrink-0 ml-3">
                                            @if($isSelected && $isCorrectOpt)
                                                <span class="px-2.5 py-1 rounded bg-surface-container-lowest text-secondary font-label-sm text-label-sm shadow-2xs flex items-center gap-1 font-semibold">
                                                    <span class="material-symbols-outlined text-[16px]">done_all</span>
                                                    <span>Pilihan Siswa &amp; Kunci Resmi</span>
                                                </span>
                                            @elseif($isSelected && !$isCorrectOpt)
                                                <span class="px-2.5 py-1 rounded bg-error-container text-on-error-container font-label-sm text-label-sm flex items-center gap-1 font-semibold">
                                                    <span class="material-symbols-outlined text-[16px]">close</span>
                                                    <span>Jawaban Siswa (Salah)</span>
                                                </span>
                                            @elseif($isCorrectOpt)
                                                <span class="px-2.5 py-1 rounded bg-surface-container-lowest text-secondary font-label-sm text-label-sm shadow-2xs flex items-center gap-1 font-semibold">
                                                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                                    <span>Kunci Jawaban Benar</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($q->type === 'essay')
                            <!-- Essay Answer View & Teacher Grading Panel -->
                            <div class="flex flex-col gap-3">
                                <div class="p-4 rounded-xl bg-surface-container-low border border-slate-200">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Jawaban Teks Siswa:</span>
                                        <span class="text-xs text-on-surface-variant font-medium">
                                            {{ !empty(trim($answer->answer_text ?? '')) ? str_word_count($answer->answer_text) . ' kata' : 'Kosong' }}
                                        </span>
                                    </div>
                                    <div class="p-3.5 rounded-lg bg-surface-container-lowest text-on-surface font-body-md text-body-md leading-relaxed whitespace-pre-wrap border border-slate-200/60">
                                        {{ $answer->answer_text ?: '(Siswa tidak mengisikan teks jawaban)' }}
                                    </div>
                                </div>

                                <!-- Form Penilaian Esai Guru -->
                                <form action="{{ route('teacher.exams.grade_essay', ['exam' => $exam->id, 'studentId' => $student->id]) }}" method="POST" class="p-4 rounded-xl bg-surface-container-lowest border-2 border-primary/20 shadow-xs flex flex-col gap-3" onsubmit="submitEssayGrade(event, this)">
                                    @csrf
                                    <input type="hidden" name="answer_id" value="{{ $answer->id }}">
                                    
                                    <div class="flex items-center justify-between flex-wrap gap-2">
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-primary text-[20px]">grading</span>
                                            <span class="font-label-md text-label-md font-bold text-on-surface">Penilaian Jawaban Esai:</span>
                                        </div>
                                        <span class="text-xs font-semibold text-on-surface-variant bg-surface-container px-2.5 py-1 rounded-md">
                                            Rentang Nilai: 0 sampai {{ $maxQScore }} Poin
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-3 flex-wrap">
                                        <div class="flex items-center gap-2">
                                            <label for="score-input-{{ $answer->id }}" class="text-xs font-bold text-on-surface">Beri Nilai:</label>
                                            <input 
                                                type="number" 
                                                step="0.5" 
                                                min="0" 
                                                max="{{ $maxQScore }}" 
                                                id="score-input-{{ $answer->id }}"
                                                name="score" 
                                                value="{{ $answer->score_obtained ?? 0 }}" 
                                                class="w-28 p-2 rounded-lg border border-slate-300 font-headline-sm text-sm font-bold text-center focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white" 
                                                required
                                            >
                                            <span class="text-xs text-on-surface-variant font-semibold">/ {{ $maxQScore }}</span>
                                        </div>

                                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-primary hover:bg-primary-container text-white font-label-md text-xs font-bold transition-all shadow-sm active:scale-95 cursor-pointer">
                                            <span class="material-symbols-outlined text-[16px]">save</span>
                                            <span>Simpan Nilai</span>
                                        </button>

                                        <!-- Quick Grade Buttons -->
                                        <div class="flex items-center gap-1 text-xs">
                                            <button type="button" onclick="quickFillScore({{ $answer->id }}, 0)" class="px-2 py-1 rounded bg-surface-container hover:bg-surface-container-high text-on-surface font-semibold" title="Beri Nilai 0">0 Poin</button>
                                            <button type="button" onclick="quickFillScore({{ $answer->id }}, {{ round($maxQScore / 2, 1) }})" class="px-2 py-1 rounded bg-surface-container hover:bg-surface-container-high text-on-surface font-semibold" title="Beri Nilai Setengah (50%)">50%</button>
                                            <button type="button" onclick="quickFillScore({{ $answer->id }}, {{ $maxQScore }})" class="px-2 py-1 rounded bg-surface-container hover:bg-surface-container-high text-on-surface font-semibold" title="Beri Nilai Penuh (100%)">Penuh ({{ $maxQScore }})</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif

                        <!-- Discussion / Explanation Accordion Box -->
                        @if($q->explanation)
                            <div class="mt-space-xs p-space-md rounded-lg {{ $isCorrect ? 'bg-surface-container-low border border-slate-200/60' : 'bg-error-container/15 border border-error/20' }} flex flex-col gap-1">
                                <div class="flex items-center gap-1.5 {{ $isCorrect ? 'text-primary' : 'text-error' }} font-label-sm text-label-sm font-semibold">
                                    <span class="material-symbols-outlined text-[16px]">{{ $isCorrect ? 'lightbulb' : 'report_problem' }}</span>
                                    <span>{{ $isCorrect ? 'Langkah Penyelesaian / Pembahasan Soal' : 'Catatan Analisis Kunci Jawaban' }}</span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface leading-relaxed">
                                    {{ $q->explanation }}
                                </p>
                            </div>
                        @endif
                    </div>
                </article>
            @empty
                <div class="p-8 text-center bg-surface-container-lowest rounded-xl border border-slate-200">
                    <span class="material-symbols-outlined text-outline text-4xl mb-2">assignment_late</span>
                    <p class="font-headline-sm text-on-surface font-bold">Tidak ada rincian jawaban ditemukan</p>
                </div>
            @endforelse

            <!-- TEACHER NOTE SECTION (BOTTOM ANCHOR) -->
            <section class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border border-slate-100 flex flex-col gap-space-md scroll-mt-24" id="catatan-guru">
                <div class="flex items-center justify-between pb-space-sm bg-surface-container-low/50 -mx-space-lg -mt-space-lg px-space-lg pt-space-md rounded-t-xl border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[22px]">rate_review</span>
                        <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Catatan &amp; Umpan Balik Guru Pengajar</h3>
                    </div>
                    <span class="font-label-sm text-label-sm text-on-surface-variant font-medium">Evaluasi Pembelajaran Siswa</span>
                </div>

                <!-- Teacher Feedback Box -->
                <div class="flex gap-space-md p-space-md rounded-xl bg-surface-container-low border border-slate-200/60">
                    <img alt="{{ auth()->user()->name }}" class="w-12 h-12 rounded-full object-cover shrink-0 shadow-sm ring-2 ring-primary/20" src="{{ auth()->user()->avatar_url }}"/>
                    <div class="flex flex-col gap-1 w-full">
                        <div class="flex items-center justify-between flex-wrap gap-1">
                            <div>
                                <span class="font-label-lg text-label-lg text-on-surface font-bold">{{ auth()->user()->name }}</span>
                                <span class="font-label-sm text-label-sm text-on-surface-variant ml-1.5 font-medium">Guru Pengampu • {{ $exam->subject->name ?? 'Mata Pelajaran' }}</span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-[11px] font-semibold">
                                Umpan Balik Pendidik
                            </span>
                        </div>
                        <p id="savedTeacherNoteDisplay" class="font-body-md text-body-md text-on-surface italic mt-1 leading-relaxed bg-surface-container-lowest p-space-md rounded-lg shadow-2xs border border-slate-100">
                            {{ round($result->total_score) >= round($exam->passing_grade) ? '“Kerja bagus, ' . $student->name . '! Pemahaman konsep sudah baik dan memenuhi KKM. Pertahankan prestasimu dan pelajari kembali butir soal yang belum tepat.”' : '“Tetap semangat, ' . $student->name . '! Pelajari kembali materi ' . ($exam->subject->name ?? 'ujian ini') . ' terutama pada bagian soal yang belum tepat. Silakan berkonsultasi untuk jadwal remedial.”' }}
                        </p>
                    </div>
                </div>

                <!-- Teacher Note Form -->
                <div class="flex flex-col gap-2 mt-space-xs">
                    <label class="font-label-md text-label-md text-on-surface font-semibold" for="input-catatan">
                        Tulis Catatan Rekomendasi / Tindak Lanjut untuk Siswa:
                    </label>
                    <textarea 
                        id="input-catatan" 
                        class="w-full p-space-md bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container transition-all border border-slate-200" 
                        placeholder="Tulis instruksi tindak lanjut untuk {{ $student->name }} (misal: rekomendasi pengayaan materi atau jadwal remedial)..." 
                        rows="3"
                    ></textarea>
                    <div class="flex justify-end gap-space-sm mt-1">
                        <button onclick="document.getElementById('input-catatan').value = ''" class="px-space-md py-2 rounded-lg bg-surface-container text-on-surface font-label-md text-label-md hover:bg-surface-container-high transition-colors cursor-pointer" type="button">
                            Reset
                        </button>
                        <button onclick="saveTeacherNote()" class="px-space-md py-2 rounded-lg bg-primary-container text-on-primary font-label-md text-label-md shadow-sm hover:bg-primary transition-all font-bold cursor-pointer" type="button">
                            Simpan Catatan Guru
                        </button>
                    </div>
                </div>
            </section>
        </div>

        <!-- Fast Matrix Navigator & Metadata Sidebar (4 cols) -->
        <aside class="lg:col-span-4 flex flex-col gap-space-lg static lg:sticky lg:top-24">

            <!-- Nomor Soal Palette (Nomor Soal Matrix) -->
            <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border border-slate-100 flex flex-col">
                <div class="flex items-center justify-between mb-space-md">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px] text-primary">apps</span>
                        <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Peta Jawaban Siswa</h3>
                    </div>
                    <span class="font-label-sm text-label-sm text-on-surface-variant font-semibold">{{ $totalQ }} Butir Soal</span>
                </div>

                <!-- Legend -->
                <div class="flex items-center gap-3 text-on-surface-variant font-label-sm text-label-sm pb-space-sm mb-space-md bg-surface-container-low p-2 rounded-lg justify-around border border-slate-200/60">
                    <div class="flex items-center gap-1.5 font-medium">
                        <span class="w-3 h-3 rounded-sm bg-secondary"></span>
                        <span>Benar ({{ $correctCount }})</span>
                    </div>
                    <div class="flex items-center gap-1.5 font-medium">
                        <span class="w-3 h-3 rounded-sm bg-error"></span>
                        <span>Salah ({{ $wrongCount }})</span>
                    </div>
                    <div class="flex items-center gap-1.5 font-medium">
                        <span class="w-3 h-3 rounded-sm bg-surface-container-high ring-1 ring-primary"></span>
                        <span>Aktif</span>
                    </div>
                </div>

                <!-- Question Matrix Grid -->
                <div class="grid grid-cols-5 sm:grid-cols-8 lg:grid-cols-5 gap-2">
                    @foreach($answers as $index => $answer)
                        @php
                            $qNum = $index + 1;
                            $btnClass = 'bg-secondary text-on-secondary';
                            $titleText = "Soal {$qNum}: Benar";
                            if (!$answer->is_correct) {
                                if ($answer->selected_option_id || $answer->answer_text) {
                                    $btnClass = 'bg-error text-on-error';
                                    $titleText = "Soal {$qNum}: Salah";
                                } else {
                                    $btnClass = 'bg-surface-container-high text-on-surface-variant';
                                    $titleText = "Soal {$qNum}: Kosong";
                                }
                            }
                        @endphp
                        <button 
                            onclick="scrollToQuestion({{ $qNum }})"
                            id="matrix-btn-{{ $qNum }}"
                            class="matrix-btn h-10 rounded-lg {{ $btnClass }} font-label-md text-label-md flex items-center justify-center font-bold shadow-2xs transition-all active:scale-90 cursor-pointer hover:opacity-90" 
                            title="{{ $titleText }}" 
                            type="button"
                        >
                            {{ $qNum }}
                        </button>
                    @endforeach
                </div>

                <!-- Jump to Next Wrong Question -->
                <div class="mt-space-md pt-space-sm flex flex-col gap-2 border-t border-slate-100">
                    <div class="flex items-center justify-between text-on-surface-variant font-body-sm text-body-sm text-xs">
                        <span>Nomor Salah Terdeteksi:</span>
                        <span class="font-semibold text-error">
                            {{ count($wrongIndices) > 0 ? implode(', ', $wrongIndices) : 'Tidak ada' }}
                        </span>
                    </div>
                    @if(count($wrongIndices) > 0)
                        <button 
                            onclick="jumpToNextWrongQuestion()" 
                            class="w-full py-2 bg-surface-container hover:bg-surface-container-high rounded-lg text-primary font-label-sm text-label-sm font-bold transition-colors text-center cursor-pointer flex items-center justify-center gap-1" 
                            type="button"
                        >
                            <span>Loncat ke Soal Salah Berikutnya</span>
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Proctor & System Integrity Card -->
            <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border border-slate-100 flex flex-col gap-space-sm">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-[20px]">security</span>
                    <h4 class="font-label-lg text-label-lg text-on-surface font-bold">Integritas CBT &amp; Log Sesi</h4>
                </div>
                <ul class="flex flex-col gap-2 mt-1 font-body-sm text-body-sm text-on-surface-variant text-xs">
                    <li class="flex items-center justify-between py-1.5 bg-surface-container-low px-2.5 rounded-lg border border-slate-200/50">
                        <span>Status Pengerjaan:</span>
                        <span class="text-secondary font-bold">{{ strtoupper($participant->status) }}</span>
                    </li>
                    <li class="flex items-center justify-between py-1.5 bg-surface-container-low px-2.5 rounded-lg border border-slate-200/50">
                        <span>Waktu Mulai:</span>
                        <span class="text-on-surface font-semibold font-mono">{{ $participant->started_at ? $participant->started_at->copy()->setTimezone($exam->timezone)->format('H:i:s') . ' ' . $exam->timezone_code : '-' }}</span>
                    </li>
                    <li class="flex items-center justify-between py-1.5 bg-surface-container-low px-2.5 rounded-lg border border-slate-200/50">
                        <span>Waktu Submit:</span>
                        <span class="text-on-surface font-semibold font-mono">{{ $participant->submitted_at ? $participant->submitted_at->copy()->setTimezone($exam->timezone)->format('H:i:s') . ' ' . $exam->timezone_code : '-' }}</span>
                    </li>
                    <li class="flex items-center justify-between py-1.5 bg-surface-container-low px-2.5 rounded-lg border border-slate-200/50">
                        <span>Ketepatan Waktu:</span>
                        <span class="{{ $participant->is_late_submission ? 'text-error font-bold' : 'text-secondary font-bold' }}">
                            {{ $participant->is_late_submission ? 'Terlambat' : 'Tepat Waktu' }}
                        </span>
                    </li>
                    <li class="flex items-center justify-between py-1.5 bg-surface-container-low px-2.5 rounded-lg border border-slate-200/50">
                        <span>Guru Pembuat Ujian:</span>
                        <span class="text-on-surface font-semibold truncate max-w-[150px]">{{ $exam->teacher ? $exam->teacher->name : 'Guru Pengajar' }}</span>
                    </li>
                </ul>
            </div>
        </aside>

    </div>
</div>

<script>
    // 1. Scroll to Question
    function scrollToQuestion(qNum) {
        const el = document.getElementById('soal-' + qNum);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });

            // Highlight temporarily
            document.querySelectorAll('.question-item').forEach(item => item.classList.remove('ring-2', 'ring-primary'));
            el.classList.add('ring-2', 'ring-primary');
            setTimeout(() => el.classList.remove('ring-2', 'ring-primary'), 2000);

            // Matrix active indicator
            document.querySelectorAll('.matrix-btn').forEach(btn => btn.classList.remove('ring-2', 'ring-primary', 'ring-offset-2'));
            const activeBtn = document.getElementById('matrix-btn-' + qNum);
            if (activeBtn) {
                activeBtn.classList.add('ring-2', 'ring-primary', 'ring-offset-2');
            }
        }
    }

    // 2. Filter Questions (All / Wrong / Correct)
    function filterQuestions(type) {
        const items = document.querySelectorAll('.question-item');
        const btnAll = document.getElementById('filterAllBtn');
        const btnWrong = document.getElementById('filterWrongBtn');
        const btnCorrect = document.getElementById('filterCorrectBtn');

        // Reset button states
        [btnAll, btnWrong, btnCorrect].forEach(btn => {
            btn.className = 'px-3 py-1.5 rounded-lg bg-surface text-on-surface-variant hover:bg-surface-container font-label-sm text-label-sm font-semibold transition-colors cursor-pointer';
        });

        if (type === 'all') {
            btnAll.className = 'px-3 py-1.5 rounded-lg bg-primary-container text-on-primary font-label-sm text-label-sm font-semibold transition-all cursor-pointer shadow-2xs';
            items.forEach(el => el.classList.remove('hidden'));
        } else if (type === 'salah') {
            btnWrong.className = 'px-3 py-1.5 rounded-lg bg-primary-container text-on-primary font-label-sm text-label-sm font-semibold transition-all cursor-pointer shadow-2xs';
            items.forEach(el => {
                if (el.getAttribute('data-status') === 'salah' || el.getAttribute('data-status') === 'kosong') {
                    el.classList.remove('hidden');
                } else {
                    el.classList.add('hidden');
                }
            });
        } else if (type === 'benar') {
            btnCorrect.className = 'px-3 py-1.5 rounded-lg bg-primary-container text-on-primary font-label-sm text-label-sm font-semibold transition-all cursor-pointer shadow-2xs';
            items.forEach(el => {
                if (el.getAttribute('data-status') === 'benar') {
                    el.classList.remove('hidden');
                } else {
                    el.classList.add('hidden');
                }
            });
        }
    }

    // 3. Jump to Next Wrong Question
    const wrongQuestions = @json($wrongIndices);
    let currentWrongIndex = -1;

    function jumpToNextWrongQuestion() {
        if (!wrongQuestions || wrongQuestions.length === 0) return;
        currentWrongIndex = (currentWrongIndex + 1) % wrongQuestions.length;
        scrollToQuestion(wrongQuestions[currentWrongIndex]);
    }

    // 4. Save Teacher Note
    function saveTeacherNote() {
        const text = document.getElementById('input-catatan').value.trim();
        if (!text) {
            alert('Silakan tulis catatan atau umpan balik terlebih dahulu.');
            return;
        }
        document.getElementById('savedTeacherNoteDisplay').innerText = '“' + text + '”';
        alert('Catatan evaluasi siswa berhasil diperbarui.');
        document.getElementById('input-catatan').value = '';
    }

    // 5. Quick Fill Score for Essay
    function quickFillScore(answerId, score) {
        const input = document.getElementById('score-input-' + answerId);
        if (input) {
            input.value = score;
            input.focus();
        }
    }

    // 6. Submit Essay Grade via AJAX
    function submitEssayGrade(e, form) {
        e.preventDefault();
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalHtml = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="material-symbols-outlined text-[16px] animate-spin">progress_activity</span><span>Menyimpan...</span>';

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message || 'Nilai esai berhasil disimpan!');
                // Reload halaman agar seluruh metrik, grafik, dan total score terupdate akurat
                window.location.reload();
            } else {
                alert(data.message || 'Gagal menyimpan nilai esai.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHtml;
            }
        })
        .catch(err => {
            // Fallback submit form secara regular jika fetch gagal
            form.submit();
        });
    }
</script>
@endsection
