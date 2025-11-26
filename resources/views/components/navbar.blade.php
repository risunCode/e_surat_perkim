<!-- Navbar -->
<header class="sticky top-0 z-30 backdrop-blur-md border-b" style="background-color: color-mix(in srgb, var(--bg-primary) 90%, transparent); border-color: var(--border-color);">
    <div class="flex items-center justify-between h-16 px-4 lg:px-6">
        <!-- Left: Mobile menu button, Logo & Search -->
        <div class="flex items-center gap-4">
            <button onclick="toggleSidebar()" class="lg:hidden" style="color: var(--text-secondary);" aria-label="Toggle menu">
                <i class="bx bx-menu text-2xl"></i>
            </button>
            
            <!-- Government Logos -->
            <div class="flex items-center gap-2 lg:gap-3 pr-2 lg:pr-4 border-r" style="border-color: var(--border-color);">
                <img src="{{ asset('images/logo-kab.png') }}" alt="Logo Kabupaten" class="h-6 lg:h-8 w-auto" onerror="this.style.display='none'">
                <img src="{{ asset('images/logo-perkim.png') }}" alt="Logo Perkim" class="h-6 lg:h-8 w-auto" onerror="this.style.display='none'">
            </div>
            
            <form action="{{ route('incoming.index') }}" method="GET" class="hidden md:block relative">
                <input type="text" name="search" placeholder="Cari surat..." value="{{ request('search') }}" class="theme-input w-64 pl-10 pr-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);">
                <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2" style="color: var(--text-secondary);" aria-label="Cari"><i class="bx bx-search"></i></button>
            </form>
        </div>
        
        <!-- Right: Development Indicator, IP Display, Theme Toggle, Notifications & User -->
        <div class="flex items-center gap-1">
            @if(config('app.debug'))
            <!-- Development Badge (Debug Mode Only) -->
            <div class="hidden lg:flex items-center gap-2 px-3 py-1.5 rounded-lg mr-2 border-2 border-dashed border-orange-400 bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20" 
                 title="Mode Development Aktif&#10;&#10;Untuk menonaktifkan:&#10;1. Edit file .env&#10;2. Ubah APP_DEBUG=false&#10;3. Restart server&#10;&#10;⚠️ Jangan lupa nonaktifkan di production!">
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1">
                        <div class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></div>
                        <span class="text-xs font-bold text-orange-700 dark:text-orange-400">DEVELOPMENT</span>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">|</div>
                    <div class="flex items-center gap-1">
                        <i class="bx bx-desktop text-xs text-blue-600 dark:text-blue-400"></i>
                        <span class="text-xs font-mono text-blue-700 dark:text-blue-300">{{ request()->ip() }}</span>
                    </div>
                    @php
                        // Get machine IP (prioritize IPv4)
                        $machineIp = '127.0.0.1';
                        if (!empty($_SERVER['SERVER_ADDR']) && filter_var($_SERVER['SERVER_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                            $machineIp = $_SERVER['SERVER_ADDR'];
                        } else {
                            try {
                                $output = shell_exec('ipconfig');
                                if ($output && preg_match('/Wireless LAN adapter Wi-Fi:.*?IPv4 Address[.\s]*:\s*([0-9.]+)/s', $output, $matches)) {
                                    if (filter_var($matches[1], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                                        $machineIp = $matches[1];
                                    }
                                }
                            } catch (Exception $e) {
                                // Keep default
                            }
                        }
                        $port = request()->getPort();
                    @endphp
                    <div class="text-xs text-gray-500 dark:text-gray-400">|</div>
                    <div class="flex items-center gap-1">
                        <i class="bx bx-wifi text-xs text-green-600 dark:text-green-400"></i>
                        <span class="text-xs font-mono text-green-700 dark:text-green-300">{{ $machineIp }}:{{ $port }}</span>
                    </div>
                </div>
            </div>
            @else
            <!-- Production IP Display (Desktop Only) -->
            <div class="hidden lg:flex items-center gap-2 px-3 py-1.5 rounded-lg mr-2" style="background-color: var(--bg-input); border: 1px solid var(--border-color);">
                <i class="bx bx-globe text-sm" style="color: var(--text-secondary);"></i>
                <span class="text-xs font-mono" style="color: var(--text-primary);" title="IP Address Anda">{{ request()->ip() }}</span>
            </div>
            @endif
            
            <!-- Theme Selector -->
            <div class="relative dropdown-container">
                <button onclick="toggleDropdown(this)" class="p-2.5 hover:bg-[var(--bg-input)] rounded-xl transition-colors" style="color: var(--text-secondary);" title="Ganti Tema" aria-label="Ganti tema">
                    <i class="bx bx-palette text-xl"></i>
                </button>
                <!-- Theme Dropdown -->
                <div class="hidden absolute right-0 mt-2 w-48 rounded-xl shadow-xl overflow-hidden border" style="background-color: var(--bg-card); border-color: var(--border-color);">
                    <div class="p-2 text-xs font-semibold uppercase tracking-wider border-b" style="color: var(--text-secondary); border-color: var(--border-color);">Pilih Tema</div>
                    <div class="p-1">
                        <button onclick="setTheme('light')" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm hover:bg-[var(--bg-input)] transition-colors text-left" style="color: var(--text-primary);">
                            <span class="w-5 h-5 rounded-full bg-white border-2 border-gray-300 flex items-center justify-center">
                                <i class="bx bx-check text-xs text-gray-600 theme-check" data-theme="light"></i>
                            </span>
                            White
                        </button>
                        <button onclick="setTheme('dark')" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm hover:bg-[var(--bg-input)] transition-colors text-left" style="color: var(--text-primary);">
                            <span class="w-5 h-5 rounded-full bg-neutral-700 border-2 border-neutral-600 flex items-center justify-center">
                                <i class="bx bx-check text-xs text-white theme-check" data-theme="dark"></i>
                            </span>
                            Dark
                        </button>
                        <button onclick="setTheme('black')" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm hover:bg-[var(--bg-input)] transition-colors text-left" style="color: var(--text-primary);">
                            <span class="w-5 h-5 rounded-full bg-black border-2 border-neutral-800 flex items-center justify-center">
                                <i class="bx bx-check text-xs text-white theme-check" data-theme="black"></i>
                            </span>
                            Pure Black
                        </button>
                        <button onclick="setTheme('pink')" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm hover:bg-[var(--bg-input)] transition-colors text-left" style="color: var(--text-primary);">
                            <span class="w-5 h-5 rounded-full bg-gradient-to-br from-pink-200 to-pink-400 border-2 border-pink-300 flex items-center justify-center">
                                <i class="bx bx-check text-xs text-pink-800 theme-check" data-theme="pink"></i>
                            </span>
                            Pink Gradient
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Divider -->
            <div class="w-px h-6 mx-1" style="background-color: var(--border-color);"></div>
            
            <!-- Notifications -->
            <div class="relative dropdown-container">
                <button onclick="toggleDropdown(this)" class="relative p-2.5 hover:bg-[var(--bg-input)] rounded-xl transition-colors" style="color: var(--text-secondary);" aria-label="Notifikasi">
                    <i class="bx bx-bell text-xl"></i>
                    <span id="notification-badge" class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center hidden">0</span>
                </button>
                
                <!-- Dropdown -->
                <div class="hidden absolute right-0 mt-2 w-80 rounded-xl shadow-xl overflow-hidden border" style="background-color: var(--bg-card); border-color: var(--border-color);">
                    <div class="px-4 py-3 border-b flex items-center justify-between" style="border-color: var(--border-color);">
                        <h3 class="font-semibold" style="color: var(--text-primary);">Notifikasi</h3>
                        <button onclick="markAllAsRead()" class="text-xs hover:underline" style="color: var(--text-secondary);">Tandai semua dibaca</button>
                    </div>
                    <div id="notification-container" class="max-h-80 overflow-y-auto" style="scrollbar-width: none; -ms-overflow-style: none;">
                        <p class="px-4 py-3 text-sm" style="color: var(--text-secondary);">Memuat...</p>
                    </div>
                    <a href="{{ route('notifications.index') }}" class="block px-4 py-3 text-center text-sm border-t hover:underline" style="color: var(--text-secondary); border-color: var(--border-color);">
                        Lihat semua notifikasi
                    </a>
                </div>
            </div>
            
            <!-- Divider -->
            <div class="w-px h-6 mx-1" style="background-color: var(--border-color);"></div>
            
            <!-- User Menu -->
            <div class="relative dropdown-container">
                <button onclick="toggleDropdown(this)" class="flex items-center gap-2 py-1.5 px-2 hover:bg-[var(--bg-input)] rounded-xl transition-colors">
                    @if(auth()->user()->profile_picture)
                        <img src="{{ Storage::url(auth()->user()->profile_picture) }}" alt="" class="w-8 h-8 rounded-full object-cover ring-2" style="--tw-ring-color: var(--border-color);">
                    @else
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-semibold text-sm ring-2" style="background-color: var(--accent-color); --tw-ring-color: var(--border-color);">
                            {{ auth()->user()->initials }}
                        </div>
                    @endif
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium leading-tight" style="color: var(--text-primary);">{{ auth()->user()->name }}</p>
                        <p class="text-xs leading-tight" style="color: var(--text-secondary);">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Staff' }}</p>
                    </div>
                    <i class="bx bx-chevron-down" style="color: var(--text-secondary);"></i>
                </button>
                
                <!-- Dropdown -->
                <div class="hidden absolute right-0 mt-2 w-56 rounded-xl shadow-xl overflow-hidden border" style="background-color: var(--bg-card); border-color: var(--border-color);">
                    <div class="px-4 py-3 border-b" style="border-color: var(--border-color);">
                        <p class="text-sm font-medium" style="color: var(--text-primary);">{{ auth()->user()->name }}</p>
                        <p class="text-xs" style="color: var(--text-secondary);">{{ auth()->user()->email }}</p>
                        <div class="mt-2">
                            <span class="inline-block px-2 py-0.5 text-xs rounded" style="background-color: var(--bg-input); color: var(--text-secondary);">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Staff' }}</span>
                            
                            @if(config('app.debug'))
                            <!-- Development Info for Mobile -->
                            <div class="lg:hidden mt-2 p-2 rounded border-2 border-dashed border-orange-400 bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20">
                                <div class="flex items-center gap-1 mb-1">
                                    <div class="w-1.5 h-1.5 bg-orange-500 rounded-full animate-pulse"></div>
                                    <span class="text-xs font-bold text-orange-700 dark:text-orange-400">DEV MODE</span>
                                </div>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-1 text-xs">
                                        <i class="bx bx-desktop text-xs text-blue-600 dark:text-blue-400"></i>
                                        <span class="font-mono text-blue-700 dark:text-blue-300">{{ request()->ip() }}</span>
                                    </div>
                                    @php
                                        $machineIp = '127.0.0.1';
                                        if (!empty($_SERVER['SERVER_ADDR']) && filter_var($_SERVER['SERVER_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                                            $machineIp = $_SERVER['SERVER_ADDR'];
                                        } else {
                                            try {
                                                $output = shell_exec('ipconfig');
                                                if ($output && preg_match('/Wireless LAN adapter Wi-Fi:.*?IPv4 Address[.\s]*:\s*([0-9.]+)/s', $output, $matches)) {
                                                    if (filter_var($matches[1], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                                                        $machineIp = $matches[1];
                                                    }
                                                }
                                            } catch (Exception $e) {
                                                // Keep default
                                            }
                                        }
                                        $port = request()->getPort();
                                    @endphp
                                    <div class="flex items-center gap-1 text-xs">
                                        <i class="bx bx-wifi text-xs text-green-600 dark:text-green-400"></i>
                                        <span class="font-mono text-green-700 dark:text-green-300">{{ $machineIp }}:{{ $port }}</span>
                                    </div>
                                </div>
                            </div>
                            @else
                            <!-- IP for Mobile - Production Only -->
                            <div class="lg:hidden flex items-center gap-1 px-2 py-0.5 rounded text-xs mt-2 w-fit" style="background-color: var(--bg-input); color: var(--text-secondary);" title="IP Address Anda">
                                <i class="bx bx-globe text-xs"></i>
                                <span class="font-mono">{{ request()->ip() }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Akun -->
                    <div class="py-1">
                        <p class="px-4 py-1 text-xs font-semibold uppercase" style="color: var(--text-secondary);">Akun</p>
                        <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-[var(--bg-input)] transition-colors" style="color: var(--text-primary);">
                            <i class="bx bx-user text-lg" style="color: var(--text-secondary);"></i>
                            <div>
                                <p class="font-medium">Profil Saya</p>
                                <p class="text-xs" style="color: var(--text-secondary);">Kelola informasi akun</p>
                            </div>
                        </a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-[var(--bg-input)] transition-colors" style="color: var(--text-primary);">
                            <i class="bx bx-cog text-lg" style="color: var(--text-secondary);"></i>
                            <div>
                                <p class="font-medium">Pengaturan</p>
                                <p class="text-xs" style="color: var(--text-secondary);">Konfigurasi sistem</p>
                            </div>
                        </a>
                        @endif
                    </div>
                    
                    <!-- Logout -->
                    <div class="py-1 border-t" style="border-color: var(--border-color);">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 w-full px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <i class="bx bx-log-out text-lg"></i>
                                <div class="text-left">
                                    <p class="font-medium">Keluar</p>
                                    <p class="text-xs opacity-70">Akhiri sesi login</p>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
const themes = ['light', 'dark', 'black', 'pink'];

// Toggle dropdown and close others
function toggleDropdown(button) {
    const container = button.closest('.dropdown-container');
    const dropdown = button.nextElementSibling;
    const isHidden = dropdown.classList.contains('hidden');
    
    // Close all dropdowns first
    closeAllDropdowns();
    
    // If it was hidden, show it
    if (isHidden) {
        dropdown.classList.remove('hidden');
    }
}

function closeAllDropdowns() {
    document.querySelectorAll('.dropdown-container > div:not(button)').forEach(el => {
        if (!el.classList.contains('w-px')) { // Skip dividers
            el.classList.add('hidden');
        }
    });
}

// Close dropdowns when clicking outside
document.addEventListener('click', (e) => {
    if (!e.target.closest('.dropdown-container')) {
        closeAllDropdowns();
    }
});

function setTheme(theme) {
    // Remove all theme classes
    themes.forEach(t => document.documentElement.classList.remove(t));
    
    // Add new theme class (except for light which is default)
    if (theme !== 'light') {
        document.documentElement.classList.add(theme);
    }
    
    // Save to localStorage
    localStorage.setItem('theme', theme);
    
    // Update checkmarks
    updateThemeChecks(theme);
    
    // Close dropdown
    closeAllDropdowns();
}

function updateThemeChecks(currentTheme) {
    document.querySelectorAll('.theme-check').forEach(check => {
        if (check.dataset.theme === currentTheme) {
            check.style.display = 'block';
        } else {
            check.style.display = 'none';
        }
    });
}

// Initialize theme checks on page load
document.addEventListener('DOMContentLoaded', () => {
    const currentTheme = localStorage.getItem('theme') || 'light';
    updateThemeChecks(currentTheme);
});

function markAllAsRead() {
    fetch('{{ route("notifications.read-all") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }).then(() => fetchNotifications());
}
</script>
