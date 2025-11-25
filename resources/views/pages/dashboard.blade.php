@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="rounded-2xl p-6 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--text-primary);">
                    @php
                        $hour = date('H');
                        if ($hour < 12) $greeting = 'Selamat Pagi';
                        elseif ($hour < 15) $greeting = 'Selamat Siang';
                        elseif ($hour < 18) $greeting = 'Selamat Sore';
                        else $greeting = 'Selamat Malam';
                    @endphp
                    {{ $greeting }}, {{ auth()->user()->name }}!
                </h1>
                <p class="mt-1" style="color: var(--text-secondary);">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }} • Laporan hari ini</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('incoming.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg transition-colors font-medium" style="background-color: var(--accent-color); color: white;">
                    <i class="bx bx-envelope-open"></i>
                    Surat Masuk
                </a>
                <a href="{{ route('outgoing.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg transition-colors font-medium border" style="background-color: var(--bg-input); color: var(--text-primary); border-color: var(--border-color);">
                    <i class="bx bx-send"></i>
                    Surat Keluar
                </a>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Surat Masuk -->
        <div class="rounded-xl p-5 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm" style="color: var(--text-secondary);">Surat Masuk</p>
                    <p class="text-3xl font-bold mt-1" style="color: var(--text-primary);">{{ $stats['incoming_today'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: var(--bg-input);">
                    <i class="bx bx-envelope-open text-2xl" style="color: var(--text-secondary);"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1 text-sm">
                @if($stats['incoming_change'] >= 0)
                    <span class="text-green-500"><i class="bx bx-up-arrow-alt"></i> {{ $stats['incoming_change'] }}%</span>
                @else
                    <span class="text-red-500"><i class="bx bx-down-arrow-alt"></i> {{ abs($stats['incoming_change']) }}%</span>
                @endif
                <span style="color: var(--text-secondary);">dari kemarin</span>
            </div>
        </div>
        
        <!-- Surat Keluar -->
        <div class="rounded-xl p-5 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm" style="color: var(--text-secondary);">Surat Keluar</p>
                    <p class="text-3xl font-bold mt-1" style="color: var(--text-primary);">{{ $stats['outgoing_today'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: var(--bg-input);">
                    <i class="bx bx-send text-2xl" style="color: var(--text-secondary);"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1 text-sm">
                @if($stats['outgoing_change'] >= 0)
                    <span class="text-green-500"><i class="bx bx-up-arrow-alt"></i> {{ $stats['outgoing_change'] }}%</span>
                @else
                    <span class="text-red-500"><i class="bx bx-down-arrow-alt"></i> {{ abs($stats['outgoing_change']) }}%</span>
                @endif
                <span style="color: var(--text-secondary);">dari kemarin</span>
            </div>
        </div>
        
        <!-- Disposisi -->
        <div class="rounded-xl p-5 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm" style="color: var(--text-secondary);">Disposisi</p>
                    <p class="text-3xl font-bold mt-1" style="color: var(--text-primary);">{{ $stats['disposition_today'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: var(--bg-input);">
                    <i class="bx bx-transfer text-2xl" style="color: var(--text-secondary);"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1 text-sm">
                @if($stats['disposition_change'] >= 0)
                    <span class="text-green-500"><i class="bx bx-up-arrow-alt"></i> {{ $stats['disposition_change'] }}%</span>
                @else
                    <span class="text-red-500"><i class="bx bx-down-arrow-alt"></i> {{ abs($stats['disposition_change']) }}%</span>
                @endif
                <span style="color: var(--text-secondary);">dari kemarin</span>
            </div>
        </div>
        
        <!-- Pengguna Aktif -->
        <div class="rounded-xl p-5 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm" style="color: var(--text-secondary);">Pengguna Aktif</p>
                    <p class="text-3xl font-bold mt-1" style="color: var(--text-primary);">{{ $stats['active_users'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: var(--bg-input);">
                    <i class="bx bx-user-check text-2xl" style="color: var(--text-secondary);"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1 text-sm">
                <span style="color: var(--text-secondary);">Total pengguna aktif</span>
            </div>
        </div>
    </div>
    
    <!-- Chart -->
    <div class="rounded-xl p-6 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-semibold" style="color: var(--text-primary);">Grafik Transaksi Surat</h2>
                <p class="text-sm" style="color: var(--text-secondary);">7 hari terakhir</p>
            </div>
            <span class="px-3 py-1 text-sm rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);">Minggu ini</span>
        </div>
        <div class="h-80">
            <canvas id="transactionChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Get computed styles for theme colors
    const styles = getComputedStyle(document.documentElement);
    const accentColor = styles.getPropertyValue('--accent-color').trim() || '#1e3a5f';
    const textSecondary = styles.getPropertyValue('--text-secondary').trim() || '#64748b';
    const borderColor = styles.getPropertyValue('--border-color').trim() || '#e2e8f0';
    
    const ctx = document.getElementById('transactionChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: [
                {
                    label: 'Surat Masuk',
                    data: @json($chartData['incoming']),
                    borderColor: accentColor,
                    backgroundColor: accentColor + '20',
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'Surat Keluar',
                    data: @json($chartData['outgoing']),
                    borderColor: textSecondary,
                    backgroundColor: textSecondary + '20',
                    tension: 0.4,
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: textSecondary
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        color: borderColor
                    },
                    ticks: {
                        color: textSecondary
                    }
                },
                y: {
                    grid: {
                        color: borderColor
                    },
                    ticks: {
                        color: textSecondary
                    },
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
@endsection
