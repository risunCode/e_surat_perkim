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
        @if($stats['active'] > 0)
        <form action="{{ route('admin.attack-alerts.dismiss-all') }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2"
                onclick="return confirm('Dismiss semua alert aktif?')">
                <i class='bx bx-check-double'></i> Dismiss All ({{ $stats['active'] }})
            </button>
        </form>
        @endif
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                    <i class='bx bx-shield text-gray-500 text-xl'></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                    <p class="text-xs text-gray-500">Total Alerts</p>
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
                    <i class='bx bx-calendar text-yellow-500 text-xl'></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['today'] }}</p>
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
                    <p class="text-xs text-gray-500">Critical Active</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <form method="GET" class="flex items-center gap-4">
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
                <option value="dismissed" {{ request('status') == 'dismissed' ? 'selected' : '' }}>Dismissed</option>
            </select>
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm">
                <i class='bx bx-filter'></i> Filter
            </button>
            <a href="{{ route('admin.attack-alerts.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">Reset</a>
        </form>
    </div>

    {{-- Alerts Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
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
                <tr class="{{ !$alert->is_dismissed ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
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
                        @if($alert->is_dismissed)
                        <span class="text-green-600 text-sm"><i class='bx bx-check'></i> Dismissed</span>
                        @else
                        <span class="text-red-600 text-sm font-medium"><i class='bx bx-error-circle'></i> Active</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.attack-alerts.show', $alert) }}" class="text-gray-500 hover:text-primary-600">
                                <i class='bx bx-show'></i>
                            </a>
                            @if(!$alert->is_dismissed)
                            <form action="{{ route('admin.attack-alerts.dismiss', $alert) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-500 hover:text-green-600" title="Dismiss">
                                    <i class='bx bx-check'></i>
                                </button>
                            </form>
                            @endif
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

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $alerts->links() }}
    </div>
</div>
@endsection
