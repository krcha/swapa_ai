<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'listing_id',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForListing($query, $listingId)
    {
        return $query->where('listing_id', $listingId);
    }

    // Helper methods
    public static function toggle($userId, $listingId)
    {
        $favorite = static::where('user_id', $userId)
                         ->where('listing_id', $listingId)
                         ->first();

        if ($favorite) {
            $favorite->delete();
            return false; // Removed
        } else {
            static::create([
                'user_id' => $userId,
                'listing_id' => $listingId,
            ]);
            return true; // Added
        }
    }

    public static function isFavorited($userId, $listingId)
    {
        return static::where('user_id', $userId)
                    ->where('listing_id', $listingId)
                    ->exists();
    }
}