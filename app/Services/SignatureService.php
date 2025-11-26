<?php

namespace App\Services;

use App\Models\DocumentSignature;
use App\Models\Letter;
use Illuminate\Support\Facades\Auth;

class SignatureService
{
    /**
     * Generate digital signature for letter (invalidates old signatures)
     */
    public function generateLetterSignature(Letter $letter, ?int $userId = null): DocumentSignature
    {
        $userId = $userId ?? Auth::id();
        $letterType = $letter->isIncoming() ? 'incoming' : 'outgoing';
        $fromTo = $letter->isIncoming() ? $letter->from : $letter->to;
        
        // Invalidate old signatures
        $letter->signatures()->update(['is_valid' => false]);
        
        // Generate document content for hashing
        $content = $this->generateDocumentContent($letter, $letterType, $fromTo);
        
        // Generate signature hash
        $signatureHash = DocumentSignature::generateSignature(
            $content, 
            $letter->id, 
            $userId, 
            'letter'
        );
        
        // Generate content hash
        $contentHash = DocumentSignature::generateContentHash($content);
        
        // Store signature in database
        return DocumentSignature::create([
            'letter_id' => $letter->id,
            'user_id' => $userId,
            'document_type' => 'letter',
            'signature_hash' => $signatureHash,
            'content_hash' => $contentHash,
            'signed_at' => now(),
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'user_agent' => request()->userAgent() ?? 'CLI',
            'metadata' => [
                'letter_type' => $letterType,
                'reference_number' => $letter->reference_number,
                'from_to' => $fromTo,
                'letter_date' => $letter->letter_date?->toDateString(),
                'generated_at' => now()->toDateTimeString()
            ]
        ]);
    }

    /**
     * Generate or get existing signature for transcript
     * Reuses existing valid signature if available
     */
    public function getOrCreateTranscriptSignature(Letter $letter): DocumentSignature
    {
        // Check for existing valid signature
        $existing = $letter->latestSignature;
        
        if ($existing) {
            return $existing;
        }
        
        // Generate new signature
        return $this->generateLetterSignature($letter);
    }
    
    /**
     * Generate document content for signature
     */
    public function generateDocumentContent(Letter $letter, string $letterType, string $fromTo): string
    {
        $content = [
            'reference_number' => $letter->reference_number,
            'type' => $letterType,
            'from_to' => $fromTo,
            'letter_date' => $letter->letter_date?->toDateString(),
            'description' => $letter->description,
            'classification' => $letter->classification?->code,
            'user' => $letter->user?->name,
            'attachments_count' => $letter->attachments->count(),
            'attachments' => $letter->attachments->pluck('filename')->sort()->values()->toArray(),
        ];
        
        ksort($content);
        return json_encode($content, JSON_UNESCAPED_UNICODE);
    }
}
