<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to OAuth provider
     */
    public function redirect(string $provider)
    {
        $this->validateProvider($provider);

        return Socialite::driver($provider)
            ->stateless()
            ->redirect();
    }

    /**
     * Handle OAuth callback
     */
    public function callback(string $provider)
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            return redirect($this->frontendUrl('/login?error=social_auth_failed'));
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
            }
        } else {
            // Update avatar on each login
            $user->update([
                'social_avatar' => $socialUser->getAvatar(),
            ]);
        }

        // Create Sanctum token
        $token = $user->createToken('social-auth-token')->plainTextToken;

        // Redirect to frontend callback page with token
        $params = http_build_query([
            'token' => $token,
            'user' => json_encode([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
                'avatar' => $user->social_avatar ?? $user->avatar ?? '',
                'role' => $user->role,
                'addresses' => $user->addresses ?? [],
            ]),
        ]);

        return redirect($this->frontendUrl("/auth/callback?{$params}"));
    }

    private function validateProvider(string $provider): void
    {
        if (!in_array($provider, ['google', 'facebook'])) {
            abort(404, 'Provider not supported');
        }
    }

    private function frontendUrl(string $path = ''): string
    {
        return rtrim(env('FRONTEND_URL', 'http://localhost:3000'), '/') . $path;
    }
}
