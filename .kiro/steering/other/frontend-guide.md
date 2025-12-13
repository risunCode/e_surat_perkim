# Panduan Frontend E-Surat Perkim

## Tech Stack Frontend

| Technology | Version | Fungsi |
|------------|---------|--------|
| Blade | Laravel 12 | Template engine |
| TailwindCSS | 4.x | Utility-first CSS |
| Vite | 7.x | Build tool |
| Boxicons | Latest | Icon library |
| SweetAlert2 | Latest | Alert & confirmation dialogs |
| Cropper.js | Latest | Image cropping (profile photo) |
| QRious.js | 4.x | QR Code generation |

---

## Struktur Views

```
resources/views/
├── components/                 # Reusable Blade components
│   ├── attachment-preview.blade.php
│   ├── footer.blade.php
│   ├── modal.blade.php
│   ├── navbar.blade.php
│   ├── pagination.blade.php
│   └── sidebar.blade.php
├── layouts/                    # Layout templates
│   └── app.blade.php          # Main layout
├── pages/                      # Page views
│   ├── admin/                 # Admin pages
│   │   ├── classification/
│   │   ├── reference-code/
│   │   ├── setting/
│   │   ├── status/
│   │   └── user/
│   ├── agenda/                # Agenda pages
│   ├── auth/                  # Authentication pages
│   ├── gallery/               # Gallery pages
│   ├── notifications/         # Notification pages
│   ├── profile/               # Profile pages
│   ├── transaction/           # Transaction pages
│   │   ├── disposition/
│   │   ├── incoming/
│   │   └── outgoing/
│   ├── verification/          # Document verification
│   ├── about.blade.php
│   └── dashboard.blade.php
├── pdf/                        # PDF templates
└── welcome.blade.php
```

---

## Blade Components

### Navbar Component
```blade
{{-- resources/views/components/navbar.blade.php --}}
<x-navbar />
```

### Sidebar Component
```blade
{{-- resources/views/components/sidebar.blade.php --}}
<x-sidebar />
```

### Modal Component
```blade
{{-- Usage --}}
<x-modal id="delete-modal" title="Konfirmasi Hapus">
    <p>Apakah Anda yakin ingin menghapus?</p>
    <form method="POST" action="...">
        @csrf
        @method('DELETE')
        <button type="submit">Hapus</button>
    </form>
</x-modal>
```

### Pagination Component
```blade
{{-- Usage --}}
<x-pagination :paginator="$letters" />
```

### Attachment Preview Component
```blade
{{-- Usage --}}
<x-attachment-preview :attachment="$attachment" />
```

---

## Theme System

Aplikasi mendukung 4 tema:
- Light (default)
- Dark
- Black
- Pink

### Theme Switching
```javascript
// Di navbar atau settings
function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
}

// Load saved theme
const savedTheme = localStorage.getItem('theme') || 'light';
setTheme(savedTheme);
```

### TailwindCSS Dark Mode
```css
/* Menggunakan data-theme attribute */
[data-theme="dark"] {
    --bg-primary: #1a1a2e;
    --text-primary: #eaeaea;
}
```

---

## SweetAlert2 Integration

### Success Alert
```javascript
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: 'Data berhasil disimpan.',
    timer: 2000,
    showConfirmButton: false
});
```

### Confirmation Dialog
```javascript
Swal.fire({
    title: 'Konfirmasi Hapus',
    text: 'Data yang dihapus tidak dapat dikembalikan!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
}).then((result) => {
    if (result.isConfirmed) {
        // Submit form
        document.getElementById('delete-form').submit();
    }
});
```

### Toast Notification
```javascript
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
});

Toast.fire({
    icon: 'success',
    title: 'Berhasil disalin!'
});
```

---

## Boxicons Usage

```html
<!-- Solid icon -->
<i class='bx bxs-envelope'></i>

<!-- Regular icon -->
<i class='bx bx-envelope'></i>

<!-- Logo icon -->
<i class='bx bxl-github'></i>

<!-- With size -->
<i class='bx bx-envelope bx-sm'></i>  <!-- small -->
<i class='bx bx-envelope bx-md'></i>  <!-- medium -->
<i class='bx bx-envelope bx-lg'></i>  <!-- large -->

<!-- With animation -->
<i class='bx bx-loader bx-spin'></i>
```

### Common Icons
```html
<i class='bx bx-envelope-open'></i>    <!-- Surat masuk -->
<i class='bx bx-send'></i>             <!-- Surat keluar -->
<i class='bx bx-transfer'></i>         <!-- Disposisi -->
<i class='bx bx-file-pdf'></i>         <!-- PDF -->
<i class='bx bx-image'></i>            <!-- Image -->
<i class='bx bx-user'></i>             <!-- User -->
<i class='bx bx-cog'></i>              <!-- Settings -->
<i class='bx bx-bell'></i>             <!-- Notification -->
<i class='bx bx-check-circle'></i>     <!-- Success -->
<i class='bx bx-x-circle'></i>         <!-- Error -->
```

---

## QR Code Generation

```javascript
// Menggunakan QRious.js
const qr = new QRious({
    element: document.getElementById('qr-code'),
    value: 'https://example.com/verify/abc123',
    size: 200,
    level: 'H'  // Error correction level
});
```

---

## Cropper.js (Profile Photo)

```javascript
// Initialize cropper
const image = document.getElementById('image-preview');
const cropper = new Cropper(image, {
    aspectRatio: 1,
    viewMode: 1,
    minCropBoxWidth: 100,
    minCropBoxHeight: 100
});

// Get cropped image
cropper.getCroppedCanvas({
    width: 300,
    height: 300
}).toBlob((blob) => {
    // Upload blob
    const formData = new FormData();
    formData.append('photo', blob, 'profile.jpg');
    
    fetch('/profile/photo', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });
});
```

---

## Form Patterns

### Standard Form
```blade
<form method="POST" action="{{ route('incoming.store') }}" enctype="multipart/form-data">
    @csrf
    
    <div class="mb-4">
        <label for="reference_number" class="block text-sm font-medium">
            Nomor Surat <span class="text-red-500">*</span>
        </label>
        <input type="text" 
               name="reference_number" 
               id="reference_number"
               value="{{ old('reference_number') }}"
               class="mt-1 block w-full rounded-md border-gray-300 @error('reference_number') border-red-500 @enderror">
        @error('reference_number')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>
    
    <button type="submit" class="btn btn-primary">
        Simpan
    </button>
</form>
```

### File Upload
```blade
<div class="mb-4">
    <label class="block text-sm font-medium">Lampiran</label>
    <input type="file" 
           name="attachments[]" 
           multiple
           accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif"
           class="mt-1 block w-full">
    <p class="mt-1 text-xs text-gray-500">
        Format: PDF, DOC, DOCX, PPT, PPTX, TXT, JPG, PNG, GIF. Max 15MB total.
    </p>
</div>
```

---

## AJAX Patterns

### Fetch API
```javascript
async function markNotificationAsRead(id) {
    try {
        const response = await fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            // Update UI
        }
    } catch (error) {
        console.error('Error:', error);
    }
}
```

### Polling Notifications
```javascript
// Refresh notifications setiap 2 menit
setInterval(async () => {
    const response = await fetch('/notifications/unread');
    const data = await response.json();
    
    // Update notification badge
    document.getElementById('notification-count').textContent = data.count;
}, 120000);
```

---

## Responsive Design

```blade
{{-- Mobile-first approach --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    {{-- Cards --}}
</div>

{{-- Hide on mobile --}}
<div class="hidden md:block">
    Desktop only content
</div>

{{-- Show on mobile only --}}
<div class="md:hidden">
    Mobile only content
</div>
```
