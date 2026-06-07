# InternGrowth 🚀

**Connecting talented students with innovative startups for meaningful internship experiences.**

InternGrowth is a comprehensive platform that bridges the gap between students seeking practical experience and startups looking for talented interns. The platform features a task-based internship system with points, certificates, and a complete workflow management system.

---

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [User Roles](#user-roles)
- [Key Features](#key-features)
- [Workflow](#workflow)
- [Database Structure](#database-structure)
- [Configuration](#configuration)
- [Usage Guide](#usage-guide)
- [Screenshots](#screenshots)
- [Contributing](#contributing)
- [License](#license)

---

## ✨ Features

### For Students
- 📝 Browse and apply for internship tasks
- 💼 Build portfolio with completed projects
- 🏆 Earn points and certificates
- 📊 Track application status in real-time
- 💬 Direct messaging with startups
- 📈 Leaderboard rankings
- 🎓 Downloadable completion certificates

### For Startups
- 📢 Post internship tasks
- 👥 Review student applications
- ✅ Approve/reject applications
- 📥 Review work submissions
- ⏱️ 24-hour review window for fair evaluation
- 🔒 Anti-scam protection system
- ⭐ Rate student performance
- 🔐 Verification system (admin approval required)

### For Admins
- 👔 Approve/reject startup accounts
- 🛡️ Moderate flagged tasks
- 📊 Platform oversight and management
- 🎯 Monitor system activity

---

## 🛠️ Tech Stack

- **Framework**: Laravel 11
- **Authentication**: Laravel Breeze
- **Database**: MySQL
- **Frontend**: Tailwind CSS (via CDN)
- **Font**: Inter (Google Fonts)
- **PHP Version**: 8.2+
- **Design Pattern**: Repository Pattern (for complex queries)

---

## 📦 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL
- Node.js & NPM (optional, for asset compilation)

### Step 1: Clone the Repository
```bash
git clone <repository-url>
cd InternGrowth
```

### Step 2: Install Dependencies
```bash
composer install
```

### Step 3: Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### Step 4: Database Setup
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=interngrowth
DB_USERNAME=root
DB_PASSWORD=
```

Create the database:
```sql
CREATE DATABASE interngrowth;
```

### Step 5: Run Migrations
```bash
php artisan migrate
```

### Step 6: Seed Database (Optional)
```bash
php artisan db:seed
```

### Step 7: Start Development Server
```bash
php artisan serve
```

Visit: `http://127.0.0.1:8000`

---

## 👥 User Roles

### 1. Student
- Register and create profile
- Browse available tasks
- Apply for tasks with cover letter
- Submit completed work
- Earn points and certificates
- View leaderboard rankings

### 2. Startup
- Register company account
- Wait for admin verification
- Post internship tasks (after verification)
- Review student applications
- Accept/reject submissions
- Rate student performance
- Cannot edit tasks after approving applications

### 3. Admin
- Approve/reject startup accounts
- Moderate flagged content
- Monitor platform activity
- Issue certificates (automatic)

---

## 🎯 Key Features

### Task Management System
- **Task Posting**: Startups post tasks with descriptions, skills, and rewards
- **Application Process**: Students apply with cover letters
- **Status Tracking**: Real-time status updates with badges
  - 📢 Open (Blue) - Available for applications
  - ⏳ In Progress (Yellow) - Student working on it
  - ✓ Completed (Green) - Successfully finished

### Points & Rewards System
- Students earn points for completed tasks
- Points tracked in personal wallet
- Transaction history maintained
- Leaderboard rankings based on points

### Certificate System
- **Automatic Issuance**: Certificates generated when work is accepted
- **Unique Certificate Numbers**: Format: CERT-ABC123XYZ
- **Downloadable**: Students can download PDF certificates
- **Verifiable**: Public verification via certificate number
- **Includes**: Student name, task title, issue date, QR code

### Anti-Scam Protection
- **24-Hour Review Window**: After downloading submission files
- **Fair Rejection Policy**: 
  - Before download: Can reject based on description
  - After download: 24 hours to reject with detailed feedback (min 20 chars)
  - After 24 hours: Must accept or contact admin
- **Payment Lock**: Prevents unfair rejections after review period

### Messaging System
- Direct communication between students and startups
- Conversation threads per task
- Real-time message notifications

### Verification System
- Startups must be verified by admin before posting tasks
- One-time verification alert after approval
- Unverified startups see persistent pending message

### Task Edit Restrictions
- Tasks can be edited before any application is approved
- Once an application is approved, task becomes locked
- Protects students from requirement changes mid-work

---

## 🔄 Workflow

### Student Journey
```
1. Register → 2. Browse Tasks → 3. Apply → 4. Get Approved → 
5. Submit Work → 6. Work Accepted → 7. Earn Points & Certificate
```

### Startup Journey
```
1. Register → 2. Wait for Verification → 3. Post Task → 
4. Review Applications → 5. Approve Student → 6. Review Submission → 
7. Accept/Reject Work → 8. Rate Student
```

### Task Lifecycle
```
Posted → Applications Received → Student Approved → 
Work Submitted → Under Review → Accepted/Rejected → 
Completed (if accepted)
```

---

## 🗄️ Database Structure

### Core Tables
- **users**: User accounts (students, startups, admins)
- **student_profiles**: Student information and bio
- **startup_profiles**: Company information and verification status
- **tasks**: Internship tasks posted by startups
- **applications**: Student applications to tasks
- **submissions**: Work submitted by students
- **certificates**: Completion certificates
- **points_wallets**: Student point balances
- **points_transactions**: Point earning history
- **messages**: Direct messaging between users
- **conversations**: Message threads
- **ratings**: Startup ratings of students
- **skills**: Available skills
- **notifications**: User notifications

### Key Relationships
- User → Student Profile (1:1)
- User → Startup Profile (1:1)
- Startup → Tasks (1:Many)
- Task → Applications (1:Many)
- Application → Submission (1:1)
- Student → Certificates (1:Many)
- Student → Points Wallet (1:1)

---

## ⚙️ Configuration

### Logo Setup
Place your logo at: `public/images/logo.png`
- Navigation: 40px height
- Auth pages: 80px height

### Color Scheme
- Primary: Indigo (#6366f1)
- Secondary: Purple (#8b5cf6)
- Gradients: from-indigo-600 to-purple-600

### Email Configuration
Update `.env` for email notifications:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

---

## 📖 Usage Guide

### For Students

#### 1. Registration & Profile Setup
```
1. Click "Register" → Select "Student"
2. Fill in name, email, password
3. Complete profile with bio and skills
4. Add portfolio links (optional)
```

#### 2. Applying for Tasks
```
1. Go to "Tasks" in navigation
2. Browse available tasks (look for "Open" badge)
3. Click "View Details" on interesting tasks
4. Write cover letter (optional but recommended)
5. Click "Apply Now"
```

#### 3. Submitting Work
```
1. Wait for startup to approve your application
2. Go to Dashboard → "My Applications"
3. Click "Submit Work" on approved application
4. Write description of your work
5. Upload files (optional)
6. Submit
```

#### 4. Viewing Certificates
```
1. Go to Dashboard
2. Scroll to "My Certificates" section
3. Click "Download" to get PDF certificate
```

### For Startups

#### 1. Registration & Verification
```
1. Click "Register" → Select "Startup"
2. Fill in company details
3. Wait for admin approval (check dashboard for status)
4. Once verified, you can post tasks
```

#### 2. Posting Tasks
```
1. Go to Dashboard
2. Click "Post New Task"
3. Fill in:
   - Task title
   - Description
   - Required skills
   - Reward points
   - Stipend (optional)
4. Submit
```

#### 3. Managing Applications
```
1. Go to Dashboard → "My Tasks"
2. Click "View Details" on a task
3. Review applications in "Applications & Submissions" section
4. Click "Approve" or "Reject" for each application
```

#### 4. Reviewing Submissions
```
1. View task details
2. Read submission description first
3. Download files if needed (starts 24-hour review window)
4. Options:
   - Accept Work: Awards points & certificate
   - Request Revision: Ask for changes
   - Reject Work: Provide detailed feedback (min 20 chars)
```

### For Admins

#### 1. Approving Startups
```
1. Login as admin
2. Go to Admin Dashboard
3. Click "Manage Startups"
4. Review pending startups
5. Click "Approve" or "Reject"
```

#### 2. Moderating Tasks
```
1. Go to Admin Dashboard
2. Click "Moderate Tasks"
3. Review flagged tasks
4. Take appropriate action
```

---

## 🎨 Design Features

### Status Badges
- **Open** (Blue): Task available for applications
- **In Progress** (Yellow): Student working on task
- **Completed** (Green): Task successfully finished
- **Approved** (Green): Application accepted
- **Rejected** (Red): Application/submission declined
- **Pending** (Gray): Awaiting review

### Notifications
- Auto-dismiss after 3 seconds
- Manual close option available
- Color-coded by type (success, error, warning, info)

### Responsive Design
- Mobile-friendly navigation
- Responsive grid layouts
- Touch-friendly buttons
- Optimized for all screen sizes

---

## 📁 Project Structure

```
InternGrowth/
├── app/
│   ├── Console/Commands/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php
│   │   │   ├── ApplicationController.php
│   │   │   ├── CertificateController.php
│   │   │   ├── MessageController.php
│   │   │   ├── StartupController.php
│   │   │   ├── StudentController.php
│   │   │   ├── SubmissionController.php
│   │   │   └── TaskController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   ├── Models/
│   │   ├── Application.php
│   │   ├── Certificate.php
│   │   ├── Message.php
│   │   ├── StartupProfile.php
│   │   ├── StudentProfile.php
│   │   ├── Submission.php
│   │   └── Task.php
│   ├── Policies/
│   └── Repositories/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   └── images/
│       └── logo.png
├── resources/
│   └── views/
│       ├── admin/
│       ├── certificates/
│       ├── messages/
│       ├── startup/
│       ├── student/
│       ├── submissions/
│       └── tasks/
└── routes/
    └── web.php
```

---

## 🔐 Security Features

- **Authentication**: Laravel Breeze with role-based access
- **Authorization**: Policies for resource access control
- **CSRF Protection**: All forms protected
- **Password Hashing**: Bcrypt encryption
- **SQL Injection Prevention**: Eloquent ORM
- **XSS Protection**: Blade templating auto-escaping
- **Anti-Scam System**: 24-hour review window

---

## 🚀 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Configure production database
- [ ] Set up email service
- [ ] Configure queue workers
- [ ] Set up SSL certificate
- [ ] Configure caching (Redis/Memcached)
- [ ] Set up backup system
- [ ] Configure logging

### Optimization Commands
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## 📊 Statistics & Analytics

The platform tracks:
- Total tasks posted
- Total applications submitted
- Points earned by students
- Certificates issued
- Completion rates
- User activity

---

## 🐛 Troubleshooting

### Common Issues

**Issue**: Cannot post tasks as startup
**Solution**: Wait for admin verification. Check dashboard for verification status.

**Issue**: Certificate not generated
**Solution**: Ensure task status is set to 'completed' and migration has run.

**Issue**: 24-hour countdown not showing
**Solution**: Files must be downloaded first to start the review window.

**Issue**: Task edit button disabled
**Solution**: Tasks cannot be edited after approving an application.

---

## 📝 Additional Documentation

- [CERTIFICATE_GUIDE.md](CERTIFICATE_GUIDE.md) - Certificate system details
- [DATABASE_STRUCTURE.md](DATABASE_STRUCTURE.md) - Database schema
- [TASK_MANAGEMENT_GUIDE.md](TASK_MANAGEMENT_GUIDE.md) - Task workflow
- [MYSQL_SETUP_GUIDE.md](MYSQL_SETUP_GUIDE.md) - Database setup

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License

This project is licensed under the MIT License.

---

## 👨‍💻 Developer

Developed with ❤️ for connecting students and startups.

---

## 📞 Support

For support, email support@interngrowth.com or open an issue in the repository.

---

## 🎉 Acknowledgments

- Laravel Framework
- Tailwind CSS
- Inter Font Family
- All contributors and testers

---

**InternGrowth** - Empowering the next generation of talent! 🚀
