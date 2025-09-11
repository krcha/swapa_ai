<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'gateway',
        'gateway_payment_method_id',
        'is_default',
        'is_active',
        'last_four',
        'exp_month',
        'exp_year',
        'brand',
        'metadata',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'exp_month' => 'integer',
        'exp_year' => 'integer',
        'metadata' => 'array',
    ];

    const TYPE_CARD = 'card';
    const TYPE_BANK_ACCOUNT = 'bank_account';
    const TYPE_DIGITAL_WALLET = 'digital_wallet';

    /**
     * Get the user that owns the payment method
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if payment method is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if payment method is default
     */
    public function isDefault(): bool
    {
        return $this->is_default;
    }

    /**
     * Check if payment method is expired
     */
    public function isExpired(): bool
    {
        if ($this->type !== self::TYPE_CARD) {
            return false;
        }

        $now = now();
        return $this->exp_year < $now->year || 
               ($this->exp_year == $now->year && $this->exp_month < $now->month);
    }

    /**
     * Get masked card number
     */
    public function getMaskedNumberAttribute(): string
    {
        if ($this->type !== self::TYPE_CARD || !$this->last_four) {
            return '****';
        }

        return '**** **** **** ' . $this->last_four;
    }

    /**
     * Get formatted expiry date
     */
    public function getFormattedExpiryAttribute(): string
    {
        if ($this->type !== self::TYPE_CARD || !$this->exp_month || !$this->exp_year) {
            return '';
        }

        return str_pad($this->exp_month, 2, '0', STR_PAD_LEFT) . '/' . $this->exp_year;
    }

    /**
     * Get display name
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->type === self::TYPE_CARD) {
            return $this->brand . ' ' . $this->masked_number;
        }

        return ucfirst(str_replace('_', ' ', $this->type));
    }

    /**
     * Set as default payment method
     */
    public function setAsDefault(): bool
    {
        // Remove default from all user's payment methods
        $this->user->paymentMethods()->update(['is_default' => false]);
        
        // Set this as default
        return $this->update(['is_default' => true]);
    }

    /**
     * Deactivate payment method
     */
    public function deactivate(): bool
    {
        return $this->update(['is_active' => false]);
    }

    /**
     * Activate payment method
     */
    public function activate(): bool
    {
        return $this->update(['is_active' => true]);
    }

    /**
     * Scope for active payment methods
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for default payment methods
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope for card payment methods
     */
    public function scopeCards($query)
    {
        return $query->where('type', self::TYPE_CARD);
    }

    /**
     * Scope for bank account payment methods
     */
    public function scopeBankAccounts($query)
    {
        return $query->where('type', self::TYPE_BANK_ACCOUNT);
    }

    /**
     * Scope for expired payment methods
     */
    public function scopeExpired($query)
    {
        $now = now();
        return $query->where('type', self::TYPE_CARD)
                    ->where(function ($q) use ($now) {
                        $q->where('exp_year', '<', $now->year)
                          ->orWhere(function ($q2) use ($now) {
                              $q2->where('exp_year', $now->year)
                                 ->where('exp_month', '<', $now->month);
                          });
                    });
    }
}