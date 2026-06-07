-- Add verification columns to student_profiles table
ALTER TABLE `student_profiles` 
ADD COLUMN `college_email` VARCHAR(255) NULL AFTER `bio`,
ADD COLUMN `college_name` VARCHAR(255) NULL AFTER `college_email`,
ADD COLUMN `verification_token` VARCHAR(255) NULL AFTER `college_name`,
ADD COLUMN `is_verified` TINYINT(1) NOT NULL DEFAULT 0 AFTER `verification_token`,
ADD COLUMN `email_verified_at` TIMESTAMP NULL AFTER `is_verified`;
