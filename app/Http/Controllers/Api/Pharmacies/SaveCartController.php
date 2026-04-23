<?php

namespace App\Http\Controllers\Api\Pharmacies;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Medicine;
use App\Models\Pharmacy; // ✨ Added for validation
use App\Models\PharmacyMedicine; // ✨ Added for stock validation
use App\Http\Requests\Api\Save\SaveCartRequest;
use App\Http\Requests\Api\Save\CartItemsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SaveCartController extends Controller
{
    /**
     * Add, Update Quantity, or Remove item from cart
     */
    public function toggleItem(SaveCartRequest $request): JsonResponse
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please log in to manage your cart.',
                    'data'    => null
                ], 401);
            }

            $medicineId = (int) $request->medicine_id;
            $pharmacyId = (int) $request->pharmacy_id;
            $requestedQuantity = (int) ($request->quantity ?? 1); // Default to 1 if not provided

            // ✨ Security Check 1: Ensure Pharmacy is active
            $pharmacy = Pharmacy::find($pharmacyId);
            if (!$pharmacy || !$pharmacy->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'This pharmacy is currently inactive or unavailable.',
                    'data'    => null
                ], 403);
            }

            // ✨ Security Check 2: Ensure the Pharmacy actually stocks this Medicine
            $stock = PharmacyMedicine::where('pharmacy_id', $pharmacyId)
                                     ->where('medicine_id', $medicineId)
                                     ->first();

            if (!$stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'This medicine is not available at the selected pharmacy.',
                    'data'    => null
                ], 404);
            }

            // Get or create cart
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            // Check if item already exists in the cart
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('medicine_id', $medicineId)
                ->where('pharmacy_id', $pharmacyId)
                ->first();

            // ✨ Pure Toggle Logic: إذا كان موجوداً، قم بحذفه ✨
            if ($cartItem) {
                $cartItem->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart successfully.',
                    'data'    => [
                        'action'      => 'removed',
                        'medicine_id' => $medicineId,
                        'pharmacy_id' => $pharmacyId,
                    ]
                ], 200);
            }

            // ✨ إذا لم يكن موجوداً، قم بإنشائه (إضافته للعربة) ✨
            $medicine = Medicine::findOrFail($medicineId);
            $priceToUse = $medicine->official_price;

            $item = CartItem::create([
                'cart_id'     => $cart->id,
                'medicine_id' => $medicineId,
                'pharmacy_id' => $pharmacyId,
                'quantity'    => $requestedQuantity,
                'price'       => $priceToUse
            ]);

            return response()->json([
                'success' => true,
                'message' => "Item added to cart successfully.",
                'data'    => [
                    'action' => 'added',
                    'item'   => [
                        'id'          => $item->id,
                        'medicine_id' => $item->medicine_id,
                        'pharmacy_id' => $item->pharmacy_id,
                        'quantity'    => $item->quantity,
                        'price'       => $item->price,
                        'total'       => round($item->quantity * $item->price, 2)
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Toggle Cart Item Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while modifying your cart.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }
    /**
     * Get list of unique pharmacies the user has items from in their cart
     */
    public function CartPharmacies(): JsonResponse
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please log in to view your cart.',
                    'data'    => null
                ], 401);
            }

            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                return response()->json([
                    'success' => true,
                    'message' => 'Your cart is currently empty.',
                    'data'    => []
                ], 200);
            }

            // Fetch unique pharmacies from cart items
            $pharmacies = CartItem::where('cart_id', $cart->id)
                ->with('pharmacy:id,pharmacy_name,image,address,city') // Added address/city for better UI
                ->get()
                ->pluck('pharmacy')
                ->filter() // Remove nulls in case a pharmacy was deleted
                ->unique('id')
                ->values()
                ->map(function ($pharmacy) {
                    // Safe image parsing
                    if (!empty($pharmacy->image) && !str_starts_with($pharmacy->image, 'http')) {
                        $pharmacy->image = asset('storage/' . $pharmacy->image);
                    }
                    return $pharmacy;
                });

            $message = $pharmacies->isEmpty()
                ? 'Your cart is empty.'
                : 'Cart pharmacies retrieved successfully.';

            return response()->json([
                'success' => true,
                'message' => $message,
                'data'    => $pharmacies
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Get Cart Pharmacies Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve cart pharmacies.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }

    /**
     * Get items in the cart belonging to a specific pharmacy
     */
public function PharmacyCartItems(CartItemsRequest $request): JsonResponse
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please log in to view cart items.',
                    'data'    => null
                ], 401);
            }

            $pharmacyId = (int) $request->pharmacy_id;
            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                return response()->json([
                    'success' => true,
                    'message' => 'Your cart is completely empty.',
                    'data'    => [
                        'pharmacy_id' => $pharmacyId,
                        'total_items' => 0,
                        'total_price' => 0,
                        'items'       => []
                    ]
                ], 200);
            }

            $items = CartItem::where('cart_id', $cart->id)
                ->where('pharmacy_id', $pharmacyId)
                ->with('medicine:id,name,official_price,image') // Added image for UI
                ->get();

            $formattedItems = $items->map(function ($item) {
                $itemTotal = $item->price * $item->quantity;
                $med = $item->medicine;

                $imageUrl = null;
                if ($med && !empty($med->image)) {
                    $imageUrl = str_starts_with($med->image, 'http') ? $med->image : asset('storage/' . $med->image);
                }

                return [
                    'cart_item_id' => $item->id, // Renamed for clarity
                    'quantity'     => $item->quantity,
                    'unit_price'   => $item->price, // Current price in cart
                    'item_total'   => round($itemTotal, 2),
                    'medicine'     => $med ? [
                        'id'             => $med->id,
                        'name'           => $med->name,
                        'official_price' => $med->official_price,
                        'image'          => $imageUrl
                    ] : null
                ];
            });

            $total = $formattedItems->sum('item_total');

            // ✨ معالجة الرسالة ذكياً بناءً على وجود عناصر أم لا ✨
            $message = $formattedItems->isEmpty()
                ? 'There are no items in your cart for this pharmacy.'
                : 'Pharmacy cart items retrieved successfully.';

            return response()->json([
                'success' => true,
                'message' => $message,
                'data'    => [
                    'pharmacy_id' => $pharmacyId,
                    'total_items' => $formattedItems->count(),
                    'total_price' => round($total, 2),
                    'items'       => $formattedItems,
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Get Pharmacy Cart Items Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve cart items for this pharmacy.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }
}
