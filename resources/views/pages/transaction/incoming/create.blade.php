@extends('layouts.app')

@section('title', 'Tambah Surat Masuk')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('incoming.index') }}" class="inline-flex items-center gap-1 text-gray-400 hover:text-white mb-2">
            <i class="bx bx-arrow-back"></i>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-white">Tambah Surat Masuk</h1>
        <p class="text-gray-400">Isi form berikut untuk menambahkan surat masuk baru</p>
    </div>
    
    <!-- Form -->
    <form action="{{ route('incoming.store') }}" method="POST" enctype="multipart/form-data" class="bg-neutral-900 border border-neutral-800 rounded-xl p-6 space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Nomor Surat <span class="text-red-400">*</span></label>
                <input type="text" name="reference_number" value="{{ old('reference_number') }}" required
                    class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white/20"
                    placeholder="Contoh: 001/SK/2024">
                @error('reference_number')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Nomor Agenda</label>
                <input type="text" name="agenda_number" value="{{ old('agenda_number') }}"
                    class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white/20"
                    placeholder="Nomor agenda">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-300 mb-1">Dari/Pengirim <span class="text-red-400">*</span></label>
                <input type="text" name="from" value="{{ old('from') }}" required
                    class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white/20"
                    placeholder="Nama pengirim surat">
                @error('from')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Tanggal Surat <span class="text-red-400">*</span></label>
                <input type="date" name="letter_date" value="{{ old('letter_date') }}" required
                    class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/20">
                @error('letter_date')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Tanggal Diterima <span class="text-red-400">*</span></label>
                <input type="date" name="received_date" value="{{ old('received_date', date('Y-m-d')) }}" required
                    class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/20">
                @error('received_date')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-300 mb-1">Klasifikasi <span class="text-red-400">*</span></label>
                <select name="classification_code" required class="w-full px-3 py-2 rounded-lg border text-sm focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                    <option value="">Pilih Klasifikasi</option>
                    @foreach($classifications as $c)
                    <option value="{{ $c->code }}" {{ old('classification_code') == $c->code ? 'selected' : '' }}>
                        {{ $c->code }} - {{ $c->type }}
                    </option>
                    @endforeach
                </select>
                @error('classification_code')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-300 mb-1">Perihal/Deskripsi</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white/20"
                    placeholder="Perihal surat">{{ old('description') }}</textarea>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-300 mb-1">Catatan</label>
                <textarea name="note" rows="2"
                    class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white/20"
                    placeholder="Catatan tambahan">{{ old('note') }}</textarea>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-300 mb-1">Lampiran (PDF)</label>
                <input type="file" name="attachment" accept=".pdf"
                    class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white file:mr-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:bg-neutral-700 file:text-white hover:file:bg-neutral-600">
                <p class="mt-1 text-xs text-gray-500">Maksimal 10MB, format PDF</p>
                @error('attachment')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="flex items-center gap-3 pt-4 border-t border-neutral-800">
            <button type="submit" class="px-6 py-2 bg-white text-black rounded-lg hover:bg-gray-200 transition-colors font-medium">
                Simpan
            </button>
            <a href="{{ route('incoming.index') }}" class="px-6 py-2 bg-neutral-800 text-white rounded-lg hover:bg-neutral-700 transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
