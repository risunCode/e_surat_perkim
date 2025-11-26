@if(isset($pdfMode) && $pdfMode)
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>TRANSKRIP SURAT {{ strtoupper($type === 'incoming' ? 'MASUK' : 'KELUAR') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Times New Roman', serif; 
            font-size: 11px; 
            line-height: 1.5; 
            color: #000000;
            background: white;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000000;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .section { margin-bottom: 20px; }
        h1, h2, h3 { color: #000000; font-weight: bold; }
        .no-print, .print-only-hide { display: none !important; }
    </style>
</head>
<body>
@else
@extends('layouts.app')

@section('title', 'Preview Transkrip Surat ' . ($type === 'incoming' ? 'Masuk' : 'Keluar'))

@section('content')
@endif
<div class="space-y-6">
    @if(!isset($pdfMode) || !$pdfMode)
    <!-- Header dengan Action Buttons -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="no-print">
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">
                Preview Transkrip Surat {{ $type === 'incoming' ? 'Masuk' : 'Keluar' }}
            </h1>
            <p style="color: var(--text-secondary);">
                {{ $letter->reference_number }} - {{ $letter->from ?? $letter->to }}
            </p>
        </div>
        
        <div class="flex gap-3 no-print">
            <a href="{{ route('gallery.' . $type) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium" style="background-color: var(--bg-input); color: var(--text-primary); border: 1px solid var(--border-color);">
                <i class="bx bx-arrow-back"></i>
                Kembali ke Galeri
            </a>
            <button onclick="showPrintDialog()" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium btn-primary">
                <i class="bx bx-printer"></i>
                Print or Download as PDF
            </button>
        </div>
    </div>
    @endif

    <!-- Preview Content -->
    <div class="rounded-xl p-6 border" style="background-color: var(--bg-card); border-color: var(--border-color);" id="print-content">
        <!-- Transkrip Header -->
        <div class="text-center border-b-2 pb-4 mb-6" style="border-color: var(--accent-color);">
            <h2 class="text-xl font-bold" style="color: var(--accent-color);">
                TRANSKRIP SURAT {{ strtoupper($type === 'incoming' ? 'MASUK' : 'KELUAR') }}
            </h2>
            <p class="text-sm mt-1" style="color: var(--text-secondary);">
                Dinas Perumahan dan Kawasan Permukiman
            </p>
        </div>

        <!-- Letter Information -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">
                <i class="bx bx-info-circle mr-2"></i>Informasi Surat
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-3">
                    <div class="flex">
                        <span class="w-32 font-medium" style="color: var(--text-secondary);">Nomor Surat:</span>
                        <span style="color: var(--text-primary);">{{ $letter->reference_number }}</span>
                    </div>
                    @if($letter->agenda_number)
                    <div class="flex">
                        <span class="w-32 font-medium" style="color: var(--text-secondary);">Nomor Agenda:</span>
                        <span style="color: var(--text-primary);">{{ $letter->agenda_number }}</span>
                    </div>
                    @endif
                    <div class="flex">
                        <span class="w-32 font-medium" style="color: var(--text-secondary);">{{ $type === 'incoming' ? 'Dari' : 'Kepada' }}:</span>
                        <span style="color: var(--text-primary);">{{ $type === 'incoming' ? $letter->from : $letter->to }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 font-medium" style="color: var(--text-secondary);">Tanggal Surat:</span>
                        <span style="color: var(--text-primary);">{{ $letter->letter_date?->format('d F Y') }}</span>
                    </div>
                </div>
                <div class="space-y-3">
                    @if($type === 'incoming' && $letter->received_date)
                    <div class="flex">
                        <span class="w-32 font-medium" style="color: var(--text-secondary);">Tanggal Diterima:</span>
                        <span style="color: var(--text-primary);">{{ $letter->received_date?->format('d F Y') }}</span>
                    </div>
                    @endif
                    <div class="flex">
                        <span class="w-32 font-medium" style="color: var(--text-secondary);">Klasifikasi:</span>
                        <span style="color: var(--text-primary);">{{ $letter->classification?->code }} - {{ $letter->classification?->type }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 font-medium" style="color: var(--text-secondary);">Diinput oleh:</span>
                        <span style="color: var(--text-primary);">{{ $letter->user?->name }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 font-medium" style="color: var(--text-secondary);">Status:</span>
                        <span class="font-medium {{ $letter->is_completed ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $letter->is_completed ? 'SELESAI' : 'DALAM PROSES' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description -->
        @if($letter->description)
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-3" style="color: var(--text-primary);">
                <i class="bx bx-file-text mr-2"></i>Perihal
            </h3>
            <div class="pl-4" style="border-left: 3px solid var(--border-color);">
                <p style="color: var(--text-primary);">{{ $letter->description }}</p>
            </div>
        </div>
        @endif

        <!-- Attachments -->
        @if($letter->attachments->count() > 0)
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-3" style="color: var(--text-primary);">
                <i class="bx bx-paperclip mr-2"></i>Lampiran ({{ $letter->attachments->count() }} file)
            </h3>
            <div class="space-y-2">
                @foreach($letter->attachments as $index => $attachment)
                    @if($loop->index < 3)
                    <div class="flex items-center gap-3 py-2 px-3 rounded border" style="background-color: var(--bg-input); border-color: var(--border-color);">
                        @if(in_array($attachment->extension, ['jpg','jpeg','png','gif']))
                            <img src="{{ route('attachment.serve', $attachment->id) }}" 
                                 class="w-8 h-8 rounded object-cover cursor-pointer hover:opacity-80" 
                                 onclick="openAttachmentGallery({{ $index }})"
                                 alt="{{ $attachment->filename }}"
                                 title="Click to preview">
                        @else
                            <div class="w-8 h-8 rounded flex items-center justify-center cursor-pointer hover:opacity-80
                                {{ $attachment->extension == 'pdf' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}"
                                 onclick="openAttachmentGallery({{ $index }})"
                                 title="Click to preview">
                                <i class="bx {{ $attachment->extension == 'pdf' ? 'bxs-file-pdf' : 'bx-file' }} text-sm"></i>
                            </div>
                        @endif
                        
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate" style="color: var(--text-primary);">{{ $attachment->filename }}</p>
                            <p class="text-xs" style="color: var(--text-secondary);">{{ strtoupper($attachment->extension) }} • {{ $attachment->formatted_size }}</p>
                        </div>
                        
                        <div class="flex gap-1 no-print">
                            <button onclick="openAttachmentGallery({{ $index }})" 
                                    class="text-xs p-1 rounded hover:opacity-80" 
                                    style="background-color: var(--bg-card); color: var(--text-secondary);" title="Preview">
                                <i class="bx bx-show"></i>
                            </button>
                            <a href="{{ route('attachment.download', $attachment->id) }}" 
                               target="_blank" 
                               class="text-xs p-1 rounded btn-primary" title="Download">
                                <i class="bx bx-download"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                @endforeach
                
                @if($letter->attachments->count() > 3)
                <div class="text-center py-2 text-sm" style="color: var(--text-secondary);">
                    <i class="bx bx-dots-horizontal-rounded"></i> Dan {{ $letter->attachments->count() - 3 }} file lainnya
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Dispositions -->
        @if($letter->dispositions->count() > 0)
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-3" style="color: var(--text-primary);">
                <i class="bx bx-transfer mr-2"></i>Riwayat Disposisi ({{ $letter->dispositions->count() }} disposisi)
            </h3>
            <div class="space-y-3">
                @foreach($letter->dispositions as $disposition)
                <div class="p-4 rounded-lg bg-yellow-50 border border-yellow-200">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-medium text-yellow-800">
                            Disposisi {{ $loop->iteration }} - {{ $disposition->user?->name }}
                        </h4>
                        <span class="text-xs text-yellow-600">{{ $disposition->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <p class="text-sm text-yellow-700 mb-1">Status: {{ $disposition->status?->status }}</p>
                    @if($disposition->notes)
                    <p class="text-sm text-yellow-700">Catatan: {{ $disposition->notes }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Correspondence Chain -->
        @if($correspondenceChain->count() > 1)
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-3" style="color: var(--text-primary);">
                <i class="bx bx-git-branch mr-2"></i>Riwayat Korespondensi
            </h3>
            <div class="space-y-4">
                @foreach($correspondenceChain as $item)
                <div class="p-4 rounded-lg border-l-4 
                    {{ class_basename($item) === 'Letter' && $item->type === 'incoming' ? 'border-green-400 bg-green-50' : 'border-blue-400 bg-blue-50' }}">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-medium 
                            {{ class_basename($item) === 'Letter' && $item->type === 'incoming' ? 'text-green-800' : 'text-blue-800' }}">
                            {{ $item->type === 'incoming' ? 'SURAT MASUK' : 'SURAT KELUAR' }} - {{ $item->reference_number }}
                        </h4>
                        <span class="text-xs 
                            {{ class_basename($item) === 'Letter' && $item->type === 'incoming' ? 'text-green-600' : 'text-blue-600' }}">
                            {{ $item->letter_date?->format('d/m/Y') }}
                        </span>
                    </div>
                    <p class="text-sm mb-1 
                        {{ class_basename($item) === 'Letter' && $item->type === 'incoming' ? 'text-green-700' : 'text-blue-700' }}">
                        {{ $item->type === 'incoming' ? 'Dari' : 'Kepada' }}: {{ $item->type === 'incoming' ? $item->from : $item->to }}
                    </p>
                    @if($item->description)
                    <p class="text-sm mb-2
                        {{ class_basename($item) === 'Letter' && $item->type === 'incoming' ? 'text-green-700' : 'text-blue-700' }}">
                        Perihal: {{ $item->description }}
                    </p>
                    @endif
                    @if($item->attachments && $item->attachments->count() > 0)
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-xs 
                            {{ class_basename($item) === 'Letter' && $item->type === 'incoming' ? 'text-green-600' : 'text-blue-600' }}">
                            Lampiran:
                        </span>
                        @foreach($item->attachments->take(3) as $att)
                            @if(in_array($att->extension, ['jpg','jpeg','png','gif']))
                                <img src="{{ route('attachment.serve', $att->id) }}" 
                                     class="w-8 h-8 rounded object-cover" 
                                     alt="{{ $att->filename }}"
                                     title="{{ $att->filename }}">
                            @else
                                <div class="w-8 h-8 rounded flex items-center justify-center text-xs
                                    {{ $att->extension == 'pdf' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}"
                                     title="{{ $att->filename }}">
                                    {{ strtoupper($att->extension) }}
                                </div>
                            @endif
                        @endforeach
                        @if($item->attachments->count() > 3)
                            <span class="text-xs px-1 py-0.5 rounded 
                                {{ class_basename($item) === 'Letter' && $item->type === 'incoming' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }}">
                                +{{ $item->attachments->count() - 3 }}
                            </span>
                        @endif
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Footer with Digital Signature -->
        <div class="mt-8 pt-4">
            <div class="flex justify-between items-start">
                <!-- Left: Document Info -->
                <div class="text-sm no-print" style="color: var(--text-secondary);">
                    <p><strong>Dokumen Digital Tersertifikasi</strong></p>
                    <p>Transkrip digenerate pada: {{ $generatedAt }}</p>
                    <p>Oleh: {{ $generatedBy }}</p>
                    <div class="mt-3">
                        <p class="text-xs mb-1">ID Dokumen:</p>
                        <code class="text-xs" style="word-break: break-all; display: block; line-height: 1.2; max-width: 400px;">{{ $signature->signature_hash }}</code>
                    </div>
                </div>
                
                <!-- Right: QR Code -->
                <div class="text-right">
                    <canvas id="qr-canvas" width="80" height="80" style="display: block; border: 1px solid #ccc; margin-left: auto;"></canvas>
                    <noscript>
                        <div style="width: 80px; height: 80px; border: 2px solid #000; display: flex; align-items: center; justify-content: center; font-size: 8px; text-align: center; background: #f9f9f9; flex-direction: column; padding: 4px; margin-left: auto;">
                            <div style="font-weight: bold; margin-bottom: 2px;">VERIFIKASI</div>
                            <div style="font-size: 6px; line-height: 1.2;">Scan untuk Verifikasi</div>
                        </div>
                    </noscript>
                    <p class="text-xs" style="color: var(--text-secondary); margin-top: 2px;">Scan untuk Verifikasi</p>
                </div>
            </div>
            
            <div class="mt-4 pt-2 border-t text-center text-xs" style="border-color: var(--border-color); color: var(--text-secondary);">
                <p class="font-medium">E-Surat Perkim - Sistem Manajemen Surat Digital</p>
                <p>Dinas Perumahan dan Kawasan Permukiman</p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    /* Hide browser headers and footers */
    @page {
        margin: 0.5in;
        size: A4;
    }
    
    /* Hide everything except print content */
    body * {
        visibility: hidden;
    }
    
    #print-content, #print-content * {
        visibility: visible;
    }
    
    #print-content {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background: white !important;
        box-shadow: none !important;
        border: none !important;
        padding: 20px !important;
        font-family: 'Times New Roman', serif !important;
        font-size: 11px !important;
        line-height: 1.5 !important;
        color: #000000 !important;
    }
    
    /* Hide buttons and interactive elements */
    .no-print { display: none !important; }
    
    /* Professional office print styling - Black & White */
    * { color: #000000 !important; }
    
    h1, h2, h3, h4, h5, h6 { 
        color: #000000 !important;
        font-weight: bold !important;
    }
    
    /* Clean header styling */
    #print-content .text-center:first-child {
        text-align: center !important;
        border-bottom: 2px solid #000000 !important;
        padding-bottom: 15px !important;
        margin-bottom: 25px !important;
    }
    
    #print-content h2:first-of-type {
        font-size: 16px !important;
        font-weight: bold !important;
        text-transform: uppercase !important;
        letter-spacing: 1px !important;
        margin-bottom: 8px !important;
    }
    
    /* Section headers - reduce borders */
    h3 {
        font-size: 13px !important;
        text-transform: uppercase !important;
        font-weight: bold !important;
        margin-bottom: 12px !important;
        padding-bottom: 3px !important;
    }
    
    /* Remove excessive borders from cards and containers */
    .rounded-xl, .border-l-4 {
        border: none !important;
        box-shadow: none !important;
        background: white !important;
    }
    
    /* Clean attachment list - simple lines */
    .border-b {
        border-bottom: 1px solid #000000 !important;
    }
    
    .last\:border-b-0:last-child {
        border-bottom: none !important;
    }
    
    /* Perihal and attachment left border for office style */
    .pl-4 {
        border-left: 2px solid #000000 !important;
        padding-left: 12px !important;
    }
    
    /* Clean attachment layout for print */
    .space-y-2 > * + * { margin-top: 8px !important; }
    .rounded { border-radius: 0 !important; }
    .w-8, .h-8 { width: 24px !important; height: 24px !important; }
    .gap-3 { gap: 8px !important; }
    .px-3 { padding-left: 8px !important; padding-right: 8px !important; }
    .py-2 { padding-top: 4px !important; padding-bottom: 4px !important; }
    
    /* Remove all background colors and make everything B&W */
    .text-green-600, .text-green-700, .text-green-800,
    .text-blue-600, .text-blue-700, .text-blue-800,
    .text-yellow-600, .text-yellow-700, .text-yellow-800,
    .bg-green-50, .bg-blue-50, .bg-yellow-50,
    .bg-green-100, .bg-blue-100, .bg-red-100,
    .text-green-600, .text-blue-600, .text-red-600 { 
        background-color: #ffffff !important;
        color: #000000 !important;
    }
    
    /* Status formatting */
    .font-medium { font-weight: bold !important; }
    
    /* Hide icons in print */
    .bx, i[class*="bx"] { display: none !important; }
    
    /* Clean footer */
    .footer, .border-t {
        border-top: 1px solid #000000 !important;
        margin-top: 25px !important;
        padding-top: 12px !important;
        text-align: center !important;
    }
    
    /* QR Code in print - simple right alignment */
    .text-right {
        text-align: right !important;
    }
    
    #qr-canvas {
        display: block !important;
        margin-left: auto !important;
    }
    
    /* Document ID in print */
    code {
        font-size: 8px !important;
        word-break: break-all !important;
        display: block !important;
        margin: 4px 0 !important;
        border: 1px solid #ccc !important;
        padding: 2px 4px !important;
        background: #f9f9f9 !important;
    }
}
</style>

@if(isset($pdfMode) && $pdfMode)
</body>
</html>
@else
@push('scripts')
<script src="/js/qrious.min.js"></script>
<script>
// Generate QR Code
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('qr-canvas');
    if (canvas && typeof QRious !== 'undefined') {
        try {
            new QRious({
                element: canvas,
                value: '{{ $signature->verification_url }}',
                size: 80,
                foreground: '#000000',
                background: '#ffffff'
            });
        } catch (error) {
            console.error('QR generation failed:', error);
            // Hide canvas jika error
            canvas.style.display = 'none';
        }
    }
});

// Attachment gallery functionality
@if($letter->attachments->count() > 0)
const attachmentFiles = [
    @foreach($letter->attachments as $att)
    { url: '{{ route('attachment.serve', $att->id) }}', name: '{{ $att->filename }}' },
    @endforeach
];

function openAttachmentGallery(index) {
    openGallery(attachmentFiles, index);
}
@endif

// Simple print function - no complex templates
function showPrintDialog() {
    console.log('Print function called');
    
    // Show instruction then print
    const proceed = confirm(`TIPS PRINT BERSIH:

1. Setelah OK, dialog print akan muncul
2. Cari "More settings" atau "Pengaturan lanjutan"  
3. MATIKAN "Headers and footers"
4. Pilih printer atau "Save as PDF"
5. Klik Print/Save

Lanjutkan?`);
    
    if (proceed) {
        window.print();
    }
}
</script>
@endpush
@endsection
@endif
