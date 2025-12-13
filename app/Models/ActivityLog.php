<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get action badge color
     */
    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'create' => 'green',
            'update' => 'yellow',
            'delete' => 'red',
            'toggle_completion' => 'blue',
            'restore' => 'purple',
            default => 'gray',
        };
    }

    /**
     * Get action icon
     */
    public function getActionIconAttribute(): string
    {
        return match ($this->action) {
            'create' => 'bx-plus-circle',
            'update' => 'bx-edit',
            'delete' => 'bx-trash',
            'toggle_completion' => 'bx-check-circle',
            'restore' => 'bx-revision',
            default => 'bx-info-circle',
        };
    }

    /**
     * Get model type label in Indonesian
     */
    public function getModelTypeLabelAttribute(): string
    {
        return match ($this->model_type) {
            'Letter' => 'Surat',
            'Disposition' => 'Disposisi',
            'Attachment' => 'Lampiran',
            'User' => 'Pengguna',
            'Classification' => 'Klasifikasi',
            'LetterStatus' => 'Status Surat',
            'ReferenceCode' => 'Kode Referensi',
            default => $this->model_type,
        };
    }

    /**
     * Log an activity (with duplicate prevention)
     */
    public static function log(
        string $action,
        string $modelType,
        ?int $modelId,
        string $description,
        ?array $oldValues = null,
        ?array $newValues = null
    ): ?self {
        $user = auth()->user();
        
        // Prevent duplicate logs within 2 seconds
        $recentLog = self::where('action', $action)
            ->where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->where('user_id', $user?->id)
            ->where('created_at', '>=', now()->subSeconds(2))
            ->first();
            
        if ($recentLog) {
            return null; // Skip duplicate
        }

        return self::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'System',
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
