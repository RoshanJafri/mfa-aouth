<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'device_uuid' => 'required|uuid',
            'fingerprint_hash' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $device = Device::updateOrCreate(
            ['user_id' => Auth::id(), 'device_uuid' => $request->device_uuid],
            [
                'fingerprint_hash' => $request->fingerprint_hash,
                'trusted' => true,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'trusted' => $request->trusted,
                'last_used_at' => now(),
            ]
        );

        return response()->json(['message'=>'Device registered', 'device'=>$device]);
    }
}