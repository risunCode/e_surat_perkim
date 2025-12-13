# Arsitektur Aplikasi E-Surat Perkim

## Pola Arsitektur

Aplikasi ini menggunakan arsitektur **MVC (Model-View-Controller)** dengan tambahan **Service Layer** untuk business logic yang kompleks.

```
┌─────────────────────────────────────────────────────────────┐
│                        HTTP Request                          │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                     Middleware Stack                         │
│  SecurityHeaders → SanitizeInput → CheckUserActive → Auth   │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                       Controllers                            │
│  IncomingLetterController, OutgoingLetterController, etc.   │
└─────────────────────────────────────────────────────────────┘
                              │
                    ┌─────────┴─────────┐
                    ▼                   ▼
┌───────────────────────┐   ┌───────────────────────┐
│       Services        │   │        Models         │
│  SignatureService     │   │  Letter, User, etc.   │
│  NotificationService  │   │                       │
└───────────────────────┘   └───────────────────────┘
                    │                   │
                    └─────────┬─────────┘
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                        Database                              │
│              MySQL / MariaDB / SQLite                        │
└─────────────────────────────────────────────────────────────┘
```

## Middleware Stack

Middleware diregistrasi di `bootstrap/app.php`:

| Middleware | Fungsi |
|------------|--------|
| `SecurityHeaders` | Menambahkan security headers (X-XSS-Protection, X-Frame-Options, dll) |
| `SanitizeInput` | Mendeteksi dan memblokir SQL injection & XSS |
| `CheckUserActive` | Memastikan user aktif sebelum akses |
| `Authenticate` | Verifikasi autentikasi user |
| `RoleMiddleware` | Kontrol akses berdasarkan role (admin/staff) |
| `EnsureSecuritySetup` | Memastikan user sudah setup security questions |
| `ThrottleLogins` | Rate limiting untuk login (5x/menit) |

## Service Layer

### SignatureService
Menangani pembuatan dan verifikasi digital signature untuk dokumen:
- `generateLetterSignature()` - Generate signature baru (invalidate yang lama)
- `getOrCreateTranscriptSignature()` - Get atau create signature untuk transcript
- `generateDocumentContent()` - Generate content untuk hashing

### NotificationService
Menangani broadcast notifikasi ke semua user:
- `broadcastToAll()` - Kirim notifikasi ke semua user aktif
- `bulkCreate()` - Bulk insert notifikasi (optimized)
- `notifyIncomingLetter()` - Notifikasi surat masuk baru
- `notifyOutgoingLetter()` - Notifikasi surat keluar baru
- `notifyDisposition()` - Notifikasi disposisi baru

## Routing Structure

```
/                           → Dashboard
/auth                       → Login/Register (guest only)
/verify/{hash}              → Document verification (public)

/transaksi/surat-masuk/*    → CRUD Surat Masuk
/transaksi/surat-keluar/*   → CRUD Surat Keluar
/disposisi/*                → CRUD Disposisi

/agenda/masuk               → Agenda Surat Masuk
/agenda/keluar              → Agenda Surat Keluar

/galeri/masuk               → Galeri Lampiran Surat Masuk
/galeri/keluar              → Galeri Lampiran Surat Keluar

/histori/*                  → Activity Log (Histori Aktivitas)

/notifications/*            → Notifikasi
/profile/*                  → Profile Management

/admin/*                    → Admin Panel (role:admin)
  /admin/users              → User Management
  /admin/classifications    → Klasifikasi Surat
  /admin/statuses           → Status Disposisi
  /admin/reference-codes    → Kode Referensi
  /admin/settings           → Pengaturan Aplikasi
```

## Authorization Pattern

```php
// Di Controller - Manual authorization (admin only)
private function authorizeEdit(Letter $letter)
{
    if (!Auth::user()->isAdmin()) {
        abort(403, 'Hanya administrator yang dapat mengubah atau menghapus surat.');
    }
}

// Di Routes - Middleware authorization
Route::middleware('role:admin')->prefix('admin')->group(function () {
    // Admin-only routes
});
```

## File Upload Pattern

```php
// Validasi ukuran total file (max 15MB)
if ($request->hasFile('attachments')) {
    $totalSize = 0;
    foreach ($request->file('attachments') as $file) {
        $totalSize += $file->getSize();
    }
    if ($totalSize > 15 * 1024 * 1024) {
        return back()->withErrors(['attachments' => 'Total ukuran tidak boleh lebih dari 15MB.']);
    }
}

// Store dengan UUID filename
$filename = \Str::uuid() . '.' . $file->getClientOriginalExtension();
$file->storeAs('attachments/incoming', $filename, 'public');
```

## Eager Loading Pattern

```php
// Optimized eager loading dengan selective columns
$query = Letter::incoming()->with([
    'user:id,name',
    'classification:code,type,description',
    'attachments:id,letter_id,filename,extension,path,mime_type',
    'replies:id,reference_to',
    'dispositions:id,letter_id,letter_status'
]);
```
