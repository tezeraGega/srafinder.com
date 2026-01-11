<?php
// Update verification status to pending
require_once '../config/config.php';

// Ensure user is logged in
if (!isLoggedIn()) {
    redirect_to('login.php');
    exit;
}

$user_id = getUserId();

try {
    // Update verification status to pending
    $stmt = $pdo->prepare("UPDATE users SET verification_status = 'pending', is_verified = 0, updated_at = NOW() WHERE user_id = ?");
    $result = $stmt->execute([$user_id]);
    
    if ($result) {
        // Set success message
        setFlashMessage('success', 'Verification request sent successfully.');
    } else {
        setFlashMessage('error', 'Error sending verification request.');
    }
} catch (Exception $e) {
    setFlashMessage('error', 'Database error: ' . $e->getMessage());
}

// Redirect back to profile
redirect_to('../jobseeker/profile.php');
exit;
?>