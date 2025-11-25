@extends('layouts.app')
@section('title', 'Klasifikasi Surat')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div><h1 class="text-2xl font-bold" style="color: var(--text-primary);">Klasifikasi Surat</h1><p style="color: var(--text-secondary);">Kelola kode klasifikasi surat</p></div>
        <button onclick="openModal('modal-create-classification')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium  btn-primary"><i class="bx bx-plus"></i> Tambah</button>
    </div>
    <div class="rounded-xl overflow-hidden border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="overflow-x-auto">
        <table class="w-full">
            <thead style="background-color: var(--bg-input);"><tr>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Kode</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Tipe</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Deskripsi</th>
                <th class="px-4 py-3 text-right text-xs font-semibold uppercase" style="color: var(--text-secondary);">Aksi</th>
            </tr></thead>
            <tbody class="divide-y" style="border-color: var(--border-color);">
                @forelse($classifications as $c)
                <tr class="hover:opacity-80">
                    <td class="px-4 py-3"><span class="px-2 py-1 font-mono text-sm rounded" style="background-color: var(--bg-input); color: var(--text-primary);">{{ $c->code }}</span></td>
                    <td class="px-4 py-3" style="color: var(--text-primary);">{{ $c->type }}</td>
                    <td class="px-4 py-3" style="color: var(--text-secondary);">{{ $c->description ?? '-' }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="editClassification({{ $c->id }}, '{{ $c->code }}', '{{ $c->type }}', '{{ addslashes($c->description) }}')" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs" style="background-color: var(--bg-input); color: var(--text-secondary);"><i class="bx bx-edit"></i> Edit</button>
                            <form id="delete-class-{{ $c->id }}" action="{{ route('admin.classifications.destroy', $c) }}" method="POST" class="inline">@csrf @method('DELETE')<button type="button" onclick="confirmDelete('delete-class-{{ $c->id }}')" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs bg-red-100 text-red-600 hover:bg-red-200"><i class="bx bx-trash"></i> Hapus</button></form>
                        </div>
                    </td>
                </tr>
                @empty<tr><td colspan="4" class="px-4 py-12 text-center" style="color: var(--text-secondary);">Tidak ada data</td></tr>@endforelse
            </tbody>
        </table>
        </div>
        @if($classifications->hasPages())<div class="px-4 py-3 border-t" style="border-color: var(--border-color);">{{ $classifications->links() }}</div>@endif
    </div>
</div>

<!-- Modal Create Classification -->
<div id="modal-create-classification-backdrop" class="modal-backdrop"></div>
<div id="modal-create-classification" class="modal-content w-full max-w-lg">
    <div class="rounded-2xl shadow-2xl border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color: var(--border-color);">
            <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Tambah Klasifikasi</h3>
            <button onclick="closeModal('modal-create-classification')" class="p-1 rounded-lg hover:opacity-70" style="color: var(--text-secondary);"><i class="bx bx-x text-2xl"></i></button>
        </div>
        <form action="{{ route('admin.classifications.store') }}" method="POST" class="p-6">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Kode *</label>
                    <input type="text" name="code" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);" placeholder="001">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Tipe *</label>
                    <input type="text" name="type" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);" placeholder="Umum">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Deskripsi</label>
                    <textarea name="description" rows="2" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);" placeholder="Keterangan klasifikasi..."></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('modal-create-classification')" class="px-4 py-2 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-lg font-medium  btn-primary"><i class="bx bx-save mr-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Classification -->
<div id="modal-edit-classification-backdrop" class="modal-backdrop"></div>
<div id="modal-edit-classification" class="modal-content w-full max-w-lg">
    <div class="rounded-2xl shadow-2xl border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color: var(--border-color);">
            <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Edit Klasifikasi</h3>
            <button onclick="closeModal('modal-edit-classification')" class="p-1 rounded-lg hover:opacity-70" style="color: var(--text-secondary);"><i class="bx bx-x text-2xl"></i></button>
        </div>
        <form id="form-edit-classification" method="POST" class="p-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Kode *</label>
                    <input type="text" name="code" id="edit-class-code" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Tipe *</label>
                    <input type="text" name="type" id="edit-class-type" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Deskripsi</label>
                    <textarea name="description" id="edit-class-desc" rows="2" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('modal-edit-classification')" class="px-4 py-2 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-lg font-medium  btn-primary"><i class="bx bx-save mr-1"></i> Update</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function editClassification(id, code, type, description) {
    document.getElementById('form-edit-classification').action = `/admin/classifications/${id}`;
    document.getElementById('edit-class-code').value = code;
    document.getElementById('edit-class-type').value = type;
    document.getElementById('edit-class-desc').value = description || '';
    openModal('modal-edit-classification');
}
</script>
@endpush
@endsection
