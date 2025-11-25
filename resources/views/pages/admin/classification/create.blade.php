@extends('layouts.app')
@section('title', 'Tambah Klasifikasi')
@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.classifications.index') }}" class="inline-flex items-center gap-1 text-gray-400 hover:text-white mb-2"><i class="bx bx-arrow-back"></i> Kembali</a>
        <h1 class="text-2xl font-bold text-white">Tambah Klasifikasi</h1>
    </div>
    <form action="{{ route('admin.classifications.store') }}" method="POST" class="bg-neutral-900 border border-neutral-800 rounded-xl p-6 space-y-6">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Kode *</label>
                <input type="text" name="code" value="{{ old('code') }}" required class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/20" placeholder="Contoh: 001">
                @error('code')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Tipe *</label>
                <input type="text" name="type" value="{{ old('type') }}" required class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/20" placeholder="Contoh: Umum">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/20">{{ old('description') }}</textarea>
            </div>
        </div>
        <div class="flex gap-3 pt-4 border-t border-neutral-800">
            <button type="submit" class="px-6 py-2 bg-white text-black rounded-lg hover:bg-gray-200 font-medium">Simpan</button>
            <a href="{{ route('admin.classifications.index') }}" class="px-6 py-2 bg-neutral-800 text-white rounded-lg hover:bg-neutral-700">Batal</a>
        </div>
    </form>
</div>
@endsection
