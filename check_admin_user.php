<?php
// check_admin_user.php
require_once 'config/config.php';

try {
    $stmt = $pdo->query('SELECT * FROM users WHERE role = "admin"');
    $result = $stmt->fetchAll();
    
    echo 'Admin users found: ' . count($result) . "\n";
    foreach($result as $user) {
        echo 'Email: ' . $user['email'] . ', Name: ' . $user['first_name'] . ' ' . $user['last_name'] . "\n";
    }
    
    // Also check if the admins table exists and has data
    $stmt2 = $pdo->query('SELECT * FROM admins');
    $result2 = $stmt2->fetchAll();
    
    echo "\nAdmin profiles found: " . count($result2) . "\n";
    foreach($result2 as $admin) {
        echo 'Admin ID: ' . $admin['admin_id'] . ', Full Name: ' . $admin['full_name'] . ', Position: ' . $admin['position'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>