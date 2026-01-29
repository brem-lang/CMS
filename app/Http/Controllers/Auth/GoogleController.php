<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            Log::error('Google OAuth redirect failed: '.$e->getMessage());

            return redirect()->route('login')
                ->with('error', 'Unable to connect with Google. Please try again.');
        }
    }

    /**
     * Obtain the user information from Google.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user already exists
            $user = \App\Models\User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // User exists - automatically log them in
                Auth::login($user, true);
            } else {
                // User doesn't exist - create new user
                $user = \App\Models\User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'email_verified_at' => now(),
                    'role' => 'user',
                    'password' => Hash::make('password'), // Random password since OAuth
                ]);

                // Log the new user in
                Auth::login($user, true);
            }

            // Migrate guest cart to database if user had items in session
            app(CartService::class)->migrateGuestCartToDatabase($user->id);

            session()->regenerate();

            return redirect()->intended(route('home'));
        } catch (\Exception $e) {
            Log::error('Google OAuth callback failed: '.$e->getMessage());

            return redirect()->route('login')
                ->with('error', 'Unable to authenticate with Google. Please try again.');
        }
    }
}
