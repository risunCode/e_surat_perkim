@extends('layouts.app')
@section('title', 'Tambah Kode Referensi')
@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.reference-codes.index') }}" class="inline-flex items-center gap-1 text-gray-400 hover:text-white mb-2"><i class="bx bx-arrow-back"></i> Kembali</a>
        <h1 class="text-2xl font-bold text-white">Tambah Kode Referensi</h1>
    </div>
    <form action="{{ route('admin.reference-codes.store') }}" method="POST" class="bg-neutral-900 border border-neutral-800 rounded-xl p-6 space-y-6">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Nama *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/20" placeholder="Contoh: Kode Registrasi Staff 2024">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Kode <span class="text-gray-500">(kosongkan untuk generate otomatis)</span></label>
                <input type="text" name="code" value="{{ old('code') }}" class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/20" placeholder="AUTO-GENERATE">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Role *</label>
                <select name="role" required class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/20">
                    @foreach($roles as $v => $l)<option value="{{ $v }}" {{ old('role') == $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Maksimal Penggunaan *</label>
                <input type="number" name="max_usage" value="{{ old('max_usage', 1) }}" min="0" required class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/20">
                <p class="mt-1 text-xs text-gray-500">0 = tidak terbatas</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Tanggal Kadaluarsa</label>
                <input type="date" name="expired_at" value="{{ old('expired_at') }}" class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/20">
            </div>
        </div>
        <div class="flex gap-3 pt-4 border-t border-neutral-800">
            <button type="submit" class="px-6 py-2 bg-white text-black rounded-lg hover:bg-gray-200 font-medium">Simpan</button>
            <a href="{{ route('admin.reference-codes.index') }}" class="px-6 py-2 bg-neutral-800 text-white rounded-lg hover:bg-neutral-700">Batal</a>
        </div>
    </form>
</div>
@endsection
