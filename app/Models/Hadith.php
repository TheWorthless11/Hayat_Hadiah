<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Hadith extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection',
        'book',
        'reference',
        'text',
        'narrator',
        'translation',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function favoritedBy(): MorphToMany
    {
        return $this->morphToMany(User::class, 'favoritable', 'user_favorites');
    }
}
