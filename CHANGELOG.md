# Changelog
All notable changes to E-Surat Perkim will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [2.0.0] "Shorekeeper" - 2025-11-25
**"Resonance of Enhanced File Management"**

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
