---
description: E-Surat Perkim - Repository Information Overview
alwaysApply: true
---

# E-Surat Perkim - Sistem Manajemen Surat Elektronik

## Summary
**E-SURAT PERKIM** adalah sistem manajemen surat elektronik modern berbasis **Laravel 11** dengan **TailwindCSS** dan sistem tema dinamis. Aplikasi ini digunakan untuk mengelola surat masuk, surat keluar, disposisi, dan fitur balas surat dengan pelacakan korespondensi. Dilengkapi dengan autentikasi Laravel Fortify, role-based access control, notifikasi real-time, dan UI/UX modern dengan dukungan tema terang/gelap.

## Tech Stack
- **Backend**: Laravel 11 (PHP 8.2+)
- **Authentication**: Laravel Fortify
- **Frontend**: Blade Templates + TailwindCSS
- **Icons**: Boxicons
- **Build Tool**: Vite
- **Notifications**: SweetAlert2
- **Charts**: (Dashboard statistics)

## Key Commands
```bash
composer install          # Install PHP dependencies
npm install               # Install Node dependencies
npm run dev              # Start Vite dev server
php artisan migrate      # Run database migrations
php artisan db:seed      # Seed initial data
php artisan serve        # Start Laravel dev server
php artisan storage:link # Create storage symlink
```

## Database Tables (14 Tables)
1. users, letters, dispositions, attachments
2. classifications, letter_statuses, notifications
3. reference_codes, reference_code_usage
4. configs, password_resets, failed_jobs
5. personal_access_tokens, sessions

## Models (9)
User, Letter, Disposition, Attachment, Classification, LetterStatus, Notification, ReferenceCode, Config

## Controllers (13)
PageController, IncomingLetterController, OutgoingLetterController, DispositionController, GalleryController, NotificationController, UserController, ReferenceCodeController, ClassificationController, LetterStatusController, SettingController, ProfileController, BroadcastController
