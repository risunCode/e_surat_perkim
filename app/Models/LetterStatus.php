<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LetterStatus extends Model
{
    use HasFactory;

    /**
     * Status name constants for reliable lookups
     */
    public const STATUS_PENDING = 'Pending';
    public const STATUS_DIPROSES = 'Diproses';
    public const STATUS_SELESAI = 'Selesai';
    public const STATUS_DITOLAK = 'Ditolak';
    public const STATUS_URGENT = 'Urgent';

    protected $fillable = [
        'status',
    ];

    public function dispositions(): HasMany
    {
        return $this->hasMany(Disposition::class, 'letter_status');
    }

    /**
     * Get the completed status ID
     */
    public static function getCompletedStatusId(): ?int
    {
        return static::where('status', self::STATUS_SELESAI)->value('id');
    }

    /**
     * Check if this status is the completed status
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_SELESAI;
    }
}
