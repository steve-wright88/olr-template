<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrizePosition extends Model
{
    protected $fillable = [
        'prize_category_id',
        'label',
        'amount',
        'sort_order',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PrizeCategory::class, 'prize_category_id');
    }
}
