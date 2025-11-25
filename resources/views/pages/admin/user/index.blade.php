@extends('layouts.app')
@section('title', 'Kelola Pengguna')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div><h1 class="text-2xl font-bold" style="color: var(--text-primary);">Kelola Pengguna</h1><p style="color: var(--text-secondary);">Manajemen pengguna sistem</p></div>
        <button onclick="openModal('modal-create-user')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium btn-primary"><i class="bx bx-plus"></i> Tambah</button>
    </div>
    <div class="rounded-xl p-4 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/email..." class="flex-1 min-w-[200px] px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
            <select name="role" aria-label="Filter role" class="px-4 py-2 rounded-lg border" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                <option value="">Semua Role</option>
                @foreach($roles as $v => $l)<option value="{{ $v }}" {{ request('role') == $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach
            </select>
            <select name="status" aria-label="Filter status" class="px-4 py-2 rounded-lg border" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <button type="submit" class="px-4 py-2 rounded-lg btn-primary" aria-label="Cari"><i class="bx bx-search"></i></button>
        </form>
    </div>
    <div class="rounded-xl overflow-hidden border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="overflow-x-auto">
        <table class="w-full">
            <thead style="background-color: var(--bg-input);"><tr>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Pengguna</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Role</th>
                <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Status</th>
                <th class="px-4 py-3 text-right text-xs font-semibold uppercase" style="color: var(--text-secondary);">Aksi</th>
            </tr></thead>
            <tbody class="divide-y" style="border-color: var(--border-color);">
                @forelse($users as $user)
                <tr class="hover:opacity-80">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold btn-primary">{{ $user->initials }}</div>
                            <div><p class="font-medium" style="color: var(--text-primary);">{{ $user->name }}</p><p class="text-sm" style="color: var(--text-secondary);">{{ $user->email }}</p></div>
                        </div>
                    </td>
                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded" style="background-color: var(--bg-input); color: var(--text-secondary);">{{ $user->role === 'admin' ? 'Admin' : 'Staff' }}</span></td>
                    <td class="px-4 py-3">
                        @if($user->is_active)<span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded">Aktif</span>
                        @else<span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded">Nonaktif</span>@endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->phone }}', '{{ $user->role }}')" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs" style="background-color: var(--bg-input); color: var(--text-secondary);"><i class="bx bx-edit"></i> Edit</button>
                            <form id="toggle-user-{{ $user->id }}" action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">@csrf<button type="button" onclick="confirmToggle('toggle-user-{{ $user->id }}', '{{ $user->is_active ? 'nonaktifkan' : 'aktifkan' }}')" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs {{ $user->is_active ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}"><i class="bx bx-power-off"></i> {{ $user->is_active ? 'Nonaktif' : 'Aktifkan' }}</button></form>
                            <form id="delete-user-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">@csrf @method('DELETE')<button type="button" onclick="confirmDelete('delete-user-{{ $user->id }}')" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs bg-red-100 text-red-600 hover:bg-red-200"><i class="bx bx-trash"></i> Hapus</button></form>
                        </div>
                    </td>
                </tr>
                @empty<tr><td colspan="4" class="px-4 py-12 text-center" style="color: var(--text-secondary);">Tidak ada data</td></tr>@endforelse
            </tbody>
        </table>
        </div>
        @if($users->hasPages())<div class="px-4 py-3 border-t" style="border-color: var(--border-color);">{{ $users->withQueryString()->links() }}</div>@endif
    </div>
</div>

<!-- Modal Create User -->
<div id="modal-create-user-backdrop" class="modal-backdrop"></div>
<div id="modal-create-user" class="modal-content w-full max-w-3xl">
    <div class="rounded-2xl shadow-2xl border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color: var(--border-color);">
            <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Tambah Pengguna</h3>
            <button onclick="closeModal('modal-create-user')" class="p-1 rounded-lg hover:opacity-70" style="color: var(--text-secondary);"><i class="bx bx-x text-2xl"></i></button>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Nama *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Email *</label>
                    <input type="email" name="email" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Telepon</label>
                    <input type="text" name="phone" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Role *</label>
                    <select name="role" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                        @foreach($roles as $v => $l)<option value="{{ $v }}">{{ $l }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Password *</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Konfirmasi Password *</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('modal-create-user')" class="px-4 py-2 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-lg font-medium btn-primary"><i class="bx bx-save mr-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit User -->
<div id="modal-edit-user-backdrop" class="modal-backdrop"></div>
<div id="modal-edit-user" class="modal-content w-full max-w-3xl">
    <div class="rounded-2xl shadow-2xl border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color: var(--border-color);">
            <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Edit Pengguna</h3>
            <button onclick="closeModal('modal-edit-user')" class="p-1 rounded-lg hover:opacity-70" style="color: var(--text-secondary);"><i class="bx bx-x text-2xl"></i></button>
        </div>
        <form id="form-edit-user" method="POST" class="p-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Nama *</label>
                    <input type="text" name="name" id="edit-user-name" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Email *</label>
                    <input type="email" name="email" id="edit-user-email" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Telepon</label>
                    <input type="text" name="phone" id="edit-user-phone" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Role *</label>
                    <select name="role" id="edit-user-role" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                        @foreach($roles as $v => $l)<option value="{{ $v }}">{{ $l }}</option>@endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Password Baru <span class="opacity-60">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('modal-edit-user')" class="px-4 py-2 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-lg font-medium btn-primary"><i class="bx bx-save mr-1"></i> Update</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function editUser(id, name, email, phone, role) {
    document.getElementById('form-edit-user').action = `/admin/users/${id}`;
    document.getElementById('edit-user-name').value = name;
    document.getElementById('edit-user-email').value = email;
    document.getElementById('edit-user-phone').value = phone || '';
    document.getElementById('edit-user-role').value = role;
    openModal('modal-edit-user');
}

function confirmToggle(formId, action) {
    Swal.fire({
        title: 'Konfirmasi',
        text: `Yakin ingin ${action} pengguna ini?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#eab308',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, ' + action,
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) document.getElementById(formId).submit();
    });
}
</script>
@endpush
@endsection
