<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::active();

        if ($request->has('category_id')) {
            $query->byCategory($request->category_id);
        }

        $brands = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $brands
        ]);
    }

    public function show($id)
    {
        $brand = Brand::with('category')
            ->active()
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $brand
        ]);
    }
}
