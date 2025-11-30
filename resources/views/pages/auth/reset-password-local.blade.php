@extends('layouts.guest')

@section('title', 'Password Baru')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-md border border-gray-200">
            <!-- Header -->
            <div class="bg-white border-b border-gray-200 px-6 py-6">
                <div class="text-center">
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <img src="{{ asset('images/logo-kab.png') }}" alt="Logo Kabupaten" class="h-8 w-auto" onerror="this.style.display='none'">
                        <img src="{{ asset('images/logo-perkim.png') }}" alt="Logo Perkim" class="h-8 w-auto" onerror="this.style.display='none'">
                    </div>
                    <h1 class="text-xl font-bold text-gray-900 mb-1">PASSWORD BARU</h1>
                    <p class="text-sm text-gray-600">Sistem E-Surat Perkim</p>
                    <p class="text-xs text-gray-500">Dinas Perumahan dan Kawasan Permukiman</p>
                </div>
            </div>

            <!-- Content -->
            <div class="px-6 py-8">
                <!-- Success Message -->
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 text-sm">
                    <i class="bx bx-check-circle mr-2"></i>Verifikasi berhasil! Silakan buat password baru Anda.
                </div>

                <form method="POST" action="{{ route('password.update.local') }}">
                    @csrf
                    
                    <!-- New Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password Baru
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                required 
                                minlength="8"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-10"
                                placeholder="Minimal 8 karakter"
                            >
                            <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="bx bx-hide text-xl"></i>
                            </button>
                        </div>
                        @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password Baru
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="password_confirmation" 
                                required 
                                minlength="8"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-10"
                                placeholder="Ulangi password baru"
                            >
                            <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="bx bx-hide text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white py-3 px-4 font-medium transition-colors">
                        SIMPAN PASSWORD BARU
                    </button>
                </form>
            </div>

            <!-- Footer Info -->
            <div class="bg-gray-50 border-t border-gray-200 p-6">
                <div class="bg-white border border-gray-200 p-4">
                    <h3 class="font-semibold text-gray-800 mb-2 text-sm">SYARAT PASSWORD YANG AMAN:</h3>
                    <ol class="text-xs text-gray-600 space-y-1 list-decimal list-inside">
                        <li>Minimal 8 karakter panjang</li>
                        <li>Kombinasi huruf besar dan kecil</li>
                        <li>Mengandung angka dan simbol</li>
                        <li>Hindari menggunakan informasi pribadi</li>
                        <li>Jangan gunakan password yang sama di tempat lain</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bx-hide', 'bx-show');
    } else {
        input.type = 'password';
        icon.classList.replace('bx-show', 'bx-hide');
    }
}
</script>
@endsection
