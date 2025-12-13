# 📚 E-Surat Perkim - Steering Documentation

Dokumentasi lengkap untuk pemahaman mendalam tentang project E-Surat Perkim v2.0.1 "Optimized Shorekeeper".

---

## 📋 Daftar Dokumentasi

### 🏠 Overview
| File | Deskripsi |
|------|-----------|
| [project-overview.md](project-overview.md) | Gambaran umum project, tech stack, struktur direktori |

### 🏗️ Architecture
| File | Deskripsi |
|------|-----------|
| [architecture.md](architecture.md) | Pola arsitektur MVC + Service Layer, middleware stack |
| [database-schema.md](database-schema.md) | ERD, relationships, enums, indexes |

### 💻 Backend
| File | Deskripsi |
|------|-----------|
| [models-guide.md](models-guide.md) | Panduan semua Eloquent models, fillable, casts, methods |
| [controllers-guide.md](controllers-guide.md) | Panduan controllers, CRUD patterns, validation rules |
| [api-reference.md](api-reference.md) | Daftar lengkap routes dan endpoints |

### 🎨 Frontend
| File | Deskripsi |
|------|-----------|
| [frontend-guide.md](frontend-guide.md) | Blade components, TailwindCSS, SweetAlert2, Boxicons |

### 🔐 Security
| File | Deskripsi |
|------|-----------|
| [security-guide.md](security-guide.md) | Authentication, middleware, digital signature, CSRF |

### ⚡ Features
| File | Deskripsi |
|------|-----------|
| [features-guide.md](features-guide.md) | Panduan fitur utama, business rules, alur kerja |

### 🛠️ Development
| File | Deskripsi |
|------|-----------|
| [development-workflow.md](development-workflow.md) | Quick start, artisan commands, deployment |
| [coding-standards.md](coding-standards.md) | Naming conventions, code style, best practices |
| [troubleshooting.md](troubleshooting.md) | Common issues dan solusinya |

---

## 🚀 Quick Reference

### Tech Stack
- **Backend:** Laravel 12, PHP 8.3+
- **Database:** MySQL 8.0 / MariaDB / SQLite
- **Frontend:** Blade, TailwindCSS 4.x, Vite 7
- **Auth:** Laravel Fortify
- **PDF:** barryvdh/laravel-dompdf

### Default Login
```
Email: admin@perkim.go.id
Password: perkim2025x
```

### Essential Commands
```bash
# Development
composer dev              # Run all dev services
php artisan serve         # Laravel server only
npm run dev               # Vite dev server

# Database
php artisan migrate       # Run migrations
php artisan db:seed       # Run seeders
php artisan migrate:fresh --seed  # Reset database

# Cache
php artisan optimize      # Cache for production
php artisan optimize:clear # Clear all caches

# Custom
php artisan signature:generate        # Generate SIGNATURE_KEY
php artisan letters:sync-signatures   # Sync document signatures
```

### Key Routes
```
/                           → Dashboard
/transaksi/surat-masuk      → Surat Masuk
/transaksi/surat-keluar     → Surat Keluar
/disposisi/{letter}         → Disposisi
/verify/{hash}              → Document Verification (public)
/admin/*                    → Admin Panel
```

### Models
```
User, Letter, Disposition, Attachment, Classification,
LetterStatus, Notification, DocumentSignature, ReferenceCode, Config
```

### Enums
```php
Role: ADMIN, STAFF
LetterType: INCOMING, OUTGOING
NotificationType: INCOMING, OUTGOING, DISPOSITION, TRACKING, OUTGOING_STATUS_UPDATED
```

---

## 📁 Project Structure

```
e-surat-perkim/
├── app/
│   ├── Console/Commands/     # Artisan commands
│   ├── Enums/                # Role, LetterType, etc.
│   ├── Http/
│   │   ├── Controllers/      # Main + Admin controllers
│   │   └── Middleware/       # Security middleware
│   ├── Models/               # 10 Eloquent models
│   └── Services/             # SignatureService, NotificationService
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Data seeders
├── resources/views/
│   ├── components/           # Blade components
│   ├── layouts/              # Layout templates
│   └── pages/                # Page views
└── routes/web.php            # Web routes
```

---

## 🔗 Related Files

- `README.md` - Project README
- `CHANGELOG.md` - Version history
- `SECURITY.md` - Security policy
- `.env.example` - Environment template
