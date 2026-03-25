<?php

namespace App\Http\Controllers\Api\Pharmacies;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Exception;

class NearMedicinesController extends Controller
{
    /**
     * Display a listing of the nearest medicines.
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->user();

            // 1. Case: User or Location Missing
            if (!$user || !$user->latitude || !$user->longitude) {
                return response()->json([
                    'success' => false,
                    'message' => 'User location is missing. Please enable location services to find nearby medicines.',
                    'data'    => null
                ], 400); // 400 Bad Request
            }

            $lat = $user->latitude;
            $lng = $user->longitude;

            $radius = 6371; // Earth's radius in kilometers
            $perPage = (int) $request->input('per_page', 10);

            // Fetching Pharmacies ordered by distance
            $pharmacies = Pharmacy::with(['medicines'])
                ->where('is_active', true) // التأكد أن الصيدلية مفعلة
                ->whereNotNull('lat')
                ->whereNotNull('lng')
                ->selectRaw("
                    *,
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
                ->get();

            // Extracting medicines from the nearest pharmacies
            $medicines = $pharmacies
                ->pluck('medicines')
                ->flatten()
                ->unique('id')
                ->values();

            // Manual Pagination Setup
            $currentPage = Paginator::resolveCurrentPage() ?: 1;

            // 2. Case: No medicines found nearby
            if ($medicines->isEmpty()) {
                // Return an empty paginator structure to prevent mobile app crashes
                $emptyPaginator = new LengthAwarePaginator([], 0, $perPage, $currentPage, [
                    'path' => Paginator::resolveCurrentPath()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'No medicines found nearby at the moment.',
                    'data'    => $emptyPaginator
                ], 200);
            }

            // Slicing data for the current page
            $currentPageItems = $medicines->slice(($currentPage - 1) * $perPage, $perPage)->values();

            $paginator = new LengthAwarePaginator(
                $currentPageItems,
                $medicines->count(),
                $perPage,
                $currentPage,
                ['path' => Paginator::resolveCurrentPath()]
            );

            // 3. Case: Success with Data
            return response()->json([
                'success' => true,
                'message' => 'Nearest medicines fetched successfully.',
                'data'    => $paginator
            ], 200);

        } catch (Exception $e) {
            // 4. Case: Server Error
            return response()->json([
                'success' => false,
                'message' => 'An unexpected server error occurred while fetching nearest medicines.',
                'error'   => $e->getMessage(), // You can remove this in production for security
                'data'    => null
            ], 500); // 500 Internal Server Error
        }
    }
}
