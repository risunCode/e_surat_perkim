# Route Documentation - E-Surat Perkim

Total: **76 routes**

---

# PROPOSED: Route Baru (Indonesian)

## Perbandingan Route

| Current | Proposed | Keterangan |
|---------|----------|------------|
| `/transaction/incoming` | `/transaksi/surat-masuk` | Full Indonesian |
| `/transaction/outgoing` | `/transaksi/surat-keluar` | Full Indonesian |
| `/disposition` | `/disposisi` | Indonesian |
| `/agenda/incoming` | `/agenda/masuk` | Konsisten |
| `/agenda/outgoing` | `/agenda/keluar` | Konsisten |
| `/gallery/incoming` | `/galeri/masuk` | Indonesian |
| `/gallery/outgoing` | `/galeri/keluar` | Indonesian |
| `/notifications` | `/notifikasi` | Indonesian |
| `/profile` | `/profil` | Indonesian |
| `/admin/users` | `/kelola/pengguna` | Full Indonesian |
| `/admin/classifications` | `/kelola/klasifikasi` | Full Indonesian |
| `/admin/statuses` | `/kelola/status` | Full Indonesian |
| `/admin/reference-codes` | `/kelola/kode-referral` | Full Indonesian |
| `/admin/settings` | `/kelola/pengaturan` | Full Indonesian |
| `/auth` | `/masuk` | Indonesian |
| `/forgot-password` | `/lupa-password` | Indonesian |
| `/security-setup` | `/setup-keamanan` | Indonesian |
| `/about` | `/tentang` | Indonesian |

---

## Proposed Routes (Full)

### Guest Routes
| Method | URI | Name |
|--------|-----|------|
| GET | `/masuk` | auth |
| GET | `/lupa-password` | password.request |
| POST | `/lupa-password` | password.verify-email |
| GET | `/verifikasi-keamanan` | password.security |
| POST | `/verifikasi-keamanan` | password.verify-security |
| GET | `/reset-password` | password.reset.form |
| POST | `/reset-password` | password.update.local |

### Security Setup
| Method | URI | Name |
|--------|-----|------|
| GET | `/setup-keamanan` | security.setup |
| POST | `/setup-keamanan` | security.store |

### Dashboard & General
| Method | URI | Name |
|--------|-----|------|
| GET | `/` | dashboard |
| GET | `/tentang` | about |

### Transaksi - Surat Masuk
| Method | URI | Name |
|--------|-----|------|
| GET | `/transaksi/surat-masuk` | masuk.index |
| GET | `/transaksi/surat-masuk/tambah` | masuk.create |
| POST | `/transaksi/surat-masuk` | masuk.store |
| GET | `/transaksi/surat-masuk/{surat}` | masuk.show |
| GET | `/transaksi/surat-masuk/{surat}/edit` | masuk.edit |
| PUT | `/transaksi/surat-masuk/{surat}` | masuk.update |
| DELETE | `/transaksi/surat-masuk/{surat}` | masuk.destroy |
| PATCH | `/transaksi/surat-masuk/{surat}/selesai` | masuk.toggleCompletion |

### Transaksi - Surat Keluar
| Method | URI | Name |
|--------|-----|------|
| GET | `/transaksi/surat-keluar` | keluar.index |
| GET | `/transaksi/surat-keluar/tambah` | keluar.create |
| POST | `/transaksi/surat-keluar` | keluar.store |
| GET | `/transaksi/surat-keluar/{surat}` | keluar.show |
| GET | `/transaksi/surat-keluar/{surat}/edit` | keluar.edit |
| PUT | `/transaksi/surat-keluar/{surat}` | keluar.update |
| DELETE | `/transaksi/surat-keluar/{surat}` | keluar.destroy |
| PATCH | `/transaksi/surat-keluar/{surat}/selesai` | keluar.toggleCompletion |

### Disposisi
| Method | URI | Name |
|--------|-----|------|
| GET | `/disposisi/{surat}/tambah` | disposisi.create |
| POST | `/disposisi/{surat}` | disposisi.store |
| GET | `/disposisi/{disposisi}/edit` | disposisi.edit |
| PUT | `/disposisi/{disposisi}` | disposisi.update |
| DELETE | `/disposisi/{disposisi}` | disposisi.destroy |

### Agenda
| Method | URI | Name |
|--------|-----|------|
| GET | `/agenda/masuk` | agenda.masuk |
| GET | `/agenda/keluar` | agenda.keluar |
| GET | `/agenda/masuk/cetak` | agenda.masuk.print |
| GET | `/agenda/keluar/cetak` | agenda.keluar.print |

### Galeri
| Method | URI | Name |
|--------|-----|------|
| GET | `/galeri/masuk` | galeri.masuk |
| GET | `/galeri/keluar` | galeri.keluar |

### Notifikasi
| Method | URI | Name |
|--------|-----|------|
| GET | `/notifikasi` | notifikasi.index |
| GET | `/notifikasi/belum-dibaca` | notifikasi.unread |
| POST | `/notifikasi/{notifikasi}/baca` | notifikasi.read |
| POST | `/notifikasi/baca-semua` | notifikasi.read-all |

### Profil
| Method | URI | Name |
|--------|-----|------|
| GET | `/profil` | profil.show |
| PUT | `/profil` | profil.update |
| PUT | `/profil/password` | profil.password |
| POST | `/profil/foto` | profil.photo |
| POST | `/profil/nonaktif` | profil.deactivate |

### Kelola - Pengguna (Admin)
| Method | URI | Name |
|--------|-----|------|
| GET | `/kelola/pengguna` | kelola.pengguna.index |
| GET | `/kelola/pengguna/tambah` | kelola.pengguna.create |
| POST | `/kelola/pengguna` | kelola.pengguna.store |
| GET | `/kelola/pengguna/{user}/edit` | kelola.pengguna.edit |
| PUT | `/kelola/pengguna/{user}` | kelola.pengguna.update |
| DELETE | `/kelola/pengguna/{user}` | kelola.pengguna.destroy |
| POST | `/kelola/pengguna/{user}/toggle` | kelola.pengguna.toggle |

### Kelola - Klasifikasi (Admin)
| Method | URI | Name |
|--------|-----|------|
| GET | `/kelola/klasifikasi` | kelola.klasifikasi.index |
| GET | `/kelola/klasifikasi/tambah` | kelola.klasifikasi.create |
| POST | `/kelola/klasifikasi` | kelola.klasifikasi.store |
| GET | `/kelola/klasifikasi/{id}/edit` | kelola.klasifikasi.edit |
| PUT | `/kelola/klasifikasi/{id}` | kelola.klasifikasi.update |
| DELETE | `/kelola/klasifikasi/{id}` | kelola.klasifikasi.destroy |

### Kelola - Status (Admin)
| Method | URI | Name |
|--------|-----|------|
| GET | `/kelola/status` | kelola.status.index |
| GET | `/kelola/status/tambah` | kelola.status.create |
| POST | `/kelola/status` | kelola.status.store |
| GET | `/kelola/status/{id}/edit` | kelola.status.edit |
| PUT | `/kelola/status/{id}` | kelola.status.update |
| DELETE | `/kelola/status/{id}` | kelola.status.destroy |

### Kelola - Kode Referral (Admin)
| Method | URI | Name |
|--------|-----|------|
| GET | `/kelola/kode-referral` | kelola.referral.index |
| GET | `/kelola/kode-referral/tambah` | kelola.referral.create |
| POST | `/kelola/kode-referral` | kelola.referral.store |
| GET | `/kelola/kode-referral/{id}/edit` | kelola.referral.edit |
| PUT | `/kelola/kode-referral/{id}` | kelola.referral.update |
| DELETE | `/kelola/kode-referral/{id}` | kelola.referral.destroy |
| POST | `/kelola/kode-referral/{id}/toggle` | kelola.referral.toggle |
| GET | `/kelola/kode-referral/{id}/penggunaan` | kelola.referral.usage |

### Kelola - Pengaturan (Admin)
| Method | URI | Name |
|--------|-----|------|
| GET | `/kelola/pengaturan` | kelola.pengaturan.index |
| PUT | `/kelola/pengaturan` | kelola.pengaturan.update |

---

## Impact Analysis

### Yang Perlu Diubah:
1. **routes/web.php** - Update semua route definition
2. **Controllers** - Rename route names di redirect
3. **Views (Blade)** - Update semua `route()` helper calls
4. **Middleware** - Check jika ada hardcoded paths
5. **JavaScript** - Update fetch/axios URLs

### Database Migration:
- **TIDAK PERLU** migrate database
- Route hanya mengubah URL, bukan struktur data
- Model & table names tetap sama (letters, dispositions, dll)

### Tips:
1. Gunakan **Find & Replace** di seluruh project
2. Test setiap module setelah rename
3. Update `.env` jika ada URL hardcoded

---
---

---

## Guest Routes (Unauthenticated)

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/auth` | auth | Login/Register page |
| GET | `/forgot-password` | password.request | Forgot password form |
| POST | `/forgot-password` | password.verify-email | Verify email |
| GET | `/verify-security` | password.security | Security question form |
| POST | `/verify-security` | password.verify-security | Verify security answer |
| GET | `/reset-password` | password.reset.form | Reset password form |
| POST | `/reset-password` | password.update.local | Update password |

---

## Security Setup

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/security-setup` | security.setup | Setup security form |
| POST | `/security-setup` | security.store | Save security setup |

---

## Dashboard & General

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/` | dashboard | Dashboard |
| GET | `/about` | about | About page |

---

## Transaction - Surat Masuk

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/transaction/incoming` | incoming.index | List surat masuk |
| GET | `/transaction/incoming/create` | incoming.create | Form tambah |
| POST | `/transaction/incoming` | incoming.store | Simpan |
| GET | `/transaction/incoming/{letter}` | incoming.show | Detail |
| GET | `/transaction/incoming/{letter}/edit` | incoming.edit | Form edit |
| PUT | `/transaction/incoming/{letter}` | incoming.update | Update |
| DELETE | `/transaction/incoming/{letter}` | incoming.destroy | Hapus |
| PATCH | `/transaction/incoming/{letter}/toggle-completion` | incoming.toggleCompletion | Toggle selesai |

---

## Transaction - Surat Keluar

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/transaction/outgoing` | outgoing.index | List surat keluar |
| GET | `/transaction/outgoing/create` | outgoing.create | Form tambah |
| POST | `/transaction/outgoing` | outgoing.store | Simpan |
| GET | `/transaction/outgoing/{letter}` | outgoing.show | Detail |
| GET | `/transaction/outgoing/{letter}/edit` | outgoing.edit | Form edit |
| PUT | `/transaction/outgoing/{letter}` | outgoing.update | Update |
| DELETE | `/transaction/outgoing/{letter}` | outgoing.destroy | Hapus |
| PATCH | `/transaction/outgoing/{letter}/toggle-completion` | outgoing.toggleCompletion | Toggle selesai |

---

## Disposisi

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/disposition/{letter}/create` | disposition.create | Form disposisi |
| POST | `/disposition/{letter}` | disposition.store | Simpan |
| GET | `/disposition/{disposition}/edit` | disposition.edit | Form edit |
| PUT | `/disposition/{disposition}` | disposition.update | Update |
| DELETE | `/disposition/{disposition}` | disposition.destroy | Hapus |

---

## Agenda

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/agenda/incoming` | agenda.incoming | Agenda surat masuk |
| GET | `/agenda/outgoing` | agenda.outgoing | Agenda surat keluar |
| GET | `/agenda/incoming/print` | agenda.incoming.print | Print agenda masuk |
| GET | `/agenda/outgoing/print` | agenda.outgoing.print | Print agenda keluar |

---

## Gallery

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/gallery/incoming` | gallery.incoming | Galeri lampiran masuk |
| GET | `/gallery/outgoing` | gallery.outgoing | Galeri lampiran keluar |

---

## Notifications

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/notifications` | notifications.index | List notifikasi |
| GET | `/notifications/unread` | notifications.unread | Get unread (API) |
| POST | `/notifications/{notification}/read` | notifications.read | Mark as read |
| POST | `/notifications/read-all` | notifications.read-all | Mark all read |

---

## Profile

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/profile` | profile.show | Lihat profil |
| PUT | `/profile` | profile.update | Update profil |
| PUT | `/profile/password` | profile.password | Ganti password |
| POST | `/profile/photo` | profile.photo | Update foto |
| POST | `/profile/deactivate` | profile.deactivate | Nonaktifkan akun |

---

## Admin - Users

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/users` | admin.users.index | List users |
| GET | `/admin/users/create` | admin.users.create | Form tambah |
| POST | `/admin/users` | admin.users.store | Simpan |
| GET | `/admin/users/{user}/edit` | admin.users.edit | Form edit |
| PUT | `/admin/users/{user}` | admin.users.update | Update |
| DELETE | `/admin/users/{user}` | admin.users.destroy | Hapus |
| POST | `/admin/users/{user}/toggle-status` | admin.users.toggle-status | Toggle aktif |

---

## Admin - Classifications

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/classifications` | admin.classifications.index | List klasifikasi |
| GET | `/admin/classifications/create` | admin.classifications.create | Form tambah |
| POST | `/admin/classifications` | admin.classifications.store | Simpan |
| GET | `/admin/classifications/{classification}/edit` | admin.classifications.edit | Form edit |
| PUT | `/admin/classifications/{classification}` | admin.classifications.update | Update |
| DELETE | `/admin/classifications/{classification}` | admin.classifications.destroy | Hapus |

---

## Admin - Letter Statuses

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/statuses` | admin.statuses.index | List status |
| GET | `/admin/statuses/create` | admin.statuses.create | Form tambah |
| POST | `/admin/statuses` | admin.statuses.store | Simpan |
| GET | `/admin/statuses/{status}/edit` | admin.statuses.edit | Form edit |
| PUT | `/admin/statuses/{status}` | admin.statuses.update | Update |
| DELETE | `/admin/statuses/{status}` | admin.statuses.destroy | Hapus |

---

## Admin - Reference Codes

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/reference-codes` | admin.reference-codes.index | List kode referral |
| GET | `/admin/reference-codes/create` | admin.reference-codes.create | Form tambah |
| POST | `/admin/reference-codes` | admin.reference-codes.store | Simpan |
| GET | `/admin/reference-codes/{code}/edit` | admin.reference-codes.edit | Form edit |
| PUT | `/admin/reference-codes/{code}` | admin.reference-codes.update | Update |
| DELETE | `/admin/reference-codes/{code}` | admin.reference-codes.destroy | Hapus |
| POST | `/admin/reference-codes/{code}/toggle-status` | admin.reference-codes.toggle-status | Toggle aktif |
| GET | `/admin/reference-codes/{code}/usage` | admin.reference-codes.usage | Lihat usage |

---

## Admin - Settings

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/settings` | admin.settings.index | Halaman settings |
| PUT | `/admin/settings` | admin.settings.update | Update settings |

---

## Summary

| Group | Count |
|-------|-------|
| Guest (Auth) | 7 |
| Security Setup | 2 |
| Dashboard & About | 2 |
| Transaction Incoming | 8 |
| Transaction Outgoing | 8 |
| Disposition | 5 |
| Agenda | 4 |
| Gallery | 2 |
| Notifications | 4 |
| Profile | 5 |
| Admin Users | 7 |
| Admin Classifications | 6 |
| Admin Statuses | 6 |
| Admin Reference Codes | 8 |
| Admin Settings | 2 |
| **Total** | **76** |
