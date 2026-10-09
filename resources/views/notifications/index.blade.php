@php
    $userRole = auth()->user()->role;
    $layout = match($userRole) {
        'admin'   => 'layouts.admin',
        'teacher' => 'layouts.teacher',
        default   => 'layouts.student',
    };
@endphp

@extends($layout)

@section('title', 'Pusat Notifikasi — EduExam')

@section($userRole === 'admin' ? 'admin-content' : ($userRole === 'teacher' ? 'teacher-content' : 'student-content'))
<div class="flex flex-col w-full max-w-4xl mx-auto pb-10 {{ $userRole === 'student' ? 'mt-2' : '' }}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="font-headline-lg text-xl sm:text-2xl font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[26px]">notifications_active</span>
                <span>Pusat Notifikasi</span>
            </h1>
            <p class="font-body-md text-xs sm:text-sm text-on-surface-variant mt-0.5">
                Semua pemberitahuan sistem, jadwal ujian, dan informasi akademik Anda
            </p>
        </div>

        @if($notifications->where('is_read', false)->count() > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl bg-primary text-on-primary font-label-md text-xs font-semibold flex items-center gap-1.5 shadow-sm hover:bg-primary-hover active:scale-95 transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">done_all</span>
                    <span>Tandai Semua Dibaca</span>
                </button>
            </form>
        @endif
    </div>

    <!-- Notification Cards List -->
    <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden divide-y divide-slate-100">
        @forelse($notifications as $notif)
            @php
                $isUnread = !$notif->is_read;
                $typeIcon = match($notif->type) {
                    'exam'    => 'quiz',
                    'result'  => 'verified',
                    'warning' => 'warning',
                    'system'  => 'settings',
                    default   => 'info',
                };
                $typeColor = match($notif->type) {
                    'exam'    => 'bg-blue-100 text-blue-700',
                    'result'  => 'bg-emerald-100 text-emerald-700',
                    'warning' => 'bg-amber-100 text-amber-700',
                    'system'  => 'bg-purple-100 text-purple-700',
                    default   => 'bg-indigo-100 text-indigo-700',
                };
            @endphp
            <div class="p-4 sm:p-5 flex items-start gap-4 hover:bg-surface-container-low/40 transition-colors {{ $isUnread ? 'bg-primary/5' : '' }}">
                <div class="w-10 h-10 rounded-xl shrink-0 flex items-center justify-center {{ $typeColor }}">
                    <span class="material-symbols-outlined text-[20px]">{{ $typeIcon }}</span>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <h3 class="font-headline-sm text-sm sm:text-base font-bold text-on-surface {{ $isUnread ? 'text-primary' : '' }}">
                                {{ $notif->title }}
                            </h3>
                            @if($isUnread)
                                <span class="px-2 py-0.5 rounded-full bg-primary text-white text-[10px] font-bold">Baru</span>
                            @endif
                        </div>
                        <span class="text-[11px] font-mono text-outline shrink-0">
                            {{ $notif->created_at?->diffForHumans() ?? '-' }}
                        </span>
                    </div>

                    <p class="font-body-md text-xs sm:text-sm text-on-surface-variant mt-1 leading-relaxed">
                        {{ $notif->message }}
                    </p>

                    <div class="flex items-center gap-3 mt-3">
                        @if($isUnread)
                            <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs text-primary font-semibold hover:underline flex items-center gap-1 cursor-pointer">
                                    <span class="material-symbols-outlined text-[15px]">done</span>
                                    <span>Tandai telah dibaca</span>
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-outline flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px]">check_circle</span>
                                <span>Sudah dibaca</span>
                            </span>
                        @endif

                        <form action="{{ route('notifications.destroy', $notif->id) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-outline hover:text-error transition-colors flex items-center gap-1 cursor-pointer" title="Hapus Notifikasi">
                                <span class="material-symbols-outlined text-[15px]">delete</span>
                                <span>Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-16 px-4 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 rounded-full bg-surface-container-low flex items-center justify-center mb-3 text-outline">
                    <span class="material-symbols-outlined text-[32px]">notifications_none</span>
                </div>
                <h3 class="font-headline-sm text-base font-bold text-on-surface">Belum ada notifikasi</h3>
                <p class="font-body-md text-xs text-outline mt-1 max-w-sm">
                    Pemberitahuan aktivitas ujian, jadwal baru, dan laporan sistem Anda akan ditampilkan di halaman ini.
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
