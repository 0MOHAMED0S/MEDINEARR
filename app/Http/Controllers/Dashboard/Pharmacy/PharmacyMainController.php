<?php

namespace App\Http\Controllers\Dashboard\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\Order;
use App\Models\PharmacyWallet;
use App\Models\WithdrawalRequest;
use App\Models\PharmacyMedicine;
use Illuminate\Http\Request;

class PharmacyMainController extends Controller
{
    public function index(Request $request)
    {
        // Get all pharmacies owned by the authenticated user
        $pharmacies = Pharmacy::where('user_id', auth()->id())->get();
        $pharmacyIds = $pharmacies->pluck('id')->toArray();

        // 1. منطق الفلترة بالتاريخ (يمكنك تطويره لاحقاً ليشمل Order Filters)
        $dateFilter = $request->get('date_filter', 'all_time');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $periodLabel = "كل الأوقات";

        // 2. جلب الإحصائيات الحقيقية الخاصة بصيدليات المستخدم فقط
        $stats = [
            // Order Stats
            'total_orders'      => Order::whereIn('pharmacy_id', $pharmacyIds)->count(),
            'pending_orders'    => Order::whereIn('pharmacy_id', $pharmacyIds)->where('status', 'pending')->count(),
            'delivered_orders'  => Order::whereIn('pharmacy_id', $pharmacyIds)->where('status', 'delivered')->count(),
            'total_order_value' => Order::whereIn('pharmacy_id', $pharmacyIds)->where('status', 'delivered')->sum('grand_total'),

            // Financial Stats
            'total_earnings'      => PharmacyWallet::whereIn('pharmacy_id', $pharmacyIds)->sum('total_earned'),
            'available_balance'   => PharmacyWallet::whereIn('pharmacy_id', $pharmacyIds)->sum('balance'),
            'pending_withdrawals' => WithdrawalRequest::whereIn('pharmacy_id', $pharmacyIds)->where('status', 'pending')->sum('amount'),
            'approved_withdrawals'=> WithdrawalRequest::whereIn('pharmacy_id', $pharmacyIds)->where('status', 'approved')->sum('amount'),
        ];

        // 3. Tables Data (Recent Activities)
        $recentOrders = Order::whereIn('pharmacy_id', $pharmacyIds)->latest()->take(5)->get();
        $recentWithdrawals = WithdrawalRequest::whereIn('pharmacy_id', $pharmacyIds)->latest()->take(5)->get();
        $recentInventory = PharmacyMedicine::with('medicine')->whereIn('pharmacy_id', $pharmacyIds)->latest()->take(5)->get();

        return view('pharmacy.index', compact(
            'stats',
            'dateFilter',
            'startDate',
            'endDate',
            'periodLabel',
            'pharmacies',
            'recentOrders',
            'recentWithdrawals',
            'recentInventory'
        ));
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }
}
