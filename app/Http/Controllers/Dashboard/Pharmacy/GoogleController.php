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
            $user = User::where(function ($query) use ($googleUser) {
                $query->where('provider_id', $googleUser->getId())
                    ->where('provider_type', 'google');
            })->orWhere('email', $googleUser->getEmail())->first();

            if ($user) {
                $user->update([
                    'provider_id'   => $googleUser->getId(),
                    'provider_type' => 'google',
                    'avatar'        => $googleUser->getAvatar(),
                ]);
            } else {
                $user = User::create([
                    'name'          => $googleUser->getName(),
                    'email'         => $googleUser->getEmail(),
                    'avatar'        => $googleUser->getAvatar(),
                    'provider_id'   => $googleUser->getId(),
                    'provider_type' => 'google',
                    'role'          => 'pharmacy',
                    'password'      => null,
                    'email_verified_at' => now(),
                ]);
            }

            Auth::login($user);

            return redirect()->intended('/pharmacy/pharmacyApplication');
        } catch (\Exception $e) {
            Log::error('Google Auth Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'فشل تسجيل الدخول بواسطة جوجل، حاول مجدداً.');
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'تم تسجيل الخروج بنجاح، ننتظر عودتك قريباً!');
    }
}
