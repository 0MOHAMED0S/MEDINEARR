<?php

namespace App\Http\Controllers\Api\Pharmacies;

use App\Http\Controllers\Controller;
use App\Models\SavedPharmacy;
use Illuminate\Http\Request;

class SavePharmaciesController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function togglePharmacy(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'message' => 'Unauthenticated'
                ], 401);
            }

            $pharmacyId = $request->input('pharmacy_id');

            $result = $user->savedPharmacies()->toggle($pharmacyId);

            return response()->json([
                'message' => 'Done',
                'status' => count($result['attached']) ? 'added' : 'removed'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function index()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'message' => 'Unauthenticated'
                ], 401);
            }

            $pharmacies = $user->savedPharmacies;    

            return response()->json([
                'message' => 'Saved pharmacies fetched successfully',
                'data' => $pharmacies
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
