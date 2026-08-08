-- Run this SQL in phpMyAdmin or your MySQL client to create the verified_phone_leads table.
-- Database: nooryak_launchshopp

CREATE TABLE IF NOT EXISTS `verified_phone_leads` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `country_code` varchar(255) DEFAULT NULL,
  `purchased` tinyint(1) NOT NULL DEFAULT 0,
  `otp_sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `verified_phone_leads_phone_unique` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Also insert a row into the migrations table so Laravel knows this migration has run:
INSERT IGNORE INTO `migrations` (`migration`, `batch`)
VALUES ('2026_07_11_000001_create_verified_phone_leads_table', (SELECT COALESCE(MAX(batch),0)+1 FROM migrations m2));
