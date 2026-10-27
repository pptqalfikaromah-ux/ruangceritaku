<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'murid') {
                return redirect()->route('murid.dashboard');
            }
            if (Auth::user()->role === 'wali_kelas') {
                return redirect()->route('walikelas.dashboard');
            }
            return redirect()->route('panel.dashboard');
        }

        $selectedRole = $request->query('role', 'murid');

        return view('auth.login', compact('selectedRole'));
    }

    public function showAdminLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'murid') {
                return redirect()->route('murid.dashboard');
            }
            if (Auth::user()->role === 'wali_kelas') {
                return redirect()->route('walikelas.dashboard');
            }
            return redirect()->route('panel.dashboard');
        }

        $selectedRole = 'bk';
        return view('auth.login', compact('selectedRole'));
    }

    public function showWaliKelasLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'murid') {
                return redirect()->route('murid.dashboard');
            }
            if (Auth::user()->role === 'wali_kelas') {
                return redirect()->route('walikelas.dashboard');
            }
            return redirect()->route('panel.dashboard');
        }

        $selectedRole = 'wali_kelas';
        return view('auth.login', compact('selectedRole'));
    }

    public function showMuridLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'murid') {
                return redirect()->route('murid.dashboard');
            }
            if (Auth::user()->role === 'wali_kelas') {
                return redirect()->route('walikelas.dashboard');
            }
            return redirect()->route('panel.dashboard');
        }

        $selectedRole = 'murid';
        return view('auth.login', compact('selectedRole'));
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $loginField = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [
            $loginField => $request->input('login'),
            'password' => $request->input('password'),
        ];

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withInput($request->only('login'))
                    ->with('error', 'Akun Anda dinonaktifkan. Silakan hubungi Administrator.');
            }

            $request->session()->regenerate();

            // Multi-role redirect
            if ($user->role === 'murid') {
                $request->session()->forget('url.intended');
                return redirect()->route('murid.dashboard')
                    ->with('success', 'Selamat datang di Ruang Ceritaku, ' . $user->name . '!');
            }

            if ($user->role === 'wali_kelas') {
                $request->session()->forget('url.intended');
                return redirect()->route('walikelas.dashboard')
                    ->with('success', 'Selamat datang di Ruang Ceritaku, ' . $user->name . '!');
            }

            if ($user->must_change_password) {
                return redirect()->route('panel.password.change')
                    ->with('warning', 'Selamat datang! Demi keamanan, silakan ganti password default Anda.');
            }

            // Pastikan admin/guru_bk tidak terlempar ke URL murid jika ada sisa session
            $intended = $request->session()->get('url.intended');
            if ($intended && str_contains($intended, '/murid')) {
                $request->session()->forget('url.intended');
            }

            return redirect()->intended(route('panel.dashboard'))
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return back()->withInput($request->only('login', 'remember'))
            ->withErrors(['login' => 'Kombinasi username/email dan password tidak sesuai.']);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing')->with('success', 'Anda telah berhasil keluar.');
    }
}
