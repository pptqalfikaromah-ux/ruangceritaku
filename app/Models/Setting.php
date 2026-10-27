<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'description',
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('app_settings_all');
        });

        static::deleted(function () {
            Cache::forget('app_settings_all');
        });
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return app_setting($key, $default);
    }

    public static function set(string $key, mixed $value, string $group = 'general', ?string $description = null): self
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'description' => $description,
            ]
        );

        Cache::forget('app_settings_all');

        return $setting;
    }
}
