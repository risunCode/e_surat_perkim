<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Security headers to protect against common attacks.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent XSS attacks
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        
        // Referrer policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Permissions policy
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        // Content Security Policy (disabled for debugging)
        // $csp = "default-src 'self'; " .
        //        "script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net unpkg.com; " .
        //        "style-src 'self' 'unsafe-inline' cdn.jsdelivr.net fonts.googleapis.com; " .
        //        "font-src 'self' fonts.gstatic.com; " .
        //        "img-src 'self' data: blob:; " .
        //        "media-src 'self'; " .
        //        "connect-src 'self'; " .
        //        "frame-ancestors 'self'";
        // $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
