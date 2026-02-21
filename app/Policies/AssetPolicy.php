<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * AssetPolicy - RBAC Implementation
 * 
 * CRITICAL: Prevent unauthorized file access and malicious uploads
 * 
 * Authorization Matrix:
 * ┌──────────────┬────────┬──────────┬──────┐
 * │ Action       │ Admin  │ Panitia  │ KPPS │
 * ├──────────────┼────────┼──────────┼──────┤
 * │ viewAny      │   ✅   │    ✅    │  ❌  │
 * │ view         │   ✅   │    ✅    │  ❌  │
 * │ download     │   ✅   │    ✅    │  ❌  │
 * │ create       │   ✅   │    ✅    │  ❌  │
 * │ update       │   ✅   │    ⚠️    │  ❌  │
 * │ delete       │   ✅   │    ⚠️    │  ❌  │
 * └──────────────┴────────┴──────────┴──────┘
 * 
 * ⚠️  Panitia can only manage their own uploaded files
 */
class AssetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if user can view asset list
     */
    public function viewAny(User $user): bool
    {
        // Admin and Panitia can view asset manager
        return in_array($user->role, ['admin', 'panitia']);
    }

    /**
     * Determine if user can view a specific asset
     */
    public function view(User $user, Asset $asset): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'panitia') {
            // Panitia can view any asset (for collaboration)
            // But download is restricted to own files
            return true;
        }

        return false;
    }

    /**
     * Determine if user can download asset
     * 
     * CRITICAL: Prevent unauthorized file download (IDOR)
     */
    public function download(User $user, Asset $asset): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'panitia') {
            // Panitia can download:
            // 1. Their own uploads
            // 2. Files in shared folders (folder_id IS NULL or public flag)
            return $asset->uploaded_by === $user->id || 
                   $asset->folder_id === null;
        }

        return false;
    }

    /**
     * Determine if user can upload files
     */
    public function create(User $user): bool
    {
        // Admin and Panitia can upload
        return in_array($user->role, ['admin', 'panitia']);
    }

    /**
     * Determine if user can rename/move assets
     */
    public function update(User $user, Asset $asset): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'panitia') {
            // Panitia can only manage their own files
            return $asset->uploaded_by === $user->id;
        }

        return false;
    }

    /**
     * Determine if user can delete assets
     * 
     * CRITICAL: Prevent data loss
     */
    public function delete(User $user, Asset $asset): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'panitia') {
            // Panitia can only delete their own uploads
            return $asset->uploaded_by === $user->id;
        }

        return false;
    }

    /**
     * Determine if user can create folders
     */
    public function createFolder(User $user): bool
    {
        // Admin and Panitia can create folders
        return in_array($user->role, ['admin', 'panitia']);
    }

    /**
     * Determine if user can manage folders
     */
    public function manageFolder(User $user, $folder): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'panitia') {
            // Panitia can manage folders they created
            return $folder->created_by === $user->id;
        }

        return false;
    }
}
