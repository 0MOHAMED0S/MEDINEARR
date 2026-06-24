<?php

namespace App\Http\Controllers\Dashboard\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Pharmacy;
use App\Models\PharmacyWallet;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PharmacyWalletController extends Controller
{
    public function index()
    {
        try {
            $pharmacy = Pharmacy::where('user_id', auth()->id())->first();

            if (!$pharmacy) {
                return redirect()->route('pharmacy.application.index')->with('error', 'لم يتم العثور على الصيدلية الخاصة بك.');
            }

            $wallet = PharmacyWallet::firstOrCreate(
                ['pharmacy_id' => $pharmacy->id],
                ['balance' => 0, 'total_earned' => 0]
            );

            // Number of online payments (Paymob Delivered Orders)
            $onlinePaymentsCount = Order::where('pharmacy_id', $pharmacy->id)
                ->where('payment_method', 'paymob')
                ->where('status', 'delivered')
                ->count();

            // Since there isn't a dedicated transactions table, we'll use recent online completed orders
            $transactions = Order::with('user')
                ->where('pharmacy_id', $pharmacy->id)
                ->where('payment_method', 'paymob')
                ->where('status', 'delivered')
                ->orderBy('updated_at', 'desc')
                ->paginate(10, ['*'], 'transactions_page');

            // Withdrawal Requests
            $withdrawals = WithdrawalRequest::where('pharmacy_id', $pharmacy->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'withdrawals_page');

            return view('pharmacy.wallet.index', compact('wallet', 'onlinePaymentsCount', 'transactions', 'withdrawals'));

        } catch (\Exception $e) {
            Log::error('PharmacyWalletController@index: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل بيانات المحفظة.');
        }
    }

    public function requestWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'payment_method' => 'required|string|in:Vodafone Cash,InstaPay,Bank Account',
            'account_details' => 'required|string|max:255',
        ], [
            'amount.min' => 'الحد الأدنى للسحب هو 100 ج.م.',
            'amount.numeric' => 'يرجى إدخال مبلغ صحيح.',
            'payment_method.in' => 'طريقة السحب غير صالحة.'
        ]);

        try {
            $pharmacy = Pharmacy::where('user_id', auth()->id())->first();

            if (!$pharmacy) {
                return back()->with('error', 'الصيدلية غير موجودة.');
            }

            $wallet = PharmacyWallet::where('pharmacy_id', $pharmacy->id)->first();

            if (!$wallet || $wallet->balance < $request->amount) {
                return back()->withErrors(['amount' => 'رصيدك الحالي لا يكفي لإتمام عملية السحب.'])->withInput();
            }

            DB::beginTransaction();

            // Deduct the requested amount from the balance immediately to prevent double spending
            $wallet->balance -= $request->amount;
            $wallet->save();

            WithdrawalRequest::create([
                'pharmacy_id' => $pharmacy->id,
                'amount' => $request->amount,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'account_details' => $request->account_details,
            ]);

            DB::commit();

            // Notify Admins
            $admins = \App\Models\User::where('role', 'admin')->get();
            if ($admins->count() > 0) {
                \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\SystemNotification(
                    'طلب سحب رصيد 💰',
                    "طلب سحب جديد بقيمة {$request->amount} ج.م من صيدلية {$pharmacy->pharmacy_name}.",
                    'warning',
                    '/admin/withdrawals'
                ));
            }

            return back()->with('success', 'تم تقديم طلب السحب بنجاح. سيتم مراجعة الطلب وتحويل المبلغ قريباً.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PharmacyWalletController@requestWithdrawal: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء معالجة طلب السحب.');
        }
    }
}
