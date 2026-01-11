<?php
// install_cv_builder.php
// Set a default HTTP_HOST to avoid warnings when running from command line
if (!isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = 'localhost';
}
require_once 'config/config.php';

try {
    // Create the job_seeker_cvs table
    $sql = "CREATE TABLE IF NOT EXISTS job_seeker_cvs (
        cv_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        cv_data JSON NOT NULL,
        is_default BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );";
    
    $pdo->exec($sql);
    echo "SUCCESS: job_seeker_cvs table created successfully!\n";
    
    // Add has_cv_builder column to job_seekers table
    $sql = "ALTER TABLE job_seekers 
            ADD COLUMN IF NOT EXISTS has_cv_builder BOOLEAN DEFAULT FALSE";
    
    $pdo->exec($sql);
    echo "SUCCESS: has_cv_builder column added to job_seekers table!\n";
    
    echo "\nCV Builder database tables have been installed successfully!\n";
    echo "You can now use the CV Builder feature in the job seeker dashboard.\n";
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>