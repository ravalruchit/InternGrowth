# Complete List of All Blade Files in InternGrowth Project

## 📁 Total Blade Files: 40+

---

## 1. LAYOUT FILES (2 files)

### resources/views/layouts/app.blade.php
**Purpose**: Main authenticated layout with navigation and footer  
**Used by**: All authenticated pages (student, startup, admin dashboards)

### resources/views/layouts/guest.blade.php
**Purpose**: Guest layout for login/register pages  
**Used by**: Authentication pages

---

## 2. COMPONENT FILES (2 files)

### resources/views/components/application-logo.blade.php
**Purpose**: Logo component  
**Used by**: Navigation and auth pages

### resources/views/components/app-layout.blade.php
**Purpose**: App layout component  
**Used by**: Main layout wrapper

### resources/views/components/guest-layout.blade.php
**Purpose**: Guest layout component  
**Used by**: Auth pages wrapper

---

## 3. WELCOME/HOME (1 file)

### resources/views/welcome.blade.php
**Purpose**: Landing page  
**Route**: /  
**Access**: Public

---

## 4. AUTHENTICATION FILES (9 files)

### resources/views/auth/confirm-password.blade.php
**Purpose**: Password confirmation  
**Route**: /confirm-password

### resources/views/auth/forgot-password.blade.php
**Purpose**: Request password reset  
**Route**: /forgot-password

### resources/views/auth/login.blade.php
**Purpose**: User login  
**Route**: /login

### resources/views/auth/register.blade.php
**Purpose**: User registration  
**Route**: /register

### resources/views/auth/reset-password.blade.php
**Purpose**: Reset password with token  
**Route**: /reset-password/{token}

### resources/views/auth/verify-email.blade.php
**Purpose**: Email verification notice  
**Route**: /verify-email

---

## 5. STUDENT FILES (3 files)

### resources/views/student/dashboard.blade.php
**Purpose**: Student dashboard with applications and certificates  
**Route**: /student/dashboard  
**Role**: Student only

### resources/views/student/profile.blade.php
**Purpose**: Student profile edit page  
**Route**: /student/profile  
**Role**: Student only

### resources/views/student/public-profile.blade.php
**Purpose**: Public student profile view  
**Route**: /students/{id}/profile  
**Access**: Public

---

## 6. STARTUP FILES (3 files)

### resources/views/startup/dashboard.blade.php
**Purpose**: Startup dashboard with tasks and applications  
**Route**: /startup/dashboard  
**Role**: Startup only

### resources/views/startup/profile.blade.php
**Purpose**: Startup profile edit page  
**Route**: /startup/profile  
**Role**: Startup only

---

## 7. ADMIN FILES (4 files)

### resources/views/admin/dashboard.blade.php
**Purpose**: Admin dashboard with statistics  
**Route**: /admin/dashboard  
**Role**: Admin only

### resources/views/admin/startups.blade.php
**Purpose**: Manage and approve startups  
**Route**: /admin/startups  
**Role**: Admin only

### resources/views/admin/tasks.blade.php
**Purpose**: Moderate tasks  
**Route**: /admin/tasks  
**Role**: Admin only

### resources/views/admin/submissions.blade.php
**Purpose**: Review plagiarized submissions  
**Route**: /admin/submissions  
**Role**: Admin only

---

## 8. TASK FILES (4 files)

### resources/views/tasks/index.blade.php
**Purpose**: Browse all available tasks  
**Route**: /tasks  
**Access**: Authenticated

### resources/views/tasks/show.blade.php
**Purpose**: View single task details  
**Route**: /tasks/{id}  
**Access**: Authenticated

### resources/views/tasks/create.blade.php
**Purpose**: Create new task  
**Route**: /tasks/create  
**Role**: Startup only

### resources/views/tasks/edit.blade.php
**Purpose**: Edit existing task  
**Route**: /tasks/{id}/edit  
**Role**: Startup only (task owner)

---

## 9. SUBMISSION FILES (2 files)

### resources/views/submissions/create.blade.php
**Purpose**: Submit work for approved application  
**Route**: /applications/{applicationId}/submit  
**Role**: Student only

### resources/views/submissions/review.blade.php
**Purpose**: Review student submission  
**Route**: /startup/submissions/{id}/review  
**Role**: Startup only

---

## 10. CERTIFICATE FILES (2 files)

### resources/views/certificates/download.blade.php
**Purpose**: Download certificate PDF  
**Route**: /student/certificates/{id}/download  
**Role**: Student only (certificate owner)

### resources/views/certificates/verify.blade.php
**Purpose**: Verify certificate authenticity  
**Route**: /certificates/verify/{certificateNumber}  
**Access**: Public

---

## 11. MESSAGE FILES (2 files)

### resources/views/messages/index.blade.php
**Purpose**: List all conversations  
**Route**: /messages  
**Access**: Authenticated

### resources/views/messages/show.blade.php
**Purpose**: View conversation and send messages  
**Route**: /messages/{id}  
**Access**: Authenticated (conversation participant)

---

## 12. LEADERBOARD (1 file)

### resources/views/leaderboard/index.blade.php
**Purpose**: Display student rankings  
**Route**: /leaderboard  
**Access**: Public

---

## 13. PROFILE (1 file)

### resources/views/profile/edit.blade.php
**Purpose**: Edit user profile  
**Route**: /profile  
**Access**: Authenticated

---

## FILE ORGANIZATION SUMMARY

```
resources/views/
├── layouts/
│   ├── app.blade.php (Main layout)
│   └── guest.blade.php (Auth layout)
├── components/
│   ├── application-logo.blade.php
│   ├── app-layout.blade.php
│   └── guest-layout.blade.php
├── auth/ (9 files)
│   ├── confirm-password.blade.php
│   ├── forgot-password.blade.php
│   ├── login.blade.php
│   ├── register.blade.php
│   ├── reset-password.blade.php
│   └── verify-email.blade.php
├── student/ (3 files)
│   ├── dashboard.blade.php
│   ├── profile.blade.php
│   └── public-profile.blade.php
├── startup/ (3 files)
│   ├── dashboard.blade.php
│   └── profile.blade.php
├── admin/ (4 files)
│   ├── dashboard.blade.php
│   ├── startups.blade.php
│   ├── tasks.blade.php
│   └── submissions.blade.php
├── tasks/ (4 files)
│   ├── index.blade.php
│   ├── show.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── submissions/ (2 files)
│   ├── create.blade.php
│   └── review.blade.php
├── certificates/ (2 files)
│   ├── download.blade.php
│   └── verify.blade.php
├── messages/ (2 files)
│   ├── index.blade.php
│   └── show.blade.php
├── leaderboard/
│   └── index.blade.php
├── profile/
│   └── edit.blade.php
└── welcome.blade.php
```

---

## BLADE FILES BY ROLE ACCESS

### Public Access (3 files)
- welcome.blade.php
- students/{id}/profile (public-profile.blade.php)
- certificates/verify (verify.blade.php)

### Student Only (6 files)
- student/dashboard.blade.php
- student/profile.blade.php
- submissions/create.blade.php
- certificates/download.blade.php
- tasks/index.blade.php (browse)
- tasks/show.blade.php (apply)

### Startup Only (7 files)
- startup/dashboard.blade.php
- startup/profile.blade.php
- tasks/create.blade.php
- tasks/edit.blade.php
- tasks/show.blade.php (review applications)
- submissions/review.blade.php

### Admin Only (4 files)
- admin/dashboard.blade.php
- admin/startups.blade.php
- admin/tasks.blade.php
- admin/submissions.blade.php

### All Authenticated (5 files)
- messages/index.blade.php
- messages/show.blade.php
- leaderboard/index.blade.php
- profile/edit.blade.php
- tasks/index.blade.php

### Auth Pages (9 files)
- All files in auth/ directory

---

## BLADE FILES BY FEATURE

### Dashboard Pages (3)
- student/dashboard.blade.php
- startup/dashboard.blade.php
- admin/dashboard.blade.php

### Profile Pages (3)
- student/profile.blade.php
- startup/profile.blade.php
- profile/edit.blade.php

### Task Management (4)
- tasks/index.blade.php
- tasks/show.blade.php
- tasks/create.blade.php
- tasks/edit.blade.php

### Application & Submission (2)
- submissions/create.blade.php
- submissions/review.blade.php

### Messaging (2)
- messages/index.blade.php
- messages/show.blade.php

### Certificates (2)
- certificates/download.blade.php
- certificates/verify.blade.php

### Admin Management (4)
- admin/dashboard.blade.php
- admin/startups.blade.php
- admin/tasks.blade.php
- admin/submissions.blade.php

---

## MOST IMPORTANT FILES TO CUSTOMIZE

### Priority 1 (Core User Experience)
1. layouts/app.blade.php - Main layout
2. student/dashboard.blade.php - Student home
3. startup/dashboard.blade.php - Startup home
4. tasks/index.blade.php - Task browsing
5. tasks/show.blade.php - Task details

### Priority 2 (Key Features)
6. submissions/create.blade.php - Work submission
7. messages/index.blade.php - Messaging
8. certificates/download.blade.php - Certificates
9. admin/dashboard.blade.php - Admin panel
10. welcome.blade.php - Landing page

### Priority 3 (Supporting Pages)
11. student/profile.blade.php - Profile editing
12. startup/profile.blade.php - Company profile
13. tasks/create.blade.php - Task creation
14. admin/startups.blade.php - Startup approval

---

## TOTAL COUNT BY CATEGORY

- **Layouts**: 2 files
- **Components**: 3 files
- **Authentication**: 9 files
- **Student**: 3 files
- **Startup**: 3 files
- **Admin**: 4 files
- **Tasks**: 4 files
- **Submissions**: 2 files
- **Certificates**: 2 files
- **Messages**: 2 files
- **Other**: 3 files

**GRAND TOTAL: ~40 Blade Files**

---

This is the complete structure of all Blade template files in your InternGrowth project!
