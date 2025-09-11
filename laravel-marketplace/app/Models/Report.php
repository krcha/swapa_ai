<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'listing_id',
        'user_id',
        'type',
        'reason',
        'description',
        'status',
        'reviewed_by',
        'admin_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeDismissed($query)
    {
        return $query->where('status', 'dismissed');
    }

    public function scopeForType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForReason($query, $reason)
    {
        return $query->where('reason', $reason);
    }

    // Helper methods
    public function markAsReviewed($reviewerId, $adminNotes = null)
    {
        $this->update([
            'status' => 'reviewed',
            'reviewed_by' => $reviewerId,
            'admin_notes' => $adminNotes,
            'reviewed_at' => now(),
        ]);
    }

    public function markAsResolved($reviewerId, $adminNotes = null)
    {
        $this->update([
            'status' => 'resolved',
            'reviewed_by' => $reviewerId,
            'admin_notes' => $adminNotes,
            'reviewed_at' => now(),
        ]);
    }

    public function markAsDismissed($reviewerId, $adminNotes = null)
    {
        $this->update([
            'status' => 'dismissed',
            'reviewed_by' => $reviewerId,
            'admin_notes' => $adminNotes,
            'reviewed_at' => now(),
        ]);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'reviewed' => 'bg-blue-100 text-blue-800',
            'resolved' => 'bg-green-100 text-green-800',
            'dismissed' => 'bg-gray-100 text-gray-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getReasonTextAttribute()
    {
        $reasons = [
            'spam' => 'Spam',
            'inappropriate' => 'Inappropriate Content',
            'fraud' => 'Fraud',
            'fake' => 'Fake Listing',
            'harassment' => 'Harassment',
            'copyright' => 'Copyright Violation',
            'other' => 'Other',
        ];

        return $reasons[$this->reason] ?? 'Other';
    }
}