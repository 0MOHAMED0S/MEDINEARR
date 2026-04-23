<?php

namespace App\Http\Controllers\Api\Pharmacies;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Save\SaveMedicinesRequest;
use App\Models\SavedMedicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SaveMedicinesController extends Controller
{
    /**
     * Toggle medicine (save / unsave)
     */
    public function toggleMedicine(SaveMedicinesRequest $request)
    {
        try {
            $user = auth()->user();

            // 1. Auth check
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated access.',
                    'data'    => null
                ], 401);
            }

            $medicineId = $request->medicine_id;
            $pharmacyId = $request->pharmacy_id;

            // 2. Manual toggle (عشان عندنا pharmacy_id)
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
                        'medicine_id' => (int) $medicineId,
                        'pharmacy_id' => (int) $pharmacyId,
                        'is_saved'    => false
                    ]
                ], 200);
            }

            SavedMedicine::create([
                'user_id' => $user->id,
                'medicine_id' => $medicineId,
                'pharmacy_id' => $pharmacyId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Medicine saved successfully.',
                'data'    => [
                    'medicine_id' => (int) $medicineId,
                    'pharmacy_id' => (int) $pharmacyId,
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

            // 1. query
            $query = SavedMedicine::with(['medicine', 'pharmacy'])
                ->where('user_id', $user->id);

            // 2. distance (لو عايز تحسب بناء على الصيدلية)
            if ($user->latitude && $user->longitude) {

                $lat = $user->latitude;
                $lng = $user->longitude;
                $radius = 6371;

                $haversineRaw = "( $radius * acos( cos(radians(?)) * cos(radians(pharmacies.lat)) * cos(radians(pharmacies.lng) - radians(?)) + sin(radians(?)) * sin(radians(pharmacies.lat)) ) )";

                $query->join('pharmacies', 'saved_medicines.pharmacy_id', '=', 'pharmacies.id')
                    ->selectRaw("saved_medicines.*, $haversineRaw AS distance", [$lat, $lng, $lat])
                    ->orderBy('distance', 'asc');

            } else {
                $query->orderBy('created_at', 'desc');
            }

            // 3. pagination
            $medicines = $query->paginate($perPage)->withQueryString();

            // 4. transform
            $medicines->getCollection()->transform(function ($item) {

                if (isset($item->distance)) {
                    if ($item->distance < 1) {
                        $item->distance_text = round($item->distance * 1000) . ' m';
                    } else {
                        $item->distance_text = round($item->distance, 2) . ' km';
                    }

                    $item->distance = round($item->distance, 2);
                } else {
                    $item->distance = null;
                    $item->distance_text = null;
                }

                return [
                    'id' => $item->id,
                    'medicine' => $item->medicine,
                    'pharmacy' => $item->pharmacy,
                    'distance' => $item->distance,
                    'distance_text' => $item->distance_text,
                    'created_at' => $item->created_at,
                ];
            });

            // 5. message
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