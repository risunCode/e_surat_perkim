-- =====================================================
-- E-SURAT PERKIM - DATABASE SCHEMA
-- Generated: 25 November 2025
-- Version: 2.3 (File Upload Enhancement + Metadata)
-- =====================================================
-- 
-- Cara Penggunaan:
-- 1. Buat database baru: CREATE DATABASE e_surat_perkim;
-- 2. Jalankan file ini: mysql -u root -p e_surat_perkim < e_surat_perkim_full.sql
-- 
-- =====================================================

SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================
-- TABLE: users
-- =====================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `password` VARCHAR(255) NOT NULL,
    `two_factor_secret` TEXT NULL,
    `two_factor_recovery_codes` TEXT NULL,
    `two_factor_confirmed_at` TIMESTAMP NULL DEFAULT NULL,
    `phone` VARCHAR(255) NULL DEFAULT NULL,
    `role` VARCHAR(255) NOT NULL DEFAULT 'staff' COMMENT 'admin, staff',
    `birth_date` DATE NULL DEFAULT NULL,
    `security_question` VARCHAR(255) NULL DEFAULT NULL,
    `security_answer` VARCHAR(255) NULL DEFAULT NULL,
    `security_setup_completed` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `profile_picture` VARCHAR(255) NULL DEFAULT NULL,
    `remember_token` VARCHAR(100) NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: password_reset_tokens
-- =====================================================
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: sessions
-- =====================================================
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
    `id` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `ip_address` VARCHAR(45) NULL DEFAULT NULL,
    `user_agent` TEXT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: cache
-- =====================================================
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
    `key` VARCHAR(255) NOT NULL,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: cache_locks
-- =====================================================
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) NOT NULL,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: jobs
-- =====================================================
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED NULL DEFAULT NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: job_batches
-- =====================================================
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
    `id` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `total_jobs` INT NOT NULL,
    `pending_jobs` INT NOT NULL,
    `failed_jobs` INT NOT NULL,
    `failed_job_ids` LONGTEXT NOT NULL,
    `options` MEDIUMTEXT NULL,
    `cancelled_at` INT NULL DEFAULT NULL,
    `created_at` INT NOT NULL,
    `finished_at` INT NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: failed_jobs
-- =====================================================
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(255) NOT NULL,
    `connection` TEXT NOT NULL,
    `queue` TEXT NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `exception` LONGTEXT NOT NULL,
    `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: classifications
-- =====================================================
DROP TABLE IF EXISTS `classifications`;
CREATE TABLE `classifications` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(255) NOT NULL,
    `type` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `classifications_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: letter_statuses
-- =====================================================
DROP TABLE IF EXISTS `letter_statuses`;
CREATE TABLE `letter_statuses` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `status` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: letters
-- =====================================================
DROP TABLE IF EXISTS `letters`;
CREATE TABLE `letters` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `reference_number` VARCHAR(255) NOT NULL,
    `agenda_number` VARCHAR(255) NULL DEFAULT NULL,
    `from` VARCHAR(255) NULL DEFAULT NULL,
    `to` VARCHAR(255) NULL DEFAULT NULL,
    `letter_date` DATE NULL DEFAULT NULL,
    `received_date` DATE NULL DEFAULT NULL,
    `description` TEXT NULL,
    `note` TEXT NULL,
    `type` VARCHAR(255) NOT NULL DEFAULT 'incoming' COMMENT 'incoming, outgoing',
    `status` ENUM('draft', 'sent', 'completed', 'cancelled') DEFAULT 'draft' COMMENT 'Status surat',
    `is_completed` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Simple completion flag',
    `sent_at` TIMESTAMP NULL DEFAULT NULL,
    `completed_at` TIMESTAMP NULL DEFAULT NULL,
    `status_note` TEXT NULL,
    `reference_to` BIGINT UNSIGNED NULL DEFAULT NULL COMMENT 'FK to letters.id for reply chain',
    `classification_code` VARCHAR(255) NULL DEFAULT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `letters_reference_number_unique` (`reference_number`),
    KEY `idx_letters_type` (`type`),
    KEY `idx_letters_letter_date` (`letter_date`),
    KEY `idx_letters_user_id` (`user_id`),
    KEY `idx_letters_classification_code` (`classification_code`),
    KEY `idx_letters_is_completed` (`is_completed`),
    KEY `letters_reference_to_foreign` (`reference_to`),
    CONSTRAINT `letters_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
    CONSTRAINT `letters_classification_code_foreign` FOREIGN KEY (`classification_code`) REFERENCES `classifications` (`code`) ON DELETE SET NULL,
    CONSTRAINT `letters_reference_to_foreign` FOREIGN KEY (`reference_to`) REFERENCES `letters` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: dispositions
-- =====================================================
DROP TABLE IF EXISTS `dispositions`;
CREATE TABLE `dispositions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `to` VARCHAR(255) NOT NULL COMMENT 'Tujuan disposisi',
    `due_date` DATE NOT NULL,
    `content` TEXT NOT NULL,
    `note` TEXT NULL,
    `letter_status` BIGINT UNSIGNED NOT NULL,
    `letter_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `dispositions_letter_status_foreign` (`letter_status`),
    KEY `dispositions_letter_id_foreign` (`letter_id`),
    KEY `dispositions_user_id_foreign` (`user_id`),
    CONSTRAINT `dispositions_letter_status_foreign` FOREIGN KEY (`letter_status`) REFERENCES `letter_statuses` (`id`) ON DELETE CASCADE,
    CONSTRAINT `dispositions_letter_id_foreign` FOREIGN KEY (`letter_id`) REFERENCES `letters` (`id`) ON DELETE CASCADE,
    CONSTRAINT `dispositions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: attachments
-- =====================================================
DROP TABLE IF EXISTS `attachments`;
CREATE TABLE `attachments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `path` VARCHAR(255) NULL DEFAULT NULL,
    `filename` VARCHAR(255) NOT NULL,
    `extension` VARCHAR(255) NOT NULL DEFAULT 'pdf',
    `file_size` BIGINT NULL DEFAULT NULL COMMENT 'File size in bytes',
    `mime_type` VARCHAR(255) NULL DEFAULT NULL COMMENT 'File MIME type',
    `letter_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `attachments_letter_id_foreign` (`letter_id`),
    KEY `attachments_user_id_foreign` (`user_id`),
    CONSTRAINT `attachments_letter_id_foreign` FOREIGN KEY (`letter_id`) REFERENCES `letters` (`id`) ON DELETE CASCADE,
    CONSTRAINT `attachments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: notifications
-- =====================================================
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `type` VARCHAR(255) NOT NULL COMMENT 'incoming, outgoing, disposition, tracking',
    `title` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `link` VARCHAR(255) NULL DEFAULT NULL,
    `icon` VARCHAR(255) NOT NULL DEFAULT 'bx-envelope',
    `data` JSON NULL DEFAULT NULL COMMENT 'Additional notification data',
    `is_read` TINYINT(1) NOT NULL DEFAULT 0,
    `read_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_notifications_user_read` (`user_id`, `is_read`),
    KEY `idx_notifications_created_at` (`created_at`),
    CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: reference_codes
-- =====================================================
DROP TABLE IF EXISTS `reference_codes`;
CREATE TABLE `reference_codes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `max_usage` INT NOT NULL DEFAULT 1 COMMENT '0 = unlimited',
    `used_count` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `expired_at` TIMESTAMP NULL DEFAULT NULL,
    `role` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Role yang akan diberikan',
    `created_by` BIGINT UNSIGNED NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `reference_codes_code_unique` (`code`),
    KEY `idx_reference_codes_code` (`code`),
    KEY `idx_reference_codes_is_active` (`is_active`),
    KEY `reference_codes_created_by_foreign` (`created_by`),
    CONSTRAINT `reference_codes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: reference_code_usage
-- =====================================================
DROP TABLE IF EXISTS `reference_code_usage`;
CREATE TABLE `reference_code_usage` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `reference_code_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `used_at` TIMESTAMP NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_ref_code_usage` (`reference_code_id`, `user_id`),
    CONSTRAINT `reference_code_usage_reference_code_id_foreign` FOREIGN KEY (`reference_code_id`) REFERENCES `reference_codes` (`id`) ON DELETE CASCADE,
    CONSTRAINT `reference_code_usage_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: configs
-- =====================================================
DROP TABLE IF EXISTS `configs`;
CREATE TABLE `configs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(255) NOT NULL,
    `value` TEXT NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `configs_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: migrations (Laravel)
-- =====================================================
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `migration` VARCHAR(255) NOT NULL,
    `batch` INT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERT DATA: Upload Configuration
-- =====================================================
INSERT INTO `configs` (`code`, `value`, `created_at`, `updated_at`) VALUES
('upload_max_file_size', '15', NOW(), NOW()),
('upload_max_total_size', '15', NOW(), NOW()),
('upload_allowed_types', 'pdf,doc,docx,ppt,pptx,txt,jpg,jpeg,png,gif', NOW(), NOW()),
('upload_max_files', '20', NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- END OF SCHEMA
-- =====================================================
-- 
-- File ini berisi struktur tabel lengkap v2.3
-- Fitur baru:
-- - Status tracking untuk surat (status, is_completed, sent_at, completed_at)
-- - Data JSON untuk notifications
-- - File metadata tracking (file_size, mime_type)
-- - Upload configuration settings (15MB per file, various file types)
-- - Index untuk is_completed
-- 
-- Untuk data default, gunakan file terpisah:
-- - e_surat_perkim_references.sql (kode referral)
-- - e_surat_perkim_firstadmin.sql (admin pertama)
-- =====================================================
