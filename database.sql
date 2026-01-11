-- SiraFinder Job Portal Database Schema

-- Create database
CREATE DATABASE IF NOT EXISTS sirafinder_db;
USE sirafinder_db;

-- Users table (for job seekers and employers)
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('jobseeker', 'employer', 'admin') NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Job Seekers table
CREATE TABLE job_seekers (
    seeker_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    bio TEXT,
    skills TEXT,
    experience_level ENUM('entry', 'intermediate', 'senior', 'executive'),
    education TEXT,
    resume_path VARCHAR(500),
    profile_image VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Employers table
CREATE TABLE employers (
    employer_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    company_description TEXT,
    industry VARCHAR(100),
    company_size VARCHAR(50),
    website VARCHAR(255),
    address TEXT,
    contact_person VARCHAR(255),
    contact_email VARCHAR(255),
    contact_phone VARCHAR(20),
    logo_path VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Job Categories table
CREATE TABLE job_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Jobs table
CREATE TABLE jobs (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    employer_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    requirements TEXT NOT NULL,
    category_id INT,
    location VARCHAR(255) NOT NULL,
    job_type ENUM('full-time', 'part-time', 'contract', 'freelance', 'internship') NOT NULL,
    salary_min DECIMAL(10, 2),
    salary_max DECIMAL(10, 2),
    experience_required VARCHAR(100),
    education_required VARCHAR(255),
    is_featured BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employer_id) REFERENCES employers(employer_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES job_categories(category_id) ON DELETE SET NULL
);

-- Job Applications table
CREATE TABLE job_applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    seeker_id INT NOT NULL,
    cover_letter TEXT,
    resume_path VARCHAR(500),
    status ENUM('pending', 'reviewed', 'accepted', 'rejected') DEFAULT 'pending',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(job_id) ON DELETE CASCADE,
    FOREIGN KEY (seeker_id) REFERENCES job_seekers(seeker_id) ON DELETE CASCADE,
    UNIQUE KEY unique_application (job_id, seeker_id)
);

-- Subscription Plans table
CREATE TABLE subscription_plans (
    plan_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    duration_months INT NOT NULL,
    job_post_limit INT NOT NULL,
    featured_job_limit INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Employer Subscriptions table
CREATE TABLE employer_subscriptions (
    subscription_id INT AUTO_INCREMENT PRIMARY KEY,
    employer_id INT NOT NULL,
    plan_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('active', 'expired', 'cancelled') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employer_id) REFERENCES employers(employer_id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES subscription_plans(plan_id) ON DELETE CASCADE
);

-- Admin table
CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    position VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Insert default admin user (password: admin123)
INSERT INTO users (email, password, role, first_name, last_name) 
VALUES ('admin@sirafinder.et', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Admin', 'User');

INSERT INTO admins (user_id, full_name, position) 
VALUES (1, 'System Admin', 'Administrator');

-- Insert default subscription plans
INSERT INTO subscription_plans (name, description, price, duration_months, job_post_limit, featured_job_limit) VALUES
('Free Plan', 'Basic plan with limited features', 0.00, 30, 1, 0),
('Standard Plan', 'Standard plan with more job posts', 150.00, 30, 10, 2),
('Premium Plan', 'Premium plan with unlimited features', 300.00, 30, 50, 10);

-- Insert default job categories
INSERT INTO job_categories (name, description) VALUES
('Information Technology', 'Technology and software development jobs'),
('Healthcare', 'Medical and healthcare related jobs'),
('Finance', 'Banking and financial services jobs'),
('Education', 'Teaching and educational jobs'),
('Engineering', 'Engineering and technical jobs'),
('Marketing', 'Marketing and advertising jobs'),
('Sales', 'Sales and business development jobs'),
('Human Resources', 'HR and personnel management jobs');