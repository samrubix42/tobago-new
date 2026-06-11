<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 🔷 1. Secure Referrer-Policy Header
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 🔷 2. X-Content-Type-Options Header (Prevents MIME Sniffing)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // 🔷 3. X-Frame-Options Header (Prevents Clickjacking)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        
        // 🔷 4. HSTS (HTTP Strict Transport Security) Header
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        
        // 🔷 5. Content-Security-Policy Header
        $response->headers->set('Content-Security-Policy', "default-src 'self' https: data: 'unsafe-inline' 'unsafe-eval';");

        return $response;
    }
}
