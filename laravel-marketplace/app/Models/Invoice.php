<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'payment_id',
        'invoice_number',
        'amount',
        'currency',
        'status',
        'due_date',
        'paid_at',
        'pdf_path',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'datetime',
        'paid_at' => 'datetime',
        'metadata' => 'array',
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_PAID = 'paid';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_CANCELED = 'canceled';

    /**
     * Get the subscription that owns the invoice
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the payment for the invoice
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Check if invoice is paid
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Check if invoice is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_OVERDUE || 
               ($this->due_date < now() && $this->status !== self::STATUS_PAID);
    }

    /**
     * Check if invoice is draft
     */
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'RSD ' . number_format($this->amount, 2);
    }

    /**
     * Mark invoice as paid
     */
    public function markAsPaid(): bool
    {
        return $this->update([
            'status' => self::STATUS_PAID,
            'paid_at' => now(),
        ]);
    }

    /**
     * Mark invoice as overdue
     */
    public function markAsOverdue(): bool
    {
        return $this->update([
            'status' => self::STATUS_OVERDUE,
        ]);
    }

    /**
     * Generate invoice number
     */
    public static function generateInvoiceNumber(): string
    {
        $lastInvoice = self::orderBy('id', 'desc')->first();
        $nextNumber = $lastInvoice ? $lastInvoice->id + 1 : 1;
        return 'INV-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Scope for paid invoices
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    /**
     * Scope for overdue invoices
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_OVERDUE)
                    ->orWhere(function ($q) {
                        $q->where('due_date', '<', now())
                          ->where('status', '!=', self::STATUS_PAID);
                    });
    }

    /**
     * Scope for draft invoices
     */
    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    /**
     * Scope for invoices by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}