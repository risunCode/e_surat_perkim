<!DOCTYPE html>
<html lang="id">
<head>
    <script>
        // Theme initialization
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme !== 'light') {
                document.documentElement.classList.add(theme);
            }
        })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    <meta name="description" content="E-Surat Perkim - Sistem Manajemen Surat Elektronik untuk instansi pemerintah. Kelola surat masuk, surat keluar, disposisi dan korespondensi dalam satu platform terintegrasi.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-perkim.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-perkim.png') }}">
    
    <!-- Preconnect to CDNs -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://unpkg.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    
    <!-- Fonts (non-blocking) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    
    <!-- Icons (deferred) -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    
    <!-- SweetAlert2 (deferred) -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Hide scrollbar */
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { scrollbar-width: none; -ms-overflow-style: none; }
        #notification-container::-webkit-scrollbar { display: none; }
        #sidebar nav::-webkit-scrollbar { display: none; }
        
        /* SweetAlert2 Dark Theme Override */
        .dark .swal2-popup {
            background: #171717 !important;
            color: #f5f5f5 !important;
        }
        .dark .swal2-title { color: #ffffff !important; }
        .dark .swal2-html-container { color: #a3a3a3 !important; }
        .dark .swal2-input, .dark .swal2-textarea, .dark .swal2-select {
            background: #262626 !important;
            border-color: #404040 !important;
            color: #ffffff !important;
        }
        .dark .swal2-validation-message {
            background: #262626 !important;
            color: #f87171 !important;
        }
        /* Table Responsive */
        .overflow-x-auto table {
            min-width: 700px;
        }
        
        /* Modal Styles */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
        }
        .modal-backdrop.active {
            opacity: 1;
            visibility: visible;
        }
        .modal-content {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.95);
            z-index: 101;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
            max-height: 90vh;
            overflow-y: auto;
        }
        .modal-content.active {
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, -50%) scale(1);
        }
    </style>
    
    @stack('styles')
</head>
<body class="min-h-screen transition-colors duration-300" style="background-color: var(--bg-secondary); color: var(--text-primary);">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('components.sidebar')
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col lg:ml-64 min-w-0 overflow-x-hidden">
            <!-- Navbar -->
            @include('components.navbar')
            
            <!-- Page Content -->
            <main class="flex-1 p-4 lg:p-6 min-w-0">
                {{-- Session alerts now handled by SweetAlert2 Toast --}}
                
                @yield('content')
            </main>
            
            <!-- Footer -->
            @include('components.footer')
        </div>
    </div>
    
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>
    
    <!-- Gallery Lightbox -->
    <div id="gallery-lightbox" class="fixed inset-0 z-50 hidden">
        <!-- Backdrop with blur -->
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeGallery()"></div>
        
        <!-- Header -->
        <div class="absolute top-0 left-0 right-0 p-4 flex items-center justify-between z-10">
            <div class="flex items-center gap-4">
                <span id="gallery-counter" class="text-white/80 text-sm font-medium bg-black/30 px-3 py-1 rounded-full">1 / 1</span>
                <span id="gallery-filename" class="text-white/80 text-sm truncate max-w-xs"></span>
            </div>
            <div class="flex items-center gap-2">
                <a id="gallery-download" href="#" target="_blank" class="p-2 text-white/80 hover:text-white bg-black/30 rounded-lg">
                    <i class="bx bx-download text-xl"></i>
                </a>
                <button onclick="closeGallery()" class="p-2 text-white/80 hover:text-white bg-black/30 rounded-lg">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Content -->
        <div class="absolute inset-0 flex items-center justify-center p-4 pt-16 pb-20">
            <div id="gallery-content" class="max-w-full max-h-full flex items-center justify-center"></div>
        </div>
        
        <!-- Navigation -->
        <button id="gallery-prev" onclick="prevGallery()" class="absolute left-4 top-1/2 -translate-y-1/2 p-4 text-white hover:text-white bg-black/50 hover:bg-black/70 rounded-full transition-all z-10 hidden">
            <i class="bx bx-chevron-left text-4xl"></i>
        </button>
        <button id="gallery-next" onclick="nextGallery()" class="absolute right-4 top-1/2 -translate-y-1/2 p-4 text-white hover:text-white bg-black/50 hover:bg-black/70 rounded-full transition-all z-10 hidden">
            <i class="bx bx-chevron-right text-4xl"></i>
        </button>
        
        <!-- Zoom Controls -->
        <div id="gallery-zoom-controls" class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2 bg-black/40 backdrop-blur-sm rounded-full px-3 py-2">
            <button onclick="zoomOut()" class="p-1 text-white/80 hover:text-white">
                <i class="bx bx-minus text-lg"></i>
            </button>
            <span id="gallery-zoom" class="text-white/80 text-sm min-w-[50px] text-center">100%</span>
            <button onclick="zoomIn()" class="p-1 text-white/80 hover:text-white">
                <i class="bx bx-plus text-lg"></i>
            </button>
            <div class="w-px h-4 bg-white/20 mx-1"></div>
            <button onclick="resetZoom()" class="p-1 text-white/80 hover:text-white text-xs">Reset</button>
        </div>
        
        <!-- Footer hint -->
        <div class="absolute bottom-16 left-1/2 -translate-x-1/2 text-white/40 text-xs">
            Klik gambar untuk zoom • Scroll +/- • ← → Navigasi • ESC Tutup
        </div>
    </div>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
        
        // Notification polling
        function fetchNotifications() {
            fetch('{{ route("notifications.unread") }}')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notification-badge');
                    const container = document.getElementById('notification-container');
                    
                    if (data.unread_count > 0) {
                        badge.textContent = data.unread_count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                    
                    if (container) {
                        container.innerHTML = data.notifications.map(n => `
                            <a href="#" onclick="markAndGo(${n.id}, '${n.link}')" class="block px-4 py-3 hover:opacity-80 border-b transition-colors ${n.is_read ? 'opacity-60' : ''}" style="border-color: var(--border-color); ${n.is_read ? 'background-color: var(--bg-input);' : ''}">
                                <div class="flex items-start gap-3">
                                    <i class="bx ${n.icon} text-xl" style="color: ${n.is_read ? 'var(--text-secondary)' : 'var(--accent-color)'};"></i>
                                    <div>
                                        <p class="text-sm ${n.is_read ? '' : 'font-medium'}" style="color: ${n.is_read ? 'var(--text-secondary)' : 'var(--text-primary)'};">${n.title}</p>
                                        <p class="text-xs" style="color: var(--text-secondary);">${n.message}</p>
                                    </div>
                                </div>
                            </a>
                        `).join('') || '<p class="px-4 py-3 text-sm" style="color: var(--text-secondary);">Tidak ada notifikasi</p>';
                    }
                });
        }
        
        // Mark notification as read then navigate
        function markAndGo(id, link) {
            fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).finally(() => {
                window.location.href = link;
            });
        }
        
        // Poll every 2 minutes
        setInterval(fetchNotifications, 120000);
        fetchNotifications();
    </script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert2 Helper Functions
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        function showSuccess(message) {
            Toast.fire({ icon: 'success', title: message });
        }

        function showError(message) {
            Toast.fire({ icon: 'error', title: message });
        }

        function confirmDelete(formId, title = 'Yakin ingin menghapus?') {
            Swal.fire({
                title: title,
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#000000',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        function confirmAction(callback, title = 'Yakin?', text = '') {
            Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#000000',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) callback();
            });
        }

        // Gallery Lightbox - Simple zoom with drag
        let galleryFiles = [];
        let galleryIndex = 0;
        let galleryZoom = 1;
        let isDragging = false;
        let dragStart = { x: 0, y: 0 };
        let imgPos = { x: 0, y: 0 };
        
        function openGallery(files, startIndex = 0) {
            galleryFiles = files;
            galleryIndex = startIndex;
            showGalleryItem();
            document.getElementById('gallery-lightbox').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Show/hide navigation based on file count
            const prevBtn = document.getElementById('gallery-prev');
            const nextBtn = document.getElementById('gallery-next');
            if (files.length > 1) {
                prevBtn.classList.remove('hidden');
                nextBtn.classList.remove('hidden');
            } else {
                prevBtn.classList.add('hidden');
                nextBtn.classList.add('hidden');
            }
        }
        
        function closeGallery() {
            document.getElementById('gallery-lightbox').classList.add('hidden');
            document.body.style.overflow = '';
            resetZoom();
        }
        
        function prevGallery() {
            galleryIndex = (galleryIndex - 1 + galleryFiles.length) % galleryFiles.length;
            resetZoom();
            showGalleryItem();
        }
        
        function nextGallery() {
            galleryIndex = (galleryIndex + 1) % galleryFiles.length;
            resetZoom();
            showGalleryItem();
        }
        
        function resetZoom() {
            galleryZoom = 1;
            imgPos = { x: 0, y: 0 };
            applyZoom();
        }
        
        function zoomIn() {
            galleryZoom = Math.min(galleryZoom + 0.5, 4);
            applyZoom();
        }
        
        function zoomOut() {
            galleryZoom = Math.max(galleryZoom - 0.5, 1);
            if (galleryZoom === 1) imgPos = { x: 0, y: 0 };
            applyZoom();
        }
        
        function toggleZoom() {
            if (galleryZoom > 1) {
                galleryZoom = 1;
                imgPos = { x: 0, y: 0 };
            } else {
                galleryZoom = 2;
            }
            applyZoom();
        }
        
        function applyZoom() {
            const img = document.querySelector('#gallery-content img');
            if (img) {
                img.style.transform = `translate(${imgPos.x}px, ${imgPos.y}px) scale(${galleryZoom})`;
                img.style.cursor = galleryZoom > 1 ? (isDragging ? 'grabbing' : 'grab') : 'zoom-in';
            }
            const display = document.getElementById('gallery-zoom');
            if (display) display.textContent = Math.round(galleryZoom * 100) + '%';
        }
        
        function showGalleryItem() {
            const file = galleryFiles[galleryIndex];
            const ext = file.name.split('.').pop().toLowerCase();
            const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(ext);
            const isPdf = ext === 'pdf';
            
            const content = document.getElementById('gallery-content');
            const counter = document.getElementById('gallery-counter');
            const filename = document.getElementById('gallery-filename');
            const downloadBtn = document.getElementById('gallery-download');
            const zoomControls = document.getElementById('gallery-zoom-controls');
            
            counter.textContent = `${galleryIndex + 1} / ${galleryFiles.length}`;
            filename.textContent = file.name;
            downloadBtn.href = file.url;
            
            if (isImage) {
                zoomControls.classList.remove('hidden');
                content.innerHTML = `<img src="${file.url}" class="max-w-full max-h-[70vh] rounded-lg shadow-2xl transition-transform duration-100 cursor-zoom-in select-none" id="gallery-img" draggable="false">`;
                applyZoom();
                
                // Setup image interactions
                const img = document.getElementById('gallery-img');
                img.addEventListener('click', (e) => {
                    if (!dragMoved) toggleZoom();
                });
                img.addEventListener('mousedown', (e) => {
                    if (galleryZoom > 1) {
                        e.preventDefault();
                        isDragging = true;
                        dragStart = { x: e.clientX - imgPos.x, y: e.clientY - imgPos.y };
                        img.style.cursor = 'grabbing';
                    }
                });
            } else if (isPdf) {
                zoomControls.classList.add('hidden');
                content.innerHTML = `<iframe src="${file.url}" class="w-full max-w-4xl h-[70vh] rounded-lg bg-white"></iframe>`;
            } else {
                zoomControls.classList.add('hidden');
                content.innerHTML = `
                    <div class="text-center p-12 rounded-lg" style="background-color: var(--bg-card);">
                        <i class="bx bx-file text-6xl mb-4" style="color: var(--text-secondary);"></i>
                        <p class="text-lg font-medium" style="color: var(--text-primary);">${file.name}</p>
                        <p class="text-sm mt-2" style="color: var(--text-secondary);">Preview tidak tersedia untuk file ini</p>
                    </div>`;
            }
        }
        
        // Scroll wheel zoom
        document.getElementById('gallery-lightbox')?.addEventListener('wheel', (e) => {
            const img = document.querySelector('#gallery-content img');
            if (img) {
                e.preventDefault();
                if (e.deltaY < 0) zoomIn();
                else zoomOut();
            }
        }, { passive: false });
        
        // Mouse drag for panning zoomed image
        let dragMoved = false;
        document.addEventListener('mousemove', (e) => {
            if (isDragging && galleryZoom > 1) {
                dragMoved = true;
                imgPos = { x: e.clientX - dragStart.x, y: e.clientY - dragStart.y };
                applyZoom();
            }
        });
        document.addEventListener('mouseup', () => {
            if (isDragging) {
                setTimeout(() => { isDragging = false; dragMoved = false; }, 10);
                const img = document.getElementById('gallery-img');
                if (img) img.style.cursor = galleryZoom > 1 ? 'grab' : 'zoom-in';
            }
        });
        
        // Legacy single file preview (calls gallery with single item)
        function previewFile(url, filename) {
            openGallery([{ url, name: filename }], 0);
        }
        
        // Keyboard navigation for gallery
        document.addEventListener('keydown', (e) => {
            const gallery = document.getElementById('gallery-lightbox');
            if (gallery && !gallery.classList.contains('hidden')) {
                if (e.key === 'Escape') closeGallery();
                if (e.key === 'ArrowLeft') prevGallery();
                if (e.key === 'ArrowRight') nextGallery();
                if (e.key === '+' || e.key === '=') zoomIn();
                if (e.key === '-') zoomOut();
                if (e.key === '0') resetZoom();
            }
        });

        // Preview multiple files before upload
        function previewMultipleFiles(input, previewContainerId) {
            const container = document.getElementById(previewContainerId);
            if (!container) return;
            container.innerHTML = '';
            
            if (input.files && input.files.length > 0) {
                let totalSize = 0;
                const files = Array.from(input.files);
                files.forEach(f => totalSize += f.size);
                const totalMB = (totalSize / 1024 / 1024).toFixed(1);
                
                // Summary + toggle button
                const summaryDiv = document.createElement('div');
                summaryDiv.className = 'flex items-center justify-between py-2';
                summaryDiv.innerHTML = `
                    <span class="text-sm" style="color: var(--text-primary);"><strong>${files.length}</strong> file dipilih • Total: <strong>${totalMB} MB</strong></span>
                    <button type="button" onclick="togglePreviewTable('${previewContainerId}-table')" class="text-xs px-2 py-1 rounded" style="background-color: var(--accent-color); color: white;">
                        <i class="bx bx-show mr-1"></i>Lihat Preview
                    </button>`;
                container.appendChild(summaryDiv);
                
                // Table (hidden by default)
                const tableDiv = document.createElement('div');
                tableDiv.id = previewContainerId + '-table';
                tableDiv.className = 'hidden mt-2 rounded-lg border overflow-hidden';
                tableDiv.style.cssText = 'border-color: var(--border-color);';
                
                let tableHTML = `<table class="w-full text-sm">
                    <thead style="background-color: var(--bg-input);">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium" style="color: var(--text-secondary);">Preview</th>
                            <th class="px-3 py-2 text-left text-xs font-medium" style="color: var(--text-secondary);">Nama File</th>
                            <th class="px-3 py-2 text-left text-xs font-medium" style="color: var(--text-secondary);">Tipe</th>
                            <th class="px-3 py-2 text-right text-xs font-medium" style="color: var(--text-secondary);">Ukuran</th>
                        </tr>
                    </thead>
                    <tbody>`;
                
                files.forEach((file, idx) => {
                    const ext = file.name.split('.').pop().toLowerCase();
                    const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(ext);
                    const sizeMB = file.size > 1024*1024 ? (file.size/1024/1024).toFixed(1) + ' MB' : (file.size/1024).toFixed(0) + ' KB';
                    const iconClass = ext === 'pdf' ? 'bxs-file-pdf text-red-500' : (isImage ? 'bx-image text-green-500' : 'bx-file text-blue-500');
                    const typeBadge = isImage ? 'bg-green-100 text-green-700' : (ext === 'pdf' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700');
                    
                    tableHTML += `<tr style="border-top: 1px solid var(--border-color);">
                        <td class="px-3 py-2">
                            <div id="thumb-${previewContainerId}-${idx}" class="w-10 h-10 rounded flex items-center justify-center" style="background-color: var(--bg-input);">
                                <i class="bx ${iconClass} text-xl"></i>
                            </div>
                        </td>
                        <td class="px-3 py-2" style="color: var(--text-primary);">
                            <p class="truncate max-w-[200px]">${file.name}</p>
                        </td>
                        <td class="px-3 py-2">
                            <span class="px-2 py-0.5 text-xs rounded ${typeBadge}">${ext.toUpperCase()}</span>
                        </td>
                        <td class="px-3 py-2 text-right" style="color: var(--text-secondary);">${sizeMB}</td>
                    </tr>`;
                });
                
                tableHTML += '</tbody></table>';
                tableDiv.innerHTML = tableHTML;
                container.appendChild(tableDiv);
                
                // Generate image thumbnails
                files.forEach((file, idx) => {
                    const ext = file.name.split('.').pop().toLowerCase();
                    const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(ext);
                    if (isImage) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const thumb = document.getElementById(`thumb-${previewContainerId}-${idx}`);
                            if (thumb) thumb.innerHTML = `<img src="${e.target.result}" class="w-10 h-10 rounded object-cover">`;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        }
        
        function togglePreviewTable(tableId) {
            const table = document.getElementById(tableId);
            if (table) {
                table.classList.toggle('hidden');
                const btn = table.previousElementSibling.querySelector('button');
                if (btn) {
                    const isHidden = table.classList.contains('hidden');
                    btn.innerHTML = isHidden ? '<i class="bx bx-show mr-1"></i>Lihat Preview' : '<i class="bx bx-hide mr-1"></i>Sembunyikan';
                }
            }
        }

        // File Size Display
        function showFileSize(input, targetId) {
            const target = document.getElementById(targetId);
            if (input.files && input.files[0]) {
                const size = input.files[0].size;
                const sizeMB = (size / 1024 / 1024).toFixed(2);
                const sizeKB = (size / 1024).toFixed(0);
                
                if (size > 2 * 1024 * 1024) {
                    target.innerHTML = `<span class="text-red-500">⚠️ ${sizeMB} MB - Terlalu besar!</span>`;
                } else if (size > 1024 * 1024) {
                    target.innerHTML = `<span class="text-green-600">✓ ${sizeMB} MB</span>`;
                } else {
                    target.innerHTML = `<span class="text-green-600">✓ ${sizeKB} KB</span>`;
                }
            } else {
                target.innerHTML = '';
            }
        }

        // Modal Functions
        function openModal(modalId) {
            document.getElementById(modalId + '-backdrop').classList.add('active');
            document.getElementById(modalId).classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId + '-backdrop').classList.remove('active');
            document.getElementById(modalId).classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close modal on backdrop click
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-backdrop')) {
                e.target.classList.remove('active');
                const modalId = e.target.id.replace('-backdrop', '');
                document.getElementById(modalId)?.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-backdrop.active').forEach(el => {
                    el.classList.remove('active');
                    const modalId = el.id.replace('-backdrop', '');
                    document.getElementById(modalId)?.classList.remove('active');
                });
                document.body.style.overflow = '';
            }
        });

        // Show session messages with SweetAlert
        @if(session('success'))
            showSuccess("{{ session('success') }}");
        @endif
        @if(session('error'))
            showError("{{ session('error') }}");
        @endif
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: `<ul class="text-left text-sm">@foreach($errors->all() as $error)<li>• {{ $error }}</li>@endforeach</ul>`,
                confirmButtonColor: '#1e3a5f'
            });
        @endif
    </script>
    
    @stack('scripts')
</body>
</html>
