<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    public function notify()
    {
        $fingerprint = session('fingerprint');

        return view('fingerprinting.notification', ['fingerprint' => $fingerprint]);
    }

    public function check(Request $request)
    {
        $fingerprint = $request->device_fingerprint;
        $user = Auth::user();


        $trusted = ($user->trusted_devices === $fingerprint);

        return view('fingerprinting.result', [
            'trusted' => $trusted,
            'device_data' => $fingerprint
        ]);
    }
    public function ouathcheck(Request $request)
    {
        $user = Auth::user();

        $trustedDevices = json_decode($user->trusted_devices ?? '[]', true);
        if (!is_array($trustedDevices)) {
            $trustedDevices = [];
        }

        $lat = $request->input('lat');
        $lng = $request->input('lng');

        if (is_numeric($lat) && is_numeric($lng)) {
            if ($user) {
                $user->geolocation_history = $lat . ',' . $lng;
                $user->save();
            }
        }

        $fingerprintJson = $request->input('device_fingerprint');
        $fingerprint = json_decode($fingerprintJson, true);

        if (!$fingerprint || !is_array($fingerprint)) {
            return redirect()->route('device.check.form');
        }

        $incomingFingerprintId = $fingerprint['id'] ?? null;

        if (!$incomingFingerprintId) {
            return redirect()->route('device.check.form');
        }

        $isTrusted = collect($trustedDevices)->pluck('id')->contains($incomingFingerprintId);

        if ($isTrusted) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('device.check.form');
        }
    }

    public function trust(Request $request)
    {

        $fingerprintJson = $request->input('device_fingerprint');


        $fingerprint = json_decode($fingerprintJson, true);

        $user = Auth::user();


        $trusted = is_array($user->trusted_devices)
            ? $user->trusted_devices
            : json_decode($user->trusted_devices ?? '[]', true);


        $fingerprintId = $fingerprint['id'] ?? null;


        $alreadyTrusted = collect($trusted)->pluck('id')->contains($fingerprintId);

        if ($request->has('save_device') && !$alreadyTrusted && $fingerprintId) {

            $trusted[] = $fingerprint;


            $user->trusted_devices = json_encode($trusted);
            $user->save();
        }

        return redirect()->route('dashboard');
    }
    public function removeTrustedDevice(Request $request)
    {
        $user = Auth::user();
        $deviceId = $request->input('id');

        $trustedDevices = json_decode($user->trusted_devices ?? '[]', true);


        $updatedDevices = collect($trustedDevices)
            ->reject(fn($device) => $device['id'] === $deviceId)
            ->values()
            ->all();

        $user->trusted_devices = json_encode($updatedDevices);
        $user->save();

        return redirect()->back()->with('success', 'Device removed from trusted list.');
    }
}
