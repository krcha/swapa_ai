<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminListingController extends Controller
{
    /**
     * Display a listing of listings for moderation
     */
    public function index(Request $request)
    {
        $query = Listing::with(['user', 'category', 'brand', 'images']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('first_name', 'like', "%{$search}%")
                               ->orWhere('last_name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by moderation status
        if ($request->has('moderation_status')) {
            switch ($request->moderation_status) {
                case 'pending':
                    $query->where('status', 'pending');
                    break;
                case 'approved':
                    $query->where('status', 'active');
                    break;
                case 'rejected':
                    $query->where('status', 'rejected');
                    break;
                case 'flagged':
                    $query->where('is_flagged', true);
                    break;
            }
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $listings = $query->paginate(20);

        // Get statistics
        $stats = [
            'total_listings' => Listing::count(),
            'pending_listings' => Listing::where('status', 'pending')->count(),
            'active_listings' => Listing::where('status', 'active')->count(),
            'rejected_listings' => Listing::where('status', 'rejected')->count(),
            'flagged_listings' => Listing::where('is_flagged', true)->count(),
            'featured_listings' => Listing::where('is_featured', true)->count(),
        ];

        return view('admin.listings.index', compact('listings', 'stats'));
    }

    /**
     * Approve a listing
     */
    public function approve(Request $request, Listing $listing)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $listing->update([
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'moderation_notes' => $request->notes,
        ]);

        Log::info('Admin approved listing', [
            'admin_id' => auth()->id(),
            'listing_id' => $listing->id,
            'user_id' => $listing->user_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Listing approved successfully.',
        ]);
    }

    /**
     * Reject a listing
     */
    public function reject(Request $request, Listing $listing)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $listing->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
            'rejection_reason' => $request->reason,
            'moderation_notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Listing rejected successfully.',
        ]);
    }

    /**
     * Feature a listing
     */
    public function feature(Listing $listing)
    {
        $listing->update([
            'is_featured' => true,
            'featured_at' => now(),
            'featured_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Listing featured successfully.',
        ]);
    }

    /**
     * Flag a listing
     */
    public function flag(Request $request, Listing $listing)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $listing->update([
            'is_flagged' => true,
            'flagged_at' => now(),
            'flagged_by' => auth()->id(),
            'flag_reason' => $request->reason,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Listing flagged successfully.',
        ]);
    }

    /**
     * Bulk actions on listings
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,feature,unfeature,flag,unflag',
            'listing_ids' => 'required|array|min:1',
            'listing_ids.*' => 'exists:listings,id',
        ]);

        $listingIds = $request->listing_ids;
        $action = $request->action;
        $count = 0;

        switch ($action) {
            case 'approve':
                Listing::whereIn('id', $listingIds)->update([
                    'status' => 'active',
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                ]);
                $count = count($listingIds);
                break;

            case 'reject':
                Listing::whereIn('id', $listingIds)->update([
                    'status' => 'rejected',
                    'rejected_at' => now(),
                    'rejected_by' => auth()->id(),
                ]);
                $count = count($listingIds);
                break;

            case 'feature':
                Listing::whereIn('id', $listingIds)->update([
                    'is_featured' => true,
                    'featured_at' => now(),
                    'featured_by' => auth()->id(),
                ]);
                $count = count($listingIds);
                break;

            case 'unfeature':
                Listing::whereIn('id', $listingIds)->update([
                    'is_featured' => false,
                    'featured_at' => null,
                    'featured_by' => null,
                ]);
                $count = count($listingIds);
                break;
        }

        return response()->json([
            'success' => true,
            'message' => "Bulk action '{$action}' performed on {$count} listings successfully.",
        ]);
    }

    /**
     * Get listing statistics
     */
    public function statistics()
    {
        $stats = [
            'total_listings' => Listing::count(),
            'active_listings' => Listing::where('status', 'active')->count(),
            'pending_listings' => Listing::where('status', 'pending')->count(),
            'rejected_listings' => Listing::where('status', 'rejected')->count(),
            'featured_listings' => Listing::where('is_featured', true)->count(),
            'flagged_listings' => Listing::where('is_flagged', true)->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats,
        ]);
    }
}