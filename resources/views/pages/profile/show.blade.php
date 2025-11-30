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
                    <img src="{{ route('profile.picture', basename(auth()->user()->profile_picture)) }}" class="w-24 h-24 rounded-full object-cover mx-auto mb-4 ring-4" style="--tw-ring-color: var(--border-color);">
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
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date', auth()->user()->birth_date ? auth()->user()->birth_date->format('Y-m-d') : '') }}" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                        </div>
                    </div>
                    <button type="submit" class="px-6 py-2 rounded-lg font-medium  btn-primary">
                        <i class="bx bx-save mr-1"></i> Simpan Profil
                    </button>
                </form>
            </div>

            <!-- Security Questions Status -->
            <div class="rounded-xl p-6 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);"><i class="bx bx-shield-quarter mr-1"></i> Pertanyaan Keamanan</h3>
                
                @if(auth()->user()->security_setup_completed)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="bx bx-check text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-medium" style="color: var(--text-primary);">Sudah Diatur</p>
                                <p class="text-sm" style="color: var(--text-secondary);">Pertanyaan keamanan aktif untuk reset password</p>
                            </div>
                        </div>
                        <button onclick="openSecurityModal()" class="px-4 py-2 rounded-lg font-medium text-sm btn-secondary">
                            <i class="bx bx-edit mr-1"></i> Ubah
                        </button>
                    </div>
                @else
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                <i class="bx bx-exclamation text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="font-medium" style="color: var(--text-primary);">Belum Diatur</p>
                                <p class="text-sm" style="color: var(--text-secondary);">Diperlukan untuk reset password</p>
                            </div>
                        </div>
                        <button onclick="openSecurityModal()" class="px-4 py-2 rounded-lg font-medium text-sm btn-primary">
                            <i class="bx bx-plus mr-1"></i> Atur Sekarang
                        </button>
                    </div>
                @endif
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
                    <div class="flex items-center justify-between">
                        <button type="submit" class="px-6 py-2 rounded-lg font-medium btn-primary">
                            <i class="bx bx-key mr-1"></i> Ubah Password
                        </button>
                        <a href="{{ route('password.request') }}" class="text-sm font-medium hover:underline" style="color: var(--accent-color);">
                            <i class="bx bx-help-circle mr-1"></i> Lupa Password Lama?
                        </a>
                    </div>
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

<!-- Security Modal -->
<div id="security-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/80" onclick="closeSecurityModal()"></div>
    <div class="absolute inset-4 md:inset-auto md:top-1/2 md:left-1/2 md:-translate-x-1/2 md:-translate-y-1/2 md:w-full md:max-w-lg rounded-2xl shadow-2xl overflow-hidden" style="background-color: var(--bg-card);">
        <!-- Step 1: Birth Date Verification -->
        <div id="birth-date-step" class="">
            <div class="p-4 border-b flex items-center justify-between" style="border-color: var(--border-color);">
                <h3 class="text-lg font-semibold" style="color: var(--text-primary);">
                    <i class="bx bx-calendar mr-2"></i>Verifikasi Tanggal Lahir
                </h3>
                <button onclick="closeSecurityModal()" class="p-1 rounded hover:opacity-70" style="color: var(--text-secondary);">
                    <i class="bx bx-x text-2xl"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bx bx-shield-quarter text-blue-600 text-2xl"></i>
                    </div>
                    <p class="text-sm" style="color: var(--text-secondary);">Masukkan tanggal lahir untuk melanjutkan</p>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Tanggal Lahir</label>
                    <input type="date" id="birth-date-input" required class="w-full px-4 py-3 rounded-lg border focus:outline-none text-center text-lg" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeSecurityModal()" class="flex-1 px-4 py-3 rounded-lg font-medium btn-secondary">
                        Batal
                    </button>
                    <button type="button" onclick="verifyBirthDate()" class="flex-1 px-4 py-3 rounded-lg font-medium btn-primary">
                        <i class="bx bx-check mr-1"></i> Lanjutkan
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 2: Security Questions -->
        <div id="security-questions-step" class="hidden">
            <div class="p-4 border-b flex items-center justify-between" style="border-color: var(--border-color);">
                <div class="flex items-center">
                    <button onclick="backToBirthDate()" class="p-1 rounded hover:opacity-70 mr-2" style="color: var(--text-secondary);">
                        <i class="bx bx-arrow-back text-xl"></i>
                    </button>
                    <h3 class="text-lg font-semibold" style="color: var(--text-primary);">
                        <i class="bx bx-shield-quarter mr-2"></i>Atur Pertanyaan Keamanan
                    </h3>
                </div>
                <button onclick="closeSecurityModal()" class="p-1 rounded hover:opacity-70" style="color: var(--text-secondary);">
                    <i class="bx bx-x text-2xl"></i>
                </button>
            </div>
            <div class="p-6">
                <form action="{{ route('profile.security') }}" method="POST" id="security-form">
                    @csrf @method('PUT')
                    <input type="hidden" name="birth_date" id="verified-birth-date">
                    
                    <div class="space-y-4">
                        <!-- Security Question -->
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Pilih Pertanyaan</label>
                            <select name="security_question" required class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                                <option value="">-- Pilih Pertanyaan --</option>
                                @php
                                    $questions = \App\Http\Controllers\SecurityQuestionController::getQuestions();
                                    $currentQuestion = auth()->user()->security_question ?? '';
                                    $isCustom = str_starts_with($currentQuestion, 'custom:');
                                @endphp
                                @foreach($questions as $key => $question)
                                    <option value="{{ $key }}" {{ !$isCustom && $currentQuestion === $key ? 'selected' : '' }}>{{ $question }}</option>
                                @endforeach
                                <option value="custom" {{ $isCustom ? 'selected' : '' }}>Pertanyaan Kustom</option>
                            </select>
                        </div>

                        <!-- Custom Question -->
                        <div id="custom-question-field-modal" style="{{ $isCustom ? '' : 'display: none;' }}">
                            <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Pertanyaan Kustom</label>
                            <input type="text" name="custom_question" value="{{ $isCustom ? substr($currentQuestion, 7) : '' }}" placeholder="Masukkan pertanyaan kustom Anda" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                        </div>

                        <!-- Security Answer -->
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Jawaban</label>
                            <input type="text" name="security_answer" required placeholder="Masukkan jawaban untuk pertanyaan keamanan" class="w-full px-4 py-2 rounded-lg border focus:outline-none" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                            <p class="text-xs mt-1" style="color: var(--text-secondary);">Jawaban ini akan digunakan untuk reset password</p>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="backToBirthDate()" class="flex-1 px-4 py-2 rounded-lg font-medium btn-secondary">
                            <i class="bx bx-arrow-back mr-1"></i> Kembali
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 rounded-lg font-medium btn-primary">
                            <i class="bx bx-shield-check mr-1"></i> Simpan
                        </button>
                    </div>
                </form>
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

// Security Modal Functions
function openSecurityModal() {
    document.getElementById('security-modal').classList.remove('hidden');
    // Reset to first step
    document.getElementById('birth-date-step').classList.remove('hidden');
    document.getElementById('security-questions-step').classList.add('hidden');
    // Clear inputs
    document.getElementById('birth-date-input').value = '';
    document.getElementById('verified-birth-date').value = '';
}

function closeSecurityModal() {
    document.getElementById('security-modal').classList.add('hidden');
    // Reset to first step
    document.getElementById('birth-date-step').classList.remove('hidden');
    document.getElementById('security-questions-step').classList.add('hidden');
}

function verifyBirthDate() {
    const birthDateInput = document.getElementById('birth-date-input');
    const birthDate = birthDateInput.value;
    
    if (!birthDate) {
        Swal.fire({
            icon: 'warning',
            title: 'Tanggal lahir diperlukan',
            text: 'Silakan masukkan tanggal lahir untuk melanjutkan',
        });
        return;
    }
    
    // Basic validation - not future date
    const today = new Date();
    const selectedDate = new Date(birthDate);
    
    if (selectedDate >= today) {
        Swal.fire({
            icon: 'error',
            title: 'Tanggal tidak valid',
            text: 'Tanggal lahir tidak boleh di masa depan',
        });
        return;
    }
    
    // Store verified birth date
    document.getElementById('verified-birth-date').value = birthDate;
    
    // Move to step 2
    document.getElementById('birth-date-step').classList.add('hidden');
    document.getElementById('security-questions-step').classList.remove('hidden');
}

function backToBirthDate() {
    document.getElementById('security-questions-step').classList.add('hidden');
    document.getElementById('birth-date-step').classList.remove('hidden');
}

// Handle custom question toggle in modal
document.addEventListener('DOMContentLoaded', function() {
    const securitySelect = document.querySelector('#security-modal select[name="security_question"]');
    const customField = document.getElementById('custom-question-field-modal');
    const customInput = document.querySelector('#security-modal input[name="custom_question"]');
    
    if (securitySelect) {
        securitySelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customField.style.display = 'block';
                customInput.required = true;
            } else {
                customField.style.display = 'none';
                customInput.required = false;
                customInput.value = '';
            }
        });
    }
});
</script>
<style>
.cropper-view-box,
.cropper-face {
    border-radius: 50%;
}
</style>
@endpush
@endsection
