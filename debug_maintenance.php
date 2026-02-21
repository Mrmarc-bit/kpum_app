<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUG MAINTENANCE MODE ===\n\n";

// 1. Check if settings table exists
try {
    $settings = DB::table('settings')->get();
    echo "✅ Settings table exists\n";
    echo "Total settings: " . $settings->count() . "\n\n";

    if ($settings->count() > 0) {
        echo "Current settings:\n";
        foreach ($settings as $setting) {
            echo "  - {$setting->key} = {$setting->value}\n";
        }
    } else {
        echo "⚠️  No settings found in database\n";
    }
} catch (\Exception $e) {
    echo "❌ Error accessing settings table: " . $e->getMessage() . "\n";
}

echo "\n";

// 2. Check maintenance_mode setting specifically
$maintenanceMode = \App\Models\Setting::get('maintenance_mode');
echo "Maintenance Mode Setting: " . ($maintenanceMode ?? 'NULL') . "\n";

// 3. Check if isMaintenanceMode() works
$isActive = \App\Models\Setting::isMaintenanceMode();
echo "Is Maintenance Active: " . ($isActive ? 'YES' : 'NO') . "\n";

echo "\n";

// 4. Check helper function
try {
    $helperTest = setting('maintenance_mode', 'NOT_FOUND');
    echo "Helper function test: {$helperTest}\n";
} catch (\Exception $e) {
    echo "❌ Helper error: " . $e->getMessage() . "\n";
}

echo "\n=== END DEBUG ===\n";
