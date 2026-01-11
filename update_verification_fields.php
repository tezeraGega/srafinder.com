<?php
// update_verification_fields.php
require_once 'config/config.php';

try {
    // Add verification fields to users table if they don't exist
    $sql = "ALTER TABLE users 
            ADD COLUMN IF NOT EXISTS profile_photo VARCHAR(255) DEFAULT NULL,
            ADD COLUMN IF NOT EXISTS is_verified TINYINT(1) DEFAULT 0,
            ADD COLUMN IF NOT EXISTS verification_status ENUM('none','pending','approved','rejected') DEFAULT 'none',
            ADD COLUMN IF NOT EXISTS is_premium TINYINT(1) DEFAULT 0";
    
    $pdo->exec($sql);
    echo "SUCCESS: Verification fields added to users table successfully!\n";
    
    // Verify the fields were added
    $result = $pdo->query("DESCRIBE users");
    $columns = $result->fetchAll();
    
    echo "\nUpdated users table structure:\n";
    foreach($columns as $col) {
        if (in_array($col['Field'], ['profile_photo', 'is_verified', 'verification_status', 'is_premium'])) {
            echo "- {$col['Field']} | {$col['Type']} | {$col['Null']} | {$col['Default']}\n";
        }
    }
    
    echo "\nVerification system is now properly set up in the database!\n";
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>