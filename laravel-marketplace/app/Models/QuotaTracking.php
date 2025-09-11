<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotaTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'year',
        'month',
        'listings_used',
        'listings_limit',
        'is_unlimited',
        'reset_at',
    ];

    protected $casts = [
        'is_unlimited' => 'boolean',
        'reset_at' => 'datetime',
    ];

    /**
     * Get the user that owns the quota tracking
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan for this quota tracking
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Check if user has remaining quota
     */
    public function hasRemainingQuota(): bool
    {
        if ($this->is_unlimited) {
            return true;
        }
        
        return $this->listings_used < $this->listings_limit;
    }

    /**
     * Get remaining quota
     */
    public function getRemainingQuotaAttribute(): int
    {
        if ($this->is_unlimited) {
            return -1; // Unlimited
        }
        
        return max(0, $this->listings_limit - $this->listings_used);
    }

    /**
     * Increment listings used
     */
    public function incrementListingsUsed(): bool
    {
        if (!$this->hasRemainingQuota()) {
            return false;
        }
        
        $this->increment('listings_used');
        return true;
    }

    /**
     * Decrement listings used
     */
    public function decrementListingsUsed(): bool
    {
        if ($this->listings_used <= 0) {
            return false;
        }
        
        $this->decrement('listings_used');
        return true;
    }

    /**
     * Reset quota for new month
     */
    public function resetForNewMonth(): bool
    {
        $this->update([
            'listings_used' => 0,
            'reset_at' => now(),
        ]);
        
        return true;
    }

    /**
     * Scope for current month
     */
    public function scopeCurrentMonth($query)
    {
        $now = now();
        return $query->where('year', $now->year)
                    ->where('month', $now->month);
    }

    /**
     * Scope for specific month
     */
    public function scopeForMonth($query, $year, $month)
    {
        return $query->where('year', $year)
                    ->where('month', $month);
    }

    /**
     * Scope for users with remaining quota
     */
    public function scopeWithRemainingQuota($query)
    {
        return $query->where(function ($q) {
            $q->where('is_unlimited', true)
              ->orWhereRaw('listings_used < listings_limit');
        });
    }

    /**
     * Scope for users at quota limit
     */
    public function scopeAtQuotaLimit($query)
    {
        return $query->where('is_unlimited', false)
                    ->whereRaw('listings_used >= listings_limit');
    }
}
