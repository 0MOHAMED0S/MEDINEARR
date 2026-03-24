<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Pharmacy; // تأكد من استدعاء المودل

class CheckApprovedPharmacy
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $hasApprovedPharmacy = Pharmacy::where('user_id', $user->id)->exists();
        if (!$hasApprovedPharmacy) {
            return redirect()->route('pharmacy.application.index')
                ->with('error', 'عذراً، يجب أن يتم الموافقة على طلب انضمام صيدليتك أولاً لتتمكن من الوصول إلى لوحة التحكم.');
        }
        return $next($request);
    }
}
