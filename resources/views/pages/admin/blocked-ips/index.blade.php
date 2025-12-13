@extends('layouts.app')

@section('title', 'Blocked IPs')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class='bx bx-block text-red-500'></i> Blocked IPs
            </h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Kelola IP yang diblokir dari sistem</p>
        </div>
        
        <div class="flex items-center gap-2">
            <button onclick="openModal('addIpModal')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                <i class='bx bx-plus'></i> Block IP
            </button>
            @if($stats['total'] > 0)
            <form action="{{ route('admin.blocked-ips.unblock-all') }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2"
                    onclick="return confirm('Unblock semua IP?')">
                    <i class='bx bx-reset'></i> Unblock All
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <i class='bx bx-block text-red-500 text-xl'></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                    <p class="text-xs text-gray-500">Total Blocked</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-orange-200 dark:border-orange-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                    <i class='bx bx-time text-orange-500 text-xl'></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-orange-600">{{ $stats['temporary'] }}</p>
                    <p class="text-xs text-gray-500">Temporary (3 days)</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-purple-200 dark:border-purple-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <i class='bx bx-lock text-purple-500 text-xl'></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['permanent'] }}</p>
                    <p class="text-xs text-gray-500">Permanent</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Search --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <form method="GET" class="flex items-center gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari IP address..."
                class="flex-1 bg-gray-100 dark:bg-gray-700 border-0 rounded-lg text-sm">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm">
                <i class='bx bx-search'></i> Cari
            </button>
            @if(request('search'))
            <a href="{{ route('admin.blocked-ips.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reason</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Blocked By</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expires</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($blockedIps as $blocked)
                    <tr>
                        <td class="px-4 py-3 font-mono font-semibold text-gray-900 dark:text-white">
                            {{ $blocked->ip_address }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate">
                            {{ $blocked->reason ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            @if($blocked->is_permanent)
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                Permanent
                            </span>
                            @else
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                                3 Days
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                            {{ $blocked->blockedByUser?->name ?? 'System' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            @if($blocked->is_permanent)
                                <span class="text-purple-600">Never</span>
                            @elseif($blocked->blocked_until)
                                {{ $blocked->blocked_until->diffForHumans() }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <form action="{{ route('admin.blocked-ips.destroy', $blocked) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 text-sm bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900/30 dark:text-green-400 rounded-lg"
                                    onclick="return confirm('Unblock IP {{ $blocked->ip_address }}?')">
                                    <i class='bx bx-check'></i> Unblock
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <i class='bx bx-check-shield text-4xl mb-2'></i>
                            <p>Tidak ada IP yang diblokir</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $blockedIps->links() }}
    </div>
</div>

{{-- Add IP Modal --}}
<div id="addIpModal-backdrop" class="modal-backdrop"></div>
<div id="addIpModal" class="modal-content bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Block IP Address</h3>
    </div>
    <form action="{{ route('admin.blocked-ips.store') }}" method="POST">
        @csrf
        <div class="p-4 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">IP Address</label>
                <input type="text" name="ip_address" required placeholder="192.168.1.1"
                    class="w-full bg-gray-100 dark:bg-gray-700 border-0 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason (optional)</label>
                <input type="text" name="reason" placeholder="Suspicious activity..."
                    class="w-full bg-gray-100 dark:bg-gray-700 border-0 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Duration</label>
                <select name="duration" class="w-full bg-gray-100 dark:bg-gray-700 border-0 rounded-lg">
                    <option value="3days">3 Days (auto-remove)</option>
                    <option value="permanent">Permanent</option>
                </select>
            </div>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-2">
            <button type="button" onclick="closeModal('addIpModal')" class="px-4 py-2 text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg text-sm">
                Batal
            </button>
            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">
                <i class='bx bx-block'></i> Block IP
            </button>
        </div>
    </form>
</div>
@endsection
