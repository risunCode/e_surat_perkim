@extends('layouts.app')
@section('title', 'Tentang Aplikasi')
@section('content')
<div class="space-y-6">
    <!-- Hero Section -->
    <div class="rounded-2xl p-8 border relative overflow-hidden" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="absolute top-0 right-0 w-64 h-64 opacity-5">
            <i class="bx bx-envelope text-[20rem] -mt-20 -mr-20" style="color: var(--accent-color);"></i>
        </div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">v2.4.0 Stable</span>
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">🔐 Digital Signatures</span>
                <span class="px-3 py-1 rounded-full text-xs font-medium" style="background-color: var(--bg-input); color: var(--text-secondary);">Laravel 12</span>
                <span class="px-3 py-1 rounded-full text-xs font-medium" style="background-color: var(--bg-input); color: var(--text-secondary);">PHP 8.3</span>
            </div>
            <h1 class="text-4xl font-bold mb-3" style="color: var(--text-primary);">E-Surat Perkim</h1>
            <p class="text-xl mb-6 max-w-2xl" style="color: var(--text-secondary);">
                Sistem Manajemen Surat Elektronik dengan <strong>QR Code Verification</strong> & Digital Signatures untuk Dinas Perumahan Rakyat dan Kawasan Permukiman
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-medium btn-primary">
                    <i class="bx bx-home"></i> Dashboard
                </a>
                <a href="https://github.com/risunCode/e_surat_perkim" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-medium" style="background-color: var(--bg-input); color: var(--text-primary);">
                    <i class="bx bxl-github"></i> Source Code
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="rounded-xl p-5 border text-center" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <i class="bx bx-envelope-open text-4xl mb-2" style="color: var(--accent-color);"></i>
            <p class="text-2xl font-bold" style="color: var(--text-primary);">∞</p>
            <p class="text-sm" style="color: var(--text-secondary);">Surat Masuk</p>
        </div>
        <div class="rounded-xl p-5 border text-center" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <i class="bx bx-send text-4xl mb-2" style="color: var(--accent-color);"></i>
            <p class="text-2xl font-bold" style="color: var(--text-primary);">∞</p>
            <p class="text-sm" style="color: var(--text-secondary);">Surat Keluar</p>
        </div>
        <div class="rounded-xl p-5 border text-center" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <i class="bx bx-transfer text-4xl mb-2" style="color: var(--accent-color);"></i>
            <p class="text-2xl font-bold" style="color: var(--text-primary);">∞</p>
            <p class="text-sm" style="color: var(--text-secondary);">Disposisi</p>
        </div>
        <div class="rounded-xl p-5 border text-center" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <i class="bx bx-qr-scan text-4xl mb-2" style="color: var(--accent-color);"></i>
            <p class="text-2xl font-bold" style="color: var(--text-primary);">QR</p>
            <p class="text-sm" style="color: var(--text-secondary);">Verification</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Features -->
        <div class="rounded-xl p-6 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <h2 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">
                <i class="bx bx-star mr-2"></i>Fitur Utama
            </h2>
            <div class="space-y-3">
                <div class="flex items-start gap-3 p-3 rounded-lg bg-blue-50" style="border: 1px solid #dbeafe;">
                    <i class="bx bx-qr-scan text-xl text-blue-600 mt-0.5"></i>
                    <div>
                        <p class="font-medium text-blue-900">QR Code Verification</p>
                        <p class="text-sm text-blue-700">Verifikasi integritas dokumen dengan QR code</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3 rounded-lg bg-purple-50" style="border: 1px solid #e9d5ff;">
                    <i class="bx bx-shield-quarter text-xl text-purple-600 mt-0.5"></i>
                    <div>
                        <p class="font-medium text-purple-900">Digital Signatures</p>
                        <p class="text-sm text-purple-700">Tanda tangan digital SHA-256 untuk setiap dokumen</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3 rounded-lg" style="background-color: var(--bg-input);">
                    <i class="bx bx-check-circle text-xl text-green-500 mt-0.5"></i>
                    <div>
                        <p class="font-medium" style="color: var(--text-primary);">Manajemen Surat Lengkap</p>
                        <p class="text-sm" style="color: var(--text-secondary);">Kelola surat masuk & keluar dengan mudah</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3 rounded-lg" style="background-color: var(--bg-input);">
                    <i class="bx bx-check-circle text-xl text-green-500 mt-0.5"></i>
                    <div>
                        <p class="font-medium" style="color: var(--text-primary);">Cross-Platform Support</p>
                        <p class="text-sm" style="color: var(--text-secondary);">Windows, Linux, macOS compatible</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3 rounded-lg" style="background-color: var(--bg-input);">
                    <i class="bx bx-check-circle text-xl text-green-500 mt-0.5"></i>
                    <div>
                        <p class="font-medium" style="color: var(--text-primary);">SweetAlert Integration</p>
                        <p class="text-sm" style="color: var(--text-secondary);">Interactive notifications & confirmations</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3 rounded-lg" style="background-color: var(--bg-input);">
                    <i class="bx bx-check-circle text-xl text-green-500 mt-0.5"></i>
                    <div>
                        <p class="font-medium" style="color: var(--text-primary);">Copy Document ID</p>
                        <p class="text-sm" style="color: var(--text-secondary);">One-click copy dengan toast notification</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3 rounded-lg" style="background-color: var(--bg-input);">
                    <i class="bx bx-check-circle text-xl text-green-500 mt-0.5"></i>
                    <div>
                        <p class="font-medium" style="color: var(--text-primary);">Multi-Theme UI</p>
                        <p class="text-sm" style="color: var(--text-secondary);">Light, Dark, Black, Pink themes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tech Stack -->
        <div class="rounded-xl p-6 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <h2 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">
                <i class="bx bx-layer mr-2"></i>Tech Stack
            </h2>
            <div class="grid grid-cols-2 gap-3">
                <div class="flex items-center gap-3 p-3 rounded-lg" style="background-color: var(--bg-input);">
                    <i class="bx bxl-php text-3xl text-indigo-500"></i>
                    <div>
                        <p class="font-medium" style="color: var(--text-primary);">PHP 8.3</p>
                        <p class="text-xs" style="color: var(--text-secondary);">Backend Runtime</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg" style="background-color: var(--bg-input);">
                    <i class="bx bxl-tailwind-css text-3xl text-cyan-500"></i>
                    <div>
                        <p class="font-medium" style="color: var(--text-primary);">TailwindCSS</p>
                        <p class="text-xs" style="color: var(--text-secondary);">Utility-first CSS</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg" style="background-color: var(--bg-input);">
                    <i class="bx bxl-javascript text-3xl text-yellow-500"></i>
                    <div>
                        <p class="font-medium" style="color: var(--text-primary);">JavaScript</p>
                        <p class="text-xs" style="color: var(--text-secondary);">Frontend Logic</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg" style="background-color: var(--bg-input);">
                    <i class="bx bxs-data text-3xl text-blue-500"></i>
                    <div>
                        <p class="font-medium" style="color: var(--text-primary);">MySQL</p>
                        <p class="text-xs" style="color: var(--text-secondary);">Database</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg" style="background-color: var(--bg-input);">
                    <i class="bx bxl-laravel text-3xl text-red-500"></i>
                    <div>
                        <p class="font-medium" style="color: var(--text-primary);">Laravel 12</p>
                        <p class="text-xs" style="color: var(--text-secondary);">PHP Framework</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg" style="background-color: var(--bg-input);">
                    <i class="bx bx-package text-3xl text-orange-500"></i>
                    <div>
                        <p class="font-medium" style="color: var(--text-primary);">Vite</p>
                        <p class="text-xs" style="color: var(--text-secondary);">Build Tool</p>
                    </div>
                </div>
            </div>
            
            <!-- Libraries -->
            <h3 class="text-sm font-semibold mt-6 mb-3" style="color: var(--text-secondary);">Libraries & Tools</h3>
            <div class="flex flex-wrap gap-2">
                <span class="px-3 py-1 rounded-full text-xs" style="background-color: var(--bg-input); color: var(--text-secondary);">Laravel Fortify</span>
                <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-700">QRious.js</span>
                <span class="px-3 py-1 rounded-full text-xs" style="background-color: var(--bg-input); color: var(--text-secondary);">SweetAlert2</span>
                <span class="px-3 py-1 rounded-full text-xs" style="background-color: var(--bg-input); color: var(--text-secondary);">Boxicons</span>
                <span class="px-3 py-1 rounded-full text-xs" style="background-color: var(--bg-input); color: var(--text-secondary);">Cropper.js</span>
                <span class="px-3 py-1 rounded-full text-xs bg-purple-100 text-purple-700">SHA-256 Hashing</span>
            </div>
        </div>
    </div>

    <!-- QR Code Section -->
    <div class="rounded-xl p-6 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                <i class="bx bx-qr-scan text-2xl text-blue-600"></i>
            </div>
            <div>
                <h2 class="text-lg font-semibold" style="color: var(--text-primary);">QR Code Verification System</h2>
                <p class="text-sm" style="color: var(--text-secondary);">Verifikasi integritas dokumen dengan teknologi blockchain-like</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 rounded-lg" style="background-color: var(--bg-input);">
                <i class="bx bx-shield-quarter text-2xl text-green-500 mb-2"></i>
                <h3 class="font-medium mb-1" style="color: var(--text-primary);">Digital Signature</h3>
                <p class="text-sm" style="color: var(--text-secondary);">Setiap dokumen memiliki SHA-256 hash yang unik</p>
            </div>
            <div class="p-4 rounded-lg" style="background-color: var(--bg-input);">
                <i class="bx bx-qr text-2xl text-blue-500 mb-2"></i>
                <h3 class="font-medium mb-1" style="color: var(--text-primary);">QR Code Generation</h3>
                <p class="text-sm" style="color: var(--text-secondary);">QR code berisi URL verifikasi untuk validasi</p>
            </div>
            <div class="p-4 rounded-lg" style="background-color: var(--bg-input);">
                <i class="bx bx-check-shield text-2xl text-purple-500 mb-2"></i>
                <h3 class="font-medium mb-1" style="color: var(--text-primary);">Public Verification</h3>
                <p class="text-sm" style="color: var(--text-secondary);">Verifikasi tanpa login, cross-platform support</p>
            </div>
        </div>
        
        <div class="mt-4 p-4 rounded-lg bg-gradient-to-r from-blue-50 to-purple-50" style="border: 1px solid #e0e7ff;">
            <div class="flex items-center gap-2 mb-2">
                <i class="bx bx-info-circle text-blue-600"></i>
                <span class="font-medium text-blue-900">Cara Kerja</span>
            </div>
            <p class="text-sm text-blue-800">Setiap surat yang dibuat/diupdate akan generate signature unik. QR code di preview dokumen mengarah ke halaman verifikasi publik yang menampilkan metadata dokumen dan status validitas.</p>
        </div>
    </div>

    <!-- Developer Section -->
    <div class="rounded-xl p-6 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="relative">
                <img src="https://avatars.githubusercontent.com/u/155391863?v=4" alt="risunCode" class="w-28 h-28 rounded-2xl shadow-xl object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                <div class="w-28 h-28 rounded-2xl flex items-center justify-center text-4xl font-bold text-white shadow-xl hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    RC
                </div>
            </div>
            <div class="text-center md:text-left flex-1">
                <p class="text-sm font-medium mb-1" style="color: var(--text-secondary);">Developed by</p>
                <h3 class="text-2xl font-bold mb-1" style="color: var(--text-primary);">risunCode</h3>
                <p class="mb-4" style="color: var(--text-secondary);">Full-Stack Developer • Open Source Enthusiast</p>
                <div class="flex flex-wrap justify-center md:justify-start gap-3">
                    <a href="https://github.com/risunCode" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-colors hover:opacity-80" style="background-color: var(--bg-input); color: var(--text-primary);">
                        <i class="bx bxl-github text-xl"></i> GitHub Profile
                    </a>
                    <a href="https://github.com/risunCode/e_surat_perkim" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium btn-primary">
                        <i class="bx bx-git-repo-forked text-xl"></i> Fork Repository
                    </a>
                </div>
            </div>
            <div class="hidden lg:block text-right">
                <p class="text-sm" style="color: var(--text-secondary);">Project Started</p>
                <p class="text-lg font-semibold" style="color: var(--text-primary);">November 2025</p>
                <p class="text-sm mt-3" style="color: var(--text-secondary);">License</p>
                <p class="font-medium" style="color: var(--text-primary);">MIT License</p>
            </div>
        </div>
    </div>

    <!-- Footer Info -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-xl p-5 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-blue-100">
                    <i class="bx bx-qr-scan text-xl text-blue-600"></i>
                </div>
                <div>
                    <p class="font-medium" style="color: var(--text-primary);">QR Verified</p>
                    <p class="text-xs" style="color: var(--text-secondary);">Document Integrity Check</p>
                </div>
            </div>
        </div>
        <div class="rounded-xl p-5 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-purple-100">
                    <i class="bx bx-shield-quarter text-xl text-purple-600"></i>
                </div>
                <div>
                    <p class="font-medium" style="color: var(--text-primary);">Digitally Signed</p>
                    <p class="text-xs" style="color: var(--text-secondary);">SHA-256 Protected</p>
                </div>
            </div>
        </div>
        <div class="rounded-xl p-5 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-green-100">
                    <i class="bx bx-devices text-xl text-green-600"></i>
                </div>
                <div>
                    <p class="font-medium" style="color: var(--text-primary);">Cross-Platform</p>
                    <p class="text-xs" style="color: var(--text-secondary);">Windows, Linux, macOS</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="text-center py-4">
        <p class="text-sm" style="color: var(--text-secondary);">
            © {{ date('Y') }} <strong>risunCode</strong>. Built with <i class="bx bx-heart text-red-500"></i> using Laravel & TailwindCSS
        </p>
    </div>
</div>
@endsection
