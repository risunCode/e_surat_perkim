# 📋 DOKUMENTASI LENGKAP E-SURAT PERKIM

## 🎯 RINGKASAN APLIKASI

**E-SURAT PERKIM** adalah sistem manajemen surat elektronik modern berbasis **Laravel 11** dengan **TailwindCSS**. Aplikasi ini memiliki fitur manajemen surat masuk, surat keluar, disposisi, balas surat, dan pelacakan korespondensi. Dilengkapi dengan sistem tema dinamis (Light/Dark/Ocean/Forest/Sunset), UI modern, dan notifikasi real-time.

**Versi**: 2.0 (Updated: 25 November 2025)

---

## 🎨 SISTEM TEMA (THEME SYSTEM)

### Tema yang Tersedia:
1. **Light** - Tema terang default
2. **Dark** - Tema gelap 
3. **Ocean** - Tema biru laut
4. **Forest** - Tema hijau hutan
5. **Sunset** - Tema oranye senja

### CSS Variables:
```css
--bg-main        /* Background utama */
--bg-sidebar     /* Background sidebar */
--bg-card        /* Background kartu */
--bg-input       /* Background input */
--text-primary   /* Teks utama */
--text-secondary /* Teks sekunder */
--border-color   /* Warna border */
--accent-color   /* Warna aksen */
--accent-hover   /* Warna aksen hover */
```

---

## 🗂️ STRUKTUR NAVIGASI

### **SIDEBAR MENU**

#### 1. BERANDA
- **Icon**: 🏠 `bx-home-circle`
- **Route**: `/`
- **Akses**: Semua user

#### 2. TRANSAKSI SURAT
- **Surat Masuk** - `/transaction/incoming`
  - Daftar surat masuk dengan status (Baru/Dibalas/Ada Disposisi)
  - Create, Read, Update, Delete
  - Balas surat → buat surat keluar
  - Buat disposisi
  - Upload lampiran (PDF, DOC, JPG, PNG)
  
- **Surat Keluar** - `/transaction/outgoing`
  - Daftar surat keluar dengan status (Original/Balasan)
  - Create, Read, Update, Delete
  - Upload lampiran

#### 3. BUKU AGENDA
- **Agenda Surat Masuk** - `/agenda/incoming`
- **Agenda Surat Keluar** - `/agenda/outgoing`

#### 4. GALERI SURAT
- **Galeri Surat Masuk** - `/gallery/incoming`
  - Tabel dengan search & filter
  - Client-side sorting
  - Preview & download lampiran
  - Filter tanggal (advanced)
  
- **Galeri Surat Keluar** - `/gallery/outgoing`
  - Sama seperti galeri masuk

#### 5. ADMINISTRATOR (Admin Only)
- **Kelola Pengguna** - `/admin/user`
- **Kode Referensi** - `/admin/reference-code`
- **Klasifikasi Surat** - `/admin/classification`
- **Status Disposisi** - `/admin/status`
- **Pengaturan** - `/admin/settings`

### **TOP NAVBAR**
- **Search Bar** - Pencarian global surat masuk
- **Theme Toggle** - Dropdown pilihan tema (5 tema)
- **Notifications** - Dropdown notifikasi
  - Badge unread count
  - Mark as read per item (klik langsung)
  - Mark all as read
  - Notifikasi sudah dibaca: warna abu-abu
- **User Menu** - Profile, Logout

---

## 🆕 FITUR BARU: BALAS SURAT & KORESPONDENSI

### Alur Balas Surat:
```
📥 Surat Masuk (dari PT ABC)
    │
    ├── 🔄 BALAS → Buat 📤 Surat Keluar BARU
    │              (Form pre-filled dengan data pengirim)
    │              (Terhubung via reference_to)
    │
    └── 📋 DISPOSISI → Teruskan internal
```

### Database: Kolom reference_to
```sql
letters.reference_to (bigint, FK → letters.id, nullable)
```

### Model Letter - Relasi Baru:
```php
// Surat yang dibalas (parent)
public function referenceTo()

// Balasan dari surat ini (children)  
public function replies()

// Cek tipe surat
public function isIncoming()
public function isOutgoing()

// Ambil chain korespondensi lengkap
public function getCorrespondenceChain()
```

### Tampilan Korespondensi:
```
🔀 Korespondensi
Riwayat surat dan balasannya • 🟣 Surat Masuk → 🟢 Surat Keluar

┌─────────┐     ┌─────────────┐
│ ① 1522  │ → │ ② 1522xxx2  │
│ 25 Nov  │     │ 25 Nov      │
└─────────┘     └─────────────┘
```

---

## 📝 FITUR DISPOSISI

### Tampilan Grid Cards:
```
┌─────────────────────────────┐  ┌─────────────────────────────┐
│ [Pending]         25 Nov 25 │  │ [Selesai]        24 Nov 25  │
├─────────────────────────────┤  ├─────────────────────────────┤
│ KEPADA          JATUH TEMPO │  │ KEPADA          JATUH TEMPO │
│ Pak Budi        29 Nov 2025 │  │ Pak Andi        28 Nov 2025 │
│                 3 hari lagi │  │                 Selesai     │
│ INSTRUKSI                   │  │ INSTRUKSI                   │
│ Tindak lanjuti segera       │  │ Review dokumen              │
├─────────────────────────────┤  ├─────────────────────────────┤
│ oleh Admin  [Lihat][Edit][X]│  │ oleh Admin  [Lihat][Edit][X]│
└─────────────────────────────┘  └─────────────────────────────┘
```

### Modal Detail Disposisi:
- Header: Judul + Status badge
- Grid 3 kolom: Kepada, Dibuat, Jatuh Tempo
- Instruksi lengkap
- Catatan (jika ada)
- Tombol: Edit, Tutup

### Status Disposisi:
- 🟡 **Pending** - Kuning
- 🔴 **Urgent** - Merah
- 🟢 **Selesai** - Hijau
- Terlambat: Tampil "Terlambat!" dengan warna merah

---

## 📊 KOLOM STATUS DI TABEL

### Surat Masuk:
| Status | Warna | Kondisi |
|--------|-------|---------|
| 🟡 Baru | Kuning | Belum ada balasan & disposisi |
| 🟢 Dibalas | Hijau | Ada surat keluar sebagai balasan |
| 🔵 Disposisi | Biru | Ada disposisi (+ jumlah) |

### Surat Keluar:
| Status | Warna | Kondisi |
|--------|-------|---------|
| 🟣 Balasan | Ungu | Merupakan balasan surat masuk |
| ⚪ Original | Abu-abu | Surat keluar mandiri |

---

## 🔔 SISTEM NOTIFIKASI

### Fitur:
- Real-time polling (setiap 2 menit)
- Badge unread count
- Mark as read per notifikasi (klik langsung redirect)
- Notifikasi sudah dibaca: background abu-abu
- Scrollbar tersembunyi di dropdown

### Tipe Notifikasi:
- `incoming` - Surat masuk baru
- `outgoing` - Surat keluar baru
- `disposition` - Disposisi baru
- `reply` - Balasan surat

### API Endpoints:
```
GET  /notifications          - Halaman notifikasi
GET  /notifications/unread   - Ambil notifikasi belum dibaca
POST /notifications/{id}/read - Tandai dibaca
POST /notifications/read-all  - Tandai semua dibaca
```

---

## 🗄️ SKEMA DATABASE

### Tabel letters (Updated):
```sql
- id (bigint, PK)
- reference_number (varchar, unique)
- agenda_number (varchar)
- from (varchar, nullable)
- to (varchar, nullable)
- letter_date (date)
- received_date (date, nullable)
- description (text, nullable)
- note (text, nullable)
- type (enum: 'incoming', 'outgoing')
- classification_code (FK → classifications.code)
- user_id (FK → users.id)
- reference_to (FK → letters.id, nullable) -- NEW!
- created_at, updated_at
```

### Relasi Letters:
```
letters (N) ───> (1) letters (reference_to/self-reference)
letters (1) ───< (N) letters (replies)
letters (1) ───< (N) dispositions
letters (1) ───< (N) attachments
letters (N) ───> (1) classifications
letters (N) ───> (1) users
```

---

## 📁 STRUKTUR FILE VIEWS

```
resources/views/
├── layouts/
│   └── app.blade.php          # Layout utama dengan tema
├── components/
│   ├── navbar.blade.php       # Navbar dengan search, theme, notif
│   └── sidebar.blade.php      # Sidebar navigasi
├── pages/
│   ├── dashboard.blade.php    # Dashboard statistik
│   ├── transaction/
│   │   ├── incoming/
│   │   │   ├── index.blade.php  # Daftar + status column
│   │   │   ├── show.blade.php   # Detail + disposisi grid + korespondensi
│   │   │   └── create.blade.php
│   │   └── outgoing/
│   │       ├── index.blade.php  # Daftar + status column
│   │       ├── show.blade.php   # Detail + korespondensi
│   │       └── create.blade.php # Form balas surat (3 kolom)
│   ├── gallery/
│   │   ├── incoming.blade.php   # Galeri + sort + filter
│   │   └── outgoing.blade.php
│   ├── notifications/
│   │   └── index.blade.php      # Halaman semua notifikasi
│   └── admin/
│       ├── user/
│       ├── reference-code/
│       ├── classification/
│       └── status/
```

---

## 🔐 ROLE & PERMISSIONS

### Staff:
✅ CRUD surat masuk/keluar (milik sendiri)
✅ Buat & kelola disposisi (milik sendiri)
✅ Balas surat masuk
✅ View galeri & agenda
✅ Notifikasi
✅ Update profile

### Administrator:
✅ Semua akses Staff
✅ CRUD semua surat (semua user)
✅ Kelola pengguna
✅ Kelola kode referensi
✅ Kelola klasifikasi & status
✅ Pengaturan sistem

---

## 🎨 UI/UX IMPROVEMENTS

### Tombol Aksi dengan Label:
```html
[👁 Lihat] [✏️ Edit] [🗑️ Hapus]
```
Semua tombol aksi memiliki teks label, bukan hanya icon.

### Form Balas Surat (3 Kolom):
```
┌─────────────────────────────────┬───────────────┐
│  Form (2/3 lebar)               │  Info (1/3)   │
│  ┌────────┬─────┬─────┬─────┐   │  📧 Surat     │
│  │No.Surat│Agenda│Tgl  │     │   │     Asal     │
│  ├────────┴──────┴─────┼─────┤   │  - No: xxx   │
│  │ Kepada              │Klas │   │  - Dari: xxx │
│  └─────────────────────┴─────┘   │              │
│  [Kirim Balasan] [Batal]         │              │
└─────────────────────────────────┴───────────────┘
```

### Galeri dengan Advanced Filter:
- Search real-time
- Filter klasifikasi
- Filter tanggal (toggle advanced)
- Client-side sorting (klik header)

---

## ⚙️ KONFIGURASI

### Environment (.env):
```env
APP_NAME="E-Surat Perkim"
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=mysql
DB_DATABASE=e_surat_perkim
```

### Vite Config:
```js
// vite.config.js
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```

---

## 📈 STATISTIK PROJECT

| Metrik | Jumlah |
|--------|--------|
| Models | 9 |
| Controllers | 13 |
| Migrations | 18 |
| Seeders | 8 |
| Database Tables | 14 |
| User Roles | 2 |
| Tema | 5 |
| Routes (Web) | ~50+ |

---

## ✅ FITUR YANG SUDAH DIIMPLEMENTASI

### Core Features:
- [x] Autentikasi (Login/Register/Forgot Password)
- [x] Role-based Access Control
- [x] CRUD Surat Masuk
- [x] CRUD Surat Keluar
- [x] CRUD Disposisi
- [x] Upload Lampiran (Multi-file)
- [x] Balas Surat dengan Chain Tracking
- [x] Notifikasi Real-time
- [x] Mark as Read per Notifikasi

### UI/UX:
- [x] Multi-theme (5 tema)
- [x] Responsive Design
- [x] Modern Card Layout
- [x] Grid Disposisi
- [x] Timeline Korespondensi
- [x] SweetAlert Confirmations
- [x] Hidden Scrollbar di Navbar

### Admin:
- [x] Kelola Pengguna
- [x] Kode Referensi
- [x] Klasifikasi Surat
- [x] Status Disposisi
- [x] Pengaturan Sistem

---

## 🔒 SECURITY CHECKLIST

- ✅ CSRF Token protection
- ✅ Password hashing (bcrypt)
- ✅ Role-based middleware
- ✅ SQL injection protection (Eloquent)
- ✅ XSS protection (Blade escaping)
- ✅ File upload validation
- ⚠️ Rate limiting (belum)
- ⚠️ 2FA (belum)
- ⚠️ Activity logging (belum)

---

## 📝 CATATAN PENGEMBANGAN

### Session 25 November 2025:
1. Implementasi fitur Balas Surat
2. Tambah kolom `reference_to` di tabel letters
3. Tambah relasi `referenceTo` dan `replies` di Model Letter
4. Tambah tampilan Korespondensi Chain
5. Tambah kolom Status di tabel surat masuk/keluar
6. Redesign Disposisi ke Grid Cards
7. Perbaiki Modal Detail Disposisi (compact, 3 kolom)
8. Notifikasi mark as read per item
9. Hidden scrollbar di navbar dropdown
10. Perbaikan UI/UX secara keseluruhan

---

**Generated**: 25 November 2025  
**Version**: 2.0  
**Author**: Development Team
