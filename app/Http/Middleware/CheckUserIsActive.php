<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'عذراً، تم إيقاف حسابك مؤقتاً. يرجى التواصل مع الإدارة.'
                ], 403);
            }
            return redirect('/')->with('error', 'عذراً، تم إيقاف حسابك مؤقتاً. يرجى التواصل مع الدعم الفني.');
        }
        return $next($request);
    }
}
