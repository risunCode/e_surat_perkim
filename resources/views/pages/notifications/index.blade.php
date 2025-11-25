@extends('layouts.app')
@section('title', 'Notifikasi')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Notifikasi</h1>
            <p style="color: var(--text-secondary);">Semua notifikasi sistem</p>
        </div>
        <form action="{{ route('notifications.read-all') }}" method="POST">
            @csrf
            <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm" style="background-color: var(--bg-input); color: var(--text-secondary);">
                <i class="bx bx-check-double"></i> Tandai Semua Dibaca
            </button>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-xl p-4 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-blue-100 text-blue-600">
                    <i class="bx bx-bell text-2xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold" style="color: var(--text-primary);">{{ $notifications->total() }}</p>
                    <p class="text-sm" style="color: var(--text-secondary);">Total Notifikasi</p>
                </div>
            </div>
        </div>
        <div class="rounded-xl p-4 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-yellow-100 text-yellow-600">
                    <i class="bx bx-envelope text-2xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold" style="color: var(--text-primary);">{{ $notifications->where('is_read', false)->count() }}</p>
                    <p class="text-sm" style="color: var(--text-secondary);">Belum Dibaca</p>
                </div>
            </div>
        </div>
        <div class="rounded-xl p-4 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-green-100 text-green-600">
                    <i class="bx bx-check-circle text-2xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold" style="color: var(--text-primary);">{{ $notifications->where('is_read', true)->count() }}</p>
                    <p class="text-sm" style="color: var(--text-secondary);">Sudah Dibaca</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Sort -->
    <div class="flex items-center gap-3 flex-wrap">
        <div class="flex items-center gap-2">
            <span class="text-sm" style="color: var(--text-secondary);">Filter:</span>
            <select id="statusFilter" onchange="filterTable()" aria-label="Filter status" class="px-3 py-2 rounded-lg border text-sm focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                <option value="all">Semua Status</option>
                <option value="unread">Baru (Belum Dibaca)</option>
                <option value="read">Sudah Dibaca</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-sm" style="color: var(--text-secondary);">Urutkan:</span>
            <select id="sortOrder" onchange="sortTable()" aria-label="Urutkan" class="px-3 py-2 rounded-lg border text-sm focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                <option value="newest">Terbaru</option>
                <option value="oldest">Terlama</option>
            </select>
        </div>
        <span id="resultCount" class="text-sm" style="color: var(--text-secondary);"></span>
    </div>

    <!-- Table -->
    <div class="rounded-xl overflow-hidden border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="overflow-x-auto">
        <table class="w-full" id="notifTable">
            <thead style="background-color: var(--bg-input);">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase cursor-pointer hover:opacity-70" style="color: var(--text-secondary);" onclick="toggleStatusFilter()">
                        Status <i class="bx bx-filter-alt ml-1"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Notifikasi</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase cursor-pointer hover:opacity-70" style="color: var(--text-secondary);" onclick="toggleSort()">
                        Waktu <i class="bx bx-sort ml-1" id="sortIcon"></i>
                    </th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase" style="color: var(--text-secondary);">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="border-color: var(--border-color);" id="notifBody">
                @forelse($notifications as $notif)
                <tr class="hover:opacity-80 notif-row" data-status="{{ $notif->is_read ? 'read' : 'unread' }}" data-date="{{ $notif->created_at->timestamp }}">
                    <td class="px-4 py-4">
                        @if(!$notif->is_read)
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Baru
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs" style="background-color: var(--bg-input); color: var(--text-secondary);">
                            <i class="bx bx-check"></i> Dibaca
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: var(--bg-input);">
                                <i class="bx {{ $notif->icon }} text-xl" style="color: var(--accent-color);"></i>
                            </div>
                            <div>
                                <p class="font-medium" style="color: var(--text-primary);">{{ $notif->title }}</p>
                                <p class="text-sm" style="color: var(--text-secondary);">{{ Str::limit($notif->message, 60) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <p class="text-sm" style="color: var(--text-primary);">{{ $notif->created_at->format('d M Y') }}</p>
                        <p class="text-xs" style="color: var(--text-secondary);">{{ $notif->created_at->format('H:i') }} • {{ $notif->created_at->diffForHumans() }}</p>
                    </td>
                    <td class="px-4 py-4 text-right">
                        @if($notif->link)
                        <a href="#" onclick="markAndGo({{ $notif->id }}, '{{ $notif->link }}')" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm {{ $notif->is_read ? '' : 'btn-primary' }}" style="{{ $notif->is_read ? 'background-color: var(--bg-input); color: var(--text-secondary);' : '' }}">
                            <i class="bx bx-link-external"></i> Lihat
                        </a>
                        @else
                        <span class="text-sm" style="color: var(--text-secondary);">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="4" class="px-4 py-12 text-center" style="color: var(--text-secondary);">
                        <i class="bx bx-bell-off text-4xl mb-2 block opacity-50"></i>
                        <p>Tidak ada notifikasi</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentSort = 'newest';
        
        function filterTable() {
            const filter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('.notif-row');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const status = row.dataset.status;
                if (filter === 'all' || filter === status) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            document.getElementById('resultCount').textContent = `${visibleCount} notifikasi`;
        }
        
        function sortTable() {
            const order = document.getElementById('sortOrder').value;
            currentSort = order;
            const tbody = document.getElementById('notifBody');
            const rows = Array.from(document.querySelectorAll('.notif-row'));
            
            rows.sort((a, b) => {
                const dateA = parseInt(a.dataset.date);
                const dateB = parseInt(b.dataset.date);
                return order === 'newest' ? dateB - dateA : dateA - dateB;
            });
            
            rows.forEach(row => tbody.appendChild(row));
            updateSortIcon();
        }
        
        function toggleSort() {
            const select = document.getElementById('sortOrder');
            select.value = select.value === 'newest' ? 'oldest' : 'newest';
            sortTable();
        }
        
        function toggleStatusFilter() {
            const select = document.getElementById('statusFilter');
            const options = ['all', 'unread', 'read'];
            const currentIndex = options.indexOf(select.value);
            select.value = options[(currentIndex + 1) % options.length];
            filterTable();
        }
        
        function updateSortIcon() {
            const icon = document.getElementById('sortIcon');
            icon.className = currentSort === 'newest' ? 'bx bx-sort-down ml-1' : 'bx bx-sort-up ml-1';
        }
        
        // Initialize
        filterTable();
    </script>
    @endpush

    @if($notifications->hasPages())
    <div class="mt-4">{{ $notifications->links() }}</div>
    @endif
</div>
@endsection
