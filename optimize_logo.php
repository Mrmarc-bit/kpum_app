<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

$setting = Setting::where('key', 'app_logo')->first();
if ($setting && $setting->value) {
    if (str_ends_with($setting->value, '.png') || str_ends_with($setting->value, '.jpg')) {
        echo "Optimizing logo: {$setting->value}\n";
        $absolutePath = public_path($setting->value);
        if (file_exists($absolutePath)) {
            try {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($absolutePath);
                $image->scaleDown(width: 500);
                
                // Keep the same path but change extension
                $newVal = preg_replace('/\.(png|jpg|jpeg)$/i', '.webp', $setting->value);
                $newAbsolutePath = public_path($newVal);
                
                $image->toWebp(60)->save($newAbsolutePath);
                
                $setting->value = $newVal;
                $setting->save();
                
                echo "Successfully changed app_logo to $newVal\n";
            } catch (\Exception $e) {
                echo "Error: " . $e->getMessage() . "\n";
            }
        } else {
             echo "File not found: $absolutePath\n";
        }
    } else {
        echo "Already optimized or not an image: {$setting->value}\n";
    }
} else {
    echo "No app_logo setting found.\n";
}
