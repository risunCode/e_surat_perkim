@extends('layouts.app')
@section('title', 'Kode Referensi')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div><h1 class="text-2xl font-bold" style="color: var(--text-primary);">Kode Referensi</h1><p style="color: var(--text-secondary);">Kelola kode registrasi pengguna</p></div>
        <button onclick="openModal('modal-create-refcode')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium  btn-primary"><i class="bx bx-plus"></i> Tambah</button>
    </div>
    <div class="rounded-xl overflow-hidden border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="overflow-x-auto">
        <table class="w-full">
            <thead style="background-color: var(--bg-input);"><tr>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase w-12" style="color: var(--text-secondary);">No</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Kode</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Nama</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Role</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Penggunaan</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Status</th>
                <th class="px-4 py-3 text-right text-xs font-semibold uppercase" style="color: var(--text-secondary);">Aksi</th>
            </tr></thead>
            <tbody class="divide-y" style="border-color: var(--border-color);">
                @forelse($codes as $index => $code)
                <tr class="hover:opacity-80">
                    <td class="px-4 py-3 text-center" style="color: var(--text-secondary);">{{ $codes->firstItem() + $index }}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 font-mono text-sm rounded" style="background-color: var(--bg-input); color: var(--text-primary);">{{ $code->code }}</span></td>
                    <td class="px-4 py-3" style="color: var(--text-primary);">{{ $code->name }}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded" style="background-color: var(--bg-input); color: var(--text-secondary);">{{ $code->role === 'admin' ? 'Admin' : 'Staff' }}</span></td>
                    <td class="px-4 py-3" style="color: var(--text-secondary);">{{ $code->used_count }}/{{ $code->max_usage == 0 ? '∞' : $code->max_usage }}</td>
                    <td class="px-4 py-3">
                        @if($code->isValid())<span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded">Aktif</span>
                        @else<span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded">Nonaktif</span>@endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2 flex-wrap">
                            <button onclick="showUsage({{ $code->id }})" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs bg-blue-100 text-blue-600"><i class="bx bx-user-check"></i> Usage</button>
                            <button onclick="editRefCode({{ $code->id }}, '{{ $code->name }}', '{{ $code->code }}', '{{ $code->role }}', {{ $code->max_usage }}, '{{ $code->expired_at?->format('Y-m-d') }}')" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs" style="background-color: var(--bg-input); color: var(--text-secondary);"><i class="bx bx-edit"></i> Edit</button>
                            <form id="toggle-{{ $code->id }}" action="{{ route('admin.reference-codes.toggle-status', $code) }}" method="POST" class="inline">@csrf<button type="button" onclick="confirmToggle('toggle-{{ $code->id }}', '{{ $code->isValid() ? 'nonaktifkan' : 'aktifkan' }}')" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs {{ $code->isValid() ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}"><i class="bx bx-power-off"></i> {{ $code->isValid() ? 'Off' : 'On' }}</button></form>
                            <form id="delete-refcode-{{ $code->id }}" action="{{ route('admin.reference-codes.destroy', $code) }}" method="POST" class="inline">@csrf @method('DELETE')<button type="button" onclick="confirmDelete('delete-refcode-{{ $code->id }}')" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs bg-red-100 text-red-600 hover:bg-red-200"><i class="bx bx-trash"></i> Hapus</button></form>
                        </div>
                    </td>
                </tr>
                @empty<tr><td colspan="7" class="px-4 py-12 text-center" style="color: var(--text-secondary);">Tidak ada data</td></tr>@endforelse
            </tbody>
        </table>
        </div>
        @if($codes->hasPages())<div class="px-4 py-3 border-t" style="border-color: var(--border-color);">{{ $codes->links() }}</div>@endif
    </div>
</div>

<!-- Modal Create Reference Code -->
<div id="modal-create-refcode-backdrop" class="modal-backdrop"></div>
<div id="modal-create-refcode" class="modal-content w-full max-w-2xl">
    <div class="rounded-2xl shadow-2xl border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color: var(--border-color);">
            <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Tambah Kode Referensi</h3>
            <button onclick="closeModal('modal-create-refcode')" class="p-1 rounded-lg hover:opacity-70" style="color: var(--text-secondary);"><i class="bx bx-x text-2xl"></i></button>
        </div>
        <form action="{{ route('admin.reference-codes.store') }}" method="POST" class="p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Nama *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);" placeholder="Kode Registrasi Staff 2024">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Kode <span class="opacity-60">(auto)</span></label>
                    <input type="text" name="code" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);" placeholder="Kosongkan untuk auto-generate">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Role *</label>
                    <select name="role" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                        <option value="admin">Admin</option>
                        <option value="staff" selected>Staff</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Maks Penggunaan *</label>
                    <input type="number" name="max_usage" value="1" min="0" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                    <p class="text-xs mt-1" style="color: var(--text-secondary);">0 = tidak terbatas</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Tanggal Kadaluarsa</label>
                    <input type="date" name="expired_at" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('modal-create-refcode')" class="px-4 py-2 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-lg font-medium  btn-primary"><i class="bx bx-save mr-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Usage -->
<div id="modal-usage-backdrop" class="modal-backdrop"></div>
<div id="modal-usage" class="modal-content w-full max-w-2xl">
    <div class="rounded-2xl shadow-2xl border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color: var(--border-color);">
            <h3 class="text-lg font-semibold" style="color: var(--text-primary);"><i class="bx bx-user-check mr-2"></i>Riwayat Penggunaan Kode</h3>
            <button onclick="closeModal('modal-usage')" class="p-1 rounded-lg hover:opacity-70" style="color: var(--text-secondary);"><i class="bx bx-x text-2xl"></i></button>
        </div>
        <div class="p-6">
            <div class="text-center mb-4">
                <span id="usage-code" class="px-4 py-2 font-mono text-lg font-bold rounded-lg" style="background-color: var(--bg-input); color: var(--text-primary);"></span>
                <p class="mt-2 text-sm" style="color: var(--text-secondary);">Total: <span id="usage-count" class="font-bold"></span> penggunaan</p>
            </div>
            <div class="rounded-lg overflow-hidden border" style="border-color: var(--border-color);">
                <table class="w-full">
                    <thead style="background-color: var(--bg-input);">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Pengguna</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody id="usage-table-body"></tbody>
                </table>
            </div>
            <div id="usage-empty" class="text-center py-8 hidden" style="color: var(--text-secondary);">
                <i class="bx bx-info-circle text-4xl mb-2"></i>
                <p>Belum ada yang menggunakan kode ini</p>
            </div>
        </div>
    </div>
</div>

<!-- Usage Data (Hidden) -->
@foreach($codes as $code)
<script type="application/json" id="usage-data-{{ $code->id }}">
{
    "code": "{{ $code->code }}",
    "users": [
        @foreach($code->users as $idx => $u)
        {
            "name": "{{ $u->name }}",
            "email": "{{ $u->email }}",
            "used_at": "{{ $u->pivot->used_at ? \Carbon\Carbon::parse($u->pivot->used_at)->format('d M Y, H:i') : '-' }}"
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
    ]
}
</script>
@endforeach

<!-- Modal Edit Reference Code -->
<div id="modal-edit-refcode-backdrop" class="modal-backdrop"></div>
<div id="modal-edit-refcode" class="modal-content w-full max-w-2xl">
    <div class="rounded-2xl shadow-2xl border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color: var(--border-color);">
            <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Edit Kode Referensi</h3>
            <button onclick="closeModal('modal-edit-refcode')" class="p-1 rounded-lg hover:opacity-70" style="color: var(--text-secondary);"><i class="bx bx-x text-2xl"></i></button>
        </div>
        <form id="form-edit-refcode" method="POST" class="p-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Nama *</label>
                    <input type="text" name="name" id="edit-name" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Kode</label>
                    <input type="text" name="code" id="edit-code" readonly class="w-full px-4 py-2 rounded-lg border focus:outline-none opacity-60" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Role *</label>
                    <select name="role" id="edit-role" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Maks Penggunaan *</label>
                    <input type="number" name="max_usage" id="edit-max-usage" min="0" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Tanggal Kadaluarsa</label>
                    <input type="date" name="expired_at" id="edit-expired" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('modal-edit-refcode')" class="px-4 py-2 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-lg font-medium  btn-primary"><i class="bx bx-save mr-1"></i> Update</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function editRefCode(id, name, code, role, maxUsage, expiredAt) {
    document.getElementById('form-edit-refcode').action = `/admin/reference-codes/${id}`;
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-code').value = code;
    document.getElementById('edit-role').value = role;
    document.getElementById('edit-max-usage').value = maxUsage;
    document.getElementById('edit-expired').value = expiredAt || '';
    openModal('modal-edit-refcode');
}

function showUsage(codeId) {
    const dataEl = document.getElementById('usage-data-' + codeId);
    if (!dataEl) return;
    
    const data = JSON.parse(dataEl.textContent);
    document.getElementById('usage-code').textContent = data.code;
    document.getElementById('usage-count').textContent = data.users.length;
    
    const tbody = document.getElementById('usage-table-body');
    const empty = document.getElementById('usage-empty');
    const table = tbody.closest('table').parentElement;
    
    if (data.users.length === 0) {
        table.classList.add('hidden');
        empty.classList.remove('hidden');
    } else {
        table.classList.remove('hidden');
        empty.classList.add('hidden');
        tbody.innerHTML = data.users.map((u, i) => `
            <tr style="border-bottom: 1px solid var(--border-color);">
                <td class="px-4 py-3" style="color: var(--text-secondary);">${i + 1}</td>
                <td class="px-4 py-3" style="color: var(--text-primary);">${u.name}</td>
                <td class="px-4 py-3" style="color: var(--text-secondary);">${u.email}</td>
                <td class="px-4 py-3" style="color: var(--text-secondary);">${u.used_at}</td>
            </tr>
        `).join('');
    }
    
    openModal('modal-usage');
}

function confirmToggle(formId, action) {
    Swal.fire({
        title: 'Konfirmasi',
        text: `Yakin ingin ${action} kode ini?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#eab308',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, ' + action,
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}
</script>
@endpush
@endsection
