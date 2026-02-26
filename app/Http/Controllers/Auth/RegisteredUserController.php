<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
            
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        $device = \App\Models\Device::create([
            'user_id' => $user->id,
            'device_uuid' => $request->device_uuid,
            'fingerprint_hash' => $request->fingerprint_hash,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'user_agent' => $request->userAgent(),
            'last_used_at' => now(),
            'trusted' => 0, // not trusted until user confirms
        ]);

        $loginLog = LoginLog::create([
            'user_id' => $user->id,
            'device_id' => $device->id,
            'ip_address' => $request->ip(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'risk_score' => 0, // no risk scoring for registration
            'requires_otp' => true,
            'status' => 'pending',
        ]);

        session(['login_log_id' => $loginLog->id]);

        app(OtpService::class)->generate($user);

        return redirect()->route('otp.verify')
            ->with('warning', 'Please verify your email to activate your account.');
    }

    public function trustDevice()
    {
        return view('auth.trust-device');
    }

}
