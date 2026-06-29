<?php

namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\PaymobService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    /**
     * الدالة الأولى: جلب ملخص الطلب (Order Summary) لصيدلية محددة
     */
    public function summary(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'pharmacy_id' => 'required|exists:pharmacies,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data'    => null
            ], 422);
        }

        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please log in to view order summary.',
                    'data'    => null
                ], 401);
            }

            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty.',
                    'data'    => null
                ], 404);
            }

            // جلب عناصر السلة الخاصة بالصيدلية المطلوبة فقط
            $cartItems = CartItem::with('medicine:id,name')->where('cart_id', $cart->id)
                ->where('pharmacy_id', $request->pharmacy_id)
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No items found in your cart for this pharmacy.',
                    'data'    => null
                ], 400);
            }

            // حساب الإجمالي والتحقق من المخزون
            $subTotal = 0;
            $outOfStockItems = [];

            foreach ($cartItems as $item) {
                $stockRecord = \App\Models\PharmacyMedicine::where('pharmacy_id', $request->pharmacy_id)
                    ->where('medicine_id', $item->medicine_id)
                    ->first();

                $availableStock = $stockRecord ? $stockRecord->quantity : 0;

                if ($item->quantity > $availableStock) {
                    $medicineName = $item->medicine ? $item->medicine->name : 'Item ID ' . $item->medicine_id;
                    $outOfStockItems[] = "{$medicineName} (Requested: {$item->quantity}, Available: {$availableStock})";
                }

                $subTotal += ($item->price * $item->quantity);
            }

            if (!empty($outOfStockItems)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some items in your cart exceed available stock.',
                    'data'    => [
                        'out_of_stock_items' => $outOfStockItems
                    ]
                ], 400);
            }

            // يمكنك إضافة ثوابت للتوصيل والضرائب هنا (مثال)
            $deliveryFee = 0.00; // قيمة افتراضية للتوصيل
            $taxRate = 0.0;       // ضريبة (مثلاً 14%) - حالياً صفر

            $taxAmount = $subTotal * $taxRate;
            $grandTotal = $subTotal + $deliveryFee + $taxAmount;

            return response()->json([
                'success' => true,
                'message' => 'Order summary retrieved successfully.',
                'data'    => [
                    'pharmacy_id'  => (int) $request->pharmacy_id,
                    'total_items'  => $cartItems->sum('quantity'),
                    'sub_total'    => round($subTotal, 2),
                    'delivery_fee' => round($deliveryFee, 2),
                    'tax_amount'   => round($taxAmount, 2),
                    'grand_total'  => round($grandTotal, 2),
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Checkout Summary Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving the summary.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }

    /**
     * الدالة الثانية: تطبيق كوبون الخصم وإرجاع القيمة الجديدة
     */
    public function applyCoupon(Request $request): JsonResponse
    {
        // 1. التحقق من المدخلات
        $validator = Validator::make($request->all(), [
            'pharmacy_id' => 'required|exists:pharmacies,id',
            'code'        => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data'    => null
            ], 422);
        }

        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please log in to apply a coupon.',
                    'data'    => null
                ], 401);
            }

            // 2. التحقق من السلة والإجمالي (نفس خطوة الـ Summary)
            $cart = Cart::where('user_id', $user->id)->first();
            $cartItems = $cart ? CartItem::with('medicine:id,name')->where('cart_id', $cart->id)->where('pharmacy_id', $request->pharmacy_id)->get() : collect();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty for this pharmacy.',
                    'data'    => null
                ], 400);
            }

            $subTotal = 0;
            $outOfStockItems = [];

            foreach ($cartItems as $item) {
                $stockRecord = \App\Models\PharmacyMedicine::where('pharmacy_id', $request->pharmacy_id)
                    ->where('medicine_id', $item->medicine_id)
                    ->first();

                $availableStock = $stockRecord ? $stockRecord->quantity : 0;

                if ($item->quantity > $availableStock) {
                    $medicineName = $item->medicine ? $item->medicine->name : 'Item ID ' . $item->medicine_id;
                    $outOfStockItems[] = "{$medicineName} (Requested: {$item->quantity}, Available: {$availableStock})";
                }

                $subTotal += ($item->price * $item->quantity);
            }

            if (!empty($outOfStockItems)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some items in your cart exceed available stock.',
                    'data'    => [
                        'out_of_stock_items' => $outOfStockItems
                    ]
                ], 400);
            }

            // 3. التحقق من الكوبون (Coupon Logic)
            $coupon = Coupon::where('code', $request->code)->first();

            // هل الكوبون موجود ومفعل؟
            if (!$coupon || !$coupon->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or inactive coupon code.',
                    'data'    => null
                ], 400);
            }

            // هل الكوبون منتهي الصلاحية؟
            if ($coupon->expiry_date && Carbon::now()->startOfDay()->gt($coupon->expiry_date)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This coupon has expired.',
                    'data'    => null
                ], 400);
            }

            // هل الكوبون تخطى الحد الأقصى للاستخدام؟
            if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
                return response()->json([
                    'success' => false,
                    'message' => 'This coupon usage limit has been reached.',
                    'data'    => null
                ], 400);
            }

            // 4. حساب الخصم
            $discountAmount = 0;

            if ($coupon->discount_type === 'percentage') {
                // خصم نسبة مئوية (مثال: 10%)
                $discountAmount = ($subTotal * $coupon->discount_value) / 100;
            } elseif ($coupon->discount_type === 'fixed') {
                // خصم مبلغ ثابت (مثال: 50 جنيه)
                $discountAmount = $coupon->discount_value;
            }

            // التأكد أن الخصم لا يتعدى قيمة المنتجات (عشان الإجمالي ميبقاش بالسالب!)
            if ($discountAmount > $subTotal) {
                $discountAmount = $subTotal;
            }

            // 5. حساب الإجمالي الجديد
            $deliveryFee = 0.00;
            $taxAmount = 0;

            $newSubTotal = $subTotal - $discountAmount;
            $grandTotal = $newSubTotal + $deliveryFee + $taxAmount;

            // 6. إرجاع الاستجابة للموبايل
            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully!',
                'data'    => [
                    'coupon_code'      => $coupon->code,
                    'coupon_title'     => $coupon->title,
                    'original_subtotal'=> round($subTotal, 2),
                    'discount_amount'  => round($discountAmount, 2),
                    'new_subtotal'     => round($newSubTotal, 2),
                    'delivery_fee'     => round($deliveryFee, 2),
                    'grand_total'      => round($grandTotal, 2),
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Apply Coupon Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while applying the coupon.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }

    /**
     * الدالة الثالثة: إتمام الطلب (Cash & Paymob)
     */
    public function placeOrder(Request $request, PaymobService $paymobService): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'pharmacy_id'    => 'required|exists:pharmacies,id',
            'payment_method' => 'required|in:cash,paymob',
            'address'        => 'required|string',
            'phone'          => 'required|string',
            'notes'          => 'nullable|string',
            'coupon_code'    => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data'    => null
            ], 422);
        }

        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please log in to place an order.',
                    'data'    => null
                ], 401);
            }

            $cart = Cart::where('user_id', $user->id)->first();
            if (!$cart) {
                return response()->json(['success' => false, 'message' => 'Your cart is empty.', 'data' => null], 404);
            }

            $cartItems = CartItem::with('medicine:id,name')->where('cart_id', $cart->id)
                ->where('pharmacy_id', $request->pharmacy_id)
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No items found in your cart for this pharmacy.', 'data' => null], 400);
            }

            $subTotal = 0;
            $outOfStockItems = [];

            foreach ($cartItems as $item) {
                $stockRecord = \App\Models\PharmacyMedicine::where('pharmacy_id', $request->pharmacy_id)
                    ->where('medicine_id', $item->medicine_id)
                    ->first();

                $availableStock = $stockRecord ? $stockRecord->quantity : 0;

                if ($item->quantity > $availableStock) {
                    $medicineName = $item->medicine ? $item->medicine->name : 'Item ID ' . $item->medicine_id;
                    $outOfStockItems[] = "{$medicineName} (Requested: {$item->quantity}, Available: {$availableStock})";
                }

                $subTotal += ($item->price * $item->quantity);
            }

            if (!empty($outOfStockItems)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some items in your cart exceed available stock.',
                    'data'    => [
                        'out_of_stock_items' => $outOfStockItems
                    ]
                ], 400);
            }

            $discountAmount = 0;
            $couponId = null;

            if ($request->filled('coupon_code')) {
                $coupon = Coupon::where('code', $request->coupon_code)->first();
                if ($coupon && $coupon->is_active && (!$coupon->expiry_date || Carbon::now()->startOfDay()->lte($coupon->expiry_date)) && ($coupon->usage_limit === null || $coupon->used_count < $coupon->usage_limit)) {
                    if ($coupon->discount_type === 'percentage') {
                        $discountAmount = ($subTotal * $coupon->discount_value) / 100;
                    } else {
                        $discountAmount = $coupon->discount_value;
                    }
                    if ($discountAmount > $subTotal) $discountAmount = $subTotal;
                    $couponId = $coupon->id;
                }
            }

            $deliveryFee = 0.00; // default delivery fee
            $grandTotal = ($subTotal - $discountAmount) + $deliveryFee;

            DB::beginTransaction();

            // Delete any existing pending order for this user and pharmacy to avoid duplicates
            $existingOrder = Order::where('user_id', $user->id)
                ->where('pharmacy_id', $request->pharmacy_id)
                ->where('payment_status', 'pending')
                ->first();

            if ($existingOrder) {
                OrderItem::where('order_id', $existingOrder->id)->delete();
                $existingOrder->delete();
            }

            $order = Order::create([
                'user_id'        => $user->id,
                'pharmacy_id'    => $request->pharmacy_id,
                'coupon_id'      => $couponId,
                'sub_total'      => $subTotal,
                'discount'       => $discountAmount,
                'delivery_fee'   => $deliveryFee,
                'grand_total'    => $grandTotal,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'status'         => 'pending',
                'phone'          => $request->phone,
                'address'        => $request->address,
                'notes'          => $request->notes,
            ]);

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id'    => $order->id,
                    'medicine_id' => $cartItem->medicine_id,
                    'quantity'    => $cartItem->quantity,
                    'price'       => $cartItem->price,
                ]);
            }

            // Remove items from cart only if cash. Paymob will clear cart on successful webhook.
            if ($request->payment_method === 'cash') {
                CartItem::where('cart_id', $cart->id)->where('pharmacy_id', $request->pharmacy_id)->delete();
            }

            if ($couponId) {
                $coupon = Coupon::find($couponId);
                $coupon->increment('used_count');
            }

            DB::commit();

            if ($request->payment_method === 'cash') {
                $pharmacyUser = \App\Models\User::where('role', 'pharmacy')
                    ->whereHas('pharmacy', function ($q) use ($order) {
                        $q->where('id', $order->pharmacy_id);
                    })->first();

                if ($pharmacyUser) {
                    $pharmacyUser->notify(new \App\Notifications\SystemNotification(
                        'طلب جديد! 📦',
                        "تم استلام طلب جديد (الدفع عند الاستلام) برقم #{$order->id} بقيمة {$order->grand_total} جنيه.",
                        'success',
                        '/pharmacy/dashboard' // Update to orders page when it exists
                    ));
                }
            }

            if ($request->payment_method === 'paymob') {
                $orderData = [
                    'amount'     => $grandTotal,
                    'reference'  => $order->order_reference,
                    'email'      => $user->email ?? 'test@example.com',
                    'first_name' => strtok($user->name, ' ') ?: 'John', // Use first word of name or John
                    'last_name'  => 'Doe', // Always provide a valid last name
                    'phone'      => $request->phone,
                ];

                $paymentResult = $paymobService->processPayment($orderData);

                if ($paymentResult && isset($paymentResult['iframe_url'])) {
                    $order->update([
                        'paymob_order_id' => $paymentResult['paymob_order_id']
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Order placed. Redirect to payment link.',
                        'data'    => [
                            'order' => $order,
                            'payment_url' => $paymentResult['iframe_url']
                        ]
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to initialize Paymob payment.',
                        'data'    => null
                    ], 500);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully via Cash on Delivery.',
                'data'    => [
                    'order' => $order
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('API Place Order Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while placing the order.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }
}
