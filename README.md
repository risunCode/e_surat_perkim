# E-Surat Perkim v2.0.1 "Optimized Shorekeeper"

> *Performance & UX Refined* — Sistem Manajemen Surat Digital untuk Instansi Pemerintah

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3+">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="TailwindCSS">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

E-Surat Perkim adalah aplikasi manajemen surat-menyurat digital yang menggantikan proses manual pencatatan surat dengan sistem terintegrasi. Dirancang khusus untuk instansi pemerintah dengan fitur disposisi berjenjang, pelacakan korespondensi, dan **verifikasi dokumen via QR Code & Digital Signature**.

<img width="100%" alt="E-Surat Perkim Dashboard" src="https://github.com/user-attachments/assets/ae43e73b-d498-43c2-8932-530489cb74a4" />

---

## 📋 Daftar Isi

- [Tech Stack](#-tech-stack)
- [Prerequisites](#-prerequisites)
- [Quick Start](#-quick-start)
- [Default Login](#-default-login)
- [Fitur Lengkap](#-fitur-lengkap)
- [Project Structure](#-project-structure)
- [Database Schema](#-database-schema)
- [Production Deployment](#-production-deployment)
- [Ngrok Deployment](#-ngrok-deployment)
- [LAN Hosting](#-lan-hosting)
- [Artisan Commands](#-artisan-commands)
- [Kontribusi](#-kontribusi)

---

## 🛠 Tech Stack

| Layer | Technology |
|-------|------------|
| **Backend** | Laravel 12, PHP 8.3+ |
| **Database** | MySQL 8.0 / MariaDB 10.6+ |
| **Frontend** | Blade, TailwindCSS 3.x, Vite |
| **Authentication** | Laravel Fortify |
| **UI Components** | Boxicons, SweetAlert2, Cropper.js, QRious.js |
| **Security** | SHA-256 Digital Signatures, QR Verification |
| **File Storage** | Local disk dengan metadata tracking |

---

## 📦 Prerequisites

Pastikan sudah terinstall:

| Software | Versi Minimum |
|----------|---------------|
| PHP | 8.3+ |
| Composer | 2.x |
| Node.js | 18+ |
| MySQL | 8.0 / MariaDB 10.6+ |
| Git | Latest |

### PHP Extensions Required
```ini
extension=fileinfo
extension=gd
extension=mbstring
extension=openssl
extension=pdo_mysql
extension=zip
```

---

## 🚀 Quick Start

### 1. Clone & Install

```bash
# Clone repository
git clone https://github.com/your-repo/e-surat-perkim.git
cd e-surat-perkim

# Install dependencies
composer install
npm install
```

### 2. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Generate signature key (untuk digital signature)
php artisan signature:generate
```

### 3. Configure `.env`

```env
APP_NAME="E-Surat Perkim"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Digital Signature (REQUIRED - 32+ chars)
SIGNATURE_KEY=your_random_signature_key_here_minimum_32_characters

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_surat_perkim
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Database Setup

```bash
# Buat database terlebih dahulu di MySQL
# CREATE DATABASE e_surat_perkim;

# Jalankan migration & seeder
php artisan migrate
php artisan db:seed

# Atau gunakan SQL file (alternatif)
mysql -u root -p e_surat_perkim < database/migraplus/e_surat_perkim_full.sql
mysql -u root -p e_surat_perkim < database/migraplus/e_surat_perkim_firstadmin.sql
mysql -u root -p e_surat_perkim < database/migraplus/e_surat_perkim_references.sql
```

### 5. Storage & Build

```bash
# Create storage link
php artisan storage:link

# Build frontend assets
npm run build

# Jalankan server
php artisan serve
```

**🎉 Akses:** http://127.0.0.1:8000

---

## 🔑 Default Login

| Email | Password | Role |
|-------|----------|------|
| `admin@perkim.go.id` | `perkim2025x` | Administrator |

**Reference Codes untuk Registrasi:**
| Code | Role | Usage |
|------|------|-------|
| `ADMIN2025` | Admin | Max 5x |
| `STAFF2025` | Staff | Unlimited |

---

## ✨ Fitur Lengkap

### 📧 Manajemen Surat
- **Surat Masuk** — Input surat dengan lampiran, klasifikasi, agenda number
- **Surat Keluar** — Tracking korespondensi dengan reply chain
- **Disposisi** — Instruksi internal dengan status & deadline
- **Korespondensi** — Pelacakan rantai surat balasan
- **Agenda** — Laporan agenda dengan cetak PDF
- **Toggle Completion** — Tandai surat selesai/belum

### 🔐 Security & Verification
- **Digital Signature** — SHA-256 untuk setiap dokumen
- **QR Code** — Verifikasi publik tanpa login
- **Reference Codes** — Registrasi dengan kode referral
- **Security Questions** — Reset password tanpa email
- **Rate Limiting** — 5x/menit untuk login
- **Input Sanitization** — XSS & SQL Injection protection

### 📁 File Management
- **Multi-Format** — PDF, DOC, DOCX, PPT, PPTX, TXT, Images
- **Max Upload** — 15MB per file
- **Galeri** — Preview & download dengan metadata
- **Secure Storage** — Akses kontrol per user

### 🎨 UI/UX
- **Multi-Theme** — Light, Dark, Black, Pink
- **Real-time Notifications** — SweetAlert2 integration
- **Responsive** — Mobile-friendly design
- **Copy Document ID** — One-click copy dengan toast

### 👥 User Management
- **Roles** — Admin (full) & Staff (limited)
- **Profile** — Foto profil, password management
- **Admin Panel** — Kelola users, klasifikasi, status, settings

---

## 📂 Project Structure

```
e-surat-perkim/
├── app/
│   ├── Console/Commands/       # Artisan commands
│   ├── Enums/                  # Role, LetterType, NotificationType
│   ├── Http/
│   │   ├── Controllers/        # Main controllers
│   │   │   └── Admin/          # Admin-only controllers
│   │   └── Middleware/         # Custom middleware
│   ├── Models/                 # Eloquent models
│   └── Services/               # Business logic services
├── database/
│   ├── migrations/             # Database migrations
│   ├── migraplus/              # SQL files for quick setup
│   └── seeders/                # Data seeders
├── resources/
│   ├── css/                    # TailwindCSS
│   ├── js/                     # JavaScript
│   └── views/                  # Blade templates
├── routes/
│   └── web.php                 # Web routes
└── .zencoder/rules/            # AI coding rules
```

### Key Files

| File | Description |
|------|-------------|
| `app/Models/Letter.php` | Surat dengan korespondensi chain |
| `app/Models/Disposition.php` | Disposisi surat |
| `app/Models/DocumentSignature.php` | Digital signature & QR |
| `app/Services/SignatureService.php` | Signature generation |
| `app/Services/NotificationService.php` | Notification broadcast |
| `bootstrap/app.php` | Middleware & proxy config |

---

## 🗄 Database Schema

```
┌─────────────┐     ┌─────────────────┐     ┌──────────────┐
│    users    │────<│     letters     │────<│ dispositions │
└─────────────┘     └─────────────────┘     └──────────────┘
                           │  │
                           │  └────<┌─────────────┐
                           │        │ attachments │
                           │        └─────────────┘
                           │
                           └────<┌────────────────────┐
                                 │ document_signatures│
                                 └────────────────────┘

┌─────────────────┐     ┌───────────────┐     ┌─────────────────┐
│ classifications │────<│    letters    │     │ letter_statuses │
└─────────────────┘     └───────────────┘     └─────────────────┘
                                                      │
                                                      └────<┌──────────────┐
                                                            │ dispositions │
                                                            └──────────────┘

┌─────────────────┐     ┌───────────────────────┐     ┌─────────────┐
│ reference_codes │────<│ reference_code_usage  │>────│    users    │
└─────────────────┘     └───────────────────────┘     └─────────────┘
```

<img width="100%" alt="Database ER Diagram" src="https://github.com/user-attachments/assets/a71a62a7-40c4-4eda-9a12-810fce0f4f4c" />

---

## 🌐 Production Deployment

### Standard Production

```bash
# 1. Set environment
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# 2. Build assets
npm run build

# 3. Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 4. Sync signatures (jika ada data existing)
php artisan letters:sync-signatures
```

### PHP Settings (php.ini)

```ini
upload_max_filesize = 15M
post_max_size = 50M
max_file_uploads = 20
memory_limit = 256M
```

---

## 🔗 Ngrok Deployment

Untuk deploy via ngrok (tunneling):

### 1. Update `.env`

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-subdomain.ngrok-free.dev

# Session configuration for ngrok
SESSION_DOMAIN=.ngrok-free.dev
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=none

# Logging
LOG_LEVEL=error
```

### 2. Trust Proxies

File `bootstrap/app.php` sudah dikonfigurasi untuk trust proxies:
```php
$middleware->trustProxies(at: '*');
```

### 3. Run Ngrok

```bash
# Terminal 1: Laravel server
php artisan serve --host=127.0.0.1 --port=8000

# Terminal 2: Ngrok tunnel
ngrok http 8000 --subdomain=your-subdomain
```

### 4. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## 📡 LAN Hosting

Untuk akses dari perangkat lain di jaringan lokal:

```bash
# Cari IP lokal
ipconfig  # Windows
ip addr   # Linux

# Jalankan server
php artisan serve --host=0.0.0.0 --port=8000

# Buka firewall (PowerShell Admin - Windows)
netsh advfirewall firewall add rule name="Laravel" dir=in action=allow protocol=tcp localport=8000
```

Akses: `http://192.168.x.x:8000`

---

## ⚙️ Artisan Commands

| Command | Description |
|---------|-------------|
| `php artisan letters:sync-signatures` | Generate signature untuk surat tanpa signature |
| `php artisan letters:sync-signatures --force` | Regenerate semua signature |
| `php artisan signature:generate` | Generate SIGNATURE_KEY baru |
| `php artisan optimize` | Cache config, routes, views |
| `php artisan optimize:clear` | Clear all caches |

---

## 🤝 Kontribusi

1. Fork repository
2. Create branch: `git checkout -b feature/nama-fitur`
3. Commit: `git commit -m 'Add: nama fitur'`
4. Push: `git push origin feature/nama-fitur`
5. Create Pull Request

---

## 📄 License

© 2025 E-Surat Perkim. All rights reserved.

---

<p align="center">
  <strong>Made with ❤️ for Indonesian Government Institutions</strong>
</p>
