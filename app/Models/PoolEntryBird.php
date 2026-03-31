<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PoolEntryBird extends Model
{
    protected $fillable = [
        'pool_entry_id',
        'ring_number',
        'stakes',
        'bird_total',
    ];

    protected $casts = [
        'stakes' => 'array',
        'bird_total' => 'decimal:2',
    ];

    public function poolEntry(): BelongsTo
    {
        return $this->belongsTo(PoolEntry::class);
    }
}
