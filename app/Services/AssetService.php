<?php

namespace App\Services;

use App\Models\Asset;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssetService
{
    // Whitelist MIME types untuk keamanan
    private const ALLOWED_MIME_TYPES = [
        // Images
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/svg+xml',
        // Documents
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    // Max file size: 5MB
    private const MAX_FILE_SIZE = 5 * 1024 * 1024;

    /**
     * Upload dan simpan asset dengan validasi keamanan
     */
    public function uploadAsset(UploadedFile $file, array $data, int $userId): Asset
    {
        // 1. Validasi MIME type
        $this->validateMimeType($file);

        // 2. Validasi ukuran file
        $this->validateFileSize($file);

        // 3. Sanitize filename (hapus karakter berbahaya)
        $originalFilename = $this->sanitizeFilename($file->getClientOriginalName());

        // 4. Generate nama file acak untuk security (hindari directory traversal)
        $extension = $file->getClientOriginalExtension();
        $hashedFilename = Str::random(40) . '.' . $extension;

        // 5. Tentukan path dengan tanggal untuk organisasi
        $directory = 'assets/' . date('Y/m');
        
        // 6. Store file ke storage/app/public/assets/YYYY/MM/
        $path = $file->storeAs($directory, $hashedFilename, 'public');

        // 7. Deteksi tipe asset
        $type = $this->detectAssetType($file->getMimeType(), $originalFilename);

        // 8. Buat record di database
        return Asset::create([
            'name' => $data['name'] ?? $originalFilename,
            'original_filename' => $originalFilename,
            'filename' => $hashedFilename,
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'type' => $data['type'] ?? $type,
            'description' => $data['description'] ?? null,
            'folder_id' => $data['folder_id'] ?? null, // Added
            'uploaded_by' => $userId,
        ]);
    }

    /**
     * Hapus asset beserta filenya
     */
    public function deleteAsset(Asset $asset): bool
    {
        // Hapus file dari storage
        if (Storage::disk('public')->exists($asset->path)) {
            Storage::disk('public')->delete($asset->path);
        }

        // Hapus record dari database
        return $asset->delete();
    }

    /**
     * Pindahkan asset ke folder lain
     */
    public function moveAsset(Asset $asset, ?int $folderId): bool
    {
        return $asset->update(['folder_id' => $folderId]);
    }

    /**
     * Validasi MIME type
     */
    private function validateMimeType(UploadedFile $file): void
    {
        if (!in_array($file->getMimeType(), self::ALLOWED_MIME_TYPES)) {
            throw new \InvalidArgumentException(
                'Tipe file tidak diizinkan. Hanya gambar (JPG, PNG, GIF, WebP, SVG) dan dokumen (PDF, Word, Excel) yang diperbolehkan.'
            );
        }

        // Extra validation: Check file extension matches MIME
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();
        
        // Validate extension-MIME consistency
        $validExtensions = $this->getAllowedExtensionsForMime($mimeType);
        if (!in_array($extension, $validExtensions)) {
            throw new \InvalidArgumentException('Ekstensi file tidak sesuai dengan tipe file.');
        }
    }

    /**
     * Validasi ukuran file
     */
    private function validateFileSize(UploadedFile $file): void
    {
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new \InvalidArgumentException(
                'Ukuran file terlalu besar. Maksimal 5MB.'
            );
        }
    }

    /**
     * Sanitize filename untuk keamanan
     */
    private function sanitizeFilename(string $filename): string
    {
        // Hapus path traversal characters
        $filename = basename($filename);
        
        // Hapus karakter berbahaya
        $filename = preg_replace('/[^\w\s\.\-]/', '', $filename);
        
        // Batasi panjang
        if (strlen($filename) > 255) {
            $filename = substr($filename, 0, 255);
        }
        
        return $filename;
    }

    /**
     * Deteksi tipe asset berdasarkan MIME type
     */
    private function detectAssetType(string $mimeType, string $filename): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            // Check if it's a logo based on filename
            if (Str::contains(strtolower($filename), 'logo')) {
                return 'logo';
            }
            return 'image';
        }

        if ($mimeType === 'application/pdf' || Str::contains($mimeType, 'word') || Str::contains($mimeType, 'excel')) {
            return 'document';
        }

        return 'other';
    }

    /**
     * Get allowed extensions for a MIME type
     */
    private function getAllowedExtensionsForMime(string $mimeType): array
    {
        $mapping = [
            'image/jpeg' => ['jpg', 'jpeg'],
            'image/png' => ['png'],
            'image/gif' => ['gif'],
            'image/webp' => ['webp'],
            'image/svg+xml' => ['svg'],
            'application/pdf' => ['pdf'],
            'application/msword' => ['doc'],
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['docx'],
            'application/vnd.ms-excel' => ['xls'],
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => ['xlsx'],
        ];

        return $mapping[$mimeType] ?? [];
    }
}
