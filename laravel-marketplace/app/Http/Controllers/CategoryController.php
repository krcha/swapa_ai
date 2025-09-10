<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')
            ->active()
            ->root()
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function show($id)
    {
        $category = Category::with(['children', 'brands'])
            ->active()
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }
}
