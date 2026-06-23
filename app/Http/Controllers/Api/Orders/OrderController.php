<?php

namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Get all recent orders for the authenticated user with all details
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                    'data'    => null
                ], 401);
            }

            // Fetch orders with relations
            $orders = Order::with(['pharmacy:id,pharmacy_name,address,image', 'items.medicine:id,name,official_price,image'])
                ->where('user_id', $user->id)
                ->where(function ($query) {
                    // Show order if it's Cash, OR if it's Paid/Failed, OR if it's a very recent Pending order (< 30 mins)
                    $query->where('payment_method', '!=', 'paymob')
                          ->orWhere('payment_status', '!=', 'pending')
                          ->orWhere('created_at', '>=', now()->subMinutes(30));
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10); // Paginated for professional app usage

            return response()->json([
                'success' => true,
                'message' => 'Recent orders retrieved successfully.',
                'data'    => $orders
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Get Recent Orders Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving orders.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }

    /**
     * Get orders for the authenticated user in a specific pharmacy
     */
    public function pharmacyOrders(Request $request, $pharmacy_id): JsonResponse
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                    'data'    => null
                ], 401);
            }

            // Fetch orders with relations for specific pharmacy
            $orders = Order::with(['items.medicine:id,name,official_price,image'])
                ->where('user_id', $user->id)
                ->where('pharmacy_id', $pharmacy_id)
                ->where(function ($query) {
                    // Show order if it's Cash, OR if it's Paid/Failed, OR if it's a very recent Pending order (< 30 mins)
                    $query->where('payment_method', '!=', 'paymob')
                          ->orWhere('payment_status', '!=', 'pending')
                          ->orWhere('created_at', '>=', now()->subMinutes(30));
                })
                ->orderBy('created_at', 'desc')
                ->get();

            if ($orders->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No orders found for this pharmacy.',
                    'data'    => []
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pharmacy orders retrieved successfully.',
                'data'    => $orders
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Get Pharmacy Orders Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving pharmacy orders.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }
}
