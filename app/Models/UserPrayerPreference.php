<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPrayerPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'location_id',
        'calculation_method',
        'asr_madhab',
        'high_latitude_rule',
        'notifications_enabled',
        'reminder_offset_minutes',
        'custom_offsets',
    ];

    protected $casts = [
        'notifications_enabled' => 'boolean',
        'custom_offsets' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
