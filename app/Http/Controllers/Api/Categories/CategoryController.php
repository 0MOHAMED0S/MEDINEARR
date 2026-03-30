<?php

namespace App\Http\Controllers\Api\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

//get all categories for user to show them in the home page
    /**
     * Display a listing of the resource.
     * try and catch
     */
    public function index(Request $request)
    {
        try {
            $perPage = min($request->input('per_page', 10), 50);     

            $categories = Category::where('status', 1)
                ->select('id', 'name', 'image', 'description')
                ->latest()
                ->paginate($perPage)
                ->through(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'description' => $category->description,
                        'image' => $category->image 
                            ? asset('storage/' . $category->image) 
                            : null,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Categories retrieved successfully',
                'data' => $categories
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
