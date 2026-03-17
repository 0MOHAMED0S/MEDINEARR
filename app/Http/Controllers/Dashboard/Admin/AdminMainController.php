<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pharmacy;
use App\Models\PharmacyApplication;
use App\Models\Medicine;
use Carbon\Carbon;

class AdminMainController extends Controller
{
    public function index(Request $request)
    {
        // 1. محرك الفلترة الزمني الذكي - الافتراضي أصبح "كل الأوقات"
        $dateFilter = $request->input('date_filter', 'all_time');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $queryStart = null;
        $queryEnd = null;
        $now = Carbon::now();

        switch ($dateFilter) {
            case 'today':
                $queryStart = $now->copy()->startOfDay();
                $queryEnd = $now->copy()->endOfDay();
                break;
            case 'this_week':
                $queryStart = $now->copy()->startOfWeek();
                $queryEnd = $now->copy()->endOfWeek();
                break;
            case 'this_month':
                $queryStart = $now->copy()->startOfMonth();
                $queryEnd = $now->copy()->endOfMonth();
                break;
            case 'last_month':
                $queryStart = $now->copy()->subMonth()->startOfMonth();
                $queryEnd = $now->copy()->subMonth()->endOfMonth();
                break;
            case 'this_year':
                $queryStart = $now->copy()->startOfYear();
                $queryEnd = $now->copy()->endOfYear();
                break;
            case 'last_year':
                $queryStart = $now->copy()->subYear()->startOfYear();
                $queryEnd = $now->copy()->subYear()->endOfYear();
                break;
            case 'custom':
                if ($startDate) $queryStart = Carbon::parse($startDate)->startOfDay();
                if ($endDate) $queryEnd = Carbon::parse($endDate)->endOfDay();
                break;
            case 'all_time':
            default:
                // لا يوجد فلتر زمني (جلب الكل)
                break;
        }

        // دالة مساعدة لتطبيق الفلتر الزمني على أي Query
        $applyDateFilter = function ($query) use ($queryStart, $queryEnd) {
            if ($queryStart) $query->where('created_at', '>=', $queryStart);
            if ($queryEnd) $query->where('created_at', '<=', $queryEnd);
            return $query;
        };

        // 2. حساب الإحصائيات بناءً على الفترة الزمنية المحددة
        $stats = [
            'users'             => $applyDateFilter(User::where('role', 'user'))->count(),
            'total_pharmacies'  => Pharmacy::count(), // هذا الرقم الكلي يظل ثابتاً للمقارنة
            'new_pharmacies'    => $applyDateFilter(Pharmacy::query())->count(),
            'pending_apps'      => $applyDateFilter(PharmacyApplication::where('status', 'under_review'))->count(),
            'new_medicines'     => $applyDateFilter(Medicine::query())->count(),
        ];

        $periodLabel = $this->getPeriodLabel($dateFilter, $queryStart, $queryEnd);

        // 3. بيانات الخريطة (جلب جميع الصيدليات النشطة لعرض الدبابيس)
        $mapPharmacies = Pharmacy::where('is_active', 1)
            ->select('id', 'pharmacy_name', 'owner_name', 'phone', 'email', 'city', 'address', 'lat', 'lng', 'is_big_pharmacy', 'image', 'created_at')
            ->get();

        // 4. أحدث الطلبات لجدول النشاطات السريعة
        $latestApplications = PharmacyApplication::latest()->take(5)->get();

        return view('dashboard.index', compact('stats', 'periodLabel', 'dateFilter', 'startDate', 'endDate', 'mapPharmacies', 'latestApplications'));
    }

    private function getPeriodLabel($filter, $start, $end)
    {
        $labels = [
            'today' => 'اليوم',
            'this_week' => 'هذا الأسبوع',
            'this_month' => 'هذا الشهر',
            'last_month' => 'الشهر الماضي',
            'this_year' => 'هذا العام',
            'last_year' => 'العام الماضي',
            'all_time' => 'إجمالي النظام (كل الأوقات)',
        ];

        if ($filter === 'custom') {
            if ($start && $end) return "من " . $start->format('Y-m-d') . " إلى " . $end->format('Y-m-d');
            if ($start) return "من " . $start->format('Y-m-d') . " إلى الآن";
            if ($end) return "حتى " . $end->format('Y-m-d');
        }

        return $labels[$filter] ?? 'إجمالي النظام (كل الأوقات)';
    }
}
