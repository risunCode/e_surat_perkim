@extends('layouts.app')
@section('title', 'Tambah Pengguna')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1 text-gray-400 hover:text-white mb-2"><i class="bx bx-arrow-back"></i> Kembali</a>
        <h1 class="text-2xl font-bold text-white">Tambah Pengguna</h1>
    </div>
    <form action="{{ route('admin.users.store') }}" method="POST" class="bg-neutral-900 border border-neutral-800 rounded-xl p-6 space-y-6">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Nama *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/20">
                @error('name')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/20">
                @error('email')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Role *</label>
                <select name="role" required class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/20">
                    @foreach($roles as $v => $l)<option value="{{ $v }}" {{ old('role') == $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Password *</label>
                <input type="password" name="password" required class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/20">
                @error('password')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Konfirmasi Password *</label>
                <input type="password" name="password_confirmation" required class="w-full px-4 py-2 bg-neutral-800 border border-neutral-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/20">
            </div>
        </div>
        <div class="flex gap-3 pt-4 border-t border-neutral-800">
            <button type="submit" class="px-6 py-2 bg-white text-black rounded-lg hover:bg-gray-200 font-medium">Simpan</button>
            <a href="{{ route('admin.users.index') }}" class="px-6 py-2 bg-neutral-800 text-white rounded-lg hover:bg-neutral-700">Batal</a>
        </div>
    </form>
</div>
@endsection
