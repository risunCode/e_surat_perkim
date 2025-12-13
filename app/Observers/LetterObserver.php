<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Letter;

class LetterObserver
{
    public function created(Letter $letter): void
    {
        $type = $letter->isIncoming() ? 'masuk' : 'keluar';
        
        ActivityLog::log(
            action: 'create',
            modelType: 'Letter',
            modelId: $letter->id,
            description: "Menambah surat {$type} #{$letter->reference_number}",
            newValues: $this->getLetterData($letter)
        );
    }

    public function updated(Letter $letter): void
    {
        $type = $letter->isIncoming() ? 'masuk' : 'keluar';
        $changes = $letter->getChanges();
        
        // Check if only completion status changed
        if (isset($changes['is_completed']) && count($changes) <= 2) {
            $status = $letter->is_completed ? 'selesai' : 'belum selesai';
            ActivityLog::log(
                action: 'toggle_completion',
                modelType: 'Letter',
                modelId: $letter->id,
                description: "Menandai surat {$type} #{$letter->reference_number} sebagai {$status}",
                oldValues: ['is_completed' => !$letter->is_completed],
                newValues: ['is_completed' => $letter->is_completed]
            );
            return;
        }

        ActivityLog::log(
            action: 'update',
            modelType: 'Letter',
            modelId: $letter->id,
            description: "Mengedit surat {$type} #{$letter->reference_number}",
            oldValues: $letter->getOriginal(),
            newValues: $this->getLetterData($letter)
        );
    }

    public function deleted(Letter $letter): void
    {
        $type = $letter->isIncoming() ? 'masuk' : 'keluar';
        
        ActivityLog::log(
            action: 'delete',
            modelType: 'Letter',
            modelId: $letter->id,
            description: "Menghapus surat {$type} #{$letter->reference_number}",
            oldValues: $this->getLetterData($letter)
        );
    }

    private function getLetterData(Letter $letter): array
    {
        return [
            'reference_number' => $letter->reference_number,
            'type' => $letter->type,
            'from' => $letter->from,
            'to' => $letter->to,
            'letter_date' => $letter->letter_date?->format('Y-m-d'),
            'description' => $letter->description,
            'classification_code' => $letter->classification_code,
        ];
    }
}
