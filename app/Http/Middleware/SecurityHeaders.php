<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        // Generate cryptographic nonce per request
        $nonce = base64_encode(random_bytes(16));
        app()->instance('csp-nonce', $nonce);
        View::share('cspNonce', $nonce);

        // Tell Vite to automatically inject nonce into its generated script/style tags
        if (class_exists(Vite::class)) {
            Vite::useCspNonce($nonce);
        }

        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');

        // Content Security Policy (Strict Nonce-based against XSS & data injection)
        $csp = "default-src 'self'; "
            ."script-src 'self' 'nonce-{$nonce}' https://cdn.jsdelivr.net https://code.jquery.com https://www.google.com https://www.gstatic.com https://www.googletagmanager.com; "
            ."style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com; "
            ."img-src 'self' data: blob: https:; "
            ."font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net; "
            ."frame-src 'self' https://www.google.com https://maps.google.com https://www.googletagmanager.com; "
            ."connect-src 'self' https: https://www.google.com https://www.google-analytics.com https://analytics.google.com https://www.googletagmanager.com; "
            ."frame-ancestors 'self'; "
            ."base-uri 'self'; "
            ."form-action 'self';";

        $response->headers->set('Content-Security-Policy', $csp);

        if ($request->isSecure() || $request->header('X-Forwarded-Proto') === 'https') {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
