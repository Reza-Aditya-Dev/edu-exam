@extends('layouts.admin')

@section('title', 'Tambah Peserta Didik Baru — EduExam')
@section('page_title', 'Tambah Siswa Baru')

@section('admin-content')
<div class="flex flex-col w-full gap-space-lg pb-24">
    <!-- Breadcrumb and Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-space-md bg-surface-container-lowest p-space-lg rounded-xl shadow-sm border border-slate-100">
        <div class="flex flex-col gap-1">
            <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 font-label-sm text-label-sm text-on-surface-variant mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary cursor-pointer transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">home</span>
                    <span>Master Data</span>
                </a>
                <span class="material-symbols-outlined text-[14px] text-outline">chevron_right</span>
                <a href="{{ route('admin.students') }}" class="hover:text-primary cursor-pointer transition-colors">Data Siswa</a>
                <span class="material-symbols-outlined text-[14px] text-outline">chevron_right</span>
                <span class="text-primary font-semibold">Tambah Siswa Baru</span>
            </nav>
            <h1 class="font-headline-md text-headline-md text-on-surface tracking-tight font-bold">Tambah Peserta Didik Baru</h1>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">
                Lengkapi formulir registrasi data siswa untuk integrasi akun Computer Based Test (CBT) dan penempatan rombel kelas {{ \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}.
            </p>
        </div>
        <div class="flex items-center gap-space-sm self-start md:self-auto shrink-0">
            <a href="{{ route('admin.students') }}" class="h-10 px-4 rounded-lg bg-surface-container-low text-on-surface-variant font-label-lg text-label-lg hover:bg-surface-container hover:text-on-surface flex items-center justify-center transition-colors no-underline">
                Batal
            </a>
            <button form="student-form" type="submit" name="save_action" value="publish" class="h-10 px-5 rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg hover:bg-primary shadow-sm flex items-center gap-2 transition-all active:scale-[0.98] cursor-pointer">
                <span class="material-symbols-outlined text-[18px]">save</span>
                <span>Simpan Data Siswa</span>
            </button>
        </div>
    </div>

    <!-- Error Validation Summary Alert -->
    @if ($errors->any())
        <div class="p-space-md rounded-xl bg-error-container text-on-error-container flex items-start gap-3 shadow-sm border border-red-200">
            <span class="material-symbols-outlined text-[24px] text-error shrink-0">error</span>
            <div class="flex flex-col text-sm">
                <span class="font-bold mb-1">Terdapat kesalahan pada pengisian formulir:</span>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Form Grid: 2 Columns Layout -->
    <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-start" id="student-form">
        @csrf
        <input type="hidden" name="is_active_submitted" value="1">

        <!-- LEFT COLUMN: Identitas & Akun CBT (7 Columns) -->
        <div class="lg:col-span-7 flex flex-col gap-space-lg">
            <!-- Card 1: Identitas Pokok Siswa -->
            <section class="bg-surface-container-lowest p-space-lg rounded-xl shadow-sm flex flex-col gap-space-md border border-slate-100">
                <div class="flex items-center justify-between pb-space-sm border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-primary-fixed text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]">badge</span>
                        </div>
                        <div>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Identitas Pokok Siswa</h2>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Sesuai akta kelahiran &amp; data induk sekolah</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-surface-container-low text-primary font-label-sm text-label-sm font-semibold">Wajib Diisi</span>
                </div>

                <div class="flex flex-col gap-4 mt-2">
                    <!-- Nama Lengkap -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-lg text-label-lg text-on-surface font-semibold flex items-center justify-between" for="nama-lengkap">
                            <span>Nama Lengkap Siswa <span class="text-error">*</span></span>
                            <span class="font-body-sm text-body-sm text-outline">Huruf kapital di awal kata</span>
                        </label>
                        <div class="relative">
                            <input class="w-full h-11 px-3.5 bg-surface-container-low rounded-lg text-on-surface font-body-md text-body-md focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container outline-none transition-all border border-slate-200/50 @error('name') border-error @enderror" id="nama-lengkap" name="name" placeholder="Masukkan nama lengkap siswa..." type="text" value="{{ old('name') }}" required />
                        </div>
                        @error('name') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- NISN & NIS Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-lg text-label-lg text-on-surface font-semibold flex items-center justify-between" for="nisn">
                                <span>NISN (10 Digit)</span>
                                <span class="material-symbols-outlined text-outline text-[16px] cursor-help" title="Nomor Induk Siswa Nasional">help</span>
                            </label>
                            <input class="w-full h-11 px-3.5 bg-surface-container-low rounded-lg text-on-surface font-body-md text-body-md focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container outline-none tracking-wider font-mono transition-all border border-slate-200/50 @error('nisn') border-error @enderror" id="nisn" name="nisn" maxlength="10" placeholder="Contoh: 0054819201" type="text" value="{{ old('nisn') }}" />
                            @error('nisn') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-lg text-label-lg text-on-surface font-semibold flex items-center justify-between" for="nis">
                                <span>Nomor Induk Sekolah (NIS) <span class="text-error">*</span></span>
                                <span class="text-outline text-xs">ID Siswa</span>
                            </label>
                            <input class="w-full h-11 px-3.5 bg-surface-container-low rounded-lg text-on-surface font-body-md text-body-md focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container outline-none font-mono transition-all border border-slate-200/50 @error('nis') border-error @enderror" id="nis" name="nis" placeholder="Contoh: 20260101" type="text" value="{{ old('nis') }}" required />
                            @error('nis') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="flex flex-col gap-2">
                        <span class="font-label-lg text-label-lg text-on-surface font-semibold">Jenis Kelamin <span class="text-error">*</span></span>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative flex items-center gap-3 p-3 rounded-lg bg-surface-container-low hover:bg-surface-container cursor-pointer transition-colors has-[:checked]:bg-primary-container has-[:checked]:text-on-primary border border-slate-200/50">
                                <input class="w-4 h-4 accent-primary" name="gender" type="radio" value="L" {{ old('gender', 'L') === 'L' ? 'checked' : '' }} required />
                                <span class="material-symbols-outlined text-[20px]">male</span>
                                <span class="font-label-lg text-label-lg font-semibold">Laki-laki</span>
                            </label>
                            <label class="relative flex items-center gap-3 p-3 rounded-lg bg-surface-container-low hover:bg-surface-container cursor-pointer transition-colors has-[:checked]:bg-primary-container has-[:checked]:text-on-primary border border-slate-200/50">
                                <input class="w-4 h-4 accent-primary" name="gender" type="radio" value="P" {{ old('gender') === 'P' ? 'checked' : '' }} required />
                                <span class="material-symbols-outlined text-[20px]">female</span>
                                <span class="font-label-lg text-label-lg font-semibold">Perempuan</span>
                            </label>
                        </div>
                        @error('gender') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tempat & Tanggal Lahir -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-lg text-label-lg text-on-surface font-semibold" for="tempat-lahir">Tempat Lahir</label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined text-outline text-[18px] absolute left-3 pointer-events-none">location_on</span>
                                <input class="w-full h-11 pl-9 pr-3.5 bg-surface-container-low rounded-lg text-on-surface font-body-md text-body-md focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container outline-none transition-all border border-slate-200/50" id="tempat-lahir" name="birth_place" placeholder="Kota kelahiran..." type="text" value="{{ old('birth_place') }}" />
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-lg text-label-lg text-on-surface font-semibold" for="tanggal-lahir">Tanggal Lahir</label>
                            <div class="relative flex items-center">
                                <input class="w-full h-11 px-3.5 bg-surface-container-low rounded-lg text-on-surface font-body-md text-body-md focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container outline-none transition-all border border-slate-200/50" id="tanggal-lahir" name="birth_date" type="date" value="{{ old('birth_date') }}" />
                            </div>
                        </div>
                    </div>

                    <!-- Agama & No Telepon Wali -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="flex flex-col gap-1.5 sm:col-span-1">
                            <label class="font-label-lg text-label-lg text-on-surface font-semibold" for="agama">Agama</label>
                            <div class="relative">
                                <select class="w-full h-11 px-3 pr-7 bg-surface-container-low rounded-lg text-on-surface font-body-md text-body-md focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container outline-none cursor-pointer border border-slate-200/50 appearance-none" id="agama" name="religion">
                                    <option value="Islam" {{ old('religion', 'Islam') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Kristen Protestan" {{ old('religion') == 'Kristen Protestan' ? 'selected' : '' }}>Kristen Protestan</option>
                                    <option value="Katolik" {{ old('religion') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                    <option value="Hindu" {{ old('religion') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Buddha" {{ old('religion') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                    <option value="Konghucu" {{ old('religion') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                </select>
                                <span class="material-symbols-outlined text-outline text-[16px] absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none">expand_more</span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <label class="font-label-lg text-label-lg text-on-surface font-semibold" for="telepon-ortu">No. Telepon / WhatsApp Wali</label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined text-outline text-[18px] absolute left-3 pointer-events-none">phone</span>
                                <input class="w-full h-11 pl-9 pr-3.5 bg-surface-container-low rounded-lg text-on-surface font-body-md text-body-md focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container outline-none transition-all border border-slate-200/50" id="telepon-ortu" name="phone" placeholder="08xxxxxxxxxx" type="tel" value="{{ old('phone') }}" />
                            </div>
                        </div>
                    </div>

                    <!-- Alamat Tinggal -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-lg text-label-lg text-on-surface font-semibold" for="alamat">Alamat Domisili Siswa</label>
                        <textarea class="w-full p-3 bg-surface-container-low rounded-lg text-on-surface font-body-md text-body-md focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container outline-none transition-all border border-slate-200/50 resize-y min-h-[70px]" id="alamat" name="address" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota...">{{ old('address') }}</textarea>
                    </div>
                </div>
            </section>

            <!-- Card 2: Akun Login & Akses CBT -->
            <section class="bg-surface-container-lowest p-space-lg rounded-xl shadow-sm flex flex-col gap-space-md border border-slate-100">
                <div class="flex items-center justify-between pb-space-sm border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-secondary-container text-on-secondary-container flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]">security</span>
                        </div>
                        <div>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Akun Login &amp; Akses CBT</h2>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Kredensial portal tes dan keamanan sesi siswa</p>
                        </div>
                    </div>
                    <span class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">
                        <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                        Siap CBT
                    </span>
                </div>

                <div class="flex flex-col gap-4 mt-2">
                    <!-- Email Resmi -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-lg text-label-lg text-on-surface font-semibold flex items-center justify-between" for="email-sso">
                            <span>Email Resmi Siswa / Akun Belajar <span class="text-error">*</span></span>
                            <span class="font-label-sm text-label-sm text-secondary flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">check_circle</span> Domain Terverifikasi
                            </span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined text-outline text-[18px] absolute left-3 pointer-events-none">alternate_email</span>
                            <input class="w-full h-11 pl-9 pr-3.5 bg-surface-container-low rounded-lg text-on-surface font-body-md text-body-md focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container outline-none transition-all border border-slate-200/50 @error('email') border-error @enderror" id="email-sso" name="email" placeholder="nama.siswa@sekolah.sch.id" type="email" value="{{ old('email') }}" required />
                        </div>
                        @error('email') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Username CBT -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-lg text-label-lg text-on-surface font-semibold flex items-center justify-between" for="username-cbt">
                            <span>Username Login CBT</span>
                            <span class="text-outline text-xs">Kosongkan jika ingin disamakan dengan NIS</span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined text-outline text-[18px] absolute left-3 pointer-events-none">account_circle</span>
                            <input class="w-full h-11 pl-9 pr-3.5 bg-surface-container-low rounded-lg text-on-surface font-body-md text-body-md focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container outline-none font-mono transition-all border border-slate-200/50 @error('username') border-error @enderror" id="username-cbt" name="username" placeholder="Contoh: 20260101 atau nama_siswa" type="text" value="{{ old('username') }}" />
                        </div>
                        @error('username') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password Akun Ujian -->
                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <label class="font-label-lg text-label-lg text-on-surface font-semibold" for="exam-password">Kata Sandi Akun Ujian <span class="text-error">*</span></label>
                            <button class="font-label-sm text-label-sm text-primary hover:underline flex items-center gap-1 cursor-pointer bg-transparent border-none p-0" onclick="generateRandomPass()" type="button">
                                <span class="material-symbols-outlined text-[14px]">autorenew</span>
                                <span>Acak Sandi Otomatis</span>
                            </button>
                        </div>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined text-outline text-[18px] absolute left-3 pointer-events-none">lock</span>
                            <input class="w-full h-11 pl-9 pr-12 bg-surface-container-low rounded-lg text-on-surface font-body-md text-body-md focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container outline-none transition-all font-mono border border-slate-200/50 @error('password') border-error @enderror" id="exam-password" name="password" placeholder="Minimal 6 karakter unik..." type="password" value="{{ old('password', '123456') }}" required minlength="6" />
                            <button class="absolute right-3 text-outline hover:text-on-surface flex items-center justify-center p-1 cursor-pointer bg-transparent border-none" onclick="togglePassVisibility()" type="button" title="Lihat/Sembunyikan Sandi">
                                <span class="material-symbols-outlined text-[20px]" id="pass-toggle-icon">visibility</span>
                            </button>
                        </div>
                        @error('password') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                        <p class="font-body-sm text-body-sm text-outline">Kombinasi huruf atau angka. Standar bawaan: <strong>123456</strong>.</p>
                    </div>

                    <!-- Toggle Switches -->
                    <div class="flex flex-col gap-3 pt-2">
                        <!-- Status Akun Toggle -->
                        <label class="flex items-center justify-between p-3.5 rounded-lg bg-surface-container-low hover:bg-surface-container cursor-pointer transition-colors border border-slate-200/50">
                            <div class="flex flex-col">
                                <span class="font-label-lg text-label-lg text-on-surface font-semibold">Status Akun Siswa</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant">Aktif - Siswa dapat masuk ke dashboard ujian &amp; mengerjakan soal</span>
                            </div>
                            <div class="relative inline-flex items-center cursor-pointer shrink-0 ml-4">
                                <input checked class="sr-only peer" name="is_active" type="checkbox" value="1" id="isActiveInput" />
                                <div class="w-11 h-6 bg-outline-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-surface-container-lowest after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-surface-container-lowest after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-secondary"></div>
                            </div>
                        </label>

                        <!-- IP Restriction Info Toggle -->
                        <label class="flex items-center justify-between p-3.5 rounded-lg bg-surface-container-low hover:bg-surface-container cursor-pointer transition-colors border border-slate-200/50">
                            <div class="flex flex-col">
                                <span class="font-label-lg text-label-lg text-on-surface font-semibold">Batasi Akses IP / Perangkat Ujian</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant">Khusus jaringan laboratorium CBT atau Wi-Fi sekolah resmi</span>
                            </div>
                            <div class="relative inline-flex items-center cursor-pointer shrink-0 ml-4">
                                <input checked class="sr-only peer" type="checkbox" />
                                <div class="w-11 h-6 bg-outline-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-surface-container-lowest after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-surface-container-lowest after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-container"></div>
                            </div>
                        </label>
                    </div>
                </div>
            </section>
        </div>

        <!-- RIGHT COLUMN: Rombel, Foto & Sinkronisasi Dapodik (5 Columns) -->
        <div class="lg:col-span-5 flex flex-col gap-space-lg">
            <!-- Card 3: Penempatan Rombel & Akademik -->
            <section class="bg-surface-container-lowest p-space-lg rounded-xl shadow-sm flex flex-col gap-space-md border border-slate-100">
                <div class="flex items-center gap-2.5 pb-space-sm border-b border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-surface-container text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">meeting_room</span>
                    </div>
                    <div>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Penempatan Rombel &amp; Akademik</h2>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Tahun ajaran &amp; kelas aktif pengujian</p>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <!-- Tahun Ajaran -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-lg text-label-lg text-on-surface font-semibold" for="tahun-ajaran">Tahun Ajaran Masuk <span class="text-error">*</span></label>
                        <div class="relative">
                            <select class="w-full h-11 px-3.5 pr-8 bg-surface-container-low rounded-lg text-on-surface font-body-md text-body-md focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container outline-none cursor-pointer border border-slate-200/50 appearance-none" id="tahun-ajaran" name="academic_year_id">
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ ($academicYear && $academicYear->id == $ay->id) ? 'selected' : '' }}>
                                        {{ $ay->name }} {{ $ay->is_active ? '(Periode Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined text-outline text-[18px] absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    <!-- Rombel / Kelas -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-lg text-label-lg text-on-surface font-semibold" for="rombel">
                            Rombongan Belajar (Kelas) <span class="text-error">*</span>
                        </label>
                        <div class="relative">
                            <select class="w-full h-11 px-3.5 pr-8 bg-surface-container-low rounded-lg text-on-surface font-body-md text-body-md focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container outline-none cursor-pointer border border-slate-200/50 appearance-none @error('classroom_id') border-error @enderror" id="rombel" name="classroom_id" required>
                                <option value="">-- Pilih Kelas Siswa --</option>
                                @foreach($classrooms as $c)
                                    <option value="{{ $c->id }}" {{ old('classroom_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }} (Wali: {{ $c->homeroomTeacher->name ?? 'Belum Ada' }})
                                    </option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined text-outline text-[18px] absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none">expand_more</span>
                        </div>
                        @error('classroom_id') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jalur Masuk Info Card -->
                    <div class="p-3 bg-surface-container-low rounded-lg flex items-center justify-between border border-slate-200/50">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-[20px]">verified_user</span>
                            <div class="flex flex-col">
                                <span class="font-label-md text-label-md text-on-surface font-semibold">Integrasi Dapodik &amp; CBT</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant">Siswa Reguler Tahun Ajaran {{ $academicYear ? $academicYear->name : '2026/2027' }}</span>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">Terdaftar</span>
                    </div>
                </div>
            </section>

            <!-- Card 4: Foto Profil Siswa -->
            <section class="bg-surface-container-lowest p-space-lg rounded-xl shadow-sm flex flex-col gap-space-md border border-slate-100">
                <div class="flex items-center justify-between pb-space-sm border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-surface-container-high text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]">account_box</span>
                        </div>
                        <div>
                            <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Pasfoto Profil Siswa</h2>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Verifikasi kartu peserta &amp; proctoring CBT</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-outline text-[20px]">photo_camera</span>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-space-md bg-surface-container-low p-4 rounded-xl border border-slate-200/50">
                    <!-- Photo Preview Container -->
                    <div class="relative shrink-0 w-28 h-28 rounded-xl overflow-hidden shadow-md bg-surface-container-high flex items-center justify-center border border-slate-200">
                        <img id="avatarPreview" alt="Foto Siswa" class="w-full h-full object-cover hidden" />
                        <div id="avatarPlaceholder" class="flex flex-col items-center justify-center text-outline text-center p-2">
                            <span class="material-symbols-outlined text-[36px]">person</span>
                            <span class="text-[10px] font-semibold mt-1 uppercase">Pasfoto 3x4</span>
                        </div>
                        <div class="absolute bottom-1 right-1 bg-secondary text-surface-container-lowest rounded-full p-0.5 shadow-sm">
                            <span class="material-symbols-outlined text-[14px] block">verified</span>
                        </div>
                    </div>

                    <!-- Upload Controls -->
                    <div class="flex flex-col gap-2 w-full text-center sm:text-left">
                        <div class="flex flex-col">
                            <span class="font-label-lg text-label-lg text-on-surface font-semibold">Unggah Pasfoto Siswa</span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant">Format JPG, PNG, atau WEBP maks. 2MB.</span>
                        </div>
                        <div class="flex items-center gap-2 mt-1 justify-center sm:justify-start">
                            <label class="h-9 px-3.5 rounded-lg bg-primary-container text-on-primary font-label-md text-label-md hover:bg-primary cursor-pointer flex items-center gap-1.5 transition-colors shadow-sm">
                                <span class="material-symbols-outlined text-[16px]">upload_file</span>
                                <span>Pilih Berkas Foto</span>
                                <input id="avatarInput" accept="image/png, image/jpeg, image/jpg, image/webp" class="sr-only" name="avatar" type="file" onchange="previewAvatar(this)" />
                            </label>
                            <button id="removeAvatarBtn" class="h-9 px-3 rounded-lg bg-error-container text-on-error-container font-label-md text-label-md hover:bg-error hover:text-on-error flex items-center gap-1 transition-colors cursor-pointer hidden" onclick="removeAvatar()" type="button">
                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-surface-container-low rounded-lg flex items-start gap-2.5 border border-slate-200/40">
                    <span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">info</span>
                    <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                        Foto ini otomatis dicetak pada <strong>Kartu Peserta Ujian CBT</strong> dan dimuat saat siswa login ke portal ujian.
                    </p>
                </div>
            </section>

            <!-- Card 5: Status Verifikasi Dapodik -->
            <section class="bg-surface-container-lowest p-space-lg rounded-xl shadow-sm flex flex-col gap-space-sm border border-slate-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[22px]">cloud_sync</span>
                        <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Status Database Dapodik</h3>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">done_all</span> Siap Sinkron
                    </span>
                </div>

                <div class="mt-2 p-3 bg-surface-container-low rounded-lg flex flex-col gap-1.5 border border-slate-200/50">
                    <div class="flex items-center justify-between font-body-sm text-body-sm">
                        <span class="text-on-surface-variant">Server Lembaga:</span>
                        <span class="text-on-surface font-mono font-medium">{{ \App\Models\SchoolSetting::get('school_name', 'SMA Nusantara') }}</span>
                    </div>
                    <div class="flex items-center justify-between font-body-sm text-body-sm">
                        <span class="text-on-surface-variant">NPSN Resmi:</span>
                        <span class="text-on-surface font-semibold font-mono">{{ \App\Models\SchoolSetting::get('school_npsn', '20103482') }}</span>
                    </div>
                    <div class="flex items-center justify-between font-body-sm text-body-sm">
                        <span class="text-on-surface-variant">Koneksi CBT:</span>
                        <span class="text-secondary font-medium flex items-center gap-0.5">
                            <span class="material-symbols-outlined text-[14px]">check</span> Siap Mengikuti Ujian
                        </span>
                    </div>
                </div>

                <a href="{{ route('admin.students') }}" class="w-full mt-2 h-9 rounded-lg bg-surface-container text-primary hover:bg-surface-container-high font-label-md text-label-md flex items-center justify-center gap-1.5 transition-colors no-underline">
                    <span class="material-symbols-outlined text-[16px]">format_list_bulleted</span>
                    <span>Lihat Daftar Siswa Terdaftar</span>
                </a>
            </section>
        </div>
    </form>

    <!-- Sticky Bottom Actions Bar -->
    <div class="fixed bottom-0 left-0 lg:left-72 right-0 bg-surface-container-lowest/95 backdrop-blur-md px-space-md md:px-space-lg py-3 shadow-[0_-4px_16px_rgba(0,0,0,0.06)] z-30 flex items-center justify-between border-t border-slate-200/60">
        <div class="flex items-center gap-2 text-on-surface-variant hidden md:flex">
            <span class="material-symbols-outlined text-secondary text-[20px]">check_circle</span>
            <span class="font-body-sm text-body-sm">Formulir siap disimpan ke database &amp; didaftarkan ke sesi CBT</span>
        </div>
        <div class="flex items-center gap-space-sm w-full md:w-auto justify-end">
            <a href="{{ route('admin.students') }}" class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface-variant font-label-lg text-label-lg hover:bg-surface-container hover:text-on-surface flex items-center justify-center transition-colors no-underline">
                Batal
            </a>
            <button form="student-form" type="submit" name="save_action" value="draft" class="h-11 px-4 rounded-lg bg-surface-container text-primary font-label-lg text-label-lg hover:bg-surface-container-high flex items-center gap-1.5 transition-colors cursor-pointer border-none">
                <span class="material-symbols-outlined text-[18px]">draft</span>
                <span>Simpan sebagai Draf</span>
            </button>
            <button form="student-form" type="submit" name="save_action" value="publish" class="h-11 px-6 rounded-lg bg-primary-container text-on-primary font-label-lg text-label-lg hover:bg-primary shadow-md flex items-center gap-2 transition-all active:scale-[0.98] cursor-pointer border-none">
                <span class="material-symbols-outlined text-[20px]">check</span>
                <span>Simpan &amp; Daftarkan Siswa</span>
            </button>
        </div>
    </div>
</div>

<script>
    function togglePassVisibility() {
        const input = document.getElementById('exam-password');
        const icon = document.getElementById('pass-toggle-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    }

    function generateRandomPass() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%';
        let result = 'CBT' + (new Date().getFullYear().toString().slice(-2)) + '#';
        for (let i = 0; i < 4; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        const input = document.getElementById('exam-password');
        input.value = result;
        input.type = 'text';
        document.getElementById('pass-toggle-icon').textContent = 'visibility_off';
    }

    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');
                const placeholder = document.getElementById('avatarPlaceholder');
                const removeBtn = document.getElementById('removeAvatarBtn');

                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
                removeBtn.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeAvatar() {
        const input = document.getElementById('avatarInput');
        const preview = document.getElementById('avatarPreview');
        const placeholder = document.getElementById('avatarPlaceholder');
        const removeBtn = document.getElementById('removeAvatarBtn');

        input.value = '';
        preview.src = '';
        preview.classList.add('hidden');
        placeholder.classList.remove('hidden');
        removeBtn.classList.add('hidden');
    }
</script>
@endsection
