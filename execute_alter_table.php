<?php
require_once 'config/config.php';

try {
    // Execute the ALTER TABLE command to add verification fields
    $sql = "ALTER TABLE users 
            ADD COLUMN is_verified TINYINT(1) DEFAULT 0,
            ADD COLUMN verification_status ENUM('none','pending','approved','rejected') DEFAULT 'none'";
    
    $pdo->exec($sql);
    echo "SUCCESS: Verification fields added to users table!\n";
    echo "Added fields:\n";
    echo "- is_verified (TINYINT(1), DEFAULT 0)\n";
    echo "- verification_status (ENUM('none','pending','approved','rejected'), DEFAULT 'none')\n\n";
    
    // Verify the fields were added
    $result = $pdo->query("DESCRIBE users");
    $columns = $result->fetchAll();
    
    echo "Current users table structure:\n";
    foreach($columns as $col) {
        if ($col['Field'] == 'is_verified' || $col['Field'] == 'verification_status') {
            echo "✓ " . $col['Field'] . ' | ' . $col['Type'] . ' | ' . $col['Null'] . ' | ' . $col['Default'] . ' | ' . $col['Extra'] . "\n";
        }
    }
    
} catch (PDOException $e) {
    // Check if the fields already exist (which would cause a duplicate column error)
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "INFO: Verification fields already exist in the users table.\n";
        
        // Show the existing verification fields
        $result = $pdo->query("DESCRIBE users");
        $columns = $result->fetchAll();
        
        echo "Verification fields currently in users table:\n";
        foreach($columns as $col) {
            if ($col['Field'] == 'is_verified' || $col['Field'] == 'verification_status') {
                echo "✓ " . $col['Field'] . ' | ' . $col['Type'] . ' | ' . $col['Null'] . ' | ' . $col['Default'] . ' | ' . $col['Extra'] . "\n";
            }
        }
    } else {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>