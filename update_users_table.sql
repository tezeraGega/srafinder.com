-- Update users table to add verification and premium fields
ALTER TABLE users 
ADD COLUMN profile_photo VARCHAR(255) DEFAULT NULL,
ADD COLUMN is_verified TINYINT(1) DEFAULT 0,
ADD COLUMN verification_status ENUM('none','pending','approved','rejected') DEFAULT 'none',
ADD COLUMN is_premium TINYINT(1) DEFAULT 0;