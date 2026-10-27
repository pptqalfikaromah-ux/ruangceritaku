<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Cek status aktif
        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan oleh Administrator.');
        }

        // Dukung role dipisahkan koma atau parameter terpisah (misal: 'admin,guru_bk' atau 'admin', 'guru_bk')
        $allowedRoles = [];
        foreach ($roles as $role) {
            $allowedRoles = array_merge($allowedRoles, explode(',', $role));
        }

        if (!in_array($user->role, $allowedRoles)) {
            // Murid mencoba masuk ke area khusus Admin / Guru BK -> alihkan ke portal murid
            if ($user->role === 'murid' && !in_array('murid', $allowedRoles)) {
                return redirect()->route('murid.dashboard')
                    ->with('warning', 'Halaman tersebut khusus Administrator & Guru BK. Anda dialihkan ke Portal Murid.');
            }

            // Guru BK / Admin mencoba masuk ke portal murid -> alihkan ke panel dashboard
            if (in_array('murid', $allowedRoles) && in_array($user->role, ['admin', 'guru_bk'])) {
                return redirect()->route('panel.dashboard')
                    ->with('warning', 'Halaman tersebut khusus Murid. Anda dialihkan ke Panel Utama.');
            }

            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk membuka halaman ini.');
        }

        return $next($request);
    }
}
