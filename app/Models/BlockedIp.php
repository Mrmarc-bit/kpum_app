<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedIp extends Model
{
    protected $fillable = ['ip_address', 'reason', 'attempts', 'is_permanent', 'blocked_until'];

    protected $casts = [
        'blocked_until' => 'datetime',
        'is_permanent' => 'boolean',
    ];
}
