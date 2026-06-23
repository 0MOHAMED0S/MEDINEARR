@extends('dashboard.layout.master')

@section('title', 'إدارة طلبات المنصة')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<style>
    /* تخصيص شكل TomSelect ليطابق تصميم Tailwind */
    .ts-wrapper.single .ts-control {
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
        padding: 0.5rem 0.75rem;
        box-shadow: none;
        font-family: inherit;
        font-size: 0.875rem;
        background-color: white;
    }
    .ts-wrapper.single.focus .ts-control {
        border-color: #00965e;
        box-shadow: 0 0 0 2px rgba(0, 150, 94, 0.2);
    }
    .ts-dropdown {
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        font-family: inherit;
        font-size: 0.875rem;
    }
    .ts-dropdown .active {
        background-color: #f3f4f6;
        color: #111827;
    }
    .modal-enter { opacity: 0; transform: scale(0.95); }
    .modal-enter-active { opacity: 1; transform: scale(1); transition: all 0.3s ease-out; }
    .modal-leave { opacity: 1; transform: scale(1); }
    .modal-leave-active { opacity: 0; transform: scale(0.95); transition: all 0.2s ease-in; }

    /* إعدادات الطباعة الاحترافية */
    @media print {
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body.printing-mode > *:not(#active-print-section) {
            display: none !important;
        }

        #active-print-section {
            display: block !important;
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            padding: 20px !important;
            margin: 0 !important;
            background: white !important;
            box-shadow: none !important;
            direction: rtl !important;
        }

        .no-print {
            display: none !important;
        }

        @page {
            margin: 1cm;
        }
    }
</style>

<div class="p-4 md:p-6 lg:p-8 font-cairo">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 no-print">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">إدارة طلبات المنصة</h1>
            <p class="text-sm text-gray-500 mt-1">تابع كافة الطلبات عبر جميع الصيدليات، راجع المدفوعات وحالات التوصيل.</p>
        </div>
        <div>
            <button class="bg-[#00965e] hover:bg-[#007b4d] text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-download"></i>
                تصدير التقرير
            </button>
        </div>
    </div>

    <!-- Premium KPI Analytics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8 no-print">
        <!-- Total Orders -->
        <div class="bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-purple-200 group">
            <div class="w-14 h-14 md:w-16 md:h-16 bg-purple-50 text-purple-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-clipboard-list"></i>
            </div>
            <div class="min-w-0 text-right">
                <p class="text-[10px] md:text-xs text-gray-500 font-bold mb-1">إجمالي الطلبات بالمنصة</p>
                <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight leading-none">{{ number_format($totalOrders) }}</h3>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-orange-200 group">
            <div class="w-14 h-14 md:w-16 md:h-16 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <div class="min-w-0 text-right">
                <p class="text-[10px] md:text-xs text-gray-500 font-bold mb-1">الطلبات قيد الانتظار</p>
                <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight leading-none">{{ number_format($pendingOrders) }}</h3>
            </div>
        </div>

        <!-- Processing Orders -->
        <div class="bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-blue-200 group">
            <div class="w-14 h-14 md:w-16 md:h-16 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-motorcycle"></i>
            </div>
            <div class="min-w-0 text-right">
                <p class="text-[10px] md:text-xs text-gray-500 font-bold mb-1">قيد التجهيز / جاري التوصيل</p>
                <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight leading-none">{{ number_format($processingOrders) }}</h3>
            </div>
        </div>

        <!-- Delivered Orders -->
        <div class="bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-emerald-200 group">
            <div class="w-14 h-14 md:w-16 md:h-16 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <div class="min-w-0 text-right">
                <p class="text-[10px] md:text-xs text-gray-500 font-bold mb-1">الطلبات المكتملة بنجاح</p>
                <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight leading-none">{{ number_format($deliveredOrders) }}</h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6 no-print">
        <form action="{{ route('orders.index') ?? '#' }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-8 gap-4">

            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">البحث (الطلب، العميل، الهاتف)</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث..."
                        class="w-full pl-3 pr-10 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#00965e]/20 focus:border-[#00965e] outline-none transition-all text-sm">
                    <i class="fa-solid fa-search absolute right-3 top-2.5 text-gray-400"></i>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">الصيدلية</label>
                <select name="pharmacy_id" id="pharmacy-select" class="w-full text-sm" placeholder="ابحث باسم الصيدلية...">
                    <option value="">كل الصيدليات</option>
                    @foreach($pharmacies as $pharmacy)
                        <option value="{{ $pharmacy->id }}" {{ request('pharmacy_id') == $pharmacy->id ? 'selected' : '' }}>{{ $pharmacy->pharmacy_name ?? 'صيدلية' }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">حالة الطلب</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#00965e]/20 focus:border-[#00965e] outline-none transition-all text-sm appearance-none">
                    <option value="">الكل</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>تم القبول</option>
                    <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>جاري التجهيز</option>
                    <option value="out_for_delivery" {{ request('status') == 'out_for_delivery' ? 'selected' : '' }}>في الطريق</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>تم التوصيل</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">الدفع</label>
                <select name="payment_method" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#00965e]/20 focus:border-[#00965e] outline-none transition-all text-sm appearance-none">
                    <option value="">الكل</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>عند الاستلام</option>
                    <option value="paymob" {{ request('payment_method') == 'paymob' ? 'selected' : '' }}>أونلاين</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">من تاريخ</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#00965e]/20 focus:border-[#00965e] outline-none transition-all text-sm text-gray-700">
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">إلى تاريخ</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#00965e]/20 focus:border-[#00965e] outline-none transition-all text-sm text-gray-700">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm">
                    تطبيق
                </button>
                <a href="{{ route('orders.index') ?? '#' }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-sm font-semibold transition-all flex items-center justify-center" title="إعادة ضبط">
                    <i class="fa-solid fa-rotate-right"></i>
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden no-print">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase">الطلب والتاريخ</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase">الصيدلية والعميل</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase">الإجمالي والدفع</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase">الحالة</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase text-center">تفاصيل</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">

                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="py-3 px-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-800 text-sm">#{{ $order->order_reference }}</span>
                                    <span class="text-xs text-gray-500 mt-0.5">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-indigo-700 text-sm"><i class="fa-solid fa-house-medical text-[10px]"></i> {{ $order->pharmacy->pharmacy_name ?? 'صيدلية' }}</span>
                                    <span class="text-xs font-semibold text-gray-600 mt-0.5"><i class="fa-solid fa-user text-[10px]"></i> {{ $order->user->name ?? 'عميل' }}</span>
                                    <span class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                        <i class="fa-solid fa-phone text-[10px]"></i> {{ $order->phone }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex flex-col items-start">
                                    <span class="font-bold text-[#00965e] text-sm">{{ number_format($order->grand_total, 2) }} ج.م</span>
                                    <div class="mt-1 flex items-center gap-1.5">
                                        @if($order->payment_method == 'paymob')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                                <i class="fa-brands fa-cc-visa"></i> أونلاين
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                                <i class="fa-solid fa-money-bill-wave"></i> كاش
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                        'accepted' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'preparing' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'out_for_delivery' => 'bg-orange-50 text-orange-700 border-orange-200',
                                        'delivered' => 'bg-green-50 text-green-700 border-green-200',
                                        'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'قيد الانتظار',
                                        'accepted' => 'تم القبول',
                                        'preparing' => 'جاري التجهيز',
                                        'out_for_delivery' => 'في الطريق',
                                        'delivered' => 'تم التوصيل',
                                        'cancelled' => 'ملغي',
                                    ];
                                    $colorClass = $statusColors[$order->status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                    $label = $statusLabels[$order->status] ?? 'غير معروف';
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $colorClass }}">
                                    @if($order->status == 'pending')
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                                    @elseif($order->status == 'delivered')
                                        <i class="fa-solid fa-check-circle"></i>
                                    @elseif($order->status == 'cancelled')
                                        <i class="fa-solid fa-xmark-circle"></i>
                                    @else
                                        <i class="fa-solid fa-circle text-[8px]"></i>
                                    @endif
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @php
                                    $itemsJson = $order->items->map(function($item) {
                                        return [
                                            'qty' => $item->quantity,
                                            'name' => $item->medicine->name ?? 'دواء محذوف',
                                            'price' => number_format($item->price, 2)
                                        ];
                                    })->toJson();
                                    $paymentLabel = $order->payment_method == 'paymob' ? 'أونلاين' : 'كاش';
                                @endphp
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="openDetailsModal('{{ $order->order_reference }}', '{{ $order->user->name ?? 'عميل' }}', '{{ $order->phone }}', '{{ $order->address }}', '{{ number_format($order->grand_total, 2) }}', '{{ $paymentLabel }}', '{{ $label }}', this)"
                                            data-items="{{ $itemsJson }}"
                                            class="p-2 text-gray-400 hover:text-[#00965e] hover:bg-emerald-50 rounded-lg transition-colors" title="عرض التفاصيل">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button onclick="printOrder('{{ $order->order_reference }}', '{{ $order->user->name ?? 'عميل' }}', '{{ $order->phone }}', '{{ $order->address }}', '{{ number_format($order->grand_total, 2) }}', '{{ $paymentLabel }}', '{{ $label }}')"
                                            class="p-2 text-gray-400 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors" title="طباعة">
                                        <i class="fa-solid fa-print"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <i class="fa-solid fa-box-open text-2xl text-gray-300"></i>
                                    </div>
                                    <h3 class="text-sm font-bold text-gray-800">لا توجد طلبات تطابق بحثك</h3>
                                    <p class="text-xs text-gray-500 mt-1">حاول تغيير إعدادات الفلتر أو البحث بكلمة مختلفة.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            <div class="flex items-center justify-between text-sm text-gray-600">
                <span>عرض {{ $orders->firstItem() ?? 0 }} إلى {{ $orders->lastItem() ?? 0 }} من أصل {{ $orders->total() }} طلب</span>
                <div class="flex gap-1" dir="ltr">
                    {{ $orders->withQueryString()->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Details Modal & Print Section -->
<div id="detailsModal" class="fixed inset-0 z-50 hidden no-print" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeDetailsModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div id="print-section" class="relative transform overflow-hidden rounded-2xl bg-white text-right shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-100">

                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2" id="modal-title">
                        <i class="fa-solid fa-receipt text-[#00965e]"></i>
                        تفاصيل الطلب <span id="mdl-ref" class="text-[#00965e]"></span>
                    </h3>
                    <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-red-500 transition-colors no-print">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <div class="px-6 py-5">
                    <div class="grid grid-cols-2 gap-4 mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">اسم العميل</p>
                            <p class="text-sm font-bold text-gray-800" id="mdl-name"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">رقم الهاتف</p>
                            <p class="text-sm font-bold text-gray-800" id="mdl-phone"></p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs text-gray-500 mb-1">عنوان التوصيل</p>
                            <p class="text-sm font-bold text-gray-800" id="mdl-address"></p>
                        </div>
                    </div>

                    <div class="mb-4 flex justify-between items-center pb-2 border-b border-gray-100">
                        <h4 class="font-bold text-gray-700 text-sm">الأدوية المطلوبة</h4>
                        <span class="text-xs font-semibold px-2 py-1 bg-gray-100 rounded text-gray-600" id="mdl-status"></span>
                    </div>

                    <ul class="space-y-3 mb-6" id="mdl-items-list">
                        <!-- Dynamic items will be injected here -->
                    </ul>

                    <div class="bg-[#00965e]/5 rounded-xl p-4 border border-[#00965e]/20">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">طريقة الدفع</span>
                            <span class="font-bold text-gray-800" id="mdl-payment"></span>
                        </div>
                        <div class="flex justify-between text-base font-bold mt-3 pt-3 border-t border-[#00965e]/20">
                            <span class="text-gray-800">الإجمالي النهائي</span>
                            <span class="text-[#00965e]" id="mdl-total"></span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3 no-print">
                    <button onclick="closeDetailsModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all">
                        إغلاق
                    </button>
                    <button onclick="printOrder(document.getElementById('mdl-ref').innerText, document.getElementById('mdl-name').innerText, document.getElementById('mdl-phone').innerText, document.getElementById('mdl-address').innerText, document.getElementById('mdl-total').innerText.replace(' ج.م', ''), document.getElementById('mdl-payment').innerText, document.getElementById('mdl-status').innerText)" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-semibold hover:bg-gray-900 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-print"></i> طباعة الفاتورة
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openDetailsModal(ref, name, phone, address, total, payment, status, btnElement) {
        document.getElementById('mdl-ref').innerText = ref;
        document.getElementById('mdl-name').innerText = name;
        document.getElementById('mdl-phone').innerText = phone;
        document.getElementById('mdl-address').innerText = address;
        document.getElementById('mdl-total').innerText = total + ' ج.م';
        document.getElementById('mdl-payment').innerText = payment;
        document.getElementById('mdl-status').innerText = status;

        const itemsList = document.getElementById('mdl-items-list');
        itemsList.innerHTML = '';
        
        if(btnElement) {
            const items = JSON.parse(btnElement.getAttribute('data-items') || '[]');
            items.forEach(item => {
                itemsList.innerHTML += `
                    <li class="flex justify-between text-sm">
                        <span class="text-gray-700"><span class="font-bold text-[#00965e]">${item.qty}x</span> ${item.name}</span>
                        <span class="font-semibold text-gray-800">${item.price} ج.م</span>
                    </li>
                `;
            });
        }

        const modal = document.getElementById('detailsModal');
        const modalContent = modal.querySelector('.transform');

        modal.classList.remove('hidden');
        void modal.offsetWidth;

        modalContent.classList.add('modal-enter-active');
        modalContent.classList.remove('modal-enter', 'modal-leave-active', 'modal-leave');
    }

    function closeDetailsModal() {
        const modal = document.getElementById('detailsModal');
        const modalContent = modal.querySelector('.transform');

        modalContent.classList.add('modal-leave-active');
        modalContent.classList.remove('modal-enter-active');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    function printOrder(ref, name, phone, address, total, payment, status) {
        document.getElementById('mdl-ref').innerText = ref;
        document.getElementById('mdl-name').innerText = name;
        document.getElementById('mdl-phone').innerText = phone;
        document.getElementById('mdl-address').innerText = address;
        document.getElementById('mdl-total').innerText = total + ' ج.م';
        document.getElementById('mdl-payment').innerText = payment;
        document.getElementById('mdl-status').innerText = status;

        const printContent = document.getElementById('print-section').cloneNode(true);
        printContent.id = 'active-print-section';

        const noPrintElements = printContent.querySelectorAll('.no-print');
        noPrintElements.forEach(el => el.remove());

        document.body.appendChild(printContent);
        document.body.classList.add('printing-mode');

        setTimeout(() => {
            window.print();
            document.body.classList.remove('printing-mode');
            printContent.remove();
        }, 100);
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('select').forEach(function(selectEl) {
            new TomSelect(selectEl, {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        });
    });
</script>
@endsection
