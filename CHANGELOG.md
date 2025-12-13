# Changelog
All notable changes to E-Surat Perkim will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [2.1.1] "Fortified Shield" - 2025-12-14
**"Security Hardened & Battle Tested"** 🛡️

### ✨ Added
- **🛡️ Attack Alert Management System**
  - New status workflow: Active → Mitigated → Archived
  - Bulk actions: Mitigate All, Archive All, Delete Archived
  - Per-alert actions: Mitigate, Archive, Block IP, Delete
  - Mitigation notes support for tracking remediation
  - Live security console with real-time attack monitoring
  - Enhanced stats dashboard (Active, Mitigated, Archived, Today, Critical)
  - Tips section for admin guidance

- **🚫 IP Blocking System**
  - Block malicious IPs from attack alerts
  - Default block duration: 3 days (auto-expire)
  - Option for permanent blocks
  - Manual IP blocking via admin panel
  - Unblock single or all IPs
  - Integration with SanitizeInput middleware

- **🔔 Attack Alert Toast Notification**
  - Real-time toast notification for admin (bottom-right)
  - Shows attack type, severity, IP, and target
  - Snooze for 30 minutes option
  - Auto-clear snooze when all alerts archived
  - Polling every 10 seconds for new attacks

- **🔒 Content Security Policy (CSP)**
  - Optional CSP header protection against XSS
  - Configurable via `CSP_ENABLED=true` in .env
  - Whitelisted CDNs: jsdelivr, unpkg, Google Fonts
  - Vite dev server and websocket support
  - `/test-deface` excluded for pentest demo

- **🎯 Pentest Tool Improvements**
  - IDOR test now detects actual login status (not logged in / admin / staff)
  - Smart vulnerability detection based on user role
  - Brute force shows valid credentials when found
  - Debug logging for response status and redirects
  - Ngrok interstitial bypass header
  - Target URL logging on attack start

### 🔧 Fixed
- **Authorization Consistency**
  - Disposition edit/delete now admin-only (matching Letter policy)
  - Attachment access simplified (all authenticated users can view)
  
- **CSRF Protection**
  - `/test-deface` properly excluded from CSRF validation
  - Fixed 419 errors on pentest deface endpoint

- **Pentest Tool Bugs**
  - Fixed brute force not detecting valid passwords
  - Fixed IDOR test always saying "logged in as admin"
  - Fixed path detector not finding paths on ngrok

### 🔄 Changed
- **Attack Alert Model**
  - New `status` field replacing `is_dismissed`
  - Added `mitigated_by`, `mitigated_at`, `mitigation_notes`
  - Added `archived_by`, `archived_at`
  - Scopes: `active()`, `mitigated()`, `archived()`, `notArchived()`

- **Security Headers Middleware**
  - CSP disabled by default (opt-in via .env)
  - Dynamic CSP based on environment
  - Excluded routes for intentionally vulnerable endpoints

### 📚 Documentation
- Updated SECURITY.md with CSP configuration guide
- Updated .env.example with CSP_ENABLED option
- Added CSP warning and testing notes

### 🗄️ Database
- Migration: `add_status_to_attack_alerts_table`
- Migration: `create_blocked_ips_table`
- Auto-migrates existing dismissed alerts to archived status
- Attack logging throttle: 1 log per IP per attack type per 5 minutes
- Auto-cleanup archived alerts older than 7 days

---

## [2.0.2] "Flowing Tides" - 2025-12-12
**"Real-time & Refined"** 🔔

### ✨ Added
- **🔔 Real-time Notification System**
  - Toast notifications slide dari kanan atas
  - Sound alerts menggunakan Web Audio API (Windows Style Extended)
  - Badge counter di sidebar untuk surat masuk/keluar
  - Polling setiap 30 detik untuk update real-time
  - Test functions: `testNotification()`, `testNotificationSound()`

- **🎵 Notification Sound Options**
  - Windows Style Extended (active) - ascending dengan bounce
  - Office Chime Professional (backup) - tersimpan di comment
  - Pure Web Audio API, tidak perlu file eksternal

- **📍 Toast Notification Features**
  - Posisi: kanan atas dibawah navbar
  - Auto-dismiss setelah 8 detik
  - Tombol close yang jelas (bulat, hover merah)
  - Klik untuk navigate ke surat terkait

### 🔧 Fixed
- **Timezone Configuration**
  - Changed dari `UTC` ke `Asia/Jakarta`

- **Dynamic Status Lookup**
  - Hardcoded status ID `3` diganti dengan `LetterStatus::getCompletedStatusId()`
  - Caching untuk performance

- **SanitizeInput Middleware**
  - Proper response handling untuk non-AJAX requests
  - `expectsJson()` check untuk return type

- **Disposisi Validation**
  - Added `after_or_equal:today` untuk `due_date`

### 🗑️ Removed
- **Folder `database/migraplus/`** - SQL files tidak lagi diperlukan
- **Logo `logo-kab.png`** dari navbar
- **IP Display di production** - hanya tampil saat `APP_DEBUG=true`

### 🔄 Changed
- **Notification Controller**
  - New `getRealtime()` method untuk enhanced polling
  - Badge counts untuk incoming/outgoing notifications

- **Project Structure**
  - Steering docs dipindah ke `.kiro/steering/`
  - Added `public/js/realtime-notifications.js`

### 📚 Documentation
- **README.md** - Updated installation guide
  - Hapus referensi ke migraplus SQL files
  - Added "Setup First Admin User" section
  - Updated project structure

---

## [2.0.1] "Optimized Shorekeeper" - 2025-11-30
**"Performance & UX Refined"** ⚡

### ✨ Added
- **🚀 Performance Optimization System**
  - Database indexing untuk query performance (50-90% faster)
  - Missing indexes: `is_completed`, `reference_to`, `letter_status`, `due_date`
  - Composite indexes untuk complex queries
  - Query optimization dengan selective columns dan eager loading

- **🔐 Enhanced Security Questions Management**
  - Simplified profile-based security management
  - Two-step modal flow (birth date → security questions)
  - Birth date verification instead of password confirmation
  - Status indicator with visual feedback (✅ Sudah Diatur / ⚠️ Belum Diatur)
  - Smart pre-fill untuk existing questions

- **💎 Professional Password Reset Flow**
  - Complete UI redesign matching document verification style
  - Formal government system aesthetics (no gradients)
  - Header-Content-Footer structure dengan official branding
  - Consistent styling across all reset pages
  - Authenticated user support (sibaraku-style flow)

- **⚙️ Comprehensive Environment Configuration**
  - Complete `.env.example` dengan deployment scenarios
  - Local, Ngrok, dan Production configuration guides
  - Signature key generation tutorial
  - Security checklist dan performance tips
  - Deployment-ready examples

- **🛡️ Enhanced Error Handling**
  - Try-catch blocks around critical service calls
  - Graceful failure handling (service errors don't break main operations)
  - Proper logging untuk debugging
  - Resilient notification dan signature services

### 🔧 Fixed
- **Authentication Flow Issues**
  - Removed guest middleware blocking dari password reset routes
  - Authenticated users dapat access forgot password (sibaraku pattern)
  - Direct security form rendering (no 302 redirects)
  - Smooth password reset experience untuk logged-in users

- **Asset Loading Stability**
  - Icon loading fixes dengan proper CSS delivery
  - Boxicons fallback implementation
  - CSP headers adjustment untuk external resources
  - Cache clearing automation

- **UI/UX Improvements** 
  - Redundant birth date hints removal
  - Clean modal interfaces
  - Professional color scheme (gray-based)
  - Consistent button styling dan interactions

### 🔄 Changed
- **Database Performance Architecture**
  - Migration: `add_performance_indexes` untuk optimal query speed
  - Notification indexing untuk faster unread counts
  - Letter status indexing untuk disposition queries
  - Attachment type indexing untuk gallery performance

- **Security Questions Workflow**
  - Modal-based editing instead of inline forms
  - Simplified verification process
  - Status-first approach dengan clear visual indicators
  - Birth date validation integration

- **Password Reset Design Language**
  - Government-standard formal styling
  - Professional typography dan spacing
  - Official branding integration
  - Consistent user guidance

### 📈 Performance
- **Query Optimization**
  - 50-90% performance improvement pada large datasets
  - Selective column loading untuk list views
  - Eager loading dengan proper relationships
  - Pagination safety limits (MAX_SHOW_ALL: 1000 records)

- **Memory Management**
  - Chunked operations untuk bulk inserts
  - Efficient notification batching
  - Optimized correspondence chain building
  - Safe pagination untuk large result sets

### 🔒 Security
- **Enhanced Authentication**
  - Birth date verification untuk security questions
  - Authenticated reset flow dengan proper session handling
  - Service failure isolation (errors don't expose system state)
  - Input validation improvements

### 📚 Documentation
- **Deployment Guide Enhancement**
  - Complete environment setup instructions
  - Multi-scenario deployment support
  - Signature key generation walkthrough
  - Security best practices checklist
  - Performance tuning guidelines

### ⚡ Migration & Upgrade
- **Database Updates**
  - Run: `php artisan migrate` untuk performance indexes
  - Automatic index creation untuk existing installations
  - Backward compatible changes
  - Zero-downtime deployment ready

---

## [2.0.0] "Shorekeeper" - 2025-11-25
**"I watch the moon"** 🌙

### ✨ Added
- **🔐 Digital Signature System**
  - QR Code verification untuk setiap dokumen
  - SHA-256 digital signatures dengan unique hash
  - Cross-platform IP detection (Windows, Linux, macOS)
  - Public document verification tanpa login
  - Signature metadata tracking (IP address, user agent, timestamp)

- **📱 QR Code Integration**
  - QRious.js library untuk generate QR codes
  - QR code mengarah ke public verification page
  - Auto-generate QR code di document preview
  - Backward compatibility alias `/verify/{hash}` untuk printed documents

- **🎯 SignatureService Architecture**
  - Centralized signature generation logic
  - Reduced code duplication across controllers
  - Signature reuse optimization untuk transcripts
  - `getOrCreateTranscriptSignature()` method untuk efficiency
  - Artisan command: `letters:sync-signatures` untuk existing data
  
- **🔧 Enhanced Environment Setup**
  - `SIGNATURE_KEY` requirement untuk digital signatures
  - Environment validation untuk security
  - Cross-platform compatibility checks

### 🛠️ Fixed
- **Exception Namespace Issues**
  - Fixed `catch (Exception $e)` → `catch (\Exception $e)` in DocumentSignature.php
  - Prevented runtime errors dari unresolved Exception class

- **Code Quality Improvements**
  - Eliminated duplicated signature generation methods
  - Consistent signature logic across all controllers
  - Improved error handling dan logging
 
### 🔄 Changed
- **Database Schema Updates**
  - Added `document_signatures` table dengan foreign keys
  - Updated `e_surat_perkim_full.sql` to version 2.4
  - Enhanced signature indexing untuk performance

- **UI/UX Enhancements**  
  - Updated README.md dengan comprehensive feature list
  - Enhanced About page dengan QR verification explanation
  - SweetAlert integration improvements
  - Copy Document ID dengan toast notifications

### 🔒 Security
- **Digital Document Integrity**
  - SHA-256 content hashing untuk tamper detection
  - IP-based verification tracking
  - Signature validation middleware
  - Public verification endpoints dengan rate limiting

### 📚 Documentation
- **Comprehensive Updates**
  - Updated tech stack documentation
  - Added QR code system explanation  
  - Enhanced setup instructions dengan SIGNATURE_KEY
  - Featured cross-platform support prominently

---

## [2.0.0] "Shorekeeper" - 2025-11-25 

### ✨ Added
- **Enhanced File Upload System**
  - Support upload files up to 15MB per file
  - Total upload limit 15MB for all files combined
  - Support for multiple file formats: pdf, doc, docx, ppt, pptx, txt, jpg, jpeg, png, gif
  - File metadata tracking (size, MIME type)
  - Proper validation with user-friendly error messages

- **Database Enhancements**
  - New `file_size` column in attachments table (tracks file size in bytes)
  - New `mime_type` column in attachments table (tracks file MIME type)
  - Upload configuration stored in database (configurable limits)
  - Migration system for incremental updates

- **Model Improvements**
  - Enhanced Attachment model with helper methods
  - `getFormattedSizeAttribute()` - displays human-readable file size
  - `isImage()` and `isPdf()` helper methods for file type detection
  - Support for file metadata in all CRUD operations

- **Configuration Management**
  - Configurable upload limits via database settings
  - Upload configuration seeder for easy deployment
  - Production-ready server configuration examples

### 🔧 Changed
- **PHP Configuration**
  - Updated php.ini requirements: `upload_max_filesize = 15M`, `post_max_size = 50M`
  - Improved server configuration documentation
  - Enhanced deployment guide with troubleshooting

- **Validation System**
  - Updated validation rules to support 15MB per file limit
  - Custom total file size validation (15MB combined)
  - Improved error messages in Indonesian

- **Database Schema**
  - Updated migraplus SQL files to v2.3
  - Added upload configuration data to full schema
  - Separate migration file for existing database updates

### 📚 Documentation
- **DEPLOYMENT.md** - Complete deployment guide with server configuration
- **README.md** - Updated with version 2.0.0 and enhanced features
- **Migraplus Updates** - All SQL files updated to latest schema
- **Configuration Examples** - PHP, Apache, and Nginx configuration samples

### 🐛 Fixed
- PostTooLargeException handling with proper error messages
- File upload limits now consistent across frontend and backend
- Migration sequence issues resolved
- Exception handling for large file uploads

### 🗄️ Database
- Migration: `2025_11_25_230700_add_file_size_to_attachments_table.php`
- Seeder: `UploadConfigSeeder.php`
- Updated: `e_surat_perkim_full.sql` to v2.3
- New: `e_surat_perkim_upload_config.sql` for incremental updates

### ⚡ Performance
- Optimized file upload handling
- Improved error handling for large files
- Better validation performance with early checks

### 🔒 Security
- Proper file type validation via MIME type detection
- File size limits enforced at multiple levels
- Sanitized file storage with timestamp prefixes

---

## [1.x.x] Previous Versions
- Initial release with basic document management
- User authentication and role management
- Letter tracking and disposition system
- Multi-theme support
- Security questions for password reset
