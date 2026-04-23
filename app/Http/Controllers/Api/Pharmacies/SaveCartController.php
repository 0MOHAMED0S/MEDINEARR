<?php

namespace App\Http\Controllers\Api\Pharmacies;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Medicine;
use App\Http\Requests\Api\Save\SaveCartRequest;
use App\Http\Requests\Api\Save\CartItemsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SaveCartController extends Controller
{
    public function toggleItem(SaveCartRequest $request): JsonResponse
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated access.',
                    'data' => null
                ], 401);
            }

            // get or create cart
            $cart = Cart::firstOrCreate([
                'user_id' => $user->id
            ]);

            // check item exists
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('medicine_id', $request->medicine_id)
                ->where('pharmacy_id', $request->pharmacy_id)
                ->first();

            if ($cartItem) {
                $cartItem->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart.',
                    'data' => [
                        'action' => 'removed',
                        'medicine_id' => (int) $request->medicine_id,
                        'pharmacy_id' => (int) $request->pharmacy_id,
                    ]
                ], 200);
            }

            $medicine = Medicine::findOrFail($request->medicine_id);
            $item = CartItem::create([
                'cart_id' => $cart->id,
                'medicine_id' => $request->medicine_id, 
                'pharmacy_id' => $request->pharmacy_id,
                'quantity' => $request->quantity ?? 1,
                'price' => $medicine->official_price
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart.',
                'data' => [
                    'action' => 'added',
                    'item' => $item
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('API Toggle Cart Item Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while toggling cart item.',
                'error' => config('app.debug') ? $e->getMessage() : null,
                'data' => null
            ], 500);
        }
    }

    public function CartPharmacies(): JsonResponse
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated access.',
                    'data' => null
                ], 401);
            }

            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart is empty.',
                    'data' => []
                ], 200);
            }

            $pharmacies = CartItem::where('cart_id', $cart->id)
                ->with('pharmacy:id,pharmacy_name,image')
                ->get()
                ->pluck('pharmacy')
                ->unique('id')
                ->values();

            return response()->json([
                'success' => true,
                'message' => 'Cart pharmacies fetched successfully.',
                'data' => $pharmacies
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Get Cart Pharmacies Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cart pharmacies.',
                'error' => config('app.debug') ? $e->getMessage() : null,
                'data' => null
            ], 500);
        }
    }


    public function PharmacyCartItems(CartItemsRequest $request): JsonResponse
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated access.',
                    'data' => null
                ], 401);
            }

            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart is empty.',
                    'data' => [
                        'items' => [],
                        'total' => 0
                    ]
                ], 200);
            }

            $items = CartItem::where('cart_id', $cart->id)
                ->where('pharmacy_id', $request->pharmacy_id)
                ->with('medicine:id,name,official_price')
                ->get();

            $formattedItems = $items->map(function ($item) {
                $itemTotal = $item->price * $item->quantity;

                return [
                    'id' => $item->id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'item_total' => round($itemTotal, 2),
                    'medicine' => [
                        'id' => $item->medicine?->id,
                        'name' => $item->medicine?->name,
                        'official_price' => $item->medicine?->official_price,
                    ]
                ];
            });

            $total = $formattedItems->sum('item_total');

            return response()->json([
                'success' => true,
                'message' => 'Cart items fetched successfully.',
                'data' => [
                    'items' => $formattedItems,
                    'total' => round($total, 2),
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Get Pharmacy Cart Items Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cart items.',
                'error' => config('app.debug') ? $e->getMessage() : null,
                'data' => null
            ], 500);
        }
    }


}