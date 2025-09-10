<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'jmbg_hash',
        'is_verified',
        'is_sms_verified',
        'is_email_verified',
        'is_age_verified',
        'is_admin',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'jmbg_hash',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'is_sms_verified' => 'boolean',
        'is_email_verified' => 'boolean',
        'is_age_verified' => 'boolean',
        'is_admin' => 'boolean',
    ];

    // Relationships
    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    public function tokenTransactions()
    {
        return $this->hasMany(TokenTransaction::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'buyer_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    // Helper methods
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getTokenBalanceAttribute()
    {
        return $this->tokenTransactions()
            ->where('type', 'credit')
            ->sum('amount') - 
            $this->tokenTransactions()
            ->where('type', 'debit')
            ->sum('amount');
    }

    public function isFullyVerified()
    {
        return $this->is_verified && 
               $this->is_sms_verified && 
               $this->is_email_verified && 
               $this->is_age_verified;
    }

    public function canCreateListing()
    {
        return $this->isFullyVerified() && $this->token_balance > 0;
    }

    // JMBG validation
    public static function validateJMBG($jmbg)
    {
        if (strlen($jmbg) !== 13 || !ctype_digit($jmbg)) {
            return false;
        }

        // JMBG checksum validation
        $weights = [7, 6, 5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        
        for ($i = 0; $i < 12; $i++) {
            $sum += intval($jmbg[$i]) * $weights[$i];
        }
        
        $remainder = $sum % 11;
        $checkDigit = $remainder < 2 ? $remainder : 11 - $remainder;
        
        return $checkDigit == intval($jmbg[12]);
    }

    public static function calculateAgeFromJMBG($jmbg)
    {
        if (strlen($jmbg) !== 13) {
            return null;
        }

        $day = intval(substr($jmbg, 0, 2));
        $month = intval(substr($jmbg, 2, 2));
        $year = intval(substr($jmbg, 4, 3));
        
        // Determine century
        if ($year >= 0 && $year <= 99) {
            $year += 2000;
        } else {
            $year += 1000;
        }
        
        $birthDate = \Carbon\Carbon::createFromDate($year, $month, $day);
        return $birthDate->age;
    }
}
