<?php

namespace App\Http\Controllers;

use App\Models\ContactClick;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContactClickController extends Controller
{
    /**
     * Track a contact seller button click
     */
    public function trackClick(Request $request, Listing $listing): JsonResponse
    {
        try {
            // Track the click
            $click = ContactClick::trackClick($listing->id, $request);
            
            return response()->json([
                'success' => true,
                'message' => 'Click tracked successfully',
                'click_id' => $click->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to track click',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get click statistics for admin
     */
    public function getStats(): JsonResponse
    {
        try {
            $stats = ContactClick::getClickStats();
            $topListings = ContactClick::getTopClickedListings(5);
            $trends = ContactClick::getClickTrends(7); // Last 7 days

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats,
                    'top_listings' => $topListings,
                    'trends' => $trends,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch click statistics',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get click statistics for a specific listing
     */
    public function getListingStats(Listing $listing): JsonResponse
    {
        try {
            $totalClicks = ContactClick::byListing($listing->id)->count();
            $todayClicks = ContactClick::byListing($listing->id)->today()->count();
            $thisWeekClicks = ContactClick::byListing($listing->id)->thisWeek()->count();
            $thisMonthClicks = ContactClick::byListing($listing->id)->thisMonth()->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'listing_id' => $listing->id,
                    'listing_title' => $listing->title,
                    'total_clicks' => $totalClicks,
                    'today_clicks' => $todayClicks,
                    'this_week_clicks' => $thisWeekClicks,
                    'this_month_clicks' => $thisMonthClicks,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch listing click statistics',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}