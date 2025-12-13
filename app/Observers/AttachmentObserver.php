<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Attachment;

class AttachmentObserver
{
    public function created(Attachment $attachment): void
    {
        $letterRef = $attachment->letter?->reference_number ?? 'Unknown';
        
        ActivityLog::log(
            action: 'create',
            modelType: 'Attachment',
            modelId: $attachment->id,
            description: "Menambah lampiran '{$attachment->filename}' ke surat #{$letterRef}",
            newValues: [
                'filename' => $attachment->filename,
                'extension' => $attachment->extension,
                'file_size' => $attachment->file_size,
            ]
        );
    }

    public function deleted(Attachment $attachment): void
    {
        $letterRef = $attachment->letter?->reference_number ?? 'Unknown';
        
        ActivityLog::log(
            action: 'delete',
            modelType: 'Attachment',
            modelId: $attachment->id,
            description: "Menghapus lampiran '{$attachment->filename}' dari surat #{$letterRef}",
            oldValues: [
                'filename' => $attachment->filename,
                'extension' => $attachment->extension,
                'file_size' => $attachment->file_size,
            ]
        );
    }
}
