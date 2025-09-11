<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class VerificationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'code',
        'user_id',
        'expires_at',
        'verified',
        'verified_at',
        'attempts',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'verified' => 'boolean',
        'attempts' => 'integer',
    ];

    /**
     * Get the user that owns the verification code
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the verification code is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }

    /**
     * Check if the verification code is verified
     */
    public function isVerified(): bool
    {
        return $this->verified;
    }

    /**
     * Check if the verification code has exceeded attempt limit
     */
    public function hasExceededAttempts(): bool
    {
        return $this->attempts >= 5;
    }

    /**
     * Check if the verification code is valid (not expired, not verified, not exceeded attempts)
     */
    public function isValid(): bool
    {
        return !$this->isExpired() && !$this->isVerified() && !$this->hasExceededAttempts();
    }

    /**
     * Mark verification code as verified
     */
    public function markAsVerified(): bool
    {
        return $this->update([
            'verified' => true,
            'verified_at' => now(),
        ]);
    }

    /**
     * Increment attempt count
     */
    public function incrementAttempts(): bool
    {
        return $this->increment('attempts');
    }

    /**
     * Get formatted phone number
     */
    public function getFormattedPhoneAttribute(): string
    {
        $phone = $this->phone_number;
        
        // Format Serbian phone number
        if (str_starts_with($phone, '+381')) {
            return $phone;
        } elseif (str_starts_with($phone, '381')) {
            return '+' . $phone;
        } elseif (str_starts_with($phone, '0')) {
            return '+381' . substr($phone, 1);
        }
        
        return $phone;
    }

    /**
     * Get masked phone number for display
     */
    public function getMaskedPhoneAttribute(): string
    {
        $phone = $this->phone_number;
        
        if (strlen($phone) >= 6) {
            $start = substr($phone, 0, 3);
            $end = substr($phone, -3);
            $middle = str_repeat('*', strlen($phone) - 6);
            return $start . $middle . $end;
        }
        
        return $phone;
    }

    /**
     * Get time remaining until expiration
     */
    public function getTimeRemainingAttribute(): int
    {
        if ($this->isExpired()) {
            return 0;
        }
        
        return now()->diffInSeconds($this->expires_at);
    }

    /**
     * Get formatted time remaining
     */
    public function getFormattedTimeRemainingAttribute(): string
    {
        $seconds = $this->time_remaining;
        
        if ($seconds <= 0) {
            return 'Expired';
        }
        
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;
        
        if ($minutes > 0) {
            return "{$minutes}m {$remainingSeconds}s";
        }
        
        return "{$remainingSeconds}s";
    }

    /**
     * Scope for valid verification codes
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now())
                    ->where('verified', false)
                    ->where('attempts', '<', 5);
    }

    /**
     * Scope for expired verification codes
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Scope for verified codes
     */
    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    /**
     * Scope for codes by phone number
     */
    public function scopeByPhone($query, $phoneNumber)
    {
        return $query->where('phone_number', $phoneNumber);
    }

    /**
     * Scope for codes by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for recent codes (last 24 hours)
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDay());
    }

    /**
     * Clean up expired verification codes
     */
    public static function cleanupExpired()
    {
        return self::where('expires_at', '<', now())
                  ->orWhere('created_at', '<', now()->subDay())
                  ->delete();
    }

    /**
     * Get verification statistics
     */
    public static function getStatistics($phoneNumber = null)
    {
        $query = self::query();
        
        if ($phoneNumber) {
            $query->where('phone_number', $phoneNumber);
        }
        
        return [
            'total_codes' => $query->count(),
            'verified_codes' => $query->clone()->verified()->count(),
            'expired_codes' => $query->clone()->expired()->count(),
            'valid_codes' => $query->clone()->valid()->count(),
            'recent_codes' => $query->clone()->recent()->count(),
        ];
    }
}