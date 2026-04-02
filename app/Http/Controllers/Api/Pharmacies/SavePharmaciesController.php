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
    public function togglePharmacy($id)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'message' => 'Unauthenticated'
                ], 401);
            }

            $result = $user->savedPharmacies()->toggle($id);

            return response()->json([
                'message' => 'Done',
                'status' => count($result['attached']) ? 'added' : 'removed'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage() // شيلها في production
            ], 500);
        }
    }



}
