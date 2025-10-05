<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IslamicCalendarEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hijri_date',
        'gregorian_date',
        'type',
        'meta',
    ];

    protected $casts = [
        'gregorian_date' => 'date',
        'meta' => 'array',
    ];
}
