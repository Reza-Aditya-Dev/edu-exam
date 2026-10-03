@extends('layouts.admin')

@section('title', 'Tambah Tenaga Pendidik & Guru — EduExam')
@section('page_title', 'Tambah Guru Baru')

@section('admin-content')
<div class="flex flex-col w-full pb-10">

    <!-- Top Command Bar / Breadcrumb & Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-space-md mb-space-xl">
        <div class="flex flex-col min-w-0">
            <nav class="flex items-center gap-2 mb-space-xs text-on-surface-variant font-label-sm text-label-sm">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors cursor-pointer flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">home</span>
                    <span>Master Data</span>
                </a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <a href="{{ route('admin.teachers') }}" class="hover:text-primary transition-colors cursor-pointer">Data Guru</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-semibold">Tambah Guru Baru</span>
            </nav>
            <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">Tambah Tenaga Pendidik &amp; Guru</h1>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1 max-w-2xl">
                Daftarkan profil guru baru, integrasi akun pembuatan ujian CBT, dan alokasi mata pelajaran kurikulum.
            </p>
        </div>

        <!-- Quick Actions Header -->
        <div class="flex items-center gap-space-sm self-start md:self-auto shrink-0">
            <a href="{{ route('admin.teachers') }}" class="px-5 py-2.5 rounded-lg bg-surface-container-low hover:bg-surface-container-high text-on-surface font-label-lg text-label-lg transition-colors shadow-sm inline-flex items-center gap-1.5 no-underline">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                <span>Batal</span>
            </a>
            <button onclick="document.getElementById('teacherForm').requestSubmit()" type="button" class="px-5 py-2.5 rounded-lg bg-primary-container hover:bg-primary text-on-primary font-label-lg text-label-lg shadow-sm flex items-center gap-2 transition-all active:scale-95 cursor-pointer">
                <span class="material-symbols-outlined text-[18px]">save</span>
                <span>Simpan Data Guru</span>
            </button>
        </div>
    </div>

    <!-- Error Validation Summary Alert -->
    @if (isset($errors) && $errors->any())
        <div class="mb-space-lg p-space-md rounded-xl bg-error-container text-on-error-container flex items-start gap-3 shadow-sm border border-red-200">
            <span class="material-symbols-outlined text-[24px] text-error shrink-0">error</span>
            <div class="flex flex-col text-sm">
                <span class="font-bold mb-1">Terdapat kesalahan pada isian form:</span>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Form 2-Column Grid -->
    <form action="{{ route('admin.teachers.store') }}" method="POST" enctype="multipart/form-data" id="teacherForm" class="grid grid-cols-1 xl:grid-cols-12 gap-space-xl items-start">
        @csrf

        <!-- LEFT COLUMN: Data Pokok & Penugasan (7 cols on XL) -->
        <div class="xl:col-span-7 flex flex-col gap-space-xl">

            <!-- Card 1: Identitas Pokok Pendidik -->
            <section class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border border-slate-100 flex flex-col gap-space-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-[22px]">badge</span>
                        </div>
                        <div>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Identitas Pokok Pendidik</h2>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Sesuai data resmi Dapodik &amp; Surat Keputusan Pengangkatan</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">
                        Data Wajib
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
                    <!-- Nama Lengkap beserta Gelar -->
                    <div class="md:col-span-2 flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold">
                            Nama Lengkap beserta Gelar <span class="text-error font-bold">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px] pointer-events-none">person</span>
                            <input 
                                name="name" 
                                class="w-full bg-surface-container-low focus:bg-surface-container-lowest rounded-lg pl-11 pr-4 py-2.5 font-body-md text-body-md text-on-surface outline-none border border-transparent focus:border-primary-container transition-all focus:shadow-md @error('name') border-error @enderror" 
                                placeholder="Contoh: Drs. Bambang Sutrisno, M.Si." 
                                type="text" 
                                value="{{ old('name') }}" 
                                required
                            />
                        </div>
                        @error('name')
                            <span class="text-error font-body-sm text-xs mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- NIP -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold">
                            Nomor Induk Pegawai (NIP) <span class="text-error font-bold">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px] pointer-events-none">pin</span>
                            <input 
                                name="nip" 
                                class="w-full bg-surface-container-low focus:bg-surface-container-lowest rounded-lg pl-11 pr-4 py-2.5 font-body-md text-body-md text-on-surface outline-none border border-transparent focus:border-primary-container transition-all focus:shadow-md @error('nip') border-error @enderror" 
                                placeholder="Contoh: 19850324 200801 2 004" 
                                type="text" 
                                value="{{ old('nip') }}" 
                                required
                            />
                        </div>
                        @error('nip')
                            <span class="text-error font-body-sm text-xs mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Username Akun CBT (Opsional/Otomatis) -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold">
                            Username Login CBT <span class="text-on-surface-variant font-normal text-xs">(Opsional)</span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px] pointer-events-none">alternate_email</span>
                            <input 
                                name="username" 
                                class="w-full bg-surface-container-low focus:bg-surface-container-lowest rounded-lg pl-11 pr-4 py-2.5 font-body-md text-body-md text-on-surface outline-none border border-transparent focus:border-primary-container transition-all focus:shadow-md @error('username') border-error @enderror" 
                                placeholder="Contoh: bambang.sutrisno" 
                                type="text" 
                                value="{{ old('username') }}"
                            />
                        </div>
                        <span class="font-body-sm text-[11px] text-on-surface-variant">Otomatis dibuat dari email/nama jika dikosongkan.</span>
                        @error('username')
                            <span class="text-error font-body-sm text-xs mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold">
                            Jenis Kelamin <span class="text-error font-bold">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-2 h-[44px]">
                            @php $currGender = old('gender', 'L'); @endphp
                            <label class="flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-surface-container-low text-on-surface-variant font-label-lg text-label-lg cursor-pointer hover:bg-surface-container transition-all has-[:checked]:bg-primary-container has-[:checked]:text-on-primary shadow-sm border border-transparent has-[:checked]:border-primary-container">
                                <input class="hidden" name="gender" type="radio" value="L" {{ $currGender == 'L' ? 'checked' : '' }} required/>
                                <span class="material-symbols-outlined text-[18px]">man</span>
                                <span>Laki-laki</span>
                            </label>
                            <label class="flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-surface-container-low text-on-surface-variant font-label-lg text-label-lg cursor-pointer hover:bg-surface-container transition-all has-[:checked]:bg-primary-container has-[:checked]:text-on-primary shadow-sm border border-transparent has-[:checked]:border-primary-container">
                                <input class="hidden" name="gender" type="radio" value="P" {{ $currGender == 'P' ? 'checked' : '' }}/>
                                <span class="material-symbols-outlined text-[18px]">woman</span>
                                <span>Perempuan</span>
                            </label>
                        </div>
                        @error('gender')
                            <span class="text-error font-body-sm text-xs mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- WhatsApp / Telepon -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold">
                            Nomor Telepon / WhatsApp
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px] pointer-events-none">call</span>
                            <input 
                                name="phone" 
                                class="w-full bg-surface-container-low focus:bg-surface-container-lowest rounded-lg pl-11 pr-4 py-2.5 font-body-md text-body-md text-on-surface outline-none border border-transparent focus:border-primary-container transition-all focus:shadow-md @error('phone') border-error @enderror" 
                                placeholder="+62 812-3456-7890" 
                                type="tel" 
                                value="{{ old('phone') }}"
                            />
                        </div>
                        @error('phone')
                            <span class="text-error font-body-sm text-xs mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </section>

            <!-- Card 2: Alokasi Mata Pelajaran & Kelas Mengajar -->
            <section class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border border-slate-100 flex flex-col gap-space-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-secondary-container flex items-center justify-center text-on-secondary-container">
                            <span class="material-symbols-outlined text-[22px]">auto_stories</span>
                        </div>
                        <div>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Alokasi Mata Pelajaran &amp; Kelas Mengajar</h2>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Penetapan rombongan belajar dan hak pembuatan bank soal CBT</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-semibold">
                        <span class="material-symbols-outlined text-[14px]">tune</span>
                        <span>Kurikulum Merdeka</span>
                    </div>
                </div>

                <div class="flex flex-col gap-space-md">
                    <!-- Mata Pelajaran yang Diampu -->
                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <label class="font-label-md text-label-md text-on-surface font-semibold">
                                Mata Pelajaran yang Diampu <span class="text-error font-bold">*</span>
                            </label>
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Centang satu atau lebih mapel</span>
                        </div>
                        
                        <div class="p-3 rounded-lg bg-surface-container-low border border-slate-200/70">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5 max-h-48 overflow-y-auto pr-1">
                                @forelse($subjects as $s)
                                    @php
                                        $isSubChecked = is_array(old('subject_ids')) && in_array($s->id, old('subject_ids'));
                                    @endphp
                                    <label class="subject-chip flex items-center gap-2.5 p-2 rounded-lg bg-surface-container-lowest hover:bg-surface-container border border-slate-200 cursor-pointer transition-all has-[:checked]:bg-primary-container has-[:checked]:text-on-primary has-[:checked]:border-primary-container shadow-2xs">
                                        <input 
                                            type="checkbox" 
                                            name="subject_ids[]" 
                                            value="{{ $s->id }}" 
                                            class="rounded border-slate-300 text-primary-container focus:ring-primary-container cursor-pointer"
                                            {{ $isSubChecked ? 'checked' : '' }}
                                            onchange="updateSubjectBadge()"
                                        />
                                        <span class="font-label-md text-label-md truncate">{{ $s->name }}</span>
                                    </label>
                                @empty
                                    <div class="col-span-full py-4 text-center text-xs text-on-surface-variant">
                                        Belum ada mata pelajaran terdaftar. Silakan tambahkan di menu <a href="{{ route('admin.subjects') }}" class="text-primary underline">Mata Pelajaran</a>.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        @error('subject_ids')
                            <span class="text-error font-body-sm text-xs mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Kelas yang diajar -->
                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <label class="font-label-md text-label-md text-on-surface font-semibold">
                                Kelas yang Diajar (Rombongan Belajar)
                            </label>
                            <span id="selectedClassCount" class="font-label-sm text-label-sm text-secondary font-semibold">0 Rombel Terpilih</span>
                        </div>
                        
                        <div class="p-3 rounded-lg bg-surface-container-low border border-slate-200/70 flex flex-wrap items-center gap-2">
                            @forelse($classrooms as $c)
                                @php
                                    $isClassChecked = is_array(old('classroom_ids')) && in_array($c->id, old('classroom_ids'));
                                @endphp
                                <label class="classroom-chip inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface-container-lowest hover:bg-surface-container text-on-surface font-label-md text-label-md border border-slate-200 shadow-2xs cursor-pointer transition-all has-[:checked]:bg-primary has-[:checked]:text-on-primary has-[:checked]:border-primary">
                                    <input 
                                        type="checkbox" 
                                        name="classroom_ids[]" 
                                        value="{{ $c->id }}" 
                                        class="hidden" 
                                        {{ $isClassChecked ? 'checked' : '' }}
                                        onchange="updateClassCount()"
                                    />
                                    <span class="material-symbols-outlined text-[16px]">domain</span>
                                    <span>{{ $c->name }}</span>
                                </label>
                            @empty
                                <div class="w-full py-3 text-center text-xs text-on-surface-variant">
                                    Belum ada rombongan belajar kelas aktif.
                                </div>
                            @endforelse
                        </div>
                        <span class="font-body-sm text-[11px] text-on-surface-variant">Pilih rombongan belajar yang diajarkan oleh guru untuk integrasi jadwal dan rekap nilai CBT.</span>
                        @error('classroom_ids')
                            <span class="text-error font-body-sm text-xs mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Penugasan Khusus Wali Kelas -->
                    <div class="pt-2 flex flex-col gap-2">
                        <label class="flex items-start gap-3 p-3.5 rounded-lg bg-surface-container-low hover:bg-surface-container cursor-pointer transition-colors border border-slate-200/70">
                            <input 
                                id="isHomeroomCheckbox" 
                                name="is_homeroom" 
                                value="1" 
                                class="mt-0.5 w-5 h-5 rounded accent-primary-container cursor-pointer" 
                                type="checkbox"
                                {{ old('is_homeroom') ? 'checked' : '' }}
                                onchange="toggleHomeroomClassSelect()"
                            />
                            <div class="flex flex-col">
                                <span class="font-label-lg text-label-lg text-on-surface font-semibold">Ditugaskan sebagai Wali Kelas</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant">Memiliki wewenang rekapitulasi nilai rapor CBT dan absensi kelas terpadu.</span>
                            </div>
                        </label>

                        <!-- Pilihan Kelas Wali Kelas (Muncul jika dicentang) -->
                        <div id="homeroomSelectWrapper" class="p-3 rounded-lg bg-surface-container-lowest border border-slate-200 {{ old('is_homeroom') ? '' : 'hidden' }}">
                            <label class="font-label-sm text-label-sm text-on-surface font-semibold block mb-1.5">
                                Pilih Kelas Perwalian:
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3 text-outline text-[18px] pointer-events-none">supervised_user_circle</span>
                                <select name="homeroom_classroom_id" class="w-full bg-surface-container-low focus:bg-surface-container-lowest rounded-lg pl-9 pr-10 py-2 font-body-md text-body-md text-on-surface outline-none border border-transparent focus:border-primary-container transition-all cursor-pointer">
                                    <option value="">-- Pilih Kelas yang Dibina --</option>
                                    @foreach($classrooms as $c)
                                        <option value="{{ $c->id }}" {{ old('homeroom_classroom_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }} (Tingkat {{ $c->grade }} - {{ $c->academicYear ? $c->academicYear->name : 'Aktif' }})
                                        </option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-3 text-outline pointer-events-none text-[18px]">expand_more</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- RIGHT COLUMN: Foto & Kredensial CBT (5 cols on XL) -->
        <div class="xl:col-span-5 flex flex-col gap-space-xl">

            <!-- Card 3: Foto Profil Pendidik -->
            <section class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border border-slate-100 flex flex-col gap-space-md">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-[22px]">account_circle</span>
                        </div>
                        <div>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Foto Profil Pendidik</h2>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Ditampilkan di kartu pengawas &amp; header CBT</p>
                        </div>
                    </div>
                    <span class="font-label-sm text-label-sm text-outline">Format JPG/PNG</span>
                </div>

                <!-- Photo preview & controls -->
                <div class="flex items-center gap-space-md p-space-md rounded-xl bg-surface-container-low border border-slate-200/60">
                    <div class="relative shrink-0 w-24 h-24 rounded-2xl overflow-hidden shadow-md bg-surface-container-high border-2 border-white">
                        <img 
                            id="avatarPreviewImg" 
                            alt="Preview Pasfoto" 
                            class="w-full h-full object-cover" 
                            src="https://ui-avatars.com/api/?name=Guru+Baru&background=4f46e5&color=fff&size=128"
                        />
                        <div class="absolute bottom-1 right-1 w-3.5 h-3.5 bg-secondary rounded-full ring-2 ring-surface-container-lowest" title="Aktif"></div>
                    </div>
                    <div class="flex flex-col gap-2 flex-1 min-w-0">
                        <div class="flex flex-col">
                            <span id="avatarFileName" class="font-label-md text-label-md text-on-surface truncate font-semibold">Belum ada foto dipilih</span>
                            <span id="avatarFileSize" class="font-body-sm text-body-sm text-on-surface-variant text-xs">Maksimal 2 MB • Rasio 1:1</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="document.getElementById('avatarInput').click()" class="px-3 py-1.5 rounded-lg bg-surface-container-lowest hover:bg-surface-container text-on-surface font-label-md text-label-md shadow-2xs transition-all flex items-center gap-1.5 cursor-pointer border border-slate-200" type="button">
                                <span class="material-symbols-outlined text-[16px] text-primary">cached</span>
                                <span>Pilih Foto</span>
                            </button>
                            <button id="avatarResetBtn" onclick="resetAvatarPreview()" class="px-3 py-1.5 rounded-lg hover:bg-error-container text-error font-label-md text-label-md transition-colors flex items-center gap-1.5 cursor-pointer hidden" type="button">
                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                <span>Batal</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Hidden Actual File Input -->
                <input 
                    type="file" 
                    name="avatar" 
                    id="avatarInput" 
                    accept="image/jpeg,image/png,image/jpg,image/webp" 
                    class="hidden" 
                    onchange="handleAvatarChange(event)"
                />

                <button onclick="document.getElementById('avatarInput').click()" class="w-full py-2.5 px-4 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary font-label-lg text-label-lg transition-colors flex items-center justify-center gap-2 cursor-pointer border border-primary/20 shadow-2xs" type="button">
                    <span class="material-symbols-outlined text-[20px]">upload_file</span>
                    <span>Unggah Pasfoto Resmi (Maks 2MB)</span>
                </button>
                @error('avatar')
                    <span class="text-error font-body-sm text-xs mt-0.5">{{ $message }}</span>
                @enderror
            </section>

            <!-- Card 4: Kredensial & Akses CBT Portal Guru -->
            <section class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border border-slate-100 flex flex-col gap-space-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary flex items-center justify-center shadow-xs">
                            <span class="material-symbols-outlined text-[22px]">lock</span>
                        </div>
                        <div>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Kredensial &amp; Akses CBT</h2>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Hak akses login ke Bank Soal &amp; Pengawasan</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-secondary text-[22px]" title="Enkripsi Aktif">security</span>
                </div>

                <div class="flex flex-col gap-space-md">
                    <!-- Email Guru -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold">
                            Email Resmi Sekolah (SSO CBT) <span class="text-error font-bold">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px] pointer-events-none">mail</span>
                            <input 
                                name="email" 
                                class="w-full bg-surface-container-low focus:bg-surface-container-lowest rounded-lg pl-11 pr-4 py-2.5 font-body-md text-body-md text-on-surface outline-none border border-transparent focus:border-primary-container transition-all focus:shadow-md @error('email') border-error @enderror" 
                                placeholder="nama.guru@sekolah.sch.id" 
                                type="email" 
                                value="{{ old('email') }}" 
                                required
                            />
                        </div>
                        @error('email')
                            <span class="text-error font-body-sm text-xs mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password with strength & generator -->
                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <label class="font-label-md text-label-md text-on-surface font-semibold">
                                Kata Sandi Akun Guru <span class="text-error font-bold">*</span>
                            </label>
                            <button onclick="generateStrongPassword()" class="text-primary hover:underline font-label-sm text-label-sm flex items-center gap-1 cursor-pointer transition-colors" type="button">
                                <span class="material-symbols-outlined text-[15px]">autorenew</span>
                                <span>Acak Sandi Kuat</span>
                            </button>
                        </div>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px] pointer-events-none">key</span>
                            <input 
                                id="passwordInput" 
                                name="password" 
                                class="w-full bg-surface-container-low focus:bg-surface-container-lowest rounded-lg pl-11 pr-11 py-2.5 font-body-md text-body-md text-on-surface outline-none border border-transparent focus:border-primary-container transition-all focus:shadow-md @error('password') border-error @enderror" 
                                type="password" 
                                placeholder="Minimal 6 karakter" 
                                required 
                                minlength="6"
                                oninput="checkPasswordStrength(this.value)"
                            />
                            <button id="togglePasswordBtn" onclick="togglePasswordVisibility()" class="absolute right-3.5 text-outline hover:text-on-surface flex items-center cursor-pointer transition-colors" type="button" title="Lihat/Sembunyikan Sandi">
                                <span id="togglePasswordIcon" class="material-symbols-outlined text-[20px]">visibility_off</span>
                            </button>
                        </div>
                        <!-- Password strength meter bar -->
                        <div class="flex items-center gap-1.5 mt-1">
                            <div id="bar1" class="flex-1 h-1.5 rounded-full bg-surface-container-high transition-colors"></div>
                            <div id="bar2" class="flex-1 h-1.5 rounded-full bg-surface-container-high transition-colors"></div>
                            <div id="bar3" class="flex-1 h-1.5 rounded-full bg-surface-container-high transition-colors"></div>
                            <div id="bar4" class="flex-1 h-1.5 rounded-full bg-surface-container-high transition-colors"></div>
                            <span id="strengthText" class="font-label-sm text-label-sm text-outline font-semibold pl-1 text-xs">Minimal 6 Karakter</span>
                        </div>
                        @error('password')
                            <span class="text-error font-body-sm text-xs mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Status Akun Toggle -->
                    <div class="p-3.5 rounded-xl bg-surface-container-low border border-slate-200/60 flex items-center justify-between gap-space-md">
                        <div class="flex flex-col">
                            <span class="font-label-lg text-label-lg text-on-surface font-semibold">Status Akun CBT</span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant text-xs">Aktif - Guru berhak membuat bank soal &amp; jadwal ujian</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input name="is_active" value="1" class="sr-only peer" type="checkbox" checked/>
                            <div class="w-11 h-6 bg-surface-container-high peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-secondary"></div>
                        </label>
                    </div>

                    <!-- Hak Akses Khusus Modul Ujian -->
                    <div class="flex flex-col gap-2 pt-1">
                        <span class="font-label-md text-label-md text-on-surface font-semibold">Hak Akses Modul CBT:</span>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 p-2.5 rounded-lg bg-surface-container-low hover:bg-surface-container cursor-pointer transition-colors border border-slate-200/60">
                                <input checked class="w-4 h-4 rounded accent-primary-container cursor-pointer" type="checkbox"/>
                                <div class="flex flex-col">
                                    <span class="font-label-md text-label-md text-on-surface font-semibold">Korektor Jawaban Esai</span>
                                    <span class="text-[11px] text-on-surface-variant">Dapat memeriksa dan menilai jawaban esai ujian siswa.</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-2.5 rounded-lg bg-surface-container-low hover:bg-surface-container cursor-pointer transition-colors border border-slate-200/60">
                                <input checked class="w-4 h-4 rounded accent-primary-container cursor-pointer" type="checkbox"/>
                                <div class="flex flex-col">
                                    <span class="font-label-md text-label-md text-on-surface font-semibold">Pembuat Paket Soal</span>
                                    <span class="text-[11px] text-on-surface-variant">Dapat menyusun butir soal pilihan ganda, esai, dan bank soal.</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-2.5 rounded-lg bg-surface-container-low hover:bg-surface-container cursor-pointer transition-colors border border-slate-200/60">
                                <input class="w-4 h-4 rounded accent-primary-container cursor-pointer" type="checkbox"/>
                                <div class="flex flex-col">
                                    <span class="font-label-md text-label-md text-on-surface font-semibold">Koordinator Pengawas Ujian</span>
                                    <span class="text-[11px] text-on-surface-variant">Dapat memantau live token dan aktivitas peserta saat ujian.</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Sticky Floating Action Bar at the Bottom -->
        <div class="xl:col-span-12 sticky bottom-4 z-30 p-space-md rounded-2xl bg-surface-container-lowest/95 backdrop-blur-xl shadow-xl border border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-space-md mt-space-md">
            <div class="flex items-center gap-2 text-on-surface-variant font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-secondary text-[20px]">info</span>
                <span>Notifikasi aktivasi dan kredensial akun sementara dapat dibagikan langsung kepada guru yang bersangkutan.</span>
            </div>
            <div class="flex items-center gap-space-sm w-full sm:w-auto justify-end">
                <a href="{{ route('admin.teachers') }}" class="px-5 py-2.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-lg text-label-lg transition-colors no-underline">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary-container hover:bg-primary text-on-primary font-label-lg text-label-lg shadow-md hover:shadow-lg transition-all flex items-center gap-2 active:scale-95 cursor-pointer font-bold">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    <span>Simpan &amp; Daftarkan Guru</span>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // 1. Live Avatar Upload & Preview
    function handleAvatarChange(event) {
        const file = event.target.files[0];
        if (!file) return;

        if (file.size > 2097152) { // 2MB
            alert('Ukuran file melebihi batas 2 MB. Silakan pilih foto dengan resolusi lebih kecil.');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreviewImg').src = e.target.result;
            document.getElementById('avatarFileName').innerText = file.name;
            const sizeInKb = Math.round(file.size / 1024);
            document.getElementById('avatarFileSize').innerText = sizeInKb + ' KB • Siap diunggah';
            document.getElementById('avatarResetBtn').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    function resetAvatarPreview() {
        const input = document.getElementById('avatarInput');
        input.value = '';
        document.getElementById('avatarPreviewImg').src = 'https://ui-avatars.com/api/?name=Guru+Baru&background=4f46e5&color=fff&size=128';
        document.getElementById('avatarFileName').innerText = 'Belum ada foto dipilih';
        document.getElementById('avatarFileSize').innerText = 'Maksimal 2 MB • Rasio 1:1';
        document.getElementById('avatarResetBtn').classList.add('hidden');
    }

    // 2. Password Strength & Visibility
    function togglePasswordVisibility() {
        const pwd = document.getElementById('passwordInput');
        const icon = document.getElementById('togglePasswordIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.innerText = 'visibility';
        } else {
            pwd.type = 'password';
            icon.innerText = 'visibility_off';
        }
    }

    function generateStrongPassword() {
        const charsUpper = "ABCDEFGHJKLMNPQRSTUVWXYZ";
        const charsLower = "abcdefghijkmnpqrstuvwxyz";
        const charsNum = "23456789";
        const charsSpecial = "@#!$%&*";
        
        let pass = "";
        pass += charsUpper.charAt(Math.floor(Math.random() * charsUpper.length));
        pass += charsLower.charAt(Math.floor(Math.random() * charsLower.length));
        pass += charsNum.charAt(Math.floor(Math.random() * charsNum.length));
        pass += charsSpecial.charAt(Math.floor(Math.random() * charsSpecial.length));

        const all = charsUpper + charsLower + charsNum + charsSpecial;
        for (let i = 0; i < 6; i++) {
            pass += all.charAt(Math.floor(Math.random() * all.length));
        }

        // Shuffle
        pass = pass.split('').sort(() => 0.5 - Math.random()).join('');

        const input = document.getElementById('passwordInput');
        input.value = pass;
        input.type = 'text';
        document.getElementById('togglePasswordIcon').innerText = 'visibility';
        checkPasswordStrength(pass);
    }

    function checkPasswordStrength(val) {
        let score = 0;
        if (!val || val.length < 6) {
            updateStrengthBars(0, 'Minimal 6 Karakter', 'text-outline');
            return;
        }

        score += 1; // min length reached
        if (val.length >= 8) score += 1;
        if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score += 1;
        if (/[0-9]/.test(val) && /[^A-Za-z0-9]/.test(val)) score += 1;

        if (score <= 1) {
            updateStrengthBars(1, 'Lemah', 'text-error');
        } else if (score === 2) {
            updateStrengthBars(2, 'Cukup', 'text-amber-500');
        } else if (score === 3) {
            updateStrengthBars(3, 'Kuat', 'text-secondary');
        } else {
            updateStrengthBars(4, 'Sangat Kuat', 'text-secondary');
        }
    }

    function updateStrengthBars(level, text, colorClass) {
        const bar1 = document.getElementById('bar1');
        const bar2 = document.getElementById('bar2');
        const bar3 = document.getElementById('bar3');
        const bar4 = document.getElementById('bar4');
        const textSpan = document.getElementById('strengthText');

        // Reset
        [bar1, bar2, bar3, bar4].forEach(b => {
            b.className = 'flex-1 h-1.5 rounded-full bg-surface-container-high transition-colors';
        });

        const activeColor = level === 1 ? 'bg-error' : (level === 2 ? 'bg-amber-500' : 'bg-secondary');

        if (level >= 1) bar1.className = 'flex-1 h-1.5 rounded-full ' + activeColor;
        if (level >= 2) bar2.className = 'flex-1 h-1.5 rounded-full ' + activeColor;
        if (level >= 3) bar3.className = 'flex-1 h-1.5 rounded-full ' + activeColor;
        if (level >= 4) bar4.className = 'flex-1 h-1.5 rounded-full ' + activeColor;

        textSpan.innerText = text;
        textSpan.className = 'font-label-sm text-label-sm font-semibold pl-1 text-xs ' + colorClass;
    }

    // 3. Classrooms Counter
    function updateClassCount() {
        const checkboxes = document.querySelectorAll('input[name="classroom_ids[]"]:checked');
        const count = checkboxes.length;
        document.getElementById('selectedClassCount').innerText = count + ' Rombel Terpilih';
    }

    // 4. Homeroom Class Selector Toggle
    function toggleHomeroomClassSelect() {
        const check = document.getElementById('isHomeroomCheckbox');
        const wrapper = document.getElementById('homeroomSelectWrapper');
        if (check.checked) {
            wrapper.classList.remove('hidden');
        } else {
            wrapper.classList.add('hidden');
        }
    }

    // Initialize counts on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateClassCount();
        const pwdInput = document.getElementById('passwordInput');
        if (pwdInput && pwdInput.value) {
            checkPasswordStrength(pwdInput.value);
        }
    });
</script>
@endsection
