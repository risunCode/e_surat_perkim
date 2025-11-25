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
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .flip-container { perspective: 1000px; }
        .flipper { 
            transition: transform 0.6s;
            transform-style: preserve-3d;
            position: relative;
            min-height: 520px;
        }
        .flip-container.flipped .flipper { transform: rotateY(180deg); }
        .front, .back {
            backface-visibility: hidden;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }
        .front { 
            z-index: 2; 
            transform: rotateY(0deg); 
        }
        .back { 
            transform: rotateY(180deg); 
        }
        .flip-container:not(.flipped) .back { pointer-events: none; }
        .flip-container.flipped .front { pointer-events: none; }
        
        .bg-gradient-purple {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        }
        
        .slide-indicator {
            transition: all 0.3s ease;
        }
        .slide-indicator.active {
            width: 24px;
            background: white;
        }
        
        /* Card Animation */
        .auth-card {
            animation: slideUp 0.6s ease-out;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Button Shine Effect */
        .btn-shine {
            position: relative;
            overflow: hidden;
        }
        .btn-shine::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg) translateX(-100%);
            transition: transform 0.6s;
        }
        .btn-shine:hover::after {
            transform: rotate(45deg) translateX(100%);
        }
        
        /* Card Glow */
        .card-glow {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 
                        0 0 40px rgba(139, 92, 246, 0.1);
        }
    </style>
</head>
<body class="min-h-screen bg-gray-900">
    <div class="min-h-screen flex">
        <!-- Left Side - Background Image Slider -->
        <div class="hidden lg:flex lg:w-3/5 relative bg-slate-900">
            <!-- Slider Images -->
            <div id="slider-1" class="slider-bg absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-100" style="background-image: url('{{ asset('images/authslider/slider-1.jpg') }}');"></div>
            <div id="slider-2" class="slider-bg absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0" style="background-image: url('{{ asset('images/authslider/slider-2.jpg') }}');"></div>
            <div id="slider-3" class="slider-bg absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0" style="background-image: url('{{ asset('images/authslider/slider-3.jpg') }}');"></div>
            <!-- Dark Overlay -->
            <div class="absolute inset-0 bg-black/40"></div>
            
            <!-- Logos & Clock - Absolute Top Right -->
            <div class="absolute top-6 right-6 z-20 flex items-center gap-3">
                <!-- Clock -->
                <div class="flex items-center gap-2 px-4 py-3 bg-white/10 backdrop-blur-md rounded-xl border border-white/20">
                    <i class="bx bx-time text-white text-xl"></i>
                    <div>
                        <p class="text-lg font-bold text-white leading-tight" id="clock">--:--</p>
                        <p class="text-xs text-white/70" id="date-short">--</p>
                    </div>
                </div>
                <!-- Logos -->
                <div class="flex items-center gap-3 px-4 py-3 bg-white/10 backdrop-blur-md rounded-xl border border-white/20">
                    <img src="{{ asset('images/logo-kab.png') }}" alt="Logo Kabupaten" class="h-10 w-auto">
                    <img src="{{ asset('images/logo-perkim.png') }}" alt="Logo Perkim" class="h-10 w-auto">
                </div>
            </div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-between p-12 text-white h-full">
                <!-- Top Badge -->
                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-lg">
                        <i class="bx bx-envelope text-xl"></i>
                        <div>
                            <p class="font-semibold text-sm">E-Surat</p>
                            <p class="text-xs text-white/70" id="badge-text">Masuk ke Akun</p>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom Content -->
                <div>
                    <h2 class="text-3xl font-bold mb-2" id="tagline-title">Kelola Surat Digital</h2>
                    <p class="text-white/80 mb-6" id="tagline-desc">Sistem manajemen surat elektronik untuk instansi modern</p>
                    
                    <!-- Slide Indicators -->
                    <div class="flex items-center gap-2">
                        <button onclick="setSlide(0)" class="slide-indicator w-2 h-2 rounded-full bg-white/50 active" data-slide="0"></button>
                        <button onclick="setSlide(1)" class="slide-indicator w-2 h-2 rounded-full bg-white/50" data-slide="1"></button>
                        <button onclick="setSlide(2)" class="slide-indicator w-2 h-2 rounded-full bg-white/50" data-slide="2"></button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Auth Forms -->
        <div class="w-full lg:w-2/5 flex items-start justify-center p-8 pt-16 bg-slate-800 overflow-hidden">
            <div class="w-full max-w-md">
                <!-- Flip Card Container -->
                <div class="flip-container {{ $mode === 'register' ? 'flipped' : '' }}" id="authCard">
                    <div class="flipper">
                        <!-- Front - Login -->
                        <div class="front">
                            <div class="auth-card card-glow bg-white rounded-2xl p-8">
                                <h2 class="text-xl font-bold text-gray-900 mb-1">Selamat Datang</h2>
                                <p class="text-gray-500 text-sm mb-6">Masuk ke akun Anda</p>
                                
                                @if(session('status'))
                                    <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center gap-2">
                                        <i class="bx bx-check-circle text-lg"></i>
                                        {{ session('status') }}
                                    </div>
                                @endif
                                
                                @if($errors->has('email') && $errors->first('email') == trans('auth.failed'))
                                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm" id="loginError">
                                        <div class="flex items-start gap-3">
                                            <i class="bx bx-error-circle text-xl"></i>
                                            <div>
                                                <p class="font-medium">Login Gagal</p>
                                                <p class="mt-1">Email atau kata sandi yang Anda masukkan salah.</p>
                                            </div>
                                            <button type="button" onclick="document.getElementById('loginError').remove()" class="ml-auto text-red-400 hover:text-red-600">
                                                <i class="bx bx-x text-xl"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                
                                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('email') border-red-300 @enderror"
                                            placeholder="Masukkan email">
                                        @error('email')
                                            @if($message != trans('auth.failed'))
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @endif
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                        <div class="relative">
                                            <input type="password" name="password" id="loginPassword" required
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                                placeholder="Password">
                                            <button type="button" onclick="togglePassword('loginPassword')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                                <i class="bx bx-hide text-xl"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                            <span class="text-sm text-gray-600">Ingat saya</span>
                                        </label>
                                        <a href="{{ route('password.request') }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium">Lupa password?</a>
                                    </div>
                                    
                                    <button type="submit" class="btn-shine w-full py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 transition shadow-lg">
                                        <i class="bx bx-log-in mr-1"></i> Masuk
                                    </button>
                                </form>
                                
                                <div class="mt-6 text-center">
                                    <p class="text-sm text-gray-500">
                                        Belum punya akun? 
                                        <button onclick="flipCard()" class="text-purple-600 hover:text-purple-700 font-semibold">Daftar di sini</button>
                                    </p>
                                </div>
                                
                                <p class="text-center text-xs text-gray-400 mt-6">© {{ date('Y') }} E-Surat</p>
                            </div>
                        </div>
                        
                        <!-- Back - Register -->
                        <div class="back">
                            <div class="auth-card card-glow bg-white rounded-2xl p-8">
                                <h2 class="text-xl font-bold text-gray-900 mb-1">Buat Akun</h2>
                                <p class="text-gray-500 text-sm mb-6">Daftar untuk mulai menggunakan</p>
                                
                                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                        <input type="text" name="name" value="{{ old('name') }}" required
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                            placeholder="Masukkan nama">
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                        <input type="email" name="email" value="{{ old('email') }}" required
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                            placeholder="Masukkan email">
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                            <div class="relative">
                                                <input type="password" name="password" id="regPassword" required minlength="8"
                                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                                    placeholder="Min. 8 karakter">
                                                <button type="button" onclick="togglePassword('regPassword')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                                    <i class="bx bx-hide text-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi</label>
                                            <div class="relative">
                                                <input type="password" name="password_confirmation" id="regPasswordConfirm" required minlength="8"
                                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                                    placeholder="Ulangi">
                                                <button type="button" onclick="togglePassword('regPasswordConfirm')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                                    <i class="bx bx-hide text-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @error('password')
                                        <p class="text-sm text-red-500 -mt-2">{{ $message }}</p>
                                    @enderror
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Referensi <span class="text-red-500">*</span></label>
                                        <input type="text" name="reference_code" value="{{ old('reference_code') }}" required
                                            autocomplete="off" data-lpignore="true" data-form-type="other"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition uppercase"
                                            placeholder="KODE REFERENSI">
                                        <p class="mt-1 text-xs text-gray-400"><i class="bx bx-info-circle"></i> Wajib diisi untuk mendaftar</p>
                                        @error('reference_code')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <button type="submit" class="btn-shine w-full py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 transition shadow-lg">
                                        <i class="bx bx-user-plus mr-1"></i> Daftar Akun
                                    </button>
                                </form>
                                
                                <div class="mt-6 text-center">
                                    <p class="text-sm text-gray-500">
                                        Sudah punya akun? 
                                        <button onclick="flipCard()" class="text-purple-600 hover:text-purple-700 font-semibold">Login di sini</button>
                                    </p>
                                </div>
                                
                                <p class="text-center text-xs text-gray-400 mt-4">© {{ date('Y') }} E-Surat</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        const taglines = [
            { title: 'Kelola Surat Digital', desc: 'Sistem manajemen surat elektronik untuk instansi modern' },
            { title: 'Kolaborasi Tim', desc: 'Bekerja lebih efektif bersama tim' },
            { title: 'Lacak Korespondensi', desc: 'Pantau alur surat masuk dan keluar dengan mudah' }
        ];
        
        let currentSlide = 0;
        
        function setSlide(index) {
            currentSlide = index;
            document.getElementById('tagline-title').textContent = taglines[index].title;
            document.getElementById('tagline-desc').textContent = taglines[index].desc;
            
            // Update indicators
            document.querySelectorAll('.slide-indicator').forEach((el, i) => {
                el.classList.toggle('active', i === index);
            });
            
            // Update background images
            document.querySelectorAll('.slider-bg').forEach((el, i) => {
                el.style.opacity = i === index ? '1' : '0';
            });
        }
        
        function flipCard() {
            const card = document.getElementById('authCard');
            const isFlipped = card.classList.toggle('flipped');
            document.getElementById('badge-text').textContent = isFlipped ? 'Pendaftaran Akun' : 'Masuk ke Akun';
            
            // Update URL without reload
            const newUrl = isFlipped ? '/auth?mode=register' : '/auth';
            history.pushState({}, '', newUrl);
        }
        
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
        
        // Auto slide
        setInterval(() => {
            currentSlide = (currentSlide + 1) % taglines.length;
            setSlide(currentSlide);
        }, 5000);
        
        // Clock
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            
            document.getElementById('clock').textContent = `${hours}:${minutes}`;
            
            const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            const dayName = days[now.getDay()];
            const date = now.getDate();
            const month = now.getMonth() + 1;
            const year = now.getFullYear();
            
            document.getElementById('date-short').textContent = `${dayName}, ${date}/${month}/${year}`;
        }
        
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>
</html>
