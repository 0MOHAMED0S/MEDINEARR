<?php

namespace App\Http\Controllers\Api\Pharmacies;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use App\Models\Pharmacy;
use App\Models\Medicine;
use Illuminate\Support\Facades\Validator;
use App\Jobs\LogSearchHistoryJob;
use Exception;

class PharmacySearchController extends Controller
{
    /**
     * Unified Search Endpoint (Pharmacies & Medicines)
     */
    public function index(Request $request)
    {
        // 1. Input Validation
        $validator = Validator::make($request->all(), [
            'type'        => 'required|in:pharmacy,medicine',
            'q'           => 'required_if:type,pharmacy|string|nullable',
            'medicine_id' => 'required_if:type,medicine|exists:medicines,id',
            'per_page'    => 'nullable|integer|min:1|max:50'
        ], [
            'type.required'           => 'Please specify the search type (pharmacy or medicine).',
            'q.required_if'           => 'Please enter the pharmacy search query.',
            'medicine_id.required_if' => 'Please specify the medicine to search for.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data'    => null
            ], 422);
        }

        try {
            $user = auth()->user();

            // 2. Check User Location
            if (!$user || !$user->latitude || !$user->longitude) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to determine your location. Please enable location services (GPS) to search.',
                    'data'    => null
                ], 400);
            }

            $lat = $user->latitude;
            $lng = $user->longitude;
            $radius = 6371; // Earth's radius in kilometers
            $perPage = (int) $request->input('per_page', 10);

            // Haversine Formula to calculate distance
            $haversineRaw = "( $radius * acos( cos(radians(?)) * cos(radians(lat)) * cos(radians(lng) - radians(?)) + sin(radians(?)) * sin(radians(lat)) ) )";
            $bindings = [$lat, $lng, $lat];

            // ========================================================
            // 🔴 Case 1: Search for pharmacies by name
            // ========================================================
            if ($request->type === 'pharmacy') {

                $pharmacies = Pharmacy::where('is_active', true)
                    ->whereNotNull('lat')
                    ->whereNotNull('lng')
                    ->when($request->filled('q'), function ($query) use ($request) {
                        $query->where('pharmacy_name', 'LIKE', '%' . $request->q . '%');
                    })
                    ->selectRaw("id, pharmacy_name, address, phone, working_hours, lat, lng, is_big_pharmacy, $haversineRaw AS distance", $bindings)
                    ->orderBy('distance', 'asc')
                    ->paginate($perPage)
                    ->withQueryString(); // 👈 Essential for pagination to keep filters

                // Smart Distance Formatter for Pharmacies Search
                $pharmacies->getCollection()->transform(function ($pharmacy) {
                    if ($pharmacy->distance < 1) {
                        $pharmacy->distance_text = round($pharmacy->distance * 1000) . ' m';
                    } else {
                        $pharmacy->distance_text = round($pharmacy->distance, 2) . ' km';
                    }
                    $pharmacy->distance = round($pharmacy->distance, 2);
                    return $pharmacy;
                });

                // ✨ Save search history in the background (first 10 results) ✨
                $returnedIds = $pharmacies->take(10)->pluck('id')->toArray();
                LogSearchHistoryJob::dispatchAfterResponse([
                    'user_id'               => $user->id,
                    'search_type'           => 'pharmacy',
                    'search_query'          => $request->q,
                    'medicine_id'           => null,
                    'lat'                   => $lat,
                    'lng'                   => $lng,
                    'results_count'         => $pharmacies->total(),
                    'returned_pharmacy_ids' => $returnedIds,
                ]);

                if ($pharmacies->isEmpty()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'No pharmacies matching your search were found nearby.',
                        'data'    => $pharmacies
                    ], 200);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Pharmacies fetched successfully.',
                    'data'    => $pharmacies
                ], 200);
            }

            // ========================================================
            // 🔵 Case 2: Search for a specific medicine in nearby pharmacies
            // ========================================================
            if ($request->type === 'medicine') {

                // Get the requested medicine with its category
                $medicine = Medicine::with('category:id,name')->find($request->medicine_id);

                // Get pharmacies that have the medicine (or Big Pharmacies always)
                $pharmacies = Pharmacy::where('is_active', true)
                    ->whereNotNull('lat')
                    ->whereNotNull('lng')
                    ->where(function ($query) use ($medicine) {

                        // Pharmacy has the medicine and it's available
                        $query->whereHas('medicines', function ($subQuery) use ($medicine) {
                            $subQuery->where('pharmacy_medicines.medicine_id', $medicine->id)
                                ->where('pharmacy_medicines.status', 'available');
                        })
                            // Or it is a Big Pharmacy
                            ->orWhere('is_big_pharmacy', true);
                    })
                    // Get the specific status of this medicine in the pharmacy (if any)
                    ->with(['medicines' => function ($query) use ($medicine) {
                        $query->where('medicines.id', $medicine->id);
                    }])
                    ->selectRaw("id, pharmacy_name, address, phone, working_hours, lat, lng, is_big_pharmacy, $haversineRaw AS distance", $bindings)
                    ->orderBy('distance', 'asc')
                    ->paginate($perPage)
                    ->withQueryString(); // 👈 Essential for pagination to keep filters

                // Data Transformation
                $pharmacies->getCollection()->transform(function ($pharmacy) {

                    // 1. Smart Distance Formatter
                    if ($pharmacy->distance < 1) {
                        $pharmacy->distance_text = round($pharmacy->distance * 1000) . ' m';
                    } else {
                        $pharmacy->distance_text = round($pharmacy->distance, 2) . ' km';
                    }
                    $pharmacy->distance = round($pharmacy->distance, 2);

                    // 2. Find the pivot data for the medicine in this pharmacy
                    $pivotData = $pharmacy->medicines->first()?->pivot;

                    // Set medicine status as an independent object inside the pharmacy
                    $pharmacy->medicine_availability = [
                        'status'   => $pivotData ? $pivotData->status : 'not_listed',
                        'price'    => $pivotData ? (float) $pivotData->price : null,
                        'quantity' => $pivotData ? (int) $pivotData->quantity : 0,
                    ];

                    // Remove the medicines array to clean up the JSON response
                    unset($pharmacy->medicines);

                    return $pharmacy;
                });

                // ✨ Save search history in the background (first 10 results) ✨
                $returnedIds = $pharmacies->take(10)->pluck('id')->toArray();
                LogSearchHistoryJob::dispatchAfterResponse([
                    'user_id'               => $user->id,
                    'search_type'           => 'medicine',
                    'search_query'          => null,
                    'medicine_id'           => $medicine->id,
                    'lat'                   => $lat,
                    'lng'                   => $lng,
                    'results_count'         => $pharmacies->total(),
                    'returned_pharmacy_ids' => $returnedIds,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Medicine and available pharmacies fetched successfully.',
                    'data'    => [
                        'medicine'   => $medicine,
                        'pharmacies' => $pharmacies
                    ]
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred during the search.',
                'error'   => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    /**
     * Get the authenticated user's recent search history (Suggestions)
     */
    public function recentSearches()
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

            // Fetch the last 50 searches for the user, then filter the unique ones in memory
            $history = \App\Models\SearchHistory::with('medicine:id,name')
                ->where('user_id', $user->id)
                ->latest()
                ->take(50)
                ->get()
                ->unique(function ($item) {
                    // Create a unique signature so we don't show duplicate suggestions
                    return $item->search_type . '-' . $item->search_query . '-' . $item->medicine_id;
                })
                ->take(10) // Keep only the top 10 unique recent searches
                ->values(); // Reset array keys so JSON returns a clean array

            // Format the data perfectly for the Mobile Developer
            $formattedHistory = $history->map(function ($item) {
                return [
                    'search_type'  => $item->search_type,
                    'search_query' => $item->search_query,
                    'medicine_id'  => $item->medicine_id,

                    // ✨ Smart Display Text: The mobile app can just print this directly to the UI
                    'display_text' => $item->search_type === 'medicine'
                        ? ($item->medicine->name ?? 'Unknown Medicine')
                        : $item->search_query,

                    'time_ago'     => $item->created_at->diffForHumans(), // e.g., "2 hours ago"
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Recent searches fetched successfully.',
                'data'    => $formattedHistory
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while fetching search history.',
                'error'   => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }
}
