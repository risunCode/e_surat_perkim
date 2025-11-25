@extends('layouts.app')
@section('title', 'Edit Surat Masuk')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('incoming.show', $letter) }}" class="inline-flex items-center gap-1 mb-2 hover:opacity-70" style="color: var(--text-secondary);">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Edit Surat Masuk</h1>
            <p style="color: var(--text-secondary);">{{ $letter->reference_number }}</p>
        </div>
    </div>

    <form action="{{ route('incoming.update', $letter) }}" method="POST" enctype="multipart/form-data" class="rounded-xl p-6 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Nomor Surat *</label>
                <input type="text" name="reference_number" value="{{ old('reference_number', $letter->reference_number) }}" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Nomor Agenda</label>
                <input type="text" name="agenda_number" value="{{ old('agenda_number', $letter->agenda_number) }}" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Klasifikasi *</label>
                <select name="classification_code" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                    @foreach($classifications as $c)<option value="{{ $c->code }}" {{ $letter->classification_code == $c->code ? 'selected' : '' }}>{{ $c->code }} - {{ $c->type }}</option>@endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Dari/Pengirim *</label>
                <input type="text" name="from" value="{{ old('from', $letter->from) }}" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Tanggal Surat *</label>
                <input type="date" name="letter_date" value="{{ old('letter_date', $letter->letter_date?->format('Y-m-d')) }}" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Tanggal Diterima *</label>
                <input type="date" name="received_date" value="{{ old('received_date', $letter->received_date?->format('Y-m-d')) }}" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Lampiran Baru</label>
                <input type="file" name="attachments[]" id="edit-attachments" multiple accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif" class="w-full px-4 py-2 rounded-lg border text-sm" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);" onchange="previewMultipleFiles(this, 'preview-edit')">
                <p class="text-xs mt-1" style="color: var(--text-secondary);">
                    Maks 15MB total • Multiple files • Mengganti lampiran lama
                </p>
                <div id="preview-edit" class="mt-2"></div>
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Perihal</label>
                <textarea name="description" rows="2" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">{{ old('description', $letter->description) }}</textarea>
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Catatan</label>
                <textarea name="note" rows="2" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">{{ old('note', $letter->note) }}</textarea>
            </div>
        </div>
        <div class="flex gap-3 mt-6 pt-4 border-t" style="border-color: var(--border-color);">
            <button type="submit" class="px-6 py-2 rounded-lg font-medium btn-primary"><i class="bx bx-save mr-1"></i> Simpan</button>
            <a href="{{ route('incoming.show', $letter) }}" class="px-4 py-2 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);">Batal</a>
        </div>
    </form>
</div>
@endsection
