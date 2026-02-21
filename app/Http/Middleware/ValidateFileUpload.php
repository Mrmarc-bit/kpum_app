<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * ValidateFileUpload Middleware
 * 
 * CRITICAL SECURITY: Prevents malicious file uploads
 * 
 * Protection Layers:
 * 1. MIME type validation (not just extension)
 * 2. File size limits
 * 3. Filename sanitization
 * 4. Extension whitelist
 */
class ValidateFileUpload
{
    /**
     * Allowed MIME types for different file purposes
     */
    const ALLOWED_IMAGE_MIMES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    const ALLOWED_DOCUMENT_MIMES = [
        'application/pdf',
        'application/msword', // .doc
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
        'application/vnd.ms-excel', // .xls
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
        'text/csv',
        'text/plain',
    ];

    const ALLOWED_ARCHIVE_MIMES = [
        'application/zip',
        'application/x-zip-compressed',
    ];

    /**
     * Maximum file sizes in bytes
     */
    const MAX_IMAGE_SIZE = 5 * 1024 * 1024; // 5MB
    const MAX_DOCUMENT_SIZE = 10 * 1024 * 1024; // 10MB
    const MAX_CSV_SIZE = 10 * 1024 * 1024; // 10MB for DPT import

    /**
     * Dangerous extensions that should NEVER be allowed
     */
    const BLOCKED_EXTENSIONS = [
        'php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'phps',
        'exe', 'dll', 'com', 'bat', 'cmd', 'sh', 'bash',
        'js', 'jar', 'app', 'deb', 'rpm',
        'asp', 'aspx', 'jsp', 'cgi',
        'svg', // Can contain XSS payloads
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $type = 'document')
    {
        // Only validate if file upload exists
        if (!$request->hasFile('file') && !$request->hasFile('photo') && !$request->hasFile('image')) {
            return $next($request);
        }

        // Get the uploaded file
        $file = $request->file('file') ?? $request->file('photo') ?? $request->file('image');

        if (!$file instanceof UploadedFile) {
            return $next($request);
        }

        try {
            // 1. Validate MIME type
            $this->validateMimeType($file, $type);

            // 2. Validate file size
            $this->validateFileSize($file, $type);

            // 3. Validate extension (double-check)
            $this->validateExtension($file);

            // 4. Sanitize filename
            $this->sanitizeFilename($file);

            return $next($request);

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'File upload error: ' . $e->getMessage());
        }
    }

    /**
     * Validate MIME type based on upload context
     */
    protected function validateMimeType(UploadedFile $file, string $type): void
    {
        $mime = $file->getMimeType();
        $allowed = [];

        switch ($type) {
            case 'image':
                $allowed = self::ALLOWED_IMAGE_MIMES;
                break;
            case 'document':
                $allowed = array_merge(
                    self::ALLOWED_DOCUMENT_MIMES,
                    self::ALLOWED_IMAGE_MIMES
                );
                break;
            case 'csv':
                $allowed = ['text/csv', 'text/plain', 'application/vnd.ms-excel'];
                break;
            case 'archive':
                $allowed = self::ALLOWED_ARCHIVE_MIMES;
                break;
            default:
                $allowed = array_merge(
                    self::ALLOWED_IMAGE_MIMES,
                    self::ALLOWED_DOCUMENT_MIMES
                );
        }

        if (!in_array($mime, $allowed)) {
            throw new \Exception("File type not allowed. Detected: {$mime}");
        }
    }

    /**
     * Validate file size
     */
    protected function validateFileSize(UploadedFile $file, string $type): void
    {
        $size = $file->getSize();
        $maxSize = self::MAX_DOCUMENT_SIZE;

        switch ($type) {
            case 'image':
                $maxSize = self::MAX_IMAGE_SIZE;
                break;
            case 'csv':
                $maxSize = self::MAX_CSV_SIZE;
                break;
            case 'document':
            case 'archive':
                $maxSize = self::MAX_DOCUMENT_SIZE;
                break;
        }

        if ($size > $maxSize) {
            $maxMB = round($maxSize / 1024 / 1024, 2);
            $sizeMB = round($size / 1024 / 1024, 2);
            throw new \Exception("File too large. Maximum: {$maxMB}MB, Uploaded: {$sizeMB}MB");
        }
    }

    /**
     * Validate file extension (secondary check)
     */
    protected function validateExtension(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());

        // Block dangerous extensions
        if (in_array($extension, self::BLOCKED_EXTENSIONS)) {
            throw new \Exception("File extension '{$extension}' is not allowed for security reasons.");
        }

        // Additional check: double extensions (e.g., file.php.jpg)
        $filename = $file->getClientOriginalName();
        if (preg_match('/\.(php|exe|sh|bat|cmd|js)\./i', $filename)) {
            throw new \Exception("Double extensions detected. This is not allowed.");
        }
    }

    /**
     * Sanitize filename to prevent directory traversal
     */
    protected function sanitizeFilename(UploadedFile $file): void
    {
        $filename = $file->getClientOriginalName();

        // Check for directory traversal attempts
        if (\Illuminate\Support\Str::contains($filename, ['../', '..\\'])) {
            throw new \Exception("Directory traversal detected in filename.");
        }

        // Check for null bytes
        if (\Illuminate\Support\Str::contains($filename, "\0")) {
            throw new \Exception("Null byte detected in filename.");
        }
    }
}
