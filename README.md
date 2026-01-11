# SiraFinder - Ethiopia's Premier Job Portal

SiraFinder is a complete job portal system built for Ethiopia, connecting job seekers with employers. The system includes user authentication, job posting, application management, and monetization features.

## Features

- **User Authentication**: Job seekers, employers, and admin accounts
- **Job Management**: Post, search, and apply for jobs
- **Application Tracking**: Manage job applications
- **Monetization**: Subscription plans for employers
- **Admin Panel**: Manage users, jobs, and system settings

## Technology Stack

- **Frontend**: HTML5, CSS3 (Bootstrap 5), JavaScript
- **Backend**: PHP (procedural)
- **Database**: MySQL
- **Server**: Apache (XAMPP)

## Installation

### Prerequisites

- XAMPP (Apache, MySQL, PHP)

### Setup Instructions

1. **Download and Install XAMPP**
   - Download from: https://www.apachefriends.org/
   - Install and start Apache and MySQL services

2. **Place Files**
   - Extract this project to `C:\xampp\htdocs\SiraFinder1`

3. **Create Database**
   - Open phpMyAdmin (usually available at http://localhost/phpmyadmin)
   - Create a new database named `sirafinder_db`
   - Import the `database.sql` file located in the project root

4. **Configure Database**
   - The database configuration is already set in `config/config.php`
   - Default settings: localhost, root user, no password

5. **Access the Application**
   - Open your browser and go to: http://localhost/SiraFinder1

## Default Admin Account

- **Email**: admin@sirafinder.et
- **Password**: admin123

## Project Structure

```
SiraFinder1/
├── config/
│   └── config.php          # Database and session configuration
├── admin/                  # Admin panel files
│   ├── auth_admin.php      # Admin authentication check
│   ├── dashboard.php       # Admin dashboard
│   ├── login.php           # Admin login
│   ├── users.php           # User management
│   ├── jobs.php            # Job management
│   └── monetization.php    # Subscription management
├── employer/               # Employer panel files
│   ├── auth_employer.php   # Employer authentication check
│   ├── dashboard.php       # Employer dashboard
│   ├── post_job.php        # Post new job
│   ├── my_jobs.php         # Manage employer's jobs
│   └── applicants.php      # View job applicants
├── jobseeker/              # Job seeker panel files
│   ├── auth_jobseeker.php  # Job seeker authentication check
│   ├── profile.php         # Job seeker profile
│   ├── applied_jobs.php    # View applied jobs
│   └── apply.php           # Apply for jobs
├── assets/                 # Static assets
│   ├── css/                # CSS files
│   ├── js/                 # JavaScript files
│   └── images/             # Image files
├── database.sql            # Database schema
├── index.php               # Home page
├── jobs.php                # Job listings
├── job_details.php         # Job detail page
├── login.php               # User login
├── register.php            # User registration
└── logout.php              # User logout
```

## Security Features

- Password hashing using `password_hash()`
- Prepared statements to prevent SQL injection
- Input sanitization
- Session management
- Role-based access control
- CSRF protection through session validation

## Monetization

- Free plan: 1 job post
- Standard plan: 10 job posts, 2 featured jobs
- Premium plan: 50 job posts, 10 featured jobs

## Testing the Application

1. **Public Pages**:
   - Visit http://localhost/SiraFinder1 to see the home page
   - Browse jobs at http://localhost/SiraFinder1/jobs.php

2. **User Registration**:
   - Register as job seeker or employer at http://localhost/SiraFinder1/register.php

3. **Admin Panel**:
   - Login to admin panel at http://localhost/SiraFinder1/admin/login.php
   - Use default credentials: admin@sirafinder.et / admin123

4. **Employer Panel**:
   - Register as employer and post jobs
   - Manage applications

5. **Job Seeker Panel**:
   - Register as job seeker
   - Apply for jobs
   - View applied jobs

## Troubleshooting

### Common Issues

1. **Database Connection Error**:
   - Ensure MySQL service is running in XAMPP Control Panel
   - Check database credentials in `config/config.php`

2. **Page Not Found**:
   - Ensure the project is placed in `C:\xampp\htdocs\SiraFinder1`
   - Restart Apache service

3. **Session Issues**:
   - Clear browser cache and cookies
   - Ensure session_start() is called only once in `config/config.php`

### XAMPP Configuration

- Apache port: 80 (default)
- MySQL port: 3306 (default)
- If ports are different, update in `config/config.php`

## Customization

### Adding New Job Categories

- Add new categories in the `job_categories` table in the database

### Modifying Subscription Plans

- Update the `subscription_plans` table in the database
- Modify the limits as needed

## License

This project is created for educational purposes and can be used freely for non-commercial projects.