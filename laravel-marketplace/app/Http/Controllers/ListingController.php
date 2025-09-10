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
        $query = Listing::with(['user', 'category', 'brand', 'images'])
            ->active();

        // Search
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        // Filters
        if ($request->has('category_id') && $request->category_id) {
            $query->byCategory($request->category_id);
        }

        if ($request->has('brand_id') && $request->brand_id) {
            $query->byBrand($request->brand_id);
        }

        if ($request->has('condition') && $request->condition) {
            $query->byCondition($request->condition);
        }

        if ($request->has('min_price') && $request->min_price) {
            $query->byPriceRange($request->min_price, $request->max_price ?? 999999);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['created_at', 'price', 'view_count'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $listings = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $listings
        ]);
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

        if (!$user->canCreateListing()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be verified and have tokens to create a listing'
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

        // Consume token
        TokenTransaction::consumeToken($user, $listing, 'Listing creation');

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

        // Refund token if listing is pending
        if ($listing->status === 'pending') {
            TokenTransaction::refundToken(Auth::user(), $listing, 'Listing deletion');
        }

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
            return response()->json([
                'success' => false,
                'message' => 'You need tokens to renew a listing'
            ], 403);
        }

        $listing->renew();
        TokenTransaction::consumeToken(Auth::user(), $listing, 'Listing renewal');

        return response()->json([
            'success' => true,
            'message' => 'Listing renewed successfully',
            'data' => $listing
        ]);
    }
}
