@extends('layouts.app')
@section('title', 'Detail Surat Keluar')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('outgoing.index') }}" class="inline-flex items-center gap-1 mb-2 hover:opacity-70" style="color: var(--text-secondary);"><i class="bx bx-arrow-back"></i> Kembali</a>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">{{ $letter->reference_number }}</h1>
            <p style="color: var(--text-secondary);">Detail surat keluar</p>
        </div>
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
            @if($letter->is_completed)
                <span class="inline-flex items-center gap-1 px-3 py-1.5 text-sm rounded-lg bg-green-100 text-green-700">
                    <i class="bx bx-check-circle"></i> Selesai
                    @if($letter->completed_at)
                        <span class="text-xs ml-1">({{ $letter->completed_at->format('d/m/Y') }})</span>
                    @endif
                </span>
            @endif
            
            @if(auth()->user()->isAdmin() || $letter->user_id === auth()->id())
            <div class="flex items-center gap-2">
                @if($letter->is_completed)
                    <form action="{{ route('outgoing.toggleCompletion', $letter) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-lg bg-yellow-100 text-yellow-700 hover:bg-yellow-200">
                            <i class="bx bx-undo"></i> Belum Selesai
                        </button>
                    </form>
                @else
                    <form action="{{ route('outgoing.toggleCompletion', $letter) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-lg bg-green-500 text-white hover:bg-green-600">
                            <i class="bx bx-check"></i> Tandai Selesai
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('outgoing.edit', $letter) }}" class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-lg" style="background-color: var(--bg-input); color: var(--text-primary);"><i class="bx bx-edit"></i> Edit</a>
            </div>
            @endif
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-xl p-6 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <h2 class="text-lg font-semibold mb-4" style="color: var(--text-primary);"><i class="bx bx-envelope mr-2"></i>Informasi Surat</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><p class="text-xs uppercase tracking-wide mb-1" style="color: var(--text-secondary);">Nomor Surat</p><p class="font-medium" style="color: var(--text-primary);">{{ $letter->reference_number }}</p></div>
                <div><p class="text-xs uppercase tracking-wide mb-1" style="color: var(--text-secondary);">Nomor Agenda</p><p style="color: var(--text-primary);">{{ $letter->agenda_number ?? '-' }}</p></div>
                <div><p class="text-xs uppercase tracking-wide mb-1" style="color: var(--text-secondary);">Kepada/Tujuan</p><p style="color: var(--text-primary);">{{ $letter->to }}</p></div>
                <div><p class="text-xs uppercase tracking-wide mb-1" style="color: var(--text-secondary);">Klasifikasi</p><span class="inline-block px-2 py-1 text-sm rounded" style="background-color: var(--bg-input); color: var(--text-secondary);">{{ $letter->classification?->code }} - {{ $letter->classification?->type }}</span></div>
                <div><p class="text-xs uppercase tracking-wide mb-1" style="color: var(--text-secondary);">Tanggal Surat</p><p style="color: var(--text-primary);">{{ $letter->letter_date?->format('d F Y') }}</p></div>
                <div><p class="text-xs uppercase tracking-wide mb-1" style="color: var(--text-secondary);">Dibuat oleh</p><p style="color: var(--text-primary);">{{ $letter->user->name }}</p></div>
                <div class="md:col-span-2"><p class="text-xs uppercase tracking-wide mb-1" style="color: var(--text-secondary);">Perihal</p><p style="color: var(--text-primary);">{{ $letter->description ?? '-' }}</p></div>
                <div class="md:col-span-2"><p class="text-xs uppercase tracking-wide mb-1" style="color: var(--text-secondary);">Catatan</p><p style="color: var(--text-primary);">{{ $letter->note ?? '-' }}</p></div>
            </div>
        </div>
        
        <div class="space-y-4">
            <!-- Attachments -->
            <div class="rounded-xl p-6 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <h2 class="text-lg font-semibold mb-4" style="color: var(--text-primary);"><i class="bx bx-paperclip mr-2"></i>Lampiran</h2>
            @if($letter->attachments->count())
            <div class="space-y-2">
                @foreach($letter->attachments as $index => $att)
                <div class="flex items-center justify-between p-3 rounded-lg" style="background-color: var(--bg-input);">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        @if(in_array($att->extension, ['jpg','jpeg','png','gif']))
                        <img src="{{ Storage::url($att->full_path) }}" class="w-10 h-10 rounded object-cover cursor-pointer" onclick="openOutgoingGallery({{ $index }})">
                        @else
                        <div class="w-10 h-10 rounded flex items-center justify-center {{ $att->extension == 'pdf' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                            <i class="bx {{ $att->extension == 'pdf' ? 'bxs-file-pdf' : 'bx-file' }} text-xl"></i>
                        </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate" style="color: var(--text-primary);">{{ $att->filename }}</p>
                            <p class="text-xs uppercase" style="color: var(--text-secondary);">{{ $att->extension }}</p>
                        </div>
                    </div>
                    <div class="flex gap-1 ml-2">
                        <button onclick="openOutgoingGallery({{ $index }})" class="p-2 rounded-lg hover:opacity-70" style="background-color: var(--bg-card); color: var(--text-secondary);"><i class="bx bx-show"></i></button>
                        <a href="{{ Storage::url($att->full_path) }}" target="_blank" class="p-2 rounded-lg btn-primary"><i class="bx bx-download"></i></a>
                    </div>
                </div>
                @endforeach
            </div>
            @push('scripts')
            <script>
                const outgoingFiles = [@foreach($letter->attachments as $att){ url: '{{ Storage::url($att->full_path) }}', name: '{{ $att->filename }}' },@endforeach];
                function openOutgoingGallery(index) { openGallery(outgoingFiles, index); }
            </script>
            @endpush
            @else
            <p class="text-center py-6" style="color: var(--text-secondary);"><i class="bx bx-file text-4xl mb-2 block opacity-50"></i>Tidak ada lampiran</p>
            @endif
            </div>
        </div>
    </div>

    <!-- Correspondence Chain -->
    @if($correspondenceChain->count() > 1)
    <div class="rounded-xl p-4 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <h2 class="text-base font-semibold mb-2 flex items-center" style="color: var(--text-primary);">
            <i class="bx bx-git-branch mr-2"></i>Alur Korespondensi ({{ $correspondenceChain->count() }} surat)
        </h2>
        
        <div class="flex items-center gap-2 overflow-x-auto pb-2">
            @foreach($correspondenceChain as $index => $chainLetter)
                @if($index > 0)
                <i class="bx bx-right-arrow-alt text-lg flex-shrink-0" style="color: var(--text-secondary);"></i>
                @endif
                
                <div class="flex-shrink-0 {{ $chainLetter->id === $letter->id ? 'ring-2 ring-blue-400' : '' }}">
                    <a href="{{ route($chainLetter->isIncoming() ? 'incoming.show' : 'outgoing.show', $chainLetter) }}" 
                       class="block p-2 rounded-lg transition-all hover:opacity-80" 
                       style="background-color: var(--bg-input); min-width: 160px;">
                        
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-5 h-5 rounded-full flex items-center justify-center text-white text-xs font-bold {{ $chainLetter->isIncoming() ? 'bg-purple-500' : 'bg-green-500' }}">
                                {{ $index + 1 }}
                            </span>
                            <span class="text-xs font-medium {{ $chainLetter->isIncoming() ? 'text-purple-700' : 'text-green-700' }}">
                                {{ $chainLetter->isIncoming() ? 'Masuk' : 'Keluar' }}
                            </span>
                            @if($chainLetter->id === $letter->id)
                            <span class="text-xs px-1 py-0.5 bg-blue-100 text-blue-700 rounded">Ini</span>
                            @endif
                        </div>
                        
                        <p class="text-sm font-medium mb-0.5" style="color: var(--text-primary);">{{ $chainLetter->reference_number }}</p>
                        <p class="text-xs" style="color: var(--text-secondary);">{{ $chainLetter->letter_date?->format('d/m/Y') }}</p>
                    </a>
                </div>
            @endforeach
        </div>
        
        <p class="text-xs mt-2 text-center" style="color: var(--text-secondary);">
            <i class="bx bx-info-circle mr-1"></i>Geser untuk melihat semua surat dalam alur
        </p>
    </div>
    @endif
</div>
@endsection
