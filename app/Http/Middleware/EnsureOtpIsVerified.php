<?php

namespace App\Http\Middleware;

use App\Models\LoginLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOtpIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $loginLogId = session('login_log_id');

        if (!$loginLogId) {
            return $next($request);
        }

        $loginLog = LoginLog::find($loginLogId);

        if (!$loginLog) {
            session()->forget('login_log_id');
            return $next($request);
        }

        if (
            $loginLog->requires_otp &&
            is_null($loginLog->otp_verified_at)
        ) {
            if (!$request->routeIs('otp.*')) {
                return redirect()->route('otp.verify');
            }
        }

        return $next($request);
    }
}