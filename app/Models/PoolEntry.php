<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PoolEntry extends Model
{
    protected $fillable = [
        'reference',
        'pool_type',
        'syndicate_name',
        'email',
        'phone',
        'race_point',
        'race_date',
        'grand_total',
        'status',
        'season_year',
    ];

    protected $casts = [
        'race_date' => 'date',
        'grand_total' => 'decimal:2',
    ];

    public function birds(): HasMany
    {
        return $this->hasMany(PoolEntryBird::class);
    }

    public function scopeForYear($query, string $year)
    {
        return $query->where('season_year', $year);
    }

    public function scopeForType($query, string $type)
    {
        return $query->where('pool_type', $type);
    }

    public static function generateReference(): string
    {
        $last = static::max('id') ?? 0;
        return 'POOL-' . str_pad($last + 1, 5, '0', STR_PAD_LEFT);
    }
}
