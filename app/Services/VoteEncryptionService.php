<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class VoteEncryptionService
{
    /**
     * Encrypt vote data based on selected encryption level
     */
    public static function encryptVote($data, string $level = 'standard'): array
    {
        $encryptedData = $data;
        
        switch ($level) {
            case 'high':
                // Double encryption with custom timestamp salt
                $salt = env('APP_KEY') . now()->timestamp;
                $encryptedData['kandidat_id'] = base64_encode(Crypt::encryptString($data['kandidat_id'] . '|' . $salt));
                $encryptedData['encryption_meta'] = [
                    'level' => 'high',
                    'salt_hash' => Hash::make($salt),
                    'encrypted_at' => now()->toDateTimeString()
                ];
                break;
                
            case 'blockchain':
                // Global lock untuk mencegah 1000 suara menimpa state previous_hash secara paralel
                $lock = \Illuminate\Support\Facades\Cache::lock('global_blockchain_insert', 10);
                
                try {
                    $lock->block(5); // Wait max 5 seconds for lock
                    
                    // Blockchain-like chain verification
                    $previousHash = self::getLastBlockHash();
                    $blockData = [
                        'kandidat_id' => $data['kandidat_id'],
                        'mahasiswa_id' => $data['mahasiswa_id'],
                        'timestamp' => now()->timestamp,
                        'previous_hash' => $previousHash
                    ];
                    
                    $blockHash = hash('sha256', json_encode($blockData));
                    
                    $encryptedData['kandidat_id'] = Crypt::encryptString($data['kandidat_id']);
                    $encryptedData['encryption_meta'] = [
                        'level' => 'blockchain',
                        'block_hash' => $blockHash,
                        'previous_hash' => $previousHash,
                        'chain_index' => self::getChainIndex() + 1,
                        'encrypted_at' => now()->toDateTimeString()
                    ];
                    
                    // Store hash in chain
                    self::storeBlockHash($blockHash);
                } finally {
                    $lock->release();
                }
                break;
                
            case 'standard':
            default:
                // Standard Laravel encryption
                $encryptedData['kandidat_id'] = Crypt::encryptString($data['kandidat_id']);
                $encryptedData['encryption_meta'] = [
                    'level' => 'standard',
                    'encrypted_at' => now()->toDateTimeString()
                ];
                break;
        }
        
        return $encryptedData;
    }
    
    /**
     * Decrypt vote data
     */
    public static function decryptVote($encryptedKandidatId, ?array $meta = null): int
    {
        if (!$meta || !isset($meta['level'])) {
            // Fallback: try standard decryption
            return (int) Crypt::decryptString($encryptedKandidatId);
        }
        
        switch ($meta['level']) {
            case 'high':
                // Decrypt and strip salt
                $decoded = base64_decode($encryptedKandidatId);
                $decrypted = Crypt::decryptString($decoded);
                return (int) explode('|', $decrypted)[0];
                
            case 'blockchain':
            case 'standard':
            default:
                return (int) Crypt::decryptString($encryptedKandidatId);
        }
    }
    
    /**
     * Get current encryption level from settings
     */
    public static function getCurrentLevel(): string
    {
        return \App\Models\Setting::get('encryption_level', 'standard');
    }
    
    /**
     * Verify blockchain integrity
     */
    public static function verifyBlockchainIntegrity(): array
    {
        $votes = \App\Models\Vote::whereJsonContains('encryption_meta->level', 'blockchain')
            ->orderBy('id')
            ->get();
            
        $valid = true;
        $errors = [];
        
        foreach ($votes as $index => $vote) {
            if ($index === 0) continue; // Skip first block
            
            $previousVote = $votes[$index - 1];
            $meta = $vote->encryption_meta;
            $previousMeta = $previousVote->encryption_meta;
            
            if ($meta['previous_hash'] !== $previousMeta['block_hash']) {
                $valid = false;
                $errors[] = "Chain broken at vote ID {$vote->id}";
            }
        }
        
        return [
            'valid' => $valid,
            'total_blocks' => $votes->count(),
            'errors' => $errors
        ];
    }
    
    /**
     * Get last block hash from cache
     */
    private static function getLastBlockHash(): ?string
    {
        return \Illuminate\Support\Facades\Cache::get('vote_blockchain_last_hash');
    }
    
    /**
     * Store block hash to cache
     */
    private static function storeBlockHash(string $hash): void
    {
        \Illuminate\Support\Facades\Cache::put('vote_blockchain_last_hash', $hash);
        \Illuminate\Support\Facades\Cache::increment('vote_blockchain_index', 1);
    }
    
    /**
     * Get current chain index
     */
    private static function getChainIndex(): int
    {
        return (int) \Illuminate\Support\Facades\Cache::get('vote_blockchain_index', 0);
    }
}
