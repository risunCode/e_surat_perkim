# Database Schema E-Surat Perkim

## Entity Relationship Diagram

```
┌─────────────────┐     ┌─────────────────────┐     ┌──────────────────┐
│      users      │────<│       letters       │────<│   dispositions   │
│─────────────────│     │─────────────────────│     │──────────────────│
│ id              │     │ id                  │     │ id               │
│ name            │     │ reference_number    │     │ to               │
│ email           │     │ agenda_number       │     │ due_date         │
│ password        │     │ from/to             │     │ content          │
│ phone           │     │ letter_date         │     │ note             │
│ role            │     │ received_date       │     │ letter_status    │
│ is_active       │     │ description         │     │ letter_id (FK)   │
│ profile_picture │     │ note                │     │ user_id (FK)     │
│ birth_date      │     │ type                │     └──────────────────┘
│ security_*      │     │ is_completed        │
└─────────────────┘     │ completed_at        │
                        │ reference_to (FK)   │────┐ Self-reference
                        │ classification_code │    │ (reply chain)
                        │ user_id (FK)        │<───┘
                        └─────────────────────┘
                               │  │
          ┌────────────────────┘  └────────────────────┐
          ▼                                            ▼
┌─────────────────────┐                    ┌────────────────────────┐
│    attachments      │                    │  document_signatures   │
│─────────────────────│                    │────────────────────────│
│ id                  │                    │ id                     │
│ path                │                    │ letter_id (FK)         │
│ filename            │                    │ user_id (FK)           │
│ extension           │                    │ document_type          │
│ file_size           │                    │ signature_hash         │
│ mime_type           │                    │ content_hash           │
│ letter_id (FK)      │                    │ signed_at              │
│ user_id (FK)        │                    │ ip_address             │
└─────────────────────┘                    │ user_agent             │
                                           │ metadata (JSON)        │
                                           │ is_valid               │
                                           └────────────────────────┘

┌─────────────────────┐     ┌─────────────────────┐
│   classifications   │     │   letter_statuses   │
│─────────────────────│     │─────────────────────│
│ id                  │     │ id                  │
│ code (unique)       │     │ name                │
│ type                │     │ color               │
│ description         │     │ description         │
└─────────────────────┘     └─────────────────────┘

┌─────────────────────┐     ┌─────────────────────────┐
│   reference_codes   │────<│  reference_code_usage   │
│─────────────────────│     │─────────────────────────│
│ id                  │     │ reference_code_id (FK)  │
│ code (unique)       │     │ user_id (FK)            │
│ role                │     │ used_at                 │
│ max_usage           │     └─────────────────────────┘
│ is_active           │
│ created_by (FK)     │
└─────────────────────┘

┌─────────────────────┐     ┌─────────────────────┐
│    notifications    │     │       configs       │
│─────────────────────│     │─────────────────────│
│ id                  │     │ id                  │
│ user_id (FK)        │     │ key (unique)        │
│ type                │     │ value               │
│ title               │     │ description         │
│ message             │     └─────────────────────┘
│ link                │
│ icon                │
│ data (JSON)         │
│ is_read             │
│ read_at             │
└─────────────────────┘
```

## Model Relationships

### User
```php
hasMany: letters, dispositions, attachments, userNotifications, createdReferenceCodes
belongsToMany: usedReferenceCodes (pivot: reference_code_usage)
```

### Letter
```php
belongsTo: user, classification, referenceTo (self)
hasMany: dispositions, attachments, signatures, replies (self)
hasOne: latestSignature
```

### Disposition
```php
belongsTo: letter, status (LetterStatus), user
```

### Attachment
```php
belongsTo: letter, user
```

### DocumentSignature
```php
belongsTo: letter, user
```

## Enums

### Role
```php
ADMIN = 'admin'    // Administrator - full access
STAFF = 'staff'    // Staff - limited access
```

### LetterType
```php
INCOMING = 'incoming'  // Surat Masuk
OUTGOING = 'outgoing'  // Surat Keluar
```

### NotificationType
```php
INCOMING = 'incoming'                      // Surat Masuk Baru
OUTGOING = 'outgoing'                      // Surat Keluar Baru
DISPOSITION = 'disposition'                // Disposisi Baru
TRACKING = 'tracking'                      // Tracking Update
OUTGOING_STATUS_UPDATED = 'outgoing_status_updated'  // Status Update
```

### ConfigKey
```php
APP_NAME = 'app_name'
APP_DESCRIPTION = 'app_description'
ORGANIZATION_NAME = 'organization_name'
ORGANIZATION_ADDRESS = 'organization_address'
NOTIFICATION_REFRESH_INTERVAL = 'notification_refresh_interval'
```

## Indexes (Performance)

Migration `2025_11_30_102831_add_performance_indexes.php` menambahkan index untuk:
- letters: type, classification_code, user_id, is_completed, reference_to
- dispositions: letter_id, letter_status, user_id
- attachments: letter_id, user_id
- notifications: user_id, is_read
- document_signatures: letter_id, signature_hash, is_valid
