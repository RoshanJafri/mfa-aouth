<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function showForm()
    {
        return view('otp.form');
    }

    public function verify(Request $request)
    {
        $otpExpiresAt = session('otp_expires_at');

        if (!$otpExpiresAt || now()->gt($otpExpiresAt)) {
            return back()->withErrors(['otp' => 'OTP expired or not set.']);
        }

        if ($request->input('otp') != session('otp_code')) {
            return back()->withErrors(['otp' => 'Incorrect OTP.']);
        }

        session(['otp_verified' => true]);


        session(['otp_device_pending_trust' => true]);


        $lat = $request->input('lat');
        $lng = $request->input('lng');

        if (is_numeric($lat) && is_numeric($lng)) {
            $user = \App\Models\User::find(session('user_id_for_otp'));
            if ($user) {
                $user->geolocation_history = $lat . ',' . $lng;
                $user->save();
            }
        }


        return redirect()->route('device.check.form');
    }
}
