@extends('dashboard.layout.master')
@section('content')
    <div class="p-4 md:p-6 lg:p-8 relative">

        <div id="ajax-toast-container" class="fixed top-4 left-4 right-4 md:left-auto md:right-6 md:top-6 z-[9999] flex flex-col gap-3 pointer-events-none"></div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">إدارة الصيدليات المعتمدة</h2>
                <p class="text-sm text-gray-500 font-medium mt-1">متابعة وإدارة الصيدليات النشطة على المنصة</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-8">
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

        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden transition-all">
            <div class="p-5 md:p-6 border-b border-gray-50 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5 bg-slate-50/30">
                <div class="flex items-center gap-3 shrink-0">
                    <h3 class="font-black text-slate-800 text-lg tracking-tight">سجل الصيدليات</h3>
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
                            <option value="1">نشط (مفعل)</option>
                            <option value="0">موقوف</option>
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
                            <th class="p-5 w-32 text-center">تاريخ الانضمام</th>
                            <th class="p-5 w-32 text-center">تفعيل / إيقاف</th>
                            <th class="p-5 w-32 text-center text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="tableBody">
                        @forelse ($pharmacies ?? [] as $index => $pharmacy)
                            <tr class="pharmacy-row hover:bg-slate-50 transition-all duration-300 group"
                                data-name="{{ mb_strtolower($pharmacy->pharmacy_name . ' ' . ($pharmacy->user->name ?? $pharmacy->owner_name)) }}"
                                data-active="{{ $pharmacy->is_active ? '1' : '0' }}">

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
                                            <span class="font-black text-slate-800 text-sm mb-1 flex items-center gap-2">
                                                {{ $pharmacy->pharmacy_name }}
                                                <span id="badge-{{ $pharmacy->id }}" class="text-[8px] px-1.5 py-0.5 rounded-md font-black uppercase tracking-wider border {{ $pharmacy->is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200' }}">
                                                    {{ $pharmacy->is_active ? 'نشط' : 'موقوف' }}
                                                </span>
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
                                            onclick="openToggleConfirmModal({{ $pharmacy->id }}, '{{ $pharmacy->pharmacy_name }}', {{ $pharmacy->is_active ? 'true' : 'false' }})"
                                            class="relative inline-flex h-6 w-12 items-center rounded-full transition-colors duration-300 ease-in-out focus:outline-none {{ $pharmacy->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}">
                                        <span id="toggle-knob-{{ $pharmacy->id }}"
                                              class="inline-block h-4 w-4 transform rounded-full bg-white transition duration-300 ease-in-out {{ $pharmacy->is_active ? '-translate-x-7' : '-translate-x-1' }}">
                                        </span>
                                    </button>
                                </td>

                                <td class="p-5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" onclick="openViewModal({{ json_encode($pharmacy) }})" class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 hover:text-primary hover:bg-teal-50 hover:border-teal-200 transition-all flex items-center justify-center shadow-sm tooltip" title="عرض الملف الكامل">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="defaultEmptyRow">
                                <td colspan="6" class="p-20 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fa-solid fa-store-slash text-4xl mb-4 text-gray-200"></i>
                                        <p class="font-bold text-lg text-slate-600">لا توجد صيدليات معتمدة بعد</p>
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
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-xs font-bold text-slate-500 text-center sm:text-right">
                        عرض <span class="text-slate-800">{{ $pharmacies->firstItem() ?? 0 }}</span> إلى <span class="text-slate-800">{{ $pharmacies->lastItem() ?? 0 }}</span> من إجمالي <span class="text-slate-800">{{ $pharmacies->total() }}</span> صيدلية
                    </p>
                    <div class="custom-pagination">
                        {{ $pharmacies->links() }}
                    </div>
                </div>
            </div>
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
                    <div class="flex-1 text-center md:text-right">
                        <h2 id="v-name" class="text-2xl font-black text-slate-800 mb-1">اسم الصيدلية</h2>
                        <p id="v-owner" class="text-sm font-bold text-gray-500"><i class="fa-solid fa-user-tie ml-1"></i> اسم المالك</p>
                    </div>
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
            </div>
        </div>
    </div>

    <script>
        let currentToggleId = null;
        let currentToggleState = null;
        const csrfToken = '{{ csrf_token() }}';

        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

        function formatArabicDate(dateString) {
            if (!dateString) return 'غير متوفر';
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('ar-EG', options);
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
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                toggleModal('toggleConfirmModal');
                if (data.success) {
                    showToast(data.message, true);
                    updateSwitchUI(currentToggleId, data.is_active);
                    updateStatsCounters(data.is_active);
                } else {
                    showToast(data.message, false);
                }
            })
            .catch(error => {
                toggleModal('toggleConfirmModal');
                showToast('حدث خطأ في الاتصال بالخادم.', false);
            })
            .finally(() => {
                btn.disabled = false;
            });
        }

        function updateSwitchUI(id, isNowActive) {
            const btn = document.getElementById(`toggle-btn-${id}`);
            const knob = document.getElementById(`toggle-knob-${id}`);
            const badge = document.getElementById(`badge-${id}`);
            const row = btn.closest('tr');
            row.setAttribute('data-active', isNowActive ? '1' : '0');
            btn.className = `relative inline-flex h-6 w-12 items-center rounded-full transition-colors duration-300 ease-in-out focus:outline-none ${isNowActive ? 'bg-emerald-500' : 'bg-gray-300'}`;
            knob.className = `inline-block h-4 w-4 transform rounded-full bg-white transition duration-300 ease-in-out ${isNowActive ? '-translate-x-7' : '-translate-x-1'}`;
            const pharmacyName = row.getAttribute('data-name');
            btn.setAttribute('onclick', `openToggleConfirmModal(${id}, '${pharmacyName}', ${isNowActive ? 'true' : 'false'})`);
            if (isNowActive) {
                badge.className = 'text-[8px] px-1.5 py-0.5 rounded-md font-black uppercase tracking-wider border bg-emerald-50 text-emerald-600 border-emerald-200';
                badge.innerText = 'نشط';
            } else {
                badge.className = 'text-[8px] px-1.5 py-0.5 rounded-md font-black uppercase tracking-wider border bg-rose-50 text-rose-600 border-rose-200';
                badge.innerText = 'موقوف';
            }
        }

        function updateStatsCounters(isNowActive) {
            let activeEl = document.getElementById('stat-active');
            let inactiveEl = document.getElementById('stat-inactive');
            let currentActive = parseInt(activeEl.innerText);
            let currentInactive = parseInt(inactiveEl.innerText);
            if (isNowActive) {
                activeEl.innerText = currentActive + 1;
                inactiveEl.innerText = currentInactive - 1;
            } else {
                activeEl.innerText = currentActive - 1;
                inactiveEl.innerText = currentInactive + 1;
            }
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

        function filterTable() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const status = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('.pharmacy-row');
            let count = 0;
            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                const rowActive = row.getAttribute('data-active');
                const matchesSearch = name.includes(search);
                const matchesStatus = status === 'all' || rowActive === status;
                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                    count++;
                } else {
                    row.style.display = 'none';
                }
            });
            document.getElementById('resultCounter').innerText = count + ' صيدلية';
            document.getElementById('noResultsRow').style.display = count === 0 ? '' : 'none';
        }

        document.getElementById('searchInput').addEventListener('input', filterTable);
        document.getElementById('statusFilter').addEventListener('change', filterTable);
    </script>

    <style>
        .animate-scale-up { animation: scaleUp 0.3s forwards; }
        @keyframes scaleUp { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
        .animate-toast { animation: toastSlideIn 0.4s forwards; }
        @keyframes toastSlideIn { from { opacity: 0; transform: translateX(50px); } to { opacity: 1; transform: translateX(0); } }
        .animate-bounce-short { animation: bounceShort 1s infinite; }
        @keyframes bounceShort { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10%); } }
        .custom-pagination nav svg { width: 1.25rem; height: 1.25rem; }
    </style>
@endsection
