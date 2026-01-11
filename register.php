<?php
// register.php
require_once 'config/config.php';

// Function to get a setting value from database
function getSetting($pdo, $key, $default = '') {
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM system_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : $default;
    } catch (PDOException $e) {
        return $default;
    }
}

// Check if registration is allowed via system settings
$allow_registration = getSetting($pdo, 'allow_user_registration', '1') === '1';

// If registration is disabled, redirect to home page
if (!$allow_registration) {
    redirect_to('index.php');
}

// If already logged in, redirect to appropriate dashboard
if (isLoggedIn()) {
    $role = getUserRole();
    if ($role === 'employer') {
        redirect_to('employer/dashboard.php');
    } elseif ($role === 'jobseeker') {
        redirect_to('jobseeker/profile.php');
    } else {
        // For any other role (including admin), redirect to home
        redirect_to('index.php');
    }
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = sanitize_input($_POST['role']);
    $first_name = sanitize_input($_POST['first_name']);
    $last_name = sanitize_input($_POST['last_name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    $errors = [];
    
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $errors[] = 'Please fill in all required fields.';
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long.';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }
    
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors[] = 'Email address is already registered.';
    }
    
    if (empty($errors)) {
        try {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Start transaction
            $pdo->beginTransaction();
            
            // Insert user
            $stmt = $pdo->prepare("INSERT INTO users (email, password, role, first_name, last_name, phone) 
                                  VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$email, $hashed_password, $role, $first_name, $last_name, $phone]);
            $user_id = $pdo->lastInsertId();
            
            // Insert into role-specific table
            if ($role === 'jobseeker') {
                $stmt = $pdo->prepare("INSERT INTO job_seekers (user_id) 
                                      VALUES (?)");
                $stmt->execute([$user_id]);
            } elseif ($role === 'employer') {
                $stmt = $pdo->prepare("INSERT INTO employers (user_id, company_name, contact_person, contact_email) 
                                      VALUES (?, ?, ?, ?)");
                $stmt->execute([$user_id, $first_name . ' ' . $last_name, $first_name . ' ' . $last_name, $email]);
            }
            
            // Commit transaction
            $pdo->commit();
            
            // Automatically log in the user after successful registration
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role;
            
            // Redirect to appropriate dashboard based on role
            if ($role === 'employer') {
                redirect_to('employer/dashboard.php');
            } elseif ($role === 'jobseeker') {
                redirect_to('jobseeker/profile.php');
            } else {
                // For any other role (including admin), redirect to home
                redirect_to('index.php');
            }
        } catch (Exception $e) {
            // Rollback transaction
            $pdo->rollback();
            $error_message = 'Registration failed. Please try again.';
        }
    } else {
        $error_message = implode('<br>', $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SiraFinder</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                            <img src="assets/images/logo/eeee.png" alt="SiraFinder Logo" style="height: 40px;">
                        </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="jobs.php">Find Jobs</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="register.php">Register</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <!-- Registration Section -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary-custom text-white text-center py-4">
                        <h3 class="mb-0 fw-bold">Create Account</h3>
                        <p class="mb-0 text-light opacity-75">Join SiraFinder today</p>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        
                        <?php if (!empty($success_message)): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label fw-bold">First Name</label>
                                    <input type="text" class="form-control rounded-pill" id="first_name" name="first_name" value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label fw-bold">Last Name</label>
                                    <input type="text" class="form-control rounded-pill" id="last_name" name="last_name" value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email Address</label>
                                <input type="email" class="form-control rounded-pill" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label fw-bold">Phone Number</label>
                                <input type="tel" class="form-control rounded-pill" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="role" class="form-label fw-bold">Account Type</label>
                                <select class="form-select rounded-pill" id="role" name="role" required>
                                    <option value="">Select Account Type</option>
                                    <option value="jobseeker" <?php echo (isset($_POST['role']) && $_POST['role'] === 'jobseeker') ? 'selected' : ''; ?>>Job Seeker</option>
                                    <option value="employer" <?php echo (isset($_POST['role']) && $_POST['role'] === 'employer') ? 'selected' : ''; ?>>Employer</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">Password</label>
                                <input type="password" class="form-control rounded-pill" id="password" name="password" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label fw-bold">Confirm Password</label>
                                <input type="password" class="form-control rounded-pill" id="confirm_password" name="confirm_password" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary-custom rounded-pill py-2 fw-bold">Register</button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-3">
                            <p>Already have an account? <a href="login.php" class="text-primary-custom fw-bold text-decoration-none">Login here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold text-white mb-3">SiraFinder</h5>
                    <p class="text-white">Ethiopia's premier job portal connecting talented professionals with leading employers.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold text-white mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php" class="text-white text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="jobs.php" class="text-white text-decoration-none">Find Jobs</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">For Employers</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">About</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold text-white mb-3">Contact Us</h5>
                    <p class="text-white">
                        <i class="bi bi-envelope"></i> info@sirafinder.et<br>
                        <i class="bi bi-telephone"></i> +251-11-123-4567<br>
                        <i class="bi bi-geo-alt"></i> Addis Ababa, Ethiopia
                    </p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="text-white mb-0">&copy; 2025 SiraFinder. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>