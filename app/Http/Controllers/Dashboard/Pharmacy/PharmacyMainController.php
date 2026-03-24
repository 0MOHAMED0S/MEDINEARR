<?php

namespace App\Http\Controllers\Dashboard\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PharmacyMainController extends Controller
{
    public function index(Request $request)
    {
        // 1. منطق الفلترة بالتاريخ (كما في الكود السابق)
        $dateFilter = $request->get('date_filter', 'all_time');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $periodLabel = "كل الأوقات"; // يمكن تطويرها حسب الفلتر

        // 2. جلب الإحصائيات (أرقام تجريبية، استبدلها ببيانات قاعدة بياناتك)
        $stats = [
            'users' => 1250,
            'new_pharmacies' => 45,
            'total_pharmacies' => 150,
            'pending_apps' => 12,
            'new_medicines' => 850,
        ];

        // 3. بيانات الخريطة والطلبات الحديثة
        $mapPharmacies = []; // اجلب بيانات الصيدليات مع (lat, lng) هنا
        $latestApplications = []; // اجلب أحدث طلبات الانضمام هنا

        return view('pharmacy.index', compact(
            'stats',
            'dateFilter',
            'startDate',
            'endDate',
            'periodLabel',
            'mapPharmacies',
            'latestApplications'
        ));
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }
}
