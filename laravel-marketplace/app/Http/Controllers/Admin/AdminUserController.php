<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Listing;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminUserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::with(['subscriptions', 'listings', 'payments']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by verification status
        if ($request->has('verification_status')) {
            switch ($request->verification_status) {
                case 'verified':
                    $query->whereNotNull('email_verified_at')
                          ->whereNotNull('phone_verified_at');
                    break;
                case 'email_only':
                    $query->whereNotNull('email_verified_at')
                          ->whereNull('phone_verified_at');
                    break;
                case 'phone_only':
                    $query->whereNull('email_verified_at')
                          ->whereNotNull('phone_verified_at');
                    break;
                case 'unverified':
                    $query->whereNull('email_verified_at')
                          ->whereNull('phone_verified_at');
                    break;
            }
        }

        // Filter by subscription status
        if ($request->has('subscription_status')) {
            switch ($request->subscription_status) {
                case 'active':
                    $query->whereHas('subscriptions', function ($q) {
                        $q->where('status', 'active')
                          ->where('ends_at', '>', now());
                    });
                    break;
                case 'expired':
                    $query->whereHas('subscriptions', function ($q) {
                        $q->where('ends_at', '<=', now());
                    });
                    break;
                case 'none':
                    $query->whereDoesntHave('subscriptions');
                    break;
            }
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(20);

        // Get statistics
        $stats = [
            'total_users' => User::count(),
            'verified_users' => User::whereNotNull('email_verified_at')
                ->whereNotNull('phone_verified_at')->count(),
            'active_subscriptions' => User::whereHas('subscriptions', function ($q) {
                $q->where('status', 'active')->where('ends_at', '>', now());
            })->count(),
            'new_this_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load([
            'subscriptions.plan',
            'listings' => function ($query) {
                $query->latest()->limit(10);
            },
            'payments' => function ($query) {
                $query->latest()->limit(10);
            }
        ]);

        // Get user statistics
        $stats = [
            'total_listings' => $user->listings()->count(),
            'active_listings' => $user->listings()->where('status', 'active')->count(),
            'total_payments' => $user->payments()->count(),
            'total_revenue' => $user->payments()->where('status', 'completed')->sum('amount'),
            'last_login' => $user->last_login_at,
            'account_age' => $user->created_at->diffForHumans(),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Update user verification status
     */
    public function updateVerification(Request $request, User $user)
    {
        $request->validate([
            'email_verified' => 'boolean',
            'phone_verified' => 'boolean',
        ]);

        $user->update([
            'email_verified_at' => $request->email_verified ? now() : null,
            'phone_verified_at' => $request->phone_verified ? now() : null,
        ]);

        Log::info('Admin updated user verification', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'email_verified' => $request->email_verified,
            'phone_verified' => $request->phone_verified,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User verification status updated successfully.',
        ]);
    }

    /**
     * Suspend user account
     */
    public function suspend(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
            'duration' => 'nullable|integer|min:1|max:365', // days
        ]);

        $suspendedUntil = $request->duration 
            ? now()->addDays($request->duration) 
            : null;

        $user->update([
            'suspended_at' => now(),
            'suspended_until' => $suspendedUntil,
            'suspension_reason' => $request->reason,
        ]);

        // Deactivate all user's listings
        $user->listings()->update(['status' => 'suspended']);

        Log::info('Admin suspended user account', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'reason' => $request->reason,
            'duration' => $request->duration,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User account suspended successfully.',
        ]);
    }

    /**
     * Unsuspend user account
     */
    public function unsuspend(User $user)
    {
        $user->update([
            'suspended_at' => null,
            'suspended_until' => null,
            'suspension_reason' => null,
        ]);

        // Reactivate user's listings
        $user->listings()->where('status', 'suspended')->update(['status' => 'active']);

        Log::info('Admin unsuspended user account', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User account unsuspended successfully.',
        ]);
    }

    /**
     * Get user statistics for dashboard
     */
    public function statistics()
    {
        $stats = [
            'total_users' => User::count(),
            'verified_users' => User::whereNotNull('email_verified_at')
                ->whereNotNull('phone_verified_at')->count(),
            'suspended_users' => User::whereNotNull('suspended_at')->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_users_this_week' => User::where('created_at', '>=', now()->startOfWeek())->count(),
            'new_users_this_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
            'active_subscriptions' => User::whereHas('subscriptions', function ($q) {
                $q->where('status', 'active')->where('ends_at', '>', now());
            })->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats,
        ]);
    }
}