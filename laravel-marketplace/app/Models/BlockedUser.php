<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'blocker_id',
        'blocked_id',
        'reason',
    ];

    // Relationships
    public function blocker()
    {
        return $this->belongsTo(User::class, 'blocker_id');
    }

    public function blocked()
    {
        return $this->belongsTo(User::class, 'blocked_id');
    }

    // Scopes
    public function scopeBlockedBy($query, $userId)
    {
        return $query->where('blocker_id', $userId);
    }

    public function scopeBlocking($query, $userId)
    {
        return $query->where('blocked_id', $userId);
    }

    // Helper methods
    public static function isBlocked($blockerId, $blockedId)
    {
        return static::where('blocker_id', $blockerId)
                    ->where('blocked_id', $blockedId)
                    ->exists();
    }

    public static function block($blockerId, $blockedId, $reason = null)
    {
        return static::create([
            'blocker_id' => $blockerId,
            'blocked_id' => $blockedId,
            'reason' => $reason,
        ]);
    }

    public static function unblock($blockerId, $blockedId)
    {
        return static::where('blocker_id', $blockerId)
                    ->where('blocked_id', $blockedId)
                    ->delete();
    }

    public static function getBlockedUsers($userId)
    {
        return static::where('blocker_id', $userId)
                    ->with('blocked')
                    ->get()
                    ->pluck('blocked');
    }

    public static function getBlockingUsers($userId)
    {
        return static::where('blocked_id', $userId)
                    ->with('blocker')
                    ->get()
                    ->pluck('blocker');
    }
}