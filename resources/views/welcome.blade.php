<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'الرئيسية') | MediNear - دوائك أقرب مما تتخيل</title>

    <meta name="title" content="MediNear - دوائك أقرب مما تتخيل">
    <meta name="description" content="منصة MediNear هي بوابتك الأسرع والأسهل للبحث عن الأدوية، العثور على أقرب صيدلية، وتوصيل طلباتك الطبية بكل أمان وموثوقية أينما كنت.">
    <meta name="keywords" content="صيدلية, أدوية, توصيل أدوية, تطبيق صيدليات, روشتة, صحة, صيدليات قريبة, MediNear">
    <meta name="author" content="MediNear Team">

    <meta name="theme-color" content="#0d9488">

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon-16x16.png') }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/apple-touch-icon.png') }}">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="MediNear - دوائك أقرب مما تتخيل">
    <meta property="og:description" content="بوابتك الأسرع والأسهل للبحث عن الأدوية والعثور على أقرب صيدلية إليك.">
    <meta property="og:image" content="{{ asset('assets/images/logo.png') }}">
    <meta property="og:site_name" content="MediNear">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="MediNear - دوائك أقرب مما تتخيل">
    <meta property="twitter:description" content="بوابتك الأسرع والأسهل للبحث عن الأدوية والعثور على أقرب صيدلية إليك.">
    <meta property="twitter:image" content="{{ asset('assets/images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Tajawal:wght@400;500;700;800;900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

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
            /* Prevent horizontal scroll on mobile */
            scroll-behavior: smooth;
        }

        /* --- Premium Professional Loader --- */
        #page-loader {
            position: fixed;
            inset: 0;
            z-index: 999999;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: opacity 0.6s cubic-bezier(0.8, 0, 0.2, 1), visibility 0.6s cubic-bezier(0.8, 0, 0.2, 1);
            overflow: hidden;
        }

        /* Ambient glowing shapes */
        #page-loader::before,
        #page-loader::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            animation: pulse-ambient 4s infinite alternate;
        }

        #page-loader::before {
            width: 400px;
            height: 400px;
            background: var(--grad-start);
            top: -100px;
            left: -100px;
        }

        #page-loader::after {
            width: 300px;
            height: 300px;
            background: var(--grad-end);
            bottom: -50px;
            right: -50px;
            animation-delay: 2s;
        }

        @keyframes pulse-ambient {
            0% {
                transform: scale(1);
                opacity: 0.1;
            }

            100% {
                transform: scale(1.2);
                opacity: 0.25;
            }
        }

        .app-logo-loader {
            width: 110px;
            height: 110px;
            animation: float-logo 3s ease-in-out infinite;
            filter: drop-shadow(0 15px 25px rgba(20, 184, 166, 0.4));
            z-index: 10;
        }

        @keyframes float-logo {
            0% {
                transform: translateY(0px) scale(1);
            }

            50% {
                transform: translateY(-15px) scale(1.05);
            }

            100% {
                transform: translateY(0px) scale(1);
            }
        }

        .logo-pulse-ring {
            position: absolute;
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(20, 184, 166, 0.2) 0%, rgba(20, 184, 166, 0) 70%);
            animation: ripple 2s linear infinite;
            z-index: 5;
        }

        @media (max-width: 768px) {
            #page-loader::before {
                width: 250px;
                height: 250px;
                top: -50px;
                left: -50px;
                filter: blur(60px);
            }

            #page-loader::after {
                width: 200px;
                height: 200px;
                bottom: -20px;
                right: -20px;
                filter: blur(60px);
            }

            .app-logo-loader {
                width: 80px;
                height: 80px;
                filter: drop-shadow(0 10px 15px rgba(20, 184, 166, 0.3));
            }

            .logo-pulse-ring {
                width: 80px;
                height: 80px;
            }

            .loader-text-wrapper {
                margin-top: 1.5rem;
                padding: 0.75rem 1.5rem;
                border-radius: 1rem;
            }

            .loader-brand-name {
                font-size: 1.25rem;
            }

            .loader-status {
                font-size: 0.75rem;
            }
        }

        @keyframes ripple {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            100% {
                transform: scale(2.2);
                opacity: 0;
            }
        }

        .loader-text-wrapper {
            margin-top: 2.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            z-index: 10;
        }

        .loader-brand-name {
            font-size: 1.75rem;
            font-weight: 900;
            letter-spacing: 2px;
            color: #1e293b;
        }

        .loader-brand-name span {
            color: var(--grad-start);
        }

        .loader-status {
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .loading-dots span {
            display: inline-block;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background-color: var(--grad-start);
            animation: bounceDots 1.4s infinite ease-in-out both;
        }

        .loading-dots span:nth-child(1) {
            animation-delay: -0.32s;
        }

        .loading-dots span:nth-child(2) {
            animation-delay: -0.16s;
        }

        @keyframes bounceDots {

            0%,
            80%,
            100% {
                transform: scale(0);
            }

            40% {
                transform: scale(1);
            }
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

        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 3rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(to right, var(--grad-start), var(--grad-end));
            border-radius: 4px;
        }

        /* FAQ Accordion */
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s ease, opacity 0.3s ease;
            opacity: 0;
        }

        .faq-item.active .faq-answer {
            max-height: 300px;
            padding-top: 1rem;
            padding-bottom: 1rem;
            opacity: 1;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
            color: var(--grad-start);
        }

        .faq-icon {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), color 0.3s ease;
        }

        /* --- Fast & Creative Interactive Cards --- */
        .card-hover {
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
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
            background: linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.6) 50%, rgba(255, 255, 255, 0) 100%);
            transform: skewX(-25deg);
            transition: left 0.7s cubic-bezier(0.25, 0.8, 0.25, 1);
            z-index: 1;
            pointer-events: none;
        }

        @media (hover: hover) {
            .card-hover:hover::before {
                left: 200%;
            }

            .card-hover:hover {
                transform: translateY(-8px);
                box-shadow: 0 25px 50px -12px rgba(13, 148, 136, 0.2), 0 0 0 1px rgba(13, 148, 136, 0.1) inset;
                border-color: transparent;
                z-index: 10;
            }

            .card-hover-visible:hover {
                transform: translateY(-8px) scale(1.01);
                box-shadow: 0 25px 50px -12px rgba(13, 148, 136, 0.15), 0 10px 20px -5px rgba(13, 148, 136, 0.08);
                border-color: rgba(13, 148, 136, 0.3);
                z-index: 10;
            }
        }

        .card-hover>* {
            position: relative;
            z-index: 2;
        }

        .card-hover-visible {
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
        }

        /* --- Swiper Carousel Overrides --- */
        .swiper-pagination-bullet {
            background: #cbd5e1;
            opacity: 1;
            transition: all 0.3s ease;
            width: 8px;
            height: 8px;
        }

        .swiper-pagination-bullet-active {
            background: var(--grad-start);
            width: 24px;
            border-radius: 4px;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: var(--grad-start);
            background: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            transform: scale(1.1);
            color: white;
            background: var(--grad-start);
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 16px;
            font-weight: bold;
        }

        /* --- Custom Animations --- */
        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-12px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
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

        /* Infinite Marquee for Partners */
        .marquee-wrapper {
            overflow: hidden;
            width: 100%;
            white-space: nowrap;
            position: relative;
        }

        .marquee-wrapper::before,
        .marquee-wrapper::after {
            content: '';
            position: absolute;
            top: 0;
            width: 60px;
            height: 100%;
            z-index: 2;
        }

        @media (min-width: 768px) {

            .marquee-wrapper::before,
            .marquee-wrapper::after {
                width: 100px;
            }
        }

        .marquee-wrapper::before {
            left: 0;
            background: linear-gradient(to right, white, transparent);
        }

        .marquee-wrapper::after {
            right: 0;
            background: linear-gradient(to left, white, transparent);
        }

        .marquee-content {
            display: inline-flex;
            width: max-content;
            animation: marquee 30s linear infinite;
        }

        .marquee-content:hover {
            animation-play-state: paused;
        }

        html[dir="rtl"] .marquee-content {
            animation: marquee-rtl 30s linear infinite;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        @keyframes marquee-rtl {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(50%);
            }
        }

        /* Advanced Scroll Reveal Classes */
        .reveal {
            opacity: 0;
            transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal-bottom {
            transform: translateY(30px);
        }

        .reveal-left {
            transform: translateX(-30px);
        }

        .reveal-right {
            transform: translateX(30px);
        }

        .reveal-scale {
            transform: scale(0.92);
        }

        .reveal.active {
            opacity: 1;
            transform: translate(0, 0) scale(1);
        }

        html[dir="rtl"] .step-line {
            transform: scaleX(-1);
        }

        .feature-list li {
            position: relative;
            padding-inline-start: 1.5rem;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
            color: #64748b;
            font-weight: 500;
        }

        .feature-list li::before {
            content: '\f00c';
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            inset-inline-start: 0;
            color: var(--grad-start);
            font-size: 0.9rem;
            top: 1px;
            background: rgba(13, 148, 136, 0.1);
            width: 1.2rem;
            height: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 1px solid #cbd5e1;
            border-radius: 0.75rem;
            outline: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
            background-color: #f8fafc;
        }

        .form-input:focus,
        .form-textarea:focus {
            border-color: var(--grad-start);
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.15);
            transform: translateY(-2px);
        }

        .leaflet-container {
            z-index: 10 !important;
            border-radius: 1.25rem;
        }

        /* Creative Grid Backgrounds */
        .bg-grid-pattern {
            background-image: radial-gradient(var(--grad-start) 1px, transparent 1px);
            background-size: 30px 30px;
            opacity: 0.05;
        }

        .bg-dots-pattern {
            background-image: radial-gradient(rgba(100, 116, 139, 0.2) 2px, transparent 2px);
            background-size: 20px 20px;
        }
    </style>
    <style>
        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateX(50px) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        .animate-toast {
            animation: toastIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
    </style>
</head>

<body class="text-darkText antialiased flex flex-col min-h-screen relative overflow-hidden">
    <div id="authRequiredModal"
        class="fixed inset-0 z-[10002] hidden flex-col items-center justify-center p-4 sm:p-0 text-center">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
            onclick="toggleModal('authRequiredModal')"></div>
        <div
            class="relative bg-white rounded-[2.5rem] w-full max-w-sm shadow-2xl overflow-hidden animate-scale-up p-8 md:p-10">
            <div
                class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6 text-primary text-3xl shadow-inner border-[6px] border-blue-100">
                <i class="fa-solid fa-user-lock animate-bounce-short"></i>
            </div>
            <h3 class="text-xl md:text-2xl font-black text-slate-800 mb-2" data-i18n="authModalTitle">تسجيل الدخول مطلوب
            </h3>
            <p class="text-sm text-gray-500 font-medium mb-8 leading-relaxed" data-i18n="authModalDesc">يجب عليك تسجيل
                الدخول أولاً لتتمكن من الوصول إلى خدمات الصيدليات والتقديم على طلب انضمام.</p>

            <div class="flex flex-col gap-3">
                <button onclick="window.location.href='{{ route('google.login') }}'"
                    class="w-full bg-gradient-custom text-white px-6 py-4 rounded-2xl font-bold shadow-lg hover:shadow-primary/30 transition-all flex items-center justify-center gap-3 group">
                    <i class="fa-brands fa-google text-xl group-hover:rotate-12 transition-transform"></i>
                    <span data-i18n="btnGoogleLogin">تسجيل الدخول عبر جوجل</span>
                </button>
                <button type="button" onclick="toggleModal('authRequiredModal')"
                    class="w-full px-6 py-3 rounded-xl font-bold text-slate-400 hover:text-slate-600 transition-colors text-sm"
                    data-i18n="navCancel">إلغاء</button>
            </div>
        </div>
    </div>
    <div
        class="fixed top-4 right-4 left-4 md:left-auto md:right-6 md:top-6 z-[1000001] flex flex-col gap-3 pointer-events-none md:w-[400px]">
        @if (session('success'))
            <div
                class="animate-toast pointer-events-auto bg-white border-r-4 border-emerald-500 shadow-2xl rounded-2xl p-4 flex items-center gap-4 w-full">
                <div class="bg-emerald-100 p-2 rounded-xl text-emerald-600 shrink-0">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                </div>
                <div class="flex-1 text-right">
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">تمت العملية</p>
                    <p class="text-sm font-black text-slate-800 leading-tight">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()"
                    class="text-slate-300 hover:text-slate-500 transition-colors shrink-0">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if (session('error') || $errors->any())
            <div
                class="animate-toast pointer-events-auto bg-white border-r-4 border-rose-500 shadow-2xl rounded-2xl p-4 flex items-center gap-4 w-full">
                <div class="bg-rose-100 p-2 rounded-xl text-rose-600 shrink-0">
                    <i class="fa-solid fa-circle-exclamation text-xl"></i>
                </div>
                <div class="flex-1 text-right">
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">تنبيه من النظام</p>
                    <p class="text-sm font-black text-slate-800 leading-tight">
                        {{ session('error') ?? $errors->first() }}
                    </p>
                </div>
                <button onclick="this.parentElement.remove()"
                    class="text-slate-300 hover:text-slate-500 transition-colors shrink-0">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
    </div>
    <div id="page-loader">
        <div class="relative flex justify-center items-center h-40 w-40">
            <div class="logo-pulse-ring" style="animation-delay: 0s;"></div>
            <div class="logo-pulse-ring" style="animation-delay: 1s;"></div>
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="app-logo-loader">
                <defs>
                    <linearGradient id="logoGradLoader" x1="80%" y1="0%" x2="20%" y2="100%">
                        <stop offset="0%" stop-color="#84cc16" />
                        <stop offset="100%" stop-color="#14b8a6" />
                    </linearGradient>
                </defs>
                <circle cx="50" cy="50" r="46" stroke="url(#logoGradLoader)" stroke-width="4" />
                <path
                    d="M50 15C34.536 15 22 27.536 22 43C22 62.5 50 85 50 85C50 85 78 62.5 78 43C78 27.536 65.464 15 50 15Z"
                    fill="url(#logoGradLoader)" />
                <path d="M45 28H55V37H64V47H55V56H45V47H36V37H45V28Z" fill="white" />
                <g transform="translate(38, 65) rotate(-50)">
                    <rect x="-14" y="-26" width="28" height="52" rx="14" fill="white" />
                    <rect x="-11" y="-23" width="22" height="46" rx="11" fill="white"
                        stroke="url(#logoGradLoader)" stroke-width="2" />
                    <path d="M-10 0 H10 V12 A10 10 0 0 1 -10 12 Z" fill="url(#logoGradLoader)" />
                </g>
            </svg>
        </div>
        <div
            class="loader-text-wrapper bg-white/60 backdrop-blur-md px-8 py-4 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/50 mt-4 relative z-10">
            <h2 class="loader-brand-name">Medi<span>Near</span></h2>
            <div class="loader-status">
                جاري التحميل
                <div class="loading-dots">
                    <span></span><span></span><span></span>
                </div>
            </div>
        </div>
    </div>

    <div id="scroll-progress-bar"
        class="fixed top-0 start-0 h-[4px] bg-gradient-custom z-[99999] w-0 transition-all duration-75 ease-out"></div>

    <div class="fixed w-full top-0 z-50 transition-all duration-300 shadow-sm" id="header-wrapper">

        <header class="w-full bg-white/95 backdrop-blur-lg border-b border-gray-100 transition-all duration-300"
            id="navbar">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 md:h-20 transition-all duration-300"
                    id="navbar-inner">
                    <a href="#" onclick="navigateTo('home')"
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

                    <nav class="hidden lg:flex items-center gap-5 xl:gap-8 font-semibold text-sm">
                        <button onclick="navigateTo('home')"
                            class="text-primary transition nav-link desktop-nav-link relative after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-primary after:scale-x-100 after:transition-transform"
                            data-section="home" id="nav-home" data-i18n="navHome">الرئيسية</button>
                        <button
                            onclick="navigateTo('home'); setTimeout(()=>document.getElementById('features').scrollIntoView({behavior: 'smooth'}), 100);"
                            class="text-gray-500 hover:text-primary transition nav-link desktop-nav-link relative after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-primary after:scale-x-0 hover:after:scale-x-100 after:transition-transform"
                            data-section="features" data-i18n="navFeatures">المميزات</button>
                        <button
                            onclick="navigateTo('home'); setTimeout(()=>document.getElementById('how-it-works').scrollIntoView({behavior: 'smooth'}), 100);"
                            class="text-gray-500 hover:text-primary transition nav-link desktop-nav-link relative after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-primary after:scale-x-0 hover:after:scale-x-100 after:transition-transform"
                            data-section="how-it-works" data-i18n="navHow">كيف يعمل</button>
                        <button
                            onclick="navigateTo('home'); setTimeout(()=>document.getElementById('reviews').scrollIntoView({behavior: 'smooth'}), 100);"
                            class="text-gray-500 hover:text-primary transition nav-link desktop-nav-link relative after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-primary after:scale-x-0 hover:after:scale-x-100 after:transition-transform"
                            data-section="reviews" data-i18n="navReviews">آراء العملاء</button>
                        <button
                            onclick="navigateTo('home'); setTimeout(()=>document.getElementById('contact').scrollIntoView({behavior: 'smooth'}), 100);"
                            class="text-gray-500 hover:text-primary transition nav-link desktop-nav-link relative after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-primary after:scale-x-0 hover:after:scale-x-100 after:transition-transform"
                            data-section="contact" data-i18n="navContact">اتصل بنا</button>

                        @if (Auth::check() && Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" data-i18n="navDashboard"
                                class="text-gray-500 hover:text-primary transition nav-link desktop-nav-link relative after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-primary after:scale-x-0 hover:after:scale-x-100 after:transition-transform"
                                id="nav-dashboard">لوحة التحكم</a>
                        @else
                            @guest
                                {{-- للزوار: يفتح مودال تسجيل الدخول --}}
                                <button onclick="toggleModal('authRequiredModal')"
                                    class="text-gray-500 hover:text-primary transition nav-link desktop-nav-link relative after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-primary after:scale-x-0 hover:after:scale-x-100 after:transition-transform"
                                    id="nav-pharmacies" data-section="pharmacies">
                                    <span data-i18n="navPharmacies">للصيدليات</span>
                                </button>
                            @else
                                {{-- للمسجلين: يذهب لصفحة التقديم مباشرة --}}
                                <a href="{{ route('pharmacy.application.index') }}"
                                    class="text-gray-500 hover:text-primary transition nav-link desktop-nav-link relative after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-primary after:scale-x-0 hover:after:scale-x-100 after:transition-transform"
                                    id="nav-pharmacies" data-section="pharmacies" data-i18n="navPharmacies">للصيدليات</a>
                            @endguest
                        @endif
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
                            <button onclick="window.location.href='{{ route('google.login') }}'"
                                class="bg-gradient-custom hover:opacity-90 text-white px-6 py-2.5 rounded-lg font-bold text-sm transition-all shadow-[0_4px_15px_rgba(13,148,136,0.4)] hover:shadow-[0_8px_25px_rgba(13,148,136,0.5)] hover:-translate-y-0.5 flex items-center gap-2">
                                <i class="fa-brands fa-google"></i>
                                <span data-i18n="btnRegPharmacy">تسجيل الدخول</span>
                            </button>
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
                    <button onclick="navigateTo('home'); closeMobileMenu();"
                        class="block w-full px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl font-medium transition-colors" data-i18n="navHome">الرئيسية</button>
                    <button
                        onclick="navigateTo('home'); setTimeout(()=>document.getElementById('features').scrollIntoView({behavior: 'smooth'}), 100); closeMobileMenu();"
                        class="block w-full px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl font-medium transition-colors" data-i18n="navFeatures">المميزات</button>
                    <button
                        onclick="navigateTo('home'); setTimeout(()=>document.getElementById('how-it-works').scrollIntoView({behavior: 'smooth'}), 100); closeMobileMenu();"
                        class="block w-full px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl font-medium transition-colors" data-i18n="navHow">كيف يعمل</button>
                    <button
                        onclick="navigateTo('home'); setTimeout(()=>document.getElementById('reviews').scrollIntoView({behavior: 'smooth'}), 100); closeMobileMenu();"
                        class="block w-full px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl font-medium transition-colors" data-i18n="navReviews">آراء العملاء</button>
                    <button
                        onclick="navigateTo('home'); setTimeout(()=>document.getElementById('contact').scrollIntoView({behavior: 'smooth'}), 100); closeMobileMenu();"
                        class="block w-full px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl font-medium transition-colors" data-i18n="navContact">اتصل بنا</button>

                    @auth
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" data-i18n="navDashboard"
                                class="block w-full px-4 py-3 text-primary font-bold bg-teal-50 rounded-xl transition-colors">لوحة
                                التحكم</a>
                        @else
                            <a href="{{ route('pharmacy.application.index') }}" data-i18n="navPharmacies"
                                class="block w-full px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl font-medium transition-colors text-center">للصيدليات</a>
                        @endif

                        <div
                            class="mt-6 flex flex-col items-center gap-2 py-4 bg-gray-50/80 rounded-2xl border border-gray-100 shadow-sm">
                            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=0d9488&color=fff' }}"
                                alt="Profile" class="w-14 h-14 rounded-full border-2 border-primary shadow-md">
                            <div class="text-center mt-2">
                                <div class="font-bold text-lg text-darkText">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ auth()->user()->role === 'admin' ? 'مدير النظام' : auth()->user()->email }}</div>
                            </div>
                            <div class="w-full px-4 mt-4">
                                <form
                                    action="{{ auth()->user()->role === 'admin' ? route('admin.logout') : route('pharmacy.logout') }}"
                                    method="POST" class="w-full">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-red-500 bg-red-50 hover:bg-red-100 px-4 py-3 rounded-xl font-bold transition-colors flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i> تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <button onclick="toggleModal('authRequiredModal'); closeMobileMenu();"
                            class="block w-full px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl font-medium transition-colors text-center" data-i18n="navPharmacies">للصيدليات</button>
                        <button onclick="window.location.href='{{ route('google.login') }}'"
                            class="w-full bg-gradient-custom text-white px-6 py-4 rounded-xl font-bold shadow-md hover:shadow-lg transition-shadow flex items-center justify-center gap-2 hover:-translate-y-0.5">
                            <i class="fa-brands fa-google text-xl -mt-0.5"></i>
                            <span data-i18n="btnRegPharmacy">تسجيل الدخول</span>
                        </button>
                    @endauth
                </div>
            </div>
        </header>
    </div>

    <div id="page-home" class="flex-grow pt-24 md:pt-32 relative">

        <section id="home"
            class="py-10 md:py-16 lg:pt-20 lg:pb-24 px-4 sm:px-6 lg:px-8 container mx-auto relative overflow-hidden">
            <div
                class="absolute top-0 right-0 w-full lg:w-1/2 h-full bg-gradient-to-b lg:bg-gradient-to-l from-secondary/80 to-transparent rounded-bl-[100px] z-0">
            </div>
            <div class="flex flex-col lg:flex-row items-center justify-between gap-12 lg:gap-8 relative z-10">
                <div class="w-full lg:w-1/2 text-center lg:text-start reveal reveal-right mt-4 lg:mt-0">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-darkText leading-tight mb-4 md:mb-6"
                        data-i18n="heroTitle">
                        دوائك أقرب <br> مما <span class="text-gradient">تتخيل</span>
                    </h1>

                    <p class="text-base md:text-lg text-gray-600 mb-8 md:mb-10 max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium"
                        data-i18n="heroDesc">
                        MediNear هو تطبيق ذكي يساعدك في العثور على الأدوية المتوفرة في أقرب صيدلية وطلبها بسهولة مع
                        توصيل سريع وخدمات ذكية متقدمة.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <button
                            class="w-full sm:w-auto bg-gray-900 text-white px-6 py-3 rounded-xl transition-all hover:-translate-y-1 hover:shadow-[0_15px_30px_rgba(0,0,0,0.2)] flex items-center justify-center gap-3 group border border-gray-800">
                            <i
                                class="fa-brands fa-google-play text-2xl md:text-3xl text-accent transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-12"></i>
                            <div class="text-start leading-tight">
                                <span class="block text-[9px] md:text-[10px] text-gray-300 uppercase tracking-wider"
                                    data-i18n="btnGetItOn">احصل عليه من</span>
                                <span class="block text-sm md:text-base font-bold tracking-wide font-sans"
                                    data-i18n="btnGooglePlay">Google Play</span>
                            </div>
                        </button>
                        @guest
                            {{-- تم تغيير window.location إلى toggleModal لفتح المودال --}}
                            <button onclick="toggleModal('authRequiredModal')"
                                class="w-full sm:w-auto bg-white border-2 border-gray-100 hover:border-primary text-primary px-6 py-3.5 rounded-xl font-bold text-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg flex items-center justify-center gap-2 group">
                                <span data-i18n="btnRegPharmacyAndIcon">سجل كصيدلية شريكة</span>
                                <i
                                    class="fa-solid fa-arrow-left rtl:rotate-0 ltr:rotate-180 transition-transform duration-300 group-hover:-translate-x-1 rtl:group-hover:-translate-x-1 ltr:group-hover:translate-x-1"></i>
                            </button>
                        @endguest
                        @auth
                            @if (Auth::user()->role != 'admin')
                                <button onclick="window.location.href='{{ route('pharmacy.application.index') }}'"
                                    class="w-full sm:w-auto bg-white border-2 border-gray-100 hover:border-primary text-primary px-6 py-3.5 rounded-xl font-bold text-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg flex items-center justify-center gap-2 group">
                                    <span data-i18n="btnRegPharmacyAndIcon">سجل كصيدلية شريكة</span>
                                    <i
                                        class="fa-solid fa-arrow-left rtl:rotate-0 ltr:rotate-180 transition-transform duration-300 group-hover:-translate-x-1 rtl:group-hover:-translate-x-1 ltr:group-hover:translate-x-1"></i>
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>

                <div class="w-full lg:w-1/2 relative reveal reveal-left">
                    <div class="relative w-full max-w-lg flex justify-center animate-float mx-auto">
                        <div class="absolute inset-0 bg-primary opacity-10 rounded-full transform scale-110 blur-3xl">
                        </div>

                        <div
                            class="relative w-[260px] h-[540px] sm:w-[320px] sm:h-[620px] bg-[#f8fafc] rounded-[2.5rem] sm:rounded-[3rem] shadow-2xl border-[6px] sm:border-[8px] border-gray-800 mx-auto overflow-hidden flex flex-col z-10 transition-transform duration-500 hover:scale-[1.02]">
                            <div
                                class="absolute top-0 left-1/2 transform -translate-x-1/2 w-24 sm:w-32 h-5 sm:h-6 bg-gray-800 rounded-b-2xl sm:rounded-b-3xl z-30">
                            </div>

                            <div class="flex-1 overflow-hidden px-4 sm:px-5 pt-10 sm:pt-12 pb-20 relative z-20 w-full bg-white"
                                dir="rtl" id="mockup-dir">
                                <div class="flex justify-between items-center mb-5 sm:mb-6">
                                    <div>
                                        <p class="text-[10px] sm:text-[11px] text-gray-500 font-medium"
                                            data-i18n="uiLocLabel">التوصيل إلى</p>
                                        <p class="text-xs sm:text-sm font-bold text-darkText flex items-center gap-1">
                                            <i class="fa-solid fa-location-dot text-primary"></i> <span
                                                data-i18n="uiLocValue">المنزل - القاهرة</span>
                                        </p>
                                    </div>
                                    <div
                                        class="w-8 h-8 sm:w-10 sm:h-10 bg-secondary rounded-full flex items-center justify-center shadow-sm text-primary relative border border-primary/20 cursor-pointer">
                                        <i class="fa-regular fa-bell text-sm sm:text-base"></i>
                                        <span
                                            class="absolute top-1.5 sm:top-2 right-1.5 sm:right-2.5 w-2 h-2 sm:w-2.5 sm:h-2.5 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
                                    </div>
                                </div>

                                <div
                                    class="bg-gray-50 rounded-xl sm:rounded-2xl p-3 flex items-center gap-3 mb-5 sm:mb-6 border border-gray-100 cursor-text">
                                    <i class="fa-solid fa-magnifying-glass text-primary text-sm"></i>
                                    <span class="text-gray-400 text-[10px] sm:text-xs font-medium"
                                        data-i18n="uiSearch">ابحث عن دواء، صيدلية...</span>
                                </div>

                                <div
                                    class="bg-gradient-custom rounded-xl sm:rounded-2xl p-3 sm:p-4 flex items-center justify-between shadow-md mb-5 sm:mb-6 text-white relative overflow-hidden group cursor-pointer">
                                    <div
                                        class="absolute top-0 right-0 w-20 h-20 sm:w-24 sm:h-24 bg-white opacity-10 rounded-full -mr-10 -mt-10 transition-transform duration-500 group-hover:scale-150">
                                    </div>
                                    <div class="relative z-10">
                                        <h4 class="font-bold text-xs sm:text-sm mb-1" data-i18n="uiPrescTitle">لديك
                                            وصفة
                                            طبية؟</h4>
                                        <p class="text-[9px] sm:text-[10px] opacity-90 max-w-[120px] sm:max-w-[140px] leading-tight"
                                            data-i18n="uiPrescDesc">قم بتصوير الروشتة وسنقوم بتجهيز الأدوية فوراً</p>
                                    </div>
                                    <div
                                        class="w-8 h-8 sm:w-10 sm:h-10 bg-white/20 rounded-full flex items-center justify-center text-white shrink-0 relative z-10">
                                        <i class="fa-solid fa-camera text-sm"></i>
                                    </div>
                                </div>

                                <div class="flex justify-between mb-5 sm:mb-6">
                                    <div class="flex flex-col items-center gap-1.5 sm:gap-2">
                                        <div
                                            class="w-10 h-10 sm:w-12 sm:h-12 bg-teal-50 text-teal-600 rounded-xl sm:rounded-2xl flex items-center justify-center text-base sm:text-lg shadow-sm">
                                            <i class="fa-solid fa-pills"></i>
                                        </div>
                                        <span class="text-[9px] sm:text-[10px] font-bold text-gray-600"
                                            data-i18n="uiCatMeds">أدوية</span>
                                    </div>
                                    <div class="flex flex-col items-center gap-1.5 sm:gap-2">
                                        <div
                                            class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-50 text-emerald-600 rounded-xl sm:rounded-2xl flex items-center justify-center text-base sm:text-lg shadow-sm">
                                            <i class="fa-solid fa-pump-soap"></i>
                                        </div>
                                        <span class="text-[9px] sm:text-[10px] font-bold text-gray-600"
                                            data-i18n="uiCatCare">عناية</span>
                                    </div>
                                    <div class="flex flex-col items-center gap-1.5 sm:gap-2">
                                        <div
                                            class="w-10 h-10 sm:w-12 sm:h-12 bg-cyan-50 text-cyan-600 rounded-xl sm:rounded-2xl flex items-center justify-center text-base sm:text-lg shadow-sm">
                                            <i class="fa-solid fa-baby-carriage"></i>
                                        </div>
                                        <span class="text-[9px] sm:text-[10px] font-bold text-gray-600"
                                            data-i18n="uiCatBaby">أم وطفل</span>
                                    </div>
                                    <div class="flex flex-col items-center gap-1.5 sm:gap-2">
                                        <div
                                            class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 text-blue-600 rounded-xl sm:rounded-2xl flex items-center justify-center text-base sm:text-lg shadow-sm">
                                            <i class="fa-solid fa-user-doctor"></i>
                                        </div>
                                        <span class="text-[9px] sm:text-[10px] font-bold text-gray-600"
                                            data-i18n="uiCatConsult">استشارة</span>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center mb-2 sm:mb-3">
                                    <h3 class="font-bold text-xs sm:text-sm text-darkText" data-i18n="uiNearPharm">
                                        أقرب
                                        صيدلية</h3>
                                    <span class="text-[9px] sm:text-[10px] text-primary font-bold">عرض الكل</span>
                                </div>
                                <div
                                    class="bg-white rounded-xl sm:rounded-2xl p-2 sm:p-3 shadow-sm border border-gray-100 flex gap-2 sm:gap-3 items-center">
                                    <div
                                        class="w-10 h-10 sm:w-14 sm:h-14 bg-secondary text-primary rounded-lg sm:rounded-xl flex items-center justify-center text-base sm:text-xl shrink-0">
                                        <i class="fa-solid fa-store"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-xs sm:text-sm text-darkText"
                                            data-i18n="uiPharmName">
                                            صيدلية الشفاء</h4>
                                        <p
                                            class="text-[9px] sm:text-[10px] text-gray-500 mb-0.5 sm:mb-1 flex items-center gap-1">
                                            <i class="fa-solid fa-star text-yellow-400 text-[8px]"></i> 4.8 (120+)
                                        </p>
                                        <p
                                            class="text-[9px] sm:text-[10px] text-primary font-bold flex items-center gap-1">
                                            <i class="fa-solid fa-motorcycle text-[8px]"></i> <span
                                                data-i18n="uiPharmTime">توصيل 15 دقيقة</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="absolute bottom-0 w-full h-[60px] sm:h-[70px] bg-white border-t border-gray-100 flex justify-around items-center px-1 sm:px-2 z-30 pb-1 sm:pb-2 rounded-b-[2rem] sm:rounded-b-[2.5rem]">
                                <div class="flex flex-col items-center text-primary w-10 sm:w-12"><i
                                        class="fa-solid fa-house text-sm sm:text-lg mb-0.5 sm:mb-1"></i><span
                                        class="text-[8px] sm:text-[9px] font-bold"
                                        data-i18n="uiNavHome">الرئيسية</span>
                                </div>
                                <div class="flex flex-col items-center text-gray-400 w-10 sm:w-12"><i
                                        class="fa-solid fa-receipt text-sm sm:text-lg mb-0.5 sm:mb-1"></i><span
                                        class="text-[8px] sm:text-[9px] font-bold"
                                        data-i18n="uiNavOrders">طلباتي</span>
                                </div>
                                <div class="flex flex-col items-center relative w-10 sm:w-12 group">
                                    <div
                                        class="absolute -top-6 sm:-top-8 w-12 h-12 sm:w-14 sm:h-14 bg-gradient-custom rounded-full flex items-center justify-center text-white shadow-lg border-4 border-white text-xl sm:text-2xl">
                                        <i class="fa-solid fa-qrcode"></i>
                                    </div>
                                </div>
                                <div class="flex flex-col items-center text-gray-400 w-10 sm:w-12"><i
                                        class="fa-solid fa-comment-medical text-sm sm:text-lg mb-0.5 sm:mb-1"></i><span
                                        class="text-[8px] sm:text-[9px] font-bold" data-i18n="uiNavChat">محادثة</span>
                                </div>
                                <div class="flex flex-col items-center text-gray-400 w-10 sm:w-12"><i
                                        class="fa-regular fa-user text-sm sm:text-lg mb-0.5 sm:mb-1"></i><span
                                        class="text-[8px] sm:text-[9px] font-bold"
                                        data-i18n="uiNavProfile">حسابي</span>
                                </div>
                            </div>
                        </div>

                        <div
                            class="absolute top-16 start-0 sm:-start-4 lg:-start-8 z-40 bg-white p-3 sm:p-4 rounded-xl sm:rounded-2xl shadow-xl flex items-center gap-3 sm:gap-4 border border-gray-50 transform sm:hover:scale-105 transition-transform scale-90 sm:scale-100 origin-start">
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center text-lg sm:text-xl">
                                <i class="fa-solid fa-check-circle"></i>
                            </div>
                            <div>
                                <p class="text-[10px] sm:text-xs text-gray-500 font-semibold uppercase tracking-wider"
                                    data-i18n="orderStatus">حالة الطلب</p>
                                <p class="text-xs sm:text-sm font-bold text-emerald-600" data-i18n="orderDelivered">تم
                                    التوصيل</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div
            class="absolute top-1/4 left-10 w-64 h-64 bg-primary/5 rounded-full blur-3xl pointer-events-none z-0 hidden md:block">
        </div>
        <div
            class="absolute bottom-1/4 right-10 w-96 h-96 bg-accent/5 rounded-full blur-3xl pointer-events-none z-0 hidden md:block">
        </div>

        <section class="py-16 md:py-20 relative bg-white overflow-hidden">
            <div class="absolute inset-0 bg-dots-pattern opacity-50 z-0"></div>

            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center reveal reveal-bottom">
                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-darkText section-title"
                        data-i18n="whyTitle">
                        لماذا تحتاج MediNear؟
                    </h2>
                </div>

                <div class="flex flex-wrap justify-center gap-4 md:gap-6 mt-8 md:mt-12">
                    <div
                        class="bg-white rounded-3xl p-6 md:p-8 text-center border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)] card-hover group reveal reveal-scale relative overflow-hidden">
                        <div
                            class="absolute -bottom-6 -right-6 text-8xl md:text-9xl text-gray-50 opacity-40 group-hover:text-primary/5 transition-all duration-500 group-hover:scale-110 group-hover:-rotate-12 pointer-events-none z-0">
                            <i class="fa-solid fa-circle-exclamation"></i>
                        </div>
                        <div class="relative z-10">
                            <div
                                class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-secondary to-teal-100 text-primary rounded-2xl flex items-center justify-center text-xl md:text-2xl mx-auto mb-4 md:mb-6 transition-all duration-300 group-hover:scale-110 group-hover:-rotate-12 group-hover:shadow-[0_0_20px_rgba(13,148,136,0.3)]">
                                <i class="fa-solid fa-circle-exclamation"></i>
                            </div>
                            <h3 class="font-bold text-sm md:text-base text-darkText" data-i18n="why1Title">صعوبة
                                العثور
                                على الأدوية<br>النادرة أو المزمنة</h3>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl p-6 md:p-8 text-center border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)] card-hover group reveal reveal-scale relative overflow-hidden"
                        style="transition-delay: 0.1s;">
                        <div
                            class="absolute -bottom-6 -right-6 text-8xl md:text-9xl text-gray-50 opacity-40 group-hover:text-primary/5 transition-all duration-500 group-hover:scale-110 group-hover:-rotate-12 pointer-events-none z-0">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div class="relative z-10">
                            <div
                                class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-secondary to-teal-100 text-primary rounded-2xl flex items-center justify-center text-xl md:text-2xl mx-auto mb-4 md:mb-6 transition-all duration-300 group-hover:scale-110 group-hover:-rotate-12 group-hover:shadow-[0_0_20px_rgba(13,148,136,0.3)]">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <h3 class="font-bold text-sm md:text-base text-darkText" data-i18n="why2Title">زيارة عدة
                                صيدليات للعثور<br>على الدواء</h3>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl p-6 md:p-8 text-center border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)] card-hover group reveal reveal-scale relative overflow-hidden"
                        style="transition-delay: 0.2s;">
                        <div
                            class="absolute -bottom-6 -right-6 text-8xl md:text-9xl text-gray-50 opacity-40 group-hover:text-primary/5 transition-all duration-500 group-hover:scale-110 group-hover:-rotate-12 pointer-events-none z-0">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                        <div class="relative z-10">
                            <div
                                class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-secondary to-teal-100 text-primary rounded-2xl flex items-center justify-center text-xl md:text-2xl mx-auto mb-4 md:mb-6 transition-all duration-300 group-hover:scale-110 group-hover:-rotate-12 group-hover:shadow-[0_0_20px_rgba(13,148,136,0.3)]">
                                <i class="fa-regular fa-clock"></i>
                            </div>
                            <h3 class="font-bold text-sm md:text-base text-darkText" data-i18n="why3Title">عدم وجود
                                تذكيرات فعالة<br>بمواعيد الدواء</h3>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl p-6 md:p-8 text-center border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)] card-hover group reveal reveal-scale relative overflow-hidden"
                        style="transition-delay: 0.3s;">
                        <div
                            class="absolute -bottom-6 -right-6 text-8xl md:text-9xl text-gray-50 opacity-40 group-hover:text-primary/5 transition-all duration-500 group-hover:scale-110 group-hover:-rotate-12 pointer-events-none z-0">
                            <i class="fa-regular fa-comments"></i>
                        </div>
                        <div class="relative z-10">
                            <div
                                class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-secondary to-teal-100 text-primary rounded-2xl flex items-center justify-center text-xl md:text-2xl mx-auto mb-4 md:mb-6 transition-all duration-300 group-hover:scale-110 group-hover:-rotate-12 group-hover:shadow-[0_0_20px_rgba(13,148,136,0.3)]">
                                <i class="fa-regular fa-comments"></i>
                            </div>
                            <h3 class="font-bold text-sm md:text-base text-darkText" data-i18n="why4Title">وصول محدود
                                لاستشارات<br>سريعة مع الصيدلي</h3>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl p-6 md:p-8 text-center border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)] card-hover group reveal reveal-scale relative overflow-hidden"
                        style="transition-delay: 0.4s;">
                        <div
                            class="absolute -bottom-6 -right-6 text-8xl md:text-9xl text-gray-50 opacity-40 group-hover:text-primary/5 transition-all duration-500 group-hover:scale-110 group-hover:-rotate-12 pointer-events-none z-0">
                            <i class="fa-regular fa-file-lines"></i>
                        </div>
                        <div class="relative z-10">
                            <div
                                class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-secondary to-teal-100 text-primary rounded-2xl flex items-center justify-center text-xl md:text-2xl mx-auto mb-4 md:mb-6 transition-all duration-300 group-hover:scale-110 group-hover:-rotate-12 group-hover:shadow-[0_0_20px_rgba(13,148,136,0.3)]">
                                <i class="fa-regular fa-file-lines"></i>
                            </div>
                            <h3 class="font-bold text-sm md:text-base text-darkText" data-i18n="why5Title">عدم وجود
                                سجل
                                صحي رقمي<br>مركزي</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="py-16 md:py-24 bg-gray-50 border-y border-gray-100 relative">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center reveal reveal-bottom">
                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-darkText section-title"
                        data-i18n="featTitle">
                        مميزات التطبيق
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8 mt-8 md:mt-12">
                    <div
                        class="bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-gray-100 card-hover group flex flex-col reveal reveal-bottom relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-gradient-custom opacity-5 rounded-bl-[100px] transition-transform duration-500 group-hover:scale-125 z-0">
                        </div>
                        <div class="relative z-10">
                            <div
                                class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-secondary to-teal-100 text-primary rounded-2xl flex items-center justify-center text-2xl md:text-3xl mb-4 md:mb-6 mx-auto sm:mx-0 transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>
                            <h3 class="font-bold text-lg md:text-xl mb-4 md:mb-6 text-darkText text-center sm:text-start"
                                data-i18n="feat1Title">بحث ذكي عن الدواء</h3>
                            <ul class="feature-list" id="feat1List">
                                <li>البحث باسم الدواء</li>
                                <li>عرض أقرب صيدلية يتوفر بها الدواء</li>
                                <li>تكامل مع الخرائط التفاعلية</li>
                                <li>تفاصيل التواصل مع الصيدلية</li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-gray-100 card-hover group flex flex-col reveal reveal-bottom relative overflow-hidden"
                        style="transition-delay: 0.1s;">
                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-gradient-custom opacity-5 rounded-bl-[100px] transition-transform duration-500 group-hover:scale-125 z-0">
                        </div>
                        <div class="relative z-10">
                            <div
                                class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-secondary to-teal-100 text-primary rounded-2xl flex items-center justify-center text-2xl md:text-3xl mb-4 md:mb-6 mx-auto sm:mx-0 transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <h3 class="font-bold text-lg md:text-xl mb-4 md:mb-6 text-darkText text-center sm:text-start"
                                data-i18n="feat2Title">الطلب والتوصيل</h3>
                            <ul class="feature-list" id="feat2List">
                                <li>شراء عبر الإنترنت</li>
                                <li>خيارات دفع متعددة</li>
                                <li>تتبع الطلب في الوقت الفعلي</li>
                                <li>دعم التوصيل الطارئ والليلي</li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-gray-100 card-hover group flex flex-col reveal reveal-bottom relative overflow-hidden"
                        style="transition-delay: 0.2s;">
                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-gradient-custom opacity-5 rounded-bl-[100px] transition-transform duration-500 group-hover:scale-125 z-0">
                        </div>
                        <div class="relative z-10">
                            <div
                                class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-secondary to-teal-100 text-primary rounded-2xl flex items-center justify-center text-2xl md:text-3xl mb-4 md:mb-6 mx-auto sm:mx-0 transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6">
                                <i class="fa-regular fa-clock"></i>
                            </div>
                            <h3 class="font-bold text-lg md:text-xl mb-4 md:mb-6 text-darkText text-center sm:text-start"
                                data-i18n="feat3Title">تذكيرات ذكية</h3>
                            <ul class="feature-list" id="feat3List">
                                <li>تنبيهات بمواعيد الأدوية</li>
                                <li>تنبيهات التنوية بنقص المخزون</li>
                                <li>اقتراحات إعادة الطلب التلقائية</li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-gray-100 card-hover group flex flex-col reveal reveal-bottom relative overflow-hidden"
                        style="transition-delay: 0.3s;">
                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-gradient-custom opacity-5 rounded-bl-[100px] transition-transform duration-500 group-hover:scale-125 z-0">
                        </div>
                        <div class="relative z-10">
                            <div
                                class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-secondary to-teal-100 text-primary rounded-2xl flex items-center justify-center text-2xl md:text-3xl mb-4 md:mb-6 mx-auto sm:mx-0 transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6">
                                <i class="fa-solid fa-clipboard-list"></i>
                            </div>
                            <h3 class="font-bold text-lg md:text-xl mb-4 md:mb-6 text-darkText text-center sm:text-start"
                                data-i18n="feat4Title">المحفظة الطبية</h3>
                            <ul class="feature-list" id="feat4List">
                                <li>رفع الوصفات الطبية (PDF أو صورة)</li>
                                <li>حفظ الملاحظات الطبية</li>
                                <li>مشاركة الوصفة مباشرة مع الصيدلية</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="how-it-works" class="py-16 md:py-24 bg-white overflow-hidden relative">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center reveal reveal-bottom">
                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-darkText section-title"
                        data-i18n="howTitle">
                        كيف يعمل التطبيق؟
                    </h2>
                </div>

                <div
                    class="grid grid-cols-4 items-start gap-2 sm:gap-4 md:gap-8 mt-10 md:mt-16 relative max-w-5xl mx-auto">
                    <div
                        class="absolute top-8 sm:top-10 md:top-12 start-[10%] w-[80%] h-[2px] border-t-2 border-dashed border-gray-200 z-0">
                    </div>
                    <div class="absolute top-8 sm:top-10 md:top-12 start-[10%] w-[80%] h-[2px] bg-gradient-custom z-0 rtl:origin-right ltr:origin-left scale-x-0 transition-transform duration-[1.5s] ease-out shadow-[0_0_10px_rgba(13,148,136,0.8)]"
                        id="progress-line"></div>

                    <div class="relative z-10 flex flex-col items-center text-center w-full reveal reveal-scale group">
                        <div
                            class="w-12 h-12 sm:w-16 sm:h-16 md:w-24 md:h-24 bg-white border-2 md:border-4 border-gray-50 group-hover:border-primary transition-colors duration-500 rounded-full flex items-center justify-center mb-3 md:mb-6 relative shadow-md md:shadow-lg group-hover:shadow-[0_0_15px_rgba(13,148,136,0.3)] md:group-hover:shadow-[0_0_25px_rgba(13,148,136,0.3)]">
                            <span
                                class="absolute -top-1 -end-1 md:-top-2 md:-end-2 bg-gradient-custom text-white w-5 h-5 sm:w-6 sm:h-6 md:w-8 md:h-8 rounded-full text-[10px] sm:text-xs md:text-sm font-bold flex items-center justify-center shadow-sm md:shadow-md animate-bounce">1</span>
                            <i
                                class="fa-solid fa-magnifying-glass text-lg sm:text-xl md:text-3xl text-gray-400 group-hover:text-primary group-hover:scale-110 transition-all duration-500"></i>
                        </div>
                        <h3 class="font-bold text-[10px] sm:text-sm md:text-lg text-darkText leading-tight hidden sm:block"
                            data-i18n="how1Title">ابحث عن دوائك</h3>
                        <h3 class="font-bold text-[10px] text-darkText leading-tight sm:hidden"
                            data-i18n="how1TitleShort">ابحث</h3>
                    </div>

                    <div class="relative z-10 flex flex-col items-center text-center w-full reveal reveal-scale group"
                        style="transition-delay: 0.2s;">
                        <div
                            class="w-12 h-12 sm:w-16 sm:h-16 md:w-24 md:h-24 bg-white border-2 md:border-4 border-gray-50 group-hover:border-primary transition-colors duration-500 rounded-full flex items-center justify-center mb-3 md:mb-6 relative shadow-md md:shadow-lg group-hover:shadow-[0_0_15px_rgba(13,148,136,0.3)] md:group-hover:shadow-[0_0_25px_rgba(13,148,136,0.3)]">
                            <span
                                class="absolute -top-1 -end-1 md:-top-2 md:-end-2 bg-gradient-custom text-white w-5 h-5 sm:w-6 sm:h-6 md:w-8 md:h-8 rounded-full text-[10px] sm:text-xs md:text-sm font-bold flex items-center justify-center shadow-sm md:shadow-md animate-bounce"
                                style="animation-delay: 0.1s">2</span>
                            <i
                                class="fa-solid fa-store text-lg sm:text-xl md:text-3xl text-gray-400 group-hover:text-primary group-hover:scale-110 transition-all duration-500"></i>
                        </div>
                        <h3 class="font-bold text-[10px] sm:text-sm md:text-lg text-darkText leading-tight hidden sm:block"
                            data-i18n="how2Title">اختر أقرب صيدلية</h3>
                        <h3 class="font-bold text-[10px] text-darkText leading-tight sm:hidden"
                            data-i18n="how2TitleShort">اختر</h3>
                    </div>

                    <div class="relative z-10 flex flex-col items-center text-center w-full reveal reveal-scale group"
                        style="transition-delay: 0.4s;">
                        <div
                            class="w-12 h-12 sm:w-16 sm:h-16 md:w-24 md:h-24 bg-white border-2 md:border-4 border-gray-50 group-hover:border-primary transition-colors duration-500 rounded-full flex items-center justify-center mb-3 md:mb-6 relative shadow-md md:shadow-lg group-hover:shadow-[0_0_15px_rgba(13,148,136,0.3)] md:group-hover:shadow-[0_0_25px_rgba(13,148,136,0.3)]">
                            <span
                                class="absolute -top-1 -end-1 md:-top-2 md:-end-2 bg-gradient-custom text-white w-5 h-5 sm:w-6 sm:h-6 md:w-8 md:h-8 rounded-full text-[10px] sm:text-xs md:text-sm font-bold flex items-center justify-center shadow-sm md:shadow-md animate-bounce"
                                style="animation-delay: 0.2s">3</span>
                            <i
                                class="fa-solid fa-wallet text-lg sm:text-xl md:text-3xl text-gray-400 group-hover:text-primary group-hover:scale-110 transition-all duration-500"></i>
                        </div>
                        <h3 class="font-bold text-[10px] sm:text-sm md:text-lg text-darkText leading-tight hidden sm:block"
                            data-i18n="how3Title">اختر طريقة الدفع</h3>
                        <h3 class="font-bold text-[10px] text-darkText leading-tight sm:hidden"
                            data-i18n="how3TitleShort">ادفع</h3>
                    </div>

                    <div class="relative z-10 flex flex-col items-center text-center w-full reveal reveal-scale group"
                        style="transition-delay: 0.6s;">
                        <div
                            class="w-12 h-12 sm:w-16 sm:h-16 md:w-24 md:h-24 bg-white border-2 md:border-4 border-gray-50 group-hover:border-primary transition-colors duration-500 rounded-full flex items-center justify-center mb-3 md:mb-6 relative shadow-md md:shadow-lg group-hover:shadow-[0_0_15px_rgba(13,148,136,0.3)] md:group-hover:shadow-[0_0_25px_rgba(13,148,136,0.3)]">
                            <span
                                class="absolute -top-1 -end-1 md:-top-2 md:-end-2 bg-gradient-custom text-white w-5 h-5 sm:w-6 sm:h-6 md:w-8 md:h-8 rounded-full text-[10px] sm:text-xs md:text-sm font-bold flex items-center justify-center shadow-sm md:shadow-md animate-bounce"
                                style="animation-delay: 0.3s">4</span>
                            <i
                                class="fa-solid fa-box-open text-lg sm:text-xl md:text-3xl text-gray-400 group-hover:text-primary group-hover:scale-110 transition-all duration-500"></i>
                        </div>
                        <h3 class="font-bold text-[10px] sm:text-sm md:text-lg text-darkText leading-tight hidden sm:block"
                            data-i18n="how4Title">استلم طلبك</h3>
                        <h3 class="font-bold text-[10px] text-darkText leading-tight sm:hidden"
                            data-i18n="how4TitleShort">استلم</h3>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-[#0f172a] py-20 md:py-28 relative overflow-hidden group/stats">
            <!-- Background Ambience -->
            <div class="absolute inset-0 opacity-20"
                style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 32px 32px;">
            </div>

            <!-- Glowing Orbs -->
            <div
                class="absolute top-0 right-1/4 w-[500px] h-[500px] bg-primary/30 rounded-full blur-[120px] mix-blend-screen pointer-events-none group-hover/stats:scale-110 transition-transform duration-[3s]">
            </div>
            <div
                class="absolute bottom-0 left-1/4 w-[400px] h-[400px] bg-accent/20 rounded-full blur-[100px] mix-blend-screen pointer-events-none group-hover/stats:scale-110 transition-transform duration-[3s] delay-300">
            </div>

            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center reveal reveal-bottom mb-14 md:mb-20">
                    <h2
                        class="inline-block text-sm md:text-base font-bold text-accent tracking-widest uppercase mb-4 px-4 py-1.5 rounded-full border border-accent/30 bg-accent/10">
                        أرقام نفخر بها</h2>
                    <h3 class="text-3xl md:text-4xl lg:text-5xl font-black text-white leading-tight">
                        ثقتكم هي <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-primary">سر
                            نجاحنا</span>
                    </h3>
                </div>

                <div class="grid grid-cols-3 gap-2 md:gap-8 max-w-6xl mx-auto">
                    <!-- Stat Card 1 -->
                    <div
                        class="relative p-[1px] rounded-2xl md:rounded-3xl overflow-hidden reveal reveal-bottom bg-gradient-to-br from-white/20 to-white/0 group">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-primary/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                        <div
                            class="h-full w-full bg-[#1e293b]/80 backdrop-blur-xl p-3 sm:p-5 md:p-10 rounded-[15px] md:rounded-[23px] text-center border border-white/5 relative z-10 flex flex-col items-center justify-center transform group-hover:-translate-y-1 transition-transform duration-300">
                            <div
                                class="w-10 h-10 sm:w-14 sm:h-14 md:w-20 md:h-20 bg-gradient-to-br from-primary/20 to-primary/5 rounded-xl md:rounded-2xl border border-primary/20 flex items-center justify-center text-primary text-xl sm:text-2xl md:text-4xl mb-3 md:mb-6 shadow-[0_0_30px_rgba(13,148,136,0.15)] group-hover:scale-110 transition-transform duration-500">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <h3
                                class="text-xl sm:text-3xl md:text-5xl lg:text-6xl font-black text-white mb-1.5 md:mb-3 tracking-tight flex items-baseline justify-center gap-1">
                                <span class="stat-number" data-target="100" data-suffix="K">+0K</span>
                            </h3>
                            <p class="text-gray-400 font-medium text-[9px] sm:text-xs md:text-base uppercase tracking-wider"
                                data-i18n="stat1">مستخدم نشط</p>
                        </div>
                    </div>

                    <!-- Stat Card 2 -->
                    <div class="relative p-[1px] rounded-2xl md:rounded-3xl overflow-hidden reveal reveal-bottom bg-gradient-to-br from-white/20 to-white/0 group"
                        style="transition-delay: 0.1s;">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-accent/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                        <div
                            class="h-full w-full bg-[#1e293b]/80 backdrop-blur-xl p-3 sm:p-5 md:p-10 rounded-[15px] md:rounded-[23px] text-center border border-white/5 relative z-10 flex flex-col items-center justify-center transform group-hover:-translate-y-1 transition-transform duration-300">
                            <div
                                class="w-10 h-10 sm:w-14 sm:h-14 md:w-20 md:h-20 bg-gradient-to-br from-accent/20 to-accent/5 rounded-xl md:rounded-2xl border border-accent/20 flex items-center justify-center text-accent text-xl sm:text-2xl md:text-4xl mb-3 md:mb-6 shadow-[0_0_30px_rgba(132,204,22,0.15)] group-hover:scale-110 transition-transform duration-500">
                                <i class="fa-solid fa-house-medical"></i>
                            </div>
                            <h3
                                class="text-xl sm:text-3xl md:text-5xl lg:text-6xl font-black text-white mb-1.5 md:mb-3 tracking-tight flex items-baseline justify-center gap-1">
                                <span class="stat-number" data-target="1200" data-suffix="">+0</span>
                            </h3>
                            <p class="text-gray-400 font-medium text-[9px] sm:text-xs md:text-base uppercase tracking-wider"
                                data-i18n="stat2">صيدلية شريكة</p>
                        </div>
                    </div>

                    <!-- Stat Card 3 -->
                    <div class="relative p-[1px] rounded-2xl md:rounded-3xl overflow-hidden reveal reveal-bottom bg-gradient-to-br from-white/20 to-white/0 group"
                        style="transition-delay: 0.2s;">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-primary/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                        <div
                            class="h-full w-full bg-[#1e293b]/80 backdrop-blur-xl p-3 sm:p-5 md:p-10 rounded-[15px] md:rounded-[23px] text-center border border-white/5 relative z-10 flex flex-col items-center justify-center transform group-hover:-translate-y-1 transition-transform duration-300">
                            <div
                                class="w-10 h-10 sm:w-14 sm:h-14 md:w-20 md:h-20 bg-gradient-to-br from-emerald-500/20 to-emerald-500/5 rounded-xl md:rounded-2xl border border-emerald-500/20 flex items-center justify-center text-emerald-400 text-xl sm:text-2xl md:text-4xl mb-3 md:mb-6 shadow-[0_0_30px_rgba(16,185,129,0.15)] group-hover:scale-110 transition-transform duration-500">
                                <i class="fa-solid fa-motorcycle"></i>
                            </div>
                            <h3
                                class="text-xl sm:text-3xl md:text-5xl lg:text-6xl font-black text-white mb-1.5 md:mb-3 tracking-tight flex items-baseline justify-center gap-1">
                                <span class="stat-number" data-target="50" data-suffix="K">+0K</span>
                            </h3>
                            <p class="text-gray-400 font-medium text-[9px] sm:text-xs md:text-base uppercase tracking-wider"
                                data-i18n="stat3">طلب ناجح</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-10 md:py-14 bg-white border-b border-gray-100 overflow-hidden">
            <div class="container mx-auto px-4 text-center reveal reveal-bottom">
                <p class="text-gray-400 text-xs md:text-sm font-bold mb-6 md:mb-10 uppercase tracking-widest"
                    data-i18n="partnersTitle">شركاء النجاح</p>

                <div class="marquee-wrapper">
                    <div class="marquee-content gap-12 md:gap-32 text-gray-300">
                        <div
                            class="text-xl md:text-2xl font-black font-sans hover:text-primary transition-colors duration-300 cursor-default flex items-center gap-2 md:gap-3 grayscale hover:grayscale-0">
                            <i class="fa-solid fa-staff-snake text-2xl md:text-3xl"></i> CureAll
                        </div>
                        <div
                            class="text-xl md:text-2xl font-black font-sans hover:text-primary transition-colors duration-300 cursor-default flex items-center gap-2 md:gap-3 grayscale hover:grayscale-0">
                            <i class="fa-solid fa-prescription-bottle-medical text-2xl md:text-3xl"></i> LifeLine
                        </div>
                        <div
                            class="text-xl md:text-2xl font-black font-sans hover:text-primary transition-colors duration-300 cursor-default flex items-center gap-2 md:gap-3 grayscale hover:grayscale-0">
                            <i class="fa-solid fa-heart-pulse text-2xl md:text-3xl"></i> MediCare
                        </div>
                        <div
                            class="text-xl md:text-2xl font-black font-sans hover:text-primary transition-colors duration-300 cursor-default flex items-center gap-2 md:gap-3 grayscale hover:grayscale-0">
                            <i class="fa-solid fa-notes-medical text-2xl md:text-3xl"></i> HealthPlus
                        </div>
                        <div
                            class="text-xl md:text-2xl font-black font-sans hover:text-primary transition-colors duration-300 cursor-default flex items-center gap-2 md:gap-3 grayscale hover:grayscale-0">
                            <i class="fa-solid fa-suitcase-medical text-2xl md:text-3xl"></i> PharmaCorp
                        </div>
                        <div
                            class="text-xl md:text-2xl font-black font-sans hover:text-primary transition-colors duration-300 cursor-default flex items-center gap-2 md:gap-3 grayscale hover:grayscale-0">
                            <i class="fa-solid fa-staff-snake text-2xl md:text-3xl"></i> CureAll
                        </div>
                        <div
                            class="text-xl md:text-2xl font-black font-sans hover:text-primary transition-colors duration-300 cursor-default flex items-center gap-2 md:gap-3 grayscale hover:grayscale-0">
                            <i class="fa-solid fa-prescription-bottle-medical text-2xl md:text-3xl"></i> LifeLine
                        </div>
                        <div
                            class="text-xl md:text-2xl font-black font-sans hover:text-primary transition-colors duration-300 cursor-default flex items-center gap-2 md:gap-3 grayscale hover:grayscale-0">
                            <i class="fa-solid fa-heart-pulse text-2xl md:text-3xl"></i> MediCare
                        </div>
                        <div
                            class="text-xl md:text-2xl font-black font-sans hover:text-primary transition-colors duration-300 cursor-default flex items-center gap-2 md:gap-3 grayscale hover:grayscale-0">
                            <i class="fa-solid fa-notes-medical text-2xl md:text-3xl"></i> HealthPlus
                        </div>
                        <div
                            class="text-xl md:text-2xl font-black font-sans hover:text-primary transition-colors duration-300 cursor-default flex items-center gap-2 md:gap-3 grayscale hover:grayscale-0">
                            <i class="fa-solid fa-suitcase-medical text-2xl md:text-3xl"></i> PharmaCorp
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="reviews" class="py-16 md:py-24 bg-gray-50/50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center reveal reveal-bottom">
                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-darkText section-title"
                        data-i18n="reviewsTitle">
                        آراء العملاء
                    </h2>
                </div>

                <div class="mt-8 md:mt-12 relative reveal reveal-bottom">
                    <!-- Swiper Main Container -->
                    <div class="swiper reviews-swiper py-10 px-4 sm:px-6 lg:px-8 pb-16 h-full">
                        <div class="swiper-wrapper items-stretch">

                            <!-- Review Slide 1 -->
                            <div class="swiper-slide h-auto">
                                <div
                                    class="bg-white p-6 md:p-8 rounded-3xl border border-gray-100 text-center card-hover-visible relative h-full flex flex-col justify-between">
                                    <div>
                                        <div
                                            class="absolute -top-6 md:-top-8 left-1/2 transform -translate-x-1/2 bg-gradient-custom text-white w-12 h-12 md:w-16 md:h-16 rounded-full shadow-[0_10px_20px_rgba(13,148,136,0.3)] flex items-center justify-center text-xl md:text-2xl z-20">
                                            <i class="fa-solid fa-quote-right rtl:scale-x-[-1]"></i>
                                        </div>
                                        <div
                                            class="flex text-yellow-400 text-xs md:text-sm mb-4 md:mb-6 justify-center mt-4">
                                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                                class="fa-solid fa-star"></i>
                                        </div>
                                        <p class="text-gray-600 text-sm md:text-base leading-relaxed mb-6 md:mb-8 italic"
                                            data-i18n="revText1">"تطبيق رائع جداً، ساعدني في العثور على دواء نادر
                                            لوالدي
                                            في دقائق
                                            معدودة. خدمة التوصيل كانت سريعة وممتازة."</p>
                                    </div>
                                    <div
                                        class="flex items-center justify-center gap-3 md:gap-4 border-t border-gray-50 pt-4 md:pt-6 mt-auto">
                                        <div
                                            class="w-10 h-10 md:w-12 md:h-12 bg-secondary rounded-full flex items-center justify-center text-primary font-black text-lg md:text-xl shrink-0">
                                            أ</div>
                                        <div class="text-start">
                                            <h4 class="font-bold text-xs md:text-sm text-darkText"
                                                data-i18n="revName1">
                                                أحمد محمد</h4>
                                            <span class="text-[10px] md:text-xs text-gray-400 font-bold uppercase"
                                                data-i18n="revRole1">مستخدم</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Review Slide 2 -->
                            <div class="swiper-slide h-auto">
                                <div
                                    class="bg-white p-6 md:p-8 rounded-3xl border border-gray-100 text-center card-hover-visible relative h-full flex flex-col justify-between">
                                    <div>
                                        <div
                                            class="absolute -top-6 md:-top-8 left-1/2 transform -translate-x-1/2 bg-gradient-custom text-white w-12 h-12 md:w-16 md:h-16 rounded-full shadow-[0_10px_20px_rgba(13,148,136,0.3)] flex items-center justify-center text-xl md:text-2xl z-20">
                                            <i class="fa-solid fa-quote-right rtl:scale-x-[-1]"></i>
                                        </div>
                                        <div
                                            class="flex text-yellow-400 text-xs md:text-sm mb-4 md:mb-6 justify-center mt-4">
                                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                                class="fa-solid fa-star"></i>
                                        </div>
                                        <p class="text-gray-600 text-sm md:text-base leading-relaxed mb-6 md:mb-8 italic"
                                            data-i18n="revText2">"الآن يمكنني طلب الأدوية الشهرية بسهولة وتصلني في
                                            موعدها دون الحاجة
                                            للنزول والبحث في الصيدليات."</p>
                                    </div>
                                    <div
                                        class="flex items-center justify-center gap-3 md:gap-4 border-t border-gray-50 pt-4 md:pt-6 mt-auto">
                                        <div
                                            class="w-10 h-10 md:w-12 md:h-12 bg-secondary rounded-full flex items-center justify-center text-primary font-black text-lg md:text-xl shrink-0">
                                            س</div>
                                        <div class="text-start">
                                            <h4 class="font-bold text-xs md:text-sm text-darkText"
                                                data-i18n="revName2">
                                                سارة علي</h4>
                                            <span class="text-[10px] md:text-xs text-gray-400 font-bold uppercase"
                                                data-i18n="revRole2">مستخدم</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Review Slide 3 -->
                            <div class="swiper-slide h-auto">
                                <div
                                    class="bg-white p-6 md:p-8 rounded-3xl border border-gray-100 text-center card-hover-visible relative h-full flex flex-col justify-between">
                                    <div>
                                        <div
                                            class="absolute -top-6 md:-top-8 left-1/2 transform -translate-x-1/2 bg-gradient-custom text-white w-12 h-12 md:w-16 md:h-16 rounded-full shadow-[0_10px_20px_rgba(13,148,136,0.3)] flex items-center justify-center text-xl md:text-2xl z-20">
                                            <i class="fa-solid fa-quote-right rtl:scale-x-[-1]"></i>
                                        </div>
                                        <div
                                            class="flex text-yellow-400 text-xs md:text-sm mb-4 md:mb-6 justify-center mt-4">
                                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                                class="fa-solid fa-star-half-stroke"></i>
                                        </div>
                                        <p class="text-gray-600 text-sm md:text-base leading-relaxed mb-6 md:mb-8 italic"
                                            data-i18n="revText3">"ميزة الاستشارات الصيدلانية ممتازة، الصيدلي أجاب على
                                            كل
                                            استفساراتي حول
                                            الدواء الجديد بشكل احترافي."</p>
                                    </div>
                                    <div
                                        class="flex items-center justify-center gap-3 md:gap-4 border-t border-gray-50 pt-4 md:pt-6 mt-auto">
                                        <div
                                            class="w-10 h-10 md:w-12 md:h-12 bg-secondary rounded-full flex items-center justify-center text-primary font-black text-lg md:text-xl shrink-0">
                                            م</div>
                                        <div class="text-start">
                                            <h4 class="font-bold text-xs md:text-sm text-darkText"
                                                data-i18n="revName3">
                                                محمود حسن</h4>
                                            <span class="text-[10px] md:text-xs text-gray-400 font-bold uppercase"
                                                data-i18n="revRole3">مستخدم</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Review Slide 4 (Extra mock for carousel scrolling) -->
                            <div class="swiper-slide h-auto">
                                <div
                                    class="bg-white p-6 md:p-8 rounded-3xl border border-gray-100 text-center card-hover-visible relative h-full flex flex-col justify-between">
                                    <div>
                                        <div
                                            class="absolute -top-6 md:-top-8 left-1/2 transform -translate-x-1/2 bg-gradient-custom text-white w-12 h-12 md:w-16 md:h-16 rounded-full shadow-[0_10px_20px_rgba(13,148,136,0.3)] flex items-center justify-center text-xl md:text-2xl z-20">
                                            <i class="fa-solid fa-quote-right rtl:scale-x-[-1]"></i>
                                        </div>
                                        <div
                                            class="flex text-yellow-400 text-xs md:text-sm mb-4 md:mb-6 justify-center mt-4">
                                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                                class="fa-solid fa-star"></i>
                                        </div>
                                        <p class="text-gray-600 text-sm md:text-base leading-relaxed mb-6 md:mb-8 italic"
                                            data-i18n="revText4">
                                            "تجربة مستخدم راقية وخدمة عملاء متجاوبة دائماً. أوصي بهذا التطبيق بشدة لكل
                                            من يحتاج أدوية بشكل دوري."
                                        </p>
                                    </div>
                                    <div
                                        class="flex items-center justify-center gap-3 md:gap-4 border-t border-gray-50 pt-4 md:pt-6 mt-auto">
                                        <div
                                            class="w-10 h-10 md:w-12 md:h-12 bg-secondary rounded-full flex items-center justify-center text-primary font-black text-lg md:text-xl shrink-0">
                                            ع</div>
                                        <div class="text-start">
                                            <h4 class="font-bold text-xs md:text-sm text-darkText"
                                                data-i18n="revName4">عمر فاروق</h4>
                                            <span class="text-[10px] md:text-xs text-gray-400 font-bold uppercase"
                                                data-i18n="revRole4">الطبيب
                                                المعالج</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Review Slide 5 (Extra mock for carousel scrolling) -->
                            <div class="swiper-slide h-auto">
                                <div
                                    class="bg-white p-6 md:p-8 rounded-3xl border border-gray-100 text-center card-hover-visible relative h-full flex flex-col justify-between">
                                    <div>
                                        <div
                                            class="absolute -top-6 md:-top-8 left-1/2 transform -translate-x-1/2 bg-gradient-custom text-white w-12 h-12 md:w-16 md:h-16 rounded-full shadow-[0_10px_20px_rgba(13,148,136,0.3)] flex items-center justify-center text-xl md:text-2xl z-20">
                                            <i class="fa-solid fa-quote-right rtl:scale-x-[-1]"></i>
                                        </div>
                                        <div
                                            class="flex text-yellow-400 text-xs md:text-sm mb-4 md:mb-6 justify-center mt-4">
                                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                                class="fa-solid fa-star-half-stroke"></i>
                                        </div>
                                        <p class="text-gray-600 text-sm md:text-base leading-relaxed mb-6 md:mb-8 italic"
                                            data-i18n="revText5">
                                            "الأسعار مناسبة جداً مقارنة بالتطبيقات الأخرى، كما أن واجهة التطبيق مريحة
                                            للعين وسهلة الاستخدام حتى لكبار السن."
                                        </p>
                                    </div>
                                    <div
                                        class="flex items-center justify-center gap-3 md:gap-4 border-t border-gray-50 pt-4 md:pt-6 mt-auto">
                                        <div
                                            class="w-10 h-10 md:w-12 md:h-12 bg-secondary rounded-full flex items-center justify-center text-primary font-black text-lg md:text-xl shrink-0">
                                            ن</div>
                                        <div class="text-start">
                                            <h4 class="font-bold text-xs md:text-sm text-darkText"
                                                data-i18n="revName5">نور إبراهيم</h4>
                                            <span class="text-[10px] md:text-xs text-gray-400 font-bold uppercase"
                                                data-i18n="revRole5">ربة
                                                منزل</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Swiper Pagination Controls -->
                        <div class="swiper-pagination mt-8"></div>
                    </div>

                    <!-- Swiper Navigation Arrows (Hidden on mobile) -->
                    <div class="swiper-button-prev hidden md:flex" style="left: -20px;"></div>
                    <div class="swiper-button-next hidden md:flex" style="right: -20px;"></div>
                </div>
            </div>
        </section>

        <section id="faq" class="py-16 md:py-24 bg-white relative">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl relative z-10">
                <div class="text-center reveal reveal-bottom">
                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-darkText section-title"
                        data-i18n="faqTitle">
                        الأسئلة الشائعة
                    </h2>
                </div>

                <div class="space-y-3 md:space-y-4 mt-8 md:mt-12 reveal reveal-scale">
                    <div class="faq-item bg-white rounded-xl md:rounded-2xl border-2 border-gray-50 shadow-[0_4px_15px_rgba(0,0,0,0.02)] overflow-hidden cursor-pointer transition-all duration-300 hover:border-primary/40 hover:shadow-md"
                        onclick="toggleFaq(this)">
                        <div class="flex justify-between items-center p-4 md:p-6">
                            <h3 class="font-bold text-darkText text-sm md:text-lg" data-i18n="faq1Q">كيف يمكنني طلب
                                دواء
                                غير متوفر في الصيدليات القريبة؟</h3>
                            <i
                                class="fa-solid fa-chevron-down text-gray-400 faq-icon text-sm md:text-lg bg-gray-50 w-6 h-6 md:w-8 md:h-8 rounded-full flex items-center justify-center shrink-0"></i>
                        </div>
                        <div class="faq-answer px-4 md:px-6 text-gray-600 text-xs md:text-base leading-relaxed border-t border-gray-50 bg-gray-50/30"
                            data-i18n="faq1A">
                            يقوم التطبيق بالبحث في شبكة واسعة من الصيدليات، وإذا لم يكن الدواء متوفراً في نطاقك الجغرافي
                            القريب، سيقترح عليك صيدليات أبعد مع خيار التوصيل السريع.
                        </div>
                    </div>
                    <div class="faq-item bg-white rounded-xl md:rounded-2xl border-2 border-gray-50 shadow-[0_4px_15px_rgba(0,0,0,0.02)] overflow-hidden cursor-pointer transition-all duration-300 hover:border-primary/40 hover:shadow-md"
                        onclick="toggleFaq(this)">
                        <div class="flex justify-between items-center p-4 md:p-6">
                            <h3 class="font-bold text-darkText text-sm md:text-lg" data-i18n="faq2Q">هل التطبيق مجاني
                                للاستخدام؟</h3>
                            <i
                                class="fa-solid fa-chevron-down text-gray-400 faq-icon text-sm md:text-lg bg-gray-50 w-6 h-6 md:w-8 md:h-8 rounded-full flex items-center justify-center shrink-0"></i>
                        </div>
                        <div class="faq-answer px-4 md:px-6 text-gray-600 text-xs md:text-base leading-relaxed border-t border-gray-50 bg-gray-50/30"
                            data-i18n="faq2A">
                            نعم، التطبيق مجاني تماماً للتحميل والبحث عن الأدوية. أنت تدفع فقط ثمن الدواء ورسوم التوصيل
                            الخاصة بالصيدلية المختارة.
                        </div>
                    </div>
                    <div class="faq-item bg-white rounded-xl md:rounded-2xl border-2 border-gray-50 shadow-[0_4px_15px_rgba(0,0,0,0.02)] overflow-hidden cursor-pointer transition-all duration-300 hover:border-primary/40 hover:shadow-md"
                        onclick="toggleFaq(this)">
                        <div class="flex justify-between items-center p-4 md:p-6">
                            <h3 class="font-bold text-darkText text-sm md:text-lg" data-i18n="faq3Q">ما هي طرق الدفع
                                المتاحة؟</h3>
                            <i
                                class="fa-solid fa-chevron-down text-gray-400 faq-icon text-sm md:text-lg bg-gray-50 w-6 h-6 md:w-8 md:h-8 rounded-full flex items-center justify-center shrink-0"></i>
                        </div>
                        <div class="faq-answer px-4 md:px-6 text-gray-600 text-xs md:text-base leading-relaxed border-t border-gray-50 bg-gray-50/30"
                            data-i18n="faq3A">
                            نوفر طرق دفع متعددة تشمل الدفع عند الاستلام، البطاقات الائتمانية، والمحافظ الإلكترونية لضمان
                            راحة جميع المستخدمين.
                        </div>
                    </div>
                    <div class="faq-item bg-white rounded-xl md:rounded-2xl border-2 border-gray-50 shadow-[0_4px_15px_rgba(0,0,0,0.02)] overflow-hidden cursor-pointer transition-all duration-300 hover:border-primary/40 hover:shadow-md"
                        onclick="toggleFaq(this)">
                        <div class="flex justify-between items-center p-4 md:p-6">
                            <h3 class="font-bold text-darkText text-sm md:text-lg" data-i18n="faq4Q">هل يمكنني إرجاع
                                الدواء إذا كان غير مطابق للطلب؟</h3>
                            <i
                                class="fa-solid fa-chevron-down text-gray-400 faq-icon text-sm md:text-lg bg-gray-50 w-6 h-6 md:w-8 md:h-8 rounded-full flex items-center justify-center shrink-0"></i>
                        </div>
                        <div class="faq-answer px-4 md:px-6 text-gray-600 text-xs md:text-base leading-relaxed border-t border-gray-50 bg-gray-50/30"
                            data-i18n="faq4A">
                            نعم، يمكنك استرجاع أو استبدال الدواء خلال 24 ساعة من استلامه طالما كانت العلبة مغلقة ولم
                            تتضرر بناءً على سياسة الاسترجاع الخاصة بوزارة الصحة.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="contact" class="py-16 md:py-24 bg-gray-50 border-t border-gray-100 relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern opacity-30"></div>

            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center reveal reveal-bottom">
                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-darkText section-title"
                        data-i18n="contactTitle">
                        تواصل معنا
                    </h2>
                    <p class="text-gray-500 mb-10 md:mb-16 max-w-xl mx-auto text-sm md:text-lg"
                        data-i18n="contactSub">
                        نحن هنا لمساعدتك! لا تتردد في إرسال استفساراتك أو ملاحظاتك وسنقوم بالرد عليك في أقرب وقت.</p>
                </div>

                <div class="flex flex-col lg:flex-row gap-8 md:gap-12 max-w-6xl mx-auto">
                    <div class="w-full lg:w-1/3 space-y-4 md:space-y-6 reveal reveal-right">
                        <div
                            class="bg-white p-5 md:p-6 rounded-2xl border border-gray-100 shadow-sm card-hover-visible flex items-center gap-4 md:gap-5 group">
                            <div
                                class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-secondary to-teal-100 text-primary rounded-xl flex items-center justify-center text-lg md:text-xl shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-12">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-darkText mb-1 text-xs md:text-sm uppercase tracking-wide"
                                    data-i18n="contactInfoTitle">العنوان</h4>
                                <p class="text-xs md:text-sm text-gray-500 font-medium">مبنى رقم 45، شارع التحرير،
                                    القاهرة، مصر</p>
                            </div>
                        </div>
                        <div class="bg-white p-5 md:p-6 rounded-2xl border border-gray-100 shadow-sm card-hover-visible flex items-center gap-4 md:gap-5 group"
                            style="transition-delay: 0.1s;">
                            <div
                                class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-secondary to-teal-100 text-primary rounded-xl flex items-center justify-center text-lg md:text-xl shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-12">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-darkText mb-1 text-xs md:text-sm uppercase tracking-wide"
                                    data-i18n="topPhone">رقم الهاتف</h4>
                                <p class="text-xs md:text-sm text-gray-500 font-medium" dir="ltr">+20 100 123
                                    4567</p>
                            </div>
                        </div>
                        <div class="bg-white p-5 md:p-6 rounded-2xl border border-gray-100 shadow-sm card-hover-visible flex items-center gap-4 md:gap-5 group"
                            style="transition-delay: 0.2s;">
                            <div
                                class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-secondary to-teal-100 text-primary rounded-xl flex items-center justify-center text-lg md:text-xl shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-12">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-darkText mb-1 text-xs md:text-sm uppercase tracking-wide"
                                    data-i18n="topEmail">البريد الإلكتروني</h4>
                                <p class="text-xs md:text-sm text-gray-500 font-medium">info@medinear.com</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="w-full lg:w-2/3 bg-white p-6 sm:p-8 md:p-12 rounded-3xl shadow-xl border border-gray-100 reveal reveal-left relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-custom"></div>
                        <form
                            onsubmit="event.preventDefault(); alert(currentLang==='ar'?'تم إرسال رسالتك بنجاح!':'Message sent successfully!');">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-4 md:mb-6">
                                <div>
                                    <label class="block text-xs md:text-sm font-bold text-gray-700 mb-1.5 md:mb-2"
                                        data-i18n="lblContactName">الاسم بالكامل *</label>
                                    <input type="text" required
                                        class="form-input bg-gray-50/50 focus:bg-white text-sm md:text-base">
                                </div>
                                <div>
                                    <label class="block text-xs md:text-sm font-bold text-gray-700 mb-1.5 md:mb-2"
                                        data-i18n="lblContactEmail">البريد الإلكتروني *</label>
                                    <input type="email" required
                                        class="form-input bg-gray-50/50 focus:bg-white text-sm md:text-base"
                                        dir="ltr">
                                </div>
                            </div>
                            <div class="mb-6 md:mb-8">
                                <label class="block text-xs md:text-sm font-bold text-gray-700 mb-1.5 md:mb-2"
                                    data-i18n="lblContactMsg">الرسالة أو الاستفسار *</label>
                                <textarea required class="form-textarea bg-gray-50/50 focus:bg-white h-24 md:h-32 resize-none text-sm md:text-base"></textarea>
                            </div>
                            <button type="submit"
                                class="bg-gradient-custom hover:opacity-90 text-white px-8 md:px-10 py-3.5 md:py-4 rounded-xl font-bold text-sm md:text-base transition-all w-full sm:w-auto shadow-[0_10px_20px_rgba(13,148,136,0.3)] hover:-translate-y-1 flex items-center justify-center gap-3 mx-auto sm:mx-0">
                                <span data-i18n="btnSendMsg">إرسال الرسالة</span>
                                <i class="fa-solid fa-paper-plane rtl:scale-x-[-1]"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-12 md:py-20 px-4 sm:px-6 lg:px-8 container mx-auto reveal reveal-bottom">
            <div
                class="bg-gradient-custom rounded-[2rem] sm:rounded-[3rem] px-6 py-12 md:p-20 text-center text-white relative overflow-hidden shadow-[0_20px_50px_rgba(13,148,136,0.4)]">
                <div
                    class="absolute top-0 end-0 w-60 h-60 md:w-80 md:h-80 bg-white opacity-10 rounded-full blur-3xl -me-10 -mt-10 animate-pulse">
                </div>
                <div class="absolute bottom-0 start-0 w-48 h-48 md:w-64 md:h-64 bg-white opacity-10 rounded-full blur-2xl -ms-10 -mb-10 animate-pulse"
                    style="animation-delay: 1s"></div>

                <div class="relative z-10 max-w-3xl mx-auto">
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-black mb-4 md:mb-6 drop-shadow-md"
                        data-i18n="ctaTitle">دوائك أقرب مما تتخيل</h2>
                    <p class="text-white/90 mb-8 md:mb-10 text-sm sm:text-base md:text-xl leading-relaxed font-medium"
                        data-i18n="ctaDesc">
                        MediNear هو تطبيق ذكي يساعدك في العثور على الأدوية المتوفرة في أقرب صيدلية وطلبها بسهولة مع
                        توصيل سريع وخدمات ذكية متقدمة.
                    </p>

                    <button
                        class="bg-gray-900 text-white px-8 md:px-10 py-3 md:py-4 rounded-xl md:rounded-2xl transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] flex items-center justify-center gap-3 md:gap-4 group border border-gray-800 mx-auto mt-2 md:mt-4 w-full sm:w-auto">
                        <i
                            class="fa-brands fa-google-play text-2xl md:text-4xl text-accent transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-12"></i>
                        <div class="text-start leading-tight">
                            <span
                                class="block text-[10px] md:text-xs text-gray-300 uppercase tracking-widest font-bold mb-0.5 md:mb-1"
                                data-i18n="btnGetItOn">احصل عليه من</span>
                            <span class="block text-lg md:text-xl font-black tracking-wide font-sans"
                                data-i18n="btnGooglePlay">Google Play</span>
                        </div>
                    </button>
                </div>
            </div>
        </section>
    </div>

    <div id="page-pharmacies" class="flex-grow pt-24 md:pt-32 hidden bg-gray-50/30">

        <section
            class="py-12 md:py-20 px-4 sm:px-6 lg:px-8 container mx-auto text-center reveal reveal-bottom relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern pointer-events-none"></div>
            <div class="absolute top-10 left-10 w-24 h-24 bg-primary/10 rounded-full blur-xl animate-pulse"></div>
            <div class="absolute bottom-10 right-10 w-32 h-32 bg-accent/10 rounded-full blur-xl animate-pulse"></div>

            <div class="relative z-10 max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 px-5 md:px-6 py-2 md:py-2.5 rounded-full bg-white text-primary text-xs md:text-sm font-bold mb-6 md:mb-8 shadow-md border border-primary/20 animate-float"
                    data-i18n="pharmTag">
                    <i class="fa-solid fa-handshake text-base md:text-lg"></i> للصيدليات
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-darkText mb-6 md:mb-8 leading-tight drop-shadow-sm"
                    data-i18n="pharmHeroTitle">
                    انضم إلى شبكة MediNear وقم بتوسيع نطاق عملك
                </h1>
                <p class="text-gray-500 text-sm md:text-lg font-medium max-w-2xl mx-auto px-4 md:px-0"
                    data-i18n="pharmHeroDesc">سجل صيدليتك الآن وابدأ في استقبال آلاف الطلبات من المرضى في منطقتك بكل
                    سهولة.</p>
            </div>
        </section>

        <section class="pb-16 md:pb-20 px-4 sm:px-6 lg:px-8 container mx-auto relative z-10">
            <div class="flex flex-wrap justify-center gap-4 md:gap-6">
                <div
                    class="bg-white p-5 md:p-6 rounded-2xl md:rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 text-center w-full sm:w-[calc(50%-8px)] lg:w-[calc(20%-20px)] card-hover group reveal reveal-scale">
                    <div
                        class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-secondary to-teal-100 text-primary rounded-2xl flex items-center justify-center text-xl md:text-2xl mx-auto mb-4 md:mb-5 transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-12 group-hover:shadow-[0_0_20px_rgba(13,148,136,0.3)]">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                    </div>
                    <h3 class="font-bold text-xs md:text-sm text-darkText uppercase tracking-wide"
                        data-i18n="pharmBen1">زيادة المبيعات</h3>
                </div>
                <div class="bg-white p-5 md:p-6 rounded-2xl md:rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 text-center w-full sm:w-[calc(50%-8px)] lg:w-[calc(20%-20px)] card-hover group reveal reveal-scale"
                    style="transition-delay: 0.1s;">
                    <div
                        class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-secondary to-teal-100 text-primary rounded-2xl flex items-center justify-center text-xl md:text-2xl mx-auto mb-4 md:mb-5 transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-12 group-hover:shadow-[0_0_20px_rgba(13,148,136,0.3)]">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h3 class="font-bold text-xs md:text-sm text-darkText uppercase tracking-wide"
                        data-i18n="pharmBen2">الوصول لعملاء جدد</h3>
                </div>
                <div class="bg-white p-5 md:p-6 rounded-2xl md:rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 text-center w-full sm:w-[calc(50%-8px)] lg:w-[calc(20%-20px)] card-hover group reveal reveal-scale"
                    style="transition-delay: 0.2s;">
                    <div
                        class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-secondary to-teal-100 text-primary rounded-2xl flex items-center justify-center text-xl md:text-2xl mx-auto mb-4 md:mb-5 transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-12 group-hover:shadow-[0_0_20px_rgba(13,148,136,0.3)]">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    <h3 class="font-bold text-xs md:text-sm text-darkText uppercase tracking-wide"
                        data-i18n="pharmBen3">إدارة الطلبات بسهولة</h3>
                </div>
                <div class="bg-white p-5 md:p-6 rounded-2xl md:rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 text-center w-full sm:w-[calc(50%-8px)] lg:w-[calc(20%-20px)] card-hover group reveal reveal-scale"
                    style="transition-delay: 0.3s;">
                    <div
                        class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-secondary to-teal-100 text-primary rounded-2xl flex items-center justify-center text-xl md:text-2xl mx-auto mb-4 md:mb-5 transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-12 group-hover:shadow-[0_0_20px_rgba(13,148,136,0.3)]">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <h3 class="font-bold text-xs md:text-sm text-darkText uppercase tracking-wide"
                        data-i18n="pharmBen4">الوصول للوحة التحليلات</h3>
                </div>
                <div class="bg-white p-5 md:p-6 rounded-2xl md:rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 text-center w-full sm:w-[calc(50%-8px)] lg:w-[calc(20%-20px)] card-hover group reveal reveal-scale"
                    style="transition-delay: 0.4s;">
                    <div
                        class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-secondary to-teal-100 text-primary rounded-2xl flex items-center justify-center text-xl md:text-2xl mx-auto mb-4 md:mb-5 transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-12 group-hover:shadow-[0_0_20px_rgba(13,148,136,0.3)]">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                    <h3 class="font-bold text-xs md:text-sm text-darkText uppercase tracking-wide"
                        data-i18n="pharmBen5">عملية تسجيل بسيطة</h3>
                </div>
            </div>
        </section>

        <section class="pb-16 md:pb-24 px-4 sm:px-6 lg:px-8 container mx-auto reveal reveal-bottom relative z-10">
            <div
                class="bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-2xl border border-gray-50 p-6 sm:p-8 md:p-12 lg:p-16 max-w-5xl mx-auto relative overflow-hidden text-center">
                <div class="absolute top-0 left-0 w-full h-2 md:h-3 bg-gradient-custom"></div>

                <div class="mb-10 mt-4 md:mt-0">
                    <h2 class="text-2xl sm:text-3xl md:text-3xl font-black text-darkText mb-4" data-i18n="formTitle">
                        التقديم كصيدلية شريكة</h2>
                    <p class="text-gray-500 text-sm md:text-base font-medium mb-8" data-i18n="formSub">انتقل إلى صفحة
                        التسجيل لتقديم طلب انضمام صيدليتك إلى شبكتنا في خطوات بسيطة.</p>

                    <a href="pharmacies.html"
                        class="inline-flex items-center justify-center gap-3 bg-gradient-custom hover:opacity-90 text-white px-10 py-4 md:py-5 rounded-xl md:rounded-2xl font-black text-lg md:text-xl transition-all duration-300 shadow-[0_10px_20px_rgba(13,148,136,0.3)] hover:-translate-y-1 w-full sm:w-auto mx-auto group">
                        <span data-i18n="btnGoRegPage">الذهاب لصفحة التسجيل</span>
                        <i class="fa-solid fa-arrow-left transition-transform duration-500 group-hover:-translate-x-1"
                            id="link-arrow"></i>
                    </a>
                </div>
            </div>
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
                    <button
                        onclick="navigateTo('home'); setTimeout(()=>document.getElementById('features').scrollIntoView({behavior:'smooth'}), 100);"
                        class="hover:text-white transition-colors" data-i18n="navFeatures">المميزات</button>
                    <button
                        onclick="navigateTo('home'); setTimeout(()=>document.getElementById('how-it-works').scrollIntoView({behavior:'smooth'}), 100);"
                        class="hover:text-white transition-colors" data-i18n="navHow">كيف يعمل</button>
                    <button
                        onclick="navigateTo('home'); setTimeout(()=>document.getElementById('contact').scrollIntoView({behavior:'smooth'}), 100);"
                        class="hover:text-white transition-colors" data-i18n="navContact">اتصل بنا</button>
                    <button onclick="window.location.href='pharmacies.html'"
                        class="hover:text-white transition-colors" data-i18n="navPharmacies">للصيدليات</button>
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

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <script>
        // Store current translations separately to keep track of dynamic updates
        const currentTranslations = window.translations || {};

        // --- 0. Page Loader ---
        window.addEventListener('load', () => {
            const loader = document.getElementById('page-loader');
            if (loader) {
                // Loader waits precisely 2 seconds
                setTimeout(() => {
                    loader.style.opacity = '0';
                    loader.style.transition = 'opacity 0.6s ease-out';
                    setTimeout(() => {
                        loader.style.display = 'none';
                        document.body.classList.remove('overflow-hidden');
                    }, 600); // Wait for opacity transition to finish
                }, 2000);
            }
        });

        // --- Swiper Initiative ---
        const initReviewsSwiper = () => {
            const isRTL = document.documentElement.dir === 'rtl';

            if (window.reviewsSwiper) {
                window.reviewsSwiper.destroy(true, true);
            }

            window.reviewsSwiper = new Swiper('.reviews-swiper', {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                dir: isRTL ? 'rtl' : 'ltr',
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    0: {
                        slidesPerView: 1,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 25,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                    },
                }
            });
        };
        // Setup initial carousels
        document.addEventListener('DOMContentLoaded', () => {
            initReviewsSwiper();
        });

        // --- 1. Navigation Logic (SPA Routing & ScrollSpy) ---
        function navigateTo(pageId) {
            document.getElementById('page-home').classList.add('hidden');
            document.getElementById('page-pharmacies').classList.add('hidden');
            document.getElementById('page-' + pageId).classList.remove('hidden');
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

            // Recreate swipers with new direction
            setTimeout(() => {
                if (typeof initReviewsSwiper === 'function') {
                    initReviewsSwiper();
                }
            }, 50);

            // Re-render map tiles explicitly
            const mapElement = document.getElementById('pharmacy-map');
            if (mapElement && !document.getElementById('page-pharmacies').classList.contains('hidden')) {
                setTimeout(() => {
                    map.invalidateSize();
                }, 300);
            }
        }

        function updateActiveLink(currentSection) {
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                const section = link.getAttribute('data-section');
                if (link.classList.contains('desktop-nav-link')) {
                    if (section === currentSection) {
                        link.classList.remove('text-gray-500', 'after:scale-x-0');
                        link.classList.add('text-primary', 'after:scale-x-100');
                    } else if (section !== 'pharmacies' || currentSection === 'home') {
                        link.classList.remove('text-primary', 'after:scale-x-100');
                        link.classList.add('text-gray-500', 'after:scale-x-0');
                    }
                } else if (link.classList.contains('mobile-nav-link')) {
                    if (section === currentSection) {
                        link.classList.remove('text-gray-600', 'font-medium', 'hover:bg-gray-50');
                        link.classList.add('text-primary', 'font-bold', 'bg-secondary', 'hover:bg-teal-100');
                    } else if (section !== 'pharmacies' || currentSection === 'home') {
                        link.classList.remove('text-primary', 'font-bold', 'bg-secondary', 'hover:bg-teal-100');
                        link.classList.add('text-gray-600', 'font-medium', 'hover:bg-gray-50');
                    }
                }
            });
        }

        // --- 2. Map Initialization & Geolocation ---
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
                    alert(currentLang === 'ar' ? 'تعذر الحصول على الموقع. يرجى السماح للمتصفح بمعرفة موقعك.' :
                        'Could not get location. Please allow browser location permissions.');
                });
            } else {
                alert(currentLang === 'ar' ? 'المتصفح الخاص بك لا يدعم تحديد الموقع تلقائياً.' :
                    'Geolocation is not supported by your browser.');
            }
        }

        // --- 3. Internationalization (i18n) System ---
        const i18n = {
            ar: {

                authModalTitle: "تسجيل الدخول مطلوب",
                authModalDesc: "يجب عليك تسجيل الدخول أولاً لتتمكن من الوصول إلى خدمات الصيدليات والتقديم على طلب انضمام.",
                btnGoogleLogin: "تسجيل الدخول عبر جوجل",
                navCancel: "إلغاء الأمر",
                navHome: "الرئيسية",
                navFeatures: "المميزات",
                navHow: "كيف يعمل",
                navReviews: "آراء العملاء",
                navPharmacies: "للصيدليات",
                navDashboard: "لوحة التحكم", // Added for Admin
                navLogout: "تسجيل الخروج",
                navContact: "اتصل بنا",
                btnRegPharmacy: "سجل كصيدلية",
                btnRegPharmacyAndIcon: "سجل كصيدلية شريكة",
                btnDownloadApp: "حمل التطبيق الآن",
                btnGetItOn: "احصل عليه من",
                btnGooglePlay: "Google Play",

                heroTitle: "دوائك أقرب <br> مما <span class='text-gradient'>تتخيل</span>",
                heroDesc: "MediNear هو تطبيق ذكي يساعدك في العثور على الأدوية المتوفرة في أقرب صيدلية وطلبها بسهولة مع توصيل سريع وخدمات ذكية متقدمة.",
                orderStatus: "حالة الطلب",
                orderDelivered: "تم التوصيل",
                whyTitle: "لماذا تحتاج MediNear؟",
                why1Title: "صعوبة العثور على الأدوية<br>النادرة أو المزمنة",
                why2Title: "زيارة عدة صيدليات للعثور<br>على الدواء",
                why3Title: "عدم وجود تذكيرات فعالة<br>بمواعيد الدواء",
                why4Title: "وصول محدود لاستشارات<br>سريعة مع الصيدلي",
                why5Title: "عدم وجود سجل صحي رقمي<br>مركزي",
                featTitle: "مميزات التطبيق",
                feat1Title: "بحث ذكي عن الدواء",
                feat1List: ["البحث باسم الدواء", "عرض أقرب صيدلية يتوفر بها الدواء", "تكامل مع الخرائط التفاعلية",
                    "تفاصيل التواصل مع الصيدلية"
                ],
                feat2Title: "الطلب والتوصيل",
                feat2List: ["شراء عبر الإنترنت", "خيارات دفع متعددة", "تتبع الطلب في الوقت الفعلي",
                    "دعم التوصيل الطارئ والليلي"
                ],
                feat3Title: "تذكيرات ذكية",
                feat3List: ["تنبيهات بمواعيد الأدوية", "تنبيهات التنوية بنقص المخزون",
                    "اقتراحات إعادة الطلب التلقائية"
                ],
                feat4Title: "المحفظة الطبية",
                feat4List: ["رفع الوصفات الطبية (PDF أو صورة)", "حفظ الملاحظات الطبية",
                    "مشاركة الوصفة مباشرة مع الصيدلية"
                ],
                howTitle: "كيف يعمل التطبيق؟",
                how1Title: "ابحث عن دوائك",
                how2Title: "اختر أقرب صيدلية",
                how3Title: "اختر طريقة الدفع",
                how4Title: "استلم طلبك",

                stat1: "مستخدم نشط",
                stat2: "صيدلية شريكة",
                stat3: "طلب ناجح",
                partnersTitle: "شركاء النجاح",
                reviewsTitle: "آراء العملاء",
                revText1: '"تطبيق رائع جداً، ساعدني في العثور على دواء نادر لوالدي في دقائق معدودة. خدمة التوصيل كانت سريعة وممتازة."',
                revName1: "أحمد محمد",
                revRole1: "مستخدم",
                revText2: '"الآن يمكنني طلب الأدوية الشهرية بسهولة وتصلني في موعدها دون الحاجة للنزول والبحث في الصيدليات."',
                revName2: "سارة علي",
                revRole2: "مستخدم",
                revText3: '"ميزة الاستشارات الصيدلانية ممتازة، الصيدلي أجاب على كل استفساراتي حول الدواء الجديد بشكل احترافي."',
                revName3: "محمود حسن",
                revRole3: "مستخدم",
                revText4: '"تجربة مستخدم راقية وخدمة عملاء متجاوبة دائماً. أوصي بهذا التطبيق بشدة لكل من يحتاج أدوية بشكل دوري."',
                revName4: "عمر فاروق",
                revRole4: "الطبيب المعالج",
                revText5: '"الأسعار مناسبة جداً مقارنة بالتطبيقات الأخرى، كما أن واجهة التطبيق مريحة للعين وسهلة الاستخدام حتى لكبار السن."',
                revName5: "نور إبراهيم",
                revRole5: "ربة منزل",
                faqTitle: "الأسئلة الشائعة",
                faq1Q: "كيف يمكنني طلب دواء غير متوفر في الصيدليات القريبة؟",
                faq1A: "يقوم التطبيق بالبحث في شبكة واسعة من الصيدليات، وإذا لم يكن الدواء متوفراً في نطاقك الجغرافي القريب، سيقترح عليك صيدليات أبعد مع خيار التوصيل السريع.",
                faq2Q: "هل التطبيق مجاني للاستخدام؟",
                faq2A: "نعم، التطبيق مجاني تماماً للتحميل والبحث عن الأدوية. أنت تدفع فقط ثمن الدواء ورسوم التوصيل الخاصة بالصيدلية المختارة.",
                faq3Q: "ما هي طرق الدفع المتاحة؟",
                faq3A: "نوفر طرق دفع متعددة تشمل الدفع عند الاستلام، البطاقات الائتمانية، والمحافظ الإلكترونية لضمان راحة جميع المستخدمين.",
                faq4Q: "هل يمكنني إرجاع الدواء إذا كان غير مطابق للطلب؟",
                faq4A: "نعم، يمكنك استرجاع أو استبدال الدواء خلال 24 ساعة من استلامه طالما كانت العلبة مغلقة ولم تتضرر بناءً على سياسة الاسترجاع الخاصة بوزارة الصحة.",

                contactTitle: "تواصل معنا",
                contactSub: "نحن هنا لمساعدتك! لا تتردد في إرسال استفساراتك أو ملاحظاتك وسنقوم بالرد عليك في أقرب وقت.",
                contactInfoTitle: "العنوان",
                topPhone: "رقم الهاتف",
                topEmail: "البريد الإلكتروني",
                lblContactName: "الاسم بالكامل *",
                lblContactEmail: "البريد الإلكتروني *",
                lblContactMsg: "الرسالة أو الاستفسار *",
                btnSendMsg: "إرسال الرسالة",

                ctaTitle: "דוائك أقرب مما تتخيل",
                ctaDesc: "MediNear هو تطبيق ذكي يساعدك في العثور على الأدوية المتوفرة في أقرب صيدلية وطلبها بسهولة مع توصيل سريع وخدمات ذكية متقدمة.",

                uiLocLabel: "التوصيل إلى",
                uiLocValue: "المنزل - القاهرة",
                uiSearch: "ابحث عن دواء، صيدلية...",
                uiPrescTitle: "لديك وصفة طبية؟",
                uiPrescDesc: "قم بتصوير الروشتة وسنقوم بتجهيز الأدوية فوراً",
                uiCatMeds: "أدوية",
                uiCatCare: "عناية",
                uiCatBaby: "أم وطفل",
                uiCatConsult: "استشارة",
                uiNearPharm: "أقرب صيدلية",
                uiSeeAll: "عرض الكل",
                uiPharmName: "صيدلية الشفاء",
                uiPharmTime: "توصيل 15 دقيقة",
                uiNavHome: "الرئيسية",
                uiNavOrders: "طلباتي",
                uiNavChat: "محادثة",
                uiNavProfile: "حسابي",

                pharmTag: "للصيدليات",
                pharmHeroTitle: "انضم إلى شبكة MediNear وقم بتوسيع نطاق عملك",
                pharmHeroDesc: "سجل صيدليتك الآن وابدأ في استقبال آلاف الطلبات من المرضى في منطقتك بكل سهولة.",
                pharmBenTitle: "فوائد الشراكة",
                pharmBen1: "زيادة المبيعات",
                pharmBen2: "الوصول لعملاء جدد",
                pharmBen3: "إدارة الطلبات بسهولة",
                pharmBen4: "الوصول للوحة التحليلات",
                pharmBen5: "عملية تسجيل بسيطة",
                formTitle: "التقديم كصيدلية شريكة",
                formSub: "انتقل إلى صفحة التسجيل لتقديم طلب انضمام صيدليتك إلى شبكتنا في خطوات بسيطة.",
                btnGoRegPage: "الذهاب لصفحة التسجيل",

                footImportantLinks: "روابط هامة:",
                footPrivacy: "سياسة الخصوصية",
                footTerms: "الشروط والأحكام",
                footData: "سياسة حماية البيانات",
                footCopy: "2026 MediNear جميع الحقوق محفوظة."
            },
            en: {
                authModalTitle: "Login Required",
                authModalDesc: "You must login first to access pharmacy services and submit an application.",
                btnGoogleLogin: "Login with Google",
                navCancel: "Cancel",
                navHome: "Home",
                navFeatures: "Features",
                navHow: "How it works",
                navReviews: "Reviews",
                navPharmacies: "For Pharmacies",
                navDashboard: "Dashboard", // Added for Admin
                navLogout: "Logout",
                navContact: "Contact Us",
                btnRegPharmacy: "Register Pharmacy",
                btnRegPharmacyAndIcon: "Register as Partner",
                btnDownloadApp: "Download App Now",
                btnGetItOn: "GET IT ON",
                btnGooglePlay: "Google Play",

                heroTitle: "Your Medicine Is Closer<br>Than You <span class='text-gradient'>Think</span>",
                heroDesc: "MediNear is a smart app that helps you find available medicines in the nearest pharmacy and order them easily with fast delivery and advanced smart services.",
                orderStatus: "Order Status",
                orderDelivered: "Delivered",
                whyTitle: "Why do you need MediNear?",
                why1Title: "Difficulty finding rare<br>or chronic medications",
                why2Title: "Visiting multiple pharmacies<br>to find medicine",
                why3Title: "Lack of effective<br>medication reminders",
                why4Title: "Limited access to quick<br>pharmacist consultations",
                why5Title: "Lack of a centralized<br>digital health record",
                featTitle: "App Features",
                feat1Title: "Smart Medicine Search",
                feat1List: ["Search by medicine name", "Show nearest pharmacy with stock",
                    "Interactive map integration", "Pharmacy contact details"
                ],
                feat2Title: "Ordering & Delivery",
                feat2List: ["Online purchasing", "Multiple payment options", "Real-time order tracking",
                    "Emergency and night delivery support"
                ],
                feat3Title: "Smart Reminders",
                feat3List: ["Medication timing alerts", "Low stock notification alerts",
                    "Automatic reorder suggestions"
                ],
                feat4Title: "Medical Wallet",
                feat4List: ["Upload prescriptions (PDF or image)", "Save medical notes",
                    "Share prescription directly with pharmacy"
                ],
                howTitle: "How Does The App Work?",
                how1Title: "Search for medicine",
                how2Title: "Choose nearest pharmacy",
                how3Title: "Select payment method",
                how4Title: "Receive your order",

                stat1: "Active Users",
                stat2: "Partner Pharmacies",
                stat3: "Successful Orders",
                partnersTitle: "Success Partners",
                reviewsTitle: "Customer Reviews",
                revText1: '"A fantastic app, helped me find rare medicine for my father in minutes. Delivery service was fast and excellent."',
                revName1: "Ahmed Mohamed",
                revRole1: "User",
                revText2: '"Now I can easily order my monthly meds and receive them on time without the hassle of going out and searching pharmacies."',
                revName2: "Sarah Ali",
                revRole2: "User",
                revText3: '"The pharmacy consultation feature is excellent. The pharmacist answered all my questions about the new medicine professionally."',
                revName3: "Mahmoud Hassan",
                revRole3: "User",
                revText4: '"Premium user experience and consistently responsive customer service. I highly recommend this app to anyone who needs regular medicine."',
                revName4: "Omar Farouk",
                revRole4: "Physician",
                revText5: '"Prices are very reasonable compared to other apps, and the interface is easy on the eyes and user-friendly even for the elderly."',
                revName5: "Nour Ibrahim",
                revRole5: "Housewife",
                faqTitle: "Frequently Asked Questions",
                faq1Q: "How can I order medicine not available in nearby pharmacies?",
                faq1A: "The app searches a wide network of pharmacies. If the medicine isn't available in your immediate area, it will suggest farther pharmacies with a fast delivery option.",
                faq2Q: "Is the app free to use?",
                faq2A: "Yes, downloading and searching for medicines is completely free. You only pay for the medicine and the delivery fee of the selected pharmacy.",
                faq3Q: "What payment methods are available?",
                faq3A: "We offer multiple payment methods including Cash on Delivery, Credit Cards, and E-Wallets to ensure convenience for all users.",
                faq4Q: "Can I return the medicine if it's incorrect?",
                faq4A: "Yes, you can return or exchange the medicine within 24 hours of receipt, provided the box is sealed and undamaged, according to the Ministry of Health's return policy.",

                contactTitle: "Contact Us",
                contactSub: "We're here to help! Feel free to send your inquiries or feedback, and we'll reply as soon as possible.",
                contactInfoTitle: "Address",
                topPhone: "Phone",
                topEmail: "Email",
                lblContactName: "Full Name *",
                lblContactEmail: "Email Address *",
                lblContactMsg: "Message or Inquiry *",
                btnSendMsg: "Send Message",

                ctaTitle: "Your Medicine Is Closer Than You Think",
                ctaDesc: "MediNear is a smart app that helps you find available medicines in the nearest pharmacy and order them easily with fast delivery and advanced smart services.",

                uiLocLabel: "Deliver to",
                uiLocValue: "Home - Cairo",
                uiSearch: "Search medicine, pharmacy...",
                uiPrescTitle: "Have a prescription?",
                uiPrescDesc: "Snap a photo and we'll prepare your meds instantly",
                uiCatMeds: "Meds",
                uiCatCare: "Care",
                uiCatBaby: "Baby",
                uiCatConsult: "Consult",
                uiNearPharm: "Nearest Pharmacy",
                uiSeeAll: "See All",
                uiPharmName: "Al-Shifa Pharmacy",
                uiPharmTime: "15 min delivery",
                uiNavHome: "Home",
                uiNavOrders: "Orders",
                uiNavChat: "Chat",
                uiNavProfile: "Profile",

                pharmTag: "For Pharmacies",
                pharmHeroTitle: "Join the MediNear Network and Expand Your Business",
                pharmHeroDesc: "Register your pharmacy now and start receiving thousands of orders from patients in your area effortlessly.",
                pharmBenTitle: "Partnership Benefits",
                pharmBen1: "Increase Sales",
                pharmBen2: "Reach New Customers",
                pharmBen3: "Manage Orders Easily",
                pharmBen4: "Access Analytics Board",
                pharmBen5: "Simple Registration",
                formTitle: "Apply as Partner Pharmacy",
                formSub: "Go to the registration page to submit your request to join our network in a few simple steps.",
                btnGoRegPage: "Go to Registration Page",

                footImportantLinks: "Important Links:",
                footPrivacy: "Privacy Policy",
                footTerms: "Terms & Conditions",
                footData: "Data Protection Policy",
                footCopy: "2026 MediNear All rights reserved."
            }
        };
        let currentLang = 'ar';

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
                document.body.classList.remove('overflow-hidden');
            }
        }

        function updateLists(lang) {
            for (let i = 1; i <= 4; i++) {
                const listEl = document.getElementById(`feat${i}List`);
                if (listEl) {
                    listEl.innerHTML = '';
                    i18n[lang][`feat${i}List`].forEach(item => {
                        const li = document.createElement('li');
                        li.innerText = item;
                        listEl.appendChild(li);
                    });
                }
            }
        }

        function toggleLanguage() {
            currentLang = currentLang === 'ar' ? 'en' : 'ar';

            document.documentElement.lang = currentLang;
            document.documentElement.dir = currentLang === 'ar' ? 'rtl' : 'ltr';

            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (i18n[currentLang][key]) {
                    el.innerHTML = i18n[currentLang][key];
                }
            });

            updateLists(currentLang);

            document.getElementById('lang-text-desktop').innerHTML = currentLang === 'ar' ? 'English' : 'عربي';
            const mobileInner = document.getElementById('lang-text-mobile-inner');
            if (mobileInner) {
                mobileInner.innerHTML = currentLang === 'ar' ? 'EN' : 'AR';
            }

            let mockupDir = document.getElementById('mockup-dir');
            if (mockupDir) {
                mockupDir.dir = currentLang === 'ar' ? 'rtl' : 'ltr';
            }

            let linkArrow = document.getElementById('link-arrow');
            if (linkArrow) {
                if (currentLang === 'en') {
                    linkArrow.classList.replace('fa-arrow-left', 'fa-arrow-right');
                } else {
                    linkArrow.classList.replace('fa-arrow-right', 'fa-arrow-left');
                }
            }

            if (map) {
                setTimeout(() => map.invalidateSize(), 400);
            }

            // Recreate swipers with new direction
            setTimeout(() => {
                if (typeof window.initReviewsSwiper === 'function') {
                    window.initReviewsSwiper();
                } else if (typeof initReviewsSwiper === 'function') {
                    initReviewsSwiper();
                }
            }, 50);

            // Re-check progress loader line to re-animate correctly
            const line = document.getElementById("progress-line");
            if (line) {
                line.classList.remove('scale-x-100');
                line.classList.add('scale-x-0');
                setTimeout(() => line.classList.replace('scale-x-0', 'scale-x-100'), 500);
            }
        }

        // --- 4. Other Interactions ---
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

        // Scroll Interactions: ScrollSpy, Top bar hide, Go up button, and Scroll Progress Bar
        window.addEventListener('scroll', () => {
            const navbarInner = document.getElementById('navbar-inner');
            const topBar = document.getElementById('top-bar');
            const goUpBtn = document.getElementById('go-up-btn');
            const scrollProgressBar = document.getElementById('scroll-progress-bar');
            const scrollY = window.scrollY;

            // Header Logic
            if (scrollY > 40) {
                navbarInner.classList.remove('h-20');
                navbarInner.classList.add('h-16');
                if (topBar) {
                    topBar.classList.replace('h-9', 'h-0');
                    topBar.style.opacity = '0';
                    topBar.classList.remove('mt-[4px]');
                }
            } else {
                navbarInner.classList.remove('h-16');
                navbarInner.classList.add('h-20');
                if (topBar) {
                    topBar.classList.replace('h-0', 'h-9');
                    topBar.style.opacity = '1';
                    topBar.classList.add('mt-[4px]');
                }
            }

            // Go Up Button Logic
            if (scrollY > 400) {
                goUpBtn.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-10');
                goUpBtn.classList.add('opacity-100', 'pointer-events-auto', 'translate-y-0');
            } else {
                goUpBtn.classList.add('opacity-0', 'pointer-events-none', 'translate-y-10');
                goUpBtn.classList.remove('opacity-100', 'pointer-events-auto', 'translate-y-0');
            }

            // Scroll Progress Bar Logic
            if (scrollProgressBar) {
                const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                const scrolled = (scrollY / height) * 100;
                scrollProgressBar.style.width = scrolled + "%";
            }

            // ScrollSpy Logic for Navbar Links
            if (!document.getElementById('page-home').classList.contains('hidden')) {
                const sections = document.querySelectorAll('#page-home section[id]');
                let currentSection = 'home';

                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    // Adjust offset for smooth trigger point
                    if (scrollY >= (sectionTop - 250)) {
                        currentSection = section.getAttribute('id');
                    }
                });

                updateActiveLink(currentSection);
            }
        });

        function toggleFaq(element) {
            const isActive = element.classList.contains('active');
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
                item.classList.remove('border-primary');
            });
            if (!isActive) {
                element.classList.add('active');
                element.classList.add('border-primary');
            }
        }

        // --- 5. Intersection Observer Animations ---
        function animateCounter(el) {
            const target = parseInt(el.getAttribute('data-target'));
            const suffix = el.getAttribute('data-suffix') || '';
            const duration = 2500;
            const start = 0;
            let startTimestamp = null;

            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const p = Math.min((timestamp - startTimestamp) / duration, 1);
                const easeOutExpo = p === 1 ? 1 : 1 - Math.pow(2, -10 * p);

                el.innerText = '+' + Math.floor(easeOutExpo * (target - start) + start) + suffix;
                if (p < 1) {
                    window.requestAnimationFrame(step);
                } else {
                    el.innerText = '+' + target + suffix;
                }
            };
            window.requestAnimationFrame(step);
        }

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

                        if (entry.target.querySelector('.stat-number')) {
                            const stat = entry.target.querySelector('.stat-number');
                            if (!stat.classList.contains('counted')) {
                                animateCounter(stat);
                                stat.classList.add('counted');
                            }
                        }

                        if (entry.target.id === 'how-it-works') {
                            const line = document.getElementById("progress-line");
                            if (line) line.classList.replace('scale-x-0', 'scale-x-100');
                        }

                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

            const sectionHow = document.getElementById('how-it-works');
            if (sectionHow) observer.observe(sectionHow);
        });
    </script>
    <script>
        // --- Toast Flash Messages Handler ---
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.animate-toast').forEach(toast => {
                setTimeout(() => {
                    if (toast) {
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateY(-10px)';
                        toast.style.transition = 'all 0.5s ease-out';
                        setTimeout(() => toast.remove(), 500);
                    }
                }, 6000);
            });
        });
    </script>
</body>

</html>
