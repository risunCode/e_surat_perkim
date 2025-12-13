<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttackAlert extends Model
{
    protected $fillable = [
        'attack_type',
        'severity',
        'ip_address',
        'user_agent',
        'fingerprint',
        'target_url',
        'target_field',
        'payload',
        'details',
        'user_id',
        'is_dismissed',
        'dismissed_by',
        'dismissed_at',
    ];

    protected function casts(): array
    {
        return [
            'is_dismissed' => 'boolean',
            'dismissed_at' => 'datetime',
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dismissedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dismissed_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_dismissed', false);
    }

    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    // Helper methods
    public function dismiss(int $userId): void
    {
        $this->update([
            'is_dismissed' => true,
            'dismissed_by' => $userId,
            'dismissed_at' => now(),
        ]);
    }

    public function getSeverityColorAttribute(): string
    {
        return match ($this->severity) {
            'critical' => 'red',
            'high' => 'orange',
            'medium' => 'yellow',
            'low' => 'blue',
            default => 'gray',
        };
    }

    public function getAttackIconAttribute(): string
    {
        return match ($this->attack_type) {
            'xss' => 'bx-code-alt',
            'sqli' => 'bx-data',
            'bruteforce' => 'bx-key',
            'idor' => 'bx-lock-open',
            'ratelimit' => 'bx-timer',
            'injection' => 'bx-bug',
            default => 'bx-shield-x',
        };
    }

    // Static method to log attack
    public static function logAttack(
        string $type,
        string $severity,
        string $ip,
        string $url,
        ?string $userAgent = null,
        ?string $field = null,
        ?string $payload = null,
        ?string $details = null,
        ?int $userId = null,
        ?string $fingerprint = null
    ): self {
        return self::create([
            'attack_type' => $type,
            'severity' => $severity,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'fingerprint' => $fingerprint,
            'target_url' => $url,
            'target_field' => $field,
            'payload' => $payload ? substr($payload, 0, 1000) : null,
            'details' => $details,
            'user_id' => $userId,
        ]);
    }
}
