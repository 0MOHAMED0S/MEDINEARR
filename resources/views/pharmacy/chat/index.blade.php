@extends('pharmacy.layout.master')

@section('title', 'المحادثات')

@section('content')
<div class="p-4 md:p-6 lg:p-8 font-cairo h-[calc(100vh-100px)]">

    <div class="bg-white rounded-3xl shadow-xl shadow-gray-100 border border-gray-100 overflow-hidden h-full flex">

        <div class="w-full md:w-1/3 lg:w-80 border-l border-gray-100 flex flex-col bg-white">
            <div class="p-5 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-xl mb-4">المحادثات</h2>
                <div class="relative">
                    <input type="text" placeholder="بحث في المحادثات..." class="w-full bg-gray-50 pl-4 pr-10 py-3 rounded-2xl text-sm border-none outline-none focus:ring-2 focus:ring-[#00965e]/20 transition-all">
                    <i class="fa-solid fa-search absolute right-4 top-3.5 text-gray-400"></i>
                </div>
            </div>

            <div class="overflow-y-auto flex-1 custom-scrollbar">
                <div class="px-4 py-4 border-b border-gray-50 bg-emerald-50/50 cursor-pointer transition-all hover:bg-emerald-50">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div class="w-12 h-12 rounded-2xl bg-[#00965e] text-white flex items-center justify-center font-bold text-lg shadow-md shadow-[#00965e]/20">م</div>
                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center mb-1">
                                <h4 class="font-bold text-sm text-gray-800 truncate">محمد سيد</h4>
                                <span class="text-[10px] text-[#00965e] font-bold">10:45 ص</span>
                            </div>
                            <p class="text-xs text-gray-500 truncate">هل الطلب رقم #MED-123456 جاهز؟</p>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-4 border-b border-gray-50 hover:bg-gray-50 cursor-pointer transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-lg">أ</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center mb-1">
                                <h4 class="font-bold text-sm text-gray-800 truncate">أحمد محمود</h4>
                                <span class="text-[10px] text-gray-400">أمس</span>
                            </div>
                            <p class="text-xs text-gray-500 truncate">شكراً جداً على سرعة التوصيل!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hidden md:flex flex-col flex-1 bg-[#f9fafb]">
            <div class="p-5 bg-white border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#00965e] text-white flex items-center justify-center font-bold shadow-sm">م</div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm">محمد سيد</h3>
                        <span class="text-[10px] text-green-500 font-semibold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> متصل الآن
                        </span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button class="p-2 text-gray-400 hover:text-[#00965e] transition-colors"><i class="fa-solid fa-phone"></i></button>
                    <button class="p-2 text-gray-400 hover:text-[#00965e] transition-colors"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                </div>
            </div>

            <div id="messages-area" class="flex-1 overflow-y-auto p-8 space-y-6 custom-scrollbar">
                <div class="flex items-end gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-500">م</div>
                    <div class="bg-white p-4 rounded-2xl rounded-tr-none shadow-sm text-sm text-gray-700 max-w-[60%] leading-relaxed border border-gray-100">
                        هل الطلب رقم #MED-123456 جاهز؟
                    </div>
                </div>

                <div class="flex items-end gap-3 flex-row-reverse">
                    <div class="bg-[#00965e] p-4 rounded-2xl rounded-tl-none shadow-lg shadow-[#00965e]/20 text-sm text-white max-w-[60%] leading-relaxed">
                        أهلاً بك يا أستاذ محمد، نعم الطلب جاهز ويخرج مع المندوب الآن.
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white border-t border-gray-100">
                <form id="chat-form" class="flex gap-3 items-center">
                    <button type="button" class="text-gray-400 hover:text-[#00965e] text-lg transition-colors">
                        <i class="fa-solid fa-paperclip"></i>
                    </button>
                    <input type="text" id="chat-input" placeholder="اكتب رسالتك هنا..." class="flex-1 bg-gray-50 border-none rounded-2xl px-5 py-3 text-sm outline-none focus:ring-2 focus:ring-[#00965e]/20">
                    <button type="submit" class="bg-[#00965e] text-white w-12 h-12 rounded-2xl flex items-center justify-center hover:bg-[#007b4d] transition-all shadow-lg shadow-[#00965e]/20">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* تخصيص شكل شريط التمرير */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
</style>

<script>
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const messagesArea = document.getElementById('messages-area');

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const messageText = chatInput.value.trim();
        if (messageText === '') return;

        const messageHtml = `
            <div class="flex items-end gap-3 flex-row-reverse animate-in fade-in zoom-in duration-300">
                <div class="w-8 h-8 rounded-full bg-[#00965e] flex items-center justify-center text-[10px] font-bold text-white">أ</div>
                <div class="bg-[#00965e] p-4 rounded-2xl rounded-tl-none shadow-lg shadow-[#00965e]/20 text-sm text-white max-w-[60%] leading-relaxed">
                    ${messageText}
                </div>
            </div>
        `;

        messagesArea.insertAdjacentHTML('beforeend', messageHtml);
        chatInput.value = '';
        messagesArea.scrollTop = messagesArea.scrollHeight;
    });
</script>
@endsection
