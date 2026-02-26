<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'user_id',
        'device_uuid',
        'fingerprint_hash',
        'user_agent',
        'trusted',
        'latitude',
        'longitude',
        'last_used_at',
    ];

    protected $casts = [
        'trusted' => 'boolean',
        'last_used_at' => 'datetime',
    ];
}