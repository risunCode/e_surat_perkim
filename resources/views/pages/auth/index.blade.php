<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $mode === 'register' ? 'Daftar' : 'Masuk' }} - {{ config('app.name') }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .auth-input {
            transition: all 0.2s ease;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .auth-input:hover {
            border-color: #cbd5e1;
            background-color: #f1f5f9;
        }
        .auth-input:focus {
            border-color: #2563eb;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .auth-input::placeholder {
            color: #94a3b8;
        }
        .btn-primary-auth {
            background: #2563eb;
            transition: all 0.2s ease;
        }
        .btn-primary-auth:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="min-h-screen flex">
        <!-- Left Side - Form (putih) -->
        <div class="w-full lg:w-[45%] flex flex-col bg-white">
            <!-- Header -->
            <div class="p-6">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-perkim.png') }}" alt="Logo" class="h-9 w-9 object-contain" 
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                    <div class="h-9 w-9 rounded-lg bg-blue-600 items-center justify-center hidden">
                        <i class="bx bx-envelope text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="font-semibold text-gray-900 text-sm">E-Surat Perkim</h1>
                        <p class="text-xs text-gray-400">Dinas Perumahan dan Permukiman</p>
                    </div>
                </div>
            </div>
            
            <!-- Form Container -->
            <div class="flex-1 flex items-center justify-center px-8 lg:px-16">
                <div class="w-full max-w-sm">
                    <!-- Login Form -->
                    <div id="login-form" class="{{ $mode === 'register' ? 'hidden' : '' }}">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
                            <h2 class="text-xl font-bold text-gray-900 mb-1">Masuk ke Akun</h2>
                            <p class="text-gray-400 text-sm mb-8">Silakan masukkan kredensial Anda</p>
                            
                            @if(session('status'))
                                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center gap-2">
                                    <i class="bx bx-check-circle text-lg"></i>
                                    {{ session('status') }}
                                </div>
                            @endif
                            
                            @if(session('warning'))
                                <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-lg text-sm flex items-center gap-2">
                                    <i class="bx bx-info-circle text-lg"></i>
                                    {{ session('warning') }}
                                </div>
                            @endif
                            
                            @if($errors->has('email') && $errors->first('email') == trans('auth.failed'))
                                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                                    <div class="flex items-center gap-2">
                                        <i class="bx bx-error-circle"></i>
                                        <span>Email atau kata sandi salah.</span>
                                    </div>
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                        placeholder="nama@email.com"
                                        class="auth-input w-full px-3 py-2.5 rounded-lg text-gray-900 text-sm focus:outline-none">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                                    <div class="relative flex items-center">
                                        <input type="password" name="password" id="loginPassword" required
                                            placeholder="Masukkan password"
                                            class="auth-input w-full px-3 py-2.5 rounded-lg text-gray-900 text-sm focus:outline-none pr-10">
                                        <button type="button" onclick="togglePassword('loginPassword')" 
                                            class="absolute right-3 text-gray-400 hover:text-gray-600">
                                            <i class="bx bx-hide text-lg"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="remember" 
                                            class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm text-gray-500">Ingat saya</span>
                                    </label>
                                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-700">
                                        Lupa password?
                                    </a>
                                </div>
                                
                                <button type="submit" class="btn-primary-auth w-full py-2.5 text-white font-medium rounded-md text-sm">
                                    Masuk
                                </button>
                            </form>
                            
                            <div class="mt-6 text-center">
                                <p class="text-sm text-gray-500">
                                    Belum punya akun? 
                                    <button onclick="showRegister()" class="text-blue-600 hover:text-blue-700 font-medium underline">Daftar</button>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 1: Reference Code Verification -->
                    <div id="refcode-form" class="{{ $mode === 'register' ? '' : 'hidden' }}">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
                            <h2 class="text-xl font-bold text-gray-900 mb-1">Verifikasi Kode Referensi</h2>
                            <p class="text-gray-400 text-sm mb-8">Masukkan kode referensi untuk melanjutkan pendaftaran</p>
                            
                            <div id="refcode-error" class="hidden mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                                <div class="flex items-center gap-2">
                                    <i class="bx bx-error-circle"></i>
                                    <span id="refcode-error-text"></span>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Kode Referensi <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="refcode-input" required autocomplete="off" maxlength="50"
                                        class="auth-input w-full px-3 py-2.5 rounded-lg text-gray-900 text-sm focus:outline-none uppercase"
                                        placeholder="Contoh: STAFF2025">
                                    <p class="mt-1 text-xs text-gray-400">Hubungi administrator untuk mendapatkan kode</p>
                                </div>
                                
                                <button type="button" onclick="verifyRefCode()" id="verify-btn"
                                    class="btn-primary-auth w-full py-2.5 text-white font-medium rounded-md text-sm flex items-center justify-center gap-2">
                                    <span>Verifikasi Kode</span>
                                    <i class="bx bx-loader-alt bx-spin hidden" id="verify-spinner"></i>
                                </button>
                            </div>
                            
                            <div class="mt-6 text-center">
                                <p class="text-sm text-gray-500">
                                    Sudah punya akun? 
                                    <button onclick="showLogin()" class="text-blue-600 hover:text-blue-700 font-medium underline">Masuk</button>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 2: Register Form (shown after valid refcode) -->
                    <div id="register-form" class="hidden">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
                            <div class="flex items-center gap-2 mb-1">
                                <button onclick="backToRefCode()" class="text-gray-400 hover:text-gray-600">
                                    <i class="bx bx-arrow-back text-lg"></i>
                                </button>
                                <h2 class="text-xl font-bold text-gray-900">Buat Akun Baru</h2>
                            </div>
                            <p class="text-gray-400 text-sm mb-6 ml-7">Lengkapi data untuk mendaftar</p>
                            
                            <!-- Show validated refcode info -->
                            <div id="refcode-info" class="mb-6 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center gap-2 text-green-700 text-sm">
                                    <i class="bx bx-check-circle text-lg"></i>
                                    <span>Kode valid: <strong id="validated-code"></strong></span>
                                </div>
                            </div>
                            
                            <form method="POST" action="{{ route('register') }}" class="space-y-4" id="register-form-element">
                                @csrf
                                <input type="hidden" name="reference_code" id="hidden-refcode">
                                <input type="hidden" name="_refcode_token" id="refcode-token">
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                        placeholder="Masukkan nama lengkap"
                                        class="auth-input w-full px-3 py-2.5 rounded-lg text-gray-900 text-sm focus:outline-none">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                        placeholder="nama@email.com"
                                        class="auth-input w-full px-3 py-2.5 rounded-lg text-gray-900 text-sm focus:outline-none">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                                        <div class="relative flex items-center">
                                            <input type="password" name="password" id="regPassword" required minlength="8"
                                                placeholder="Min. 8 karakter"
                                                class="auth-input w-full px-3 py-2.5 rounded-lg text-gray-900 text-sm focus:outline-none pr-10">
                                            <button type="button" onclick="togglePassword('regPassword')" 
                                                class="absolute right-3 text-gray-400 hover:text-gray-600">
                                                <i class="bx bx-hide text-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi</label>
                                        <div class="relative flex items-center">
                                            <input type="password" name="password_confirmation" id="regPasswordConfirm" required minlength="8"
                                                placeholder="Ulangi password"
                                                class="auth-input w-full px-3 py-2.5 rounded-lg text-gray-900 text-sm focus:outline-none pr-10">
                                            <button type="button" onclick="togglePassword('regPasswordConfirm')" 
                                                class="absolute right-3 text-gray-400 hover:text-gray-600">
                                                <i class="bx bx-hide text-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @error('password')
                                    <p class="text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                
                                <button type="submit" class="btn-primary-auth w-full py-2.5 text-white font-medium rounded-md text-sm">
                                    Daftar
                                </button>
                            </form>
                            
                            <div class="mt-6 text-center">
                                <p class="text-sm text-gray-500">
                                    Sudah punya akun? 
                                    <button onclick="showLogin()" class="text-blue-600 hover:text-blue-700 font-medium underline">Masuk</button>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="p-6 text-center">
                <p class="text-xs text-gray-400">© {{ date('Y') }} Dinas Perumahan dan Permukiman • E-Surat</p>
            </div>
        </div>
        
        <!-- Right Side - Branding (Blue with Background Image) -->
        <div class="hidden lg:flex lg:w-[55%] relative overflow-hidden">
            <!-- Background Images Slider -->
            <div id="slider-1" class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-100" 
                 style="background-image: url('{{ asset('images/authslider/slider-1.jpg') }}');"></div>
            <div id="slider-2" class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0" 
                 style="background-image: url('{{ asset('images/authslider/slider-2.jpg') }}');"></div>
            <div id="slider-3" class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0" 
                 style="background-image: url('{{ asset('images/authslider/slider-3.jpg') }}');"></div>
            
            <!-- Blue Overlay -->
            <div class="absolute inset-0 bg-blue-600/80"></div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col items-center justify-center w-full p-12">
                <!-- Logo -->
                <div class="mb-6">
                    <img src="{{ asset('images/logo-kab.png') }}" alt="Logo Kabupaten" class="h-28 w-auto"
                         onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%23ffffff22%22 width=%22100%22 height=%22100%22 rx=%2210%22/><text x=%2250%22 y=%2255%22 text-anchor=%22middle%22 fill=%22white%22 font-size=%2240%22>🏛️</text></svg>'">
                </div>
                
                <!-- Title -->
                <h2 class="text-2xl font-bold text-white text-center mb-1">E-Surat Perkim</h2>
                <p class="text-blue-200 text-center text-sm">Dinas Perumahan dan Permukiman</p>
            </div>
        </div>
    </div>
    
    <script>
        let validatedRefCode = null;
        let refCodeToken = null;
        
        function showLogin() {
            document.getElementById('login-form').classList.remove('hidden');
            document.getElementById('refcode-form').classList.add('hidden');
            document.getElementById('register-form').classList.add('hidden');
            validatedRefCode = null;
            refCodeToken = null;
            history.pushState({}, '', '/auth');
        }
        
        function showRegister() {
            document.getElementById('login-form').classList.add('hidden');
            document.getElementById('refcode-form').classList.remove('hidden');
            document.getElementById('register-form').classList.add('hidden');
            history.pushState({}, '', '/auth?mode=register');
        }
        
        function backToRefCode() {
            document.getElementById('register-form').classList.add('hidden');
            document.getElementById('refcode-form').classList.remove('hidden');
            validatedRefCode = null;
            refCodeToken = null;
        }
        
        async function verifyRefCode() {
            const input = document.getElementById('refcode-input');
            const code = input.value.trim().toUpperCase();
            const errorDiv = document.getElementById('refcode-error');
            const errorText = document.getElementById('refcode-error-text');
            const spinner = document.getElementById('verify-spinner');
            const btn = document.getElementById('verify-btn');
            
            if (!code) {
                errorDiv.classList.remove('hidden');
                errorText.textContent = 'Kode referensi wajib diisi.';
                return;
            }
            
            // Show loading
            spinner.classList.remove('hidden');
            btn.disabled = true;
            errorDiv.classList.add('hidden');
            
            try {
                const response = await fetch('{{ route("refcode.verify") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ code: code })
                });
                
                const data = await response.json();
                
                if (data.valid) {
                    // Store validated code and token
                    validatedRefCode = code;
                    refCodeToken = data.token;
                    
                    // Update hidden fields
                    document.getElementById('hidden-refcode').value = code;
                    document.getElementById('refcode-token').value = data.token;
                    document.getElementById('validated-code').textContent = code;
                    
                    // Show register form
                    document.getElementById('refcode-form').classList.add('hidden');
                    document.getElementById('register-form').classList.remove('hidden');
                } else {
                    errorDiv.classList.remove('hidden');
                    errorText.textContent = data.message || 'Kode referensi tidak valid.';
                }
            } catch (error) {
                errorDiv.classList.remove('hidden');
                errorText.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
            } finally {
                spinner.classList.add('hidden');
                btn.disabled = false;
            }
        }
        
        // Allow Enter key to verify
        document.getElementById('refcode-input')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                verifyRefCode();
            }
        });
        
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
        
        // Background slider
        let currentSlide = 0;
        const totalSlides = 3;
        
        function nextSlide() {
            document.getElementById(`slider-${currentSlide + 1}`).style.opacity = '0';
            currentSlide = (currentSlide + 1) % totalSlides;
            document.getElementById(`slider-${currentSlide + 1}`).style.opacity = '1';
        }
        
        setInterval(nextSlide, 5000);
    </script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Check for session expired via query param
        function showSessionExpiredAlert() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('reason') === 'session_expired') {
                // Wait for Swal to be available
                if (typeof Swal === 'undefined') {
                    setTimeout(showSessionExpiredAlert, 100);
                    return;
                }
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Sesi Berakhir',
                    text: 'Sesi Anda telah berakhir karena login dari perangkat lain.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#2563eb',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(() => {
                    // Clean URL after showing alert
                    window.history.replaceState({}, '', '/auth');
                });
            }
        }
        
        // Run on DOM ready or immediately if already ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', showSessionExpiredAlert);
        } else {
            showSessionExpiredAlert();
        }
    </script>
    
    {{-- Attack Alert Modal for Guest Users --}}
    <div id="attackAlertModal" class="fixed inset-0 z-[9999] hidden">
        <div class="fixed inset-0 bg-red-900/80 backdrop-blur-sm"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full border-4 border-red-500 overflow-hidden" style="animation: pulse-border 1s ease-in-out infinite;">
                <div class="bg-red-600 text-white p-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class='bx bx-shield-x text-2xl'></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">⚠️ PERINGATAN KEAMANAN</h2>
                        <p class="text-red-100 text-xs">Serangan terdeteksi pada sistem</p>
                    </div>
                </div>
                <div class="p-5">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                        <p class="text-red-800 font-medium text-sm mb-1">
                            <i class='bx bx-error-circle'></i> Sistem mendeteksi percobaan serangan!
                        </p>
                        <p class="text-red-600 text-xs">
                            Tim keamanan telah diberitahu dan sedang menangani masalah ini.
                        </p>
                    </div>
                    <div id="attackDetails" class="space-y-2 text-sm">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-100 rounded-lg p-2">
                                <span class="text-gray-500 text-xs">Jenis Serangan</span>
                                <p id="attackType" class="font-semibold text-red-600 uppercase text-sm">-</p>
                            </div>
                            <div class="bg-gray-100 rounded-lg p-2">
                                <span class="text-gray-500 text-xs">Tingkat Bahaya</span>
                                <p id="attackSeverity" class="font-semibold uppercase text-sm">-</p>
                            </div>
                        </div>
                        <div class="bg-gray-100 rounded-lg p-2">
                            <span class="text-gray-500 text-xs">IP Address Penyerang</span>
                            <p id="attackIp" class="font-mono font-semibold text-sm">-</p>
                        </div>
                        <div class="bg-gray-100 rounded-lg p-2">
                            <span class="text-gray-500 text-xs">Waktu Terdeteksi</span>
                            <p id="attackTime" class="font-semibold text-sm">-</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-100 p-4 flex items-center justify-between">
                    <p class="text-xs text-gray-500">
                        <i class='bx bx-info-circle'></i> Peringatan ini akan ditutup oleh admin
                    </p>
                    <button onclick="closeAttackAlert()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                        <i class='bx bx-right-arrow-alt'></i> Lanjutkan Login
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        @keyframes pulse-border {
            0%, 100% { border-color: rgb(239 68 68); }
            50% { border-color: rgb(252 165 165); }
        }
    </style>
    
    <script>
        // Check for active attack alerts (public endpoint)
        async function checkAttackAlerts() {
            try {
                const response = await fetch('/api/public/attack-alerts');
                const data = await response.json();
                
                if (data.count > 0) {
                    const alert = data.alerts[0];
                    document.getElementById('attackType').textContent = alert.type.toUpperCase();
                    document.getElementById('attackSeverity').textContent = alert.severity.toUpperCase();
                    document.getElementById('attackSeverity').className = `font-semibold uppercase text-sm ${getSeverityColor(alert.severity)}`;
                    document.getElementById('attackIp').textContent = alert.ip;
                    document.getElementById('attackTime').textContent = alert.time;
                    document.getElementById('attackAlertModal').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Failed to check attack alerts:', error);
            }
        }
        
        function getSeverityColor(severity) {
            switch (severity) {
                case 'critical': return 'text-red-600';
                case 'high': return 'text-orange-600';
                case 'medium': return 'text-yellow-600';
                case 'low': return 'text-blue-600';
                default: return 'text-gray-600';
            }
        }
        
        function closeAttackAlert() {
            document.getElementById('attackAlertModal').classList.add('hidden');
        }
        
        // Check on page load and every 30 seconds
        checkAttackAlerts();
        setInterval(checkAttackAlerts, 30000);
    </script>
</body>
</html>
