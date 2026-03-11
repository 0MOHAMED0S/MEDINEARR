<aside id="sidebar"
    class="sidebar fixed right-0 top-0 h-full w-[260px] sm:w-64 md:w-72 sidebar-gradient text-white z-50 transition-transform duration-300 ease-in-out lg:translate-x-0">

    <div class="p-4 md:p-6 flex items-center gap-2.5 md:gap-3 border-b border-white/10 shrink-0">
        <div class="bg-white p-1.5 md:p-2 rounded-xl flex items-center justify-center shrink-0 shadow-lg">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 md:w-8 md:h-8">
                <circle cx="50" cy="50" r="46" stroke="url(#logoGradSidebar)" stroke-width="4" />
                <path
                    d="M50 15C34.536 15 22 27.536 22 43C22 62.5 50 85 50 85C50 85 78 62.5 78 43C78 27.536 65.464 15 50 15Z"
                    fill="url(#logoGradSidebar)" />
                <path d="M45 28H55V37H64V47H55V56H45V47H36V37H45V28Z" fill="white" />
            </svg>
        </div>
        <div class="min-w-0">
            <span class="text-base md:text-xl font-black tracking-tight block truncate uppercase">MediNear</span>
            <span
                class="text-[8px] md:text-[9px] text-white/50 uppercase tracking-widest block font-bold mt-0.5 opacity-80">Admin
                Panel</span>
        </div>
    </div>

    <nav
        class="mt-4 md:mt-6 px-2 md:px-4 space-y-1 md:space-y-1.5 overflow-y-auto max-h-[calc(100vh-220px)] scrollbar-hide">

        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 p-3 md:p-4 transition-all group rounded-xl md:rounded-2xl
            {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 border-l-4 border-accent shadow-inner' : 'hover:bg-white/5' }}">
            <i
                class="fa-solid fa-chart-pie {{ request()->routeIs('admin.dashboard') ? 'text-accent' : 'text-white/40 group-hover:text-white' }} text-sm md:text-base"></i>
            <span
                class="{{ request()->routeIs('admin.dashboard') ? 'font-bold text-white' : 'text-white/70 group-hover:text-white' }} text-xs md:text-base">الرئيسية</span>
        </a>

        <a href="{{ route('categories.index') }}"
            class="flex items-center gap-3 p-3 md:p-4 transition-all group rounded-xl md:rounded-2xl
            {{ request()->routeIs('categories.*') ? 'bg-white/10 border-l-4 border-accent shadow-inner' : 'hover:bg-white/5' }}">
            <i
                class="fa-solid fa-layer-group {{ request()->routeIs('categories.*') ? 'text-accent' : 'text-white/40 group-hover:text-white' }} text-sm md:text-base"></i>
            <span
                class="{{ request()->routeIs('categories.*') ? 'font-bold text-white' : 'text-white/70 group-hover:text-white' }} text-xs md:text-base">الفئات</span>
        </a>
        <a href="{{ route('medicines.index') }}"
            class="flex items-center gap-3 p-3 md:p-4 transition-all group rounded-xl md:rounded-2xl
            {{ request()->routeIs('medicines.*') ? 'bg-white/10 border-l-4 border-accent shadow-inner' : 'hover:bg-white/5' }}">
            <i
                class="fa-solid fa-layer-group {{ request()->routeIs('medicines.*') ? 'text-accent' : 'text-white/40 group-hover:text-white' }} text-sm md:text-base"></i>
            <span
                class="{{ request()->routeIs('medicines.*') ? 'font-bold text-white' : 'text-white/70 group-hover:text-white' }} text-xs md:text-base">الأدوية</span>
        </a>

                <a href="{{ route('pharmacies.index') }}"
            class="flex items-center gap-3 p-3 md:p-4 transition-all group rounded-xl md:rounded-2xl
            {{ request()->routeIs('pharmacies.*') ? 'bg-white/10 border-l-4 border-accent shadow-inner' : 'hover:bg-white/5' }}">
            <i
                class="fa-solid fa-layer-group {{ request()->routeIs('pharmacies.*') ? 'text-accent' : 'text-white/40 group-hover:text-white' }} text-sm md:text-base"></i>
            <span
                class="{{ request()->routeIs('pharmacies.*') ? 'font-bold text-white' : 'text-white/70 group-hover:text-white' }} text-xs md:text-base">الصيدليات</span>
        </a>
    </nav>

    <div class="absolute bottom-4 md:bottom-8 w-full px-4 md:px-6">
        <div
            class="bg-gradient-to-br from-white/10 to-white/5 p-3 md:p-4 rounded-xl md:rounded-2xl border border-white/5 text-center">
            <div class="w-7 h-7 md:w-8 md:h-8 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-2">
                <i class="fa-solid fa-headset text-accent text-[10px] md:text-xs"></i>
            </div>
            <p class="text-[9px] md:text-[10px] text-white/50 mb-2 md:mb-3 leading-tight">تحتاج مساعدة؟</p>
            <button
                class="text-[10px] md:text-[11px] bg-accent hover:bg-lime-500 text-darkText font-black py-1.5 md:py-2 px-3 md:px-4 rounded-lg md:rounded-xl w-full transition-all shadow-lg shadow-accent/10">
                الدعم الفني
            </button>
        </div>
    </div>
</aside>
