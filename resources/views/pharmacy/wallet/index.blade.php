@extends('pharmacy.layout.master')

@section('title', 'المحفظة والأرباح')

@section('content')
<div class="p-4 md:p-6 lg:p-8 font-cairo">

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">المحفظة المالية</h1>
            <p class="text-sm text-gray-500 mt-1">إدارة الأرباح، عمليات التحويل، وكشف الحساب.</p>
        </div>
        <button class="bg-[#00965e] hover:bg-[#007b4d] text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-[#00965e]/20 transition-all flex items-center gap-2">
            <i class="fa-solid fa-money-bill-transfer"></i>
            طلب سحب رصيد جديد
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-[#00965e] to-[#00b370] rounded-2xl p-6 text-white shadow-xl shadow-[#00965e]/20 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-white/80 text-xs font-semibold uppercase tracking-wider mb-1">الرصيد المتاح للسحب</p>
                <h2 class="text-4xl font-bold">4,520.00 <span class="text-xl font-normal">ج.م</span></h2>
            </div>
            <i class="fa-solid fa-wallet absolute -left-2 -bottom-2 text-white/10 text-8xl"></i>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">إجمالي الأرباح الكلية</p>
            <h2 class="text-3xl font-bold text-gray-800">12,850.00 <span class="text-lg text-gray-400 font-normal">ج.م</span></h2>
            <div class="mt-4 text-xs text-emerald-600 bg-emerald-50 w-fit px-3 py-1 rounded-full font-bold">
                +12% زيادة هذا الشهر
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">عدد عمليات الدفع (أونلاين)</p>
            <h2 class="text-3xl font-bold text-gray-800">142 <span class="text-lg text-gray-400 font-normal">عملية</span></h2>
            <p class="text-xs text-gray-400 mt-4">آخر تحديث: {{ now()->format('d M, Y') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h3 class="font-bold text-gray-800 text-lg">سجل الحركات المالية</h3>
            <button class="text-sm text-[#00965e] font-bold hover:bg-emerald-50 px-4 py-2 rounded-lg transition-all border border-transparent hover:border-emerald-100">
                تحميل كشف حساب (PDF)
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">رقم الحركة</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">نوع العملية</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">التاريخ</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">الحالة</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-left">القيمة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <tr class="hover:bg-gray-50/80 transition-all">
                        <td class="py-4 px-6 text-sm font-bold text-gray-700">#TRX-9981</td>
                        <td class="py-4 px-6 text-sm text-gray-600 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                                <i class="fa-solid fa-arrow-down-long text-xs"></i>
                            </span>
                            أرباح طلب #MED-123456
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-400">20 مايو 2026</td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-green-50 text-green-600">مكتملة</span>
                        </td>
                        <td class="py-4 px-6 text-sm font-extrabold text-green-600 text-left">+250.00 ج.م</td>
                    </tr>
                    <tr class="hover:bg-gray-50/80 transition-all">
                        <td class="py-4 px-6 text-sm font-bold text-gray-700">#TRX-9980</td>
                        <td class="py-4 px-6 text-sm text-gray-600 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                                <i class="fa-solid fa-arrow-up-long text-xs"></i>
                            </span>
                            عملية سحب رصيد
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-400">15 مايو 2026</td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600">قيد المراجعة</span>
                        </td>
                        <td class="py-4 px-6 text-sm font-extrabold text-red-600 text-left">-1,000.00 ج.م</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
