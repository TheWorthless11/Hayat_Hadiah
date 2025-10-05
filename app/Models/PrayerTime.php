<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrayerTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'prayer_date',
        'imsak',
        'fajr',
        'sunrise',
        'dhuhr',
        'asr',
        'maghrib',
        'isha',
        'calculation_source',
        'adjustments',
    ];

    protected $casts = [
        'prayer_date' => 'date',
        'imsak' => 'datetime:H:i',
        'fajr' => 'datetime:H:i',
        'sunrise' => 'datetime:H:i',
        'dhuhr' => 'datetime:H:i',
        'asr' => 'datetime:H:i',
        'maghrib' => 'datetime:H:i',
        'isha' => 'datetime:H:i',
        'adjustments' => 'array',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function nextPrayer(CarbonInterface $reference): ?string
    {
        $schedule = [
            'imsak' => $this->imsak,
            'fajr' => $this->fajr,
            'sunrise' => $this->sunrise,
            'dhuhr' => $this->dhuhr,
            'asr' => $this->asr,
            'maghrib' => $this->maghrib,
            'isha' => $this->isha,
        ];

        foreach ($schedule as $name => $time) {
            if ($time instanceof CarbonInterface && $time->greaterThan($reference)) {
                return $name;
            }
        }

        return null;
    }
}
