# Startup Verification System

## Overview
The startup verification system prevents fake accounts by requiring startups to submit official documents and company information before they can post tasks.

## How It Works

### For Startups:
1. **Submit Verification Request** (`/startup/verification`)
   - Fill in company registration number
   - Provide GST number (optional)
   - Enter company address
   - Add contact phone number
   - Upload verification documents (PDF, JPG, PNG - max 5MB each)
   - Can upload multiple documents

2. **View Status** (Startup Dashboard)
   - See verification status: Pending, Approved, or Rejected
   - If rejected, view admin's feedback notes
   - Can resubmit after fixing issues

### For Admins:
1. **Review Requests** (`/admin/verifications`)
   - See all pending verification requests
   - View company details:
     - Company registration number
     - GST number
     - Contact phone
     - Company address
     - Website
   - Download and view uploaded documents

2. **Approve or Reject**
   - **Approve**: Sets `is_verified = true`, startup can post tasks
   - **Reject**: Provide feedback notes explaining why (shown to startup)

3. **Access Verification Dashboard**
   - From admin dashboard, click "Verify Startups" card
   - Or navigate to `/admin/verifications`

## Database Fields (startup_profiles table)
- `company_registration_number` - Required for verification
- `gst_number` - Optional tax identification
- `company_address` - Physical address
- `contact_phone` - Contact number
- `verification_documents` - JSON array of uploaded files
- `verification_status` - pending/approved/rejected
- `verification_notes` - Admin feedback (shown on rejection)
- `verification_submitted_at` - Timestamp of submission
- `verification_reviewed_at` - Timestamp of admin review
- `is_verified` - Boolean flag (true when approved)

## Routes
- `GET /startup/verification` - Verification request form
- `POST /startup/verification` - Submit verification
- `GET /admin/verifications` - Admin verification dashboard
- `POST /admin/verifications/{id}/approve` - Approve startup
- `POST /admin/verifications/{id}/reject` - Reject with notes

## Files Modified
- `app/Http/Controllers/AdminController.php` - Added verification methods
- `app/Http/Controllers/StartupController.php` - Added verification submission
- `routes/web.php` - Added verification routes
- `resources/views/admin/verifications.blade.php` - Admin review interface
- `resources/views/startup/verification.blade.php` - Startup submission form
- `resources/views/admin/dashboard.blade.php` - Added verification link

## Testing the Flow
1. Login as a startup
2. Go to dashboard, click "Get Verified"
3. Fill form and upload documents
4. Login as admin
5. Go to "Verify Startups" from dashboard
6. Review documents and approve/reject
7. Startup sees updated status on their dashboard
