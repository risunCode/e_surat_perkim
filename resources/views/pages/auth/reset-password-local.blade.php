<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - {{ config('app.name') }}</title>
    
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
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl mb-4 shadow-lg">
                    <i class="bx bx-key text-white text-3xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900">Reset Password</h2>
                <p class="text-gray-500 text-sm mt-1">Buat password baru untuk akun Anda</p>
            </div>
            
            <!-- Steps -->
            <div class="flex items-center justify-center gap-2 mb-6">
                <div class="flex items-center justify-center w-8 h-8 bg-green-500 text-white rounded-full text-sm font-bold">
                    <i class="bx bx-check"></i>
                </div>
                <div class="w-8 h-0.5 bg-green-500"></div>
                <div class="flex items-center justify-center w-8 h-8 bg-green-500 text-white rounded-full text-sm font-bold">
                    <i class="bx bx-check"></i>
                </div>
                <div class="w-8 h-0.5 bg-green-500"></div>
                <div class="flex items-center justify-center w-8 h-8 bg-green-500 text-white rounded-full text-sm font-bold">3</div>
            </div>
            
            <!-- Success Message -->
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
                <div class="flex items-center gap-2">
                    <i class="bx bx-check-circle text-xl"></i>
                    <span>Verifikasi berhasil! Silakan buat password baru.</span>
                </div>
            </div>
            
            <form method="POST" action="{{ route('password.update.local') }}" class="space-y-4">
                @csrf
                
                <!-- New Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required minlength="8"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                            placeholder="Minimal 8 karakter">
                        <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="bx bx-hide text-xl"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                            placeholder="Ulangi password baru">
                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="bx bx-hide text-xl"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold rounded-xl hover:from-green-600 hover:to-emerald-600 transition shadow-lg">
                    <i class="bx bx-check mr-1"></i> Reset Password
                </button>
            </form>
            
            <p class="text-center text-xs text-gray-400 mt-6">© {{ date('Y') }} E-Surat</p>
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
</body>
</html>
