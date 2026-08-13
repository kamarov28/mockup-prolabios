<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!\Illuminate\Support\Facades\Auth::check() || !\Illuminate\Support\Facades\Auth::user()?->isAdmin()) {
            \Illuminate\Support\Facades\Auth::logout();
            return redirect()->route('admin.login')->with('error', 'Silakan login dengan akun administrator untuk mengakses panel admin.');
        }

        return $next($request);
    }
}
