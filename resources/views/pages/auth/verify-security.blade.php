<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Keamanan - {{ config('app.name') }}</title>
    
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
                    <i class="bx bx-shield-quarter text-white text-3xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900">Verifikasi Keamanan</h2>
                <p class="text-gray-500 text-sm mt-1">Jawab pertanyaan untuk melanjutkan</p>
            </div>
            
            <!-- Steps -->
            <div class="flex items-center justify-center gap-2 mb-6">
                <div class="flex items-center justify-center w-8 h-8 bg-green-500 text-white rounded-full text-sm font-bold">
                    <i class="bx bx-check"></i>
                </div>
                <div class="w-8 h-0.5 bg-purple-600"></div>
                <div class="flex items-center justify-center w-8 h-8 bg-purple-600 text-white rounded-full text-sm font-bold">2</div>
                <div class="w-8 h-0.5 bg-gray-200"></div>
                <div class="flex items-center justify-center w-8 h-8 bg-gray-200 text-gray-400 rounded-full text-sm font-bold">3</div>
            </div>
            
            <!-- Email Info -->
            <div class="mb-6 p-3 bg-gray-50 rounded-xl text-center">
                <p class="text-xs text-gray-500">Reset password untuk</p>
                <p class="font-medium text-gray-900">{{ $email }}</p>
            </div>
            
            <form method="POST" action="{{ route('password.verify-security') }}" class="space-y-4">
                @csrf
                
                <!-- Birth Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="bx bx-calendar mr-1"></i> Tanggal Lahir
                    </label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('birth_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Security Question -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="bx bx-help-circle mr-1"></i> {{ $question }}
                    </label>
                    <input type="text" name="security_answer" value="{{ old('security_answer') }}" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                        placeholder="Masukkan jawaban Anda">
                    @error('security_answer')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 transition shadow-lg">
                    <i class="bx bx-right-arrow-alt mr-1"></i> Verifikasi
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <a href="{{ route('password.request') }}" class="inline-flex items-center text-sm text-purple-600 hover:text-purple-700 font-medium">
                    <i class="bx bx-arrow-back mr-1"></i> Kembali
                </a>
            </div>
            
            <p class="text-center text-xs text-gray-400 mt-6">© {{ date('Y') }} E-Surat</p>
        </div>
    </div>
</body>
</html>
