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
        // Check if IP is blocked
        $ip = $request->ip();
        if (\App\Models\BlockedIp::isBlocked($ip)) {
            \Log::warning('Blocked IP attempted access', ['ip' => $ip, 'url' => $request->fullUrl()]);
            
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Access denied.'], 403);
            }
            
            abort(403, 'Your IP address has been blocked due to suspicious activity.');
        }
        
        $input = $request->except($this->skipFields);
        
        foreach ($input as $key => $value) {
            if (is_string($value)) {
                $detected = $this->detectAttack($value);
                
                if ($detected) {
                    // Throttle: Only log if no similar attack from same IP in last 5 minutes
                    $recentAttack = AttackAlert::where('ip_address', $request->ip())
                        ->where('attack_type', $detected['type'])
                        ->where('created_at', '>', now()->subMinutes(5))
                        ->exists();
                    
                    if (!$recentAttack) {
                        // Cleanup: Delete alerts older than 7 days (keep DB small)
                        AttackAlert::where('created_at', '<', now()->subDays(7))
                            ->where('status', AttackAlert::STATUS_ARCHIVED)
                            ->delete();
                        
                        // Log to attack_alerts table with auto-mitigated status
                        AttackAlert::create([
                            'attack_type' => $detected['type'],
                            'severity' => $detected['severity'],
                            'ip_address' => $request->ip(),
                            'user_agent' => $request->userAgent(),
                            'fingerprint' => $request->header('X-Fingerprint'),
                            'target_url' => $request->fullUrl(),
                            'target_field' => $key,
                            'payload' => substr($value, 0, 500), // Reduced payload size
                            'details' => "Pattern matched",
                            'user_id' => auth()->id(),
                            'status' => AttackAlert::STATUS_MITIGATED,
                            'mitigation_notes' => 'Auto-blocked by middleware',
                            'mitigated_at' => now(),
                        ]);

                        \Log::warning('Attack detected', [
                            'type' => $detected['type'],
                            'ip' => $request->ip(),
                        ]);
                    }

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
