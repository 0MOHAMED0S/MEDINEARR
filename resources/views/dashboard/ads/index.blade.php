@extends('dashboard.layout.master')
@section('content')
        <div id="ajax-toast-container" class="fixed top-4 left-1/2 -translate-x-1/2 md:left-auto md:translate-x-0 md:right-8 z-[999999] flex flex-col gap-3 pointer-events-none w-max max-w-[90vw]">
            @if (session('success'))
                <div class="animate-toast pointer-events-auto bg-white border-r-4 border-emerald-500 shadow-[0_10px_40px_rgba(0,0,0,0.1)] rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm text-right">
                    <div class="bg-emerald-100 p-2 rounded-xl text-emerald-600 shrink-0">
                        <i class="fa-solid fa-circle-check text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">تمت العملية</p>
                        <p class="text-sm font-black text-slate-800 leading-tight">{{ session('success') }}</p>
                    </div>
                    <button type="button" onclick="this.closest('.animate-toast').remove()" class="text-slate-300 hover:text-slate-500 transition-colors shrink-0">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if (session('error') || $errors->any())
                <div class="animate-toast pointer-events-auto bg-white border-r-4 border-rose-500 shadow-[0_10px_40px_rgba(0,0,0,0.1)] rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm text-right">
                    <div class="bg-rose-100 p-2 rounded-xl text-rose-600 shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">خطأ في النظام</p>
                        <p class="text-sm font-black text-slate-800 leading-tight">
                            {{ session('error') ?? $errors->first() }}
                        </p>
                    </div>
                    <button type="button" onclick="this.closest('.animate-toast').remove()" class="text-slate-300 hover:text-slate-500 transition-colors shrink-0">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif
        </div>

    <div class="p-4 md:p-6 lg:p-8 relative z-0">


        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">إدارة الإعلانات الترويجية</h2>
                <p class="text-sm text-gray-500 font-medium mt-1">تخصيص وإدارة البنرات الإعلانية في تطبيق الموبايل</p>
            </div>
            <button onclick="openAdModal('add')" class="bg-gradient-custom text-white px-6 py-3 rounded-2xl font-bold shadow-[0_10px_20px_rgba(13,148,136,0.3)] hover:shadow-[0_15px_25px_rgba(13,148,136,0.4)] hover:-translate-y-1 transition-all flex items-center gap-2 group">
                <i class="fa-solid fa-plus transition-transform group-hover:rotate-90"></i> إضافة إعلان جديد
            </button>
        </div>


        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-8">
            <div class="stat-card bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md group">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform"><i class="fa-solid fa-bullhorn"></i></div>
                <div class="min-w-0 text-right">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate mb-1">إجمالي الإعلانات</p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['total'] ?? 0 }}</h3>
                </div>
            </div>
            <div class="stat-card bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md group">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform"><i class="fa-solid fa-eye"></i></div>
                <div class="min-w-0 text-right">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate mb-1">إعلانات نشطة</p>
                    <h3 id="stat-active" class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['active'] ?? 0 }}</h3>
                </div>
            </div>
            <div class="stat-card bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 transition-all hover:shadow-md group">
                <div class="w-14 h-14 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform"><i class="fa-solid fa-eye-slash"></i></div>
                <div class="min-w-0 text-right">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate mb-1">إعلانات متوقفة</p>
                    <h3 id="stat-inactive" class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['inactive'] ?? 0 }}</h3>
                </div>
            </div>
        </div>


        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden mb-8 p-6 md:p-10 flex flex-col lg:flex-row items-center justify-between gap-10 relative z-10">
            <div class="flex-1 text-center lg:text-right space-y-4">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 text-primary text-3xl mb-2">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                </div>
                <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">المعاينة الحية للتطبيق</h3>
                <p class="text-sm md:text-base text-gray-500 font-medium leading-relaxed max-w-lg mx-auto lg:mx-0">
                    يعرض هذا الهاتف شريط الإعلانات التلقائي (Auto-Carousel) تماماً كما سيظهر للمستخدمين في الصفحة الرئيسية للتطبيق. <br>
                    <strong class="text-emerald-500 bg-emerald-50 px-3 py-1 rounded-lg mt-3 inline-block border border-emerald-100">
                        <i class="fa-solid fa-bolt mr-1"></i> تتحدث هذه الواجهة فوراً بمجرد تفعيل أو إيقاف أي إعلان!
                    </strong>
                </p>
            </div>

            <div class="relative w-[280px] h-[580px] bg-slate-50 border-[12px] border-slate-900 rounded-[3rem] shadow-2xl shrink-0 overflow-hidden ring-4 ring-slate-100 flex flex-col z-10">
                <div class="absolute top-0 inset-x-0 h-6 bg-slate-900 rounded-b-2xl mx-16 z-50"></div>

                <div class="bg-white pt-10 pb-4 px-5 flex items-center justify-between z-40 shadow-sm relative">
                    <i class="fa-solid fa-bars text-slate-400 text-lg"></i>
                    <span class="font-black text-slate-800 tracking-widest text-sm">الرئيسية</span>
                    <div class="relative">
                        <i class="fa-regular fa-bell text-slate-400 text-lg"></i>
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-rose-500 rounded-full"></span>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto relative z-0 hide-scrollbar pb-6 flex flex-col">

                    <div class="px-4 mt-4 mb-2">
                        <div class="w-full h-10 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center px-3 gap-2">
                            <i class="fa-solid fa-search text-gray-300 text-xs"></i>
                            <div class="h-2 w-24 bg-gray-200 rounded-full"></div>
                        </div>
                    </div>

                    <div class="mt-3 w-full relative">
                        <div class="px-4 flex justify-between items-center mb-3">
                            <h4 class="text-xs font-black text-slate-800">أقوى العروض</h4>
                        </div>

                        <div class="relative w-full overflow-hidden">
                            <div class="flex overflow-x-auto snap-x snap-mandatory hide-scrollbar gap-3 px-4 pb-2 w-full transition-transform duration-300 ease-in-out" id="mobile-ads-carousel" dir="rtl">
                                </div>
                        </div>

                        <div class="flex justify-center items-center gap-1.5 mt-2 h-4" id="carousel-dots" dir="ltr"></div>
                    </div>

                    <div class="px-4 mb-4 mt-2">
                        <h4 class="text-xs font-black text-slate-800 mb-3 text-right">تسوق حسب القسم</h4>
                        <div class="grid grid-cols-4 gap-3">
                            <div class="flex flex-col items-center gap-1.5"><div class="w-12 h-12 rounded-full bg-white shadow-sm border border-gray-100"></div><div class="h-1.5 w-8 bg-gray-200 rounded-full"></div></div>
                            <div class="flex flex-col items-center gap-1.5"><div class="w-12 h-12 rounded-full bg-white shadow-sm border border-gray-100"></div><div class="h-1.5 w-8 bg-gray-200 rounded-full"></div></div>
                            <div class="flex flex-col items-center gap-1.5"><div class="w-12 h-12 rounded-full bg-white shadow-sm border border-gray-100"></div><div class="h-1.5 w-8 bg-gray-200 rounded-full"></div></div>
                            <div class="flex flex-col items-center gap-1.5"><div class="w-12 h-12 rounded-full bg-white shadow-sm border border-gray-100"></div><div class="h-1.5 w-8 bg-gray-200 rounded-full"></div></div>
                        </div>
                    </div>

                    <div class="px-4">
                        <h4 class="text-xs font-black text-slate-800 mb-3 text-right">الأكثر مبيعاً</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="h-32 bg-white rounded-2xl shadow-sm border border-gray-100"></div>
                            <div class="h-32 bg-white rounded-2xl shadow-sm border border-gray-100"></div>
                        </div>
                    </div>

                </div>

                <div class="bg-white border-t border-gray-100 h-16 flex items-center justify-around text-slate-400 z-40 rounded-b-[2rem]">
                    <i class="fa-solid fa-house text-primary text-xl"></i>
                    <i class="fa-solid fa-layer-group text-xl opacity-50"></i>
                    <i class="fa-solid fa-cart-shopping text-xl opacity-50"></i>
                    <i class="fa-solid fa-user text-xl opacity-50"></i>
                </div>
            </div>
        </div>


        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden mb-6 p-5 relative z-20">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-5">
                <div class="flex items-center gap-3 shrink-0">
                    <h3 class="font-black text-slate-800 text-lg">قائمة الإعلانات</h3>
                    <span id="resultCounter" class="bg-primary/10 text-primary text-[10px] font-black px-3 py-1.5 rounded-full uppercase">{{ count($ads ?? []) }} إعلان</span>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                    <div class="relative w-full sm:w-64">
                        <input type="text" id="searchInput" placeholder="ابحث بعنوان الإعلان..." class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 pr-11 text-sm focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none">
                        <i class="fa-solid fa-search absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <select id="statusFilter" class="w-full sm:w-auto bg-slate-50 border border-slate-200 rounded-2xl py-3 px-4 text-sm focus:bg-white focus:ring-4 focus:ring-primary/10 transition-all outline-none cursor-pointer">
                        <option value="all">جميع الحالات</option>
                        <option value="1">النشطة فقط</option>
                        <option value="0">المتوقفة فقط</option>
                    </select>
                </div>
            </div>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-6 relative z-10" id="adsGrid">
            @forelse($ads ?? [] as $ad)
                @php
                    $isActive = $ad->is_active;
                    $bgClass = $isActive ? '' : 'opacity-75 grayscale-[40%]';

                    // Safely extract Coupon code if relation exists
                    $couponCode = null;
                    if($ad->coupon) {
                        $couponCode = $ad->coupon->code;
                    }

                    // Package data for JS
                    $adDataForJs = [
                        'id' => $ad->id,
                        'type' => $ad->type,
                        'title' => $ad->title,
                        'description' => $ad->description,
                        'bg_color' => $ad->bg_color,
                        'image_url' => $ad->image_url,
                        'link' => $ad->link,
                        'coupon_id' => $ad->coupon_id,
                        'coupon_code' => $couponCode
                    ];
                @endphp

                <div class="ad-card relative bg-white rounded-[2rem] border border-gray-100 shadow-sm transition-all duration-300 overflow-hidden flex flex-col hover:-translate-y-1.5 hover:shadow-[0_20px_40px_rgba(0,0,0,0.06)] hover:border-primary/20 group/card {{ $bgClass }}"
                     data-search="{{ mb_strtolower($ad->title) }}"
                     data-active="{{ $isActive ? '1' : '0' }}"
                     data-ad-info="{{ json_encode($adDataForJs) }}">

                    <div class="absolute top-3 left-3 right-3 flex justify-between items-start z-30 pointer-events-none">
                        @if(!$isActive)
                            <div class="bg-rose-500/90 backdrop-blur-md text-white text-[10px] font-black px-3 py-1.5 rounded-xl shadow-md border border-white/20 flex items-center gap-1.5">
                                <i class="fa-solid fa-ban text-[8px]"></i> متوقف
                            </div>
                        @else
                            <div></div>
                        @endif

                        @if($couponCode)
                            <div class="bg-white/90 backdrop-blur-md shadow-lg border border-white text-primary text-[10px] font-black px-3 py-1.5 rounded-xl flex items-center gap-1.5 transform group-hover/card:scale-105 transition-transform duration-300">
                                <i class="fa-solid fa-ticket text-rose-500"></i>
                                <span class="tracking-widest">{{ $couponCode }}</span>
                            </div>
                        @endif
                    </div>

                    @if($ad->type === 'banner')
                        <div class="h-44 relative flex flex-col items-center justify-center p-6 z-10 transition-colors overflow-hidden group/banner" style="background-color: {{ $ad->bg_color ?? '#f8fafc' }}">
                            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover/banner:scale-150 transition-transform duration-700 ease-out"></div>
                            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-black/10 rounded-full blur-2xl group-hover/banner:scale-150 transition-transform duration-700 ease-out"></div>

                            <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl p-1.5 shadow-[0_8px_20px_rgba(0,0,0,0.15)] border border-white/40 mb-3 relative z-20 transform rotate-3 group-hover/banner:rotate-0 group-hover/banner:scale-110 transition-all duration-500 ease-out">
                                <img src="{{ $ad->image_url ?? 'https://ui-avatars.com/api/?name=Ad&background=fff' }}" class="w-full h-full object-cover rounded-xl">
                            </div>
                            <h4 class="font-black text-base text-center truncate w-full relative z-20 drop-shadow-md px-2" style="color: {{ $ad->bg_color && hexdec(str_replace('#','',$ad->bg_color)) < 0x888888 ? '#ffffff' : '#1e293b' }}">
                                {{ $ad->title }}
                            </h4>
                        </div>
                    @else
                        <div class="h-44 relative bg-slate-900 z-10 overflow-hidden">
                            <img src="{{ $ad->image_url ?? 'https://via.placeholder.com/400x200' }}" class="w-full h-full object-cover opacity-90 transition-transform duration-700 ease-out group-hover/card:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/95 via-slate-900/40 to-transparent flex items-end p-5">
                                <h4 class="font-black text-white text-base w-full drop-shadow-lg leading-tight line-clamp-2">{{ $ad->title }}</h4>
                            </div>
                        </div>
                    @endif

                    <div class="p-5 flex-grow flex flex-col relative z-20 bg-white">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[10px] font-bold uppercase {{ $ad->type === 'banner' ? 'text-blue-600 bg-blue-50 border-blue-100' : 'text-emerald-600 bg-emerald-50 border-emerald-100' }} px-3 py-1.5 rounded-lg border flex items-center gap-1.5">
                                <i class="fa-solid {{ $ad->type === 'banner' ? 'fa-bullhorn' : 'fa-image' }}"></i>
                                {{ $ad->type === 'banner' ? 'إعلان بنر' : 'إعلان غلاف' }}
                            </span>
                        </div>

                        @if($ad->type === 'banner' && $ad->description)
                            <p class="text-xs text-slate-500 font-medium line-clamp-2 mb-4 leading-relaxed">{{ $ad->description }}</p>
                        @endif

                        <div class="mt-auto">
                            @if($ad->link)
                                <a href="{{ $ad->link }}" target="_blank" class="group/link flex items-center gap-3 p-2.5 rounded-xl bg-slate-50 hover:bg-primary/5 border border-slate-100 hover:border-primary/20 transition-all text-left" dir="ltr">
                                    <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-slate-400 group-hover/link:text-primary transition-colors shrink-0">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">رابط التوجيه</p>
                                        <p class="text-[11px] font-bold text-slate-700 group-hover/link:text-primary truncate transition-colors">
                                            {{ Str::limit($ad->link, 35) }}
                                        </p>
                                    </div>
                                </a>
                            @else
                                <div class="flex items-center gap-3 p-2.5 rounded-xl bg-gray-50 border border-gray-100 text-left opacity-70" dir="ltr">
                                    <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-slate-300 shrink-0">
                                        <i class="fa-solid fa-link-slash text-[10px]"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">الرابط</p>
                                        <p class="text-[11px] font-bold text-slate-500">لا يوجد توجيه</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-slate-50/60 p-4 border-t border-gray-100 flex items-center justify-between relative z-20">
                        <div class="tooltip" title="{{ $isActive ? 'إيقاف الإعلان وإزالته من التطبيق' : 'تفعيل وعرض في التطبيق' }}">
                            <button type="button"
                                    onclick="inlineToggleStatus({{ $ad->id }}, this)"
                                    class="action-btn-toggle w-10 h-10 rounded-xl border border-gray-200 transition-all flex items-center justify-center shadow-sm
                                           {{ $isActive ? 'bg-white text-emerald-500 hover:border-emerald-300 hover:bg-emerald-50' : 'bg-white text-slate-400 hover:bg-slate-100' }}">
                                <i class="fa-solid fa-power-off text-sm"></i>
                            </button>
                        </div>

                        <div class="flex gap-2">
                            <button type="button" onclick="openAdModal('edit', this.closest('.ad-card'))" class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-300 transition-all flex items-center justify-center shadow-sm tooltip" title="تعديل الإعلان">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </button>
                            <button type="button" onclick="openDeleteModal({{ $ad->id }}, '{{ $ad->title }}')" class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-slate-500 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-300 transition-all flex items-center justify-center shadow-sm tooltip" title="حذف نهائي">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-white rounded-[3rem] border border-dashed border-gray-200">
                    <div class="w-24 h-24 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-5xl mx-auto mb-4"><i class="fa-solid fa-bullhorn"></i></div>
                    <p class="font-bold text-slate-500 text-lg">لا توجد إعلانات مضافة حالياً</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8 custom-pagination">
            {{ method_exists($ads ?? [], 'links') ? $ads->links() : '' }}
        </div>
    </div>


    <div id="adModal" class="fixed inset-0 z-[100001] hidden flex-col items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('adModal')"></div>
        <div class="relative bg-white rounded-[3rem] w-full max-w-2xl shadow-2xl overflow-hidden animate-scale-up flex flex-col max-h-[90vh]">
            <div class="p-6 md:p-8 border-b border-gray-50 flex justify-between items-center bg-slate-50/50 shrink-0">
                <h3 id="modalTitle" class="text-xl md:text-2xl font-black text-slate-800">إضافة إعلان</h3>
                <button type="button" onclick="toggleModal('adModal')" class="w-10 h-10 rounded-full bg-white shadow-sm border border-gray-100 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form id="adForm" action="{{ route('ads.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 overflow-y-auto scrollbar-thin flex-grow">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="space-y-6 text-right">

                    <div class="bg-slate-50 p-5 rounded-2xl border border-gray-200">
                        <label class="block text-sm font-black text-slate-700 mb-3">اختر نوع الإعلان <span class="text-rose-500">*</span></label>
                        <div class="flex gap-4">
                            <label class="flex-1 cursor-pointer group">
                                <input type="radio" name="type" value="banner" id="type_banner" class="peer sr-only" onchange="toggleFormFields()" {{ old('type', 'banner') == 'banner' ? 'checked' : '' }}>
                                <div class="p-4 bg-white border-2 border-gray-200 rounded-xl text-center peer-checked:border-primary peer-checked:bg-teal-50 hover:border-primary/50 transition-all">
                                    <i class="fa-solid fa-ad text-2xl text-slate-400 peer-checked:text-primary group-hover:text-primary mb-2 transition-colors"></i>
                                    <p class="font-bold text-sm text-slate-600 peer-checked:text-primary">إعلان بنر نصي</p>
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer group">
                                <input type="radio" name="type" value="cover" id="type_cover" class="peer sr-only" onchange="toggleFormFields()" {{ old('type') == 'cover' ? 'checked' : '' }}>
                                <div class="p-4 bg-white border-2 border-gray-200 rounded-xl text-center peer-checked:border-primary peer-checked:bg-teal-50 hover:border-primary/50 transition-all">
                                    <i class="fa-regular fa-image text-2xl text-slate-400 peer-checked:text-primary group-hover:text-primary mb-2 transition-colors"></i>
                                    <p class="font-bold text-sm text-slate-600 peer-checked:text-primary">إعلان غلاف كبير</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">عنوان الإعلان <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" id="ad_title" class="w-full bg-slate-50 border border-gray-200 focus:border-primary focus:ring-primary/20 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-4 transition-all outline-none text-slate-700" placeholder="مثال: خصم هائل على العناية بالبشرة" value="{{ old('title') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">رابط التوجيه (URL)</label>
                        <div class="relative">
                            <input type="url" name="link" id="ad_link" class="w-full bg-slate-50 border border-gray-200 focus:border-primary focus:ring-primary/20 rounded-xl px-4 py-3 pl-10 text-sm font-mono text-left focus:bg-white focus:ring-4 transition-all outline-none" placeholder="https://..." value="{{ old('link') }}" dir="ltr">
                            <i class="fa-solid fa-link absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1 font-bold">اختياري: وجه المستخدم إلى صفحة معينة عند الضغط</p>
                    </div>

                    <div id="banner_fields" class="space-y-6 animate-scale-up">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">الوصف النصي</label>
                            <input type="text" name="description" id="ad_description" class="w-full bg-slate-50 border border-gray-200 focus:border-primary focus:ring-primary/20 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-4 transition-all outline-none text-slate-700" placeholder="وصف قصير يظهر تحت العنوان" value="{{ old('description') }}">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">لون الخلفية</label>
                                <div class="flex items-center gap-2 bg-slate-50 border border-gray-200 rounded-xl p-1 focus-within:ring-4 focus-within:ring-primary/20 focus-within:border-primary transition-all">
                                    <input type="color" name="bg_color" id="ad_bg_color" class="h-9 w-12 rounded-lg cursor-pointer border-0" value="{{ old('bg_color', '#0f172a') }}">
                                    <input type="text" id="ad_bg_color_text" class="w-full bg-transparent border-0 px-2 py-2 text-sm font-mono text-left outline-none" value="{{ old('bg_color', '#0f172a') }}" dir="ltr" readonly>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">ربط بكوبون (اختياري)</label>
                                <div class="relative">
                                    <select name="coupon_id" id="ad_coupon_id" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm focus:bg-white focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all outline-none cursor-pointer appearance-none">
                                        <option value="">بدون كوبون</option>
                                        @foreach($coupons ?? [] as $c)
                                            <option value="{{ $c->id }}" {{ old('coupon_id') == $c->id ? 'selected' : '' }}>{{ $c->code }} - {{ $c->title }}</option>
                                        @endforeach
                                    </select>
                                    <i class="fa-solid fa-ticket absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                    <i class="fa-solid fa-chevron-down absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-3 text-center">أيقونة/شعار الإعلان <span class="text-rose-500">*</span></label>
                            <p class="text-[10px] text-gray-400 text-center mb-3 font-medium">يفضل صورة مربعة (مثال: 200x200)</p>
                            <div class="relative w-28 h-28 mx-auto rounded-full border-4 border-dashed border-gray-200 bg-slate-50 flex items-center justify-center cursor-pointer hover:border-primary transition-colors overflow-hidden group shadow-inner">
                                <input type="file" name="banner_image" id="bannerImageInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="image/*" onchange="previewImage(this, 'bannerPreviewImg', 'bannerPreviewContainer', 'bannerDefaultIcon')">
                                <i id="bannerDefaultIcon" class="fa-solid fa-camera text-3xl text-gray-300 group-hover:text-primary transition-colors z-10"></i>
                                <div id="bannerPreviewContainer" class="absolute inset-0 hidden z-10 bg-white">
                                    <img id="bannerPreviewImg" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="cover_fields" class="hidden space-y-6 animate-scale-up">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-3 text-center">صورة الغلاف (Cover) <span class="text-rose-500">*</span></label>
                            <p class="text-[10px] text-gray-400 text-center mb-3 font-medium">يفضل مقاس مستطيل أفقي للحفاظ على الجودة</p>
                            <div class="relative w-full h-48 rounded-[2rem] border-4 border-dashed border-gray-200 bg-slate-50 flex flex-col items-center justify-center cursor-pointer hover:border-primary transition-colors overflow-hidden group shadow-inner">
                                <input type="file" name="cover_image" id="coverImageInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="image/*" onchange="previewImage(this, 'coverPreviewImg', 'coverPreviewContainer', 'coverDefaultIcon')">
                                <div id="coverDefaultIcon" class="flex flex-col items-center z-10 transition-colors">
                                    <i class="fa-regular fa-image text-5xl text-gray-300 group-hover:text-primary mb-3 transition-colors"></i>
                                    <span class="text-sm font-bold text-gray-500 group-hover:text-primary transition-colors">اضغط لرفع صورة</span>
                                </div>
                                <div id="coverPreviewContainer" class="absolute inset-0 hidden z-10 bg-slate-800">
                                    <img id="coverPreviewImg" class="w-full h-full object-cover opacity-90">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex gap-4 mt-10 pt-6 border-t border-gray-100">
                    <button type="button" onclick="toggleModal('adModal')" class="flex-1 py-4 rounded-2xl font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 transition-all text-sm md:text-base">إلغاء</button>
                    <button type="submit" id="submit-btn" class="flex-[2] py-4 rounded-2xl font-bold text-white bg-primary hover:bg-primaryDark shadow-lg shadow-primary/30 transition-all flex items-center justify-center gap-2 text-sm md:text-base">
                        <i class="fa-solid fa-save"></i> حفظ الإعلان
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-[100002] hidden flex-col items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('deleteModal')"></div>
        <div class="relative bg-white rounded-[3rem] w-full max-w-sm shadow-2xl p-10 text-center animate-scale-up">
            <div class="w-24 h-24 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 border-4 shadow-inner bg-rose-50 text-rose-500 border-rose-100">
                <i class="fa-solid fa-trash-can animate-bounce-short"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800 mb-2">تأكيد الحذف</h3>
            <p id="deleteDesc" class="text-sm text-gray-500 font-medium mb-10 leading-relaxed"></p>
            <form id="deleteForm" method="POST" class="flex gap-3">
                @csrf @method('DELETE')
                <button type="button" onclick="toggleModal('deleteModal')" class="flex-1 py-4 rounded-2xl font-bold bg-slate-100 text-slate-500 hover:bg-slate-200 transition-all">تراجع</button>
                <button type="submit" class="flex-1 py-4 rounded-2xl font-bold text-white bg-rose-500 hover:bg-rose-600 shadow-lg shadow-rose-500/30 transition-all">نعم، احذف</button>
            </form>
        </div>
    </div>

    <script>
        const csrfToken = '{{ csrf_token() }}';

        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                toggleModal('adModal');
                toggleFormFields();
                syncCarousel();
            });
        @else
            document.addEventListener('DOMContentLoaded', function() {
                toggleFormFields();
                syncCarousel();
            });
        @endif

        function toggleModal(id) {
            const el = document.getElementById(id);
            if (el.classList.contains('hidden')) {
                el.classList.replace('hidden', 'flex');
            } else {
                el.classList.replace('flex', 'hidden');
                if (id === 'adModal') {
                    setTimeout(() => {
                        document.getElementById('adForm').reset();
                        document.getElementById('formMethod').value = 'POST';
                        document.getElementById('adForm').action = "{{ route('ads.store') }}";
                        document.getElementById('modalTitle').innerText = 'إضافة إعلان جديد';
                        document.getElementById('type_banner').checked = true;
                        toggleFormFields();

                        document.getElementById('bannerPreviewContainer').classList.add('hidden');
                        document.getElementById('bannerDefaultIcon').classList.remove('hidden');
                        document.getElementById('coverPreviewContainer').classList.add('hidden');
                        document.getElementById('coverDefaultIcon').classList.remove('hidden');

                        const errs = document.querySelectorAll('.text-rose-500.text-xs');
                        errs.forEach(e => e.remove());
                        const inputs = document.querySelectorAll('.border-rose-400');
                        inputs.forEach(i => {
                            i.classList.remove('border-rose-400', 'focus:border-rose-500', 'focus:ring-rose-500/20', 'bg-rose-50');
                            if(i.tagName === 'INPUT' || i.tagName === 'SELECT') {
                                i.classList.add('border-slate-200', 'focus:border-primary', 'focus:ring-primary/20');
                            }
                        });
                    }, 300);
                }
            }
        }

        function toggleFormFields() {
            const isBanner = document.getElementById('type_banner').checked;
            const bannerFields = document.getElementById('banner_fields');
            const coverFields = document.getElementById('cover_fields');

            if (isBanner) {
                bannerFields.classList.remove('hidden');
                coverFields.classList.add('hidden');
            } else {
                bannerFields.classList.add('hidden');
                coverFields.classList.remove('hidden');
            }
        }

        function previewImage(input, imgId, containerId, defaultId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(imgId).src = e.target.result;
                    document.getElementById(containerId).classList.remove('hidden');
                    document.getElementById(defaultId).classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('ad_bg_color').addEventListener('input', function() {
            document.getElementById('ad_bg_color_text').value = this.value;
        });

        function showToast(msg, success = true) {
            const container = document.getElementById('ajax-toast-container');
            const toast = document.createElement('div');

            const colorClass = success ? 'emerald' : 'rose';
            const icon = success ? 'fa-check' : 'fa-triangle-exclamation';
            const title = success ? 'نجاح العملية' : 'تنبيه';

            toast.className = `pointer-events-auto bg-white border-r-4 border-${colorClass}-500 shadow-[0_10px_40px_rgba(0,0,0,0.1)] rounded-2xl p-4 flex items-center gap-4 toast-slide-in min-w-[280px] max-w-sm text-right mb-3 mx-auto md:mr-auto md:ml-0`;
            toast.innerHTML = `
                <div class="w-10 h-10 bg-${colorClass}-50 text-${colorClass}-500 rounded-full flex items-center justify-center text-xl shrink-0 shadow-inner">
                    <i class="fa-solid ${icon}"></i>
                </div>
                <div class="flex-grow text-right">
                    <h4 class="font-bold text-slate-800 text-sm mb-0.5">${title}</h4>
                    <p class="text-xs text-slate-500 font-medium">${msg}</p>
                </div>
                <button type="button" onclick="this.closest('.toast-slide-in').remove()" class="text-slate-400 hover:text-${colorClass}-500 transition-colors p-2 shrink-0"><i class="fa-solid fa-xmark"></i></button>
            `;

            container.appendChild(toast);
            setTimeout(() => {
                if(toast.parentElement) {
                    toast.style.opacity = '0';
                    toast.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => toast.remove(), 500);
                }
            }, 4000);
        }

        let carouselInterval = null;
        let currentSlideIndex = 0;
        let totalSlidesCount = 0;

        function syncCarousel() {
            const carousel = document.getElementById('mobile-ads-carousel');
            const dotsContainer = document.getElementById('carousel-dots');
            const activeCards = document.querySelectorAll('.ad-card[data-active="1"]');

            carousel.innerHTML = '';
            dotsContainer.innerHTML = '';
            clearInterval(carouselInterval);
            currentSlideIndex = 0;
            totalSlidesCount = activeCards.length;

            if (totalSlidesCount === 0) {
                carousel.innerHTML = `
                    <div class="w-full h-[120px] flex flex-col items-center justify-center bg-white rounded-2xl border border-dashed border-gray-300 shadow-sm shrink-0">
                        <i class="fa-solid fa-box-open text-gray-300 text-2xl mb-1"></i>
                        <span class="text-xs text-gray-400 font-bold">لا توجد عروض نشطة</span>
                    </div>
                `;
                return;
            }

            activeCards.forEach((card, index) => {
                const adData = JSON.parse(card.getAttribute('data-ad-info'));

                let couponBadge = adData.coupon_code
                    ? `<span class="absolute top-2 left-2 bg-white/90 backdrop-blur-sm text-primary text-[8px] font-black px-2 py-1 rounded shadow-sm z-20 flex items-center gap-1 border border-white/50"><i class="fa-solid fa-ticket"></i> ${adData.coupon_code}</span>`
                    : '';

                let slideHtml = '';

                if (adData.type === 'banner') {
                    slideHtml = `
                        <div id="mobile-slide-${index}" class="snap-center shrink-0 w-[90%] mx-[5%] h-[120px] rounded-2xl p-4 flex items-center gap-3 shadow-md border border-black/5 relative overflow-hidden" style="background-color: ${adData.bg_color || '#0f172a'}">
                            ${couponBadge}
                            <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent z-0"></div>
                            <div class="w-14 h-14 bg-white/20 backdrop-blur-md border border-white/30 rounded-full p-1 shrink-0 shadow-inner z-10">
                                <img src="${adData.image_url || 'https://ui-avatars.com/api/?name=Ad&background=fff'}" class="w-full h-full object-cover rounded-full">
                            </div>
                            <div class="text-white min-w-0 text-right w-full z-10" style="color: ${adData.bg_color && hexdec(adData.bg_color.replace('#','')) < 0x888888 ? '#ffffff' : '#1e293b'}">
                                <h4 class="font-black text-[13px] leading-tight mb-1 truncate drop-shadow-md">${adData.title}</h4>
                                <p class="text-[9px] opacity-90 leading-tight truncate">${adData.description || ''}</p>
                            </div>
                        </div>
                    `;
                } else {
                    slideHtml = `
                        <div id="mobile-slide-${index}" class="snap-center shrink-0 w-[90%] mx-[5%] h-[120px] bg-slate-900 rounded-2xl overflow-hidden relative shadow-md border border-black/5">
                            ${couponBadge}
                            <img src="${adData.image_url || 'https://via.placeholder.com/400x200'}" class="w-full h-full object-cover opacity-90">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex items-end p-4 z-10">
                                <h4 class="font-black text-white text-[13px] w-full text-right drop-shadow-lg leading-tight truncate">${adData.title}</h4>
                            </div>
                        </div>
                    `;
                }

                carousel.insertAdjacentHTML('beforeend', slideHtml);

                const dot = document.createElement('button');
                dot.type = 'button';
                dot.id = `dot-${index}`;
                dot.onclick = () => goToSlide(index);
                dot.className = `h-1.5 rounded-full transition-all duration-300 cursor-pointer ${index === 0 ? 'w-4 bg-primary' : 'w-1.5 bg-gray-300 hover:bg-gray-400'}`;
                dotsContainer.appendChild(dot);
            });

            startCarousel();
        }

        function goToSlide(index) {
            const carousel = document.getElementById('mobile-ads-carousel');
            const slide = document.getElementById(`mobile-slide-${index}`);

            if (carousel && slide) {
                carousel.scrollTo({
                    left: slide.offsetLeft - carousel.offsetLeft,
                    behavior: 'smooth'
                });

                currentSlideIndex = index;
                updateDotsUI();
                resetCarouselInterval();
            }
        }

        function updateDotsUI() {
            for(let i = 0; i < totalSlidesCount; i++) {
                const dot = document.getElementById(`dot-${i}`);
                if (dot) {
                    dot.className = i === currentSlideIndex
                        ? 'h-1.5 w-4 rounded-full transition-all duration-300 bg-primary cursor-pointer'
                        : 'h-1.5 w-1.5 rounded-full transition-all duration-300 bg-gray-300 cursor-pointer hover:bg-gray-400';
                }
            }
        }

        function startCarousel() {
            if (totalSlidesCount <= 1) return;
            resetCarouselInterval();
        }

        function resetCarouselInterval() {
            clearInterval(carouselInterval);
            if (totalSlidesCount <= 1) return;

            carouselInterval = setInterval(() => {
                let nextSlide = (currentSlideIndex + 1) % totalSlidesCount;
                goToSlide(nextSlide);
            }, 3000);
        }

        function openAdModal(action, element = null) {
            const form = document.getElementById('adForm');
            const method = document.getElementById('formMethod');

            const errs = document.querySelectorAll('.text-rose-500.text-xs');
            errs.forEach(e => e.remove());
            const inputs = document.querySelectorAll('.border-rose-400');
            inputs.forEach(i => {
                i.classList.remove('border-rose-400', 'focus:border-rose-500', 'focus:ring-rose-500/20', 'bg-rose-50');
                if(i.tagName === 'INPUT' || i.tagName === 'SELECT') {
                    i.classList.add('border-slate-200', 'focus:border-primary', 'focus:ring-primary/20');
                }
            });

            form.reset();

            if (action === 'add') {
                document.getElementById('modalTitle').innerText = 'إضافة إعلان جديد';
                form.action = "{{ route('ads.store') }}";
                method.value = 'POST';
                document.getElementById('type_banner').checked = true;

                document.getElementById('bannerPreviewContainer').classList.add('hidden');
                document.getElementById('bannerDefaultIcon').classList.remove('hidden');
                document.getElementById('coverPreviewContainer').classList.add('hidden');
                document.getElementById('coverDefaultIcon').classList.remove('hidden');

            } else {
                const data = JSON.parse(element.getAttribute('data-ad-info'));
                document.getElementById('modalTitle').innerText = 'تعديل الإعلان';
                form.action = `/admin/ads/${data.id}`;
                method.value = 'PUT';

                document.getElementById('ad_title').value = data.title;
                document.getElementById('ad_link').value = data.link;

                if(data.type === 'banner') {
                    document.getElementById('type_banner').checked = true;
                    document.getElementById('ad_description').value = data.description;
                    document.getElementById('ad_bg_color').value = data.bg_color || '#0f172a';
                    document.getElementById('ad_bg_color_text').value = data.bg_color || '#0f172a';
                    document.getElementById('ad_coupon_id').value = data.coupon_id || '';

                    if(data.image_url) {
                        document.getElementById('bannerPreviewImg').src = data.image_url;
                        document.getElementById('bannerPreviewContainer').classList.remove('hidden');
                        document.getElementById('bannerDefaultIcon').classList.add('hidden');
                    }
                } else {
                    document.getElementById('type_cover').checked = true;
                    if(data.image_url) {
                        document.getElementById('coverPreviewImg').src = data.image_url;
                        document.getElementById('coverPreviewContainer').classList.remove('hidden');
                        document.getElementById('coverDefaultIcon').classList.add('hidden');
                    }
                }
            }
            toggleFormFields();
            toggleModal('adModal');
        }

        function openDeleteModal(id, title) {
            document.getElementById('deleteDesc').innerHTML = `سيتم حذف الإعلان <strong>${title}</strong> نهائياً. لا يمكن التراجع.`;
            document.getElementById('deleteForm').action = `/admin/ads/${id}`;
            toggleModal('deleteModal');
        }

        async function inlineToggleStatus(id, btnEl) {
            const originalHTML = btnEl.innerHTML;
            btnEl.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-sm"></i>';
            btnEl.disabled = true;

            try {
                const response = await fetch(`/admin/ads/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                btnEl.innerHTML = originalHTML;
                btnEl.disabled = false;

                if (data.success) {
                    showToast(data.message, true);
                    updateCardDOM(btnEl, data.is_active);
                    syncCarousel();

                    const aEl = document.getElementById('stat-active');
                    const iEl = document.getElementById('stat-inactive');
                    if(aEl && iEl) {
                        aEl.innerText = parseInt(aEl.innerText) + (data.is_active ? 1 : -1);
                        iEl.innerText = parseInt(iEl.innerText) + (data.is_active ? -1 : 1);
                    }
                } else {
                    showToast(data.message, false);
                }
            } catch (err) {
                btnEl.innerHTML = originalHTML;
                btnEl.disabled = false;
                showToast('خطأ في الاتصال بالخادم.', false);
            }
        }

        function updateCardDOM(btn, isActive) {
            const card = btn.closest('.ad-card');
            card.setAttribute('data-active', isActive ? '1' : '0');

            if (isActive) {
                card.classList.remove('opacity-75', 'grayscale-[40%]');
                card.classList.add('hover:-translate-y-1.5', 'hover:shadow-[0_20px_40px_rgba(0,0,0,0.06)]', 'hover:border-primary/20');
                btn.className = "action-btn-toggle w-10 h-10 rounded-xl border border-gray-200 transition-all flex items-center justify-center shadow-sm bg-white text-emerald-500 hover:border-emerald-300 hover:bg-emerald-50";
                const badge = card.querySelector('.bg-rose-500\\/90');
                if(badge) badge.remove();
            } else {
                card.classList.add('opacity-75', 'grayscale-[40%]');
                card.classList.remove('hover:-translate-y-1.5', 'hover:shadow-[0_20px_40px_rgba(0,0,0,0.06)]', 'hover:border-primary/20');
                btn.className = "action-btn-toggle w-10 h-10 rounded-xl border border-gray-200 transition-all flex items-center justify-center shadow-sm bg-white text-slate-400 hover:bg-slate-100";

                const badgeContainer = card.querySelector('.absolute.top-3.left-3.right-3');
                if(badgeContainer && !badgeContainer.querySelector('.bg-rose-500\\/90')) {
                    badgeContainer.insertAdjacentHTML('afterbegin', '<div class="bg-rose-500/90 backdrop-blur-md text-white text-[10px] font-black px-3 py-1.5 rounded-xl shadow-md border border-white/20 flex items-center gap-1.5"><i class="fa-solid fa-ban text-[8px]"></i> متوقف</div>');
                }
            }
        }

        function filterAds() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const status = document.getElementById('statusFilter').value;
            const cards = document.querySelectorAll('.ad-card');
            let count = 0;

            cards.forEach(card => {
                const searchData = card.getAttribute('data-search');
                const active = card.getAttribute('data-active');
                const match = searchData.includes(search) && (status === 'all' || active === status);
                card.style.display = match ? 'flex' : 'none';
                if(match) count++;
            });
            document.getElementById('resultCounter').innerText = count + ' إعلان';
        }

        document.getElementById('searchInput').addEventListener('input', filterAds);
        document.getElementById('statusFilter').addEventListener('change', filterAds);

        document.getElementById('adForm').addEventListener('submit', function() {
            const btn = document.getElementById('submit-btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جاري الحفظ...';
            btn.classList.add('opacity-75', 'cursor-not-allowed');
        });

        function hexdec(hexString) {
            return parseInt(hexString, 16);
        }
    </script>

    <style>
        .animate-scale-up { animation: scaleUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes scaleUp { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .toast-slide-in { animation: slideInDown 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes slideInDown { 0% { opacity: 0; transform: translateY(-50px); } 100% { opacity: 1; transform: translateY(0); } }
        .animate-bounce-short { animation: bounceShort 1.5s infinite; }
        @keyframes bounceShort { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10%); } }
        .bg-gradient-custom { background: linear-gradient(135deg, #0d9488 0%, #84cc16 100%); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
@endsection
