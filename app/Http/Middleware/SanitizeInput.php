<?php

namespace App\Http\Middleware;

use App\Models\AttackAlert;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    /**
     * Dangerous patterns with their attack types and severity.
     */
    protected array $dangerousPatterns = [
        // XSS patterns
        ['pattern' => '/<script[^>]*>.*?<\/script>/is', 'type' => 'xss', 'severity' => 'high'],
        ['pattern' => '/javascript\s*:/i', 'type' => 'xss', 'severity' => 'high'],
        ['pattern' => '/vbscript\s*:/i', 'type' => 'xss', 'severity' => 'high'],
        ['pattern' => '/on(click|load|error|mouse|focus|blur)\s*=/i', 'type' => 'xss', 'severity' => 'high'],
        ['pattern' => '/<iframe[^>]*>/i', 'type' => 'xss', 'severity' => 'medium'],
        ['pattern' => '/<object[^>]*>/i', 'type' => 'xss', 'severity' => 'medium'],
        ['pattern' => '/<embed[^>]*>/i', 'type' => 'xss', 'severity' => 'medium'],
        ['pattern' => '/<svg[^>]*onload/i', 'type' => 'xss', 'severity' => 'high'],
        ['pattern' => '/<img[^>]*onerror/i', 'type' => 'xss', 'severity' => 'high'],
        
        // SQL Injection patterns
        ['pattern' => '/(union\s+select|union\s+all\s+select)/i', 'type' => 'sqli', 'severity' => 'critical'],
        ['pattern' => '/(drop\s+table|drop\s+database)/i', 'type' => 'sqli', 'severity' => 'critical'],
        ['pattern' => '/(insert\s+into|delete\s+from)/i', 'type' => 'sqli', 'severity' => 'critical'],
        ['pattern' => '/(\'\s*or\s+\'?\d+\'?\s*=\s*\'?\d+)/i', 'type' => 'sqli', 'severity' => 'high'],
        ['pattern' => '/(;\s*drop\s|;\s*delete\s|;\s*update\s)/i', 'type' => 'sqli', 'severity' => 'critical'],
        ['pattern' => '/(\/\*.*\*\/|--\s)/i', 'type' => 'sqli', 'severity' => 'medium'],
        
        // Command Injection
        ['pattern' => '/(;\s*(ls|cat|rm|wget|curl|bash|sh|nc|netcat)\s)/i', 'type' => 'injection', 'severity' => 'critical'],
        ['pattern' => '/(\|\s*(ls|cat|rm|wget|curl|bash|sh)\s)/i', 'type' => 'injection', 'severity' => 'critical'],
        ['pattern' => '/(`[^`]+`)/i', 'type' => 'injection', 'severity' => 'high'],
        
        // Path Traversal
        ['pattern' => '/(\.\.\/|\.\.\\\\){2,}/i', 'type' => 'traversal', 'severity' => 'high'],
        ['pattern' => '/(\/etc\/passwd|\/etc\/shadow)/i', 'type' => 'traversal', 'severity' => 'critical'],
    ];

    /**
     * Fields to skip sanitization.
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
        $input = $request->except($this->skipFields);
        
        foreach ($input as $key => $value) {
            if (is_string($value)) {
                $detected = $this->detectAttack($value);
                
                if ($detected) {
                    // Log to attack_alerts table
                    AttackAlert::logAttack(
                        type: $detected['type'],
                        severity: $detected['severity'],
                        ip: $request->ip(),
                        url: $request->fullUrl(),
                        userAgent: $request->userAgent(),
                        field: $key,
                        payload: $value,
                        details: "Pattern matched: {$detected['pattern']}",
                        userId: auth()->id(),
                        fingerprint: $request->header('X-Fingerprint')
                    );

                    // Also log to Laravel log
                    \Log::warning('Attack detected and logged', [
                        'type' => $detected['type'],
                        'severity' => $detected['severity'],
                        'ip' => $request->ip(),
                        'field' => $key,
                        'url' => $request->fullUrl(),
                    ]);

                    if ($request->expectsJson()) {
                        return response()->json([
                            'message' => 'Input tidak valid terdeteksi.',
                        ], 400);
                    }

                    return back()->withErrors(['error' => 'Input tidak valid terdeteksi.'])->withInput();
                }
            }
        }

        return $next($request);
    }

    /**
     * Detect attack pattern and return details.
     */
    protected function detectAttack(string $value): ?array
    {
        foreach ($this->dangerousPatterns as $item) {
            if (preg_match($item['pattern'], $value)) {
                return [
                    'type' => $item['type'],
                    'severity' => $item['severity'],
                    'pattern' => $item['pattern'],
                ];
            }
        }
        return null;
    }
}
