<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'is_sms_verified',
        'is_email_verified',
        'is_age_verified',
        'is_admin',
        'phone_verified_at',
        'user_type',
        'business_name',
        'business_registration_number',
        'business_tax_id',
        'business_address',
        'business_city',
        'business_country',
        'business_phone',
        'business_email',
        'business_website',
        'subscription_tier',
        'is_business_verified',
        'business_verified_at',
        'has_priority_listing',
        'listing_limit',
        'is_banned',
        'ban_reason',
        'banned_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'business_verified_at' => 'datetime',
        'banned_at' => 'datetime',
        'password' => 'hashed',
        'is_sms_verified' => 'boolean',
        'is_email_verified' => 'boolean',
        'is_age_verified' => 'boolean',
        'is_admin' => 'boolean',
        'is_business_verified' => 'boolean',
        'has_priority_listing' => 'boolean',
        'is_banned' => 'boolean',
    ];

    // API Token methods
    public function createToken($name, $abilities = ['*'])
    {
        return (object) ['plainTextToken' => Str::random(40)];
    }

    public function makeHidden($attributes)
    {
        if (is_array($attributes)) {
            $this->hidden = array_merge($this->hidden, $attributes);
        } else {
            $this->hidden[] = $attributes;
        }
        return $this;
    }

    // Verification methods  
    public function isFullyVerified()
    {
        return $this->hasPhoneVerification() && $this->email_verified_at !== null;
    }

    public function hasPhoneVerification()
    {
        return $this->phone_verified_at !== null;
    }

    // Subscription methods
    public function canCreateListing()
    {
        $subscription = $this->activeSubscription();
        if (!$subscription) return false;
        
        $plan = $subscription->plan;
        if (!$plan->listing_limit) return true;
        
        $currentMonth = now()->format('Y-m');
        $listingsThisMonth = $this->listings()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
            
        return $listingsThisMonth < $plan->listing_limit;
    }

    public function currentPlan()
    {
        $subscription = $this->activeSubscription();
        return $subscription ? $subscription->plan : Plan::where('price', 0)->first();
    }

    public function getRemainingListingQuota()
    {
        $subscription = $this->activeSubscription();
        if (!$subscription) return 0;
        
        $plan = $subscription->plan;
        if (!$plan->listing_limit) return 999;
        
        $currentMonth = now()->format('Y-m');
        $listingsThisMonth = $this->listings()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
            
        return max(0, $plan->listing_limit - $listingsThisMonth);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latest()
            ->first();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the user's listings.
     */
    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    /**
     * Get the user's payment methods.
     */
    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    /**
     * Get the user's invoices.
     */
    public function invoices()
    {
        return $this->hasManyThrough(Invoice::class, Subscription::class);
    }

    /**
     * Get the user's conversations as buyer.
     */
    public function buyerConversations()
    {
        return $this->hasMany(Conversation::class, 'buyer_id');
    }

    /**
     * Get the user's conversations as seller.
     */
    public function sellerConversations()
    {
        return $this->hasMany(Conversation::class, 'seller_id');
    }

    /**
     * Get all conversations for the user.
     */
    public function conversations()
    {
        return Conversation::where('buyer_id', $this->id)
                          ->orWhere('seller_id', $this->id);
    }

    /**
     * Get the user's sent messages.
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get the user's received messages.
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    /**
     * Get the user's favorites.
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get the user's favorite listings.
     */
    public function favoriteListings()
    {
        return $this->belongsToMany(Listing::class, 'favorites');
    }

    /**
     * Get the user's price alerts.
     */
    public function priceAlerts()
    {
        return $this->hasMany(PriceAlert::class);
    }

    /**
     * Get the user's reports.
     */
    public function reports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    /**
     * Get reports against the user.
     */
    public function reportedAgainst()
    {
        return $this->hasMany(Report::class, 'user_id');
    }

    /**
     * Get users blocked by this user.
     */
    public function blockedUsers()
    {
        return $this->hasMany(BlockedUser::class, 'blocker_id');
    }

    /**
     * Get users who blocked this user.
     */
    public function blockedByUsers()
    {
        return $this->hasMany(BlockedUser::class, 'blocked_id');
    }

    /**
     * Check if user is blocked by another user.
     */
    public function isBlockedBy($userId)
    {
        return BlockedUser::isBlocked($userId, $this->id);
    }

    /**
     * Check if user has blocked another user.
     */
    public function hasBlocked($userId)
    {
        return BlockedUser::isBlocked($this->id, $userId);
    }

    /**
     * Block another user.
     */
    public function blockUser($userId, $reason = null)
    {
        return BlockedUser::block($this->id, $userId, $reason);
    }

    /**
     * Unblock another user.
     */
    public function unblockUser($userId)
    {
        return BlockedUser::unblock($this->id, $userId);
    }

    /**
     * Get unread message count.
     */
    public function getUnreadMessageCount()
    {
        return Message::where('recipient_id', $this->id)
                     ->where('is_read', false)
                     ->count();
    }

    /**
     * Get recent conversations.
     */
    public function getRecentConversations($limit = 10)
    {
        return $this->conversations()
                   ->with(['listing', 'buyer', 'seller', 'messages' => function($query) {
                       $query->latest()->limit(1);
                   }])
                   ->orderBy('last_message_at', 'desc')
                   ->limit($limit)
                   ->get();
    }

    /**
     * Check if user is a business account.
     */
    public function isBusiness()
    {
        return $this->user_type === 'business';
    }

    /**
     * Check if user is a personal account.
     */
    public function isPersonal()
    {
        return $this->user_type === 'personal';
    }

    /**
     * Check if user has priority listing (business listings appear on top).
     */
    public function hasPriorityListing()
    {
        return $this->isBusiness() && $this->has_priority_listing;
    }

    /**
     * Check if user can register as business (Tier 2 or Tier 3).
     */
    public function canRegisterAsBusiness()
    {
        return in_array($this->subscription_tier, ['tier_2', 'tier_3']);
    }

    /**
     * Get business display name.
     */
    public function getBusinessDisplayName()
    {
        if ($this->isBusiness() && $this->business_name) {
            return $this->business_name;
        }
        return $this->first_name . ' ' . $this->last_name;
    }
}