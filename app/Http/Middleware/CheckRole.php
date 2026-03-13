<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. التحقق من تسجيل الدخول
        if (!auth()->check()) {
            return redirect()->route('google.login');
        }

        // 2. التحقق مما إذا كان دور المستخدم ضمن الأدوار المسموح بها
        $userRole = auth()->user()->role;

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // 3. إذا لم يكن لديه صلاحية: التوجيه للرئيسية مع رسالة خطأ
        // نستخدم back() للعودة لصفحته السابقة أو route('home') للرئيسية
        return redirect('/')
            ->with('error', 'عذراً، لا تملك الصلاحية الكافية للدخول إلى هذه الصفحة.');
    }
}
