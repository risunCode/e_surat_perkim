@extends('layouts.app')
@section('title', 'Buat Disposisi')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('incoming.show', $letter) }}" class="inline-flex items-center gap-1 text-gray-400 hover:text-white mb-2"><i class="bx bx-arrow-back"></i> Kembali</a>
        <h1 class="text-2xl font-bold text-white">Buat Disposisi</h1>
        <p class="text-gray-400">Untuk surat: {{ $letter->reference_number }}</p>
    </div>
    <form action="{{ route('disposition.store', $letter) }}" method="POST" class="bg-neutral-900 border border-neutral-800 rounded-xl p-6 space-y-6">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Kepada/Tujuan Disposisi *</label>
                <input type="text" name="to" value="{{ old('to') }}" required class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white/20" placeholder="Nama tujuan disposisi">
                @error('to')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Tanggal Jatuh Tempo *</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}" required class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Status *</label>
                    <select name="letter_status" required class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/20">
                        <option value="">Pilih Status</option>
                        @foreach($statuses as $s)<option value="{{ $s->id }}" {{ old('letter_status') == $s->id ? 'selected' : '' }}>{{ $s->status }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Isi Disposisi *</label>
                <textarea name="content" rows="4" required class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white/20" placeholder="Isi/instruksi disposisi">{{ old('content') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Catatan</label>
                <textarea name="note" rows="2" class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white/20">{{ old('note') }}</textarea>
            </div>
        </div>
        <div class="flex gap-3 pt-4 border-t border-neutral-800">
            <button type="submit" class="px-6 py-2 bg-white text-black rounded-lg hover:bg-gray-200 font-medium">Simpan</button>
            <a href="{{ route('incoming.show', $letter) }}" class="px-6 py-2 bg-neutral-800 text-white rounded-lg hover:bg-neutral-700">Batal</a>
        </div>
    </form>
</div>
@endsection
