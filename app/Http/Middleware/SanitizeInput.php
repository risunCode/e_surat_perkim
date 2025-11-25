<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    /**
     * Dangerous patterns that might indicate SQL injection or XSS.
     */
    protected array $dangerousPatterns = [
        '/(\%27)|(\')|(\-\-)|(\%23)|(#)/i',  // SQL injection basics
        '/((\%3D)|(=))[^\n]*((\%27)|(\')|(\-\-)|(\%3B)|(;))/i', // SQL injection
        '/\w*((\%27)|(\'))((\%6F)|o|(\%4F))((\%72)|r|(\%52))/i', // SQL OR
        '/((\%3C)|<)((\%2F)|\/)*[a-z0-9\%]+((\%3E)|>)/i', // XSS tags
        '/((\%3C)|<)((\%69)|i|(\%49))((\%6D)|m|(\%4D))((\%67)|g|(\%47))/i', // XSS img
        '/javascript\s*:/i', // JavaScript protocol
        '/vbscript\s*:/i', // VBScript protocol
        '/on\w+\s*=/i', // Event handlers
    ];

    /**
     * Fields to skip sanitization (like passwords).
     */
    protected array $skipFields = [
        'password',
        'password_confirmation',
        'current_password',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check for dangerous patterns in input
        $input = $request->except($this->skipFields);
        
        foreach ($input as $key => $value) {
            if (is_string($value) && $this->containsDangerousPattern($value)) {
                // Log the attempt
                \Log::warning('Potential injection attempt detected', [
                    'ip' => $request->ip(),
                    'field' => $key,
                    'value' => substr($value, 0, 100),
                    'url' => $request->fullUrl(),
                    'user_id' => auth()->id(),
                ]);

                return response()->json([
                    'message' => 'Input tidak valid terdeteksi.',
                ], 400);
            }
        }

        return $next($request);
    }

    /**
     * Check if value contains dangerous patterns.
     */
    protected function containsDangerousPattern(string $value): bool
    {
        foreach ($this->dangerousPatterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }
        return false;
    }
}
