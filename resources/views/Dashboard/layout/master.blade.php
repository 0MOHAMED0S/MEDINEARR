<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>لوحة التحكم | MediNear</title>

    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0d9488',
                        primaryDark: '#0f766e',
                        accent: '#84cc16',
                        darkText: '#1e293b',
                        bgLight: '#f8fafc'
                    },
                    fontFamily: { sans: ['Tajawal', 'sans-serif'] },
                    screens: { 'xs': '375px' }
                }
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f8fafc;
            -webkit-tap-highlight-color: transparent;
            scroll-behavior: smooth;
        }

        .sidebar-gradient {
            background: linear-gradient(180deg, #0d9488 0%, #0f766e 100%);
        }

        /* تحسين استجابة القائمة الجانبية */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(100%);
                width: 280px; /* حجم ثابت ومناسب للموبايل */
            }
            .sidebar.active {
                transform: translateX(0);
                box-shadow: -10px 0 30px rgba(13, 148, 136, 0.15);
            }
        }

        /* تخصيص السكرول بار ليناسب التصميم */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #0d9488; border-radius: 10px; }

        /* أنيميشن لرسائل التنبيه */
        @keyframes toastIn {
            from { opacity: 0; transform: translateY(-20px) scale(0.9); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .animate-toast { animation: toastIn 0.3s ease-out forwards; }
    </style>
</head>

<body class="overflow-x-hidden font-sans antialiased text-slate-900">

    <div class="fixed top-4 left-4 right-4 md:left-auto md:right-6 md:top-6 z-[9999] flex flex-col gap-3 pointer-events-none">
        @if(session('success'))
            <div class="animate-toast pointer-events-auto bg-white border-r-4 border-emerald-500 shadow-xl rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm ml-auto">
                <div class="bg-emerald-100 p-2 rounded-xl text-emerald-600">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">تمت العملية</p>
                    <p class="text-sm font-black text-slate-800 leading-tight">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-slate-300 hover:text-slate-500 transition-colors"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="animate-toast pointer-events-auto bg-white border-r-4 border-rose-500 shadow-xl rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm ml-auto">
                <div class="bg-rose-100 p-2 rounded-xl text-rose-600">
                    <i class="fa-solid fa-circle-exclamation text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">خطأ في النظام</p>
                    <p class="text-sm font-black text-slate-800 leading-tight">
                        {{ session('error') ?? $errors->first() }}
                    </p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-slate-300 hover:text-slate-500 transition-colors"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif
    </div>

    <div id="overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 hidden lg:hidden transition-all duration-300"></div>

    @include('dashboard.layout.components.sidebar')

    <main class="lg:mr-72 min-h-screen flex flex-col transition-all duration-300 ease-in-out">

        @include('dashboard.layout.components.header')

        <div class="p-3 xs:p-4 md:p-6 lg:p-10 flex-1 w-full max-w-[1600px] mx-auto">
            @yield('content')
        </div>

        <footer class="px-6 py-4 text-center">
            <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex flex-col md:flex-row items-center justify-between gap-2">
                <p class="text-[11px] md:text-xs font-bold text-slate-400">
                    حقوق الطبع والنشر &copy; 2024 <span class="text-primary font-black">MediNear</span>.
                </p>
                <div class="flex items-center gap-4">
                    <a href="#" class="text-[10px] font-bold text-slate-400 hover:text-primary transition-colors">الدعم الفني</a>
                    <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                    <a href="#" class="text-[10px] font-bold text-slate-400 hover:text-primary transition-colors">سياسة الخصوصية</a>
                </div>
            </div>
        </footer>
    </main>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        // إعدادات SweetAlert العالمية (للاستخدام داخل صفحات الـ CRUD لاحقاً)
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-start',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // وظيفة التحكم في القائمة الجانبية
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }

        // إغلاق التنبيهات تلقائياً بعد 6 ثوانٍ
        document.querySelectorAll('.animate-toast').forEach(toast => {
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-10px)';
                toast.style.transition = '0.5s';
                setTimeout(() => toast.remove(), 500);
            }, 6000);
        });
    </script>
</body>

</html>
