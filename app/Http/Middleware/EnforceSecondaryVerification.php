<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnforceSecondaryVerification
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();


        $isOAuth = $user->social_accounts !== null;


        $emailVerified = $isOAuth || $user->mfa_email_verified_at;


        $deviceTrusted = in_array($request->userAgent(), $user->trusted_devices ?? []);


        $locationSafe = $this->isLocationSafe($request);

        if ($emailVerified && $deviceTrusted && $locationSafe) {
            return $next($request);
        }

        return redirect('/verify-mfa');
    }

    protected function isLocationSafe($request)
    {

        $user = Auth::user();
        $safeLocations = $user->geolocations ?? [];

        $currentLocation = session('current_location');

        foreach ($safeLocations as $safe) {
            if (
                abs($safe['lat'] - $currentLocation['lat']) < 0.5 &&
                abs($safe['lng'] - $currentLocation['lng']) < 0.5
            ) {
                return true;
            }
        }

        return false;
    }
}
