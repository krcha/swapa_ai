<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'description',
        'reference_id',
        'reference_type',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'expires_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reference()
    {
        return $this->morphTo('reference');
    }

    // Scopes
    public function scopeCredits($query)
    {
        return $query->where('type', 'credit');
    }

    public function scopeDebits($query)
    {
        return $query->where('type', 'debit');
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeValid($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    // Helper methods
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at < now();
    }

    public function isCredit()
    {
        return $this->type === 'credit';
    }

    public function isDebit()
    {
        return $this->type === 'debit';
    }

    // Static methods
    public static function giveFreeTokens(User $user, $amount = 1)
    {
        return self::create([
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => $amount,
            'description' => 'Monthly free tokens',
            'expires_at' => now()->addMonth(),
        ]);
    }

    public static function consumeToken(User $user, $reference = null, $description = 'Token consumed')
    {
        // Check if user has available tokens
        $availableTokens = $user->token_balance;
        
        if ($availableTokens <= 0) {
            return false;
        }

        // Find oldest valid token to consume
        $oldestToken = $user->tokenTransactions()
            ->credits()
            ->valid()
            ->orderBy('created_at')
            ->first();

        if (!$oldestToken) {
            return false;
        }

        // Create debit transaction
        return self::create([
            'user_id' => $user->id,
            'type' => 'debit',
            'amount' => 1,
            'description' => $description,
            'reference_id' => $reference ? $reference->id : null,
            'reference_type' => $reference ? get_class($reference) : null,
        ]);
    }

    public static function refundToken(User $user, $reference = null, $description = 'Token refunded')
    {
        return self::create([
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => 1,
            'description' => $description,
            'reference_id' => $reference ? $reference->id : null,
            'reference_type' => $reference ? get_class($reference) : null,
        ]);
    }
}
