@extends('layouts.app')

@section('title', 'Detail Surat Masuk')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('incoming.index') }}" class="inline-flex items-center gap-1 mb-2 hover:opacity-70" style="color: var(--text-secondary);">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">{{ $letter->reference_number }}</h1>
            <p style="color: var(--text-secondary);">Detail surat masuk</p>
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
            
            <div class="flex items-center gap-2">
                @if(auth()->user()->isAdmin() || $letter->user_id === auth()->id())
                    @if($letter->is_completed)
                        <form action="{{ route('incoming.toggleCompletion', $letter) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-lg bg-yellow-100 text-yellow-700 hover:bg-yellow-200">
                                <i class="bx bx-undo"></i> Belum Selesai
                            </button>
                        </form>
                    @else
                        <form action="{{ route('incoming.toggleCompletion', $letter) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-lg bg-green-500 text-white hover:bg-green-600">
                                <i class="bx bx-check"></i> Tandai Selesai
                            </button>
                        </form>
                    @endif
                @endif
                
                <a href="{{ route('outgoing.create', ['reply_to' => $letter->id]) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium bg-green-600 text-white hover:bg-green-700">
                    <i class="bx bx-reply"></i> Balas Surat
                </a>
                <button onclick="openModal('modal-create-disposition')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium btn-primary">
                    <i class="bx bx-transfer"></i> Buat Disposisi
                </button>
                @if(auth()->user()->isAdmin() || $letter->user_id === auth()->id())
                <a href="{{ route('incoming.edit', $letter) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border" style="background-color: var(--bg-input); color: var(--text-primary); border-color: var(--border-color);">
                    <i class="bx bx-edit"></i> Edit
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Letter Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-xl p-6 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <h2 class="text-lg font-semibold mb-4" style="color: var(--text-primary);"><i class="bx bx-envelope mr-2"></i>Informasi Surat</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-xs font-medium uppercase mb-1" style="color: var(--text-secondary);">Nomor Surat</p>
                        <p class="font-semibold" style="color: var(--text-primary);">{{ $letter->reference_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase mb-1" style="color: var(--text-secondary);">Nomor Agenda</p>
                        <p style="color: var(--text-primary);">{{ $letter->agenda_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase mb-1" style="color: var(--text-secondary);">Klasifikasi</p>
                        <span class="inline-block px-2 py-1 text-sm rounded" style="background-color: var(--bg-input); color: var(--text-primary);">{{ $letter->classification?->code }} - {{ $letter->classification?->type }}</span>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs font-medium uppercase mb-1" style="color: var(--text-secondary);">Dari/Pengirim</p>
                        <p style="color: var(--text-primary);">{{ $letter->from }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase mb-1" style="color: var(--text-secondary);">Tanggal Surat</p>
                        <p style="color: var(--text-primary);">{{ $letter->letter_date?->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase mb-1" style="color: var(--text-secondary);">Tanggal Diterima</p>
                        <p style="color: var(--text-primary);">{{ $letter->received_date?->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase mb-1" style="color: var(--text-secondary);">Dibuat oleh</p>
                        <p style="color: var(--text-primary);">{{ $letter->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase mb-1" style="color: var(--text-secondary);">Tanggal Input</p>
                        <p style="color: var(--text-primary);">{{ $letter->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="md:col-span-3">
                        <p class="text-xs font-medium uppercase mb-1" style="color: var(--text-secondary);">Perihal/Deskripsi</p>
                        <p style="color: var(--text-primary);">{{ $letter->description ?? '-' }}</p>
                    </div>
                    @if($letter->note)
                    <div class="md:col-span-3">
                        <p class="text-xs font-medium uppercase mb-1" style="color: var(--text-secondary);">Catatan</p>
                        <p style="color: var(--text-primary);">{{ $letter->note }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Dispositions - Grid Style -->
            <div class="rounded-xl p-5 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-semibold" style="color: var(--text-primary);"><i class="bx bx-transfer mr-2"></i>Disposisi</h2>
                        <p class="text-sm" style="color: var(--text-secondary);">Instruksi internal untuk menindaklanjuti surat</p>
                    </div>
                    <button onclick="openModal('modal-create-disposition')" class="inline-flex items-center gap-2 text-sm px-4 py-2 rounded-lg btn-primary">
                        <i class="bx bx-plus"></i> Tambah Disposisi
                    </button>
                </div>
                @if($letter->dispositions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($letter->dispositions as $disposition)
                    <div class="rounded-xl border overflow-hidden" style="background-color: var(--bg-input); border-color: var(--border-color);">
                        <!-- Header -->
                        <div class="px-4 py-3 flex items-center justify-between {{ $disposition->status?->status === 'Selesai' ? 'bg-green-50' : ($disposition->status?->status === 'Urgent' ? 'bg-red-50' : 'bg-yellow-50') }}">
                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $disposition->status?->status === 'Selesai' ? 'bg-green-100 text-green-700' : ($disposition->status?->status === 'Urgent' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ $disposition->status?->status }}</span>
                            <span class="text-xs" style="color: var(--text-secondary);">{{ $disposition->created_at->translatedFormat('d M Y') }}</span>
                        </div>
                        <!-- Body -->
                        <div class="p-4">
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <p class="text-xs uppercase font-medium mb-1" style="color: var(--text-secondary);">Kepada</p>
                                    <p class="font-semibold" style="color: var(--text-primary);">{{ $disposition->to }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase font-medium mb-1" style="color: var(--text-secondary);">Jatuh Tempo</p>
                                    <p class="font-semibold {{ $disposition->due_date->isPast() ? 'text-red-500' : '' }}" style="{{ !$disposition->due_date->isPast() ? 'color: var(--text-primary);' : '' }}">{{ $disposition->due_date->translatedFormat('d M Y') }}</p>
                                    <p class="text-xs {{ $disposition->due_date->isPast() ? 'text-red-500' : '' }}" style="{{ !$disposition->due_date->isPast() ? 'color: var(--text-secondary);' : '' }}">{{ $disposition->due_date->isPast() ? 'Terlambat!' : $disposition->due_date->diffForHumans() }}</p>
                                </div>
                                <div class="col-span-2">
                                    <p class="text-xs uppercase font-medium mb-1" style="color: var(--text-secondary);">Instruksi</p>
                                    <p style="color: var(--text-primary);">{{ Str::limit($disposition->content, 100) }}</p>
                                </div>
                                @if($disposition->note)
                                <div class="col-span-2">
                                    <p class="text-xs uppercase font-medium mb-1" style="color: var(--text-secondary);">Catatan</p>
                                    <p class="p-2 rounded text-sm" style="background-color: var(--bg-card); color: var(--text-secondary);">{{ Str::limit($disposition->note, 60) }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        <!-- Footer -->
                        <div class="px-4 py-3 flex items-center justify-between border-t" style="border-color: var(--border-color); background-color: var(--bg-card);">
                            <span class="text-xs" style="color: var(--text-secondary);">oleh {{ $disposition->user->name }}</span>
                            <div class="flex items-center gap-1">
                                <button onclick="openModal('modal-disp-{{ $disposition->id }}')" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs" style="background-color: var(--bg-input); color: var(--text-secondary);"><i class="bx bx-show"></i> Lihat</button>
                                @if(auth()->user()->isAdmin() || $disposition->user_id === auth()->id())
                                <a href="{{ route('disposition.edit', $disposition) }}" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs" style="background-color: var(--bg-input); color: var(--text-secondary);"><i class="bx bx-edit"></i> Edit</a>
                                <form id="delete-disp-{{ $disposition->id }}" action="{{ route('disposition.destroy', $disposition) }}" method="POST" class="inline">@csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete('delete-disp-{{ $disposition->id }}')" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs bg-red-50 text-red-600 hover:bg-red-100"><i class="bx bx-trash"></i> Hapus</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 rounded-xl" style="background-color: var(--bg-input);">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-full flex items-center justify-center" style="background-color: var(--bg-card);">
                        <i class="bx bx-transfer text-3xl" style="color: var(--text-secondary);"></i>
                    </div>
                    <p class="font-medium" style="color: var(--text-primary);">Belum ada disposisi</p>
                    <p class="text-sm" style="color: var(--text-secondary);">Klik "Tambah Disposisi" untuk membuat instruksi baru</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Right: Attachments & Info -->
        <div class="space-y-6">
            <!-- Attachments -->
            <div class="rounded-xl p-6 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <h2 class="text-lg font-semibold mb-4" style="color: var(--text-primary);"><i class="bx bx-paperclip mr-2"></i>Lampiran</h2>
                @if($letter->attachments->count() > 0)
                <div class="space-y-2">
                    @foreach($letter->attachments as $index => $attachment)
                    <div class="flex items-center justify-between p-3 rounded-lg" style="background-color: var(--bg-input);">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            @if(in_array($attachment->extension, ['jpg','jpeg','png','gif']))
                            <img src="{{ Storage::url($attachment->full_path) }}" class="w-12 h-12 rounded-lg object-cover cursor-pointer hover:opacity-80" onclick="openAttachmentGallery({{ $index }})" alt="{{ $attachment->filename }}">
                            @else
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center cursor-pointer hover:opacity-80 {{ $attachment->extension == 'pdf' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}" onclick="openAttachmentGallery({{ $index }})">
                                <i class="bx {{ $attachment->extension == 'pdf' ? 'bxs-file-pdf' : 'bx-file' }} text-2xl"></i>
                            </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate" style="color: var(--text-primary);">{{ $attachment->filename }}</p>
                                <p class="text-xs uppercase" style="color: var(--text-secondary);">{{ $attachment->extension }}</p>
                            </div>
                        </div>
                        <div class="flex gap-1 ml-2">
                            <button onclick="openAttachmentGallery({{ $index }})" class="p-2 rounded-lg hover:opacity-70" style="background-color: var(--bg-card); color: var(--text-secondary);" title="Preview">
                                <i class="bx bx-show"></i>
                            </button>
                            <a href="{{ Storage::url($attachment->full_path) }}" target="_blank" class="p-2 rounded-lg text-sm btn-primary" title="Download">
                                <i class="bx bx-download"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @push('scripts')
                <script>
                    const attachmentFiles = [
                        @foreach($letter->attachments as $att)
                        { url: '{{ Storage::url($att->full_path) }}', name: '{{ $att->filename }}' },
                        @endforeach
                    ];
                    function openAttachmentGallery(index) {
                        openGallery(attachmentFiles, index);
                    }
                </script>
                @endpush
                @else
                <p class="text-center py-6" style="color: var(--text-secondary);">
                    <i class="bx bx-file text-4xl mb-2 block opacity-50"></i>
                    Tidak ada lampiran
                </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Correspondence Chain -->
    @if($letter->replies->count() > 0)
    <div class="rounded-xl p-5 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <h2 class="text-lg font-semibold mb-1" style="color: var(--text-primary);"><i class="bx bx-git-branch mr-2"></i>Korespondensi</h2>
        <p class="text-sm mb-4" style="color: var(--text-secondary);">Riwayat surat dan balasannya • 🟣 Surat Masuk → 🟢 Surat Keluar</p>
        <div class="flex items-center gap-2 flex-wrap">
            @foreach($correspondenceChain as $index => $chainLetter)
                @if($index > 0)<i class="bx bx-right-arrow-alt text-xl" style="color: var(--text-secondary);"></i>@endif
                <a href="{{ route($chainLetter->isIncoming() ? 'incoming.show' : 'outgoing.show', $chainLetter) }}" 
                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg {{ $chainLetter->id === $letter->id ? 'ring-2 ring-blue-500' : '' }}" 
                   style="background-color: var(--bg-input);" title="{{ $chainLetter->isIncoming() ? 'Surat Masuk' : 'Surat Keluar' }}">
                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-sm font-medium {{ $chainLetter->isIncoming() ? 'bg-purple-500' : 'bg-green-500' }}">{{ $index + 1 }}</span>
                    <div>
                        <p class="text-sm font-medium" style="color: var(--text-primary);">{{ $chainLetter->reference_number }}</p>
                        <p class="text-xs" style="color: var(--text-secondary);">{{ $chainLetter->letter_date?->translatedFormat('d M Y') }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Modal Create Disposition -->
<div id="modal-create-disposition-backdrop" class="modal-backdrop"></div>
<div id="modal-create-disposition" class="modal-content w-full max-w-2xl">
    <div class="rounded-2xl shadow-2xl border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color: var(--border-color);">
            <div>
                <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Buat Disposisi</h3>
                <p class="text-sm" style="color: var(--text-secondary);">Surat: {{ $letter->reference_number }}</p>
            </div>
            <button onclick="closeModal('modal-create-disposition')" class="p-1 rounded-lg hover:opacity-70" style="color: var(--text-secondary);"><i class="bx bx-x text-2xl"></i></button>
        </div>
        <form action="{{ route('disposition.store', $letter) }}" method="POST" class="p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Kepada/Tujuan *</label>
                    <input type="text" name="to" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);" placeholder="Nama tujuan disposisi">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Jatuh Tempo *</label>
                    <input type="date" name="due_date" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Status *</label>
                    <select name="letter_status" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                        <option value="">Pilih Status</option>
                        @foreach($statuses ?? [] as $s)<option value="{{ $s->id }}">{{ $s->status }}</option>@endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Isi Disposisi *</label>
                    <textarea name="content" rows="3" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);" placeholder="Instruksi disposisi..."></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Catatan</label>
                    <textarea name="note" rows="2" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('modal-create-disposition')" class="px-4 py-2 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-lg font-medium btn-primary"><i class="bx bx-save mr-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Disposition Detail Modals -->
@foreach($letter->dispositions as $disposition)
<div id="modal-disp-{{ $disposition->id }}-backdrop" class="modal-backdrop"></div>
<div id="modal-disp-{{ $disposition->id }}" class="modal-content w-full max-w-3xl">
    <div class="rounded-2xl shadow-2xl overflow-hidden border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <!-- Header -->
        <div class="px-6 py-4 border-b" style="border-color: var(--border-color);">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Detail Disposisi</h3>
                    <span class="px-2 py-1 text-xs font-semibold rounded {{ $disposition->status?->status === 'Selesai' ? 'bg-green-100 text-green-700' : ($disposition->status?->status === 'Urgent' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ $disposition->status?->status }}</span>
                </div>
                <button onclick="closeModal('modal-disp-{{ $disposition->id }}')" class="p-2 rounded-lg hover:opacity-70" style="color: var(--text-secondary);"><i class="bx bx-x text-xl"></i></button>
            </div>
        </div>
        
        <!-- Body dengan grid - compact -->
        <div class="p-4">
            <div class="grid grid-cols-3 gap-3">
                <div class="p-3 rounded-lg" style="background-color: var(--bg-input);">
                    <p class="text-xs uppercase font-medium mb-1" style="color: var(--text-secondary);">Kepada</p>
                    <p class="font-semibold" style="color: var(--text-primary);">{{ $disposition->to }}</p>
                </div>
                <div class="p-3 rounded-lg" style="background-color: var(--bg-input);">
                    <p class="text-xs uppercase font-medium mb-1" style="color: var(--text-secondary);">Dibuat</p>
                    <p class="font-medium text-sm" style="color: var(--text-primary);">{{ $disposition->created_at->translatedFormat('d M Y, H:i') }}</p>
                </div>
                <div class="p-3 rounded-lg {{ $disposition->due_date->isPast() ? 'bg-red-50' : '' }}" style="{{ !$disposition->due_date->isPast() ? 'background-color: var(--bg-input);' : '' }}">
                    <p class="text-xs uppercase font-medium mb-1 {{ $disposition->due_date->isPast() ? 'text-red-600' : '' }}" style="{{ !$disposition->due_date->isPast() ? 'color: var(--text-secondary);' : '' }}">Jatuh Tempo</p>
                    <p class="font-medium text-sm {{ $disposition->due_date->isPast() ? 'text-red-600' : '' }}" style="{{ !$disposition->due_date->isPast() ? 'color: var(--text-primary);' : '' }}">{{ $disposition->due_date->translatedFormat('d M Y') }}</p>
                    <p class="text-xs {{ $disposition->due_date->isPast() ? 'text-red-500' : 'text-green-600' }}">{{ $disposition->due_date->isPast() ? 'Terlambat!' : $disposition->due_date->diffForHumans() }}</p>
                </div>
                <div class="col-span-3">
                    <p class="text-xs uppercase font-medium mb-1" style="color: var(--text-secondary);">Instruksi</p>
                    <p class="p-3 rounded-lg" style="background-color: var(--bg-input); color: var(--text-primary);">{{ $disposition->content }}</p>
                </div>
                @if($disposition->note)
                <div class="col-span-3">
                    <p class="text-xs uppercase font-medium mb-1" style="color: var(--text-secondary);">Catatan</p>
                    <p class="p-3 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);">{{ $disposition->note }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 border-t flex items-center justify-between" style="border-color: var(--border-color); background-color: var(--bg-input);">
            <div class="flex items-center gap-2">
                @if(auth()->user()->isAdmin() || $disposition->user_id === auth()->id())
                <a href="{{ route('disposition.edit', $disposition) }}" class="inline-flex items-center gap-1 px-3 py-2 rounded-lg text-sm" style="background-color: var(--bg-card); color: var(--text-secondary);"><i class="bx bx-edit"></i> Edit</a>
                @endif
            </div>
            <button onclick="closeModal('modal-disp-{{ $disposition->id }}')" class="px-4 py-2 rounded-lg text-sm btn-primary">Tutup</button>
        </div>
    </div>
</div>
@endforeach
@endsection
