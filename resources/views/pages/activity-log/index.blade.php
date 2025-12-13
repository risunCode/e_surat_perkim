@extends('layouts.app')
@section('title', 'Histori Aktivitas')
@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Histori Aktivitas</h1>
            <p style="color: var(--text-secondary);">Catatan semua aktivitas dalam sistem</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="rounded-xl p-4 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-green-100 text-green-600">
                    <i class="bx bx-plus-circle text-xl"></i>
                </div>
                <div>
                    <p class="text-xl font-bold" style="color: var(--text-primary);">{{ $logs->where('action', 'create')->count() }}</p>
                    <p class="text-xs" style="color: var(--text-secondary);">Ditambah</p>
                </div>
            </div>
        </div>
        <div class="rounded-xl p-4 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-yellow-100 text-yellow-600">
                    <i class="bx bx-edit text-xl"></i>
                </div>
                <div>
                    <p class="text-xl font-bold" style="color: var(--text-primary);">{{ $logs->where('action', 'update')->count() }}</p>
                    <p class="text-xs" style="color: var(--text-secondary);">Diedit</p>
                </div>
            </div>
        </div>
        <div class="rounded-xl p-4 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-red-100 text-red-600">
                    <i class="bx bx-trash text-xl"></i>
                </div>
                <div>
                    <p class="text-xl font-bold" style="color: var(--text-primary);">{{ $logs->where('action', 'delete')->count() }}</p>
                    <p class="text-xs" style="color: var(--text-secondary);">Dihapus</p>
                </div>
            </div>
        </div>
        <div class="rounded-xl p-4 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-blue-100 text-blue-600">
                    <i class="bx bx-history text-xl"></i>
                </div>
                <div>
                    <p class="text-xl font-bold" style="color: var(--text-primary);">{{ $logs->total() }}</p>
                    <p class="text-xs" style="color: var(--text-secondary);">Total Log</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="rounded-xl p-4 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aktivitas..." 
                    class="w-full px-4 py-2 rounded-lg border focus:outline-none" 
                    style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
            </div>
            <select name="action" class="px-4 py-2 rounded-lg border focus:outline-none" 
                style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                <option value="">Semua Aksi</option>
                @foreach($actions as $action)
                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $action)) }}
                </option>
                @endforeach
            </select>
            <select name="model_type" class="px-4 py-2 rounded-lg border focus:outline-none" 
                style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                <option value="">Semua Tipe</option>
                @foreach($modelTypes as $type)
                <option value="{{ $type }}" {{ request('model_type') == $type ? 'selected' : '' }}>
                    {{ $type == 'Letter' ? 'Surat' : ($type == 'Disposition' ? 'Disposisi' : ($type == 'Attachment' ? 'Lampiran' : $type)) }}
                </option>
                @endforeach
            </select>
            <select name="user_id" class="px-4 py-2 rounded-lg border focus:outline-none" 
                style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                <option value="">Semua Pengguna</option>
                @foreach($users as $user)
                <option value="{{ $user->user_id }}" {{ request('user_id') == $user->user_id ? 'selected' : '' }}>
                    {{ $user->user_name }}
                </option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 rounded-lg btn-primary">
                <i class="bx bx-search mr-1"></i> Cari
            </button>
            @if(request()->hasAny(['search', 'action', 'model_type', 'user_id', 'date_from', 'date_to']))
            <a href="{{ route('activity-log.index') }}" class="px-4 py-2 rounded-lg border" 
                style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-secondary);">
                <i class="bx bx-x mr-1"></i> Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Activity Timeline -->
    <div class="rounded-xl border overflow-hidden" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="divide-y" style="border-color: var(--border-color);">
            @forelse($logs as $log)
            <div class="p-4 hover:opacity-90 transition-opacity">
                <div class="flex items-start gap-4">
                    <!-- Action Icon -->
                    <div class="flex-shrink-0">
                        @php
                            $bgColor = match($log->action) {
                                'create' => 'bg-green-100 text-green-600',
                                'update' => 'bg-yellow-100 text-yellow-600',
                                'delete' => 'bg-red-100 text-red-600',
                                'toggle_completion' => 'bg-blue-100 text-blue-600',
                                default => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $bgColor }}">
                            <i class="bx {{ $log->action_icon }} text-xl"></i>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-medium" style="color: var(--text-primary);">{{ $log->user_name }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $bgColor }}">
                                {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                            </span>
                            <span class="text-xs px-2 py-0.5 rounded-full" style="background-color: var(--bg-input); color: var(--text-secondary);">
                                {{ $log->model_type_label }}
                            </span>
                        </div>
                        <p class="mt-1" style="color: var(--text-primary);">{{ $log->description }}</p>
                        <div class="mt-2 flex items-center gap-4 text-xs" style="color: var(--text-secondary);">
                            <span><i class="bx bx-time mr-1"></i>{{ $log->created_at->format('d M Y, H:i') }}</span>
                            @if($log->ip_address)
                            <span><i class="bx bx-globe mr-1"></i>{{ $log->ip_address }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Detail Button -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('activity-log.show', $log) }}" 
                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs border hover:opacity-80"
                            style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-secondary);">
                            <i class="bx bx-detail"></i> Detail
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center" style="color: var(--text-secondary);">
                <i class="bx bx-history text-4xl mb-2 block opacity-50"></i>
                <p>Belum ada aktivitas tercatat</p>
            </div>
            @endforelse
        </div>
        
        @if($logs->hasPages())
        <div class="px-4 py-3 border-t" style="border-color: var(--border-color);">
            {{ $logs->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
