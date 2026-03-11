<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>لوحة التحكم | MediNear Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0d9488',
                        primaryHover: '#0f766e',
                        accent: '#84cc16',
                        darkText: '#1e293b',
                    },
                    fontFamily: {
                        sans: ['Tajawal', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <link rel="stylesheet" href="{{ asset('Dashboard/Login/login.css') }}">

    <style>
        @keyframes slideInMobile {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInDesktop {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }

        .animate-flash {
            animation: slideInMobile 0.4s ease-out forwards;
        }

        @media (min-width: 768px) {
            .animate-flash {
                animation: slideInDesktop 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
            }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .kabsola { border-radius: 50px; position: absolute; opacity: 0.6; }
        .k-teal { background: #0d9488; }
        .k-lime { background: #84cc16; }
        .k-blue { background: #3b82f6; }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        .anim-float { animation: float 6s ease-in-out infinite; }
    </style>
</head>

<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4 overflow-x-hidden">

    <div class="fixed top-4 inset-x-4 md:inset-x-auto md:top-6 md:right-6 z-[100] flex flex-col gap-3 items-center md:items-end pointer-events-none">
        @if(session('success'))
            <div class="notification-item pointer-events-auto w-full max-w-[340px] bg-white border-r-4 border-emerald-500 shadow-2xl rounded-2xl p-4 flex items-center gap-4 animate-flash">
                <div class="bg-emerald-100 p-2.5 rounded-xl shrink-0">
                    <i class="fa-solid fa-check text-emerald-600"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">تم بنجاح</p>
                    <p class="text-sm font-extrabold text-darkText truncate">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-gray-300 hover:text-gray-500 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="notification-item pointer-events-auto w-full max-w-[340px] bg-white border-r-4 border-red-500 shadow-2xl rounded-2xl p-4 flex items-center gap-4 animate-flash">
                <div class="bg-red-100 p-2.5 rounded-xl shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">تنبيه نظام</p>
                    <p class="text-sm font-extrabold text-darkText leading-tight">
                        {{ session('error') ?? $errors->first() }}
                    </p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-gray-300 hover:text-gray-400 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
    </div>

    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="kabsola k-teal w-8 h-20 md:w-12 md:h-32 anim-float top-[10%] right-[10%] md:right-[15%]" style="animation-delay: 0s;"></div>
        <div class="kabsola k-lime w-6 h-16 md:w-10 md:h-24 anim-float bottom-[10%] right-[5%] md:right-[10%]" style="animation-delay: 1s;"></div>
        <div class="kabsola k-blue w-5 h-12 md:w-8 md:h-20 anim-float top-[40%] left-[5%]" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative z-10 w-full max-w-[95%] sm:max-w-md mx-auto">
        <div class="text-center mb-6 md:mb-8">
            <div class="w-16 h-16 md:w-20 md:h-20 bg-white rounded-2xl md:rounded-3xl shadow-xl flex items-center justify-center mx-auto mb-4 border border-teal-50 transform rotate-3">
                <i class="fa-solid fa-shield-halved text-3xl md:text-4xl text-primary -rotate-3"></i>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-darkText tracking-tight">لوحة <span class="text-primary">الإدارة</span></h1>
            <p class="text-[10px] md:text-[11px] font-bold text-gray-400 uppercase tracking-[0.1em] md:tracking-[0.2em] mt-1">MediNear Secure Portal</p>
        </div>

        <div class="glass-card rounded-[2rem] md:rounded-[2.5rem] p-6 sm:p-10 md:p-12 shadow-[0_20px_50px_rgba(0,0,0,0.1)]">
            <div class="text-center mb-8 md:mb-10">
                <h2 class="text-xl md:text-2xl font-bold text-darkText">دخول المسؤول</h2>
                <p class="text-xs md:text-sm text-gray-500 mt-2">يرجى إدخال بيانات الاعتماد للوصول</p>
            </div>

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4 md:space-y-6">
                @csrf

                <div class="relative group">
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full bg-slate-100/50 border-2 border-transparent focus:border-primary focus:bg-white rounded-xl md:rounded-2xl py-3.5 md:py-4 pr-11 md:pr-12 pl-4 outline-none font-bold text-sm transition-all placeholder:text-gray-400"
                        placeholder="البريد الإلكتروني">
                    <i class="fa-solid fa-envelope absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary transition-colors text-base md:text-lg"></i>
                </div>

                <div class="relative group">
                    <input type="password" name="password" id="password"
                        class="w-full bg-slate-100/50 border-2 border-transparent focus:border-primary focus:bg-white rounded-xl md:rounded-2xl py-3.5 md:py-4 pr-11 md:pr-12 pl-12 md:pl-14 outline-none font-bold text-sm transition-all placeholder:text-gray-400"
                        placeholder="كلمة المرور">
                    <i class="fa-solid fa-lock absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary transition-colors text-base md:text-lg"></i>
                    <button type="button" onclick="togglePassword()" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 hover:text-primary transition-colors">
                        <i class="fa-solid fa-eye text-base md:text-lg" id="eyeIcon"></i>
                    </button>
                </div>

                <div class="flex items-center justify-between px-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 md:w-5 md:h-5 rounded border-gray-300 text-primary focus:ring-primary accent-primary">
                        <span class="text-[11px] md:text-xs font-bold text-gray-500 group-hover:text-primary transition-colors">تذكرني</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-primaryHover text-white py-3.5 md:py-4.5 rounded-xl md:rounded-2xl font-black text-base md:text-lg flex justify-center items-center shadow-lg shadow-teal-900/20 active:scale-[0.98] transition-all gap-3 mt-2">
                    دخول النظام
                    <i class="fa-solid fa-arrow-left-long text-xs md:text-sm"></i>
                </button>
            </form>
        </div>

        <div class="text-center mt-8 md:mt-10">
            <a href="/" class="text-xs md:text-sm font-bold text-gray-400 hover:text-primary transition-all inline-flex items-center gap-2 group">
                <i class="fa-solid fa-house group-hover:-translate-y-0.5 transition-transform"></i>
                العودة للرئيسية
            </a>
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        document.querySelectorAll('.notification-item').forEach(item => {
            setTimeout(() => {
                item.style.opacity = '0';
                item.style.transform = window.innerWidth > 768 ? 'translateX(100%)' : 'translateY(-20px)';
                item.style.transition = 'all 0.6s ease-in';
                setTimeout(() => item.remove(), 600);
            }, 5000);
        });
    </script>
</body>
</html>
