<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'logo_path',
    ];
    public function getLogoPathAttribute($value)
    {
        if (empty($value)) return null;
        if (\Illuminate\Support\Str::contains($value, '/')) return $value;
        return "images/medium/{$value}.webp";
    }

    /**
     * Kandidat yang didukung oleh partai ini
     */
    public function kandidats()
    {
        return $this->belongsToMany(Kandidat::class, 'kandidat_party')
                    ->withTimestamps();
    }
}
