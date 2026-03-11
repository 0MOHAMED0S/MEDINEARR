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
        // 1. Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 2. Check if the user's role is in the allowed list
        $userRole = auth()->user()->role;

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // 3. If not authorized, throw 403 or redirect
        abort(403, 'Unauthorized action. This area is restricted to ' . implode(' or ', $roles));
    }
}
