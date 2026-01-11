<?php
require_once 'config/config.php';

try {
    // Check if verification fields already exist
    $result = $pdo->query("DESCRIBE users");
    $columns = $result->fetchAll();
    $columnNames = array_column($columns, 'Field');
    
    $missingFields = [];
    
    if (!in_array('profile_photo', $columnNames)) {
        $missingFields[] = 'profile_photo';
    }
    if (!in_array('is_verified', $columnNames)) {
        $missingFields[] = 'is_verified';
    }
    if (!in_array('verification_status', $columnNames)) {
        $missingFields[] = 'verification_status';
    }
    if (!in_array('is_premium', $columnNames)) {
        $missingFields[] = 'is_premium';
    }
    
    if (!empty($missingFields)) {
        echo "Missing fields: " . implode(', ', $missingFields) . "\n";
        echo "Adding verification fields to users table...\n";
        
        // Add the missing fields
        $sql = "ALTER TABLE users 
                ADD COLUMN profile_photo VARCHAR(255) DEFAULT NULL,
                ADD COLUMN is_verified TINYINT(1) DEFAULT 0,
                ADD COLUMN verification_status ENUM('none','pending','approved','rejected') DEFAULT 'none',
                ADD COLUMN is_premium TINYINT(1) DEFAULT 0";
        
        $pdo->exec($sql);
        echo "Verification fields added successfully!\n";
    } else {
        echo "All verification fields already exist in the users table.\n";
    }
    
    // Show the updated structure
    $result = $pdo->query("DESCRIBE users");
    $columns = $result->fetchAll();
    echo "\nUpdated users table structure:\n";
    foreach($columns as $col) {
        echo $col['Field'] . ' | ' . $col['Type'] . ' | ' . $col['Null'] . ' | ' . $col['Default'] . ' | ' . $col['Extra'] . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>