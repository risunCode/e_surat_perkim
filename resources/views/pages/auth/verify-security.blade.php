@extends('layouts.guest')

@section('title', 'Verifikasi Keamanan')

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
                    <h1 class="text-xl font-bold text-gray-900 mb-1">VERIFIKASI KEAMANAN</h1>
                    <p class="text-sm text-gray-600">Sistem E-Surat Perkim</p>
                    <p class="text-xs text-gray-500">Dinas Perumahan dan Kawasan Permukiman</p>
                </div>
            </div>

            <!-- Content -->
            <div class="px-6 py-8">
                <!-- User Info -->
                <div class="mb-6 p-4 bg-gray-50 border border-gray-200">
                    <div class="text-center">
                        <p class="text-xs text-gray-500 mb-1">
                            @if($is_authenticated_reset ?? false)
                                Konfirmasi identitas untuk reset password
                            @else
                                Reset password untuk akun
                            @endif
                        </p>
                        <p class="font-medium text-gray-900">{{ $email }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('password.verify-security') }}">
                    @csrf
                    
                    <!-- Birth Date -->
                    <div class="mb-6">
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Lahir
                        </label>
                        <input 
                            type="date" 
                            name="birth_date" 
                            id="birth_date"
                            value="{{ old('birth_date') }}" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        @error('birth_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Security Question -->
                    <div class="mb-6">
                        <label for="security_answer" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $question }}
                        </label>
                        <input 
                            type="text" 
                            name="security_answer" 
                            id="security_answer"
                            value="{{ old('security_answer') }}" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan jawaban Anda"
                        >
                        @error('security_answer')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white py-3 px-4 font-medium transition-colors">
                        VERIFIKASI IDENTITAS
                    </button>
                </form>

                <!-- Navigation -->
                <div class="mt-6 text-center">
                    @if($is_authenticated_reset ?? false)
                        <a href="{{ route('profile.show') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 font-medium">
                            <i class="bx bx-arrow-back mr-1"></i> Kembali ke Profil
                        </a>
                    @else
                        <a href="{{ route('password.request') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 font-medium">
                            <i class="bx bx-arrow-back mr-1"></i> Kembali
                        </a>
                    @endif
                </div>
            </div>

            <!-- Footer Info -->
            <div class="bg-gray-50 border-t border-gray-200 p-6">
                <div class="bg-white border border-gray-200 p-4">
                    <h3 class="font-semibold text-gray-800 mb-2 text-sm">PETUNJUK VERIFIKASI:</h3>
                    <ol class="text-xs text-gray-600 space-y-1 list-decimal list-inside">
                        <li>Masukkan tanggal lahir yang sesuai dengan profil Anda</li>
                        <li>Jawab pertanyaan keamanan dengan benar</li>
                        <li>Jawaban bersifat case-insensitive (tidak membedakan huruf besar/kecil)</li>
                        <li>Pastikan jawaban sesuai dengan yang Anda daftarkan sebelumnya</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
