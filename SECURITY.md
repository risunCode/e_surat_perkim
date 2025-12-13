# Security Implementation

## Proteksi yang Diterapkan

### 1. Rate Limiting
- **Login**: Maksimal 5 percobaan per menit per IP + email
- **Password Reset**: Rate limited via Fortify
- **API**: Throttle middleware Laravel

### 2. Anti Brute Force
- Throttle login attempts
- Progressive lockout setelah gagal login
- IP + Email based tracking
- Auto-clear setelah login sukses

### 3. SQL Injection Prevention
- Laravel Eloquent ORM (parameterized queries)
- Input sanitization middleware
- Dangerous pattern detection
- Logging suspicious attempts

### 4. XSS Prevention
- Blade auto-escaping (`{{ }}`)
- X-XSS-Protection header
- Content-Type-Options header
- Input pattern validation

### 5. CSRF Protection
- Laravel CSRF token pada semua form
- SameSite cookie policy

### 6. Security Headers
```
X-XSS-Protection: 1; mode=block
X-Content-Type-Options: nosniff
X-Frame-Options: SAMEORIGIN
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: geolocation=(), microphone=(), camera=()
```

### 7. Content Security Policy (CSP) - Optional
CSP memberikan perlindungan tambahan terhadap XSS dengan membatasi sumber script/style.

**Status**: Disabled by default (dapat merusak beberapa fitur)

**Cara mengaktifkan**:
```env
# .env
CSP_ENABLED=true
```

**⚠️ WARNING**: 
- Test di staging environment terlebih dahulu
- CSP dapat memblokir inline scripts dan external resources
- Pastikan semua CDN sudah terdaftar di whitelist
- `/test-deface` route di-exclude dari CSP (untuk pentest demo)

**Policy yang diterapkan**:
```
default-src 'self'
script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net unpkg.com [vite-url]
style-src 'self' 'unsafe-inline' cdn.jsdelivr.net unpkg.com fonts.googleapis.com [vite-url]
font-src 'self' data: fonts.gstatic.com unpkg.com
img-src 'self' data: blob:
connect-src 'self' ws: wss: [vite-url]
frame-ancestors 'self'
form-action 'self'
base-uri 'self'
```

**Exceptions**:
- `/test-deface` - Intentionally vulnerable endpoint untuk pentest demo

### 8. Password Security
- Bcrypt hashing (12 rounds)
- Minimum 8 karakter
- Password confirmation required

### 9. Session Security
- Database session driver
- 7 hari expiry dengan inactivity
- Encrypted session option available

## Konfigurasi Production

Edit `.env` untuk production:

```env
APP_ENV=production
APP_DEBUG=false
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
```

## Logging

Suspicious activities dicatat di:
- `storage/logs/laravel.log`

Contoh log:
```
[WARNING] Potential injection attempt detected
- IP: 192.168.1.1
- Field: search
- URL: /admin/users
```

## Testing Security

```bash
# Test SQL injection
curl -X POST http://localhost/login -d "email=admin' OR '1'='1"

# Test XSS
curl -X POST http://localhost/search -d "q=<script>alert(1)</script>"

# Test rate limiting
for i in {1..10}; do curl -X POST http://localhost/login; done
```
