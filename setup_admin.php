<?php
// setup_admin.php
// Script to create an initial admin user if one doesn't exist

require_once 'config/config.php';

try {
    // Check if admin user already exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'");
    $adminCount = $stmt->fetchColumn();
    
    if ($adminCount == 0) {
        // Create default admin user
        $email = 'admin@sirafinder.et';
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $firstName = 'System';
        $lastName = 'Admin';
        
        // Insert user record
        $stmt = $pdo->prepare("INSERT INTO users (email, password, role, first_name, last_name) VALUES (?, ?, 'admin', ?, ?)");
        $stmt->execute([$email, $password, $firstName, $lastName]);
        $userId = $pdo->lastInsertId();
        
        // Insert admin record
        $stmt = $pdo->prepare("INSERT INTO admins (user_id, full_name, position) VALUES (?, 'System Admin', 'Administrator')");
        $stmt->execute([$userId]);
        
        echo "Admin user created successfully!\n";
        echo "Email: admin@sirafinder.et\n";
        echo "Password: admin123\n";
    } else {
        echo "Admin user already exists. Skipping creation.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>