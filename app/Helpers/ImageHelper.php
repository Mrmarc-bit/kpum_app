<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get Optimized Image URL
     * 
     * @param string|null $filename Base filename from database
     * @param string $variant 'original', 'thumb', 'medium', 'large', 'webp'
     * @return string Full URL to image
     */
    public static function url(?string $filename, string $variant = 'medium'): string
    {
        if (!$filename) {
            // Return placeholder or empty string
            return asset('assets/images/placeholder.png'); // Pastikan placeholder ada atau null
        }

        // Jika user meminta original, kita harus mencari ekstensinya atau 
        // asumsi user menyimpan full path original jika logic berbeda.
        // Tapi di ImageService, kita hanya return baseName.
        // Jadi Original agak tricky jika tidak simpan ekstensi.
        // SEMENTARA: Kita arahkan ke varian 'webp' (Full Res) sebagai pengganti original untuk frontend.
        // Karena 'original' folder itu backup raw file (mungkin besar & belum optimize).
        
        if ($variant === 'original') {
            // Best practice: Gunakan 'webp' folder untuk full resolution display
            $variant = 'webp';
        }

        $path = "images/{$variant}/{$filename}.webp";
        
        // Cek jika file ada? (Opsional, berat jika dicek setiap saat)
        // Production: Langsung return URL.
        
        return Storage::url($path);
    }

    /**
     * Check if image variant exists
     */
    public static function exists(?string $filename, string $variant = 'medium'): bool
    {
        if (!$filename) return false;
        return Storage::disk('public')->exists("images/{$variant}/{$filename}.webp");
    }
}
