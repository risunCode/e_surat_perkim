<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    /**
     * Dangerous patterns that might indicate SQL injection or XSS.
     * Reduced false positives, focused on actual threats.
     */
    protected array $dangerousPatterns = [
        '/<script[^>]*>.*?<\/script>/is', // Script tags
        '/javascript\s*:/i', // JavaScript protocol
        '/vbscript\s*:/i', // VBScript protocol  
        '/on(click|load|error|mouse)\s*=/i', // Common event handlers
        '/(union\s+select|drop\s+table|insert\s+into)/i', // SQL injection
        '/(\-\-\s|\/\*|\*\/)/i', // SQL comments
        '/<iframe[^>]*>/i', // Iframe tags
        '/<object[^>]*>/i', // Object tags
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
