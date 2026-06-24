@extends('pharmacy.layout.master')

@section('title', 'المحادثات المباشرة')

@section('content')
<div class="font-cairo h-[calc(100vh-100px)] flex flex-col p-2 md:p-6 lg:p-8 bg-slate-50/50 relative overflow-hidden">
    <!-- Subtle Background Elements -->
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-emerald-300/20 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-teal-300/20 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-2xl shadow-emerald-900/5 border border-white/60 overflow-hidden h-full flex flex-col md:flex-row relative z-10 transition-all duration-300 ring-1 ring-slate-900/5">

        <!-- Sidebar (Sessions) -->
        <div class="w-full md:w-80 lg:w-[350px] border-b md:border-b-0 md:border-l border-slate-100 flex flex-col bg-white/50 backdrop-blur-md">
            <div class="p-6 border-b border-slate-100/80 bg-white/40">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-black text-slate-800 text-2xl tracking-tight">الرسائل</h2>
                    <div class="flex gap-2">
                        <span class="bg-rose-100 text-rose-600 text-xs font-bold px-3 py-1 rounded-full shadow-sm hidden" id="total-unread-badge">0 رسالة جديدة</span>
                        <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full shadow-sm" id="active-chats-count">0 نشط</span>
                    </div>
                </div>
                <div class="relative group">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                        <i class="fa-solid fa-search"></i>
                    </div>
                    <input type="text" placeholder="ابحث في المحادثات..." class="w-full bg-slate-50/80 border border-slate-200/60 rounded-2xl pr-11 pl-4 py-3.5 text-sm outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/50 transition-all text-slate-700 shadow-sm placeholder:text-slate-400">
                </div>
            </div>

            <div id="sessions-list" class="overflow-y-auto flex-1 custom-scrollbar">
                <!-- Skeletons -->
                <div class="p-4 space-y-4">
                    @for($i=0; $i<4; $i++)
                    <div class="flex items-center gap-4 animate-pulse">
                        <div class="w-14 h-14 bg-slate-200 rounded-2xl"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-4 bg-slate-200 rounded w-1/2"></div>
                            <div class="h-3 bg-slate-100 rounded w-3/4"></div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Main Chat Area -->
        <div id="chat-container" class="hidden md:flex flex-col flex-1 bg-slate-50/30 relative">
            
            <!-- Chat Header -->
            <div class="p-5 px-8 bg-white/70 backdrop-blur-md border-b border-slate-100 flex items-center justify-between z-10 shadow-sm">
                <div class="flex items-center gap-4">
                    <button class="md:hidden p-2 text-slate-400 hover:text-emerald-600 transition-colors" onclick="document.getElementById('chat-container').classList.add('hidden')">
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <div class="relative">
                        <div id="current-chat-avatar" class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center font-bold text-lg shadow-lg shadow-emerald-500/30"></div>
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div>
                        <h3 id="current-chat-name" class="font-extrabold text-slate-800 text-lg"></h3>
                        <p class="text-xs text-emerald-600 font-semibold flex items-center gap-1.5 mt-0.5">
                            نشط الآن
                        </p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 flex items-center justify-center transition-all duration-300"><i class="fa-solid fa-phone"></i></button>
                    <button class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 flex items-center justify-center transition-all duration-300"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                </div>
            </div>

            <!-- Messages Area -->
            <div id="messages-area" class="flex-1 overflow-y-auto p-4 md:p-8 space-y-6 custom-scrollbar scroll-smooth">
                <!-- Welcome State -->
                <div class="h-full flex flex-col items-center justify-center text-slate-400 opacity-60">
                    <div class="w-24 h-24 bg-slate-200 rounded-full flex items-center justify-center mb-4">
                        <i class="fa-regular fa-comments text-4xl"></i>
                    </div>
                    <p class="font-semibold">اختر محادثة للبدء</p>
                </div>
            </div>

            <!-- Chat Form -->
            <div class="p-4 md:p-6 bg-white/70 backdrop-blur-lg border-t border-slate-100">
                <div id="file-name-preview" class="text-xs font-semibold text-emerald-600 mb-2 px-4 py-2 bg-emerald-50 rounded-lg hidden w-fit border border-emerald-100 flex items-center gap-2">
                    <i class="fa-solid fa-paperclip"></i>
                    <span id="file-name-text"></span>
                    <button type="button" class="ml-2 text-red-400 hover:text-red-600" onclick="clearFile()"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form id="chat-form" class="flex gap-3 items-end">
                    <input type="file" id="chat-file" class="hidden" accept="image/*,.pdf,.doc,.docx">
                    
                    <button type="button" onclick="document.getElementById('chat-file').click()" class="shrink-0 w-12 h-12 rounded-2xl bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-700 text-lg flex items-center justify-center transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-slate-200/50">
                        <i class="fa-solid fa-paperclip"></i>
                    </button>
                    
                    <div class="flex-1 relative">
                        <textarea id="chat-input" rows="1" placeholder="اكتب رسالتك..." class="w-full bg-slate-50 border border-slate-200 rounded-[1.25rem] px-5 py-3.5 text-sm outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/50 transition-all text-slate-700 shadow-sm resize-none custom-scrollbar leading-relaxed" style="min-height: 52px; max-height: 120px;"></textarea>
                    </div>
                    
                    <button type="submit" class="shrink-0 bg-gradient-to-br from-emerald-500 to-teal-600 text-white w-12 h-12 rounded-2xl flex items-center justify-center hover:shadow-lg hover:shadow-emerald-500/30 hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-emerald-500/30">
                        <i class="fa-solid fa-paper-plane mr-1 text-lg"></i>
                    </button>
                </form>
            </div>
            
            <!-- Empty State Overlay (when no chat is selected) -->
            <div id="empty-state" class="absolute inset-0 bg-white/60 backdrop-blur-sm z-20 flex flex-col items-center justify-center">
                <div class="w-32 h-32 bg-emerald-50 rounded-full flex items-center justify-center mb-6 shadow-inner border border-emerald-100">
                    <i class="fa-regular fa-message text-5xl text-emerald-300"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 mb-2">رسائل الصيدلية</h3>
                <p class="text-slate-500 font-medium">اختر محادثة من القائمة الجانبية للرد على العملاء</p>
            </div>
            
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    @keyframes slideUpFade {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .msg-animate { animation: slideUpFade 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>

@vite(['resources/js/app.js'])

<script type="module">
    const authPharmacyId = {{ auth('sanctum')->user()?->pharmacy?->id ?? 'null' }};
    
    document.addEventListener("DOMContentLoaded", () => {
        const sessionsList = document.getElementById('sessions-list');
        const messagesArea = document.getElementById('messages-area');
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');
        const chatFile = document.getElementById('chat-file');
        const fileNamePreview = document.getElementById('file-name-preview');
        const fileNameText = document.getElementById('file-name-text');
        const chatContainer = document.getElementById('chat-container');
        const currentChatName = document.getElementById('current-chat-name');
        const currentChatAvatar = document.getElementById('current-chat-avatar');
        const emptyState = document.getElementById('empty-state');
        const activeChatsCount = document.getElementById('active-chats-count');
        
        let activeSessionId = null;
        let currentSubscription = null;
        let totalUnread = 0;

        function updateTotalUnreadBadge() {
            const badge = document.getElementById('total-unread-badge');
            if (totalUnread > 0) {
                badge.textContent = totalUnread + ' رسائل جديدة';
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }

        // Auto resize textarea
        chatInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        chatInput.addEventListener('keydown', function(e) {
            if(e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit'));
            }
        });

        window.clearFile = () => {
            chatFile.value = '';
            fileNamePreview.classList.add('hidden');
        };

        // Fetch Sessions
        axios.get('/pharmacy/chats/sessions').then(response => {
            const sessions = response.data.sessions;
            sessionsList.innerHTML = '';
            
            if(sessions.length === 0) {
                sessionsList.innerHTML = '<div class="p-10 text-center text-slate-400 font-medium"><i class="fa-solid fa-inbox text-3xl mb-3 block opacity-50"></i>لا توجد محادثات حتى الآن</div>';
                activeChatsCount.textContent = '0 نشط';
                return;
            }

            activeChatsCount.textContent = sessions.length + ' نشط';
            
            totalUnread = sessions.reduce((sum, session) => sum + (session.unread_count || 0), 0);
            updateTotalUnreadBadge();

            sessions.forEach(session => {
                const userName = session.user?.name || 'مستخدم';
                const initial = userName.charAt(0);
                const isSelected = activeSessionId === session.id;
                
                const sessionHtml = `
                    <div id="session-card-${session.id}" class="px-6 py-4 border-b border-slate-50 hover:bg-slate-50 cursor-pointer transition-all duration-200 group ${isSelected ? 'bg-emerald-50/50 border-l-4 border-l-emerald-500' : 'border-l-4 border-l-transparent'}" onclick="loadSession(${session.id}, '${userName}')">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 text-slate-600 flex items-center justify-center font-extrabold text-xl shadow-inner group-hover:shadow-md transition-shadow">${initial}</div>
                                <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-center mb-1.5">
                                    <h4 class="font-bold text-sm text-slate-800 truncate group-hover:text-emerald-700 transition-colors">${userName}</h4>
                                    <span class="text-[10px] text-slate-400 font-medium">الآن</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-xs text-slate-500 truncate font-medium">انقر لعرض المحادثة...</p>
                                    <span id="unread-badge-${session.id}" class="bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full ${session.unread_count > 0 ? '' : 'hidden'} shadow-sm shadow-rose-500/30">${session.unread_count}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                sessionsList.insertAdjacentHTML('beforeend', sessionHtml);
                
                // Subscribe to all sessions to listen for unread count updates globally
                if(window.Echo) {
                    window.Echo.private('chat.' + session.id).listen('MessageSent', (e) => {
                         if(e.message.sender_type !== 'pharmacy') {
                             if(activeSessionId !== session.id) {
                                 const unreadBadge = document.getElementById('unread-badge-' + session.id);
                                 if(unreadBadge) {
                                     let currentCount = parseInt(unreadBadge.textContent) || 0;
                                     unreadBadge.textContent = currentCount + 1;
                                     unreadBadge.classList.remove('hidden');
                                     totalUnread++;
                                     updateTotalUnreadBadge();
                                     // Move session card to top
                                     const sessionCard = document.getElementById('session-card-' + session.id);
                                     if(sessionCard) sessionCard.parentNode.prepend(sessionCard);
                                 }
                             }
                         }
                    });
                }
            });
        }).catch(err => {
            console.error('Error fetching sessions:', err);
            sessionsList.innerHTML = '<div class="p-6 text-center text-red-500 text-sm font-bold bg-red-50 m-4 rounded-xl border border-red-100">حدث خطأ في الاتصال</div>';
        });

        chatFile.addEventListener('change', () => {
            if(chatFile.files.length > 0) {
                fileNameText.textContent = chatFile.files[0].name;
                fileNamePreview.classList.remove('hidden');
                fileNamePreview.classList.add('flex');
            } else {
                clearFile();
            }
        });

        // Load specific session
        window.loadSession = (sessionId, userName) => {
            activeSessionId = sessionId;
            emptyState.classList.add('hidden');
            chatContainer.classList.remove('hidden'); // For mobile
            currentChatName.textContent = userName;
            currentChatAvatar.textContent = userName.charAt(0);
            
            // Highlight selected in list
            document.querySelectorAll('[id^="session-card-"]').forEach(el => {
                el.classList.remove('bg-emerald-50/50', 'border-l-emerald-500');
                el.classList.add('border-l-transparent');
            });
            const selectedCard = document.getElementById(`session-card-${sessionId}`);
            if(selectedCard) {
                selectedCard.classList.add('bg-emerald-50/50', 'border-l-emerald-500');
                selectedCard.classList.remove('border-l-transparent');
            }
            
            // Mark as read immediately on frontend
            const unreadBadge = document.getElementById('unread-badge-' + sessionId);
            if (unreadBadge && !unreadBadge.classList.contains('hidden')) {
                const count = parseInt(unreadBadge.textContent);
                totalUnread = Math.max(0, totalUnread - count);
                updateTotalUnreadBadge();
                unreadBadge.classList.add('hidden');
                unreadBadge.textContent = '0';
                
                // Call API to mark as read
                axios.post(`/pharmacy/chats/${sessionId}/read`).catch(err => console.error(err));
            }
            
            messagesArea.innerHTML = `
                <div class="h-full flex flex-col items-center justify-center text-slate-400 space-y-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500"></div>
                    <span class="font-medium text-sm">جاري التحميل...</span>
                </div>
            `;

            // Unsubscribe from previous
            if(currentSubscription && window.Echo) {
                window.Echo.leave('chat.' + currentSubscription);
            }

            // Fetch messages
            axios.get(`/pharmacy/chats/${sessionId}/messages`).then(response => {
                const messages = response.data.messages;
                messagesArea.innerHTML = '';
                
                if (messages.length === 0) {
                    messagesArea.innerHTML = '<div class="text-center py-10 text-slate-400 font-medium text-sm bg-white/50 rounded-2xl mx-8 mt-8 border border-slate-100">بداية المحادثة. يمكنك إرسال رسالة الآن.</div>';
                } else {
                    let lastDate = null;
                    messages.forEach(msg => {
                        // Very simple date separator logic
                        const msgDate = new Date(msg.created_at).toLocaleDateString('ar-EG');
                        if (msgDate !== lastDate) {
                            messagesArea.insertAdjacentHTML('beforeend', `
                                <div class="flex justify-center my-6">
                                    <span class="bg-white/60 text-slate-400 text-[10px] font-bold px-3 py-1 rounded-full border border-slate-100/50 shadow-sm">${msgDate}</span>
                                </div>
                            `);
                            lastDate = msgDate;
                        }
                        appendMessage(msg, false);
                    });
                }
                scrollToBottom();

                // Subscribe to Pusher channel
                currentSubscription = sessionId;
                if(window.Echo) {
                    window.Echo.private('chat.' + sessionId)
                        .listen('MessageSent', (e) => {
                            if(e.message.sender_type !== 'pharmacy') {
                                // Remove 'بداية المحادثة' if exists
                                const startMsg = messagesArea.querySelector('.text-center.py-10');
                                if(startMsg) startMsg.remove();
                                
                                appendMessage(e.message, true);
                                scrollToBottom();
                                
                                // Auto-mark as read since we are viewing it
                                axios.post(`/pharmacy/chats/${sessionId}/read`).catch(err => console.error(err));
                            }
                        })
                        .listen('MessageRead', (e) => {
                            if (e.readBy === 'user') {
                                // Mark all pharmacy messages as read visually
                                document.querySelectorAll('i[id^="msg-status-"]').forEach(icon => {
                                    icon.classList.remove('text-emerald-100/50', 'fa-check');
                                    icon.classList.add('text-blue-400', 'fa-check-double');
                                });
                            }
                        });
                }
            }).catch(err => {
                messagesArea.innerHTML = '<div class="text-center py-10 text-rose-500 font-bold bg-rose-50 rounded-2xl mx-8 mt-8 border border-rose-100">فشل في تحميل الرسائل</div>';
            });
        };

        // Submit form
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if(!activeSessionId) return;

            const messageText = chatInput.value.trim();
            const file = chatFile.files[0];

            if (messageText === '' && !file) return;

            const formData = new FormData();
            if(messageText) formData.append('message', messageText);
            if(file) formData.append('file', file);

            // Optimistic UI
            const tempId = 'temp-' + Date.now();
            
            // Remove 'بداية المحادثة' if exists
            const startMsg = messagesArea.querySelector('.text-center.py-10');
            if(startMsg) startMsg.remove();

            appendMessage({
                id: tempId,
                sender_type: 'pharmacy',
                body: file ? 'جاري الإرسال...' : messageText,
                type: file ? 'text' : 'text', // Show as text initially
                is_optimistic: true
            }, true);
            
            scrollToBottom();
            chatInput.value = '';
            chatInput.style.height = 'auto'; // reset height
            clearFile();

            axios.post(`/pharmacy/chats/${activeSessionId}/messages`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            }).then(response => {
                // Replace optimistic message
                const tempEl = document.getElementById('msg-' + tempId);
                if(tempEl) tempEl.remove();
                appendMessage(response.data.message, false);
                scrollToBottom();
            }).catch(err => {
                console.error('Error sending message', err);
                const tempEl = document.getElementById('msg-' + tempId);
                if(tempEl) {
                    tempEl.querySelector('.bubble').classList.add('bg-rose-500');
                    tempEl.querySelector('.bubble').textContent = 'فشل الإرسال';
                }
            });
        });

        function appendMessage(msg, animate = false) {
            const isMe = msg.sender_type === 'pharmacy';
            const initial = isMe ? 'ص' : 'م';
            
            // Classes for Me vs Other
            const layoutClass = isMe ? 'flex-row-reverse' : '';
            const avatarClass = isMe ? 'bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-emerald-500/30' : 'bg-white text-slate-500 border border-slate-100 shadow-slate-200/50';
            const bubbleWrapper = isMe ? 'items-end' : 'items-start';
            const bubbleClass = isMe 
                ? 'bg-gradient-to-br from-emerald-500 to-teal-600 rounded-[1.25rem] rounded-tl-sm shadow-md shadow-emerald-500/20 text-white' 
                : 'bg-white rounded-[1.25rem] rounded-tr-sm shadow-md shadow-slate-200/40 border border-slate-100 text-slate-700';
            const timeClass = isMe ? 'text-emerald-100/70' : 'text-slate-400';
            
            let contentHtml = '';
            if(msg.type === 'image' && msg.file_path) {
                contentHtml = `<div class="mb-2 overflow-hidden rounded-xl bg-white/10 ring-1 ring-black/5"><img src="/storage/${msg.file_path}" class="max-w-full h-auto max-h-64 object-cover hover:scale-105 transition-transform duration-300" alt="صورة"></div>`;
            } else if (msg.type === 'file' && msg.file_path) {
                const isPdf = msg.file_path.toLowerCase().endsWith('.pdf');
                const icon = isPdf ? 'fa-file-pdf text-red-400' : 'fa-file-lines text-blue-400';
                contentHtml = `<a href="/storage/${msg.file_path}" target="_blank" class="flex items-center gap-3 p-3 bg-white/10 rounded-xl hover:bg-white/20 transition-colors mb-2 ring-1 ring-black/5"><i class="fa-solid ${icon} text-2xl"></i><span class="font-semibold text-sm underline decoration-white/30 underline-offset-4">تحميل المرفق</span></a>`;
            }
            
            if(msg.body) {
                contentHtml += `<div class="whitespace-pre-wrap">${msg.body}</div>`;
            }
            
            const timeString = msg.created_at ? new Date(msg.created_at).toLocaleTimeString('ar-EG', {hour: '2-digit', minute:'2-digit'}) : 'الآن';
            const opacityClass = msg.is_optimistic ? 'opacity-70' : '';
            const animateClass = animate ? 'msg-animate' : '';
            const msgId = msg.id ? `id="msg-${msg.id}"` : '';

            const messageHtml = `
                <div ${msgId} class="flex items-end gap-3 ${layoutClass} ${opacityClass} ${animateClass}">
                    <div class="w-8 h-8 rounded-full ${avatarClass} flex items-center justify-center text-xs font-bold shadow-sm shrink-0">${initial}</div>
                    <div class="flex flex-col ${bubbleWrapper} max-w-[75%] md:max-w-[65%]">
                        <div class="bubble p-4 text-sm leading-relaxed ${bubbleClass}">
                            ${contentHtml}
                        </div>
                        <div class="flex items-center gap-1 mt-1 justify-end px-1">
                            <span class="text-[10px] ${timeClass} font-medium">${timeString}</span>
                            ${isMe ? `<i id="msg-status-${msg.id}" class="fa-solid ${msg.is_read ? 'fa-check-double text-blue-400' : 'fa-check text-emerald-100/50'} text-[10px]"></i>` : ''}
                        </div>
                    </div>
                </div>
            `;
            messagesArea.insertAdjacentHTML('beforeend', messageHtml);
        }

        function scrollToBottom() {
            messagesArea.scrollTop = messagesArea.scrollHeight;
        }
    });
</script>
@endsection
