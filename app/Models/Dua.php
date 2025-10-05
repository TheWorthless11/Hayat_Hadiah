<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dua extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'arabic_text',
        'transliteration',
        'translation',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function reminders(): HasMany
    {
        return $this->hasMany(UserDuaReminder::class);
    }

    public function favoritedBy(): MorphToMany
    {
        return $this->morphToMany(User::class, 'favoritable', 'user_favorites');
    }
}
