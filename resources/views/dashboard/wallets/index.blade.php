@extends('dashboard.layout.master')

@section('title', 'مَحافظ الصيدليات')

@section('content')
<div class="p-4 md:p-6 lg:p-8 font-cairo">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">مَحافظ الصيدليات</h1>
            <p class="text-sm text-gray-500 mt-1">تتبع الأرصدة الحالية وإجمالي أرباح جميع الصيدليات على المنصة.</p>
        </div>
    </div>

    <!-- Premium KPI Analytics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        
        <!-- Total Active Wallets -->
        <div class="bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-purple-200 group">
            <div class="w-14 h-14 md:w-16 md:h-16 bg-purple-50 text-purple-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div class="min-w-0 text-right">
                <p class="text-[10px] md:text-xs text-gray-500 font-bold mb-1">إجمالي المحافظ النشطة</p>
                <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight leading-none">{{ number_format($totalActiveWallets) }}</h3>
            </div>
        </div>

        <!-- Total Platform Debt (Balance owed) -->
        <div class="bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-blue-200 group">
            <div class="w-14 h-14 md:w-16 md:h-16 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-money-check-dollar"></i>
            </div>
            <div class="min-w-0 text-right">
                <p class="text-[10px] md:text-xs text-gray-500 font-bold mb-1">إجمالي الأرصدة المستحقة</p>
                <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight leading-none">{{ number_format($totalPlatformDebt, 2) }}</h3>
            </div>
        </div>

        <!-- Total Earnings -->
        <div class="bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-emerald-200 group">
            <div class="w-14 h-14 md:w-16 md:h-16 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <div class="min-w-0 text-right">
                <p class="text-[10px] md:text-xs text-gray-500 font-bold mb-1">إجمالي أرباح الصيدليات</p>
                <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight leading-none">{{ number_format($totalPlatformEarnings, 2) }}</h3>
            </div>
        </div>

        <!-- Pending Withdrawals -->
        <div class="bg-white p-5 md:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 md:gap-5 transition-all hover:shadow-md hover:border-orange-200 group">
            <div class="w-14 h-14 md:w-16 md:h-16 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center text-2xl md:text-3xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-money-bill-transfer"></i>
            </div>
            <div class="min-w-0 text-right">
                <p class="text-[10px] md:text-xs text-gray-500 font-bold mb-1">طلبات السحب المعلقة</p>
                <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight leading-none">{{ number_format($totalPendingWithdrawals) }}</h3>
            </div>
        </div>

    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
        <form action="{{ route('admin.wallets.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">البحث برقم الهاتف أو اسم الصيدلية</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث..."
                        class="w-full pl-3 pr-10 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#00965e]/20 focus:border-[#00965e] outline-none transition-all text-sm">
                    <i class="fa-solid fa-search absolute right-3 top-2.5 text-gray-400"></i>
                </div>
            </div>

            <div class="md:w-1/4">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">الترتيب حسب</label>
                <select name="sort" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#00965e]/20 focus:border-[#00965e] outline-none transition-all text-sm bg-white" onchange="this.form.submit()">
                    <option value="balance_desc" {{ request('sort') == 'balance_desc' ? 'selected' : '' }}>الأعلى رصيداً (الافتراضي)</option>
                    <option value="balance_asc" {{ request('sort') == 'balance_asc' ? 'selected' : '' }}>الأقل رصيداً</option>
                    <option value="earned_desc" {{ request('sort') == 'earned_desc' ? 'selected' : '' }}>الأعلى ربحاً (إجمالي)</option>
                    <option value="earned_asc" {{ request('sort') == 'earned_asc' ? 'selected' : '' }}>الأقل ربحاً (إجمالي)</option>
                </select>
            </div>

            <div class="md:w-auto flex items-end">
                <button type="submit" class="w-full bg-[#00965e] hover:bg-[#007b4d] text-white px-6 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm">
                    تصفية
                </button>
            </div>
            @if(request()->hasAny(['search', 'sort']))
                <div class="md:w-auto flex items-end">
                    <a href="{{ route('admin.wallets.index') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-semibold transition-all text-center">
                        إلغاء
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="p-4 font-bold text-gray-600">الصيدلية</th>
                        <th class="p-4 font-bold text-gray-600">الرصيد المتاح (للسحب)</th>
                        <th class="p-4 font-bold text-gray-600">إجمالي الأرباح (تراكمي)</th>
                        <th class="p-4 font-bold text-gray-600">آخر تحديث</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($wallets as $wallet)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-[#00965e] flex items-center justify-center font-bold text-lg shrink-0">
                                        {{ mb_substr($wallet->pharmacy->pharmacy_name ?? 'ص', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $wallet->pharmacy->pharmacy_name ?? 'صيدلية محذوفة' }}</p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-[11px] text-gray-500 font-medium">
                                                <i class="fa-solid fa-phone text-gray-400 ml-1"></i>
                                                <span dir="ltr">{{ $wallet->pharmacy->phone ?? 'غير متوفر' }}</span>
                                            </span>
                                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                            <span class="text-[11px] text-gray-500 font-medium">
                                                <i class="fa-solid fa-location-dot text-gray-400 ml-1"></i>
                                                {{ $wallet->pharmacy->city ?? 'غير محدد' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg {{ $wallet->balance > 0 ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-600' }} font-bold">
                                    {{ number_format($wallet->balance, 2) }} <span class="text-[10px]">ج.م</span>
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg {{ $wallet->total_earned > 0 ? 'bg-emerald-50 text-[#00965e]' : 'bg-gray-100 text-gray-600' }} font-bold">
                                    <i class="fa-solid fa-arrow-trend-up text-xs"></i>
                                    {{ number_format($wallet->total_earned, 2) }} <span class="text-[10px]">ج.م</span>
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="text-xs text-gray-500 font-medium">
                                    <p>{{ $wallet->updated_at->format('Y-m-d') }}</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ $wallet->updated_at->format('h:i A') }}</p>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 text-2xl mb-3">
                                        <i class="fa-solid fa-wallet"></i>
                                    </div>
                                    <p class="text-gray-500 font-bold mb-1">لا توجد محافظ صيدليات حتى الآن</p>
                                    <p class="text-sm text-gray-400">لم يتم العثور على أي بيانات تطابق بحثك.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($wallets->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $wallets->links('pagination::tailwind') }}
            </div>
        @endif
    </div>

</div>
@endsection
