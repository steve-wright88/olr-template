<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntryBird extends Model
{
    protected $fillable = [
        'entry_id',
        'ring_number',
        'pigeon_name',
    ];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }
}
