<?php

if (!function_exists('setting')) {
    /**
     * Get or set setting value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('setSetting')) {
    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @param string|null $description
     * @return \App\Models\Setting
     */
    function setSetting($key, $value, $description = null)
    {
        return \App\Models\Setting::set($key, $value, $description);
    }
}
