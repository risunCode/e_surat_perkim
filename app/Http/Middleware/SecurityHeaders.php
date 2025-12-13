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
        
        // Content Security Policy - Protect against XSS
        // DISABLED by default - Only enabled if CSP_ENABLED=true in .env
        // 
        // WARNING: CSP may break some features if not configured properly.
        // Test thoroughly before enabling in production.
        // 
        // EXCEPTION: /test-deface is excluded (intentionally vulnerable for pentest demo)
        $isTestDeface = $request->is('test-deface') || $request->is('test-deface/*');
        
        if (!$isTestDeface && (env('CSP_ENABLED') === true || env('CSP_ENABLED') === 'true')) {
            // Build dynamic CSP based on environment
            $appUrl = config('app.url', 'http://localhost:8000');
            $viteUrl = env('VITE_DEV_SERVER_URL', 'http://localhost:5173');
            
            $csp = "default-src 'self'; " .
                   // Scripts: self, inline (for Blade), eval (for some libs), CDNs, Vite HMR
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net unpkg.com {$viteUrl}; " .
                   // Styles: self, inline (for dynamic styles), CDNs, Google Fonts
                   "style-src 'self' 'unsafe-inline' cdn.jsdelivr.net unpkg.com fonts.googleapis.com {$viteUrl}; " .
                   // Fonts: self, Google Fonts, unpkg (boxicons)
                   "font-src 'self' data: fonts.gstatic.com unpkg.com; " .
                   // Images: self, data URIs, blobs (for previews)
                   "img-src 'self' data: blob:; " .
                   // Media: self
                   "media-src 'self'; " .
                   // Connect: self, Vite HMR websocket
                   "connect-src 'self' ws: wss: {$viteUrl}; " .
                   // Frame ancestors: self only (prevent clickjacking)
                   "frame-ancestors 'self'; " .
                   // Form action: self only
                   "form-action 'self'; " .
                   // Base URI: self only
                   "base-uri 'self'";
            
            $response->headers->set('Content-Security-Policy', $csp);
        }

        return $response;
    }
}
