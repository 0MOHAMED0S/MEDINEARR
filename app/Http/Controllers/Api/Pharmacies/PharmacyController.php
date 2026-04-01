<?php

namespace App\Http\Controllers\Api\Pharmacies;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

class PharmacyController extends Controller
{

//get all pharmacies
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Pharmacy::where('is_active', true);
            // 🔍 Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('pharmacy_name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            // 🏙️ Filter by city
            if ($request->filled('city')) {
                $query->where('city', $request->city);
            }

            // ⭐ Filter big pharmacies
            if ($request->filled('is_big')) {
                $query->where('is_big_pharmacy', $request->is_big == 1);
            }

            $perPage = $request->input('per_page', 10);

            $pharmacies = $query->select(
                'id',
                'pharmacy_name',
                'phone',
                'address',
                'city',
                'lat',
                'lng',
                'image',
                'is_big_pharmacy'
            )
                ->latest()
                ->paginate($perPage) // 👈 Pagination هنا
                ->through(function ($pharmacy) {
                    return [
                        'id' => $pharmacy->id,
                        'name' => $pharmacy->pharmacy_name,
                        'phone' => $pharmacy->phone,
                        'address' => $pharmacy->address,
                        'city' => $pharmacy->city,
                        'location' => [
                            'lat' => $pharmacy->lat,
                            'lng' => $pharmacy->lng,
                        ],
                        'image' => $pharmacy->image
                            ? asset('storage/' . $pharmacy->image)
                            : null,
                        'is_big_pharmacy' => (bool) $pharmacy->is_big_pharmacy,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Pharmacies retrieved successfully',
                'data' => $pharmacies
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving pharmacies',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieve pharmacy details and its specific inventory (Grouped by Category)
     *
     * @param int $id
     * @return JsonResponse
     */

    public function getInventory($id): JsonResponse
    {
        try {
            // 1. Fetch the pharmacy with non-hidden medicines and their categories
            $pharmacy = Pharmacy::with([
                'medicines' => function ($query) {
                    $query->wherePivot('status', '!=', 'hidden');
                },
                'medicines.category'
            ])
                ->where('is_active', true)
                ->find($id);

            // 2. Error Handling: Pharmacy not found or inactive
            if (!$pharmacy) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Pharmacy not found or currently inactive.',
                    'data'    => null
                ], 404);
            }

            // 3. Group medicines by category
            $groupedInventory = $pharmacy->medicines->groupBy(function ($medicine) {
                // Fallback to 'Others' for uncategorized medicines
                return $medicine->category->name ?? 'Others';
            })->map(function ($medicinesGroup, $categoryName) {

                $category = $medicinesGroup->first()->category;

                return [
                    'category_id'    => $category->id ?? null,
                    'category_name'  => $categoryName,
                    'category_image' => $category ? ($category->image_url ?? asset('Dashboard/images/default-category.png')) : asset('Dashboard/images/default-category.png'),
                    'medicines'      => $medicinesGroup->map(function ($medicine) {
                        return [
                            'medicine_id' => $medicine->id,
                            'name'        => $medicine->name,
                            'description' => $medicine->description,
                            'image'       => $medicine->image_url ?? asset('Dashboard/images/logo.png'),

                            // Pivot (Inventory) Data
                            'price'       => (float) $medicine->pivot->price,
                            'quantity'    => (int) $medicine->pivot->quantity,
                            'status'      => $medicine->pivot->status,
                        ];
                    })->values()->all()
                ];
            })->values()->all();

            // ✨ 4. Calculate Distance using PHP (Haversine Formula) ✨
            $distance = null;
            $distance_text = null;
            $user = auth()->guard('sanctum')->user(); // Use your correct auth guard

            if ($user && $user->latitude && $user->longitude && $pharmacy->lat && $pharmacy->lng) {
                $lat1 = $user->latitude;
                $lng1 = $user->longitude;
                $lat2 = $pharmacy->lat;
                $lng2 = $pharmacy->lng;

                $earthRadius = 6371; // Radius of the earth in km
                $dLat = deg2rad($lat2 - $lat1);
                $dLng = deg2rad($lng2 - $lng1);

                $a = sin($dLat / 2) * sin($dLat / 2) +
                     cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                     sin($dLng / 2) * sin($dLng / 2);

                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                $rawDistance = $earthRadius * $c;

                // Smart Formatter
                if ($rawDistance < 1) {
                    $distance_text = round($rawDistance * 1000) . ' m';
                } else {
                    $distance_text = round($rawDistance, 2) . ' km';
                }
                $distance = round($rawDistance, 2);
            }

            // 5. Build the final response payload
            $responseData = [
                'pharmacy_info' => [
                    // ✨ Returning ALL data of the pharmacy ✨
                    'id'                      => $pharmacy->id,
                    'user_id'                 => $pharmacy->user_id,
                    'pharmacy_application_id' => $pharmacy->pharmacy_application_id,
                    'pharmacy_name'           => $pharmacy->pharmacy_name,
                    'owner_name'              => $pharmacy->owner_name,
                    'phone'                   => $pharmacy->phone,
                    'email'                   => $pharmacy->email,
                    'city'                    => $pharmacy->city,
                    'address'                 => $pharmacy->address,
                    'working_hours'           => $pharmacy->working_hours,
                    'license_number'          => $pharmacy->license_number,
                    'image'                   => $pharmacy->image,
                    'cover'                   => $pharmacy->cover,
                    'license_document'        => $pharmacy->license_document,
                    'location' => [
                        'lat' => $pharmacy->lat ? (float) $pharmacy->lat : null,
                        'lng' => $pharmacy->lng ? (float) $pharmacy->lng : null,
                    ],
                    // ✨ Distance Fields Added ✨
                    'distance'                => $distance,
                    'distance_text'           => $distance_text,

                    'services'                => $pharmacy->services,
                    'has_collaboration'       => (bool) $pharmacy->has_collaboration,
                    'is_active'               => (bool) $pharmacy->is_active,
                    'is_big_pharmacy'         => (bool) $pharmacy->is_big_pharmacy,
                    'created_at'              => $pharmacy->created_at,
                    'updated_at'              => $pharmacy->updated_at,
                ],
                'inventory' => $groupedInventory
            ];

            // 6. Success Handling: Check if inventory is actually empty to provide a precise message
            $message = empty($groupedInventory)
                ? 'Pharmacy details retrieved successfully, but the inventory is empty.'
                : 'Pharmacy inventory retrieved successfully.';

            return response()->json([
                'status'  => true,
                'message' => $message,
                'data'    => $responseData
            ], 200);
        } catch (Exception $e) {
            // 7. Server Error Handling
            Log::error('API Pharmacy Inventory Error (ID: ' . $id . '): ' . $e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => 'An unexpected server error occurred while retrieving data. Please try again later.',
                'data'    => null
            ], 500);
        }
    }
}
