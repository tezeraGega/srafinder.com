<?php
// about.php
require_once 'config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - SiraFinder</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
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
                        <a class="nav-link" href="employer/about.php">For Employers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="about.php">About</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (!isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php else: ?>
                        <?php if (getUserRole() === 'jobseeker'): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    <img src="<?php
                                        $user_id = $_SESSION['user_id'];
                                        $stmt = $pdo->prepare("SELECT profile_image FROM job_seekers WHERE user_id = ?");
                                        $stmt->execute([$user_id]);
                                        $seeker = $stmt->fetch();
                                        if ($seeker && $seeker['profile_image']) {
                                            echo 'uploads/jobseekers/' . htmlspecialchars($seeker['profile_image']);
                                        } else {
                                            echo 'assets/images/default-avatar.php';
                                        }
                                    ?>" 
                                    alt="Profile" 
                                    class="rounded-circle me-2" 
                                    style="width: 40px; height: 40px; object-fit: cover;">
                                    Job Seeker
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="jobseeker/profile.php">Profile</a></li>
                                    <li><a class="dropdown-item" href="jobseeker/applied_jobs.php">Applied Jobs</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                </ul>
                            </li>
                        <?php elseif (getUserRole() === 'employer'): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    <img src="<?php
                                        $user_id = $_SESSION['user_id'];
                                        $stmt = $pdo->prepare("SELECT logo_path FROM employers WHERE user_id = ?");
                                        $stmt->execute([$user_id]);
                                        $employer = $stmt->fetch();
                                        if ($employer && $employer['logo_path']) {
                                            echo 'uploads/employers/' . htmlspecialchars($employer['logo_path']);
                                        } else {
                                            echo 'assets/images/default-avatar.php';
                                        }
                                    ?>" 
                                    alt="Profile" 
                                    class="rounded-circle me-2" 
                                    style="width: 40px; height: 40px; object-fit: cover;">
                                    Employer
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="employer/dashboard.php">Dashboard</a></li>
                                    <li><a class="dropdown-item" href="employer/my_jobs.php">My Jobs</a></li>
                                    <li><a class="dropdown-item" href="employer/applicants.php">Applicants</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                </ul>
                            </li>
                        <?php elseif (getUserRole() === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin/dashboard.php">
                                    <i class="bi bi-shield-lock"></i> Admin
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Logout</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="py-4 bg-light">
        <div class="container">
            <h1 class="mb-0 fw-bold">About SiraFinder</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Home</a></li>
                    <li class="breadcrumb-item active text-primary-custom">About</li>
                </ol>
            </nav>
        </div>
    </section>

    <div class="container my-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-body p-5">
                        <h2 class="fw-bold text-primary-custom mb-4">About SiraFinder</h2>
                        <p class="lead">SiraFinder is Ethiopia's premier job portal, connecting talented professionals with leading employers across the country.</p>
                        
                        <div class="row mt-5">
                            <div class="col-md-6 mb-4">
                                <div class="p-4 text-center">
                                    <i class="bi bi-people display-3 text-primary-custom mb-3"></i>
                                    <h4 class="fw-bold">For Job Seekers</h4>
                                    <p class="text-muted">Find your dream job with thousands of opportunities from top employers in Ethiopia.</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="p-4 text-center">
                                    <i class="bi bi-building display-3 text-primary-custom mb-3"></i>
                                    <h4 class="fw-bold">For Employers</h4>
                                    <p class="text-muted">Connect with qualified candidates and fill your positions efficiently.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-5">
                            <h3 class="fw-bold text-primary-custom">Our Mission</h3>
                            <p class="text-muted">We aim to bridge the gap between job seekers and employers in Ethiopia by providing a seamless, efficient, and user-friendly platform that connects talent with opportunity.</p>
                            
                            <h3 class="fw-bold text-primary-custom mt-4">Our Vision</h3>
                            <p class="text-muted">To be the leading job portal in Ethiopia that empowers professionals to achieve their career goals and helps organizations find the right talent to drive their success.</p>
                            
                            <h3 class="fw-bold text-primary-custom mt-4">Why Choose SiraFinder?</h3>
                            <ul class="list-group list-group-flush mt-4">
                                <li class="list-group-item border-0 px-0">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <strong>Local Focus:</strong> Dedicated to the Ethiopian job market with deep understanding of local business practices
                                </li>
                                <li class="list-group-item border-0 px-0">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <strong>Comprehensive:</strong> Features for both job seekers and employers in one integrated platform
                                </li>
                                <li class="list-group-item border-0 px-0">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <strong>Efficient:</strong> Advanced matching and search features to quickly find the right opportunities
                                </li>
                                <li class="list-group-item border-0 px-0">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <strong>Trusted:</strong> Used by thousands of job seekers and hundreds of employers across Ethiopia
                                </li>
                            </ul>
                        </div>
                        
                        <div class="mt-5 text-center">
                            <a href="jobs.php" class="btn btn-primary-custom btn-lg rounded-pill px-4 py-2 me-2">
                                <i class="bi bi-search me-2"></i>Find Jobs
                            </a>
                            <a href="employer/about.php" class="btn btn-outline-primary btn-lg rounded-pill px-4 py-2">
                                <i class="bi bi-briefcase me-2"></i>For Employers
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-5">
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-people fs-2 text-primary-custom"></i>
                                </div>
                                <h5 class="fw-bold">5000+</h5>
                                <p class="text-muted mb-0">Job Seekers</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-building fs-2 text-primary-custom"></i>
                                </div>
                                <h5 class="fw-bold">500+</h5>
                                <p class="text-muted mb-0">Employers</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-briefcase fs-2 text-primary-custom"></i>
                                </div>
                                <h5 class="fw-bold">2000+</h5>
                                <p class="text-muted mb-0">Jobs Listed</p>
                            </div>
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
                <div class="col-md-2 mb-4">
                    <h5 class="fw-bold text-white mb-3">For Job Seekers</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="jobs.php" class="text-white text-decoration-none">Browse Jobs</a></li>
                        <li class="mb-2"><a href="register.php" class="text-white text-decoration-none">Create Account</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Career Advice</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Resume Builder</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="fw-bold text-white mb-3">For Employers</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="register.php" class="text-white text-decoration-none">Post a Job</a></li>
                        <li class="mb-2"><a href="employer/about.php" class="text-white text-decoration-none">For Employers</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Recruiting Solutions</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Pricing</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold text-white mb-3">Contact Us</h5>
                    <p class="text-white">
                        <i class="bi bi-envelope me-2"></i> info@sirafinder.et<br>
                        <i class="bi bi-telephone me-2"></i> +251-11-123-4567<br>
                        <i class="bi bi-geo-alt me-2"></i> Addis Ababa, Ethiopia
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