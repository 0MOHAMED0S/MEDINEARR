<?php

namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymobController extends Controller
{
    /**
     * Webhook Callback from Paymob to update order payment status
     */
    public function callback(Request $request)
    {
        try {
            $data = $request->all();
            
            // Log incoming webhook for debugging
            Log::info('Paymob Webhook Received: ', $data);

            if (isset($data['obj']) && isset($data['obj']['order']) && isset($data['obj']['success'])) {
                $paymobOrderId = $data['obj']['order']['id'];
                $isSuccess = $data['obj']['success'] === true || $data['obj']['success'] === 'true';

                // Find the order by paymob_order_id
                $order = Order::where('paymob_order_id', $paymobOrderId)->first();

                if ($order) {
                    // Update order transaction and status
                    $order->update([
                        'paymob_transaction_id' => $data['obj']['id'],
                        'payment_status' => $isSuccess ? 'paid' : 'failed'
                    ]);

                    if ($isSuccess) {
                        // Clear the cart items for this pharmacy and user
                        $cart = Cart::where('user_id', $order->user_id)->first();
                        if ($cart) {
                            CartItem::where('cart_id', $cart->id)
                                ->where('pharmacy_id', $order->pharmacy_id)
                                ->delete();
                        }
                    }

                    Log::info("Order {$order->id} payment status updated to {$order->payment_status}");
                } else {
                    Log::warning("Paymob Webhook: Order with paymob_order_id {$paymobOrderId} not found.");
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Paymob Webhook Exception: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Redirect Response Callback from Paymob after user finishes payment
     */
    public function responseCallback(Request $request)
    {
        $success = $request->query('success');
        
        if ($success === 'true' || $success === true) {
            return response()->json([
                'success' => true,
                'message' => 'Payment completed successfully! Your order is placed.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Payment was declined or failed. Please try again.'
        ]);
    }
}
