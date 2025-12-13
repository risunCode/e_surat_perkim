@extends('layouts.app')

@section('title', 'Attack Alerts')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class='bx bx-shield-x text-red-500'></i> Attack Alerts
            </h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Monitor dan kelola peringatan serangan</p>
        </div>
        
        {{-- Bulk Actions Dropdown --}}
        <div class="flex items-center gap-2">
            @if($stats['active'] > 0 || $stats['mitigated'] > 0)
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                    <i class='bx bx-cog'></i> Bulk Actions
                    <i class='bx bx-chevron-down'></i>
                </button>
                <div x-show="open" @click.away="open = false" 
                     class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                    @if($stats['active'] > 0)
                    <form action="{{ route('admin.attack-alerts.mitigate-all') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2"
                            onclick="return confirm('Tandai semua alert aktif sebagai mitigated?')">
                            <i class='bx bx-wrench text-yellow-500'></i> Mitigate All ({{ $stats['active'] }})
                        </button>
                    </form>
                    @endif
                    
                    @if($stats['active'] > 0 || $stats['mitigated'] > 0)
                    <form action="{{ route('admin.attack-alerts.archive-all') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2"
                            onclick="return confirm('Archive semua alert yang belum di-archive?')">
                            <i class='bx bx-archive-in text-green-500'></i> Archive All
                        </button>
                    </form>
                    @endif
                    
                    @if($stats['archived'] > 0)
                    <div class="border-t border-gray-200 dark:border-gray-700"></div>
                    <form action="{{ route('admin.attack-alerts.delete-archived') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 text-left text-sm hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 flex items-center gap-2"
                            onclick="return confirm('HAPUS PERMANEN semua alert yang sudah di-archive? Aksi ini tidak dapat dibatalkan!')">
                            <i class='bx bx-trash'></i> Delete Archived ({{ $stats['archived'] }})
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                    <i class='bx bx-shield text-gray-500 text-xl'></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                    <p class="text-xs text-gray-500">Total</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-red-200 dark:border-red-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <i class='bx bx-error text-red-500 text-xl'></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['active'] }}</p>
                    <p class="text-xs text-gray-500">Active</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-yellow-200 dark:border-yellow-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                    <i class='bx bx-wrench text-yellow-500 text-xl'></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['mitigated'] }}</p>
                    <p class="text-xs text-gray-500">Mitigated</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-green-200 dark:border-green-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <i class='bx bx-archive-in text-green-500 text-xl'></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['archived'] }}</p>
                    <p class="text-xs text-gray-500">Archived</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <i class='bx bx-calendar text-blue-500 text-xl'></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['today'] }}</p>
                    <p class="text-xs text-gray-500">Today</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-orange-200 dark:border-orange-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                    <i class='bx bx-bomb text-orange-500 text-xl'></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-orange-600">{{ $stats['critical'] }}</p>
                    <p class="text-xs text-gray-500">Critical</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <select name="type" class="bg-gray-100 dark:bg-gray-700 border-0 rounded-lg text-sm">
                <option value="">All Types</option>
                <option value="xss" {{ request('type') == 'xss' ? 'selected' : '' }}>XSS</option>
                <option value="sqli" {{ request('type') == 'sqli' ? 'selected' : '' }}>SQL Injection</option>
                <option value="injection" {{ request('type') == 'injection' ? 'selected' : '' }}>Command Injection</option>
                <option value="traversal" {{ request('type') == 'traversal' ? 'selected' : '' }}>Path Traversal</option>
            </select>
            <select name="severity" class="bg-gray-100 dark:bg-gray-700 border-0 rounded-lg text-sm">
                <option value="">All Severity</option>
                <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                <option value="high" {{ request('severity') == 'high' ? 'selected' : '' }}>High</option>
                <option value="medium" {{ request('severity') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="low" {{ request('severity') == 'low' ? 'selected' : '' }}>Low</option>
            </select>
            <select name="status" class="bg-gray-100 dark:bg-gray-700 border-0 rounded-lg text-sm">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="mitigated" {{ request('status') == 'mitigated' ? 'selected' : '' }}>Mitigated</option>
                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm">
                <i class='bx bx-filter'></i> Filter
            </button>
            <a href="{{ route('admin.attack-alerts.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">Reset</a>
        </form>
    </div>

    {{-- Alerts Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Severity</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Target</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($alerts as $alert)
                    <tr class="@if($alert->isActive()) bg-red-50 dark:bg-red-900/10 @elseif($alert->isMitigated()) bg-yellow-50 dark:bg-yellow-900/10 @endif">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <i class='bx {{ $alert->attack_icon }} text-lg'></i>
                                <span class="font-medium uppercase text-sm">{{ $alert->attack_type }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($alert->severity == 'critical') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                @elseif($alert->severity == 'high') bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400
                                @elseif($alert->severity == 'medium') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                @else bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                @endif">
                                {{ strtoupper($alert->severity) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-mono text-sm">{{ $alert->ip_address }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate">{{ $alert->target_url }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $alert->created_at->diffForHumans() }}</td>
                        <td class="px-4 py-3">
                            @if($alert->isActive())
                            <span class="text-red-600 text-sm font-medium flex items-center gap-1">
                                <i class='bx bx-error-circle'></i> Active
                            </span>
                            @elseif($alert->isMitigated())
                            <span class="text-yellow-600 text-sm font-medium flex items-center gap-1">
                                <i class='bx bx-wrench'></i> Mitigated
                            </span>
                            @else
                            <span class="text-green-600 text-sm flex items-center gap-1">
                                <i class='bx bx-check'></i> Archived
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.attack-alerts.show', $alert) }}" 
                                   class="p-1.5 text-gray-500 hover:text-primary-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded" title="View">
                                    <i class='bx bx-show'></i>
                                </a>
                                
                                @if($alert->isActive())
                                <form action="{{ route('admin.attack-alerts.mitigate', $alert) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-gray-500 hover:text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded" title="Mark as Mitigated">
                                        <i class='bx bx-wrench'></i>
                                    </button>
                                </form>
                                @endif
                                
                                @if(!$alert->isArchived())
                                <form action="{{ route('admin.attack-alerts.archive', $alert) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-gray-500 hover:text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded" title="Archive">
                                        <i class='bx bx-archive-in'></i>
                                    </button>
                                </form>
                                @endif
                                
                                <form action="{{ route('admin.attack-alerts.destroy', $alert) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded" 
                                            title="Delete" onclick="return confirm('Hapus alert ini?')">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            <i class='bx bx-shield-alt-2 text-4xl mb-2'></i>
                            <p>Tidak ada attack alerts</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $alerts->links() }}
    </div>
</div>
@endsection
