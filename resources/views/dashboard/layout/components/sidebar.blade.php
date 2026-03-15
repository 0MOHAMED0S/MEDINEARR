<aside id="sidebar"
    class="sidebar fixed right-0 top-0 h-full w-[260px] sm:w-64 md:w-72 sidebar-gradient text-white z-50 transition-transform duration-300 ease-in-out lg:translate-x-0">

    <div class="p-4 md:p-6 flex items-center gap-3 border-b border-white/10 shrink-0">
        <div class="bg-white p-2 rounded-xl flex items-center justify-center shrink-0 shadow-lg">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 md:w-8 md:h-8">
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
        <div class="min-w-0">
            <span class="text-lg md:text-xl font-black tracking-tight block truncate uppercase">MediNear</span>
            <span class="text-[9px] md:text-[10px] text-white/50 uppercase tracking-widest block font-bold mt-0.5 opacity-80">
                Admin Panel
            </span>
        </div>
    </div>

    <nav class="mt-4 md:mt-6 px-3 md:px-4 space-y-1.5 overflow-y-auto max-h-[calc(100vh-20px)] scrollbar-hide">

        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 p-3.5 md:p-4 transition-all group rounded-xl md:rounded-2xl
            {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 border-l-4 border-accent shadow-inner' : 'hover:bg-white/5' }}">
            <i class="fa-solid fa-chart-pie w-5 text-center {{ request()->routeIs('admin.dashboard') ? 'text-accent' : 'text-white/40 group-hover:text-white' }} text-base md:text-lg"></i>
            <span class="{{ request()->routeIs('admin.dashboard') ? 'font-bold text-white' : 'text-white/70 group-hover:text-white' }} text-sm md:text-base">الرئيسية</span>
        </a>

        <a href="{{ route('pharmaciesApplications.index') }}"
            class="flex items-center gap-3 p-3.5 md:p-4 transition-all group rounded-xl md:rounded-2xl
            {{ request()->routeIs('pharmaciesApplications.*') ? 'bg-white/10 border-l-4 border-accent shadow-inner' : 'hover:bg-white/5' }}">
            <i class="fa-solid fa-clipboard-list w-5 text-center {{ request()->routeIs('pharmaciesApplications.*') ? 'text-accent' : 'text-white/40 group-hover:text-white' }} text-base md:text-lg"></i>
            <span class="{{ request()->routeIs('pharmaciesApplications.*') ? 'font-bold text-white' : 'text-white/70 group-hover:text-white' }} text-sm md:text-base">طلبات الصيدليات</span>
        </a>

        <a href="{{ route('pharmacies.index') }}"
            class="flex items-center gap-3 p-3.5 md:p-4 transition-all group rounded-xl md:rounded-2xl
            {{ request()->routeIs('pharmacies.*') ? 'bg-white/10 border-l-4 border-accent shadow-inner' : 'hover:bg-white/5' }}">
            <i class="fa-solid fa-store w-5 text-center {{ request()->routeIs('pharmacies.*') ? 'text-accent' : 'text-white/40 group-hover:text-white' }} text-base md:text-lg"></i>
            <span class="{{ request()->routeIs('pharmacies.*') ? 'font-bold text-white' : 'text-white/70 group-hover:text-white' }} text-sm md:text-base">الصيدليات المعتمدة</span>
        </a>

        <a href="{{ route('categories.index') }}"
            class="flex items-center gap-3 p-3.5 md:p-4 transition-all group rounded-xl md:rounded-2xl
            {{ request()->routeIs('categories.*') ? 'bg-white/10 border-l-4 border-accent shadow-inner' : 'hover:bg-white/5' }}">
            <i class="fa-solid fa-tags w-5 text-center {{ request()->routeIs('categories.*') ? 'text-accent' : 'text-white/40 group-hover:text-white' }} text-base md:text-lg"></i>
            <span class="{{ request()->routeIs('categories.*') ? 'font-bold text-white' : 'text-white/70 group-hover:text-white' }} text-sm md:text-base">الفئات والأقسام</span>
        </a>

        <a href="{{ route('medicines.index') }}"
            class="flex items-center gap-3 p-3.5 md:p-4 transition-all group rounded-xl md:rounded-2xl
            {{ request()->routeIs('medicines.*') ? 'bg-white/10 border-l-4 border-accent shadow-inner' : 'hover:bg-white/5' }}">
            <i class="fa-solid fa-pills w-5 text-center {{ request()->routeIs('medicines.*') ? 'text-accent' : 'text-white/40 group-hover:text-white' }} text-base md:text-lg"></i>
            <span class="{{ request()->routeIs('medicines.*') ? 'font-bold text-white' : 'text-white/70 group-hover:text-white' }} text-sm md:text-base">الأدوية والمنتجات</span>
        </a>

        <a href="{{ route('coupons.index') }}"
            class="flex items-center gap-3 p-3.5 md:p-4 transition-all group rounded-xl md:rounded-2xl
            {{ request()->routeIs('coupons.*') ? 'bg-white/10 border-l-4 border-accent shadow-inner' : 'hover:bg-white/5' }}">
            <i class="fa-solid fa-pills w-5 text-center {{ request()->routeIs('coupons.*') ? 'text-accent' : 'text-white/40 group-hover:text-white' }} text-base md:text-lg"></i>
            <span class="{{ request()->routeIs('coupons.*') ? 'font-bold text-white' : 'text-white/70 group-hover:text-white' }} text-sm md:text-base">الكوبونات</span>
        </a>

        <a href="{{ route('ads.index') }}"
            class="flex items-center gap-3 p-3.5 md:p-4 transition-all group rounded-xl md:rounded-2xl
            {{ request()->routeIs('ads.*') ? 'bg-white/10 border-l-4 border-accent shadow-inner' : 'hover:bg-white/5' }}">
            <i class="fa-solid fa-pills w-5 text-center {{ request()->routeIs('ads.*') ? 'text-accent' : 'text-white/40 group-hover:text-white' }} text-base md:text-lg"></i>
            <span class="{{ request()->routeIs('ads.*') ? 'font-bold text-white' : 'text-white/70 group-hover:text-white' }} text-sm md:text-base">الاعلانات</span>
        </a>

        <a href="{{ route('admin.profile.index') }}"
            class="flex items-center gap-3 p-3.5 md:p-4 transition-all group rounded-xl md:rounded-2xl mt-4
            {{ request()->routeIs('admin.profile.*') ? 'bg-white/10 border-l-4 border-accent shadow-inner' : 'hover:bg-white/5' }}">
            <i class="fa-solid fa-user-gear w-5 text-center {{ request()->routeIs('admin.profile.*') ? 'text-accent' : 'text-white/40 group-hover:text-white' }} text-base md:text-lg"></i>
            <span class="{{ request()->routeIs('admin.profile.*') ? 'font-bold text-white' : 'text-white/70 group-hover:text-white' }} text-sm md:text-base">الملف الشخصي</span>
        </a>

    </nav>

</aside>
