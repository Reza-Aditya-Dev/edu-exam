@extends('layouts.admin')

@section('title', 'Pengaturan & Konfigurasi Sistem CBT — EduExam')
@section('page_title', 'Pengaturan Sistem')

@section('admin-content')
<div class="flex flex-col w-full pb-28">
    <!-- Top Navigation / Context Header -->
    <div class="flex flex-col gap-4 mb-space-lg">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-on-surface-variant font-label-md text-label-md">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors cursor-pointer">Konfigurasi</a>
            <span class="material-symbols-outlined text-[16px] text-outline">chevron_right</span>
            <span class="text-primary font-semibold">Pengaturan Sistem</span>
        </div>

        <!-- Title & Top Actions Bento -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex flex-col max-w-2xl">
                <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">Pengaturan &amp; Konfigurasi Sistem CBT</h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1 leading-relaxed">
                    Konfigurasi global platform EduExam, profil sekolah {{ \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}, kebijakan ujian, keamanan akun, dan notifikasi.
                </p>
            </div>
            <div class="flex items-center gap-3 self-start lg:self-center flex-wrap">
                <button type="button" onclick="confirmResetDefaults()" class="px-4 py-2.5 rounded-lg bg-surface-container-high hover:bg-surface-variant text-on-surface font-label-lg text-label-lg transition-all flex items-center gap-2 active:scale-95 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">history</span>
                    <span>Kembalikan Default</span>
                </button>
                <button type="button" onclick="submitSettingsForm()" class="px-5 py-2.5 rounded-lg bg-primary hover:bg-primary-container text-on-primary font-label-lg text-label-lg transition-all flex items-center gap-2 active:scale-95 shadow-md">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    <span>Simpan Seluruh Perubahan</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Interactive Horizontal Navigation Tabs -->
    <div class="bg-surface-container-lowest rounded-xl p-1.5 shadow-sm mb-space-lg flex flex-wrap gap-1 sticky top-20 z-20 border border-slate-200/50 backdrop-blur-md">
        <button class="tab-btn px-4 py-2.5 rounded-lg font-label-lg text-label-lg transition-all flex items-center gap-2 bg-primary-container text-on-primary shadow-sm" data-target="tab-sekolah" onclick="switchSettingsTab('tab-sekolah', this)" type="button">
            <span class="material-symbols-outlined text-[18px]">domain</span>
            <span>1. Profil Sekolah</span>
            <span class="active-badge px-2 py-0.5 rounded-full bg-surface-container-lowest/20 text-on-primary font-label-sm text-label-sm">Aktif</span>
        </button>
        <button class="tab-btn px-4 py-2.5 rounded-lg font-label-lg text-label-lg text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-all flex items-center gap-2" data-target="tab-cbt" onclick="switchSettingsTab('tab-cbt', this)" type="button">
            <span class="material-symbols-outlined text-[18px]">tune</span>
            <span>2. Standar Ujian (CBT)</span>
        </button>
        <button class="tab-btn px-4 py-2.5 rounded-lg font-label-lg text-label-lg text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-all flex items-center gap-2" data-target="tab-keamanan" onclick="switchSettingsTab('tab-keamanan', this)" type="button">
            <span class="material-symbols-outlined text-[18px]">security</span>
            <span>3. Keamanan &amp; Akun</span>
        </button>
        <button class="tab-btn px-4 py-2.5 rounded-lg font-label-lg text-label-lg text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-all flex items-center gap-2" data-target="tab-notifikasi" onclick="switchSettingsTab('tab-notifikasi', this)" type="button">
            <span class="material-symbols-outlined text-[18px]">notifications_active</span>
            <span>4. Preferensi Notifikasi</span>
        </button>
    </div>

    <!-- Main Settings Content Form Stack -->
    <form id="settingsForm" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-space-lg">
        @csrf

        <!-- Section 1: Profil Resmi Satuan Pendidikan -->
        <div class="settings-panel flex flex-col gap-6 scroll-mt-36" id="tab-sekolah">
            <div class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-sm border border-slate-100">
                <div class="flex flex-col md:flex-row md:items-center justify-between pb-5 mb-6 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-[24px]">verified</span>
                        </div>
                        <div>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Profil Resmi Satuan Pendidikan</h2>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Identitas kelembagaan yang tertera pada lembar ujian, kartu peserta, dan cetak berita acara resmi.</p>
                        </div>
                    </div>
                    <span class="mt-2 md:mt-0 self-start md:self-auto px-3 py-1 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm flex items-center gap-1.5 font-semibold">
                        <span class="w-2 h-2 rounded-full bg-secondary"></span>
                        Terverifikasi Dapodik
                    </span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    <!-- Logo & Brand Visual Card (Bento Left) -->
                    <div class="lg:col-span-4 bg-surface-container-low rounded-xl p-space-md flex flex-col items-center text-center border border-slate-200/60">
                        <div class="relative group">
                            <div class="w-36 h-36 rounded-2xl bg-surface-container-lowest p-3 shadow-md flex items-center justify-center overflow-hidden border border-slate-100">
                                @php
                                    $defaultLogoUrl = 'https://lh3.googleusercontent.com/aida/AEtjO1XMS8OoDyx9qMlALXP9TtuM5s4zpW0D5N3ytWGvYxbdvqUjZZ5FXm2GvesC7yV2dU_lvY8FcAuG-5l5zfbCYVYiRhRo55DRaszwwEcl1t-hRyIUSC1qj57X-7KPTuO2jIxLwO0QOx2pwe5uCFxzwEZ64h0x3c47pmb9kutcuJN19Lnn9rvWvQ2-pRKw_gJ_L86A-M39djczoe3xgAMLsdz92TG3iB8KaZq0KAl32qfFeToww6Vho-FjAM8';
                                    $currentLogo = \App\Models\SchoolSetting::get('school_logo', $defaultLogoUrl);
                                @endphp
                                <img id="logo_preview" alt="Logo Resmi Sekolah" class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-105" src="{{ $currentLogo }}" />
                            </div>
                            <button type="button" onclick="document.getElementById('school_logo_file').click()" class="absolute -bottom-2 -right-2 w-9 h-9 rounded-full bg-primary text-on-primary flex items-center justify-center shadow-lg hover:scale-110 active:scale-95 transition-transform" title="Ganti Berkas Logo">
                                <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                            </button>
                        </div>

                        <!-- Hidden File Input for Logo Upload -->
                        <input type="file" id="school_logo_file" name="school_logo_file" accept="image/png,image/jpeg,image/svg+xml,image/webp" class="hidden" onchange="previewLogo(this)">
                        <input type="hidden" id="school_logo_input" name="school_logo" value="{{ $currentLogo }}">

                        <div class="mt-4 flex flex-col items-center">
                            <span class="font-label-lg text-label-lg text-on-surface font-semibold">Emblem Resmi EduExam</span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Format PNG, SVG, atau JPG (Maks. 2MB)</span>
                        </div>

                        <div class="mt-5 w-full flex flex-col gap-2">
                            <button type="button" onclick="document.getElementById('school_logo_file').click()" class="w-full py-2.5 px-4 rounded-lg bg-surface-container-high hover:bg-surface-variant text-primary font-label-md text-label-md flex items-center justify-center gap-2 transition-colors active:scale-[0.98]">
                                <span class="material-symbols-outlined text-[18px]">upload_file</span>
                                <span>Ubah Logo Resmi Sekolah</span>
                            </button>
                            <button type="button" onclick="resetLogoToDefault()" class="w-full py-1.5 px-3 rounded-lg text-xs font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-200/50 transition-colors">
                                Gunakan Logo Default
                            </button>
                        </div>

                        <div class="mt-4 p-3 rounded-lg bg-surface-container-lowest w-full text-left flex items-start gap-2.5 border border-slate-100">
                            <span class="material-symbols-outlined text-tertiary text-[18px] shrink-0 mt-0.5">info</span>
                            <p class="font-body-sm text-body-sm text-on-surface-variant text-xs leading-relaxed">
                                Logo ini otomatis terintegrasi pada kop naskah soal, kartu peserta ujian nasional/sekolah, dan portal verifikasi proctor.
                            </p>
                        </div>
                    </div>

                    <!-- Fields Grid (Bento Right) -->
                    <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-space-md">
                        <!-- Nama Sekolah -->
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <label class="font-label-md text-label-md text-on-surface font-semibold flex items-center justify-between">
                                <span>Nama Resmi Sekolah</span>
                                <span class="text-outline font-normal text-body-sm">Sesuai SK Pendirian</span>
                            </label>
                            <div class="flex items-center bg-surface rounded-lg px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-primary shadow-sm border border-slate-200/60">
                                <span class="material-symbols-outlined text-outline text-[20px] mr-3">school</span>
                                <input class="w-full bg-transparent border-none outline-none font-body-md text-body-md text-on-surface font-semibold" type="text" name="school_name" value="{{ old('school_name', \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara')) }}" required />
                            </div>
                        </div>

                        <!-- NPSN -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface font-semibold">Nomor Pokok Sekolah Nasional (NPSN)</label>
                            <div class="flex items-center bg-surface rounded-lg px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-primary shadow-sm border border-slate-200/60">
                                <span class="material-symbols-outlined text-outline text-[20px] mr-3">badge</span>
                                <input class="w-full bg-transparent border-none outline-none font-body-md text-body-md text-on-surface font-mono font-bold" type="text" name="school_npsn" value="{{ old('school_npsn', \App\Models\SchoolSetting::get('school_npsn', '20103482')) }}" required />
                            </div>
                        </div>

                        <!-- Akreditasi -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface font-semibold">Status Akreditasi BAN-S/M</label>
                            <div class="flex items-center bg-surface rounded-lg px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-primary shadow-sm border border-slate-200/60">
                                <span class="material-symbols-outlined text-secondary text-[20px] mr-3">military_tech</span>
                                <input class="w-full bg-transparent border-none outline-none font-body-md text-body-md text-on-surface font-medium" type="text" name="school_accreditation" value="{{ old('school_accreditation', \App\Models\SchoolSetting::get('school_accreditation', 'A (Unggul)')) }}" />
                            </div>
                        </div>

                        <!-- Alamat Lengkap -->
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <label class="font-label-md text-label-md text-on-surface font-semibold">Alamat Lengkap Satuan Pendidikan</label>
                            <div class="flex items-start bg-surface rounded-lg px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-primary shadow-sm border border-slate-200/60">
                                <span class="material-symbols-outlined text-outline text-[20px] mr-3 mt-0.5">location_on</span>
                                <textarea class="w-full bg-transparent border-none outline-none font-body-md text-body-md text-on-surface resize-none leading-relaxed" rows="2" name="school_address">{{ old('school_address', \App\Models\SchoolSetting::get('school_address', 'Jl. Garuda No. 45, Kebayoran Baru, Jakarta Selatan, DKI Jakarta 12120')) }}</textarea>
                            </div>
                        </div>

                        <!-- Kontak & Email -->
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <label class="font-label-md text-label-md text-on-surface font-semibold">Kontak &amp; Email Resmi Helpdesk CBT</label>
                            <div class="flex items-center bg-surface rounded-lg px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-primary shadow-sm border border-slate-200/60">
                                <span class="material-symbols-outlined text-outline text-[20px] mr-3">contact_mail</span>
                                <input class="w-full bg-transparent border-none outline-none font-body-md text-body-md text-on-surface" type="text" name="school_contact" value="{{ old('school_contact', \App\Models\SchoolSetting::get('school_contact', 'info@smanusantara.sch.id / +62 21-7201928')) }}" />
                            </div>
                        </div>

                        <!-- Nama Kepala Sekolah -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface font-semibold flex items-center justify-between">
                                <span>Nama Kepala Sekolah</span>
                                <span class="text-outline font-normal text-body-sm">Beserta Gelar</span>
                            </label>
                            <div class="flex items-center bg-surface rounded-lg px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-primary shadow-sm border border-slate-200/60">
                                <span class="material-symbols-outlined text-outline text-[20px] mr-3">person_pin</span>
                                <input class="w-full bg-transparent border-none outline-none font-body-md text-body-md text-on-surface font-semibold" type="text" name="principal_name" value="{{ old('principal_name', \App\Models\SchoolSetting::get('principal_name', 'Drs. H. Mulyadi, M.Pd')) }}" placeholder="Contoh: Drs. H. Mulyadi, M.Pd" />
                            </div>
                        </div>

                        <!-- NIP Kepala Sekolah -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface font-semibold flex items-center justify-between">
                                <span>NIP Kepala Sekolah</span>
                                <span class="text-outline font-normal text-body-sm">Nomor Induk Pegawai</span>
                            </label>
                            <div class="flex items-center bg-surface rounded-lg px-3.5 py-2.5 focus-within:ring-2 focus-within:ring-primary shadow-sm border border-slate-200/60">
                                <span class="material-symbols-outlined text-outline text-[20px] mr-3">fingerprint</span>
                                <input class="w-full bg-transparent border-none outline-none font-body-md text-body-md text-on-surface font-mono" type="text" name="principal_nip" value="{{ old('principal_nip', \App\Models\SchoolSetting::get('principal_nip', '19780512 200312 1 002')) }}" placeholder="Contoh: 19780512 200312 1 002" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Parameter & Standar Default Ujian CBT -->
        <div class="settings-panel flex flex-col gap-6 scroll-mt-36" id="tab-cbt">
            <div class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-sm border border-slate-100">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[24px]">assignment_turned_in</span>
                    </div>
                    <div>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Parameter &amp; Standar Default Ujian CBT</h2>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Kebijakan otomatisasi pengerjaan, KKM, pengawas virtual, dan tata tertib daring.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-space-lg">
                    <!-- Durasi Standar & KKM Card -->
                    <div class="flex flex-col gap-space-md p-space-md bg-surface-container-low rounded-xl border border-slate-200/60">
                        <span class="font-label-lg text-label-lg text-primary uppercase tracking-wide font-bold">Pengaturan Nilai &amp; Waktu</span>
                        
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface font-semibold">Durasi Standar Pelaksanaan Ujian</label>
                            <div class="flex items-center bg-surface-container-lowest rounded-lg px-3.5 py-2.5 shadow-sm border border-slate-200/60">
                                <span class="material-symbols-outlined text-outline text-[20px] mr-3">timer</span>
                                <input class="w-full bg-transparent border-none outline-none font-body-md text-body-md text-on-surface font-semibold" type="text" name="exam_default_duration" value="{{ old('exam_default_duration', \App\Models\SchoolSetting::get('exam_default_duration', '60 Menit')) }}" />
                            </div>
                            <span class="font-body-sm text-body-sm text-on-surface-variant text-xs">Dapat disesuaikan kembali per paket soal ujian spesifik.</span>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface font-semibold">Kriteria Ketuntasan Minimal (KKM) Standar</label>
                            <div class="flex items-center bg-surface-container-lowest rounded-lg px-3.5 py-2.5 shadow-sm border border-slate-200/60">
                                <span class="material-symbols-outlined text-outline text-[20px] mr-3">grade</span>
                                <input class="w-full bg-transparent border-none outline-none font-body-md text-body-md text-on-surface font-semibold text-secondary" type="text" name="exam_default_kkm" value="{{ old('exam_default_kkm', \App\Models\SchoolSetting::get('exam_default_kkm', '75 Poin')) }}" />
                            </div>
                            <span class="font-body-sm text-body-sm text-on-surface-variant text-xs">Ambang batas acuan kelulusan siswa dan rekap rapor formatif.</span>
                        </div>
                    </div>

                    <!-- Publikasi Hasil Nilai -->
                    <div class="flex flex-col gap-space-md p-space-md bg-surface-container-low rounded-xl border border-slate-200/60">
                        <span class="font-label-lg text-label-lg text-primary uppercase tracking-wide font-bold">Publikasi Hasil &amp; Skoring</span>
                        <div class="flex flex-col gap-3">
                            <label class="font-label-md text-label-md text-on-surface font-semibold">Kebijakan Penayangan Skor ke Peserta</label>
                            
                            @php
                                $scorePolicy = old('score_policy', \App\Models\SchoolSetting::get('score_policy', 'instant'));
                            @endphp

                            <!-- Option 1: Instant -->
                            <label class="flex items-start gap-3 p-3.5 rounded-xl bg-surface-container-lowest cursor-pointer hover:bg-surface-container-high transition-colors shadow-sm border border-slate-100">
                                <input class="mt-1 w-4 h-4 text-primary focus:ring-primary accent-primary" name="score_policy" type="radio" value="instant" {{ $scorePolicy === 'instant' ? 'checked' : '' }} />
                                <div class="flex flex-col">
                                    <span class="font-label-md text-label-md text-on-surface font-semibold">Langsung tampilkan nilai ke siswa setelah ujian</span>
                                    <span class="font-body-sm text-body-sm text-on-surface-variant text-xs mt-0.5 leading-relaxed">Siswa melihat total skor dan rekap jawaban benar/salah seketika setelah menekan tombol "Selesai".</span>
                                </div>
                            </label>

                            <!-- Option 2: Moderated -->
                            <label class="flex items-start gap-3 p-3.5 rounded-xl bg-surface-container-lowest cursor-pointer hover:bg-surface-container-high transition-colors shadow-sm border border-slate-100">
                                <input class="mt-1 w-4 h-4 text-primary focus:ring-primary accent-primary" name="score_policy" type="radio" value="moderate" {{ $scorePolicy === 'moderate' ? 'checked' : '' }} />
                                <div class="flex flex-col">
                                    <span class="font-label-md text-label-md text-on-surface font-semibold">Tahan hingga guru verifikasi manual</span>
                                    <span class="font-body-sm text-body-sm text-on-surface-variant text-xs mt-0.5 leading-relaxed">Nilai disembunyikan sampai guru mata pelajaran merilis nilai resmi dan mengoreksi soal esai.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Toggle 1: Auto-submit -->
                    <div class="p-space-md rounded-xl bg-surface-container-low flex items-center justify-between gap-4 border border-slate-200/60">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-surface-container-highest flex items-center justify-center text-primary shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-[20px]">send_and_archive</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-label-lg text-label-lg text-on-surface font-semibold">Auto-Submit Ketika Waktu Habis</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant text-xs">Ujian otomatis dikumpulkan dan dinilai seketika tanpa memerlukan konfirmasi manual siswa.</span>
                            </div>
                        </div>
                        <!-- Switch UI -->
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="hidden" name="exam_auto_submit" value="0">
                            <input class="sr-only peer" type="checkbox" name="exam_auto_submit" value="1" {{ filter_var(old('exam_auto_submit', \App\Models\SchoolSetting::get('exam_auto_submit', '1')), FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }} />
                            <div class="w-12 h-6 bg-surface-container-highest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[3px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>

                    <!-- Toggle 2: Proctor Lock Browser -->
                    <div class="p-space-md rounded-xl bg-surface-container-low flex items-center justify-between gap-4 border border-slate-200/60">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-error-container flex items-center justify-center text-on-error-container shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-[20px]">screen_lock_rotation</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-label-lg text-label-lg text-on-surface font-semibold">Kunci Jendela Peramban (Safe Exam Browser)</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant text-xs">Kirim peringatan otomatis saat siswa pindah tab / alt-tab dan bekukan sesi setelah 3 kali pelanggaran.</span>
                            </div>
                        </div>
                        <!-- Switch UI -->
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="hidden" name="exam_safe_browser" value="0">
                            <input class="sr-only peer" type="checkbox" name="exam_safe_browser" value="1" {{ filter_var(old('exam_safe_browser', \App\Models\SchoolSetting::get('exam_safe_browser', '1')), FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }} />
                            <div class="w-12 h-6 bg-surface-container-highest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[3px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Keamanan & Kebijakan Akun Pengguna -->
        <div class="settings-panel flex flex-col gap-6 scroll-mt-36" id="tab-keamanan">
            <div class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-sm border border-slate-100">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[24px]">gpp_good</span>
                    </div>
                    <div>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Keamanan &amp; Kebijakan Akun Pengguna</h2>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Proteksi akses gerbang ujian, otentikasi biometrik/ganda, dan restriksi jaringan laboratorium.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-space-lg">
                    <!-- Session Timeout Dropdown -->
                    <div class="flex flex-col gap-2 p-space-md bg-surface-container-low rounded-xl border border-slate-200/60">
                        <label class="font-label-md text-label-md text-on-surface font-semibold flex items-center gap-2">
                            <span class="material-symbols-outlined text-outline text-[18px]">timelapse</span>
                            <span>Batas Sesi Kedaluwarsa (Session Timeout)</span>
                        </label>
                        @php
                            $sessionTimeout = old('session_timeout', \App\Models\SchoolSetting::get('session_timeout', '30'));
                        @endphp
                        <div class="relative">
                            <select name="session_timeout" class="w-full bg-surface-container-lowest font-body-md text-body-md text-on-surface px-4 py-3 rounded-lg appearance-none outline-none focus:ring-2 focus:ring-primary shadow-sm cursor-pointer pr-10 border border-slate-200/60">
                                <option value="15" {{ $sessionTimeout == '15' ? 'selected' : '' }}>15 Menit tidak aktif</option>
                                <option value="30" {{ $sessionTimeout == '30' ? 'selected' : '' }}>30 Menit tidak aktif</option>
                                <option value="60" {{ $sessionTimeout == '60' ? 'selected' : '' }}>60 Menit tidak aktif</option>
                                <option value="120" {{ $sessionTimeout == '120' ? 'selected' : '' }}>2 Jam tidak aktif</option>
                            </select>
                            <span class="material-symbols-outlined text-outline text-[20px] absolute right-3 top-3.5 pointer-events-none">expand_more</span>
                        </div>
                        <span class="font-body-sm text-body-sm text-on-surface-variant text-xs">Sesi otomatis ditutup untuk mencegah penyalahgunaan terminal komputer laboratorium.</span>
                    </div>

                    <!-- Password Policy -->
                    <div class="flex flex-col gap-2 p-space-md bg-surface-container-low rounded-xl border border-slate-200/60">
                        <label class="font-label-md text-label-md text-on-surface font-semibold flex items-center gap-2">
                            <span class="material-symbols-outlined text-outline text-[18px]">password</span>
                            <span>Kebijakan Kompleksitas Kata Sandi</span>
                        </label>
                        <div class="flex flex-col gap-2.5 mt-1">
                            <label class="flex items-center gap-3 p-2 rounded-lg bg-surface-container-lowest shadow-sm cursor-pointer hover:bg-surface-container-high transition-colors border border-slate-100">
                                <input type="hidden" name="password_min_length" value="0">
                                <input class="w-4 h-4 rounded text-primary focus:ring-primary accent-primary" type="checkbox" name="password_min_length" value="1" {{ filter_var(old('password_min_length', \App\Models\SchoolSetting::get('password_min_length', '1')), FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }} />
                                <span class="font-body-md text-body-md text-on-surface font-medium">Minimal 8 karakter</span>
                            </label>
                            <label class="flex items-center gap-3 p-2 rounded-lg bg-surface-container-lowest shadow-sm cursor-pointer hover:bg-surface-container-high transition-colors border border-slate-100">
                                <input type="hidden" name="password_require_mixed" value="0">
                                <input class="w-4 h-4 rounded text-primary focus:ring-primary accent-primary" type="checkbox" name="password_require_mixed" value="1" {{ filter_var(old('password_require_mixed', \App\Models\SchoolSetting::get('password_require_mixed', '1')), FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }} />
                                <span class="font-body-md text-body-md text-on-surface font-medium">Wajib kombinasi angka &amp; huruf besar</span>
                            </label>
                        </div>
                    </div>

                    <!-- 2FA Toggle -->
                    <div class="p-space-md rounded-xl bg-surface-container-low flex items-center justify-between gap-4 border border-slate-200/60">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-secondary-container flex items-center justify-center text-on-secondary-container shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-[20px]">find_replace</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-label-lg text-label-lg text-on-surface font-semibold">Otentikasi Ganda (2FA) Guru &amp; Admin</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant text-xs">Kirimkan OTP via WhatsApp atau Google Authenticator saat login dari perangkat baru.</span>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="hidden" name="two_factor_auth" value="0">
                            <input class="sr-only peer" type="checkbox" name="two_factor_auth" value="1" {{ filter_var(old('two_factor_auth', \App\Models\SchoolSetting::get('two_factor_auth', '1')), FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }} />
                            <div class="w-12 h-6 bg-surface-container-highest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[3px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>

                    <!-- IP / Wi-Fi Lab Restriction -->
                    <div class="p-space-md rounded-xl bg-surface-container-low flex items-center justify-between gap-4 border border-slate-200/60">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-surface-container-highest flex items-center justify-center text-primary shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-[20px]">wifi_lock</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-label-lg text-label-lg text-on-surface font-semibold">Pembatasan Jaringan IP / Wi-Fi Lab</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant text-xs">Hanya izinkan pengerjaan dari SSID sekolah <code class="px-1.5 py-0.5 rounded bg-surface-container-lowest text-primary font-mono font-bold">{{ \App\Models\SchoolSetting::get('lab_wifi_ssid', 'SMA_NUSANTARA_CBT') }}</code>.</span>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="hidden" name="lab_ip_restriction" value="0">
                            <input type="hidden" name="lab_wifi_ssid" value="{{ \App\Models\SchoolSetting::get('lab_wifi_ssid', 'SMA_NUSANTARA_CBT') }}">
                            <input class="sr-only peer" type="checkbox" name="lab_ip_restriction" value="1" {{ filter_var(old('lab_ip_restriction', \App\Models\SchoolSetting::get('lab_ip_restriction', '1')), FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }} />
                            <div class="w-12 h-6 bg-surface-container-highest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[3px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: Konfigurasi Notifikasi & Pengingat -->
        <div class="settings-panel flex flex-col gap-6 scroll-mt-36" id="tab-notifikasi">
            <div class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-sm border border-slate-100">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[24px]">mark_chat_unread</span>
                    </div>
                    <div>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Konfigurasi Notifikasi &amp; Pengingat</h2>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Jadwal blast informasi ke aplikasi siswa, email wali kelas, dan pimpinan sekolah.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-space-lg">
                    <!-- Toggle Notification 1 -->
                    <div class="p-space-md rounded-xl bg-surface-container-low flex items-center justify-between gap-4 border border-slate-200/60">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-surface-container-highest flex items-center justify-center text-primary shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-[20px]">notification_important</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-label-lg text-label-lg text-on-surface font-semibold">Pengingat Jadwal Ujian Siswa</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant text-xs">Broadcast otomatis pada H-1 dan 1 Jam sebelum ujian dimulai melalui Email &amp; Notifikasi Aplikasi Mobile.</span>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="hidden" name="notif_exam_reminder" value="0">
                            <input class="sr-only peer" type="checkbox" name="notif_exam_reminder" value="1" {{ filter_var(old('notif_exam_reminder', \App\Models\SchoolSetting::get('notif_exam_reminder', '1')), FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }} />
                            <div class="w-12 h-6 bg-surface-container-highest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[3px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>

                    <!-- Toggle Notification 2 -->
                    <div class="p-space-md rounded-xl bg-surface-container-low flex items-center justify-between gap-4 border border-slate-200/60">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-secondary-container flex items-center justify-center text-on-secondary-container shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-[20px]">analytics</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-label-lg text-label-lg text-on-surface font-semibold">Laporan Rekap Nilai Otomatis</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant text-xs">Generate dan kirim ringkasan ketuntasan per kelas ke Wali Kelas &amp; Kepala Sekolah pasca ujian berakhir.</span>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="hidden" name="notif_auto_report" value="0">
                            <input class="sr-only peer" type="checkbox" name="notif_auto_report" value="1" {{ filter_var(old('notif_auto_report', \App\Models\SchoolSetting::get('notif_auto_report', '1')), FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }} />
                            <div class="w-12 h-6 bg-surface-container-highest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[3px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Sticky Save Bar at Bottom -->
    <div class="fixed bottom-0 left-0 lg:left-72 right-0 bg-surface-container-lowest/95 backdrop-blur-md px-space-md md:px-space-lg py-3.5 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] z-30 flex items-center justify-between border-t border-slate-200/70">
        <div class="flex items-center gap-3">
            <span class="w-2.5 h-2.5 rounded-full bg-secondary animate-pulse"></span>
            <div class="flex flex-col sm:flex-row sm:items-center sm:gap-2">
                <span class="font-label-md text-label-md text-on-surface font-semibold">Status Konfigurasi:</span>
                <span class="font-body-sm text-body-sm text-on-surface-variant">
                    @php
                        $lastUpdated = \App\Models\SchoolSetting::latest('updated_at')->first()?->updated_at;
                    @endphp
                    Perubahan terakhir disimpan: {{ $lastUpdated ? $lastUpdated->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB' : 'Hari ini, ' . date('H:i') . ' WIB' }}
                </span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" onclick="cancelSettingsEdit()" class="px-4 py-2 rounded-lg bg-surface-container-high hover:bg-surface-variant text-on-surface font-label-md text-label-md transition-colors active:scale-95">
                Batal
            </button>
            <button type="button" onclick="submitSettingsForm()" class="px-5 py-2 rounded-lg bg-primary hover:bg-primary-container text-on-primary font-label-md text-label-md transition-all flex items-center gap-2 shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[18px]">check</span>
                <span>Simpan Pengaturan Sistem</span>
            </button>
        </div>
    </div>

    <!-- Micro-interaction Toast Placeholder -->
    <div class="fixed top-24 right-6 bg-secondary text-on-secondary px-5 py-3 rounded-xl shadow-2xl flex items-center gap-3 transform -translate-y-24 opacity-0 transition-all duration-300 pointer-events-none z-50" id="save-toast">
        <span class="material-symbols-outlined text-[20px]">check_circle</span>
        <span class="font-label-md text-label-md font-semibold" id="toast-message">Pengaturan Sistem berhasil diperbarui!</span>
    </div>
</div>

<script>
    const DEFAULT_LOGO_URL = 'https://lh3.googleusercontent.com/aida/AEtjO1XMS8OoDyx9qMlALXP9TtuM5s4zpW0D5N3ytWGvYxbdvqUjZZ5FXm2GvesC7yV2dU_lvY8FcAuG-5l5zfbCYVYiRhRo55DRaszwwEcl1t-hRyIUSC1qj57X-7KPTuO2jIxLwO0QOx2pwe5uCFxzwEZ64h0x3c47pmb9kutcuJN19Lnn9rvWvQ2-pRKw_gJ_L86A-M39djczoe3xgAMLsdz92TG3iB8KaZq0KAl32qfFeToww6Vho-FjAM8';

    function switchSettingsTab(tabId, el) {
        const buttons = document.querySelectorAll('.tab-btn');
        buttons.forEach(btn => {
            btn.className = 'tab-btn px-4 py-2.5 rounded-lg font-label-lg text-label-lg text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-all flex items-center gap-2';
            const badge = btn.querySelector('.active-badge');
            if (badge) badge.remove();
        });

        el.className = 'tab-btn px-4 py-2.5 rounded-lg font-label-lg text-label-lg transition-all flex items-center gap-2 bg-primary-container text-on-primary shadow-sm';
        const activeBadge = document.createElement('span');
        activeBadge.className = 'active-badge px-2 py-0.5 rounded-full bg-surface-container-lowest/20 text-on-primary font-label-sm text-label-sm';
        activeBadge.textContent = 'Aktif';
        el.appendChild(activeBadge);

        const targetElement = document.getElementById(tabId);
        if (targetElement) {
            targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function previewLogo(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran berkas logo maksimal 2MB.');
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logo_preview').src = e.target.result;
                triggerSaveNotification('Logo baru dipilih. Klik simpan untuk menerapkan perubahan!');
            };
            reader.readAsDataURL(file);
        }
    }

    function resetLogoToDefault() {
        if (confirm('Kembalikan logo sekolah ke emblem resmi standar EduExam?')) {
            document.getElementById('school_logo_file').value = '';
            document.getElementById('school_logo_input').value = DEFAULT_LOGO_URL;
            document.getElementById('logo_preview').src = DEFAULT_LOGO_URL;
            triggerSaveNotification('Logo dikembalikan ke default. Simpan untuk memperbarui.');
        }
    }

    function confirmResetDefaults() {
        if (confirm('Kembalikan seluruh pengaturan ke standar default sistem? Form akan diisi dengan konfigurasi awal.')) {
            document.querySelector('[name="school_name"]').value = 'SMA Nusantara';
            document.querySelector('[name="school_npsn"]').value = '20103482';
            document.querySelector('[name="school_accreditation"]').value = 'A (Unggul)';
            document.querySelector('[name="school_address"]').value = 'Jl. Garuda No. 45, Kebayoran Baru, Jakarta Selatan, DKI Jakarta 12120';
            document.querySelector('[name="school_contact"]').value = 'info@smanusantara.sch.id / +62 21-7201928';
            document.querySelector('[name="principal_name"]').value = 'Drs. H. Mulyadi, M.Pd';
            document.querySelector('[name="principal_nip"]').value = '19780512 200312 1 002';
            document.querySelector('[name="exam_default_duration"]').value = '60 Menit';
            document.querySelector('[name="exam_default_kkm"]').value = '75 Poin';

            const instantRadio = document.querySelector('input[name="score_policy"][value="instant"]');
            if (instantRadio) instantRadio.checked = true;

            const checkBoxes = ['exam_auto_submit', 'exam_safe_browser', 'password_min_length', 'password_require_mixed', 'two_factor_auth', 'lab_ip_restriction', 'notif_exam_reminder', 'notif_auto_report'];
            checkBoxes.forEach(name => {
                const cb = document.querySelector(`input[type="checkbox"][name="${name}"]`);
                if (cb) cb.checked = true;
            });

            const timeoutSelect = document.querySelector('select[name="session_timeout"]');
            if (timeoutSelect) timeoutSelect.value = '30';

            resetLogoToDefault();
            triggerSaveNotification('Pengaturan di-reset ke nilai default! Klik "Simpan Seluruh Perubahan" untuk menyimpan.');
        }
    }

    function cancelSettingsEdit() {
        if (confirm('Batalkan perubahan dan muat ulang halaman?')) {
            window.location.reload();
        }
    }

    function submitSettingsForm() {
        const form = document.getElementById('settingsForm');
        if (form.reportValidity()) {
            form.submit();
        }
    }

    function triggerSaveNotification(customMsg) {
        const toast = document.getElementById('save-toast');
        const msgEl = document.getElementById('toast-message');
        if (!toast) return;

        if (customMsg && msgEl) {
            msgEl.textContent = customMsg;
        }

        toast.classList.remove('-translate-y-24', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
            toast.classList.add('-translate-y-24', 'opacity-0');
            toast.classList.remove('translate-y-0', 'opacity-100');
        }, 3000);
    }

    // Auto-trigger toast if redirected with Laravel flash message
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            triggerSaveNotification("{{ session('success') }}");
        @endif

        // Scroll spy to highlight active tab
        const sections = ['tab-sekolah', 'tab-cbt', 'tab-keamanan', 'tab-notifikasi'];
        window.addEventListener('scroll', function() {
            let current = '';
            sections.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    const rect = el.getBoundingClientRect();
                    if (rect.top <= 220 && rect.bottom >= 220) {
                        current = id;
                    }
                }
            });

            if (current) {
                const buttons = document.querySelectorAll('.tab-btn');
                buttons.forEach(btn => {
                    const isTarget = btn.getAttribute('data-target') === current;
                    const badge = btn.querySelector('.active-badge');
                    if (isTarget) {
                        if (!badge) {
                            btn.className = 'tab-btn px-4 py-2.5 rounded-lg font-label-lg text-label-lg transition-all flex items-center gap-2 bg-primary-container text-on-primary shadow-sm';
                            const newBadge = document.createElement('span');
                            newBadge.className = 'active-badge px-2 py-0.5 rounded-full bg-surface-container-lowest/20 text-on-primary font-label-sm text-label-sm';
                            newBadge.textContent = 'Aktif';
                            btn.appendChild(newBadge);
                        }
                    } else {
                        btn.className = 'tab-btn px-4 py-2.5 rounded-lg font-label-lg text-label-lg text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-all flex items-center gap-2';
                        if (badge) badge.remove();
                    }
                });
            }
        });
    });
</script>
@endsection
