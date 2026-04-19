@extends('dashboard.layout.master')
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div class="p-4 md:p-6 lg:p-8 relative">

        <div id="ajax-toast-container" class="fixed top-4 left-4 right-4 md:left-auto md:right-6 md:top-6 z-[999999] flex flex-col gap-3 pointer-events-none w-max max-w-[90vw]">
            @if(session('success'))
                <div class="animate-toast pointer-events-auto bg-white border-r-4 border-emerald-500 shadow-xl rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm ml-auto text-right mb-3">
                    <div class="bg-emerald-100 p-2 rounded-xl text-emerald-600 shrink-0">
                        <i class="fa-solid fa-circle-check text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">تمت العملية</p>
                        <p class="text-sm font-black text-slate-800 leading-tight">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-slate-300 hover:text-slate-500 transition-colors shrink-0"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="animate-toast pointer-events-auto bg-white border-r-4 border-rose-500 shadow-xl rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm ml-auto text-right mb-3">
                    <div class="bg-rose-100 p-2 rounded-xl text-rose-600 shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">خطأ</p>
                        <p class="text-sm font-black text-slate-800 leading-tight">{{ $errors->first() ?? 'يرجى مراجعة البيانات المدخلة.' }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-slate-300 hover:text-slate-500 transition-colors shrink-0"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">إدارة الصيدليات المعتمدة</h2>
                <p class="text-sm text-gray-500 font-medium mt-1">متابعة وإدارة الصيدليات النشطة، التحليلات، والتوزيع الجغرافي</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-primary/20 group">
                <div class="w-14 h-14 md:w-16 md:h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div class="min-w-0 text-right">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate mb-1">إجمالي الصيدليات</p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['total'] ?? 0 }}</h3>
                </div>
            </div>

            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-emerald-200 group">
                <div class="w-14 h-14 md:w-16 md:h-16 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-power-off"></i>
                </div>
                <div class="min-w-0 text-right">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate mb-1">نشطة (مفعلة)</p>
                    <h3 id="stat-active" class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['active'] ?? 0 }}</h3>
                </div>
            </div>

            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-amber-200 group">
                <div class="w-14 h-14 md:w-16 md:h-16 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-star text-xl"></i>
                </div>
                <div class="min-w-0 text-right">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate mb-1">صيدليات كبرى</p>
                    <h3 id="stat-big" class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['big'] ?? 0 }}</h3>
                </div>
            </div>

            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-rose-200 group">
                <div class="w-14 h-14 md:w-16 md:h-16 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-ban"></i>
                </div>
                <div class="min-w-0 text-right">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate mb-1">موقوفة</p>
                    <h3 id="stat-inactive" class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['inactive'] ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden transition-all mb-8" id="pharmacyTableSection">
            <div class="p-5 md:p-6 border-b border-gray-50 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5 bg-slate-50/30">
                <div class="flex items-center gap-3 shrink-0">
                    <h3 class="font-black text-slate-800 text-lg tracking-tight">سجل الصيدليات المعتمدة</h3>
                    <span class="bg-primary/10 text-primary text-[10px] font-black px-3 py-1.5 rounded-full transition-all uppercase tracking-wider">
                        {{ $pharmacies->total() }} صيدلية
                    </span>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto flex-wrap">

                    <div class="relative w-full sm:w-56 lg:w-64">
                        <input type="text" id="serverSearchInput" value="{{ request('search') }}" placeholder="ابحث باسم الصيدلية (اضغط Enter)" class="w-full bg-white border border-gray-200 rounded-2xl px-5 py-3 pr-11 text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none text-slate-700 shadow-sm">
                        <i class="fa-solid fa-search absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        @if(request('search'))
                            <button type="button" onclick="setServerFilter('search', '')" class="absolute left-3 top-1/2 -translate-y-1/2 text-rose-400 hover:text-rose-600 text-xs font-bold">إلغاء</button>
                        @endif
                    </div>

                    <div class="relative w-full sm:w-auto">
                        @php $currentLoc = request('location', 'all'); @endphp
                        <select onchange="setServerFilter('location', this.value)" class="w-full sm:w-auto bg-white border border-gray-200 rounded-2xl py-3 pr-10 pl-8 text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none appearance-none text-slate-600 shadow-sm font-medium cursor-pointer">
                            <option value="all">كل المحافظات</option>
                            @foreach($governoratesMap as $key => $arName)
                                <option value="{{ $key }}" {{ $currentLoc === $key ? 'selected' : '' }}>{{ $arName }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-map-location-dot absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        <i class="fa-solid fa-chevron-down absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                    </div>

                    <div class="relative w-full sm:w-auto">
                        @php $currentBig = request('big', 'all'); @endphp
                        <select onchange="setServerFilter('big', this.value)" class="w-full sm:w-auto bg-white border border-gray-200 rounded-2xl py-3 pr-10 pl-8 text-sm focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all outline-none appearance-none text-slate-600 shadow-sm font-medium cursor-pointer">
                            <option value="all" {{ $currentBig === 'all' ? 'selected' : '' }}>التصنيف (الكل)</option>
                            <option value="1" {{ $currentBig === '1' ? 'selected' : '' }}>صيدليات كبرى فقط</option>
                            <option value="0" {{ $currentBig === '0' ? 'selected' : '' }}>صيدليات عادية</option>
                        </select>
                        <i class="fa-solid fa-star absolute right-4 top-1/2 -translate-y-1/2 text-amber-400 pointer-events-none"></i>
                        <i class="fa-solid fa-chevron-down absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                    </div>

                    <div class="relative w-full sm:w-auto">
                        @php $currentStatus = request('status', 'all'); @endphp
                        <select onchange="setServerFilter('status', this.value)" class="w-full sm:w-auto bg-white border border-gray-200 rounded-2xl py-3 pr-10 pl-8 text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none appearance-none text-slate-600 shadow-sm font-medium cursor-pointer">
                            <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>الحالة (الكل)</option>
                            <option value="1" {{ $currentStatus === '1' ? 'selected' : '' }}>نشط (مفعل)</option>
                            <option value="0" {{ $currentStatus === '0' ? 'selected' : '' }}>موقوف</option>
                        </select>
                        <i class="fa-solid fa-filter absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        <i class="fa-solid fa-chevron-down absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                    </div>

                </div>
            </div>

            <div class="overflow-x-auto scrollbar-thin">
                <table class="w-full text-right min-w-[1000px]">
                    <thead class="bg-slate-50 border-b border-gray-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                        <tr>
                            <th class="p-5 w-16 text-center">#</th>
                            <th class="p-5">الصيدلية والموقع</th>
                            <th class="p-5">المالك والتواصل</th>
                            <th class="p-5 w-32 text-center">تاريخ الانضمام</th>
                            <th class="p-5 w-32 text-center">تفعيل / إيقاف</th>
                            <th class="p-5 w-32 text-center">صيدلية كبرى</th>
                            <th class="p-5 w-48 text-center text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="tableBody">
                        @forelse ($pharmacies as $index => $pharmacy)
                            @php
                                $isBigPharmacy = $pharmacy->is_big_pharmacy ?? false;
                            @endphp

                            <tr class="pharmacy-row hover:bg-slate-50 transition-all duration-300 group"
                                data-id="{{ $pharmacy->id }}"
                                data-name="{{ mb_strtolower($pharmacy->pharmacy_name . ' ' . ($pharmacy->user->name ?? $pharmacy->owner_name)) }}"
                                data-active="{{ $pharmacy->is_active ? '1' : '0' }}"
                                data-big="{{ $pharmacy->is_big_pharmacy ? '1' : '0' }}"
                                data-lat="{{ $pharmacy->lat ?? '' }}"
                                data-lng="{{ $pharmacy->lng ?? '' }}"
                                data-phone="{{ $pharmacy->phone ?? 'غير متوفر' }}"
                                data-address="{{ $pharmacy->address ?? 'العنوان غير محدد' }}"
                                data-image="{{ $pharmacy->image ? asset('storage/'.$pharmacy->image) : 'https://ui-avatars.com/api/?name='.urlencode($pharmacy->pharmacy_name).'&background=0d9488&color=fff' }}">

                                <td class="p-5 font-bold text-slate-400 text-xs text-center">{{ $pharmacies->firstItem() + $index }}</td>

                                <td class="p-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center overflow-hidden shadow-sm border border-gray-100 shrink-0">
                                            @if($pharmacy->image)
                                                <img src="{{ asset('storage/'.$pharmacy->image) }}" class="w-full h-full object-cover">
                                            @else
                                                <i class="fa-solid fa-store text-primary/50 text-xl"></i>
                                            @endif
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-black text-slate-800 text-sm mb-1 flex items-center flex-wrap gap-2">
                                                {{ $pharmacy->pharmacy_name }}

                                                <span id="badge-{{ $pharmacy->id }}" class="text-[8px] px-1.5 py-0.5 rounded-md font-black uppercase tracking-wider border {{ $pharmacy->is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200' }}">
                                                    {{ $pharmacy->is_active ? 'نشط' : 'موقوف' }}
                                                </span>

                                                <span id="big-badge-{{ $pharmacy->id }}" class="items-center gap-1 px-2 py-0.5 rounded-md bg-gradient-to-r from-amber-100 to-yellow-50 text-amber-600 border border-amber-200 text-[9px] font-black shadow-sm tooltip {{ $pharmacy->is_big_pharmacy ? 'flex' : 'hidden' }}" title="صيدلية كبرى">
                                                    <i class="fa-solid fa-star text-amber-400"></i> كبرى
                                                </span>
                                            </span>

                                            <span class="text-[11px] text-gray-500 font-medium flex items-center gap-1.5 mt-0.5">
                                                <i class="fa-solid fa-map-location-dot text-gray-400"></i>
                                                <span class="font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">{{ $pharmacy->city ?? 'غير محدد' }}</span>
                                                @if($pharmacy->address)
                                                    <span class="text-gray-300">|</span>
                                                    <span class="truncate max-w-[120px] sm:max-w-[150px] inline-block" title="{{ $pharmacy->address }}">{{ $pharmacy->address }}</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td class="p-5">
                                    <div class="flex flex-col items-start">
                                        @php
                                            $ownerData = [
                                                'name'   => $pharmacy->user->name ?? $pharmacy->owner_name,
                                                'email'  => $pharmacy->user->email ?? $pharmacy->email,
                                                'phone'  => $pharmacy->user->phone ?? $pharmacy->phone,
                                                'avatar' => $pharmacy->user->avatar ?? null
                                            ];
                                        @endphp
                                        <button type="button"
                                                onclick="openOwnerModal({{ json_encode($ownerData) }})"
                                                class="font-bold text-slate-700 text-sm mb-1 hover:text-blue-600 transition-colors flex items-center gap-1.5 tooltip text-right" title="عرض تفاصيل حساب المالك">
                                            <i class="fa-regular fa-id-badge text-gray-400 text-xs"></i>
                                            {{ $ownerData['name'] }}
                                        </button>
                                        <span class="text-[11px] text-gray-500 font-mono" dir="ltr">{{ $ownerData['phone'] }}</span>
                                    </div>
                                </td>

                                <td class="p-5 text-center">
                                    <span class="text-xs font-bold text-slate-500">{{ $pharmacy->created_at->format('Y/m/d') }}</span>
                                </td>

                                <td class="p-5 text-center">
                                    <button type="button"
                                            id="toggle-btn-{{ $pharmacy->id }}"
                                            onclick="openToggleConfirmModal({{ $pharmacy->id }}, '{{ explode(' ', $pharmacy->pharmacy_name)[0] }}', {{ $pharmacy->is_active ? 'true' : 'false' }})"
                                            class="relative inline-flex h-6 w-12 items-center rounded-full transition-colors duration-300 ease-in-out focus:outline-none {{ $pharmacy->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}">
                                        <span id="toggle-knob-{{ $pharmacy->id }}"
                                              class="inline-block h-4 w-4 transform rounded-full bg-white transition duration-300 ease-in-out {{ $pharmacy->is_active ? '-translate-x-7' : '-translate-x-1' }}">
                                        </span>
                                    </button>
                                </td>

                                <td class="p-5 text-center">
                                    <button type="button"
                                            id="toggle-big-btn-{{ $pharmacy->id }}"
                                            onclick="openToggleBigConfirmModal({{ $pharmacy->id }}, '{{ explode(' ', $pharmacy->pharmacy_name)[0] }}', {{ $pharmacy->is_big_pharmacy ? 'true' : 'false' }})"
                                            class="relative inline-flex h-6 w-12 items-center rounded-full transition-colors duration-300 ease-in-out focus:outline-none {{ $pharmacy->is_big_pharmacy ? 'bg-amber-500' : 'bg-gray-300' }}">
                                        <span id="toggle-big-knob-{{ $pharmacy->id }}" class="inline-block h-4 w-4 transform rounded-full bg-white transition duration-300 ease-in-out {{ $pharmacy->is_big_pharmacy ? '-translate-x-7' : '-translate-x-1' }} flex items-center justify-center shadow-sm">
                                              <i id="toggle-big-icon-{{ $pharmacy->id }}" class="fa-solid {{ $pharmacy->is_big_pharmacy ? 'fa-star text-amber-500' : 'fa-star text-gray-300' }} text-[8px]"></i>
                                        </span>
                                    </button>
                                </td>

                                <td class="p-5 text-center">
                                    <div class="flex items-center justify-center gap-2">

                                        @if($pharmacy->lat && $pharmacy->lng && $pharmacy->is_active)
                                            <button type="button" onclick="focusOnMap({{ $pharmacy->lat }}, {{ $pharmacy->lng }}, {{ $pharmacy->id }})" class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 hover:border-emerald-200 transition-all flex items-center justify-center shadow-sm tooltip" title="تحديد على الخريطة">
                                                <i class="fa-solid fa-location-crosshairs text-sm"></i>
                                            </button>
                                        @endif

                                        <button type="button" onclick="openViewModal({{ json_encode($pharmacy) }})" class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 hover:text-primary hover:bg-teal-50 hover:border-teal-200 transition-all flex items-center justify-center shadow-sm tooltip" title="عرض الملف الكامل">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </button>

                                        <button type="button" onclick="openEditModal({{ json_encode($pharmacy) }})" class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 transition-all flex items-center justify-center shadow-sm tooltip" title="تعديل بيانات الصيدلية">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </button>
                                    </div>
                                </td>

                                <input type="hidden" id="raw-pharmacy-{{ $pharmacy->id }}" value="{{ json_encode($pharmacy) }}">
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-20 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        @if(request('search') || request('status') || request('big') || request('location'))
                                            <i class="fa-solid fa-magnifying-glass text-4xl mb-4 text-gray-200"></i>
                                            <p class="font-bold text-lg text-slate-600">لا توجد نتائج مطابقة لبحثك</p>
                                            <a href="{{ route('admin.pharmacies.index') }}" class="mt-4 text-sm text-primary hover:underline">إلغاء جميع الفلاتر</a>
                                        @else
                                            <i class="fa-solid fa-store-slash text-4xl mb-4 text-gray-200"></i>
                                            <p class="font-bold text-lg text-slate-600">لا توجد صيدليات معتمدة بعد</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pharmacies->hasPages())
            <div class="px-6 py-4 bg-slate-50/50 border-t border-gray-100">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-center sm:text-right">
                    <p class="text-xs font-bold text-slate-500">
                        عرض <span class="text-slate-800">{{ $pharmacies->firstItem() ?? 0 }}</span> إلى <span class="text-slate-800">{{ $pharmacies->lastItem() ?? 0 }}</span> من إجمالي <span class="text-slate-800">{{ $pharmacies->total() }}</span> صيدلية
                    </p>
                    <div class="custom-pagination">
                        {{ $pharmacies->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="mt-10">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <h3 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">توزيع الصيدليات المعتمدة</h3>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 md:gap-4">

                <div onclick="setServerFilter('location', 'all')" class="gov-card bg-white p-3.5 md:p-4 rounded-2xl border {{ $currentLoc === 'all' ? 'border-rose-400 ring-2 ring-rose-50 bg-rose-50/20' : 'border-gray-100' }} shadow-sm flex items-center justify-between group hover:border-rose-400 hover:shadow-lg transition-all cursor-pointer">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center group-hover:bg-rose-500 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-layer-group text-[10px]"></i>
                        </div>
                        <span class="font-bold text-slate-700 text-[11px] truncate">الكل</span>
                    </div>
                    <span class="px-2 py-1 rounded-md text-[10px] font-black shadow-inner {{ $totalFilteredCount > 0 ? 'bg-rose-500 text-white' : 'bg-slate-100 text-slate-400' }}">
                        {{ $totalFilteredCount }}
                    </span>
                </div>

                @foreach($governoratesMap as $key => $name)
                    <div onclick="setServerFilter('location', '{{ $key }}')" class="gov-card bg-white p-3.5 md:p-4 rounded-2xl border {{ $currentLoc === $key ? 'border-rose-400 ring-2 ring-rose-50 bg-rose-50/20' : 'border-gray-100' }} shadow-sm flex items-center justify-between group hover:border-rose-400 hover:shadow-lg transition-all cursor-pointer">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center group-hover:bg-rose-500 group-hover:text-white transition-colors">
                                <i class="fa-solid fa-map-pin text-[10px]"></i>
                            </div>
                            <span class="font-bold text-slate-700 text-[11px] truncate">{{ $name }}</span>
                        </div>
                        <span class="px-2 py-1 rounded-md text-[10px] font-black shadow-inner transition-colors {{ ($govCounts[$key] ?? 0) > 0 ? 'bg-rose-500 text-white' : 'bg-rose-50 text-rose-600' }}">
                            {{ $govCounts[$key] ?? 0 }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-10 bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-map-marked-alt"></i>
                </div>
                <h3 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">خريطة الانتشار (الصيدليات المعتمدة)</h3>
            </div>

            <div id="egyptMap" class="w-full h-[500px] rounded-2xl z-10 border border-gray-200 shadow-inner"></div>
        </div>

    </div>

    <div id="toggleConfirmModal" class="fixed inset-0 z-[110] hidden flex-col items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('toggleConfirmModal')"></div>
        <div class="relative bg-white rounded-[2.5rem] w-full max-w-sm shadow-2xl overflow-hidden animate-scale-up text-center p-8 md:p-10">
            <div id="tc-icon-bg" class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner border-[6px]">
                <i id="tc-icon" class="fa-solid animate-bounce-short"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800 mb-2">تأكيد تغيير الحالة</h3>
            <p id="tc-message" class="text-sm text-gray-500 font-medium mb-8 leading-relaxed"></p>

            <div class="flex items-center justify-center gap-3">
                <button type="button" onclick="toggleModal('toggleConfirmModal')" class="flex-1 px-6 py-4 rounded-2xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors text-sm">إلغاء الأمر</button>
                <button type="button" id="tc-confirm-btn" onclick="executeToggle()" class="flex-1 px-6 py-4 rounded-2xl font-bold text-white shadow-lg transition-all text-sm">
                    تأكيد
                </button>
            </div>
        </div>
    </div>

    <div id="toggleBigConfirmModal" class="fixed inset-0 z-[110] hidden flex-col items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('toggleBigConfirmModal')"></div>
        <div class="relative bg-white rounded-[2.5rem] w-full max-w-sm shadow-2xl overflow-hidden animate-scale-up text-center p-8 md:p-10">
            <div id="tb-icon-bg" class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner border-[6px]">
                <i id="tb-icon" class="fa-solid animate-bounce-short"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800 mb-2">تعديل تصنيف الصيدلية</h3>
            <p id="tb-message" class="text-sm text-gray-500 font-medium mb-8 leading-relaxed"></p>

            <div class="flex items-center justify-center gap-3">
                <button type="button" onclick="toggleModal('toggleBigConfirmModal')" class="flex-1 px-6 py-4 rounded-2xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors text-sm">إلغاء الأمر</button>
                <button type="button" id="tb-confirm-btn" onclick="executeBigToggle()" class="flex-1 px-6 py-4 rounded-2xl font-bold text-white shadow-lg transition-all text-sm">
                    تأكيد
                </button>
            </div>
        </div>
    </div>

    <div id="ownerModal" class="fixed inset-0 z-[110] hidden flex-col items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('ownerModal')"></div>
        <div class="relative bg-white rounded-[2.5rem] w-full max-w-sm shadow-2xl overflow-hidden animate-scale-up text-center p-8">
            <button type="button" onclick="toggleModal('ownerModal')" class="absolute top-4 left-4 w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition-all flex items-center justify-center">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="w-24 h-24 rounded-full border-4 border-white shadow-lg mx-auto mb-4 overflow-hidden bg-slate-100">
                <img id="o-avatar" src="" alt="Owner Account" class="w-full h-full object-cover">
            </div>

            <h3 id="o-name" class="text-xl font-black text-slate-800 mb-1">اسم المالك</h3>
            <p class="text-[11px] font-black text-blue-500 mb-6 uppercase tracking-wider bg-blue-50 inline-block px-3 py-1 rounded-full border border-blue-100">بيانات الحساب المسجل</p>

            <div class="bg-slate-50 rounded-2xl p-4 text-right space-y-4 border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[10px] uppercase font-bold text-gray-400 mb-0.5">رقم هاتف الحساب</span>
                        <p id="o-phone" class="text-sm font-bold text-slate-700 font-mono" dir="ltr"></p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[10px] uppercase font-bold text-gray-400 mb-0.5">البريد الإلكتروني المسجل</span>
                        <p id="o-email" class="text-sm font-bold text-slate-700 font-mono truncate" dir="ltr"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="viewProfileModal" class="fixed inset-0 z-[100] hidden flex-col items-center justify-center p-4 sm:p-6">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('viewProfileModal')"></div>
        <div class="relative bg-white rounded-[2.5rem] w-full max-w-3xl shadow-2xl overflow-hidden animate-scale-up flex flex-col max-h-full">
            <div class="relative h-32 md:h-40 bg-gradient-custom shrink-0">
                <button type="button" onclick="toggleModal('viewProfileModal')" class="absolute top-4 left-4 w-10 h-10 rounded-2xl bg-white/20 hover:bg-white text-white hover:text-slate-800 transition-all flex items-center justify-center backdrop-blur-md">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="px-6 md:px-10 pb-8 flex-1 overflow-y-auto scrollbar-thin relative -mt-16 text-right">
                <div class="flex flex-col md:flex-row gap-5 items-center md:items-end border-b border-gray-100 pb-6 mb-6">
                    <div class="w-28 h-28 md:w-32 md:h-32 rounded-full border-4 border-white shadow-lg bg-white overflow-hidden shrink-0">
                        <img id="v-image" src="" alt="Pharmacy" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 text-center md:text-right mt-4 md:mt-0">
                        <h2 class="text-2xl font-black text-slate-800 mb-1 flex items-center justify-center md:justify-start gap-2">
                            <span id="v-name-text">اسم الصيدلية</span>
                            <span id="v-big-badge" class="hidden items-center gap-1 px-2 py-0.5 rounded-md bg-gradient-to-r from-amber-100 to-yellow-50 text-amber-600 border border-amber-200 text-[10px] font-black shadow-sm">
                                <i class="fa-solid fa-star text-amber-400"></i> كبرى
                            </span>
                        </h2>
                        <p id="v-owner" class="text-sm font-bold text-gray-500"><i class="fa-solid fa-user-tie ml-1"></i> اسم المالك</p>
                    </div>
                    <div id="v-status-badge" class="shrink-0 mt-4 md:mt-0"></div>
                </div>

                <div class="flex flex-wrap items-center justify-between bg-slate-50 p-4 rounded-2xl border border-gray-100 mb-6 gap-4">
                    <div>
                        <span class="block text-[10px] uppercase font-black text-slate-400 mb-1"><i class="fa-regular fa-calendar ml-1"></i> تاريخ الانضمام للمنصة</span>
                        <p id="v-created-date" class="font-bold text-slate-700 text-sm font-mono"></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 text-right">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-gray-100">
                        <span class="block text-[10px] uppercase font-black text-slate-400 mb-1">رقم الهاتف</span>
                        <p id="v-phone" class="font-bold text-slate-700 text-sm font-mono" dir="ltr"></p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-gray-100">
                        <span class="block text-[10px] uppercase font-black text-slate-400 mb-1">البريد الإلكتروني</span>
                        <p id="v-email" class="font-bold text-slate-700 text-sm font-mono" dir="ltr"></p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-gray-100">
                        <span class="block text-[10px] uppercase font-black text-slate-400 mb-1">المحافظة / النطاق</span>
                        <p id="v-city" class="font-bold text-slate-700 text-sm"></p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-gray-100">
                        <span class="block text-[10px] uppercase font-black text-slate-400 mb-1">ساعات العمل</span>
                        <p id="v-hours" class="font-bold text-slate-700 text-sm font-mono" dir="ltr"></p>
                    </div>
                    <div class="md:col-span-2 bg-slate-50 p-4 rounded-2xl border border-gray-100">
                        <span class="block text-[10px] uppercase font-black text-slate-400 mb-1">العنوان التفصيلي</span>
                        <p id="v-address" class="font-bold text-slate-700 text-sm"></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-black text-slate-800 text-sm mb-3 border-b border-gray-100 pb-2">الخدمات والمميزات</h4>
                        <div id="v-services" class="flex flex-wrap gap-2 mb-4"></div>
                        <div class="flex items-center gap-2 p-3 bg-blue-50 text-blue-700 rounded-xl text-xs font-bold border border-blue-100">
                            <i class="fa-solid fa-handshake"></i>
                            <span id="v-collab">يتعاون مع أطباء</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="editPharmacyModal" class="fixed inset-0 z-[120] hidden flex-col items-center justify-center p-4 sm:p-6">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('editPharmacyModal')"></div>
        <div class="relative bg-white rounded-[2.5rem] w-full max-w-4xl shadow-2xl overflow-hidden animate-scale-up flex flex-col max-h-full">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-blue-50/50 shrink-0">
                <h3 class="text-xl font-black text-slate-800 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    تعديل بيانات الصيدلية
                </h3>
                <button type="button" onclick="toggleModal('editPharmacyModal')" class="w-8 h-8 rounded-full bg-white text-slate-400 hover:text-rose-500 transition-all flex items-center justify-center shadow-sm border border-gray-200">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-6 md:p-8 overflow-y-auto scrollbar-thin">
                <form id="editPharmacyForm" method="POST" enctype="multipart/form-data" action="">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6 text-right">

                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-5 bg-slate-50 p-5 rounded-[1.5rem] border border-gray-100 mb-2">
                            <div>
                                <label class="block text-xs font-black text-slate-700 mb-3">شعار الصيدلية (Logo)</label>
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-full border border-gray-200 bg-white overflow-hidden shrink-0">
                                        <img id="edit_image_preview" src="" class="w-full h-full object-cover">
                                    </div>
                                    <input type="file" name="image" id="edit_image" accept="image/*" onchange="previewEditImage(this, 'edit_image_preview')" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-700 mb-3">صورة الغلاف (Cover)</label>
                                <div class="flex items-center gap-4">
                                    <div class="w-24 h-16 rounded-xl border border-gray-200 bg-white overflow-hidden shrink-0 flex items-center justify-center">
                                        <img id="edit_cover_preview" src="" class="w-full h-full object-cover hidden">
                                        <i id="edit_cover_icon" class="fa-solid fa-image text-gray-300 text-xl"></i>
                                    </div>
                                    <input type="file" name="cover" id="edit_cover" accept="image/*" onchange="previewEditImage(this, 'edit_cover_preview')" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-bold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 cursor-pointer">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-700 mb-2">اسم الصيدلية</label>
                            <input type="text" name="pharmacy_name" id="edit_pharmacy_name" required class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-700 mb-2">اسم المالك / المدير</label>
                            <input type="text" name="owner_name" id="edit_owner_name" required class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-700 mb-2">رقم الهاتف</label>
                            <input type="text" name="phone" id="edit_phone" dir="ltr" required class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none font-mono text-left">
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-700 mb-2">البريد الإلكتروني</label>
                            <input type="email" name="email" id="edit_email" dir="ltr" required class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none font-mono text-left">
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-700 mb-2">المحافظة / المدينة</label>
                            <input type="text" name="city" id="edit_city" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-700 mb-2">رقم الترخيص</label>
                            <input type="text" name="license_number" id="edit_license_number" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none font-mono">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-black text-slate-700 mb-2">ساعات العمل</label>
                            <input type="text" name="working_hours" id="edit_working_hours" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-black text-slate-700 mb-2">العنوان التفصيلي</label>
                            <input type="text" name="address" id="edit_address" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3 pt-5 border-t border-gray-100">
                        <button type="button" onclick="toggleModal('editPharmacyModal')" class="px-6 py-3 rounded-xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200 transition-colors text-sm">إلغاء</button>
                        <button type="submit" class="px-8 py-3 rounded-xl bg-blue-600 text-white font-black hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all text-sm flex items-center gap-2">
                            حفظ التعديلات
                            <i class="fa-solid fa-check"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentToggleId = null;
        let currentToggleState = null;
        let currentToggleBigId = null;
        const csrfToken = '{{ csrf_token() }}';

        // ==================== SERVER-SIDE FILTERING ====================
        function setServerFilter(param, value) {
            const url = new URL(window.location.href);

            if (value === 'all' || value === '') {
                url.searchParams.delete(param);
            } else {
                url.searchParams.set(param, value);
            }

            url.searchParams.delete('page');
            window.location.href = url.href;
        }

        document.getElementById('serverSearchInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                setServerFilter('search', this.value);
            }
        });

        // ==================== MAP INITIALIZATION ====================
        let map;
        let markersLayer;
        let mapMarkers = {};

        // حقن جميع بيانات الخريطة من السيرفر مباشرة
        const allPharmaciesForMap = @json($allMapPharmacies ?? []);

        const egyptBounds = L.latLngBounds([21.5, 24.5], [31.8, 37.0]);

        function initEgyptMap() {
            if (document.getElementById('egyptMap')) {
                map = L.map('egyptMap', {
                    center: [26.8206, 30.8025],
                    zoom: 6,
                    minZoom: 5,
                    maxBounds: egyptBounds,
                    maxBoundsViscosity: 1.0
                });

                L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; OSM'
                }).addTo(map);

                markersLayer = L.layerGroup().addTo(map);
                updateMapMarkers();
            }
        }

        function focusOnMap(lat, lng, id) {
            document.getElementById('egyptMap').scrollIntoView({ behavior: 'smooth', block: 'center' });
            map.flyTo([lat, lng], 15, { duration: 1.5 });
            setTimeout(() => {
                if(mapMarkers[id]) {
                    mapMarkers[id].openPopup();
                }
            }, 1500);
        }

        function updateMapMarkers() {
            if (!map || !markersLayer) return;
            markersLayer.clearLayers();
            mapMarkers = {};

            const activeIcon = L.divIcon({
                className: 'custom-div-icon',
                html: "<div style='background-color:#10b981;' class='marker-pin'></div><i class='fa-solid fa-pills text-white relative z-10 text-[10px] mt-[6px]'></i>",
                iconSize: [30, 42],
                iconAnchor: [15, 42],
                popupAnchor: [0, -35]
            });

            const bigActiveIcon = L.divIcon({
                className: 'custom-div-icon',
                html: "<div style='background-color:#f59e0b;' class='marker-pin'></div><i class='fa-solid fa-star text-white relative z-10 text-[10px] mt-[6px]'></i>",
                iconSize: [30, 42],
                iconAnchor: [15, 42],
                popupAnchor: [0, -35]
            });

            // الاعتماد على قائمة السيرفر الكاملة وليس صفوف الـ HTML
            allPharmaciesForMap.forEach(pharmacy => {
                // عرض الصيدليات النشطة فقط على الخريطة
                if (pharmacy.is_active && pharmacy.lat && pharmacy.lng) {
                    const lat = parseFloat(pharmacy.lat);
                    const lng = parseFloat(pharmacy.lng);
                    const isBig = pharmacy.is_big_pharmacy;
                    const pName = pharmacy.pharmacy_name;
                    // معالجة مسار الصورة إذا كانت موجودة
                    const pImg = pharmacy.image ? `/storage/${pharmacy.image}` : `https://ui-avatars.com/api/?name=${encodeURI(pName)}&background=0d9488&color=fff`;
                    const pPhone = pharmacy.phone || 'غير متوفر';
                    const pAddress = pharmacy.address || 'العنوان غير محدد';
                    const pId = pharmacy.id;

                    const popupHtml = `
                        <div class="flex flex-col pb-1">
                            <div class="h-16 ${isBig ? 'bg-gradient-to-r from-amber-400 to-orange-400' : 'bg-gradient-to-r from-emerald-500 to-teal-400'} relative">
                                <img src="${pImg}" class="w-12 h-12 rounded-full border-2 border-white object-cover absolute -bottom-4 right-4 shadow-sm bg-white">
                            </div>
                            <div class="pt-5 pb-2 px-4 text-right">
                                <h4 class="font-black text-slate-800 text-sm mb-1">${pName}</h4>
                                <p class="text-[10px] text-gray-500 mb-1.5 whitespace-nowrap overflow-hidden text-ellipsis"><i class="fa-solid fa-location-dot text-gray-400 ml-1"></i> ${pAddress}</p>
                                <p class="text-[10px] text-gray-500 mb-3 font-mono" dir="ltr"><i class="fa-solid fa-phone text-gray-400 mr-1"></i> ${pPhone}</p>
                            </div>
                        </div>
                    `;

                    if (!isNaN(lat) && !isNaN(lng)) {
                        const selectedIcon = isBig ? bigActiveIcon : activeIcon;
                        const marker = L.marker([lat, lng], {icon: selectedIcon})
                                        .bindPopup(popupHtml, { className: 'custom-popup' })
                                        .addTo(markersLayer);
                        mapMarkers[pId] = marker;
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            initEgyptMap();
        });

        // ==================== MODALS & ACTIONS ====================
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

        function showToast(message, isSuccess) {
            const container = document.getElementById('ajax-toast-container');
            const colorClass = isSuccess ? 'emerald' : 'rose';
            const icon = isSuccess ? 'fa-circle-check' : 'fa-triangle-exclamation';
            const title = isSuccess ? 'تمت العملية' : 'خطأ';

            const toast = document.createElement('div');
            toast.className = `animate-toast pointer-events-auto bg-white border-r-4 border-${colorClass}-500 shadow-xl rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm ml-auto text-right mb-3`;
            toast.innerHTML = `
                <div class="bg-${colorClass}-100 p-2 rounded-xl text-${colorClass}-600 shrink-0"><i class="fa-solid ${icon} text-xl"></i></div>
                <div class="flex-1">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">${title}</p>
                    <p class="text-sm font-black text-slate-800 leading-tight">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-slate-300 hover:text-slate-500 transition-colors shrink-0"><i class="fa-solid fa-xmark"></i></button>
            `;
            container.appendChild(toast);
            setTimeout(() => toast.remove(), 5000);
        }

        function openOwnerModal(userAccount) {
            document.getElementById('o-name').innerText = userAccount.name;
            document.getElementById('o-phone').innerText = userAccount.phone || 'غير محدد';
            document.getElementById('o-email').innerText = userAccount.email || 'غير محدد';
            document.getElementById('o-avatar').src = userAccount.avatar ? (userAccount.avatar.startsWith('http') ? userAccount.avatar : `/storage/${userAccount.avatar}`) : `https://ui-avatars.com/api/?name=${encodeURI(userAccount.name)}&background=e2e8f0&color=475569`;
            toggleModal('ownerModal');
        }

        function formatArabicDate(dateString) {
            if (!dateString) return 'غير متوفر';
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('ar-EG', options);
        }

        function openViewModal(pharmacy) {
            document.getElementById('v-name-text').innerText = pharmacy.pharmacy_name;
            document.getElementById('v-owner').innerHTML = `<i class="fa-solid fa-user-tie ml-1"></i> ${pharmacy.owner_name}`;
            document.getElementById('v-phone').innerText = pharmacy.phone;
            document.getElementById('v-email').innerText = pharmacy.email;
            document.getElementById('v-address').innerText = pharmacy.address || 'غير محدد';
            document.getElementById('v-hours').innerText = pharmacy.working_hours || 'غير محدد';
            document.getElementById('v-city').innerText = pharmacy.city || 'غير محدد';

            let imgUrl = pharmacy.image ? `/storage/${pharmacy.image}` : `https://ui-avatars.com/api/?name=${encodeURI(pharmacy.pharmacy_name)}&background=0d9488&color=fff`;
            document.getElementById('v-image').src = imgUrl;

            document.getElementById('v-created-date').innerText = formatArabicDate(pharmacy.created_at);

            const bigBadge = document.getElementById('v-big-badge');
            if (pharmacy.is_big_pharmacy_flag || pharmacy.is_big_pharmacy) {
                bigBadge.classList.remove('hidden'); bigBadge.classList.add('inline-flex');
            } else {
                bigBadge.classList.add('hidden'); bigBadge.classList.remove('inline-flex');
            }

            const servicesContainer = document.getElementById('v-services');
            servicesContainer.innerHTML = '';
            const services = typeof pharmacy.services === 'string' ? JSON.parse(pharmacy.services) : pharmacy.services;
            if(services && services.length > 0) {
                const serviceNames = { '24_hours': '24 ساعة', 'delivery': 'توصيل منزلي', 'consultation': 'استشارات طبية' };
                services.forEach(s => {
                    let text = serviceNames[s] || s;
                    servicesContainer.innerHTML += `<span class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-bold text-slate-600 shadow-sm">${text}</span>`;
                });
            } else {
                servicesContainer.innerHTML = `<span class="text-xs text-gray-400">لا توجد خدمات محددة</span>`;
            }

            let collabElem = document.getElementById('v-collab');
            if(pharmacy.has_collaboration == 1 || pharmacy.collab === 'yes') {
                collabElem.innerText = 'يتعاون مع أطباء أو عيادات';
                collabElem.parentElement.className = 'flex items-center gap-2 p-3 bg-blue-50 text-blue-700 rounded-xl text-xs font-bold border border-blue-100';
            } else {
                collabElem.innerText = 'لا يوجد تعاون طبي مباشر';
                collabElem.parentElement.className = 'flex items-center gap-2 p-3 bg-slate-50 text-slate-500 rounded-xl text-xs font-bold border border-gray-200';
            }

            toggleModal('viewProfileModal');
        }

        // ✨ الدوال الخاصة بتعديل بيانات الصيدلية (Edit Modal) ✨
        function openEditModal(pharmacy) {
            const form = document.getElementById('editPharmacyForm');
            // تأكد من صحة مسار التعديل ليتناسب مع الـ Routes الخاصة بك
            form.action = `/admin/pharmacies/${pharmacy.id}/update`;

            // تعبئة الحقول النصية
            document.getElementById('edit_pharmacy_name').value = pharmacy.pharmacy_name || '';
            document.getElementById('edit_owner_name').value = pharmacy.owner_name || '';
            document.getElementById('edit_phone').value = pharmacy.phone || '';
            document.getElementById('edit_email').value = pharmacy.email || '';
            document.getElementById('edit_city').value = pharmacy.city || '';
            document.getElementById('edit_address').value = pharmacy.address || '';
            document.getElementById('edit_working_hours').value = pharmacy.working_hours || '';
            document.getElementById('edit_license_number').value = pharmacy.license_number || '';

            // تصفير حقول رفع الملفات وإعداد معاينة الصور
            document.getElementById('edit_image').value = '';
            document.getElementById('edit_cover').value = '';

            const imgPreview = document.getElementById('edit_image_preview');
            imgPreview.src = pharmacy.image ? `/storage/${pharmacy.image}` : `https://ui-avatars.com/api/?name=${encodeURI(pharmacy.pharmacy_name)}&background=0d9488&color=fff`;

            const coverPreview = document.getElementById('edit_cover_preview');
            const coverIcon = document.getElementById('edit_cover_icon');
            if(pharmacy.cover) {
                coverPreview.src = `/storage/${pharmacy.cover}`;
                coverPreview.classList.remove('hidden');
                coverIcon.classList.add('hidden');
            } else {
                coverPreview.src = '';
                coverPreview.classList.add('hidden');
                coverIcon.classList.remove('hidden');
            }

            toggleModal('editPharmacyModal');
        }

        function previewEditImage(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    let previewImage = document.getElementById(previewId);
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');

                    if (previewId === 'edit_cover_preview') {
                        document.getElementById('edit_cover_icon').classList.add('hidden');
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // ==================== TOGGLE ACTIVE STATUS ====================
        function openToggleConfirmModal(id, name, isCurrentlyActive) {
            currentToggleId = id;
            currentToggleState = isCurrentlyActive;

            const iconBg = document.getElementById('tc-icon-bg');
            const icon = document.getElementById('tc-icon');
            const message = document.getElementById('tc-message');
            const confirmBtn = document.getElementById('tc-confirm-btn');

            if (isCurrentlyActive) {
                iconBg.className = 'w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner border-[6px] bg-rose-50 text-rose-500 border-rose-100';
                icon.className = 'fa-solid fa-power-off animate-bounce-short';
                message.innerHTML = `هل أنت متأكد أنك تريد إيقاف تفعيل صيدلية <strong>${name}</strong>؟<br>لن تتمكن من استقبال أي طلبات جديدة.`;
                confirmBtn.className = 'flex-1 px-6 py-4 rounded-2xl font-bold text-white shadow-lg transition-all text-sm bg-rose-500 hover:bg-rose-600 shadow-rose-500/30';
                confirmBtn.innerText = 'نعم، قم بالإيقاف';
            } else {
                iconBg.className = 'w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner border-[6px] bg-emerald-50 text-emerald-500 border-emerald-100';
                icon.className = 'fa-solid fa-check animate-bounce-short';
                message.innerHTML = `هل أنت متأكد أنك تريد تفعيل صيدلية <strong>${name}</strong>؟<br>ستتمكن من استقبال الطلبات فوراً.`;
                confirmBtn.className = 'flex-1 px-6 py-4 rounded-2xl font-bold text-white shadow-lg transition-all text-sm bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/30';
                confirmBtn.innerText = 'نعم، قم بالتفعيل';
            }

            toggleModal('toggleConfirmModal');
        }

        function executeToggle() {
            const btn = document.getElementById('tc-confirm-btn');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جاري المعالجة...';
            btn.disabled = true;

            fetch(`/admin/pharmacies/${currentToggleId}/toggle-status`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(res => res.json())
            .then(data => {
                toggleModal('toggleConfirmModal');
                if (data.success) {
                    showToast(data.message, true);
                    const rowBtn = document.getElementById(`toggle-btn-${currentToggleId}`);
                    const knob = document.getElementById(`toggle-knob-${currentToggleId}`);
                    const badge = document.getElementById(`badge-${currentToggleId}`);
                    const row = rowBtn.closest('tr');

                    row.setAttribute('data-active', data.is_active ? '1' : '0');
                    rowBtn.className = `relative inline-flex h-6 w-12 items-center rounded-full transition-colors duration-300 ease-in-out focus:outline-none ${data.is_active ? 'bg-emerald-500' : 'bg-gray-300'}`;
                    knob.className = `inline-block h-4 w-4 transform rounded-full bg-white transition duration-300 ease-in-out ${data.is_active ? '-translate-x-7' : '-translate-x-1'}`;
                    const pharmacyName = row.getAttribute('data-name').split(' ')[0];
                    rowBtn.setAttribute('onclick', `openToggleConfirmModal(${currentToggleId}, '${pharmacyName}', ${data.is_active ? 'true' : 'false'})`);

                    if (data.is_active) {
                        badge.className = 'text-[8px] px-1.5 py-0.5 rounded-md font-black uppercase tracking-wider border bg-emerald-50 text-emerald-600 border-emerald-200';
                        badge.innerText = 'نشط';
                    } else {
                        badge.className = 'text-[8px] px-1.5 py-0.5 rounded-md font-black uppercase tracking-wider border bg-rose-50 text-rose-600 border-rose-200';
                        badge.innerText = 'موقوف';
                    }

                    let activeEl = document.getElementById('stat-active');
                    let inactiveEl = document.getElementById('stat-inactive');
                    if (activeEl && inactiveEl) {
                        let cA = parseInt(activeEl.innerText); let cI = parseInt(inactiveEl.innerText);
                        if (data.is_active) { activeEl.innerText = cA + 1; inactiveEl.innerText = cI - 1; }
                        else { activeEl.innerText = cA - 1; inactiveEl.innerText = cI + 1; }
                    }
                } else { showToast(data.message, false); }
            })
            .catch(error => { toggleModal('toggleConfirmModal'); showToast('حدث خطأ في الاتصال بالخادم.', false); })
            .finally(() => { btn.disabled = false; });
        }

        // ==================== BIG PHARMACY TOGGLE ====================
        function openToggleBigConfirmModal(id, name, isCurrentlyBig) {
            currentToggleBigId = id;
            const iconBg = document.getElementById('tb-icon-bg');
            const icon = document.getElementById('tb-icon');
            const message = document.getElementById('tb-message');
            const confirmBtn = document.getElementById('tb-confirm-btn');

            if (isCurrentlyBig) {
                iconBg.className = 'w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner border-[6px] bg-slate-50 text-slate-500 border-slate-200';
                icon.className = 'fa-solid fa-star-half-stroke animate-bounce-short';
                message.innerHTML = `هل أنت متأكد أنك تريد إزالة تصنيف <strong>${name}</strong> كصيدلية كبرى؟`;
                confirmBtn.className = 'flex-1 px-6 py-4 rounded-2xl font-bold text-white shadow-lg transition-all text-sm bg-slate-500 hover:bg-slate-600 shadow-slate-500/30';
                confirmBtn.innerText = 'نعم، إزالة التصنيف';
            } else {
                iconBg.className = 'w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl shadow-inner border-[6px] bg-amber-50 text-amber-500 border-amber-100';
                icon.className = 'fa-solid fa-star animate-bounce-short';
                message.innerHTML = `هل أنت متأكد أنك تريد تعيين <strong>${name}</strong> كصيدلية كبرى؟`;
                confirmBtn.className = 'flex-1 px-6 py-4 rounded-2xl font-bold text-white shadow-lg transition-all text-sm bg-amber-500 hover:bg-amber-600 shadow-amber-500/30';
                confirmBtn.innerText = 'نعم، تعيين كبرى';
            }
            toggleModal('toggleBigConfirmModal');
        }

        function executeBigToggle() {
            const btn = document.getElementById('tb-confirm-btn');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جاري المعالجة...';
            btn.disabled = true;

            fetch(`/admin/pharmacies/${currentToggleBigId}/toggle-big`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(res => res.json())
            .then(data => {
                toggleModal('toggleBigConfirmModal');
                if (data.success) {
                    showToast(data.message, true);
                    const rowBtn = document.getElementById(`toggle-big-btn-${currentToggleBigId}`);
                    const knob = document.getElementById(`toggle-big-knob-${currentToggleBigId}`);
                    const icon = document.getElementById(`toggle-big-icon-${currentToggleBigId}`);
                    const badge = document.getElementById(`big-badge-${currentToggleBigId}`);
                    const row = rowBtn.closest('tr');

                    row.setAttribute('data-big', data.is_big_pharmacy ? '1' : '0');
                    if(data.is_big_pharmacy) {
                        rowBtn.className = "relative inline-flex h-6 w-12 items-center rounded-full transition-colors duration-300 ease-in-out focus:outline-none bg-amber-500";
                        knob.className = "inline-block h-4 w-4 transform rounded-full bg-white transition duration-300 ease-in-out -translate-x-7 flex items-center justify-center shadow-sm";
                        icon.className = "fa-solid fa-star text-amber-500 text-[8px]";
                        badge.classList.remove('hidden'); badge.classList.add('flex');
                    } else {
                        rowBtn.className = "relative inline-flex h-6 w-12 items-center rounded-full transition-colors duration-300 ease-in-out focus:outline-none bg-gray-300";
                        knob.className = "inline-block h-4 w-4 transform rounded-full bg-white transition duration-300 ease-in-out -translate-x-1 flex items-center justify-center shadow-sm";
                        icon.className = "fa-solid fa-star text-gray-300 text-[8px]";
                        badge.classList.add('hidden'); badge.classList.remove('flex');
                    }
                    const pharmacyName = row.getAttribute('data-name').split(' ')[0];
                    rowBtn.setAttribute('onclick', `openToggleBigConfirmModal(${currentToggleBigId}, '${pharmacyName}', ${data.is_big_pharmacy ? 'true' : 'false'})`);

                    let bigEl = document.getElementById('stat-big');
                    if (bigEl) {
                        let cB = parseInt(bigEl.innerText);
                        bigEl.innerText = data.is_big_pharmacy ? cB + 1 : (cB - 1 > 0 ? cB - 1 : 0);
                    }
                } else { showToast(data.message, false); }
            })
            .catch(error => { toggleModal('toggleBigConfirmModal'); showToast('حدث خطأ في الاتصال بالخادم.', false); })
            .finally(() => { btn.disabled = false; });
        }

        setTimeout(() => {
            document.querySelectorAll('.animate-toast').forEach(t => {
                t.style.opacity = '0';
                t.style.transform = 'translateY(-10px)';
                t.style.transition = 'all 0.3s ease';
                setTimeout(() => t.remove(), 300);
            });
        }, 5000);
    </script>

    <style>
        .animate-scale-up { animation: scaleUp 0.3s forwards; }
        @keyframes scaleUp { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
        .animate-toast { animation: toastSlideIn 0.4s forwards; }
        @keyframes toastSlideIn { from { opacity: 0; transform: translateX(50px); } to { opacity: 1; transform: translateX(0); } }
        .animate-bounce-short { animation: bounceShort 1s infinite; }
        @keyframes bounceShort { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10%); } }
        .custom-pagination nav svg { width: 1.25rem; height: 1.25rem; }
        .bg-gradient-custom { background: linear-gradient(135deg, #0d9488 0%, #84cc16 100%); }
        /* Custom map popup styles */
        .custom-popup .leaflet-popup-content-wrapper { border-radius: 1rem; padding: 0; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); }
        .custom-popup .leaflet-popup-content { margin: 0; width: 240px !important; }
        .custom-popup .leaflet-popup-tip-container { margin-top: -1px; }
        .marker-pin { width: 30px; height: 30px; border-radius: 50% 50% 50% 0; position: absolute; transform: rotate(-45deg); left: 50%; top: 50%; margin: -15px 0 0 -15px; box-shadow: 0 4px 6px rgba(0,0,0,0.3); }
        .custom-div-icon { background: none; border: none; }
        .custom-div-icon i { position: absolute; width: 22px; font-size: 14px; left: 0; right: 0; text-align: center; }
    </style>
@endsection
