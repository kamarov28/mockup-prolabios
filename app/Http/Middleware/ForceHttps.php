<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Redirect HTTP requests to HTTPS in production.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only enforce in production environment
        if (! app()->environment('production')) {
            return $next($request);
        }

        // Check if request is already HTTPS
        // Also check X-Forwarded-Proto for proxies/load balancers (Cloudflare, etc.)
        if (! $request->secure() && $request->header('X-Forwarded-Proto') !== 'https') {
            $uri = '/'.ltrim($request->getRequestUri(), '/\\');

            return redirect()->secure($uri);
        }

        return $next($request);
    }
}
