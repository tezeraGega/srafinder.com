<?php
// index.php
require_once 'config/config.php';

// Get featured jobs for the homepage
$stmt = $pdo->prepare("SELECT j.*, e.company_name FROM jobs j 
                      JOIN employers e ON j.employer_id = e.employer_id 
                      WHERE j.is_active = 1 AND j.is_featured = 1 
                      ORDER BY j.created_at DESC LIMIT 6");
$stmt->execute();
$featured_jobs = $stmt->fetchAll();

// Get all job categories
$stmt = $pdo->query("SELECT * FROM job_categories ORDER BY name");
$categories = $stmt->fetchAll();

// Get total counts for statistics
$stmt = $pdo->query("SELECT COUNT(*) as total_jobs FROM jobs WHERE is_active = 1");
$total_jobs = $stmt->fetch()['total_jobs'];

$stmt = $pdo->query("SELECT COUNT(*) as total_employers FROM employers");
$total_employers = $stmt->fetch()['total_employers'];

$stmt = $pdo->query("SELECT COUNT(*) as total_seekers FROM job_seekers");
$total_seekers = $stmt->fetch()['total_seekers'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiraFinder - Ethiopia's Premier Job Portal</title>
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
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="jobs.php">Find Jobs</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <?php if (getUserRole() === 'jobseeker'): ?>
                            <?php
                                // Get job seeker profile photo
                                $stmt = $pdo->prepare("SELECT js.profile_image, js.is_verified, js.is_premium, u.first_name, u.last_name FROM job_seekers js JOIN users u ON js.user_id = u.user_id WHERE js.user_id = ?");
                                $stmt->execute([getUserId()]);
                                $seeker = $stmt->fetch();
                            ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="<?php
                                        if ($seeker['profile_image']) {
                                            echo 'uploads/jobseekers/' . htmlspecialchars($seeker['profile_image']);
                                        } else {
                                            echo 'assets/images/default-avatar.php';
                                        }
                                    ?>" 
                                    alt="Profile" 
                                    class="rounded-circle me-2" 
                                    style="width: 40px; height: 40px; object-fit: cover;"
                                    onerror="this.onerror=null;this.src='assets/images/default-avatar.php';">
                                    <span><?php echo htmlspecialchars($seeker['first_name'] . ' ' . $seeker['last_name']); ?></span>
                                                                <?php if ($seeker['is_verified']): ?>
                                                                    <i class="bi bi-patch-check-fill text-success ms-1" title="Verified"></i>
                                                                <?php endif; ?>
                                                                <?php if ($seeker['is_premium']): ?>
                                                                    <i class="bi bi-star-fill text-warning ms-1" title="Premium"></i>
                                                                <?php endif; ?>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="jobseeker/profile.php">Profile</a></li>
                                    <li><a class="dropdown-item" href="jobseeker/applied_jobs.php">Applied Jobs</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                </ul>
                            </li>
                        <?php elseif (getUserRole() === 'employer'): ?>
                            <?php
                                // Get employer profile photo
                                $stmt = $pdo->prepare("SELECT e.logo_path, e.company_name FROM employers e WHERE e.user_id = ?");
                                $stmt->execute([getUserId()]);
                                $employer = $stmt->fetch();
                            ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="<?php
                                        if ($employer['logo_path']) {
                                            echo 'uploads/employers/' . htmlspecialchars($employer['logo_path']);
                                        } else {
                                            echo 'assets/images/default-avatar.php';
                                        }
                                    ?>" 
                                    alt="Profile" 
                                    class="rounded-circle me-2" 
                                    style="width: 40px; height: 40px; object-fit: cover;"
                                    onerror="this.onerror=null;this.src='assets/images/default-avatar.php';">
                                    <span><?php echo htmlspecialchars($employer['company_name']); ?></span>
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
                                <a class="nav-link" href="logout.php">Logout</a>
                            </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-primary me-2" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-white py-5">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-3">Find Your Dream Job in Ethiopia</h1>
                    <p class="lead mb-4">Connect with top employers and discover opportunities across various industries in Ethiopia's leading job portal.</p>
                    
                    <!-- Job Search Form -->
                    <div class="search-form mx-auto" style="max-width: 700px;">
                        <form method="GET" action="jobs.php" class="row g-3">
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="search" placeholder="Job title, keywords, or company">
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="location" placeholder="Location">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">Search Jobs</button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="mt-4">
                        <a href="jobs.php" class="btn btn-outline-light me-2">Browse All Jobs</a>
                        <a href="register.php" class="btn btn-light">Post a Job</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5" style="background-color: var(--background-color);">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="card stats-card h-100 border-0">
                        <div class="card-body">
                            <i class="bi bi-briefcase display-4 text-primary mb-3"></i>
                            <h3 class="card-title mb-0"><?php echo $total_jobs; ?></h3>
                            <p class="card-text text-muted mb-0">Active Jobs</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card stats-card h-100 border-0">
                        <div class="card-body">
                            <i class="bi bi-building display-4 text-primary mb-3"></i>
                            <h3 class="card-title mb-0"><?php echo $total_employers; ?></h3>
                            <p class="card-text text-muted mb-0">Companies</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card stats-card h-100 border-0">
                        <div class="card-body">
                            <i class="bi bi-people display-4 text-primary mb-3"></i>
                            <h3 class="card-title mb-0"><?php echo $total_seekers; ?></h3>
                            <p class="card-text text-muted mb-0">Job Seekers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Jobs Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">Featured Jobs</h2>
            <div class="row">
                <?php if (count($featured_jobs) > 0): ?>
                    <?php foreach ($featured_jobs as $job): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card job-card h-100">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold"><?php echo htmlspecialchars($job['title']); ?></h5>
                                    <p class="card-text text-primary fw-medium"><?php echo htmlspecialchars($job['company_name']); ?></p>
                                    <p class="card-text text-muted mb-2"><?php echo substr(htmlspecialchars($job['description']), 0, 100); ?>...</p>
                                    <div class="job-meta">
                                        <span class="me-3"><i class="bi bi-geo-alt me-1"></i> <?php echo htmlspecialchars($job['location']); ?></span>
                                        <?php if ($job['salary_min'] && $job['salary_max']): ?>
                                            <span><i class="bi bi-currency-dollar me-1"></i> <?php echo number_format($job['salary_min']); ?> - <?php echo number_format($job['salary_max']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mt-3">
                                        <a href="job_details.php?job_id=<?php echo $job['job_id']; ?>" class="btn btn-primary w-100">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">No featured jobs available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="text-center mt-4">
                <a href="jobs.php" class="btn btn-outline-primary">View All Jobs</a>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-5" style="background-color: var(--background-color);">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">Popular Job Categories</h2>
            <div class="row">
                <?php foreach ($categories as $category): ?>
                    <div class="col-md-4 col-lg-3 mb-3">
                        <a href="jobs.php?category=<?php echo $category['category_id']; ?>" class="btn btn-outline-primary rounded-pill w-100">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold text-white mb-3">SiraFinder</h5>
                    <p class="text-white">Ethiopia's premier job portal connecting talented professionals with leading employers.</p>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
                    </div>
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
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Browse Resumes</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Recruiting Solutions</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Pricing</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold text-white mb-3">Contact Us</h5>
                    <p class="text-white">
                        <i class="bi bi-geo-alt me-2"></i> Addis Ababa, Ethiopia<br>
                        <i class="bi bi-envelope me-2"></i> info@sirafinder.et<br>
                        <i class="bi bi-telephone me-2"></i> +251-11-123-4567
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