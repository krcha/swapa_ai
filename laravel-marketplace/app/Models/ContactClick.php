<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactClick extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'ip_address',
        'user_agent',
        'referer',
        'clicked_at',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    // Relationships
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('clicked_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('clicked_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('clicked_at', now()->month)
                    ->whereYear('clicked_at', now()->year);
    }

    public function scopeByListing($query, $listingId)
    {
        return $query->where('listing_id', $listingId);
    }

    // Helper methods
    public static function trackClick($listingId, $request = null)
    {
        return self::create([
            'listing_id' => $listingId,
            'ip_address' => $request ? $request->ip() : null,
            'user_agent' => $request ? $request->userAgent() : null,
            'referer' => $request ? $request->header('referer') : null,
            'clicked_at' => now(),
        ]);
    }

    public static function getClickStats()
    {
        return [
            'total_clicks' => self::count(),
            'today_clicks' => self::today()->count(),
            'this_week_clicks' => self::thisWeek()->count(),
            'this_month_clicks' => self::thisMonth()->count(),
            'unique_listings_clicked' => self::distinct('listing_id')->count(),
        ];
    }

    public static function getTopClickedListings($limit = 10)
    {
        return self::selectRaw('listing_id, COUNT(*) as click_count')
            ->with('listing:id,title,price,user_id')
            ->groupBy('listing_id')
            ->orderBy('click_count', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getClickTrends($days = 30)
    {
        return self::selectRaw('DATE(clicked_at) as date, COUNT(*) as clicks')
            ->where('clicked_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}