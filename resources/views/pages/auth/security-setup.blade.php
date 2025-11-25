<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pengaturan Keamanan - {{ config('app.name') }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-900">
    <!-- Header Bar -->
    <div class="bg-slate-800 border-b border-slate-700">
        <div class="max-w-4xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-perkim.png') }}" alt="Logo Disperkim" class="h-10 w-auto">
                <div>
                    <h1 class="text-white font-semibold">E-Surat Perkim</h1>
                    <p class="text-slate-400 text-xs">Pengaturan Keamanan Akun</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-white text-sm flex items-center gap-1">
                    <i class="bx bx-log-out"></i> Keluar
                </button>
            </form>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-6 py-8">
        <!-- Welcome -->
        <div class="mb-6 p-6 bg-white rounded-xl shadow">
            <h2 class="text-2xl font-bold text-gray-900">Halo, {{ auth()->user()->name }}!</h2>
            <p class="text-gray-600 mt-1">Sebelum melanjutkan, lengkapi pengaturan keamanan akun Anda.</p>
        </div>
        
        <!-- Alert -->
        <div class="mb-6 p-4 bg-amber-50 border border-amber-300 rounded-lg">
            <div class="flex items-start gap-3">
                <i class="bx bx-info-circle text-amber-600 text-xl"></i>
                <div class="text-sm">
                    <p class="font-medium text-amber-800">Mengapa ini penting?</p>
                    <p class="mt-1 text-amber-700">Data ini digunakan untuk memulihkan akun jika Anda lupa password. Pastikan mengingat jawaban yang diisi.</p>
                </div>
            </div>
        </div>
        
        <!-- Form Card -->
        <div class="bg-white rounded-xl p-6 shadow-lg">
            <form method="POST" action="{{ route('security.store') }}" class="space-y-5">
                @csrf
                
                <!-- Birth Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bx bx-calendar mr-1"></i> Tanggal Lahir
                    </label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}" required
                        class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                    @error('birth_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Security Question -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bx bx-help-circle mr-1"></i> Pertanyaan Keamanan
                    </label>
                    <select name="security_question" id="securityQuestion" required onchange="toggleCustomQuestion()"
                        class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                        <option value="" class="text-gray-500">-- Pilih Pertanyaan --</option>
                        @foreach($questions as $key => $question)
                            <option value="{{ $key }}" {{ old('security_question') == $key ? 'selected' : '' }}>
                                {{ $question }}
                            </option>
                        @endforeach
                        <option value="custom" {{ old('security_question') == 'custom' ? 'selected' : '' }}>✏️ Tulis pertanyaan sendiri...</option>
                    </select>
                    @error('security_question')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Custom Question (hidden by default) -->
                <div id="customQuestionDiv" class="{{ old('security_question') == 'custom' ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bx bx-edit mr-1"></i> Tulis Pertanyaan Anda
                    </label>
                    <input type="text" name="custom_question" id="customQuestion" value="{{ old('custom_question') }}"
                        autocomplete="off" data-lpignore="true" data-form-type="other"
                        class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                        placeholder="Contoh: Apa warna favorit saya?">
                    @error('custom_question')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Security Answer -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="bx bx-message-square-detail mr-1"></i> Jawaban
                    </label>
                    <input type="text" name="security_answer" value="{{ old('security_answer') }}" required
                        autocomplete="off" data-lpignore="true" data-form-type="other"
                        class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                        placeholder="Masukkan jawaban">
                    @error('security_answer')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <p class="text-xs text-gray-500"><i class="bx bx-info-circle"></i> Jawaban tidak case-sensitive (huruf besar/kecil sama saja)</p>
                
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition flex items-center gap-2">
                        Simpan & Lanjutkan <i class="bx bx-right-arrow-alt"></i>
                    </button>
                </div>
            </form>
        </div>
        
        <p class="text-center text-xs text-slate-500 mt-8">© {{ date('Y') }} E-Surat Perkim</p>
    </div>
    
    <script>
        function toggleCustomQuestion() {
            const select = document.getElementById('securityQuestion');
            const customDiv = document.getElementById('customQuestionDiv');
            const customInput = document.getElementById('customQuestion');
            
            if (select.value === 'custom') {
                customDiv.classList.remove('hidden');
                customInput.required = true;
            } else {
                customDiv.classList.add('hidden');
                customInput.required = false;
            }
        }
        
        // Run on page load
        toggleCustomQuestion();
    </script>
</body>
</html>
