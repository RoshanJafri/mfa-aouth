<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        $googleUser = Socialite::driver('google')->user();


        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'email_verified_at' => now(),
                'password' => bcrypt(Str::random(16)),
                'last_login_at' => now(),

                'social_accounts' => json_encode([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar()
                ]),
            ]
        );


        if (!$user->wasRecentlyCreated) {
            $socialAccounts = json_decode($user->social_accounts ?? '{}', true) ?: [];
            $socialAccounts['google_id'] = $googleUser->getId();
            $socialAccounts['avatar'] = $googleUser->getAvatar();
            $user->social_accounts = json_encode($socialAccounts);

            $user->last_login_at = now();
            $user->last_login_ip = $request->ip();
            $user->save();
        }

        Auth::login($user);
        session(['otp_verified' => true]);

        return redirect(route('device.oauth-check.form'));
    }
}
