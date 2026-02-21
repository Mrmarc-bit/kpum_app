<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Mahasiswa;
use App\Models\Vote;
use App\Models\DpmVote;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResetElectionService
{
    /**
     * Nukes all election data. WARNING: Destructive Action.
     * Transaction safe.
     */
    public function nukeElectionData($user, $ipAddress, $userAgent)
    {
        return DB::transaction(function () use ($user, $ipAddress, $userAgent) {
            // 1. Truncate Tables
            $presmaCount = Vote::count();
            Vote::query()->delete();

            $dpmCount = DpmVote::count();
            DpmVote::query()->delete();

            // 2. Reset Voter Status (Batched Update is fast enough for SQL)
            Mahasiswa::query()->update([
                'voted_at' => null, 
                'dpm_voted_at' => null,
                'voting_method' => null,
                'ip_address' => null,
                'attended_at' => null
            ]);

            // 3. Log Action
            AuditLog::create([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'action' => 'RESET ELECTION',
                'details' => "Reset pemilihan: {$presmaCount} Presma votes + {$dpmCount} DPM votes dihapus, semua status voter direset.",
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent
            ]);

            Log::warning('ELECTION RESET EXECUTED', [
                'user' => $user->name,
                'ip' => $ipAddress
            ]);

            return true;
        });
    }
}
