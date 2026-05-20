<?php

namespace App\Http\Controllers\Api\Pharmacies;

use App\Http\Controllers\Controller;
use App\Models\Packet;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PacketController extends Controller
{
    /**
     * جلب جميع الحقائب الخاصة بالمستخدم (مع Pagination)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = (int) $request->input('per_page', 10);

            $packets = Packet::where('user_id', auth()->id())
                ->latest()
                ->paginate($perPage)
                ->withQueryString();

            return response()->json([
                'success' => true,
                'message' => 'Packets retrieved successfully.',
                'data'    => $packets
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Get Packets Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving packets.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }

    /**
     * إنشاء حقيبة جديدة
     */
    public function store(Request $request): JsonResponse
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data'    => null
            ], 422);
        }

        try {
            // 2. Create Packet
            $packet = Packet::create([
                'user_id'     => auth()->id(),
                'title'       => $request->title,
                'description' => $request->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Packet created successfully.',
                'data'    => $packet
            ], 201);

        } catch (\Exception $e) {
            Log::error('API Store Packet Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create packet.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }

    /**
     * تعديل بيانات الحقيبة
     */
    public function update(Request $request, $id): JsonResponse
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data'    => null
            ], 422);
        }

        try {
            // 2. Find Packet ensuring it belongs to the authenticated user
            $packet = Packet::where('user_id', auth()->id())->find($id);

            if (!$packet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Packet not found or access denied.',
                    'data'    => null
                ], 404);
            }

            // 3. Update Packet
            $packet->update($request->only(['title', 'description']));

            return response()->json([
                'success' => true,
                'message' => 'Packet updated successfully.',
                'data'    => $packet
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Update Packet Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update packet.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }

    /**
     * حذف الحقيبة بالكامل
     */
    public function destroy($id): JsonResponse
    {
        try {
            // 1. Find Packet ensuring it belongs to the authenticated user
            $packet = Packet::where('user_id', auth()->id())->find($id);

            if (!$packet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Packet not found or access denied.',
                    'data'    => null
                ], 404);
            }

            // 2. Delete Packet (Cascade delete will handle packet_items if configured in migration)
            $packet->delete();

            return response()->json([
                'success' => true,
                'message' => 'Packet deleted successfully.',
                'data'    => null
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Delete Packet Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete packet.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }
}
