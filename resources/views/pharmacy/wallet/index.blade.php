@extends('pharmacy.layout.master')

@section('title', 'المحفظة والأرباح')

@section('content')
<div class="p-4 md:p-6 lg:p-8 font-cairo">

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">المحفظة المالية</h1>
            <p class="text-sm text-gray-500 mt-1">إدارة الأرباح، عمليات التحويل، وكشف الحساب.</p>
        </div>
        <button onclick="document.getElementById('withdrawModal').classList.remove('hidden')" class="bg-[#00965e] hover:bg-[#007b4d] text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-[#00965e]/20 transition-all flex items-center gap-2">
            <i class="fa-solid fa-money-bill-transfer"></i>
            طلب سحب رصيد جديد
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-[#00965e] to-[#00b370] rounded-2xl p-6 text-white shadow-xl shadow-[#00965e]/20 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-white/80 text-xs font-semibold uppercase tracking-wider mb-1">الرصيد المتاح للسحب</p>
                <h2 class="text-4xl font-bold">{{ number_format($wallet->balance, 2) }} <span class="text-xl font-normal">ج.م</span></h2>
            </div>
            <i class="fa-solid fa-wallet absolute -left-2 -bottom-2 text-white/10 text-8xl"></i>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">إجمالي الأرباح الكلية</p>
            <h2 class="text-3xl font-bold text-gray-800">{{ number_format($wallet->total_earned, 2) }} <span class="text-lg text-gray-400 font-normal">ج.م</span></h2>
            <div class="mt-4 text-xs text-emerald-600 bg-emerald-50 w-fit px-3 py-1 rounded-full font-bold">
                +12% زيادة هذا الشهر
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
            <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">عدد عمليات الدفع (أونلاين)</p>
            <h2 class="text-3xl font-bold text-gray-800">{{ $onlinePaymentsCount }} <span class="text-lg text-gray-400 font-normal">عملية</span></h2>
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
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="py-4 px-6 text-gray-800 font-bold group-hover:text-[#00965e] transition-colors">#{{ $transaction->order_reference }}</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 px-2 py-1 rounded-md text-xs font-bold border border-emerald-100">
                                <i class="fa-solid fa-arrow-down text-[10px]"></i> مبيعات طلب
                            </span>
                        </td>
                        <td class="py-4 px-6 text-gray-500 font-medium">{{ $transaction->updated_at->format('d M, Y') }}</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-[10px] font-bold bg-green-50 text-green-600 border border-green-100">
                                <i class="fa-solid fa-check-circle"></i> مكتمل
                            </span>
                        </td>
                        <td class="py-4 px-6 font-black text-gray-800 text-left" dir="ltr">
                            +{{ number_format($transaction->grand_total, 2) }} ج.م
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500 text-sm">لا توجد حركات مالية حتى الآن.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            <div class="flex items-center justify-between text-sm text-gray-600">
                <span>عرض {{ $transactions->firstItem() ?? 0 }} إلى {{ $transactions->lastItem() ?? 0 }} من أصل {{ $transactions->total() }} حركة</span>
                <div class="flex gap-1" dir="ltr">
                    {{ $transactions->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Withdrawal Requests Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-8">
        <div class="p-6 border-b border-gray-50">
            <h3 class="font-bold text-gray-800 text-lg">طلبات سحب الرصيد</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">رقم الطلب</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">التاريخ</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">طريقة السحب</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">الحساب</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">الحالة</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-left">المبلغ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($withdrawals as $withdrawal)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="py-4 px-6 text-gray-800 font-bold group-hover:text-[#00965e] transition-colors">#WREQ-{{ $withdrawal->id }}</td>
                        <td class="py-4 px-6 text-gray-500 font-medium">{{ $withdrawal->created_at->format('d M, Y - h:i A') }}</td>
                        <td class="py-4 px-6 text-gray-600 font-bold">{{ $withdrawal->payment_method }}</td>
                        <td class="py-4 px-6 text-gray-500 text-xs" dir="ltr">{{ $withdrawal->account_details }}</td>
                        <td class="py-4 px-6">
                            @if($withdrawal->status == 'pending')
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-[10px] font-bold bg-yellow-50 text-yellow-600 border border-yellow-100">
                                    <i class="fa-solid fa-clock"></i> قيد المراجعة
                                </span>
                            @elseif($withdrawal->status == 'approved')
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-[10px] font-bold bg-green-50 text-green-600 border border-green-100">
                                    <i class="fa-solid fa-check-circle"></i> تم التحويل
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-[10px] font-bold bg-red-50 text-red-600 border border-red-100">
                                    <i class="fa-solid fa-times-circle"></i> مرفوض
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 font-black text-red-500 text-left" dir="ltr">
                            -{{ number_format($withdrawal->amount, 2) }} ج.م
                        </td>
                    </tr>
                    @if($withdrawal->admin_notes && $withdrawal->status == 'rejected')
                    <tr class="bg-red-50/30">
                        <td colspan="6" class="py-3 px-6 text-xs text-red-600 font-semibold border-b border-gray-50">
                            سبب الرفض: {{ $withdrawal->admin_notes }}
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500 text-sm">لا توجد طلبات سحب سابقة.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            <div class="flex items-center justify-between text-sm text-gray-600">
                <span>عرض {{ $withdrawals->firstItem() ?? 0 }} إلى {{ $withdrawals->lastItem() ?? 0 }} من أصل {{ $withdrawals->total() }} طلب</span>
                <div class="flex gap-1" dir="ltr">
                    {{ $withdrawals->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Withdraw Modal -->
<div id="withdrawModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('withdrawModal').classList.add('hidden')"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-2xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form action="{{ route('pharmacy.wallet.withdraw') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start flex-row-reverse">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-right w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                                طلب سحب رصيد
                            </h3>
                            
                            @if($errors->any())
                                <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4">
                                    <ul class="list-disc list-inside">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">المبلغ المراد سحبه (ج.م)</label>
                                <input type="number" name="amount" step="0.01" min="100" max="{{ $wallet->balance }}" required
                                       class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#00965e]/20 focus:border-[#00965e] outline-none transition-all text-sm"
                                       placeholder="مثال: 500">
                                <p class="text-xs text-gray-400 mt-1">الحد الأدنى للسحب: 100 ج.م | الرصيد المتاح: {{ number_format($wallet->balance, 2) }} ج.م</p>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">طريقة السحب</label>
                                <select name="payment_method" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#00965e]/20 focus:border-[#00965e] outline-none transition-all text-sm">
                                    <option value="" disabled selected>اختر طريقة السحب...</option>
                                    <option value="Vodafone Cash">فودافون كاش (Vodafone Cash)</option>
                                    <option value="InstaPay">انستا باي (InstaPay)</option>
                                    <option value="Bank Account">تحويل بنكي (Bank Account)</option>
                                </select>
                            </div>

                            <div class="mb-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">تفاصيل الحساب (رقم الهاتف أو الحساب البنكي)</label>
                                <input type="text" name="account_details" required
                                       class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#00965e]/20 focus:border-[#00965e] outline-none transition-all text-sm"
                                       placeholder="أدخل رقم الهاتف أو الحساب البنكي...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-2 bg-[#00965e] text-base font-medium text-white hover:bg-[#007b4d] focus:outline-none sm:w-auto sm:text-sm transition-all">
                        تأكيد طلب السحب
                    </button>
                    <button type="button" onclick="document.getElementById('withdrawModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm transition-all">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any())
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('withdrawModal').classList.remove('hidden');
    });
</script>
@endif

@endsection
