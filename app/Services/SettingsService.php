<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class SettingsService
{
    /**
     * Update settings safely handling file uploads and caching.
     */
    public function updateSettings(array $data, ?UploadedFile $logo = null)
    {
        if ($logo && $logo->isValid()) {
             try {
                 $filename = 'logo_' . time() . '.webp';
                 $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                 $image = $manager->read($logo);
                 $image->scaleDown(width: 500);
                 Storage::disk('public')->put('settings/' . $filename, (string) $image->toWebp(60));
                 $data['app_logo'] = 'storage/settings/' . $filename;
             } catch (\Exception $e) {
                 \Illuminate\Support\Facades\Log::warning('Failed applying intervention image on logo: ' . $e->getMessage());
                 $filename = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
                 Storage::disk('public')->put('settings/' . $filename, file_get_contents($logo));
                 $data['app_logo'] = 'storage/settings/' . $filename;
             }
        }

        // 2. Ensure booleans are set
        $booleanSettings = ['show_candidates', 'enable_quick_count'];
        foreach ($booleanSettings as $key) {
            if (!array_key_exists($key, $data)) {
                $data[$key] = 'false';
            }
        }

        // 3. Update DB & Clear Cache
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Atomic cache clearing where possible, but for settings widespread flush is safer
        Cache::flush(); 

        return $data;
    }

    public function updateMaintenance(bool $status, string $message, ?string $endTime)
    {
        Setting::set('maintenance_mode', $status ? 'true' : 'false', 'Enable or disable maintenance mode');
        Setting::set('maintenance_message', $message, 'Message displayed during maintenance');
        Setting::set('maintenance_end_time', $endTime, 'Estimated end time for maintenance');

        // Nuclear clear
        Cache::flush();
    }
}
