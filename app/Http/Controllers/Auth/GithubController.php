<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GithubController extends Controller
{
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        $githubUser = Socialite::driver('github')->user();

        $user = User::firstOrCreate(
            ['email' => $githubUser->getEmail()],
            [
                'name' => $githubUser->getName() ?? $githubUser->getNickname(),
                'email_verified_at' => now(),
                'password' => bcrypt(str()->random(16)),
                'social_accounts' => json_encode([
                    'github_id' => $githubUser->getId(),
                    'username' => $githubUser->getNickname(),
                    'avatar' => $githubUser->getAvatar(),
                    'profile_url' => $githubUser->user['html_url'] ?? null
                ])
            ]
        );

        Auth::login($user);

        return redirect(route('dashboard'));
    }
}
