<?php
require_once 'config/config.php';

try {
    $stmt = $pdo->query("SELECT setting_value FROM system_settings WHERE setting_key = 'allowed_file_types'");
    $result = $stmt->fetch();
    
    if ($result) {
        echo 'Current allowed file types: ' . $result['setting_value'] . "\n";
    } else {
        echo "Setting not found in database\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Clean up this temporary file after execution
unlink(__FILE__);
?>