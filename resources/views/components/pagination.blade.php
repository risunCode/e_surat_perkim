<!-- Enhanced Pagination Component -->
@if((method_exists($paginator, 'hasPages') && $paginator->hasPages()) || request('per_page') === 'all' || isset($paginator->showAll))
<div class="px-4 py-3 border-t flex items-center justify-between" style="border-color: var(--border-color);">
    <!-- Left: Per Page Options -->
    <div class="flex items-center gap-3">
        <span class="text-sm" style="color: var(--text-secondary);">Tampilkan:</span>
        <select onchange="changePage(this.value)" class="text-sm rounded-lg border px-3 py-1.5 min-w-0" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 per halaman</option>
            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per halaman</option>
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per halaman</option>
            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per halaman</option>
            <option value="all" {{ request('per_page') === 'all' ? 'selected' : '' }}>Tampilkan Semua</option>
        </select>
    </div>

    <!-- Center: Results Info -->
    <div class="text-sm" style="color: var(--text-secondary);">
        @if(request('per_page') === 'all' || isset($paginator->showAll))
            Menampilkan semua {{ number_format($totalCount ?? 0) }} data
        @elseif(method_exists($paginator, 'firstItem'))
            Menampilkan {{ number_format($paginator->firstItem() ?? 0) }}-{{ number_format($paginator->lastItem() ?? 0) }} dari {{ number_format($paginator->total()) }}
        @else
            Menampilkan data
        @endif
    </div>

    <!-- Right: Pagination Links -->
    @if(request('per_page') !== 'all' && !isset($paginator->showAll) && method_exists($paginator, 'hasPages'))
    <div class="flex items-center gap-1">
        {{-- Previous Button --}}
        @if($paginator->onFirstPage())
            <span class="px-3 py-2 text-sm rounded-lg opacity-50" style="background-color: var(--bg-input); color: var(--text-secondary);">
                <i class="bx bx-chevron-left"></i> Sebelum
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-sm rounded-lg hover:opacity-80 transition-opacity" style="background-color: var(--bg-input); color: var(--text-primary);">
                <i class="bx bx-chevron-left"></i> Sebelum
            </a>
        @endif

        {{-- Page Numbers --}}
        @if(method_exists($paginator, 'getUrlRange'))
            @php
                $start = max($paginator->currentPage() - 2, 1);
                $end = min($start + 4, $paginator->lastPage());
                $start = max($end - 4, 1);
            @endphp
            
            @if($start > 1)
                <a href="{{ $paginator->url(1) }}" class="px-3 py-2 text-sm rounded-lg hover:opacity-80 transition-opacity" style="background-color: var(--bg-input); color: var(--text-primary);">1</a>
                @if($start > 2)
                    <span class="px-3 py-2 text-sm" style="color: var(--text-secondary);">...</span>
                @endif
            @endif

            @for($page = $start; $page <= $end; $page++)
                @if($page == $paginator->currentPage())
                    <span class="px-3 py-2 text-sm font-semibold rounded-lg btn-primary">{{ $page }}</span>
                @else
                    <a href="{{ $paginator->url($page) }}" class="px-3 py-2 text-sm rounded-lg hover:opacity-80 transition-opacity" style="background-color: var(--bg-input); color: var(--text-primary);">{{ $page }}</a>
                @endif
            @endfor

            @if($end < $paginator->lastPage())
                @if($end < $paginator->lastPage() - 1)
                    <span class="px-3 py-2 text-sm" style="color: var(--text-secondary);">...</span>
                @endif
                <a href="{{ $paginator->url($paginator->lastPage()) }}" class="px-3 py-2 text-sm rounded-lg hover:opacity-80 transition-opacity" style="background-color: var(--bg-input); color: var(--text-primary);">{{ $paginator->lastPage() }}</a>
            @endif
        @endif

        {{-- Next Button --}}
        @if($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-sm rounded-lg hover:opacity-80 transition-opacity" style="background-color: var(--bg-input); color: var(--text-primary);">
                Selanjutnya <i class="bx bx-chevron-right"></i>
            </a>
        @else
            <span class="px-3 py-2 text-sm rounded-lg opacity-50" style="background-color: var(--bg-input); color: var(--text-secondary);">
                Selanjutnya <i class="bx bx-chevron-right"></i>
            </span>
        @endif
    </div>
    @endif
</div>

<script>
function changePage(perPage) {
    const url = new URL(window.location);
    if (perPage === 'all') {
        url.searchParams.set('per_page', 'all');
        url.searchParams.delete('page'); // Remove page param for "show all"
    } else {
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', '1'); // Reset to page 1 when changing per_page
    }
    window.location.href = url.toString();
}
</script>
@endif
