<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDuaReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dua_id',
        'custom_title',
        'reminder_time',
        'frequency',
        'days_of_week',
        'is_active',
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dua(): BelongsTo
    {
        return $this->belongsTo(Dua::class);
    }
}
