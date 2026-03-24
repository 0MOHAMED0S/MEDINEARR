<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'الرئيسية') | لوحة تحكم الصيدلية | MediNear</title>

    <meta name="title" content="لوحة تحكم الصيدلية | MediNear">
    <meta name="description"
        content="النظام الإداري الخاص بالصيدلية لإدارة المخزون، الأدوية، والطلبات الطبية الواردة عبر منصة MediNear باحترافية.">
    <meta name="author" content="MediNear Pharmacy Team">

    <meta name="theme-color" content="#0d9488">

    <link rel="icon" type="image/x-icon" href="{{ asset('Dashboard/images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon-16x16.png') }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/apple-touch-icon.png') }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="لوحة تحكم الصيدلية | MediNear">
    <meta property="og:description" content="النظام الإداري الشامل للصيدليات المشتركة في منصة MediNear الطبية.">
    <meta property="og:image" content="{{ asset('Dashboard/images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap"
        rel="stylesheet">
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
                    fontFamily: {
                        sans: ['Tajawal', 'sans-serif']
                    },
                    screens: {
                        'xs': '375px'
                    }
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

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(100%);
                width: 280px;
            }

            .sidebar.active {
                transform: translateX(0);
                box-shadow: -10px 0 30px rgba(13, 148, 136, 0.15);
            }
        }

        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #0d9488;
            border-radius: 10px;
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-toast {
            animation: toastIn 0.3s ease-out forwards;
        }

        #top-loader {
            position: fixed;
            top: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(to left, #84cc16, #0d9488);
            z-index: 10000;
            width: 0%;
            transition: width 0.4s cubic-bezier(0.1, 0.7, 0.1, 1);
            box-shadow: 0 0 10px rgba(13, 148, 136, 0.4);
            pointer-events: none;
        }

        #page-content-wrapper {
            transition: opacity 0.2s ease-out;
        }

        .page-exit {
            opacity: 0.5 !important;
        }
    </style>
</head>

<body class="overflow-x-hidden font-sans antialiased text-slate-900">

    <div id="top-loader"></div>

    <div
        class="fixed top-4 left-4 right-4 md:left-auto md:right-6 md:top-6 z-[9999] flex flex-col gap-3 pointer-events-none text-right">
        @if (session('success'))
            <div
                class="animate-toast pointer-events-auto bg-white border-r-4 border-emerald-500 shadow-xl rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm ml-auto">
                <div class="bg-emerald-100 p-2 rounded-xl text-emerald-600 shrink-0">
                    <i class="fa-solid fa-notes-medical text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">تحديث الصيدلية</p>
                    <p class="text-sm font-black text-slate-800 leading-tight">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()"
                    class="text-slate-300 hover:text-slate-500 transition-colors shrink-0"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        @if (session('error') || $errors->any())
            <div
                class="animate-toast pointer-events-auto bg-white border-r-4 border-rose-500 shadow-xl rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm ml-auto">
                <div class="bg-rose-100 p-2 rounded-xl text-rose-600 shrink-0">
                    <i class="fa-solid fa-circle-exclamation text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">خطأ في النظام</p>
                    <p class="text-sm font-black text-slate-800 leading-tight">
                        {{ session('error') ?? $errors->first() }}
                    </p>
                </div>
                <button onclick="this.parentElement.remove()"
                    class="text-slate-300 hover:text-slate-500 transition-colors shrink-0"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
        @endif
    </div>

    <div id="overlay" onclick="toggleSidebar()"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 hidden lg:hidden transition-all duration-300"></div>

    @include('pharmacy.layout.components.sidebar')

    <main class="lg:mr-72 min-h-screen flex flex-col transition-all duration-300 ease-in-out">

        @include('pharmacy.layout.components.header')

        <div id="page-content-wrapper" class="p-3 xs:p-4 md:p-6 lg:p-10 flex-1 w-full max-w-[1600px] mx-auto">
            @yield('content')
        </div>

        <footer class="px-6 py-4 text-center mt-auto">
            <div
                class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex flex-col md:flex-row items-center justify-between gap-2">
                <p class="text-[11px] md:text-xs font-bold text-slate-400">
                    نظام إدارة الصيدلية &copy; 2024 <span class="text-primary font-black">MediNear</span>.
                </p>
                <div class="flex items-center gap-4">
                    <a href="#"
                        class="text-[10px] font-bold text-slate-400 hover:text-primary transition-colors">دعم الصيدليات</a>
                    <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                    <a href="#"
                        class="text-[10px] font-bold text-slate-400 hover:text-primary transition-colors">دليل الاستخدام</a>
                </div>
            </div>
        </footer>
    </main>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        // إعدادات SweetAlert العالمية للتطبيقات الصيدلانية
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

        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            if (!modal) return;

            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');

                if (!document.querySelector('div[id*="Modal"]:not(.hidden)')) {
                    document.body.classList.remove('overflow-hidden');
                }
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }

        document.querySelectorAll('.animate-toast').forEach(toast => {
            setTimeout(() => {
                if (toast.parentElement) toast.remove();
            }, 6000);
        });

        document.addEventListener('DOMContentLoaded', () => {
            const loader = document.getElementById('top-loader');
            const wrapper = document.getElementById('page-content-wrapper');

            document.addEventListener('click', (e) => {
                const link = e.target.closest('a');
                if (!link) return;

                const href = link.getAttribute('href');
                const isInternal = href && (href.startsWith('/') || href.includes(window.location.hostname));
                const isSpecial = link.getAttribute('target') === '_blank' ||
                    href.startsWith('#') ||
                    link.hasAttribute('onclick') ||
                    link.closest('[onclick]');

                if (isInternal && !isSpecial) {
                    if (e.ctrlKey || e.metaKey || e.shiftKey) return;
                    if (href === window.location.href || href === window.location.pathname) return;

                    loader.style.width = '80%';
                    wrapper.classList.add('page-exit');
                }
            });
        });

        window.addEventListener('pageshow', () => {
            const loader = document.getElementById('top-loader');
            const wrapper = document.getElementById('page-content-wrapper');

            wrapper.classList.remove('page-exit');
            loader.style.width = '100%';

            setTimeout(() => {
                loader.style.opacity = '0';
                setTimeout(() => {
                    loader.style.width = '0%';
                    loader.style.opacity = '1';
                }, 400);
            }, 300);
        });
    </script>
</body>

</html>
