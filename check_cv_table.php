<?php
// check_cv_table.php
require_once 'config/config.php';

try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'job_seeker_cvs'");
    $result = $stmt->fetchAll();
    
    echo 'job_seeker_cvs table exists: ' . (count($result) > 0 ? 'Yes' : 'No') . "\n";
    
    // Check if has_cv_builder column exists in job_seekers table
    $stmt2 = $pdo->query("SHOW COLUMNS FROM job_seekers LIKE 'has_cv_builder'");
    $result2 = $stmt2->fetchAll();
    
    echo 'has_cv_builder column exists: ' . (count($result2) > 0 ? 'Yes' : 'No') . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>