<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Replace shortcodes like {{year}} in content with their setting values.
     */
    public static function replaceShortcodes(?string $content): ?string
    {
        if (! $content) {
            return $content;
        }

        $year = static::get('season_year', (string) date('Y'));

        return str_replace(
            ['{{year}}', '{{ year }}'],
            [$year, $year],
            $content
        );
    }
}
