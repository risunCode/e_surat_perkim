@extends('layouts.app')
@section('title', 'Tambah Status')
@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.statuses.index') }}" class="inline-flex items-center gap-1 text-gray-400 hover:text-white mb-2"><i class="bx bx-arrow-back"></i> Kembali</a>
        <h1 class="text-2xl font-bold text-white">Tambah Status</h1>
    </div>
    <form action="{{ route('admin.statuses.store') }}" method="POST" class="bg-neutral-900 border border-neutral-800 rounded-xl p-6 space-y-6">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Nama Status *</label>
            <input type="text" name="status" value="{{ old('status') }}" required class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/20" placeholder="Contoh: Pending">
            @error('status')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
        </div>
        <div class="flex gap-3 pt-4 border-t border-neutral-800">
            <button type="submit" class="px-6 py-2 bg-white text-black rounded-lg hover:bg-gray-200 font-medium">Simpan</button>
            <a href="{{ route('admin.statuses.index') }}" class="px-6 py-2 bg-neutral-800 text-white rounded-lg hover:bg-neutral-700">Batal</a>
        </div>
    </form>
</div>
@endsection
