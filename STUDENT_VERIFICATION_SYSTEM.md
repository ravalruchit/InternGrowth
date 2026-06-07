# Student Verification System

## Overview
Students must verify their college email to access all tasks. Unverified students can only see 5 tasks, while verified students get full access.

## How It Works

### For Unverified Students:
1. **Limited Access**
   - Can only see 5 tasks on the tasks page
   - See warning banner on dashboard and tasks page
   - Can still register and apply, but with limited options

2. **Verification Process**
   - Click "Verify Now" button on dashboard
   - Or go to `/student/verification`
   - Enter college email (must end with .edu, .ac.in, or .edu.in)
   - Enter college/university name
   - Click "Send Verification Email"

3. **Email Verification**
   - Receive email with verification link
   - Click link to verify
   - Automatically redirected to dashboard with success message

### For Verified Students:
- See ALL available tasks (no limit)
- Green "Verified Student" badge on dashboard
- Shows college name
- Full platform access

## Database Fields (student_profiles table)
- `college_email` - Student's college email address
- `college_name` - Name of college/university
- `verification_token` - Unique token for email verification
- `is_verified` - Boolean flag (true when email verified)
- `email_verified_at` - Timestamp of verification

## Routes
- `GET /student/verification` - Verification form
- `POST /student/verification/send` - Send verification email
- `GET /verify-email/{token}` - Verify email via link

## Email Validation
College emails must end with:
- `.edu` (US colleges)
- `.ac.in` (Indian colleges)
- `.edu.in` (Indian universities)

## Files Created/Modified

### New Files:
- `database/migrations/2026_02_21_050000_add_verification_fields_to_student_profiles_table.php`
- `resources/views/student/verification.blade.php`
- `resources/views/emails/student-verification.blade.php`

### Modified Files:
- `app/Models/StudentProfile.php` - Added verification fields
- `app/Http/Controllers/StudentController.php` - Added verification methods
- `app/Http/Controllers/TaskController.php` - Added task limiting logic
- `routes/web.php` - Added verification routes
- `resources/views/student/dashboard.blade.php` - Added verification banner
- `resources/views/tasks/index.blade.php` - Added limitation warning

## Testing the Flow
1. Login as a student (unverified)
2. See only 5 tasks on tasks page
3. See warning banner on dashboard
4. Click "Verify Now"
5. Enter college email and name
6. Check email inbox
7. Click verification link
8. See success message and verified badge
9. Now see all tasks

## Important Notes
- Verification token is single-use
- Email must be valid college domain
- Verification status shown on dashboard
- Unverified students can still apply to the 5 visible tasks
- Startups see student verification status (future feature)
