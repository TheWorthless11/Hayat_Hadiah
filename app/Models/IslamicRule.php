<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class IslamicRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'content',
        'references',
    ];

    protected $casts = [
        'references' => 'array',
    ];

    public function favoritedBy(): MorphToMany
    {
        return $this->morphToMany(User::class, 'favoritable', 'user_favorites');
    }
}
