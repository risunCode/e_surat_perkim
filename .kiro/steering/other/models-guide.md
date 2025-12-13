# Panduan Models E-Surat Perkim

## Daftar Models

| Model | Tabel | Deskripsi |
|-------|-------|-----------|
| User | users | Pengguna aplikasi |
| Letter | letters | Surat masuk/keluar |
| Disposition | dispositions | Disposisi surat |
| Attachment | attachments | Lampiran file |
| Classification | classifications | Klasifikasi surat |
| LetterStatus | letter_statuses | Status disposisi |
| Notification | notifications | Notifikasi user |
| DocumentSignature | document_signatures | Digital signature |
| ReferenceCode | reference_codes | Kode referensi registrasi |
| Config | configs | Konfigurasi aplikasi |

---

## User Model

**File:** `app/Models/User.php`

### Fillable
```php
'name', 'email', 'password', 'phone', 'role', 'is_active',
'profile_picture', 'birth_date', 'security_question', 
'security_answer', 'security_setup_completed'
```

### Casts
```php
'email_verified_at' => 'datetime',
'password' => 'hashed',
'is_active' => 'boolean',
'birth_date' => 'date',
'security_setup_completed' => 'boolean'
```

### Helper Methods
```php
isAdmin(): bool      // Cek apakah user adalah admin
isStaff(): bool      // Cek apakah user adalah staff
getInitialsAttribute(): string  // Get inisial nama (e.g., "JD" dari "John Doe")
```

---

## Letter Model

**File:** `app/Models/Letter.php`

### Fillable
```php
'reference_number', 'agenda_number', 'from', 'to', 'letter_date',
'received_date', 'description', 'note', 'type', 'is_completed',
'completed_at', 'reference_to', 'classification_code', 'user_id'
```

### Casts
```php
'letter_date' => 'date',
'received_date' => 'date',
'is_completed' => 'boolean',
'completed_at' => 'datetime'
```

### Scopes
```php
scopeIncoming($query)  // Filter surat masuk
scopeOutgoing($query)  // Filter surat keluar
```

### Helper Methods
```php
isIncoming(): bool           // Cek apakah surat masuk
isOutgoing(): bool           // Cek apakah surat keluar
hasReply(): bool             // Cek apakah sudah dibalas
needsReply(): bool           // Cek apakah perlu dibalas
markAsCompleted(): void      // Tandai selesai
markAsIncomplete(): void     // Tandai belum selesai
getCorrespondenceChain(): Collection  // Get rantai korespondensi
```

### Correspondence Chain
Surat dapat memiliki relasi reply chain:
- `referenceTo` - Surat yang dibalas (parent)
- `replies` - Surat-surat balasan (children)

```php
// Contoh: Get semua surat terkait
$chain = $letter->getCorrespondenceChain();
// Returns: [Original Incoming] → [Reply Outgoing] → [Reply to Reply] → ...
```

---

## Disposition Model

**File:** `app/Models/Disposition.php`

### Fillable
```php
'to', 'due_date', 'content', 'note', 'letter_status', 'letter_id', 'user_id'
```

### Casts
```php
'due_date' => 'date'
```

### Relationships
```php
letter()  // BelongsTo Letter
status()  // BelongsTo LetterStatus
user()    // BelongsTo User (pembuat disposisi)
```

---

## Attachment Model

**File:** `app/Models/Attachment.php`

### Fillable
```php
'path', 'filename', 'extension', 'file_size', 'mime_type', 'letter_id', 'user_id'
```

### Accessors
```php
getFullPathAttribute(): string      // path/filename
getFormattedSizeAttribute(): string // "1.5 MB"
```

### Helper Methods
```php
isImage(): bool       // Cek apakah file gambar
isPdf(): bool         // Cek apakah file PDF
getFileIcon(): string // Get Boxicons class
getFileColor(): string // Get Tailwind color class
getThumbnailUrl(): string // Get URL thumbnail untuk gambar
```

---

## DocumentSignature Model

**File:** `app/Models/DocumentSignature.php`

### Fillable
```php
'letter_id', 'user_id', 'document_type', 'signature_hash',
'content_hash', 'signed_at', 'ip_address', 'user_agent', 'metadata', 'is_valid'
```

### Casts
```php
'signed_at' => 'datetime',
'metadata' => 'array',
'is_valid' => 'boolean'
```

### Static Methods
```php
generateSignature(string $content, int $letterId, int $userId, string $documentType): string
generateContentHash(string $content): string
verifySignature(string $signatureHash): ?self
```

### Accessors
```php
getVerificationUrlAttribute(): string  // URL untuk verifikasi publik
```

---

## Classification Model

**File:** `app/Models/Classification.php`

### Fillable
```php
'code', 'type', 'description'
```

### Relationships
```php
letters()  // HasMany Letter (via classification_code)
```

---

## Notification Model

**File:** `app/Models/Notification.php`

### Fillable
```php
'user_id', 'type', 'title', 'message', 'link', 'icon', 'data', 'is_read', 'read_at'
```

### Casts
```php
'is_read' => 'boolean',
'read_at' => 'datetime',
'data' => 'array'
```

### Scopes
```php
scopeUnread($query)  // Filter notifikasi belum dibaca
```

### Methods
```php
markAsRead(): void  // Tandai sudah dibaca
```

---

## Best Practices

### Eager Loading
Selalu gunakan eager loading untuk menghindari N+1 queries:
```php
// ✅ Good
$letters = Letter::with(['user', 'classification', 'attachments'])->get();

// ❌ Bad
$letters = Letter::all();
foreach ($letters as $letter) {
    echo $letter->user->name; // N+1 query!
}
```

### Selective Columns
Gunakan selective columns untuk optimasi:
```php
Letter::with([
    'user:id,name',
    'classification:code,type,description'
])->get();
```

### Scopes
Gunakan scopes untuk query yang sering dipakai:
```php
// Di Model
public function scopeActive($query) {
    return $query->where('is_active', true);
}

// Penggunaan
User::active()->get();
```
