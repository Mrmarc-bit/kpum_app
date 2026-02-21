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
     * Automatically convert basename to full relative path for storage
     * Ensures compatibility with existing views using asset('storage/...')
     */
    public function getFotoAttribute($value)
    {
        if (empty($value)) return null;
        if (\Illuminate\Support\Str::contains($value, '/')) return $value;
        return "storage/images/medium/{$value}.webp";
    }

    public function getFotoWakilAttribute($value)
    {
        if (empty($value)) return null;
        if (\Illuminate\Support\Str::contains($value, '/')) return $value;
        return "storage/images/medium/{$value}.webp";
    }
}
