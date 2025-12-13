@extends('layouts.app')

@section('title', 'Attack Alert Detail')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.attack-alerts.index') }}" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-arrow-back text-xl'></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class='bx {{ $attackAlert->attack_icon }} text-red-500'></i>
                    {{ strtoupper($attackAlert->attack_type) }} Attack
                </h1>
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    Detected {{ $attackAlert->created_at->format('d M Y H:i:s') }}
                </p>
            </div>
        </div>
        
        {{-- Action Buttons --}}
        <div class="flex items-center gap-2">
            @if($attackAlert->isActive())
            <form action="{{ route('admin.attack-alerts.mitigate', $attackAlert) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                    <i class='bx bx-wrench'></i> Mark Mitigated
                </button>
            </form>
            @endif
            
            @if(!$attackAlert->isArchived())
            <form action="{{ route('admin.attack-alerts.archive', $attackAlert) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                    <i class='bx bx-archive-in'></i> Archive
                </button>
            </form>
            @endif
            
            <form action="{{ route('admin.attack-alerts.destroy', $attackAlert) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2"
                    onclick="return confirm('Hapus alert ini secara permanen?')">
                    <i class='bx bx-trash'></i> Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Attack Details --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-red-50 dark:bg-red-900/20">
                    <h2 class="font-semibold text-red-700 dark:text-red-400 flex items-center gap-2">
                        <i class='bx bx-error-circle'></i> Attack Details
                    </h2>
                </div>
                <div class="p-4 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-500">Attack Type</label>
                            <p class="font-semibold text-lg uppercase">{{ $attackAlert->attack_type }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Severity</label>
                            <p>
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    @if($attackAlert->severity == 'critical') bg-red-100 text-red-700
                                    @elseif($attackAlert->severity == 'high') bg-orange-100 text-orange-700
                                    @elseif($attackAlert->severity == 'medium') bg-yellow-100 text-yellow-700
                                    @else bg-blue-100 text-blue-700
                                    @endif">
                                    {{ strtoupper($attackAlert->severity) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Target URL</label>
                        <p class="font-mono text-sm bg-gray-100 dark:bg-gray-700 p-2 rounded break-all">{{ $attackAlert->target_url }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Target Field</label>
                        <p class="font-mono">{{ $attackAlert->target_field ?? '-' }}</p>
                    </div>
                    @if($attackAlert->payload)
                    <div>
                        <label class="text-xs text-gray-500">Payload</label>
                        <pre class="font-mono text-xs bg-red-50 dark:bg-red-900/20 p-3 rounded border border-red-200 dark:border-red-800 overflow-x-auto whitespace-pre-wrap">{{ $attackAlert->payload }}</pre>
                    </div>
                    @endif
                    @if($attackAlert->details)
                    <div>
                        <label class="text-xs text-gray-500">Details</label>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $attackAlert->details }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Attacker Info --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="font-semibold flex items-center gap-2">
                        <i class='bx bx-user-x'></i> Attacker Information
                    </h2>
                </div>
                <div class="p-4 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-500">IP Address</label>
                            <p class="font-mono text-lg font-semibold">{{ $attackAlert->ip_address }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Logged In User</label>
                            <p>{{ $attackAlert->user?->name ?? 'Guest (Not logged in)' }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">User Agent</label>
                        <p class="font-mono text-xs bg-gray-100 dark:bg-gray-700 p-2 rounded break-all">{{ $attackAlert->user_agent ?? '-' }}</p>
                    </div>
                    @if($attackAlert->fingerprint)
                    <div>
                        <label class="text-xs text-gray-500">Browser Fingerprint</label>
                        <p class="font-mono text-sm">{{ $attackAlert->fingerprint }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Mitigation Notes (if mitigated) --}}
            @if($attackAlert->isMitigated() && $attackAlert->mitigation_notes)
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-yellow-200 dark:border-yellow-800 overflow-hidden">
                <div class="p-4 border-b border-yellow-200 dark:border-yellow-800 bg-yellow-50 dark:bg-yellow-900/20">
                    <h2 class="font-semibold text-yellow-700 dark:text-yellow-400 flex items-center gap-2">
                        <i class='bx bx-wrench'></i> Mitigation Notes
                    </h2>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $attackAlert->mitigation_notes }}</p>
                    <p class="text-xs text-gray-500 mt-2">
                        By {{ $attackAlert->mitigatedByUser?->name ?? 'Unknown' }} 
                        on {{ $attackAlert->mitigated_at?->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Status --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="font-semibold mb-3">Status</h3>
                @if($attackAlert->isArchived())
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3">
                    <p class="text-green-700 dark:text-green-400 font-medium flex items-center gap-1">
                        <i class='bx bx-archive-in'></i> Archived
                    </p>
                    <p class="text-xs text-gray-500 mt-1">By: {{ $attackAlert->archivedByUser?->name ?? 'Unknown' }}</p>
                    <p class="text-xs text-gray-500">At: {{ $attackAlert->archived_at?->format('d M Y H:i') }}</p>
                </div>
                @elseif($attackAlert->isMitigated())
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3">
                    <p class="text-yellow-700 dark:text-yellow-400 font-medium flex items-center gap-1">
                        <i class='bx bx-wrench'></i> Mitigated
                    </p>
                    <p class="text-xs text-gray-500 mt-1">By: {{ $attackAlert->mitigatedByUser?->name ?? 'Unknown' }}</p>
                    <p class="text-xs text-gray-500">At: {{ $attackAlert->mitigated_at?->format('d M Y H:i') }}</p>
                </div>
                @else
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3">
                    <p class="text-red-700 dark:text-red-400 font-medium flex items-center gap-1">
                        <i class='bx bx-error-circle'></i> Active
                    </p>
                    <p class="text-xs text-gray-500 mt-1">This alert requires attention.</p>
                </div>
                @endif
            </div>

            {{-- Timeline --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="font-semibold mb-3">Timeline</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-start gap-2">
                        <i class='bx bx-error-circle text-red-500 mt-0.5'></i>
                        <div>
                            <p class="font-medium">Detected</p>
                            <p class="text-xs text-gray-500">{{ $attackAlert->created_at->format('d M Y H:i:s') }}</p>
                        </div>
                    </div>
                    @if($attackAlert->mitigated_at)
                    <div class="flex items-start gap-2">
                        <i class='bx bx-wrench text-yellow-500 mt-0.5'></i>
                        <div>
                            <p class="font-medium">Mitigated</p>
                            <p class="text-xs text-gray-500">{{ $attackAlert->mitigated_at->format('d M Y H:i:s') }}</p>
                            <p class="text-xs text-gray-400">by {{ $attackAlert->mitigatedByUser?->name ?? 'Unknown' }}</p>
                        </div>
                    </div>
                    @endif
                    @if($attackAlert->archived_at)
                    <div class="flex items-start gap-2">
                        <i class='bx bx-archive-in text-green-500 mt-0.5'></i>
                        <div>
                            <p class="font-medium">Archived</p>
                            <p class="text-xs text-gray-500">{{ $attackAlert->archived_at->format('d M Y H:i:s') }}</p>
                            <p class="text-xs text-gray-400">by {{ $attackAlert->archivedByUser?->name ?? 'Unknown' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Quick Actions --}}
            @if($attackAlert->isActive())
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="font-semibold mb-3">Add Mitigation Notes</h3>
                <form action="{{ route('admin.attack-alerts.mitigate', $attackAlert) }}" method="POST">
                    @csrf
                    <textarea name="notes" rows="3" 
                        class="w-full bg-gray-100 dark:bg-gray-700 border-0 rounded-lg text-sm mb-2"
                        placeholder="Describe mitigation steps taken..."></textarea>
                    <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        <i class='bx bx-wrench'></i> Mark as Mitigated
                    </button>
                </form>
            </div>
            @endif

            {{-- Related Attacks --}}
            @if($relatedAttacks->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="font-semibold mb-3">Related Attacks (Same IP)</h3>
                <div class="space-y-2">
                    @foreach($relatedAttacks as $related)
                    <a href="{{ route('admin.attack-alerts.show', $related) }}" 
                       class="block p-2 bg-gray-50 dark:bg-gray-700 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium uppercase">{{ $related->attack_type }}</span>
                            <span class="text-xs text-gray-500">{{ $related->created_at->diffForHumans() }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
