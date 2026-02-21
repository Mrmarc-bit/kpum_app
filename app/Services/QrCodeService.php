<?php

namespace App\Services;

class QrCodeService
{
    /**
     * Generate an encrypted string containing student identity.
     * 
     * @param \App\Models\Mahasiswa $mahasiswa
     * @return string
     */
    public function generateSecureCode(\App\Models\Mahasiswa $mahasiswa)
    {
        $data = [
            'id' => $mahasiswa->id,
            'nim' => $mahasiswa->nim,
            // Minimal data to keep QR code less dense
        ];

        // Encrypt the JSON data
        return \Illuminate\Support\Facades\Crypt::encryptString(json_encode($data));
    }

    /**
     * Decrypt the secure string to get student data.
     * 
     * @param string $encryptedString
     * @return array|null Returns null if decryption fails
     */
    public function decryptSecureCode($encryptedString)
    {
        try {
            $decrypted = \Illuminate\Support\Facades\Crypt::decryptString($encryptedString);
            return json_decode($decrypted, true);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return null;
        }
    }

    /**
     * Generate a unique, readable access code for login.
     * Format: K-[RANDOM_LETTERS]-[RANDOM_NUMBERS] like K-AB-1234
     * 
     * @return string
     */
    public function generateAccessCode()
    {
        $letters = strtoupper(\Illuminate\Support\Str::random(2));
        $numbers = rand(1000, 9999);
        return "K-{$letters}-{$numbers}";
    }
}
