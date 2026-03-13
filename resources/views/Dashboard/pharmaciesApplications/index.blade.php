@extends('dashboard.layout.master')
@section('content')
    <div class="p-4 md:p-6 lg:p-8 relative">

        <div id="toast-container" class="fixed top-4 left-4 right-4 md:left-auto md:right-6 md:top-6 z-[9999] flex flex-col gap-3 pointer-events-none">
            @if(session('success'))
                <div class="animate-toast pointer-events-auto bg-white border-r-4 border-emerald-500 shadow-xl rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm ml-auto text-right">
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
                <div class="animate-toast pointer-events-auto bg-white border-r-4 border-rose-500 shadow-xl rounded-2xl p-4 flex items-center gap-4 min-w-[280px] max-w-sm ml-auto text-right">
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
                <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">إدارة طلبات الصيدليات</h2>
                <p class="text-sm text-gray-500 font-medium mt-1">مراجعة وإدارة طلبات انضمام الصيدليات للمنصة</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-primary/20 group">
                <div class="w-14 h-14 md:w-16 md:h-16 bg-slate-50 text-slate-600 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-briefcase-medical"></i>
                </div>
                <div class="min-w-0 text-right">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate mb-1">إجمالي الطلبات</p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['total'] ?? 0 }}</h3>
                </div>
            </div>

            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-emerald-200 group">
                <div class="w-14 h-14 md:w-16 md:h-16 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-check-double"></i>
                </div>
                <div class="min-w-0 text-right">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate mb-1">صيدليات مقبولة</p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['approved'] ?? 0 }}</h3>
                </div>
            </div>

            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-amber-200 group">
                <div class="w-14 h-14 md:w-16 md:h-16 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-hourglass-half text-xl"></i>
                </div>
                <div class="min-w-0 text-right">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate mb-1">قيد المراجعة</p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['under_review'] ?? 0 }}</h3>
                </div>
            </div>

            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-rose-200 group">
                <div class="w-14 h-14 md:w-16 md:h-16 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-ban"></i>
                </div>
                <div class="min-w-0 text-right">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate mb-1">طلبات مرفوضة</p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['rejected'] ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden transition-all">
            <div class="p-5 md:p-6 border-b border-gray-50 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5 bg-slate-50/30">
                <div class="flex items-center gap-3 shrink-0">
                    <h3 class="font-black text-slate-800 text-lg tracking-tight">قائمة الصيدليات</h3>
                    <span id="resultCounter" class="bg-primary/10 text-primary text-[10px] font-black px-3 py-1.5 rounded-full transition-all uppercase tracking-wider">
                        {{ $pharmacies->total() }} صيدلية
                    </span>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto flex-wrap">
                    <div class="relative w-full sm:w-64">
                        <input type="text" id="searchInput" placeholder="ابحث باسم الصيدلية أو المالك..." class="w-full bg-white border border-gray-200 rounded-2xl px-5 py-3 pr-11 text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none text-slate-700 shadow-sm">
                        <i class="fa-solid fa-search absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <div class="relative w-full sm:w-auto">
                        <select id="statusFilter" class="w-full sm:w-auto bg-white border border-gray-200 rounded-2xl py-3 pr-10 pl-10 text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none appearance-none text-slate-600 shadow-sm font-medium cursor-pointer">
                            <option value="all">جميع الحالات</option>
                            <option value="approved">المقبولة</option>
                            <option value="under_review">قيد المراجعة</option>
                            <option value="rejected">المرفوضة</option>
                        </select>
                        <i class="fa-solid fa-filter absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        <i class="fa-solid fa-chevron-down absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
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
                            <th class="p-5 w-32 text-center">تاريخ الطلب</th>
                            <th class="p-5 w-32 text-center">الحالة</th>
                            <th class="p-5 w-48 text-center text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="tableBody">
                        @forelse ($pharmacies ?? [] as $index => $pharmacy)
                            <tr class="pharmacy-row hover:bg-slate-50 transition-all duration-300 group"
                                data-name="{{ mb_strtolower($pharmacy->pharmacy_name . ' ' . ($pharmacy->user->name ?? $pharmacy->owner_name)) }}"
                                data-status="{{ $pharmacy->status }}">

                                <td class="p-5 font-bold text-slate-400 text-xs text-center">{{ $pharmacies->firstItem() + $index }}</td>

                                <td class="p-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center overflow-hidden shadow-sm border border-gray-100 shrink-0">
                                            @if($pharmacy->image)
                                                <img src="{{ asset('storage/'.$pharmacy->image) }}" class="w-full h-full object-cover">
                                            @else
                                                <i class="fa-solid fa-briefcase-medical text-primary/50 text-xl"></i>
                                            @endif
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-black text-slate-800 text-sm mb-1 flex items-center gap-2">
                                                {{ $pharmacy->pharmacy_name }}
                                                @if(\Carbon\Carbon::parse($pharmacy->created_at)->diffInHours(now()) <= 24)
                                                    <span class="bg-blue-100 text-blue-600 text-[8px] px-1.5 py-0.5 rounded-md font-black uppercase tracking-wider animate-pulse border border-blue-200">جديد</span>
                                                @endif
                                            </span>
                                            <span class="text-[11px] text-gray-500 font-medium flex items-center gap-1">
                                                <i class="fa-solid fa-location-dot text-gray-400"></i> {{ $pharmacy->city }}
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
                                        <button type="button" onclick="openOwnerModal({{ json_encode($ownerData) }})" class="font-bold text-slate-700 text-sm mb-1 hover:text-blue-600 transition-colors flex items-center gap-1.5 tooltip text-right" title="عرض تفاصيل المالك">
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
                                    @if($pharmacy->status == 'approved')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100">
                                            <i class="fa-solid fa-check"></i> مقبولة
                                        </span>
                                    @elseif($pharmacy->status == 'under_review')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black bg-amber-50 text-amber-600 border border-amber-100">
                                            <i class="fa-solid fa-hourglass-half animate-pulse"></i> مراجعة
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black bg-rose-50 text-rose-600 border border-rose-100">
                                            <i class="fa-solid fa-xmark"></i> مرفوضة
                                        </span>
                                    @endif
                                </td>

                                <td class="p-5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" onclick="openViewModal({{ json_encode($pharmacy) }})" class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 hover:text-primary hover:bg-teal-50 hover:border-teal-200 transition-all flex items-center justify-center shadow-sm tooltip" title="عرض الملف">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </button>

                                        @if($pharmacy->status == 'under_review')
                                            <button type="button" onclick="openApproveModal({{ $pharmacy->id }})" class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 hover:border-emerald-200 transition-all flex items-center justify-center shadow-sm" title="قبول">
                                                <i class="fa-solid fa-check text-sm"></i>
                                            </button>

                                            <button type="button" onclick="openRejectModal({{ json_encode($pharmacy) }})" class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 hover:text-orange-600 hover:bg-orange-50 hover:border-orange-200 transition-all flex items-center justify-center shadow-sm" title="رفض">
                                                <i class="fa-solid fa-xmark text-sm"></i>
                                            </button>

                                            <button type="button" onclick="openEditModal({{ json_encode($pharmacy) }})" class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 transition-all flex items-center justify-center shadow-sm" title="تعديل">
                                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                                            </button>

                                            <button type="button" onclick="openDeleteModal({{ $pharmacy->id }})" class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition-all flex items-center justify-center shadow-sm" title="حذف">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="defaultEmptyRow">
                                <td colspan="6" class="p-20 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fa-solid fa-inbox text-4xl mb-4 text-gray-200"></i>
                                        <p class="font-bold text-lg text-slate-600">لا توجد طلبات صيدليات بعد</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                        <tr id="noResultsRow" style="display: none;">
                            <td colspan="6" class="p-20 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-magnifying-glass text-4xl mb-4 text-gray-200"></i>
                                    <p class="font-bold text-lg text-slate-600">لا توجد نتائج مطابقة لبحثك</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-slate-50/50 border-t border-gray-100">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-center sm:text-right">
                    <p class="text-xs font-bold text-slate-500">
                        عرض <span class="text-slate-800">{{ $pharmacies->firstItem() ?? 0 }}</span> إلى <span class="text-slate-800">{{ $pharmacies->lastItem() ?? 0 }}</span> من إجمالي <span class="text-slate-800">{{ $pharmacies->total() }}</span> طلب
                    </p>
                    <div class="custom-pagination">
                        {{ $pharmacies->links() }}
                    </div>
                </div>
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
                <img id="o-avatar" src="" alt="Owner" class="w-full h-full object-cover">
            </div>
            <h3 id="o-name" class="text-xl font-black text-slate-800 mb-1">اسم المالك</h3>
            <p class="text-[11px] font-black text-blue-500 mb-6 uppercase tracking-wider bg-blue-50 inline-block px-3 py-1 rounded-full border border-blue-100">بيانات الحساب</p>

            <div class="bg-slate-50 rounded-2xl p-4 text-right space-y-4 border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[10px] uppercase font-bold text-gray-400 mb-0.5">رقم الهاتف</span>
                        <p id="o-phone" class="text-sm font-bold text-slate-700 font-mono" dir="ltr"></p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[10px] uppercase font-bold text-gray-400 mb-0.5">البريد الإلكتروني</span>
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
                    <div class="flex-1 text-center md:text-right">
                        <h2 id="v-name" class="text-2xl font-black text-slate-800 mb-1">اسم الصيدلية</h2>
                        <p id="v-owner" class="text-sm font-bold text-gray-500"><i class="fa-solid fa-user-tie ml-1"></i> اسم المالك</p>
                    </div>
                    <div id="v-status-badge" class="shrink-0 mt-4 md:mt-0"></div>
                </div>

                <div class="flex flex-wrap items-center justify-between bg-slate-50 p-4 rounded-2xl border border-gray-100 mb-6 gap-4">
                    <div>
                        <span class="block text-[10px] uppercase font-black text-slate-400 mb-1"><i class="fa-regular fa-calendar ml-1"></i> تاريخ التقديم</span>
                        <p id="v-created-date" class="font-bold text-slate-700 text-sm font-mono"></p>
                    </div>
                    <div id="v-action-date-container" class="hidden">
                        <span id="v-action-label" class="block text-[10px] uppercase font-black text-slate-400 mb-1">تاريخ الإجراء</span>
                        <p id="v-action-date" class="font-bold text-slate-700 text-sm font-mono"></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-gray-100">
                        <span class="block text-[10px] uppercase font-black text-slate-400 mb-1">رقم الهاتف</span>
                        <p id="v-phone" class="font-bold text-slate-700 text-sm font-mono" dir="ltr"></p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-gray-100">
                        <span class="block text-[10px] uppercase font-black text-slate-400 mb-1">البريد الإلكتروني</span>
                        <p id="v-email" class="font-bold text-slate-700 text-sm font-mono" dir="ltr"></p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-gray-100">
                        <span class="block text-[10px] uppercase font-black text-slate-400 mb-1">المحافظة / المدينة</span>
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

                    <div>
                        <h4 class="font-black text-slate-800 text-sm mb-3 border-b border-gray-100 pb-2">المستندات والتراخيص</h4>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-gray-100 flex items-center justify-between gap-4 mb-3">
                            <div class="overflow-hidden">
                                <span class="block text-[10px] uppercase font-black text-slate-400 mb-1">رقم الترخيص</span>
                                <p id="v-license" class="font-bold text-slate-700 text-sm font-mono truncate"></p>
                            </div>
                            <a id="v-license-btn" href="#" target="_blank" class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-colors shadow-sm shrink-0 tooltip" title="عرض المستند">
                                <i class="fa-solid fa-file-pdf"></i>
                            </a>
                        </div>
                        <a id="v-map-btn" href="#" target="_blank" class="w-full py-3 bg-slate-800 hover:bg-black text-white rounded-2xl font-bold text-xs transition-colors flex items-center justify-center gap-2 shadow-lg">
                            <i class="fa-solid fa-map-location-dot"></i>
                            عرض الموقع على الخريطة
                        </a>
                    </div>
                </div>

                <div id="v-notes-container" class="mt-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl hidden">
                    <span class="block text-[10px] uppercase font-black text-rose-400 mb-1">سبب الرفض (ملاحظات الإدارة)</span>
                    <p id="v-notes" class="font-bold text-rose-700 text-sm"></p>
                </div>
            </div>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 z-[105] hidden flex-col items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('editModal')"></div>
        <div class="relative bg-white rounded-[2.5rem] w-full max-w-4xl shadow-2xl overflow-hidden animate-scale-up flex flex-col max-h-[90vh]">

            <div class="relative h-28 bg-gradient-to-r from-blue-600 to-indigo-600 shrink-0 flex items-center px-8 text-right">
                <button type="button" onclick="toggleModal('editModal')" class="absolute top-4 left-4 w-10 h-10 rounded-2xl bg-white/20 hover:bg-white text-white hover:text-slate-800 transition-all flex items-center justify-center backdrop-blur-md">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
                <h3 class="text-2xl font-black text-white flex items-center gap-3">
                    <i class="fa-solid fa-pen-to-square ml-2"></i> التعديل الشامل لبيانات الطلب
                </h3>
            </div>

            <form id="editForm" action="" method="POST" class="flex flex-col flex-1 overflow-hidden" onsubmit="disableSubmitButton(this)">
                @csrf @method('PUT')
                <div class="p-6 md:p-8 space-y-6 overflow-y-auto scrollbar-thin text-right bg-slate-50/30">

                    <div class="flex items-center justify-between bg-blue-50 p-4 rounded-2xl border border-blue-100">
                        <div class="flex items-center gap-3 text-blue-700">
                            <i class="fa-solid fa-circle-info text-xl"></i>
                            <div class="text-sm font-bold">يمكنك التعديل فقط لأن الطلب لا يزال <span class="font-black">قيد المراجعة</span>.</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">اسم الصيدلية</label>
                            <input type="text" id="e-name" name="pharmacy_name" required class="w-full bg-white border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">اسم المالك</label>
                            <input type="text" id="e-owner" name="owner_name" required class="w-full bg-white border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">رقم الهاتف</label>
                            <input type="text" id="e-phone" name="phone" required class="w-full bg-white border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none shadow-sm" dir="ltr">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">البريد الإلكتروني</label>
                            <input type="email" id="e-email" name="email" required class="w-full bg-white border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none shadow-sm" dir="ltr">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">المحافظة / المدينة</label>
                            <input type="text" id="e-city" name="city" class="w-full bg-white border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">ساعات العمل</label>
                            <input type="text" id="e-working-hours" name="working_hours" class="w-full bg-white border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none shadow-sm" dir="ltr">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">العنوان التفصيلي</label>
                            <input type="text" id="e-address" name="address" class="w-full bg-white border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">خط العرض (Latitude)</label>
                            <input type="text" id="e-lat" name="lat" class="w-full bg-white border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none shadow-sm" dir="ltr">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">خط الطول (Longitude)</label>
                            <input type="text" id="e-lng" name="lng" class="w-full bg-white border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none shadow-sm" dir="ltr">
                        </div>
                        <div class="bg-white p-5 rounded-2xl border border-gray-200">
                            <label class="block text-sm font-bold text-slate-700 mb-3 border-b border-gray-100 pb-2">الخدمات المتوفرة</label>
                            <div class="flex flex-wrap gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="services[]" value="24_hours" id="srv_24_hours" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                                    <span class="text-sm font-bold text-slate-600">24 ساعة</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="services[]" value="delivery" id="srv_delivery" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                                    <span class="text-sm font-bold text-slate-600">توصيل منزلي</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="services[]" value="consultation" id="srv_consultation" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                                    <span class="text-sm font-bold text-slate-600">استشارات طبية</span>
                                </label>
                            </div>
                        </div>
                        <div class="bg-white p-5 rounded-2xl border border-gray-200">
                            <label class="block text-sm font-bold text-slate-700 mb-3 border-b border-gray-100 pb-2">التعاون الطبي</label>
                            <select id="e-collab" name="has_collaboration" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none shadow-sm font-bold">
                                <option value="1">نعم، يوجد تعاون</option>
                                <option value="0">لا يوجد تعاون</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="p-6 bg-white border-t border-gray-100 flex items-center justify-end gap-3 shrink-0">
                    <button type="button" onclick="toggleModal('editModal')" class="px-6 py-3.5 rounded-2xl font-bold text-slate-500 bg-slate-50 border border-gray-200 hover:bg-slate-100 transition-colors text-sm text-right">إلغاء</button>
                    <button type="submit" class="px-8 py-3.5 rounded-2xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-600/30 transition-all text-sm flex items-center gap-2">
                        <i class="fa-solid fa-save ml-1"></i> حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="approveModal" class="fixed inset-0 z-[105] hidden flex-col items-center justify-center p-4 sm:p-0 text-center">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('approveModal')"></div>
        <div class="relative bg-white rounded-[2.5rem] w-full max-w-sm shadow-2xl overflow-hidden animate-scale-up p-8 md:p-10">
            <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-6 text-emerald-500 text-4xl shadow-inner border-[6px] border-emerald-100">
                <i class="fa-solid fa-check animate-bounce-short"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800 mb-2">تفعيل الصيدلية؟</h3>
            <p class="text-sm text-gray-500 font-medium mb-8 leading-relaxed">بالموافقة، ستتمكن الصيدلية من تسجيل الدخول واستقبال الطلبات.</p>
            <form id="approveForm" action="" method="POST" onsubmit="disableSubmitButton(this)">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="approved">
                <div class="flex items-center justify-center gap-3">
                    <button type="button" onclick="toggleModal('approveModal')" class="flex-1 px-6 py-4 rounded-2xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors text-sm">إلغاء</button>
                    <button type="submit" class="flex-1 px-6 py-4 rounded-2xl font-bold text-white bg-emerald-500 hover:bg-emerald-600 shadow-lg shadow-emerald-500/30 transition-all text-sm">تأكيد القبول</button>
                </div>
            </form>
        </div>
    </div>

    <div id="rejectModal" class="fixed inset-0 z-[105] hidden flex-col items-center justify-center p-4 sm:p-0 text-right">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('rejectModal')"></div>
        <div class="relative bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden animate-scale-up">
            <div class="p-6 md:p-8 border-b border-gray-100 flex justify-between items-center bg-orange-50/50">
                <h3 class="text-xl font-black text-orange-600 flex items-center gap-2"><i class="fa-solid fa-ban"></i> رفض الطلب</h3>
                <button type="button" onclick="toggleModal('rejectModal')" class="w-10 h-10 rounded-2xl bg-white border border-gray-200 text-slate-400 hover:text-orange-500 transition-all flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <form id="rejectForm" action="" method="POST" class="p-6 md:p-8" onsubmit="disableSubmitButton(this)">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="rejected">
                <p class="text-sm text-gray-500 font-medium mb-4">يرجى كتابة سبب الرفض ليظهر للمستخدم.</p>
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">سبب الرفض</label>
                    <textarea id="rejectNotes" name="admin_notes" rows="4" required class="w-full bg-slate-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:bg-white focus:ring-4 focus:ring-orange-500/20 focus:border-orange-400 transition-all outline-none resize-none shadow-sm" placeholder="مثال: صورة الترخيص غير واضحة..."></textarea>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <button type="button" onclick="toggleModal('rejectModal')" class="px-6 py-3.5 rounded-2xl font-bold text-slate-500 bg-white border border-gray-200 hover:bg-slate-50 transition-colors text-sm">إلغاء</button>
                    <button type="submit" class="px-8 py-3.5 rounded-2xl font-bold text-white bg-orange-500 hover:bg-orange-600 shadow-lg shadow-orange-500/30 transition-all text-sm">تأكيد الرفض</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-[105] hidden flex-col items-center justify-center p-4 sm:p-0 text-center">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('deleteModal')"></div>
        <div class="relative bg-white rounded-[2.5rem] w-full max-w-sm shadow-2xl overflow-hidden animate-scale-up p-8 md:p-10">
            <div class="w-24 h-24 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-6 text-rose-500 text-4xl shadow-inner border-[6px] border-rose-100">
                <i class="fa-solid fa-trash-can animate-bounce-short"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800 mb-2">حذف الطلب؟</h3>
            <p class="text-sm text-gray-500 font-medium mb-8 leading-relaxed">سيتم حذف هذا الطلب نهائياً، لا يمكن التراجع.</p>
            <form id="deleteForm" action="" method="POST" onsubmit="disableSubmitButton(this)">
                @csrf @method('DELETE')
                <div class="flex items-center justify-center gap-3">
                    <button type="button" onclick="toggleModal('deleteModal')" class="flex-1 px-6 py-4 rounded-2xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors text-sm">إلغاء</button>
                    <button type="submit" class="flex-1 px-6 py-4 rounded-2xl font-bold text-white bg-rose-500 hover:bg-rose-600 shadow-lg shadow-rose-500/30 transition-all text-sm">تأكيد الحذف</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

        function disableSubmitButton(form) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if(submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin ml-2"></i> جاري المعالجة...';
            }
            return true;
        }

        function formatArabicDate(dateString) {
            if (!dateString) return 'غير متوفر';
            const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(dateString).toLocaleDateString('ar-EG', options);
        }

        function openOwnerModal(userAccount) {
            document.getElementById('o-name').innerText = userAccount.name;
            document.getElementById('o-phone').innerText = userAccount.phone || 'غير محدد';
            document.getElementById('o-email').innerText = userAccount.email || 'غير محدد';

            if (userAccount.avatar) {
                let avatarSrc = userAccount.avatar.startsWith('http') ? userAccount.avatar : `/storage/${userAccount.avatar}`;
                document.getElementById('o-avatar').src = avatarSrc;
            } else {
                document.getElementById('o-avatar').src = 'https://ui-avatars.com/api/?name=' + encodeURI(userAccount.name) + '&background=e2e8f0&color=475569';
            }
            toggleModal('ownerModal');
        }

        function openViewModal(pharmacy) {
            document.getElementById('v-name').innerText = pharmacy.pharmacy_name;
            document.getElementById('v-owner').innerHTML = `<i class="fa-solid fa-user-tie ml-1"></i> ${pharmacy.owner_name}`;
            document.getElementById('v-phone').innerText = pharmacy.phone;
            document.getElementById('v-email').innerText = pharmacy.email;
            document.getElementById('v-city').innerText = pharmacy.city || 'غير محدد';
            document.getElementById('v-address').innerText = pharmacy.address || 'غير محدد';
            document.getElementById('v-hours').innerText = pharmacy.working_hours || 'غير محدد';
            document.getElementById('v-license').innerText = pharmacy.license_number || 'غير محدد';

            let imgUrl = pharmacy.image ? `/storage/${pharmacy.image}` : 'https://ui-avatars.com/api/?name=' + encodeURI(pharmacy.pharmacy_name) + '&background=0d9488&color=fff';
            document.getElementById('v-image').src = imgUrl;

            document.getElementById('v-license-btn').href = `/storage/${pharmacy.license_document}`;
            document.getElementById('v-map-btn').href = `https://www.google.com/maps/search/?api=1&query=$${pharmacy.lat},${pharmacy.lng}`;

            document.getElementById('v-created-date').innerText = formatArabicDate(pharmacy.created_at);

            const actionDateContainer = document.getElementById('v-action-date-container');
            const actionLabel = document.getElementById('v-action-label');
            const actionDate = document.getElementById('v-action-date');

            if (pharmacy.status === 'approved') {
                actionLabel.innerHTML = '<i class="fa-regular fa-clock ml-1"></i> تاريخ الموافقة';
                actionDate.innerText = formatArabicDate(pharmacy.updated_at);
                actionDateContainer.classList.remove('hidden');
            } else if (pharmacy.status === 'rejected') {
                actionLabel.innerHTML = '<i class="fa-regular fa-clock ml-1"></i> تاريخ الرفض';
                actionDate.innerText = formatArabicDate(pharmacy.updated_at);
                actionDateContainer.classList.remove('hidden');
            } else {
                actionDateContainer.classList.add('hidden');
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

            let badgeHtml = '';
            if(pharmacy.status === 'approved') badgeHtml = `<span class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-2xl text-xs font-black"><i class="fa-solid fa-check ml-1"></i> مقبولة</span>`;
            else if(pharmacy.status === 'under_review') badgeHtml = `<span class="px-4 py-2 bg-amber-100 text-amber-700 rounded-2xl text-xs font-black"><i class="fa-solid fa-hourglass-half ml-1"></i> قيد المراجعة</span>`;
            else badgeHtml = `<span class="px-4 py-2 bg-rose-100 text-rose-700 rounded-2xl text-xs font-black"><i class="fa-solid fa-xmark ml-1"></i> مرفوضة</span>`;
            document.getElementById('v-status-badge').innerHTML = badgeHtml;

            let notesContainer = document.getElementById('v-notes-container');
            if(pharmacy.status === 'rejected' && pharmacy.admin_notes) {
                document.getElementById('v-notes').innerText = pharmacy.admin_notes;
                notesContainer.classList.remove('hidden');
            } else {
                notesContainer.classList.add('hidden');
            }

            toggleModal('viewProfileModal');
        }

        function openApproveModal(id) {
            document.getElementById('approveForm').action = `/admin/pharmaciesApplications/${id}/status`;
            toggleModal('approveModal');
        }

        function openRejectModal(pharmacy) {
            document.getElementById('rejectForm').action = `/admin/pharmaciesApplications/${pharmacy.id}/status`;
            document.getElementById('rejectNotes').value = pharmacy.admin_notes ? pharmacy.admin_notes : '';
            toggleModal('rejectModal');
        }

        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = `/admin/pharmaciesApplications/${id}`;
            toggleModal('deleteModal');
        }

        function openEditModal(pharmacy) {
            document.getElementById('editForm').action = `/admin/pharmaciesApplications/${pharmacy.id}`;
            document.getElementById('e-name').value = pharmacy.pharmacy_name;
            document.getElementById('e-owner').value = pharmacy.owner_name;
            document.getElementById('e-phone').value = pharmacy.phone;
            document.getElementById('e-email').value = pharmacy.email;
            document.getElementById('e-city').value = pharmacy.city || '';
            document.getElementById('e-address').value = pharmacy.address || '';
            document.getElementById('e-working-hours').value = pharmacy.working_hours || '';
            document.getElementById('e-lat').value = pharmacy.lat || '';
            document.getElementById('e-lng').value = pharmacy.lng || '';
            document.getElementById('e-collab').value = (pharmacy.has_collaboration == 1 || pharmacy.collab === 'yes') ? '1' : '0';

            document.getElementById('srv_24_hours').checked = false;
            document.getElementById('srv_delivery').checked = false;
            document.getElementById('srv_consultation').checked = false;

            const services = typeof pharmacy.services === 'string' ? JSON.parse(pharmacy.services) : pharmacy.services;
            if(services && services.length > 0) {
                if (services.includes('24_hours')) document.getElementById('srv_24_hours').checked = true;
                if (services.includes('delivery')) document.getElementById('srv_delivery').checked = true;
                if (services.includes('consultation')) document.getElementById('srv_consultation').checked = true;
            }
            toggleModal('editModal');
        }

        // Auto hide toasts
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
        .animate-scale-up { animation: scaleUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes scaleUp { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        .animate-toast { animation: toastSlideIn 0.4s forwards; }
        @keyframes toastSlideIn { from { opacity: 0; transform: translateX(100%); } to { opacity: 1; transform: translateX(0); } }
        .animate-bounce-short { animation: bounceShort 1s infinite; }
        @keyframes bounceShort { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10%); } }

        .custom-pagination nav svg { width: 1.25rem; height: 1.25rem; }
    </style>
@endsection
