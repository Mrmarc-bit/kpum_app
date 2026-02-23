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
        if (\Illuminate\Support\Str::contains($value, '/'))
            return $value;
        return "images/medium/{$value}.webp";
    }

    public function getFotoThumbAttribute()
    {
        $value = $this->attributes['foto'] ?? null;
        if (empty($value))
            return null;
        if (\Illuminate\Support\Str::contains($value, '/'))
            return $value;
        return "images/thumb/{$value}.webp";
    }

    public function getFotoWakilAttribute($value)
    {
        if (empty($value))
            return null;
        if (\Illuminate\Support\Str::contains($value, '/'))
            return $value;
        return "images/medium/{$value}.webp";
    }

    public function getFotoWakilThumbAttribute()
    {
        $value = $this->attributes['foto_wakil'] ?? null;
        if (empty($value))
            return null;
        if (\Illuminate\Support\Str::contains($value, '/'))
            return $value;
        return "images/thumb/{$value}.webp";
    }
}
