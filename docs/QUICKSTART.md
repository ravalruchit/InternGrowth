# InternGrowth - Quick Start Guide

## Get Started in 3 Minutes

### 1. Start the Application
```bash
cd InternGrowth
php artisan serve
```

Visit: **http://localhost:8000**

### 2. Login with Demo Accounts

**Admin Account:**
- Email: `admin@interngrowth.com`
- Password: `password`
- Access: Approve startups, moderate tasks, issue certificates

**Student Account:**
- Email: `student@example.com`
- Password: `password`
- Access: Browse tasks, apply, submit work, earn points

**Startup Account:**
- Email: `startup@example.com`
- Password: `password`
- Access: Post tasks, review applications, manage submissions

### 3. Try These Workflows

#### As a Student:
1. Login → Browse Tasks → Apply to a task
2. Edit Profile → Add skills and portfolio links
3. View Leaderboard → See your ranking
4. Check Dashboard → View application status

#### As a Startup:
1. Login → Post New Task
2. Define skills, points, and optional stipend
3. Review Applications → Approve/Reject
4. Review Submissions → Accept/Request Revision/Reject
5. Rate Students after completion

#### As an Admin:
1. Login → Approve pending startups
2. Moderate flagged tasks
3. Review plagiarized submissions
4. Issue certificates for completed work

## Key Features to Explore

### Points System
- Students earn points for completed tasks
- Points displayed on leaderboard
- Transaction history tracked

### Certificate System
- Certificates issued by admin
- QR code verification (placeholder)
- Downloadable PDF format

### Notification System
- Application status updates
- Submission feedback
- Points earned notifications

### Scoring System
- Student reliability score (0-1)
- Startup credibility score (0-1)
- Displayed on profiles and leaderboard

## Creating New Content

### Register New Users
1. Click "Register" on homepage
2. Choose role: Student or Startup
3. Students are auto-verified
4. Startups need admin approval

### Post a Task (Startup)
1. Login as startup
2. Dashboard → "Post New Task"
3. Fill in details, select skills
4. Set reward points and optional stipend

### Apply to Task (Student)
1. Login as student
2. Browse Tasks → Select task
3. Write cover letter (optional)
4. Click "Apply Now"

## Workflow Example

**Complete Task Lifecycle:**

1. **Startup** posts task with 100 points reward
2. **Student** applies with cover letter
3. **Startup** approves application
4. **Student** submits work (text + files)
5. **Startup** reviews submission:
   - Accept → Student earns 100 points
   - Request Revision → Student resubmits
   - Reject → Task ends
6. **Admin** issues certificate
7. **Student** downloads certificate with QR code

## Troubleshooting

### Database Issues
```bash
php artisan migrate:fresh --seed
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Permission Errors
Ensure storage and bootstrap/cache are writable:
```bash
chmod -R 775 storage bootstrap/cache
```

## Next Steps

- Customize views in `resources/views/`
- Add more skills via database seeder
- Integrate real QR code generation
- Add email notifications
- Implement file uploads for submissions
- Add payment gateway for stipends

## Support

Check `README_SETUP.md` for detailed documentation.
