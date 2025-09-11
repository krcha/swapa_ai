<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Listing;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminAnalyticsController extends Controller
{
    /**
     * Display analytics dashboard
     */
    public function index()
    {
        // Get overview statistics
        $overview = $this->getOverviewStats();
        
        // Get revenue analytics
        $revenue = $this->getRevenueAnalytics();
        
        // Get user analytics
        $users = $this->getUserAnalytics();
        
        // Get listing analytics
        $listings = $this->getListingAnalytics();

        return view('admin.analytics.index', compact(
            'overview', 'revenue', 'users', 'listings'
        ));
    }

    /**
     * Get overview statistics
     */
    private function getOverviewStats()
    {
        return [
            'total_users' => User::count(),
            'active_users' => User::whereHas('subscriptions', function ($q) {
                $q->where('status', 'active')->where('ends_at', '>', now());
            })->count(),
            'total_listings' => Listing::count(),
            'active_listings' => Listing::where('status', 'active')->count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'monthly_revenue' => Payment::where('status', 'completed')
                ->where('created_at', '>=', now()->startOfMonth())
                ->sum('amount'),
            'pending_listings' => Listing::where('status', 'pending')->count(),
            'flagged_listings' => Listing::where('is_flagged', true)->count(),
        ];
    }

    /**
     * Get revenue analytics
     */
    private function getRevenueAnalytics()
    {
        // Monthly revenue for last 12 months
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Payment::where('status', 'completed')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');
            
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue,
            ];
        }

        // Revenue by payment gateway
        $revenueByGateway = Payment::where('status', 'completed')
            ->select('gateway', DB::raw('SUM(amount) as total'))
            ->groupBy('gateway')
            ->get();

        return [
            'monthly_revenue' => $monthlyRevenue,
            'revenue_by_gateway' => $revenueByGateway,
        ];
    }

    /**
     * Get user analytics
     */
    private function getUserAnalytics()
    {
        // User growth over time
        $userGrowth = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $users = User::whereDate('created_at', $date)->count();
            
            $userGrowth[] = [
                'date' => $date->format('Y-m-d'),
                'users' => $users,
            ];
        }

        // User verification status
        $verificationStats = [
            'fully_verified' => User::whereNotNull('email_verified_at')
                ->whereNotNull('phone_verified_at')->count(),
            'email_only' => User::whereNotNull('email_verified_at')
                ->whereNull('phone_verified_at')->count(),
            'phone_only' => User::whereNull('email_verified_at')
                ->whereNotNull('phone_verified_at')->count(),
            'unverified' => User::whereNull('email_verified_at')
                ->whereNull('phone_verified_at')->count(),
        ];

        return [
            'user_growth' => $userGrowth,
            'verification_stats' => $verificationStats,
        ];
    }

    /**
     * Get listing analytics
     */
    private function getListingAnalytics()
    {
        // Listing growth over time
        $listingGrowth = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $listings = Listing::whereDate('created_at', $date)->count();
            
            $listingGrowth[] = [
                'date' => $date->format('Y-m-d'),
                'listings' => $listings,
            ];
        }

        // Listing status distribution
        $statusDistribution = Listing::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return [
            'listing_growth' => $listingGrowth,
            'status_distribution' => $statusDistribution,
        ];
    }

    /**
     * Get revenue data for charts
     */
    public function revenueData(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $data = [];

        switch ($period) {
            case 'daily':
                for ($i = 29; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $revenue = Payment::where('status', 'completed')
                        ->whereDate('created_at', $date)
                        ->sum('amount');
                    
                    $data[] = [
                        'date' => $date->format('Y-m-d'),
                        'revenue' => $revenue,
                    ];
                }
                break;

            case 'monthly':
            default:
                for ($i = 11; $i >= 0; $i--) {
                    $date = now()->subMonths($i);
                    $revenue = Payment::where('status', 'completed')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->sum('amount');
                    
                    $data[] = [
                        'month' => $date->format('M Y'),
                        'revenue' => $revenue,
                    ];
                }
                break;
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}