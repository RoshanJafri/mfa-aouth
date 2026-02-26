<?php

namespace App\Models;

use App\Models\Device;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{

    protected $table = "login_logs";

    protected $fillable = [
        'user_id',
        'device_id',
        'latitude',
        'longitude',
        'ip_address',
        'risk_score',
        'requires_otp',
        'otp_verified_at',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
