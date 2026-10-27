<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MustChangePassword
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role !== 'murid' && Auth::user()->must_change_password) {
            $exemptRoutes = [
                'panel.password.change',
                'panel.password.update',
                'logout',
            ];

            if (!in_array($request->route()?->getName(), $exemptRoutes)) {
                return redirect()->route('panel.password.change')
                    ->with('warning', 'Demi keamanan, Anda wajib mengganti password default sebelum melanjutkan.');
            }
        }

        return $next($request);
    }
}
