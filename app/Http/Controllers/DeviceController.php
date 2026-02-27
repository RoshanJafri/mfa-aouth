<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{

    public function index()
    {
        $devices = Device::paginate(100);
        return view("devices.index", compact("devices"));
    }

    public function trust(Device $device)
    {
        abort_if($device->user_id !== auth()->id(), 403);

        $device->update(['trusted' => true]);

        return back();
    }

    public function untrust(Device $device)
    {
        abort_if($device->user_id !== auth()->id(), 403);

        $device->update(['trusted' => false]);

        return back();
    }

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
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'trusted' => $request->trusted,
                'last_used_at' => now(),
            ]
        );

        return redirect()->route('dashboard');
    }
}