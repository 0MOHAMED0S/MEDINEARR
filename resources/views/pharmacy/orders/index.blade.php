@extends('pharmacy.layout.master')

@section('title', 'إدارة الطلبات')

@section('content')
<style>
    .modal-enter { opacity: 0; transform: scale(0.95); }
    .modal-enter-active { opacity: 1; transform: scale(1); transition: all 0.3s ease-out; }
    .modal-leave { opacity: 1; transform: scale(1); }
    .modal-leave-active { opacity: 0; transform: scale(0.95); transition: all 0.2s ease-in; }

    /* إعدادات الطباعة الاحترافية */
    @media print {
        /* إجبار المتصفح على طباعة الألوان والخلفيات */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* إخفاء كل محتويات الموقع الأساسية وقت الطباعة فقط */
        body.printing-mode > *:not(#active-print-section) {
            display: none !important;
        }

        /* تنسيق الفاتورة المستنسخة لتملأ الورقة بالكامل */
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

        /* إخفاء أي زرار أو عنصر مش عايزينه يظهر في الورقة */
        .no-print {
            display: none !important;
        }

        @page {
            margin: 1cm; /* هوامش الورقة */
        }
    }
</style>

<div class="p-4 md:p-6 lg:p-8 font-cairo">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 no-print">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">إدارة الطلبات</h1>
            <p class="text-sm text-gray-500 mt-1">تابع طلبات عملائك، حدث الحالات، وراجع المدفوعات.</p>
        </div>
        <div>
            <button class="bg-[#00965e] hover:bg-[#007b4d] text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-download"></i>
                تصدير التقرير
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6 no-print">
        <form action="{{ route('pharmacy.orders') ?? '#' }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">البحث (رقم الطلب أو الهاتف)</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث عن أوردر..."
                        class="w-full pl-3 pr-10 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#00965e]/20 focus:border-[#00965e] outline-none transition-all text-sm">
                    <i class="fa-solid fa-search absolute right-3 top-2.5 text-gray-400"></i>
                </div>
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
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">طريقة الدفع</label>
                <select name="payment_method" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#00965e]/20 focus:border-[#00965e] outline-none transition-all text-sm appearance-none">
                    <option value="">الكل</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>الدفع عند الاستلام</option>
                    <option value="paymob" {{ request('payment_method') == 'paymob' ? 'selected' : '' }}>Paymob (أونلاين)</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm">
                    تطبيق
                </button>
                <a href="{{ route('pharmacy.orders') ?? '#' }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-sm font-semibold transition-all flex items-center justify-center" title="إعادة ضبط">
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
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase">العميل</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase">الإجمالي والدفع</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase">الحالة</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">

                    {{-- Loop Example: @forelse ($orders as $order) --}}

                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="py-3 px-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-800 text-sm">#MED-123456</span>
                                <span class="text-xs text-gray-500 mt-0.5">20 مايو 2026, 06:45 PM</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-800 text-sm">محمد سيد</span>
                                <span class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                    <i class="fa-solid fa-phone text-[10px]"></i> 01110562097
                                </span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex flex-col items-start">
                                <span class="font-bold text-[#00965e] text-sm">250.00 ج.م</span>
                                <div class="mt-1 flex items-center gap-1.5">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                        <i class="fa-brands fa-cc-visa"></i> أونلاين
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span> قيد الانتظار
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openDetailsModal('MED-123456', 'محمد سيد', '01110562097', 'شارع 9، المعادي', '250.00', 'أونلاين', 'قيد الانتظار')"
                                        class="p-2 text-gray-400 hover:text-[#00965e] hover:bg-emerald-50 rounded-lg transition-colors" title="عرض التفاصيل">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button onclick="openStatusModal('1', 'MED-123456', 'pending')"
                                        class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="تحديث الحالة">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button onclick="printOrder('MED-123456', 'محمد سيد', '01110562097', 'شارع 9، المعادي', '250.00', 'أونلاين', 'قيد الانتظار')"
                                        class="p-2 text-gray-400 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors" title="طباعة">
                                    <i class="fa-solid fa-print"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="py-3 px-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-800 text-sm">#MED-987654</span>
                                <span class="text-xs text-gray-500 mt-0.5">20 مايو 2026, 02:30 PM</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-800 text-sm">أحمد محمود</span>
                                <span class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                    <i class="fa-solid fa-phone text-[10px]"></i> 01001234567
                                </span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex flex-col items-start">
                                <span class="font-bold text-[#00965e] text-sm">180.00 ج.م</span>
                                <div class="mt-1 flex items-center gap-1.5">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                        <i class="fa-solid fa-money-bill-wave"></i> كاش
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                <i class="fa-solid fa-check-circle"></i> تم التوصيل
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openDetailsModal('MED-987654', 'أحمد محمود', '01001234567', 'مدينة نصر، مكرم عبيد', '180.00', 'كاش', 'تم التوصيل')"
                                        class="p-2 text-gray-400 hover:text-[#00965e] hover:bg-emerald-50 rounded-lg transition-colors" title="عرض التفاصيل">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button onclick="openStatusModal('2', 'MED-987654', 'delivered')"
                                        class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="تحديث الحالة">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button onclick="printOrder('MED-987654', 'أحمد محمود', '01001234567', 'مدينة نصر، مكرم عبيد', '180.00', 'كاش', 'تم التوصيل')"
                                        class="p-2 text-gray-400 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors" title="طباعة">
                                    <i class="fa-solid fa-print"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Empty State --}}
                    {{--
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
                    --}}

                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            <div class="flex items-center justify-between text-sm text-gray-600">
                <span>عرض 1 إلى 10 من أصل 45 طلب</span>
                {{-- {{ $orders->withQueryString()->links() }} --}}
                <div class="flex gap-1">
                    <button class="px-3 py-1 bg-white border border-gray-200 rounded hover:bg-gray-50 text-gray-400" disabled>السابق</button>
                    <button class="px-3 py-1 bg-[#00965e] text-white border border-[#00965e] rounded">1</button>
                    <button class="px-3 py-1 bg-white border border-gray-200 rounded hover:bg-gray-50">2</button>
                    <button class="px-3 py-1 bg-white border border-gray-200 rounded hover:bg-gray-50">التالي</button>
                </div>
            </div>
        </div>
    </div>

</div>

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

                    <ul class="space-y-3 mb-6">
                        <li class="flex justify-between text-sm">
                            <span class="text-gray-700"><span class="font-bold text-[#00965e]">2x</span> Panadol Extra 500mg</span>
                            <span class="font-semibold text-gray-800">90.00 ج.م</span>
                        </li>
                        <li class="flex justify-between text-sm">
                            <span class="text-gray-700"><span class="font-bold text-[#00965e]">1x</span> Concor 5mg</span>
                            <span class="font-semibold text-gray-800">60.00 ج.م</span>
                        </li>
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


<div id="statusModal" class="fixed inset-0 z-50 hidden no-print" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeStatusModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-right shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">

                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">تحديث حالة الطلب</h3>
                    <button onclick="closeStatusModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <form id="updateStatusForm" method="POST" action="">
                    @csrf
                    @method('PATCH')
                    <div class="px-6 py-6">
                        <p class="text-sm text-gray-600 mb-4">جاري تحديث حالة الطلب رقم <span id="status-mdl-ref" class="font-bold text-[#00965e]"></span></p>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">اختر الحالة الجديدة:</label>
                        <select name="status" id="status-select" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#00965e]/20 focus:border-[#00965e] outline-none transition-all text-sm font-semibold">
                            <option value="pending">قيد الانتظار</option>
                            <option value="accepted">تم القبول (جاري التجهيز)</option>
                            <option value="out_for_delivery">في الطريق (مع المندوب)</option>
                            <option value="delivered">مكتمل (تم التوصيل)</option>
                            <option value="cancelled">إلغاء الطلب</option>
                        </select>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                        <button type="button" onclick="closeStatusModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all">
                            إلغاء
                        </button>
                        <button type="submit" class="px-4 py-2 bg-[#00965e] text-white rounded-lg text-sm font-semibold hover:bg-[#007b4d] transition-all">
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openDetailsModal(ref, name, phone, address, total, payment, status) {
        document.getElementById('mdl-ref').innerText = ref;
        document.getElementById('mdl-name').innerText = name;
        document.getElementById('mdl-phone').innerText = phone;
        document.getElementById('mdl-address').innerText = address;
        document.getElementById('mdl-total').innerText = total + ' ج.م';
        document.getElementById('mdl-payment').innerText = payment;
        document.getElementById('mdl-status').innerText = status;

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

    function openStatusModal(orderId, ref, currentStatus) {
        document.getElementById('status-mdl-ref').innerText = ref;
        document.getElementById('status-select').value = currentStatus;

        // document.getElementById('updateStatusForm').action = `/pharmacy/orders/${orderId}/status`;

        const modal = document.getElementById('statusModal');
        const modalContent = modal.querySelector('.transform');

        modal.classList.remove('hidden');
        void modal.offsetWidth;

        modalContent.classList.add('modal-enter-active');
        modalContent.classList.remove('modal-enter', 'modal-leave-active', 'modal-leave');
    }

    function closeStatusModal() {
        const modal = document.getElementById('statusModal');
        const modalContent = modal.querySelector('.transform');

        modalContent.classList.add('modal-leave-active');
        modalContent.classList.remove('modal-enter-active');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    function printOrder(ref, name, phone, address, total, payment, status) {
        // 1. تحديث الداتا في القسم الخفي
        document.getElementById('mdl-ref').innerText = ref;
        document.getElementById('mdl-name').innerText = name;
        document.getElementById('mdl-phone').innerText = phone;
        document.getElementById('mdl-address').innerText = address;
        document.getElementById('mdl-total').innerText = total + ' ج.م';
        document.getElementById('mdl-payment').innerText = payment;
        document.getElementById('mdl-status').innerText = status;

        // 2. عمل Clone للقسم المستهدف
        const printContent = document.getElementById('print-section').cloneNode(true);
        printContent.id = 'active-print-section';

        // 3. إزالة الأزرار من النسخة المطبوعة
        const noPrintElements = printContent.querySelectorAll('.no-print');
        noPrintElements.forEach(el => el.remove());

        // 4. وضع النسخة في الـ body مباشرة
        document.body.appendChild(printContent);
        document.body.classList.add('printing-mode');

        // 5. الطباعة والتنظيف
        setTimeout(() => {
            window.print();

            document.body.classList.remove('printing-mode');
            printContent.remove();
        }, 100);
    }
</script>

@endsection
