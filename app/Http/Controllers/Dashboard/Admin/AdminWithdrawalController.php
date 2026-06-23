<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\PharmacyWallet;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminWithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $query = WithdrawalRequest::with('pharmacy');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->orderBy('created_at', 'desc')->paginate(10);

        $pendingCount = WithdrawalRequest::where('status', 'pending')->count();
        $approvedCount = WithdrawalRequest::where('status', 'approved')->count();
        $rejectedCount = WithdrawalRequest::where('status', 'rejected')->count();

        // Advanced Financial Analysis
        $totalPendingAmount = WithdrawalRequest::where('status', 'pending')->sum('amount');
        $totalApprovedAmount = WithdrawalRequest::where('status', 'approved')->sum('amount');
        
        // Platform wide stats
        $totalPlatformEarnings = PharmacyWallet::sum('total_earned');
        $totalAvailableBalances = PharmacyWallet::sum('balance'); // Unrequested money sitting in wallets

        // Payout Trends (Last 7 Days) for Chart
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $last7Days->push(Carbon::today()->subDays($i)->format('Y-m-d'));
        }

        $payoutTrends = WithdrawalRequest::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(amount) as total')
        )
        ->where('status', 'approved')
        ->where('created_at', '>=', Carbon::today()->subDays(6))
        ->groupBy(DB::raw('DATE(created_at)'))
        ->get()
        ->keyBy('date');

        $chartDates = $last7Days->map(fn($date) => Carbon::parse($date)->format('M d'))->toArray();
        $chartData = $last7Days->map(fn($date) => $payoutTrends->has($date) ? $payoutTrends[$date]->total : 0)->toArray();

        return view('dashboard.withdrawals.index', compact(
            'withdrawals', 'pendingCount', 'approvedCount', 'rejectedCount',
            'totalPendingAmount', 'totalApprovedAmount', 'chartDates', 'chartData',
            'totalPlatformEarnings', 'totalAvailableBalances'
        ));
    }

    public function approve(Request $request, WithdrawalRequest $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'لا يمكن تعديل حالة هذا الطلب لأنه ليس قيد المراجعة.');
        }

        try {
            $withdrawal->update(['status' => 'approved']);
            return back()->with('success', "تم الموافقة على طلب سحب الرصيد رقم WREQ-{$withdrawal->id} بنجاح.");
        } catch (\Exception $e) {
            Log::error('AdminWithdrawalController@approve: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء الموافقة على الطلب.');
        }
    }

    public function reject(Request $request, WithdrawalRequest $withdrawal)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500'
        ], [
            'admin_notes.required' => 'يرجى إدخال سبب الرفض.'
        ]);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'لا يمكن تعديل حالة هذا الطلب لأنه ليس قيد المراجعة.');
        }

        try {
            DB::beginTransaction();

            $withdrawal->update([
                'status' => 'rejected',
                'admin_notes' => $request->admin_notes
            ]);

            // Refund the balance to the pharmacy
            $wallet = PharmacyWallet::where('pharmacy_id', $withdrawal->pharmacy_id)->first();
            if ($wallet) {
                $wallet->balance += $withdrawal->amount;
                $wallet->save();
            }

            DB::commit();

            return back()->with('success', "تم رفض طلب السحب رقم WREQ-{$withdrawal->id} وتم استرجاع الرصيد للمحفظة بنجاح.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('AdminWithdrawalController@reject: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء رفض الطلب.');
        }
    }
}
