<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaabaLocation extends Model
{
    use HasFactory;

    protected $table = 'kaaba_location';

    protected $fillable = [
        'latitude',
        'longitude',
        'location_name',
        'city',
        'country',
        'description',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    /**
     * Get the Kaaba location (singleton pattern)
     */
    public static function getKaaba()
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'latitude' => 21.4225,
                'longitude' => 39.8262,
                'location_name' => 'Holy Kaaba, Mecca',
                'city' => 'Mecca',
                'country' => 'Saudi Arabia',
                'description' => 'The Holy Kaaba, the Qibla for all Muslims worldwide',
            ]
        );
    }

    /**
     * Calculate Qibla direction from a given location to Kaaba
     * 
     * @param float $latitude User's latitude
     * @param float $longitude User's longitude
     * @return float Qibla direction in degrees (0-360)
     */
    public static function calculateQiblaDirection($latitude, $longitude)
    {
        $kaaba = self::getKaaba();
        
        // Convert degrees to radians
        $lat1 = deg2rad($latitude);
        $lon1 = deg2rad($longitude);
        $lat2 = deg2rad($kaaba->latitude);
        $lon2 = deg2rad($kaaba->longitude);
        
        // Calculate the bearing using the formula
        $dLon = $lon2 - $lon1;
        
        $y = sin($dLon) * cos($lat2);
        $x = cos($lat1) * sin($lat2) - sin($lat1) * cos($lat2) * cos($dLon);
        
        $bearing = atan2($y, $x);
        
        // Convert from radians to degrees
        $bearing = rad2deg($bearing);
        
        // Normalize to 0-360
        $bearing = ($bearing + 360) % 360;
        
        return round($bearing, 2);
    }

    /**
     * Calculate distance to Kaaba from a given location
     * Uses Haversine formula
     * 
     * @param float $latitude User's latitude
     * @param float $longitude User's longitude
     * @return float Distance in kilometers
     */
    public static function calculateDistanceToKaaba($latitude, $longitude)
    {
        $kaaba = self::getKaaba();
        
        // Earth's radius in kilometers
        $earthRadius = 6371;
        
        // Convert degrees to radians
        $lat1 = deg2rad($latitude);
        $lon1 = deg2rad($longitude);
        $lat2 = deg2rad($kaaba->latitude);
        $lon2 = deg2rad($kaaba->longitude);
        
        // Haversine formula
        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;
        
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos($lat1) * cos($lat2) *
             sin($dLon / 2) * sin($dLon / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        $distance = $earthRadius * $c;
        
        return round($distance, 2);
    }
}
