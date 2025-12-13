<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockedIp extends Model
{
    protected $fillable = [
        'ip_address',
        'reason',
        'blocked_by',
        'blocked_until',
        'is_permanent',
        'attack_alert_id',
    ];

    protected function casts(): array
    {
        return [
            'blocked_until' => 'datetime',
            'is_permanent' => 'boolean',
        ];
    }

    public function blockedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }

    public function attackAlert(): BelongsTo
    {
        return $this->belongsTo(AttackAlert::class);
    }

    // Check if IP is currently blocked
    public static function isBlocked(string $ip): bool
    {
        return self::where('ip_address', $ip)
            ->where(function ($q) {
                $q->where('is_permanent', true)
                  ->orWhere('blocked_until', '>', now());
            })
            ->exists();
    }

    // Get block record for IP
    public static function getBlock(string $ip): ?self
    {
        return self::where('ip_address', $ip)
            ->where(function ($q) {
                $q->where('is_permanent', true)
                  ->orWhere('blocked_until', '>', now());
            })
            ->first();
    }

    // Scope for active blocks
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->where('is_permanent', true)
              ->orWhere('blocked_until', '>', now());
        });
    }
}
