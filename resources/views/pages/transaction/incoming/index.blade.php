@extends('layouts.app')

@section('title', 'Surat Masuk')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Surat Masuk</h1>
            <p style="color: var(--text-secondary);">Kelola semua surat masuk</p>
        </div>
        <button onclick="openModal('modal-create-incoming')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium btn-primary">
            <i class="bx bx-plus"></i>
            Tambah Surat
        </button>
    </div>
    
    <!-- Filters -->
    <div class="rounded-xl p-4 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <form method="GET" class="space-y-3">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="md:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor surat, pengirim..." class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
                <select name="classification" aria-label="Filter klasifikasi" class="px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                    <option value="">Semua Klasifikasi</option>
                    @foreach($classifications as $classification)
                        <option value="{{ $classification->code }}" {{ request('classification') == $classification->code ? 'selected' : '' }}>{{ $classification->type }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 rounded-lg btn-primary" aria-label="Cari"><i class="bx bx-search"></i> Cari</button>
            </div>
            
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="show_completed" value="1" 
                           {{ request('show_completed') ? 'checked' : '' }} 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                           onchange="this.form.submit()">
                    <span class="text-sm" style="color: var(--text-secondary);">
                        <i class="bx bx-check-circle mr-1"></i>Tampilkan surat selesai
                    </span>
                </label>
                @unless(request('show_completed'))
                <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-700">
                    <i class="bx bx-info-circle mr-1"></i>Surat selesai disembunyikan
                </span>
                @endunless
            </div>
        </form>
    </div>
    
    <!-- Table -->
    <div class="rounded-xl overflow-hidden border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead style="background-color: var(--bg-input);">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase w-12" style="color: var(--text-secondary);">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">No. Surat</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Dari</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Lampiran</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase" style="color: var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--border-color);">
                    @forelse(isset($letters->showAll) ? $letters->items : $letters as $index => $letter)
                    <tr class="hover:opacity-80">
                        <td class="px-4 py-3 text-center" style="color: var(--text-secondary);">
                            {{ isset($letters->showAll) ? $loop->iteration : $letters->firstItem() + $loop->index }}
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-medium" style="color: var(--text-primary);">{{ $letter->reference_number }}</p>
                            <p class="text-xs" style="color: var(--text-secondary);">{{ $letter->agenda_number }}</p>
                        </td>
                        <td class="px-4 py-3" style="color: var(--text-secondary);">{{ $letter->from }}</td>
                        <td class="px-4 py-3" style="color: var(--text-secondary);">{{ $letter->letter_date?->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            @if($letter->is_completed)
                                <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700">
                                    <i class="bx bx-check-circle mr-0.5"></i>Selesai
                                </span>
                            @else
                                <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700">
                                    <i class="bx bx-time mr-0.5"></i>Belum Selesai
                                </span>
                            @endif
                            
                            <div class="flex flex-wrap gap-1 mt-1">
                                @if($letter->replies->count() > 0)
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-purple-100 text-purple-700"><i class="bx bx-check mr-0.5"></i>Dibalas</span>
                                @endif
                                @if($letter->dispositions->count() > 0)
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-orange-100 text-orange-700"><i class="bx bx-transfer mr-0.5"></i>{{ $letter->dispositions->count() }} Disposisi</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <x-attachment-preview :attachments="$letter->attachments" />
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('incoming.show', $letter) }}" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs" style="background-color: var(--bg-input); color: var(--text-secondary);">
                                    <i class="bx bx-show"></i> Lihat
                                </a>
                                @if(auth()->user()->isAdmin() || $letter->user_id === auth()->id())
                                <a href="{{ route('incoming.edit', $letter) }}" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs" style="background-color: var(--bg-input); color: var(--text-secondary);">
                                    <i class="bx bx-edit"></i> Edit
                                </a>
                                <form id="delete-form-{{ $letter->id }}" action="{{ route('incoming.destroy', $letter) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete('delete-form-{{ $letter->id }}')" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs bg-red-100 text-red-600 hover:bg-red-200">
                                        <i class="bx bx-trash"></i> Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center" style="color: var(--text-secondary);">
                            <i class="bx bx-inbox text-4xl mb-2"></i>
                            <p>Belum ada surat masuk</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @include('components.pagination', [
            'paginator' => $letters, 
            'totalCount' => isset($letters->totalCount) ? $letters->totalCount : null
        ])
    </div>
</div>

<!-- Modal Create Incoming Letter -->
<div id="modal-create-incoming-backdrop" class="modal-backdrop"></div>
<div id="modal-create-incoming" class="modal-content w-full max-w-5xl">
    <div class="rounded-2xl shadow-2xl border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color: var(--border-color);">
            <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Tambah Surat Masuk</h3>
            <button onclick="closeModal('modal-create-incoming')" class="p-1 rounded-lg hover:opacity-70" style="color: var(--text-secondary);">
                <i class="bx bx-x text-2xl"></i>
            </button>
        </div>
        <form action="{{ route('incoming.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Nomor Surat *</label>
                    <input type="text" name="reference_number" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);" placeholder="001/SK/2024">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Nomor Agenda</label>
                    <input type="text" name="agenda_number" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Klasifikasi *</label>
                    <select name="classification_code" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                        <option value="">Pilih Klasifikasi</option>
                        @foreach($classifications as $c)<option value="{{ $c->code }}">{{ $c->code }} - {{ $c->type }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Dari/Pengirim *</label>
                    <input type="text" name="from" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);" placeholder="Nama pengirim">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Tanggal Surat *</label>
                    <input type="date" name="letter_date" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Tanggal Diterima *</label>
                    <input type="date" name="received_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Perihal</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);" placeholder="Isi perihal surat..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Lampiran</label>
                    <input type="file" name="attachments[]" id="attachments-incoming" multiple accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif" class="w-full px-4 py-2 rounded-lg border text-sm" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);" onchange="previewMultipleFiles(this, 'preview-incoming')">
                    <p class="text-xs mt-1" style="color: var(--text-secondary);">Maks 15MB total • Support Multi-File • .pdf,.doc,.docx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif</p>
                </div>
                <div class="md:col-span-3" id="preview-incoming"></div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('modal-create-incoming')" class="px-4 py-2 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-lg font-medium btn-primary">
                    <i class="bx bx-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
