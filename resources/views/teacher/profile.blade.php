@extends('layouts.teacher')

@section('title', 'Profil Pendidik & Pengaturan Akun — EduExam')
@section('page_title', 'Profil Guru')

@section('teacher-content')
<div class="flex flex-col w-full pb-16">
    <!-- Top Breadcrumb & Page Summary Banner -->
    <div class="w-full flex flex-col md:flex-row md:items-center justify-between gap-space-md py-space-lg mb-space-md">
        <div class="flex flex-col gap-1 min-w-0">
            <div class="flex items-center gap-space-xs text-on-surface-variant font-label-sm text-label-sm">
                <a class="hover:text-primary transition-colors flex items-center gap-1" href="{{ route('teacher.dashboard') }}">
                    <span class="material-symbols-outlined text-[14px]">home</span>
                    <span>EduExam</span>
                </a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-semibold">Profil Guru</span>
            </div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">Profil Pendidik &amp; Pengaturan Akun</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Kelola data identitas pengajar, mata pelajaran yang diampu, dan kredensial keamanan.</p>
        </div>
        <!-- Quick Status Badge -->
        <div class="flex items-center gap-space-sm bg-surface-container-low px-space-md py-space-sm rounded-xl self-start md:self-auto shadow-sm border border-surface-container-high/60">
            <div class="w-2.5 h-2.5 rounded-full bg-secondary animate-pulse"></div>
            <div class="flex flex-col">
                <span class="font-label-sm text-label-sm text-on-surface-variant">Status Sinkronisasi Dapodik</span>
                <span class="font-label-md text-label-md text-on-surface font-semibold">
                    Terverifikasi Aktif {{ $activeYear ? $activeYear->name : '2026/2027' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Main Asymmetric Workspace Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-start">
        
        <!-- LEFT COLUMN: Identity & Bio Card (4 of 12 columns) -->
        <div class="lg:col-span-4 flex flex-col gap-space-md">
            <!-- Profile Card -->
            <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm flex flex-col items-center text-center relative overflow-hidden border border-surface-container-high/40">
                <!-- Subtle Top Accent Strip -->
                <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-primary to-primary-container"></div>
                
                <!-- Avatar Wrapper with Change Action -->
                <div class="relative mt-space-sm mb-space-md group">
                    <div class="w-32 h-32 rounded-full overflow-hidden shadow-md bg-surface-container-high p-1 ring-4 ring-primary/10">
                        <img id="avatarDisplay" alt="Foto Profil {{ $user->name }}" class="w-full h-full object-cover rounded-full bg-surface-container-lowest" src="{{ $user->avatar_url }}"/>
                    </div>
                    <button type="button" onclick="document.getElementById('avatarInput').click()" class="absolute bottom-1 right-1 flex items-center justify-center w-9 h-9 bg-primary text-on-primary rounded-full shadow-md hover:bg-primary-container transition-transform hover:scale-105" title="Ganti Foto Profil">
                        <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                    </button>
                </div>
                
                <h2 class="font-headline-sm text-headline-sm text-on-surface mb-1 font-bold">{{ $user->name }}</h2>
                
                <!-- Role Badge -->
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/40 text-on-secondary-container font-label-sm text-label-sm mb-space-md font-semibold">
                    <span class="material-symbols-outlined text-[16px] text-secondary">verified</span>
                    <span>Guru Pengajar {{ $schoolName }}</span>
                </div>
                
                <!-- Homeroom Assignment Callout -->
                <div class="w-full bg-surface-container-low rounded-lg p-space-sm mb-space-md flex items-center justify-between text-left border border-surface-container-high/50">
                    <div class="flex items-center gap-space-sm">
                        <div class="w-8 h-8 rounded bg-primary-fixed flex items-center justify-center text-on-primary-fixed flex-shrink-0">
                            <span class="material-symbols-outlined text-[18px]">meeting_room</span>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Penugasan Wali Kelas</span>
                            <span class="font-label-md text-label-md text-on-surface font-semibold truncate">
                                {{ $homeroom ? 'Wali Kelas ' . $homeroom->name : 'Bukan Wali Kelas' }}
                            </span>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded bg-surface-container-lowest text-on-surface font-label-sm text-label-sm shadow-sm flex-shrink-0">
                        {{ $homeroom ? 'Aktif' : 'N/A' }}
                    </span>
                </div>
                
                <!-- Key Identifiers List -->
                <div class="w-full flex flex-col gap-space-sm text-left">
                    <div class="flex flex-col py-1.5 border-b border-surface-container-high/40">
                        <span class="font-label-sm text-label-sm text-on-surface-variant">Nomor Induk Pegawai (NIP)</span>
                        <span class="font-label-md text-label-md text-on-surface tracking-wide font-semibold">{{ $user->nip ?: '-' }}</span>
                    </div>
                    <div class="flex flex-col py-1.5 border-b border-surface-container-high/40">
                        <span class="font-label-sm text-label-sm text-on-surface-variant">NUPTK / Username Akun</span>
                        <span class="font-label-md text-label-md text-on-surface tracking-wide font-semibold">{{ $user->username }}</span>
                    </div>
                    <div class="flex flex-col py-1.5 border-b border-surface-container-high/40">
                        <span class="font-label-sm text-label-sm text-on-surface-variant">Email Kedinasan</span>
                        <span class="font-body-md text-body-md text-on-surface truncate font-medium">{{ $user->email }}</span>
                    </div>
                    <div class="flex flex-col py-1.5 border-b border-surface-container-high/40">
                        <span class="font-label-sm text-label-sm text-on-surface-variant">Nomor Telepon / WhatsApp</span>
                        <span class="font-body-md text-body-md text-on-surface font-medium">{{ $user->phone ?: '-' }}</span>
                    </div>
                    <div class="flex flex-col py-1.5">
                        <span class="font-label-sm text-label-sm text-on-surface-variant">Unit Sekolah &amp; Cabang</span>
                        <span class="font-body-md text-body-md text-on-surface font-medium">{{ $schoolAddress }}</span>
                    </div>
                </div>
                
                <!-- Subjects Taught Section -->
                <div class="w-full mt-space-md pt-space-md border-t border-surface-container-high/60 flex flex-col text-left gap-2">
                    <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Mata Pelajaran Diampu</span>
                    <div class="flex flex-col gap-1.5">
                        @forelse($user->subjects as $subject)
                            <div class="flex items-center gap-2 p-2 rounded-lg bg-surface-container-low text-on-surface border border-surface-container-high/40">
                                <span class="material-symbols-outlined text-[18px] text-primary">calculate</span>
                                <span class="font-label-md text-label-md flex-1 font-semibold">{{ $subject->name }} @if($subject->code) ({{ $subject->code }}) @endif</span>
                            </div>
                        @empty
                            <div class="p-2.5 rounded-lg bg-surface-container-low text-on-surface-variant text-body-sm text-center italic">
                                Belum ada mata pelajaran terhubung
                            </div>
                        @endforelse
                    </div>
                </div>
                
                <!-- Secondary Action: Unggah Pasfoto Baru -->
                <div class="w-full mt-space-md">
                    <button onclick="document.getElementById('avatarInput').click()" class="w-full py-2.5 px-space-md rounded-lg bg-surface-container-low text-primary font-label-md text-label-md hover:bg-surface-container-high transition-colors flex items-center justify-center gap-2 font-semibold shadow-sm" type="button">
                        <span class="material-symbols-outlined text-[18px]">upload_file</span>
                        <span>Unggah Pasfoto Baru</span>
                    </button>
                </div>
            </div>
            
            <!-- Quick Academic Stats Mini Card -->
            <div class="bg-surface-container-low rounded-xl p-space-md flex flex-col gap-space-sm shadow-sm border border-surface-container-high/50">
                <div class="flex items-center justify-between">
                    <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold">Beban Mengajar Aktif</span>
                    <span class="px-2 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-label-sm font-semibold">Tercapai</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="font-headline-lg text-headline-lg text-primary font-bold">{{ $teachingHours }}</span>
                    <span class="font-body-md text-body-md text-on-surface-variant">JP / Minggu (Target Sertifikasi)</span>
                </div>
                <div class="w-full bg-surface-container-highest rounded-full h-2 overflow-hidden">
                    <div class="bg-primary h-full rounded-full transition-all duration-500" style="width: 100%;"></div>
                </div>
                <div class="grid grid-cols-2 gap-2 mt-2 pt-2 border-t border-surface-container-high/60 text-xs">
                    <div>
                        <span class="text-on-surface-variant block">Total Soal Dibuat</span>
                        <span class="font-bold text-on-surface text-sm">{{ $totalQuestions }} Soal</span>
                    </div>
                    <div>
                        <span class="text-on-surface-variant block">Paket Ujian Guru</span>
                        <span class="font-bold text-on-surface text-sm">{{ $totalExams }} Ujian</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Settings & Account Controls (8 of 12 columns) -->
        <div class="lg:col-span-8 flex flex-col gap-space-lg">
            
            <!-- SECTION 1: Informasi Akademik & Mengajar -->
            <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm flex flex-col gap-space-md border border-surface-container-high/40">
                <div class="flex items-center justify-between pb-space-sm border-b border-surface-container-high/60">
                    <div class="flex items-center gap-space-sm">
                        <div class="w-10 h-10 rounded-lg bg-primary-fixed text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-[22px]">badge</span>
                        </div>
                        <div class="flex flex-col">
                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Informasi Akademik &amp; Mengajar</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Perbarui rincian gelar, identitas kepegawaian, dan alokasi data kontak.</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-outline-variant">edit_note</span>
                </div>
                
                <form id="profileForm" action="{{ route('teacher.profile.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-space-md pt-space-xs">
                    @csrf
                    @method('PUT')
                    
                    <!-- Hidden avatar file input linked to preview and submit -->
                    <input type="file" id="avatarInput" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(event)">

                    <!-- Nama Lengkap beserta Gelar -->
                    <div class="flex flex-col gap-1.5 md:col-span-2">
                        <label class="font-label-md text-label-md text-on-surface font-semibold" for="full_name">Nama Lengkap beserta Gelar</label>
                        <input class="h-12 px-space-md bg-surface-container-low rounded-lg font-body-md text-body-md text-on-surface focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary shadow-sm transition-all @error('name') ring-2 ring-error @enderror" id="full_name" name="name" type="text" value="{{ old('name', $user->name) }}" required/>
                        @error('name')
                            <span class="text-xs text-error font-medium">{{ $message }}</span>
                        @else
                            <span class="font-body-sm text-body-sm text-on-surface-variant">Gelar akan dicantumkan pada kartu soal, berita acara, dan lembar rapor ujian.</span>
                        @enderror
                    </div>

                    <!-- NIP -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold" for="nip">Nomor Induk Pegawai (NIP)</label>
                        <input class="h-12 px-space-md bg-surface-container-low rounded-lg font-body-md text-body-md text-on-surface focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary shadow-sm transition-all @error('nip') ring-2 ring-error @enderror" id="nip" name="nip" type="text" value="{{ old('nip', $user->nip) }}"/>
                        @error('nip')
                            <span class="text-xs text-error font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nuptk / Username (Read-only reference) -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold" for="nuptk">Username Login (NUPTK / Akun)</label>
                        <input class="h-12 px-space-md bg-surface-container-low/70 rounded-lg font-body-md text-body-md text-on-surface-variant focus:outline-none shadow-sm cursor-not-allowed border border-dashed border-surface-container-high" id="nuptk" readonly="" type="text" value="{{ $user->username }}"/>
                    </div>

                    <!-- Bidang Studi Utama -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold" for="subject">Bidang Studi Utama</label>
                        <div class="relative flex items-center">
                            <input class="w-full h-12 pl-space-md pr-10 bg-surface-container-low/70 rounded-lg font-body-md text-body-md text-on-surface-variant focus:outline-none shadow-sm cursor-not-allowed border border-dashed border-surface-container-high" id="subject" readonly="" type="text" value="{{ $user->subjects->pluck('name')->implode(', ') ?: 'Umum / Terjadwal' }}"/>
                            <span class="material-symbols-outlined absolute right-3 text-on-surface-variant text-[20px]">school</span>
                        </div>
                    </div>

                    <!-- Beban Jam Mengajar -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold" for="teaching_hours">Beban Jam Mengajar</label>
                        <div class="relative flex items-center">
                            <input class="w-full h-12 pl-space-md pr-10 bg-surface-container-low/70 rounded-lg font-body-md text-body-md text-on-surface-variant focus:outline-none shadow-sm cursor-not-allowed border border-dashed border-surface-container-high" id="teaching_hours" readonly="" type="text" value="{{ $teachingHours }} JP / Minggu"/>
                            <span class="material-symbols-outlined absolute right-3 text-on-surface-variant text-[20px]">schedule</span>
                        </div>
                    </div>

                    <!-- Nomor Telepon / WhatsApp -->
                    <div class="flex flex-col gap-1.5 md:col-span-2">
                        <label class="font-label-md text-label-md text-on-surface font-semibold" for="phone">Nomor Telepon / WhatsApp</label>
                        <div class="relative flex items-center">
                            <input class="w-full h-12 pl-space-md pr-10 bg-surface-container-low rounded-lg font-body-md text-body-md text-on-surface focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary shadow-sm transition-all @error('phone') ring-2 ring-error @enderror" id="phone" name="phone" placeholder="+62 812-xxxx-xxxx" type="text" value="{{ old('phone', $user->phone) }}"/>
                            <span class="material-symbols-outlined absolute right-3 text-on-surface-variant text-[20px]">call</span>
                        </div>
                        @error('phone')
                            <span class="text-xs text-error font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Selected Avatar Notice (shows filename if picked) -->
                    <div id="avatarPickedNotice" class="hidden md:col-span-2 p-3 rounded-lg bg-primary-fixed/30 text-on-surface flex items-center justify-between text-xs border border-primary/20">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px] text-primary">image</span>
                            <span>Foto profil baru dipilih: <strong id="avatarFileName"></strong>. Klik tombol <strong>Perbarui Data Profil</strong> untuk menyimpan foto.</span>
                        </div>
                        <button type="button" onclick="cancelAvatarPick()" class="text-error hover:underline font-semibold ml-2">Batal</button>
                    </div>

                    <!-- Action Button -->
                    <div class="md:col-span-2 flex justify-end pt-space-xs">
                        <button class="h-12 px-space-xl bg-primary hover:bg-primary-container text-on-primary font-label-lg text-label-lg rounded-lg shadow-sm flex items-center gap-2 transition-all active:scale-[0.98] font-semibold cursor-pointer" type="submit">
                            <span class="material-symbols-outlined text-[20px]">save</span>
                            <span>Perbarui Data Profil</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- SECTION 2: Keamanan & Kata Sandi -->
            <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm flex flex-col gap-space-md border border-surface-container-high/40">
                <div class="flex items-center justify-between pb-space-sm border-b border-surface-container-high/60">
                    <div class="flex items-center gap-space-sm">
                        <div class="w-10 h-10 rounded-lg bg-surface-container-high text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-[22px]">lock_reset</span>
                        </div>
                        <div class="flex flex-col">
                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Keamanan &amp; Kata Sandi</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Pastikan akun pendidik dilindungi dengan sandi berkekuatan tinggi.</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-outline-variant">shield</span>
                </div>

                <form action="{{ route('teacher.profile.password') }}" method="POST" class="flex flex-col gap-space-md pt-space-xs">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-label-md text-label-md text-on-surface font-semibold" for="current_pw">Kata Sandi Saat Ini</label>
                        <div class="relative flex items-center">
                            <input class="w-full h-12 pl-space-md pr-12 bg-surface-container-low rounded-lg font-body-md text-body-md text-on-surface focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary shadow-sm transition-all @error('current_password') ring-2 ring-error @enderror" id="current_pw" name="current_password" type="password" placeholder="Masukkan kata sandi saat ini" required/>
                            <button class="absolute right-3 p-1 text-on-surface-variant hover:text-on-surface cursor-pointer" type="button" onclick="togglePasswordVisibility('current_pw', this)" title="Lihat kata sandi">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </div>
                        @error('current_password')
                            <span class="text-xs text-error font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
                        <!-- New Password -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface font-semibold" for="new_pw">Kata Sandi Baru</label>
                            <div class="relative flex items-center">
                                <input class="w-full h-12 pl-space-md pr-12 bg-surface-container-low rounded-lg font-body-md text-body-md text-on-surface focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary shadow-sm transition-all @error('password') ring-2 ring-error @enderror" id="new_pw" name="password" placeholder="Minimal 6 karakter kombinasi" type="password" required oninput="evaluatePasswordStrength(this.value)"/>
                                <button class="absolute right-3 p-1 text-on-surface-variant hover:text-on-surface cursor-pointer" type="button" onclick="togglePasswordVisibility('new_pw', this)" title="Lihat kata sandi">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                            </div>
                            @error('password')
                                <span class="text-xs text-error font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm New Password -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface font-semibold" for="confirm_pw">Konfirmasi Kata Sandi Baru</label>
                            <div class="relative flex items-center">
                                <input class="w-full h-12 pl-space-md pr-12 bg-surface-container-low rounded-lg font-body-md text-body-md text-on-surface focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary shadow-sm transition-all" id="confirm_pw" name="password_confirmation" placeholder="Ulangi kata sandi baru" type="password" required/>
                                <button class="absolute right-3 p-1 text-on-surface-variant hover:text-on-surface cursor-pointer" type="button" onclick="togglePasswordVisibility('confirm_pw', this)" title="Lihat kata sandi">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Password Strength Indicator -->
                    <div class="bg-surface-container-low p-space-sm rounded-lg flex flex-col gap-2 border border-surface-container-high/40">
                        <div class="flex items-center justify-between">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Tingkat Kekuatan Sandi:</span>
                            <span id="strengthText" class="font-label-sm text-label-sm text-on-surface-variant font-semibold flex items-center gap-1">
                                <span id="strengthIcon" class="material-symbols-outlined text-[14px]">shield</span>
                                <span id="strengthLabel">Belum Diisi</span>
                            </span>
                        </div>
                        <!-- Progress Bar -->
                        <div class="grid grid-cols-4 gap-1.5 h-1.5" id="strengthBars">
                            <div class="bg-outline-variant/40 rounded-full h-full transition-colors" id="bar1"></div>
                            <div class="bg-outline-variant/40 rounded-full h-full transition-colors" id="bar2"></div>
                            <div class="bg-outline-variant/40 rounded-full h-full transition-colors" id="bar3"></div>
                            <div class="bg-outline-variant/40 rounded-full h-full transition-colors" id="bar4"></div>
                        </div>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">Disarankan menggunakan campuran huruf besar, angka, dan karakter simbol (@, #, $).</span>
                    </div>

                    <!-- Action Button -->
                    <div class="flex justify-end pt-space-xs">
                        <button class="h-12 px-space-xl bg-surface-container-high hover:bg-surface-container-highest text-on-surface font-label-lg text-label-lg rounded-lg shadow-sm flex items-center gap-2 transition-all active:scale-[0.98] font-semibold cursor-pointer" type="submit">
                            <span class="material-symbols-outlined text-[20px]">key</span>
                            <span>Ubah Kata Sandi</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- SECTION 3: Preferensi Sistem Ujian -->
            <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm flex flex-col gap-space-md border border-surface-container-high/40">
                <div class="flex items-center justify-between pb-space-sm border-b border-surface-container-high/60">
                    <div class="flex items-center gap-space-sm">
                        <div class="w-10 h-10 rounded-lg bg-surface-container-high text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-[22px]">tune</span>
                        </div>
                        <div class="flex flex-col">
                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Preferensi Sistem Ujian</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Sesuaikan alur otomasi notifikasi dan pengawasan CBT sesuai standar penilaian.</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-outline-variant">settings_suggest</span>
                </div>

                <form action="{{ route('teacher.profile.preferences') }}" method="POST" id="preferencesForm" class="flex flex-col gap-space-sm pt-space-xs">
                    @csrf
                    <!-- Toggle Item 1 -->
                    <div class="flex items-center justify-between p-space-md rounded-xl bg-surface-container-low hover:bg-surface-container transition-colors border border-surface-container-high/40">
                        <div class="flex items-start gap-space-md pr-space-md">
                            <div class="p-2 rounded-lg bg-surface-container-lowest text-primary shadow-sm mt-0.5 flex-shrink-0">
                                <span class="material-symbols-outlined text-[20px]">mark_email_read</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-label-lg text-label-lg text-on-surface font-semibold">Notifikasi email otomatis saat siswa mengumpulkan ujian</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant">Terima ringkasan rekapitulasi pengumpulan lembar jawaban langsung ke kotak masuk.</span>
                            </div>
                        </div>
                        <!-- Toggle Switch [ON] -->
                        <button id="toggleEmailNotif" aria-checked="true" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out bg-primary focus:outline-none" role="switch" type="button" onclick="handleSwitchToggle(this, 'email_notif')">
                            <span class="translate-x-5 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                        <input type="hidden" name="email_notif" id="input_email_notif" value="1">
                    </div>

                    <!-- Toggle Item 2 -->
                    <div class="flex items-center justify-between p-space-md rounded-xl bg-surface-container-low hover:bg-surface-container transition-colors border border-surface-container-high/40">
                        <div class="flex items-start gap-space-md pr-space-md">
                            <div class="p-2 rounded-lg bg-surface-container-lowest text-tertiary shadow-sm mt-0.5 flex-shrink-0">
                                <span class="material-symbols-outlined text-[20px]">security</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-label-lg text-label-lg text-on-surface font-semibold">Peringatan otomatis siswa terindikasi pindah tab (CBT Guard)</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant">Kirim peringatan layar seketika dan catat log kecurangan saat siswa beralih jendela browser.</span>
                            </div>
                        </div>
                        <!-- Toggle Switch [ON] -->
                        <button id="toggleCbtGuard" aria-checked="true" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out bg-primary focus:outline-none" role="switch" type="button" onclick="handleSwitchToggle(this, 'cbt_guard')">
                            <span class="translate-x-5 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                        <input type="hidden" name="cbt_guard" id="input_cbt_guard" value="1">
                    </div>

                    <!-- Toggle Item 3 -->
                    <div class="flex items-center justify-between p-space-md rounded-xl bg-surface-container-low hover:bg-surface-container transition-colors border border-surface-container-high/40">
                        <div class="flex items-start gap-space-md pr-space-md">
                            <div class="p-2 rounded-lg bg-surface-container-lowest text-secondary shadow-sm mt-0.5 flex-shrink-0">
                                <span class="material-symbols-outlined text-[20px]">sync</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-label-lg text-label-lg text-on-surface font-semibold">Sinkronisasi nilai otomatis ke e-Rapor Kurikulum Merdeka</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant">Mengonversi perolehan skor sumatif langsung ke format ledger kurikulum resmi sekolah.</span>
                            </div>
                        </div>
                        <!-- Toggle Switch [ON] -->
                        <button id="toggleErapor" aria-checked="true" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out bg-primary focus:outline-none" role="switch" type="button" onclick="handleSwitchToggle(this, 'erapor_sync')">
                            <span class="translate-x-5 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                        <input type="hidden" name="erapor_sync" id="input_erapor_sync" value="1">
                    </div>

                    <!-- Action Button -->
                    <div class="flex justify-end pt-space-xs">
                        <button class="h-12 px-space-xl bg-primary hover:bg-primary-container text-on-primary font-label-lg text-label-lg rounded-lg shadow-sm flex items-center gap-2 transition-all active:scale-[0.98] font-semibold cursor-pointer" type="submit">
                            <span class="material-symbols-outlined text-[20px]">done_all</span>
                            <span>Simpan Preferensi</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- BOTTOM LOGOUT SECTION -->
            <div class="w-full flex flex-col sm:flex-row sm:items-center justify-between gap-space-sm p-space-md rounded-xl bg-error-container/20 border border-error/20">
                <div class="flex items-center gap-space-sm text-on-surface-variant">
                    <span class="material-symbols-outlined text-[20px] text-error flex-shrink-0">info</span>
                    <span class="font-body-sm text-body-sm">
                        Sesi terhubung dari IP <strong>{{ request()->ip() }}</strong>. Terakhir masuk: <strong>{{ $lastLogin ? $lastLogin->created_at->format('d/m/Y H:i') . ' WIB' : 'Hari ini' }}</strong>.
                    </span>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="m-0 flex-shrink-0">
                    @csrf
                    <button onclick="return confirm('Apakah Anda yakin ingin keluar dari sesi guru?')" class="w-full sm:w-auto h-11 px-space-lg rounded-lg bg-surface-container-lowest hover:bg-error-container/50 text-error font-label-md text-label-md transition-colors flex items-center justify-center gap-2 shadow-sm font-semibold cursor-pointer" type="submit">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
                        <span>Keluar dari Sesi Guru</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const originalAvatarSrc = "{{ $user->avatar_url }}";

    // Avatar preview handler
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarDisplay').src = e.target.result;
                document.getElementById('avatarFileName').textContent = file.name;
                document.getElementById('avatarPickedNotice').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function cancelAvatarPick() {
        const avatarInput = document.getElementById('avatarInput');
        avatarInput.value = '';
        document.getElementById('avatarDisplay').src = originalAvatarSrc;
        document.getElementById('avatarPickedNotice').classList.add('hidden');
    }

    // Password visibility toggle
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('.material-symbols-outlined');
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    }

    // Dynamic password strength evaluator
    function evaluatePasswordStrength(password) {
        const bar1 = document.getElementById('bar1');
        const bar2 = document.getElementById('bar2');
        const bar3 = document.getElementById('bar3');
        const bar4 = document.getElementById('bar4');
        const strengthLabel = document.getElementById('strengthLabel');
        const strengthIcon = document.getElementById('strengthIcon');
        const strengthText = document.getElementById('strengthText');

        // Reset
        [bar1, bar2, bar3, bar4].forEach(b => {
            b.className = 'bg-outline-variant/40 rounded-full h-full transition-colors';
        });

        if (!password || password.length === 0) {
            strengthLabel.textContent = 'Belum Diisi';
            strengthLabel.className = '';
            strengthIcon.textContent = 'shield';
            strengthText.className = 'font-label-sm text-label-sm text-on-surface-variant font-semibold flex items-center gap-1';
            return;
        }

        let score = 0;
        if (password.length >= 6) score++;
        if (password.length >= 8) score++;
        if (/[A-Z]/.test(password) && /[a-z]/.test(password)) score++;
        if (/[0-9]/.test(password) || /[^A-Za-z0-9]/.test(password)) score++;

        if (score === 1) {
            bar1.className = 'bg-error rounded-full h-full transition-colors';
            strengthLabel.textContent = 'Lemah';
            strengthIcon.textContent = 'error';
            strengthText.className = 'font-label-sm text-label-sm text-error font-semibold flex items-center gap-1';
        } else if (score === 2) {
            bar1.className = 'bg-amber-500 rounded-full h-full transition-colors';
            bar2.className = 'bg-amber-500 rounded-full h-full transition-colors';
            strengthLabel.textContent = 'Cukup';
            strengthIcon.textContent = 'warning';
            strengthText.className = 'font-label-sm text-label-sm text-amber-600 font-semibold flex items-center gap-1';
        } else if (score === 3) {
            bar1.className = 'bg-secondary rounded-full h-full transition-colors';
            bar2.className = 'bg-secondary rounded-full h-full transition-colors';
            bar3.className = 'bg-secondary rounded-full h-full transition-colors';
            strengthLabel.textContent = 'Kuat';
            strengthIcon.textContent = 'check_circle';
            strengthText.className = 'font-label-sm text-label-sm text-secondary font-semibold flex items-center gap-1';
        } else if (score >= 4) {
            [bar1, bar2, bar3, bar4].forEach(b => {
                b.className = 'bg-emerald-600 rounded-full h-full transition-colors';
            });
            strengthLabel.textContent = 'Sangat Kuat';
            strengthIcon.textContent = 'verified_user';
            strengthText.className = 'font-label-sm text-label-sm text-emerald-700 font-semibold flex items-center gap-1';
        }
    }

    // Toggle switch interaction & local state persistence
    function handleSwitchToggle(switchBtn, storageKey) {
        const isChecked = switchBtn.getAttribute('aria-checked') === 'true';
        const knob = switchBtn.querySelector('span');
        const hiddenInput = document.getElementById('input_' + storageKey);
        
        if (isChecked) {
            switchBtn.setAttribute('aria-checked', 'false');
            switchBtn.classList.remove('bg-primary');
            switchBtn.classList.add('bg-outline-variant');
            knob.classList.remove('translate-x-5');
            knob.classList.add('translate-x-0');
            if (hiddenInput) hiddenInput.value = '0';
            localStorage.setItem('cbt_pref_' + storageKey, '0');
        } else {
            switchBtn.setAttribute('aria-checked', 'true');
            switchBtn.classList.add('bg-primary');
            switchBtn.classList.remove('bg-outline-variant');
            knob.classList.add('translate-x-5');
            knob.classList.remove('translate-x-0');
            if (hiddenInput) hiddenInput.value = '1';
            localStorage.setItem('cbt_pref_' + storageKey, '1');
        }
    }

    // Restore saved switch state from localStorage on page load
    document.addEventListener('DOMContentLoaded', () => {
        ['email_notif', 'cbt_guard', 'erapor_sync'].forEach(key => {
            const saved = localStorage.getItem('cbt_pref_' + key);
            if (saved !== null) {
                const btn = document.querySelector(`button[onclick*="'${key}'"]`);
                const isChecked = saved === '1';
                if (btn) {
                    btn.setAttribute('aria-checked', isChecked ? 'true' : 'false');
                    const knob = btn.querySelector('span');
                    if (isChecked) {
                        btn.classList.add('bg-primary');
                        btn.classList.remove('bg-outline-variant');
                        knob.classList.add('translate-x-5');
                        knob.classList.remove('translate-x-0');
                    } else {
                        btn.classList.remove('bg-primary');
                        btn.classList.add('bg-outline-variant');
                        knob.classList.remove('translate-x-5');
                        knob.classList.add('translate-x-0');
                    }
                    const hiddenInput = document.getElementById('input_' + key);
                    if (hiddenInput) hiddenInput.value = saved;
                }
            }
        });
    });
</script>
@endsection
