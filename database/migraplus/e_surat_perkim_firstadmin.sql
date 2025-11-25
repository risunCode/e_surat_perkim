-- =====================================================
-- E-SURAT PERKIM - FIRST ADMIN
-- Tambah admin pertama
-- =====================================================
-- 
-- Email: admin@perkim.go.id
-- Password: perkim2025x
-- Birth Date: 2000-01-01
-- Security Question: favorite_food (Apa makanan favorit Anda?)
-- Security Answer: admin (hashed)
-- 
-- =====================================================

INSERT INTO `users` (`name`, `email`, `password`, `role`, `birth_date`, `security_question`, `security_answer`, `security_setup_completed`, `is_active`, `created_at`, `updated_at`) VALUES
('Administrator', 'admin@perkim.go.id', '$2y$12$CptFuqAF3uhB09P9DkOq6er2pUW4rt0o3WDRY6xdPK9ndWsVEPhiy', 'admin', '2000-01-01', 'favorite_food', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1, NOW(), NOW());
