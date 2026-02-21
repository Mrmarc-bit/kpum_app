<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'description'];

    /**
     * Get setting value by key WITHOUT caching
     * Cache is managed at application level
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
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
        // Get fresh value from database (no cache)
        $value = static::get('maintenance_mode', 'false');

        // Use PHP native boolean filter for consistency
        // Handles: "true", "1", "yes", "on", true, 1 → true
        // Handles: "false", "0", "no", "off", false, 0, null, "" → false
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
