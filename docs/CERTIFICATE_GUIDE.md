# Certificate System Guide

## How to Issue Certificates to Students

The InternGrowth platform has an automated certificate system that rewards students who successfully complete tasks. Here's how it works:

---

## Certificate Issuance Process

### Current System (Automatic)

Certificates are **automatically issued** when a startup accepts a student's work submission. Here's the flow:

1. **Student completes task** → Submits work
2. **Startup reviews** → Accepts the submission
3. **System automatically**:
   - Awards points to student
   - Marks task as completed
   - **Issues certificate automatically** (if implemented in SubmissionController)

### Manual Certificate Issuance (Admin)

Admins can also manually issue certificates through the admin panel:

**Steps:**
1. Login as Admin
2. Go to Admin Dashboard
3. Navigate to "Submissions" section
4. Find completed/accepted submissions
5. Click "Issue Certificate" button
6. Certificate is generated with unique certificate number

---

## Certificate Features

### What's Included in Each Certificate:

- **Student Name**: Full name of the student
- **Task Title**: Name of the completed task
- **Certificate Number**: Unique identifier (e.g., CERT-ABC123XYZ)
- **Issue Date**: Date when certificate was issued
- **QR Code**: For verification (placeholder currently)
- **Verification URL**: Link to verify certificate authenticity

### Certificate Number Format:
```
CERT-[UNIQUE_ID]
Example: CERT-65A3F2B1C4D5E
```

---

## How Students Access Their Certificates

### Option 1: Student Dashboard
1. Student logs in
2. Goes to Dashboard
3. Sees "My Certificates" section (if implemented)
4. Clicks "Download Certificate" button

### Option 2: Direct Link
Students can access certificates via:
```
/student/certificates/{certificate_id}/download
```

---

## Certificate Verification

Anyone can verify a certificate's authenticity:

1. Visit: `/certificates/verify/{certificate_number}`
2. Enter the certificate number
3. System displays:
   - Student name
   - Task completed
   - Issue date
   - Verification status

---

## Implementing Automatic Certificate Issuance

To make certificates issue automatically when work is accepted, add this to `SubmissionController@accept`:

```php
public function accept($id)
{
    $submission = Submission::with('application.task')->findOrFail($id);
    
    // Update submission status
    $submission->update(['status' => 'accepted']);
    
    // Mark task as completed
    $submission->application->task->update(['status' => 'completed']);
    
    // Award points to student
    $wallet = PointsWallet::firstOrCreate(
        ['student_profile_id' => $submission->application->student_profile_id]
    );
    $wallet->increment('balance', $submission->application->task->reward_points);
    
    // CREATE CERTIFICATE AUTOMATICALLY
    $certificateNumber = 'CERT-' . strtoupper(uniqid());
    Certificate::create([
        'student_profile_id' => $submission->application->student_profile_id,
        'task_id' => $submission->application->task_id,
        'certificate_number' => $certificateNumber,
        'issued_at' => now()
    ]);
    
    return back()->with('success', 'Work accepted and certificate issued!');
}
```

---

## Adding Certificate Section to Student Dashboard

Add this to `resources/views/student/dashboard.blade.php`:

```blade
<!-- My Certificates -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">My Certificates</h2>
    <div class="space-y-4">
        @forelse($profile->certificates as $certificate)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg text-gray-900">{{ $certificate->task->title }}</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            Certificate #{{ $certificate->certificate_number }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            Issued: {{ $certificate->issued_at->format('M d, Y') }}
                        </p>
                    </div>
                    <a href="{{ route('student.certificates.download', $certificate->id) }}" 
                       class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:shadow-lg transition">
                        📄 Download
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500 mt-2">No certificates yet.</p>
                <p class="text-gray-400 text-sm mt-1">Complete tasks to earn certificates!</p>
            </div>
        @endforelse
    </div>
</div>
```

---

## Certificate Model Relationship

Add this to `StudentProfile` model:

```php
public function certificates()
{
    return $this->hasMany(Certificate::class, 'student_profile_id');
}
```

---

## Summary

**Current Status:**
- ✅ Certificate database table exists
- ✅ Certificate model created
- ✅ Certificate download page exists
- ✅ Certificate verification system works
- ✅ Admin can manually issue certificates
- ⚠️ Automatic issuance needs to be added to SubmissionController
- ⚠️ Student dashboard needs certificate section

**To Complete:**
1. Add automatic certificate creation in `SubmissionController@accept`
2. Add certificate section to student dashboard
3. Add relationship to StudentProfile model
4. Test the complete flow

**Certificate Flow:**
```
Task Completed → Work Submitted → Startup Accepts → 
Certificate Auto-Generated → Student Can Download
```
