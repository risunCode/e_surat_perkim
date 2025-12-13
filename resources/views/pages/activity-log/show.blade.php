@extends('layouts.app')
@section('title', 'Detail Aktivitas')
@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('activity-log.index') }}" class="p-2 rounded-lg border hover:opacity-80" 
            style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-secondary);">
            <i class="bx bx-arrow-back text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Detail Aktivitas</h1>
            <p style="color: var(--text-secondary);">Log ID: #{{ $activityLog->id }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Activity Info -->
            <div class="rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <h2 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Informasi Aktivitas</h2>
                
                <div class="flex items-start gap-4 mb-6">
                    @php
                        $bgColor = match($activityLog->action) {
                            'create' => 'bg-green-100 text-green-600',
                            'update' => 'bg-yellow-100 text-yellow-600',
                            'delete' => 'bg-red-100 text-red-600',
                            'toggle_completion' => 'bg-blue-100 text-blue-600',
                            default => 'bg-gray-100 text-gray-600',
                        };
                    @endphp
                    <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $bgColor }}">
                        <i class="bx {{ $activityLog->action_icon }} text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-lg font-medium" style="color: var(--text-primary);">{{ $activityLog->description }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $bgColor }}">
                                {{ ucfirst(str_replace('_', ' ', $activityLog->action)) }}
                            </span>
                            <span class="text-xs px-2 py-0.5 rounded-full" style="background-color: var(--bg-input); color: var(--text-secondary);">
                                {{ $activityLog->model_type_label }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-wider mb-1" style="color: var(--text-secondary);">Pengguna</p>
                        <p class="font-medium" style="color: var(--text-primary);">{{ $activityLog->user_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wider mb-1" style="color: var(--text-secondary);">Waktu</p>
                        <p class="font-medium" style="color: var(--text-primary);">{{ $activityLog->created_at->format('d M Y, H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wider mb-1" style="color: var(--text-secondary);">IP Address</p>
                        <p class="font-medium" style="color: var(--text-primary);">{{ $activityLog->ip_address ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wider mb-1" style="color: var(--text-secondary);">Model ID</p>
                        <p class="font-medium" style="color: var(--text-primary);">#{{ $activityLog->model_id ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Data Changes -->
            @if($activityLog->old_values || $activityLog->new_values)
            <div class="rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <h2 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Perubahan Data</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($activityLog->old_values)
                    <div>
                        <p class="text-xs uppercase tracking-wider mb-2 flex items-center gap-1" style="color: var(--text-secondary);">
                            <i class="bx bx-minus-circle text-red-500"></i> Data Sebelum
                        </p>
                        <div class="rounded-lg p-3 text-sm overflow-x-auto" style="background-color: var(--bg-input);">
                            <pre style="color: var(--text-primary); white-space: pre-wrap; word-break: break-word;">{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                    @endif
                    
                    @if($activityLog->new_values)
                    <div>
                        <p class="text-xs uppercase tracking-wider mb-2 flex items-center gap-1" style="color: var(--text-secondary);">
                            <i class="bx bx-plus-circle text-green-500"></i> Data Sesudah
                        </p>
                        <div class="rounded-lg p-3 text-sm overflow-x-auto" style="background-color: var(--bg-input);">
                            <pre style="color: var(--text-primary); white-space: pre-wrap; word-break: break-word;">{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- User Agent -->
            @if($activityLog->user_agent)
            <div class="rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <h2 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Browser Info</h2>
                <p class="text-sm break-words" style="color: var(--text-secondary);">{{ $activityLog->user_agent }}</p>
            </div>
            @endif

            <!-- Quick Info -->
            <div class="rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <h2 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Info Cepat</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span style="color: var(--text-secondary);">Tipe Data</span>
                        <span class="font-medium" style="color: var(--text-primary);">{{ $activityLog->model_type_label }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span style="color: var(--text-secondary);">Aksi</span>
                        <span class="font-medium" style="color: var(--text-primary);">{{ ucfirst(str_replace('_', ' ', $activityLog->action)) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span style="color: var(--text-secondary);">Waktu Relatif</span>
                        <span class="font-medium" style="color: var(--text-primary);">{{ $activityLog->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
