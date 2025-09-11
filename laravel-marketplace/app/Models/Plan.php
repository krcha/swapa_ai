<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'listing_limit',
        'features',
        'is_active',
        'trial_days',
        'listing_duration_days',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'listing_limit' => 'integer',
        'is_active' => 'boolean',
        'trial_days' => 'integer',
        'listing_duration_days' => 'integer',
        'features' => 'array',
    ];

    /**
     * Get subscriptions for this plan
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Check if this is the free plan
     */
    public function isFree(): bool
    {
        return $this->price == 0;
    }

    /**
     * Check if this is a paid plan
     */
    public function isPaid(): bool
    {
        return $this->price > 0;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->isFree()) {
            return 'Free';
        }
        
        return '€' . number_format($this->price, 2) . '/month';
    }

    /**
     * Get listing limit description
     */
    public function getListingLimitDescriptionAttribute(): string
    {
        if ($this->listing_limit === -1) {
            return 'Unlimited';
        }
        
        return $this->listing_limit . ' per month';
    }

    /**
     * Scope for active plans
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for paid plans
     */
    public function scopePaid($query)
    {
        return $query->where('price', '>', 0);
    }

    /**
     * Scope for free plans
     */
    public function scopeFree($query)
    {
        return $query->where('price', 0);
    }

    /**
     * Check if user can create listing with this plan
     */
    public function canCreateListing(User $user): bool
    {
        if ($this->isFree()) {
            // For free plan, check monthly limit
            $currentMonth = now()->startOfMonth();
            $listingsThisMonth = $user->listings()
                ->where('created_at', '>=', $currentMonth)
                ->count();
            
            return $listingsThisMonth < $this->listing_limit;
        }
        
        // For paid plans, check if user has active subscription
        $activeSubscription = $user->activeSubscription();
        if (!$activeSubscription || $activeSubscription->plan_id !== $this->id) {
            return false;
        }
        
        // Check monthly quota
        $currentMonth = now()->startOfMonth();
        $listingsThisMonth = $user->listings()
            ->where('created_at', '>=', $currentMonth)
            ->count();
        
        return $listingsThisMonth < $this->listing_limit;
    }
}
