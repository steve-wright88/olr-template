<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entry extends Model
{
    protected $fillable = [
        'reference',
        'syndicate_name',
        'flyer_name',
        'email',
        'phone',
        'team_name',
        'number_of_birds',
        'notes',
        'status',
        'season_year',
        'offer_id',
        'total_fee',
    ];

    protected function casts(): array
    {
        return [
            'number_of_birds' => 'integer',
            'total_fee' => 'decimal:2',
        ];
    }

    public function birds(): HasMany
    {
        return $this->hasMany(EntryBird::class);
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(EntryOffer::class, 'offer_id');
    }

    public function scopeForYear($query, string $year)
    {
        return $query->where('season_year', $year);
    }

    public static function generateReference(): string
    {
        $lastId = static::max('id') ?? 0;

        return 'ENT-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);
    }
}
