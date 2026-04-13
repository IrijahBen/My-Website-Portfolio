<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            // Fetch all categories, ordered by their display_order
            $categories = Category::orderBy('display_order', 'asc')->get();
            
            return response()->json([
                'success' => true,
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch categories: ' . $e->getMessage()
            ], 500);
        }
    }
}
