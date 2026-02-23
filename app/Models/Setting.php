<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'description'];

    public static function get($key, $default = null)
    {
        $settings = Cache::rememberForever('global_settings_array', function () {
            return static::pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    /**
     * Set setting value by key and IMMEDIATELY clear all related caches
     */
    public static function set($key, $value, $description = null)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'description' => $description
            ]
        );

        // CRITICAL: Immediately clear ALL related caches
        Cache::forget($key);
        Cache::forget("setting_{$key}");
        Cache::forget('global_settings');
        Cache::forget('global_settings_array'); // Clear our new high-performance array cache

        return $setting;
    }

    /**
     * Check if maintenance mode is active
     * NO CACHING - Always reads fresh from database
     * 
     * @return bool
     */
    public static function isMaintenanceMode(): bool
    {
        // Dapatkan mode langsung dari DB agar aman saat cache fail/rusak (emergency mode)
        $setting = static::where('key', 'maintenance_mode')->first();
        $value = $setting ? $setting->value : 'false';

        // Use PHP native boolean filter for consistency
        // Handles: "true", "1", "yes", "on", true, 1 → true
        // Handles: "false", "0", "no", "off", false, 0, null, "" → false
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
