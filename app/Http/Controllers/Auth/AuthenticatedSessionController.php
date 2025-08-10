<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */ public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        $storedLocation = explode(',', $user->geolocation_history);
        if (count($storedLocation) < 2 || !is_numeric($storedLocation[0]) || !is_numeric($storedLocation[1])) {
            Mail::raw("New login detected from a different location than usual. Please log in to review.", function ($message) use ($user) {
                $message->to($user->email)->subject('New Login Location Alert');
            });


            $otp = rand(100000, 999999);
            session([
                'otp_code' => $otp,
                'otp_expires_at' => now()->addMinutes(5),
                'otp_verified' => false,
                'user_id_for_otp' => $user->id,
            ]);
            Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
                $message->to($user->email)->subject('Your Login OTP');
            });
            return redirect()->route('verify.otp.form')
                ->with('success', 'New location detected. Verify ownership by OTP.');
        }
        $currentLat = (float) $request->lat;
        $currentLng = (float) $request->lng;
        $distance = $this->haversineDistance($storedLocation[0], $storedLocation[1], $currentLat, $currentLng);

        if ($distance > 1) {
            Mail::raw("New login detected from a different location than usual. Please log in to review.", function ($message) use ($user) {
                $message->to($user->email)->subject('New Login Location Alert');
            });
                

            $otp = rand(100000, 999999);
            session([
                'otp_code' => $otp,
                'otp_expires_at' => now()->addMinutes(5),
                'otp_verified' => false,
                'user_id_for_otp' => $user->id,
            ]);
            Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
                $message->to($user->email)->subject('Your Login OTP');
            });
            return redirect()->route('verify.otp.form')
                ->with('success', 'New location detected. Verify ownership by OTP');
        }


        $fingerprint = json_decode($request->input('device_fingerprint'), true);


        $user->last_login_at = now();
        $user->last_login_ip = $request->ip();
        $user->geolocation_history = "$currentLat,$currentLng";
        $user->save();


        $trustedDevices = is_array($user->trusted_devices)
            ? $user->trusted_devices
            : json_decode($user->trusted_devices ?? '[]', true);


        $fingerprintId = $fingerprint['id'] ?? null;


        $alreadyTrusted = collect($trustedDevices)->pluck('id')->contains($fingerprintId);

        if (!$alreadyTrusted) {

            $otp = rand(100000, 999999);
            session([
                'otp_code' => $otp,
                'otp_expires_at' => now()->addMinutes(5),
                'otp_verified' => false,
                'user_id_for_otp' => $user->id,
                'pending_fingerprint' => $fingerprint,
            ]);

            Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
                $message->to($user->email)->subject('Your Login OTP');
            });

            return redirect()->route('verify.otp.form')
                ->with('success', 'OTP sent to your email. Enter to continue.');
        }


        session(['otp_verified' => true]);
        session(['fingerprint' => $fingerprint]);
        return redirect()->route('device.notify');
    }


    public function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
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
