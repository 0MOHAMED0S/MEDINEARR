@extends('dashboard.layout.master')
@section('content')
    <div class="p-4 md:p-6 lg:p-8 relative">

        <div id="toast-container" class="fixed top-4 left-4 right-4 md:left-auto md:right-6 md:top-6 z-[9999] flex flex-col gap-3 pointer-events-none">
            @if(session('success'))
                <div class="animate-toast pointer-events-auto bg-white border-r-4 border-emerald-500 shadow-xl rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm ml-auto text-right">
                    <div class="bg-emerald-100 p-2 rounded-xl text-emerald-600 shrink-0">
                        <i class="fa-solid fa-circle-check text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">تمت العملية</p>
                        <p class="text-sm font-black text-slate-800 leading-tight">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-slate-300 hover:text-slate-500 transition-colors shrink-0"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="animate-toast pointer-events-auto bg-white border-r-4 border-rose-500 shadow-xl rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm ml-auto text-right">
                    <div class="bg-rose-100 p-2 rounded-xl text-rose-600 shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">خطأ</p>
                        <p class="text-sm font-black text-slate-800 leading-tight">{{ $errors->first() ?? 'يرجى مراجعة البيانات المدخلة.' }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-slate-300 hover:text-slate-500 transition-colors shrink-0"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6 md:mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">الملف الشخصي</h2>
                <p class="text-xs md:text-sm text-gray-500 font-medium mt-1">إدارة معلومات حسابك وإعدادات الأمان الخاصة بك</p>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 md:gap-8">

            <div class="w-full lg:w-1/3 xl:w-1/4">
                <div class="bg-white p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem] border border-gray-100 shadow-sm text-center lg:sticky lg:top-24">

                    <div class="relative w-28 h-28 md:w-32 md:h-32 mx-auto mb-5">
                        <div class="w-full h-full rounded-full border-4 border-white shadow-lg overflow-hidden bg-slate-50">
                            @if($user->avatar)
                                <img src="{{ filter_var($user->avatar, FILTER_VALIDATE_URL) ? $user->avatar : asset('storage/'.$user->avatar) }}" alt="Profile" class="w-full h-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0ea5e9&color=fff&size=128" alt="Profile" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="absolute bottom-1 right-1 w-5 h-5 md:w-6 md:h-6 bg-emerald-500 border-2 border-white rounded-full" title="متصل"></div>
                    </div>

                    <h3 class="text-xl md:text-2xl font-black text-slate-800 mb-1">{{ $user->name }}</h3>
                    <p class="text-[10px] md:text-[11px] font-black text-blue-600 bg-blue-50 border border-blue-100 px-3 py-1 rounded-full uppercase tracking-wider inline-block mb-6">
                        <i class="fa-solid fa-shield-halved mr-1"></i> مدير النظام (Admin)
                    </p>

                    <div class="bg-slate-50 rounded-2xl p-4 text-right border border-gray-100 mb-6">
                        <span class="block text-[9px] md:text-[10px] uppercase font-bold text-gray-400 mb-1">تاريخ الانضمام</span>
                        <p class="text-sm font-bold text-slate-700 font-mono">{{ $user->created_at->format('Y/m/d') }}</p>
                    </div>

                    <div class="border-t border-gray-100 pt-6 mt-2">
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-3.5 rounded-2xl font-bold text-rose-600 bg-rose-50 hover:bg-rose-500 hover:text-white border border-rose-100 hover:border-rose-500 transition-all text-sm flex items-center justify-center gap-2 group">
                                <i class="fa-solid fa-arrow-right-from-bracket group-hover:-translate-x-1 transition-transform"></i>
                                تسجيل الخروج
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-2/3 xl:w-3/4 space-y-6 md:space-y-8">

                <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-5 md:p-8 border-b border-gray-50 flex items-center gap-4 bg-slate-50/30">
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-50 text-blue-600 rounded-xl md:rounded-2xl flex items-center justify-center text-lg md:text-xl shadow-inner border border-blue-100 shrink-0">
                            <i class="fa-regular fa-id-card"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 text-base md:text-lg tracking-tight">المعلومات الأساسية</h3>
                            <p class="text-[11px] md:text-xs text-gray-500 font-medium mt-0.5">تحديث اسمك وبريدك الإلكتروني</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.profile.info') }}" method="POST" onsubmit="disableSubmitButton(this)">
                        @csrf @method('PUT')
                        <div class="p-5 md:p-8 grid grid-cols-1 sm:grid-cols-2 gap-5 md:gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">الاسم بالكامل</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-slate-50 border border-gray-200 rounded-xl md:rounded-2xl px-4 py-3 md:px-5 md:py-3.5 text-sm focus:bg-white focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">البريد الإلكتروني</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full bg-slate-50 border border-gray-200 rounded-xl md:rounded-2xl px-4 py-3 md:px-5 md:py-3.5 text-sm focus:bg-white focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none shadow-sm" dir="ltr">
                            </div>
                        </div>
                        <div class="px-5 md:px-8 py-4 md:py-5 bg-slate-50/50 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="w-full sm:w-auto px-6 md:px-8 py-3 md:py-3.5 rounded-xl md:rounded-2xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-600/30 transition-all text-sm flex items-center justify-center gap-2">
                                <i class="fa-solid fa-save"></i> حفظ البيانات
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-5 md:p-8 border-b border-gray-50 flex items-center gap-4 bg-slate-50/30">
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-rose-50 text-rose-500 rounded-xl md:rounded-2xl flex items-center justify-center text-lg md:text-xl shadow-inner border border-rose-100 shrink-0">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 text-base md:text-lg tracking-tight">إعدادات الأمان</h3>
                            <p class="text-[11px] md:text-xs text-gray-500 font-medium mt-0.5">تغيير كلمة المرور الخاصة بحسابك</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.profile.password') }}" method="POST" onsubmit="disableSubmitButton(this)">
                        @csrf @method('PUT')
                        <div class="p-5 md:p-8 space-y-5 md:space-y-6">

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">كلمة المرور الحالية</label>
                                <input type="password" name="current_password" required placeholder="••••••••" class="w-full sm:w-2/3 lg:w-1/2 bg-slate-50 border border-gray-200 rounded-xl md:rounded-2xl px-4 py-3 md:px-5 md:py-3.5 text-sm focus:bg-white focus:ring-4 focus:ring-rose-500/20 focus:border-rose-500 transition-all outline-none shadow-sm" dir="ltr">
                            </div>

                            <div class="border-t border-gray-100 pt-5 md:pt-6"></div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 md:gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">كلمة المرور الجديدة</label>
                                    <input type="password" name="password" required placeholder="••••••••" class="w-full bg-slate-50 border border-gray-200 rounded-xl md:rounded-2xl px-4 py-3 md:px-5 md:py-3.5 text-sm focus:bg-white focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none shadow-sm" dir="ltr">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">تأكيد كلمة المرور الجديدة</label>
                                    <input type="password" name="password_confirmation" required placeholder="••••••••" class="w-full bg-slate-50 border border-gray-200 rounded-xl md:rounded-2xl px-4 py-3 md:px-5 md:py-3.5 text-sm focus:bg-white focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none shadow-sm" dir="ltr">
                                </div>
                            </div>
                        </div>
                        <div class="px-5 md:px-8 py-4 md:py-5 bg-slate-50/50 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="w-full sm:w-auto px-6 md:px-8 py-3 md:py-3.5 rounded-xl md:rounded-2xl font-bold text-white bg-slate-800 hover:bg-black shadow-lg transition-all text-sm flex items-center justify-center gap-2">
                                <i class="fa-solid fa-key"></i> تحديث كلمة المرور
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        function disableSubmitButton(form) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if(submitBtn) {
                // Save original HTML in a data attribute
                if(!submitBtn.getAttribute('data-original-html')) {
                    submitBtn.setAttribute('data-original-html', submitBtn.innerHTML);
                }

                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جاري المعالجة...';
            }
            return true;
        }

        // Auto hide toasts
        setTimeout(() => {
            document.querySelectorAll('.animate-toast').forEach(t => t.remove());
        }, 5000);
    </script>

    <style>
        .animate-toast { animation: toastSlideIn 0.4s forwards; }
        @keyframes toastSlideIn {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>
@endsection
