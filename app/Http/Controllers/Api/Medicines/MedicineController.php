<?php

namespace App\Http\Controllers\Api\Medicines;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Medicine::where('status', 1)->with('category');

            // 🔍 Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // 🏷️ Filter by category
            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }

            $perPage = min($request->input('per_page', 10), 50);

            $medicines = $query->select(
                'id',
                'name',
                'description',
                // 'price',
                'image',
                'category_id'
            )
                ->latest()
                ->get() // ✨ تم التغيير من paginate إلى get ✨
                ->map(function ($medicine) { // ✨ تم التغيير من through إلى map ✨
                    return [
                        'id'          => $medicine->id,
                        'name'        => $medicine->name,
                        'description' => $medicine->description,
                        // 'price'       => $medicine->price,
                        'image'       => $medicine->image ? asset('storage/' . $medicine->image) : null,
                        'category'    => $medicine->category ? [
                            'id'   => $medicine->category->id,
                            'name' => $medicine->category->name
                        ] : null
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Medicines retrieved successfully',
                'data'    => $medicines // سيتم إرجاع مصفوفة (Array) مباشرة هنا
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving medicines',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request)
    {
        try {
            // 1. Validation with Standardized Response
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'medicine_id' => 'required|integer|exists:medicines,id',
                'pharmacy_id' => 'required|integer|exists:pharmacies,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'data'    => null
                ], 422);
            }

            $medicineId = (int) $request->input('medicine_id');
            $pharmacyId = (int) $request->input('pharmacy_id');

            // 2. Fetch Data with Eager Loading
            $medicine = Medicine::with([
                'category',
                'pharmacies' => function ($query) use ($pharmacyId) {
                    $query->where('pharmacies.id', $pharmacyId);
                }
            ])
                ->where('id', $medicineId)
                ->where('status', 1)
                ->first();

            // 3. Business Logic Check
            if (!$medicine || $medicine->pharmacies->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This medicine is currently not available at the selected pharmacy.',
                    'data'    => null
                ], 404);
            }

            $pharmacy = $medicine->pharmacies->first();

            // 4. Handle Images Safely
            $medImage = null;
            if (!empty($medicine->image)) {
                $medImage = str_starts_with($medicine->image, 'http') ? $medicine->image : asset('storage/' . $medicine->image);
            }

            $pharmImage = null;
            if (!empty($pharmacy->image)) {
                $pharmImage = str_starts_with($pharmacy->image, 'http') ? $pharmacy->image : asset('storage/' . $pharmacy->image);
            }

            // 5. Check if saved by authenticated user (Optional but very helpful for UI)
            $isSaved = false;
            $user = auth()->guard('sanctum')->user(); // Adjust guard if you use 'api' or something else
            if ($user) {
                $isSaved = \App\Models\SavedMedicine::where('user_id', $user->id)
                    ->where('medicine_id', $medicineId)
                    ->where('pharmacy_id', $pharmacyId)
                    ->exists();
            }

            // 6. Format the output data
            $pivotPrice = $pharmacy->pivot->price ?? null;
            $pivotQuantity = $pharmacy->pivot->quantity ?? 0;

            return response()->json([
                'success' => true,
                'message' => 'Medicine details retrieved successfully.',
                'data'    => [
                    'medicine' => [
                        'id'             => $medicine->id,
                        'name'           => $medicine->name,
                        'description'    => $medicine->description,
                        'official_price' => $medicine->official_price ? (float) $medicine->official_price : null,
                        'image'          => $medImage,
                        'category'       => $medicine->category ? [
                            'id'   => $medicine->category->id,
                            'name' => $medicine->category->name
                        ] : null,
                    ],

                    'pharmacy' => [
                        'id'            => $pharmacy->id,
                        'pharmacy_name' => $pharmacy->pharmacy_name,
                        'address'       => $pharmacy->address,
                        'phone'         => $pharmacy->phone,
                        'working_hours' => $pharmacy->working_hours,
                        'image'         => $pharmImage,
                        'location'      => [
                            'lat' => $pharmacy->lat ? (float) $pharmacy->lat : null,
                            'lng' => $pharmacy->lng ? (float) $pharmacy->lng : null,
                        ]
                    ],

                    'stock_info' => [
                        // If pharmacy has a specific price, use it; otherwise fallback to official price
                        'selling_price' => $pivotPrice ? (float) $pivotPrice : (float) $medicine->official_price,
                        'quantity'      => (int) $pivotQuantity,
                        'in_stock'      => ((int) $pivotQuantity) > 0,
                    ],

                    'is_saved' => $isSaved
                ]
            ], 200);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('API Get Medicine Details Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving medicine details.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
                'data'    => null
            ], 500);
        }
    }
}
