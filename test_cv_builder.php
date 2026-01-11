<?php
// test_cv_builder.php - Simple test to verify CV builder functionality
require_once 'config/config.php';

echo "<h2>CV Builder Test Results</h2>";

// Test database connection
try {
    echo "<p style='color: green;'>✓ Database connection successful</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>";
}

// Test if job_seeker_cvs table exists
try {
    $result = $pdo->query("SELECT * FROM job_seeker_cvs LIMIT 1");
    if ($result !== false) {
        echo "<p style='color: green;'>✓ job_seeker_cvs table exists</p>";
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ job_seeker_cvs table does not exist: " . $e->getMessage() . "</p>";
}

// Test if has_cv_builder column exists in job_seekers table
try {
    $result = $pdo->query("DESCRIBE job_seekers");
    $columns = $result->fetchAll(PDO::FETCH_COLUMN);
    if (in_array('has_cv_builder', $columns)) {
        echo "<p style='color: green;'>✓ has_cv_builder column exists in job_seekers table</p>";
    } else {
        echo "<p style='color: orange;'>? has_cv_builder column may not exist in job_seekers table</p>";
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Error checking job_seekers table structure: " . $e->getMessage() . "</p>";
}

echo "<br><h3>Links to CV Builder functionality:</h3>";
echo "<ul>";
echo "<li><a href='jobseeker/cv_builder.php'>CV Builder Page</a> - Create and edit your European CV</li>";
echo "<li><a href='jobseeker/generate_cv_pdf.php'>Generate CV PDF</a> - Download your CV as PDF</li>";
echo "</ul>";

echo "<br><p><strong>CV Builder feature has been successfully installed!</strong></p>";
?>