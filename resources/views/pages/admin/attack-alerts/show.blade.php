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
        @if(!$attackAlert->is_dismissed)
        <form action="{{ route('admin.attack-alerts.dismiss', $attackAlert) }}" method="POST">
            @csrf
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                <i class='bx bx-check'></i> Dismiss Alert
            </button>
        </form>
        @else
        <span class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm font-medium">
            <i class='bx bx-check'></i> Dismissed by {{ $attackAlert->dismissedByUser?->name ?? 'Unknown' }}
        </span>
        @endif
    </div>

    <div class="grid grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="col-span-2 space-y-6">
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
                        <pre class="font-mono text-xs bg-red-50 dark:bg-red-900/20 p-3 rounded border border-red-200 dark:border-red-800 overflow-x-auto">{{ $attackAlert->payload }}</pre>
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
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Status --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="font-semibold mb-3">Status</h3>
                @if($attackAlert->is_dismissed)
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3">
                    <p class="text-green-700 dark:text-green-400 font-medium"><i class='bx bx-check-circle'></i> Dismissed</p>
                    <p class="text-xs text-gray-500 mt-1">By: {{ $attackAlert->dismissedByUser?->name ?? 'Unknown' }}</p>
                    <p class="text-xs text-gray-500">At: {{ $attackAlert->dismissed_at?->format('d M Y H:i') }}</p>
                </div>
                @else
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3">
                    <p class="text-red-700 dark:text-red-400 font-medium"><i class='bx bx-error-circle'></i> Active</p>
                    <p class="text-xs text-gray-500 mt-1">This alert is still active and visible to all users.</p>
                </div>
                @endif
            </div>

            {{-- Timeline --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="font-semibold mb-3">Timeline</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-start gap-2">
                        <i class='bx bx-time text-gray-400 mt-0.5'></i>
                        <div>
                            <p class="font-medium">Detected</p>
                            <p class="text-xs text-gray-500">{{ $attackAlert->created_at->format('d M Y H:i:s') }}</p>
                        </div>
                    </div>
                    @if($attackAlert->is_dismissed)
                    <div class="flex items-start gap-2">
                        <i class='bx bx-check text-green-500 mt-0.5'></i>
                        <div>
                            <p class="font-medium">Dismissed</p>
                            <p class="text-xs text-gray-500">{{ $attackAlert->dismissed_at?->format('d M Y H:i:s') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

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
