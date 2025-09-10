<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\User;
use App\Models\TokenTransaction;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'verified_users' => User::where('is_verified', true)->count(),
            'total_listings' => Listing::count(),
            'active_listings' => Listing::active()->count(),
            'pending_listings' => Listing::where('status', 'pending')->count(),
            'total_conversations' => Conversation::count(),
            'tokens_sold' => TokenTransaction::where('type', 'credit')
                ->where('description', 'like', '%Purchased%')
                ->sum('amount'),
            'revenue' => TokenTransaction::where('type', 'credit')
                ->where('description', 'like', '%Purchased%')
                ->count() * 5, // Assuming 5 EUR per token
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function listings(Request $request)
    {
        $query = Listing::with(['user', 'category', 'brand', 'images']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $listings = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $listings
        ]);
    }

    public function approveListing($id)
    {
        $listing = Listing::findOrFail($id);

        if ($listing->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending listings can be approved'
            ], 400);
        }

        $listing->update([
            'status' => 'active',
            'expires_at' => now()->addDays(30)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Listing approved successfully',
            'data' => $listing
        ]);
    }

    public function rejectListing($id)
    {
        $listing = Listing::findOrFail($id);

        if ($listing->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending listings can be rejected'
            ], 400);
        }

        $listing->update(['status' => 'rejected']);

        // Refund token to user
        TokenTransaction::refundToken($listing->user, $listing, 'Listing rejected by admin');

        return response()->json([
            'success' => true,
            'message' => 'Listing rejected and token refunded',
            'data' => $listing
        ]);
    }

    public function users(Request $request)
    {
        $query = User::withCount(['listings', 'conversations']);

        if ($request->has('verified')) {
            $query->where('is_verified', $request->verified);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function statistics()
    {
        $stats = [
            'users_by_month' => User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
            'listings_by_month' => Listing::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
            'top_categories' => DB::table('listings')
                ->join('categories', 'listings.category_id', '=', 'categories.id')
                ->selectRaw('categories.name, COUNT(*) as count')
                ->groupBy('categories.id', 'categories.name')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'top_brands' => DB::table('listings')
                ->join('brands', 'listings.brand_id', '=', 'brands.id')
                ->selectRaw('brands.name, COUNT(*) as count')
                ->groupBy('brands.id', 'brands.name')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
