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
                if ($user->role !== 'pharmacy') {
                    return redirect('/')->with('error', 'عفواً، هذا البريد الإلكتروني مسجل كحساب مستخدم عادي أو مدير. الدخول من هنا مخصص للصيدليات فقط.');
                }

                if ($user->provider_type !== null && $user->provider_type !== 'google') {
                    return redirect('/')->with('error', "هذا البريد مسجل مسبقاً بواسطة {$user->provider_type}. يرجى تسجيل الدخول بنفس الطريقة.");
                }

                // تحديث بيانات الصيدلية إذا كان كل شيء سليم
                $user->update([
                    'provider_id'   => $googleUser->getId(),
                    'provider_type' => 'google',
                    'avatar'        => $googleUser->getAvatar(),
                ]);
            } else {
                // ✅ إنشاء حساب جديد بصلاحية "صيدلية" فقط
                $user = User::create([
                    'name'              => $googleUser->getName(),
                    'email'             => $googleUser->getEmail(),
                    'avatar'            => $googleUser->getAvatar(),
                    'provider_id'       => $googleUser->getId(),
                    'provider_type'     => 'google',
                    'role'              => 'pharmacy', // إجبار الصلاحية كصيدلية
                    'password'          => null,
                    'email_verified_at' => now(),
                ]);
            }

            // تسجيل الدخول
            Auth::login($user);

            // التوجيه إلى صفحة التقديم أو لوحة تحكم الصيدلية مع رسالة نجاح
            return redirect()->intended('/pharmacy/pharmacyApplication')->with('success', 'تم تسجيل الدخول بنجاح كصيدلية.');

        } catch (\Exception $e) {
            Log::error('Pharmacy Web Google Auth Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect('/')->with('error', 'فشل تسجيل الدخول بواسطة جوجل، يرجى المحاولة مجدداً.');
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
