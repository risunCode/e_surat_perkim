<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa Password - {{ config('app.name') }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-800 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-2xl p-8 shadow-2xl">
            <!-- Header -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl mb-4 shadow-lg">
                    <i class="bx bx-lock-open text-white text-3xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900">Lupa Password?</h2>
                <p class="text-gray-500 text-sm mt-1">Masukkan email Anda untuk memverifikasi</p>
            </div>
            
            <!-- Steps -->
            <div class="flex items-center justify-center gap-2 mb-6">
                <div class="flex items-center justify-center w-8 h-8 bg-purple-600 text-white rounded-full text-sm font-bold">1</div>
                <div class="w-8 h-0.5 bg-gray-200"></div>
                <div class="flex items-center justify-center w-8 h-8 bg-gray-200 text-gray-400 rounded-full text-sm font-bold">2</div>
                <div class="w-8 h-0.5 bg-gray-200"></div>
                <div class="flex items-center justify-center w-8 h-8 bg-gray-200 text-gray-400 rounded-full text-sm font-bold">3</div>
            </div>
            
            @if(session('status'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
                    <i class="bx bx-check-circle mr-1"></i> {{ session('status') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('password.verify-email') }}" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                        placeholder="Masukkan email terdaftar">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 transition shadow-lg">
                    <i class="bx bx-right-arrow-alt mr-1"></i> Lanjutkan
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <a href="{{ route('auth') }}" class="inline-flex items-center text-sm text-purple-600 hover:text-purple-700 font-medium">
                    <i class="bx bx-arrow-back mr-1"></i> Kembali ke Login
                </a>
            </div>
            
            <p class="text-center text-xs text-gray-400 mt-6">© {{ date('Y') }} E-Surat</p>
        </div>
    </div>
</body>
</html>
