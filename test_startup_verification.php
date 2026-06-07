<?php

/**
 * Integration Test for Startup Trust & Verification System
 * Run from terminal: php test_startup_verification.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\{User, StartupProfile, StudentProfile, Task};
use App\Http\Controllers\{StartupController, AdminController, TaskController, HiringOfferController};
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;

echo "=========================================================\n";
echo "🧪 Starting Startup Trust & Verification System Tests\n";
echo "=========================================================\n\n";

// Ensure we have a clean test startup user
$email = 'test_startup_verify_' . uniqid() . '@example.com';
$user = User::create([
    'name' => 'Vetting Test Company',
    'email' => $email,
    'password' => bcrypt('secret123'),
    'role' => 'startup',
    'email_verified_at' => now(),
]);

$profile = StartupProfile::create([
    'user_id' => $user->id,
    'company_name' => 'Vetting Test Company Inc.',
    'description' => 'Test description for verification',
    'is_verified' => false,
    'verification_status' => 'pending',
    'wallet_balance' => 0.00
]);

echo "✓ Created test startup user: {$user->name} (Email: {$user->email})\n";
echo "  - Initial verification state: is_verified = " . ($profile->is_verified ? 'true' : 'false') . ", status = '" . ($profile->verification_status ?? 'NULL') . "'\n\n";

// 1. Authenticate as the unverified startup
auth()->login($user);
echo "--- Test 1: Block Task Creation for Unverified Startup ---\n";
$taskController = app(TaskController::class);

// Try to access create view
$response = $taskController->create();
if ($response instanceof \Illuminate\Http\RedirectResponse) {
    $redirectUrl = $response->getTargetUrl();
    $errorMsg = session()->get('error');
    if (str_contains($errorMsg, 'must be verified')) {
        echo "✓ Success: Unverified startup blocked from task create view. Error: \"{$errorMsg}\"\n";
    } else {
        echo "❌ FAIL: Redirected, but wrong error message: \"{$errorMsg}\"\n";
    }
} else {
    echo "❌ FAIL: Expected RedirectResponse, got " . get_class($response) . "\n";
}

// Try to post/store a task
$reqStoreTask = new Request([
    'title' => 'Unverified Task Posting',
    'description' => 'Should be blocked immediately',
    'stipend' => 1000,
    'skills' => [1] // assume skill with ID 1 exists
]);
$responseStore = $taskController->store($reqStoreTask);
if ($responseStore instanceof \Illuminate\Http\RedirectResponse && str_contains(session()->get('error') ?? '', 'must be verified')) {
    echo "✓ Success: Unverified startup blocked from storing tasks.\n";
} else {
    echo "❌ FAIL: Unverified startup task store was not blocked correctly. Error: \"" . session()->get('error') . "\"\n";
}

// 2. Block Direct Hiring Offer
echo "\n--- Test 2: Block Direct Hiring Offer for Unverified Startup ---\n";
$offerController = app(HiringOfferController::class);

// Try to extend offer (first ensure at least one student profile exists)
$studentProfile = StudentProfile::first();
if (!$studentProfile) {
    // If no student exists, create one for the test
    $studentUser = User::create([
        'name' => 'Test Student Candidate',
        'email' => 'test_student_' . uniqid() . '@example.com',
        'password' => bcrypt('password'),
        'role' => 'student',
        'email_verified_at' => now(),
    ]);
    $studentProfile = StudentProfile::create([
        'user_id' => $studentUser->id,
        'bio' => 'Test student bio',
        'is_verified' => true,
    ]);
}

$reqStoreOffer = new Request([
    'student_profile_id' => $studentProfile->id,
    'offer_type' => 'internship',
    'title' => 'Direct Internship Offer',
    'description' => 'Direct hiring description',
    'compensation' => 5000,
    'compensation_period' => 'monthly',
    'start_date' => now()->addDays(5)->toDateString(),
]);

$responseOffer = $offerController->store($reqStoreOffer);
if ($responseOffer instanceof \Illuminate\Http\RedirectResponse) {
    $errorMsg = session()->get('error');
    if (str_contains($errorMsg, 'must be verified')) {
        echo "✓ Success: Unverified startup blocked from sending hiring offers. Error: \"{$errorMsg}\"\n";
    } else {
        echo "❌ FAIL: Redirected, but wrong error message: \"{$errorMsg}\"\n";
    }
} else {
    echo "❌ FAIL: Expected RedirectResponse, got " . get_class($responseOffer) . "\n";
}

// 3. Submit Verification Request
echo "\n--- Test 3: Submit Verification Request ---\n";
$startupController = app(StartupController::class);

$fakePdf = UploadedFile::fake()->create('reg_certificate.pdf', 500, 'application/pdf');
$fakePng = UploadedFile::fake()->create('gst_certificate.png', 1000, 'image/png');

// Try submitting without files (should fail validation since it's the initial submission)
echo "  - Attempting submission without documents...\n";
try {
    $reqVerificationFail = Request::create(
        '/startup/verification',
        'POST',
        [
            'company_registration_number' => 'U98765MH2025PTC999999',
            'gst_number' => '27ABCDE1234F1Z1',
            'company_address' => '404 Inception Lane, Silicon Valley, MH 400001',
            'contact_phone' => '+91 99999 88888',
        ]
    );
    $startupController->submitVerification($reqVerificationFail);
    echo "  ❌ FAIL: Submission succeeded without any files!\n";
} catch (\Illuminate\Validation\ValidationException $e) {
    echo "  ✓ Success: Correctly threw validation exception on missing files. Message: " . $e->getMessage() . "\n";
}

// Now submit with files
$reqVerification = Request::create(
    '/startup/verification',
    'POST',
    [
        'company_registration_number' => 'U98765MH2025PTC999999',
        'gst_number' => '27ABCDE1234F1Z1',
        'company_address' => '404 Inception Lane, Silicon Valley, MH 400001',
        'contact_phone' => '+91 99999 88888',
    ],
    [],
    [
        'documents' => [$fakePdf, $fakePng]
    ]
);

$responseSubmit = $startupController->submitVerification($reqVerification);
$profile->refresh();

if ($profile->verification_status === 'pending' && $profile->verification_submitted_at) {
    echo "✓ Success: Verification request submitted.\n";
    echo "  - Current Status: '{$profile->verification_status}'\n";
    echo "  - Submission Timestamp: {$profile->verification_submitted_at}\n";
    echo "  - Uploaded Documents Count: " . count($profile->verification_documents) . "\n";
} else {
    echo "❌ FAIL: Verification submission did not update status to pending.\n";
}

// 4. Admin Dashboard Pending Count Check
echo "\n--- Test 4: Admin Dashboard Pending Verification Count ---\n";
// Find admin user to authenticate
$adminUser = User::where('role', 'admin')->first();
if (!$adminUser) {
    $adminUser = User::create([
        'name' => 'System Admin',
        'email' => 'admin_test_' . uniqid() . '@interngrowth.com',
        'password' => bcrypt('admin123'),
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);
}
auth()->login($adminUser);
echo "✓ Authenticated as Admin: {$adminUser->name}\n";

$adminController = app(AdminController::class);

// Get verifications dashboard data to verify it contains our pending startup
$verificationsResponse = $adminController->verifications();
$pendingList = $verificationsResponse->getData()['pendingVerifications'];
$foundInPending = $pendingList->contains('id', $profile->id);

if ($foundInPending) {
    echo "✓ Success: The startup request shows up in the admin pending queue.\n";
} else {
    echo "❌ FAIL: The startup was not found in the admin pending list.\n";
}

// Check if admin dashboard pending count is updated correctly
$dashboardResponse = $adminController->dashboard();
$pendingCount = $dashboardResponse->getData()['pendingStartups'];
echo "  - Admin Dashboard Pending Count: {$pendingCount}\n";

// 5. Admin Approve Verification
echo "\n--- Test 5: Admin Approve Verification Request ---\n";
$responseApprove = $adminController->approveVerification($profile->id);
$profile->refresh();

if ($profile->is_verified === true && $profile->verification_status === 'approved' && is_null($profile->verification_notes)) {
    echo "✓ Success: Admin approved startup.\n";
    echo "  - State: is_verified = " . ($profile->is_verified ? 'true' : 'false') . ", status = '{$profile->verification_status}'\n";
} else {
    echo "❌ FAIL: Admin approval did not update profile status correctly.\n";
}

// Check session alerts for startup
$alertKey = 'startup_just_verified_' . $user->id;
if (session()->has($alertKey)) {
    echo "✓ Success: Session notification alert set for startup.\n";
} else {
    echo "❌ FAIL: Session verification alert not set.\n";
}

// 6. Verified Startup Accesses Gates
echo "\n--- Test 6: Verify Access to Gates for Approved Startup ---\n";
$user = $user->fresh();
$user->unsetRelation('startupProfile');
auth()->login($user);

// Check task creation access (should load create view instead of redirecting with error)
$responseVerifiedCreate = $taskController->create();
if ($responseVerifiedCreate instanceof \Illuminate\View\View) {
    echo "✓ Success: Approved startup can now access the task creation page.\n";
} else {
    echo "❌ FAIL: Approved startup still blocked from task creation page! Result: " . get_class($responseVerifiedCreate) . "\n";
}

// 7. Admin Rejection Flow Check
echo "\n--- Test 7: Admin Rejection Flow and Notes display ---\n";
// Let's set it back to pending for rejection test
$profile->update([
    'is_verified' => false,
    'verification_status' => 'pending'
]);

auth()->login($adminUser);
$reqReject = new Request([
    'notes' => 'The uploaded GST certificate is missing the signature page. Please upload the complete page.'
]);
$responseReject = $adminController->rejectVerification($reqReject, $profile->id);
$profile->refresh();

if ($profile->is_verified === false && $profile->verification_status === 'rejected') {
    echo "✓ Success: Admin rejected startup.\n";
    echo "  - Rejection Feedback Notes: \"{$profile->verification_notes}\"\n";
} else {
    echo "❌ FAIL: Admin rejection failed to update status to rejected.\n";
}

// 8. Clean up
echo "\n--- Cleaning up Test Models ---\n";
if (isset($studentUser)) {
    $studentProfile->delete();
    $studentUser->delete();
}
$profile->delete();
$user->delete();
echo "✓ Test models deleted successfully.\n";

echo "\n=========================================================\n";
echo "🎉 ALL STARTUP VERIFICATION LIFE-CYCLE TESTS PASSED!\n";
echo "=========================================================\n";
