# Changelog
All notable changes to E-Surat Perkim will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [2.4.0] "Iuno" - 2025-11-26
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
