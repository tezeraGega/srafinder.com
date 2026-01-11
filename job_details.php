<?php
// job_details.php
require_once 'config/config.php';

if (!isset($_GET['job_id'])) {
    redirect_to('jobs.php');
}

$job_id = (int)$_GET['job_id'];

// Get job details
$stmt = $pdo->prepare("SELECT j.*, e.company_name, e.company_description, e.logo_path, 
                      c.name as category_name 
                      FROM jobs j 
                      JOIN employers e ON j.employer_id = e.employer_id 
                      JOIN job_categories c ON j.category_id = c.category_id 
                      WHERE j.job_id = ? AND j.is_active = 1");
$stmt->execute([$job_id]);
$job = $stmt->fetch();

if (!$job) {
    redirect_to('jobs.php');
}

// Check if user has applied for this job
$user_applied = false;
if (isJobSeeker()) {
    $stmt = $pdo->prepare("SELECT application_id FROM job_applications 
                          WHERE job_id = ? AND seeker_id = ?");
    $stmt->execute([$job_id, getUserId()]);
    $user_applied = $stmt->fetch() ? true : false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($job['title']); ?> - SiraFinder</title>
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
            <a class="navbar-brand fw-bold text-primary-custom" href="index.php">SiraFinder</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="jobs.php">Find Jobs</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <?php if (getUserRole() === 'jobseeker'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="jobseeker/profile.php">Profile</a>
                            </li>
                        <?php elseif (getUserRole() === 'employer'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="employer/dashboard.php">Dashboard</a>
                            </li>
                        <?php elseif (getUserRole() === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin/dashboard.php">Admin Panel</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/index.php">
                                <i class="bi bi-shield-lock"></i> Admin
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="py-4 bg-light">
        <div class="container">
            <h1 class="mb-0 fw-bold"><?php echo htmlspecialchars($job['title']); ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Home</a></li>
                    <li class="breadcrumb-item"><a href="jobs.php" class="text-decoration-none text-muted">Find Jobs</a></li>
                    <li class="breadcrumb-item active text-primary-custom"><?php echo htmlspecialchars($job['title']); ?></li>
                </ol>
            </nav>
        </div>
    </section>

    <div class="container my-5">
        <div class="row">
            <div class="col-lg-8">
                <!-- Job Details -->
                <div class="card job-card mb-4 shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h2 class="card-title fw-bold"><?php echo htmlspecialchars($job['title']); ?></h2>
                                <p class="card-text h5 text-primary-custom"><?php echo htmlspecialchars($job['company_name']); ?></p>
                            </div>
                            <?php if ($job['is_featured']): ?>
                                <span class="badge bg-warning text-dark fw-bold">Featured</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="card-text text-muted">
                                    <i class="bi bi-geo-alt me-2"></i> <?php echo htmlspecialchars($job['location']); ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="card-text text-muted">
                                    <i class="bi bi-briefcase me-2"></i> <?php echo ucfirst($job['job_type']); ?>
                                </p>
                            </div>
                        </div>
                        
                        <?php if ($job['salary_min'] && $job['salary_max']): ?>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p class="card-text text-muted">
                                        <i class="bi bi-currency-dollar me-2"></i> 
                                        <?php echo number_format($job['salary_min']); ?> - <?php echo number_format($job['salary_max']); ?> ETB
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="card-text text-muted">
                                        <i class="bi bi-folder me-2"></i> <?php echo htmlspecialchars($job['category_name']); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-4">
                            <h4 class="fw-bold mb-3">Description</h4>
                            <p class="text-muted"><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
                        </div>
                        
                        <div class="mb-4">
                            <h4 class="fw-bold mb-3">Requirements</h4>
                            <p class="text-muted"><?php echo nl2br(htmlspecialchars($job['requirements'])); ?></p>
                        </div>
                        
                        <?php if ($job['experience_required']): ?>
                            <div class="mb-4">
                                <h4 class="fw-bold mb-3">Experience Required</h4>
                                <p class="text-muted"><?php echo htmlspecialchars($job['experience_required']); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($job['education_required']): ?>
                            <div class="mb-4">
                                <h4 class="fw-bold mb-3">Education Required</h4>
                                <p class="text-muted"><?php echo htmlspecialchars($job['education_required']); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-4">
                            <h4 class="fw-bold mb-3">Posted</h4>
                            <p class="text-muted"><?php echo date('F j, Y', strtotime($job['created_at'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Company Info -->
                <div class="card job-card mb-4 shadow-sm border-0">
                    <div class="card-header bg-primary-custom text-white py-3">
                        <h5 class="mb-0 fw-bold">Company Info</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <h4 class="fw-bold"><?php echo htmlspecialchars($job['company_name']); ?></h4>
                        </div>
                        <p class="text-muted"><?php echo htmlspecialchars(substr($job['company_description'], 0, 150)); ?>...</p>
                    </div>
                </div>
                
                <!-- Apply Section -->
                <div class="card job-card shadow-sm border-0">
                    <div class="card-header bg-primary-custom text-white py-3">
                        <h5 class="mb-0 fw-bold">Apply for this Job</h5>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!isLoggedIn()): ?>
                            <p class="text-muted">Please <a href="login.php" class="text-primary-custom fw-bold text-decoration-none">login</a> to apply for this job.</p>
                        <?php elseif (!isJobSeeker()): ?>
                            <p class="text-muted">Only job seekers can apply for jobs.</p>
                        <?php elseif ($user_applied): ?>
                            <p class="text-success fw-bold">You have already applied for this job.</p>
                        <?php else: ?>
                            <a href="jobseeker/apply.php?job_id=<?php echo $job['job_id']; ?>" class="btn btn-primary-custom rounded-pill w-100 py-2 fw-bold">
                                Apply Now
                            </a>
                        <?php endif; ?>
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