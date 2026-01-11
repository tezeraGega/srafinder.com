<?php
// jobs.php
require_once 'config/config.php';

// Get filters
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$location = isset($_GET['location']) ? sanitize_input($_GET['location']) : '';
$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';

// Build query with filters
$sql = "SELECT j.*, e.company_name FROM jobs j 
        JOIN employers e ON j.employer_id = e.employer_id 
        WHERE j.is_active = 1";

$params = [];

if ($category_id > 0) {
    $sql .= " AND j.category_id = ?";
    $params[] = $category_id;
}

if (!empty($location)) {
    $sql .= " AND j.location LIKE ?";
    $params[] = "%$location%";
}

if (!empty($search)) {
    $sql .= " AND (j.title LIKE ? OR j.description LIKE ? OR e.company_name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY j.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$jobs = $stmt->fetchAll();

// Get all job categories for filter
$stmt = $pdo->query("SELECT * FROM job_categories ORDER BY name");
$categories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs - SiraFinder</title>
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
                                <a class="nav-link" href="admin/dashboard.php">
                                    <i class="bi bi-gear"></i> Admin
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
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

    <!-- Page Header -->
    <section class="py-4" style="background-color: var(--background-color);">
        <div class="container">
            <h1 class="mb-0 fw-bold">Find Jobs</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Jobs</li>
                </ol>
            </nav>
        </div>
    </section>

    <div class="container my-5">
        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="fw-bold">Filters</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="jobs.php">
                            <div class="mb-3">
                                <label for="search" class="form-label fw-medium">Search Keywords</label>
                                <input type="text" class="form-control" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Job title, company...">
                            </div>
                            
                            <div class="mb-3">
                                <label for="category" class="form-label fw-medium">Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['category_id']; ?>" <?php echo ($category_id == $category['category_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="location" class="form-label fw-medium">Location</label>
                                <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($location); ?>" placeholder="City, Country...">
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 rounded-pill">Apply Filters</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Job Listings -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold"><?php echo count($jobs); ?> Jobs Found</h3>
                </div>

                <?php if (count($jobs) > 0): ?>
                    <?php foreach ($jobs as $job): ?>
                        <div class="card job-card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h5 class="card-title fw-bold">
                                            <a href="job_details.php?job_id=<?php echo $job['job_id']; ?>" class="text-decoration-none text-dark">
                                                <?php echo htmlspecialchars($job['title']); ?>
                                            </a>
                                        </h5>
                                        <p class="card-text text-primary fw-medium mb-1"><?php echo htmlspecialchars($job['company_name']); ?></p>
                                        <div class="job-meta mb-2">
                                            <span class="me-3"><i class="bi bi-geo-alt me-1"></i> <?php echo htmlspecialchars($job['location']); ?></span>
                                            <?php if ($job['job_type']): ?>
                                                <span class="me-3"><i class="bi bi-briefcase me-1"></i> <?php echo ucfirst($job['job_type']); ?></span>
                                            <?php endif; ?>
                                            <?php if ($job['salary_min'] && $job['salary_max']): ?>
                                                <span><i class="bi bi-currency-dollar me-1"></i> <?php echo number_format($job['salary_min']); ?> - <?php echo number_format($job['salary_max']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="card-text text-muted"><?php echo substr(htmlspecialchars($job['description']), 0, 150); ?>...</p>
                                    </div>
                                    <div class="col-md-3 text-md-end">
                                        <a href="job_details.php?job_id=<?php echo $job['job_id']; ?>" class="btn btn-outline-primary btn-sm d-block mb-2">View Details</a>
                                        <?php if (isJobSeeker()): ?>
                                            <a href="jobseeker/apply.php?job_id=<?php echo $job['job_id']; ?>" class="btn btn-primary btn-sm d-block">Apply Now</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-search display-4 text-muted mb-3"></i>
                        <h4 class="text-muted">No jobs found</h4>
                        <p class="text-muted">Try adjusting your search criteria or check back later for new opportunities.</p>
                    </div>
                <?php endif; ?>
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