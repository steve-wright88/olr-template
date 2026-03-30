<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Season extends Model
{
    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'loft_id',
        'name',
        'is_active',
        'completed',
        'pigeon_count',
        'team_count',
        'distance',
        'pricepool',
        'currency',
        'synced_at',
    ];

    protected function casts(): array
    {
        return [
            'synced_at' => 'datetime',
            'pricepool' => 'decimal:2',
        ];
    }

    public function loft(): BelongsTo
    {
        return $this->belongsTo(Loft::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function flights(): HasMany
    {
        return $this->hasMany(Flight::class);
    }

    public function pigeons(): HasMany
    {
        return $this->hasMany(Pigeon::class);
    }
}
