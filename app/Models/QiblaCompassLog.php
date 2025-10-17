<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QiblaCompassLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'qibla_direction',
        'device_type',
        'browser',
        'ip_address',
        'accessed_at',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'qibla_direction' => 'decimal:3',
        'accessed_at' => 'datetime',
    ];

    /**
     * Get the user that accessed the compass
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get logs for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get recent logs
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('accessed_at', '>=', now()->subDays($days));
    }

    /**
     * Scope to get logs by device type
     */
    public function scopeByDevice($query, $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }
}
