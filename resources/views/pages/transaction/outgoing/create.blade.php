@extends('layouts.app')
@section('title', $replyTo ? 'Balas Surat' : 'Tambah Surat Keluar')
@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ $replyTo ? route('incoming.show', $replyTo) : route('outgoing.index') }}" class="p-2 rounded-lg hover:opacity-70" style="background-color: var(--bg-card); color: var(--text-secondary);"><i class="bx bx-arrow-back"></i></a>
            <div>
                <h1 class="text-xl font-bold" style="color: var(--text-primary);">{{ $replyTo ? 'Balas Surat' : 'Tambah Surat Keluar' }}</h1>
                @if($replyTo)<p class="text-sm" style="color: var(--text-secondary);">Re: {{ $replyTo->reference_number }}</p>@endif
            </div>
        </div>
    </div>

    <form action="{{ route('outgoing.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($replyTo)<input type="hidden" name="reference_to" value="{{ $replyTo->id }}">@endif
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Left: Form Fields -->
            <div class="lg:col-span-2 rounded-xl border p-5" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="col-span-2">
                        <label class="block text-xs font-medium mb-1" style="color: var(--text-secondary);">Nomor Surat *</label>
                        <input type="text" name="reference_number" value="{{ old('reference_number') }}" required class="w-full px-3 py-2 rounded-lg border text-sm focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);" placeholder="001/SK/2024">
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1" style="color: var(--text-secondary);">Agenda</label>
                        <input type="text" name="agenda_number" value="{{ old('agenda_number') }}" class="w-full px-3 py-2 rounded-lg border text-sm focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1" style="color: var(--text-secondary);">Tanggal *</label>
                        <input type="date" name="letter_date" value="{{ old('letter_date', date('Y-m-d')) }}" required class="w-full px-3 py-2 rounded-lg border text-sm focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                    </div>
                    <div class="col-span-2 md:col-span-3">
                        <label class="block text-xs font-medium mb-1" style="color: var(--text-secondary);">Kepada *</label>
                        <input type="text" name="to" value="{{ old('to', $replyTo?->from) }}" required class="w-full px-3 py-2 rounded-lg border text-sm focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);" placeholder="Tujuan surat">
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1" style="color: var(--text-secondary);">Klasifikasi *</label>
                        <select name="classification_code" required class="w-full px-3 py-2 rounded-lg border text-sm focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                            <option value="">Pilih Klasifikasi</option>
                            @foreach($classifications as $c)
                            <option value="{{ $c->code }}" {{ old('classification_code', $replyTo?->classification_code) == $c->code ? 'selected' : '' }}>
                                {{ $c->code }} - {{ $c->type }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2 md:col-span-4">
                        <label class="block text-xs font-medium mb-1" style="color: var(--text-secondary);">Perihal</label>
                        <textarea name="description" rows="2" class="w-full px-3 py-2 rounded-lg border text-sm focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">{{ old('description', $replyTo ? 'Balasan: ' . $replyTo->description : '') }}</textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-medium mb-1" style="color: var(--text-secondary);">Catatan</label>
                        <textarea name="note" rows="2" class="w-full px-3 py-2 rounded-lg border text-sm focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">{{ old('note') }}</textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-medium mb-1" style="color: var(--text-secondary);">Lampiran</label>
                        <input type="file" name="attachments[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif" class="w-full px-3 py-2 rounded-lg border text-xs" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                        <div id="preview-create" class="mt-2 flex flex-wrap gap-2"></div>
                    </div>
                </div>
                <div class="flex gap-2 mt-4 pt-4 border-t" style="border-color: var(--border-color);">
                    <button type="submit" class="px-5 py-2 rounded-lg text-sm font-medium btn-primary"><i class="bx bx-send mr-1"></i> {{ $replyTo ? 'Kirim Balasan' : 'Simpan' }}</button>
                    <a href="{{ $replyTo ? route('incoming.show', $replyTo) : route('outgoing.index') }}" class="px-4 py-2 rounded-lg text-sm" style="background-color: var(--bg-input); color: var(--text-secondary);">Batal</a>
                </div>
            </div>

            <!-- Right: Reference Info -->
            @if($replyTo)
            <div class="rounded-xl border p-5" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <h3 class="text-sm font-semibold mb-3 flex items-center gap-2" style="color: var(--text-primary);"><i class="bx bx-envelope text-purple-500"></i> Surat Asal</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-xs" style="color: var(--text-secondary);">Nomor Surat</p>
                        <p class="font-medium" style="color: var(--text-primary);">{{ $replyTo->reference_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs" style="color: var(--text-secondary);">Dari</p>
                        <p style="color: var(--text-primary);">{{ $replyTo->from }}</p>
                    </div>
                    <div>
                        <p class="text-xs" style="color: var(--text-secondary);">Tanggal</p>
                        <p style="color: var(--text-primary);">{{ $replyTo->letter_date?->format('d M Y') }}</p>
                    </div>
                    @if($replyTo->description)
                    <div>
                        <p class="text-xs" style="color: var(--text-secondary);">Perihal</p>
                        <p class="text-sm p-2 rounded mt-1" style="background-color: var(--bg-input); color: var(--text-primary);">{{ $replyTo->description }}</p>
                    </div>
                    @endif
                    <a href="{{ route('incoming.show', $replyTo) }}" class="inline-flex items-center gap-1 text-xs text-blue-500 hover:underline mt-2"><i class="bx bx-link-external"></i> Lihat Surat Asli</a>
                </div>
            </div>
            @else
            <div class="rounded-xl border p-5" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <h3 class="text-sm font-semibold mb-3" style="color: var(--text-primary);"><i class="bx bx-info-circle mr-1"></i> Tips</h3>
                <ul class="text-xs space-y-2" style="color: var(--text-secondary);">
                    <li>• Nomor surat harus unik</li>
                    <li>• Format: 001/KODE/TAHUN</li>
                    <li>• Lampiran maks 15MB total/file</li>
                    <li>• Format: PDF, DOC, JPG, PNG</li>
                </ul>
            </div>
            @endif
        </div>
    </form>
</div>
@endsection
