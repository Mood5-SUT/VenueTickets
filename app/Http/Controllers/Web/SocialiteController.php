<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    // Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();
            return $this->handleSocialUser($socialUser, 'google');
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google login failed. Please try again.']);
        }
    }

    // Common handler for social logins
    private function handleSocialUser($socialUser, $provider)
    {
        $user = User::where('provider', $provider)
                    ->where('provider_id', $socialUser->getId())
                    ->first();

        if (!$user) {
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                $user->provider = $provider;
                $user->provider_id = $socialUser->getId();
                $user->avatar = $socialUser->getAvatar();
                $user->save();
            } else {
                $user = User::create([
                    'name' => $socialUser->getName() ?? 'User',
                    'email' => $socialUser->getEmail(),
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar(),
                    'is_active' => true,
                    'is_verified' => true,
                    'email_verified_at' => now(),
                ]);

                $user->assignRole('customer');
            }
        }

        if ($user->isBanned()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been suspended.']);
        }

        Auth::login($user);

        if ($user->hasRole(['super-admin', 'admin'])) {
            return redirect()->intended(route('admin_dashboard'));
        }

        return redirect()->intended(route('home'));
    }
}