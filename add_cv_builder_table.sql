-- Add CV Builder table to store structured CV data
CREATE TABLE IF NOT EXISTS job_seeker_cvs (
    cv_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    cv_data JSON NOT NULL,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Add a field to indicate if the user has a CV builder profile
ALTER TABLE job_seekers 
ADD COLUMN IF NOT EXISTS has_cv_builder BOOLEAN DEFAULT FALSE;