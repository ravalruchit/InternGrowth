# InternGrowth - Database Structure & Data Storage

## 📍 Database Location

Your application uses **SQLite** database by default.

**Database File Location:**
```
InternGrowth/database/database.sqlite
```

This single file contains ALL your application data:
- Users
- Tasks
- Applications
- Messages
- Points
- Certificates
- Everything!

## 📊 Database Tables

### 1. **users** - User Accounts
**Location:** `database.sqlite`
**Stores:**
- id
- name (Student name or Startup contact name)
- email
- password (encrypted)
- role (student/startup/admin)
- is_verified (for startup approval)
- email_verified_at
- remember_token
- created_at, updated_at

**Example Data:**
```
id: 1
name: "John Student"
email: "student@example.com"
role: "student"
is_verified: true
```

---

### 2. **student_profiles** - Student Information
**Location:** `database.sqlite`
**Stores:**
- id
- user_id (links to users table)
- bio (student description)
- portfolio_links (JSON array of URLs)
- reliability_score (0.00 to 1.00)
- created_at, updated_at

**Example Data:**
```
id: 1
user_id: 2
bio: "Passionate developer..."
portfolio_links: ["https://github.com/john", "https://linkedin.com/in/john"]
reliability_score: 0.85
```

---

### 3. **startup_profiles** - Startup/Company Information
**Location:** `database.sqlite`
**Stores:**
- id
- user_id (links to users table)
- company_name
- description
- website
- credibility_score (0.00 to 1.00)
- created_at, updated_at

**Example Data:**
```
id: 1
user_id: 3
company_name: "Tech Innovations Inc"
description: "Building the future..."
website: "https://techinnovations.com"
credibility_score: 0.90
```

---

### 4. **skills** - Available Skills
**Location:** `database.sqlite`
**Stores:**
- id
- name (skill name)
- created_at, updated_at

**Example Data:**
```
id: 1, name: "PHP"
id: 2, name: "Laravel"
id: 3, name: "JavaScript"
id: 4, name: "React"
```

---

### 5. **student_skill** - Student Skills (Pivot Table)
**Location:** `database.sqlite`
**Stores:**
- student_profile_id
- skill_id

**Links students to their skills**

---

### 6. **tasks** - Internship Tasks
**Location:** `database.sqlite`
**Stores:**
- id
- startup_profile_id (who posted it)
- title
- description
- required_skills (JSON array)
- reward_points
- stipend (optional)
- status (posted/closed/moderated)
- is_flagged (for admin review)
- created_at, updated_at

**Example Data:**
```
id: 1
startup_profile_id: 1
title: "Build a Landing Page"
description: "Create responsive..."
required_skills: ["HTML", "CSS", "JavaScript"]
reward_points: 100
stipend: 50.00
status: "posted"
```

---

### 7. **skill_task** - Task Required Skills (Pivot Table)
**Location:** `database.sqlite`
**Stores:**
- task_id
- skill_id

**Links tasks to required skills**

---

### 8. **applications** - Student Applications to Tasks
**Location:** `database.sqlite`
**Stores:**
- id
- task_id
- student_profile_id
- cover_letter (optional)
- status (applied/approved/rejected)
- created_at, updated_at

**Example Data:**
```
id: 1
task_id: 1
student_profile_id: 1
cover_letter: "I'm a great fit because..."
status: "approved"
```

---

### 9. **submissions** - Student Work Submissions
**Location:** `database.sqlite`
**Stores:**
- id
- application_id
- content (text submission)
- files (JSON array of file paths)
- status (submitted/under_review/revision_requested/accepted/rejected)
- feedback (from startup)
- is_plagiarized (flag for admin)
- created_at, updated_at

**Example Data:**
```
id: 1
application_id: 1
content: "Here is my completed work..."
files: ["uploads/file1.pdf", "uploads/file2.jpg"]
status: "accepted"
feedback: "Great work!"
```

---

### 10. **points_wallets** - Student Points Balance
**Location:** `database.sqlite`
**Stores:**
- id
- student_profile_id
- balance (total points)
- created_at, updated_at

**Example Data:**
```
id: 1
student_profile_id: 1
balance: 250
```

---

### 11. **points_transactions** - Points History
**Location:** `database.sqlite`
**Stores:**
- id
- points_wallet_id
- task_id (optional)
- amount (points earned/deducted)
- type (earned/deducted/bonus)
- description
- created_at, updated_at

**Example Data:**
```
id: 1
points_wallet_id: 1
task_id: 1
amount: 100
type: "earned"
description: "Task completed"
```

---

### 12. **certificates** - Completion Certificates
**Location:** `database.sqlite`
**Stores:**
- id
- student_profile_id
- task_id
- certificate_number (unique)
- qr_code (path to QR image)
- issued_at
- created_at, updated_at

**Example Data:**
```
id: 1
student_profile_id: 1
task_id: 1
certificate_number: "CERT-ABC123"
issued_at: "2026-02-11 10:30:00"
```

---

### 13. **ratings** - Student Ratings from Startups
**Location:** `database.sqlite`
**Stores:**
- id
- task_id
- student_profile_id
- startup_profile_id
- rating (1-5)
- comment (optional)
- created_at, updated_at

**Example Data:**
```
id: 1
task_id: 1
student_profile_id: 1
startup_profile_id: 1
rating: 5
comment: "Excellent work!"
```

---

### 14. **notifications** - User Notifications
**Location:** `database.sqlite`
**Stores:**
- id
- user_id
- title
- message
- type (success/info/warning)
- is_read (boolean)
- created_at, updated_at

**Example Data:**
```
id: 1
user_id: 2
title: "Application Approved"
message: "Your application has been approved"
type: "success"
is_read: false
```

---

### 15. **conversations** - Message Threads
**Location:** `database.sqlite`
**Stores:**
- id
- student_profile_id
- startup_profile_id
- task_id (optional - what they're discussing)
- created_at, updated_at

**Example Data:**
```
id: 1
student_profile_id: 1
startup_profile_id: 1
task_id: 1
```

---

### 16. **messages** - Chat Messages
**Location:** `database.sqlite`
**Stores:**
- id
- conversation_id
- sender_id (user who sent it)
- message (text content)
- is_read (boolean)
- created_at, updated_at

**Example Data:**
```
id: 1
conversation_id: 1
sender_id: 2
message: "Hi, I'm interested in this task"
is_read: true
created_at: "2026-02-11 14:30:00"
```

---

### 17. **cache** - Application Cache
**Location:** `database.sqlite`
**Stores:** Temporary cached data

---

### 18. **jobs** - Background Jobs Queue
**Location:** `database.sqlite`
**Stores:** Queued background tasks

---

### 19. **sessions** - User Sessions
**Location:** `database.sqlite`
**Stores:** Active user login sessions

---

## 🗂️ File Storage Locations

### Uploaded Files
**Location:** `InternGrowth/storage/app/`
- Submission files
- Profile images (if added)
- Certificate QR codes

### Public Files
**Location:** `InternGrowth/public/`
- CSS/JS assets
- Public images
- Downloadable files

### Logs
**Location:** `InternGrowth/storage/logs/laravel.log`
- Application errors
- Debug information
- System logs

---

## 🔍 How to View Your Data

### Method 1: Using Database Browser (Recommended)
1. Download **DB Browser for SQLite**: https://sqlitebrowser.org/
2. Open the file: `InternGrowth/database/database.sqlite`
3. Browse all tables and data visually

### Method 2: Using Laravel Tinker (Command Line)
```bash
cd InternGrowth
php artisan tinker

# View all users
>>> App\Models\User::all();

# View all tasks
>>> App\Models\Task::all();

# View all messages
>>> App\Models\Message::all();

# Count students
>>> App\Models\User::where('role', 'student')->count();
```

### Method 3: Using SQL Queries
```bash
cd InternGrowth/database
sqlite3 database.sqlite

# View all tables
.tables

# View users
SELECT * FROM users;

# View tasks
SELECT * FROM tasks;

# View messages
SELECT * FROM messages;

# Exit
.quit
```

---

## 📦 Backup Your Data

### Quick Backup
Simply copy the database file:
```bash
copy InternGrowth\database\database.sqlite InternGrowth\database\backup_database.sqlite
```

### Full Backup (Recommended)
```bash
# Backup entire database folder
xcopy InternGrowth\database InternGrowth\database_backup\ /E /I
```

---

## 🔄 Database Relationships

```
users
├── student_profiles (1:1)
│   ├── skills (M:M via student_skill)
│   ├── applications (1:M)
│   ├── points_wallet (1:1)
│   │   └── points_transactions (1:M)
│   ├── certificates (1:M)
│   └── ratings (1:M)
│
├── startup_profiles (1:1)
│   ├── tasks (1:M)
│   │   ├── skills (M:M via skill_task)
│   │   ├── applications (1:M)
│   │   │   └── submissions (1:1)
│   │   └── certificates (1:M)
│   └── ratings (1:M)
│
├── conversations (M:M)
│   └── messages (1:M)
│
└── notifications (1:M)
```

---

## 📊 Data Summary

**Total Tables:** 19
**Main Data Tables:** 16
**System Tables:** 3 (cache, jobs, sessions)

**Storage Size:**
- Database: ~1-10 MB (depending on usage)
- Uploaded Files: Varies
- Logs: Grows over time

---

## 🛠️ Maintenance Commands

### Clear old data
```bash
# Clear cache
php artisan cache:clear

# Clear old sessions
php artisan session:clear

# Optimize database
php artisan optimize
```

### Reset database (WARNING: Deletes all data!)
```bash
php artisan migrate:fresh --seed
```

---

## 📝 Summary

**Everything is stored in ONE file:**
```
📁 InternGrowth/
  └── 📁 database/
      └── 📄 database.sqlite  ← ALL YOUR DATA IS HERE!
```

This file contains:
- ✅ All users (students, startups, admins)
- ✅ All tasks and applications
- ✅ All messages and conversations
- ✅ All points and transactions
- ✅ All certificates and ratings
- ✅ Everything!

**To backup:** Just copy this one file!
**To restore:** Replace this file with your backup!

Simple and easy! 🎉
