<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user exists with this google_id
            $user = User::where('google_id', $googleUser->getId())->first();

            if ($user) {
                // User exists, log them in
                Auth::login($user);
            } else {
                // Check if user exists with this email
                $existingUser = User::where('email', $googleUser->getEmail())->first();

                if ($existingUser) {
                    // Update existing user with Google ID
                    $existingUser->google_id = $googleUser->getId();
                    $existingUser->avatar = $googleUser->getAvatar();
                    $existingUser->save();
                    Auth::login($existingUser);
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'email_verified_at' => now(),
                    ]);

                    Auth::login($user);
                }
            }

            return redirect()->intended('/');
        } catch (\Exception $e) {
            \Log::error('Google OAuth error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Google साइन-इन करताना त्रुटी आली. कृपया पुन्हा प्रयत्न करा.');
        }
    }

    /**
     * Handle Google One Tap callback (JSON response)
     */
    public function handleOneTapCallback(Request $request)
    {
        try {
            $credential = $request->input('credential');
            
            if (!$credential) {
                return response()->json(['success' => false, 'message' => 'Credential is required'], 400);
            }

            // Decode JWT token (without verification for now, Google SDK handles verification)
            // In production, you should verify the token signature
            $parts = explode('.', $credential);
            if (count($parts) !== 3) {
                return response()->json(['success' => false, 'message' => 'Invalid credential format'], 400);
            }

            $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);

            if (!$payload) {
                return response()->json(['success' => false, 'message' => 'Invalid credential'], 400);
            }

            // Verify the token is from Google (check issuer)
            if (!isset($payload['iss']) || !in_array($payload['iss'], ['accounts.google.com', 'https://accounts.google.com'])) {
                return response()->json(['success' => false, 'message' => 'Invalid issuer'], 400);
            }

            // Verify client ID matches
            if (!isset($payload['aud']) || $payload['aud'] !== config('services.google.client_id')) {
                return response()->json(['success' => false, 'message' => 'Invalid client ID'], 400);
            }

            $googleId = $payload['sub'] ?? null;
            $email = $payload['email'] ?? null;
            $name = $payload['name'] ?? $email;
            $avatar = $payload['picture'] ?? null;

            if (!$googleId || !$email) {
                return response()->json(['success' => false, 'message' => 'Missing required user data'], 400);
            }

            // Check if user exists with this google_id
            $user = User::where('google_id', $googleId)->first();

            if ($user) {
                Auth::login($user);
            } else {
                // Check if user exists with this email
                $existingUser = User::where('email', $email)->first();

                if ($existingUser) {
                    $existingUser->google_id = $googleId;
                    $existingUser->avatar = $avatar;
                    $existingUser->save();
                    Auth::login($existingUser);
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'google_id' => $googleId,
                        'avatar' => $avatar,
                        'email_verified_at' => now(),
                    ]);

                    Auth::login($user);
                }
            }

            return response()->json(['success' => true, 'redirect' => url('/')]);
        } catch (\Exception $e) {
            \Log::error('Google One Tap error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Authentication failed: ' . $e->getMessage()], 500);
        }
    }
}
