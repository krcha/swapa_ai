<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'listing_id',
        'search_query',
        'target_price',
        'condition',
        'is_active',
        'triggered_at',
    ];

    protected $casts = [
        'target_price' => 'decimal:2',
        'is_active' => 'boolean',
        'triggered_at' => 'datetime',
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
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForListing($query, $listingId)
    {
        return $query->where('listing_id', $listingId);
    }

    public function scopeTriggered($query)
    {
        return $query->whereNotNull('triggered_at');
    }

    // Helper methods
    public function checkPrice($currentPrice)
    {
        if (!$this->is_active) {
            return false;
        }

        $triggered = false;
        switch ($this->condition) {
            case 'below':
                $triggered = $currentPrice <= $this->target_price;
                break;
            case 'above':
                $triggered = $currentPrice >= $this->target_price;
                break;
            case 'equal':
                $triggered = $currentPrice == $this->target_price;
                break;
        }

        if ($triggered && !$this->triggered_at) {
            $this->update([
                'triggered_at' => now(),
                'is_active' => false,
            ]);
        }

        return $triggered;
    }

    public function getFormattedTargetPriceAttribute()
    {
        return '$' . number_format($this->target_price, 2);
    }

    public function getDescriptionAttribute()
    {
        $conditionText = $this->condition === 'below' ? 'below' : 
                        ($this->condition === 'above' ? 'above' : 'equal to');
        
        if ($this->listing) {
            return "Alert when {$this->listing->title} goes {$conditionText} {$this->formatted_target_price}";
        } else {
            return "Alert when \"{$this->search_query}\" goes {$conditionText} {$this->formatted_target_price}";
        }
    }
}