-- Check if verification fields exist, and add them if they don't
-- First, check current structure
DESCRIBE users;

-- Add verification fields if they don't exist
-- Note: MySQL doesn't have direct "IF NOT EXISTS" for columns, so we'll add them manually
-- You can run this in phpMyAdmin or MySQL command line

-- This assumes your database is named sirafinder_db
USE sirafinder_db;

-- Add the verification fields (run this if fields are missing)
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS profile_photo VARCHAR(255) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS is_verified TINYINT(1) DEFAULT 0,
ADD COLUMN IF NOT EXISTS verification_status ENUM('none','pending','approved','rejected') DEFAULT 'none',
ADD COLUMN IF NOT EXISTS is_premium TINYINT(1) DEFAULT 0;