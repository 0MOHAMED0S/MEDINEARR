<?php

namespace App\Http\Controllers\Api\Ads;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;

class AdController extends Controller
{
    //get all ads for user and show selected ads for 2 types of ads (cover or paner text) in the home page
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $ads = Ad::where('is_active', true)
                ->select('id', 'type', 'title', 'description', 'bg_color', 'image', 'link', 'coupon_id')
                ->latest()
                ->get()
                ->map(function ($ad) {

                    return [
                        'id' => $ad->id,
                        'type' => $ad->type,    
                        'title' => $ad->title,
                        'description' => $ad->type === 'banner' ? $ad->description : null,
                        'bg_color' => $ad->type === 'banner' ? $ad->bg_color : null,
                        'image' => $ad->image ? asset('storage/' . $ad->image) : null,
                        'link' => $ad->link,
                        'coupon_id' => $ad->type === 'banner' ? $ad->coupon_id : null,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Ads retrieved successfully',
                'data' => $ads
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving ads',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
