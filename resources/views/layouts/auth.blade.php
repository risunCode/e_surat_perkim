<!DOCTYPE html>
<html lang="id">
<head>
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta name="description" content="E-Surat Perkim - Sistem Manajemen Surat Elektronik untuk instansi pemerintah. Kelola surat masuk, surat keluar, disposisi dan korespondensi dalam satu platform terintegrasi.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-perkim.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-perkim.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-black text-gray-900 dark:text-gray-100 min-h-screen flex items-center justify-center p-4 transition-colors duration-200">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-black dark:bg-white rounded-2xl mb-4">
                <i class="bx bx-envelope text-white dark:text-black text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ config('app.name') }}</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Sistem Manajemen Surat Elektronik</p>
        </div>
        
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/50 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-300 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        @endif
        
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/50 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-300 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif
        
        @yield('content')
    </div>
</body>
</html>
