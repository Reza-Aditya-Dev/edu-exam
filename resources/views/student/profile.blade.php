@extends('layouts.student')

@section('title', 'Profil Saya — EduExam')
@section('page_title', 'Profil')

@push('styles')
<style>
    /* Card Print Optimization */
    @media print {
        body * {
            visibility: hidden !important;
        }
        #cardFrontSide, #cardBackSide,
        #cardFrontSide *, #cardBackSide * {
            visibility: visible !important;
        }
        #cardDisplayContainer {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 10mm !important;
            display: flex !important;
            flex-direction: row !important;
            justify-content: center !important;
            align-items: flex-start !important;
            gap: 12mm !important;
            background: #ffffff !important;
            overflow: visible !important;
        }
        .card-side-element {
            display: flex !important;
            width: 86mm !important;
            min-height: 54mm !important;
            height: auto !important;
            max-width: 86mm !important;
            box-shadow: none !important;
            border: 1px dashed #94a3b8 !important;
            border-radius: 4mm !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            overflow: visible !important;
            padding: 3.5mm 4.5mm !important;
        }
        @page {
            size: A4 portrait;
            margin: 8mm;
        }
    }
</style>
@endpush

@section('student-content')
<div class="flex flex-col w-full pb-16">
    <div class="flex flex-col gap-space-lg w-full max-w-[1400px] mx-auto">

        <!-- ═══ 1. HERO PROFILE SECTION ═══ -->
        <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden flex flex-col border border-[#E3E8F5]">
            <!-- Indigo top accent line -->
            <div class="h-1.5 w-full bg-primary-container"></div>
            
            <div class="p-space-lg lg:p-space-xl flex flex-col xl:flex-row xl:items-center justify-between gap-space-lg">
                <!-- Student Identity Group -->
                <div class="flex items-start sm:items-center gap-space-lg">
                    <!-- Avatar + Camera Overlay -->
                    <div class="relative shrink-0">
                        <div class="w-[104px] h-[104px] rounded-full overflow-hidden bg-surface-container shadow-md border-2 border-surface-container-lowest">
                            <img id="avatarImagePreview" 
                                 class="w-full h-full object-cover" 
                                 src="{{ $user->avatar_url }}" 
                                 alt="{{ $user->name }}" />
                        </div>
                        <button class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-primary-container text-on-primary flex items-center justify-center shadow-md hover:bg-primary transition-transform hover:scale-105 cursor-pointer border border-surface-container-lowest" 
                                onclick="document.getElementById('avatarFileInput').click()" 
                                title="Ubah Foto Profil" 
                                type="button">
                            <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                        </button>
                    </div>

                    <!-- Name & Primary Identifiers -->
                    <div class="flex flex-col gap-space-xs min-w-0">
                        <div class="flex items-center flex-wrap gap-space-xs">
                            <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold leading-tight">{{ $user->name }}</h1>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant font-label-sm text-label-sm font-semibold">
                                <span class="material-symbols-outlined text-[14px]">school</span>
                                Siswa
                            </span>
                        </div>
                        <div class="flex items-center flex-wrap gap-space-xs mt-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-surface-container text-primary font-label-md text-label-md font-semibold">
                                {{ $classroom ? $classroom->name : 'Siswa' }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-surface-container-low text-on-surface-variant font-label-md text-label-md font-medium">
                                NIS: {{ $user->nis ?? '-' }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container-low text-tertiary font-label-md text-label-md font-medium">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Siswa Aktif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Academic Metadata Badges & Actions -->
                <div class="flex flex-col sm:flex-row xl:flex-col items-start xl:items-end justify-between gap-space-md shrink-0">
                    <!-- Institutional Quick Pills -->
                    <div class="flex items-center flex-wrap gap-space-xs">
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-container-low text-on-surface">
                            <span class="material-symbols-outlined text-[16px] text-primary">verified</span>
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Status:</span>
                            <span class="font-label-sm text-label-sm font-semibold text-primary">Aktif</span>
                        </div>
                        
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-container-low text-on-surface">
                            <span class="material-symbols-outlined text-[16px] text-on-surface-variant">account_balance</span>
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Sekolah:</span>
                            <span class="font-label-sm text-label-sm font-semibold text-on-surface">{{ \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-space-xs w-full sm:w-auto">
                        <button class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-surface-container-low text-on-surface font-label-lg text-label-lg hover:bg-surface-container transition-colors shadow-sm cursor-pointer" 
                                onclick="switchTab('biodata'); document.getElementById('studentPhone')?.focus();" 
                                type="button">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                            <span>Edit Kontak</span>
                        </button>
                        <button class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg hover:bg-on-primary-fixed-variant transition-colors shadow-sm cursor-pointer" 
                                onclick="switchTab('kartu-pelajar')" 
                                type="button">
                            <span class="material-symbols-outlined text-[18px]">badge</span>
                            <span>Lihat Kartu Pelajar</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Upload Form (Hidden) -->
            <form id="avatarUploadForm" action="{{ route('student.profile.avatar') }}" method="POST" enctype="multipart/form-data" class="hidden">
                @csrf
                <input type="file" id="avatarFileInput" name="avatar" accept="image/png,image/jpeg,image/webp" onchange="handleAvatarSelected(this)">
            </form>
        </div>

        <!-- ═══ 2. ACADEMIC KPI METRICS BAR (4 CARDS) ═══ -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-md">
            <!-- KPI 1: Total Ujian -->
            <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm flex items-center gap-space-md hover:shadow-md transition-shadow border border-[#E3E8F5]">
                <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center shrink-0 text-primary">
                    <span class="material-symbols-outlined text-[26px]">assignment</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="font-display-lg text-display-lg text-on-surface leading-none font-bold">{{ $totalExams }}</span>
                    <span class="font-headline-sm text-headline-sm text-on-surface mt-1 font-semibold">Total Ujian</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant">Sesi ujian diikuti</span>
                </div>
            </div>

            <!-- KPI 2: Ujian Tuntas -->
            <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm flex items-center gap-space-md hover:shadow-md transition-shadow border border-[#E3E8F5]">
                <div class="w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center shrink-0 text-primary-container">
                    <span class="material-symbols-outlined text-[26px]">task_alt</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="font-display-lg text-display-lg text-on-surface leading-none font-bold">{{ $passedExams }}</span>
                    <span class="font-headline-sm text-headline-sm text-on-surface mt-1 font-semibold">Ujian Tuntas</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant">Ujian selesai dinilai</span>
                </div>
            </div>

            <!-- KPI 3: Rata-rata Nilai -->
            <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm flex items-center gap-space-md hover:shadow-md transition-shadow border border-[#E3E8F5]">
                <div class="w-12 h-12 rounded-xl bg-surface-container-low flex items-center justify-center shrink-0 text-tertiary">
                    <span class="material-symbols-outlined text-[26px]">trending_up</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <div class="flex items-baseline gap-1">
                        <span class="font-display-lg text-display-lg text-on-surface leading-none font-bold">{{ $avgScore }}</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant">/ 100</span>
                    </div>
                    <span class="font-headline-sm text-headline-sm text-on-surface mt-1 font-semibold">Rata-rata Nilai</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant">Dari skala evaluasi 100</span>
                </div>
            </div>

            <!-- KPI 4: Nilai Terbaik -->
            <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm flex items-center gap-space-md hover:shadow-md transition-shadow border border-[#E3E8F5]">
                <div class="w-12 h-12 rounded-xl bg-secondary-container flex items-center justify-center shrink-0 text-on-secondary-fixed-variant">
                    <span class="material-symbols-outlined text-[26px]">military_tech</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <div class="flex items-baseline gap-1">
                        <span class="font-display-lg text-display-lg text-on-surface leading-none font-bold">{{ $highestScore }}</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant">/ 100</span>
                    </div>
                    <span class="font-headline-sm text-headline-sm text-on-surface mt-1 font-semibold">Nilai Terbaik</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant">Capaian tertinggi semester ini</span>
                </div>
            </div>
        </div>

        <!-- ═══ 3. SEGMENTED NAVIGATION TABS ═══ -->
        <div class="flex items-center gap-space-xs p-1.5 bg-surface-container-low rounded-xl w-fit shadow-xs">
            <button class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg shadow-sm transition-all cursor-pointer" 
                    id="tab-btn-biodata" 
                    onclick="switchTab('biodata')" 
                    type="button">
                <span class="material-symbols-outlined text-[18px]">person</span>
                <span>Biodata &amp; Sekolah</span>
            </button>
            <button class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-all font-label-lg text-label-lg cursor-pointer" 
                    id="tab-btn-kartu-pelajar" 
                    onclick="switchTab('kartu-pelajar')" 
                    type="button">
                <span class="material-symbols-outlined text-[18px]">badge</span>
                <span>Kartu Pelajar Digital</span>
            </button>
            <button class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-all font-label-lg text-label-lg cursor-pointer" 
                    id="tab-btn-keamanan" 
                    onclick="switchTab('keamanan')" 
                    type="button">
                <span class="material-symbols-outlined text-[18px]">lock</span>
                <span>Keamanan Akun</span>
            </button>
        </div>

        <!-- ═══ 4. TAB PANE: BIODATA (Active by default) ═══ -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg w-full" id="tab-pane-biodata">
            <!-- Left Column (~60%): Identitas Resmi Sekolah -->
            <div class="lg:col-span-7 flex flex-col gap-space-lg">
                <div class="bg-surface-container-lowest rounded-xl p-space-lg lg:p-space-xl shadow-sm flex flex-col justify-between h-full border border-[#E3E8F5]">
                    <div>
                        <!-- Header -->
                        <div class="flex items-start justify-between gap-space-md pb-space-md border-b border-[#E3E8F5]">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary shrink-0">
                                    <span class="material-symbols-outlined text-[22px]">verified_user</span>
                                </div>
                                <div>
                                    <h2 class="font-headline-md text-headline-md text-on-surface font-semibold">Identitas Resmi Sekolah</h2>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant">Data terdaftar resmi pada pangkalan data kurikulum {{ $schoolName ?? \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}.</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-surface-container text-primary font-label-sm text-label-sm font-semibold shrink-0">
                                <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                Tervalidasi
                            </span>
                        </div>

                        <!-- Read-Only Key-Value Rows -->
                        <div class="flex flex-col mt-space-md divide-y divide-[#F1F5F9]">
                            <!-- Row 1 -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3.5 px-3 rounded-lg hover:bg-surface-container-low transition-colors">
                                <span class="font-label-md text-label-md text-on-surface-variant font-medium">Nomor Induk Siswa (NIS)</span>
                                <div class="flex items-center gap-2 mt-1 sm:mt-0">
                                    <span class="font-label-lg text-label-lg font-semibold text-on-surface font-mono">{{ $user->nis ?? '-' }}</span>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-surface-container text-on-surface-variant font-label-sm text-label-sm">
                                        <span class="material-symbols-outlined text-[12px]">lock</span>
                                        Resmi
                                    </span>
                                </div>
                            </div>
                            <!-- Row 2 -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3.5 px-3 rounded-lg hover:bg-surface-container-low transition-colors">
                                <span class="font-label-md text-label-md text-on-surface-variant font-medium">Nomor Induk Siswa Nasional (NISN)</span>
                                <div class="flex items-center gap-2 mt-1 sm:mt-0">
                                    <span class="font-label-lg text-label-lg font-semibold text-on-surface font-mono">{{ $user->nisn ?? '-' }}</span>
                                    <span class="material-symbols-outlined text-[16px] text-primary" title="Data Nasional Terverifikasi">verified</span>
                                </div>
                            </div>
                            <!-- Row 3 -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3.5 px-3 rounded-lg hover:bg-surface-container-low transition-colors">
                                <span class="font-label-md text-label-md text-on-surface-variant font-medium">Rombel / Kelas Aktif</span>
                                <span class="font-label-lg text-label-lg font-semibold text-on-surface mt-1 sm:mt-0">
                                    {{ $classroom ? $classroom->name . ' (' . ($classroom->major ?? 'Reguler') . ')' : 'Belum Ditugaskan' }}
                                </span>
                            </div>
                            <!-- Row 4 -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3.5 px-3 rounded-lg hover:bg-surface-container-low transition-colors">
                                <span class="font-label-md text-label-md text-on-surface-variant font-medium">Institusi Pendidikan</span>
                                <span class="font-label-lg text-label-lg font-semibold text-on-surface mt-1 sm:mt-0">
                                    {{ \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}
                                </span>
                            </div>
                            <!-- Row 5 -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3.5 px-3 rounded-lg hover:bg-surface-container-low transition-colors">
                                <span class="font-label-md text-label-md text-on-surface-variant font-medium">Tahun Ajaran &amp; Semester</span>
                                <span class="font-label-lg text-label-lg font-semibold text-on-surface mt-1 sm:mt-0">
                                    2026/2027 • Semester Ganjil
                                </span>
                            </div>
                            <!-- Row 6 -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3.5 px-3 rounded-lg hover:bg-surface-container-low transition-colors">
                                <span class="font-label-md text-label-md text-on-surface-variant font-medium">Status Registrasi Akademik</span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container text-primary font-label-md text-label-md font-semibold mt-1 sm:mt-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Terdaftar Aktif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Notice Card -->
                    <div class="mt-space-lg p-space-md rounded-xl bg-surface-container-low flex items-start gap-space-sm border border-surface-container">
                        <span class="material-symbols-outlined text-[20px] text-primary shrink-0 mt-0.5">info</span>
                        <div class="flex flex-col gap-0.5">
                            <span class="font-label-md text-label-md font-semibold text-on-surface">Catatan Regulasi Akademik</span>
                            <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                                Perubahan data nama lengkap, tanggal lahir, dan nomor induk resmi hanya dapat diajukan secara langsung melalui loket Bagian Tata Usaha (TU) sekolah.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column (~40%): Kontak & Domisili Interactive Form -->
            <div class="lg:col-span-5 flex flex-col">
                <form action="{{ route('student.profile.update') }}" method="POST" class="bg-surface-container-lowest rounded-xl p-space-lg lg:p-space-xl shadow-sm flex flex-col justify-between h-full border border-[#E3E8F5]" id="editContactForm">
                    @csrf
                    @method('PUT')

                    <div>
                        <!-- Form Header -->
                        <div class="flex items-center gap-3 pb-space-md border-b border-[#E3E8F5]">
                            <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary shrink-0">
                                <span class="material-symbols-outlined text-[22px]">contact_mail</span>
                            </div>
                            <div>
                                <h2 class="font-headline-md text-headline-md text-on-surface font-semibold">Kontak &amp; Domisili</h2>
                                <p class="font-body-sm text-body-sm text-on-surface-variant">Saluran komunikasi aktif notifikasi ujian &amp; administrasi.</p>
                            </div>
                        </div>

                        <!-- Form Inputs -->
                        <div class="flex flex-col gap-space-md mt-space-md">
                            <!-- Input: Telepon / WhatsApp -->
                            <div class="flex flex-col gap-1.5">
                                <label class="font-label-md text-label-md text-on-surface font-semibold flex items-center justify-between" for="studentPhone">
                                    <span>Nomor HP / WhatsApp Siswa</span>
                                    <span class="font-body-sm text-body-sm text-primary font-normal">Aktif Notifikasi</span>
                                </label>
                                <div class="relative flex items-center">
                                    <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-[20px] pointer-events-none">phone</span>
                                    <input class="w-full h-11 pl-11 pr-4 rounded-lg bg-surface-container-low text-on-surface font-body-md text-body-md focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container outline-none transition-all border border-[#E3E8F5]" 
                                           id="studentPhone" 
                                           name="phone" 
                                           placeholder="08xx-xxxx-xxxx" 
                                           type="tel" 
                                           value="{{ old('phone', $user->phone) }}"/>
                                </div>
                            </div>

                            <!-- Input: Email (Readonly) -->
                            <div class="flex flex-col gap-1.5">
                                <label class="font-label-md text-label-md text-on-surface font-semibold" for="studentEmail">
                                    Alamat Email Terdaftar
                                </label>
                                <div class="relative flex items-center">
                                    <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-[20px] pointer-events-none">mail</span>
                                    <input class="w-full h-11 pl-11 pr-4 rounded-lg bg-surface-container-low text-on-surface font-body-md text-body-md outline-none border border-[#E3E8F5] opacity-80 cursor-not-allowed" 
                                           id="studentEmail" 
                                           readonly 
                                           type="email" 
                                           value="{{ $user->email }}"/>
                                </div>
                            </div>

                            <!-- Input: Username (Readonly) -->
                            <div class="flex flex-col gap-1.5">
                                <label class="font-label-md text-label-md text-on-surface font-semibold" for="studentUsername">
                                    Username Login
                                </label>
                                <div class="relative flex items-center">
                                    <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-[20px] pointer-events-none">account_circle</span>
                                    <input class="w-full h-11 pl-11 pr-4 rounded-lg bg-surface-container-low text-on-surface font-body-md text-body-md outline-none border border-[#E3E8F5] opacity-80 cursor-not-allowed font-mono" 
                                           id="studentUsername" 
                                           readonly 
                                           type="text" 
                                           value="{{ $user->username }}"/>
                                </div>
                            </div>

                            <!-- Textarea: Alamat Lengkap -->
                            <div class="flex flex-col gap-1.5">
                                <label class="font-label-md text-label-md text-on-surface font-semibold" for="studentAddress">
                                    Alamat Rumah Lengkap
                                </label>
                                <div class="relative">
                                    <textarea class="w-full p-3.5 rounded-lg bg-surface-container-low text-on-surface font-body-md text-body-md focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container outline-none transition-all border border-[#E3E8F5] resize-y leading-relaxed" 
                                              id="studentAddress" 
                                              name="address" 
                                              placeholder="Alamat domisili saat ini..." 
                                              rows="3">{{ old('address', $user->address) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-space-lg flex items-center justify-end gap-space-xs">
                        <button class="px-5 py-2.5 rounded-lg bg-surface-container-low text-on-surface font-label-lg text-label-lg hover:bg-surface-container transition-colors cursor-pointer" type="reset">
                            Batal
                        </button>
                        <button class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg hover:bg-primary transition-colors shadow-sm cursor-pointer" type="submit">
                            <span class="material-symbols-outlined text-[18px]">check</span>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ═══ 5. TAB PANE: KARTU PELAJAR (Hidden initially) ═══ -->
        @php
            $cardSchoolName = $schoolName ?? \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara');
            $cardPrincipalName = \App\Models\SchoolSetting::get('principal_name', 'Drs. H. Mulyadi, M.Pd');
            $cardPrincipalNip = \App\Models\SchoolSetting::get('principal_nip', '19780512 200312 1 002');
            $cardSchoolAddress = \App\Models\SchoolSetting::get('school_address', 'Jl. Garuda No. 45, Kebayoran Baru, Jakarta Selatan');
            $cardSchoolContact = \App\Models\SchoolSetting::get('school_contact', 'info@smanusantara.sch.id / +62 21-7201928');
            $studentCardSerial = 'ID-EDU-' . ($user->nis ? $user->nis : sprintf('%05d', $user->id)) . '-2026';
            $barcodeValue = $user->nis ? $user->nis : sprintf('%05d', $user->id);

            // Pola vector Barcode 1D (SVG inline agar 100% selalu tercetak tajam tanpa butuh internet)
            $barcodePattern = '11010110010110110';
            foreach (str_split((string)$barcodeValue) as $char) {
                $digit = ord($char) % 10;
                $digitPatterns = [
                    0 => '100110110', 1 => '110100101', 2 => '101100101', 3 => '110110010', 4 => '100101101',
                    5 => '110010101', 6 => '101001101', 7 => '100101011', 8 => '110010110', 9 => '101101001'
                ];
                $barcodePattern .= ($digitPatterns[$digit] ?? '101011001') . '0';
            }
            $barcodePattern .= '11011001011';

            $barcodeSvgPrint = '<svg width="23mm" height="4.5mm" viewBox="0 0 ' . (strlen($barcodePattern) * 2) . ' 18" preserveAspectRatio="none" style="display:block;"><rect width="100%" height="100%" fill="#ffffff" />';
            for ($bIdx = 0; $bIdx < strlen($barcodePattern); $bIdx++) {
                if ($barcodePattern[$bIdx] === '1') {
                    $barcodeSvgPrint .= '<rect x="' . ($bIdx * 2) . '" y="0" width="1.6" height="18" fill="#0f172a" />';
                }
            }
            $barcodeSvgPrint .= '</svg>';

            // Format teks biodata resmi yang muncul saat QR Code discan kamera HP
            $studentBioQrText = "=== BIODATA RESMI SISWA ===\n"
                . "Nama        : " . $user->name . "\n"
                . "NIS         : " . ($user->nis ?? '-') . "\n"
                . "NISN        : " . ($user->nisn ?? '-') . "\n"
                . "Kelas       : " . ($classroom ? $classroom->name : 'Siswa') . "\n"
                . "Sekolah     : " . $cardSchoolName . "\n"
                . "Email       : " . $user->email . "\n"
                . "Status      : AKTIF TERVERIFIKASI\n"
                . "Tahun Ajaran: 2026/2027\n"
                . "Sistem      : CBT EduExam Online";
            $studentBioQrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=0&format=png&data=' . rawurlencode($studentBioQrText);
        @endphp

        <div class="hidden flex-col gap-space-lg w-full" id="tab-pane-kartu-pelajar">
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-space-lg items-start">
                
                <!-- Main Card Preview Panel -->
                <div class="xl:col-span-8 bg-surface-container-lowest rounded-2xl p-4 sm:p-6 lg:p-8 shadow-sm flex flex-col items-center border border-[#E3E8F5]">
                    
                    <!-- Top Bar: Title & Action Buttons -->
                    <div class="w-full flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-[#E3E8F5]">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-[22px]">badge</span>
                                <h2 class="font-headline-md text-headline-md text-on-surface font-bold">Kartu Pelajar Digital Resmi</h2>
                            </div>
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">
                                Identitas resmi siswa untuk akses ruang ujian CBT, perpustakaan, dan verifikasi kehadiran.
                            </p>
                        </div>
                        
                        <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap shrink-0">
                            <!-- Flip Card Button -->
                            <button type="button" 
                                    onclick="toggleCardSide()" 
                                    id="btnFlipCard"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-surface-container text-on-surface font-label-md text-label-md hover:bg-surface-container-high transition-all active:scale-95 cursor-pointer border border-surface-container-highest">
                                <span class="material-symbols-outlined text-[18px] text-primary">sync</span>
                                <span id="flipButtonText">Balik Kartu</span>
                            </button>

                            <!-- Print Card Button -->
                            <button type="button" 
                                    onclick="printStudentCard()" 
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary-container text-on-primary font-label-md text-label-md hover:bg-primary shadow-sm hover:shadow transition-all active:scale-95 cursor-pointer">
                                <span class="material-symbols-outlined text-[18px]">print</span>
                                <span>Cetak Kartu Pelajar</span>
                            </button>
                        </div>
                    </div>

                    <!-- View Mode Selector Pill Tabs (Depan / Belakang / Keduanya) -->
                    <div class="w-full flex items-center justify-between mt-5 mb-4 flex-wrap gap-3">
                        <div class="inline-flex p-1 bg-surface-container-low rounded-xl border border-surface-container gap-1">
                            <button type="button" 
                                    onclick="setCardViewMode('front')" 
                                    id="tabCardFront"
                                    class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all bg-primary-container text-on-primary shadow-sm cursor-pointer">
                                Tampak Depan
                            </button>
                            <button type="button" 
                                    onclick="setCardViewMode('back')" 
                                    id="tabCardBack"
                                    class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-on-surface-variant hover:text-on-surface transition-all cursor-pointer">
                                Tampak Belakang
                            </button>
                            <button type="button" 
                                    onclick="setCardViewMode('both')" 
                                    id="tabCardBoth"
                                    class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-on-surface-variant hover:text-on-surface transition-all cursor-pointer">
                                Kedua Sisi (Siap Cetak)
                            </button>
                        </div>

                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>QR Code Biodata Aktif</span>
                        </div>
                    </div>

                    <!-- ════════════ DISPLAY AREA KARTU PELAJAR ════════════ -->
                    <div class="w-full flex flex-col items-center justify-center my-3 gap-6" id="cardDisplayContainer">
                        
                        <!-- ──────────────── SISI DEPAN (FRONT SIDE) ──────────────── -->
                        <div id="cardFrontSide" 
                             class="card-side-element w-full max-w-[490px] aspect-[85.6/53.98] rounded-2xl bg-gradient-to-br from-[#1e1b4b] via-[#2e1065] to-[#1e1b4b] text-white p-5 sm:p-6 shadow-2xl relative overflow-hidden flex flex-col justify-between border border-white/20 select-none transition-all duration-300">
                            
                            <!-- Glowing Aura & Cyber Pattern Overlay -->
                            <div class="absolute -right-16 -bottom-16 w-56 h-56 rounded-full bg-indigo-500/20 blur-3xl pointer-events-none"></div>
                            <div class="absolute -left-12 -top-12 w-48 h-48 rounded-full bg-purple-500/15 blur-2xl pointer-events-none"></div>
                            
                            <!-- Subtle Grid Mesh Graphic -->
                            <div class="absolute inset-0 opacity-10 pointer-events-none bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
                            
                            <!-- Watermark School Symbol in Background -->
                            <div class="absolute right-4 bottom-2 opacity-[0.07] pointer-events-none">
                                <span class="material-symbols-outlined text-[150px]">school</span>
                            </div>

                            <!-- FRONT HEADER -->
                            <div class="flex items-center justify-between z-10 gap-3 border-b border-white/15 pb-2.5">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-amber-400 to-amber-200 text-slate-900 flex items-center justify-center font-bold shadow-md shrink-0">
                                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">school</span>
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <h4 class="font-headline-sm text-xs sm:text-[13px] font-bold tracking-wide uppercase truncate leading-tight text-white">
                                            {{ $cardSchoolName }}
                                        </h4>
                                        <p class="text-[9px] sm:text-[10px] text-indigo-200 font-medium tracking-wider uppercase">
                                            KARTU TANDA PELAJAR DIGITAL
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <span class="px-2.5 py-0.5 rounded-full bg-white/10 backdrop-blur-md text-[10px] font-mono font-semibold tracking-wider text-amber-200 border border-white/10">
                                        2026/2027
                                    </span>
                                </div>
                            </div>

                            <!-- FRONT BODY: Photo & Main Info -->
                            <div class="flex items-center gap-4 z-10 my-auto py-1">
                                <!-- Student Photo -->
                                <div class="relative shrink-0">
                                    <div class="w-[84px] h-[100px] sm:w-[92px] sm:h-[110px] rounded-xl overflow-hidden bg-slate-900/60 p-1 border-2 border-white/30 shadow-lg">
                                        <img class="w-full h-full object-cover rounded-lg" 
                                             src="{{ $user->avatar_url }}" 
                                             alt="{{ $user->name }}" 
                                             onerror="this.src='{{ asset('images/default-avatar.png') }}'" />
                                    </div>
                                    <span class="absolute -bottom-1 -right-1 px-1.5 py-0.2 rounded-full bg-emerald-500 text-white text-[8.5px] font-bold tracking-wide shadow flex items-center gap-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                        AKTIF
                                    </span>
                                </div>

                                <!-- Student Details -->
                                <div class="flex flex-col min-w-0 flex-1">
                                    <h3 class="font-headline-sm text-sm sm:text-base font-extrabold text-white truncate leading-tight drop-shadow-sm">
                                        {{ $user->name }}
                                    </h3>
                                    
                                    <div class="grid grid-cols-1 gap-0.5 mt-1.5 text-[10px] sm:text-[11px]">
                                        <div class="flex items-center gap-1.5 text-indigo-200 font-mono">
                                            <span class="text-white/60 text-[9.5px] font-sans">NIS:</span>
                                            <span class="font-bold text-white tracking-wider">{{ $user->nis ?? '-' }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-indigo-200 font-mono">
                                            <span class="text-white/60 text-[9.5px] font-sans">NISN:</span>
                                            <span class="font-semibold text-indigo-100">{{ $user->nisn ?? '-' }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-white/15 backdrop-blur-md text-[10.5px] font-bold text-white border border-white/15">
                                            <span class="material-symbols-outlined text-[12px] text-amber-300">class</span>
                                            {{ $classroom ? $classroom->name : 'Siswa' }}
                                        </span>
                                        <span class="text-[9.5px] text-indigo-200 uppercase tracking-wider font-mono">
                                            CBT-VERIFIED
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- FRONT FOOTER: Dynamic QR Code, Authentic 1D Barcode & Holographic Security Chip -->
                            <div class="flex items-end justify-between z-10 pt-2 border-t border-white/15">
                                <!-- QR Code Box with Camera Scan Prompt -->
                                <div class="flex items-center gap-2.5">
                                    <div class="p-1 bg-white rounded-lg shadow-md shrink-0 flex items-center justify-center">
                                        <img id="mainCardQrImg"
                                             crossorigin="anonymous"
                                             src="{{ $studentBioQrUrl }}" 
                                             alt="QR Code Biodata Siswa" 
                                             class="w-11 h-11 sm:w-12 sm:h-12 object-contain"
                                             title="Pindai dengan kamera untuk melihat biodata resmi siswa" />
                                    </div>
                                    <div class="flex flex-col text-[8.5px] sm:text-[9.5px] leading-tight">
                                        <span class="text-amber-300 font-bold uppercase tracking-wider flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[11px]">qr_code_scanner</span>
                                            Scan Biodata
                                        </span>
                                        <span class="text-white/70 font-mono mt-0.5">{{ $studentCardSerial }}</span>
                                        <span class="text-indigo-200 text-[8px]">Pindai via Kamera HP</span>
                                    </div>
                                </div>

                                <!-- Realistic Gold Smart Card Microchip & 1D Barcode NIS Graphic -->
                                <div class="flex items-end gap-2">
                                    <!-- Authentic 1D Barcode Vector Card -->
                                    <div class="hidden xs:flex flex-col items-center bg-white px-1.5 py-0.5 rounded-md shadow-sm border border-white/20">
                                        <svg width="68" height="13" viewBox="0 0 {{ strlen($barcodePattern) * 2 }} 18" preserveAspectRatio="none" class="block">
                                            <rect width="100%" height="100%" fill="#ffffff" />
                                            @for ($bIdx = 0; $bIdx < strlen($barcodePattern); $bIdx++)
                                                @if ($barcodePattern[$bIdx] === '1')
                                                    <rect x="{{ $bIdx * 2 }}" y="0" width="1.6" height="18" fill="#0f172a" />
                                                @endif
                                            @endfor
                                        </svg>
                                        <span class="text-[6.5px] font-mono font-bold text-slate-900 tracking-wider leading-none mt-0.5">* {{ $barcodeValue }} *</span>
                                    </div>

                                    <!-- Gold Contact Chip Graphic -->
                                    <div class="w-8 h-6 rounded-md bg-gradient-to-tr from-amber-500 via-amber-300 to-yellow-200 p-0.5 shadow-sm border border-amber-600/60 flex flex-col justify-between shrink-0" title="Smart RFID Security">
                                        <div class="h-1 border-b border-amber-700/40"></div>
                                        <div class="flex justify-between h-2 px-0.5">
                                            <div class="w-1 border-r border-amber-700/40"></div>
                                            <div class="w-1 border-l border-amber-700/40"></div>
                                        </div>
                                        <div class="h-1 border-t border-amber-700/40"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ──────────────── SISI BELAKANG (BACK SIDE) ──────────────── -->
                        <div id="cardBackSide" 
                             class="card-side-element hidden w-full max-w-[490px] aspect-[85.6/53.98] rounded-2xl bg-gradient-to-br from-[#0b172a] via-[#0f2444] to-[#081322] text-white p-5 sm:p-6 shadow-2xl relative overflow-hidden flex flex-col justify-between border border-white/20 select-none transition-all duration-300">
                            
                            <!-- Magnetic Stripe across top -->
                            <div class="absolute left-0 right-0 top-3 h-8 bg-gradient-to-b from-[#111827] via-[#030712] to-[#111827] border-y border-white/10 flex items-center justify-end px-6 pointer-events-none">
                                <span class="text-[8px] font-mono text-white/40 tracking-widest">MAGNETIC RFID COMPLIANT • CBT INTEGRATED</span>
                            </div>

                            <!-- Hologram Security Line -->
                            <div class="absolute left-0 right-0 top-11 h-[2px] bg-gradient-to-r from-transparent via-cyan-400 to-transparent opacity-40 pointer-events-none"></div>

                            <!-- Watermark Background -->
                            <div class="absolute right-6 top-16 opacity-[0.06] pointer-events-none">
                                <span class="material-symbols-outlined text-[130px]">verified_user</span>
                            </div>

                            <!-- BACK HEADER (Positioned below magnetic stripe) -->
                            <div class="mt-7 flex items-center justify-between z-10 pb-1.5 border-b border-white/10">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-cyan-400 text-[18px]">contactless</span>
                                    <span class="font-headline-sm text-[11px] sm:text-xs font-bold tracking-wider uppercase text-white">
                                        TATA TERTIB & KETENTUAN KARTU
                                    </span>
                                </div>
                                <span class="text-[9px] text-cyan-300 font-mono font-semibold">
                                    OFFICIAL CARD
                                </span>
                            </div>

                            <!-- BACK BODY: Official School Rules & Guidelines -->
                            <div class="z-10 space-y-1 my-auto text-[9px] sm:text-[9.5px] leading-relaxed text-slate-200">
                                <div class="flex items-start gap-1.5">
                                    <span class="font-bold text-amber-300 shrink-0">1.</span>
                                    <p>Kartu ini adalah identitas resmi siswa <strong class="text-white">{{ $cardSchoolName }}</strong> yang wajib dibawa selama ujian CBT & pembelajaran.</p>
                                </div>
                                <div class="flex items-start gap-1.5">
                                    <span class="font-bold text-amber-300 shrink-0">2.</span>
                                    <p>Pindai (scan) QR Code pada kartu untuk verifikasi keaslian biodata siswa secara real-time di server sekolah.</p>
                                </div>
                                <div class="flex items-start gap-1.5">
                                    <span class="font-bold text-amber-300 shrink-0">3.</span>
                                    <p>Dilarang memindahtangankan, meminjamkan, atau memalsukan kartu identitas ini kepada pihak lain.</p>
                                </div>
                                <div class="flex items-start gap-1.5">
                                    <span class="font-bold text-amber-300 shrink-0">4.</span>
                                    <p>Jika kartu hilang atau ditemukan, mohon diserahkan ke bagian Tata Usaha / Sekretariat Sekolah.</p>
                                </div>
                            </div>

                            <!-- BACK FOOTER: School Contact, Digital Seal & Principal Signature -->
                            <div class="flex items-end justify-between z-10 pt-2 border-t border-white/10 gap-2">
                                <!-- School Contact Info -->
                                <div class="flex flex-col text-[8px] sm:text-[8.5px] text-slate-300 leading-tight max-w-[55%]">
                                    <span class="font-bold text-white text-[9px] uppercase truncate">{{ $cardSchoolName }}</span>
                                    <span class="truncate mt-0.5 text-slate-400">{{ $cardSchoolAddress }}</span>
                                    <span class="text-cyan-300 truncate mt-0.5">{{ $cardSchoolContact }}</span>
                                </div>

                                <!-- Official Digital Stamp & Principal Validation -->
                                <div class="flex items-center gap-3 shrink-0">
                                    <!-- Digital Stamp Badge -->
                                    <div class="w-10 h-10 rounded-full border-2 border-cyan-400/80 bg-cyan-950/40 flex flex-col items-center justify-center text-center p-0.5 -rotate-6 shadow-sm">
                                        <span class="material-symbols-outlined text-[13px] text-cyan-300" style="font-variation-settings: 'FILL' 1;">verified</span>
                                        <span class="text-[6px] font-bold text-cyan-200 uppercase leading-none">TERVERIFIKASI</span>
                                    </div>

                                    <!-- Principal Signature Space -->
                                    <div class="flex flex-col items-center text-center">
                                        <span class="text-[7.5px] text-slate-400 leading-none">Kepala Sekolah,</span>
                                        <!-- Cursive Digital Signature Accent -->
                                        <div class="my-0.5 text-cyan-200 italic font-serif text-[11px] leading-none select-none tracking-tight">
                                            {{ Str::limit($cardPrincipalName, 16) }}
                                        </div>
                                        <span class="text-[8.5px] font-bold text-white underline leading-none">{{ $cardPrincipalName }}</span>
                                        <span class="text-[7px] text-slate-400 font-mono mt-0.5">NIP. {{ $cardPrincipalNip }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <!-- Tips Text below preview -->
                    <div class="w-full flex items-center justify-between text-xs text-on-surface-variant pt-4 border-t border-[#E3E8F5] mt-2">
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-primary">info</span>
                            Arahkan kamera smartphone ke QR Code untuk membaca biodata lengkap.
                        </span>
                        <span class="font-mono text-[11px] text-on-surface-variant hidden sm:inline-block">
                            Ukuran Cetak Standar: CR80 (85.6mm x 53.98mm)
                        </span>
                    </div>

                </div>

                <!-- Right Side: Card Feature Guide & Technical Specifications -->
                <div class="xl:col-span-4 bg-surface-container-lowest rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col gap-4 border border-[#E3E8F5]">
                    
                    <div class="flex items-center gap-3 pb-4 border-b border-[#E3E8F5]">
                        <div class="w-10 h-10 rounded-xl bg-primary-container/10 flex items-center justify-center text-primary shrink-0">
                            <span class="material-symbols-outlined text-[22px]">contactless</span>
                        </div>
                        <div>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Fitur Kartu Digital</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Panduan penggunaan & cetak mandiri.</p>
                        </div>
                    </div>

                    <!-- Feature Box 1: QR Biodata Scan -->
                    <div class="p-3.5 rounded-xl bg-surface-container-low border border-surface-container flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                            <span class="material-symbols-outlined text-[18px]">qr_code_scanner</span>
                        </div>
                        <div class="text-xs">
                            <h4 class="font-bold text-on-surface">QR Code Biodata Terintegrasi</h4>
                            <p class="text-on-surface-variant mt-0.5 leading-relaxed">
                                Kamera HP atau Google Lens yang memindai QR Code ini akan langsung menampilkan Nama, NIS, NISN, Kelas, dan Nama Sekolah secara akurat.
                            </p>
                        </div>
                    </div>

                    <!-- Feature Box 2: Dedicated Clean Print -->
                    <div class="p-3.5 rounded-xl bg-surface-container-low border border-surface-container flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-primary-container/10 text-primary flex items-center justify-center shrink-0 mt-0.5">
                            <span class="material-symbols-outlined text-[18px]">print</span>
                        </div>
                        <div class="text-xs">
                            <h4 class="font-bold text-on-surface">Cetak Khusus Kartu Saja</h4>
                            <p class="text-on-surface-variant mt-0.5 leading-relaxed">
                                Tombol cetak dirancang secara presisi untuk mencetak <strong>hanya kartu pelajar</strong> (tampak depan & tampak belakang berdampingan) tanpa elemen website.
                            </p>
                        </div>
                    </div>

                    <!-- Feature Box 3: Double-Sided Design -->
                    <div class="p-3.5 rounded-xl bg-surface-container-low border border-surface-container flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-600 flex items-center justify-center shrink-0 mt-0.5">
                            <span class="material-symbols-outlined text-[18px]">flip</span>
                        </div>
                        <div class="text-xs">
                            <h4 class="font-bold text-on-surface">Desain Modern Bolak-Balik</h4>
                            <p class="text-on-surface-variant mt-0.5 leading-relaxed">
                                Dilengkapi magnetic stripe simulator, poin tata tertib resmi, dan tanda tangan digital kepala sekolah untuk legalitas kartu identitas.
                            </p>
                        </div>
                    </div>

                    <!-- Quick Print CTA Banner -->
                    <div class="mt-2 p-4 rounded-xl bg-gradient-to-br from-primary-container to-primary text-on-primary flex flex-col gap-2.5">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">local_printshop</span>
                            <span class="font-bold text-xs uppercase tracking-wide">Siap Dicetak & Dilaminasi</span>
                        </div>
                        <p class="text-[11px] text-white/80 leading-relaxed">
                            Cetak di kertas tebal (Art Paper 260/310 gsm atau PVC Card) kemudian gunting sesuai garis panduan untuk hasil kartu fisik yang profesional.
                        </p>
                        <button type="button" 
                                onclick="printStudentCard()" 
                                class="w-full py-2.5 rounded-lg bg-white text-primary font-bold text-xs shadow hover:bg-slate-50 transition-all flex items-center justify-center gap-1.5 cursor-pointer mt-1">
                            <span class="material-symbols-outlined text-[16px]">print</span>
                            <span>Cetak Sekarang (PDF / Print)</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ 6. TAB PANE: KEAMANAN (Hidden initially) ═══ -->
        <div class="hidden flex-col gap-space-lg w-full" id="tab-pane-keamanan">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg">
                <!-- Password Change Card -->
                <div class="lg:col-span-7 bg-surface-container-lowest rounded-xl p-space-lg lg:p-space-xl shadow-sm flex flex-col justify-between border border-[#E3E8F5]">
                    <form action="{{ route('student.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div>
                            <div class="flex items-center gap-3 pb-space-md border-b border-[#E3E8F5]">
                                <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary shrink-0">
                                    <span class="material-symbols-outlined text-[22px]">password</span>
                                </div>
                                <div>
                                    <h2 class="font-headline-md text-headline-md text-on-surface font-semibold">Ganti Kata Sandi</h2>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant">Perbarui password akun untuk keamanan sesi ujian CBT.</p>
                                </div>
                            </div>

                            @if($errors->has('current_password') || $errors->has('password'))
                                <div class="mt-3 p-3 bg-error-container/50 border border-error/30 rounded-xl text-error text-xs flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">error</span>
                                    <span>{{ $errors->first('current_password') ?: $errors->first('password') }}</span>
                                </div>
                            @endif

                            <div class="flex flex-col gap-space-md mt-space-md">
                                <div class="flex flex-col gap-1.5">
                                    <label class="font-label-md text-label-md text-on-surface font-semibold" for="currentPasswordInput">
                                        Kata Sandi Saat Ini
                                    </label>
                                    <div class="relative">
                                        <input class="w-full h-11 pl-4 pr-11 rounded-lg bg-surface-container-low text-on-surface font-body-md text-body-md focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container outline-none transition-all border border-[#E3E8F5]" 
                                               id="currentPasswordInput" 
                                               name="current_password" 
                                               placeholder="••••••••••••" 
                                               required 
                                               type="password"/>
                                        <button type="button" onclick="togglePasswordVisibility('currentPasswordInput', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface cursor-pointer">
                                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-1.5">
                                    <label class="font-label-md text-label-md text-on-surface font-semibold" for="newPasswordInput">
                                        Kata Sandi Baru
                                    </label>
                                    <div class="relative">
                                        <input class="w-full h-11 pl-4 pr-11 rounded-lg bg-surface-container-low text-on-surface font-body-md text-body-md focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container outline-none transition-all border border-[#E3E8F5]" 
                                               id="newPasswordInput" 
                                               name="password" 
                                               placeholder="Minimal 6 karakter" 
                                               required 
                                               type="password"/>
                                        <button type="button" onclick="togglePasswordVisibility('newPasswordInput', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface cursor-pointer">
                                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-1.5">
                                    <label class="font-label-md text-label-md text-on-surface font-semibold" for="confirmPasswordInput">
                                        Konfirmasi Kata Sandi Baru
                                    </label>
                                    <div class="relative">
                                        <input class="w-full h-11 pl-4 pr-11 rounded-lg bg-surface-container-low text-on-surface font-body-md text-body-md focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container outline-none transition-all border border-[#E3E8F5]" 
                                               id="confirmPasswordInput" 
                                               name="password_confirmation" 
                                               placeholder="Ulangi kata sandi baru" 
                                               required 
                                               type="password"/>
                                        <button type="button" onclick="togglePasswordVisibility('confirmPasswordInput', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface cursor-pointer">
                                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-space-lg flex justify-end gap-space-xs">
                            <button class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg hover:bg-primary transition-colors shadow-sm cursor-pointer" type="submit">
                                <span class="material-symbols-outlined text-[18px]">lock_reset</span>
                                <span>Simpan Kata Sandi</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Session & Device History -->
                <div class="lg:col-span-5 bg-surface-container-lowest rounded-xl p-space-lg lg:p-space-xl shadow-sm flex flex-col justify-between border border-[#E3E8F5]">
                    <div>
                        <div class="flex items-center gap-3 pb-space-md border-b border-[#E3E8F5]">
                            <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary shrink-0">
                                <span class="material-symbols-outlined text-[22px]">devices</span>
                            </div>
                            <div>
                                <h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">Sesi Login Aktif</h3>
                                <p class="font-body-sm text-body-sm text-on-surface-variant">Perangkat yang sedang login akun ini.</p>
                            </div>
                        </div>

                        <div class="flex flex-col gap-space-sm mt-space-md">
                            <div class="flex items-center justify-between p-3.5 rounded-xl bg-surface-container-low border border-surface-container">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary text-[24px]">devices</span>
                                    <div class="flex flex-col">
                                        <span class="font-label-md text-label-md font-semibold text-on-surface">Sesi Saat Ini (Browser Aktif)</span>
                                        <span class="font-body-sm text-body-sm text-on-surface-variant">IP: {{ request()->ip() }} • {{ now()->translatedFormat('d M Y, H:i') }} WIB</span>
                                    </div>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full bg-surface-container text-primary font-label-sm text-label-sm font-semibold">Aktif</span>
                            </div>
                        </div>

                        <!-- Theme Preference Section -->
                        <div class="mt-space-md pt-space-md border-t border-[#E3E8F5]">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary shrink-0">
                                    <span class="material-symbols-outlined text-[20px]">palette</span>
                                </div>
                                <div>
                                    <h4 class="font-headline-sm text-sm font-semibold text-on-surface">Tema Tampilan</h4>
                                    <p class="font-body-sm text-xs text-on-surface-variant">Pilih tema terang atau gelap sesuai kenyamanan belajar Anda.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2.5">
                                <button type="button" 
                                        onclick="setStudentTheme('light')" 
                                        id="themeOptionLight"
                                        class="theme-choice-btn flex items-center justify-center gap-2 p-3 rounded-xl border border-surface-container bg-surface-container-low hover:bg-surface-container transition-all text-xs font-semibold cursor-pointer">
                                    <span class="material-symbols-outlined text-[18px] text-amber-500">light_mode</span>
                                    <span>Mode Terang</span>
                                </button>
                                <button type="button" 
                                        onclick="setStudentTheme('dark')" 
                                        id="themeOptionDark"
                                        class="theme-choice-btn flex items-center justify-center gap-2 p-3 rounded-xl border border-surface-container bg-surface-container-low hover:bg-surface-container transition-all text-xs font-semibold cursor-pointer">
                                    <span class="material-symbols-outlined text-[18px] text-indigo-400">dark_mode</span>
                                    <span>Mode Gelap</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pt-space-lg">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full py-2.5 rounded-lg bg-surface-container-low text-error hover:bg-error-container/40 hover:text-error transition-colors font-label-md text-label-md font-semibold text-center cursor-pointer border border-error/20 flex items-center justify-center gap-2" 
                                    type="submit" 
                                    onclick="return confirm('Apakah Anda yakin ingin keluar dari akun?')">
                                <span class="material-symbols-outlined text-[18px]">logout</span>
                                <span>Keluar Dari Akun Ini</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ FOOTER LOGOUT ACTION (Paling Bawah) ═══ -->
        <div class="w-full mt-2 p-4 sm:p-5 rounded-2xl bg-surface-container-lowest border border-[#E3E8F5] shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-error-container/40 text-error flex items-center justify-center shrink-0 shadow-xs">
                    <span class="material-symbols-outlined text-[24px]">logout</span>
                </div>
                <div class="flex flex-col text-center sm:text-left">
                    <span class="font-headline-sm text-sm sm:text-base font-bold text-on-surface">Keluar dari Akun Siswa</span>
                    <span class="font-body-sm text-xs text-on-surface-variant mt-0.5">Selesaikan sesi aktif Anda pada perangkat ini dengan aman.</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                @csrf
                <button type="submit" 
                        onclick="return confirm('Apakah Anda yakin ingin keluar dari akun?')" 
                        class="w-full sm:w-auto px-6 py-2.5 sm:py-3 rounded-xl bg-error hover:bg-red-700 text-white font-label-lg text-xs sm:text-sm font-bold shadow-sm hover:shadow transition-all flex items-center justify-center gap-2 active:scale-95 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                    <span>Keluar (Logout)</span>
                </button>
            </form>
        </div>

    </div>
</div>

<!-- ═══ 7. MODAL PRATINJAU FOTO PROFIL SEBELUM UNGGAH ═══ -->
<div id="avatarPreviewModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="w-full max-w-sm bg-surface-container-lowest rounded-2xl shadow-2xl border border-surface-container p-6 flex flex-col items-center text-center animate-[scaleIn_0.15s_ease-out]">
        <div class="w-12 h-12 rounded-2xl bg-primary-fixed flex items-center justify-center text-primary mb-3">
            <span class="material-symbols-outlined text-[24px]">crop_original</span>
        </div>
        <h3 class="font-headline-sm text-base font-bold text-on-surface">Pratinjau Foto Profil</h3>
        <p class="text-xs text-on-surface-variant mt-1 mb-4">Pastikan wajah Anda terlihat jelas untuk tanda pengenal kartu ujian.</p>

        <!-- Preview Image Container -->
        <div class="w-32 h-32 rounded-full overflow-hidden ring-4 ring-primary shadow-md mb-5 bg-surface-container">
            <img id="avatarModalPreviewImg" src="" alt="Pratinjau Foto" class="w-full h-full object-cover">
        </div>

        <div class="flex items-center gap-2 w-full">
            <button type="button" onclick="cancelAvatarUpload()" class="flex-1 py-2.5 rounded-xl bg-surface-container text-on-surface font-semibold text-xs hover:bg-surface-container-high transition-colors cursor-pointer">
                Pilih Ulang
            </button>
            <button type="button" onclick="confirmAvatarUpload()" class="flex-1 py-2.5 rounded-xl bg-primary-container text-on-primary font-bold text-xs shadow-md hover:bg-primary transition-all cursor-pointer">
                Simpan Foto
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Tab Switching Logic
    function switchTab(tabId) {
        const tabs = ['biodata', 'kartu-pelajar', 'keamanan'];

        tabs.forEach(t => {
            const pane = document.getElementById('tab-pane-' + t);
            const btn = document.getElementById('tab-btn-' + t);

            if (t === tabId) {
                if (pane) {
                    pane.classList.remove('hidden');
                    pane.classList.add(t === 'biodata' ? 'grid' : 'flex');
                }
                if (btn) {
                    btn.className = "inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg shadow-sm transition-all cursor-pointer";
                }
            } else {
                if (pane) {
                    pane.classList.add('hidden');
                    pane.classList.remove('grid', 'flex');
                }
                if (btn) {
                    btn.className = "inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-all font-label-lg text-label-lg cursor-pointer";
                }
            }
        });
    }

    // Avatar Upload Selection & Modal Preview
    function handleAvatarSelected(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];

            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2 MB!');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const modal = document.getElementById('avatarPreviewModal');
                const modalImg = document.getElementById('avatarModalPreviewImg');
                if (modal && modalImg) {
                    modalImg.src = e.target.result;
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            };
            reader.readAsDataURL(file);
        }
    }

    function confirmAvatarUpload() {
        const form = document.getElementById('avatarUploadForm');
        if (form) form.submit();
    }

    function cancelAvatarUpload() {
        const modal = document.getElementById('avatarPreviewModal');
        const input = document.getElementById('avatarFileInput');
        if (input) input.value = '';
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    // Password Toggle Visibility
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('.material-symbols-outlined');
        if (input && icon) {
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }
    }

    // Automatically switch to security tab if there are password errors
    @if($errors->has('current_password') || $errors->has('password'))
        document.addEventListener('DOMContentLoaded', function() {
            switchTab('keamanan');
        });
    @endif

    // ════════ DIGITAL STUDENT CARD INTERACTIONS & PRINTING ════════
    let currentCardSide = 'front';

    function setCardViewMode(mode) {
        const front = document.getElementById('cardFrontSide');
        const back = document.getElementById('cardBackSide');
        const tabFront = document.getElementById('tabCardFront');
        const tabBack = document.getElementById('tabCardBack');
        const tabBoth = document.getElementById('tabCardBoth');
        const flipBtnText = document.getElementById('flipButtonText');

        const activeClass = "px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all bg-primary-container text-on-primary shadow-sm cursor-pointer";
        const inactiveClass = "px-3.5 py-1.5 rounded-lg text-xs font-semibold text-on-surface-variant hover:text-on-surface transition-all cursor-pointer";

        if (tabFront) tabFront.className = inactiveClass;
        if (tabBack) tabBack.className = inactiveClass;
        if (tabBoth) tabBoth.className = inactiveClass;

        if (mode === 'front') {
            currentCardSide = 'front';
            if (front) front.classList.remove('hidden');
            if (back) back.classList.add('hidden');
            if (tabFront) tabFront.className = activeClass;
            if (flipBtnText) flipBtnText.textContent = "Balik ke Belakang";
        } else if (mode === 'back') {
            currentCardSide = 'back';
            if (front) front.classList.add('hidden');
            if (back) back.classList.remove('hidden');
            if (tabBack) tabBack.className = activeClass;
            if (flipBtnText) flipBtnText.textContent = "Balik ke Depan";
        } else if (mode === 'both') {
            if (front) front.classList.remove('hidden');
            if (back) back.classList.remove('hidden');
            if (tabBoth) tabBoth.className = activeClass;
            if (flipBtnText) flipBtnText.textContent = "Balik Kartu";
        }
    }

    function toggleCardSide() {
        if (currentCardSide === 'front') {
            setCardViewMode('back');
        } else {
            setCardViewMode('front');
        }
    }

    // Function to Print ONLY the Student Card (Front + Back) with FULL VIEW and GUARANTEED BARCODE & QR CODE
    function printStudentCard() {
        // 1. Ekstrak DataURL Base64 dari QR code yang sudah ada di halaman preview jika memungkinkan
        let qrDataUrl = null;
        const mainQrImg = document.getElementById('mainCardQrImg');
        if (mainQrImg && mainQrImg.complete && mainQrImg.naturalWidth > 0) {
            try {
                const canvas = document.createElement('canvas');
                canvas.width = mainQrImg.naturalWidth || 300;
                canvas.height = mainQrImg.naturalHeight || 300;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(mainQrImg, 0, 0);
                qrDataUrl = canvas.toDataURL('image/png');
            } catch (err) {
                console.warn('Canvas QR export failed, using direct URL:', err);
                qrDataUrl = null;
            }
        }
        const finalQrSrc = qrDataUrl || `{!! $studentBioQrUrl !!}`;

        // 2. Ekstrak Avatar Foto Siswa jika sudah ter-render di DOM
        let avatarDataUrl = null;
        const mainAvatarImg = document.querySelector('#cardFrontSide img[alt="{{ addslashes($user->name) }}"]');
        if (mainAvatarImg && mainAvatarImg.complete && mainAvatarImg.naturalWidth > 0) {
            try {
                const canvas = document.createElement('canvas');
                canvas.width = mainAvatarImg.naturalWidth;
                canvas.height = mainAvatarImg.naturalHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(mainAvatarImg, 0, 0);
                avatarDataUrl = canvas.toDataURL('image/png');
            } catch (err) {
                avatarDataUrl = null;
            }
        }
        const finalAvatarSrc = avatarDataUrl || '{{ $user->avatar_url }}';

        // 3. Buat iframe cetak terisolasi dengan viewport aktif di luar layar agar browser me-render & decode semua gambar
        let printFrame = document.getElementById('student_card_print_iframe');
        if (printFrame) {
            printFrame.remove();
        }

        printFrame = document.createElement('iframe');
        printFrame.id = 'student_card_print_iframe';
        printFrame.style.position = 'fixed';
        printFrame.style.left = '-9999px';
        printFrame.style.top = '0';
        printFrame.style.width = '1024px';
        printFrame.style.height = '768px';
        printFrame.style.opacity = '0';
        printFrame.style.pointerEvents = 'none';
        printFrame.style.border = '0';
        document.body.appendChild(printFrame);

        const frameDoc = printFrame.contentWindow.document;
        frameDoc.open();
        frameDoc.write(`
            <!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <title>Cetak Kartu Pelajar Digital — {{ addslashes($user->name) }}</title>
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
                <style>
                    * {
                        box-sizing: border-box;
                        margin: 0;
                        padding: 0;
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                    }
                    body {
                        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                        background: #ffffff;
                        color: #ffffff;
                        padding: 8mm 6mm;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: flex-start;
                    }
                    .print-header {
                        text-align: center;
                        margin-bottom: 6mm;
                        color: #0f172a;
                    }
                    .print-header h1 {
                        font-size: 14px;
                        font-weight: 800;
                        letter-spacing: 0.5px;
                        text-transform: uppercase;
                    }
                    .print-header p {
                        font-size: 10.5px;
                        color: #64748b;
                        margin-top: 2px;
                    }
                    .cards-container {
                        display: flex;
                        flex-direction: row;
                        justify-content: center;
                        align-items: flex-start;
                        gap: 10mm;
                        flex-wrap: wrap;
                    }
                    .card-unit {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                    }
                    /* Standard Card Dimensions: 86mm x 54mm */
                    .card-box {
                        width: 86mm;
                        height: 54mm;
                        border-radius: 3.5mm;
                        position: relative;
                        display: flex;
                        flex-direction: column;
                        justify-content: space-between;
                        padding: 2.8mm 3.5mm;
                        border: 1px dashed #94a3b8;
                        page-break-inside: avoid;
                        break-inside: avoid;
                        overflow: visible;
                    }
                    /* FRONT CARD STYLES */
                    .card-front {
                        background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 50%, #1e1b4b 100%);
                    }
                    .cf-header {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        border-bottom: 0.8px solid rgba(255,255,255,0.22);
                        padding-bottom: 1.2mm;
                    }
                    .cf-header-left {
                        display: flex;
                        align-items: center;
                        gap: 1.8mm;
                        min-width: 0;
                    }
                    .cf-emblem {
                        width: 5.5mm;
                        height: 5.5mm;
                        background: linear-gradient(135deg, #fbbf24, #f59e0b);
                        border-radius: 1.2mm;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 9px;
                        color: #0f172a;
                        font-weight: 900;
                        flex-shrink: 0;
                    }
                    .cf-titles {
                        display: flex;
                        flex-direction: column;
                        min-width: 0;
                    }
                    .cf-school-name {
                        font-size: 7.5pt;
                        font-weight: 800;
                        text-transform: uppercase;
                        letter-spacing: 0.2px;
                        color: #ffffff;
                        line-height: 1.1;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        max-width: 52mm;
                    }
                    .cf-card-sub {
                        font-size: 5pt;
                        color: #c7d2fe;
                        font-weight: 600;
                        text-transform: uppercase;
                        letter-spacing: 0.4px;
                    }
                    .cf-badge-year {
                        font-size: 5.5pt;
                        background: rgba(255,255,255,0.18);
                        color: #fef08a;
                        padding: 1px 4px;
                        border-radius: 2mm;
                        font-weight: 700;
                        font-family: 'JetBrains Mono', monospace;
                        border: 0.5px solid rgba(255,255,255,0.2);
                        flex-shrink: 0;
                    }

                    /* FRONT BODY */
                    .cf-body {
                        display: flex;
                        align-items: center;
                        gap: 2.8mm;
                        flex: 1;
                        padding: 0.8mm 0;
                        min-height: 0;
                    }
                    .cf-photo {
                        width: 17mm;
                        height: 22mm;
                        border-radius: 1.5mm;
                        overflow: hidden;
                        border: 1px solid rgba(255,255,255,0.5);
                        background: #0f172a;
                        flex-shrink: 0;
                    }
                    .cf-photo img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        display: block;
                    }
                    .cf-info {
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        flex: 1;
                        min-width: 0;
                    }
                    .cf-name {
                        font-size: 8.5pt;
                        font-weight: 800;
                        color: #ffffff;
                        line-height: 1.15;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    }
                    .cf-meta {
                        display: flex;
                        align-items: center;
                        gap: 2px;
                        font-size: 6.2pt;
                        color: #c7d2fe;
                        margin-top: 0.8px;
                        font-family: 'JetBrains Mono', monospace;
                    }
                    .cf-meta-label {
                        color: rgba(255,255,255,0.65);
                        font-family: 'Plus Jakarta Sans', sans-serif;
                        font-size: 5.5pt;
                    }
                    .cf-meta-val {
                        font-weight: 700;
                        color: #ffffff;
                    }
                    .cf-class-pill {
                        display: inline-block;
                        margin-top: 1.2mm;
                        background: rgba(255,255,255,0.2);
                        color: #ffffff;
                        font-size: 6pt;
                        font-weight: 700;
                        padding: 1px 4.5px;
                        border-radius: 1.2mm;
                        width: fit-content;
                    }

                    /* FRONT FOOTER */
                    .cf-footer {
                        display: flex;
                        align-items: flex-end;
                        justify-content: space-between;
                        border-top: 0.8px solid rgba(255,255,255,0.22);
                        padding-top: 1mm;
                    }
                    .cf-qr-area {
                        display: flex;
                        align-items: center;
                        gap: 1.8mm;
                    }
                    .cf-qr-box {
                        width: 10mm;
                        height: 10mm;
                        background: #ffffff;
                        border-radius: 1mm;
                        padding: 0.5mm;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-shrink: 0;
                        box-shadow: 0 1px 2px rgba(0,0,0,0.2);
                    }
                    .cf-qr-box img {
                        width: 100%;
                        height: 100%;
                        display: block;
                        object-fit: contain;
                    }
                    .cf-qr-text {
                        font-size: 5.2pt;
                        line-height: 1.2;
                    }
                    .cf-qr-text strong {
                        color: #fde047;
                        display: block;
                        font-size: 5.8pt;
                    }
                    .cf-qr-text span {
                        color: rgba(255,255,255,0.7);
                        font-family: 'JetBrains Mono', monospace;
                    }
                    .cf-barcode-chip-group {
                        display: flex;
                        align-items: center;
                        gap: 1.8mm;
                        flex-shrink: 0;
                    }
                    .cf-barcode-card {
                        background: #ffffff;
                        padding: 0.6mm 1mm;
                        border-radius: 0.8mm;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        box-shadow: 0 1px 2px rgba(0,0,0,0.2);
                    }
                    .cf-barcode-num {
                        font-size: 4pt;
                        font-weight: 700;
                        font-family: 'JetBrains Mono', monospace;
                        color: #0f172a;
                        line-height: 1;
                        margin-top: 0.3mm;
                        letter-spacing: 0.4px;
                    }
                    .cf-chip {
                        width: 6.5mm;
                        height: 4.8mm;
                        background: linear-gradient(135deg, #f59e0b, #fbbf24);
                        border-radius: 0.8mm;
                        border: 0.5px solid #d97706;
                        display: flex;
                        flex-direction: column;
                        justify-content: space-between;
                        padding: 0.8px;
                        flex-shrink: 0;
                    }
                    .cf-chip-line {
                        height: 0.5px;
                        background: rgba(180,83,9,0.5);
                    }

                    /* BACK CARD STYLES */
                    .card-back {
                        background: linear-gradient(135deg, #0b172a 0%, #0f2444 50%, #081322 100%);
                        padding-top: 7.2mm;
                    }
                    .cb-magnetic {
                        position: absolute;
                        left: 0;
                        right: 0;
                        top: 2.2mm;
                        height: 4.5mm;
                        background: linear-gradient(180deg, #111827 0%, #030712 100%);
                        border-top: 0.5px solid rgba(255,255,255,0.15);
                        border-bottom: 0.5px solid rgba(255,255,255,0.15);
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        padding: 0 3mm;
                    }
                    .cb-magnetic span {
                        font-size: 4pt;
                        font-family: 'JetBrains Mono', monospace;
                        color: rgba(255,255,255,0.5);
                        letter-spacing: 0.5px;
                    }
                    .cb-header {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        border-bottom: 0.8px solid rgba(255,255,255,0.15);
                        padding-bottom: 0.8mm;
                    }
                    .cb-header-title {
                        font-size: 6.5pt;
                        font-weight: 800;
                        text-transform: uppercase;
                        letter-spacing: 0.3px;
                        color: #ffffff;
                    }
                    .cb-header-badge {
                        font-size: 5pt;
                        color: #22d3ee;
                        font-family: 'JetBrains Mono', monospace;
                    }
                    .cb-rules {
                        font-size: 5.2pt;
                        line-height: 1.35;
                        color: #cbd5e1;
                        display: flex;
                        flex-direction: column;
                        gap: 0.6mm;
                        margin: auto 0;
                        padding: 0.6mm 0;
                    }
                    .cb-rule {
                        display: flex;
                        align-items: flex-start;
                        gap: 1.2mm;
                    }
                    .cb-rule-num {
                        font-weight: 800;
                        color: #fde047;
                        flex-shrink: 0;
                    }
                    .cb-footer {
                        display: flex;
                        align-items: flex-end;
                        justify-content: space-between;
                        border-top: 0.8px solid rgba(255,255,255,0.15);
                        padding-top: 0.8mm;
                        gap: 2mm;
                    }
                    .cb-contact {
                        font-size: 4.6pt;
                        line-height: 1.25;
                        color: #94a3b8;
                        max-width: 50%;
                    }
                    .cb-contact strong {
                        color: #ffffff;
                        font-size: 5pt;
                        display: block;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    }
                    .cb-contact span {
                        color: #67e8f9;
                        display: block;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    }
                    .cb-seal {
                        width: 7.5mm;
                        height: 7.5mm;
                        border: 0.8px solid #22d3ee;
                        border-radius: 50%;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        font-size: 3.5pt;
                        font-weight: 800;
                        color: #a5f3fc;
                        text-align: center;
                        line-height: 1;
                        transform: rotate(-6deg);
                        flex-shrink: 0;
                    }
                    .cb-principal {
                        text-align: center;
                        font-size: 4.8pt;
                        color: #cbd5e1;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        flex-shrink: 0;
                    }
                    .cb-principal-name {
                        font-size: 5.6pt;
                        font-weight: 800;
                        color: #ffffff;
                        text-decoration: underline;
                        margin-top: 0.6mm;
                        line-height: 1.1;
                    }
                    .cb-principal-nip {
                        font-size: 4.5pt;
                        font-family: 'JetBrains Mono', monospace;
                        color: #94a3b8;
                    }
                    .cut-guide {
                        font-size: 7pt;
                        color: #64748b;
                        font-family: monospace;
                        text-align: center;
                        margin-top: 3px;
                    }
                    .print-tips {
                        margin-top: 8mm;
                        border-top: 1px solid #e2e8f0;
                        padding-top: 3mm;
                        text-align: center;
                        font-size: 8pt;
                        color: #94a3b8;
                        max-width: 520px;
                        line-height: 1.4;
                    }
                    @page {
                        size: A4 portrait;
                        margin: 10mm;
                    }
                    @media print {
                        body {
                            padding: 4mm 0;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="print-header">
                    <h1>KARTU TANDA PELAJAR DIGITAL RESMI</h1>
                    <p>{{ $cardSchoolName }} • Tahun Ajaran 2026/2027</p>
                </div>

                <div class="cards-container">
                    <!-- SISI DEPAN (FRONT) -->
                    <div class="card-unit">
                        <div class="card-box card-front">
                            <!-- Header -->
                            <div class="cf-header">
                                <div class="cf-header-left">
                                    <div class="cf-emblem">🎓</div>
                                    <div class="cf-titles">
                                        <div class="cf-school-name">{{ $cardSchoolName }}</div>
                                        <div class="cf-card-sub">KARTU TANDA PELAJAR DIGITAL</div>
                                    </div>
                                </div>
                                <div class="cf-badge-year">2026/2027</div>
                            </div>

                            <!-- Body -->
                            <div class="cf-body">
                                <div class="cf-photo">
                                    <img src="${finalAvatarSrc}" alt="{{ $user->name }}" onerror="this.src='{{ asset('images/default-avatar.png') }}'" />
                                </div>
                                <div class="cf-info">
                                    <div class="cf-name">{{ $user->name }}</div>
                                    <div class="cf-meta">
                                        <span class="cf-meta-label">NIS:</span>
                                        <span class="cf-meta-val">{{ $user->nis ?? '-' }}</span>
                                    </div>
                                    <div class="cf-meta">
                                        <span class="cf-meta-label">NISN:</span>
                                        <span class="cf-meta-val">{{ $user->nisn ?? '-' }}</span>
                                    </div>
                                    <div class="cf-class-pill">{{ $classroom ? $classroom->name : 'Siswa' }}</div>
                                </div>
                            </div>

                            <!-- Footer: QR Code & 1D Barcode & Chip -->
                            <div class="cf-footer">
                                <div class="cf-qr-area">
                                    <div class="cf-qr-box">
                                        <img src="${finalQrSrc}" alt="QR Code Biodata" />
                                    </div>
                                    <div class="cf-qr-text">
                                        <strong>SCAN BIODATA</strong>
                                        <span>{{ $studentCardSerial }}</span>
                                    </div>
                                </div>
                                <div class="cf-barcode-chip-group">
                                    <div class="cf-barcode-card">
                                        {!! $barcodeSvgPrint !!}
                                        <div class="cf-barcode-num">* {{ $barcodeValue }} *</div>
                                    </div>
                                    <div class="cf-chip">
                                        <div class="cf-chip-line"></div>
                                        <div class="cf-chip-line"></div>
                                        <div class="cf-chip-line"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cut-guide">▲ SISI DEPAN (Gunting garis putus-putus)</div>
                    </div>

                    <!-- SISI BELAKANG (BACK) -->
                    <div class="card-unit">
                        <div class="card-box card-back">
                            <!-- Magnetic Stripe -->
                            <div class="cb-magnetic">
                                <span>AUTHENTIC RFID PASS</span>
                                <span>ID: {{ $studentCardSerial }}</span>
                            </div>

                            <!-- Header -->
                            <div class="cb-header">
                                <div class="cb-header-title">TATA TERTIB &amp; KETENTUAN KARTU</div>
                                <div class="cb-header-badge">OFFICIAL</div>
                            </div>

                            <!-- Rules -->
                            <div class="cb-rules">
                                <div class="cb-rule">
                                    <span class="cb-rule-num">1.</span>
                                    <div>Kartu ini bukti sah siswa <strong>{{ $cardSchoolName }}</strong> untuk ujian CBT &amp; belajar.</div>
                                </div>
                                <div class="cb-rule">
                                    <span class="cb-rule-num">2.</span>
                                    <div>Pindai QR Code untuk verifikasi biodata siswa di pangkalan kurikulum sekolah.</div>
                                </div>
                                <div class="cb-rule">
                                    <span class="cb-rule-num">3.</span>
                                    <div>Dilarang memindahtangankan, menggandakan, atau memalsukan kartu ini.</div>
                                </div>
                                <div class="cb-rule">
                                    <span class="cb-rule-num">4.</span>
                                    <div>Jika kartu ini hilang atau ditemukan, kembalikan ke bagian Tata Usaha sekolah.</div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="cb-footer">
                                <div class="cb-contact">
                                    <strong>{{ $cardSchoolName }}</strong>
                                    <div>{{ Str::limit($cardSchoolAddress, 45) }}</div>
                                    <span>{{ $cardSchoolContact }}</span>
                                </div>
                                <div class="cb-seal">
                                    <span>VERIFIED</span>
                                    <span style="font-size:3pt;">CBT ID</span>
                                </div>
                                <div class="cb-principal">
                                    <div>Kepala Sekolah,</div>
                                    <div class="cb-principal-name">{{ $cardPrincipalName }}</div>
                                    <div class="cb-principal-nip">NIP. {{ $cardPrincipalNip }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="cut-guide">▲ SISI BELAKANG (Lipat / Rekatkan)</div>
                    </div>
                </div>

                <div class="print-tips">
                    Petunjuk: Cetak pada kertas tebal (Art Paper 260/310 gsm) atau kertas PVC Card. Gunting sesuai garis panduan putus-putus kemudian laminasi untuk ketahanan maksimal.
                </div>
            </body>
            </html>
        `);
        frameDoc.close();

        // 4. Pastikan semua gambar di dalam iframe ter-decode sepenuhnya sebelum memanggil print
        const allImgs = Array.from(frameDoc.images);
        let hasPrinted = false;

        const executePrint = () => {
            if (hasPrinted) return;
            hasPrinted = true;
            setTimeout(() => {
                printFrame.contentWindow.focus();
                printFrame.contentWindow.print();
            }, 180);
        };

        // Safety fallback timer 2.5 detik jika koneksi gambar lambat
        const safetyTimer = setTimeout(executePrint, 2500);

        if (allImgs.length === 0) {
            clearTimeout(safetyTimer);
            executePrint();
        } else {
            let loadedCount = 0;
            const onImgReady = () => {
                loadedCount++;
                if (loadedCount >= allImgs.length) {
                    clearTimeout(safetyTimer);
                    executePrint();
                }
            };

            allImgs.forEach(img => {
                if (img.complete && img.naturalWidth > 0) {
                    onImgReady();
                } else {
                    img.onload = onImgReady;
                    img.onerror = onImgReady;
                }
            });
        }
    }

    // ═══ THEME PREFERENCE CONTROLLER ═══
    function updateProfileThemeButtons(isDark) {
        const lightBtn = document.getElementById('themeOptionLight');
        const darkBtn = document.getElementById('themeOptionDark');
        if (!lightBtn || !darkBtn) return;

        if (isDark) {
            darkBtn.className = 'theme-choice-btn flex items-center justify-center gap-2 p-3 rounded-xl border border-primary bg-primary/10 text-primary font-bold shadow-xs ring-2 ring-primary/20 transition-all text-xs cursor-pointer';
            lightBtn.className = 'theme-choice-btn flex items-center justify-center gap-2 p-3 rounded-xl border border-surface-container bg-surface-container-low text-on-surface-variant hover:bg-surface-container transition-all text-xs font-semibold cursor-pointer';
        } else {
            lightBtn.className = 'theme-choice-btn flex items-center justify-center gap-2 p-3 rounded-xl border border-primary bg-primary/10 text-primary font-bold shadow-xs ring-2 ring-primary/20 transition-all text-xs cursor-pointer';
            darkBtn.className = 'theme-choice-btn flex items-center justify-center gap-2 p-3 rounded-xl border border-surface-container bg-surface-container-low text-on-surface-variant hover:bg-surface-container transition-all text-xs font-semibold cursor-pointer';
        }
    }

    function setStudentTheme(mode) {
        const isDark = (mode === 'dark');
        if (isDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        localStorage.setItem('theme', mode);
        if (typeof updateThemeUI === 'function') {
            updateThemeUI(isDark);
        }
        updateProfileThemeButtons(isDark);
    }

    window.addEventListener('themeChanged', function(e) {
        updateProfileThemeButtons(e.detail.isDark);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const isDark = document.documentElement.classList.contains('dark');
        updateProfileThemeButtons(isDark);
    });
</script>
@endpush
@endsection
