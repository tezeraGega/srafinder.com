<?php
// setup_settings_table.php
require_once 'config/config.php';

try {
    // Create settings table
    $sql = "CREATE TABLE IF NOT EXISTS system_settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(255) UNIQUE NOT NULL,
        setting_value TEXT,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    echo "SUCCESS: Created system_settings table!\n";
    
    // Insert default system settings
    $settings = [
        ['site_name', 'SiraFinder Job Portal', 'Name of the website'],
        ['site_description', 'Find your dream job with SiraFinder - Ethiopia\'s leading job portal platform.', 'Description of the website'],
        ['contact_email', 'admin@sirafinder.et', 'Main contact email address'],
        ['contact_phone', '+251-11-123-4567', 'Main contact phone number'],
        ['timezone', 'Africa/Addis_Ababa', 'Default timezone for the system'],
        ['currency', 'ETB - Ethiopian Birr', 'Default currency for the system'],
        ['maintenance_mode', '0', 'Whether the system is in maintenance mode (1 for on, 0 for off)'],
        ['require_email_verification', '1', 'Whether to require email verification (1 for on, 0 for off)'],
        ['allow_user_registration', '1', 'Whether to allow new user registration (1 for on, 0 for off)'],
        ['max_file_upload_size', '5242880', 'Maximum file upload size in bytes (5MB)'],
        ['allowed_file_types', 'jpg,jpeg,png,pdf,doc,docx', 'Comma-separated list of allowed file types'],
        ['email_from_name', 'SiraFinder Admin', 'Name to use for system emails'],
        ['email_from_address', 'admin@sirafinder.et', 'Email address to use for system emails'],
        ['default_user_role', 'jobseeker', 'Default role for new user registrations'],
        ['max_job_applications_per_day', '10', 'Maximum job applications a user can submit per day']
    ];
    
    $stmt = $pdo->prepare("INSERT IGNORE INTO system_settings (setting_key, setting_value, description) VALUES (?, ?, ?)");
    
    foreach ($settings as $setting) {
        $stmt->execute($setting);
    }
    
    echo "SUCCESS: Inserted default settings!\n";
    
    // Verify the table was created and settings were inserted
    $result = $pdo->query("SELECT COUNT(*) as count FROM system_settings");
    $count = $result->fetch()['count'];
    
    echo "SUCCESS: Total settings in database: $count\n";
    echo "System settings table has been set up successfully!\n";
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>