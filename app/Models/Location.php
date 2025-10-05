<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'country',
        'latitude',
        'longitude',
        'timezone',
        'calculation_method',
        'meta',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'meta' => 'array',
    ];

    public function prayerTimes(): HasMany
    {
        return $this->hasMany(PrayerTime::class);
    }

    public function fastingSchedules(): HasMany
    {
        return $this->hasMany(FastingSchedule::class);
    }

    public function userPrayerPreferences(): HasMany
    {
        return $this->hasMany(UserPrayerPreference::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function zakatRecords(): HasManyThrough
    {
        return $this->hasManyThrough(
            ZakatRecord::class,
            User::class,
            'location_id',
            'user_id'
        );
    }
}
