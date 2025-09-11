<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Category;
use App\Models\Brand;
use App\Models\TokenTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::where('status', 'active')->with(['user', 'brand', 'category']);
        
        // Apply brand filter (match exact brand names)
        if ($request->filled('brand') && $request->brand !== 'all') {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('name', $request->brand);
            });
        }
        
        // Apply category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('name', $request->category);
            });
        }
        
        // Apply condition filter
        if ($request->filled('condition') && $request->condition !== 'all') {
            $query->where('condition', $request->condition);
        }
        
        // Price range filters
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float)$request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float)$request->max_price);
        }
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('brand', function($brandQuery) use ($search) {
                      $brandQuery->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('category', function($categoryQuery) use ($search) {
                      $categoryQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Sort options
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        switch ($sortBy) {
            case 'price':
                $query->orderBy('price', $sortOrder);
                break;
            case 'condition':
                $query->orderByRaw("CASE 
                    WHEN condition = 'like_new' THEN 1 
                    WHEN condition = 'excellent' THEN 2 
                    WHEN condition = 'good' THEN 3 
                    WHEN condition = 'fair' THEN 4 
                    ELSE 5 END");
                break;
            case 'created_at':
            default:
                $query->orderBy('created_at', $sortOrder);
                break;
        }
        
        $listings = $query->paginate(12)->withQueryString();
        
        // Get CLEAN brand and category lists (just the names)
        $brands = Listing::where('status', 'active')
            ->whereNotNull('brand_id')
            ->with('brand')
            ->get()
            ->pluck('brand.name')
            ->filter()
            ->unique()
            ->sort()
            ->values();
            
        $categories = Listing::where('status', 'active')
            ->whereNotNull('category_id')
            ->with('category')
            ->get()
            ->pluck('category.name')
            ->filter()
            ->unique()
            ->sort()
            ->values();
    
        return view('listings.index', compact('listings', 'brands', 'categories'));
    }

    public function show($id)
    {
        $listing = Listing::with(['user', 'category', 'brand', 'images'])
            ->findOrFail($id);

        // Increment view count
        $listing->incrementViewCount();

        return response()->json([
            'success' => true,
            'data' => $listing
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Check if user can create listing (phone verification + quota check)
        if (!$user->canCreateListing()) {
            $currentPlan = $user->currentPlan();
            $remainingQuota = $user->getRemainingListingQuota();
            
            if (!$user->hasPhoneVerification()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phone verification is required to create listings. Please verify your phone number first.',
                    'requires_phone_verification' => true
                ], 403);
            }
            
            if ($remainingQuota === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have reached your monthly listing limit. Upgrade your plan to create more listings.',
                    'current_plan' => $currentPlan ? $currentPlan->name : 'Free',
                    'listing_limit' => $currentPlan ? $currentPlan->listing_limit : 1,
                    'requires_upgrade' => true
                ], 403);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to create listing. Please check your account status.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|in:like_new,excellent,good,fair',
            'storage' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'battery_health' => 'nullable|integer|min:0|max:100',
            'screen_condition' => 'nullable|string|max:100',
            'body_condition' => 'nullable|string|max:100',
            'carrier' => 'nullable|string|max:100',
            'contact_preference' => 'required|in:phone,email,both',
            'images' => 'required|array|min:3|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create listing
        $listing = Listing::create([
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'storage' => $request->storage,
            'color' => $request->color,
            'battery_health' => $request->battery_health,
            'screen_condition' => $request->screen_condition,
            'body_condition' => $request->body_condition,
            'carrier' => $request->carrier,
            'contact_preference' => $request->contact_preference,
            'expires_at' => now()->addDays(30),
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('listings', 'public');
                
                $listing->images()->create([
                    'image_path' => $path,
                    'image_url' => asset('storage/' . $path),
                    'sort_order' => $index,
                    'is_primary' => $index === 0
                ]);
            }
        }

        // No token consumption needed for subscription model
        // Quota is enforced by the subscription system

        return response()->json([
            'success' => true,
            'message' => 'Listing created successfully',
            'data' => $listing->load(['category', 'brand', 'images'])
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $listing = Listing::where('user_id', Auth::id())->findOrFail($id);

        if ($listing->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending listings can be updated'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:2000',
            'price' => 'sometimes|required|numeric|min:0',
            'condition' => 'sometimes|required|in:like_new,excellent,good,fair',
            'storage' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'battery_health' => 'nullable|integer|min:0|max:100',
            'screen_condition' => 'nullable|string|max:100',
            'body_condition' => 'nullable|string|max:100',
            'carrier' => 'nullable|string|max:100',
            'contact_preference' => 'sometimes|required|in:phone,email,both',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $listing->update($request->only([
            'title', 'description', 'price', 'condition', 'storage',
            'color', 'battery_health', 'screen_condition', 'body_condition',
            'carrier', 'contact_preference'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Listing updated successfully',
            'data' => $listing->load(['category', 'brand', 'images'])
        ]);
    }

    public function destroy($id)
    {
        $listing = Listing::where('user_id', Auth::id())->findOrFail($id);

        if ($listing->status === 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Active listings cannot be deleted'
            ], 403);
        }

        // No token refund needed for subscription model
        // Quota is managed by subscription system

        $listing->delete();

        return response()->json([
            'success' => true,
            'message' => 'Listing deleted successfully'
        ]);
    }

    public function renew($id)
    {
        $listing = Listing::where('user_id', Auth::id())->findOrFail($id);

        if (!$listing->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Only expired listings can be renewed'
            ], 403);
        }

        if (!Auth::user()->canCreateListing()) {
            $currentPlan = Auth::user()->currentPlan();
            $remainingQuota = Auth::user()->getRemainingListingQuota();
            
            return response()->json([
                'success' => false,
                'message' => 'You have reached your monthly listing limit. Upgrade your plan to renew listings.',
                'current_plan' => $currentPlan ? $currentPlan->name : 'Free',
                'listing_limit' => $currentPlan ? $currentPlan->listing_limit : 1,
                'remaining_quota' => $remainingQuota,
                'requires_upgrade' => true
            ], 403);
        }

        $listing->renew();
        // No token consumption needed for subscription model

        return response()->json([
            'success' => true,
            'message' => 'Listing renewed successfully',
            'data' => $listing
        ]);
    }
}
