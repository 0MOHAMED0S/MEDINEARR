<?php

namespace App\Http\Controllers\Dashboard\Pharmacy;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Look for an existing user by Google ID or Email
            $user = User::where(function ($query) use ($googleUser) {
                $query->where('provider_id', $googleUser->getId())
                    ->where('provider_type', 'google');
            })->orWhere('email', $googleUser->getEmail())->first();

            if ($user) {
                // SECURITY CHECK: Ensure the user's role is strictly 'pharmacy'
                if ($user->role !== 'pharmacy') {
                    return redirect('/login')->with('error', 'عفواً، هذا البريد الإلكتروني مسجل كحساب مستخدم عادي. الدخول من هنا مخصص للصيدليات فقط.');
                }

                // Check for provider conflict (e.g., registered via Facebook but trying to use Google)
                if ($user->provider_type !== null && $user->provider_type !== 'google') {
                    return redirect('/login')->with('error', "هذا البريد مسجل مسبقاً بواسطة {$user->provider_type}. يرجى تسجيل الدخول بنفس الطريقة.");
                }

                // Update data for the valid pharmacy user
                $user->update([
                    'provider_id'   => $googleUser->getId(),
                    'provider_type' => 'google',
                    'avatar'        => $googleUser->getAvatar(),
                ]);
            } else {
                // Create a completely new pharmacy user
                $user = User::create([
                    'name'              => $googleUser->getName(),
                    'email'             => $googleUser->getEmail(),
                    'avatar'            => $googleUser->getAvatar(),
                    'provider_id'       => $googleUser->getId(),
                    'provider_type'     => 'google',
                    'role'              => 'pharmacy',
                    'password'          => null,
                    'email_verified_at' => now(),
                ]);
            }

            Auth::login($user);

            return redirect()->intended('/pharmacy/pharmacyApplication');
        } catch (\Exception $e) {
            Log::error('Pharmacy Web Google Auth Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect('/login')->with('error', 'فشل تسجيل الدخول بواسطة جوجل، يرجى المحاولة مجدداً.');
        }
    }
    /**
     * Secure Web Logout
     *
     * Logs the user out, invalidates the session to prevent fixation attacks,
     * and dynamically redirects based on their role.
     */
    public function logout(Request $request)
    {
        try {
            // 1. Capture the user's role BEFORE logging them out to determine the redirect path
            $user = Auth::user();
            $redirectPath = '/login'; // Default fallback route

            if ($user) {
                if ($user->role === 'admin') {
                    $redirectPath = '/'; // Redirect admins back to their specific login door
                } elseif ($user->role === 'pharmacy') {
                    $redirectPath = '/'; // Adjust if you have a specific pharmacy login page
                } else {
                    $redirectPath = '/'; // Standard users go to the homepage
                }
            }

            // 2. Perform the logout
            Auth::logout();

            // 3. Completely destroy the session and regenerate the CSRF token (Crucial for security)
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // 4. Redirect the user to the correct door with the success message
            return redirect($redirectPath)->with('success', 'تم تسجيل الخروج بنجاح، ننتظر عودتك قريباً!');
        } catch (\Throwable $e) {
            Log::error('Web Logout Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/')->with('error', 'حدث خطأ أثناء تسجيل الخروج.');
        }
    }
}
