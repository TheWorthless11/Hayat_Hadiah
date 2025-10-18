<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mosque extends Model
{
    protected $table = 'saved_mosques';

    protected $fillable = [
        'user_id',
        'place_id',
        'name',
        'address',
        'city',
        'latitude',
        'longitude',
        'rating',
        'user_ratings_total',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'rating' => 'decimal:1',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate distance from a given point (in kilometers)
     */
    public function distanceFrom(float $lat, float $lng): float
    {
        $earthRadius = 6371; // kilometers

        $latDiff = deg2rad($this->latitude - $lat);
        $lngDiff = deg2rad($this->longitude - $lng);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
             cos(deg2rad($lat)) * cos(deg2rad($this->latitude)) *
             sin($lngDiff / 2) * sin($lngDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }
}
