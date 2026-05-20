<?php

namespace App\Http\Controllers\Api\Pharmacies;

use App\Http\Controllers\Controller;
use App\Models\PacketItem;
use App\Models\Packet;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PacketItemController extends Controller
{
    /**
     * Helper Method: توحيد شكل الاستجابة لعنصر الحقيبة
     */
    private function formatItem($item): array
    {
        $medicineData = null;
        if ($item->medicine) {
            $medicineData = $item->medicine->toArray();
            $medicineData['image'] = $this->getImageUrl($item->medicine->image);
        }

        return [
            'id'         => $item->id,
            'note'       => $item->note,
            'image'      => $this->getImageUrl($item->image),
            'medicine'   => $medicineData,
            'created_at' => $item->created_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Helper Method: معالجة الروابط للصور
     */
    private function getImageUrl(?string $path): ?string
    {
        if (empty($path)) return null;
        return str_starts_with($path, 'http') ? $path : asset('storage/' . $path);
    }

    /**
     * عرض جميع العناصر داخل حقيبة محددة
     */
    public function index($packet_id): JsonResponse
    {
        try {
            $packet = Packet::where('user_id', auth()->id())->find($packet_id);

            if (!$packet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Packet not found or access denied.',
                    'data'    => null
                ], 404);
            }

            $items = $packet->items()
                ->with('medicine')
                ->latest()
                ->get();

            $formattedItems = $items->map(fn ($item) => $this->formatItem($item));

            // تجميع البيانات لتسهيل العرض في الموبايل
            $groupedResponse = [
                'note'      => $formattedItems->firstWhere('note', '!=', null) ?: null,
                'medicines' => $formattedItems->filter(fn($item) => $item['medicine'] !== null)->values()->toArray(),
                'images'    => $formattedItems->filter(fn($item) => $item['image'] !== null)->values()->toArray(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Packet items retrieved successfully.',
                'data'    => $groupedResponse
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Get Packet Items Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving items.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }

    /**
     * إضافة عنصر جديد (Single Insert)
     */
    public function store(Request $request, $packet_id): JsonResponse
    {
        // 1. التحقق من المدخلات الفردية
        $validator = Validator::make($request->all(), [
            'note'        => 'nullable|string',
            'medicine_id' => 'nullable|exists:medicines,id',
            'image'       => 'nullable|image|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data'    => null
            ], 422);
        }

        try {
            $packet = Packet::where('user_id', auth()->id())->find($packet_id);

            if (!$packet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Packet not found or access denied.',
                    'data'    => null
                ], 404);
            }

            // 2. تأمين: منع الإضافة الفارغة
            if (!$request->filled('note') && !$request->filled('medicine_id') && !$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot create an empty item. Please provide a note, a medicine, or an image.',
                    'data'    => null
                ], 422);
            }

            // 3. تجهيز البيانات للحفظ
            $data = [
                'note'        => $request->note,
                'medicine_id' => $request->medicine_id,
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('packets/items', 'public');
            }

            $item = $packet->items()->create($data);

            if ($item->medicine_id) {
                $item->load('medicine');
            }

            return response()->json([
                'success' => true,
                'message' => 'Item added successfully.',
                'data'    => $this->formatItem($item) // إرجاع العنصر بتنسيق موحد
            ], 201);

        } catch (\Exception $e) {
            Log::error('API Store Packet Item Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }

    /**
     * تعديل عنصر محدد
     */
    public function update(Request $request, $packet_id, $item_id): JsonResponse
    {
        // 1. إصلاح قاعدة التحقق وإزالة trim
        $validator = Validator::make($request->all(), [
            'note'        => 'nullable|string',
            'medicine_id' => 'nullable|exists:medicines,id',
            'image'       => 'nullable|image|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data'    => null
            ], 422);
        }

        try {
            $item = PacketItem::where('packet_id', $packet_id)
                ->whereHas('packet', function ($q) {
                    $q->where('user_id', auth()->id());
                })
                ->find($item_id);

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found or access denied.',
                    'data'    => null
                ], 404);
            }

            // 2. تحديث الحقول في الذاكرة
            if ($request->has('note')) {
                $item->note = $request->note;
            }

            if ($request->has('medicine_id')) {
                $item->medicine_id = $request->medicine_id;
            }

            // معالجة الصورة بشكل منفصل
            if ($request->hasFile('image')) {
                if ($item->image && !str_starts_with($item->image, 'http')) {
                    Storage::disk('public')->delete($item->image);
                }
                $item->image = $request->file('image')->store('packets/items', 'public');
            } elseif ($request->has('image') && empty($request->image)) {
                if ($item->image && !str_starts_with($item->image, 'http')) {
                    Storage::disk('public')->delete($item->image);
                }
                $item->image = null;
            }

            // 3. تأمين: منع حفظ العنصر إذا أصبح فارغاً تماماً
            if (empty($item->note) && empty($item->medicine_id) && empty($item->image)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item cannot be completely empty. Please provide at least one value or delete the item instead.',
                    'data'    => null
                ], 422);
            }

            $item->save();

            if ($item->medicine_id) {
                $item->load('medicine');
            }

            return response()->json([
                'success' => true,
                'message' => 'Item updated successfully.',
                'data'    => $this->formatItem($item)
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Update Packet Item Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update item.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }

    /**
     * حذف عنصر محدد
     */
    public function destroy($packet_id, $item_id): JsonResponse
    {
        try {
            $item = PacketItem::where('packet_id', $packet_id)
                ->whereHas('packet', function ($q) {
                    $q->where('user_id', auth()->id());
                })
                ->find($item_id);

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found or access denied.',
                    'data'    => null
                ], 404);
            }

            if ($item->image && !str_starts_with($item->image, 'http')) {
                Storage::disk('public')->delete($item->image);
            }

            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item deleted successfully.',
                'data'    => null
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Delete Packet Item Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete item.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }
}
