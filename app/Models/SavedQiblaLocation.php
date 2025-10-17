<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedQiblaLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'location_name',
        'latitude',
        'longitude',
        'qibla_direction',
        'distance_to_kaaba',
        'address',
        'city',
        'country',
        'is_favorite',
        'usage_count',
        'last_accessed_at',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'qibla_direction' => 'decimal:3',
        'distance_to_kaaba' => 'decimal:2',
        'is_favorite' => 'boolean',
        'last_accessed_at' => 'datetime',
    ];

    /**
     * Get the user that owns this saved location
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Increment usage count and update last accessed timestamp
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
        $this->update(['last_accessed_at' => now()]);
    }

    /**
     * Toggle favorite status
     */
    public function toggleFavorite()
    {
        $this->update(['is_favorite' => !$this->is_favorite]);
    }

    /**
     * Scope to get favorite locations
     */
    public function scopeFavorites($query)
    {
        return $query->where('is_favorite', true);
    }

    /**
     * Scope to get most used locations
     */
    public function scopeMostUsed($query, $limit = 5)
    {
        return $query->orderBy('usage_count', 'desc')->limit($limit);
    }

    /**
     * Scope to get recently accessed locations
     */
    public function scopeRecentlyAccessed($query, $limit = 5)
    {
        return $query->whereNotNull('last_accessed_at')
                     ->orderBy('last_accessed_at', 'desc')
                     ->limit($limit);
    }
}
