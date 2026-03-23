<?php

namespace App\Http\Controllers\Api\Pharmacies;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

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
}
