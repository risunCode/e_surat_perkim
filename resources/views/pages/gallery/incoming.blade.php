@extends('layouts.app')
@section('title', 'Galeri Surat Masuk')
@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Galeri Surat Masuk</h1>
            <p style="color: var(--text-secondary);">Dokumen lampiran surat masuk</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="rounded-xl p-4 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-blue-100 text-blue-600"><i class="bx bx-file text-xl"></i></div>
                <div>
                    <p class="text-xl font-bold" style="color: var(--text-primary);">{{ $totalFiles }}</p>
                    <p class="text-xs" style="color: var(--text-secondary);">Total File</p>
                </div>
            </div>
        </div>
        <div class="rounded-xl p-4 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-purple-100 text-purple-600"><i class="bx bx-envelope text-xl"></i></div>
                <div>
                    <p class="text-xl font-bold" style="color: var(--text-primary);">{{ $letters->total() }}</p>
                    <p class="text-xs" style="color: var(--text-secondary);">Surat Masuk</p>
                </div>
            </div>
        </div>
        <div class="rounded-xl p-4 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-yellow-100 text-yellow-600"><i class="bx bx-calendar text-xl"></i></div>
                <div>
                    <p class="text-xl font-bold" style="color: var(--text-primary);">{{ now()->format('Y') }}</p>
                    <p class="text-xs" style="color: var(--text-secondary);">Tahun Aktif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="rounded-xl p-4 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor surat, pengirim..." class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
            </div>
            <select name="classification" aria-label="Filter klasifikasi" class="px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                <option value="">Semua Klasifikasi</option>
                @foreach($classifications as $c)<option value="{{ $c->code }}" {{ request('classification') == $c->code ? 'selected' : '' }}>{{ $c->type }}</option>@endforeach
            </select>
            <button type="submit" class="px-4 py-2 rounded-lg btn-primary"><i class="bx bx-search mr-1"></i> Cari</button>
            <button type="button" onclick="document.getElementById('advanced-filters').classList.toggle('hidden')" class="px-3 py-2 rounded-lg border text-sm" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-secondary);" aria-label="Toggle filter lanjutan">
                <i class="bx bx-filter-alt"></i> Filter
            </button>
        </form>
        <!-- Advanced Filters -->
        <div id="advanced-filters" class="hidden mt-4 pt-4 border-t flex flex-wrap gap-3" style="border-color: var(--border-color);">
            <div>
                <label class="block text-xs mb-1" style="color: var(--text-secondary);">Dari Tanggal (opsional)</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" form="filter-form" class="px-3 py-1.5 rounded-lg border text-sm focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
            </div>
            <div>
                <label class="block text-xs mb-1" style="color: var(--text-secondary);">Sampai Tanggal (opsional)</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" form="filter-form" class="px-3 py-1.5 rounded-lg border text-sm focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="rounded-xl overflow-hidden border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="overflow-x-auto">
            <table class="w-full" id="gallery-table">
                <thead style="background-color: var(--bg-input);">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase w-12" style="color: var(--text-secondary);">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase cursor-pointer hover:opacity-70" style="color: var(--text-secondary);" onclick="sortTable(1)"><i class="bx bx-sort-alt-2 mr-1"></i>Surat</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase cursor-pointer hover:opacity-70" style="color: var(--text-secondary);" onclick="sortTable(2)"><i class="bx bx-sort-alt-2 mr-1"></i>Dari</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Lampiran</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase cursor-pointer hover:opacity-70" style="color: var(--text-secondary);" onclick="sortTable(4)"><i class="bx bx-sort-alt-2 mr-1"></i>Tanggal</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase" style="color: var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--border-color);">
                    @forelse($letters as $index => $letter)
                    <tr class="hover:opacity-80">
                        <td class="px-4 py-3 text-center" style="color: var(--text-secondary);">{{ $letters->firstItem() + $index }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium" style="color: var(--text-primary);">{{ $letter->reference_number }}</p>
                            <p class="text-xs" style="color: var(--text-secondary);">{{ $letter->classification?->type }}</p>
                        </td>
                        <td class="px-4 py-3" style="color: var(--text-primary);">{{ $letter->from }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                            <x-attachment-preview :attachments="$letter->attachments" />
                            <span class="text-xs" style="color: var(--text-secondary);">({{ $letter->attachments->count() }})</span>
                        </div>
                        </td>
                        <td class="px-4 py-3" style="color: var(--text-secondary);">{{ $letter->letter_date?->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('incoming.show', $letter) }}" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs" style="background-color: var(--bg-input); color: var(--text-secondary);"><i class="bx bx-link-external"></i> Detail</a>
                                <a href="{{ route('gallery.incoming.print', $letter->id) }}" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs btn-primary">
                                    <i class="bx bx-file-text"></i> Transkrip
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center" style="color: var(--text-secondary);"><i class="bx bx-image text-4xl mb-2 block opacity-50"></i><p>Belum ada dokumen</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($letters->hasPages())<div class="px-4 py-3 border-t" style="border-color: var(--border-color);">{{ $letters->withQueryString()->links() }}</div>@endif
    </div>
</div>

@push('scripts')
<script>
@foreach($letters as $letter)
const files{{ $letter->id }} = [@foreach($letter->attachments as $att){ url: '{{ Storage::url($att->full_path) }}', name: '{{ $att->filename }}' },@endforeach];
function openGalleryFor{{ $letter->id }}(index) { openGallery(files{{ $letter->id }}, index); }
@endforeach

// Client-side table sorting
let sortDir = {};
function sortTable(colIndex) {
    const table = document.getElementById('gallery-table');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    if (rows.length === 0 || rows[0].querySelector('td[colspan]')) return;
    
    sortDir[colIndex] = !sortDir[colIndex];
    
    rows.sort((a, b) => {
        const aText = a.cells[colIndex]?.textContent.trim() || '';
        const bText = b.cells[colIndex]?.textContent.trim() || '';
        
        // Check if date format (dd/mm/yyyy)
        if (aText.match(/^\d{2}\/\d{2}\/\d{4}$/)) {
            const [aD, aM, aY] = aText.split('/');
            const [bD, bM, bY] = bText.split('/');
            const aDate = new Date(aY, aM - 1, aD);
            const bDate = new Date(bY, bM - 1, bD);
            return sortDir[colIndex] ? aDate - bDate : bDate - aDate;
        }
        
        return sortDir[colIndex] 
            ? aText.localeCompare(bText, 'id') 
            : bText.localeCompare(aText, 'id');
    });
    
    rows.forEach(row => tbody.appendChild(row));
}

</script>
@endpush
@endsection
