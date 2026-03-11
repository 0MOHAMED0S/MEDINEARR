<header class="glass-nav sticky top-0 z-30 px-2 sm:px-6 py-2.5 md:py-4 border-b border-slate-100 bg-white/80 backdrop-blur-md flex items-center justify-between transition-all">
    <div class="flex items-center gap-1.5 sm:gap-4 min-w-0 flex-1">
        <button onclick="toggleSidebar()" class="lg:hidden text-primary text-lg p-2 hover:bg-teal-50 rounded-xl transition-colors shrink-0">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>

        <div class="min-w-0">
            <h2 class="text-sm sm:text-lg md:text-xl font-bold text-slate-800 leading-tight truncate">
                مرحباً، <span class="text-primary">{{ explode(' ', Auth::user()->name)[0] }}</span> 👋
            </h2>
            <p class="text-[8px] md:text-xs font-medium text-slate-400 hidden xs:block italic truncate">
                صلاحيات كاملة
            </p>
        </div>
    </div>

    <div class="flex items-center gap-1.5 sm:gap-4 shrink-0">
        <button class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-500 relative hover:bg-teal-50 hover:text-primary transition-all group shrink-0">
            <i class="fa-solid fa-bell text-sm sm:text-lg group-hover:shake"></i>
            <span class="absolute top-1.5 sm:top-2.5 left-1.5 sm:left-2.5 w-1.5 h-1.5 sm:w-2 sm:h-2 bg-rose-500 rounded-full border-2 border-white"></span>
        </button>

        <div class="h-6 w-[1px] bg-slate-100 mx-0.5 sm:mx-1 hidden min-[350px]:block"></div>

        <div class="flex items-center gap-2">
            <div class="hidden xl:flex flex-col items-start text-right min-w-0 ml-1">
                <span class="text-[10px] font-black text-slate-700 leading-none mb-1 truncate max-w-[100px]">
                    {{ Auth::user()->name }}
                </span>
                <span class="text-[8px] font-bold text-slate-400 lowercase truncate max-w-[100px]">
                    {{ Auth::user()->email }}
                </span>
            </div>

            <div class="relative group">
                <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-11 md:h-11 rounded-xl bg-primary/10 p-0.5 border border-primary/20 cursor-pointer overflow-hidden shadow-sm shrink-0">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0d9488&color=fff&bold=true"
                    alt="Profile" class="w-full h-full rounded-[10px] object-cover">
                </div>

                <div class="absolute left-0 right-auto top-full mt-2 w-40 sm:w-48 bg-white border border-slate-100 shadow-2xl rounded-2xl p-1.5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 origin-top-left">

                    <div class="xl:hidden px-3 py-2 border-b border-slate-50 mb-1">
                        <p class="text-[10px] font-black text-slate-700 truncate text-right">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] text-slate-400 truncate lowercase text-right">{{ Auth::user()->email }}</p>
                    </div>

                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-start gap-2.5 px-3 py-2.5 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors font-bold text-[11px] sm:text-sm">
                            <i class="fa-solid fa-right-from-bracket shrink-0"></i>
                            <span>تسجيل الخروج</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
