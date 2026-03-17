@extends('dashboard.layout.master')
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div class="p-4 md:p-6 lg:p-8 relative">

        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5 mb-8 bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">نظرة عامة والتحليلات</h2>
                <p class="text-xs md:text-sm text-gray-500 font-medium mt-1">
                    إحصائيات النظام <span class="text-primary font-bold">({{ $periodLabel }})</span>
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <div class="relative w-full sm:w-auto">
                    <select id="dateFilterSelect" onchange="handleDateFilterChange()" class="w-full sm:w-48 bg-slate-50 border border-gray-200 rounded-xl py-3 pr-10 pl-8 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none appearance-none shadow-sm cursor-pointer">
                        <option value="all_time" {{ $dateFilter == 'all_time' ? 'selected' : '' }}>كل الأوقات (الافتراضي)</option>
                        <option value="today" {{ $dateFilter == 'today' ? 'selected' : '' }}>اليوم</option>
                        <option value="this_week" {{ $dateFilter == 'this_week' ? 'selected' : '' }}>هذا الأسبوع</option>
                        <option value="this_month" {{ $dateFilter == 'this_month' ? 'selected' : '' }}>هذا الشهر</option>
                        <option value="last_month" {{ $dateFilter == 'last_month' ? 'selected' : '' }}>الشهر الماضي</option>
                        <option value="this_year" {{ $dateFilter == 'this_year' ? 'selected' : '' }}>هذا العام</option>
                        <option value="last_year" {{ $dateFilter == 'last_year' ? 'selected' : '' }}>العام الماضي</option>
                        <option value="custom" {{ $dateFilter == 'custom' ? 'selected' : '' }}>نطاق مخصص...</option>
                    </select>
                    <i class="fa-regular fa-calendar absolute right-4 top-1/2 -translate-y-1/2 text-primary pointer-events-none"></i>
                    <i class="fa-solid fa-chevron-down absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                </div>

                <div id="customDateContainer" class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto {{ $dateFilter == 'custom' ? 'flex' : 'hidden' }}">
                    <input type="date" id="startDate" value="{{ $startDate }}" class="w-full sm:w-auto bg-slate-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs text-slate-600 outline-none focus:border-primary font-mono">
                    <span class="text-gray-400 text-xs font-bold">إلى</span>
                    <input type="date" id="endDate" value="{{ $endDate }}" class="w-full sm:w-auto bg-slate-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs text-slate-600 outline-none focus:border-primary font-mono">
                    <button onclick="applyCustomDate()" class="w-full sm:w-auto bg-slate-800 hover:bg-black text-white rounded-xl px-4 py-2.5 text-xs font-bold transition-all shadow-md">تطبيق</button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">

            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-blue-200 group">
                <div class="w-14 h-14 md:w-16 md:h-16 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="min-w-0 text-right">
                    <p class="text-[10px] md:text-xs text-gray-500 font-bold mb-1">المرضى والمستخدمين</p>
                    <div class="flex items-end gap-2">
                        <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight leading-none">{{ number_format($stats['users']) }}</h3>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-emerald-200 group">
                <div class="w-14 h-14 md:w-16 md:h-16 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div class="min-w-0 text-right">
                    <p class="text-[10px] md:text-xs text-gray-500 font-bold mb-1">الصيدليات المعتمدة</p>
                    <div class="flex items-center gap-2">
                        <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight leading-none">{{ number_format($stats['new_pharmacies']) }}</h3>
                        @if($dateFilter != 'all_time')
                            <span class="text-[9px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded font-bold">من {{ number_format($stats['total_pharmacies']) }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-amber-200 group">
                <div class="w-14 h-14 md:w-16 md:h-16 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-file-circle-exclamation text-xl"></i>
                </div>
                <div class="min-w-0 text-right">
                    <p class="text-[10px] md:text-xs text-gray-500 font-bold mb-1">طلبات قيد المراجعة</p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight leading-none">{{ number_format($stats['pending_apps']) }}</h3>
                </div>
            </div>

            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-teal-200 group">
                <div class="w-14 h-14 md:w-16 md:h-16 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-pills"></i>
                </div>
                <div class="min-w-0 text-right">
                    <p class="text-[10px] md:text-xs text-gray-500 font-bold mb-1">الأدوية المسجلة</p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight leading-none">{{ number_format($stats['new_medicines']) }}</h3>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
            <div class="xl:col-span-2 bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex flex-col h-[600px]">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-5 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 text-white flex items-center justify-center text-lg shadow-md">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <div>
                            <h3 class="text-lg md:text-xl font-black text-slate-800 tracking-tight">خريطة الانتشار الجغرافي</h3>
                            <p class="text-[10px] md:text-xs text-emerald-600 font-bold mt-0.5"><i class="fa-solid fa-circle text-[8px] animate-pulse mr-1"></i> تعرض {{ count($mapPharmacies) }} صيدلية نشطة حالياً</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-500 bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-100"><i class="fa-solid fa-circle text-emerald-500"></i> صيدلية عادية</span>
                        <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-500 bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-100"><i class="fa-solid fa-star text-amber-500"></i> صيدلية كبرى</span>
                    </div>
                </div>
                <div id="dashboardMap" class="w-full flex-1 rounded-2xl z-10 border border-gray-200 shadow-inner bg-slate-50"></div>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex flex-col h-[600px]">
                <div class="flex items-center justify-between mb-6 shrink-0">
                    <h3 class="text-lg font-black text-slate-800 tracking-tight">أحدث طلبات الانضمام</h3>
                    <a href="{{ route('pharmaciesApplications.index') }}" class="text-xs font-bold text-primary hover:underline">عرض الكل</a>
                </div>

                <div class="flex-1 overflow-y-auto scrollbar-thin pr-2 space-y-3">
                    @forelse($latestApplications as $app)
                        <div class="p-4 rounded-2xl border border-gray-100 bg-slate-50/50 hover:bg-white hover:shadow-md transition-all group">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center overflow-hidden shrink-0 shadow-sm">
                                        <i class="fa-solid fa-store text-gray-400"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-slate-800 mb-0.5">{{ $app->pharmacy_name }}</h4>
                                        <p class="text-[10px] text-gray-500 font-bold"><i class="fa-regular fa-user ml-1"></i>{{ $app->owner_name }}</p>
                                    </div>
                                </div>
                                @if($app->status == 'approved')
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-[9px] font-black">مقبول</span>
                                @elseif($app->status == 'under_review')
                                    <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded text-[9px] font-black">مراجعة</span>
                                @else
                                    <span class="px-2 py-1 bg-rose-100 text-rose-700 rounded text-[9px] font-black">مرفوض</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between mt-2 pt-3 border-t border-gray-100">
                                <span class="text-[10px] text-gray-400 font-mono" dir="ltr">{{ $app->created_at->diffForHumans() }}</span>
                                <span class="text-[10px] font-bold text-slate-600 bg-white px-2 py-1 rounded-md border border-gray-200"><i class="fa-solid fa-location-dot text-gray-400 ml-1"></i>{{ $app->city ?? 'غير محدد' }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full text-gray-400">
                            <i class="fa-solid fa-inbox text-4xl mb-3 text-gray-200"></i>
                            <p class="font-bold text-sm text-slate-500">لا توجد طلبات حديثة</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div id="viewProfileModal" class="fixed inset-0 z-[100] hidden flex-col items-center justify-center p-4 sm:p-6">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('viewProfileModal')"></div>
        <div class="relative bg-white rounded-[2.5rem] w-full max-w-3xl shadow-2xl overflow-hidden animate-scale-up flex flex-col max-h-full">
            <div class="relative h-32 md:h-40 bg-slate-800 shrink-0">
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
                        <h2 class="text-2xl font-black text-slate-800 mb-1 flex items-center justify-center md:justify-start gap-2 flex-wrap">
                            <span id="v-name-text">اسم الصيدلية</span>
                            <span id="v-id-badge" class="bg-slate-100 text-slate-500 border border-slate-200 px-2 py-0.5 rounded-lg text-xs font-mono shadow-sm">#--</span>

                            <span id="v-big-badge" class="hidden items-center gap-1 px-2 py-0.5 rounded-md bg-amber-100 text-amber-600 border border-amber-200 text-[10px] font-black shadow-sm">
                                <i class="fa-solid fa-star text-amber-500"></i> كبرى
                            </span>
                        </h2>
                        <p id="v-owner" class="text-sm font-bold text-gray-500"><i class="fa-solid fa-user-tie ml-1"></i> اسم المالك</p>
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
                        <span class="block text-[10px] uppercase font-black text-slate-400 mb-1">تاريخ الانضمام</span>
                        <p id="v-created-date" class="font-bold text-slate-700 text-sm font-mono" dir="ltr"></p>
                    </div>
                    <div class="md:col-span-2 bg-slate-50 p-4 rounded-2xl border border-gray-100">
                        <span class="block text-[10px] uppercase font-black text-slate-400 mb-1">العنوان التفصيلي</span>
                        <p id="v-address" class="font-bold text-slate-700 text-sm"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- Date Filter Logic ---
        function handleDateFilterChange() {
            const val = document.getElementById('dateFilterSelect').value;
            if (val === 'custom') {
                document.getElementById('customDateContainer').classList.remove('hidden');
                document.getElementById('customDateContainer').classList.add('flex');
            } else {
                document.getElementById('customDateContainer').classList.add('hidden');
                document.getElementById('customDateContainer').classList.remove('flex');
                applyServerFilter('date_filter', val, true);
            }
        }

        function applyCustomDate() {
            const start = document.getElementById('startDate').value;
            const end = document.getElementById('endDate').value;
            const url = new URL(window.location.href);

            url.searchParams.set('date_filter', 'custom');
            if (start) url.searchParams.set('start_date', start); else url.searchParams.delete('start_date');
            if (end) url.searchParams.set('end_date', end); else url.searchParams.delete('end_date');

            window.location.href = url.href;
        }

        function applyServerFilter(param, value, clearCustom = false) {
            const url = new URL(window.location.href);
            url.searchParams.set(param, value);
            if (clearCustom) {
                url.searchParams.delete('start_date');
                url.searchParams.delete('end_date');
            }
            window.location.href = url.href;
        }

        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

        function formatArabicDate(dateString) {
            if (!dateString) return 'غير متوفر';
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('ar-EG', options);
        }

        // --- MAP LOGIC ---
        let map;
        let markersLayer;
        const pharmaciesMapData = @json($mapPharmacies ?? []);
        const egyptBounds = L.latLngBounds([21.5, 24.5], [31.8, 37.0]);

        function initDashboardMap() {
            if (document.getElementById('dashboardMap')) {
                map = L.map('dashboardMap', {
                    center: [26.8206, 30.8025],
                    zoom: 6,
                    minZoom: 5,
                    maxBounds: egyptBounds,
                    maxBoundsViscosity: 1.0
                });

                L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                    attribution: '© OSM'
                }).addTo(map);

                markersLayer = L.layerGroup().addTo(map);
                drawMarkers();
            }
        }

        function drawMarkers() {
            if (!map || !markersLayer) return;

            const normalIcon = L.divIcon({
                className: 'custom-div-icon',
                html: "<div style='background-color:#10b981;' class='marker-pin'></div><i class='fa-solid fa-pills text-white relative z-10 text-[10px] mt-[6px]'></i>",
                iconSize: [30, 42], iconAnchor: [15, 42], popupAnchor: [0, -35]
            });

            const bigIcon = L.divIcon({
                className: 'custom-div-icon',
                html: "<div style='background-color:#f59e0b;' class='marker-pin'></div><i class='fa-solid fa-star text-white relative z-10 text-[10px] mt-[6px]'></i>",
                iconSize: [30, 42], iconAnchor: [15, 42], popupAnchor: [0, -35]
            });

            pharmaciesMapData.forEach(p => {
                if (p.lat && p.lng && !isNaN(p.lat) && !isNaN(p.lng)) {
                    const iconToUse = p.is_big_pharmacy ? bigIcon : normalIcon;
                    const pImg = p.image ? `/storage/${p.image}` : `https://ui-avatars.com/api/?name=${encodeURI(p.pharmacy_name)}&background=0f172a&color=fff`;

                    // تم إصلاح كود الـ HTML هنا لتجنب أي تداخل مع مكتبة Leaflet
                    const popupHtml = `
                        <div class="bg-white p-3 text-right" dir="rtl">
                            <div class="flex items-center gap-3 mb-2 border-b border-gray-100 pb-2">
                                <img src="${pImg}" class="w-10 h-10 rounded-full object-cover shadow-sm border border-gray-200 shrink-0">
                                <div class="overflow-hidden">
                                    <h4 class="font-black text-slate-800 text-sm truncate leading-tight">${p.pharmacy_name}</h4>
                                    <p class="text-[10px] text-gray-500 truncate mt-0.5"><i class="fa-solid fa-user-tie ml-1"></i>${p.owner_name}</p>
                                </div>
                            </div>
                            <div class="space-y-1.5 mb-3">
                                <p class="text-[10px] text-gray-600 truncate"><i class="fa-solid fa-location-dot text-gray-400 ml-1"></i>${p.address || p.city || 'غير محدد'}</p>
                                <p class="text-[10px] text-gray-600 font-mono text-right" dir="ltr"><i class="fa-solid fa-phone text-gray-400 mr-1"></i>${p.phone || 'غير متوفر'}</p>
                            </div>
                            <button onclick="openViewModalFromMap(${p.id})" class="w-full py-2 bg-slate-800 hover:bg-black text-white rounded-lg text-[10px] font-bold transition-all shadow-md">عرض التفاصيل كاملة</button>
                        </div>
                    `;

                    L.marker([parseFloat(p.lat), parseFloat(p.lng)], {icon: iconToUse})
                        .bindPopup(popupHtml, { className: 'custom-popup' })
                        .addTo(markersLayer);
                }
            });
        }

        // دالة الفتح النظيفة
        window.openViewModalFromMap = function(id) {
            const p = pharmaciesMapData.find(ph => ph.id == id);

            if(p) {
                document.getElementById('v-name-text').innerText = p.pharmacy_name;
                document.getElementById('v-id-badge').innerText = '#' + p.id; // إضافة الـ ID هنا
                document.getElementById('v-owner').innerHTML = `<i class="fa-solid fa-user-tie ml-1"></i> ${p.owner_name}`;
                document.getElementById('v-phone').innerText = p.phone || 'غير متوفر';
                document.getElementById('v-email').innerText = p.email || 'غير متوفر';
                document.getElementById('v-address').innerText = p.address || 'غير محدد';
                document.getElementById('v-city').innerText = p.city || 'غير محدد';
                document.getElementById('v-created-date').innerText = formatArabicDate(p.created_at);

                let imgUrl = p.image ? `/storage/${p.image}` : `https://ui-avatars.com/api/?name=${encodeURI(p.pharmacy_name)}&background=0f172a&color=fff`;
                document.getElementById('v-image').src = imgUrl;

                const bigBadge = document.getElementById('v-big-badge');
                if (p.is_big_pharmacy) {
                    bigBadge.classList.remove('hidden'); bigBadge.classList.add('inline-flex');
                } else {
                    bigBadge.classList.add('hidden'); bigBadge.classList.remove('inline-flex');
                }

                toggleModal('viewProfileModal');
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            initDashboardMap();
        });
    </script>

    <style>
        .animate-scale-up { animation: scaleUp 0.3s forwards; }
        @keyframes scaleUp { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1); } }

        /* Custom map popup styles - المحدثة لمنع التداخل */
        .custom-popup .leaflet-popup-content-wrapper { border-radius: 1rem; padding: 0; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); }
        .custom-popup .leaflet-popup-content { margin: 0; width: 240px !important; }
        .custom-popup .leaflet-popup-content p { margin: 0 !important; } /* حجب إعدادات Leaflet المزعجة */
        .custom-popup .leaflet-popup-tip-container { margin-top: -1px; }

        .marker-pin { width: 30px; height: 30px; border-radius: 50% 50% 50% 0; position: absolute; transform: rotate(-45deg); left: 50%; top: 50%; margin: -15px 0 0 -15px; box-shadow: 0 4px 6px rgba(0,0,0,0.3); }
        .custom-div-icon { background: none; border: none; }
        .custom-div-icon i { position: absolute; width: 22px; font-size: 14px; left: 0; right: 0; text-align: center; }
    </style>
@endsection
