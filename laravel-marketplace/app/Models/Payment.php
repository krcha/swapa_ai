<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'user_id',
        'amount',
        'currency',
        'status',
        'gateway',
        'gateway_payment_id',
        'payment_method_id',
        'payment_date',
        'failure_reason',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'metadata' => 'array',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_SUCCEEDED = 'succeeded';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELED = 'canceled';
    const STATUS_REFUNDED = 'refunded';

    /**
     * Get the subscription that owns the payment
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the user that owns the payment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the invoice for the payment
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Check if payment is successful
     */
    public function isSuccessful(): bool
    {
        return in_array($this->status, [self::STATUS_SUCCEEDED, self::STATUS_COMPLETED]);
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if payment is failed
     */
    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Check if payment is refunded
     */
    public function isRefunded(): bool
    {
        return $this->status === self::STATUS_REFUNDED;
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        return '€' . number_format($this->amount, 2);
    }

    /**
     * Mark payment as successful
     */
    public function markAsSuccessful(): bool
    {
        return $this->update([
            'status' => self::STATUS_SUCCEEDED,
            'payment_date' => now(),
        ]);
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed(string $reason = null): bool
    {
        return $this->update([
            'status' => self::STATUS_FAILED,
            'failure_reason' => $reason,
        ]);
    }

    /**
     * Mark payment as refunded
     */
    public function markAsRefunded(): bool
    {
        return $this->update([
            'status' => self::STATUS_REFUNDED,
        ]);
    }

    /**
     * Scope for successful payments
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', self::STATUS_SUCCEEDED);
    }

    /**
     * Scope for failed payments
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for payments by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }
}
