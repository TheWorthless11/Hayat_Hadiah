<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'goal_amount',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'goal_amount' => 'decimal:2',
    ];

    /**
     * Get all donations for this category
     */
    public function donations()
    {
        return $this->hasMany(Donation::class, 'category_id');
    }

    /**
     * Scope to get only active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get total amount raised for this category
     */
    public function getTotalRaisedAttribute()
    {
        return $this->donations()->where('payment_status', 'success')->sum('amount');
    }

    /**
     * Get goal progress percentage
     */
    public function getProgressPercentageAttribute()
    {
        if (!$this->goal_amount || $this->goal_amount <= 0) {
            return 0;
        }
        
        $percentage = ($this->total_raised / $this->goal_amount) * 100;
        return min(round($percentage, 1), 100); // Cap at 100%
    }

    /**
     * Check if goal is reached
     */
    public function getIsGoalReachedAttribute()
    {
        if (!$this->goal_amount) {
            return false;
        }
        
        return $this->total_raised >= $this->goal_amount;
    }

    /**
     * Get remaining amount to reach goal
     */
    public function getRemainingAmountAttribute()
    {
        if (!$this->goal_amount) {
            return 0;
        }
        
        $remaining = $this->goal_amount - $this->total_raised;
        return max($remaining, 0);
    }
}
