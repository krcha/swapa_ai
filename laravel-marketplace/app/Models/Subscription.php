<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'starts_at',
        'ends_at',
        'trial_ends_at',
        'status',
        'payment_method',
        'billing_cycle',
        'auto_renew',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'auto_renew' => 'boolean',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_TRIALING = 'trialing';
    const STATUS_PAST_DUE = 'past_due';
    const STATUS_CANCELED = 'canceled';
    const STATUS_UNPAID = 'unpaid';
    const STATUS_EXPIRED = 'expired';

    /**
     * Get the user that owns the subscription
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan for this subscription
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get payments for this subscription
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get invoices for this subscription
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE && 
               $this->ends_at > now();
    }

    /**
     * Check if subscription is in trial
     */
    public function isTrialing(): bool
    {
        return $this->status === self::STATUS_TRIALING && 
               $this->trial_ends_at > now();
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired(): bool
    {
        return $this->ends_at < now() || 
               $this->status === self::STATUS_EXPIRED;
    }

    /**
     * Check if subscription is canceled
     */
    public function isCanceled(): bool
    {
        return $this->status === self::STATUS_CANCELED;
    }

    /**
     * Check if subscription is past due
     */
    public function isPastDue(): bool
    {
        return $this->status === self::STATUS_PAST_DUE;
    }

    /**
     * Get days remaining in subscription
     */
    public function getDaysRemainingAttribute(): int
    {
        if ($this->isExpired()) {
            return 0;
        }
        
        return max(0, now()->diffInDays($this->ends_at, false));
    }

    /**
     * Get days remaining in trial
     */
    public function getTrialDaysRemainingAttribute(): int
    {
        if (!$this->trial_ends_at || $this->trial_ends_at < now()) {
            return 0;
        }
        
        return max(0, now()->diffInDays($this->trial_ends_at, false));
    }

    /**
     * Check if subscription is in grace period (30 days after expiration)
     */
    public function isInGracePeriod(): bool
    {
        if ($this->isActive() || $this->isTrialing()) {
            return false;
        }
        
        $gracePeriodEnd = $this->ends_at->addDays(30);
        return now() < $gracePeriodEnd;
    }

    /**
     * Cancel subscription
     */
    public function cancel(): bool
    {
        $this->update([
            'status' => self::STATUS_CANCELED,
            'auto_renew' => false,
        ]);
        
        return true;
    }

    /**
     * Renew subscription
     */
    public function renew(): bool
    {
        if ($this->isCanceled()) {
            return false;
        }
        
        $newEndsAt = $this->ends_at->addMonth();
        
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'ends_at' => $newEndsAt,
        ]);
        
        return true;
    }

    /**
     * Scope for active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                    ->where('ends_at', '>', now());
    }

    /**
     * Scope for trialing subscriptions
     */
    public function scopeTrialing($query)
    {
        return $query->where('status', self::STATUS_TRIALING)
                    ->where('trial_ends_at', '>', now());
    }

    /**
     * Scope for expired subscriptions
     */
    public function scopeExpired($query)
    {
        return $query->where(function ($q) {
            $q->where('ends_at', '<', now())
              ->orWhere('status', self::STATUS_EXPIRED);
        });
    }

    /**
     * Scope for subscriptions in grace period
     */
    public function scopeInGracePeriod($query)
    {
        return $query->where('ends_at', '>=', now()->subDays(30))
                    ->where('ends_at', '<', now())
                    ->whereNotIn('status', [self::STATUS_ACTIVE, self::STATUS_TRIALING]);
    }
}
