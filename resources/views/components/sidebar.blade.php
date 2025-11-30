<!-- Sidebar -->
<aside id="sidebar" class="fixed top-0 left-0 z-50 w-64 h-screen transform -translate-x-full lg:translate-x-0 transition-transform duration-300" style="background: var(--bg-sidebar);">
    <!-- Logo -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-white/10">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center bg-white/20">
                <img 
                    src="{{ asset('images/logo-perkim.png') }}" 
                    alt="Logo Perkim" 
                    class="w-full h-full rounded-lg object-cover"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'"
                >
                <i class="bx bx-envelope text-xl text-white" style="display: none;"></i>
            </div>
            <div>
                <span class="font-bold text-white text-lg">E-Surat</span>
                <p class="text-[10px] text-white/60 -mt-1">PERKIM</p>
            </div>
        </a>
        <button onclick="toggleSidebar()" class="lg:hidden text-white/70 hover:text-white" aria-label="Tutup menu">
            <i class="bx bx-x text-2xl"></i>
        </button>
    </div>
    
    <!-- Navigation -->
    @php
        $activeStyle = 'background-color: var(--sidebar-active); color: var(--sidebar-text); border-left: 3px solid white;';
        $inactiveStyle = 'color: var(--sidebar-text-muted);';
    @endphp
    <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100vh-4rem)]" style="scrollbar-width: none; -ms-overflow-style: none;">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'active' : '' }}" style="{{ request()->routeIs('dashboard') ? $activeStyle : $inactiveStyle }}">
            <i class="bx bx-home-circle text-xl"></i>
            <span class="font-medium">Beranda</span>
        </a>
        
        <!-- Transaksi Surat -->
        <div class="pt-4">
            <p class="px-3 text-xs font-semibold uppercase tracking-wider mb-2" style="color: var(--sidebar-text-muted);">Transaksi Surat</p>
            
            <a href="{{ route('incoming.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('incoming.*') ? 'active' : '' }}" style="{{ request()->routeIs('incoming.*') ? $activeStyle : $inactiveStyle }}">
                <i class="bx bx-envelope-open text-xl"></i>
                <span class="font-medium">Surat Masuk</span>
            </a>
            
            <a href="{{ route('outgoing.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('outgoing.*') ? 'active' : '' }}" style="{{ request()->routeIs('outgoing.*') ? $activeStyle : $inactiveStyle }}">
                <i class="bx bx-send text-xl"></i>
                <span class="font-medium">Surat Keluar</span>
            </a>
        </div>
        
        <!-- Buku Agenda -->
        <div class="pt-4">
            <p class="px-3 text-xs font-semibold uppercase tracking-wider mb-2" style="color: var(--sidebar-text-muted);">Buku Agenda</p>
            
            <a href="{{ route('agenda.incoming') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('agenda.incoming*') ? 'active' : '' }}" style="{{ request()->routeIs('agenda.incoming*') ? $activeStyle : $inactiveStyle }}">
                <i class="bx bx-book-open text-xl"></i>
                <span class="font-medium">Agenda Masuk</span>
            </a>
            
            <a href="{{ route('agenda.outgoing') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('agenda.outgoing*') ? 'active' : '' }}" style="{{ request()->routeIs('agenda.outgoing*') ? $activeStyle : $inactiveStyle }}">
                <i class="bx bx-book text-xl"></i>
                <span class="font-medium">Agenda Keluar</span>
            </a>
        </div>
        
        <!-- Galeri -->
        <div class="pt-4">
            <p class="px-3 text-xs font-semibold uppercase tracking-wider mb-2" style="color: var(--sidebar-text-muted);">Galeri Surat</p>
            
            <a href="{{ route('gallery.incoming') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('gallery.incoming') ? 'active' : '' }}" style="{{ request()->routeIs('gallery.incoming') ? $activeStyle : $inactiveStyle }}">
                <i class="bx bx-images text-xl"></i>
                <span class="font-medium">Galeri Masuk</span>
            </a>
            
            <a href="{{ route('gallery.outgoing') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('gallery.outgoing') ? 'active' : '' }}" style="{{ request()->routeIs('gallery.outgoing') ? $activeStyle : $inactiveStyle }}">
                <i class="bx bx-image text-xl"></i>
                <span class="font-medium">Galeri Keluar</span>
            </a>
        </div>
        
        @if(auth()->user()->isAdmin())
        <!-- Administrator -->
        <div class="pt-4">
            <p class="px-3 text-xs font-semibold uppercase tracking-wider mb-2" style="color: var(--sidebar-text-muted);">Administrator</p>
            
            <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.users.*') ? $activeStyle : $inactiveStyle }}">
                <i class="bx bx-user-circle text-xl"></i>
                <span class="font-medium">Kelola Pengguna</span>
            </a>
            
            <a href="{{ route('admin.reference-codes.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.reference-codes.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.reference-codes.*') ? $activeStyle : $inactiveStyle }}">
                <i class="bx bx-key text-xl"></i>
                <span class="font-medium">Kode Referensi</span>
            </a>
            
            <a href="{{ route('admin.classifications.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.classifications.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.classifications.*') ? $activeStyle : $inactiveStyle }}">
                <i class="bx bx-category text-xl"></i>
                <span class="font-medium">Klasifikasi</span>
            </a>
            
            <a href="{{ route('admin.statuses.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.statuses.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.statuses.*') ? $activeStyle : $inactiveStyle }}">
                <i class="bx bx-list-check text-xl"></i>
                <span class="font-medium">Status Surat</span>
            </a>
            
            <a href="{{ route('admin.settings.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" style="{{ request()->routeIs('admin.settings.*') ? $activeStyle : $inactiveStyle }}">
                <i class="bx bx-cog text-xl"></i>
                <span class="font-medium">Pengaturan</span>
            </a>
        </div>
        @endif
        
        <!-- Lainnya -->
        <div class="space-y-1">
            <p class="px-3 py-2 text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Lainnya</p>
            
            <a href="{{ route('about') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('about') ? 'active' : '' }}" style="{{ request()->routeIs('about') ? $activeStyle : $inactiveStyle }}">
                <i class="bx bx-info-circle text-xl"></i>
                <span class="font-medium">Tentang Aplikasi</span>
            </a>
        </div>
    </nav>
</aside>
