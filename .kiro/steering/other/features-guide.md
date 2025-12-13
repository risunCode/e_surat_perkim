# Panduan Fitur E-Surat Perkim

## Fitur Utama

### 1. Manajemen Surat Masuk

**Route:** `/transaksi/surat-masuk`
**Controller:** `IncomingLetterController`

#### Alur Kerja
1. User membuat surat masuk baru dengan data:
   - Nomor surat (unique)
   - Nomor agenda (optional)
   - Pengirim (from)
   - Tanggal surat
   - Tanggal diterima
   - Klasifikasi
   - Deskripsi
   - Catatan
   - Lampiran (multiple files)

2. Setelah disimpan:
   - Digital signature di-generate otomatis
   - Notifikasi dikirim ke semua user aktif
   - QR Code tersedia untuk verifikasi

3. Surat masuk dapat:
   - Dilihat detailnya
   - Diedit (oleh admin saja)
   - Dihapus (oleh admin saja)
   - Ditandai selesai/belum selesai
   - Diberi disposisi

---

### 2. Manajemen Surat Keluar

**Route:** `/transaksi/surat-keluar`
**Controller:** `OutgoingLetterController`

#### Alur Kerja
1. User membuat surat keluar dengan data:
   - Nomor surat (unique)
   - Nomor agenda (optional)
   - Tujuan (to)
   - Tanggal surat
   - Klasifikasi
   - Deskripsi
   - Catatan
   - **Referensi ke surat masuk** (optional - untuk reply)
   - Lampiran

2. Jika surat keluar adalah balasan:
   - Pilih surat masuk yang dibalas
   - Sistem mencatat relasi `reference_to`
   - Correspondence chain terbentuk

---

### 3. Disposisi

**Route:** `/disposisi/{letter}`
**Controller:** `DispositionController`

#### Alur Kerja
1. Dari detail surat masuk, user dapat membuat disposisi
2. Data disposisi:
   - Tujuan disposisi (to)
   - Tanggal jatuh tempo (due_date)
   - Isi disposisi (content)
   - Catatan (note)
   - Status (letter_status)

3. Status disposisi:
   - Diproses
   - Ditindaklanjuti
   - Selesai
   - (Custom status dari admin)

4. Notifikasi dikirim ke semua user saat disposisi dibuat

---

### 4. Correspondence Chain (Rantai Korespondensi)

#### Konsep
Surat dapat memiliki relasi parent-child:
- Surat masuk → Surat keluar (balasan) → Surat masuk (balasan dari balasan) → ...

#### Implementasi
```php
// Model Letter
public function referenceTo(): BelongsTo
{
    return $this->belongsTo(Letter::class, 'reference_to');
}

public function replies(): HasMany
{
    return $this->hasMany(Letter::class, 'reference_to');
}

public function getCorrespondenceChain(): Collection
{
    // Get root letter
    $root = $this;
    while ($root->referenceTo) {
        $root = $root->referenceTo;
    }
    
    // Build chain from root
    $chain = collect();
    $this->buildChain($root, $chain);
    return $chain;
}
```

#### Tampilan
Di halaman detail surat, correspondence chain ditampilkan sebagai timeline.

---

### 5. Digital Signature & QR Verification

#### Generate Signature
```php
// Otomatis saat create/update surat
app(SignatureService::class)->generateLetterSignature($letter);
```

#### Signature Content
```php
[
    'reference_number' => $letter->reference_number,
    'type' => 'incoming' | 'outgoing',
    'from_to' => $letter->from | $letter->to,
    'letter_date' => $letter->letter_date,
    'description' => $letter->description,
    'classification' => $letter->classification->code,
    'user' => $letter->user->name,
    'attachments_count' => count,
    'attachments' => [filenames...]
]
```

#### Verification
- URL: `/verify/{signature_hash}`
- Public access (no auth required)
- Menampilkan detail dokumen jika valid

---

### 6. Agenda & Laporan

**Route:** `/agenda/masuk`, `/agenda/keluar`
**Controller:** `AgendaController`

#### Fitur
- List surat dengan filter tanggal
- Cetak PDF agenda
- Export data

---

### 7. Galeri Lampiran

**Route:** `/galeri/masuk`, `/galeri/keluar`
**Controller:** `GalleryController`

#### Fitur
- Grid view semua lampiran
- Preview gambar
- Download file
- Filter berdasarkan tipe file

---

### 8. Notifikasi

**Route:** `/notifications`
**Controller:** `NotificationController`

#### Tipe Notifikasi
- `incoming` - Surat masuk baru
- `outgoing` - Surat keluar baru
- `disposition` - Disposisi baru
- `tracking` - Update tracking
- `outgoing_status_updated` - Status update

#### Fitur
- Real-time badge count (polling setiap 2 menit)
- Mark as read
- Mark all as read
- Link ke surat terkait

---

### 9. Profile Management

**Route:** `/profile`
**Controller:** `ProfileController`

#### Fitur
- Update nama, email, phone
- Update password
- Update security questions
- Upload foto profil (dengan crop)
- Deactivate account

---

### 10. Admin Panel

**Route:** `/admin/*`
**Middleware:** `role:admin`

#### User Management
- List users
- Create/edit/delete users
- Toggle active status
- Assign roles

#### Classification Management
- CRUD klasifikasi surat
- Code, type, description

#### Letter Status Management
- CRUD status disposisi
- Name, color, description

#### Reference Code Management
- CRUD kode referensi
- Code, role, max_usage
- Toggle active status
- View usage history

#### Settings
- App name
- App description
- Organization name
- Organization address
- Notification refresh interval

---

## Business Rules

### Authorization
1. **Admin** dapat mengakses semua data dan fitur, termasuk edit/delete semua surat
2. **Staff** hanya dapat:
   - Melihat semua surat
   - Membuat surat baru
   - Membuat disposisi
   - TIDAK dapat edit/delete surat (admin only)
   - Tidak dapat mengakses admin panel

### File Upload
1. Format yang diizinkan: PDF, DOC, DOCX, PPT, PPTX, TXT, JPG, JPEG, PNG, GIF
2. Max size per file: 15MB
3. Max total size: 15MB
4. Filename di-generate dengan UUID untuk keamanan

### Completion Status
1. Surat masuk dapat ditandai selesai/belum selesai
2. Saat surat keluar (balasan) dibuat, disposisi terkait otomatis di-update ke "Selesai"

### Security Questions
1. Wajib diisi setelah registrasi pertama kali
2. Digunakan untuk reset password tanpa email
3. Dapat diupdate di halaman profile
