@extends('pharmacy.layout.master')

@section('title', 'الرئيسية والتحليلات')

@section('content')
<div class="p-4 md:p-6 lg:p-8 font-cairo">

    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-[#00965e] to-[#00b370] rounded-[2rem] p-6 md:p-8 text-white mb-8 shadow-lg shadow-[#00965e]/20 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl md:text-3xl font-black mb-2 flex items-center gap-2">
                مرحباً بك في لوحة القيادة الخاصة بك! <span class="animate-bounce inline-block">👋</span>
            </h2>
            <p class="text-white/90 text-sm md:text-base font-medium">
                إليك ملخص شامل ومباشر لأداء مبيعاتك، أرباحك، وكل ما يخص صيدليتك عبر منصة MediNear.
            </p>
        </div>
        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shrink-0 backdrop-blur-sm">
            <i class="fa-solid fa-chart-pie text-3xl"></i>
        </div>
    </div>

    <!-- Financial Analytics Section -->
    <div class="mb-10">
        <h3 class="text-xl font-black text-slate-800 tracking-tight mb-5 flex items-center gap-2">
            <i class="fa-solid fa-vault text-[#00965e]"></i> التحليل المالي والمحفظة
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <!-- Available Balance -->
            <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-[2rem] shadow-lg shadow-indigo-500/20 p-5 md:p-6 text-white relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-white/80 text-[11px] font-bold uppercase tracking-widest mb-1">الرصيد المتاح للسحب</p>
                    <h3 class="text-2xl md:text-3xl font-black mb-2">{{ number_format($stats['available_balance'], 2) }} <span class="text-sm font-normal">ج.م</span></h3>
                    <p class="text-white/90 text-[10px]"><i class="fa-solid fa-wallet"></i> متوفر الآن في محفظتك</p>
                </div>
                <i class="fa-solid fa-piggy-bank absolute -left-4 -bottom-4 text-white/10 text-8xl group-hover:scale-110 transition-transform duration-500"></i>
            </div>

            <!-- Total Earnings -->
            <div class="bg-gradient-to-br from-cyan-500 to-blue-500 rounded-[2rem] shadow-lg shadow-cyan-500/20 p-5 md:p-6 text-white relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-white/80 text-[11px] font-bold uppercase tracking-widest mb-1">إجمالي الأرباح الكلية</p>
                    <h3 class="text-2xl md:text-3xl font-black mb-2">{{ number_format($stats['total_earnings'], 2) }} <span class="text-sm font-normal">ج.م</span></h3>
                    <p class="text-white/90 text-[10px]"><i class="fa-solid fa-chart-line"></i> أرباحك منذ الانضمام</p>
                </div>
                <i class="fa-solid fa-coins absolute -left-4 -bottom-4 text-white/10 text-8xl group-hover:scale-110 transition-transform duration-500"></i>
            </div>

            <!-- Pending Withdrawals -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-[2rem] shadow-lg shadow-orange-500/20 p-5 md:p-6 text-white relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-white/80 text-[11px] font-bold uppercase tracking-widest mb-1">مبالغ قيد المراجعة</p>
                    <h3 class="text-2xl md:text-3xl font-black mb-2">{{ number_format($stats['pending_withdrawals'], 2) }} <span class="text-sm font-normal">ج.م</span></h3>
                    <p class="text-white/90 text-[10px]"><i class="fa-solid fa-clock"></i> طلبات سحب في انتظار الموافقة</p>
                </div>
                <i class="fa-solid fa-clock-rotate-left absolute -left-4 -bottom-4 text-white/10 text-8xl group-hover:scale-110 transition-transform duration-500"></i>
            </div>

            <!-- Approved Withdrawals -->
            <div class="bg-gradient-to-br from-[#00965e] to-[#007b4d] rounded-[2rem] shadow-lg shadow-[#00965e]/20 p-5 md:p-6 text-white relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-white/80 text-[11px] font-bold uppercase tracking-widest mb-1">المبالغ المسحوبة بنجاح</p>
                    <h3 class="text-2xl md:text-3xl font-black mb-2">{{ number_format($stats['approved_withdrawals'], 2) }} <span class="text-sm font-normal">ج.م</span></h3>
                    <p class="text-white/90 text-[10px]"><i class="fa-solid fa-check-double"></i> إجمالي التحويلات المكتملة لك</p>
                </div>
                <i class="fa-solid fa-money-bill-transfer absolute -left-4 -bottom-4 text-white/10 text-8xl group-hover:scale-110 transition-transform duration-500"></i>
            </div>
        </div>
    </div>

    <!-- Orders Analytics Section -->
    <div class="mb-10">
        <h3 class="text-xl font-black text-slate-800 tracking-tight mb-5 flex items-center gap-2">
            <i class="fa-solid fa-cart-shopping text-blue-500"></i> تحليلات الطلبات والمبيعات
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <!-- Total Orders -->
            <div class="bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-purple-200 group">
                <div class="w-14 h-14 md:w-16 md:h-16 bg-purple-50 text-purple-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <div class="min-w-0 text-right">
                    <p class="text-[10px] md:text-xs text-gray-500 font-bold mb-1">إجمالي الطلبات الكلية</p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight leading-none">{{ number_format($stats['total_orders']) }}</h3>
                </div>
            </div>

            <!-- Pending Orders -->
            <div class="bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-orange-200 group">
                <div class="w-14 h-14 md:w-16 md:h-16 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <div class="min-w-0 text-right">
                    <p class="text-[10px] md:text-xs text-gray-500 font-bold mb-1">طلبات بانتظار الموافقة</p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight leading-none">{{ number_format($stats['pending_orders']) }}</h3>
                </div>
            </div>

            <!-- Delivered Orders -->
            <div class="bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-emerald-200 group">
                <div class="w-14 h-14 md:w-16 md:h-16 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <div class="min-w-0 text-right">
                    <p class="text-[10px] md:text-xs text-gray-500 font-bold mb-1">الطلبات المكتملة (تم التوصيل)</p>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight leading-none">{{ number_format($stats['delivered_orders']) }}</h3>
                </div>
            </div>

            <!-- Total Order Value -->
            <div class="bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-blue-200 group">
                <div class="w-14 h-14 md:w-16 md:h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-money-bills"></i>
                </div>
                <div class="min-w-0 text-right">
                    <p class="text-[10px] md:text-xs text-gray-500 font-bold mb-1">حجم مبيعات الطلبات المكتملة</p>
                    <div class="flex items-end gap-1">
                        <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight leading-none">{{ number_format($stats['total_order_value']) }}</h3>
                        <span class="text-[10px] text-gray-500 font-bold pb-1 block">ج.م</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Recent Orders -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
            <div class="p-4 border-b border-gray-50 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-bell text-orange-500 animate-pulse"></i> أحدث الطلبات
                </h3>
                <a href="{{ route('pharmacy.orders') }}" class="text-xs text-blue-600 hover:text-blue-700 font-bold">عرض الكل</a>
            </div>
            <div class="p-0 flex-1 overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-3 font-bold text-slate-700">#ORD-{{ $order->id }}</td>
                            <td class="p-3 text-emerald-600 font-black" dir="ltr">{{ number_format($order->grand_total, 2) }} ج.م</td>
                            <td class="p-3">
                                @if($order->status == 'pending')
                                    <span class="bg-orange-50 text-orange-600 px-2 py-1 rounded text-[10px] font-bold">جديد</span>
                                @elseif($order->status == 'delivered')
                                    <span class="bg-emerald-50 text-emerald-600 px-2 py-1 rounded text-[10px] font-bold">مكتمل</span>
                                @else
                                    <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-[10px] font-bold">{{ $order->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="p-6 text-center text-gray-400">لا توجد طلبات حديثة</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Withdrawals -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
            <div class="p-4 border-b border-gray-50 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-money-bill-transfer text-[#00965e]"></i> سجل السحوبات
                </h3>
                <a href="{{ route('pharmacy.wallet') }}" class="text-xs text-blue-600 hover:text-blue-700 font-bold">المحفظة</a>
            </div>
            <div class="p-0 flex-1 overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentWithdrawals as $withdrawal)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-3 font-bold text-slate-700">{{ $withdrawal->payment_method }}</td>
                            <td class="p-3 text-[#00965e] font-black" dir="ltr">{{ number_format($withdrawal->amount, 2) }} ج.م</td>
                            <td class="p-3">
                                @if($withdrawal->status == 'pending')
                                    <span class="text-orange-500 font-bold text-[10px]"><i class="fa-solid fa-clock"></i> قيد المراجعة</span>
                                @elseif($withdrawal->status == 'approved')
                                    <span class="text-emerald-500 font-bold text-[10px]"><i class="fa-solid fa-check"></i> تم التحويل</span>
                                @else
                                    <span class="text-red-500 font-bold text-[10px]"><i class="fa-solid fa-times"></i> مرفوض</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="p-6 text-center text-gray-400">لم تقم بأي سحوبات بعد</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Latest Inventory -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
            <div class="p-4 border-b border-gray-50 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-pills text-blue-500"></i> أحدث الأدوية المضافة
                </h3>
                <a href="{{ route('pharmacy.medicines.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-bold">المخزن</a>
            </div>
            <div class="p-0 flex-1 overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentInventory as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-3 font-bold text-slate-700 truncate max-w-[120px]" title="{{ $item->medicine->name ?? 'غير متوفر' }}">
                                {{ $item->medicine->name ?? 'غير متوفر' }}
                            </td>
                            <td class="p-3 text-gray-500 font-semibold" dir="ltr">{{ number_format($item->price, 2) }} ج.م</td>
                            <td class="p-3">
                                @if($item->quantity > 0)
                                    <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-[10px] font-bold">{{ $item->quantity }} عبوة</span>
                                @else
                                    <span class="bg-red-50 text-red-600 px-2 py-1 rounded text-[10px] font-bold">نفذت الكمية</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="p-6 text-center text-gray-400">لا يوجد أدوية في المخزن</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
