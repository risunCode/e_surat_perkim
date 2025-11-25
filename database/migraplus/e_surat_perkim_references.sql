-- =====================================================
-- E-SURAT PERKIM - KODE REFERRAL
-- Kode untuk registrasi user baru
-- =====================================================
-- 
-- Kode referral default:
-- - STAFF2025 = untuk staff, unlimited usage
-- - ADMIN2025 = untuk admin, max 5 usage
-- 
-- =====================================================

-- Kode Referral untuk Admin (limited 5x)
INSERT INTO `reference_codes` (`code`, `name`, `max_usage`, `used_count`, `is_active`, `role`, `expired_at`, `created_by`, `created_at`, `updated_at`) VALUES
('ADMIN2025', 'Kode Registrasi Admin', 5, 0, 1, 'admin', DATE_ADD(NOW(), INTERVAL 1 YEAR), 1, NOW(), NOW());

-- Kode Referral untuk Staff (unlimited)
INSERT INTO `reference_codes` (`code`, `name`, `max_usage`, `used_count`, `is_active`, `role`, `expired_at`, `created_by`, `created_at`, `updated_at`) VALUES
('STAFF2025', 'Kode Registrasi Staff', 0, 0, 1, 'staff', DATE_ADD(NOW(), INTERVAL 1 YEAR), 1, NOW(), NOW());
