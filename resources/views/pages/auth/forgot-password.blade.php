@extends('layouts.guest')

@section('title', 'Reset Password')

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
                    <h1 class="text-xl font-bold text-gray-900 mb-1">RESET PASSWORD</h1>
                    <p class="text-sm text-gray-600">Sistem E-Surat Perkim</p>
                    <p class="text-xs text-gray-500">Dinas Perumahan dan Kawasan Permukiman</p>
                </div>
            </div>

            <!-- Content -->
            <div class="px-6 py-8">
                @if(session('status'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 text-sm">
                        <i class="bx bx-check-circle mr-2"></i>{{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.verify-email') }}">
                    @csrf
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Terdaftar
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            value="{{ old('email') }}" 
                            required 
                            autofocus
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan email terdaftar Anda"
                        >
                        @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white py-3 px-4 font-medium transition-colors">
                        LANJUTKAN VERIFIKASI
                    </button>
                </form>

                <!-- Navigation -->
                <div class="mt-6 text-center">
                    <a href="{{ route('auth') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 font-medium">
                        <i class="bx bx-arrow-back mr-1"></i> Kembali ke Login
                    </a>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="bg-gray-50 border-t border-gray-200 p-6">
                <div class="bg-white border border-gray-200 p-4">
                    <h3 class="font-semibold text-gray-800 mb-2 text-sm">LANGKAH RESET PASSWORD:</h3>
                    <ol class="text-xs text-gray-600 space-y-1 list-decimal list-inside">
                        <li>Masukkan email yang terdaftar di sistem</li>
                        <li>Jawab pertanyaan keamanan untuk verifikasi</li>
                        <li>Buat password baru yang aman</li>
                        <li>Login dengan password baru Anda</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
