<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Listing;
use App\Models\ContactClick;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::with(['listings', 'subscriptions.plan'])->withCount('listings');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // Filter by user type
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        // Filter by subscription tier
        if ($request->filled('subscription_tier')) {
            if ($request->subscription_tier === 'tier1') {
                $query->whereDoesntHave('subscriptions');
            } else {
                $query->whereHas('subscriptions.plan', function($q) use ($request) {
                    $q->where('slug', $request->subscription_tier);
                });
            }
        }

        // Filter by listing status
        if ($request->filled('has_listings')) {
            if ($request->has_listings === 'yes') {
                $query->has('listings');
            } elseif ($request->has_listings === 'no') {
                $query->doesntHave('listings');
            }
        }

        // Filter by verification status
        if ($request->filled('verification')) {
            if ($request->verification === 'verified') {
                $query->where('email_verified_at', '!=', null);
            } elseif ($request->verification === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        // Filter by date range
        if ($request->filled('joined_after')) {
            $query->whereDate('created_at', '>=', $request->joined_after);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('city', 'LIKE', "%{$request->location}%")
                  ->orWhere('country', 'LIKE', "%{$request->location}%");
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'banned') {
                $query->where('is_banned', true);
            } elseif ($request->status === 'active') {
                $query->where('is_banned', false);
            } elseif ($request->status === 'inactive') {
                $query->where('is_banned', false)
                      ->where('last_login_at', '<', now()->subDays(30));
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load([
            'listings' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(10);
            },
            'subscriptions.plan'
        ]);

        $plans = Plan::all();

        return view('admin.users.show', compact('user', 'plans'));
    }

    /**
     * Show the form for editing the user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'user_type' => 'required|in:personal,business',
            'listing_limit' => 'required|integer|min:0|max:1000',
            'is_banned' => 'boolean',
            'ban_reason' => 'nullable|string|max:500',
            'business_name' => 'nullable|string|max:255',
            'business_registration' => 'nullable|string|max:255',
            'business_address' => 'nullable|string|max:500',
            'business_phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'user_type' => $request->user_type,
            'listing_limit' => $request->listing_limit,
            'is_banned' => $request->boolean('is_banned'),
            'ban_reason' => $request->ban_reason,
            'business_name' => $request->business_name,
            'business_registration' => $request->business_registration,
            'business_address' => $request->business_address,
            'business_phone' => $request->business_phone,
        ]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Ban a user.
     */
    public function ban(Request $request, User $user)
    {
        $request->validate([
            'ban_reason' => 'required|string|max:500',
        ]);

        $user->update([
            'is_banned' => true,
            'ban_reason' => $request->ban_reason,
            'banned_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'User has been banned successfully.');
    }

    /**
     * Unban a user.
     */
    public function unban(User $user)
    {
        $user->update([
            'is_banned' => false,
            'ban_reason' => null,
            'banned_at' => null,
        ]);

        return redirect()->back()
            ->with('success', 'User has been unbanned successfully.');
    }

    /**
     * Update user's listing limit.
     */
    public function updateListingLimit(Request $request, User $user)
    {
        $request->validate([
            'listing_limit' => 'required|integer|min:0|max:1000',
        ]);

        $user->update([
            'listing_limit' => $request->listing_limit,
        ]);

        return redirect()->back()
            ->with('success', 'Listing limit updated successfully.');
    }

    /**
     * Change user type.
     */
    public function changeUserType(Request $request, User $user)
    {
        $request->validate([
            'user_type' => 'required|in:personal,business',
        ]);

        $user->update([
            'user_type' => $request->user_type,
        ]);

        return redirect()->back()
            ->with('success', 'User type updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Delete user's listings first
        $user->listings()->delete();
        
        // Delete the user
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User and all their listings have been deleted successfully.');
    }

    /**
     * Get user statistics.
     */
    public function statistics()
    {
        $stats = [
            'total_users' => User::count(),
            'individual_users' => User::where('user_type', 'personal')->count(),
            'business_users' => User::where('user_type', 'business')->count(),
            'banned_users' => User::where('is_banned', true)->count(),
            'active_users' => User::where('is_banned', false)->count(),
            'total_listings' => Listing::count(),
            'active_listings' => Listing::where('status', 'active')->count(),
        ];

        // Get contact click statistics
        $contactClickStats = ContactClick::getClickStats();
        $topClickedListings = ContactClick::getTopClickedListings(5);
        $clickTrends = ContactClick::getClickTrends(7);

        return view('admin.statistics', compact('stats', 'contactClickStats', 'topClickedListings', 'clickTrends'));
    }

    /**
     * Update user subscription
     */
    public function updateSubscription(Request $request, User $user)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'status' => 'required|in:active,inactive,cancelled',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at'
        ]);

        // Cancel current active subscription
        $user->subscriptions()
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        // Create new subscription
        $user->subscriptions()->create([
            'plan_id' => $request->plan_id,
            'status' => $request->status,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at
        ]);

        return redirect()->back()->with('success', 'User subscription updated successfully!');
    }

    /**
     * Cancel user subscription
     */
    public function cancelSubscription(User $user)
    {
        $user->subscriptions()
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'User subscription cancelled successfully!');
    }

    /**
     * Extend user subscription
     */
    public function extendSubscription(Request $request, User $user)
    {
        $request->validate([
            'extension_days' => 'required|integer|min:1|max:365'
        ]);

        $activeSubscription = $user->activeSubscription();
        if ($activeSubscription) {
            $activeSubscription->update([
                'ends_at' => $activeSubscription->ends_at->addDays($request->extension_days)
            ]);
        }

        return redirect()->back()->with('success', "User subscription extended by {$request->extension_days} days!");
    }
}
