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
    public function toggleMedicine($id)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'message' => 'Unauthenticated'
                ], 401);
            }

            $result = $user->savedMedicines()->toggle($id);

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







}
