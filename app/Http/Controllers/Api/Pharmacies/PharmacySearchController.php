<?php

namespace App\Http\Controllers\Api\Pharmacies;
use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Exception;

class PharmacySearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user || !$user->latitude || !$user->longitude) {
                return response()->json([
                    'message' => 'User location not found'
                ], 400);
            }

            $lat = $user->latitude;
            $lng = $user->longitude;

            $radius = 6371;

            $perPage = min($request->input('per_page', 5),50);

            $pharmacies = Pharmacy::query()
                ->whereNotNull('lat')
                ->whereNotNull('lng')
                ->selectRaw("
                    id,
                    pharmacy_name,
                    address,
                    lat,
                    lng,
                    (
                        $radius * acos(
                            cos(radians(?)) *
                            cos(radians(lat)) *
                            cos(radians(lng) - radians(?)) +
                            sin(radians(?)) *
                            sin(radians(lat))
                        )
                    ) AS distance
                ", [$lat, $lng, $lat])
                ->orderBy('distance', 'asc')
                ->paginate($perPage);

            return response()->json([
                'message' => 'Nearest pharmacies fetched successfully',
                'data' => $pharmacies
            ]);

        } catch (Exception $e) {

            return response()->json([
                'message' => 'Error fetching nearest pharmacies',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
