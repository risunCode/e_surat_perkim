<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Enums\LetterType;
use App\Services\SignatureService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TranscriptController extends Controller
{
    /**
     * Generate PDF transcript for incoming letter
     */
    public function incomingTranscript($id)
    {
        $letter = Letter::with([
            'attachments',
            'classification',
            'user',
            'dispositions.user',
            'dispositions.status'
        ])->where('type', LetterType::INCOMING)->findOrFail($id);

        // Build correspondence chain - get related outgoing letters
        $correspondenceChain = collect([$letter]);
        $outgoingLetters = Letter::where('type', LetterType::OUTGOING)
            ->where('reference_to', $letter->id)
            ->with(['attachments', 'user'])
            ->get();
        if ($outgoingLetters->count() > 0) {
            $correspondenceChain = $correspondenceChain->merge($outgoingLetters);
        }

        $data = [
            'letter' => $letter,
            'correspondenceChain' => $correspondenceChain,
            'type' => 'incoming',
            'generatedAt' => now()->format('d/m/Y H:i:s'),
            'generatedBy' => auth()->user()->name
        ];

        $data['pdfMode'] = true; // Flag untuk PDF mode
        $pdf = Pdf::loadView('pages.gallery.preview', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'Transkrip-Surat-Masuk-' . $letter->reference_number . '.pdf';
        $filename = preg_replace('/[^A-Za-z0-9\-_.]/', '-', $filename);

        return $pdf->download($filename);
    }

    /**
     * Generate PDF transcript for outgoing letter
     */
    public function outgoingTranscript($id)
    {
        $letter = Letter::with([
            'attachments',
            'classification', 
            'user'
        ])->where('type', LetterType::OUTGOING)->findOrFail($id);

        // Build correspondence chain - get reference letter
        $correspondenceChain = collect();
        if ($letter->reference_to) {
            $referenceLetter = Letter::with(['attachments', 'user'])->find($letter->reference_to);
            if ($referenceLetter) {
                $correspondenceChain->push($referenceLetter);
            }
        }
        $correspondenceChain->push($letter);

        $data = [
            'letter' => $letter,
            'correspondenceChain' => $correspondenceChain,
            'type' => 'outgoing',
            'generatedAt' => now()->format('d/m/Y H:i:s'),
            'generatedBy' => auth()->user()->name
        ];

        $data['pdfMode'] = true; // Flag untuk PDF mode
        $pdf = Pdf::loadView('pages.gallery.preview', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'Transkrip-Surat-Keluar-' . $letter->reference_number . '.pdf';
        $filename = preg_replace('/[^A-Za-z0-9\-_.]/', '-', $filename);

        return $pdf->download($filename);
    }

    /**
     * Generate print-friendly view (alternative to PDF)
     */
    public function printView($type, $id)
    {
        if ($type === 'incoming') {
            $letter = IncomingLetter::with([
                'attachments', 'classification', 'user',
                'dispositions.user', 'dispositions.status', 'replies.user', 'replies.attachments'
            ])->findOrFail($id);
            
            $correspondenceChain = collect([$letter]);
            if ($letter->replies->count() > 0) {
                $correspondenceChain = $correspondenceChain->merge($letter->replies);
            }
        } else {
            $letter = OutgoingLetter::with([
                'attachments', 'classification', 'user', 'referenceTo.user', 'referenceTo.attachments'
            ])->findOrFail($id);
            
            $correspondenceChain = collect();
            if ($letter->referenceTo) {
                $correspondenceChain->push($letter->referenceTo);
            }
            $correspondenceChain->push($letter);
        }

        return view('pdf.letter-transcript', [
            'letter' => $letter,
            'correspondenceChain' => $correspondenceChain,
            'type' => $type,
            'generatedAt' => now()->format('d/m/Y H:i:s'),
            'generatedBy' => auth()->user()->name,
            'printMode' => true
        ]);
    }

    /**
     * Gallery preview page for incoming letters
     */
    public function galleryIncomingPreview($id)
    {
        $letter = Letter::with([
            'attachments',
            'classification',
            'user',
            'dispositions.user',
            'dispositions.status'
        ])->where('type', LetterType::INCOMING)->findOrFail($id);

        // Build correspondence chain - get related outgoing letters
        $correspondenceChain = collect([$letter]);
        $outgoingLetters = Letter::where('type', LetterType::OUTGOING)
            ->where('reference_to', $letter->id)
            ->with(['attachments', 'user'])
            ->get();
        if ($outgoingLetters->count() > 0) {
            $correspondenceChain = $correspondenceChain->merge($outgoingLetters);
        }

        // Get or create signature (reuses existing valid signature)
        $signature = app(SignatureService::class)->getOrCreateTranscriptSignature($letter);

        return view('pages.gallery.preview', [
            'letter' => $letter,
            'correspondenceChain' => $correspondenceChain,
            'type' => 'incoming',
            'generatedAt' => now()->format('d/m/Y H:i:s'),
            'generatedBy' => auth()->user()->name,
            'signature' => $signature
        ]);
    }

    /**
     * Gallery preview page for outgoing letters
     */
    public function galleryOutgoingPreview($id)
    {
        $letter = Letter::with([
            'attachments',
            'classification', 
            'user',
            'dispositions.user',
            'dispositions.status'
        ])->where('type', LetterType::OUTGOING)->findOrFail($id);

        // Build correspondence chain - get reference letter
        $correspondenceChain = collect();
        if ($letter->reference_to) {
            $referenceLetter = Letter::with(['attachments', 'user'])->find($letter->reference_to);
            if ($referenceLetter) {
                $correspondenceChain->push($referenceLetter);
            }
        }
        $correspondenceChain->push($letter);

        // Get or create signature (reuses existing valid signature)
        $signature = app(SignatureService::class)->getOrCreateTranscriptSignature($letter);

        return view('pages.gallery.preview', [
            'letter' => $letter,
            'correspondenceChain' => $correspondenceChain,
            'type' => 'outgoing',
            'generatedAt' => now()->format('d/m/Y H:i:s'),
            'generatedBy' => auth()->user()->name,
            'signature' => $signature
        ]);
    }
}
