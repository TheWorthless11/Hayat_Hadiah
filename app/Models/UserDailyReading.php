<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDailyReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quran_verse_id',
        'hadith_id',
        'reading_date',
        'is_favorite',
    ];

    protected $casts = [
        'reading_date' => 'date',
        'is_favorite' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quranVerse(): BelongsTo
    {
        return $this->belongsTo(QuranVerse::class);
    }

    public function hadith(): BelongsTo
    {
        return $this->belongsTo(Hadith::class);
    }
}
