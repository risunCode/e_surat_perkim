# API Reference E-Surat Perkim

## Overview

Aplikasi ini menggunakan web routes dengan Blade views. Beberapa endpoint mengembalikan JSON untuk AJAX requests.

---

## Authentication Routes

### Guest Only (Unauthenticated)

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/auth` | `auth` | Login/Register page |
| GET | `/forgot-password` | `password.request` | Forgot password form |
| POST | `/forgot-password` | `password.verify-email` | Verify email exists |
| GET | `/verify-security` | `password.security` | Security question form |
| POST | `/verify-security` | `password.verify-security` | Verify security answer |
| GET | `/reset-password` | `password.reset.form` | Reset password form |
| POST | `/reset-password` | `password.update.local` | Update password |

### Authenticated Only

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/security-setup` | `security.setup` | Security setup form |
| POST | `/security-setup` | `security.store` | Store security questions |

---

## Public Routes (No Auth)

### Document Verification

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/verify` | `document.verification` | Verification form |
| POST | `/verify` | `document.check` | Check document |
| GET | `/verify/{hash}` | `document.verify` | Verify by hash |

---

## Authenticated Routes

### Dashboard

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/` | `dashboard` | Dashboard page |
| GET | `/about` | `about` | About page |

### Surat Masuk

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/transaksi/surat-masuk` | `incoming.index` | List surat masuk |
| GET | `/transaksi/surat-masuk/tambah` | `incoming.create` | Create form |
| POST | `/transaksi/surat-masuk` | `incoming.store` | Store surat |
| GET | `/transaksi/surat-masuk/{letter}` | `incoming.show` | Show detail |
| GET | `/transaksi/surat-masuk/{letter}/edit` | `incoming.edit` | Edit form |
| PUT | `/transaksi/surat-masuk/{letter}` | `incoming.update` | Update surat |
| DELETE | `/transaksi/surat-masuk/{letter}` | `incoming.destroy` | Delete surat |
| PATCH | `/transaksi/surat-masuk/{letter}/selesai` | `incoming.toggleCompletion` | Toggle completion |

### Surat Keluar

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/transaksi/surat-keluar` | `outgoing.index` | List surat keluar |
| GET | `/transaksi/surat-keluar/tambah` | `outgoing.create` | Create form |
| POST | `/transaksi/surat-keluar` | `outgoing.store` | Store surat |
| GET | `/transaksi/surat-keluar/{letter}` | `outgoing.show` | Show detail |
| GET | `/transaksi/surat-keluar/{letter}/edit` | `outgoing.edit` | Edit form |
| PUT | `/transaksi/surat-keluar/{letter}` | `outgoing.update` | Update surat |
| DELETE | `/transaksi/surat-keluar/{letter}` | `outgoing.destroy` | Delete surat |
| PATCH | `/transaksi/surat-keluar/{letter}/selesai` | `outgoing.toggleCompletion` | Toggle completion |

### Disposisi

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/disposisi/{letter}/tambah` | `disposition.create` | Create form |
| POST | `/disposisi/{letter}` | `disposition.store` | Store disposisi |
| GET | `/disposisi/{disposition}/edit` | `disposition.edit` | Edit form |
| PUT | `/disposisi/{disposition}` | `disposition.update` | Update disposisi |
| DELETE | `/disposisi/{disposition}` | `disposition.destroy` | Delete disposisi |

### Agenda

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/agenda/masuk` | `agenda.incoming` | Agenda surat masuk |
| GET | `/agenda/keluar` | `agenda.outgoing` | Agenda surat keluar |
| GET | `/agenda/masuk/cetak` | `agenda.incoming.print` | Print agenda masuk |
| GET | `/agenda/keluar/cetak` | `agenda.outgoing.print` | Print agenda keluar |

### Galeri

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/galeri/masuk` | `gallery.incoming` | Galeri surat masuk |
| GET | `/galeri/keluar` | `gallery.outgoing` | Galeri surat keluar |

### Activity Log (Histori Aktivitas)

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/histori` | `activity-log.index` | List activity logs |
| GET | `/histori/{activityLog}` | `activity-log.show` | Show detail |

### Notifications (JSON)

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/notifications` | `notifications.index` | List notifications |
| GET | `/notifications/unread` | `notifications.unread` | Get unread (JSON) |
| GET | `/notifications/realtime` | `notifications.realtime` | Get realtime updates (JSON) |
| POST | `/notifications/{notification}/read` | `notifications.read` | Mark as read |
| POST | `/notifications/read-all` | `notifications.read-all` | Mark all as read |

### Profile

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/profile` | `profile.show` | Profile page |
| PUT | `/profile` | `profile.update` | Update profile |
| PUT | `/profile/password` | `profile.password` | Update password |
| PUT | `/profile/security` | `profile.security` | Update security |
| POST | `/profile/photo` | `profile.photo` | Update photo |
| POST | `/profile/deactivate` | `profile.deactivate` | Deactivate account |

### File Serving

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/attachments/{id}` | `attachment.serve` | Serve file |
| GET | `/attachments/{id}/download` | `attachment.download` | Download file |
| GET | `/profile-pictures/{filename}` | `profile.picture` | Profile picture |

### Transcript

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/transcript/{type}/{id}/print` | `transcript.print` | Print view |
| GET | `/galeri/masuk/print/{id}` | `gallery.incoming.print` | Gallery preview |
| GET | `/galeri/keluar/print/{id}` | `gallery.outgoing.print` | Gallery preview |

---

## Admin Routes (role:admin)

### Users

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/users` | `admin.users.index` | List users |
| GET | `/admin/users/create` | `admin.users.create` | Create form |
| POST | `/admin/users` | `admin.users.store` | Store user |
| GET | `/admin/users/{user}/edit` | `admin.users.edit` | Edit form |
| PUT | `/admin/users/{user}` | `admin.users.update` | Update user |
| DELETE | `/admin/users/{user}` | `admin.users.destroy` | Delete user |
| POST | `/admin/users/{user}/toggle-status` | `admin.users.toggle-status` | Toggle active |

### Classifications

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/classifications` | `admin.classifications.index` | List |
| GET | `/admin/classifications/create` | `admin.classifications.create` | Create form |
| POST | `/admin/classifications` | `admin.classifications.store` | Store |
| GET | `/admin/classifications/{classification}/edit` | `admin.classifications.edit` | Edit form |
| PUT | `/admin/classifications/{classification}` | `admin.classifications.update` | Update |
| DELETE | `/admin/classifications/{classification}` | `admin.classifications.destroy` | Delete |

### Letter Statuses

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/statuses` | `admin.statuses.index` | List |
| GET | `/admin/statuses/create` | `admin.statuses.create` | Create form |
| POST | `/admin/statuses` | `admin.statuses.store` | Store |
| GET | `/admin/statuses/{status}/edit` | `admin.statuses.edit` | Edit form |
| PUT | `/admin/statuses/{status}` | `admin.statuses.update` | Update |
| DELETE | `/admin/statuses/{status}` | `admin.statuses.destroy` | Delete |

### Reference Codes

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/reference-codes` | `admin.reference-codes.index` | List |
| GET | `/admin/reference-codes/create` | `admin.reference-codes.create` | Create form |
| POST | `/admin/reference-codes` | `admin.reference-codes.store` | Store |
| GET | `/admin/reference-codes/{referenceCode}/edit` | `admin.reference-codes.edit` | Edit form |
| PUT | `/admin/reference-codes/{referenceCode}` | `admin.reference-codes.update` | Update |
| DELETE | `/admin/reference-codes/{referenceCode}` | `admin.reference-codes.destroy` | Delete |
| POST | `/admin/reference-codes/{referenceCode}/toggle-status` | `admin.reference-codes.toggle-status` | Toggle active |
| GET | `/admin/reference-codes/{referenceCode}/usage` | `admin.reference-codes.usage` | View usage |

### Settings

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/settings` | `admin.settings.index` | Settings page |
| PUT | `/admin/settings` | `admin.settings.update` | Update settings |

### Attack Alerts

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/attack-alerts` | `admin.attack-alerts.index` | List alerts |
| GET | `/admin/attack-alerts/{attackAlert}` | `admin.attack-alerts.show` | Show detail |
| POST | `/admin/attack-alerts/{attackAlert}/mitigate` | `admin.attack-alerts.mitigate` | Mark as mitigated |
| POST | `/admin/attack-alerts/{attackAlert}/archive` | `admin.attack-alerts.archive` | Archive alert |
| POST | `/admin/attack-alerts/{attackAlert}/block-ip` | `admin.attack-alerts.block-ip` | Block IP (3 days) |
| DELETE | `/admin/attack-alerts/{attackAlert}` | `admin.attack-alerts.destroy` | Delete alert |
| POST | `/admin/attack-alerts/bulk/mitigate-all` | `admin.attack-alerts.mitigate-all` | Mitigate all active |
| POST | `/admin/attack-alerts/bulk/archive-all` | `admin.attack-alerts.archive-all` | Archive all |
| DELETE | `/admin/attack-alerts/bulk/delete-archived` | `admin.attack-alerts.delete-archived` | Delete all archived |

### Blocked IPs

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/blocked-ips` | `admin.blocked-ips.index` | List blocked IPs |
| POST | `/admin/blocked-ips` | `admin.blocked-ips.store` | Block IP manually |
| DELETE | `/admin/blocked-ips/{blockedIp}` | `admin.blocked-ips.destroy` | Unblock IP |
| DELETE | `/admin/blocked-ips/unblock-all/all` | `admin.blocked-ips.unblock-all` | Unblock all IPs |

---

## JSON Responses

### Notifications Unread
```json
GET /notifications/unread

Response:
{
    "count": 5,
    "notifications": [
        {
            "id": 1,
            "type": "incoming",
            "title": "Surat Masuk Baru",
            "message": "Surat masuk baru dengan nomor ABC/123",
            "link": "/transaksi/surat-masuk/1",
            "icon": "bx-envelope-open",
            "is_read": false,
            "created_at": "2025-01-01T00:00:00.000000Z"
        }
    ]
}
```

### Mark Notification as Read
```json
POST /notifications/{id}/read

Response:
{
    "success": true
}
```

### Attack Alerts Active (for toast notification)
```json
GET /api/attack-alerts/active

Response:
{
    "count": 2,
    "active_count": 1,
    "mitigated_count": 1,
    "alerts": [
        {
            "id": 1,
            "type": "xss",
            "severity": "high",
            "status": "mitigated",
            "ip": "192.168.1.100",
            "url": "/auth?search=<script>",
            "time": "5 minutes ago",
            "timestamp": "2025-12-13T15:25:53+07:00",
            "user_agent": "Mozilla/5.0..."
        }
    ]
}
```
