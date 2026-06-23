<?php

namespace App\Http\Controllers\Dashboard\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Pharmacy;
use App\Models\PharmacyWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PharmacyOrderController extends Controller
{
    /**
     * Display a listing of the pharmacy orders.
     */
    public function index(Request $request)
    {
        try {
            $pharmacies = Pharmacy::where('user_id', auth()->id())->get();
            $pharmacyIds = $pharmacies->pluck('id')->toArray();

            if (empty($pharmacyIds)) {
                return redirect()->route('pharmacy.application.index')->with('error', 'لم يتم العثور على أي صيدلية مسجلة باسمك.');
            }

            $query = Order::with(['user', 'items.medicine'])
                ->whereIn('pharmacy_id', $pharmacyIds);

            // Filter by specific pharmacy if selected
            if ($request->filled('pharmacy_id') && in_array($request->pharmacy_id, $pharmacyIds)) {
                $query->where('pharmacy_id', $request->pharmacy_id);
            }

            // Search by order reference or phone
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('order_reference', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q2) use ($search) {
                          $q2->where('name', 'like', "%{$search}%");
                      });
                });
            }

            // Filter by order status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter by payment method
            if ($request->filled('payment_method')) {
                $query->where('payment_method', $request->payment_method);
            }

            // Filter by Date Range
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Hide pending paymob orders that are older than 30 minutes
            $query->where(function ($q) {
                $q->where('payment_method', '!=', 'paymob')
                  ->orWhere('payment_status', '!=', 'pending')
                  ->orWhere('created_at', '>=', now()->subMinutes(30));
            });

            // Calculate Analytics (Matches the applied filters)
            $statsQuery = clone $query;
            $totalOrders = $statsQuery->count();

            $revenueQuery = clone $query;
            $totalRevenue = $revenueQuery->where('status', 'delivered')->sum('grand_total');

            $pendingQuery = clone $query;
            $pendingOrders = $pendingQuery->where('status', 'pending')->count();

            $processingQuery = clone $query;
            $processingOrders = $processingQuery->where('status', 'accepted')->count();

            $deliveredQuery = clone $query;
            $deliveredOrders = $deliveredQuery->where('status', 'delivered')->count();

            $orders = $query->orderBy('created_at', 'desc')->paginate(10);

            return view('pharmacy.orders.index', compact(
                'orders',
                'totalOrders',
                'totalRevenue',
                'pendingOrders',
                'processingOrders',
                'deliveredOrders',
                'pharmacies'
            ));
        } catch (\Exception $e) {
            Log::error('PharmacyOrderController@index: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء جلب الطلبات.');
        }
    }

    /**
     * Update the status of the specified order.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,preparing,out_for_delivery,delivered,cancelled'
        ]);

        try {
            $pharmacy = Pharmacy::where('user_id', auth()->id())->first();

            // Security check: ensure the order belongs to this pharmacy
            if (!$pharmacy || $order->pharmacy_id !== $pharmacy->id) {
                return back()->with('error', 'ليس لديك صلاحية لتعديل هذا الطلب.');
            }

            $oldStatus = $order->status;

            DB::beginTransaction();

            $order->update([
                'status' => $request->status
            ]);

            // Update wallet if the order was just delivered
            if ($oldStatus !== 'delivered' && $request->status === 'delivered') {
                $wallet = PharmacyWallet::firstOrCreate(
                    ['pharmacy_id' => $order->pharmacy_id],
                    ['balance' => 0, 'total_earned' => 0]
                );

                $wallet->total_earned += $order->grand_total;

                // Only increase withdrawable balance if the customer paid online
                if ($order->payment_method === 'paymob') {
                    $wallet->balance += $order->grand_total;
                }

                $wallet->save();
            }

            DB::commit();

            return back()->with('success', "تم تحديث حالة الطلب ({$order->order_reference}) بنجاح.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PharmacyOrderController@updateStatus: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحديث حالة الطلب.');
        }
    }
}
