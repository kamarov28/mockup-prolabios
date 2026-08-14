<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GzipCompress
{
    /**
     * Handle an incoming request and compress text/HTML/JSON responses if client supports gzip.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Skip if client doesn't support gzip or response is binary/streamed
        if (! function_exists('gzencode') || ! $request->hasHeader('Accept-Encoding') || ! str_contains($request->header('Accept-Encoding'), 'gzip')) {
            return $response;
        }

        // Only compress text, html, json, xml responses
        $contentType = $response->headers->get('Content-Type', '');
        $compressible = empty($contentType) || str_contains($contentType, 'text/') || str_contains($contentType, 'application/json') || str_contains($contentType, 'application/xml');

        if (! $compressible || $response->headers->has('Content-Encoding')) {
            return $response;
        }

        $content = $response->getContent();
        if ($content && strlen($content) > 1024) { // Only compress payloads > 1KB
            $compressed = gzencode($content, 6);
            if ($compressed !== false) {
                $response->setContent($compressed);
                $response->headers->set('Content-Encoding', 'gzip');
                $response->headers->set('Content-Length', (string) strlen($compressed));
                $response->headers->set('Vary', 'Accept-Encoding');
            }
        }

        return $response;
    }
}
