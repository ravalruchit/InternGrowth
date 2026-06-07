# InternGrowth - Student-Startup Internship Microtask Marketplace

A complete Laravel 11 application for connecting students with startups through microtask internships.

## Features

### User Roles
- **Students**: Create profiles, apply to tasks, submit work, earn points, download certificates
- **Startups**: Post tasks, review applications, manage submissions, rate students
- **Admins**: Approve startups, moderate tasks, handle disputes, issue certificates

### Core Functionality
- Role-based authentication with Laravel Breeze
- Points wallet system with transaction history
- Certificate generation with QR verification
- Notification system
- Leaderboard with student rankings
- Reliability and credibility scoring
- Task workflow: posted → applied → approved → submitted → reviewed → accepted/rejected

## Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM (optional for frontend assets)
- SQLite/MySQL/PostgreSQL

### Setup Steps

1. **Navigate to project directory**
```bash
cd InternGrowth
```

2. **Install dependencies**
```bash
composer install
```

3. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database in .env**
```
DB_CONNECTION=sqlite
# Or for MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=interngrowth
# DB_USERNAME=root
# DB_PASSWORD=
```

5. **Run migrations and seed database**
```bash
php artisan migrate --seed
```

6. **Install frontend dependencies (optional)**
```bash
npm install
npm run build
```

7. **Start development server**
```bash
php artisan serve
```

Visit: http://localhost:8000

## Default Credentials

After seeding, you can login with:

**Admin:**
- Email: admin@interngrowth.com
- Password: password

**Student:**
- Email: student@example.com
- Password: password

**Startup:**
- Email: startup@example.com
- Password: password

## Project Structure

### Models
- User, StudentProfile, StartupProfile
- Task, Application, Submission
- Skill, PointsWallet, PointsTransaction
- Certificate, Rating, Notification

### Controllers
- StudentController - Student dashboard and profile
- StartupController - Startup dashboard and profile
- AdminController - Admin management
- TaskController - Task CRUD operations
- ApplicationController - Application management
- SubmissionController - Submission review workflow
- CertificateController - Certificate generation and verification
- LeaderboardController - Student rankings

### Repositories
- StudentRepository
- TaskRepository
- ApplicationRepository

### Policies
- TaskPolicy
- ApplicationPolicy
- SubmissionPolicy

### Middleware
- RoleMiddleware - Role-based access control

## Key Routes

### Public
- `/` - Welcome page
- `/leaderboard` - Student leaderboard
- `/students/{id}/profile` - Public student profile
- `/certificates/verify/{number}` - Certificate verification

### Student Routes (Prefix: /student)
- `/dashboard` - Student dashboard
- `/profile` - Edit profile
- `/certificates/{id}/download` - Download certificate

### Startup Routes (Prefix: /startup)
- `/dashboard` - Startup dashboard
- `/profile` - Edit profile
- `/applications/{id}/approve` - Approve application
- `/submissions/{id}/review` - Review submission

### Admin Routes (Prefix: /admin)
- `/dashboard` - Admin dashboard
- `/startups` - Manage startups
- `/tasks` - Moderate tasks
- `/submissions` - Handle plagiarism

### Shared Routes
- `/tasks` - Browse tasks
- `/tasks/{id}` - View task details
- `/tasks/create` - Create task (startup only)
- `/tasks/{id}/apply` - Apply to task (student only)

## Database Schema

### Users Table
- id, name, email, password, role (student/startup/admin), is_verified

### Student Profiles
- id, user_id, bio, portfolio_links (JSON), reliability_score

### Startup Profiles
- id, user_id, company_name, description, website, credibility_score

### Tasks
- id, startup_profile_id, title, description, required_skills (JSON), reward_points, stipend, status, is_flagged

### Applications
- id, task_id, student_profile_id, cover_letter, status (applied/approved/rejected)

### Submissions
- id, application_id, content, files (JSON), status, feedback, is_plagiarized

### Points Wallet & Transactions
- Wallet: id, student_profile_id, balance
- Transaction: id, points_wallet_id, task_id, amount, type, description

### Certificates
- id, student_profile_id, task_id, certificate_number, qr_code, issued_at

### Skills
- id, name
- Pivot tables: student_skill, skill_task

### Ratings
- id, task_id, student_profile_id, startup_profile_id, rating, comment

### Notifications
- id, user_id, title, message, type, is_read

## Architecture Patterns

### Repository Pattern
Business logic is separated into repository classes for better testability and maintainability.

### Policy-Based Authorization
Laravel policies control access to resources based on user roles and ownership.

### Service Layer
Complex operations (points transactions, certificate generation) can be extracted to service classes.

## Customization

### Adding New Skills
```php
php artisan tinker
>>> App\Models\Skill::create(['name' => 'New Skill']);
```

### Adjusting Scoring Algorithms
Edit the repository methods in:
- `app/Repositories/StudentRepository.php`
- Update `reliability_score` calculation logic

### Certificate Customization
Modify the certificate template in:
- `resources/views/certificates/download.blade.php`

## Security Features

- Role-based middleware protection
- Policy-based authorization
- CSRF protection on all forms
- Password hashing with bcrypt
- SQL injection protection via Eloquent ORM

## Future Enhancements

- Real QR code generation (integrate SimpleSoftwareIO/simple-qrcode)
- Email notifications
- File upload for submissions
- Advanced plagiarism detection
- Payment integration for stipends
- Real-time notifications with WebSockets
- API for mobile apps

## License

This project is open-sourced software licensed under the MIT license.

## Support

For issues and questions, please create an issue in the repository.
