<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttackAlert extends Model
{
    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_MITIGATED = 'mitigated';
    const STATUS_ARCHIVED = 'archived';

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
        'status',
        'mitigation_notes',
        'mitigated_by',
        'mitigated_at',
        'archived_by',
        'archived_at',
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
            'mitigated_at' => 'datetime',
            'archived_at' => 'datetime',
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

    public function mitigatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mitigated_by');
    }

    public function archivedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeMitigated($query)
    {
        return $query->where('status', self::STATUS_MITIGATED);
    }

    public function scopeArchived($query)
    {
        return $query->where('status', self::STATUS_ARCHIVED);
    }

    public function scopeNotArchived($query)
    {
        return $query->where('status', '!=', self::STATUS_ARCHIVED);
    }

    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    // Status check helpers
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isMitigated(): bool
    {
        return $this->status === self::STATUS_MITIGATED;
    }

    public function isArchived(): bool
    {
        return $this->status === self::STATUS_ARCHIVED;
    }

    // Action methods
    public function markAsMitigated(int $userId, ?string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_MITIGATED,
            'mitigated_by' => $userId,
            'mitigated_at' => now(),
            'mitigation_notes' => $notes,
        ]);
    }

    public function archive(int $userId): void
    {
        $this->update([
            'status' => self::STATUS_ARCHIVED,
            'archived_by' => $userId,
            'archived_at' => now(),
            'is_dismissed' => true,
            'dismissed_by' => $userId,
            'dismissed_at' => now(),
        ]);
    }

    // Legacy dismiss method (now archives)
    public function dismiss(int $userId): void
    {
        $this->archive($userId);
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

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'red',
            self::STATUS_MITIGATED => 'yellow',
            self::STATUS_ARCHIVED => 'green',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_MITIGATED => 'Mitigated',
            self::STATUS_ARCHIVED => 'Archived',
            default => 'Unknown',
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
            'status' => self::STATUS_ACTIVE,
        ]);
    }
}
