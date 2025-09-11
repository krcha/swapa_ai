<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Favorite;
use App\Models\PriceAlert;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BuyerController extends Controller
{
    /**
     * Display buyer dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get user's favorites
        $favorites = $user->favoriteListings()
            ->with(['user', 'category', 'brand', 'images'])
            ->latest()
            ->limit(10)
            ->get();

        // Get recent conversations
        $conversations = $user->getRecentConversations(5);

        // Get price alerts
        $priceAlerts = $user->priceAlerts()
            ->with('listing')
            ->active()
            ->latest()
            ->limit(5)
            ->get();

        // Get recently viewed listings (from session)
        $recentlyViewed = $this->getRecentlyViewed();

        // Get unread message count
        $unreadCount = $user->getUnreadMessageCount();

        return view('buyer.dashboard', compact(
            'favorites', 'conversations', 'priceAlerts', 'recentlyViewed', 'unreadCount'
        ));
    }

    /**
     * Display favorites page
     */
    public function favorites()
    {
        $user = Auth::user();
        
        $favorites = $user->favoriteListings()
            ->with(['user', 'category', 'brand', 'images'])
            ->latest()
            ->paginate(20);

        return view('buyer.favorites', compact('favorites'));
    }

    /**
     * Toggle favorite status
     */
    public function toggleFavorite(Listing $listing)
    {
        $user = Auth::user();
        
        $isFavorited = Favorite::toggle($user->id, $listing->id);
        
        return response()->json([
            'success' => true,
            'is_favorited' => $isFavorited,
            'message' => $isFavorited ? 'Added to favorites' : 'Removed from favorites',
        ]);
    }

    /**
     * Display price alerts page
     */
    public function priceAlerts()
    {
        $user = Auth::user();
        
        $priceAlerts = $user->priceAlerts()
            ->with('listing')
            ->latest()
            ->paginate(20);

        return view('buyer.price-alerts', compact('priceAlerts'));
    }

    /**
     * Create price alert
     */
    public function createPriceAlert(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'listing_id' => 'nullable|exists:listings,id',
            'search_query' => 'nullable|string|max:255',
            'target_price' => 'required|numeric|min:0',
            'condition' => 'required|in:below,above,equal',
        ]);

        // Check if listing exists and is active
        if ($request->listing_id) {
            $listing = Listing::findOrFail($request->listing_id);
            if (!$listing->isActive()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This listing is no longer available.',
                ], 400);
            }
        }

        // Create price alert
        $priceAlert = PriceAlert::create([
            'user_id' => $user->id,
            'listing_id' => $request->listing_id,
            'search_query' => $request->search_query,
            'target_price' => $request->target_price,
            'condition' => $request->condition,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Price alert created successfully.',
            'price_alert' => $priceAlert,
        ]);
    }

    /**
     * Delete price alert
     */
    public function deletePriceAlert(PriceAlert $priceAlert)
    {
        $user = Auth::user();
        
        // Check if user owns this price alert
        if ($priceAlert->user_id !== $user->id) {
            abort(403, 'Unauthorized access to price alert.');
        }

        $priceAlert->delete();

        return response()->json([
            'success' => true,
            'message' => 'Price alert deleted successfully.',
        ]);
    }

    /**
     * Display recently viewed listings
     */
    public function recentlyViewed()
    {
        $recentlyViewed = $this->getRecentlyViewed();
        
        return view('buyer.recently-viewed', compact('recentlyViewed'));
    }

    /**
     * Add listing to recently viewed
     */
    public function addToRecentlyViewed(Listing $listing)
    {
        $user = Auth::user();
        
        // Add to session
        $recentlyViewed = session('recently_viewed', []);
        
        // Remove if already exists
        $recentlyViewed = array_filter($recentlyViewed, function($id) use ($listing) {
            return $id !== $listing->id;
        });
        
        // Add to beginning
        array_unshift($recentlyViewed, $listing->id);
        
        // Keep only last 20
        $recentlyViewed = array_slice($recentlyViewed, 0, 20);
        
        session(['recently_viewed' => $recentlyViewed]);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Get recently viewed listings
     */
    private function getRecentlyViewed()
    {
        $recentlyViewedIds = session('recently_viewed', []);
        
        if (empty($recentlyViewedIds)) {
            return collect();
        }

        return Listing::whereIn('id', $recentlyViewedIds)
            ->with(['user', 'category', 'brand', 'images'])
            ->active()
            ->get()
            ->sortBy(function($listing) use ($recentlyViewedIds) {
                return array_search($listing->id, $recentlyViewedIds);
            });
    }

    /**
     * Advanced search with suggestions
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $categoryId = $request->get('category_id');
        $brandId = $request->get('brand_id');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $condition = $request->get('condition');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $listings = Listing::with(['user', 'category', 'brand', 'images'])
            ->active();

        // Search query
        if ($query) {
            $listings->search($query);
        }

        // Category filter
        if ($categoryId) {
            $listings->byCategory($categoryId);
        }

        // Brand filter
        if ($brandId) {
            $listings->byBrand($brandId);
        }

        // Price range filter
        if ($minPrice || $maxPrice) {
            $minPrice = $minPrice ?: 0;
            $maxPrice = $maxPrice ?: 999999;
            $listings->byPriceRange($minPrice, $maxPrice);
        }

        // Condition filter
        if ($condition) {
            $listings->byCondition($condition);
        }

        // Sorting
        $listings->orderBy($sortBy, $sortOrder);

        $listings = $listings->paginate(20);

        // Get filter options
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        // Get search suggestions
        $suggestions = $this->getSearchSuggestions($query);

        return view('buyer.search', compact(
            'listings', 'categories', 'brands', 'suggestions', 'query'
        ));
    }

    /**
     * Get search suggestions
     */
    private function getSearchSuggestions($query)
    {
        if (strlen($query) < 2) {
            return collect();
        }

        // Get popular search terms
        $suggestions = collect();
        
        // Add category suggestions
        $categorySuggestions = Category::where('name', 'like', "%{$query}%")
            ->orderBy('name')
            ->limit(5)
            ->get()
            ->map(function($category) {
                return [
                    'type' => 'category',
                    'text' => $category->name,
                    'url' => route('buyer.search', ['q' => $category->name]),
                ];
            });

        $suggestions = $suggestions->merge($categorySuggestions);

        // Add brand suggestions
        $brandSuggestions = Brand::where('name', 'like', "%{$query}%")
            ->orderBy('name')
            ->limit(5)
            ->get()
            ->map(function($brand) {
                return [
                    'type' => 'brand',
                    'text' => $brand->name,
                    'url' => route('buyer.search', ['q' => $brand->name]),
                ];
            });

        $suggestions = $suggestions->merge($brandSuggestions);

        // Add listing title suggestions
        $listingSuggestions = Listing::where('title', 'like', "%{$query}%")
            ->active()
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function($listing) {
                return [
                    'type' => 'listing',
                    'text' => $listing->title,
                    'url' => route('listings.show', $listing),
                ];
            });

        $suggestions = $suggestions->merge($listingSuggestions);

        return $suggestions->take(10);
    }

    /**
     * Get search suggestions (AJAX)
     */
    public function getSuggestions(Request $request)
    {
        $query = $request->get('q', '');
        $suggestions = $this->getSearchSuggestions($query);

        return response()->json([
            'success' => true,
            'suggestions' => $suggestions,
        ]);
    }

    /**
     * Get similar listings
     */
    public function getSimilarListings(Listing $listing)
    {
        $similarListings = Listing::where('id', '!=', $listing->id)
            ->where('category_id', $listing->category_id)
            ->where('brand_id', $listing->brand_id)
            ->active()
            ->with(['user', 'category', 'brand', 'images'])
            ->limit(6)
            ->get();

        return response()->json([
            'success' => true,
            'listings' => $similarListings,
        ]);
    }

    /**
     * Get user's purchase history
     */
    public function purchaseHistory()
    {
        $user = Auth::user();
        
        // This would be implemented when payment system is integrated
        $purchases = collect(); // Placeholder

        return view('buyer.purchase-history', compact('purchases'));
    }

    /**
     * Get user's saved searches
     */
    public function savedSearches()
    {
        $user = Auth::user();
        
        $savedSearches = $user->priceAlerts()
            ->whereNotNull('search_query')
            ->with('listing')
            ->latest()
            ->paginate(20);

        return view('buyer.saved-searches', compact('savedSearches'));
    }
}