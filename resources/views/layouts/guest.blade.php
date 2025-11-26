<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'E-Surat Perkim')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-perkim.png') }}">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* CSS Variables for Light Theme */
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-card: #ffffff;
            --bg-input: #f8fafc;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --accent-color: #3b82f6;
            --accent-hover: #2563eb;
        }

        /* Dark Theme */
        .dark {
            --bg-primary: #1f2937;
            --bg-secondary: #111827;
            --bg-card: #1f2937;
            --bg-input: #374151;
            --text-primary: #f9fafb;
            --text-secondary: #d1d5db;
            --border-color: #374151;
            --accent-color: #3b82f6;
            --accent-hover: #2563eb;
        }

        /* Black Theme */
        .black {
            --bg-primary: #000000;
            --bg-secondary: #0a0a0a;
            --bg-card: #111111;
            --bg-input: #1a1a1a;
            --text-primary: #ffffff;
            --text-secondary: #a1a1aa;
            --border-color: #262626;
            --accent-color: #3b82f6;
            --accent-hover: #2563eb;
        }

        /* Pink Theme */
        .pink {
            --bg-primary: #fdf2f8;
            --bg-secondary: #fce7f3;
            --bg-card: #fdf2f8;
            --bg-input: #fce7f3;
            --text-primary: #831843;
            --text-secondary: #be185d;
            --border-color: #f9a8d4;
            --accent-color: #ec4899;
            --accent-hover: #db2777;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .theme-input:focus {
            ring-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }
    </style>

    @stack('styles')
</head>

<body class="font-sans antialiased">
    <!-- Header -->
    <header class="bg-white dark:bg-gray-900 shadow-sm border-b" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-kab.png') }}" alt="Logo Kabupaten" class="h-8 w-auto" onerror="this.style.display='none'">
                    <img src="{{ asset('images/logo-perkim.png') }}" alt="Logo Perkim" class="h-8 w-auto" onerror="this.style.display='none'">
                    <div class="border-l pl-3 ml-3" style="border-color: var(--border-color);">
                        <h1 class="text-lg font-semibold" style="color: var(--text-primary);">E-Surat Perkim</h1>
                        <p class="text-xs" style="color: var(--text-secondary);">Sistem Manajemen Surat Digital</p>
                    </div>
                </div>

                <!-- Theme Toggle -->
                <div class="relative">
                    <button onclick="toggleTheme()" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" style="color: var(--text-secondary);" title="Ganti Tema">
                        <i class="bx bx-palette text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto py-8 border-t" style="border-color: var(--border-color); background-color: var(--bg-secondary);">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-kab.png') }}" alt="Logo Kabupaten" class="h-6 w-auto" onerror="this.style.display='none'">
                        <img src="{{ asset('images/logo-perkim.png') }}" alt="Logo Perkim" class="h-6 w-auto" onerror="this.style.display='none'">
                    </div>
                    <div class="text-sm" style="color: var(--text-secondary);">
                        <p class="font-semibold" style="color: var(--text-primary);">E-Surat Perkim</p>
                        <p>Sistem Manajemen Surat Digital</p>
                        <p>Dinas Perumahan dan Kawasan Permukiman</p>
                    </div>
                </div>
                <div class="text-xs" style="color: var(--text-secondary);">
                    <p>&copy; {{ date('Y') }} E-Surat Perkim. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Theme management
        const themes = ['light', 'dark', 'black', 'pink'];
        let currentThemeIndex = 0;

        function toggleTheme() {
            currentThemeIndex = (currentThemeIndex + 1) % themes.length;
            const newTheme = themes[currentThemeIndex];
            setTheme(newTheme);
        }

        function setTheme(theme) {
            // Remove all theme classes
            themes.forEach(t => document.documentElement.classList.remove(t));
            
            // Add new theme class (except for light which is default)
            if (theme !== 'light') {
                document.documentElement.classList.add(theme);
            }
            
            // Save to localStorage
            localStorage.setItem('theme', theme);
            currentThemeIndex = themes.indexOf(theme);
        }

        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme') || 'light';
            setTheme(savedTheme);
        });
    </script>

    @stack('scripts')
</body>
</html>
