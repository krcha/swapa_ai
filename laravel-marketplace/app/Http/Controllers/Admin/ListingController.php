<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Listing::with(['user', 'category', 'brand', 'images'])
            ->withCount(['images']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('model_name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('first_name', 'LIKE', "%{$search}%")
                               ->orWhere('last_name', 'LIKE', "%{$search}%")
                               ->orWhere('email', 'LIKE', "%{$search}%")
                               ->orWhere('business_name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by brand
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // Filter by condition
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                case 'featured':
                    $query->where('is_featured', true);
                    break;
                case 'priority':
                    $query->where('has_priority_listing', true);
                    break;
            }
        }

        // Filter by price range
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Filter by date range
        if ($request->filled('created_after')) {
            $query->where('created_at', '>=', $request->created_after);
        }
        if ($request->filled('created_before')) {
            $query->where('created_at', '<=', $request->created_before);
        }

        // Filter by user type
        if ($request->filled('user_type')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('user_type', $request->user_type);
            });
        }

        // Filter by verification status
        if ($request->filled('user_verified')) {
            if ($request->user_verified === 'verified') {
                $query->whereHas('user', function($q) {
                    $q->where('is_email_verified', true)
                      ->where('is_sms_verified', true);
                });
            } else {
                $query->whereHas('user', function($q) {
                    $q->where(function($subQ) {
                        $subQ->where('is_email_verified', false)
                             ->orWhere('is_sms_verified', false);
                    });
                });
            }
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('city', 'LIKE', "%{$request->location}%")
                  ->orWhere('country', 'LIKE', "%{$request->location}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Handle special sorting cases
        if ($sortBy === 'user_name') {
            $query->join('users', 'listings.user_id', '=', 'users.id')
                  ->orderBy('users.first_name', $sortOrder)
                  ->orderBy('users.last_name', $sortOrder)
                  ->select('listings.*');
        } elseif ($sortBy === 'category_name') {
            $query->join('categories', 'listings.category_id', '=', 'categories.id')
                  ->orderBy('categories.name', $sortOrder)
                  ->select('listings.*');
        } elseif ($sortBy === 'brand_name') {
            $query->join('brands', 'listings.brand_id', '=', 'brands.id')
                  ->orderBy('brands.name', $sortOrder)
                  ->select('listings.*');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $listings = $query->paginate(20)->withQueryString();

        // Get filter options
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $conditions = ['new', 'like_new', 'good', 'fair', 'poor'];
        $userTypes = ['personal', 'business'];

        // Statistics
        $stats = [
            'total' => Listing::count(),
            'active' => Listing::where('is_active', true)->count(),
            'inactive' => Listing::where('is_active', false)->count(),
            'featured' => Listing::where('is_featured', true)->count(),
            'priority' => Listing::where('has_priority_listing', true)->count(),
            'today' => Listing::whereDate('created_at', today())->count(),
            'this_week' => Listing::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Listing::whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.listings.index', compact(
            'listings', 
            'categories', 
            'brands', 
            'conditions', 
            'userTypes', 
            'stats'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Listing $listing)
    {
        $listing->load(['user', 'category', 'brand', 'images']);
        return view('admin.listings.show', compact('listing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listing $listing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Listing $listing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing)
    {
        $listing->delete();
        return redirect()->route('admin.listings.index')
            ->with('success', 'Listing deleted successfully.');
    }

    /**
     * Toggle listing status
     */
    public function toggleStatus(Listing $listing)
    {
        $listing->update(['is_active' => !$listing->is_active]);
        
        $status = $listing->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Listing {$status} successfully.");
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Listing $listing)
    {
        $listing->update(['is_featured' => !$listing->is_featured]);
        
        $status = $listing->is_featured ? 'featured' : 'unfeatured';
        return redirect()->back()
            ->with('success', "Listing {$status} successfully.");
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $listingIds = $request->listing_ids;

        if (!$listingIds || !$action) {
            return redirect()->back()->with('error', 'Please select listings and an action.');
        }

        switch ($action) {
            case 'activate':
                Listing::whereIn('id', $listingIds)->update(['is_active' => true]);
                $message = 'Selected listings activated successfully.';
                break;
            case 'deactivate':
                Listing::whereIn('id', $listingIds)->update(['is_active' => false]);
                $message = 'Selected listings deactivated successfully.';
                break;
            case 'feature':
                Listing::whereIn('id', $listingIds)->update(['is_featured' => true]);
                $message = 'Selected listings featured successfully.';
                break;
            case 'unfeature':
                Listing::whereIn('id', $listingIds)->update(['is_featured' => false]);
                $message = 'Selected listings unfeatured successfully.';
                break;
            case 'delete':
                Listing::whereIn('id', $listingIds)->delete();
                $message = 'Selected listings deleted successfully.';
                break;
            default:
                return redirect()->back()->with('error', 'Invalid action selected.');
        }

        return redirect()->back()->with('success', $message);
    }
}