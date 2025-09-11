<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Listing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'brand_id',
        'title',
        'description',
        'price',
        'condition',
        'storage',
        'color',
        'battery_health',
        'model_year',
        'screen_condition',
        'body_condition',
        'functionality',
        'carrier',
        'contact_preference',
        'status',
        'expires_at',
        'view_count',
        'has_priority_listing',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'expires_at' => 'datetime',
        'view_count' => 'integer',
        'has_priority_listing' => 'boolean',
        'model_year' => 'integer',
        'battery_health' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ListingImage::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function priceAlerts()
    {
        return $this->hasMany(PriceAlert::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('expires_at', '>', now());
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByBrand($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    public function scopeByCondition($query, $condition)
    {
        return $query->where('condition', $condition);
    }

    public function scopeByPriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('title', 'like', "%{$searchTerm}%")
              ->orWhere('description', 'like', "%{$searchTerm}%");
        });
    }

    // Helper methods
    public function getPrimaryImageAttribute()
    {
        return $this->images()->where('is_primary', true)->first();
    }

    public function isExpired()
    {
        return $this->expires_at < now();
    }

    public function isActive()
    {
        return $this->status === 'active' && !$this->isExpired();
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function renew()
    {
        $this->update([
            'expires_at' => now()->addDays(30),
            'status' => 'active'
        ]);
    }
}
