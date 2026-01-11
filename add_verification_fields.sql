-- Add verification and premium fields to job_seekers table
ALTER TABLE job_seekers 
ADD COLUMN is_verified TINYINT(1) DEFAULT 0,
ADD COLUMN is_premium TINYINT(1) DEFAULT 0,
ADD COLUMN verification_status ENUM('none','pending','approved','rejected') DEFAULT 'none',
ADD COLUMN premium_expiry DATE NULL;