@extends('layouts.app')
@section('title', 'Pengaturan Sistem')
@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Pengaturan Sistem</h1>
        <p style="color: var(--text-secondary);">Konfigurasi aplikasi</p>
    </div>
    <form action="{{ route('admin.settings.update') }}" method="POST" class="rounded-xl p-6 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($configs as $code => $config)
            <div>
                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">{{ $config['label'] }}</label>
                <input type="text" name="{{ $code }}" value="{{ old($code, $config['value']) }}" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
            </div>
            @endforeach
        </div>
        <div class="flex gap-3 pt-6 mt-6 border-t" style="border-color: var(--border-color);">
            <button type="submit" class="px-6 py-2 rounded-lg font-medium  btn-primary">
                <i class="bx bx-save mr-1"></i> Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection
