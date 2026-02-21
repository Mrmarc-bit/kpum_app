<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver; // Driver: GD
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class ImageService
{
    protected ImageManager $manager;
    
    // Konfigurasi Disk Utama
    protected string $disk = 'public';
    protected string $baseDir = 'images';

    public function __construct()
    {
        // Setup Intervention Image Manager v3 dengan GD Driver
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Upload Gambar & Generate Semua Varian (Production Ready)
     * 
     * @param UploadedFile $file The uploaded file instance
     * @param string|null $customName Nama opsional (jika null, akan digenerate UUID)
     * @return string Mengembalikan BASE FILENAME (tanpa ekstensi) untuk disimpan di DB.
     *                Contoh: "user-profile-2024-xc9d3"
     */
    public function upload(UploadedFile $file, ?string $customName = null): string
    {
        // 1. Validasi Extension & Mime Type secara manual jika perlu,
        // tapi biasanya sudah divalidasi di Request Validation Controller.
        
        // 2. Generate Unique Filename
        $ext = $file->getClientOriginalExtension();
        $uniqueId = Str::random(10);
        
        if ($customName) {
            // Slugify nama custom agar aman di URL + append random string
            $baseFilename = Str::slug($customName) . '-' . $uniqueId;
        } else {
            // Gunakan UUID jika tidak ada nama khusus
            $baseFilename = (string) Str::uuid();
        }

        // 3. Pastikan Struktur Folder Siap
        $this->ensureDirectories();

        // 4. Proses Simpan Original (Backup File Mentah)
        // Simpan file asli ke folder 'original' dengan nama aslinya.
        // storeAs mengembalikan path relatif.
        $originalRelPath = $file->storeAs(
            "{$this->baseDir}/original", 
            $baseFilename . '.' . $ext, 
            $this->disk
        );

        // Dapatkan Path Absolut Original untuk diproses Intervention
        $absOriginalPath = Storage::disk($this->disk)->path($originalRelPath);

        try {
            // 5. Baca Image ke Memory (Resource Intensive Step)
            $image = $this->manager->read($absOriginalPath);

            // 6. Generate Varian (Thumb, Medium, Large)
            // Function helper processVariant akan melakukan clone, resize, save, dan optimize.

            // -- THUMBNAIL (300px) --
            $this->processVariant($image, 'thumb', 300, $baseFilename);

            // -- MEDIUM (800px) --
            $this->processVariant($image, 'medium', 800, $baseFilename);

            // -- LARGE (1600px) --
            $this->processVariant($image, 'large', 1600, $baseFilename);

            // -- WEBP FULL RES (Convert Only) --
            // Simpan versi full resolution tapi format WebP
            $this->processVariant($image, 'webp', null, $baseFilename); // Width null = no resize

            return $baseFilename;

        } catch (\Exception $e) {
            // Log Error krusial
            Log::error("ImageService Upload Failed: " . $e->getMessage());
            
            // Clean up: Hapus file original yang sempat terupload agar tidak jadi sampah
            if (isset($originalRelPath)) {
                Storage::disk($this->disk)->delete($originalRelPath);
            }
            
            throw $e;
        }
    }

    /**
     * Import Existing Image from Path (For Migration/Seeding)
     *
     * @param string $absolutePath Path fisik file (harus ada permission read)
     * @param string|null $customName Nama opsional
     * @return string Base filename
     */
    public function importFromPath(string $absolutePath, ?string $customName = null): string
    {
        if (!file_exists($absolutePath)) {
            throw new \Exception("File not found: {$absolutePath}");
        }

        // 1. Generate Info
        $info = pathinfo($absolutePath);
        $ext = $info['extension'] ?? 'jpg';
        $uniqueId = Str::random(10);
        
        if ($customName) {
            $baseFilename = Str::slug($customName) . '-' . $uniqueId;
        } else {
            $originalName = $info['filename'];
            $baseFilename = Str::slug($originalName) . '-' . $uniqueId;
        }

        // 2. Setup Folder
        $this->ensureDirectories();

        // 3. Copy Original
        $destinationOriginal = "{$this->baseDir}/original/{$baseFilename}.{$ext}";
        // Read content
        $content = file_get_contents($absolutePath);
        Storage::disk($this->disk)->put($destinationOriginal, $content);

        // 4. Read & Process
        try {
            $image = $this->manager->read($absolutePath);

            // Generate Variants
            $this->processVariant($image, 'thumb', 300, $baseFilename);
            $this->processVariant($image, 'medium', 800, $baseFilename);
            $this->processVariant($image, 'large', 1600, $baseFilename);
            $this->processVariant($image, 'webp', null, $baseFilename);

            return $baseFilename;

        } catch (\Exception $e) {
            Log::error("ImageService Import Failed: " . $e->getMessage());
            Storage::disk($this->disk)->delete($destinationOriginal);
            throw $e;
        }
    }

    /**
     * Helper: Resize -> Convert WebP -> Optimize
     * 
     * @param \Intervention\Image\Interfaces\ImageInterface $originalImage Instance image (akan diclone)
     * @param string $folder Subfolder tujuan (thumb/medium/large)
     * @param int|null $width Lebar target (pixel). Jika null, tidak di-resize.
     * @param string $filename Nama file dasar (tanpa ekstensi)
     */
    protected function processVariant($originalImage, string $folder, ?int $width, string $filename): void
    {
        // CLONE image agar operasi ini tidak mempengaruhi operasi selanjutnya pada instance yang sama.
        // Intervention v3 object bersifat mutable pada modifier tertentu.
        $variant = clone $originalImage;

        // RESIZE (jika width ditentukan)
        if ($width) {
            // scaleDown: Resize hanya jika gambar > width. Menjaga aspect ratio.
            $variant->scaleDown(width: $width);
        }

        // PATH TUJUAN
        // Simpan sebagai .webp
        $relativePath = "{$this->baseDir}/{$folder}/{$filename}.webp";
        $absolutePath = Storage::disk($this->disk)->path($relativePath);

        // ENCODE & SAVE
        // Quality 80 adalah sweet spot antara ukuran & kualitas.
        $variant->toWebp(quality: 80)->save($absolutePath);

        // OPTIMIZE (Spatie)
        // Menjalankan tools external (jpegoptim, pngquant, cwebp, dll) jika tersedia di server.
        // Menggunakan try-catch agar aman jika binary tidak terinstall.
        try {
            ImageOptimizer::optimize($absolutePath);
        } catch (\Exception $e) {
            // Silent fail, log warning only.
            Log::warning("Spatie Optimizer skipped for {$relativePath}: " . $e->getMessage());
        }
    }

    /**
     * Hapus Gambar (Semua Varian) dari Storage
     * 
     * @param string $baseFilename Nama file dasar yg tersimpan di DB
     * @return bool True jika berhasil hapus salah satu file setidaknya.
     */
    public function delete(string $baseFilename): bool
    {
        if (empty($baseFilename)) return false;

        $deleted = false;
        $variants = ['thumb', 'medium', 'large', 'webp'];

        // 1. Hapus Varian WebP (Pasti .webp)
        foreach ($variants as $folder) {
            $path = "{$this->baseDir}/{$folder}/{$baseFilename}.webp";
            if (Storage::disk($this->disk)->exists($path)) {
                Storage::disk($this->disk)->delete($path);
                $deleted = true;
            }
        }

        // 2. Hapus Original (Ekstensi mungkin beda-beda)
        // Kita brute-force cek ekstensi umum daripada listing directory (lebih cepat)
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
        foreach ($extensions as $ext) {
            $path = "{$this->baseDir}/original/{$baseFilename}.{$ext}";
            if (Storage::disk($this->disk)->exists($path)) {
                Storage::disk($this->disk)->delete($path);
                $deleted = true;
                // Kita tidak break, jaga-jaga ada file duplikat beda ekstensi dengan nama sama (edge case)
            }
        }

        return $deleted;
    }

    /**
     * Lazy Creation of Directories
     */
    protected function ensureDirectories(): void
    {
        $folders = ['original', 'thumb', 'medium', 'large', 'webp'];
        foreach ($folders as $folder) {
            $path = "{$this->baseDir}/{$folder}";
            if (!Storage::disk($this->disk)->exists($path)) {
                Storage::disk($this->disk)->makeDirectory($path);
            }
        }
    }
}
