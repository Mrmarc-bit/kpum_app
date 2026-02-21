<?php

namespace App\Services;

use App\Models\Vote;
use App\Models\DpmVote;
use App\Models\AuditLog;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\VoteEncryptionService;

class VoteService
{
    /**
     * Submit a Presma Vote safely with Redis Atomic Lock + DB Pessimistic Lock
     * 
     * CRITICAL SECURITY: Prevents race condition attacks
     * Protection Layers:
     * 1. Redis distributed lock (prevents concurrent requests)
     * 2. Database pessimistic lock (prevents concurrent transactions)
     * 3. Double-check after lock acquisition
     */
    public function submitPresmaVote(Mahasiswa $mahasiswa, int $kandidatId)
    {
        // 1. Redis Lock to prevent double submission race condition for THIS specific student
        // Key: vote_lock_presma_{student_id}
        // Wait max 5s, Release after 10s
        return Cache::lock('vote_lock_presma_' . $mahasiswa->id, 10)->block(5, function () use ($mahasiswa, $kandidatId) {
            
            // Lakukan enkripsi DULUAN sebelum lock transaksi DB (Hemat waktu koneksi DB)
            $encryptionLevel = VoteEncryptionService::getCurrentLevel(); 
            $voteData = ['kandidat_id' => $kandidatId, 'encryption_meta' => null];

            try {
                $encrypted = VoteEncryptionService::encryptVote([
                    'mahasiswa_id' => $mahasiswa->id,
                    'kandidat_id' => $kandidatId
                ], $encryptionLevel);
                
                $voteData['kandidat_id'] = $encrypted['kandidat_id'];
                $voteData['encryption_meta'] = $encrypted['encryption_meta'];

            } catch (\Exception $e) {
                Log::error('Vote Encryption Failed (Presma)', ['user' => $mahasiswa->id, 'error' => $e->getMessage()]);
                $encryptionLevel = 'none (fallback)';
            }

            return DB::transaction(function () use ($mahasiswa, $kandidatId, $encryptionLevel, $voteData) {
                // 2. CRITICAL: Pessimistic Lock (SELECT FOR UPDATE)
                // This prevents race condition at the database level
                $lockedMahasiswa = Mahasiswa::where('id', $mahasiswa->id)
                    ->lockForUpdate()
                    ->first();
                
                // 3. Double Check inside lock (Source of Truth)
                if ($lockedMahasiswa->voted_at) {
                    throw new \Exception('Anda sudah memberikan suara untuk Presma.');
                }

                // Create Vote
                Vote::create([
                    'mahasiswa_id' => $lockedMahasiswa->id,
                    'kandidat_id' => $voteData['kandidat_id'],
                    'encryption_meta' => $voteData['encryption_meta']
                ]);

                // Update Status with timestamp
                $lockedMahasiswa->update(['voted_at' => now()]);

                // Audit
                AuditLog::create([
                    'user_id' => $lockedMahasiswa->id,
                    'user_name' => $lockedMahasiswa->name ?? 'Mahasiswa',
                    'action' => 'VOTE: PRESMA',
                    'details' => "Mahasiswa {$lockedMahasiswa->nim} memilih kandidat (Enc: {$encryptionLevel})",
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);

                return true;
            });
        });
    }

    /**
     * Submit a DPM Vote safely with Redis Atomic Lock + DB Pessimistic Lock
     * 
     * CRITICAL SECURITY: Prevents race condition attacks
     */
    public function submitDpmVote(Mahasiswa $mahasiswa, int $calonDpmId)
    {
        return Cache::lock('vote_lock_dpm_' . $mahasiswa->id, 10)->block(5, function () use ($mahasiswa, $calonDpmId) {
            
            $encryptionLevel = VoteEncryptionService::getCurrentLevel(); 
            $voteData = ['calon_dpm_id' => $calonDpmId, 'encryption_meta' => null];

            try {
                $encrypted = VoteEncryptionService::encryptVote([
                    'mahasiswa_id' => $mahasiswa->id,
                    'kandidat_id' => $calonDpmId
                ], $encryptionLevel);
                
                $voteData['calon_dpm_id'] = $encrypted['kandidat_id'];
                $voteData['encryption_meta'] = $encrypted['encryption_meta'];

            } catch (\Exception $e) {
                Log::error('Vote Encryption Failed (DPM)', ['user' => $mahasiswa->id, 'error' => $e->getMessage()]);
                $encryptionLevel = 'none (fallback)';
            }

            return DB::transaction(function () use ($mahasiswa, $calonDpmId, $encryptionLevel, $voteData) {
                // CRITICAL: Pessimistic Lock (SELECT FOR UPDATE)
                $lockedMahasiswa = Mahasiswa::where('id', $mahasiswa->id)
                    ->lockForUpdate()
                    ->first();
                
                // Double-check after acquiring lock
                if ($lockedMahasiswa->dpm_voted_at) {
                    throw new \Exception('Anda sudah memberikan suara untuk DPM.');
                }

                DpmVote::create([
                    'mahasiswa_id' => $lockedMahasiswa->id,
                    'calon_dpm_id' => $voteData['calon_dpm_id'],
                    'encryption_meta' => $voteData['encryption_meta']
                ]);

                $lockedMahasiswa->update(['dpm_voted_at' => now()]);

                AuditLog::create([
                    'user_id' => $lockedMahasiswa->id,
                    'user_name' => $lockedMahasiswa->name ?? 'Mahasiswa',
                    'action' => 'VOTE: DPM',
                    'details' => "Mahasiswa {$lockedMahasiswa->nim} memilih DPM (Enc: {$encryptionLevel})",
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);

                return true;
            });
        });
    }
}
