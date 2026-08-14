<?php

namespace App\Http\Controllers;

use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // ----------------------------------------------------
    // Admin Authentication Handlers
    // ----------------------------------------------------
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $loginInput = trim($request->input('username', ''));
        $password = $request->input('password', '');

        // Attempt Native Laravel Auth via User model (email or name)
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        if (Auth::attempt([$field => $loginInput, 'password' => $password])) {
            if (! Auth::user()->isAdmin()) {
                AuditLogger::log('admin.login_rejected_non_admin', 'User', Auth::id(), [
                    'username' => $loginInput,
                ]);

                Auth::logout();

                return redirect()->back()->withInput()->with('error', 'Akses ditolak: Akun Anda tidak memiliki hak akses administrator.');
            }
            $request->session()->regenerate();

            AuditLogger::log('admin.login_success', 'User', Auth::id(), [
                'username' => $loginInput,
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, Administrator!');
        }

        AuditLogger::log('admin.login_failed', 'User', null, [
            'attempted_username' => $loginInput,
        ]);

        return redirect()->back()->withInput()->with('error', 'Username atau password yang Anda masukkan salah.');
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();
        AuditLogger::log('admin.logout', 'User', $userId);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil logout.');
    }
}
