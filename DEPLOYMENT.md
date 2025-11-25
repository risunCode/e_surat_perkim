# E-Surat Perkim - Deployment Guide

## File Upload Configuration

### PHP Settings Required

Aplikasi ini membutuhkan pengaturan PHP berikut untuk upload file hingga 15MB per file:

```ini
# php.ini
upload_max_filesize = 15M
post_max_size = 50M
max_file_uploads = 20
max_execution_time = 300
memory_limit = 256M
```

### Apache Configuration

Jika menggunakan Apache, tambahkan ke `.htaccess`:

```apache
php_value upload_max_filesize 15M
php_value post_max_size 50M
php_value max_file_uploads 20
php_value max_execution_time 300
php_value memory_limit 256M
```

### Nginx Configuration

Jika menggunakan Nginx, tambahkan ke konfigurasi server:

```nginx
client_max_body_size 50M;
```

## Validation Settings

Aplikasi menggunakan Laravel validation dengan:
- **Per file**: max 15MB (15360 KB)
- **Total files**: max 15MB combined
- **File types**: pdf, doc, docx, ppt, pptx, txt, jpg, jpeg, png, gif

## Database Updates

### Migration
Jalankan migration untuk update tabel attachments:
```bash
php artisan migrate
```

Ini akan menambah kolom baru:
- `file_size` - ukuran file dalam bytes
- `mime_type` - tipe MIME file

### Seeder
Jalankan seeder untuk konfigurasi upload:
```bash
php artisan db:seed --class=UploadConfigSeeder
```

Ini akan menambah config:
- `upload_max_file_size` - 15MB per file
- `upload_max_total_size` - 15MB total
- `upload_allowed_types` - ekstensi file yang diizinkan
- `upload_max_files` - max 20 file sekaligus

## Storage

File uploads disimpan di:
- **Path**: `storage/app/public/attachments/`
- **Incoming**: `attachments/incoming/`
- **Outgoing**: `attachments/outgoing/`

Pastikan folder `storage` memiliki permission yang tepat:
```bash
chmod -R 775 storage
chown -R www-data:www-data storage
```

### Attachment Model
Model sekarang menyimpan metadata file:
- File size dalam bytes
- MIME type untuk deteksi jenis file
- Helper methods: `formatted_size`, `isImage()`, `isPdf()`

## Testing Upload

Untuk test upload limits:
1. Buat file > 15MB → should fail with validation error
2. Upload multiple files > 15MB total → should fail
3. Upload file 1-15MB → should success

## Troubleshooting

**Error "PostTooLargeException"**: 
- Check `post_max_size` setting
- Restart web server setelah edit php.ini

**Error "upload_max_filesize exceeded"**:
- Check `upload_max_filesize` setting
- Check Laravel validation rules

**Files not stored**:
- Check storage permissions
- Run `php artisan storage:link`
