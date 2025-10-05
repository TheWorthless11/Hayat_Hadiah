<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FastingSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'gregorian_date',
        'hijri_date',
        'sehri_time',
        'iftar_time',
        'is_ramadan',
        'ramadan_day',
        'meta',
    ];

    protected $casts = [
        'gregorian_date' => 'date',
        'sehri_time' => 'datetime:H:i',
        'iftar_time' => 'datetime:H:i',
        'is_ramadan' => 'boolean',
        'meta' => 'array',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
