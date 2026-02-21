<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportFile extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'status',
        'path',
        'disk',
        'error_message',
        'details',
        'progress',
    ];

    /**
     * Get the user that generated this report file.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
