<?php

namespace App\Policies;

use App\Models\Vote;
use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * VotePolicy - ULTRA-CRITICAL Security
 * 
 * CRITICAL RULES:
 * 1. Votes are IMMUTABLE (cannot be updated/deleted after creation)
 * 2. Only Admin can view aggregated vote data (for reports)
 * 3. Individual votes CANNOT be viewed (anonymity protection)
 * 4. Only Mahasiswa can create votes (via voting flow)
 * 
 * Authorization Matrix:
 * ┌──────────────┬────────┬──────────┬──────┬─────────┐
 * │ Action       │ Admin  │ Panitia  │ KPPS │Mahasiswa│
 * ├──────────────┼────────┼──────────┼──────┼─────────┤
 * │ viewAny      │   ⚠️   │    ❌    │  ❌  │   ❌    │
 * │ view         │   ❌   │    ❌    │  ❌  │   ❌    │
 * │ create       │   ❌   │    ❌    │  ❌  │   ✅    │
 * │ update       │   ❌   │    ❌    │  ❌  │   ❌    │
 * │ delete       │   ❌   │    ❌    │  ❌  │   ❌    │
 * │ viewReport   │   ✅   │    ✅    │  ❌  │   ❌    │
 * └──────────────┴────────┴──────────┴──────┴─────────┘
 * 
 * ⚠️  Admin can ONLY view aggregated data, NOT individual votes
 * ❌  NO ONE can update or delete votes (integrity protection)
 */
class VotePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if user can view vote list
     * 
     * CRITICAL: This should NEVER return true for individual votes
     * Only aggregated/anonymized data is allowed
     */
    public function viewAny(?User $user): bool
    {
        // NO ONE can view individual vote list
        // This protects voter anonymity
        return false;
    }

    /**
     * Determine if user can view a specific vote
     * 
     * ULTRA-CRITICAL: Protect voter anonymity
     */
    public function view(?User $user, Vote $vote): bool
    {
        // ABSOLUTELY NO ONE can view individual votes
        // This ensures vote secrecy is maintained
        return false;
    }

    /**
     * Determine if user can create votes
     * 
     * Note: This is handled by VoteService with additional checks
     */
    public function create(?User $user): bool
    {
        // This policy is not used directly
        // Vote creation is handled through VoteController with Mahasiswa guard
        // Kept here for completeness
        return false;
    }

    /**
     * Determine if user can update votes
     * 
     * CRITICAL: Votes are IMMUTABLE
     */
    public function update(?User $user, Vote $vote): bool
    {
        // VOTES CANNOT BE MODIFIED UNDER ANY CIRCUMSTANCES
        // This maintains election integrity
        return false;
    }

    /**
     * Determine if user can delete votes
     * 
     * CRITICAL: Votes cannot be deleted (audit trail)
     */
    public function delete(?User $user, Vote $vote): bool
    {
        // VOTES CANNOT BE DELETED
        // Even Admin cannot delete votes
        // Use soft deletes ONLY for emergency with audit log
        return false;
    }

    /**
     * Determine if user can view aggregated vote reports
     */
    public function viewReports(User $user): bool
    {
        // Only Admin and Panitia can view vote counts
        // Reports show ONLY aggregated data, never individual votes
        return in_array($user->role, ['admin', 'panitia']);
    }

    /**
     * Determine if user can export vote data
     */
    public function exportResults(User $user): bool
    {
        // Only Admin and Panitia can export results
        // Export MUST be anonymized (no mahasiswa_id exposed)
        return in_array($user->role, ['admin', 'panitia']);
    }

    /**
     * Determine if user can view vote encryption metadata
     */
    public function viewEncryption(User $user): bool
    {
        // Only Admin can view encryption settings
        // This is for security audit purposes
        return $user->role === 'admin';
    }

    /**
     * Determine if user can reset all votes (emergency)
     * 
     * CRITICAL: Requires special authorization
     */
    public function resetElection(User $user): bool
    {
        // Only Admin can reset election
        // This should be combined with additional confirmation
        // (e.g., 2FA, confirmation code, multiple approvals)
        return $user->role === 'admin';
    }

    /**
     * Special check for Mahasiswa vote creation
     * Called from VoteController
     */
    public static function canMahasiswaVote(?Mahasiswa $mahasiswa, string $type): bool
    {
        if (!$mahasiswa) {
            return false;
        }

        // Check if already voted
        if ($type === 'presma' && $mahasiswa->voted_at) {
            return false;
        }

        if ($type === 'dpm' && $mahasiswa->dpm_voted_at) {
            return false;
        }

        // Check voting period
        $startTime = \App\Models\Setting::get('voting_start_time');
        $endTime = \App\Models\Setting::get('voting_end_time');

        if (!$startTime || !$endTime) {
            return false;
        }

        try {
            $start = \Carbon\Carbon::parse($startTime);
            $end = \Carbon\Carbon::parse($endTime);
            $now = now();

            if (!$now->between($start, $end)) {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
