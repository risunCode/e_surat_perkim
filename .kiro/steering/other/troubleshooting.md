# Troubleshooting E-Surat Perkim

## Common Issues & Solutions

---

## Installation Issues

### Composer Install Failed
```
Problem: Your requirements could not be resolved to an installable set of packages.
```

**Solution:**
```bash
# Update composer
composer self-update

# Clear cache
composer clear-cache

# Try install again
composer install
```

### NPM Install Failed
```
Problem: npm ERR! code ERESOLVE
```

**Solution:**
```bash
# Clear npm cache
npm cache clean --force

# Delete node_modules and lock file
rm -rf node_modules package-lock.json

# Install with legacy peer deps
npm install --legacy-peer-deps
```

---

## Database Issues

### Migration Failed
```
Problem: SQLSTATE[HY000] [1049] Unknown database 'e_surat_perkim'
```

**Solution:**
```sql
-- Buat database terlebih dahulu
CREATE DATABASE e_surat_perkim CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Foreign Key Constraint Error
```
Problem: Cannot add foreign key constraint
```

**Solution:**
```bash
# Fresh migrate
php artisan migrate:fresh --seed
```

### SQLite Database Locked
```
Problem: SQLSTATE[HY000]: General error: 5 database is locked
```

**Solution:**
```bash
# Pastikan tidak ada proses lain yang mengakses database
# Atau gunakan MySQL untuk production
```

---

## Authentication Issues

### CSRF Token Mismatch
```
Problem: 419 | Page Expired
```

**Solution:**
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Pastikan @csrf ada di form
```

### Session Not Persisting
```
Problem: User logged out after redirect
```

**Solution:**
```bash
# Check session config
php artisan config:clear

# Untuk ngrok, update .env:
SESSION_DOMAIN=.ngrok-free.dev
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=none
```

### Login Rate Limited
```
Problem: Too many login attempts
```

**Solution:**
```bash
# Clear rate limiter
php artisan cache:clear

# Atau tunggu 60 detik
```

---

## File Upload Issues

### File Too Large
```
Problem: The file failed to upload. / PostTooLargeException
```

**Solution:**
```ini
; php.ini
upload_max_filesize = 15M
post_max_size = 50M
max_file_uploads = 20
memory_limit = 256M
```

### Storage Permission Denied
```
Problem: The "storage/app/public" directory does not exist
```

**Solution:**
```bash
# Create storage link
php artisan storage:link

# Fix permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### File Not Found After Upload
```
Problem: File uploaded but cannot be accessed
```

**Solution:**
```bash
# Pastikan storage link sudah dibuat
php artisan storage:link

# Check file exists
ls -la storage/app/public/attachments/
```

---

## Signature Issues

### SIGNATURE_KEY Not Configured
```
Problem: SIGNATURE_KEY not configured. Please set SIGNATURE_KEY in your .env file.
```

**Solution:**
```bash
# Generate signature key
php artisan signature:generate

# Atau manual di .env (min 32 chars)
SIGNATURE_KEY=your_random_signature_key_minimum_32_characters
```

### Signature Verification Failed
```
Problem: Dokumen tidak ditemukan atau tidak valid
```

**Solution:**
```bash
# Regenerate signatures
php artisan letters:sync-signatures --force
```

---

## View Issues

### View Not Found
```
Problem: View [pages.transaction.incoming.index] not found.
```

**Solution:**
```bash
# Clear view cache
php artisan view:clear

# Check file exists
ls -la resources/views/pages/transaction/incoming/
```

### Blade Syntax Error
```
Problem: syntax error, unexpected token
```

**Solution:**
- Check for unclosed `@if`, `@foreach`, `@section`
- Check for missing `@csrf` in forms
- Check for typos in Blade directives

---

## Route Issues

### Route Not Found
```
Problem: 404 | Not Found
```

**Solution:**
```bash
# Clear route cache
php artisan route:clear

# List routes
php artisan route:list
```

### Method Not Allowed
```
Problem: 405 | Method Not Allowed
```

**Solution:**
- Check form method matches route
- Use `@method('PUT')` or `@method('DELETE')` for non-POST forms

---

## Performance Issues

### Slow Page Load
```
Problem: Pages loading slowly
```

**Solution:**
```bash
# Optimize for production
php artisan optimize

# Check for N+1 queries
# Use eager loading in controllers
Letter::with(['user', 'classification', 'attachments'])->get();
```

### Memory Exhausted
```
Problem: Allowed memory size exhausted
```

**Solution:**
```ini
; php.ini
memory_limit = 256M
```

---

## Ngrok Issues

### Session Lost After Redirect
```
Problem: User logged out when accessing via ngrok
```

**Solution:**
```env
# .env
APP_URL=https://your-subdomain.ngrok-free.dev
SESSION_DOMAIN=.ngrok-free.dev
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=none
```

### Mixed Content Warning
```
Problem: Mixed Content: The page was loaded over HTTPS
```

**Solution:**
```php
// bootstrap/app.php sudah dikonfigurasi untuk trust proxies
$middleware->trustProxies(at: '*');
```

---

## Queue Issues

### Jobs Not Processing
```
Problem: Notifications not being sent
```

**Solution:**
```bash
# Start queue worker
php artisan queue:work

# Or use sync driver for development
# .env
QUEUE_CONNECTION=sync
```

---

## Debug Commands

### Check Application Status
```bash
# Check Laravel version
php artisan --version

# Check environment
php artisan env

# Check routes
php artisan route:list

# Check config
php artisan config:show app
```

### Clear All Caches
```bash
php artisan optimize:clear
```

### Check Logs
```bash
# Real-time logs
php artisan pail

# Or check log file
tail -f storage/logs/laravel.log
```

### Database Debug
```bash
# Check migrations status
php artisan migrate:status

# Rollback last migration
php artisan migrate:rollback

# Fresh start
php artisan migrate:fresh --seed
```

---

## Getting Help

1. Check `storage/logs/laravel.log` for detailed error messages
2. Enable debug mode: `APP_DEBUG=true`
3. Use `php artisan pail` for real-time logs
4. Check browser console for JavaScript errors
5. Use Laravel Telescope for detailed debugging (if installed)
