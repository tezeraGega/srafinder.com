<?php
// login.php
require_once 'config/config.php';

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
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error_message = 'Please fill in all fields.';
    } else {
        // Check user in database
        $stmt = $pdo->prepare("SELECT user_id, email, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect based on role
            if ($user['role'] === 'employer') {
                redirect_to('employer/dashboard.php');
            } elseif ($user['role'] === 'jobseeker') {
                redirect_to('jobseeker/profile.php');
            } else {
                // For any other role (including admin), redirect to home
                redirect_to('index.php');
            }
        } else {
            $error_message = 'Invalid email or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiraFinder</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
                        <a class="nav-link active" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary-custom text-white text-center py-4">
                        <h3 class="mb-0 fw-bold">Login to Your Account</h3>
                        <p class="mb-0 text-light opacity-75">Access your SiraFinder account</p>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger rounded-pill text-center"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        
                        <?php if (!empty($success_message)): ?>
                            <div class="alert alert-success rounded-pill text-center"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-4">
                                <label for="email" class="form-label fw-medium">Email Address</label>
                                <input type="email" class="form-control rounded-pill" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label fw-medium">Password</label>
                                <input type="password" class="form-control rounded-pill" id="password" name="password" required>
                            </div>
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary rounded-pill py-2 fw-medium">Login</button>
                            </div>
                        </form>
                        
                        <div class="text-center">
                            <p class="mb-0">Don't have an account? <a href="register.php" class="text-primary text-decoration-none fw-medium">Register here</a></p>
                            <p class="mb-0 mt-2"><a href="index.php" class="text-muted text-decoration-none">← Back to Home</a></p>
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
                        <li class="mb-2"><a href="login.php" class="text-white text-decoration-none">Login</a></li>
                        <li class="mb-2"><a href="register.php" class="text-white text-decoration-none">Register</a></li>
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