@extends('layouts.app')
@section('title', 'Edit Disposisi')
@section('content')
<div class="space-y-6">
    <div>
        <a href="{{ route('incoming.show', $disposition->letter_id) }}" class="inline-flex items-center gap-1 mb-2 hover:opacity-70" style="color: var(--text-secondary);">
            <i class="bx bx-arrow-back"></i> Kembali
        </a>
        <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Edit Disposisi</h1>
        <p style="color: var(--text-secondary);">Surat: {{ $disposition->letter->reference_number ?? '' }}</p>
    </div>

    <form action="{{ route('disposition.update', $disposition) }}" method="POST" class="rounded-xl p-6 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Kepada/Tujuan *</label>
                <input type="text" name="to" value="{{ old('to', $disposition->to) }}" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Jatuh Tempo *</label>
                <input type="date" name="due_date" value="{{ old('due_date', $disposition->due_date->format('Y-m-d')) }}" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Status *</label>
                <select name="letter_status" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                    @foreach($statuses as $s)<option value="{{ $s->id }}" {{ $disposition->letter_status == $s->id ? 'selected' : '' }}>{{ $s->status }}</option>@endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Isi Disposisi *</label>
                <textarea name="content" rows="3" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">{{ old('content', $disposition->content) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Catatan</label>
                <textarea name="note" rows="2" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">{{ old('note', $disposition->note) }}</textarea>
            </div>
        </div>
        <div class="flex gap-3 mt-6 pt-4 border-t" style="border-color: var(--border-color);">
            <button type="submit" class="px-6 py-2 rounded-lg font-medium btn-primary"><i class="bx bx-save mr-1"></i> Simpan</button>
            <a href="{{ route('incoming.show', $disposition->letter_id) }}" class="px-4 py-2 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);">Batal</a>
        </div>
    </form>
</div>
@endsection
