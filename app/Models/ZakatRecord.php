<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZakatRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'zakat_category_id',
        'assets_value',
        'liabilities_value',
        'zakat_due',
        'calculation_year',
        'paid_at',
        'notes',
        'breakdown',
    ];

    protected $casts = [
        'assets_value' => 'float',
        'liabilities_value' => 'float',
        'zakat_due' => 'float',
        'paid_at' => 'date',
        'breakdown' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ZakatCategory::class, 'zakat_category_id');
    }
}
