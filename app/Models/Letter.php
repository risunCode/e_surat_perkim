<?php

namespace App\Models;

use App\Enums\LetterType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Letter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference_number',
        'agenda_number',
        'from',
        'to',
        'letter_date',
        'received_date',
        'description',
        'note',
        'type',
        'is_completed',
        'completed_at',
        'reference_to',
        'classification_code',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'letter_date' => 'date',
            'received_date' => 'date',
            'is_completed' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classification(): BelongsTo
    {
        return $this->belongsTo(Classification::class, 'classification_code', 'code');
    }

    public function dispositions(): HasMany
    {
        return $this->hasMany(Disposition::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * Digital signatures for this letter
     */
    public function signatures(): HasMany
    {
        return $this->hasMany(DocumentSignature::class);
    }

    /**
     * Get the latest valid signature
     */
    public function latestSignature()
    {
        return $this->hasOne(DocumentSignature::class)
                    ->where('is_valid', true)
                    ->latest();
    }

    /**
     * Surat yang menjadi referensi (surat yang dibalas)
     */
    public function referenceTo(): BelongsTo
    {
        return $this->belongsTo(Letter::class, 'reference_to');
    }

    /**
     * Surat-surat yang membalas surat ini
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Letter::class, 'reference_to');
    }

    /**
     * Get full correspondence chain (all related letters)
     */
    public function getCorrespondenceChain(): \Illuminate\Support\Collection
    {
        $chain = collect();
        
        // Get the root letter (original incoming letter)
        $root = $this;
        while ($root->referenceTo) {
            $root = $root->referenceTo;
        }
        
        // Build chain from root
        $this->buildChain($root, $chain);
        
        return $chain;
    }

    private function buildChain($letter, &$chain): void
    {
        $chain->push($letter);
        foreach ($letter->replies()->with(['user', 'classification'])->get() as $reply) {
            $this->buildChain($reply, $chain);
        }
    }

    public function scopeIncoming($query)
    {
        return $query->where('type', LetterType::INCOMING->value);
    }

    public function scopeOutgoing($query)
    {
        return $query->where('type', LetterType::OUTGOING->value);
    }

    public function isIncoming(): bool
    {
        return $this->type === LetterType::INCOMING->value;
    }

    public function isOutgoing(): bool
    {
        return $this->type === LetterType::OUTGOING->value;
    }

    /**
     * Check if this letter has been replied
     */
    public function hasReply(): bool
    {
        return $this->replies()->exists();
    }

    /**
     * Check if letter needs reply (has pending dispositions)
     */
    public function needsReply(): bool
    {
        $completedStatusId = $this->getCompletedStatusId();
        
        return $this->isIncoming() && 
               $completedStatusId &&
               $this->dispositions()->where('letter_status', '!=', $completedStatusId)->exists() &&
               !$this->hasReply();
    }

    /**
     * Get completed status ID with caching
     */
    protected function getCompletedStatusId(): ?int
    {
        return Cache::remember('letter_status_completed_id', 3600, function () {
            return LetterStatus::getCompletedStatusId();
        });
    }

    /**
     * Mark letter as completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        // Auto-complete related dispositions if this is a reply
        if ($this->reference_to) {
            $completedStatusId = $this->getCompletedStatusId();
            
            if ($completedStatusId) {
                $originalLetter = Letter::find($this->reference_to);
                if ($originalLetter && $originalLetter->isIncoming()) {
                    $originalLetter->dispositions()
                        ->where('letter_status', '!=', $completedStatusId)
                        ->update(['letter_status' => $completedStatusId]);
                }
            }
        }
    }

    /**
     * Mark letter as incomplete
     */
    public function markAsIncomplete(): void
    {
        $this->update([
            'is_completed' => false,
            'completed_at' => null,
        ]);
    }
}
