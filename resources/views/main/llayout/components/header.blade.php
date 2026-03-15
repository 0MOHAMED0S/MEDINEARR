<header class="w-full bg-white/95 backdrop-blur-lg border-b border-gray-100 transition-all duration-300 sticky top-0 z-[100]" id="navbar">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-20 transition-all duration-300" id="navbar-inner">

            <a href="{{ url('/') }}" class="flex-shrink-0 flex items-center gap-2 md:gap-3 cursor-pointer group">
                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="w-9 h-9 md:w-11 md:h-11 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                    <defs>
                        <linearGradient id="logoGrad" x1="80%" y1="0%" x2="20%" y2="100%">
                            <stop offset="0%" stop-color="#84cc16" />
                            <stop offset="100%" stop-color="#14b8a6" />
                        </linearGradient>
                    </defs>
                    <circle cx="50" cy="50" r="46" stroke="url(#logoGrad)" stroke-width="4" />
                    <path d="M50 15C34.536 15 22 27.536 22 43C22 62.5 50 85 50 85C50 85 78 62.5 78 43C78 27.536 65.464 15 50 15Z" fill="url(#logoGrad)" />
                    <path d="M45 28H55V37H64V47H55V56H45V47H36V37H45V28Z" fill="white" />
                </svg>
                <span class="font-black text-xl md:text-2xl text-slate-800 tracking-tight">Medi<span class="text-primary">Near</span></span>
            </a>

            <nav class="hidden lg:flex items-center gap-8 font-semibold text-sm">
                <a href="{{ url('/') }}" class="text-primary transition nav-link relative after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-primary after:scale-x-100" data-i18n="navHome">الرئيسية</a>
                <a href="#features" class="text-gray-500 hover:text-primary transition nav-link" data-i18n="navFeatures">المميزات</a>
                <a href="#how-it-works" class="text-gray-500 hover:text-primary transition nav-link" data-i18n="navHow">كيف يعمل</a>
                <a href="#contact" class="text-gray-500 hover:text-primary transition nav-link" data-i18n="navContact">اتصل بنا</a>
                <a href="{{ route('categories.index') }}" class="text-gray-500 hover:text-primary transition nav-link" data-i18n="navPharmacies">للصيدليات</a>
            </nav>

            <div class="hidden lg:flex items-center gap-4">
                <button id="lang-switch-desktop" onclick="toggleLanguage()" class="flex items-center gap-2 text-gray-500 hover:text-primary font-bold text-sm transition-colors px-3">
                    <i class="fa-solid fa-globe"></i>
                    <span id="lang-text-desktop">English</span>
                </button>

                <a href="{{ route('google.login') }}"
                   class="flex items-center gap-2 border border-gray-200 px-4 py-2.5 rounded-xl font-bold text-xs text-gray-600 hover:bg-slate-50 hover:border-primary/30 transition-all duration-300">
                    <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-4 h-4" alt="Google">
                    <span>دخول الصيدليات</span>
                </a>

                <button
                    class="bg-gradient-custom hover:opacity-90 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-primary/20 active:scale-95">
                    <span data-i18n="btnRegPharmacy">سجل كصيدلية</span>
                </button>
            </div>

            <div class="lg:hidden flex items-center gap-4">
                <button id="lang-switch-mobile" onclick="toggleLanguage()" class="text-gray-500 hover:text-primary font-bold text-sm">
                    <i class="fa-solid fa-globe"></i> EN
                </button>
                <button id="mobile-menu-btn" class="text-slate-800 hover:text-primary focus:outline-none transition-colors" aria-label="Toggle Menu">
                    <i class="fa-solid fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-gray-100 absolute w-full shadow-2xl transition-all origin-top">
        <div class="px-4 pt-4 pb-8 space-y-2 flex flex-col text-center">
            <a href="{{ url('/') }}" class="block w-full px-4 py-3 text-primary font-bold bg-teal-50 rounded-xl">الرئيسية</a>
            <a href="#features" class="block w-full px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl font-medium">المميزات</a>
            <a href="#how-it-works" class="block w-full px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl font-medium">كيف يعمل</a>

            <hr class="my-4 border-gray-100">

            <a href="{{ route('google.login') }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 border border-gray-100 rounded-xl font-bold text-gray-600 mb-2">
                <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-5 h-5" alt="Google">
                <span>دخول الصيدليات</span>
            </a>

            <button
                class="w-full bg-gradient-custom text-white px-6 py-4 rounded-xl font-bold shadow-md active:scale-95 transition-transform"
                data-i18n="btnRegPharmacy">سجل كصيدلية</button>
        </div>
    </div>
</header>
