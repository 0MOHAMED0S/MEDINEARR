@extends('pharmacy.layout.master')
@section('content')
    <div class="p-4 md:p-6 lg:p-8">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">

            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all">
                <div class="w-12 h-12 md:w-14 md:h-14 bg-teal-50 text-primary rounded-2xl flex items-center justify-center text-xl md:text-2xl shrink-0">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate">طلبات اليوم</p>
                    <h3 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">124</h3>
                </div>
            </div>

            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all">
                <div class="w-12 h-12 md:w-14 md:h-14 bg-lime-50 text-accent rounded-2xl flex items-center justify-center text-xl md:text-2xl shrink-0">
                    <i class="fa-solid fa-house-medical"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate">صيدليات نشطة</p>
                    <h3 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">42</h3>
                </div>
            </div>

            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all">
                <div class="w-12 h-12 md:w-14 md:h-14 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-xl md:text-2xl shrink-0">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate">مرضى جدد</p>
                    <h3 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">850</h3>
                </div>
            </div>

            <div class="stat-card bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all">
                <div class="w-12 h-12 md:w-14 md:h-14 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center text-xl md:text-2xl shrink-0">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs md:text-sm text-gray-500 font-bold truncate">جاري التوصيل</p>
                    <h3 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">18</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden transition-all">
            <div class="p-5 md:p-6 border-b border-gray-50 flex flex-row items-center justify-between gap-4">
                <h3 class="font-black text-slate-800 text-base md:text-lg tracking-tight">أحدث طلبات الأدوية</h3>
                <button class="text-primary font-bold text-xs md:text-sm hover:text-primaryDark transition-colors shrink-0">عرض الكل</button>
            </div>

            <div class="overflow-x-auto scrollbar-thin">
                <table class="w-full text-right min-w-[700px]">
                    <thead class="bg-slate-50/80 text-gray-500 text-[11px] md:text-xs uppercase tracking-wider font-bold">
                        <tr>
                            <th class="p-4">رقم الطلب</th>
                            <th class="p-4">المريض</th>
                            <th class="p-4">الصيدلية</th>
                            <th class="p-4">الحالة</th>
                            <th class="p-4">التاريخ</th>
                            <th class="p-4 text-center">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="p-4 font-bold text-primary text-sm">#MD-9920</td>
                            <td class="p-4 font-semibold text-slate-700 text-sm">أحمد محمد علي</td>
                            <td class="p-4 text-slate-600 text-sm">صيدلية الشفاء</td>
                            <td class="p-4">
                                <span class="inline-flex px-3 py-1 bg-lime-100 text-accent rounded-full text-[10px] md:text-xs font-black">مكتمل</span>
                            </td>
                            <td class="p-4 text-gray-400 text-[11px] md:text-xs font-medium">منذ 5 دقائق</td>
                            <td class="p-4 text-center">
                                <button class="w-8 h-8 rounded-xl bg-slate-100 text-slate-400 group-hover:bg-primary group-hover:text-white transition-all flex items-center justify-center mx-auto shadow-sm">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="p-4 font-bold text-primary text-sm">#MD-9921</td>
                            <td class="p-4 font-semibold text-slate-700 text-sm">سارة محمود</td>
                            <td class="p-4 text-slate-600 text-sm">صيدلية الحياة</td>
                            <td class="p-4">
                                <span class="inline-flex px-3 py-1 bg-orange-100 text-orange-500 rounded-full text-[10px] md:text-xs font-black">قيد التنفيذ</span>
                            </td>
                            <td class="p-4 text-gray-400 text-[11px] md:text-xs font-medium">منذ 12 دقيقة</td>
                            <td class="p-4 text-center">
                                <button class="w-8 h-8 rounded-xl bg-slate-100 text-slate-400 group-hover:bg-primary group-hover:text-white transition-all flex items-center justify-center mx-auto shadow-sm">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="md:hidden p-4 bg-slate-50/50 text-center border-t border-gray-50">
                <p class="text-[10px] text-gray-400 font-bold">اسحب لليسار لعرض المزيد من البيانات <i class="fa-solid fa-arrow-left-long mr-1"></i></p>
            </div>
        </div>
    </div>
@endsection
