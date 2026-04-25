<?php

namespace App\Http\Controllers\Api\Pharmacies;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Save\SaveMedicinesRequest;
use App\Models\SavedMedicine;
use App\Models\Pharmacy; // ✨ تمت إضافة استدعاء موديل الصيدلية
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SaveMedicinesController extends Controller
{
/**
     * حفظ / إزالة دواء من المفضلة (مرتبط بصيدلية معينة)
     */
    public function toggleMedicine(SaveMedicinesRequest $request)
    {
        try {
            $user = auth()->user();

            // 1. Auth check
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated access. Please log in.',
                    'data'    => null
                ], 401);
            }

            // ضمان أن البيانات أرقام صحيحة (Casting)
            $medicineId = (int) $request->input('medicine_id');
            $pharmacyId = (int) $request->input('pharmacy_id');

            // ✨ 2. Business Logic Check: التحقق من أن الصيدلية موجودة ومفعلة ✨
            $pharmacy = \App\Models\Pharmacy::find($pharmacyId);

            if (!$pharmacy || !$pharmacy->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'This pharmacy is currently inactive or unavailable.',
                    'data'    => null
                ], 403); // 403 Forbidden
            }

            // ✨ 3. Security/Stock Check: التحقق من أن الدواء موجود في هذه الصيدلية وحالته نشطة ✨
            $stock = \App\Models\PharmacyMedicine::where('pharmacy_id', $pharmacyId)
                ->where('medicine_id', $medicineId)
                ->first();

            // إذا لم يكن الدواء موجوداً في الصيدلية، أو كانت حالته مخفية (hidden)
            if (!$stock || $stock->status === 'hidden') {
                return response()->json([
                    'success' => false,
                    'message' => 'This medicine is not available or currently inactive at the selected pharmacy.',
                    'data'    => null
                ], 404); // 404 Not Found
            }

            // 4. Manual toggle check
            $existing = SavedMedicine::where('user_id', $user->id)
                ->where('medicine_id', $medicineId)
                ->where('pharmacy_id', $pharmacyId)
                ->first();

            if ($existing) {
                $existing->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Medicine removed from saved list.',
                    'data'    => [
                        'medicine_id' => $medicineId,
                        'pharmacy_id' => $pharmacyId,
                        'is_saved'    => false
                    ]
                ], 200);
            }

            SavedMedicine::create([
                'user_id'     => $user->id,
                'medicine_id' => $medicineId,
                'pharmacy_id' => $pharmacyId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Medicine saved successfully.',
                'data'    => [
                    'medicine_id' => $medicineId,
                    'pharmacy_id' => $pharmacyId,
                    'is_saved'    => true
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Toggle Saved Medicine Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while toggling the medicine.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }

    /**
     * Get saved medicines with pharmacy
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated access.',
                    'data'    => null
                ], 401);
            }

            $perPage = (int) $request->input('per_page', 10);

            // 1. Query: Eager load relations
            $query = SavedMedicine::with(['medicine.category', 'pharmacy'])
                ->where('medicines_saved.user_id', $user->id);

            // 2. Distance Calculation
            if ($user->latitude && $user->longitude) {
                $lat = $user->latitude;
                $lng = $user->longitude;
                $radius = 6371;

                $haversineRaw = "( $radius * acos( cos(radians(?)) * cos(radians(pharmacies.lat)) * cos(radians(pharmacies.lng) - radians(?)) + sin(radians(?)) * sin(radians(pharmacies.lat)) ) )";

                $query->join('pharmacies', 'medicines_saved.pharmacy_id', '=', 'pharmacies.id')
                    ->selectRaw("medicines_saved.*, $haversineRaw AS distance", [$lat, $lng, $lat])
                    ->orderBy('distance', 'asc');
            } else {
                $query->select('medicines_saved.*')->orderBy('medicines_saved.created_at', 'desc');
            }

            // 3. Pagination
            $medicines = $query->paginate($perPage)->withQueryString();

            // 4. Transform Data (Professional Restructuring)
            $medicines->getCollection()->transform(function ($item) {

                $distanceText = null;
                $distanceValue = null;

                if (isset($item->distance)) {
                    $distanceValue = round($item->distance, 2);
                    $distanceText = $item->distance < 1
                        ? round($item->distance * 1000) . ' m'
                        : $distanceValue . ' km';
                }

                $med = $item->medicine;
                $pharm = $item->pharmacy;

                return [
                    'save_id'  => $item->id,
                    'is_saved' => true,
                    'saved_at' => $item->created_at,

                    'medicine' => $med ? [
                        'id'             => $med->id,
                        'name'           => $med->name,
                        'description'    => $med->description,
                        // ✨ تمت إضافة السعر الرسمي هنا ✨
                        'official_price' => $med->official_price ? (float) $med->official_price : null,
                        // التعامل السليم مع مسارات الصور
                        'image'          => $med->image ? (str_starts_with($med->image, 'http') ? $med->image : asset('storage/' . $med->image)) : null,
                        'category'       => $med->category ? [
                            'id'   => $med->category->id,
                            'name' => $med->category->name
                        ] : null
                    ] : null,

                    'pharmacy' => $pharm ? [
                        'id'            => $pharm->id,
                        'pharmacy_name' => $pharm->pharmacy_name,
                        'address'       => $pharm->address,
                        'phone'         => $pharm->phone,
                        'working_hours' => $pharm->working_hours,
                        'image'         => $pharm->image ? (str_starts_with($pharm->image, 'http') ? $pharm->image : asset('storage/' . $pharm->image)) : null,
                        'location'      => [
                            'lat' => $pharm->lat ? (float) $pharm->lat : null,
                            'lng' => $pharm->lng ? (float) $pharm->lng : null,
                        ],
                        // ✨ نقل المسافة لتكون هنا، جزءاً من بيانات الصيدلية الجغرافية ✨
                        'distance'      => $distanceValue,
                        'distance_text' => $distanceText,
                    ] : null,
                ];
            });

            // 5. Response Message
            $message = $medicines->isEmpty()
                ? 'You have no saved medicines yet.'
                : 'Saved medicines fetched successfully.';

            return response()->json([
                'success' => true,
                'message' => $message,
                'data'    => $medicines
            ], 200);

        } catch (\Exception $e) {
            Log::error('API Fetch Saved Medicines Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while fetching your saved medicines.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }
}
