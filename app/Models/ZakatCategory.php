<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ZakatCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nisab_value',
        'currency',
        'description',
    ];

    protected $casts = [
        'nisab_value' => 'float',
    ];

    public function records(): HasMany
    {
        return $this->hasMany(ZakatRecord::class);
    }
}
