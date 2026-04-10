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
    
}
