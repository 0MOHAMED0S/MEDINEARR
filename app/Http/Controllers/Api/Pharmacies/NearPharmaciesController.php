<?php

namespace App\Http\Controllers\Api\Pharmacies;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class NearPharmaciesController extends Controller
{
    /**
     * Display a listing of the nearest active pharmacies.
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->user();

            // 1. Case: User or Location Missing
            if (!$user || !$user->latitude || !$user->longitude) {
                return response()->json([
                    'success' => false,
                    'message' => 'User location is missing. Please enable location services to find nearby pharmacies.',
                    'data'    => null
                ], 400); // 400 Bad Request
            }

            $lat = $user->latitude;
            $lng = $user->longitude;

            $radius = 6371; // Earth's radius in kilometers
            $perPage = (int) $request->input('per_page', 10);

            // Fetching active Pharmacies ordered by distance
            $pharmacies = DB::table('pharmacies')
                ->where('is_active', true) // 🔥 Security Check: Only active pharmacies
                ->whereNotNull('lat')
                ->whereNotNull('lng')
                ->selectRaw("
                id,
                pharmacy_name,
                address,
                phone,
                working_hours,
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

            // 2. Case: No pharmacies found
            if ($pharmacies->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No active pharmacies found nearby at the moment.',
                    'data'    => $pharmacies // Return empty paginator
                ], 200);
            }

            // 3. Case: Success with Data
            return response()->json([
                'success' => true,
                'message' => 'Nearest pharmacies fetched successfully.',
                'data'    => $pharmacies
            ], 200);
        } catch (Exception $e) {
            // 4. Case: Server Error
            return response()->json([
                'success' => false,
                'message' => 'An unexpected server error occurred while fetching nearest pharmacies.',
                'error'   => $e->getMessage(), // Remove in production if desired
                'data'    => null
            ], 500); // 500 Internal Server Error
        }
    }
}
