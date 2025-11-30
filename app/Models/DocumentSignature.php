<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentSignature extends Model
{
    use HasFactory;

    protected $fillable = [
        'letter_id',
        'user_id',
        'document_type',
        'signature_hash',
        'content_hash',
        'signed_at',
        'ip_address',
        'user_agent',
        'metadata',
        'is_valid'
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'metadata' => 'array',
        'is_valid' => 'boolean'
    ];

    /**
     * Generate signature hash for document
     */
    public static function generateSignature(string $content, int $letterId, int $userId, string $documentType): string
    {
        $timestamp = now()->timestamp;
        $signatureKey = config('app.signature_key');
        
        // Ensure signature key exists
        if (!$signatureKey) {
            throw new \Exception('SIGNATURE_KEY not configured. Please set SIGNATURE_KEY in your .env file.');
        }
        
        // Create signature from content + metadata + secret
        $signatureData = $content . $letterId . $userId . $documentType . $timestamp . $signatureKey;
        
        return hash('sha256', $signatureData);
    }

    /**
     * Generate content hash
     */
    public static function generateContentHash(string $content): string
    {
        return hash('sha256', $content);
    }

    /**
     * Verify document signature
     */
    public static function verifySignature(string $signatureHash): ?self
    {
        return self::where('signature_hash', $signatureHash)
                   ->where('is_valid', true)
                   ->first();
    }

    /**
     * Get verification URL using APP_URL
     */
    public function getVerificationUrlAttribute(): string
    {
        return config('app.url') . '/verify/' . $this->signature_hash;
    }

    /**
     * Get machine IP address (cross-platform: Windows, Linux, macOS)
     */
    private function getMachineIp(): string
    {
        // Method 1: Try to get from $_SERVER first (most reliable for web requests)
        if (!empty($_SERVER['SERVER_ADDR']) && filter_var($_SERVER['SERVER_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return $_SERVER['SERVER_ADDR'];
        }
        
        // Method 2: Platform-specific IP detection
        try {
            $osFamily = PHP_OS_FAMILY;
            $output = null;
            
            if ($osFamily === 'Windows') {
                // Windows: ipconfig
                $output = shell_exec('ipconfig');
                if ($output) {
                    // Look for Wi-Fi adapter first
                    if (preg_match('/Wireless LAN adapter Wi-Fi:.*?IPv4 Address[.\s]*:\s*([0-9.]+)/s', $output, $matches)) {
                        if ($this->isValidLanIp($matches[1])) {
                            return $matches[1];
                        }
                    }
                    // Fallback: any valid IPv4 address
                    if (preg_match_all('/IPv4 Address[.\s]*:\s*([0-9.]+)/', $output, $matches)) {
                        foreach ($matches[1] as $ip) {
                            if ($this->isValidLanIp($ip)) {
                                return $ip;
                            }
                        }
                    }
                }
            } elseif ($osFamily === 'Linux') {
                // Linux: hostname -I or ip addr
                $output = shell_exec('hostname -I 2>/dev/null');
                if ($output) {
                    $ips = explode(' ', trim($output));
                    foreach ($ips as $ip) {
                        if ($this->isValidLanIp($ip)) {
                            return $ip;
                        }
                    }
                }
            } elseif ($osFamily === 'Darwin') {
                // macOS: ipconfig getifaddr en0 (Wi-Fi) or en1 (Ethernet)
                $output = shell_exec('ipconfig getifaddr en0 2>/dev/null');
                if ($output && $this->isValidLanIp(trim($output))) {
                    return trim($output);
                }
                $output = shell_exec('ipconfig getifaddr en1 2>/dev/null');
                if ($output && $this->isValidLanIp(trim($output))) {
                    return trim($output);
                }
            }
        } catch (\Exception $e) {
            // Continue to fallback
        }
        
        // Method 3: Fallback to hostname resolution
        try {
            $resolved = gethostbyname(gethostname());
            if ($this->isValidLanIp($resolved)) {
                return $resolved;
            }
        } catch (\Exception $e) {
            // Keep default
        }
        
        return '127.0.0.1';
    }

    /**
     * Check if IP is a valid LAN IP (not localhost or APIPA)
     */
    private function isValidLanIp(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) &&
               $ip !== '127.0.0.1' &&
               !str_starts_with($ip, '169.254.'); // Skip APIPA addresses
    }

    /**
     * Relationships
     */
    public function letter(): BelongsTo
    {
        return $this->belongsTo(Letter::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
