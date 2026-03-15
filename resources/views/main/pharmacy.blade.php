<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'تسجيل صيدلية') | MediNear - كن شريكاً للنجاح</title>

    <meta name="title" content="تسجيل صيدلية في منصة MediNear">
    <meta name="description"
        content="انضم الآن إلى أكبر شبكة صيدليات عبر منصة MediNear. سجل صيدليتك، ضاعف مبيعاتك، وابدأ في استقبال طلبات الأدوية من آلاف العملاء في منطقتك بسهولة.">
    <meta name="keywords"
        content="تسجيل صيدلية, انضم كصيدلية, زيادة مبيعات الصيدلية, شركاء MediNear, منصة صيدليات, بيع أدوية أونلاين, إدارة الطلبات الطبية">
    <meta name="author" content="MediNear Team">

    <meta name="theme-color" content="#0d9488">

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon-16x16.png') }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/apple-touch-icon.png') }}">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="انضم كصيدلية مع MediNear - ضاعف مبيعاتك">
    <meta property="og:description"
        content="سجل صيدليتك الآن في منصة MediNear وابدأ في الوصول لعملاء أكثر في منطقتك بكل سهولة وموثوقية. انضم لشركاء النجاح!">
    <meta property="og:image" content="{{ asset('assets/images/logo.png') }}">
    <meta property="og:site_name" content="MediNear Partners">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="انضم كصيدلية مع MediNear - ضاعف مبيعاتك">
    <meta property="twitter:description"
        content="سجل صيدليتك الآن في منصة MediNear وابدأ في الوصول لعملاء أكثر في منطقتك بكل سهولة وموثوقية.">
    <meta property="twitter:image" content="{{ asset('assets/images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Tajawal:wght@400;500;700;800;900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0d9488', // Teal 600
                        primaryHover: '#0f766e', // Teal 700
                        accent: '#84cc16', // Lime 500
                        secondary: '#f0fdfa', // Teal 50
                        darkText: '#1e293b', // Slate 800
                        lightText: '#64748b', // Slate 500
                        footerBg: '#0f172a' // Slate 900
                    },
                    animation: {
                        'spin-slow': 'spin 8s linear infinite',
                        'float-slow': 'float 8s ease-in-out infinite',
                        'float-delayed': 'float 8s ease-in-out 4s infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0) rotate(0deg)'
                            },
                            '50%': {
                                transform: 'translateY(-20px) rotate(5deg)'
                            },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --main-font: 'Tajawal', sans-serif;
            --grad-start: #0d9488;
            --grad-end: #65a30d;
        }

        html[lang="en"] {
            --main-font: 'Inter', sans-serif;
        }

        body {
            font-family: var(--main-font);
            background-color: #f8fafc;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        /* Gradients */
        .bg-gradient-custom {
            background: linear-gradient(135deg, var(--grad-start) 0%, var(--grad-end) 100%);
        }

        .text-gradient {
            background: linear-gradient(to right, var(--grad-start), var(--grad-end));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* --- Fast & Creative Interactive Cards --- */
        .card-hover {
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .card-hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: -150%;
            width: 50%;
            height: 100%;
            background: linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.7) 50%, rgba(255, 255, 255, 0) 100%);
            transform: skewX(-25deg);
            transition: left 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 1;
            pointer-events: none;
        }

        @media (hover: hover) {
            .card-hover:hover::before {
                left: 200%;
            }

            .card-hover:hover {
                transform: translateY(-10px);
                box-shadow: 0 30px 60px -15px rgba(13, 148, 136, 0.15), 0 0 0 1px rgba(13, 148, 136, 0.1) inset;
                border-color: transparent;
                z-index: 10;
            }
        }

        .card-hover>* {
            position: relative;
            z-index: 2;
        }

        /* Continuous Shine for Submit Button */
        .btn-shine-effect {
            position: relative;
            overflow: hidden;
        }

        .btn-shine-effect::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 20%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.4), transparent);
            transform: skewX(-20deg);
            animation: shine 4s infinite;
        }

        @keyframes shine {

            0%,
            70% {
                left: -100%;
            }

            100% {
                left: 200%;
            }
        }

        /* Form Inputs */
        .form-input,
        .form-textarea {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid transparent;
            border-radius: 1rem;
            outline: none;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            font-family: inherit;
            background-color: #f1f5f9;
            color: #1e293b;
        }

        .form-input:focus,
        .form-textarea:focus {
            background-color: #ffffff;
            border-color: var(--grad-start);
            box-shadow: 0 0 0 5px rgba(13, 148, 136, 0.1);
            transform: translateY(-2px);
        }

        .form-input.error-input {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }

        .form-input.error-input:focus {
            box-shadow: 0 0 0 5px rgba(239, 68, 68, 0.1) !important;
        }

        .form-input::placeholder,
        .form-textarea::placeholder {
            color: #94a3b8;
        }

        /* Checkbox customization */
        input[type="checkbox"],
        input[type="radio"] {
            transition: all 0.3s ease;
        }

        input[type="checkbox"]:checked,
        input[type="radio"]:checked {
            transform: scale(1.1);
            box-shadow: 0 0 10px rgba(13, 148, 136, 0.3);
        }

        .leaflet-container {
            z-index: 10 !important;
            border-radius: 1.5rem;
        }

        .map-styled {
            filter: contrast(1.05) saturate(1.2);
        }

        /* Scroll Reveal */
        .reveal {
            opacity: 0;
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal-bottom {
            transform: translateY(40px);
        }

        .reveal-scale {
            transform: scale(0.9);
        }

        .reveal.active {
            opacity: 1;
            transform: translate(0, 0) scale(1);
        }

        /* Creative Backgrounds */
        .bg-grid-pattern {
            background-image: linear-gradient(to right, rgba(13, 148, 136, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(13, 148, 136, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        @keyframes pulse-glow {
            0% {
                box-shadow: 0 0 0 0 rgba(13, 148, 136, 0.6);
                transform: scale(1);
            }

            50% {
                box-shadow: 0 0 0 15px rgba(13, 148, 136, 0);
                transform: scale(1.05);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(13, 148, 136, 0);
                transform: scale(1);
            }
        }

        .animate-pulse-glow {
            animation: pulse-glow 2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }

        /* Custom Toast Animation for Top Right */
        @keyframes slideInRight {
            0% {
                opacity: 0;
                transform: translateX(100%);
            }

            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .toast-slide-in {
            animation: slideInRight 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>

<body class="text-darkText antialiased flex flex-col min-h-screen relative">

    <div id="toast-container"
        class="fixed top-24 right-4 left-4 md:left-auto md:right-8 z-[100000] flex flex-col gap-3 pointer-events-none md:w-[400px]">
        @if (session('success'))
            <div
                class="pointer-events-auto bg-white border-2 border-green-100 shadow-2xl rounded-2xl p-4 flex items-center gap-4 toast-slide-in w-full">
                <div
                    class="w-12 h-12 bg-green-50 text-green-500 rounded-full flex items-center justify-center text-2xl shrink-0 shadow-inner">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div class="flex-grow">
                    <h4 class="font-bold text-gray-800 text-lg" data-i18n="toastSuccessTitle">نجاح!</h4>
                    <p class="text-sm text-gray-500 font-medium">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()"
                    class="text-gray-400 hover:text-red-500 transition-colors p-2"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        @if ($errors->any())
            <div
                class="pointer-events-auto bg-white border-2 border-red-100 shadow-2xl rounded-2xl p-4 flex items-center gap-4 toast-slide-in w-full">
                <div
                    class="w-12 h-12 bg-red-50 text-red-500 rounded-full flex items-center justify-center text-2xl shrink-0 shadow-inner">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="flex-grow">
                    <h4 class="font-bold text-gray-800 text-lg" data-i18n="toastErrorTitle">خطأ في الإدخال!</h4>
                    <p class="text-sm text-gray-500 font-medium" data-i18n="toastErrorDesc">يرجى مراجعة الحقول باللون
                        الأحمر أدناه.</p>
                </div>
                <button onclick="this.parentElement.remove()"
                    class="text-gray-400 hover:text-red-500 transition-colors p-2"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
        @endif
    </div>

    <div id="scroll-progress-bar"
        class="fixed top-0 start-0 h-[4px] bg-gradient-custom z-[99999] w-0 transition-all duration-75 ease-out"></div>

    <div class="fixed w-full top-0 z-50 transition-all duration-300 shadow-sm" id="header-wrapper">
        <header class="w-full bg-white/95 backdrop-blur-lg border-b border-gray-100 transition-all duration-300"
            id="navbar">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 md:h-20 transition-all duration-300"
                    id="navbar-inner">
                    <a href="{{ url('/') }}"
                        class="flex-shrink-0 flex items-center gap-2 md:gap-3 cursor-pointer group">
                        <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="w-9 h-9 md:w-11 md:h-11 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                            <defs>
                                <linearGradient id="logoGrad" x1="80%" y1="0%" x2="20%"
                                    y2="100%">
                                    <stop offset="0%" stop-color="#84cc16" />
                                    <stop offset="100%" stop-color="#14b8a6" />
                                </linearGradient>
                            </defs>
                            <circle cx="50" cy="50" r="46" stroke="url(#logoGrad)" stroke-width="4" />
                            <path
                                d="M50 15C34.536 15 22 27.536 22 43C22 62.5 50 85 50 85C50 85 78 62.5 78 43C78 27.536 65.464 15 50 15Z"
                                fill="url(#logoGrad)" />
                            <path d="M45 28H55V37H64V47H55V56H45V47H36V37H45V28Z" fill="white" />
                            <g transform="translate(38, 65) rotate(-50)">
                                <rect x="-14" y="-26" width="28" height="52" rx="14" fill="white" />
                                <rect x="-11" y="-23" width="22" height="46" rx="11" fill="white"
                                    stroke="url(#logoGrad)" stroke-width="2" />
                                <path d="M-10 0 H10 V12 A10 10 0 0 1 -10 12 Z" fill="url(#logoGrad)" />
                            </g>
                        </svg>
                        <span class="font-black text-xl md:text-2xl text-darkText tracking-tight">Medi<span
                                class="text-primary">Near</span></span>
                    </a>

                    <nav class="hidden lg:flex items-center gap-6 xl:gap-8 font-bold text-sm tracking-wide">
                        <a href="{{ url('/') }}"
                            class="text-gray-500 hover:text-primary transition relative after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-primary after:scale-x-0 hover:after:scale-x-100 after:transition-transform"
                            data-i18n="navHome">الرئيسية</a>
                        <a href="{{ url('/') }}#features"
                            class="text-gray-500 hover:text-primary transition relative after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-primary after:scale-x-0 hover:after:scale-x-100 after:transition-transform"
                            data-i18n="navFeatures">المميزات</a>
                        <a href="{{ url('/') }}#how-it-works"
                            class="text-gray-500 hover:text-primary transition relative after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-primary after:scale-x-0 hover:after:scale-x-100 after:transition-transform"
                            data-i18n="navHow">كيف يعمل</a>
                        <a href="{{ url('/') }}#reviews"
                            class="text-gray-500 hover:text-primary transition relative after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-primary after:scale-x-0 hover:after:scale-x-100 after:transition-transform"
                            data-i18n="navReviews">آراء العملاء</a>
                        <a href="{{ url('/') }}#contact"
                            class="text-gray-500 hover:text-primary transition relative after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-primary after:scale-x-0 hover:after:scale-x-100 after:transition-transform"
                            data-i18n="navContact">اتصل بنا</a>
                        <a href="{{ route('pharmacy.Application.index') ?? '#' }}"
                            class="text-primary transition relative after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-primary after:scale-x-100 after:transition-transform"
                            data-i18n="navPharmacies">للصيدليات</a>
                    </nav>

                    <div class="hidden lg:flex items-center gap-6">
                        <button id="lang-switch-desktop" onclick="toggleLanguage()"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-50 text-gray-500 hover:text-primary hover:bg-gray-100 font-bold text-sm transition-all focus:outline-none">
                            <i class="fa-solid fa-globe animate-spin-slow text-primary/80"></i>
                            <span id="lang-text-desktop">English</span>
                        </button>
                        @auth
                            <div class="relative group">
                                <button
                                    class="flex items-center gap-3 bg-gray-50 hover:bg-white px-3 py-1.5 rounded-lg border border-gray-100 transition-colors shadow-sm focus:outline-none">
                                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=0d9488&color=fff' }}"
                                        alt="Profile" class="w-8 h-8 rounded-full border border-gray-200">
                                    <div class="text-start hidden md:block">
                                        <div class="font-bold text-sm text-darkText leading-none">
                                            {{ auth()->user()->name }}</div>
                                        <div class="text-[10px] text-gray-500 mt-1">
                                            {{ auth()->user()->role === 'admin' ? 'مدير النظام' : auth()->user()->email }}
                                        </div>
                                    </div>
                                    <i
                                        class="fa-solid fa-chevron-down text-gray-400 text-xs ml-1 transition-transform group-hover:rotate-180"></i>
                                </button>
                                <div
                                    class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-left -translate-y-2 group-hover:translate-y-0 z-50">
                                    <div class="p-2 text-right">
                                        @if (Auth::user()->role === 'admin')
                                            <a href="{{ route('admin.dashboard') }}"
                                                class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                                                <i class="fa-solid fa-gauge w-4"></i>
                                                <span data-i18n="navDashboard">لوحة التحكم</span>
                                            </a>
                                        @endif
                                        <hr class="my-1 border-gray-50">
                                        <form
                                            action="{{ auth()->user()->role === 'admin' ? route('admin.logout') : route('pharmacy.logout') }}"
                                            method="POST" class="w-full">
                                            @csrf
                                            <button type="submit"
                                                class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                <i class="fa-solid fa-arrow-right-from-bracket w-4"></i>
                                                <span data-i18n="navLogout">تسجيل الخروج</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="#register-form"
                                class="bg-gradient-custom hover:opacity-90 text-white px-6 py-2.5 rounded-lg font-bold text-sm transition-all shadow-[0_4px_15px_rgba(13,148,136,0.4)] hover:shadow-[0_8px_25px_rgba(13,148,136,0.5)] hover:-translate-y-0.5 flex items-center gap-2">
                                <span data-i18n="btnRegPharmacy">سجل كصيدلية</span>
                            </a>
                        @endauth
                    </div>

                    <div class="lg:hidden flex items-center gap-3">
                        <button id="lang-switch-mobile" onclick="toggleLanguage()"
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-50 text-gray-500 hover:text-primary font-bold text-sm transition-colors focus:outline-none">
                            <span class="text-xs uppercase font-black" id="lang-text-mobile-inner">EN</span>
                        </button>
                        <button id="mobile-menu-btn"
                            class="w-10 h-10 flex items-center justify-center bg-gray-50 hover:bg-gray-100 rounded-full text-darkText hover:text-primary transition-all duration-300 focus:outline-none">
                            <i class="fa-solid fa-bars text-xl transition-transform duration-300"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div id="mobile-menu"
                class="hidden lg:hidden bg-white/95 backdrop-blur-2xl border-x border-b border-gray-100/50 absolute w-full shadow-[0_20px_40px_rgba(0,0,0,0.08)] transition-all origin-top rounded-b-[2rem] overflow-y-auto overscroll-contain max-h-[85vh] z-[49]">
                <div class="px-5 pt-5 pb-8 space-y-2 flex flex-col text-center">
                    <a href="{{ url('/') }}" onclick="closeMobileMenu();"
                        class="block w-full px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl font-medium transition-colors"
                        data-i18n="navHome">الرئيسية</a>
                    <a href="{{ url('/') }}#features" onclick="closeMobileMenu();"
                        class="block w-full px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl font-medium transition-colors"
                        data-i18n="navFeatures">المميزات</a>
                    <a href="{{ url('/') }}#how-it-works" onclick="closeMobileMenu();"
                        class="block w-full px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl font-medium transition-colors"
                        data-i18n="navHow">كيف يعمل</a>
                    <a href="{{ url('/') }}#reviews" onclick="closeMobileMenu();"
                        class="block w-full px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl font-medium transition-colors"
                        data-i18n="navReviews">آراء العملاء</a>
                    <a href="{{ url('/') }}#contact" onclick="closeMobileMenu();"
                        class="block w-full px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl font-medium transition-colors"
                        data-i18n="navContact">اتصل بنا</a>
                    <a href="{{ route('pharmacy.Application.index') ?? '#' }}" onclick="closeMobileMenu();"
                        class="block w-full px-4 py-3 text-primary font-bold bg-secondary rounded-xl transition-colors hover:bg-teal-100"
                        data-i18n="navPharmacies">للصيدليات</a>
                    <hr class="my-4 border-gray-100">

                    @auth
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" data-i18n="navDashboard" onclick="closeMobileMenu();"
                                class="block w-full px-4 py-3 text-primary font-bold bg-teal-50 rounded-xl transition-colors">لوحة
                                التحكم</a>
                        @endif

                        <div
                            class="mt-6 flex flex-col items-center gap-2 py-4 bg-gray-50/80 rounded-2xl border border-gray-100 shadow-sm">
                            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=0d9488&color=fff' }}"
                                alt="Profile" class="w-14 h-14 rounded-full border-2 border-primary shadow-md">
                            <div class="text-center mt-2">
                                <div class="font-bold text-lg text-darkText">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ auth()->user()->role === 'admin' ? 'مدير النظام' : auth()->user()->email }}
                                </div>
                            </div>
                            <div class="w-full px-4 mt-4">
                                <form
                                    action="{{ auth()->user()->role === 'admin' ? route('admin.logout') : route('pharmacy.logout') }}"
                                    method="POST" class="w-full">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-red-500 bg-red-50 hover:bg-red-100 px-4 py-3 rounded-xl font-bold transition-colors flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i> <span data-i18n="navLogout">تسجيل الخروج</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="#register-form" onclick="closeMobileMenu();"
                            class="block w-full bg-gradient-custom text-white px-6 py-4 rounded-xl font-bold shadow-md"
                            data-i18n="btnRegPharmacy">سجل كصيدلية</a>
                    @endauth
                </div>
            </div>
        </header>
    </div>

    <div class="flex-grow pt-20 md:pt-28 relative">

        <section
            class="py-12 md:py-20 px-4 sm:px-6 lg:px-8 container mx-auto text-center reveal reveal-bottom relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern pointer-events-none"></div>
            <div class="absolute top-0 right-10 w-48 h-48 bg-primary/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 left-10 w-64 h-64 bg-accent/10 rounded-full blur-3xl animate-pulse"
                style="animation-delay: 2s;"></div>

            <i
                class="fa-solid fa-prescription-bottle-medical absolute top-[10%] right-[15%] text-6xl text-primary/10 animate-float-slow -rotate-12 hidden md:block"></i>
            <i
                class="fa-solid fa-staff-snake absolute bottom-[20%] left-[10%] text-8xl text-accent/10 animate-float-delayed rotate-12 hidden lg:block"></i>

            <div class="relative z-10 max-w-4xl mx-auto">
                <div
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-white text-primary text-sm font-black mb-8 shadow-sm border border-primary/20 animate-float">
                    <i class="fa-solid fa-handshake text-lg"></i> <span data-i18n="pharmTag">للصيدليات</span>
                </div>
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black text-darkText mb-8 leading-tight drop-shadow-sm tracking-tight"
                    data-i18n="pharmHeroTitle">
                    انضم إلى شبكة MediNear وقم بتوسيع نطاق عملك
                </h1>
                <p class="text-gray-500 text-base md:text-xl font-medium max-w-2xl mx-auto px-4 md:px-0 leading-relaxed"
                    data-i18n="pharmHeroDesc">
                    سجل صيدليتك الآن وابدأ في استقبال آلاف الطلبات من المرضى في منطقتك بكل سهولة وبأدوات إدارة متطورة.
                </p>
                <div class="mt-10">
                    <a href="#register-form"
                        class="inline-flex items-center justify-center gap-3 bg-darkText hover:bg-black text-white px-8 py-4 rounded-2xl font-bold text-lg transition-all shadow-xl hover:-translate-y-1">
                        <span data-i18n="btnStartNow">ابدأ التسجيل الآن</span>
                        <i class="fa-solid fa-arrow-down animate-bounce"></i>
                    </a>
                </div>
            </div>
        </section>

        <section class="pb-16 md:pb-24 px-4 sm:px-6 lg:px-8 container mx-auto relative z-10">
            <div class="flex flex-wrap justify-center gap-4 sm:gap-6">
                <div
                    class="bg-white p-4 md:p-6 lg:p-8 rounded-[1.5rem] sm:rounded-[2rem] shadow-[0_10px_30px_rgba(0,0,0,0.03)] border border-gray-100/50 text-center w-[calc(50%-8px)] sm:w-[calc(50%-12px)] lg:w-[calc(20%-20px)] card-hover group reveal reveal-scale relative backdrop-blur-sm bg-white/90">
                    <div
                        class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 bg-gradient-to-br from-secondary to-teal-50 text-primary rounded-2xl sm:rounded-3xl flex items-center justify-center text-xl sm:text-2xl md:text-3xl mx-auto mb-3 sm:mb-5 transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-12 group-hover:shadow-[0_10px_25px_rgba(13,148,136,0.2)]">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                    </div>
                    <h3 class="font-black text-xs sm:text-sm md:text-base text-darkText tracking-wide" data-i18n="pharmBen1">
                        زيادة المبيعات</h3>
                </div>
                <div class="bg-white p-4 md:p-6 lg:p-8 rounded-[1.5rem] sm:rounded-[2rem] shadow-[0_10px_30px_rgba(0,0,0,0.03)] border border-gray-100/50 text-center w-[calc(50%-8px)] sm:w-[calc(50%-12px)] lg:w-[calc(20%-20px)] card-hover group reveal reveal-scale relative backdrop-blur-sm bg-white/90"
                    style="transition-delay: 0.1s;">
                    <div
                        class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 bg-gradient-to-br from-secondary to-teal-50 text-primary rounded-2xl sm:rounded-3xl flex items-center justify-center text-xl sm:text-2xl md:text-3xl mx-auto mb-3 sm:mb-5 transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-12 group-hover:shadow-[0_10px_25px_rgba(13,148,136,0.2)]">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h3 class="font-black text-xs sm:text-sm md:text-base text-darkText tracking-wide" data-i18n="pharmBen2">
                        الوصول لعملاء جدد</h3>
                </div>
                <div class="bg-white p-4 md:p-6 lg:p-8 rounded-[1.5rem] sm:rounded-[2rem] shadow-[0_10px_30px_rgba(0,0,0,0.03)] border border-gray-100/50 text-center w-[calc(50%-8px)] sm:w-[calc(50%-12px)] lg:w-[calc(20%-20px)] card-hover group reveal reveal-scale relative backdrop-blur-sm bg-white/90"
                    style="transition-delay: 0.2s;">
                    <div
                        class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 bg-gradient-to-br from-secondary to-teal-50 text-primary rounded-2xl sm:rounded-3xl flex items-center justify-center text-xl sm:text-2xl md:text-3xl mx-auto mb-3 sm:mb-5 transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-12 group-hover:shadow-[0_10px_25px_rgba(13,148,136,0.2)]">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    <h3 class="font-black text-xs sm:text-sm md:text-base text-darkText tracking-wide" data-i18n="pharmBen3">
                        إدارة الطلبات بسهولة</h3>
                </div>
                <div class="bg-white p-4 md:p-6 lg:p-8 rounded-[1.5rem] sm:rounded-[2rem] shadow-[0_10px_30px_rgba(0,0,0,0.03)] border border-gray-100/50 text-center w-[calc(50%-8px)] sm:w-[calc(50%-12px)] lg:w-[calc(20%-20px)] card-hover group reveal reveal-scale relative backdrop-blur-sm bg-white/90"
                    style="transition-delay: 0.3s;">
                    <div
                        class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 bg-gradient-to-br from-secondary to-teal-50 text-primary rounded-2xl sm:rounded-3xl flex items-center justify-center text-xl sm:text-2xl md:text-3xl mx-auto mb-3 sm:mb-5 transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-12 group-hover:shadow-[0_10px_25px_rgba(13,148,136,0.2)]">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <h3 class="font-black text-xs sm:text-sm md:text-base text-darkText tracking-wide" data-i18n="pharmBen4">
                        الوصول للوحة التحليلات</h3>
                </div>
                <div class="bg-white p-4 md:p-6 lg:p-8 rounded-[1.5rem] sm:rounded-[2rem] shadow-[0_10px_30px_rgba(0,0,0,0.03)] border border-gray-100/50 text-center w-[calc(50%-8px)] sm:w-[calc(50%-12px)] lg:w-[calc(20%-20px)] card-hover group reveal reveal-scale relative backdrop-blur-sm bg-white/90"
                    style="transition-delay: 0.4s;">
                    <div
                        class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 bg-gradient-to-br from-secondary to-teal-50 text-primary rounded-2xl sm:rounded-3xl flex items-center justify-center text-xl sm:text-2xl md:text-3xl mx-auto mb-3 sm:mb-5 transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-12 group-hover:shadow-[0_10px_25px_rgba(13,148,136,0.2)]">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                    <h3 class="font-black text-xs sm:text-sm md:text-base text-darkText tracking-wide" data-i18n="pharmBen5">
                        عملية تسجيل بسيطة</h3>
                </div>
            </div>
        </section>

        <section id="register-form"
            class="pb-16 md:pb-24 px-4 sm:px-6 lg:px-8 container mx-auto reveal reveal-bottom relative z-10">
            @if (isset($activeApplication) && $activeApplication)
                <div
                    class="bg-white rounded-[2rem] sm:rounded-[2.5rem] shadow-[0_20px_60px_rgba(0,0,0,0.05)] border border-gray-100 p-6 sm:p-8 md:p-12 max-w-5xl mx-auto relative overflow-hidden">
                    <div
                        class="absolute top-0 left-0 w-full h-2 md:h-3 {{ $activeApplication->status == 'approved' ? 'bg-emerald-500' : 'bg-amber-500' }}">
                    </div>

                    <div class="mb-10 text-center mt-4">
                        @if ($activeApplication->status == 'under_review')
                            <div
                                class="w-24 h-24 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 animate-pulse shadow-inner">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </div>
                            <h2 class="text-2xl md:text-4xl font-black text-darkText mb-4"
                                data-i18n="statusReviewTitle">طلبك قيد المراجعة حالياً</h2>
                            <p class="text-sm md:text-base text-gray-500 font-medium" data-i18n="statusReviewDesc">
                                نشكرك على انضمامك لـ MediNear. يقوم فريقنا الآن بمراجعة بيانات صيدليتك وسيتم الرد عليك
                                في أقرب وقت.</p>
                        @else
                            <div
                                class="w-24 h-24 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 shadow-inner">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <h2 class="text-2xl md:text-4xl font-black text-darkText mb-4"
                                data-i18n="statusApproveTitle">تهانينا! تم قبول صيدليتك</h2>
                            <p class="text-sm md:text-base text-gray-500 font-medium" data-i18n="statusApproveDesc">
                                يمكنك الآن البدء في استخدام لوحة التحكم الخاصة بالصيدلية.</p>
                            <a href="{{ route('admin.dashboard') }}"
                                class="inline-block mt-8 bg-primary text-white px-8 py-3 rounded-xl font-bold hover:bg-primaryHover transition-all"
                                data-i18n="btnGoDashboard">توجه للوحة التحكم</a>
                        @endif
                    </div>

                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 text-right bg-slate-50/80 p-5 md:p-8 rounded-2xl sm:rounded-[2rem] border border-gray-100 backdrop-blur-sm">
                        <div class="space-y-1">
                            <span class="text-[11px] text-gray-400 font-black uppercase tracking-wider"
                                data-i18n="lblPharmName">اسم الصيدلية</span>
                            <p class="text-base md:text-lg font-bold text-darkText">
                                {{ $activeApplication->pharmacy_name }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[11px] text-gray-400 font-black uppercase tracking-wider"
                                data-i18n="lblOwnerName">اسم المالك</span>
                            <p class="text-base md:text-lg font-bold text-darkText">
                                {{ $activeApplication->owner_name }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[11px] text-gray-400 font-black uppercase tracking-wider"
                                data-i18n="lblLicense">رقم الترخيص</span>
                            <p class="text-base md:text-lg font-bold text-darkText font-mono">
                                {{ $activeApplication->license_number }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[11px] text-gray-400 font-black uppercase tracking-wider"
                                data-i18n="lblPhone">رقم الهاتف</span>
                            <p class="text-base md:text-lg font-bold text-darkText" dir="ltr">
                                {{ $activeApplication->phone }}</p>
                        </div>
                        <div class="space-y-1 min-w-0">
                            <span class="text-[11px] text-gray-400 font-black uppercase tracking-wider"
                                data-i18n="lblEmail">البريد الإلكتروني</span>
                            <p class="text-base md:text-lg font-bold text-darkText truncate"
                                title="{{ $activeApplication->email }}">{{ $activeApplication->email }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[11px] text-gray-400 font-black uppercase tracking-wider"
                                data-i18n="lblCity">المدينة</span>
                            <p class="text-base md:text-lg font-bold text-darkText">{{ $activeApplication->city }}</p>
                        </div>
                        <div class="space-y-1 sm:col-span-2">
                            <span class="text-[11px] text-gray-400 font-black uppercase tracking-wider"
                                data-i18n="lblAddress">العنوان التفصيلي</span>
                            <p class="text-base md:text-lg font-bold text-darkText">{{ $activeApplication->address }}
                            </p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[11px] text-gray-400 font-black uppercase tracking-wider"
                                data-i18n="lblCoordinates">الإحداثيات</span>
                            <p class="text-xs md:text-sm font-bold text-darkText font-mono mt-1" dir="ltr"><i
                                    class="fa-solid fa-location-dot text-primary/50 mr-1"></i>
                                {{ $activeApplication->lat }}, {{ $activeApplication->lng }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[11px] text-gray-400 font-black uppercase tracking-wider"
                                data-i18n="lblHours">ساعات العمل</span>
                            <p class="text-base md:text-lg font-bold text-darkText">
                                {{ $activeApplication->working_hours }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[11px] text-gray-400 font-black uppercase tracking-wider"
                                data-i18n="lblCollab">تعاون مع أطباء/عيادات</span>
                            <p class="text-base md:text-lg font-bold text-darkText">
                                {{ $activeApplication->has_collaboration ? 'نعم' : 'لا' }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[11px] text-gray-400 font-black uppercase tracking-wider"
                                data-i18n="lblAppDate">تاريخ التقديم</span>
                            <p class="text-base md:text-lg font-bold text-darkText">
                                {{ $activeApplication->created_at->format('Y-m-d') }}</p>
                        </div>

                        <div class="space-y-2 sm:col-span-2 md:col-span-3 border-t border-gray-200/60 pt-4 mt-2">
                            <span class="text-[11px] text-gray-400 font-black uppercase tracking-wider block"
                                data-i18n="lblSelectedServices">الخدمات المحددة</span>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $activeServices = is_string($activeApplication->services)
                                        ? json_decode($activeApplication->services, true)
                                        : $activeApplication->services;
                                    $activeServices = is_array($activeServices) ? $activeServices : [];
                                @endphp
                                @foreach ($activeServices as $srv)
                                    <span
                                        class="px-4 py-1.5 bg-white border border-gray-200 text-primary text-xs md:text-sm font-bold rounded-xl shadow-sm flex items-center gap-2">
                                        <i class="fa-solid fa-check text-[10px] md:text-xs text-accent"></i>
                                        {{ $srv == '24_hours' ? '24 ساعة' : ($srv == 'delivery' ? 'توصيل منزلي' : ($srv == 'consultation' ? 'استشارات طبية' : $srv)) }}
                                    </span>
                                @endforeach
                                @if (empty($activeServices))
                                    <span class="text-gray-400 text-xs md:text-sm font-medium"
                                        data-i18n="lblNoServices">لم يتم تحديد خدمات إضافية</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div
                    class="bg-white rounded-[2rem] sm:rounded-[2.5rem] md:rounded-[3rem] shadow-[0_20px_60px_rgba(0,0,0,0.05)] border border-gray-100 p-6 sm:p-10 md:p-16 max-w-5xl mx-auto relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-2 md:h-3 bg-gradient-custom"></div>

                    <div class="text-center mb-10 md:mb-16 mt-4">
                        <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-darkText mb-4"
                            data-i18n="formTitle">نموذج تسجيل صيدلية</h2>
                        <p class="text-gray-500 text-base md:text-lg font-medium" data-i18n="formSub">انضم إلى شبكة
                            MediNear وقم بتوسيع نطاق عملك في دقائق</p>
                    </div>

                    <form id="pharmacy-form" action="{{ route('pharmacy.apply') ?? '#' }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="mb-12 md:mb-16">
                            <h3
                                class="text-lg sm:text-xl md:text-2xl font-black text-primary mb-6 md:mb-8 flex items-center gap-3 sm:gap-4 border-b border-gray-100 pb-4">
                                <span
                                    class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-xl sm:rounded-2xl bg-gradient-custom text-white flex items-center justify-center text-base sm:text-lg md:text-xl shadow-[0_5px_15px_rgba(13,148,136,0.3)] shrink-0">1</span>
                                <span data-i18n="sec1Title">المعلومات الأساسية</span>
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8">
                                <div class="relative">
                                    <label class="block text-sm font-bold text-gray-700 mb-2 ml-1"
                                        data-i18n="lblPharmName">اسم الصيدلية *</label>
                                    <div class="relative flex items-center">
                                        <i
                                            class="fa-solid fa-briefcase-medical absolute rtl:right-4 ltr:left-4 text-gray-400"></i>
                                        <input type="text" name="pharmacy_name"
                                            value="{{ old('pharmacy_name') }}"
                                            class="form-input rtl:pr-10 ltr:pl-10 text-base @error('pharmacy_name') error-input @enderror">
                                    </div>
                                    @error('pharmacy_name')
                                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="relative">
                                    <label class="block text-sm font-bold text-gray-700 mb-2 ml-1"
                                        data-i18n="lblOwnerName">اسم المالك *</label>
                                    <div class="relative flex items-center">
                                        <i class="fa-solid fa-user absolute rtl:right-4 ltr:left-4 text-gray-400"></i>
                                        <input type="text" name="owner_name" value="{{ old('owner_name') }}"
                                            class="form-input rtl:pr-10 ltr:pl-10 text-base @error('owner_name') error-input @enderror">
                                    </div>
                                    @error('owner_name')
                                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="relative">
                                    <label class="block text-sm font-bold text-gray-700 mb-2 ml-1"
                                        data-i18n="lblPhone">رقم الهاتف *</label>
                                    <div class="relative flex items-center">
                                        <i class="fa-solid fa-phone absolute left-4 text-gray-400"></i>
                                        <input type="tel" name="phone" value="{{ old('phone') }}"
                                            class="form-input pl-10 text-base @error('phone') error-input @enderror"
                                            dir="ltr" placeholder="+20">
                                    </div>
                                    @error('phone')
                                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="relative">
                                    <label class="block text-sm font-bold text-gray-700 mb-2 ml-1"
                                        data-i18n="lblEmail">البريد الإلكتروني *</label>
                                    <div class="relative flex items-center">
                                        <i class="fa-solid fa-envelope absolute left-4 text-gray-400"></i>
                                        <input type="email" name="email"
                                            value="{{ old('email', auth()->user()->email ?? '') }}"
                                            class="form-input pl-10 text-base @error('email') error-input @enderror"
                                            dir="ltr">
                                    </div>
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="relative">
                                    <label class="block text-sm font-bold text-gray-700 mb-2 ml-1"
                                        data-i18n="lblCity">المحافظة / المدينة *</label>
                                    <div class="relative flex items-center">
                                        <i class="fa-solid fa-city absolute rtl:right-4 ltr:left-4 text-gray-400"></i>
                                        <input type="text" name="city" value="{{ old('city') }}"
                                            class="form-input rtl:pr-10 ltr:pl-10 text-base @error('city') error-input @enderror">
                                    </div>
                                    @error('city')
                                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="relative">
                                    <label class="block text-sm font-bold text-gray-700 mb-2 ml-1"
                                        data-i18n="lblAddress">العنوان بالكامل *</label>
                                    <div class="relative flex items-center">
                                        <i
                                            class="fa-solid fa-location-arrow absolute rtl:right-4 ltr:left-4 text-gray-400"></i>
                                        <input type="text" name="address" value="{{ old('address') }}"
                                            class="form-input rtl:pr-10 ltr:pl-10 text-base @error('address') error-input @enderror">
                                    </div>
                                    @error('address')
                                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div
                                class="mt-8 md:mt-12 bg-gray-50/50 p-2 sm:p-4 md:p-6 rounded-[2rem] border transition-all duration-300 @error('lat') border-red-300 @else border-gray-100 @enderror">
                                <div
                                    class="mb-4 md:mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-3 px-2">
                                    <div>
                                        <label
                                            class="block text-lg md:text-xl font-black text-darkText mb-2 flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-secondary text-primary flex items-center justify-center text-sm">
                                                <i class="fa-solid fa-map-location-dot"></i>
                                            </div>
                                            <span data-i18n="lblMap">تحديد الموقع على الخريطة *</span>
                                        </label>
                                        <p class="text-xs sm:text-sm text-gray-500 font-medium" data-i18n="lblMapDesc">اضغط على
                                            الخريطة لتحديد موقع الصيدلية بدقة أو استخدم التحديد التلقائي.</p>
                                    </div>
                                </div>

                                <div
                                    class="relative p-1 sm:p-2 rounded-[2rem] md:rounded-[2.5rem] bg-gradient-to-br from-primary/30 via-white to-accent/30 shadow-[0_15px_30px_rgba(13,148,136,0.1)] overflow-hidden group w-full">
                                    <div class="absolute inset-0 bg-white/50 backdrop-blur-md z-0"></div>
                                    <div
                                        class="relative w-full h-[250px] sm:h-[350px] md:h-[450px] rounded-[1.5rem] md:rounded-[2rem] overflow-hidden border-2 sm:border-4 border-white shadow-inner z-10 map-styled">
                                        <div id="pharmacy-map"
                                            class="w-full h-full z-0 transition-transform duration-[2s] group-hover:scale-[1.02]">
                                        </div>
                                        <div
                                            class="hidden sm:flex absolute top-4 md:top-6 rtl:left-4 ltr:right-4 rtl:md:left-6 ltr:md:right-6 z-20 bg-white/95 backdrop-blur-md px-4 md:px-6 py-3 rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.1)] border border-gray-50 flex-col gap-2 text-sm font-bold font-mono text-gray-600 pointer-events-none group-hover:-translate-y-1 transition-transform">
                                            <div
                                                class="flex items-center justify-between gap-6 border-b border-gray-100 pb-2">
                                                <span
                                                    class="text-gray-400 text-[10px] md:text-xs uppercase tracking-widest"><i
                                                        class="fa-solid fa-arrows-up-down text-primary/50 mr-1"></i>
                                                    Lat</span>
                                                <span id="display-lat"
                                                    class="text-primary tracking-wider">{{ old('lat', '--.------') }}</span>
                                            </div>
                                            <div class="flex items-center justify-between gap-6 pt-1">
                                                <span
                                                    class="text-gray-400 text-[10px] md:text-xs uppercase tracking-widest"><i
                                                        class="fa-solid fa-arrows-left-right text-primary/50 mr-1"></i>
                                                    Lng</span>
                                                <span id="display-lng"
                                                    class="text-primary tracking-wider">{{ old('lng', '--.------') }}</span>
                                            </div>
                                        </div>
                                        <button type="button" onclick="getCurrentLocation()" title="تحديد موقعي"
                                            class="absolute bottom-4 md:bottom-8 rtl:right-4 ltr:left-4 rtl:md:right-8 ltr:md:left-8 z-20 px-5 md:px-8 py-3 md:py-4 bg-gradient-custom text-white rounded-2xl shadow-[0_15px_30px_rgba(13,148,136,0.4)] flex items-center justify-center gap-3 text-sm md:text-base font-bold hover:scale-105 transition-all hover:-translate-y-1 animate-pulse-glow group/btn border border-white/30">
                                            <i
                                                class="fa-solid fa-location-crosshairs text-lg md:text-xl transition-transform duration-500 group-hover/btn:rotate-180"></i>
                                            <span data-i18n="btnDetectLoc" class="hidden sm:inline">تحديد موقعي
                                                تلقائياً</span>
                                            <span data-i18n="btnDetectLoc" class="sm:hidden">تحديد موقعي</span>
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" id="lat" name="lat" value="{{ old('lat') }}">
                                <input type="hidden" id="lng" name="lng" value="{{ old('lng') }}">
                                @error('lat')
                                    <p class="text-red-500 text-xs mt-2 font-bold px-2">يرجى تحديد الموقع على الخريطة</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-12 md:mb-16">
                            <h3
                                class="text-lg sm:text-xl md:text-2xl font-black text-primary mb-6 md:mb-8 flex items-center gap-3 sm:gap-4 border-b border-gray-100 pb-4">
                                <span
                                    class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-xl sm:rounded-2xl bg-gradient-custom text-white flex items-center justify-center text-base sm:text-lg md:text-xl shadow-[0_5px_15px_rgba(13,148,136,0.3)] shrink-0">2</span>
                                <span data-i18n="sec2Title">التفاصيل التشغيلية</span>
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8">
                                <div class="relative">
                                    <label class="block text-sm font-bold text-gray-700 mb-2 ml-1"
                                        data-i18n="lblHours">ساعات العمل *</label>
                                    <div class="relative flex items-center">
                                        <i class="fa-regular fa-clock absolute left-4 text-gray-400"></i>
                                        <input type="text" name="working_hours"
                                            value="{{ old('working_hours') }}"
                                            class="form-input pl-10 text-base @error('working_hours') error-input @enderror"
                                            placeholder="e.g. 9 AM - 11 PM" dir="ltr">
                                    </div>
                                    @error('working_hours')
                                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="relative">
                                    <label class="block text-sm font-bold text-gray-700 mb-2 ml-1"
                                        data-i18n="lblLicense">رقم الترخيص *</label>
                                    <div class="relative flex items-center">
                                        <i
                                            class="fa-solid fa-file-contract absolute rtl:right-4 ltr:left-4 text-gray-400"></i>
                                        <input type="text" name="license_number"
                                            value="{{ old('license_number') }}"
                                            class="form-input rtl:pr-10 ltr:pl-10 text-base @error('license_number') error-input @enderror">
                                    </div>
                                    @error('license_number')
                                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2 mt-4 relative">
                                    <label class="block text-sm font-bold text-gray-700 mb-3 ml-1"
                                        data-i18n="lblUpload">رفع مستند الترخيص *</label>
                                    <div
                                        class="relative border-2 border-dashed transition-all duration-300 @error('license_document') border-red-400 bg-red-50 @else border-primary/50 bg-gray-50/50 @enderror rounded-3xl p-8 md:p-14 text-center hover:border-primary hover:bg-secondary/40 group overflow-hidden flex flex-col items-center justify-center min-h-[200px]">
                                        <input type="file" name="license_document" id="license_upload"
                                            accept=".pdf,.png,.jpg,.jpeg"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                            aria-label="Upload License Document">
                                        <div
                                            class="absolute inset-0 bg-gradient-custom opacity-0 group-hover:opacity-5 transition-opacity duration-500 z-0 pointer-events-none">
                                        </div>
                                        <div class="relative z-10 pointer-events-none">
                                            <div
                                                class="w-20 h-20 md:w-24 md:h-24 bg-white rounded-full shadow-[0_10px_25px_rgba(0,0,0,0.05)] flex items-center justify-center mx-auto mb-5 transition-all duration-500 group-hover:-translate-y-4 group-hover:shadow-[0_15px_30px_rgba(13,148,136,0.2)] border border-gray-50">
                                                <i
                                                    class="fa-solid fa-cloud-arrow-up text-4xl md:text-5xl @error('license_document') text-red-400 @else text-primary/60 @enderror group-hover:text-primary transition-colors"></i>
                                            </div>
                                            <p id="upload_text"
                                                class="text-base md:text-lg font-black text-darkText mb-2"
                                                data-i18n="lblDragDrop">انقر أو اسحب وأفلت لرفع الملف هنا</p>
                                            <p class="text-sm text-gray-500 font-medium" data-i18n="lblFileTypes">صيغ
                                                مدعومة: PDF, PNG, JPG (الحد الأقصى 10MB)</p>
                                        </div>
                                    </div>
                                    @error('license_document')
                                        <p class="text-red-500 text-xs mt-2 font-bold px-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-10 md:mb-12">
                            <h3
                                class="text-lg sm:text-xl md:text-2xl font-black text-primary mb-6 md:mb-8 flex items-center gap-3 sm:gap-4 border-b border-gray-100 pb-4">
                                <span
                                    class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-xl sm:rounded-2xl bg-gradient-custom text-white flex items-center justify-center text-base sm:text-lg md:text-xl shadow-[0_5px_15px_rgba(13,148,136,0.3)] shrink-0">3</span>
                                <span data-i18n="sec3Title">الخدمات المتاحة</span>
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-8 relative">
                                <label
                                    class="flex items-center gap-4 p-5 md:p-6 border-2 border-gray-100 rounded-2xl cursor-pointer hover:bg-white hover:border-primary/50 transition-all duration-300 shadow-sm hover:shadow-md bg-gray-50/50 group">
                                    <div class="relative w-6 h-6 flex items-center justify-center shrink-0">
                                        <input type="checkbox" name="services[]" value="24_hours"
                                            {{ is_array(old('services')) && in_array('24_hours', old('services')) ? 'checked' : '' }}
                                            class="peer appearance-none w-6 h-6 border-2 border-gray-300 rounded-lg checked:bg-primary checked:border-primary transition-all cursor-pointer">
                                        <i
                                            class="fa-solid fa-check absolute text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></i>
                                    </div>
                                    <span
                                        class="font-bold text-base md:text-lg text-darkText group-hover:text-primary transition-colors"
                                        data-i18n="srv24">24 ساعة</span>
                                </label>

                                <label
                                    class="flex items-center gap-4 p-5 md:p-6 border-2 border-gray-100 rounded-2xl cursor-pointer hover:bg-white hover:border-primary/50 transition-all duration-300 shadow-sm hover:shadow-md bg-gray-50/50 group">
                                    <div class="relative w-6 h-6 flex items-center justify-center shrink-0">
                                        <input type="checkbox" name="services[]" value="delivery"
                                            {{ is_array(old('services')) && in_array('delivery', old('services')) ? 'checked' : '' }}
                                            class="peer appearance-none w-6 h-6 border-2 border-gray-300 rounded-lg checked:bg-primary checked:border-primary transition-all cursor-pointer">
                                        <i
                                            class="fa-solid fa-check absolute text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></i>
                                    </div>
                                    <span
                                        class="font-bold text-base md:text-lg text-darkText group-hover:text-primary transition-colors"
                                        data-i18n="srvDelivery">توصيل منزلي</span>
                                </label>

                                <label
                                    class="flex items-center gap-4 p-5 md:p-6 border-2 border-gray-100 rounded-2xl cursor-pointer hover:bg-white hover:border-primary/50 transition-all duration-300 shadow-sm hover:shadow-md bg-gray-50/50 group">
                                    <div class="relative w-6 h-6 flex items-center justify-center shrink-0">
                                        <input type="checkbox" name="services[]" value="consultation"
                                            {{ is_array(old('services')) && in_array('consultation', old('services')) ? 'checked' : '' }}
                                            class="peer appearance-none w-6 h-6 border-2 border-gray-300 rounded-lg checked:bg-primary checked:border-primary transition-all cursor-pointer">
                                        <i
                                            class="fa-solid fa-check absolute text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></i>
                                    </div>
                                    <span
                                        class="font-bold text-base md:text-lg text-darkText group-hover:text-primary transition-colors"
                                        data-i18n="srvConsult">استشارات طبية</span>
                                </label>
                            </div>
                            @error('services')
                                <p class="text-red-500 text-xs mb-4 font-bold px-2">{{ $message }}</p>
                            @enderror

                            <div class="bg-secondary/40 p-6 md:p-8 rounded-3xl border border-primary/10">
                                <label class="block text-base md:text-lg font-black text-darkText mb-5"
                                    data-i18n="lblCollab">هل تتعاون مع أطباء أو عيادات؟</label>
                                <div class="flex gap-8 md:gap-12">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="relative w-5 h-5 flex items-center justify-center shrink-0">
                                            <input type="radio" name="collab" value="yes"
                                                {{ old('collab') == 'yes' ? 'checked' : '' }}
                                                class="peer appearance-none w-5 h-5 border-2 border-gray-400 rounded-full checked:border-primary transition-all cursor-pointer">
                                            <div
                                                class="w-2.5 h-2.5 bg-primary rounded-full absolute opacity-0 peer-checked:opacity-100 transition-all scale-0 peer-checked:scale-100 pointer-events-none">
                                            </div>
                                        </div>
                                        <span
                                            class="text-base md:text-lg font-bold text-gray-700 group-hover:text-primary transition-colors"
                                            data-i18n="lblYes">نعم</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="relative w-5 h-5 flex items-center justify-center shrink-0">
                                            <input type="radio" name="collab" value="no"
                                                {{ old('collab', 'no') == 'no' ? 'checked' : '' }}
                                                class="peer appearance-none w-5 h-5 border-2 border-gray-400 rounded-full checked:border-primary transition-all cursor-pointer">
                                            <div
                                                class="w-2.5 h-2.5 bg-primary rounded-full absolute opacity-0 peer-checked:opacity-100 transition-all scale-0 peer-checked:scale-100 pointer-events-none">
                                            </div>
                                        </div>
                                        <span
                                            class="text-base md:text-lg font-bold text-gray-700 group-hover:text-primary transition-colors"
                                            data-i18n="lblNo">لا</span>
                                    </label>
                                </div>
                                @error('collab')
                                    <p class="text-red-500 text-xs mt-2 font-bold px-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center mt-12 md:mt-16 pt-8 border-t border-gray-100">
                            <button type="submit" id="submit-btn"
                                class="btn-shine-effect bg-gradient-custom text-white px-10 md:px-20 py-4 md:py-6 rounded-2xl md:rounded-[1.5rem] font-black text-lg md:text-2xl transition-all duration-300 shadow-[0_15px_30px_rgba(13,148,136,0.35)] hover:shadow-[0_20px_40px_rgba(13,148,136,0.45)] hover:-translate-y-2 w-full md:w-auto flex items-center justify-center gap-4 mx-auto group disabled:opacity-75 disabled:cursor-not-allowed">
                                <span data-i18n="btnSubmit">إرسال طلب الانضمام</span>
                                <i
                                    class="fa-solid fa-paper-plane rtl:scale-x-[-1] text-xl transition-transform duration-500 group-hover:translate-x-2 rtl:group-hover:-translate-x-2 group-hover:-translate-y-2"></i>
                            </button>
                            <p class="text-xs md:text-sm text-gray-400 font-medium mt-6 px-4" data-i18n="formTerms">
                                بإرسالك هذا النموذج، أنت توافق على الشروط والأحكام وسياسة الخصوصية الخاصة بمنصة MediNear
                            </p>
                        </div>
                    </form>
                </div>
            @endif

            @if (isset($rejectedApplications) && $rejectedApplications->count() > 0)
                <div class="mt-16 md:mt-20 max-w-5xl mx-auto px-4 sm:px-0">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="h-[2px] flex-grow bg-rose-100"></div>
                        <h3 class="text-lg md:text-2xl font-black text-slate-500 flex items-center gap-3 shrink-0">
                            <i class="fa-solid fa-history text-rose-500"></i> <span style="font-size: medium;"
                                data-i18n="historyTitle">سجل الطلبات السابقة المرفوضة</span>
                        </h3>
                        <div class="h-[2px] flex-grow bg-rose-100"></div>
                    </div>

                    <div class="space-y-4">
                        @foreach ($rejectedApplications as $rejected)
                            <div
                                class="bg-white rounded-[1.5rem] sm:rounded-[2rem] border border-rose-100 shadow-sm hover:shadow-md transition-all group overflow-hidden">
                                <div onclick="toggleHistoryDetails('rej-{{ $rejected->id }}', this)"
                                    class="p-5 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 cursor-pointer relative bg-white hover:bg-rose-50/30 transition-colors">
                                    <div class="absolute top-0 right-0 w-2 h-full bg-rose-500"></div>
                                    <div class="flex items-center gap-4 text-right w-full">
                                        <div
                                            class="w-12 h-12 md:w-14 md:h-14 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center shrink-0 shadow-inner">
                                            <i class="fa-solid fa-file-circle-xmark text-xl md:text-2xl"></i>
                                        </div>
                                        <div class="flex-grow min-w-0">
                                            <div class="flex flex-wrap gap-2 items-center mb-1">
                                                <h4 class="font-bold text-base md:text-lg text-darkText truncate"><span
                                                        data-i18n="historyItemTitle">طلب انضمام:</span>
                                                    {{ $rejected->pharmacy_name }}</h4>
                                                <span
                                                    class="text-[10px] md:text-xs font-bold px-2 py-1 bg-rose-100 text-rose-600 rounded-lg whitespace-nowrap"
                                                    data-i18n="badgeRejected">مرفوض</span>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-3">
                                                <p class="text-xs md:text-sm text-gray-400 font-medium">
                                                    <i class="fa-regular fa-calendar-xmark ml-1"></i> <span
                                                        data-i18n="lblRejectDate">تاريخ الرفض:</span>
                                                    {{ $rejected->updated_at->format('Y-m-d') }}
                                                </p>
                                                @if ($rejected->admin_notes)
                                                    <span
                                                        class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 bg-rose-50 text-rose-600 rounded-md border border-rose-100">
                                                        <i class="fa-solid fa-comment-dots"></i> توجد ملاحظة
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="shrink-0 self-end md:self-center w-full md:w-auto mt-2 md:mt-0">
                                        <button type="button"
                                            class="w-full md:w-auto text-primary font-bold text-xs md:text-sm bg-secondary px-4 py-2.5 rounded-xl flex items-center justify-center gap-2 transition-all group-hover:bg-teal-100 pointer-events-none">
                                            <span class="toggle-text-btn">عرض التفاصيل</span>
                                            <i
                                                class="fa-solid fa-chevron-down transition-transform duration-300 toggle-icon-btn"></i>
                                        </button>
                                    </div>
                                </div>

                                <div id="rej-{{ $rejected->id }}"
                                    class="hidden px-5 pb-5 md:px-8 md:pb-8 border-t border-gray-50 pt-6">

                                    @if ($rejected->admin_notes)
                                        <div
                                            class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl text-sm font-medium mb-6 relative overflow-hidden shadow-sm">
                                            <div class="absolute top-0 right-0 w-1 h-full bg-rose-500"></div>
                                            <div class="font-black mb-1 flex items-center gap-2"><i
                                                    class="fa-solid fa-comment-dots text-rose-500"></i> <span
                                                    data-i18n="lblRejectReason">سبب الرفض من الإدارة:</span></div>
                                            <p class="leading-relaxed text-rose-700">{{ $rejected->admin_notes }}</p>
                                        </div>
                                    @endif

                                    <h5 class="font-black text-gray-400 text-[10px] md:text-xs uppercase tracking-wider mb-4 border-b border-gray-100 pb-2"
                                        data-i18n="lblSentDetails">التفاصيل التي تم إرسالها</h5>

                                    <div
                                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6 text-right bg-gray-50/50 p-5 md:p-6 rounded-[1.5rem]">
                                        <div class="space-y-1">
                                            <span class="text-[10px] text-gray-400 font-black uppercase"
                                                data-i18n="lblOwnerName">اسم المالك</span>
                                            <p class="text-sm md:text-base font-bold text-darkText">
                                                {{ $rejected->owner_name }}</p>
                                        </div>
                                        <div class="space-y-1">
                                            <span class="text-[10px] text-gray-400 font-black uppercase"
                                                data-i18n="lblLicense">رقم الترخيص</span>
                                            <p class="text-sm md:text-base font-bold text-darkText font-mono">
                                                {{ $rejected->license_number }}</p>
                                        </div>
                                        <div class="space-y-1">
                                            <span class="text-[10px] text-gray-400 font-black uppercase"
                                                data-i18n="lblPhone">رقم الهاتف</span>
                                            <p class="text-sm md:text-base font-bold text-darkText" dir="ltr">
                                                {{ $rejected->phone }}</p>
                                        </div>
                                        <div class="space-y-1 min-w-0">
                                            <span class="text-[10px] text-gray-400 font-black uppercase"
                                                data-i18n="lblEmail">البريد الإلكتروني</span>
                                            <p class="text-sm md:text-base font-bold text-darkText truncate"
                                                title="{{ $rejected->email }}">{{ $rejected->email }}</p>
                                        </div>
                                        <div class="space-y-1 sm:col-span-2">
                                            <span class="text-[10px] text-gray-400 font-black uppercase"
                                                data-i18n="lblAddress">العنوان التفصيلي</span>
                                            <p class="text-sm md:text-base font-bold text-darkText">
                                                {{ $rejected->address }}</p>
                                        </div>
                                        <div class="space-y-1">
                                            <span class="text-[10px] text-gray-400 font-black uppercase"
                                                data-i18n="lblHours">ساعات العمل</span>
                                            <p class="text-sm md:text-base font-bold text-darkText">
                                                {{ $rejected->working_hours }}</p>
                                        </div>
                                        <div class="space-y-1">
                                            <span class="text-[10px] text-gray-400 font-black uppercase"
                                                data-i18n="lblCollab">تعاون مع أطباء</span>
                                            <p class="text-sm md:text-base font-bold text-darkText">
                                                {{ $rejected->has_collaboration ? 'نعم' : 'لا' }}</p>
                                        </div>

                                        <div
                                            class="space-y-2 sm:col-span-2 md:col-span-3 border-t border-gray-200/60 pt-4 mt-2">
                                            <span class="text-[10px] text-gray-400 font-black uppercase block"
                                                data-i18n="lblSelectedServices">الخدمات</span>
                                            <div class="flex flex-wrap gap-2">
                                                @php
                                                    $rejServices = is_string($rejected->services)
                                                        ? json_decode($rejected->services, true)
                                                        : $rejected->services;
                                                    $rejServices = is_array($rejServices) ? $rejServices : [];
                                                @endphp
                                                @foreach ($rejServices as $srv)
                                                    <span
                                                        class="px-3 py-1 bg-white border border-gray-200 text-gray-600 text-[10px] md:text-xs font-bold rounded-lg shadow-sm">
                                                        {{ $srv == '24_hours' ? '24 ساعة' : ($srv == 'delivery' ? 'توصيل منزلي' : ($srv == 'consultation' ? 'استشارات طبية' : $srv)) }}
                                                    </span>
                                                @endforeach
                                                @if (empty($rejServices))
                                                    <span class="text-gray-400 text-xs font-medium"
                                                        data-i18n="lblNoServices">لم يتم تحديد خدمات</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if (!$activeApplication)
                                        <div class="mt-8 text-center border-t border-gray-100 pt-6">
                                            <button type="button"
                                                onclick="document.getElementById('register-form').scrollIntoView({behavior: 'smooth'})"
                                                class="inline-flex items-center gap-2 bg-darkText text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:-translate-y-1 hover:shadow-xl transition-all">
                                                <i class="fa-solid fa-rotate-right"></i> <span
                                                    data-i18n="btnNewApp">تقديم طلب جديد</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>

    </div>

    <footer
        class="bg-footerBg text-gray-300 pt-16 md:pt-20 pb-6 md:pb-8 mt-auto border-t-[6px] md:border-t-[8px] border-primary">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8 md:gap-10 mb-12 md:mb-16">
                <div class="flex items-center gap-3 md:gap-4 cursor-default">
                    <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg"
                        class="w-10 h-10 md:w-12 md:h-12">
                        <defs>
                            <linearGradient id="logoGradFooter" x1="80%" y1="0%" x2="20%"
                                y2="100%">
                                <stop offset="0%" stop-color="#84cc16" />
                                <stop offset="100%" stop-color="#14b8a6" />
                            </linearGradient>
                        </defs>
                        <circle cx="50" cy="50" r="46" stroke="url(#logoGradFooter)"
                            stroke-width="4" />
                        <path
                            d="M50 15C34.536 15 22 27.536 22 43C22 62.5 50 85 50 85C50 85 78 62.5 78 43C78 27.536 65.464 15 50 15Z"
                            fill="url(#logoGradFooter)" />
                        <path d="M45 28H55V37H64V47H55V56H45V47H36V37H45V28Z" fill="white" />
                        <g transform="translate(38, 65) rotate(-50)">
                            <rect x="-14" y="-26" width="28" height="52" rx="14" fill="white" />
                            <rect x="-11" y="-23" width="22" height="46" rx="11" fill="white"
                                stroke="url(#logoGradFooter)" stroke-width="2" />
                            <path d="M-10 0 H10 V12 A10 10 0 0 1 -10 12 Z" fill="url(#logoGradFooter)" />
                        </g>
                    </svg>
                    <span class="font-black text-2xl md:text-3xl text-white tracking-tight">MediNear</span>
                </div>

                <div class="flex flex-wrap justify-center gap-6 md:gap-8 text-sm md:text-base font-medium">
                    <span class="text-primary font-black hidden lg:inline" data-i18n="footImportantLinks">روابط
                        هامة:</span>
                    <a href="index.html" class="hover:text-white transition-colors" data-i18n="navHome">الرئيسية</a>
                    <a href="index.html#features" class="hover:text-white transition-colors"
                        data-i18n="navFeatures">المميزات</a>
                    <a href="index.html#how-it-works" class="hover:text-white transition-colors"
                        data-i18n="navHow">كيف يعمل</a>
                    <a href="index.html#contact" class="hover:text-white transition-colors"
                        data-i18n="navContact">اتصل بنا</a>
                </div>

                <div class="flex flex-wrap justify-center gap-4 md:gap-6 text-xs md:text-sm text-gray-500 font-medium">
                    <a href="#" class="hover:text-white transition-colors" data-i18n="footPrivacy">سياسة
                        الخصوصية</a>
                    <a href="#" class="hover:text-white transition-colors" data-i18n="footTerms">الشروط
                        والأحكام</a>
                    <a href="#" class="hover:text-white transition-colors" data-i18n="footData">سياسة حماية
                        البيانات</a>
                </div>
            </div>

            <div
                class="border-t border-gray-800 pt-6 md:pt-8 text-center text-xs md:text-sm text-gray-500 font-medium">
                <p data-i18n="footCopy">2026 MediNear جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

    <button id="go-up-btn" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
        class="fixed bottom-6 rtl:left-6 ltr:right-6 md:bottom-8 rtl:md:left-8 ltr:md:right-8 bg-gradient-custom text-white w-12 h-12 md:w-14 md:h-14 rounded-full shadow-[0_10px_20px_rgba(13,148,136,0.3)] md:shadow-[0_10px_25px_rgba(13,148,136,0.4)] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-500 z-50 hover:scale-110 hover:-translate-y-1 md:hover:-translate-y-2 translate-y-10 text-lg md:text-xl border border-white/20">
        <i class="fa-solid fa-arrow-up"></i>
    </button>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        // Custom Client-Side Toast System
        function showCustomToast(type, title, message) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');

            toast.className =
                `pointer-events-auto bg-white border-2 ${type === 'success' ? 'border-green-100' : 'border-red-100'} shadow-2xl rounded-2xl p-4 flex items-center gap-4 toast-slide-in min-w-[300px] max-w-sm`;
            const iconClass = type === 'success' ? 'fa-check' : 'fa-triangle-exclamation';
            const bgClass = type === 'success' ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-500';

            toast.innerHTML = `
                <div class="w-12 h-12 ${bgClass} rounded-full flex items-center justify-center text-2xl shrink-0 shadow-inner">
                    <i class="fa-solid ${iconClass}"></i>
                </div>
                <div class="flex-grow">
                    <h4 class="font-bold text-gray-800 text-lg">${title}</h4>
                    <p class="text-sm text-gray-500 font-medium">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-red-500 transition-colors p-2">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            `;

            container.appendChild(toast);
            setTimeout(() => {
                if (toast.parentElement) toast.remove();
            }, 5000);
        }

        // Auto-hide Server Toasts
        setTimeout(() => {
            const successToast = document.querySelector('.border-green-100');
            const errorToast = document.querySelector('.border-red-100');
            if (successToast) successToast.remove();
            if (errorToast) errorToast.remove();
        }, 6000);

        // Vanilla JS History Toggle (Smooth Expansion)
        function toggleHistoryDetails(id, headerElement) {
            const content = document.getElementById(id);
            const textBtn = headerElement.querySelector('.toggle-text-btn');
            const iconBtn = headerElement.querySelector('.toggle-icon-btn');

            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                // Using simple fade-in via CSS opacity
                content.style.opacity = '0';
                setTimeout(() => {
                    content.style.transition = 'opacity 0.3s ease';
                    content.style.opacity = '1';
                }, 10);

                textBtn.innerText = currentLang === 'ar' ? 'إخفاء التفاصيل' : 'Hide Details';
                iconBtn.classList.add('rotate-180');
            } else {
                content.style.opacity = '0';
                setTimeout(() => {
                    content.classList.add('hidden');
                }, 300);
                textBtn.innerText = currentLang === 'ar' ? 'عرض التفاصيل' : 'Show Details';
                iconBtn.classList.remove('rotate-180');
            }
        }

        // Update File Name on Upload
        const fileInput = document.getElementById('license_upload');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name;
                if (fileName) {
                    document.getElementById('upload_text').innerText = fileName;
                    document.getElementById('upload_text').classList.add('text-primary');
                }
            });
        }

        // --- Front-end Form Validation & Double Click Prevention ---
        const pharmacyForm = document.getElementById('pharmacy-form');
        if (pharmacyForm) {
            pharmacyForm.addEventListener('submit', function(e) {
                e.preventDefault();

                let isValid = true;
                let firstErrorField = null;

                // 1. Reset all previous errors
                pharmacyForm.querySelectorAll('.form-input').forEach(input => {
                    input.classList.remove('error-input');
                });
                const mapContainer = document.getElementById('pharmacy-map').parentElement.parentElement;
                mapContainer.classList.remove('ring-4', 'ring-red-500', 'shadow-[0_0_20px_rgba(239,68,68,0.4)]');
                const fileZone = fileInput.closest('.border-dashed');
                fileZone.classList.remove('border-red-400', 'bg-red-50');

                // 2. Validate Text Inputs
                const requiredFields = ['pharmacy_name', 'owner_name', 'phone', 'email', 'city', 'address',
                    'working_hours', 'license_number'
                ];
                requiredFields.forEach(field => {
                    const input = pharmacyForm.querySelector(`[name="${field}"]`);
                    if (input && input.value.trim() === '') {
                        isValid = false;
                        input.classList.add('error-input');
                        if (!firstErrorField) firstErrorField = input;
                    }
                });

                // 3. Validate File
                if (fileInput && fileInput.files.length === 0) {
                    isValid = false;
                    fileZone.classList.add('border-red-400', 'bg-red-50');
                    if (!firstErrorField) firstErrorField = fileZone;
                }

                // 4. Validate Map
                const lat = document.getElementById('lat').value;
                const lng = document.getElementById('lng').value;
                if (!lat || !lng || lat === '' || lng === '') {
                    isValid = false;
                    mapContainer.classList.add('ring-4', 'ring-red-500', 'shadow-[0_0_20px_rgba(239,68,68,0.4)]');
                    if (!firstErrorField) firstErrorField = mapContainer;
                }

                // 5. Validate Services
                const servicesChecked = document.querySelectorAll('input[name="services[]"]:checked').length > 0;
                if (!servicesChecked) {
                    isValid = false;
                    if (!firstErrorField) firstErrorField = document.querySelector('input[name="services[]"]')
                        .closest('.grid');
                }

                // 6. Handle Error State
                if (!isValid) {
                    const title = currentLang === 'ar' ? 'خطأ في الإدخال' : 'Validation Error';
                    const msg = currentLang === 'ar' ?
                        'يرجى تعبئة جميع الحقول المطلوبة وتحديد الموقع الملونة بالأحمر.' :
                        'Please fill all required fields highlighted in red.';
                    showCustomToast('error', title, msg);

                    if (firstErrorField) {
                        firstErrorField.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                    return false;
                }

                // 7. Success - Disable Button and Submit
                const submitBtn = document.getElementById('submit-btn');
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    `<i class="fa-solid fa-spinner fa-spin text-xl"></i> <span data-i18n="btnSubmitting">${currentLang === 'ar' ? 'جاري الإرسال...' : 'Submitting...'}</span>`;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

                // Submit Form
                setTimeout(() => {
                    pharmacyForm.submit();
                }, 300);
            });
        }

        // --- 0. Page Load Init ---
        window.addEventListener('load', () => {
            if (document.getElementById('pharmacy-map')) {
                initMap();
                setTimeout(() => {
                    if (map) map.invalidateSize();
                }, 500);

                // Render old map coordinates if validation failed
                const oldLat = document.getElementById('lat').value;
                const oldLng = document.getElementById('lng').value;
                if (oldLat && oldLng) {
                    setTimeout(() => {
                        map.flyTo([oldLat, oldLng], 16);
                        if (marker) map.removeLayer(marker);
                        marker = L.marker([oldLat, oldLng]).addTo(map);
                    }, 1000);
                }
            }
        });

        // --- 1. Map Initialization & Geolocation ---
        let map;
        let marker;

        delete L.Icon.Default.prototype._getIconUrl;
        L.Icon.Default.mergeOptions({
            iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon-2x.png',
            iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
        });

        function updateCoordinates(lat, lng) {
            const latEl = document.getElementById('lat');
            const lngEl = document.getElementById('lng');
            const dLat = document.getElementById('display-lat');
            const dLng = document.getElementById('display-lng');

            if (latEl) latEl.value = lat;
            if (lngEl) lngEl.value = lng;

            if (dLat && dLng) {
                dLat.innerText = lat;
                dLng.innerText = lng;
                dLat.parentElement.classList.add('text-accent');
                dLng.parentElement.classList.add('text-accent');
                setTimeout(() => {
                    dLat.parentElement.classList.remove('text-accent');
                    dLng.parentElement.classList.remove('text-accent');
                }, 500);
            }
        }

        function initMap() {
            if (map) return;
            map = L.map('pharmacy-map').setView([30.0444, 31.2357], 12);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a>',
                subdomains: 'abcd',
                maxZoom: 20
            }).addTo(map);

            map.on('click', function(e) {
                if (marker) {
                    map.removeLayer(marker);
                }
                marker = L.marker(e.latlng).addTo(map);
                updateCoordinates(e.latlng.lat.toFixed(6), e.latlng.lng.toFixed(6));
                map.flyTo(e.latlng, map.getZoom(), {
                    duration: 0.5
                });
            });
        }

        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    if (!map) initMap();
                    map.flyTo([lat, lng], 16, {
                        animate: true,
                        duration: 2
                    });
                    if (marker) {
                        map.removeLayer(marker);
                    }
                    marker = L.marker([lat, lng]).addTo(map);
                    updateCoordinates(lat.toFixed(6), lng.toFixed(6));
                }, function(error) {
                    const title = currentLang === 'ar' ? 'عذراً' : 'Error';
                    const msg = currentLang === 'ar' ? 'تعذر الحصول على الموقع. يرجى السماح للمتصفح بمعرفة موقعك.' :
                        'Could not get location. Please allow browser location permissions.';
                    showCustomToast('error', title, msg);
                });
            } else {
                const title = currentLang === 'ar' ? 'تنبيه' : 'Warning';
                const msg = currentLang === 'ar' ? 'المتصفح الخاص بك لا يدعم تحديد الموقع تلقائياً.' :
                    'Geolocation is not supported by your browser.';
                showCustomToast('error', title, msg);
            }
        }

        // --- 2. Internationalization (i18n) System ---
        const i18n = {
            ar: {
                navHome: "الرئيسية",
                navFeatures: "المميزات",
                navHow: "كيف يعمل",
                navReviews: "آراء العملاء",
                navPharmacies: "للصيدليات",
                navContact: "اتصل بنا",
                btnRegPharmacy: "سجل كصيدلية",
                btnStartNow: "ابدأ التسجيل الآن",
                pharmTag: "للصيدليات",
                pharmHeroTitle: "انضم إلى شبكة MediNear وقم بتوسيع نطاق عملك",
                pharmHeroDesc: "سجل صيدليتك الآن وابدأ في استقبال آلاف الطلبات من المرضى في منطقتك بكل سهولة وبأدوات إدارة متطورة.",
                pharmBenTitle: "فوائد الشراكة",
                pharmBen1: "زيادة المبيعات",
                pharmBen2: "الوصول لعملاء جدد",
                pharmBen3: "إدارة الطلبات بسهولة",
                pharmBen4: "الوصول للوحة التحليلات",
                pharmBen5: "عملية تسجيل بسيطة",
                formTitle: "نموذج تسجيل صيدلية",
                formSub: "انضم إلى شبكة MediNear وقم بتوسيع نطاق عملك في دقائق",
                sec1Title: "المعلومات الأساسية",
                lblPharmName: "اسم الصيدلية *",
                lblOwnerName: "اسم المالك *",
                lblPhone: "رقم الهاتف *",
                lblEmail: "البريد الإلكتروني *",
                lblCity: "المحافظة / المدينة *",
                lblAddress: "العنوان بالكامل *",
                lblMap: "تحديد الموقع على الخريطة *",
                lblMapDesc: "اضغط على الخريطة لتحديد موقع الصيدلية بدقة أو استخدم التحديد التلقائي.",
                btnDetectLoc: "تحديد موقعي تلقائياً",
                sec2Title: "التفاصيل التشغيلية",
                lblHours: "ساعات العمل *",
                lblLicense: "رقم الترخيص *",
                lblUpload: "رفع مستند الترخيص *",
                lblDragDrop: "انقر أو اسحب وأفلت لرفع الملف هنا",
                lblFileTypes: "صيغ مدعومة: PDF, PNG, JPG (الحد الأقصى 10MB)",
                sec3Title: "الخدمات المتاحة",
                srv24: "24 ساعة",
                srvDelivery: "توصيل منزلي",
                srvConsult: "استشارات طبية",
                lblCollab: "هل تتعاون مع أطباء أو عيادات؟",
                lblYes: "نعم",
                lblNo: "لا",
                btnSubmit: "إرسال طلب الانضمام",
                formTerms: "بإرسالك هذا النموذج، أنت توافق على الشروط والأحكام وسياسة الخصوصية الخاصة بمنصة MediNear",
                footImportantLinks: "روابط هامة:",
                footPrivacy: "سياسة الخصوصية",
                footTerms: "الشروط والأحكام",
                footData: "سياسة حماية البيانات",
                footCopy: "2026 MediNear جميع الحقوق محفوظة.",
                navDashboard: "لوحة التحكم",
                navLogout: "تسجيل الخروج",
                statusReviewTitle: "طلبك قيد المراجعة حالياً",
                statusReviewDesc: "نشكرك على انضمامك لـ MediNear. يقوم فريقنا الآن بمراجعة بيانات صيدليتك وسيتم الرد عليك في أقرب وقت.",
                statusApproveTitle: "تهانينا! تم قبول صيدليتك",
                statusApproveDesc: "يمكنك الآن البدء في استخدام لوحة التحكم الخاصة بالصيدلية.",
                btnGoDashboard: "توجه للوحة التحكم",
                lblCoordinates: "الإحداثيات",
                lblAppDate: "تاريخ التقديم",
                lblSelectedServices: "الخدمات المحددة",
                lblNoServices: "لم يتم تحديد خدمات",
                historyTitle: "سجل الطلبات السابقة المرفوضة",
                historyItemTitle: "طلب انضمام:",
                badgeRejected: "مرفوض",
                lblRejectDate: "تاريخ الرفض:",
                lblRejectReason: "سبب الرفض من الإدارة:",
                lblSentDetails: "التفاصيل التي تم إرسالها",
                btnNewApp: "تقديم طلب جديد",
                toastSuccessTitle: "نجاح!",
                toastErrorTitle: "خطأ في الإدخال!",
                toastErrorDesc: "يرجى مراجعة الحقول باللون الأحمر أدناه.",
                btnSubmitting: "جاري الإرسال..."
            },
            en: {
                navHome: "Home",
                navFeatures: "Features",
                navHow: "How it works",
                navReviews: "Reviews",
                navPharmacies: "For Pharmacies",
                navContact: "Contact Us",
                btnRegPharmacy: "Register Pharmacy",
                btnStartNow: "Start Registration",
                pharmTag: "For Pharmacies",
                pharmHeroTitle: "Join the MediNear Network and Expand Your Business",
                pharmHeroDesc: "Register your pharmacy now and start receiving thousands of orders from patients in your area effortlessly with advanced management tools.",
                pharmBenTitle: "Partnership Benefits",
                pharmBen1: "Increase Sales",
                pharmBen2: "Reach New Customers",
                pharmBen3: "Manage Orders Easily",
                pharmBen4: "Access Analytics Board",
                pharmBen5: "Simple Registration",
                formTitle: "Pharmacy Registration Form",
                formSub: "Join the MediNear network and expand your business in minutes",
                sec1Title: "Basic Information",
                lblPharmName: "Pharmacy Name *",
                lblOwnerName: "Owner Name *",
                lblPhone: "Phone Number *",
                lblEmail: "Email Address *",
                lblCity: "Governorate / City *",
                lblAddress: "Full Address *",
                lblMap: "Set Location on Map *",
                lblMapDesc: "Click on the map to accurately pinpoint the pharmacy's location or use auto-detect.",
                btnDetectLoc: "Auto-Detect Location",
                sec2Title: "Operational Details",
                lblHours: "Working Hours *",
                lblLicense: "License Number *",
                lblUpload: "Upload License Document *",
                lblDragDrop: "Click or drag and drop to upload file here",
                lblFileTypes: "Supported: PDF, PNG, JPG (Max 10MB)",
                sec3Title: "Available Services",
                srv24: "24 Hours",
                srvDelivery: "Home Delivery",
                srvConsult: "Medical Consultations",
                lblCollab: "Do you collaborate with doctors or clinics?",
                lblYes: "Yes",
                lblNo: "No",
                btnSubmit: "Submit Application",
                formTerms: "By submitting this form, you agree to our Terms & Conditions and Privacy Policy of MediNear",
                footImportantLinks: "Important Links:",
                footPrivacy: "Privacy Policy",
                footTerms: "Terms & Conditions",
                footData: "Data Protection Policy",
                footCopy: "2026 MediNear All rights reserved.",
                navDashboard: "Dashboard",
                navLogout: "Logout",
                statusReviewTitle: "Application Under Review",
                statusReviewDesc: "Thank you for joining MediNear. Our team is currently reviewing your pharmacy data and will respond soon.",
                statusApproveTitle: "Congratulations! Approved",
                statusApproveDesc: "You can now start using the pharmacy dashboard.",
                btnGoDashboard: "Go to Dashboard",
                lblCoordinates: "Coordinates",
                lblAppDate: "Application Date",
                lblSelectedServices: "Selected Services",
                lblNoServices: "No services selected",
                historyTitle: "Rejected Applications History",
                historyItemTitle: "Application:",
                badgeRejected: "Rejected",
                lblRejectDate: "Rejected on:",
                lblRejectReason: "Admin Rejection Reason:",
                lblSentDetails: "Submitted Details",
                btnNewApp: "Submit New Request",
                toastSuccessTitle: "Success!",
                toastErrorTitle: "Validation Error!",
                toastErrorDesc: "Please check the red fields below.",
                btnSubmitting: "Submitting..."
            }
        };

        let currentLang = 'ar';

        function toggleLanguage() {
            currentLang = currentLang === 'ar' ? 'en' : 'ar';
            document.documentElement.lang = currentLang;
            document.documentElement.dir = currentLang === 'ar' ? 'rtl' : 'ltr';

            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (i18n[currentLang][key]) el.innerHTML = i18n[currentLang][key];
            });

            document.getElementById('lang-text-desktop').innerHTML = currentLang === 'ar' ? 'English' : 'عربي';
            document.getElementById('lang-switch-mobile').innerHTML = currentLang === 'ar' ?
                '<i class="fa-solid fa-globe"></i> EN' : '<i class="fa-solid fa-globe"></i> AR';

            if (map) setTimeout(() => map.invalidateSize(), 400);
        }

        // --- 3. Other Interactions ---
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const btnIcon = btn.querySelector('i');

        function closeMobileMenu() {
            if (!menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                btnIcon.classList.replace('fa-xmark', 'fa-bars');
                btnIcon.style.transform = 'rotate(0deg)';
                btn.classList.remove('bg-gray-100', 'text-primary', 'shadow-inner');
            }
        }

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            if (menu.classList.contains('hidden')) {
                document.body.classList.remove('overflow-hidden');
                btnIcon.classList.replace('fa-xmark', 'fa-bars');
                btnIcon.style.transform = 'rotate(0deg)';
                btn.classList.remove('bg-gray-100', 'text-primary', 'shadow-inner');
            } else {
                document.body.classList.add('overflow-hidden');
                btnIcon.classList.replace('fa-bars', 'fa-xmark');
                btnIcon.style.transform = 'rotate(180deg)';
                btn.classList.add('bg-gray-100', 'text-primary', 'shadow-inner');
            }
        });

        // Scroll Interactions
        window.addEventListener('scroll', () => {
            const navbarInner = document.getElementById('navbar-inner');
            const goUpBtn = document.getElementById('go-up-btn');
            const scrollProgressBar = document.getElementById('scroll-progress-bar');
            const scrollY = window.scrollY;

            if (scrollY > 40) {
                navbarInner.classList.remove('h-20');
                navbarInner.classList.add('h-16');
            } else {
                navbarInner.classList.remove('h-16');
                navbarInner.classList.add('h-20');
            }

            if (scrollY > 400) {
                goUpBtn.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-10');
                goUpBtn.classList.add('opacity-100', 'pointer-events-auto', 'translate-y-0');
            } else {
                goUpBtn.classList.add('opacity-0', 'pointer-events-none', 'translate-y-10');
                goUpBtn.classList.remove('opacity-100', 'pointer-events-auto', 'translate-y-0');
            }

            if (scrollProgressBar) {
                const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                const scrolled = (scrollY / height) * 100;
                scrollProgressBar.style.width = scrolled + "%";
            }
        });

        // --- 4. Intersection Observer Animations ---
        document.addEventListener("DOMContentLoaded", () => {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.15
            };
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);
            document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
        });
    </script>
</body>

</html>
