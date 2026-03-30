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

            // 4. Build the final response payload
            $responseData = [
                'pharmacy_info' => [
                    'id'             => $pharmacy->id,
                    'name'           => $pharmacy->pharmacy_name,
                    'address'        => $pharmacy->address,
                    'phone'          => $pharmacy->phone,
                    'working_hours'  => $pharmacy->working_hours,
                    'location' => [
                        'lat' => (float) $pharmacy->lat,
                        'lng' => (float) $pharmacy->lng,
                    ],
                    'services'       => $pharmacy->services,
                ],
                'inventory' => $groupedInventory
            ];

            // 5. Success Handling: Check if inventory is actually empty to provide a precise message
            $message = empty($groupedInventory)
                ? 'Pharmacy details retrieved successfully, but the inventory is empty.'
                : 'Pharmacy inventory retrieved successfully.';

            return response()->json([
                'status'  => true,
                'message' => $message,
                'data'    => $responseData
            ], 200);
        } catch (Exception $e) {
            // 6. Server Error Handling
            Log::error('API Pharmacy Inventory Error (ID: ' . $id . '): ' . $e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => 'An unexpected server error occurred while retrieving data. Please try again later.',
                'data'    => null
            ], 500);
        }
    }
}
