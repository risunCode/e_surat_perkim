@extends('layouts.app')
@section('title', 'Profil Saya')
@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Profil Saya</h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Profile Card -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Profile Info -->
            <div class="rounded-xl p-6 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="text-center mb-6">
                    @if(auth()->user()->profile_picture)
                    <img src="{{ Storage::url(auth()->user()->profile_picture) }}" class="w-24 h-24 rounded-full object-cover mx-auto mb-4 ring-4" style="--tw-ring-color: var(--border-color);">
                    @else
                    <div class="w-24 h-24 rounded-full flex items-center justify-center text-3xl font-bold text-white mx-auto mb-4" style="background-color: var(--accent-color);">{{ auth()->user()->initials }}</div>
                    @endif
                    <h2 class="text-xl font-semibold" style="color: var(--text-primary);">{{ auth()->user()->name }}</h2>
                    <p class="text-sm" style="color: var(--text-secondary);">{{ auth()->user()->email }}</p>
                    <span class="inline-block mt-2 px-3 py-1 text-xs rounded-full btn-primary">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Staff' }}</span>
                </div>
                
                <!-- Photo Upload with Crop -->
                <div class="space-y-3">
                    <input type="file" id="photo-input" accept="image/*" class="hidden" onchange="openCropModal(this)">
                    <button type="button" onclick="document.getElementById('photo-input').click()" class="w-full px-4 py-2 rounded-lg text-sm font-medium btn-primary">
                        <i class="bx bx-camera mr-1"></i> Ganti Foto Profil
                    </button>
                    <p class="text-xs text-center" style="color: var(--text-secondary);">Klik untuk pilih & crop foto</p>
                </div>
                @error('profile_picture')<p class="mt-2 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>

            @if(auth()->user()->isStaff())
            <!-- Deactivate -->
            <div class="rounded-xl p-6 border border-red-300 bg-red-50">
                <h3 class="text-lg font-semibold text-red-600 mb-2"><i class="bx bx-error-circle mr-1"></i> Zona Berbahaya</h3>
                <p class="text-sm text-gray-600 mb-2">Menonaktifkan akun akan membuat Anda tidak bisa login lagi.</p>
                <p class="text-xs text-red-500 mb-4 p-2 bg-red-100 rounded-lg">
                    <i class="bx bx-info-circle mr-1"></i> <strong>Perhatian:</strong> Hanya Administrator yang dapat mengaktifkan kembali akun Anda setelah dinonaktifkan.
                </p>
                <form action="{{ route('profile.deactivate') }}" method="POST" id="deactivate-form">
                    @csrf
                    <input type="password" name="password" required placeholder="Masukkan password untuk konfirmasi" class="w-full px-4 py-2 rounded-lg border mb-3 focus:outline-none bg-white border-red-200 text-gray-900">
                    <button type="button" onclick="confirmDeactivate()" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                        <i class="bx bx-power-off mr-1"></i> Nonaktifkan Akun Saya
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Right Column: Forms -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Update Profile -->
            <div class="rounded-xl p-6 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);"><i class="bx bx-user mr-1"></i> Informasi Profil</h3>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Nama</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Email</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                        </div>
                    </div>
                    <button type="submit" class="px-6 py-2 rounded-lg font-medium  btn-primary">
                        <i class="bx bx-save mr-1"></i> Simpan Profil
                    </button>
                </form>
            </div>

            <!-- Update Password -->
            <div class="rounded-xl p-6 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);"><i class="bx bx-lock-alt mr-1"></i> Ubah Password</h3>
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Password Saat Ini</label>
                            <input type="password" name="current_password" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Password Baru</label>
                            <input type="password" name="password" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                        </div>
                    </div>
                    <button type="submit" class="px-6 py-2 rounded-lg font-medium  btn-primary">
                        <i class="bx bx-key mr-1"></i> Ubah Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Crop Modal -->
<div id="crop-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/80" onclick="closeCropModal()"></div>
    <div class="absolute inset-4 md:inset-auto md:top-1/2 md:left-1/2 md:-translate-x-1/2 md:-translate-y-1/2 md:w-full md:max-w-lg rounded-2xl shadow-2xl overflow-hidden" style="background-color: var(--bg-card);">
        <div class="p-4 border-b flex items-center justify-between" style="border-color: var(--border-color);">
            <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Crop Foto Profil</h3>
            <button onclick="closeCropModal()" class="p-1 rounded hover:opacity-70" style="color: var(--text-secondary);">
                <i class="bx bx-x text-2xl"></i>
            </button>
        </div>
        <div class="p-4">
            <div class="relative w-full aspect-square bg-gray-900 rounded-lg overflow-hidden mb-4">
                <img id="crop-image" src="" class="max-w-full">
            </div>
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <button onclick="cropper.zoom(-0.1)" class="p-2 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);" title="Zoom Out">
                        <i class="bx bx-zoom-out text-xl"></i>
                    </button>
                    <button onclick="cropper.zoom(0.1)" class="p-2 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);" title="Zoom In">
                        <i class="bx bx-zoom-in text-xl"></i>
                    </button>
                    <button onclick="cropper.rotate(-90)" class="p-2 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);" title="Rotate">
                        <i class="bx bx-rotate-left text-xl"></i>
                    </button>
                </div>
                <div class="flex gap-2">
                    <button onclick="closeCropModal()" class="px-4 py-2 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);">Batal</button>
                    <button onclick="uploadCroppedImage()" class="px-4 py-2 rounded-lg font-medium btn-primary">
                        <i class="bx bx-check mr-1"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
let cropper = null;

function openCropModal(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('crop-image');
            img.src = e.target.result;
            
            document.getElementById('crop-modal').classList.remove('hidden');
            
            // Destroy previous cropper if exists
            if (cropper) cropper.destroy();
            
            // Initialize cropper
            cropper = new Cropper(img, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                cropBoxResizable: false,
                cropBoxMovable: false,
                guides: false,
                center: true,
                highlight: false,
                background: false,
                // Round crop preview
                ready: function() {
                    const cropBox = document.querySelector('.cropper-crop-box');
                    if (cropBox) cropBox.style.borderRadius = '50%';
                    const viewBox = document.querySelector('.cropper-view-box');
                    if (viewBox) viewBox.style.borderRadius = '50%';
                }
            });
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function closeCropModal() {
    document.getElementById('crop-modal').classList.add('hidden');
    document.getElementById('photo-input').value = '';
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
}

function uploadCroppedImage() {
    if (!cropper) return;
    
    // Show loading
    Swal.fire({
        title: 'Mengupload...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    cropper.getCroppedCanvas({
        width: 256,
        height: 256,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
    }).toBlob(function(blob) {
        const formData = new FormData();
        formData.append('profile_picture', blob, 'profile.jpg');
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route("profile.photo") }}', {
            method: 'POST',
            body: formData
        }).then(response => {
            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Foto profil berhasil diperbarui',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Gagal mengupload foto. Silakan coba lagi.',
                });
                closeCropModal();
            }
        }).catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan jaringan. Silakan coba lagi.',
            });
            closeCropModal();
        });
    }, 'image/jpeg', 0.9);
}

function confirmDeactivate() {
    Swal.fire({
        title: 'Nonaktifkan Akun?',
        html: `
            <p class="text-gray-600 mb-2">Anda yakin ingin menonaktifkan akun?</p>
            <p class="text-red-500 text-sm"><strong>⚠️ Hanya Admin yang bisa mengaktifkan kembali akun Anda!</strong></p>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Nonaktifkan',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deactivate-form').submit();
        }
    });
}
</script>
<style>
.cropper-view-box,
.cropper-face {
    border-radius: 50%;
}
</style>
@endpush
@endsection
