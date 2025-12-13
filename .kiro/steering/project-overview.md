# E-Surat Perkim - Project Steering

Sistem manajemen surat-menyurat digital untuk instansi pemerintah Indonesia.

## Tech Stack
- **Backend:** Laravel 12, PHP 8.3+
- **Database:** MySQL 8.0 / MariaDB / SQLite
- **Frontend:** Blade, TailwindCSS 4.x, Vite 7
- **Auth:** Laravel Fortify
- **Icons:** Boxicons | **Alerts:** SweetAlert2

## Quick Commands
```bash
composer dev                    # Run dev server + queue + vite
php artisan migrate:fresh --seed # Reset database
php artisan optimize:clear      # Clear all caches
php artisan signature:generate  # Generate SIGNATURE_KEY
```

## Authorization Rules
- **Admin:** Full access ke semua fitur, dapat edit/delete semua surat
- **Staff:** Hanya dapat melihat surat, TIDAK bisa edit/delete (admin only)

## Key Models
| Model | Deskripsi |
|-------|-----------|
| Letter | Surat masuk/keluar (type: incoming/outgoing) |
| Disposition | Disposisi surat dengan status |
| Attachment | Lampiran file (max 15MB total) |
| DocumentSignature | Digital signature untuk verifikasi |
| ActivityLog | Log aktivitas user (NEW) |

## Main Routes
```
/                           → Dashboard
/transaksi/surat-masuk/*    → CRUD Surat Masuk
/transaksi/surat-keluar/*   → CRUD Surat Keluar
/disposisi/{letter}/*       → Disposisi
/histori/*                  → Activity Log (NEW)
/verify/{hash}              → Document Verification (public)
/admin/*                    → Admin Panel (role:admin)
```

## File Upload
- Format: PDF, DOC, DOCX, PPT, PPTX, TXT, JPG, JPEG, PNG, GIF
- Max total: 15MB
- Filename: UUID untuk keamanan

## Services
- `SignatureService` - Generate/verify digital signatures
- `NotificationService` - Broadcast notifications ke semua user

## Dokumentasi Lengkap
Lihat folder `.kiro/steering/other/` untuk dokumentasi detail:
- `controllers-guide.md` - Panduan controllers & validation
- `models-guide.md` - Panduan Eloquent models
- `database-schema.md` - ERD & relationships
- `frontend-guide.md` - Blade components & JS patterns
- `security-guide.md` - Middleware & security
- `troubleshooting.md` - Common issues & solutions
