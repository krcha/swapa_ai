<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApprovedPhoneModel;
use Illuminate\Http\Request;

class ApprovedPhoneModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ApprovedPhoneModel::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('model_name', 'LIKE', "%{$search}%")
                  ->orWhere('model_code', 'LIKE', "%{$search}%")
                  ->orWhere('brand_name', 'LIKE', "%{$search}%");
            });
        }

        // Filter by brand
        if ($request->filled('brand')) {
            $query->where('brand_name', $request->brand);
        }

        // Filter by model
        if ($request->filled('model')) {
            $query->where('model_name', $request->model);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'brand_name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        switch ($sortBy) {
            case 'model_name':
                $query->orderBy('model_name', $sortOrder);
                break;
            case 'model_code':
                $query->orderBy('model_code', $sortOrder);
                break;
            case 'sort_order':
                $query->orderBy('sort_order', $sortOrder);
                break;
            case 'is_active':
                $query->orderBy('is_active', $sortOrder);
                break;
            case 'created_at':
                $query->orderBy('created_at', $sortOrder);
                break;
            default:
                $query->orderBy('brand_name', $sortOrder)
                      ->orderBy('sort_order', 'asc')
                      ->orderBy('model_name', 'asc');
        }

        $models = $query->paginate(20)->withQueryString();
        $brands = ApprovedPhoneModel::getApprovedBrands();

        return view('admin.approved-models.index', compact('models', 'brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = ApprovedPhoneModel::getApprovedBrands();
        return view('admin.approved-models.create', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'model_name' => 'required|string|max:255',
            'model_code' => 'required|string|max:255|unique:approved_phone_models,model_code',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        ApprovedPhoneModel::create([
            'brand_name' => $request->brand_name,
            'model_name' => $request->model_name,
            'model_code' => $request->model_code,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.approved-models.index')
            ->with('success', 'Phone model added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ApprovedPhoneModel $approvedPhoneModel)
    {
        return view('admin.approved-models.show', compact('approvedPhoneModel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApprovedPhoneModel $approvedPhoneModel)
    {
        $brands = ApprovedPhoneModel::getApprovedBrands();
        return view('admin.approved-models.edit', compact('approvedPhoneModel', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ApprovedPhoneModel $approvedPhoneModel)
    {
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'model_name' => 'required|string|max:255',
            'model_code' => 'required|string|max:255|unique:approved_phone_models,model_code,' . $approvedPhoneModel->id,
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $approvedPhoneModel->update([
            'brand_name' => $request->brand_name,
            'model_name' => $request->model_name,
            'model_code' => $request->model_code,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.approved-models.index')
            ->with('success', 'Phone model updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApprovedPhoneModel $approvedPhoneModel)
    {
        $approvedPhoneModel->delete();

        return redirect()->route('admin.approved-models.index')
            ->with('success', 'Phone model deleted successfully!');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(ApprovedPhoneModel $approvedPhoneModel)
    {
        $approvedPhoneModel->update([
            'is_active' => !$approvedPhoneModel->is_active
        ]);

        return redirect()->back()
            ->with('success', 'Phone model status updated successfully!');
    }
}