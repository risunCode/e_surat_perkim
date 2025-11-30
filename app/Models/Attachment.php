<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'filename',
        'extension',
        'file_size',
        'mime_type',
        'letter_id',
        'user_id',
    ];

    public function letter(): BelongsTo
    {
        return $this->belongsTo(Letter::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullPathAttribute(): string
    {
        return $this->path . '/' . $this->filename;
    }

    public function getFormattedSizeAttribute(): string
    {
        if (!$this->file_size) return 'Unknown';
        
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }

    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    /**
     * Get file icon class based on extension
     */
    public function getFileIcon(): string
    {
        if ($this->isImage()) {
            return 'bx bx-image';
        }
        
        return match(strtolower($this->extension)) {
            'pdf' => 'bx bx-file-pdf',
            'doc', 'docx' => 'bx bx-file-doc',
            'ppt', 'pptx' => 'bx bx-file-ppt',
            'txt' => 'bx bx-file-txt',
            default => 'bx bx-file'
        };
    }

    /**
     * Get file color class based on extension
     */
    public function getFileColor(): string
    {
        if ($this->isImage()) {
            return 'bg-green-100 text-green-600';
        }
        
        return match(strtolower($this->extension)) {
            'pdf' => 'bg-red-100 text-red-600',
            'doc', 'docx' => 'bg-blue-100 text-blue-600',
            'ppt', 'pptx' => 'bg-orange-100 text-orange-600',
            'txt' => 'bg-gray-100 text-gray-600',
            default => 'bg-gray-100 text-gray-600'
        };
    }

    /**
     * Get thumbnail URL for images
     */
    public function getThumbnailUrl(): string
    {
        if (!$this->isImage()) {
            return '';
        }
        
        return \Storage::url($this->full_path);
    }
}
