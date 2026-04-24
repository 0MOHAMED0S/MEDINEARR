<?php

namespace App\Http\Controllers\Api\Pharmacies;

use App\Http\Controllers\Controller;
use App\Models\Packet;
use App\Models\PacketItem;
use App\Http\Requests\Api\Packet\PacketItemRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PacketController extends Controller
{
    //add packet 
    public function store(PacketItemRequest $request): JsonResponse
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

            $packet = Packet::firstOrCreate([
                'user_id' => $user->id
            ]);

            $data = [
                'packet_id' => $packet->id,
                'type' => $request->type,
                'note' => $request->note,
                'medicine_id' => $request->medicine_id,
            ];

            // upload image
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('packets', 'public');
            }

            $item = PacketItem::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Item added successfully.',
                'data' => $item
            ], 201);

        } catch (\Exception $e) {
            Log::error('API Store Packet Item Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to add item.',
                'error' => config('app.debug') ? $e->getMessage() : null,
                'data' => null
            ], 500);
        }
    }

    // 🔹 get all packet items
    public function index(): JsonResponse
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

            $packet = Packet::where('user_id', $user->id)->first();

            if (!$packet) {
                return response()->json([
                    'success' => true,
                    'message' => 'Packet is empty.',
                    'data' => []
                ], 200);
            }

            $items = PacketItem::where('packet_id', $packet->id)
                ->with('medicine:id,name,official_price')
                ->latest()
                ->get();

            $formatted = $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => $item->type,
                    'note' => $item->note,
                    'image' => $item->image ? asset('storage/' . $item->image) : null,
                    'medicine' => $item->medicine ? [
                        'id' => $item->medicine->id,
                        'name' => $item->medicine->name,
                        'official_price' => $item->medicine->official_price
                    ] : null
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Packet items fetched successfully.',
                'data' => $formatted
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Get Packet Items Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch packet items.',
                'error' => config('app.debug') ? $e->getMessage() : null,
                'data' => null
            ], 500);
        }
    }

    public function update(PacketItemRequest $request): JsonResponse
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

            $item = PacketItem::find($request->id);

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found.',
                    'data' => null
                ], 404);
            }

            $data = [
                'type' => $request->type,
                'note' => $request->note,
                'medicine_id' => $request->medicine_id,
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('packets', 'public');
            }

            $item->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Item updated successfully.',
                'data' => $item
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Update Packet Item Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update item.',
                'error' => config('app.debug') ? $e->getMessage() : null,
                'data' => null
            ], 500);
        }
    }


    // 🔹 delete item مباشر
    public function delete(Request $request): JsonResponse
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

            $item = PacketItem::find($request->id);

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found.',
                    'data' => null
                ], 404);
            }

            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item deleted successfully.',
                'data' => null
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Delete Packet Item Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete item.',
                'error' => config('app.debug') ? $e->getMessage() : null,
                'data' => null
            ], 500);
        }
    }
    
}