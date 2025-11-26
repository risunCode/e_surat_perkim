# E-Surat Perkim v2.0.0 "Shorekeeper"
E-Surat Perkim dikembangkan untuk membantu pengelolaan administrasi surat-menyurat secara digital. Aplikasi ini menggantikan proses manual pencatatan surat dengan sistem terintegrasi yang memungkinkan pelacakan surat masuk, surat keluar, disposisi, dan korespondensi dalam satu platform.

Sistem ini dirancang khusus untuk kebutuhan instansi pemerintah dengan mempertimbangkan alur kerja birokrasi, termasuk fitur disposisi berjenjang dan pelacakan status penyelesaian dokumen.

<img width="1917" height="971" alt="E-Surat Perkim Dashboard" src="https://github.com/user-attachments/assets/2f9cbcd3-3995-4337-98e6-00be8c2829b7" />

---

## Daftar Isi

- [Tech Stack](#tech-stack)
- [Prerequisites](#prerequisites)
- [Quick Start](#-quick-start)
- [Default Login](#default-login)
- [Fitur](#fitur)
- [Production Deployment](#production-deployment)
- [LAN Hosting](#lan-hosting-opsional)
- [Referensi Tambahan](#referensi-tambahan)
- [Lisensi](#lisensi)

---

## Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Framework | Laravel 12 |
| PHP | 8.3+ |
| Database | MySQL 8.0 / MariaDB 10.6+ |
| Frontend | Blade, TailwindCSS, Vite |
| Auth | Laravel Fortify |
| UI Libraries | Boxicons, Cropper.js, SweetAlert2 |

---

## Prerequisites

Pastikan sudah terinstall:

- **PHP 8.3+**
- **Composer 2.x**
- **Node.js 18+** & NPM
- **MySQL 8.0** / MariaDB 10.6+
- **Git**

---

## Quick Start

Ikuti langkah-langkah berikut secara berurutan untuk menjalankan aplikasi di lokal.

### Step 1: Clone Repository

```bash
git clone <repository-url>
cd E-Surat-Perkim
```

### Step 2: Install Dependencies

```bash
composer install
npm install
```

### Step 3: Setup Local Server

**Laragon v6 (Recommended)** - Versi terakhir yang gratis

1. Download [Laragon Full](https://laragon.org/download/) versi 6.x
2. Install dan jalankan Laragon
3. Klik **Start All** untuk menjalankan Apache/Nginx + MySQL

**XAMPP (Alternatif)**

1. Download [XAMPP](https://www.apachefriends.org/) dengan PHP 8.3+
2. Jalankan XAMPP Control Panel
3. Start **Apache** dan **MySQL**

#### Aktifkan PHP Extensions

Pastikan extensions berikut aktif di `php.ini`:

```ini
extension=fileinfo
extension=gd
extension=mbstring
extension=openssl
extension=pdo_mysql
extension=zip
```

> **Laragon:** Menu → PHP → Extensions → centang yang diperlukan
> **XAMPP:** Edit `php.ini` di `C:\xampp\php\php.ini`, hapus `;` di depan extension

### Step 4: Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env`:

```env
APP_NAME="E-Surat Perkim"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_surat_perkim
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 5: Database Setup

**Pilih salah satu opsi:**

#### Opsi A - Via Migration (Recommended untuk Development)

```bash
php artisan migrate
php artisan db:seed
```

#### Opsi B - Via SQL File (Fresh Install)

```bash
# Buat database
mysql -u root -p -e "CREATE DATABASE e_surat_perkim"

# Import struktur tabel
mysql -u root -p e_surat_perkim < database/migraplus/e_surat_perkim_full.sql
```

### Step 6: Tambah Admin Pertama

```bash
mysql -u root -p e_surat_perkim < database/migraplus/e_surat_perkim_firstadmin.sql
```

### Step 7: Kode Referral (Opsional)

Untuk mengaktifkan registrasi user baru:

```bash
mysql -u root -p e_surat_perkim < database/migraplus/e_surat_perkim_references.sql
```

> Kode default: `ADMIN2025` (admin, max 5x), `STAFF2025` (staff, unlimited)

### Step 8: Storage Link

```bash
php artisan storage:link
```

### Step 9: Build Assets & Run

```bash
# Build frontend assets
npm run build

# Jalankan server
php artisan serve
```

**Akses:** http://127.0.0.1:8000

> **Development Mode:** Jika ingin hot-reload saat develop, jalankan `npm run dev` di terminal terpisah (bukan `npm run build`).

---

## Default Login

| Email | Password | Role |
|-------|----------|------|
| admin@perkim.go.id | perkim2025x | Admin |

---

## Fitur

| Fitur | Deskripsi |
|-------|-----------|
| **Surat Masuk** | Pencatatan surat dari eksternal dengan lampiran & klasifikasi |
| **Surat Keluar** | Pencatatan surat keluar dengan tracking korespondensi |
| **Disposisi** | Instruksi internal dengan status & deadline |
| **Korespondensi** | Pelacakan rantai surat balasan |
| **Galeri Lampiran** | Preview & download lampiran (gambar, PDF) |
| **Notifikasi** | Real-time notification untuk surat & disposisi baru |
| **Multi-Tema** | Light, Dark, Black, Pink |
| **Security Questions** | Reset password tanpa email |
| **Responsive** | Mobile-friendly design |

### Keamanan

- Rate Limiting (5x/menit)
- Anti Brute Force
- SQL Injection & XSS Protection
- CSRF Token
- Session Security (7 hari)

---

## Production Deployment

### 1. Build Assets

```bash
npm run build
```

### 2. Konfigurasi Environment

Edit `.env`:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### 3. Optimize Laravel

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 4. PHP Settings (php.ini)

```ini
upload_max_filesize = 15M
post_max_size = 50M
max_file_uploads = 20
memory_limit = 256M
```

### 5. Permissions (Linux)

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## LAN Hosting (Opsional)

Untuk akses dari perangkat lain di jaringan lokal:

```bash
# Cari IP lokal (Windows)
ipconfig

# Jalankan server
php artisan serve --host=0.0.0.0 --port=8000

# Buka firewall (PowerShell Admin)
netsh advfirewall firewall add rule name="Laravel Dev Server" dir=in action=allow protocol=tcp localport=8000
```

Akses dari perangkat lain: `http://192.168.x.x:8000`

---

## Referensi Tambahan

<details>
<summary><strong>Setup Laragon (Domain Lokal)</strong></summary>

1. Laragon → **Menu** → **Document Root** → **Browse**
2. Pilih folder project
3. Laragon → **Menu** → **Auto Virtual Hosts** → **esurat-perkim.test**
4. Update `.env`: `APP_URL=http://esurat-perkim.test`
5. Restart Laragon
6. Akses: `http://esurat-perkim.test`

</details>

<details>
<summary><strong>Struktur Database</strong></summary>

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

**Relasi:**
```
users (1) ──< (N) letters
users (1) ──< (N) dispositions
letters (1) ──< (N) dispositions
letters (1) ──< (N) attachments
letters (N) ──> (1) letters (reference_to)
```

</details>

<details>
<summary><strong>File SQL</strong></summary>

Tersedia di `database/migraplus/`:

| File | Keterangan |
|------|------------|
| `e_surat_perkim_full.sql` | Struktur tabel (wajib) |
| `e_surat_perkim_firstadmin.sql` | Admin pertama (wajib) |
| `e_surat_perkim_references.sql` | Kode referral (opsional) |

</details>

<details>
<summary><strong>API Endpoints</strong></summary>

**Notifikasi:**
```
GET  /notifications/unread    - Ambil notifikasi belum dibaca
POST /notifications/{id}/read - Tandai dibaca
POST /notifications/read-all  - Tandai semua dibaca
```

**Password Reset:**
```
GET  /forgot-password         - Form email
POST /forgot-password         - Verify email
GET  /verify-security         - Form pertanyaan keamanan
POST /verify-security         - Verify jawaban
GET  /reset-password-local    - Form password baru
POST /reset-password-local    - Update password
```

</details>

---

## Kontribusi

1. Fork repository
2. Buat branch: `git checkout -b fitur-baru`
3. Commit: `git commit -m 'Tambah fitur baru'`
4. Push: `git push origin fitur-baru`
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
