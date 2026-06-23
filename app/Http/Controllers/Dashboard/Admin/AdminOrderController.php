<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of all platform orders.
     */
    public function index(Request $request)
    {
        try {
            $query = Order::with(['user', 'pharmacy', 'items.medicine']);

            // Filter by Pharmacy ID
            if ($request->filled('pharmacy_id')) {
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
            $pharmacies = Pharmacy::all();

            return view('dashboard.orders.index', compact('orders', 'totalOrders', 'totalRevenue', 'pendingOrders', 'processingOrders', 'deliveredOrders', 'pharmacies'));
        } catch (\Exception $e) {
            Log::error('AdminOrderController@index: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء جلب الطلبات.');
        }
    }
}
