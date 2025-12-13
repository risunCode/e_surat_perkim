# Panduan Keamanan E-Surat Perkim

## Overview

Aplikasi ini menerapkan multiple layers of security untuk melindungi data dan mencegah serangan umum.

---

## 1. Authentication (Laravel Fortify)

### Login
- Email + Password authentication
- Rate limiting: 5 attempts per minute
- Session-based authentication

### Registration
- Memerlukan Reference Code (ADMIN2025 / STAFF2025)
- Reference code menentukan role user
- Max usage limit per reference code

### Password Reset (Local - No Email)
- Menggunakan Security Questions
- Flow: Email → Security Question → New Password
- Tidak memerlukan email server

---

## 2. Middleware Security

### SecurityHeaders Middleware
```php
// File: app/Http/Middleware/SecurityHeaders.php

// Headers yang ditambahkan:
'X-XSS-Protection' => '1; mode=block'           // Prevent XSS
'X-Content-Type-Options' => 'nosniff'           // Prevent MIME sniffing
'X-Frame-Options' => 'SAMEORIGIN'               // Prevent clickjacking
'Referrer-Policy' => 'strict-origin-when-cross-origin'
'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()'
```

### SanitizeInput Middleware
```php
// File: app/Http/Middleware/SanitizeInput.php

// Patterns yang diblokir:
- <script> tags
- javascript: protocol
- vbscript: protocol
- Event handlers (onclick, onload, onerror, onmouse)
- SQL injection (UNION SELECT, DROP TABLE, INSERT INTO)
- SQL comments (--, /*, */)
- <iframe> tags
- <object> tags

// Fields yang di-skip:
- password
- password_confirmation
- current_password
```

### ThrottleLogins Middleware
```php
// File: app/Http/Middleware/ThrottleLogins.php

// Rate limit: 5 attempts per minute
// Key: email + IP address
// Lockout: 60 seconds
```

### CheckUserActive Middleware
```php
// File: app/Http/Middleware/CheckUserActive.php

// Cek apakah user masih aktif
// Jika tidak aktif, logout dan redirect ke login
```

### EnsureSecuritySetup Middleware
```php
// File: app/Http/Middleware/EnsureSecuritySetup.php

// Memastikan user sudah setup security questions
// Jika belum, redirect ke /security-setup
```

---

## 3. Digital Signature & QR Verification

### Signature Generation
```php
// File: app/Services/SignatureService.php

// Signature dibuat dari:
$signatureData = $content . $letterId . $userId . $documentType . $timestamp . $signatureKey;
$hash = hash('sha256', $signatureData);

// Content yang di-hash:
[
    'reference_number' => $letter->reference_number,
    'type' => $letterType,
    'from_to' => $fromTo,
    'letter_date' => $letter->letter_date,
    'description' => $letter->description,
    'classification' => $letter->classification->code,
    'user' => $letter->user->name,
    'attachments_count' => count,
    'attachments' => [filenames...]
]
```

### Verification Flow
1. User scan QR Code atau akses `/verify/{hash}`
2. System lookup signature di database
3. Jika valid, tampilkan detail dokumen
4. Jika tidak valid, tampilkan error

### Environment Variable
```env
# WAJIB: Minimal 32 karakter
SIGNATURE_KEY=your_random_signature_key_minimum_32_characters
```

---

## 4. Authorization

### Role-Based Access Control
```php
// Roles:
- admin: Full access ke semua fitur, dapat edit/delete semua surat
- staff: Limited access (hanya bisa view & create, tidak bisa edit/delete)

// Middleware usage:
Route::middleware('role:admin')->group(function () {
    // Admin-only routes
});
```

### Resource Authorization
```php
// Di Controller (admin only untuk edit/delete):
private function authorizeEdit(Letter $letter)
{
    if (!Auth::user()->isAdmin()) {
        abort(403, 'Hanya administrator yang dapat mengubah atau menghapus surat.');
    }
}
```

---

## 5. File Upload Security

### Validasi
```php
// Allowed MIME types:
'pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt', 'jpg', 'jpeg', 'png', 'gif'

// Max file size: 15MB per file
// Max total size: 15MB untuk semua file

// Filename: UUID untuk mencegah path traversal
$filename = \Str::uuid() . '.' . $extension;
```

### Storage
```php
// Files disimpan di: storage/app/public/attachments/
// Akses via: /attachments/{id} (authenticated)
// Download via: /attachments/{id}/download (authenticated)
```

---

## 6. CSRF Protection

Laravel otomatis menerapkan CSRF protection untuk semua POST/PUT/DELETE requests.

```blade
{{-- Di form --}}
@csrf

{{-- Di AJAX --}}
headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
}
```

---

## 7. SQL Injection Prevention

### Eloquent ORM
```php
// ✅ Safe - Eloquent handles escaping
Letter::where('reference_number', $search)->get();

// ✅ Safe - Parameter binding
DB::select('SELECT * FROM letters WHERE id = ?', [$id]);

// ❌ Unsafe - Raw query
DB::select("SELECT * FROM letters WHERE id = $id");
```

### SanitizeInput Middleware
Middleware tambahan untuk mendeteksi pattern SQL injection di input.

---

## 8. XSS Prevention

### Blade Escaping
```blade
{{-- ✅ Safe - Auto escaped --}}
{{ $user->name }}

{{-- ❌ Unsafe - Raw output --}}
{!! $user->bio !!}
```

### SanitizeInput Middleware
Middleware mendeteksi dan memblokir:
- `<script>` tags
- `javascript:` protocol
- Event handlers

---

## 9. Session Security

### Configuration
```php
// config/session.php
'driver' => 'file',
'lifetime' => 120,
'expire_on_close' => false,
'encrypt' => false,
'secure' => env('SESSION_SECURE_COOKIE', false),
'same_site' => 'lax',
```

### Ngrok/Production
```env
SESSION_DOMAIN=.ngrok-free.dev
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=none
```

---

## 10. Proxy Trust (Ngrok/Load Balancer)

```php
// bootstrap/app.php
$middleware->trustProxies(
    at: '*',
    headers: Request::HEADER_X_FORWARDED_FOR |
             Request::HEADER_X_FORWARDED_HOST |
             Request::HEADER_X_FORWARDED_PORT |
             Request::HEADER_X_FORWARDED_PROTO |
             Request::HEADER_X_FORWARDED_AWS_ELB
);
```

---

## 11. Attack Detection & Monitoring

### Attack Alerts
Sistem mendeteksi dan mencatat serangan secara otomatis:
- **XSS** - Cross-Site Scripting attempts
- **SQL Injection** - Database injection attempts
- **Command Injection** - Shell command injection
- **Path Traversal** - Directory traversal attempts

### Auto-Mitigation
```php
// SanitizeInput middleware otomatis:
// 1. Mendeteksi pattern serangan
// 2. Mencatat ke database (attack_alerts)
// 3. Memblokir request dengan 403 response
// 4. Throttle logging (1 log per IP per attack type per 5 menit)
```

### Attack Alert Status
- **Active** - Serangan baru terdeteksi
- **Mitigated** - Sudah ditangani/diblokir
- **Archived** - Sudah selesai ditinjau

### Admin Panel
Route: `/admin/attack-alerts`
- Live console untuk monitoring real-time
- Filter berdasarkan type, severity, status
- Bulk actions: Mitigate All, Archive All, Delete Archived

---

## 12. IP Blocking

### Blocked IPs Management
Route: `/admin/blocked-ips`

### Fitur
- Block IP manual atau dari attack alert
- Default durasi: 3 hari (auto-expire)
- Opsi permanent block
- Unblock single atau semua IP

### Model
```php
// app/Models/BlockedIp.php
BlockedIp::isBlocked($ip);  // Check if IP is blocked
BlockedIp::block($ip, $reason, $days);  // Block IP
```

### Middleware Integration
```php
// SanitizeInput middleware cek blocked IP di awal:
if (BlockedIp::isBlocked($request->ip())) {
    abort(403, 'Access denied. Your IP has been blocked.');
}
```

---

## Security Checklist

- [ ] Set `APP_DEBUG=false` di production
- [ ] Set `APP_ENV=production` di production
- [ ] Generate unique `APP_KEY`
- [ ] Generate unique `SIGNATURE_KEY` (min 32 chars)
- [ ] Configure HTTPS
- [ ] Set proper `SESSION_SECURE_COOKIE`
- [ ] Review file permissions
- [ ] Enable firewall rules
- [ ] Regular security updates
