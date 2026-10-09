@extends('layouts.student')

@section('title', 'Profil Saya — EduExam')

@section('student-content')
<div class="flex flex-col w-full pb-8">

    <!-- Hero Profile Header Card -->
    <div class="bg-surface-container-lowest rounded-3xl shadow-sm border border-surface-container overflow-hidden relative mb-4">
        <!-- Aesthetic Mesh Gradient Cover -->
        <div class="h-28 bg-gradient-to-r from-primary via-indigo-600 to-primary-container relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-36 h-36 rounded-full bg-white/10 blur-xl pointer-events-none"></div>
            <div class="absolute left-10 -bottom-10 w-32 h-32 rounded-full bg-secondary-container/20 blur-lg pointer-events-none"></div>
            
            <div class="absolute top-3 right-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white/20 backdrop-blur-md text-white font-label-sm text-[11px] font-semibold border border-white/20 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Siswa Aktif</span>
                </span>
            </div>
        </div>

        <!-- Avatar & Student Identity -->
        <div class="px-4 pb-5 flex flex-col items-center text-center relative">
            <!-- Avatar with Camera Trigger -->
            <div class="relative -mt-14 mb-3 group">
                <div class="relative w-24 h-24 rounded-full ring-4 ring-surface-container-lowest shadow-lg overflow-hidden bg-surface-container flex items-center justify-center">
                    <img id="avatarImagePreview" 
                         src="{{ $user->avatar_url }}" 
                         alt="{{ $user->name }}" 
                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                </div>

                <!-- Camera Upload Button Badge -->
                <button type="button" 
                        onclick="document.getElementById('avatarFileInput').click()" 
                        class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center shadow-md hover:bg-primary-container transition-all active:scale-95 border-2 border-surface-container-lowest" 
                        title="Ubah Foto Profil">
                    <span class="material-symbols-outlined text-[16px]">photo_camera</span>
                </button>
            </div>

            <h2 class="font-headline-sm text-lg font-bold text-on-surface leading-tight">
                {{ $user->name }}
            </h2>

            <div class="flex flex-wrap items-center justify-center gap-1.5 mt-1.5">
                <span class="text-xs font-mono font-semibold bg-surface-container px-2.5 py-0.5 rounded-lg text-on-surface-variant">
                    NIS: {{ $user->nis ?? '-' }}
                </span>
                <span class="inline-flex items-center gap-1 bg-primary-fixed text-on-primary-fixed text-xs font-semibold px-2.5 py-0.5 rounded-full">
                    <span class="material-symbols-outlined text-[13px]">school</span>
                    <span>{{ $classroom ? $classroom->name : 'Kelas Umum' }}</span>
                </span>
            </div>

            <!-- Quick Upload Form (Hidden) -->
            <form id="avatarUploadForm" action="{{ route('student.profile.avatar') }}" method="POST" enctype="multipart/form-data" class="hidden">
                @csrf
                <input type="file" id="avatarFileInput" name="avatar" accept="image/png,image/jpeg,image/webp" onchange="handleAvatarSelected(this)">
            </form>
        </div>
    </div>

    <!-- Mini Academic Bento Stats (4 Metric Cards) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 mb-4">
        <!-- Stat 1: Total Ujian -->
        <div class="bg-surface-container-lowest rounded-2xl p-3 shadow-xs border border-surface-container flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] text-on-surface-variant font-medium">Total Ujian</span>
                <div class="w-6 h-6 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[15px]">assignment</span>
                </div>
            </div>
            <div class="flex items-baseline gap-1 mt-2">
                <span class="font-headline-sm text-lg font-bold text-on-surface">{{ $totalExams }}</span>
                <span class="text-[11px] text-on-surface-variant">Sesi</span>
            </div>
        </div>

        <!-- Stat 2: Ujian Tuntas -->
        <div class="bg-surface-container-lowest rounded-2xl p-3 shadow-xs border border-surface-container flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] text-on-surface-variant font-medium">Ujian Tuntas</span>
                <div class="w-6 h-6 rounded-lg bg-secondary-container flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                </div>
            </div>
            <div class="flex items-baseline gap-1 mt-2">
                <span class="font-headline-sm text-lg font-bold text-secondary">{{ $passedExams }}</span>
                <span class="text-[11px] text-on-surface-variant">Lulus</span>
            </div>
        </div>

        <!-- Stat 3: Rata-rata Skor -->
        <div class="bg-surface-container-lowest rounded-2xl p-3 shadow-xs border border-surface-container flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] text-on-surface-variant font-medium">Rata-rata Nilai</span>
                <div class="w-6 h-6 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[15px]">trending_up</span>
                </div>
            </div>
            <div class="flex items-baseline gap-1 mt-2">
                <span class="font-headline-sm text-lg font-bold text-primary">{{ $avgScore }}</span>
                <span class="text-[11px] text-on-surface-variant">/ 100</span>
            </div>
        </div>

        <!-- Stat 4: Skor Tertinggi -->
        <div class="bg-surface-container-lowest rounded-2xl p-3 shadow-xs border border-surface-container flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] text-on-surface-variant font-medium">Nilai Terbaik</span>
                <div class="w-6 h-6 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[15px]">stars</span>
                </div>
            </div>
            <div class="flex items-baseline gap-1 mt-2">
                <span class="font-headline-sm text-lg font-bold text-amber-600">{{ $highestScore }}</span>
                <span class="text-[11px] text-on-surface-variant">Poin</span>
            </div>
        </div>
    </div>

    <!-- Navigation Tab Buttons -->
    <div class="flex p-1 bg-surface-container rounded-2xl mb-4 shadow-xs">
        <button type="button" onclick="switchProfileTab('bio')" id="tabBtnBio" class="flex-1 py-2 px-3 rounded-xl text-xs font-bold transition-all bg-surface-container-lowest text-primary shadow-xs flex items-center justify-center gap-1.5">
            <span class="material-symbols-outlined text-[17px]">person</span>
            <span>Biodata</span>
        </button>
        <button type="button" onclick="switchProfileTab('card')" id="tabBtnCard" class="flex-1 py-2 px-3 rounded-xl text-xs font-medium transition-all text-on-surface-variant hover:text-on-surface flex items-center justify-center gap-1.5">
            <span class="material-symbols-outlined text-[17px]">badge</span>
            <span>Kartu Pelajar</span>
        </button>
        <button type="button" onclick="switchProfileTab('security')" id="tabBtnSecurity" class="flex-1 py-2 px-3 rounded-xl text-xs font-medium transition-all text-on-surface-variant hover:text-on-surface flex items-center justify-center gap-1.5">
            <span class="material-symbols-outlined text-[17px]">lock</span>
            <span>Keamanan</span>
        </button>
    </div>

    <!-- TAB 1: BIODATA & KONTAK SISWA -->
    <div id="tabContentBio" class="flex flex-col gap-4">
        <!-- Data Akademik Resmi (Read-Only) -->
        <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-surface-container p-4">
            <div class="flex items-center justify-between mb-3 pb-2 border-b border-surface-container">
                <h3 class="font-headline-sm text-sm font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[18px]">verified_user</span>
                    <span>Identitas Resmi Sekolah</span>
                </h3>
                <span class="text-[10px] uppercase font-semibold tracking-wider text-on-surface-variant px-2 py-0.5 rounded bg-surface-container">
                    Tervalidasi
                </span>
            </div>

            <div class="flex flex-col divide-y divide-surface-container text-xs">
                <div class="py-2.5 flex justify-between items-center">
                    <span class="text-on-surface-variant font-medium">Nomor Induk Siswa (NIS)</span>
                    <span class="font-mono font-bold text-on-surface">{{ $user->nis ?? '-' }}</span>
                </div>
                <div class="py-2.5 flex justify-between items-center">
                    <span class="text-on-surface-variant font-medium">NISN</span>
                    <span class="font-mono font-bold text-on-surface">{{ $user->nisn ?? '-' }}</span>
                </div>
                <div class="py-2.5 flex justify-between items-center">
                    <span class="text-on-surface-variant font-medium">Rombel / Kelas Aktif</span>
                    <span class="font-semibold text-on-surface">{{ $classroom ? $classroom->name . ' (' . ($classroom->major ?? 'Umum') . ')' : 'Belum Ditugaskan' }}</span>
                </div>
                <div class="py-2.5 flex justify-between items-center">
                    <span class="text-on-surface-variant font-medium">Jenis Kelamin</span>
                    <span class="font-semibold text-on-surface">{{ $user->gender === 'L' ? 'Laki-laki (L)' : ($user->gender === 'P' ? 'Perempuan (P)' : '-') }}</span>
                </div>
                <div class="py-2.5 flex justify-between items-center">
                    <span class="text-on-surface-variant font-medium">Tempat, Tanggal Lahir</span>
                    <span class="font-semibold text-on-surface">
                        {{ $user->birth_place ?? '-' }}{{ $user->birth_date ? ', ' . $user->birth_date->format('d/m/Y') : '' }}
                    </span>
                </div>
                <div class="py-2.5 flex justify-between items-center">
                    <span class="text-on-surface-variant font-medium">Email Terdaftar</span>
                    <span class="font-semibold text-on-surface">{{ $user->email }}</span>
                </div>
                <div class="py-2.5 flex justify-between items-center">
                    <span class="text-on-surface-variant font-medium">Username Login</span>
                    <span class="font-mono font-bold text-primary">{{ $user->username }}</span>
                </div>
            </div>
        </div>

        <!-- Edit Kontak Mandiri Siswa -->
        <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-surface-container p-4">
            <div class="flex items-center gap-2 mb-3 pb-2 border-b border-surface-container">
                <span class="material-symbols-outlined text-primary text-[18px]">contact_phone</span>
                <h3 class="font-headline-sm text-sm font-bold text-on-surface">Kontak &amp; Domisili</h3>
            </div>

            <form action="{{ route('student.profile.update') }}" method="POST" class="flex flex-col gap-3">
                @csrf
                @method('PUT')

                <div>
                    <label for="phoneInput" class="block text-xs font-semibold text-on-surface mb-1">
                        Nomor HP / WhatsApp Siswa
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined text-[18px] text-on-surface-variant absolute left-3 top-1/2 -translate-y-1/2">phone</span>
                        <input type="text" 
                               id="phoneInput" 
                               name="phone" 
                               value="{{ old('phone', $user->phone) }}" 
                               placeholder="Contoh: 08123456789" 
                               class="w-full pl-9 pr-3 py-2 bg-surface-container-low text-on-surface text-xs rounded-xl border border-surface-container focus:bg-surface-container-lowest focus:border-primary focus:outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label for="addressInput" class="block text-xs font-semibold text-on-surface mb-1">
                        Alamat Domisili
                    </label>
                    <div class="relative">
                        <textarea id="addressInput" 
                                  name="address" 
                                  rows="2" 
                                  placeholder="Tuliskan alamat tempat tinggal siswa..." 
                                  class="w-full p-3 bg-surface-container-low text-on-surface text-xs rounded-xl border border-surface-container focus:bg-surface-container-lowest focus:border-primary focus:outline-none transition-all resize-y">{{ old('address', $user->address) }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-1">
                    <button type="submit" class="px-4 py-2 rounded-xl bg-primary text-on-primary font-label-lg text-xs font-bold flex items-center gap-1.5 shadow-sm hover:bg-primary-container active:scale-95 transition-all">
                        <span class="material-symbols-outlined text-[16px]">save</span>
                        <span>Simpan Kontak</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 2: KARTU PELAJAR CBT DIGITAL -->
    <div id="tabContentCard" class="hidden flex-col gap-4">
        <!-- Digital Student Card Preview -->
        <div class="relative w-full rounded-3xl p-5 shadow-lg overflow-hidden border border-white/20 text-white bg-gradient-to-br from-indigo-900 via-primary to-indigo-950">
            <!-- Background Decorative Rings -->
            <div class="absolute -right-10 -bottom-10 w-44 h-44 rounded-full bg-white/10 blur-xl pointer-events-none"></div>
            <div class="absolute -left-10 -top-10 w-36 h-36 rounded-full bg-secondary/20 blur-xl pointer-events-none"></div>

            <!-- Card Header -->
            <div class="flex items-center justify-between pb-3 border-b border-white/20 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-md flex items-center justify-center font-bold text-sm">
                        E
                    </div>
                    <div>
                        <h4 class="font-headline-sm text-xs font-bold tracking-tight">KARTU PESERTA CBT DIGITAL</h4>
                        <p class="text-[10px] text-white/80 leading-none">SMA Nusantara EduExam</p>
                    </div>
                </div>
                <div class="px-2 py-0.5 rounded-full bg-emerald-500/30 border border-emerald-400/40 text-[10px] font-bold text-emerald-200">
                    AKTIF 2026/2027
                </div>
            </div>

            <!-- Card Body: Avatar & Info -->
            <div class="flex items-center gap-4 py-4 relative z-10">
                <div class="w-20 h-20 rounded-2xl overflow-hidden ring-2 ring-white/40 shadow-md shrink-0 bg-white/20">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-[11px] text-white/70 uppercase tracking-wider font-semibold">Nama Peserta Didik</span>
                    <h3 class="font-headline-sm text-sm sm:text-base font-extrabold truncate text-white leading-tight">
                        {{ $user->name }}
                    </h3>
                    <div class="mt-1 flex flex-col text-[11px] text-white/90 gap-0.5 font-medium">
                        <span>NIS : <strong class="font-mono text-white">{{ $user->nis ?? '-' }}</strong></span>
                        <span>NISN: <strong class="font-mono text-white">{{ $user->nisn ?? '-' }}</strong></span>
                        <span>Rombel: <strong class="text-white">{{ $classroom ? $classroom->name : 'Kelas Umum' }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Card Footer: Barcode Simulation & Info -->
            <div class="pt-3 border-t border-white/15 flex items-center justify-between text-[10px] text-white/75 relative z-10">
                <div>
                    <span class="block font-mono tracking-widest text-[9px] text-white/60">ID CBT: {{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}-{{ $user->nis ?? '0000' }}</span>
                    <span>Tervalidasi Sistem Asesmen Digital</span>
                </div>
                <div class="flex items-center gap-1 text-emerald-300 font-semibold">
                    <span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <span>Resmi</span>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-on-surface-variant leading-relaxed px-2">
            Gunakan kartu digital ini sebagai tanda pengenal resmi saat mengikuti sesi asesmen CBT dan ujian sekolah.
        </p>
    </div>

    <!-- TAB 3: KEAMANAN & GANTI KATA SANDI -->
    <div id="tabContentSecurity" class="hidden flex-col gap-4">
        <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-surface-container p-4">
            <div class="flex items-center gap-2 mb-3 pb-2 border-b border-surface-container">
                <span class="material-symbols-outlined text-primary text-[18px]">lock_reset</span>
                <h3 class="font-headline-sm text-sm font-bold text-on-surface">Ubah Kata Sandi Akun</h3>
            </div>

            <form action="{{ route('student.profile.password') }}" method="POST" class="flex flex-col gap-3">
                @csrf
                @method('PUT')

                <!-- Password Saat Ini -->
                <div>
                    <label for="currentPasswordInput" class="block text-xs font-semibold text-on-surface mb-1">
                        Kata Sandi Saat Ini
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="currentPasswordInput" 
                               name="current_password" 
                               required 
                               placeholder="Masukkan kata sandi lama Anda..." 
                               class="w-full pl-3 pr-10 py-2 bg-surface-container-low text-on-surface text-xs rounded-xl border border-surface-container focus:bg-surface-container-lowest focus:border-primary focus:outline-none transition-all">
                        <button type="button" onclick="togglePasswordVisibility('currentPasswordInput', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                        </button>
                    </div>
                </div>

                <!-- Password Baru -->
                <div>
                    <label for="newPasswordInput" class="block text-xs font-semibold text-on-surface mb-1">
                        Kata Sandi Baru (Minimal 6 Karakter)
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="newPasswordInput" 
                               name="password" 
                               required 
                               placeholder="Masukkan kata sandi baru..." 
                               class="w-full pl-3 pr-10 py-2 bg-surface-container-low text-on-surface text-xs rounded-xl border border-surface-container focus:bg-surface-container-lowest focus:border-primary focus:outline-none transition-all">
                        <button type="button" onclick="togglePasswordVisibility('newPasswordInput', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                        </button>
                    </div>
                </div>

                <!-- Konfirmasi Password Baru -->
                <div>
                    <label for="confirmPasswordInput" class="block text-xs font-semibold text-on-surface mb-1">
                        Ulangi Kata Sandi Baru
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="confirmPasswordInput" 
                               name="password_confirmation" 
                               required 
                               placeholder="Ketik ulang kata sandi baru..." 
                               class="w-full pl-3 pr-10 py-2 bg-surface-container-low text-on-surface text-xs rounded-xl border border-surface-container focus:bg-surface-container-lowest focus:border-primary focus:outline-none transition-all">
                        <button type="button" onclick="togglePasswordVisibility('confirmPasswordInput', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-primary text-on-primary font-label-lg text-xs font-bold flex items-center justify-center gap-1.5 shadow-sm hover:bg-primary-container active:scale-95 transition-all">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                        <span>Perbarui Kata Sandi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Logout Action Card -->
    <div class="mt-4 pt-2">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full h-11 bg-error-container/40 hover:bg-error-container/70 text-error border border-error/20 rounded-2xl font-label-lg text-xs font-bold flex items-center justify-center gap-1.5 active:scale-[0.98] transition-all shadow-xs" onclick="return confirm('Apakah Anda yakin ingin keluar dari akun ujian?')">
                <span class="material-symbols-outlined text-[18px]">logout</span>
                <span>Keluar dari Aplikasi</span>
            </button>
        </form>
    </div>
</div>

<!-- Modal Pratinjau Foto Profil Sebelum Unggah -->
<div id="avatarPreviewModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="w-full max-w-sm bg-surface-container-lowest rounded-3xl shadow-2xl border border-surface-container p-6 flex flex-col items-center text-center animate-[scaleIn_0.15s_ease-out]">
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
            <button type="button" onclick="cancelAvatarUpload()" class="flex-1 py-2.5 rounded-xl bg-surface-container text-on-surface font-semibold text-xs hover:bg-surface-container-high transition-colors">
                Pilih Ulang
            </button>
            <button type="button" onclick="confirmAvatarUpload()" class="flex-1 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs shadow-md hover:bg-primary-container transition-all">
                Simpan Foto
            </button>
        </div>
    </div>
</div>

<script>
    // Tab Switching Logic
    function switchProfileTab(tabName) {
        const tabs = ['bio', 'card', 'security'];
        tabs.forEach(t => {
            const content = document.getElementById('tabContent' + capitalize(t));
            const btn = document.getElementById('tabBtn' + capitalize(t));
            if (t === tabName) {
                if (content) {
                    content.classList.remove('hidden');
                    content.classList.add('flex');
                }
                if (btn) {
                    btn.className = "flex-1 py-2 px-3 rounded-xl text-xs font-bold transition-all bg-surface-container-lowest text-primary shadow-xs flex items-center justify-center gap-1.5";
                }
            } else {
                if (content) {
                    content.classList.add('hidden');
                    content.classList.remove('flex');
                }
                if (btn) {
                    btn.className = "flex-1 py-2 px-3 rounded-xl text-xs font-medium transition-all text-on-surface-variant hover:text-on-surface flex items-center justify-center gap-1.5";
                }
            }
        });
    }

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    // Avatar Upload Selection & Modal Preview
    function handleAvatarSelected(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Validasi ukuran maksimal 2MB
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
        if (form) {
            form.submit();
        }
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

    // Toggle Password Visibility (Mata)
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
</script>
@endsection
