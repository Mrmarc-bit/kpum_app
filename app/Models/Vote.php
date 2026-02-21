<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'kandidat_id',
        'encryption_meta',
    ];

    protected $casts = [
        'encryption_meta' => 'array',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function kandidat()
    {
        return $this->belongsTo(Kandidat::class);
    }
}
