@extends('layouts.admin')

@section('title', 'Edit Data Siswa — EduExam')
@section('page_title', 'Edit Data Siswa')

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
                <a href="{{ route('admin.students') }}" class="hover:text-primary transition-colors cursor-pointer">Data Siswa</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-semibold truncate max-w-[200px]">Edit: {{ $student->name }}</span>
            </nav>
            <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">Edit Profil &amp; Rombel Siswa</h1>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1 max-w-2xl">
                Perbarui biodata profil, penempatan rombongan belajar kelas (1 siswa = 1 kelas), dan kredensial login CBT.
            </p>
        </div>

        <!-- Quick Actions Header -->
        <div class="flex items-center gap-space-sm self-start md:self-auto shrink-0">
            <a href="{{ route('admin.students') }}" class="px-5 py-2.5 rounded-lg bg-surface-container-low hover:bg-surface-container-high text-on-surface font-label-lg text-label-lg transition-colors shadow-sm inline-flex items-center gap-1.5 no-underline">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                <span>Batal</span>
            </a>
            <button onclick="document.getElementById('editStudentForm').requestSubmit()" type="button" class="px-5 py-2.5 rounded-lg bg-primary-container hover:bg-primary text-on-primary font-label-lg text-label-lg shadow-sm flex items-center gap-2 transition-all active:scale-95 cursor-pointer">
                <span class="material-symbols-outlined text-[18px]">save</span>
                <span>Simpan Perubahan</span>
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
    <form action="{{ route('admin.students.update', $student->id) }}" method="POST" id="editStudentForm" class="grid grid-cols-1 xl:grid-cols-12 gap-space-xl items-start">
        @csrf
        @method('PUT')

        <!-- LEFT COLUMN: Data Pokok & Penempatan Kelas (7 cols on XL) -->
        <div class="xl:col-span-7 flex flex-col gap-space-xl">

            <!-- Card 1: Identitas Pokok & Rombongan Belajar -->
            <section class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border border-slate-100 flex flex-col gap-space-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-[22px]">badge</span>
                        </div>
                        <div>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Identitas Siswa &amp; Kelas</h2>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Penetapan rombongan belajar aktif dan data dapodik</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">
                        1 Siswa = 1 Rombel
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">

                    <!-- SEKSI KHUSUS KELAS TERDAFTAR (Full Width) -->
                    <div class="md:col-span-2 p-4 rounded-xl bg-surface-container-low border border-slate-200/80 flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <label class="font-label-md text-label-md text-on-surface font-bold flex items-center gap-1.5" for="classroom_id">
                                <span class="material-symbols-outlined text-primary text-[20px]">meeting_room</span>
                                <span>Kelas Terdaftar (Rombongan Belajar) <span class="text-error">*</span></span>
                            </label>

                            @php
                                $assignedClass = $student->classrooms->first();
                            @endphp
                            @if($assignedClass)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-label-sm font-semibold">
                                    <span class="material-symbols-outlined text-[14px]">check</span>
                                    <span>Saat ini: {{ $assignedClass->name }}</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 font-label-sm text-label-sm font-semibold">
                                    <span class="material-symbols-outlined text-[14px]">warning</span>
                                    <span>Belum ada kelas</span>
                                </span>
                            @endif
                        </div>

                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px] pointer-events-none">domain</span>
                            <select 
                                name="classroom_id" 
                                id="classroom_id" 
                                class="w-full bg-surface-container-lowest focus:bg-surface-container-lowest rounded-lg pl-11 pr-10 py-2.5 font-body-md text-body-md text-on-surface outline-none border border-slate-200 focus:border-primary-container transition-all focus:shadow-md cursor-pointer @error('classroom_id') border-error @enderror" 
                                required
                            >
                                <option value="">-- Pilih Rombongan Belajar Kelas --</option>
                                @php
                                    $selectedClassId = old('classroom_id', $assignedClass?->id);
                                @endphp
                                @foreach($classrooms as $c)
                                    <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }} (Tingkat {{ $c->grade }} - {{ $c->academicYear ? $c->academicYear->name : 'Aktif' }})
                                    </option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-3.5 text-outline pointer-events-none text-[20px]">expand_more</span>
                        </div>
                        <span class="font-body-sm text-[11px] text-on-surface-variant">
                            Siswa hanya dapat terdaftar pada <strong>satu rombongan belajar aktif</strong>. Mengubah kelas di sini akan otomatis memperbarui paket ujian dan absensi siswa ke kelas baru.
                        </span>
                        @error('classroom_id')
                            <span class="text-error font-body-sm text-xs mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nama Lengkap Siswa -->
                    <div class="md:col-span-2 flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold" for="name">
                            Nama Lengkap Siswa <span class="text-error font-bold">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px] pointer-events-none">person</span>
                            <input 
                                name="name" 
                                id="name" 
                                class="w-full bg-surface-container-low focus:bg-surface-container-lowest rounded-lg pl-11 pr-4 py-2.5 font-body-md text-body-md text-on-surface outline-none border border-transparent focus:border-primary-container transition-all focus:shadow-md @error('name') border-error @enderror" 
                                placeholder="Contoh: Muhammad Rizky Pratama" 
                                type="text" 
                                value="{{ old('name', $student->name) }}" 
                                required
                            />
                        </div>
                        @error('name')
                            <span class="text-error font-body-sm text-xs mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- NISN (Nomor Induk Siswa Nasional) -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold flex items-center justify-between" for="nisn">
                            <span>NISN (10 Digit)</span>
                            <span class="material-symbols-outlined text-outline text-[16px] cursor-help" title="Nomor Induk Siswa Nasional (Kemendikbud - Opsional)">help</span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px] pointer-events-none">badge</span>
                            <input 
                                name="nisn" 
                                id="nisn" 
                                maxlength="10" 
                                class="w-full bg-surface-container-low focus:bg-surface-container-lowest rounded-lg pl-11 pr-4 py-2.5 font-body-md text-body-md text-on-surface outline-none border border-transparent focus:border-primary-container transition-all focus:shadow-md font-mono tracking-wider @error('nisn') border-error @enderror" 
                                placeholder="Contoh: 0054819201" 
                                type="text" 
                                value="{{ old('nisn', $student->nisn) }}" 
                            />
                        </div>
                        @error('nisn')
                            <span class="text-error font-body-sm text-xs mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- NIS (Nomor Induk Siswa) -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold" for="nis">
                            Nomor Induk Siswa (NIS) <span class="text-error font-bold">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px] pointer-events-none">pin</span>
                            <input 
                                name="nis" 
                                id="nis" 
                                class="w-full bg-surface-container-low focus:bg-surface-container-lowest rounded-lg pl-11 pr-4 py-2.5 font-body-md text-body-md text-on-surface outline-none border border-transparent focus:border-primary-container transition-all focus:shadow-md font-mono @error('nis') border-error @enderror" 
                                placeholder="Contoh: 100200300" 
                                type="text" 
                                value="{{ old('nis', $student->nis) }}" 
                                required
                            />
                        </div>
                        @error('nis')
                            <span class="text-error font-body-sm text-xs mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold">
                            Jenis Kelamin <span class="text-error font-bold">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-2 h-[44px]">
                            @php $currGender = old('gender', $student->gender ?? 'L'); @endphp
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
                        <label class="font-label-md text-label-md text-on-surface font-semibold" for="phone">
                            Nomor Telepon / WhatsApp
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px] pointer-events-none">call</span>
                            <input 
                                name="phone" 
                                id="phone" 
                                class="w-full bg-surface-container-low focus:bg-surface-container-lowest rounded-lg pl-11 pr-4 py-2.5 font-body-md text-body-md text-on-surface outline-none border border-transparent focus:border-primary-container transition-all focus:shadow-md @error('phone') border-error @enderror" 
                                placeholder="08xxxxxxxxxx" 
                                type="tel" 
                                value="{{ old('phone', $student->phone) }}" 
                            />
                        </div>
                        @error('phone')
                            <span class="text-error font-body-sm text-xs mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Status Akun -->
                    <div class="md:col-span-2 flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold" for="is_active">
                            Status Akun Siswa
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px] pointer-events-none">verified_user</span>
                            <select 
                                name="is_active" 
                                id="is_active" 
                                class="w-full bg-surface-container-low focus:bg-surface-container-lowest rounded-lg pl-11 pr-10 py-2.5 font-body-md text-body-md text-on-surface outline-none border border-transparent focus:border-primary-container transition-all focus:shadow-md cursor-pointer"
                            >
                                <option value="1" {{ old('is_active', $student->is_active) ? 'selected' : '' }}>Aktif (Dapat Mengikuti Ujian)</option>
                                <option value="0" {{ !old('is_active', $student->is_active) ? 'selected' : '' }}>Nonaktif (Diblokir)</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3.5 text-outline pointer-events-none text-[20px]">expand_more</span>
                        </div>
                    </div>

                    <!-- Alamat Domisili -->
                    <div class="md:col-span-2 flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold" for="address">
                            Alamat Domisili <span class="text-on-surface-variant font-normal text-xs">(Opsional)</span>
                        </label>
                        <textarea 
                            name="address" 
                            id="address" 
                            rows="2" 
                            class="w-full bg-surface-container-low focus:bg-surface-container-lowest rounded-lg p-3 font-body-md text-body-md text-on-surface outline-none border border-transparent focus:border-primary-container transition-all focus:shadow-md @error('address') border-error @enderror" 
                            placeholder="Alamat lengkap tempat tinggal siswa..."
                        >{{ old('address', $student->address) }}</textarea>
                        @error('address')
                            <span class="text-error font-body-sm text-xs mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </section>
        </div>

        <!-- RIGHT COLUMN: Kredensial CBT & Informasi Akun (5 cols on XL) -->
        <div class="xl:col-span-5 flex flex-col gap-space-xl">

            <!-- Card 2: Ringkasan Akun Siswa -->
            <section class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border border-slate-100 flex flex-col gap-space-md">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-[22px]">account_circle</span>
                        </div>
                        <div>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Ringkasan Profil Siswa</h2>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Data akun peserta CBT</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-space-md p-space-md rounded-xl bg-surface-container-low border border-slate-200/60">
                    <div class="relative shrink-0 w-16 h-16 rounded-2xl overflow-hidden shadow-sm bg-primary-container text-on-primary flex items-center justify-center font-bold text-2xl border-2 border-white">
                        @if($student->avatar)
                            <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($student->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="font-headline-sm text-[16px] text-on-surface truncate font-bold">{{ $student->name }}</span>
                        <div class="flex items-center gap-1.5 text-xs text-on-surface-variant font-mono">
                            <span>NIS: {{ $student->nis }}</span>
                            @if($student->nisn)
                                <span class="text-outline">•</span>
                                <span>NISN: {{ $student->nisn }}</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="px-2 py-0.5 rounded-full {{ $student->is_active ? 'bg-secondary-container text-on-secondary-container' : 'bg-error-container text-on-error-container' }} font-label-sm text-[11px] font-semibold">
                                {{ $student->is_active ? 'Akun Aktif' : 'Nonaktif' }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full bg-surface-container-high text-on-surface font-label-sm text-[11px] font-semibold truncate">
                                {{ $assignedClass ? $assignedClass->name : 'Tanpa Kelas' }}
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Card 3: Kredensial Login CBT Siswa -->
            <section class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm border border-slate-100 flex flex-col gap-space-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary flex items-center justify-center shadow-xs">
                            <span class="material-symbols-outlined text-[22px]">lock</span>
                        </div>
                        <div>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Kredensial Login CBT</h2>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Akun login untuk mengikuti sesi ujian</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-secondary text-[22px]" title="Keamanan Terjamin">security</span>
                </div>

                <div class="flex flex-col gap-space-md">
                    <!-- Email Siswa -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold" for="email">
                            Alamat Email Siswa <span class="text-error font-bold">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px] pointer-events-none">mail</span>
                            <input 
                                name="email" 
                                id="email" 
                                class="w-full bg-surface-container-low focus:bg-surface-container-lowest rounded-lg pl-11 pr-4 py-2.5 font-body-md text-body-md text-on-surface outline-none border border-transparent focus:border-primary-container transition-all focus:shadow-md @error('email') border-error @enderror" 
                                placeholder="siswa@eduexam.com" 
                                type="email" 
                                value="{{ old('email', $student->email) }}" 
                                required
                            />
                        </div>
                        @error('email')
                            <span class="text-error font-body-sm text-xs mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Username Siswa -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold" for="username">
                            Username Sistem CBT
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px] pointer-events-none">alternate_email</span>
                            <input 
                                name="username" 
                                id="username" 
                                class="w-full bg-surface-container-low focus:bg-surface-container-lowest rounded-lg pl-11 pr-4 py-2.5 font-body-md text-body-md text-on-surface outline-none border border-transparent focus:border-primary-container transition-all focus:shadow-md @error('username') border-error @enderror" 
                                placeholder="Contoh: rizky123" 
                                type="text" 
                                value="{{ old('username', $student->username) }}" 
                            />
                        </div>
                        @error('username')
                            <span class="text-error font-body-sm text-xs mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Ganti Password Siswa (Opsional) -->
                    <div class="flex flex-col gap-1.5 pt-2 border-t border-slate-100">
                        <div class="flex items-center justify-between">
                            <label class="font-label-md text-label-md text-on-surface font-semibold" for="passwordInput">
                                Ganti Password Baru <span class="text-on-surface-variant font-normal text-xs">(Opsional)</span>
                            </label>
                            <button onclick="generateRandomPassword()" class="text-primary hover:underline font-label-sm text-label-sm flex items-center gap-1 cursor-pointer transition-colors" type="button">
                                <span class="material-symbols-outlined text-[15px]">autorenew</span>
                                <span>Acak Sandi</span>
                            </button>
                        </div>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px] pointer-events-none">key</span>
                            <input 
                                id="passwordInput" 
                                name="password" 
                                class="w-full bg-surface-container-low focus:bg-surface-container-lowest rounded-lg pl-11 pr-11 py-2.5 font-body-md text-body-md text-on-surface outline-none border border-transparent focus:border-primary-container transition-all focus:shadow-md @error('password') border-error @enderror" 
                                type="password" 
                                placeholder="Kosongkan jika tidak diubah" 
                                minlength="6"
                                oninput="checkPasswordStrength(this.value)"
                            />
                            <button id="togglePasswordBtn" onclick="togglePasswordVisibility()" class="absolute right-3.5 text-outline hover:text-on-surface flex items-center cursor-pointer transition-colors" type="button" title="Lihat/Sembunyikan Sandi">
                                <span id="togglePasswordIcon" class="material-symbols-outlined text-[20px]">visibility_off</span>
                            </button>
                        </div>
                        <span class="font-body-sm text-[11px] text-on-surface-variant">Hanya isi bidang ini jika siswa lupa kata sandi atau ingin mereset sandi.</span>
                        
                        <!-- Strength Bar (Hidden until user types) -->
                        <div id="strengthContainer" class="flex items-center gap-1.5 mt-1 hidden">
                            <div id="bar1" class="flex-1 h-1.5 rounded-full bg-surface-container-high transition-colors"></div>
                            <div id="bar2" class="flex-1 h-1.5 rounded-full bg-surface-container-high transition-colors"></div>
                            <div id="bar3" class="flex-1 h-1.5 rounded-full bg-surface-container-high transition-colors"></div>
                            <div id="bar4" class="flex-1 h-1.5 rounded-full bg-surface-container-high transition-colors"></div>
                            <span id="strengthText" class="font-label-sm text-label-sm text-outline font-semibold pl-1 text-xs"></span>
                        </div>
                        @error('password')
                            <span class="text-error font-body-sm text-xs mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </section>
        </div>

        <!-- Sticky Floating Action Bar at the Bottom -->
        <div class="xl:col-span-12 sticky bottom-4 z-30 p-space-md rounded-2xl bg-surface-container-lowest/95 backdrop-blur-xl shadow-xl border border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-space-md mt-space-md">
            <div class="flex items-center gap-2 text-on-surface-variant font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-secondary text-[20px]">info</span>
                <span>Pastikan penempatan rombongan belajar kelas sudah tepat sesuai tahun ajaran berjalan.</span>
            </div>
            <div class="flex items-center gap-space-sm w-full sm:w-auto justify-end">
                <a href="{{ route('admin.students') }}" class="px-5 py-2.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-lg text-label-lg transition-colors no-underline">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary-container hover:bg-primary text-on-primary font-label-lg text-label-lg shadow-md hover:shadow-lg transition-all flex items-center gap-2 active:scale-95 cursor-pointer font-bold">
                    <span class="material-symbols-outlined text-[20px]">save</span>
                    <span>Simpan Perubahan Siswa</span>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Password visibility toggle
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

    // Random password generator
    function generateRandomPassword() {
        const chars = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789@#$";
        let pass = "";
        for (let i = 0; i < 8; i++) {
            pass += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        const pwd = document.getElementById('passwordInput');
        pwd.value = pass;
        pwd.type = 'text';
        document.getElementById('togglePasswordIcon').innerText = 'visibility';
        checkPasswordStrength(pass);
    }

    // Password strength check
    function checkPasswordStrength(val) {
        const container = document.getElementById('strengthContainer');
        if (!val || val.length === 0) {
            container.classList.add('hidden');
            return;
        }
        container.classList.remove('hidden');

        let score = 0;
        if (val.length >= 6) score++;
        if (val.length >= 8) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const bar1 = document.getElementById('bar1');
        const bar2 = document.getElementById('bar2');
        const bar3 = document.getElementById('bar3');
        const bar4 = document.getElementById('bar4');
        const textSpan = document.getElementById('strengthText');

        [bar1, bar2, bar3, bar4].forEach(b => b.className = 'flex-1 h-1.5 rounded-full bg-surface-container-high transition-colors');

        const color = score <= 1 ? 'bg-error' : (score === 2 ? 'bg-amber-500' : 'bg-secondary');
        const txt = score <= 1 ? 'Lemah' : (score === 2 ? 'Cukup' : (score === 3 ? 'Kuat' : 'Sangat Kuat'));
        const txtColor = score <= 1 ? 'text-error' : (score === 2 ? 'text-amber-500' : 'text-secondary');

        if (score >= 1) bar1.className = 'flex-1 h-1.5 rounded-full ' + color;
        if (score >= 2) bar2.className = 'flex-1 h-1.5 rounded-full ' + color;
        if (score >= 3) bar3.className = 'flex-1 h-1.5 rounded-full ' + color;
        if (score >= 4) bar4.className = 'flex-1 h-1.5 rounded-full ' + color;

        textSpan.innerText = txt;
        textSpan.className = 'font-label-sm text-label-sm font-semibold pl-1 text-xs ' + txtColor;
    }
</script>
@endsection
