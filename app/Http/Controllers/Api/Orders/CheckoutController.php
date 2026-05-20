<?php

namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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
            $cartItems = CartItem::where('cart_id', $cart->id)
                ->where('pharmacy_id', $request->pharmacy_id)
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No items found in your cart for this pharmacy.',
                    'data'    => null
                ], 400);
            }

            // حساب الإجمالي
            $subTotal = 0;
            foreach ($cartItems as $item) {
                $subTotal += ($item->price * $item->quantity);
            }

            // يمكنك إضافة ثوابت للتوصيل والضرائب هنا (مثال)
            $deliveryFee = 15.00; // قيمة افتراضية للتوصيل
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
            $cartItems = $cart ? CartItem::where('cart_id', $cart->id)->where('pharmacy_id', $request->pharmacy_id)->get() : collect();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty for this pharmacy.',
                    'data'    => null
                ], 400);
            }

            $subTotal = $cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });

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
            $deliveryFee = 15.00;
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
}
