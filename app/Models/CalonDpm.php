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
    public function getFotoAttribute($value)
    {
        if (empty($value)) return null;
        if (\Illuminate\Support\Str::contains($value, '/')) return $value;
        return "storage/images/medium/{$value}.webp";
    }
}
