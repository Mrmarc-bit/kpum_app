<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DpmVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'calon_dpm_id',
        'encryption_meta'
    ];
    
    protected $casts = [
        'encryption_meta' => 'array',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function calonDpm()
    {
        return $this->belongsTo(CalonDpm::class);
    }
}
