@php
    $unreadCount = $unreadNotificationCount ?? 0;
    $notifications = $latestNotifications ?? collect();
@endphp

<div class="relative notification-dropdown-wrapper" id="globalNotificationWrapper">
    <!-- Bell Trigger Button -->
    <button 
        type="button" 
        id="notificationBellBtn"
        onclick="toggleNotificationDropdown()"
        class="relative p-2 sm:p-2.5 rounded-full bg-surface-container-low text-on-surface hover:bg-surface-container-high transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30 flex items-center justify-center cursor-pointer" 
        title="Notifikasi ({{ $unreadCount }} belum dibaca)"
        aria-label="Notifikasi"
        aria-expanded="false"
    >
        <span class="material-symbols-outlined text-[22px]">notifications</span>
        
        <!-- Unread Badge -->
        <span 
            id="notificationBadge" 
            class="{{ $unreadCount > 0 ? 'flex' : 'hidden' }} absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 bg-error text-white font-mono text-[10px] font-bold rounded-full items-center justify-center border-2 border-surface shadow-xs transition-transform transform scale-100"
        >
            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
        </span>
    </button>

    <!-- Dropdown Menu / Popover Panel -->
    <div 
        id="notificationDropdownPanel" 
        class="hidden fixed sm:absolute right-3 sm:right-0 top-16 sm:top-full mt-2 w-[calc(100vw-24px)] sm:w-[380px] max-w-[400px] bg-surface-container-lowest rounded-2xl shadow-xl border border-slate-200/80 z-50 overflow-hidden transition-all duration-200 origin-top-right backdrop-blur-sm"
        role="dialog"
        aria-label="Daftar Notifikasi"
    >
        <!-- Header -->
        <div class="px-4 py-3 bg-surface-container-low/70 border-b border-slate-200/60 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[20px]">notifications_active</span>
                <span class="font-headline-sm text-sm font-bold text-on-surface">Notifikasi</span>
                <span id="notificationUnreadPill" class="{{ $unreadCount > 0 ? 'inline-flex' : 'hidden' }} px-2 py-0.5 rounded-full bg-error-container text-on-error-container text-[10px] font-bold">
                    <span id="unreadCountText">{{ $unreadCount }}</span> Baru
                </span>
            </div>

            <button 
                type="button" 
                onclick="markAllNotificationsAsRead()"
                id="btnMarkAllRead"
                class="{{ $unreadCount > 0 ? 'inline-flex' : 'hidden' }} items-center gap-1 text-[11px] font-semibold text-primary hover:text-primary-hover hover:underline transition-colors cursor-pointer"
                title="Tandai semua notifikasi telah dibaca"
            >
                <span class="material-symbols-outlined text-[14px]">done_all</span>
                <span>Tandai Semua Dibaca</span>
            </button>
        </div>

        <!-- Notification List Container -->
        <div id="notificationList" class="max-h-[380px] overflow-y-auto divide-y divide-slate-100 divide-solid">
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
                        'exam'    => 'bg-blue-50 text-blue-600',
                        'result'  => 'bg-emerald-50 text-emerald-600',
                        'warning' => 'bg-amber-50 text-amber-600',
                        'system'  => 'bg-purple-50 text-purple-600',
                        default   => 'bg-indigo-50 text-indigo-600',
                    };
                @endphp
                <div 
                    id="notif-item-{{ $notif->id }}" 
                    class="p-3.5 flex items-start gap-3 hover:bg-surface-container-low/60 transition-colors cursor-pointer relative group {{ $isUnread ? 'bg-primary/5' : '' }}"
                    onclick="handleNotifClick({{ $notif->id }}, '{{ $notif->type }}')"
                >
                    <!-- Type Icon -->
                    <div class="w-8 h-8 rounded-xl shrink-0 flex items-center justify-center {{ $typeColor }}">
                        <span class="material-symbols-outlined text-[18px]">{{ $typeIcon }}</span>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0 pr-4">
                        <div class="flex items-center gap-1.5">
                            <h4 class="text-xs font-bold text-on-surface truncate {{ $isUnread ? 'text-primary' : '' }}">
                                {{ $notif->title }}
                            </h4>
                            @if($isUnread)
                                <span class="w-2 h-2 rounded-full bg-primary shrink-0 notif-dot"></span>
                            @endif
                        </div>
                        <p class="text-xs text-on-surface-variant line-clamp-2 mt-0.5 leading-relaxed">
                            {{ $notif->message }}
                        </p>
                        <span class="text-[10px] text-outline font-medium block mt-1">
                            {{ $notif->created_at?->diffForHumans() ?? 'Baru saja' }}
                        </span>
                    </div>

                    <!-- Dismiss / Mark Single Read Action -->
                    @if($isUnread)
                        <button 
                            type="button" 
                            onclick="event.stopPropagation(); markSingleNotificationAsRead({{ $notif->id }})"
                            class="opacity-0 group-hover:opacity-100 absolute top-3 right-3 text-outline hover:text-primary transition-opacity p-1 rounded-full hover:bg-surface-container-high"
                            title="Tandai dibaca"
                        >
                            <span class="material-symbols-outlined text-[16px]">done</span>
                        </button>
                    @endif
                </div>
            @empty
                <!-- Empty State -->
                <div id="notificationEmptyState" class="py-10 px-4 flex flex-col items-center justify-center text-center text-outline">
                    <div class="w-12 h-12 rounded-full bg-surface-container-low flex items-center justify-center mb-2 text-outline">
                        <span class="material-symbols-outlined text-[24px]">notifications_off</span>
                    </div>
                    <span class="text-xs font-semibold text-on-surface">Belum ada notifikasi baru</span>
                    <span class="text-[11px] text-outline mt-0.5">Semua pengumuman dan jadwal ujian akan tampil di sini.</span>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="p-2.5 bg-surface-container-low/50 border-t border-slate-200/60 text-center flex items-center justify-between px-4">
            <span class="text-[11px] text-outline font-medium">EduExam Live Notifier</span>
            <a href="{{ route('notifications.index') }}" class="text-[11px] font-bold text-primary hover:underline flex items-center gap-0.5 no-underline">
                <span>Lihat Semua</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            </a>
        </div>
    </div>
</div>

<script>
    function toggleNotificationDropdown() {
        const panel = document.getElementById('notificationDropdownPanel');
        const btn = document.getElementById('notificationBellBtn');
        if (!panel) return;

        const isHidden = panel.classList.contains('hidden');
        if (isHidden) {
            panel.classList.remove('hidden');
            btn?.setAttribute('aria-expanded', 'true');
        } else {
            panel.classList.add('hidden');
            btn?.setAttribute('aria-expanded', 'false');
        }
    }

    // Close when clicking outside
    document.addEventListener('click', function (e) {
        const wrapper = document.getElementById('globalNotificationWrapper');
        const panel = document.getElementById('notificationDropdownPanel');
        const btn = document.getElementById('notificationBellBtn');
        if (!wrapper || !panel) return;

        if (!wrapper.contains(e.target)) {
            panel.classList.add('hidden');
            btn?.setAttribute('aria-expanded', 'false');
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const panel = document.getElementById('notificationDropdownPanel');
            const btn = document.getElementById('notificationBellBtn');
            if (panel && !panel.classList.contains('hidden')) {
                panel.classList.add('hidden');
                btn?.setAttribute('aria-expanded', 'false');
            }
        }
    });

    // Mark single notification as read
    function markSingleNotificationAsRead(notifId) {
        fetch('{{ url("/notifikasi") }}/' + notifId + '/read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const item = document.getElementById('notif-item-' + notifId);
                if (item) {
                    item.classList.remove('bg-primary/5');
                    const dot = item.querySelector('.notif-dot');
                    if (dot) dot.remove();
                }
                updateUnreadBadge(data.unread_count);
            }
        })
        .catch(err => console.error('Error marking notification read:', err));
    }

    // Mark all notifications as read
    function markAllNotificationsAsRead() {
        fetch('{{ route("notifications.readAll") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Remove all unread indicators in list
                const list = document.getElementById('notificationList');
                if (list) {
                    list.querySelectorAll('.bg-primary\\/5').forEach(el => el.classList.remove('bg-primary/5'));
                    list.querySelectorAll('.notif-dot').forEach(el => el.remove());
                }
                updateUnreadBadge(0);
            }
        })
        .catch(err => console.error('Error marking all notifications read:', err));
    }

    // Handle click on item
    function handleNotifClick(notifId, type) {
        markSingleNotificationAsRead(notifId);
        // Optional redirect based on role and type
        const role = '{{ auth()->user()?->role }}';
        if (type === 'exam' || type === 'result') {
            if (role === 'student') {
                window.location.href = '{{ route("student.dashboard") }}';
            } else if (role === 'teacher') {
                window.location.href = '{{ route("teacher.exams.index") }}';
            } else if (role === 'admin') {
                window.location.href = '{{ route("admin.dashboard") }}';
            }
        }
    }

    // Update unread badges dynamically
    function updateUnreadBadge(count) {
        const badge = document.getElementById('notificationBadge');
        const pill = document.getElementById('notificationUnreadPill');
        const text = document.getElementById('unreadCountText');
        const markAllBtn = document.getElementById('btnMarkAllRead');

        if (badge) {
            if (count > 0) {
                badge.textContent = count > 9 ? '9+' : count;
                badge.classList.remove('hidden');
                badge.classList.add('flex');
            } else {
                badge.classList.remove('flex');
                badge.classList.add('hidden');
            }
        }

        if (pill && text) {
            if (count > 0) {
                text.textContent = count;
                pill.classList.remove('hidden');
                pill.classList.add('inline-flex');
            } else {
                pill.classList.remove('inline-flex');
                pill.classList.add('hidden');
            }
        }

        if (markAllBtn) {
            if (count > 0) {
                markAllBtn.classList.remove('hidden');
                markAllBtn.classList.add('inline-flex');
            } else {
                markAllBtn.classList.remove('inline-flex');
                markAllBtn.classList.add('hidden');
            }
        }
    }
</script>
