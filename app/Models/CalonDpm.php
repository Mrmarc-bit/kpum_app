<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalonDpm extends Model
{
    protected $guarded = ['id'];

    public function dpmVotes()
    {
        return $this->hasMany(DpmVote::class, 'calon_dpm_id');
    }

    public function parties()
    {
        return $this->belongsToMany(Party::class, 'calon_dpm_party', 'calon_dpm_id', 'party_id')->withTimestamps();
    }
    public function getFotoAttribute($value)
    {
        if (empty($value)) return null;
        // Jika sudah berisi '/', berarti path lengkap — kembalikan apa adanya
        if (\Illuminate\Support\Str::contains($value, '/')) return $value;
        // Kembalikan path relatif TANPA prefix 'storage/' karena blade
        // sudah menggunakan asset('storage/' . $calon->foto)
        return "images/medium/{$value}.webp";
    }
}
