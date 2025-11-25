# E-Surat Perkim v2.0.0 "Shorekeeper"
**"Resonance of Enhanced File Management"**

Sistem manajemen surat elektronik untuk keperluan administrasi perkantoran dengan kemampuan upload file hingga 15MB dan tracking metadata lengkap.

## Tentang Aplikasi

E-Surat Perkim dikembangkan untuk membantu pengelolaan administrasi surat-menyurat secara digital. Aplikasi ini menggantikan proses manual pencatatan surat dengan sistem terintegrasi yang memungkinkan pelacakan surat masuk, surat keluar, disposisi, dan korespondensi dalam satu platform.

Sistem ini dirancang khusus untuk kebutuhan instansi pemerintah dengan mempertimbangkan alur kerja birokrasi, termasuk fitur disposisi berjenjang dan pelacakan status penyelesaian dokumen.

<img width="1917" height="971" alt="image" src="https://github.com/user-attachments/assets/2f9cbcd3-3995-4337-98e6-00be8c2829b7" />

## Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Framework | Laravel 12 |
| PHP | 8.3+ |
| Database | MySQL 8.0 / MariaDB 10.6+ |
| Frontend | Blade Templates, TailwindCSS |
| Authentication | Laravel Fortify |
| Build Tool | Vite |
| Icons | Boxicons |
| Image Crop | Cropper.js |
| Alerts | SweetAlert2 |

## Fitur

### Surat Masuk
Pencatatan surat yang diterima dari pihak eksternal. Setiap surat masuk dapat dilengkapi dengan lampiran digital, klasifikasi, dan catatan. Surat masuk dapat dibalas langsung (menghasilkan surat keluar baru) atau diteruskan melalui disposisi internal.

### Surat Keluar
Pencatatan surat yang dikirim ke pihak eksternal. Sistem secara otomatis melacak apakah surat keluar merupakan balasan dari surat masuk sebelumnya, membentuk rantai korespondensi yang dapat ditelusuri.

### Disposisi
Instruksi internal untuk menindaklanjuti surat masuk. Disposisi mencakup tujuan, instruksi, catatan, dan tenggat waktu. Status disposisi dapat dipantau (Pending, Diproses, Selesai, Urgent) dengan indikator keterlambatan.

### Korespondensi
Fitur pelacakan rantai surat. Ketika surat masuk dibalas, sistem membuat relasi antara surat asal dan balasannya. Tampilan korespondensi menunjukkan alur komunikasi secara visual dengan penomoran urut.

### Galeri Lampiran
Tampilan khusus untuk melihat semua lampiran surat dalam format galeri. Mendukung preview langsung untuk gambar dan PDF, serta fitur download.

### Notifikasi
Sistem notifikasi real-time untuk memberitahu pengguna tentang surat baru, disposisi, dan balasan. Notifikasi dapat ditandai dibaca secara individual atau massal.

### Multi-Tema
Empat pilihan tema tampilan: Light (default), Dark, Black, dan Pink. Preferensi tema disimpan di browser pengguna.

### Manajemen Pengguna
Dua level akses: Administrator (akses penuh) dan Staff (akses terbatas pada data sendiri). Registrasi pengguna baru menggunakan sistem kode referral yang dapat diatur oleh administrator.

### Security Questions
Fitur reset password menggunakan pertanyaan keamanan untuk aplikasi lokal tanpa email. User wajib setup pertanyaan keamanan dan tanggal lahir sebelum dapat mengakses dashboard.

### Keamanan
- **Rate Limiting**: Maksimal 5 percobaan login per menit
- **Anti Brute Force**: IP + Email based tracking
- **SQL Injection Protection**: Input sanitization & pattern detection
- **XSS Prevention**: Security headers & input validation
- **CSRF Protection**: Laravel CSRF token
- **Session Security**: 7 hari expiry dengan inactivity check

### Responsive Design
- **Mobile-first**: Tampilan dioptimalkan untuk semua ukuran layar
- **Sidebar Toggle**: Sidebar tersembunyi di mobile, dapat dibuka via hamburger menu
- **Scrollable Tables**: Tabel dapat di-scroll horizontal di layar kecil
- **Touch-friendly**: Tombol dan interaksi sesuai untuk layar sentuh

## Requirements

- PHP 8.3+
- Composer 2.x
- Node.js 18+ & NPM
- MySQL 8.0 / MariaDB 10.6+
- Git

## Instalasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd E-Surat-Perkim
```

## Setup Domain Lokal (Laragon)

Jika menggunakan **Laragon**, bisa setup domain lokal yang lebih friendly:

**Metode A - Document Root Switch (Recommended):**
1. Laragon → **Menu** → **Document Root** → **Browse**
2. Pilih folder project: `D:\TokioWorld\MyGitRepository\TownProject\E-Surat-Perkim`
3. Laragon → **Menu** → **Auto Virtual Hosts** → **esurat-perkim.test**
4. Update `.env`: `APP_URL=http://esurat-perkim.test`
5. Restart Laragon (Stop All → Start All)
6. Akses: `http://esurat-perkim.test`

**Metode B - Copy ke Laragon:**
```bash
# Copy project ke folder Laragon
cp -r E-Surat-Perkim C:\laragon\www\esurat-perkim.test\
```

> **Note:** Laragon otomatis update hosts file dan setup virtual host.

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi .env

Edit file `.env` sesuai environment:

```env
# Aplikasi
APP_NAME="E-Surat Perkim"
APP_ENV=local                    # local / production
APP_DEBUG=true                   # true untuk dev, false untuk production
APP_URL=http://localhost:8000

# Database (MySQL/MariaDB)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_surat_perkim
DB_USERNAME=root
DB_PASSWORD=your_password

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# File Storage
FILESYSTEM_DISK=local
```

> **Note:** Aplikasi ini tidak memerlukan konfigurasi email (MAIL_*) karena menggunakan security questions untuk reset password.

### 5. Database Setup

**Opsi A - Via Migration (Development):**
```bash
php artisan migrate
php artisan db:seed
```

> **Note:** Seeder hanya mengisi data referensi (klasifikasi, status, config). Admin dan kode referral harus ditambahkan manual via migraplus.

**Opsi B - Via SQL File (Fresh Install):**
```bash
mysql -u root -p -e "CREATE DATABASE e_surat_perkim"
mysql -u root -p e_surat_perkim < database/migraplus/e_surat_perkim_full.sql
```

### 6. Tambah Admin Pertama

Setelah database siap, tambahkan admin pertama:
```bash
mysql -u root -p e_surat_perkim < database/migraplus/e_surat_perkim_firstadmin.sql
```

### 7. Tambah Kode Referral (Opsional)

Untuk mengaktifkan registrasi user baru:
```bash
mysql -u root -p e_surat_perkim < database/migraplus/e_surat_perkim_references.sql
```

> **Kode Default:** `ADMIN2025` (admin, max 5x), `STAFF2025` (staff, unlimited)

### 8. Storage Link
```bash
php artisan storage:link
```

### 9. Run Development Server
```bash
# Terminal 1 - Laravel
php artisan serve

# Terminal 2 - Vite (hot reload)
npm run dev
```

> **Upload Limits:** Server sudah dikonfigurasi untuk upload 15MB per file, 50MB total. Jika ada error upload, pastikan php.ini memiliki setting:
> - `upload_max_filesize = 15M`
> - `post_max_size = 50M`

Akses: http://127.0.0.1:8000

## Build untuk Production

### 1. Build Assets
```bash
npm run build
```

Hasil build akan tersimpan di `public/build/`.

### 2. Server Configuration

**PHP Settings (php.ini):**
```ini
upload_max_filesize = 15M
post_max_size = 50M
max_file_uploads = 20
max_execution_time = 300
memory_limit = 256M
```

**Web Server (.htaccess untuk Apache):**
```apache
php_value upload_max_filesize 15M
php_value post_max_size 50M
php_value max_file_uploads 20
```

### 3. Environment Configuration

Edit `.env`:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### 4. Optimize Laravel
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 5. Set Permissions (Linux)
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

> **Note:** Setelah `npm run build`, tidak perlu menjalankan `npm run dev`. Assets sudah dikompilasi.

## Hosting Jaringan Lokal

Agar aplikasi dapat diakses oleh perangkat lain di jaringan lokal (LAN):

### 1. Cari IP Lokal
```bash
# Windows
ipconfig

# Linux/Mac
ip addr
# atau
ifconfig
```

Cari **IPv4 Address** dari adapter yang aktif (biasanya `192.168.x.x`).

### 2. Jalankan Server dengan Host 0.0.0.0
```bash
# Terminal 1 - Laravel
php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2 - Vite
npm run dev -- --host
```

### 3. Buka Firewall (Windows)

Jalankan di **PowerShell sebagai Administrator**:
```powershell
netsh advfirewall firewall add rule name="Laravel Dev Server" dir=in action=allow protocol=tcp localport=8000
```

### 4. Akses dari Perangkat Lain

Perangkat lain di jaringan yang sama dapat mengakses via:
```
http://<IP-LOKAL-KAMU>:8000
```

Contoh: `http://192.168.50.23:8000`

> **Note:** Pastikan semua perangkat terhubung ke jaringan WiFi/LAN yang sama.

## Default Login

Setelah import `e_surat_perkim_firstadmin.sql`:

| Email | Password | Role |
|-------|----------|------|
| admin@perkim.go.id | perkim2025x | Admin |

> **Note:** Default admin sudah memiliki security setup completed. User baru wajib setup pertanyaan keamanan sebelum akses dashboard.

## Kode Registrasi

Setelah import `e_surat_perkim_references.sql`:

| Kode | Role | Max Usage |
|------|------|----------|
| `ADMIN2025` | Admin | 5x |
| `STAFF2025` | Staff | Unlimited |

> **Note:** Kode dapat dikelola via Admin Panel → Reference Codes.

## Struktur Database

### Tabel Utama
| Tabel | Deskripsi |
|-------|-----------|
| users | Data pengguna + security questions |
| letters | Surat masuk & keluar |
| dispositions | Disposisi surat |
| attachments | Lampiran surat |
| notifications | Notifikasi sistem |
| classifications | Klasifikasi surat |
| letter_statuses | Status disposisi |
| reference_codes | Kode referral |
| configs | Konfigurasi aplikasi |

### Kolom Security (users)
| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| birth_date | DATE | Tanggal lahir |
| security_question | VARCHAR | Key/custom pertanyaan |
| security_answer | VARCHAR | Hash jawaban |
| security_setup_completed | BOOLEAN | Status setup |

### Relasi
```
users (1) ──< (N) letters
users (1) ──< (N) dispositions
letters (1) ──< (N) dispositions
letters (1) ──< (N) attachments
letters (N) ──> (1) letters (reference_to)
```

## File SQL

Tersedia di `database/migraplus/`:

| File | Isi |
|------|-----|
| `e_surat_perkim_full.sql` | Struktur tabel lengkap (wajib) |
| `e_surat_perkim_firstadmin.sql` | Admin pertama (wajib) |
| `e_surat_perkim_references.sql` | Kode referral (opsional) |

## Konfigurasi Tema

Tema disimpan di localStorage dengan key `theme`. Pilihan:
- `light` (default)
- `dark`
- `black`
- `pink`

## API Endpoints

### Notifikasi
```
GET  /notifications/unread    - Ambil notifikasi belum dibaca
POST /notifications/{id}/read - Tandai dibaca
POST /notifications/read-all  - Tandai semua dibaca
```

### Password Reset (Local)
```
GET  /forgot-password         - Form email
POST /forgot-password         - Verify email
GET  /verify-security         - Form pertanyaan keamanan
POST /verify-security         - Verify jawaban
GET  /reset-password-local    - Form password baru
POST /reset-password-local    - Update password
```

## Kontribusi

1. Fork repository
2. Buat branch fitur (`git checkout -b fitur-baru`)
3. Commit perubahan (`git commit -m 'Tambah fitur baru'`)
4. Push ke branch (`git push origin fitur-baru`)
5. Buat Pull Request

## Lisensi

**Source Available - Non-Commercial License**

Kode sumber aplikasi ini tersedia untuk dipelajari dan digunakan dengan ketentuan:

**Diizinkan:**
- Menggunakan untuk keperluan pribadi
- Menggunakan untuk keperluan internal organisasi/instansi
- Memodifikasi sesuai kebutuhan
- Membuat fork untuk pengembangan sendiri

**Dilarang:**
- Menjual kode sumber atau aplikasi turunannya
- Menjadikan bagian dari produk komersial
- Menyediakan sebagai layanan berbayar (SaaS)
- Menghapus atau mengubah atribusi pemilik asli

Penggunaan di luar ketentuan di atas memerlukan izin tertulis dari pemilik repository.

---

© 2025 E-Surat Perkim. All rights reserved.
