@extends('layouts.guest')

@section('title', 'Verifikasi Dokumen Digital')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-md border border-gray-200">
            <!-- Header -->
            <div class="bg-white border-b border-gray-200 px-6 py-6">
                <div class="text-center">
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <img src="{{ asset('images/logo-kab.png') }}" alt="Logo Kabupaten" class="h-8 w-auto" onerror="this.style.display='none'">
                        <img src="{{ asset('images/logo-perkim.png') }}" alt="Logo Perkim" class="h-8 w-auto" onerror="this.style.display='none'">
                    </div>
                    <h1 class="text-xl font-bold text-gray-900 mb-1">HASIL VERIFIKASI DOKUMEN</h1>
                    <p class="text-sm text-gray-600">Sistem E-Surat Perkim</p>
                    <div class="mt-4">
                        @if($status === 'valid')
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 text-green-800">
                                <span class="font-semibold">✓ DOKUMEN VALID</span>
                            </div>
                        @else
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 border border-red-200 text-red-800">
                                <span class="font-semibold">✗ DOKUMEN TIDAK VALID</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="px-6 py-8">
                @if($status === 'valid')
                    <!-- Document Details -->
                    <div class="bg-gray-50 border border-gray-200 p-6 mb-6">
                        <h3 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wide">INFORMASI DOKUMEN</h3>
                        <div class="grid md:grid-cols-2 gap-x-8 gap-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nomor Surat:</span>
                                <span class="font-medium">{{ $signature->letter->reference_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Jenis:</span>
                                <span class="font-medium">{{ $signature->metadata['letter_type'] === 'incoming' ? 'Surat Masuk' : 'Surat Keluar' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $signature->metadata['letter_type'] === 'incoming' ? 'Dari' : 'Kepada' }}:</span>
                                <span class="font-medium">{{ $signature->metadata['from_to'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal Surat:</span>
                                <span class="font-medium">{{ $signature->metadata['letter_date'] ? date('d F Y', strtotime($signature->metadata['letter_date'])) : '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ditandatangani oleh:</span>
                                <span class="font-medium">{{ $signature->user->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Waktu Tanda Tangan:</span>
                                <span class="font-medium">{{ $signature->signed_at->format('d F Y, H:i:s') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Signature Details -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Detail Tanda Tangan Digital</h3>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="space-y-2">
                                <div>
                                    <span class="text-sm text-gray-600">ID Dokumen:</span>
                                    <code class="block text-xs font-mono bg-white p-2 rounded border mt-1 word-break">{{ $signature->signature_hash }}</code>
                                </div>
                                <div class="text-xs text-gray-500 mt-2">
                                    <p>✓ Dokumen ini telah diverifikasi menggunakan teknologi hash SHA-256</p>
                                    <p>✓ Integritas dokumen terjamin dan tidak dapat diubah</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Invalid Document -->
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-red-800 mb-2">{{ $message }}</h2>
                        <p class="text-gray-600 mb-6">Dokumen ini tidak dapat diverifikasi</p>

                        <div class="bg-red-50 rounded-lg p-6 text-left">
                            <h3 class="font-semibold text-red-800 mb-3">Kemungkinan Penyebab:</h3>
                            <ul class="text-sm text-red-700 space-y-2">
                                <li>• Dokumen telah dimodifikasi atau diubah</li>
                                <li>• ID verifikasi tidak valid atau expired</li>
                                <li>• Link verifikasi rusak atau tidak lengkap</li>
                            </ul>
                        </div>
                    </div>

                    <div class="border-t pt-6">
                        <div class="text-center">
                            <p class="text-sm text-gray-500 mb-4">ID Verifikasi yang Anda masukkan:</p>
                            <code class="text-xs font-mono bg-gray-100 p-2 rounded border word-break">{{ $hash }}</code>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="border-t border-gray-200 pt-6 mt-6 text-center">
                    <a href="{{ route('document.verification') }}" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 font-medium transition-colors text-sm">
                        VERIFIKASI DOKUMEN LAIN
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
.word-break {
    word-break: break-all;
}
</style>
@endsection
