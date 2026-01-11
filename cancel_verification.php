<?php
// cancel_verification.php
require_once 'config/config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    setFlashMessage('error', 'You must be logged in to cancel a verification request.');
    redirect_to('login.php');
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    setFlashMessage('error', 'Invalid request method.');
    redirect_to('index.php');
}

// Verify the user wants to cancel verification
if (!isset($_POST['cancel_verification']) || $_POST['cancel_verification'] !== 'true') {
    setFlashMessage('error', 'Invalid request to cancel verification.');
    redirect_to('index.php');
}

try {
    $user_id = getUserId();
    $role = getUserRole();
    
    // Get current verification status
    $stmt = $pdo->prepare("SELECT verification_status FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if (!$user) {
        setFlashMessage('error', 'User not found.');
        redirect_to('index.php');
    }
    
    // Only allow cancellation if verification status is 'pending'
    if ($user['verification_status'] !== 'pending') {
        setFlashMessage('error', 'Cannot cancel verification request. Status is not pending.');
        if ($role === 'jobseeker') {
            redirect_to('jobseeker/verify_profile.php');
        } elseif ($role === 'employer') {
            redirect_to('employer/verify_profile.php');
        } else {
            redirect_to('index.php');
        }
    }
    
    // Update verification status back to 'none' (cancelled)
    $stmt = $pdo->prepare("UPDATE users SET verification_status = 'none', is_verified = 0, updated_at = NOW() WHERE user_id = ?");
    $result = $stmt->execute([$user_id]);
    
    if ($result) {
        setFlashMessage('success', 'Verification request cancelled successfully.');
    } else {
        setFlashMessage('error', 'Error cancelling verification request.');
    }
    
} catch (Exception $e) {
    setFlashMessage('error', 'Error cancelling verification: ' . $e->getMessage());
}

// Redirect back to appropriate page based on user role
$role = getUserRole();
if ($role === 'jobseeker') {
    redirect_to('jobseeker/verify_profile.php');
} elseif ($role === 'employer') {
    redirect_to('employer/verify_profile.php');
} else {
    redirect_to('index.php');
}
?>