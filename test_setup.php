<?php
// test_setup.php
// This file helps verify that the setup is working correctly

require_once 'config/config.php';

echo "<h1>SiraFinder Setup Verification</h1>";

// Test database connection
try {
    $test = $pdo->query("SELECT 1");
    echo "<p style='color: green;'>✓ Database connection successful</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>";
}

// Test session
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "<p style='color: green;'>✓ Session is active</p>";
} else {
    echo "<p style='color: red;'>✗ Session is not active</p>";
}

// Test BASE_URL
if (defined('BASE_URL')) {
    echo "<p style='color: green;'>✓ BASE_URL is defined: " . BASE_URL . "</p>";
} else {
    echo "<p style='color: red;'>✗ BASE_URL is not defined</p>";
}

// Check if default admin exists
try {
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = 'admin@sirafinder.et'");
    $stmt->execute();
    $admin = $stmt->fetch();
    
    if ($admin) {
        echo "<p style='color: green;'>✓ Default admin user exists</p>";
    } else {
        echo "<p style='color: orange;'>⚠ Default admin user does not exist. You may need to run the database.sql file.</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error checking admin user: " . $e->getMessage() . "</p>";
}

echo "<h2>Important Links:</h2>";
echo "<ul>";
echo "<li><a href='index.php'>Home Page</a></li>";
echo "<li><a href='jobs.php'>Jobs Page</a></li>";
echo "<li><a href='login.php'>Login Page</a></li>";
echo "<li><a href='register.php'>Register Page</a></li>";
echo "<li><a href='admin/login.php'>Admin Login</a></li>";
echo "</ul>";

echo "<h2>Setup Instructions:</h2>";
echo "<ol>";
echo "<li>Make sure XAMPP Apache and MySQL services are running</li>";
echo "<li>Import the database.sql file into phpMyAdmin</li>";
echo "<li>Access the site at http://localhost/SiraFinder1</li>";
echo "<li>Default admin login: admin@sirafinder.et / admin123</li>";
echo "</ol>";
?>