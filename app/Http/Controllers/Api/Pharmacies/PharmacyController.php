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
            //  Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('pharmacy_name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            //  Filter by city
            if ($request->filled('city')) {
                $query->where('city', $request->city);
            }

            //  Filter big pharmacies
            if ($request->filled('is_big')) {
                $query->where('is_big_pharmacy', $request->is_big == 1);
            }

            $perPage = min($request->input('per_page', 10), 50);

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
                ->paginate($perPage)
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

public function getInventory($id): \Illuminate\Http\JsonResponse
    {
        try {
            // ✨ 1. جلب بيانات المستخدم والأدوية المحفوظة مبكراً لتجنب بطء الأداء (N+1 Problem) ✨
            $user = auth()->guard('sanctum')->user();
            $savedMedicineIds = [];
            $is_saved_pharmacy = false;
            $distance = null;
            $distance_text = null;

            // 2. Fetch the pharmacy with non-hidden medicines and their categories
            $pharmacy = \App\Models\Pharmacy::with([
                'medicines' => function ($query) {
                    $query->wherePivot('status', '!=', 'hidden');
                },
                'medicines.category'
            ])
                ->where('is_active', true)
                ->find($id);

            // 3. Error Handling: Pharmacy not found or inactive
            if (!$pharmacy) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Pharmacy not found or currently inactive.',
                    'data'    => null
                ], 404);
            }

            // ✨ 4. إذا كان المستخدم مسجلاً، نجهز بيانات المفضلة والمسافة ✨
            if ($user) {
                // التحقق مما إذا كان المستخدم قد حفظ هذه الصيدلية
                $is_saved_pharmacy = $user->savedPharmacies()->where('pharmacy_id', $pharmacy->id)->exists();

                // ✨ تم الحل هنا: جلب الأدوية المحفوظة للمستخدم في "هذه الصيدلية المحددة فقط" بدلاً من كل الصيدليات ✨
                $savedMedicineIds = \App\Models\SavedMedicine::where('user_id', $user->id)
                    ->where('pharmacy_id', $pharmacy->id)
                    ->pluck('medicine_id')
                    ->toArray();

                // حساب المسافة
                if ($user->latitude && $user->longitude && $pharmacy->lat && $pharmacy->lng) {
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

                    if ($rawDistance < 1) {
                        $distance_text = round($rawDistance * 1000) . ' m';
                    } else {
                        $distance_text = round($rawDistance, 2) . ' km';
                    }
                    $distance = round($rawDistance, 2);
                }
            }

            // 5. Group medicines by category
            // تمرير مصفوفة $savedMedicineIds بداخل الـ closures باستخدام (use)
            $groupedInventory = $pharmacy->medicines->groupBy(function ($medicine) {
                return $medicine->category->name ?? 'Others';
            })->map(function ($medicinesGroup, $categoryName) use ($savedMedicineIds) {

                $category = $medicinesGroup->first()->category;

                return [
                    'category_id'    => $category->id ?? null,
                    'category_name'  => $categoryName,
                    'category_image' => $category ? ($category->image_url ?? asset('Dashboard/images/default-category.png')) : asset('Dashboard/images/default-category.png'),
                    'medicines'      => $medicinesGroup->map(function ($medicine) use ($savedMedicineIds) {
                        return [
                            'medicine_id' => $medicine->id,
                            'name'        => $medicine->name,
                            'description' => $medicine->description,
                            'image'       => $medicine->image_url ?? asset('Dashboard/images/logo.png'),

                            // Pivot (Inventory) Data
                            'price'       => (float) $medicine->pivot->price,
                            'quantity'    => (int) $medicine->pivot->quantity,
                            'status'      => $medicine->pivot->status,

                            // ✨ NEW: Is Saved Flag For Medicine ✨
                            'is_saved'    => in_array($medicine->id, $savedMedicineIds),
                        ];
                    })->values()->all()
                ];
            })->values()->all();

            // 6. Build the final response payload
            $responseData = [
                'pharmacy_info' => [
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
                    'distance'                => $distance,
                    'distance_text'           => $distance_text,

                    // Flag الخاص بالصيدلية
                    'is_saved'                => $is_saved_pharmacy,

                    'services'                => $pharmacy->services,
                    'has_collaboration'       => (bool) $pharmacy->has_collaboration,
                    'is_active'               => (bool) $pharmacy->is_active,
                    'is_big_pharmacy'         => (bool) $pharmacy->is_big_pharmacy,
                    'created_at'              => $pharmacy->created_at,
                    'updated_at'              => $pharmacy->updated_at,
                ],
                'inventory' => $groupedInventory
            ];

            // 7. Success Handling
            $message = empty($groupedInventory)
                ? 'Pharmacy details retrieved successfully, but the inventory is empty.'
                : 'Pharmacy inventory retrieved successfully.';

            return response()->json([
                'status'  => true,
                'message' => $message,
                'data'    => $responseData
            ], 200);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('API Pharmacy Inventory Error (ID: ' . $id . '): ' . $e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => 'An unexpected server error occurred while retrieving data. Please try again later.',
                'data'    => null
            ], 500);
        }
    }

}
