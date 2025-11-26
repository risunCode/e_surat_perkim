<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transkrip Surat {{ $type === 'incoming' ? 'Masuk' : 'Keluar' }}</title>
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
        .header h1 {
            font-size: 16px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header h2 {
            font-size: 12px;
            color: #000000;
            font-weight: normal;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 140px;
            font-weight: bold;
            padding: 3px 8px 3px 0;
            vertical-align: top;
            color: #000000;
        }
        .info-value {
            display: table-cell;
            padding: 3px 0;
            vertical-align: top;
            color: #000000;
        }
        .info-separator {
            display: table-cell;
            width: 15px;
            text-align: center;
            padding: 3px 0;
            vertical-align: top;
            color: #000000;
        }

        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #000000;
            text-transform: uppercase;
            border-bottom: 1px solid #000000;
            padding-bottom: 3px;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }

        .attachment-list {
            margin: 12px 0;
        }
        .attachment-item {
            padding: 8px 12px;
            border: 1px solid #000000;
            margin-bottom: 6px;
            background-color: #ffffff;
        }
        .attachment-name {
            font-weight: bold;
            color: #000000;
            font-size: 11px;
        }
        .attachment-details {
            font-size: 10px;
            color: #000000;
            margin-top: 2px;
        }

        .disposition-item {
            padding: 12px;
            border: 1px solid #000000;
            margin-bottom: 8px;
            background-color: #ffffff;
        }
        .disposition-header {
            font-weight: bold;
            color: #000000;
            margin-bottom: 6px;
            font-size: 11px;
        }

        .correspondence-item {
            padding: 12px;
            border: 1px solid #000000;
            margin-bottom: 12px;
            background-color: #ffffff;
        }
        .correspondence-header {
            font-weight: bold;
            margin-bottom: 8px;
            color: #000000;
            border-bottom: 1px solid #000000;
            padding-bottom: 4px;
        }
        .correspondence-incoming {
            border-left: 3px solid #000000;
        }
        .correspondence-outgoing {
            border-left: 3px solid #000000;
        }

        .status-completed { color: #000000; font-weight: bold; }
        .status-pending { color: #000000; font-weight: bold; }

        .footer {
            margin-top: 25px;
            padding-top: 12px;
            border-top: 1px solid #000000;
            font-size: 10px;
            color: #000000;
            text-align: center;
        }

        @media print {
            body { print-color-adjust: exact; }
            .page-break { page-break-before: always; }
        }

        @if(isset($printMode) && $printMode)
        @media print {
            .no-print { display: none !important; }
        }
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        @endif
    </style>
</head>
<body>
    @if(isset($printMode) && $printMode)
    <button class="print-button no-print" onclick="window.print()">🖨️ Print</button>
    @endif

    <!-- Header -->
    <div class="header">
        <h1>TRANSKRIP SURAT {{ strtoupper($type === 'incoming' ? 'MASUK' : 'KELUAR') }}</h1>
        <h2>Dinas Perumahan dan Kawasan Permukiman</h2>
        <p style="font-size: 10px; margin-top: 5px; color: #000000;">Sistem E-Surat Digital</p>
    </div>

    <!-- Letter Information -->
    <div class="section">
        <div class="section-title">INFORMASI SURAT</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nomor Surat</div>
                <div class="info-separator">:</div>
                <div class="info-value">{{ $letter->reference_number }}</div>
            </div>
            @if($letter->agenda_number)
            <div class="info-row">
                <div class="info-label">Nomor Agenda</div>
                <div class="info-separator">:</div>
                <div class="info-value">{{ $letter->agenda_number }}</div>
            </div>
            @endif
            <div class="info-row">
                <div class="info-label">{{ $type === 'incoming' ? 'Dari' : 'Kepada' }}</div>
                <div class="info-separator">:</div>
                <div class="info-value">{{ $type === 'incoming' ? $letter->from : $letter->to }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Surat</div>
                <div class="info-separator">:</div>
                <div class="info-value">{{ $letter->letter_date?->format('d F Y') }}</div>
            </div>
            @if($type === 'incoming' && $letter->received_date)
            <div class="info-row">
                <div class="info-label">Tanggal Diterima</div>
                <div class="info-separator">:</div>
                <div class="info-value">{{ $letter->received_date?->format('d F Y') }}</div>
            </div>
            @endif
            <div class="info-row">
                <div class="info-label">Klasifikasi</div>
                <div class="info-separator">:</div>
                <div class="info-value">{{ $letter->classification?->code }} - {{ $letter->classification?->type }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Diinput oleh</div>
                <div class="info-separator">:</div>
                <div class="info-value">{{ $letter->user?->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status</div>
                <div class="info-separator">:</div>
                <div class="info-value">
                    <span class="{{ $letter->is_completed ? 'status-completed' : 'status-pending' }}">
                        {{ $letter->is_completed ? 'SELESAI' : 'DALAM PROSES' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Description -->
    @if($letter->description)
    <div class="section">
        <div class="section-title">PERIHAL</div>
        <div style="padding: 12px; border: 1px solid #000000; background-color: #ffffff; color: #000000;">
            {{ $letter->description }}
        </div>
    </div>
    @endif

    <!-- Attachments -->
    @if($letter->attachments->count() > 0)
    <div class="section">
        <div class="section-title">LAMPIRAN ({{ $letter->attachments->count() }} file)</div>
        <div class="attachment-list">
            @foreach($letter->attachments as $attachment)
            <div class="attachment-item">
                <div class="attachment-name">{{ $attachment->filename }}</div>
                <div class="attachment-details">
                    Format: {{ strtoupper($attachment->extension) }} | 
                    Ukuran: {{ $attachment->formatted_size }} |
                    Diupload: {{ $attachment->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Dispositions (for incoming letters) -->
    @if($type === 'incoming' && $letter->dispositions->count() > 0)
    <div class="section">
        <div class="section-title">RIWAYAT DISPOSISI ({{ $letter->dispositions->count() }} disposisi)</div>
        @foreach($letter->dispositions as $disposition)
        <div class="disposition-item">
            <div class="disposition-header">
                Disposisi {{ $loop->iteration }} - {{ $disposition->user?->name }}
            </div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Tanggal</div>
                    <div class="info-separator">:</div>
                    <div class="info-value">{{ $disposition->created_at->format('d F Y, H:i') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Status</div>
                    <div class="info-separator">:</div>
                    <div class="info-value">{{ $disposition->status?->status }}</div>
                </div>
                @if($disposition->notes)
                <div class="info-row">
                    <div class="info-label">Catatan</div>
                    <div class="info-separator">:</div>
                    <div class="info-value">{{ $disposition->notes }}</div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Correspondence Chain -->
    @if($correspondenceChain->count() > 1)
    <div class="section page-break">
        <div class="section-title">RIWAYAT KORESPONDENSI</div>
        @foreach($correspondenceChain as $item)
        <div class="correspondence-item correspondence-{{ class_basename($item) === 'IncomingLetter' ? 'incoming' : 'outgoing' }}">
            <div class="correspondence-header">
                {{ $item->type === 'incoming' ? 'SURAT MASUK' : 'SURAT KELUAR' }} - {{ $item->reference_number }}
            </div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">{{ $item->type === 'incoming' ? 'Dari' : 'Kepada' }}</div>
                    <div class="info-separator">:</div>
                    <div class="info-value">{{ $item->type === 'incoming' ? $item->from : $item->to }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal</div>
                    <div class="info-separator">:</div>
                    <div class="info-value">{{ $item->letter_date?->format('d F Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Diinput oleh</div>
                    <div class="info-separator">:</div>
                    <div class="info-value">{{ $item->user?->name }}</div>
                </div>
                @if($item->description)
                <div class="info-row">
                    <div class="info-label">Perihal</div>
                    <div class="info-separator">:</div>
                    <div class="info-value">{{ $item->description }}</div>
                </div>
                @endif
                @if($item->attachments->count() > 0)
                <div class="info-row">
                    <div class="info-label">Lampiran</div>
                    <div class="info-separator">:</div>
                    <div class="info-value">{{ $item->attachments->count() }} file</div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div>Transkrip digenerate pada: {{ $generatedAt }}</div>
        <div>Oleh: {{ $generatedBy }}</div>
        <div>E-Surat Perkim - Sistem Manajemen Surat Digital</div>
    </div>

    @if(isset($printMode) && $printMode)
    <script>
        // Auto focus for print dialog
        setTimeout(() => {
            if (window.location.search.includes('print=1')) {
                window.print();
            }
        }, 500);
    </script>
    @endif
</body>
</html>
