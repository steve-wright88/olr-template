<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Flight extends Model
{
    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'season_id',
        'name',
        'flight_type',
        'distance',
        'release_time',
        'release_time_local',
        'arrivals_count',
        'basketings_count',
        'average_speed',
        'status',
        'synced_at',
    ];

    protected function casts(): array
    {
        return [
            'release_time' => 'datetime',
            'average_speed' => 'decimal:4',
            'synced_at' => 'datetime',
        ];
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}
