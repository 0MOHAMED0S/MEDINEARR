<header class="glass-nav sticky top-0 z-30 px-4 md:px-6 py-3 md:py-4 border-b border-slate-100 bg-white/80 backdrop-blur-md flex items-center justify-between transition-all">

    <div class="flex items-center gap-3 sm:gap-4 min-w-0 flex-1">
        <button onclick="toggleSidebar()" class="lg:hidden text-primary text-xl p-2 hover:bg-teal-50 rounded-xl transition-colors shrink-0">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>

        <div class="min-w-0 mr-3">
            <h2 class="text-base sm:text-lg md:text-xl font-black text-slate-800 leading-tight truncate">
                مرحباً، <span class="text-primary">{{ explode(' ', Auth::user()->name)[0] }}</span> 👋
            </h2>
            <p class="text-[10px] sm:text-xs font-medium text-slate-400 hidden xs:block italic truncate mt-0.5">
                مدير النظام - صلاحيات كاملة
            </p>
        </div>
    </div>

    <div class="flex items-center gap-2 sm:gap-4 shrink-0">

        <button class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-500 relative hover:bg-teal-50 hover:text-primary transition-all group shrink-0">
            <i class="fa-solid fa-bell text-lg group-hover:shake"></i>
            <span class="absolute top-2 left-2.5 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white"></span>
        </button>

        <div class="h-8 w-[1px] bg-slate-200 mx-1 hidden sm:block"></div>

        <div class="flex items-center gap-3">

            <div class="hidden xl:flex flex-col items-start text-right min-w-0 ml-1">
                <span class="text-xs font-black text-slate-800 leading-none mb-1 truncate max-w-[120px]">
                    {{ Auth::user()->name }}
                </span>
                <span class="text-[10px] font-bold text-slate-400 lowercase truncate max-w-[120px]">
                    {{ Auth::user()->email }}
                </span>
            </div>

            <div class="relative group">
                <div class="w-10 h-10 sm:w-11 sm:h-11 md:w-12 md:h-12 rounded-2xl bg-primary/10 p-0.5 border border-primary/20 cursor-pointer overflow-hidden shadow-sm shrink-0 transition-transform group-hover:scale-105">
                    @if(Auth::user()->avatar)
                        <img src="{{ filter_var(Auth::user()->avatar, FILTER_VALIDATE_URL) ? Auth::user()->avatar : asset('storage/'.Auth::user()->avatar) }}" alt="Profile" class="w-full h-full rounded-[14px] object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0d9488&color=fff&bold=true" alt="Profile" class="w-full h-full rounded-[14px] object-cover">
                    @endif
                </div>

                <div class="absolute left-0 right-auto top-full mt-3 w-48 sm:w-56 bg-white border border-slate-100 shadow-2xl rounded-2xl p-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 origin-top-left">

                    <div class="xl:hidden px-3 py-3 border-b border-slate-100 mb-2 bg-slate-50 rounded-xl">
                        <p class="text-xs font-black text-slate-800 truncate text-right mb-1">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-slate-500 font-medium truncate lowercase text-right">{{ Auth::user()->email }}</p>
                    </div>

                    <a href="{{ route('admin.profile.index') }}" class="w-full flex items-center justify-start gap-3 px-4 py-3 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors font-bold text-xs sm:text-sm mb-1">
                        <i class="fa-regular fa-user shrink-0 text-base"></i>
                        <span>الملف الشخصي</span>
                    </a>

                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-start gap-3 px-4 py-3 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors font-bold text-xs sm:text-sm">
                            <i class="fa-solid fa-arrow-right-from-bracket shrink-0 text-base"></i>
                            <span>تسجيل الخروج</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
