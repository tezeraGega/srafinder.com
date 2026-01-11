<?php
// test_admin_connection.php
require_once 'config/config.php';

echo "Testing database connection...\n";

// Test admin user query
try {
    $stmt = $pdo->prepare("SELECT u.user_id, u.email, u.role 
                          FROM users u 
                          JOIN admins a ON u.user_id = a.user_id 
                          WHERE u.role = 'admin'");
    $stmt->execute();
    $adminUsers = $stmt->fetchAll();
    
    echo "Found " . count($adminUsers) . " admin user(s)\n";
    
    foreach ($adminUsers as $admin) {
        echo "- User ID: " . $admin['user_id'] . ", Email: " . $admin['email'] . "\n";
    }
    
    // Test getting dashboard stats
    $stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users WHERE role != 'admin'");
    $total_users = $stmt->fetch()['total_users'];
    echo "Total non-admin users: " . $total_users . "\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total_jobs FROM jobs");
    $total_jobs = $stmt->fetch()['total_jobs'];
    echo "Total jobs: " . $total_jobs . "\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total_applications FROM job_applications");
    $total_applications = $stmt->fetch()['total_applications'];
    echo "Total applications: " . $total_applications . "\n";
    
    echo "\nAll queries executed successfully! Admin panel should work correctly.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>