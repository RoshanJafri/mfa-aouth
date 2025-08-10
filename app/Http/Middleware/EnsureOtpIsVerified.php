<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureOtpIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        if (session('otp_verified') || session('device_trusted')) {
            return $next($request);
        }

        return redirect()->route('verify.otp.form')
            ->with('error', 'You must verify OTP before continuing.');
    }
}
