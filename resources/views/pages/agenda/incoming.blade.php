@extends('layouts.app')
@section('title', 'Buku Agenda Surat Masuk')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Buku Agenda Surat Masuk</h1>
            <p style="color: var(--text-secondary);">Rekap surat masuk berdasarkan periode</p>
        </div>
        <a href="{{ route('agenda.incoming.print', request()->query()) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium  btn-primary"><i class="bx bx-printer"></i> Cetak</a>
    </div>
    <div class="rounded-xl p-4 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <form method="GET" class="flex flex-wrap gap-4">
            <select name="month" aria-label="Filter bulan" class="px-4 py-2 rounded-lg border" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                <option value="">Semua Bulan</option>
                @for($i = 1; $i <= 12; $i++)<option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>@endfor
            </select>
            <select name="year" aria-label="Filter tahun" class="px-4 py-2 rounded-lg border" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                @for($y = date('Y'); $y >= date('Y') - 5; $y--)<option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>@endfor
            </select>
            <button type="submit" class="px-4 py-2 rounded-lg  btn-primary">Filter</button>
        </form>
    </div>
    <div class="rounded-xl overflow-hidden border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="overflow-x-auto">
        <table class="w-full">
            <thead style="background-color: var(--bg-input);">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">No. Agenda</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">No. Surat</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Dari</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Perihal</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="border-color: var(--border-color);">
                @forelse($letters as $i => $letter)
                <tr class="hover:opacity-80">
                    <td class="px-4 py-3" style="color: var(--text-secondary);">{{ $letters->firstItem() + $i }}</td>
                    <td class="px-4 py-3" style="color: var(--text-primary);">{{ $letter->agenda_number ?? '-' }}</td>
                    <td class="px-4 py-3 font-medium" style="color: var(--text-primary);">{{ $letter->reference_number }}</td>
                    <td class="px-4 py-3" style="color: var(--text-secondary);">{{ $letter->letter_date?->format('d/m/Y') }}</td>
                    <td class="px-4 py-3" style="color: var(--text-secondary);">{{ $letter->from }}</td>
                    <td class="px-4 py-3" style="color: var(--text-secondary);">{{ Str::limit($letter->description, 50) }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-12 text-center" style="color: var(--text-secondary);">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        @if($letters->hasPages())<div class="px-4 py-3 border-t" style="border-color: var(--border-color);">{{ $letters->withQueryString()->links() }}</div>@endif
    </div>
</div>
@endsection
