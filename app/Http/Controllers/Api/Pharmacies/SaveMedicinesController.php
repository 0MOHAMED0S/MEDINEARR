<?php

namespace App\Http\Controllers\Api\Pharmacies;

use App\Http\Controllers\Controller;
use App\Models\SavedMedicine;
use Illuminate\Http\Request;

class SaveMedicinesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function toggleMedicine(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'message' => 'Unauthenticated'
                ], 401);
            }

            $medicineId = $request->input('medicine_id');

            if (!$medicineId) {
                return response()->json([
                    'message' => 'medicine_id is required'
                ], 400);
            }

            $result = $user->savedMedicines()->toggle($medicineId);

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

            $medicines = $user->savedMedicines;        

            return response()->json([
                'message' => 'Saved medicines fetched successfully',
                'data' => $medicines
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
