<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_ref',
        'donor_name',
        'donor_email',
        'donor_phone',
        'amount',
        'currency',
        'category_id',
        'message',
        'payment_method',
        'payment_status',
        'payment_response',
        'payment_transaction_id',
        'user_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_response' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the category for this donation
     */
    public function category()
    {
        return $this->belongsTo(DonationCategory::class, 'category_id');
    }

    /**
     * Get the user who made this donation (if logged in)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate unique transaction reference
     */
    public static function generateTransactionRef()
    {
        $date = Carbon::now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        return "TXN-{$date}-{$random}";
    }

    /**
     * Scope for successful donations
     */
    public function scopeSuccessful($query)
    {
        return $query->where('payment_status', 'success');
    }

    /**
     * Scope for pending donations
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope for failed donations
     */
    public function scopeFailed($query)
    {
        return $query->where('payment_status', 'failed');
    }

    /**
     * Scope for date range filtering
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountAttribute()
    {
        return '৳ ' . number_format($this->amount, 2);
    }
}
