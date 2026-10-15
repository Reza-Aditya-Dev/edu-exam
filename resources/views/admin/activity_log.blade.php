@extends('layouts.admin')

@section('title', 'Log Aktivitas & Audit Jejak Sistem — EduExam')
@section('page_title', 'Log Aktivitas Sistem')

@section('admin-content')
<div class="flex flex-col w-full -mt-2">
    <!-- Top Breadcrumb & Page Summary Banner -->
    <div class="relative bg-surface-container-low rounded-2xl p-space-lg mb-space-lg shadow-sm overflow-hidden border border-slate-200/60 dark:border-slate-800">
        <div class="absolute -right-16 -top-16 w-80 h-80 rounded-full bg-primary/5 blur-3xl pointer-events-none"></div>
        <div class="absolute right-40 -bottom-20 w-64 h-64 rounded-full bg-secondary/5 blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-space-lg">
            <div class="flex flex-col">
                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-on-surface-variant font-label-md text-label-md mb-2">
                    <span class="flex items-center gap-1.5 hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-[16px]">security</span>
                        Sistem &amp; Audit
                    </span>
                    <span class="text-outline">/</span>
                    <span class="text-primary font-label-md text-label-md font-semibold">Log Aktivitas</span>
                </div>

                <!-- Headline & Subtitle -->
                <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-bold">
                    Log Aktivitas &amp; Audit Jejak Sistem
                </h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1.5 max-w-3xl leading-relaxed">
                    Catatan komprehensif seluruh aktivitas pengguna (Admin, Guru, Siswa), pembuatan soal, modifikasi nilai, dan keamanan sesi digital secara real-time.
                </p>

                <!-- Live Server Status Indicator -->
                <div class="flex flex-wrap items-center gap-space-md mt-4">
                    <div class="flex items-center gap-2 bg-surface-container-lowest px-3 py-1.5 rounded-full shadow-sm text-on-surface border border-slate-100 dark:border-slate-800">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-secondary"></span>
                        </span>
                        <span class="font-label-sm text-label-sm text-on-surface font-semibold">Audit Engine: Aktif</span>
                        <span class="text-outline text-label-sm">•</span>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">Integritas Hash SHA-256 Valid</span>
                    </div>
                    <div class="flex items-center gap-2 bg-surface-container-lowest px-3 py-1.5 rounded-full shadow-sm border border-slate-100 dark:border-slate-800">
                        <span class="material-symbols-outlined text-primary text-[18px]">verified_user</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant">Protokol Keamanan CBT: ISO 27001 Terpantau</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2.5 self-start xl:self-center">
                <!-- Export CSV -->
                <a href="{{ route('admin.logs', array_merge(request()->query(), ['export' => 'csv'])) }}" 
                   class="group flex items-center gap-2 px-4 py-2.5 bg-surface-container-lowest text-on-surface font-label-lg text-label-lg rounded-xl shadow-sm hover:bg-surface-container hover:shadow transition-all border border-slate-200/80 dark:border-slate-800" 
                   title="Unduh seluruh data log terfilter sebagai format spreadsheet CSV">
                    <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary text-[20px] transition-colors">download</span>
                    <span>Ekspor Audit Log (.csv)</span>
                </a>

                <!-- Purge Old Logs Button -->
                <button type="button" 
                        onclick="document.getElementById('modalClearLogs').classList.remove('hidden')"
                        class="group flex items-center gap-2 px-4 py-2.5 bg-surface-container-lowest text-error font-label-lg text-label-lg rounded-xl shadow-sm hover:bg-error-container hover:text-on-error-container transition-all border border-rose-100 dark:border-rose-900/40">
                    <span class="material-symbols-outlined text-error group-hover:text-on-error-container text-[20px] transition-colors">auto_delete</span>
                    <span>Bersihkan Log Lama (&gt;90 hari)</span>
                </button>

                <!-- Refresh Logs Button -->
                <button type="button" 
                        id="btn-refresh" 
                        class="flex items-center gap-2 px-5 py-2.5 bg-primary-container text-on-primary font-label-lg text-label-lg rounded-xl shadow-md hover:bg-primary transition-all active:scale-[0.98]">
                    <span class="material-symbols-outlined text-[20px]" id="refresh-icon">refresh</span>
                    <span>Perbarui Log</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Key Metrics Telemetry Strip -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-md mb-space-lg">
        <!-- Total Events Today -->
        <div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex items-center justify-between border border-slate-200/60 dark:border-slate-800">
            <div class="flex flex-col min-w-0">
                <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider font-semibold">Aktivitas Hari Ini</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="font-headline-md text-headline-md text-on-surface font-bold">{{ number_format($todayCount ?? 0) }}</span>
                    <span class="font-label-sm text-label-sm text-secondary flex items-center font-medium">
                        <span class="material-symbols-outlined text-[14px]">trending_up</span> Real-time
                    </span>
                </div>
                <span class="font-body-sm text-body-sm text-on-surface-variant mt-0.5 truncate">Total request dicatat</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-primary-fixed flex items-center justify-center text-primary shrink-0">
                <span class="material-symbols-outlined text-[24px]">dataset</span>
            </div>
        </div>

        <!-- Active CBT Sessions -->
        <div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex items-center justify-between border border-slate-200/60 dark:border-slate-800">
            <div class="flex flex-col min-w-0">
                <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider font-semibold">Sesi Ujian Aktif</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="font-headline-md text-headline-md text-on-surface font-bold">{{ number_format($activeExamsCount ?? 0) }}</span>
                    <span class="font-label-sm text-label-sm text-primary flex items-center font-medium">
                        <span class="material-symbols-outlined text-[14px]">desktop_windows</span> Terjadwal
                    </span>
                </div>
                <span class="font-body-sm text-body-sm text-on-surface-variant mt-0.5 truncate">Ujian Diterbitkan (Live)</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-secondary-fixed flex items-center justify-center text-secondary shrink-0">
                <span class="material-symbols-outlined text-[24px]">assignment_turned_in</span>
            </div>
        </div>

        <!-- Security Incidents -->
        <div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex items-center justify-between border border-slate-200/60 dark:border-slate-800">
            <div class="flex flex-col min-w-0">
                <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider font-semibold">Audit Perhatian &amp; Hapus</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="font-headline-md text-headline-md text-tertiary font-bold">{{ number_format($warningLogsCount ?? 0) }}</span>
                    <span class="font-label-sm text-label-sm text-tertiary-container px-2 py-0.5 rounded-full font-semibold">Dipantau</span>
                </div>
                <span class="font-body-sm text-body-sm text-on-surface-variant mt-0.5 truncate">Aktivitas destruktif / reset</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-tertiary-fixed flex items-center justify-center text-tertiary shrink-0">
                <span class="material-symbols-outlined text-[24px]">warning_amber</span>
            </div>
        </div>

        <!-- Total History Audit -->
        <div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex items-center justify-between border border-slate-200/60 dark:border-slate-800">
            <div class="flex flex-col min-w-0">
                <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider font-semibold">Total Log Keseluruhan</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="font-headline-md text-headline-md text-on-surface font-bold">{{ number_format($totalLogsCount ?? 0) }}</span>
                    <span class="font-label-sm text-label-sm text-secondary font-medium">99.98% OK</span>
                </div>
                <span class="font-body-sm text-body-sm text-on-surface-variant mt-0.5 truncate">Database Audit Trail</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center text-on-surface-variant shrink-0">
                <span class="material-symbols-outlined text-[24px]">cloud_sync</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar (Forms GET real + Client live filter) -->
    <div class="bg-surface-container-lowest p-space-md rounded-2xl shadow-sm mb-space-md flex flex-col gap-space-md border border-slate-200/60 dark:border-slate-800">
        <form id="filter-form" action="{{ route('admin.logs') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-space-md items-center m-0">
            <!-- Search Input -->
            <div class="md:col-span-5 lg:col-span-4 relative">
                <div class="w-full flex items-center bg-surface-container-low rounded-xl px-3.5 py-2.5 focus-within:bg-surface-container-lowest focus-within:ring-2 focus-within:ring-primary-container transition-all">
                    <span class="material-symbols-outlined text-outline text-[22px] mr-2.5 shrink-0">search</span>
                    <input class="w-full bg-transparent border-none outline-none font-body-md text-body-md text-on-surface placeholder:text-outline" 
                           id="filter-search" 
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Cari aktivitas, nama pengguna, IP address..." 
                           type="text"/>
                    <button class="{{ request('q') ? '' : 'hidden' }} text-outline hover:text-on-surface ml-1" 
                            id="clear-search" 
                            type="button">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            </div>

            <!-- Filter: Peran Pengguna -->
            <div class="md:col-span-3 lg:col-span-2 relative">
                <div class="relative">
                    <select class="w-full appearance-none bg-surface-container-low text-on-surface font-label-md text-label-md px-3.5 py-2.5 pr-8 rounded-xl outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container transition-all cursor-pointer" 
                            id="filter-role" 
                            name="role"
                            onchange="document.getElementById('filter-form').submit()">
                        <option value="all" {{ request('role', 'all') === 'all' ? 'selected' : '' }}>Semua Peran</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="teacher" {{ request('role') === 'teacher' ? 'selected' : '' }}>Guru</option>
                        <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Siswa</option>
                        <option value="system" {{ request('role') === 'system' ? 'selected' : '' }}>Sistem Otomatis</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-outline text-[20px]">expand_more</span>
                </div>
            </div>

            <!-- Filter: Kategori Aktivitas -->
            <div class="md:col-span-4 lg:col-span-3 relative">
                <div class="relative">
                    <select class="w-full appearance-none bg-surface-container-low text-on-surface font-label-md text-label-md px-3.5 py-2.5 pr-8 rounded-xl outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container transition-all cursor-pointer" 
                            id="filter-category" 
                            name="category"
                            onchange="document.getElementById('filter-form').submit()">
                        <option value="all" {{ request('category', 'all') === 'all' ? 'selected' : '' }}>Semua Kategori</option>
                        <option value="ujian" {{ request('category') === 'ujian' ? 'selected' : '' }}>Ujian &amp; CBT</option>
                        <option value="siswa" {{ request('category') === 'siswa' ? 'selected' : '' }}>Manajemen Siswa</option>
                        <option value="guru" {{ request('category') === 'guru' ? 'selected' : '' }}>Manajemen Guru</option>
                        <option value="soal" {{ request('category') === 'soal' ? 'selected' : '' }}>Bank Soal</option>
                        <option value="keamanan" {{ request('category') === 'keamanan' ? 'selected' : '' }}>Autentikasi &amp; Keamanan</option>
                        <option value="sinkronisasi" {{ request('category') === 'sinkronisasi' ? 'selected' : '' }}>Sinkronisasi &amp; Impor</option>
                        <option value="konfigurasi" {{ request('category') === 'konfigurasi' ? 'selected' : '' }}>Konfigurasi &amp; Data Master</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-outline text-[20px]">tune</span>
                </div>
            </div>

            <!-- Filter: Tanggal -->
            <div class="md:col-span-12 lg:col-span-3 relative flex items-center gap-2">
                <div class="flex items-center bg-surface-container-low px-3 py-1.5 rounded-xl text-on-surface justify-between flex-1 focus-within:ring-2 focus-within:ring-primary-container">
                    <div class="flex items-center gap-1.5 min-w-0 flex-1">
                        <span class="material-symbols-outlined text-primary text-[18px] shrink-0">calendar_today</span>
                        <input type="date" 
                               name="date" 
                               id="filter-date"
                               value="{{ request('date') }}"
                               onchange="document.getElementById('filter-form').submit()"
                               class="bg-transparent border-none outline-none font-label-sm text-label-sm text-on-surface w-full cursor-pointer">
                    </div>
                </div>
                <button type="submit" class="px-3.5 py-2.5 bg-primary-container text-on-primary rounded-xl font-label-sm text-label-sm font-semibold hover:bg-primary transition shadow-xs">
                    Cari
                </button>
            </div>
        </form>

        <!-- Active Filter Tags / Chips -->
        <div class="flex flex-wrap items-center gap-2 pt-1 border-t border-slate-100 dark:border-slate-800">
            <span class="font-label-sm text-label-sm text-outline">Filter Aktif:</span>
            
            @if(request('date'))
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-surface-container rounded-full text-on-surface-variant font-label-sm text-label-sm">
                    <span>Tanggal: {{ \Carbon\Carbon::parse(request('date'))->translatedFormat('d M Y') }}</span>
                    <a href="{{ route('admin.logs', request()->except('date')) }}" class="hover:text-error ml-1">×</a>
                </span>
            @else
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-surface-container rounded-full text-on-surface-variant font-label-sm text-label-sm">
                    <span>Rentang: Semua Periode</span>
                </span>
            @endif

            @if(request('role') && request('role') !== 'all')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-surface-container rounded-full text-on-surface-variant font-label-sm text-label-sm">
                    <span>Peran: {{ ucfirst(request('role')) }}</span>
                    <a href="{{ route('admin.logs', request()->except('role')) }}" class="hover:text-error ml-1">×</a>
                </span>
            @endif

            @if(request('category') && request('category') !== 'all')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-surface-container rounded-full text-on-surface-variant font-label-sm text-label-sm">
                    <span>Kategori: {{ ucfirst(request('category')) }}</span>
                    <a href="{{ route('admin.logs', request()->except('category')) }}" class="hover:text-error ml-1">×</a>
                </span>
            @endif

            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">
                <span class="material-symbols-outlined text-[14px]">format_image_left</span>
                <span>Anti-Tamper Signature OK</span>
            </span>

            @if(request()->hasAny(['q', 'role', 'category', 'date', 'action', 'user_id']))
                <a href="{{ route('admin.logs') }}" class="font-label-sm text-label-sm text-primary hover:underline ml-auto font-semibold">
                    Reset Filter
                </a>
            @else
                <button type="button" id="reset-filters" class="font-label-sm text-label-sm text-primary hover:underline ml-auto font-semibold">
                    Reset Filter
                </button>
            @endif
        </div>
    </div>

    <!-- Activity Timeline Table Card -->
    <div class="bg-surface-container-lowest rounded-2xl shadow-sm overflow-hidden mb-space-lg border border-slate-200/60 dark:border-slate-800">
        <!-- Table Header Toolbar -->
        <div class="px-space-lg py-space-md bg-surface-container-lowest flex items-center justify-between border-b border-surface-container-low dark:border-slate-800 flex-wrap gap-2">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-primary text-[22px]">history_edu</span>
                <span class="font-headline-sm text-headline-sm text-on-surface font-semibold">Jejak Audit Real-Time</span>
                <span class="px-2.5 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant font-label-sm text-label-sm font-semibold">
                    Live Feed
                </span>
            </div>
            <div class="flex items-center gap-3 text-outline font-label-sm text-label-sm">
                <span class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-secondary"></span> {{ number_format($logs->total()) }} Catatan
                </span>
                <span class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-tertiary"></span> Audit Aktif
                </span>
            </div>
        </div>

        <!-- Responsive Table Wrapper -->
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1100px]">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm tracking-wider uppercase border-b border-slate-200/60 dark:border-slate-800">
                        <th class="py-3.5 px-space-lg font-semibold">Waktu &amp; Tanggal</th>
                        <th class="py-3.5 px-space-md font-semibold">Pengguna &amp; Peran</th>
                        <th class="py-3.5 px-space-md font-semibold">Kategori</th>
                        <th class="py-3.5 px-space-md font-semibold">Detail Aktivitas / Tindakan</th>
                        <th class="py-3.5 px-space-md font-semibold">Target Entitas</th>
                        <th class="py-3.5 px-space-md font-semibold">IP Address &amp; Perangkat</th>
                        <th class="py-3.5 px-space-lg text-right font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-low font-body-md text-body-md text-on-surface dark:divide-slate-800" id="log-table-body">
                    @forelse($logs as $log)
                        @php
                            $actionLower = strtolower($log->action);
                            $isWarning = str_contains($actionLower, 'delete') || str_contains($actionLower, 'destroy') || str_contains($actionLower, 'warning');
                            $userRole = $log->user ? strtolower($log->user->role) : 'system';
                            $userName = $log->user ? $log->user->name : 'Sistem Otomatis';
                            
                            // Category detection
                            $categoryName = 'Sistem';
                            $categoryIcon = 'settings';
                            if (str_starts_with($actionLower, 'exam')) {
                                $categoryName = 'Ujian & CBT';
                                $categoryIcon = 'quiz';
                            } elseif (str_starts_with($actionLower, 'student')) {
                                $categoryName = 'Manajemen Siswa';
                                $categoryIcon = 'group';
                            } elseif (str_starts_with($actionLower, 'teacher')) {
                                $categoryName = 'Manajemen Guru';
                                $categoryIcon = 'school';
                            } elseif (str_starts_with($actionLower, 'question')) {
                                $categoryName = 'Bank Soal';
                                $categoryIcon = 'menu_book';
                            } elseif (in_array($actionLower, ['login', 'logout']) || str_contains($actionLower, 'password')) {
                                $categoryName = 'Autentikasi';
                                $categoryIcon = 'vpn_key';
                            } elseif (str_contains($actionLower, 'sync') || str_contains($actionLower, 'import')) {
                                $categoryName = 'Sinkronisasi';
                                $categoryIcon = 'sync_alt';
                            } elseif (str_starts_with($actionLower, 'classroom') || str_starts_with($actionLower, 'subject') || str_starts_with($actionLower, 'academic_year') || str_contains($actionLower, 'setting')) {
                                $categoryName = 'Konfigurasi';
                                $categoryIcon = 'settings_suggest';
                            }

                            // User Avatar Initials
                            $initials = 'SY';
                            if ($log->user) {
                                $words = explode(' ', trim($userName));
                                $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                            }
                        @endphp
                        
                        <tr class="{{ $isWarning ? 'bg-tertiary-fixed/15 hover:bg-tertiary-fixed/25' : 'hover:bg-surface-container-low/60' }} transition-colors group">
                            <!-- Waktu & Tanggal -->
                            <td class="py-4 px-space-lg whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="font-label-lg text-label-lg text-on-surface tabular-nums font-semibold {{ $isWarning ? 'text-tertiary' : '' }}">
                                        {{ $log->created_at ? $log->created_at->format('H:i:s') : '-' }} 
                                        <span class="text-body-sm font-normal text-outline">WIB</span>
                                    </span>
                                    <span class="font-body-sm text-body-sm text-on-surface-variant">
                                        {{ $log->created_at ? $log->created_at->translatedFormat('d M Y') : '-' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Pengguna & Peran -->
                            <td class="py-4 px-space-md">
                                <div class="flex items-center gap-3">
                                    @if($userRole === 'admin')
                                        <div class="w-8 h-8 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-bold text-label-sm shadow-xs shrink-0">
                                            {{ $initials }}
                                        </div>
                                    @elseif($userRole === 'teacher')
                                        <div class="w-8 h-8 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold text-label-sm shrink-0">
                                            {{ $initials }}
                                        </div>
                                    @elseif($userRole === 'student')
                                        <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant font-bold text-label-sm shrink-0">
                                            {{ $initials }}
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-secondary-fixed flex items-center justify-center text-secondary font-bold text-label-sm shrink-0">
                                            <span class="material-symbols-outlined text-[18px]">smart_toy</span>
                                        </div>
                                    @endif

                                    <div class="flex flex-col min-w-0">
                                        <span class="font-label-lg text-label-lg text-on-surface font-semibold leading-tight truncate">
                                            {{ $userName }}
                                        </span>
                                        <div class="mt-1">
                                            @if($userRole === 'admin')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary-container text-on-primary font-label-sm text-label-sm font-medium">
                                                    <span class="material-symbols-outlined text-[13px]">shield</span> Admin
                                                </span>
                                            @elseif($userRole === 'teacher')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary-fixed text-primary font-label-sm text-label-sm font-medium">
                                                    <span class="material-symbols-outlined text-[13px]">school</span> Guru
                                                </span>
                                            @elseif($userRole === 'student')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-medium">
                                                    <span class="material-symbols-outlined text-[13px]">person</span> Siswa
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-secondary-fixed text-secondary font-label-sm text-label-sm font-medium">
                                                    <span class="material-symbols-outlined text-[13px]">robot</span> Sistem
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Kategori -->
                            <td class="py-4 px-space-md whitespace-nowrap">
                                <div class="flex items-center gap-1.5 text-on-surface">
                                    <span class="material-symbols-outlined {{ $isWarning ? 'text-tertiary' : 'text-primary' }} text-[18px]">{{ $categoryIcon }}</span>
                                    <span class="font-label-md text-label-md font-semibold">{{ $categoryName }}</span>
                                </div>
                            </td>

                            <!-- Detail Aktivitas / Tindakan -->
                            <td class="py-4 px-space-md">
                                <div class="flex flex-col">
                                    <span class="font-body-md text-body-md text-on-surface leading-snug font-medium {{ $isWarning ? 'text-tertiary font-semibold' : '' }}">
                                        {{ $log->description ?: ucwords(str_replace('_', ' ', $log->action)) }}
                                    </span>
                                    <span class="font-label-sm text-label-sm font-mono text-slate-400 mt-0.5">
                                        action: {{ $log->action }}
                                    </span>
                                </div>
                            </td>

                            <!-- Target Entitas -->
                            <td class="py-4 px-space-md">
                                <div class="flex flex-col">
                                    @if($log->loggable_type)
                                        <span class="font-label-md text-label-md text-primary font-semibold truncate max-w-[180px]">
                                            {{ class_basename($log->loggable_type) }} #{{ $log->loggable_id }}
                                        </span>
                                        <span class="font-body-sm text-body-sm text-outline">
                                            ID: {{ $log->loggable_id }}
                                        </span>
                                    @else
                                        <span class="font-label-md text-label-md font-mono text-on-surface bg-surface-container px-2 py-0.5 rounded w-fit text-xs">
                                            #LOG-{{ str_pad($log->id, 5, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <span class="font-body-sm text-body-sm text-outline">Sistem Global</span>
                                    @endif
                                </div>
                            </td>

                            <!-- IP Address & Perangkat -->
                            <td class="py-4 px-space-md whitespace-nowrap">
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-1 font-label-sm text-label-sm font-mono {{ $isWarning ? 'text-tertiary font-bold' : 'text-on-surface' }}">
                                        <span class="material-symbols-outlined text-outline text-[14px]">lan</span>
                                        {{ $log->ip_address ?: '127.0.0.1' }}
                                    </div>
                                    <span class="font-body-sm text-body-sm text-on-surface-variant truncate max-w-[200px]" title="{{ $log->user_agent }}">
                                        @if($log->user_agent)
                                            @if(str_contains($log->user_agent, 'Windows')) Windows Client
                                            @elseif(str_contains($log->user_agent, 'Macintosh')) macOS Client
                                            @elseif(str_contains($log->user_agent, 'Android')) Android Device
                                            @elseif(str_contains($log->user_agent, 'iPhone')) iOS Device
                                            @else Web Browser
                                            @endif
                                        @else
                                            Internal Terminal
                                        @endif
                                    </span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-space-lg text-right whitespace-nowrap">
                                @if($isWarning)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-tertiary-fixed text-tertiary font-label-sm text-label-sm shadow-sm font-semibold">
                                        <span class="material-symbols-outlined text-[14px]">warning_amber</span>
                                        Peringatan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold">
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                        Sukses
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-on-surface-variant">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-slate-300 text-5xl">history</span>
                                    <p class="font-headline-sm text-headline-sm text-on-surface font-semibold">Tidak Ada Rekam Jejak Ditemukan</p>
                                    <p class="font-body-sm text-body-sm text-outline max-w-md">Tidak ada log aktivitas yang cocok dengan kriteria filter saat ini. Coba atur ulang filter pencarian Anda.</p>
                                    <a href="{{ route('admin.logs') }}" class="mt-2 px-4 py-2 bg-primary-container text-on-primary font-label-sm text-label-sm rounded-xl font-semibold shadow-xs hover:bg-primary transition">
                                        Reset Semua Filter
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer & Pagination -->
        <div class="px-space-lg py-space-md bg-surface-container-low/70 flex flex-col sm:flex-row items-center justify-between gap-space-md border-t border-slate-200/60 dark:border-slate-800">
            <!-- Records Counter -->
            <div class="flex items-center gap-2 text-on-surface-variant font-body-sm text-body-sm">
                <span class="material-symbols-outlined text-outline text-[18px]">info</span>
                <span>
                    Menampilkan <strong class="font-semibold text-on-surface">{{ $logs->count() }}</strong> dari <strong class="font-semibold text-on-surface">{{ number_format($logs->total()) }}</strong> catatan audit log
                </span>
            </div>

            <!-- Pagination Controls (Laravel Pagination with preserved filters) -->
            <div>
                {{ $logs->links() }}
            </div>
        </div>
    </div>

    <!-- Auxiliary Security Notes Card (Bento Split) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-space-md">
        <!-- Audit Integrity Notice -->
        <div class="lg:col-span-2 bg-surface-container-low p-space-md rounded-xl flex items-start gap-space-md border border-slate-200/60 dark:border-slate-800">
            <div class="w-10 h-10 rounded-xl bg-surface-container-lowest flex items-center justify-center text-primary shrink-0 shadow-sm border border-slate-200/40 dark:border-slate-800">
                <span class="material-symbols-outlined text-[22px]">lock</span>
            </div>
            <div class="flex flex-col">
                <span class="font-headline-sm text-headline-sm text-on-surface font-semibold">Kepatuhan Rekam Jejak (Immutability Policy)</span>
                <p class="font-body-sm text-body-sm text-on-surface-variant mt-1 leading-relaxed">
                    Seluruh log audit dienkripsi secara berkala ke penyimpanan cold-storage lokal dan gateway data terpusat. Riwayat ini tidak dapat diubah oleh administrator umum guna menjamin transparansi asesmen berstandar nasional.
                </p>
            </div>
        </div>

        <!-- Rapid Export Quickcard -->
        <div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex flex-col justify-between border border-slate-200/60 dark:border-slate-800">
            <div class="flex items-center justify-between">
                <span class="font-label-lg text-label-lg text-on-surface font-semibold">Laporan &amp; Berita Acara</span>
                <span class="material-symbols-outlined text-tertiary text-[20px]">notification_important</span>
            </div>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-2 mb-3">
                Cetak atau unduh rekap berkas audit aktivitas dan insiden untuk dokumentasi resmi pengawas asesmen sekolah.
            </p>
            <a href="{{ route('admin.logs', array_merge(request()->query(), ['export' => 'csv'])) }}" 
               class="w-full py-2 bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md text-label-md rounded-lg transition-colors flex items-center justify-center gap-1.5 font-medium">
                <span class="material-symbols-outlined text-[16px]">file_download</span>
                <span>Unduh Audit Log Lengkap</span>
            </a>
        </div>
    </div>
</div>

<!-- Modal: Bersihkan Log Lama -->
<div id="modalClearLogs" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4">
    <div class="bg-surface-container-lowest rounded-2xl max-w-md w-full p-space-lg shadow-xl border border-slate-200 dark:border-slate-800 animate-in fade-in zoom-in duration-150">
        <div class="flex items-center gap-3 text-error mb-3">
            <div class="w-10 h-10 rounded-xl bg-error-container text-on-error-container flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[24px]">auto_delete</span>
            </div>
            <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Bersihkan Log Audit</h3>
        </div>
        <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
            Tindakan ini akan menghapus riwayat audit log yang sudah melampaui batas hari yang ditentukan secara permanen. Pastikan Anda sudah mengekspor salinan cadangan bila diperlukan.
        </p>

        <form action="{{ route('admin.logs.clear') }}" method="POST" class="mt-4 flex flex-col gap-3">
            @csrf
            <div>
                <label class="block font-label-sm text-label-sm text-outline uppercase font-semibold mb-1">
                    Batas Usia Log yang Dihapus
                </label>
                <select name="days" class="w-full bg-surface-container-low text-on-surface px-3 py-2 rounded-xl text-sm outline-none font-medium">
                    <option value="90">Lebih dari 90 Hari (3 Bulan)</option>
                    <option value="60">Lebih dari 60 Hari (2 Bulan)</option>
                    <option value="30">Lebih dari 30 Hari (1 Bulan)</option>
                    <option value="180">Lebih dari 180 Hari (6 Bulan)</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-2 mt-2">
                <button type="button" 
                        onclick="document.getElementById('modalClearLogs').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl text-on-surface-variant hover:bg-surface-container font-label-md text-label-md transition">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 rounded-xl bg-error text-on-error font-label-md text-label-md font-semibold hover:opacity-90 shadow-sm transition">
                    Hapus Log Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Client-side Interactive Filter & Realtime Enhancement Script -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('filter-search');
        const clearSearchBtn = document.getElementById('clear-search');
        const roleFilter = document.getElementById('filter-role');
        const categoryFilter = document.getElementById('filter-category');
        const resetBtn = document.getElementById('reset-filters');
        const refreshBtn = document.getElementById('btn-refresh');
        const refreshIcon = document.getElementById('refresh-icon');
        const tableRows = document.querySelectorAll('#log-table-body tr');

        // Client instant filter while typing
        function liveFilter() {
            const query = (searchInput.value || '').toLowerCase().trim();
            if (query.length > 0 && clearSearchBtn) {
                clearSearchBtn.classList.remove('hidden');
            } else if (clearSearchBtn) {
                clearSearchBtn.classList.add('hidden');
            }

            tableRows.forEach(row => {
                const text = row.innerText.toLowerCase();
                if (query === '' || text.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', liveFilter);
        }

        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', () => {
                searchInput.value = '';
                liveFilter();
                searchInput.focus();
            });
        }

        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                window.location.href = "{{ route('admin.logs') }}";
            });
        }

        // Refresh Button Animation & Page Reload
        if (refreshBtn && refreshIcon) {
            refreshBtn.addEventListener('click', () => {
                refreshIcon.classList.add('animate-spin');
                setTimeout(() => {
                    window.location.reload();
                }, 400);
            });
        }
    });
</script>
@endsection
