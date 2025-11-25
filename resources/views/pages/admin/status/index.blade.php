@extends('layouts.app')
@section('title', 'Status Surat')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div><h1 class="text-2xl font-bold" style="color: var(--text-primary);">Status Surat</h1><p style="color: var(--text-secondary);">Kelola status disposisi</p></div>
        <button onclick="openModal('modal-create-status')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium  btn-primary"><i class="bx bx-plus"></i> Tambah</button>
    </div>
    <div class="rounded-xl overflow-hidden border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="overflow-x-auto">
        <table class="w-full">
            <thead style="background-color: var(--bg-input);"><tr>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">ID</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Status</th>
                <th class="px-4 py-3 text-right text-xs font-semibold uppercase" style="color: var(--text-secondary);">Aksi</th>
            </tr></thead>
            <tbody class="divide-y" style="border-color: var(--border-color);">
                @forelse($statuses as $s)
                <tr class="hover:opacity-80">
                    <td class="px-4 py-3" style="color: var(--text-secondary);">{{ $s->id }}</td>
                    <td class="px-4 py-3" style="color: var(--text-primary);">{{ $s->status }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="editStatus({{ $s->id }}, '{{ $s->status }}')" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs" style="background-color: var(--bg-input); color: var(--text-secondary);"><i class="bx bx-edit"></i> Edit</button>
                            <form id="delete-status-{{ $s->id }}" action="{{ route('admin.statuses.destroy', $s) }}" method="POST" class="inline">@csrf @method('DELETE')<button type="button" onclick="confirmDelete('delete-status-{{ $s->id }}')" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs bg-red-100 text-red-600 hover:bg-red-200"><i class="bx bx-trash"></i> Hapus</button></form>
                        </div>
                    </td>
                </tr>
                @empty<tr><td colspan="3" class="px-4 py-12 text-center" style="color: var(--text-secondary);">Tidak ada data</td></tr>@endforelse
            </tbody>
        </table>
        </div>
        @if($statuses->hasPages())<div class="px-4 py-3 border-t" style="border-color: var(--border-color);">{{ $statuses->links() }}</div>@endif
    </div>
</div>

<!-- Modal Create Status -->
<div id="modal-create-status-backdrop" class="modal-backdrop"></div>
<div id="modal-create-status" class="modal-content w-full max-w-md">
    <div class="rounded-2xl shadow-2xl border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color: var(--border-color);">
            <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Tambah Status</h3>
            <button onclick="closeModal('modal-create-status')" class="p-1 rounded-lg hover:opacity-70" style="color: var(--text-secondary);"><i class="bx bx-x text-2xl"></i></button>
        </div>
        <form action="{{ route('admin.statuses.store') }}" method="POST" class="p-6">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Nama Status *</label>
                <input type="text" name="status" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);" placeholder="Contoh: Pending, Diproses, Selesai">
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('modal-create-status')" class="px-4 py-2 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-lg font-medium  btn-primary"><i class="bx bx-save mr-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Status -->
<div id="modal-edit-status-backdrop" class="modal-backdrop"></div>
<div id="modal-edit-status" class="modal-content w-full max-w-md">
    <div class="rounded-2xl shadow-2xl border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color: var(--border-color);">
            <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Edit Status</h3>
            <button onclick="closeModal('modal-edit-status')" class="p-1 rounded-lg hover:opacity-70" style="color: var(--text-secondary);"><i class="bx bx-x text-2xl"></i></button>
        </div>
        <form id="form-edit-status" method="POST" class="p-6">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Nama Status *</label>
                <input type="text" name="status" id="edit-status-name" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('modal-edit-status')" class="px-4 py-2 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-lg font-medium  btn-primary"><i class="bx bx-save mr-1"></i> Update</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function editStatus(id, status) {
    document.getElementById('form-edit-status').action = `/admin/statuses/${id}`;
    document.getElementById('edit-status-name').value = status;
    openModal('modal-edit-status');
}
</script>
@endpush
@endsection
