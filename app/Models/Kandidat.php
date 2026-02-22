<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kandidat extends Model
{
    protected $guarded = ['id'];

    /**
     * Get all votes for this kandidat
     */
    public function votes()
    {
        return $this->hasMany(Vote::class, 'kandidat_id');
    }

    /**
     * Koalisi partai pengusung (many-to-many via kandidat_party)
     */
    public function parties()
    {
        return $this->belongsToMany(Party::class, 'kandidat_party')
                    ->withTimestamps();
    }

    /**
     * Automatically convert basename to full relative path for storage
     * Ensures compatibility with existing views using asset('storage/...')
     */
    public function getFotoAttribute($value)
    {
        if (empty($value))
            return null;
        // Jika sudah berisi '/', berarti path lengkap — kembalikan apa adanya
        if (\Illuminate\Support\Str::contains($value, '/'))
            return $value;
        // Kembalikan path relatif TANPA prefix 'storage/' karena semua blade
        // sudah menggunakan asset('storage/' . $kandidat->foto)
        return "images/medium/{$value}.webp";
    }

    public function getFotoWakilAttribute($value)
    {
        if (empty($value))
            return null;
        // Jika sudah berisi '/', berarti path lengkap — kembalikan apa adanya
        if (\Illuminate\Support\Str::contains($value, '/'))
            return $value;
        // Kembalikan path relatif TANPA prefix 'storage/' karena semua blade
        // sudah menggunakan asset('storage/' . $kandidat->foto_wakil)
        return "images/medium/{$value}.webp";
    }
}
