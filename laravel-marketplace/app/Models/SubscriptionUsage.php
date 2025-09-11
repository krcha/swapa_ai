<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_id',
        'plan_id',
        'listings_created',
        'listings_active',
        'listings_expired',
        'views_generated',
        'contacts_received',
        'revenue_generated',
        'tracking_date',
    ];

    protected $casts = [
        'tracking_date' => 'date',
        'revenue_generated' => 'decimal:2',
    ];

    /**
     * Get the user that owns the usage tracking
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subscription for this usage tracking
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the plan for this usage tracking
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Increment listings created
     */
    public function incrementListingsCreated(): void
    {
        $this->increment('listings_created');
    }

    /**
     * Increment listings active
     */
    public function incrementListingsActive(): void
    {
        $this->increment('listings_active');
    }

    /**
     * Increment listings expired
     */
    public function incrementListingsExpired(): void
    {
        $this->increment('listings_expired');
    }

    /**
     * Increment views generated
     */
    public function incrementViewsGenerated(int $count = 1): void
    {
        $this->increment('views_generated', $count);
    }

    /**
     * Increment contacts received
     */
    public function incrementContactsReceived(): void
    {
        $this->increment('contacts_received');
    }

    /**
     * Add revenue generated
     */
    public function addRevenueGenerated(float $amount): void
    {
        $this->increment('revenue_generated', $amount);
    }

    /**
     * Scope for current date
     */
    public function scopeToday($query)
    {
        return $query->whereDate('tracking_date', today());
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tracking_date', [$startDate, $endDate]);
    }

    /**
     * Scope for specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for specific subscription
     */
    public function scopeForSubscription($query, $subscriptionId)
    {
        return $query->where('subscription_id', $subscriptionId);
    }
}
