@extends('layouts.student')

@section('title', 'Detail Sesi Ujian — EduExam')

@push('mobile-styles')
<style>
    .bottom-nav { display: none !important; }
</style>
@endpush

@section('student-content')
<div class="flex flex-col w-full pb-24">
    <!-- Back Button -->
    <div class="py-2">
        <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-1 text-primary font-label-md text-xs font-semibold hover:opacity-80 transition-opacity">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>

    <!-- Exam Hero Card -->
    <div class="relative overflow-hidden bg-surface-container-low rounded-2xl p-4 shadow-sm border border-surface-container mb-4">
        <div class="absolute -right-4 -bottom-6 w-28 h-28 rounded-full bg-primary-fixed/30 pointer-events-none blur-xl"></div>
        <div class="flex items-center gap-1.5 mb-1.5">
            <span class="inline-flex items-center gap-1 bg-secondary-container text-on-secondary-container px-2 py-0.5 rounded-full text-[11px] font-semibold">
                <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                Siap Dimulai
            </span>
            <span class="text-xs text-on-surface-variant font-medium">
                • Kelas {{ $exam->classroom->name ?? 'Semua Kelas' }}
            </span>
        </div>
        <h2 class="font-headline-sm text-lg md:text-xl font-bold text-on-surface">
            {{ $exam->title }}
        </h2>
        <p class="font-body-sm text-xs text-on-surface-variant mt-0.5">
            {{ $exam->subject->name ?? 'Mata Pelajaran' }} • Tahun Ajaran {{ $exam->academicYear->name ?? '2026/2027' }}
        </p>

        <!-- Access Token Box -->
        <div class="mt-3 pt-2 bg-surface-container rounded-xl p-3 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-surface-container-lowest flex items-center justify-center text-primary shadow-sm">
                    <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">vpn_key</span>
                </div>
                <div>
                    <div class="text-[10px] text-on-surface-variant font-medium">Token Akses Ujian</div>
                    <div class="text-sm text-on-surface tracking-wider font-mono font-bold">
                        {{ $exam->token ?: 'TERBUKA' }}
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-1 bg-surface-container-lowest text-secondary px-2.5 py-1 rounded-full text-xs font-semibold shadow-sm">
                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                <span>Terverifikasi</span>
            </div>
        </div>
    </div>

    <!-- Exam Details Grid (2 columns) -->
    <div class="mb-4">
        <div class="flex items-center gap-1.5 mb-2.5">
            <span class="material-symbols-outlined text-primary text-[20px]">assignment</span>
            <h3 class="font-headline-sm text-sm font-bold text-on-surface">Rincian Informasi Ujian</h3>
        </div>
        <div class="grid grid-cols-2 gap-2.5">
            <div class="bg-surface-container-lowest p-3 rounded-xl shadow-sm border border-surface-container flex flex-col justify-between">
                <div class="flex items-center gap-1.5 text-on-surface-variant">
                    <span class="material-symbols-outlined text-[18px] text-primary">menu_book</span>
                    <span class="text-[11px] font-medium">Mata Pelajaran</span>
                </div>
                <div class="mt-1 text-xs font-bold text-on-surface truncate">
                    {{ $exam->subject->name ?? '-' }}
                </div>
            </div>
            <div class="bg-surface-container-lowest p-3 rounded-xl shadow-sm border border-surface-container flex flex-col justify-between">
                <div class="flex items-center gap-1.5 text-on-surface-variant">
                    <span class="material-symbols-outlined text-[18px] text-primary">person</span>
                    <span class="text-[11px] font-medium">Guru Pengampu</span>
                </div>
                <div class="mt-1 text-xs font-bold text-on-surface truncate">
                    {{ $exam->teacher->name ?? 'Guru Pengampu' }}
                </div>
            </div>
            <div class="bg-surface-container-lowest p-3 rounded-xl shadow-sm border border-surface-container flex flex-col justify-between">
                <div class="flex items-center gap-1.5 text-on-surface-variant">
                    <span class="material-symbols-outlined text-[18px] text-primary">quiz</span>
                    <span class="text-[11px] font-medium">Jumlah Soal</span>
                </div>
                <div class="mt-1 text-xs font-bold text-on-surface">
                    {{ $exam->total_questions ?? $exam->questions()->count() }} Soal
                </div>
            </div>
            <div class="bg-surface-container-lowest p-3 rounded-xl shadow-sm border border-surface-container flex flex-col justify-between">
                <div class="flex items-center gap-1.5 text-on-surface-variant">
                    <span class="material-symbols-outlined text-[18px] text-primary">timer</span>
                    <span class="text-[11px] font-medium">Durasi</span>
                </div>
                <div class="mt-1 text-xs font-bold text-on-surface">
                    {{ $exam->duration_minutes }} Menit
                </div>
            </div>
            <div class="bg-surface-container-lowest p-3 rounded-xl shadow-sm border border-surface-container flex flex-col justify-between">
                <div class="flex items-center gap-1.5 text-on-surface-variant">
                    <span class="material-symbols-outlined text-[18px] text-primary">calendar_today</span>
                    <span class="text-[11px] font-medium">Jadwal</span>
                </div>
                <div class="mt-1 text-xs font-bold text-on-surface">
                    {{ \Carbon\Carbon::parse($exam->exam_date ?? now())->translatedFormat('d M Y') }}
                </div>
            </div>
            <div class="bg-surface-container-lowest p-3 rounded-xl shadow-sm border border-surface-container flex flex-col justify-between">
                <div class="flex items-center gap-1.5 text-on-surface-variant">
                    <span class="material-symbols-outlined text-[18px] text-primary">schedule</span>
                    <span class="text-[11px] font-medium">Waktu Sesi</span>
                </div>
                <div class="mt-1 text-xs font-bold text-on-surface truncate">
                    {{ $exam->start_time ?? '08:00' }} - {{ $exam->end_time ?? '10:00' }} WIB
                </div>
            </div>
        </div>

        <!-- KKM Card -->
        <div class="mt-2.5 bg-surface-container-lowest p-3 rounded-xl shadow-sm border border-surface-container flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                </div>
                <div>
                    <div class="text-[10px] text-on-surface-variant">Kriteria Ketuntasan Minimal</div>
                    <div class="text-xs font-bold text-on-surface">Target Kelulusan Nilai (KKM)</div>
                </div>
            </div>
            <div class="bg-surface-container-high px-3 py-1 rounded-lg">
                <span class="font-headline-sm text-base font-bold text-primary">{{ $exam->passing_grade ?? 75 }}</span>
            </div>
        </div>
    </div>

    <!-- Rules / Tata Tertib Section -->
    <div class="mb-4">
        <div class="bg-surface-container-low rounded-2xl p-4 shadow-sm border border-surface-container">
            <div class="flex items-center gap-1.5 mb-2 text-on-surface">
                <span class="material-symbols-outlined text-primary text-[20px]" style="font-variation-settings: 'FILL' 1;">info</span>
                <h3 class="font-headline-sm text-sm font-bold">Petunjuk & Tata Tertib Ujian</h3>
            </div>
            <p class="font-body-sm text-xs text-on-surface-variant mb-3">
                Bacalah panduan dengan saksama untuk memastikan kelancaran evaluasi Anda:
            </p>
            <ul class="space-y-2">
                <li class="flex items-start gap-2.5 bg-surface-container-lowest p-2.5 rounded-xl shadow-sm border border-surface-container">
                    <span class="w-5 h-5 shrink-0 rounded-full bg-surface-container-high text-primary flex items-center justify-center text-xs font-bold">1</span>
                    <div class="text-on-surface font-body-sm text-xs pt-0.5">
                        Pastikan koneksi internet stabil selama mengerjakan ujian.
                    </div>
                </li>
                <li class="flex items-start gap-2.5 bg-surface-container-lowest p-2.5 rounded-xl shadow-sm border border-surface-container">
                    <span class="w-5 h-5 shrink-0 rounded-full bg-surface-container-high text-primary flex items-center justify-center text-xs font-bold">2</span>
                    <div class="text-on-surface font-body-sm text-xs pt-0.5">
                        Setiap soal memiliki bobot poin tertentu, pilih opsi yang paling tepat.
                    </div>
                </li>
                <li class="flex items-start gap-2.5 bg-surface-container-lowest p-2.5 rounded-xl shadow-sm border border-surface-container">
                    <span class="w-5 h-5 shrink-0 rounded-full bg-surface-container-high text-primary flex items-center justify-center text-xs font-bold">3</span>
                    <div class="text-on-surface font-body-sm text-xs pt-0.5">
                        Jawaban akan tersimpan secara otomatis setiap kali Anda memilih opsi atau mengetik.
                    </div>
                </li>
                <li class="flex items-start gap-2.5 bg-surface-container-lowest p-2.5 rounded-xl shadow-sm border border-surface-container">
                    <span class="w-5 h-5 shrink-0 rounded-full bg-surface-container-high text-primary flex items-center justify-center text-xs font-bold">4</span>
                    <div class="text-on-surface font-body-sm text-xs pt-0.5">
                        Ujian akan dikumpulkan otomatis ketika waktu hitung mundur habis.
                    </div>
                </li>
                <li class="flex items-start gap-2.5 bg-error-container/40 p-2.5 rounded-xl border border-error/20">
                    <span class="w-5 h-5 shrink-0 rounded-full bg-error text-white flex items-center justify-center text-xs font-bold">5</span>
                    <div class="text-on-surface font-body-sm text-xs pt-0.5 font-medium">
                        Dilarang membuka tab lain atau meninggalkan halaman ujian demi menjaga integritas proctoring.
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- Fixed Sticky Action Bar at Bottom -->
    <div class="fixed bottom-0 left-0 right-0 z-50 bg-surface/95 backdrop-blur-md border-t border-surface-container shadow-[0_-4px_16px_rgba(0,0,0,0.06)] p-3 pb-safe">
        <div class="max-w-[480px] mx-auto flex flex-col gap-2">
            <label class="flex items-start gap-2 cursor-pointer select-none group">
                <input type="checkbox" id="examAgreement" class="mt-0.5 w-4 h-4 rounded text-primary focus:ring-0 cursor-pointer transition-transform group-active:scale-95">
                <span class="font-body-sm text-xs text-on-surface-variant group-hover:text-on-surface transition-colors leading-snug">
                    Saya telah membaca dan memahami tata tertib ujian.
                </span>
            </label>

            <form action="{{ route('student.exam.start', $exam->id) }}" method="POST" id="startExamForm">
                @csrf
                <button type="submit" id="startExamBtn" disabled class="w-full h-12 bg-primary text-on-primary font-headline-sm text-sm font-semibold rounded-xl shadow-md flex items-center justify-center gap-1.5 opacity-40 cursor-not-allowed transition-all active:scale-[0.98]">
                    <span>Mulai Ujian</span>
                    <span class="material-symbols-outlined text-[18px]">play_arrow</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const agreementCheckbox = document.getElementById('examAgreement');
        const startButton = document.getElementById('startExamBtn');
        const startForm = document.getElementById('startExamForm');

        if (agreementCheckbox && startButton) {
            agreementCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    startButton.disabled = false;
                    startButton.classList.remove('opacity-40', 'cursor-not-allowed');
                    startButton.classList.add('hover:bg-primary/95', 'shadow-lg');
                } else {
                    startButton.disabled = true;
                    startButton.classList.add('opacity-40', 'cursor-not-allowed');
                    startButton.classList.remove('hover:bg-primary/95', 'shadow-lg');
                }
            });

            startForm.addEventListener('submit', function() {
                if (!startButton.disabled) {
                    startButton.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span><span>Mempersiapkan Soal...</span>';
                    startButton.disabled = true;
                }
            });
        }
    });
</script>
@endsection
