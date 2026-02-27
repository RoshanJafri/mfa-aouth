<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Device;
use App\Models\LoginLog;
use App\Models\User;
use App\Services\GeoService;
use App\Services\OtpService;
use App\Services\RiskService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{


    public function __construct(GeoService $geo, RiskService $riskService)
    {
        $this->geo = $geo;
        $this->riskService = $riskService;
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $user = User::where('email', $request->email)->first();

        if (!$user || !password_verify($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials.']);
        }

        $user->load('devices'); // eager load devices

        $deviceUuid = $request->device_uuid;
        $fingerprint = $request->fingerprint_hash;
        $currentLat = $request->latitude;
        $currentLon = $request->longitude;

        // Find or create device
        $device = Device::updateOrCreate(
            [
                'user_id' => $user->id,
                'device_uuid' => $deviceUuid,
            ],
            [
                'fingerprint_hash' => $fingerprint,
                'user_agent' => $request->userAgent(),
                'last_used_at' => now(),
            ]
        );

        // Calculate risk BEFORE login
        $risk = $this->riskService->calculate([
            'user_devices' => $user->devices,
            'device_uuid' => $deviceUuid,
            'fingerprint_hash' => $fingerprint,
            'latitude' => $currentLat,
            'longitude' => $currentLon,
            'last_login' => $user->loginLogs()->latest()->first(),
        ]);


        // Store login log
        $loginLog = LoginLog::create([
            'user_id' => $user->id,
            'device_id' => $device->id,
            'ip_address' => $request->ip(),
            'latitude' => $currentLat,
            'longitude' => $currentLon,
            'risk_score' => $risk,
            'requires_otp' => $risk >= 60,
            'status' => 'pending',
        ]);

        session(['pending_risk_score' => $risk]);

        // High-risk login , go to OTP
        if ($risk >= 60) {

            session([
                'login_log_id' => $loginLog->id
            ]);

            $otpService = new OtpService();
            $otpService->generate($loginLog->user);

            return redirect()->route('otp.verify')
                ->withErrors(['otp' => 'High-risk login detected. Please verify through OTP (risk score of ' . $risk . ')']);
        }

        // login user

        Auth::login($user);
        $request->session()->regenerate();

        $loginLog->update([
            'status' => 'success',
        ]);


        if (!$device->trusted) {
            return redirect()->route('trust.device')
                ->with('success', 'Account verified successfully.');
        } else {
            return redirect()->intended(route('dashboard'));
        }

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
