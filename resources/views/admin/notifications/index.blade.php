@extends('dashboard.layout.master')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-black text-slate-800 text-2xl tracking-tight">الإشعارات <span class="text-slate-400 text-lg font-medium">(<span id="page-unread-count">0</span> غير مقروء)</span></h2>
        <div class="flex items-center gap-3">
            <button onclick="openSendNotificationModal()" class="px-4 py-2 bg-primary text-white font-bold rounded-xl hover:bg-primaryDark transition-colors text-sm shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-paper-plane"></i> إرسال إشعار
            </button>
            <button onclick="markAllNotificationsAsRead()" class="px-4 py-2 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-teal-50 hover:text-primary transition-colors text-sm">
                <i class="fa-solid fa-check-double ml-1"></i> تحديد الكل كمقروء
            </button>
        </div>
    </div>

    <!-- Send Notification Modal -->
    <div id="send-notification-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeSendNotificationModal()"></div>
        <div class="bg-white rounded-3xl w-full max-w-lg mx-4 relative z-10 shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                        <i class="fa-solid fa-paper-plane text-lg"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-800 tracking-tight">إرسال إشعار جديد</h3>
                </div>
                <button onclick="closeSendNotificationModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-200 text-slate-400 transition-colors">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            
            <form id="send-notification-form" onsubmit="submitSendNotification(event)" class="p-6 space-y-5 overflow-y-auto">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الفئة المستهدفة</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <select name="target" required class="w-full pl-4 pr-11 py-3 bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium appearance-none">
                            <option value="both">الجميع (المستخدمين والصيدليات)</option>
                            <option value="users">المستخدمين فقط</option>
                            <option value="pharmacies">الصيدليات فقط</option>
                        </select>
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">نوع الإشعار</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="info" class="peer sr-only" checked>
                            <div class="px-3 py-2 text-center rounded-xl border border-slate-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 text-slate-500 font-bold transition-all text-sm flex items-center justify-center gap-2">
                                <i class="fa-solid fa-circle-info"></i> معلومة
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="success" class="peer sr-only">
                            <div class="px-3 py-2 text-center rounded-xl border border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-600 text-slate-500 font-bold transition-all text-sm flex items-center justify-center gap-2">
                                <i class="fa-solid fa-check-circle"></i> نجاح
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="warning" class="peer sr-only">
                            <div class="px-3 py-2 text-center rounded-xl border border-slate-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:text-amber-600 text-slate-500 font-bold transition-all text-sm flex items-center justify-center gap-2">
                                <i class="fa-solid fa-triangle-exclamation"></i> تحذير
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="error" class="peer sr-only">
                            <div class="px-3 py-2 text-center rounded-xl border border-slate-200 peer-checked:border-rose-500 peer-checked:bg-rose-50 peer-checked:text-rose-600 text-slate-500 font-bold transition-all text-sm flex items-center justify-center gap-2">
                                <i class="fa-solid fa-circle-xmark"></i> خطأ
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">عنوان الإشعار</label>
                    <input type="text" name="title" required placeholder="مثال: تحديث جديد في النظام" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium placeholder-slate-400">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">محتوى الإشعار</label>
                    <textarea name="message" required rows="4" placeholder="اكتب تفاصيل الإشعار هنا..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium placeholder-slate-400 resize-none"></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" id="submit-notification-btn" class="w-full px-6 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primaryDark transition-colors shadow-md shadow-primary/20 flex items-center justify-center gap-2">
                        <span>إرسال الإشعار الآن</span>
                        <i class="fa-solid fa-paper-plane text-sm"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div id="page-notifications-list" class="divide-y divide-slate-50 min-h-[400px] relative">
            <div id="page-loading" class="absolute inset-0 flex items-center justify-center bg-white/80 z-10">
                <i class="fa-solid fa-spinner fa-spin text-3xl text-primary opacity-50"></i>
            </div>
            <div id="page-empty-state" class="hidden absolute inset-0 flex flex-col items-center justify-center text-center p-8">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-regular fa-bell-slash text-4xl text-slate-300"></i>
                </div>
                <h3 class="text-lg font-black text-slate-800 mb-1">لا توجد إشعارات!</h3>
                <p class="text-sm text-slate-500 max-w-sm mx-auto">كل شيء هادئ هنا. ستظهر إشعاراتك الجديدة هنا عندما يكون هناك تحديثات هامة.</p>
            </div>
        </div>
        
        <div class="p-4 border-t border-slate-50 bg-slate-50/50 flex justify-center hidden" id="pagination-container">
            <!-- Pagination logic handled by Vue/React or simple load more in Vanilla JS -->
            <button id="load-more-btn" onclick="loadMoreNotifications()" class="px-6 py-2 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-colors text-sm shadow-sm hidden">
                تحميل المزيد
            </button>
        </div>
    </div>
</div>

<script>
    let currentPage = 1;
    let hasMorePages = false;

    document.addEventListener('DOMContentLoaded', function() {
        loadPageNotifications(1);
    });

    function loadPageNotifications(page) {
        document.getElementById('page-loading').classList.remove('hidden');
        axios.get('/admin/notifications?page=' + page).then(response => {
            const data = response.data;
            document.getElementById('page-loading').classList.add('hidden');
            
            if (page === 1) {
                document.getElementById('page-unread-count').textContent = data.unread_count;
            }

            const notifications = data.notifications.data;
            hasMorePages = data.notifications.next_page_url !== null;
            
            if (page === 1 && notifications.length === 0) {
                document.getElementById('page-empty-state').classList.remove('hidden');
            } else {
                document.getElementById('page-empty-state').classList.add('hidden');
                notifications.forEach(n => appendNotification(n));
            }

            const loadMoreBtn = document.getElementById('load-more-btn');
            const paginationContainer = document.getElementById('pagination-container');
            
            if (hasMorePages) {
                paginationContainer.classList.remove('hidden');
                loadMoreBtn.classList.remove('hidden');
            } else {
                if (page > 1) {
                    loadMoreBtn.classList.add('hidden');
                }
            }
        }).catch(err => {
            console.error('Error loading notifications', err);
            document.getElementById('page-loading').classList.add('hidden');
        });
    }

    function loadMoreNotifications() {
        if(hasMorePages) {
            currentPage++;
            loadPageNotifications(currentPage);
        }
    }

    function appendNotification(notif) {
        const list = document.getElementById('page-notifications-list');
        const isUnread = notif.read_at === null;
        
        let icon = 'fa-bell';
        let color = 'text-primary';
        let bg = 'bg-teal-50';
        
        if(notif.data?.type === 'success') { icon = 'fa-check-circle'; color = 'text-emerald-500'; bg = 'bg-emerald-50'; }
        else if(notif.data?.type === 'warning') { icon = 'fa-triangle-exclamation'; color = 'text-amber-500'; bg = 'bg-amber-50'; }
        else if(notif.data?.type === 'error') { icon = 'fa-circle-xmark'; color = 'text-rose-500'; bg = 'bg-rose-50'; }

        // Format Date
        const dateObj = new Date(notif.created_at);
        const time = dateObj.toLocaleTimeString('ar-EG', {hour: '2-digit', minute:'2-digit'});
        const dateStr = dateObj.toLocaleDateString('ar-EG');

        const html = `
            <div id="page-notif-${notif.id}" class="p-5 hover:bg-slate-50 transition-colors cursor-pointer relative group flex items-start gap-4 ${isUnread ? 'bg-slate-50/30' : 'opacity-70'}" onclick="handlePageNotifClick('${notif.id}', '${notif.data?.action_url || ''}')">
                ${isUnread ? '<div class="absolute right-3 top-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.6)]"></div>' : ''}
                
                <div class="w-12 h-12 rounded-2xl ${bg} ${color} flex items-center justify-center shrink-0 shadow-sm ${isUnread ? 'ring-2 ring-offset-2 ring-primary/20' : ''}">
                    <i class="fa-solid ${icon} text-xl"></i>
                </div>
                
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-4 mb-1">
                        <h4 class="text-sm font-black text-slate-800 truncate">${notif.data?.title || 'إشعار جديد'}</h4>
                        <span class="text-xs font-bold text-slate-400 shrink-0 bg-white px-2 py-1 rounded-lg border border-slate-100">${dateStr} ${time}</span>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed font-medium">${notif.data?.message || ''}</p>
                </div>
            </div>
        `;
        list.insertAdjacentHTML('beforeend', html);
    }

    window.handlePageNotifClick = function(id, url) {
        axios.post('/admin/notifications/' + id + '/read').then(() => {
            const el = document.getElementById('page-notif-' + id);
            if(el) {
                el.classList.remove('bg-slate-50/30');
                el.classList.add('opacity-70');
                const dot = el.querySelector('.bg-rose-500');
                if(dot) dot.remove();
                
                const ring = el.querySelector('.ring-2');
                if(ring) ring.classList.remove('ring-2', 'ring-offset-2', 'ring-primary/20');
            }
            
            let countEl = document.getElementById('page-unread-count');
            let count = parseInt(countEl.textContent);
            countEl.textContent = Math.max(0, count - 1);
            
            if(url && url !== 'null') {
                window.location.href = url;
            }
        });
    };

    window.onNewNotificationReceived = function(notif) {
        document.getElementById('page-empty-state').classList.add('hidden');
        const list = document.getElementById('page-notifications-list');
        // We use prependNotification logic:
        const isUnread = notif.read_at === null;
        
        let icon = 'fa-bell';
        let color = 'text-primary';
        let bg = 'bg-teal-50';
        
        if(notif.data?.type === 'success') { icon = 'fa-check-circle'; color = 'text-emerald-500'; bg = 'bg-emerald-50'; }
        else if(notif.data?.type === 'warning') { icon = 'fa-triangle-exclamation'; color = 'text-amber-500'; bg = 'bg-amber-50'; }
        else if(notif.data?.type === 'error') { icon = 'fa-circle-xmark'; color = 'text-rose-500'; bg = 'bg-rose-50'; }

        // Format Date
        const dateObj = new Date(notif.created_at);
        const time = dateObj.toLocaleTimeString('ar-EG', {hour: '2-digit', minute:'2-digit'});
        const dateStr = dateObj.toLocaleDateString('ar-EG');

        const html = `
            <div id="page-notif-${notif.id}" class="p-5 hover:bg-slate-50 transition-colors cursor-pointer relative group flex items-start gap-4 ${isUnread ? 'bg-slate-50/30' : 'opacity-70'}" onclick="handlePageNotifClick('${notif.id}', '${notif.data?.action_url || ''}')">
                ${isUnread ? '<div class="absolute right-3 top-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.6)]"></div>' : ''}
                
                <div class="w-12 h-12 rounded-2xl ${bg} ${color} flex items-center justify-center shrink-0 shadow-sm ${isUnread ? 'ring-2 ring-offset-2 ring-primary/20' : ''}">
                    <i class="fa-solid ${icon} text-xl"></i>
                </div>
                
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-4 mb-1">
                        <h4 class="text-sm font-black text-slate-800 truncate">${notif.data?.title || 'إشعار جديد'}</h4>
                        <span class="text-xs font-bold text-slate-400 shrink-0 bg-white px-2 py-1 rounded-lg border border-slate-100">${dateStr} ${time}</span>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed font-medium">${notif.data?.message || ''}</p>
                </div>
            </div>
        `;
        list.insertAdjacentHTML('afterbegin', html);
        
        const countEl = document.getElementById('page-unread-count');
        countEl.textContent = parseInt(countEl.textContent || '0') + 1;
    };

    window.markAllNotificationsAsRead = function() {
        axios.post('/admin/notifications/read-all').then(() => {
            // Update Header
            if (typeof unreadNotifCount !== 'undefined') {
                unreadNotifCount = 0;
                if (typeof updateBadge === 'function') updateBadge();
            }
            document.querySelectorAll('[id^="notif-"]').forEach(el => {
                el.classList.remove('bg-slate-50/30');
                el.classList.add('opacity-70');
                const dot = el.querySelector('.bg-rose-500');
                if(dot) dot.remove();
            });

            // Update Page
            document.getElementById('page-unread-count').textContent = '0';
            document.querySelectorAll('[id^="page-notif-"]').forEach(el => {
                el.classList.remove('bg-slate-50/30');
                el.classList.add('opacity-70');
                const dot = el.querySelector('.bg-rose-500');
                if(dot) dot.remove();
                const ring = el.querySelector('.ring-2');
                if(ring) ring.classList.remove('ring-2', 'ring-offset-2', 'ring-primary/20');
            });
        });
    };
    // Send Custom Notification Functions
    function openSendNotificationModal() {
        document.getElementById('send-notification-modal').classList.remove('hidden');
    }

    function closeSendNotificationModal() {
        document.getElementById('send-notification-modal').classList.add('hidden');
        document.getElementById('send-notification-form').reset();
    }

    function submitSendNotification(e) {
        e.preventDefault();
        const form = e.target;
        const btn = document.getElementById('submit-notification-btn');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جاري الإرسال...';
        btn.disabled = true;
        
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        axios.post('/admin/notifications/send', data).then(response => {
            closeSendNotificationModal();
            // Show toast
            const toastHtml = `
                <div class="fixed bottom-4 left-4 z-[100] animate-toast bg-emerald-50 text-emerald-600 px-6 py-4 rounded-2xl shadow-lg border border-emerald-100 flex items-center gap-3">
                    <i class="fa-solid fa-check-circle text-xl"></i>
                    <div>
                        <h4 class="font-bold text-sm">تم الإرسال</h4>
                        <p class="text-xs font-medium opacity-80">تم إرسال الإشعار بنجاح للفئة المستهدفة.</p>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', toastHtml);
            
            // Remove toast after 5s
            setTimeout(() => {
                const toasts = document.querySelectorAll('.animate-toast');
                if(toasts.length > 0) toasts[toasts.length - 1].remove();
            }, 5000);
        }).catch(err => {
            console.error(err);
            alert('حدث خطأ أثناء إرسال الإشعار.');
        }).finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
</script>
@endsection
