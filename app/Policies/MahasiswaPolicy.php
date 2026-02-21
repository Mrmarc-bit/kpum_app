<?php

namespace App\Policies;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * MahasiswaPolicy - RBAC Implementation
 * 
 * Authorization Matrix:
 * ┌──────────────┬────────┬──────────┬──────┬────────┐
 * │ Action       │ Admin  │ Panitia  │ KPPS │ User   │
 * ├──────────────┼────────┼──────────┼──────┼────────┤
 * │ viewAny      │   ✅   │    ✅    │  ✅  │   ❌   │
 * │ view         │   ✅   │    ✅    │  ✅  │   ❌   │
 * │ create       │   ✅   │    ✅    │  ❌  │   ❌   │
 * │ update       │   ✅   │    ✅    │  ❌  │   ❌   │
 * │ delete       │   ✅   │    ⚠️    │  ❌  │   ❌   │
 * │ generateCode │   ✅   │    ❌    │  ❌  │   ❌   │
 * │ downloadLetter│  ✅   │    ✅    │  ❌  │   ❌   │
 * └──────────────┴────────┴──────────┴──────┴────────┘
 * 
 * ⚠️  Panitia can delete ONLY if mahasiswa hasn't voted yet
 */
class MahasiswaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if user can view the DPT list
     */
    public function viewAny(User $user): bool
    {
        // Admin, Panitia, and KPPS can view DPT list
        return in_array($user->role, ['admin', 'panitia', 'kpps']);
    }

    /**
     * Determine if user can view a single mahasiswa record
     */
    public function view(User $user, Mahasiswa $mahasiswa): bool
    {
        // Admin, Panitia, and KPPS can view any mahasiswa
        return in_array($user->role, ['admin', 'panitia', 'kpps']);
    }

    /**
     * Determine if user can create mahasiswa records
     */
    public function create(User $user): bool
    {
        // Only Admin and Panitia can add new DPT entries
        return in_array($user->role, ['admin', 'panitia']);
    }

    /**
     * Determine if user can update mahasiswa records
     */
    public function update(User $user, Mahasiswa $mahasiswa): bool
    {
        // Only Admin and Panitia can edit DPT
        return in_array($user->role, ['admin', 'panitia']);
    }

    /**
     * Determine if user can delete mahasiswa records
     * 
     * CRITICAL SECURITY RULE:
     * - Admin: Can delete anytime (with warning if voted)
     * - Panitia: Can ONLY delete if mahasiswa hasn't voted
     * - Others: Cannot delete
     */
    public function delete(User $user, Mahasiswa $mahasiswa): bool
    {
        if ($user->role === 'admin') {
            // Admin can delete, but should be warned if voted
            return true;
        }

        if ($user->role === 'panitia') {
            // Panitia CANNOT delete if student already voted
            // This prevents vote manipulation
            return !$mahasiswa->voted_at && !$mahasiswa->dpm_voted_at;
        }

        return false;
    }

    /**
     * Determine if user can delete all DPT (bulk delete)
     */
    public function deleteAll(User $user): bool
    {
        // Only Admin can perform bulk delete
        // This is a high-risk operation
        return $user->role === 'admin';
    }

    /**
     * Determine if user can import DPT from CSV
     */
    public function import(User $user): bool
    {
        // Admin and Panitia can import
        return in_array($user->role, ['admin', 'panitia']);
    }

    /**
     * Determine if user can generate access codes
     */
    public function generateAccessCodes(User $user): bool
    {
        // Only Admin can generate access codes
        // This is sensitive operation affecting voting integrity
        return $user->role === 'admin';
    }

    /**
     * Determine if user can download notification letters
     */
    public function downloadLetter(User $user, Mahasiswa $mahasiswa): bool
    {
        // Admin and Panitia can download letters
        return in_array($user->role, ['admin', 'panitia']);
    }

    /**
     * Determine if user can download batch letters
     */
    public function downloadBatchLetters(User $user): bool
    {
        // Admin and Panitia can download batch letters
        return in_array($user->role, ['admin', 'panitia']);
    }

    /**
     * Determine if KPPS can scan QR code
     */
    public function scanQrCode(User $user): bool
    {
        // KPPS, Admin, and Panitia can use scanner
        return in_array($user->role, ['admin', 'panitia', 'kpps']);
    }

    /**
     * Determine if user can mark attendance
     */
    public function markAttendance(User $user, Mahasiswa $mahasiswa): bool
    {
        // KPPS, Admin, and Panitia can mark attendance
        return in_array($user->role, ['admin', 'panitia', 'kpps']);
    }
}
