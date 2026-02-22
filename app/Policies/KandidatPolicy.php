<?php

namespace App\Policies;

use App\Models\Kandidat;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * KandidatPolicy - RBAC Implementation
 * 
 * Authorization Matrix:
 * ┌──────────────┬────────┬──────────┬──────┐
 * │ Action       │ Admin  │ Panitia  │ KPPS │
 * ├──────────────┼────────┼──────────┼──────┤
 * │ viewAny      │   ✅   │    ✅    │  ✅  │
 * │ view         │   ✅   │    ✅    │  ✅  │
 * │ create       │   ✅   │    ✅    │  ❌  │
 * │ update       │   ✅   │    ✅    │  ❌  │
 * │ delete       │   ✅   │    ⚠️    │  ❌  │
 * │ activate     │   ✅   │    ✅    │  ❌  │
 * └──────────────┴────────┴──────────┴──────┘
 * 
 * ⚠️  Panitia can delete ONLY if no votes exist for kandidat
 * ⚠️  Cannot delete kandidat during active voting period
 */
class KandidatPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if user can view kandidat list
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view kandidat list
        // (even KPPS for verification purposes)
        return in_array($user->role, ['admin', 'super_admin', 'panitia', 'kpps']);
    }

    /**
     * Determine if user can view a single kandidat
     */
    public function view(User $user, Kandidat $kandidat): bool
    {
        // All authenticated users can view kandidat details
        return in_array($user->role, ['admin', 'super_admin', 'panitia', 'kpps']);
    }

    /**
     * Determine if user can create kandidat
     */
    public function create(User $user): bool
    {
        // Only Admin and Panitia can add kandidat
        return in_array($user->role, ['admin', 'super_admin', 'panitia']);
    }

    /**
     * Determine if user can update kandidat
     */
    public function update(User $user, Kandidat $kandidat): bool
    {
        // Only Admin and Panitia can edit kandidat
        return in_array($user->role, ['admin', 'super_admin', 'panitia']);
    }

    /**
     * Determine if user can delete kandidat
     * 
     * CRITICAL RULES:
     * 1. Cannot delete if kandidat has votes
     * 2. Cannot delete during active voting period
     * 3. Panitia needs extra validation
     */
    public function delete(User $user, Kandidat $kandidat): bool
    {
        // Check if voting is active
        $votingActive = $this->isVotingActive();
        
        if (in_array($user->role, ['admin', 'super_admin'])) {
            // Admin can delete, but only if no votes exists
            // This prevents election manipulation
            $hasVotes = \App\Models\Vote::where('kandidat_id', $kandidat->id)->exists();
            
            if ($hasVotes) {
                return false; // Cannot delete kandidat with votes
            }
            
            // Warn if deleting during voting period
            return !$votingActive;
        }

        if ($user->role === 'panitia') {
            // Panitia has stricter rules
            $hasVotes = \App\Models\Vote::where('kandidat_id', $kandidat->id)->exists();
            
            // Panitia CANNOT delete if:
            // 1. Kandidat has votes
            // 2. Voting period is active
            return !$hasVotes && !$votingActive;
        }

        return false;
    }

    /**
     * Determine if user can activate/deactivate kandidat
     */
    public function toggleStatus(User $user, Kandidat $kandidat): bool
    {
        // Admin and Panitia can activate/deactivate
        return in_array($user->role, ['admin', 'super_admin', 'panitia']);
    }

    /**
     * Determine if user can upload kandidat photo
     */
    public function uploadPhoto(User $user, Kandidat $kandidat): bool
    {
        // Admin and Panitia can upload photos
        return in_array($user->role, ['admin', 'super_admin', 'panitia']);
    }

    /**
     * Check if voting period is currently active
     */
    private function isVotingActive(): bool
    {
        $startTime = \App\Models\Setting::get('voting_start_time');
        $endTime = \App\Models\Setting::get('voting_end_time');
        
        if (!$startTime || !$endTime) {
            return false;
        }
        
        try {
            $start = \Carbon\Carbon::parse($startTime);
            $end = \Carbon\Carbon::parse($endTime);
            $now = now();
            
            return $now->between($start, $end);
        } catch (\Exception $e) {
            return false;
        }
    }
}
