<?php

namespace App\Http\Middleware;

use Closure;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // app/Http/Middleware/CheckApiKey.php

public function handle($request, Closure $next)
{
    if ($request->header('x-api-key') !== 'M505550M') {
        return response()->json([
            'status' => false,
            'message' => 'Unauthorized'
        ], 401);
    }

    return $next($request);
}
}
