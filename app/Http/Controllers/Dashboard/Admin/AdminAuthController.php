<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Exception;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('dashboard.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string'],
        ], [
            'email.exists' => 'هذا البريد الإلكتروني غير مسجل لدينا.',
            'email.required' => 'يرجى إدخال البريد الإلكتروني.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
        ]);

        try {
            $remember = $request->boolean('remember');

            if (Auth::attempt($credentials, $remember)) {
                if (Auth::user()->role !== 'admin') {
                    Auth::logout();
                    return redirect()->back()
                        ->withInput($request->only('email'))
                        ->with('error', 'عذراً، لا تملك صلاحيات كافية للوصول لوحة التحكم.');
                }
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'مرحباً بك مجدداً في لوحة الإدارة.');
            }
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'كلمة المرور غير صحيحة، حاول مرة أخرى.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'حدث خطأ فني غير متوقع، يرجى المحاولة لاحقاً.');
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')
            ->with('success', 'تم تسجيل الخروج بنجاح.');
    }
}
