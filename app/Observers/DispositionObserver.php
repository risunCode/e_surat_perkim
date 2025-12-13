<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Disposition;

class DispositionObserver
{
    public function created(Disposition $disposition): void
    {
        $letterRef = $disposition->letter?->reference_number ?? 'Unknown';
        
        ActivityLog::log(
            action: 'create',
            modelType: 'Disposition',
            modelId: $disposition->id,
            description: "Membuat disposisi untuk surat #{$letterRef}",
            newValues: $this->getDispositionData($disposition)
        );
    }

    public function updated(Disposition $disposition): void
    {
        $letterRef = $disposition->letter?->reference_number ?? 'Unknown';
        
        ActivityLog::log(
            action: 'update',
            modelType: 'Disposition',
            modelId: $disposition->id,
            description: "Mengedit disposisi untuk surat #{$letterRef}",
            oldValues: $disposition->getOriginal(),
            newValues: $this->getDispositionData($disposition)
        );
    }

    public function deleted(Disposition $disposition): void
    {
        $letterRef = $disposition->letter?->reference_number ?? 'Unknown';
        
        ActivityLog::log(
            action: 'delete',
            modelType: 'Disposition',
            modelId: $disposition->id,
            description: "Menghapus disposisi untuk surat #{$letterRef}",
            oldValues: $this->getDispositionData($disposition)
        );
    }

    private function getDispositionData(Disposition $disposition): array
    {
        return [
            'to' => $disposition->to,
            'content' => $disposition->content,
            'due_date' => $disposition->due_date?->format('Y-m-d'),
            'letter_status' => $disposition->status?->name ?? $disposition->letter_status,
        ];
    }
}
