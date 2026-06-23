<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\PharmacyWallet;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminWalletController extends Controller
{
    /**
     * Display a listing of all pharmacy wallets.
     */
    public function index(Request $request)
    {
        try {
            $query = PharmacyWallet::with('pharmacy');

            // Search by pharmacy name or phone
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('pharmacy', function ($q) use ($search) {
                    $q->where('pharmacy_name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            // Sort logic
            $sort = $request->input('sort', 'balance_desc');
            switch ($sort) {
                case 'balance_asc':
                    $query->orderBy('balance', 'asc');
                    break;
                case 'earned_desc':
                    $query->orderBy('total_earned', 'desc');
                    break;
                case 'earned_asc':
                    $query->orderBy('total_earned', 'asc');
                    break;
                case 'balance_desc':
                default:
                    $query->orderBy('balance', 'desc');
                    break;
            }

            // Calculate Key Performance Indicators (KPIs)
            $totalPlatformDebt = PharmacyWallet::sum('balance'); // Total available balance owed to pharmacies
            $totalPlatformEarnings = PharmacyWallet::sum('total_earned'); // Lifetime earnings of all pharmacies
            $totalActiveWallets = PharmacyWallet::count();
            $totalPendingWithdrawals = WithdrawalRequest::where('status', 'pending')->count();

            $wallets = $query->paginate(15)->withQueryString();

            return view('dashboard.wallets.index', compact(
                'wallets',
                'totalPlatformDebt',
                'totalPlatformEarnings',
                'totalActiveWallets',
                'totalPendingWithdrawals'
            ));
        } catch (\Exception $e) {
            Log::error('AdminWalletController@index Error: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء جلب بيانات المحافظ: ' . $e->getMessage());
        }
    }
}
