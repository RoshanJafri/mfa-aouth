<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\LoginLog;
use App\Models\Otp;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function index(){
        $otps = Otp::orderBy("created_at","desc")->paginate(20);   
        return view("otp.index" ,compact("otps"));
    }

    public function show()
    {
        return view('otp.verify');
    }

    public function resend(OtpService $otpService)
    {


        $loginLogId = session('login_log_id');

        if (!$loginLogId) {
            return redirect()->route('login');
        }

        $loginLog = LoginLog::with('user')->find($loginLogId);

        if (!$loginLog) {
            return redirect()->route('login');
        }

        $otpService->generate($loginLog->user);

        return redirect()->route('otp.verify')
            ->with('success', 'OTP resent successfully.');

    }

    public function verify(Request $request, OtpService $otpService)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $loginLogId = session('login_log_id');

        if (!$loginLogId) {
            return redirect()->route('login')
                ->withErrors(['otp' => 'Session expired. Please login again.']);
        }

        $loginLog = LoginLog::with('user')->find($loginLogId);

        if (!$loginLog || !$loginLog->requires_otp) {
            return redirect()->route('login');
        }

        $user = $loginLog->user;

        if (!$otpService->verify($user, $request->otp)) {
            return back()->withErrors([
                'otp' => 'Invalid or expired OTP.',
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        // 📱 4️⃣ Update device (from original login request)
        $device = Device::updateOrCreate(
            [
                'user_id' => $user->id,
                'device_uuid' => $loginLog->device->device_uuid,
            ],
            [
                'fingerprint_hash' => $request->fingerprint_hash,
                'latitude' => $loginLog->latitude,
                'longitude' => $loginLog->longitude,
                'user_agent' => $request->userAgent(),
                'last_used_at' => now(),
            ]
        );

        $loginLog->update([
            'device_id' => $device->id,
            'otp_verified_at' => now(),
            'status' => 'success',
        ]);

        session()->forget('login_log_id');

        if(!$device->trusted){
            return redirect()->route('trust.device')
            ->with('success', 'Account verified successfully.');
        }else{
            return redirect()->route('dashboard');
        }
    }
}