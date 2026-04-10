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
                // منع الدخول إذا كان البريد مسجلاً مسبقاً بطريقة أخرى (مثل فيسبوك أو آبل)
                if ($user->provider_type !== null && $user->provider_type !== 'google') {
                    return redirect('/login')->with('error', "هذا البريد مسجل مسبقاً بواسطة {$user->provider_type}. يرجى تسجيل الدخول بنفس الطريقة.");
                }

                // تحديث بيانات المستخدم (في حال تغيرت صورة حسابه على جوجل مثلاً)
                $user->update([
                    'provider_id'   => $googleUser->getId(),
                    'provider_type' => 'google',
                    'avatar'        => $googleUser->getAvatar(),
                ]);
            } else {
                // ✅ إنشاء حساب جديد بصلاحية "مستخدم عادي" بشكل افتراضي
                $user = User::create([
                    'name'              => $googleUser->getName(),
                    'email'             => $googleUser->getEmail(),
                    'avatar'            => $googleUser->getAvatar(),
                    'provider_id'       => $googleUser->getId(),
                    'provider_type'     => 'google',
                    'role'              => 'user', // الصلاحية الافتراضية
                    'password'          => null,
                    'email_verified_at' => now(),
                ]);
            }

            // تسجيل الدخول
            Auth::login($user);

            // ✨ التوجيه الذكي (Smart Redirect) بناءً على صلاحية المستخدم الحالية ✨
            if ($user->role === 'pharmacy') {
                return redirect()->intended('/pharmacy/dashboard')->with('success', 'تم تسجيل الدخول بنجاح. أهلاً بك في لوحة تحكم الصيدلية.');
            }

            // التوجيه للمستخدم العادي (يمكنك تغيير المسار '/' إلى مسار الصفحة الرئيسية أو لوحة المستخدم الخاصة بك)
            return redirect()->intended('/')->with('success', 'تم تسجيل الدخول بنجاح.');
        } catch (\Exception $e) {
            Log::error('Web Google Auth Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect('/login')->with('error', 'فشل تسجيل الدخول بواسطة جوجل، يرجى المحاولة مجدداً.');
        }
    }
    /**
     * Secure Web Logout
     *
     * Logs the user out, invalidates the session to prevent fixation attacks,
     * and dynamically redirects to the home page.
     */
    public function logout(Request $request)
    {
        try {
            // تسجيل الخروج
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/')->with('success', 'تم تسجيل الخروج بنجاح، ننتظر عودتك قريباً!');
        } catch (\Throwable $e) {
            Log::error('Web Logout Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/')->with('error', 'حدث خطأ أثناء تسجيل الخروج.');
        }
    }
}
