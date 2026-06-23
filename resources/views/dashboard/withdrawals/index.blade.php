@extends('dashboard.layout.master')

@section('title', 'إدارة طلبات السحب')

@section('content')
<!-- Include ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="p-4 md:p-6 lg:p-8 font-cairo">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">التحليل المالي لطلبات السحب</h1>
            <p class="text-sm text-gray-500 mt-1">نظرة عامة على المبالغ المستحقة والمدفوعة للصيدليات.</p>
        </div>
    </div>

    <!-- Advanced Financial Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        
        <!-- Total All-Time Earnings -->
        <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl shadow-lg shadow-indigo-500/20 p-6 text-white relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-white/80 text-[11px] font-bold uppercase tracking-widest mb-1">إجمالي أرباح المنصة (للصيدليات)</p>
                <h3 class="text-3xl font-black mb-2">{{ number_format($totalPlatformEarnings, 2) }} <span class="text-sm font-normal">ج.م</span></h3>
                <p class="text-white/90 text-[11px]"><i class="fa-solid fa-chart-line"></i> الأرباح الكلية منذ البداية</p>
            </div>
            <i class="fa-solid fa-vault absolute -left-4 -bottom-4 text-white/10 text-8xl group-hover:scale-110 transition-transform duration-500"></i>
        </div>

        <!-- Unrequested (Available) Balances -->
        <div class="bg-gradient-to-br from-cyan-500 to-blue-500 rounded-2xl shadow-lg shadow-cyan-500/20 p-6 text-white relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-white/80 text-[11px] font-bold uppercase tracking-widest mb-1">أرصدة لم يتم سحبها</p>
                <h3 class="text-3xl font-black mb-2">{{ number_format($totalAvailableBalances, 2) }} <span class="text-sm font-normal">ج.م</span></h3>
                <p class="text-white/90 text-[11px]"><i class="fa-solid fa-wallet"></i> أموال متاحة في محافظ الصيدليات</p>
            </div>
            <i class="fa-solid fa-piggy-bank absolute -left-4 -bottom-4 text-white/10 text-8xl group-hover:scale-110 transition-transform duration-500"></i>
        </div>

        <!-- Pending Amount -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg shadow-orange-500/20 p-6 text-white relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-white/80 text-[11px] font-bold uppercase tracking-widest mb-1">مبالغ مسحوبة (قيد المراجعة)</p>
                <h3 class="text-3xl font-black mb-2">{{ number_format($totalPendingAmount, 2) }} <span class="text-sm font-normal">ج.م</span></h3>
                <p class="text-white/90 text-[11px]"><i class="fa-solid fa-file-invoice"></i> {{ number_format($pendingCount) }} طلب معلق</p>
            </div>
            <i class="fa-solid fa-clock-rotate-left absolute -left-4 -bottom-4 text-white/10 text-8xl group-hover:scale-110 transition-transform duration-500"></i>
        </div>

        <!-- Approved Amount -->
        <div class="bg-gradient-to-br from-[#00965e] to-[#007b4d] rounded-2xl shadow-lg shadow-[#00965e]/20 p-6 text-white relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-white/80 text-[11px] font-bold uppercase tracking-widest mb-1">إجمالي المبالغ المحولة</p>
                <h3 class="text-3xl font-black mb-2">{{ number_format($totalApprovedAmount, 2) }} <span class="text-sm font-normal">ج.م</span></h3>
                <p class="text-white/90 text-[11px]"><i class="fa-solid fa-check-double"></i> {{ number_format($approvedCount) }} طلب مكتمل</p>
            </div>
            <i class="fa-solid fa-money-bill-trend-up absolute -left-4 -bottom-4 text-white/10 text-8xl group-hover:scale-110 transition-transform duration-500"></i>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Rejected Count -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between group hover:border-red-200 transition-colors h-full">
            <div>
                <p class="text-xs font-bold text-gray-500 mb-1">طلبات مرفوضة</p>
                <h3 class="text-3xl font-black text-red-600">{{ number_format($rejectedCount) }}</h3>
                <p class="text-gray-400 text-xs mt-1">تم استرجاع الرصيد لها تلقائياً</p>
            </div>
            <div class="w-14 h-14 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-red-500 group-hover:text-white transition-all">
                <i class="fa-solid fa-ban"></i>
            </div>
        </div>

        <!-- Trend Chart Mini -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 lg:col-span-2">
            <p class="text-xs font-bold text-gray-500 mb-2 px-2">مؤشر التحويلات (7 أيام)</p>
            <div id="payoutTrendChart" class="w-full h-20"></div>
        </div>
    </div>

    <!-- Filters & Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="font-bold text-gray-800 text-lg">سجل طلبات السحب</h3>
            
            <form action="{{ route('admin.withdrawals.index') }}" method="GET" class="flex items-center gap-3 w-full md:w-auto">
                <select name="status" class="px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#00965e]/20 focus:border-[#00965e] outline-none transition-all text-sm appearance-none bg-gray-50 font-semibold text-gray-700 w-full md:w-48">
                    <option value="">جميع الحالات</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد المراجعة فقط</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>تم التحويل فقط</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض فقط</option>
                </select>
                <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white px-5 py-2 rounded-xl text-sm font-bold shadow-lg shadow-gray-200 transition-all">
                    تصفية
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">رقم الطلب</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">الصيدلية</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">المبلغ</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">طريقة السحب & الحساب</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">التاريخ</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">الحالة</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($withdrawals as $withdrawal)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="py-4 px-6 text-gray-800 font-black">#WREQ-{{ $withdrawal->id }}</td>
                        <td class="py-4 px-6">
                            <div class="font-bold text-gray-800">{{ $withdrawal->pharmacy->pharmacy_name ?? 'غير معروف' }}</div>
                            <div class="text-xs text-gray-500 mt-0.5"><i class="fa-solid fa-user-tie text-gray-300"></i> {{ $withdrawal->pharmacy->user->name ?? '' }}</div>
                        </td>
                        <td class="py-4 px-6 font-black text-emerald-600 text-base" dir="ltr">{{ number_format($withdrawal->amount, 2) }} ج.م</td>
                        <td class="py-4 px-6">
                            <span class="inline-block px-2 py-1 bg-gray-100 text-gray-700 rounded text-[10px] font-bold mb-1">{{ $withdrawal->payment_method }}</span>
                            <div class="text-sm font-semibold text-gray-800" dir="ltr">{{ $withdrawal->account_details }}</div>
                        </td>
                        <td class="py-4 px-6 text-gray-500 font-medium text-xs">{{ $withdrawal->created_at->format('d M, Y - h:i A') }}</td>
                        <td class="py-4 px-6">
                            @if($withdrawal->status == 'pending')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-bold bg-orange-50 text-orange-600 border border-orange-100 shadow-sm">
                                    <i class="fa-solid fa-clock fa-spin fa-spin-reverse" style="--fa-animation-duration: 3s;"></i> قيد المراجعة
                                </span>
                            @elseif($withdrawal->status == 'approved')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-sm">
                                    <i class="fa-solid fa-check-double"></i> تم التحويل
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-bold bg-red-50 text-red-600 border border-red-100 shadow-sm">
                                    <i class="fa-solid fa-ban"></i> مرفوض
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($withdrawal->status == 'pending')
                                <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <!-- Approve Button -->
                                    <form action="{{ route('admin.withdrawals.approve', $withdrawal->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الموافقة؟ يجب أن تكون قد أرسلت المبلغ للصيدلية بالفعل.');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all shadow-sm" title="موافقة وتم التحويل">
                                            <i class="fa-solid fa-check"></i> موافقة
                                        </button>
                                    </form>
                                    
                                    <!-- Reject Button -->
                                    <button type="button" onclick="rejectWithdrawal({{ $withdrawal->id }})" class="bg-red-50 text-red-600 hover:bg-red-500 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all shadow-sm" title="رفض واسترجاع الرصيد">
                                        <i class="fa-solid fa-times"></i> رفض
                                    </button>

                                    <form id="reject-form-{{ $withdrawal->id }}" action="{{ route('admin.withdrawals.reject', $withdrawal->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="admin_notes" id="admin-notes-{{ $withdrawal->id }}">
                                    </form>
                                </div>
                            @elseif($withdrawal->status == 'rejected')
                                <button type="button" class="text-xs text-red-400 hover:text-red-600 font-semibold underline decoration-dotted underline-offset-4" title="سبب الرفض: {{ $withdrawal->admin_notes }}" onclick="alert('سبب الرفض:\n{{ $withdrawal->admin_notes }}')">
                                    عرض سبب الرفض
                                </button>
                            @else
                                <span class="text-xs text-emerald-400 font-bold"><i class="fa-solid fa-check"></i> مكتمل</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                                <i class="fa-solid fa-inbox text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-500 font-bold">لا توجد طلبات سحب تطابق الفلتر الحالي.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-100 bg-gray-50/30">
            <div class="flex gap-1" dir="ltr">
                {{ $withdrawals->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

<script>
    function rejectWithdrawal(id) {
        let reason = prompt('الرجاء إدخال سبب الرفض (سيظهر للصيدلية وسيتم استرجاع الرصيد تلقائياً):');
        if (reason != null && reason.trim() !== '') {
            document.getElementById('admin-notes-' + id).value = reason;
            document.getElementById('reject-form-' + id).submit();
        } else if (reason !== null) {
            alert('يجب كتابة سبب الرفض!');
        }
    }

    // ApexCharts Initialization for Payout Trends
    document.addEventListener("DOMContentLoaded", function() {
        var options = {
            series: [{
                name: 'التحويلات (ج.م)',
                data: @json($chartData)
            }],
            chart: {
                type: 'area',
                height: 80,
                sparkline: { enabled: true },
                fontFamily: 'Cairo, sans-serif'
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0,
                    stops: [0, 100]
                }
            },
            colors: ['#00965e'],
            tooltip: {
                fixed: { enabled: false },
                x: { show: false },
                y: {
                    title: {
                        formatter: function (seriesName) {
                            return 'المبلغ: '
                        }
                    }
                },
                marker: { show: false }
            }
        };

        var chart = new ApexCharts(document.querySelector("#payoutTrendChart"), options);
        chart.render();
    });
</script>
@endsection
