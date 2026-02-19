<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to OAuth provider
     */
    public function redirect(string $provider)
    {
        $this->validateProvider($provider);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle OAuth callback
     */
    public function callback(string $provider)
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'ไม่สามารถเข้าสู่ระบบด้วย ' . ucfirst($provider) . ' ได้ กรุณาลองใหม่อีกครั้ง');
        }

        // Find existing user by social provider+id or by email
        $user = User::where('social_provider', $provider)
            ->where('social_id', $socialUser->getId())
            ->first();

        if (!$user) {
            // Check if user with same email exists (link accounts)
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                // Link social account to existing user
                $user->update([
                    'social_id' => $socialUser->getId(),
                    'social_provider' => $provider,
                    'social_avatar' => $socialUser->getAvatar(),
                ]);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                    'email' => $socialUser->getEmail(),
                    'social_id' => $socialUser->getId(),
                    'social_provider' => $provider,
                    'social_avatar' => $socialUser->getAvatar(),
                    'avatar' => $socialUser->getAvatar() ?? '',
                    'role' => 'customer',
                    'password' => null,
                ]);

                $user->markEmailAsVerified();
                Mail::to($user->email)->send(new WelcomeMail($user));
            }
        } else {
            // Update avatar on each login
            $user->update([
                'social_avatar' => $socialUser->getAvatar(),
            ]);
        }

        Auth::login($user, true);
        session()->regenerate();

        return redirect()->intended('/');
    }

    private function validateProvider(string $provider): void
    {
        if (!in_array($provider, ['google', 'facebook'])) {
            abort(404, 'Provider not supported');
        }
    }
}
