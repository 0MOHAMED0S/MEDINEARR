<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>404 - غير موجود | MediNear</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Cairo', 'sans-serif'] },
                    colors: { primary: '#0d9488' },
                    backgroundImage: {
                        'grid-pattern': "url(\"data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 0h40v40H0V0zm20 20h20v20H20V20zM0 20h20v20H0V20z' fill='%23000000' fill-opacity='0.02' fill-rule='evenodd'/%3E%3C/svg%3E\")",
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #f8fafc;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* 404 Outline Text Effect */
        .text-outline {
            color: transparent;
            -webkit-text-stroke: 3px #e2e8f0;
            text-shadow: 10px 10px 0px rgba(226, 232, 240, 0.4);
        }
    </style>
</head>
<body class="h-[100dvh] flex flex-col items-center justify-center font-sans relative bg-grid-pattern selection:bg-primary selection:text-white">

    <div class="absolute inset-0 bg-gradient-to-b from-white/70 to-transparent z-0 pointer-events-none"></div>
    <div class="absolute top-1/4 left-1/4 w-[400px] h-[400px] bg-teal-200/40 rounded-full blur-[100px] z-0 pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-rose-200/30 rounded-full blur-[100px] z-0 pointer-events-none"></div>

    <div class="absolute inset-0 flex items-center justify-center z-0 pointer-events-none select-none overflow-hidden">
        <h1 class="text-[12rem] sm:text-[18rem] md:text-[25rem] lg:text-[30rem] font-black text-outline tracking-tighter" style="line-height: 0.8;">
            404
        </h1>
    </div>

    <div class="relative z-10 flex flex-col items-center justify-center w-full px-6 h-full">

        <div class="relative w-full max-w-2xl mx-auto flex justify-center mb-8 md:mb-12">
            <svg viewBox="0 0 600 400" class="w-full h-auto drop-shadow-2xl" style="max-height: 45vh;">
                <defs>
                    <radialGradient id="pill-blue" cx="35%" cy="30%" r="70%">
                        <stop offset="0%" stop-color="#93c5fd" />
                        <stop offset="50%" stop-color="#3b82f6" />
                        <stop offset="100%" stop-color="#1e3a8a" />
                    </radialGradient>
                    <linearGradient id="capsule-red" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#fda4af" />
                        <stop offset="40%" stop-color="#e11d48" />
                        <stop offset="100%" stop-color="#9f1239" />
                    </linearGradient>
                    <linearGradient id="capsule-white" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#ffffff" />
                        <stop offset="80%" stop-color="#e2e8f0" />
                        <stop offset="100%" stop-color="#cbd5e1" />
                    </linearGradient>
                    <linearGradient id="glass-lens" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="rgba(255,255,255,0.7)" />
                        <stop offset="100%" stop-color="rgba(255,255,255,0.1)" />
                    </linearGradient>
                    <linearGradient id="handle-wood" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#b45309" />
                        <stop offset="100%" stop-color="#713f12" />
                    </linearGradient>
                </defs>

                <ellipse cx="220" cy="350" rx="60" ry="10" fill="rgba(0,0,0,0.15)" />
                <ellipse cx="400" cy="360" rx="55" ry="10" fill="rgba(0,0,0,0.15)" />

                <g transform="translate(10, -10)">
                    <path d="M 180 290 Q 170 340 160 340" fill="none" stroke="#1e293b" stroke-width="8" stroke-linecap="round" />
                    <path d="M 145 340 Q 160 330 175 340 Z" fill="#1e293b" />

                    <path d="M 280 220 Q 310 190 290 170" fill="none" stroke="#1e293b" stroke-width="8" stroke-linecap="round" />
                    <circle cx="290" cy="170" r="10" fill="#fff" stroke="#1e293b" stroke-width="5" />

                    <circle cx="210" cy="220" r="75" fill="url(#pill-blue)" stroke="#1e293b" stroke-width="8" />
                    <path d="M 155 175 A 60 60 0 0 1 200 150 A 65 65 0 0 0 150 200 Z" fill="rgba(255,255,255,0.5)" />

                    <circle cx="185" cy="205" r="7" fill="#1e293b" />
                    <circle cx="183" cy="202" r="2.5" fill="#fff" />
                    <circle cx="230" cy="205" r="7" fill="#1e293b" />
                    <circle cx="228" cy="202" r="2.5" fill="#fff" />
                    <path d="M 175 190 Q 185 185 195 192" fill="none" stroke="#1e293b" stroke-width="4" stroke-linecap="round" />
                    <path d="M 220 192 Q 230 185 240 190" fill="none" stroke="#1e293b" stroke-width="4" stroke-linecap="round" />
                    <path d="M 198 235 Q 208 225 218 235" fill="none" stroke="#1e293b" stroke-width="5" stroke-linecap="round" />

                    <path d="M 230 290 Q 240 340 250 340" fill="none" stroke="#1e293b" stroke-width="8" stroke-linecap="round" />
                    <path d="M 235 340 Q 250 330 265 340 Z" fill="#1e293b" />

                    <path d="M 150 240 Q 110 260 100 220" fill="none" stroke="#1e293b" stroke-width="8" stroke-linecap="round" />

                    <g transform="translate(-10, -20)">
                        <line x1="110" y1="240" x2="160" y2="180" stroke="#1e293b" stroke-width="22" stroke-linecap="round" />
                        <line x1="110" y1="240" x2="160" y2="180" stroke="url(#handle-wood)" stroke-width="14" stroke-linecap="round" />

                        <circle cx="110" cy="240" r="12" fill="#fff" stroke="#1e293b" stroke-width="5" />

                        <circle cx="180" cy="150" r="55" fill="url(#glass-lens)" stroke="#1e293b" stroke-width="8" />
                        <circle cx="180" cy="150" r="50" fill="none" stroke="#94a3b8" stroke-width="4" />

                        <path d="M 145 125 A 40 40 0 0 1 175 105 A 45 45 0 0 0 140 145 Z" fill="rgba(255,255,255,0.8)" />
                    </g>
                </g>

                <g transform="rotate(-6, 400, 200)">
                    <path d="M 370 300 Q 360 350 350 350" fill="none" stroke="#1e293b" stroke-width="8" stroke-linecap="round" />
                    <path d="M 335 350 Q 350 340 365 350 Z" fill="#1e293b" />

                    <path d="M 455 200 Q 490 220 480 260" fill="none" stroke="#1e293b" stroke-width="8" stroke-linecap="round" />
                    <circle cx="480" cy="260" r="10" fill="#fff" stroke="#1e293b" stroke-width="5" />

                    <g>
                        <rect x="345" y="80" width="110" height="230" rx="55" fill="url(#capsule-white)" />
                        <path d="M 345 135 A 55 55 0 0 1 455 135 L 455 195 L 345 195 Z" fill="url(#capsule-red)" />

                        <line x1="345" y1="195" x2="455" y2="195" stroke="#1e293b" stroke-width="8" />

                        <rect x="345" y="80" width="110" height="230" rx="55" fill="none" stroke="#1e293b" stroke-width="8" />

                        <path d="M 360 110 Q 365 195 360 280" fill="none" stroke="#ffffff" stroke-width="6" stroke-linecap="round" opacity="0.6" />
                    </g>

                    <ellipse cx="380" cy="155" rx="7" ry="12" fill="#1e293b" />
                    <circle cx="380" cy="148" r="2.5" fill="#fff" />

                    <ellipse cx="420" cy="155" rx="7" ry="12" fill="#1e293b" />
                    <circle cx="420" cy="148" r="2.5" fill="#fff" />

                    <path d="M 368 132 Q 380 120 392 128" fill="none" stroke="#1e293b" stroke-width="4" stroke-linecap="round" />
                    <path d="M 408 128 Q 420 120 432 132" fill="none" stroke="#1e293b" stroke-width="4" stroke-linecap="round" />

                    <circle cx="400" cy="200" r="14" fill="#1e293b" />
                    <circle cx="400" cy="200" r="14" fill="none" stroke="#9f1239" stroke-width="4" />

                    <path d="M 410 300 Q 420 350 430 350" fill="none" stroke="#1e293b" stroke-width="8" stroke-linecap="round" />
                    <path d="M 415 350 Q 430 340 445 350 Z" fill="#1e293b" />

                    <path d="M 345 220 Q 300 190 350 180" fill="none" stroke="#1e293b" stroke-width="8" stroke-linecap="round" />
                    <circle cx="350" cy="180" r="10" fill="#fff" stroke="#1e293b" stroke-width="5" />
                </g>

            </svg>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 w-full max-w-sm mx-auto z-20">

            <a href="{{ url('/') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl font-bold text-white bg-primary hover:bg-teal-700 transition-all shadow-[0_10px_20px_rgba(13,148,136,0.3)] hover:shadow-[0_15px_25px_rgba(13,148,136,0.4)] flex items-center justify-center gap-2 text-sm md:text-base outline-none focus:ring-4 focus:ring-teal-500/30">
                <i class="fa-solid fa-house"></i> العودة للرئيسية
            </a>

            <button onclick="history.back()" class="w-full sm:w-auto px-6 py-3 rounded-xl font-bold text-slate-700 bg-white border-2 border-slate-200 hover:border-slate-300 hover:bg-slate-50 transition-all shadow-sm hover:-translate-y-1 flex items-center justify-center gap-2 text-sm md:text-base outline-none focus:ring-4 focus:ring-slate-200">
                <i class="fa-solid fa-arrow-right"></i> رجوع للخلف
            </button>

        </div>

    </div>

</body>
</html>
