<header class="glass-nav sticky top-0 z-30 px-4 md:px-6 py-3 md:py-4 border-b border-slate-100 bg-white/80 backdrop-blur-md flex items-center justify-between transition-all">

    <div class="flex items-center gap-3 sm:gap-4 min-w-0 flex-1">
        <button onclick="toggleSidebar()" class="lg:hidden text-primary text-xl p-2 hover:bg-teal-50 rounded-xl transition-colors shrink-0">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>

        <div class="min-w-0 mr-3">
            <h2 class="text-base sm:text-lg md:text-xl font-black text-slate-800 leading-tight truncate">
                مرحباً، <span class="text-primary">{{ explode(' ', Auth::user()->name)[0] }}</span> 👋
            </h2>
            <p class="text-[10px] sm:text-xs font-medium text-slate-400 hidden xs:block italic truncate mt-0.5">
                مدير النظام - صلاحيات كاملة
            </p>
        </div>
    </div>

    <div class="flex items-center gap-2 sm:gap-4 shrink-0">

        <!-- Notifications Dropdown -->
        <div class="relative group">
            <button class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-500 relative hover:bg-teal-50 hover:text-primary transition-all shrink-0" title="الإشعارات" onclick="toggleNotifications()">
                <i class="fa-solid fa-bell text-lg group-hover:shake"></i>
                <span id="header-unread-badge" class="absolute top-2 left-2.5 w-4 h-4 bg-rose-500 text-white text-[9px] font-bold flex items-center justify-center rounded-full border border-white hidden">0</span>
            </button>

            <div id="notifications-dropdown" class="absolute left-0 right-auto top-full mt-3 w-80 bg-white border border-slate-100 shadow-2xl rounded-2xl p-0 opacity-0 invisible transition-all duration-300 z-50 origin-top-left overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 text-sm">الإشعارات</h3>
                    <button onclick="markAllNotificationsAsRead()" class="text-xs text-primary hover:text-teal-700 font-bold hidden" id="mark-all-read-btn">تحديد الكل كمقروء</button>
                </div>
                <div id="notifications-list" class="max-h-80 overflow-y-auto custom-scrollbar">
                    <div class="p-6 text-center text-slate-400 text-xs font-medium" id="empty-notifications">
                        <i class="fa-regular fa-bell-slash text-2xl mb-2 opacity-50 block"></i>
                        لا توجد إشعارات حالياً
                    </div>
                </div>
                <div class="p-2 border-t border-slate-50 bg-slate-50/50 text-center hidden" id="view-all-notifications">
                    <a href="{{ route('admin.notifications.page') }}" class="text-xs font-bold text-slate-500 hover:text-primary transition-colors">عرض كل الإشعارات</a>
                </div>
            </div>
        </div>

        <div class="h-8 w-[1px] bg-slate-200 mx-1 hidden sm:block"></div>

        <div class="flex items-center gap-3">

            <div class="hidden xl:flex flex-col items-start text-right min-w-0 ml-1">
                <span class="text-xs font-black text-slate-800 leading-none mb-1 truncate max-w-[120px]">
                    {{ Auth::user()->name }}
                </span>
                <span class="text-[10px] font-bold text-slate-400 lowercase truncate max-w-[120px]">
                    {{ Auth::user()->email }}
                </span>
            </div>

            <div class="relative group">
                <div class="w-10 h-10 sm:w-11 sm:h-11 md:w-12 md:h-12 rounded-2xl bg-primary/10 p-0.5 border border-primary/20 cursor-pointer overflow-hidden shadow-sm shrink-0 transition-transform group-hover:scale-105">
                    @if(Auth::user()->avatar)
                        <img src="{{ filter_var(Auth::user()->avatar, FILTER_VALIDATE_URL) ? Auth::user()->avatar : asset('storage/'.Auth::user()->avatar) }}" alt="Profile" class="w-full h-full rounded-[14px] object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0d9488&color=fff&bold=true" alt="Profile" class="w-full h-full rounded-[14px] object-cover">
                    @endif
                </div>

                <div class="absolute left-0 right-auto top-full mt-3 w-48 sm:w-56 bg-white border border-slate-100 shadow-2xl rounded-2xl p-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 origin-top-left">

                    <div class="xl:hidden px-3 py-3 border-b border-slate-100 mb-2 bg-slate-50 rounded-xl">
                        <p class="text-xs font-black text-slate-800 truncate text-right mb-1">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-slate-500 font-medium truncate lowercase text-right">{{ Auth::user()->email }}</p>
                    </div>

                    <a href="{{ route('admin.profile.index') }}" class="w-full flex items-center justify-start gap-3 px-4 py-3 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors font-bold text-xs sm:text-sm mb-1">
                        <i class="fa-regular fa-user shrink-0 text-base"></i>
                        <span>الملف الشخصي</span>
                    </a>

                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-start gap-3 px-4 py-3 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors font-bold text-xs sm:text-sm">
                            <i class="fa-solid fa-arrow-right-from-bracket shrink-0 text-base"></i>
                            <span>تسجيل الخروج</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script type="module">
    const userId = {{ auth()->id() }};
    let unreadNotifCount = 0;
    
    window.toggleNotifications = function() {
        const dropdown = document.getElementById('notifications-dropdown');
        dropdown.classList.toggle('opacity-0');
        dropdown.classList.toggle('invisible');
    };

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notifications-dropdown');
        const bellBtn = dropdown.previousElementSibling;
        if (dropdown && bellBtn && !dropdown.contains(event.target) && !bellBtn.contains(event.target)) {
            dropdown.classList.add('opacity-0', 'invisible');
        }
    });

    function updateBadge() {
        const badge = document.getElementById('header-unread-badge');
        const markAllBtn = document.getElementById('mark-all-read-btn');
        const sidebarBadge = document.getElementById('sidebar-notif-count');
        
        if(unreadNotifCount > 0) {
            badge.textContent = unreadNotifCount > 99 ? '99+' : unreadNotifCount;
            badge.classList.remove('hidden');
            markAllBtn.classList.remove('hidden');
            if(sidebarBadge) {
                sidebarBadge.textContent = unreadNotifCount > 99 ? '99+' : unreadNotifCount;
                sidebarBadge.classList.remove('hidden');
            }
        } else {
            badge.classList.add('hidden');
            markAllBtn.classList.add('hidden');
            if(sidebarBadge) sidebarBadge.classList.add('hidden');
        }
    }

    function renderNotification(notif) {
        const emptyState = document.getElementById('empty-notifications');
        if(emptyState) emptyState.style.display = 'none';

        const list = document.getElementById('notifications-list');
        const isUnread = notif.read_at === null;
        
        let icon = 'fa-bell';
        let color = 'text-primary';
        let bg = 'bg-teal-50';
        
        if(notif.data?.type === 'success') { icon = 'fa-check-circle'; color = 'text-emerald-500'; bg = 'bg-emerald-50'; }
        else if(notif.data?.type === 'warning') { icon = 'fa-triangle-exclamation'; color = 'text-amber-500'; bg = 'bg-amber-50'; }
        else if(notif.data?.type === 'error') { icon = 'fa-circle-xmark'; color = 'text-rose-500'; bg = 'bg-rose-50'; }

        const time = new Date(notif.created_at).toLocaleTimeString('ar-EG', {hour: '2-digit', minute:'2-digit'});

        const html = `
            <div id="notif-${notif.id}" class="p-4 border-b border-slate-50 hover:bg-slate-50 transition-colors cursor-pointer relative group ${isUnread ? 'bg-slate-50/30' : 'opacity-70'}" onclick="handleNotifClick('${notif.id}', '${notif.data?.action_url || ''}')">
                ${isUnread ? '<div class="absolute right-2 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-rose-500"></div>' : ''}
                <div class="flex gap-3 items-start ${isUnread ? 'pr-3' : ''}">
                    <div class="w-8 h-8 rounded-full ${bg} ${color} flex items-center justify-center shrink-0">
                        <i class="fa-solid ${icon}"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 mb-0.5">${notif.data?.title || 'إشعار جديد'}</h4>
                        <p class="text-[10px] text-slate-500 leading-relaxed mb-1">${notif.data?.message || ''}</p>
                        <span class="text-[9px] font-medium text-slate-400"><i class="fa-regular fa-clock ml-1"></i>${time}</span>
                    </div>
                </div>
            </div>
        `;
        list.insertAdjacentHTML('afterbegin', html);
    }

    window.handleNotifClick = function(id, url) {
        axios.post('/admin/notifications/' + id + '/read').then(() => {
            const el = document.getElementById('notif-' + id);
            if(el) {
                el.classList.remove('bg-slate-50/30');
                el.classList.add('opacity-70');
                const dot = el.querySelector('.bg-rose-500');
                if(dot) dot.remove();
            }
            unreadNotifCount = Math.max(0, unreadNotifCount - 1);
            updateBadge();
            if(url && url !== 'null') {
                window.location.href = url;
            }
        });
    };

    window.markAllNotificationsAsRead = function() {
        axios.post('/admin/notifications/read-all').then(() => {
            unreadNotifCount = 0;
            updateBadge();
            document.querySelectorAll('[id^="notif-"]').forEach(el => {
                el.classList.remove('bg-slate-50/30');
                el.classList.add('opacity-70');
                const dot = el.querySelector('.bg-rose-500');
                if(dot) dot.remove();
            });
        });
    };

    // Initial Fetch
    axios.get('/admin/notifications').then(response => {
        unreadNotifCount = response.data.unread_count;
        updateBadge();
        const notifications = response.data.notifications.data;
        
        if(notifications.length > 0) {
            // Reverse so we insert afterbegin correctly
            notifications.reverse().forEach(n => renderNotification(n));
            document.getElementById('view-all-notifications').classList.remove('hidden');
        }
    }).catch(err => console.error('Error fetching notifications:', err));

    // Pusher Listener
    if(window.Echo) {
        window.Echo.private('App.Models.User.' + userId)
            .notification((notification) => {
                unreadNotifCount++;
                updateBadge();
                
                // Format matches Laravel broadcast representation
                const formattedNotif = {
                    id: notification.id,
                    data: {
                        title: notification.title,
                        message: notification.message,
                        type: notification.type,
                        action_url: notification.action_url
                    },
                    read_at: null,
                    created_at: notification.created_at || new Date().toISOString()
                };
                
                renderNotification(formattedNotif);
                if(typeof window.onNewNotificationReceived === 'function') window.onNewNotificationReceived(formattedNotif);
                
                // Play subtle sound if desired
                try {
                    const audio = new Audio('/sounds/notification.mp3'); // Example path
                    audio.play();
                } catch(e) {}
            });
    }
</script>
