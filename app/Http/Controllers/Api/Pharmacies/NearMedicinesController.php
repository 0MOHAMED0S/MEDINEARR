<?php

namespace App\Http\Controllers\Api\Pharmacies;
use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Exception;

class NearMedicinesController extends Controller
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

            $pharmacies = Pharmacy::with(['medicines'])
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

            $medicines = $pharmacies
                ->pluck('medicines')   
                ->flatten()             
                ->shuffle()           
                ->unique('id')       
                ->values();

               
            $currentPage = request()->input('page', 1);
            $items = $medicines->forPage($currentPage, $perPage);

            return response()->json([
                'message' => 'Nearest medicines fetched successfully',
                'data' => [
                    'current_page' => (int)$currentPage,
                    'data' => $items->values(),
                    'per_page' => (int)$perPage,
                    'total' => $medicines->count()
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error fetching nearest medicines',
                'error' => $e->getMessage()
            ], 500);
        }
    }  

}
