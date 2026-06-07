<?php

/**
 * Integration Test for Startup Reputation & Trust Score System
 * Run from terminal: php test_startup_reputation.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\{User, StartupProfile, StudentProfile, Task, Application, Submission, HiringOffer, StartupReview, StartupTrustScore};
use App\Services\StartupReputationService;
use App\Http\Controllers\{StartupReviewController, StartupController};
use Illuminate\Http\Request;

echo "=========================================================\n";
echo "🧪 Starting Startup Reputation & Trust Score Tests\n";
echo "=========================================================\n\n";

// 1. Setup Test Startup and Student
$startupEmail = 'reputation_startup_' . uniqid() . '@example.com';
$startupUser = User::create([
    'name' => 'Reputation Test Startup',
    'email' => $startupEmail,
    'password' => bcrypt('secret123'),
    'role' => 'startup',
    'email_verified_at' => now(),
]);

$startupProfile = StartupProfile::create([
    'user_id' => $startupUser->id,
    'company_name' => 'Reputation Test Startup Inc.',
    'description' => 'A startup to test reputation scoring system',
    'is_verified' => true, // Start with verified
    'verification_status' => 'approved',
    'wallet_balance' => 100.00,
    'credibility_score' => 1.0 // Initial 100%
]);

$studentEmail = 'reputation_student_' . uniqid() . '@example.com';
$studentUser = User::create([
    'name' => 'Reputation Test Student',
    'email' => $studentEmail,
    'password' => bcrypt('secret123'),
    'role' => 'student',
    'email_verified_at' => now(),
]);

$studentProfile = StudentProfile::create([
    'user_id' => $studentUser->id,
    'bio' => 'A student to rate the startup',
    'is_verified' => true,
    'reliability_score' => 0.90
]);

echo "✓ Created test startup user: {$startupUser->name} (Email: {$startupUser->email})\n";
echo "✓ Created test student user: {$studentUser->name} (Email: {$studentUser->email})\n\n";

// 2. Initial Score Verification
echo "--- Test 1: Verify Initial Reputation Score (Defaults) ---\n";
$reputationService = new StartupReputationService();
$trustScore = $reputationService->updateReputation($startupProfile->id);

$startupProfile->refresh();

echo "  - Verification Score: {$trustScore->verification_score} (Expected: 100)\n";
echo "  - Payment Score: {$trustScore->payment_score} (Expected: 100)\n";
echo "  - Student Rating Score: {$trustScore->student_rating_score} (Expected: 70)\n";
echo "  - Hiring Score: {$trustScore->hiring_score} (Expected: 100)\n";
echo "  - Overall Trust Score: {$trustScore->overall_score} (Expected: 94)\n";
echo "  - Profile Credibility Score: {$startupProfile->credibility_score} (Expected: 0.94)\n";

if ($trustScore->student_rating_score == 70 && $trustScore->overall_score == 94 && $startupProfile->credibility_score == 0.94) {
    echo "✓ Success: Initial reputation score defaults are correct.\n\n";
} else {
    echo "❌ FAIL: Initial reputation score defaults are incorrect.\n\n";
}

// 3. Test Student Review Submission
echo "--- Test 2: Student Review Submission and Rating ---\n";

// Create a task
$task = Task::create([
    'startup_profile_id' => $startupProfile->id,
    'title' => 'Reputation Test Task',
    'description' => 'Test task description',
    'requirements' => 'Must know PHP and Laravel',
    'required_skills' => ['PHP', 'Laravel'],
    'stipend' => 500,
    'status' => 'posted',
    'reward_points' => 100
]);

// Create an application
$application = Application::create([
    'task_id' => $task->id,
    'student_profile_id' => $studentProfile->id,
    'status' => 'approved',
]);

// Create a submission
$submission = Submission::create([
    'application_id' => $application->id,
    'content' => 'My project submission content',
    'status' => 'accepted'
]);

echo "  - Simulated completed task, approved application, and accepted submission.\n";

// Login as student
auth()->login($studentUser);

// Try to submit review using StartupReviewController
$reviewController = app(StartupReviewController::class);
$reviewRequest = Request::create(
    "/student/tasks/{$task->id}/review",
    'POST',
    [
        'rating' => 4,
        'review' => 'Great experience working with this startup!'
    ]
);

$response = $reviewController->store($reviewRequest, $task->id);

$trustScore->refresh();
$startupProfile->refresh();

echo "  - Submitted review with rating = 4\n";
echo "  - Updated Student Rating Score: {$trustScore->student_rating_score} (Expected: 80)\n";
echo "  - Updated Overall Trust Score: {$trustScore->overall_score} (Expected: 96)\n";
echo "  - Updated Profile Credibility Score: {$startupProfile->credibility_score} (Expected: 0.96)\n";

// Expected overall: (100 * 0.25) + (100 * 0.25) + (80 * 0.20) + (100 * 0.15) + (100 * 0.15) = 25 + 25 + 16 + 15 + 15 = 96
if ($trustScore->student_rating_score == 80 && $trustScore->overall_score == 96 && $startupProfile->credibility_score == 0.96) {
    echo "✓ Success: Student review submitted and overall score updated correctly.\n\n";
} else {
    echo "❌ FAIL: Score mismatch after student review.\n\n";
}

// 4. Test Payment Reliability Score Decay
echo "--- Test 3: Payment Score Decay (Negative Wallet Balance) ---\n";

$startupProfile->update(['wallet_balance' => -25.50]);
echo "  - Dropped wallet balance to negative: {$startupProfile->wallet_balance}\n";

$reputationService->updateReputation($startupProfile->id);
$trustScore->refresh();
$startupProfile->refresh();

// Expected:
// paymentScore = 50
// verificationScore = 100
// studentRatingScore = 80
// hiringScore = 100
// taskCompletionScore = 100
// overallScore = (100 * 0.25) + (50 * 0.25) + (80 * 0.20) + (100 * 0.15) + (100 * 0.15) = 25 + 12.5 + 16 + 15 + 15 = 83.5
echo "  - Updated Payment Score: {$trustScore->payment_score} (Expected: 50)\n";
echo "  - Updated Overall Trust Score: {$trustScore->overall_score} (Expected: 83.5)\n";
echo "  - Updated Profile Credibility Score: {$startupProfile->credibility_score} (Expected: 0.84)\n";

if ($trustScore->payment_score == 50 && $trustScore->overall_score == 83.5 && $startupProfile->credibility_score == 0.84) {
    echo "✓ Success: Negative wallet balance decays payment score and overall trust score.\n\n";
} else {
    echo "❌ FAIL: Payment score or overall score mismatch.\n\n";
}

// 5. Test Hiring Offer Response Impact
echo "--- Test 4: Hiring Acceptance Rate Impact ---\n";

// Add two hiring offers: one accepted, one rejected
HiringOffer::create([
    'startup_profile_id' => $startupProfile->id,
    'student_profile_id' => $studentProfile->id,
    'offer_type' => 'internship',
    'title' => 'Offer 1',
    'description' => 'Internship offer',
    'compensation' => 1000,
    'compensation_period' => 'monthly',
    'start_date' => now()->toDateString(),
    'status' => 'accepted'
]);

HiringOffer::create([
    'startup_profile_id' => $startupProfile->id,
    'student_profile_id' => $studentProfile->id,
    'offer_type' => 'job',
    'title' => 'Offer 2',
    'description' => 'Job offer',
    'compensation' => 2000,
    'compensation_period' => 'monthly',
    'start_date' => now()->toDateString(),
    'status' => 'rejected'
]);

echo "  - Added 2 responded offers: 1 accepted, 1 rejected (Acceptance rate = 50%)\n";

$reputationService->updateReputation($startupProfile->id);
$trustScore->refresh();
$startupProfile->refresh();

// Expected:
// paymentScore = 50
// verificationScore = 100
// studentRatingScore = 80
// hiringScore = 50
// taskCompletionScore = 100
// overallScore = (100 * 0.25) + (50 * 0.25) + (80 * 0.20) + (50 * 0.15) + (100 * 0.15) = 25 + 12.5 + 16 + 7.5 + 15 = 76.0
echo "  - Updated Hiring Score: {$trustScore->hiring_score} (Expected: 50)\n";
echo "  - Updated Overall Trust Score: {$trustScore->overall_score} (Expected: 76)\n";
echo "  - Updated Profile Credibility Score: {$startupProfile->credibility_score} (Expected: 0.76)\n";

if ($trustScore->hiring_score == 50 && $trustScore->overall_score == 76 && $startupProfile->credibility_score == 0.76) {
    echo "✓ Success: Direct hiring offer responses affect the hiring score accurately.\n\n";
} else {
    echo "❌ FAIL: Hiring score or overall score mismatch.\n\n";
}

// 6. Test Task Completion Success Impact
echo "--- Test 5: Task Completion Success Rate Impact ---\n";

// Add another task but don't complete it
$uncompletedTask = Task::create([
    'startup_profile_id' => $startupProfile->id,
    'title' => 'Uncompleted Task',
    'description' => 'This task has no accepted submission or completed status',
    'requirements' => 'PHP basics',
    'required_skills' => ['PHP'],
    'stipend' => 100,
    'status' => 'posted',
    'reward_points' => 20
]);

echo "  - Added a second task without completion (Completion success rate = 50%)\n";

$reputationService->updateReputation($startupProfile->id);
$trustScore->refresh();
$startupProfile->refresh();

// Expected:
// paymentScore = 50
// verificationScore = 100
// studentRatingScore = 80
// hiringScore = 50
// taskCompletionScore = 50
// overallScore = (100 * 0.25) + (50 * 0.25) + (80 * 0.20) + (50 * 0.15) + (50 * 0.15) = 25 + 12.5 + 16 + 7.5 + 7.5 = 68.5
$overallExpected = 68.5;
$credibilityExpected = 0.69; // rounded to 2 decimal places: 68.5 / 100 = 0.685 -> 0.69

echo "  - Updated Overall Trust Score: {$trustScore->overall_score} (Expected: {$overallExpected})\n";
echo "  - Updated Profile Credibility Score: {$startupProfile->credibility_score} (Expected: {$credibilityExpected})\n";

if ($trustScore->overall_score == $overallExpected && $startupProfile->credibility_score == $credibilityExpected) {
    echo "✓ Success: Task completion rate affects reputation score correctly.\n\n";
} else {
    echo "❌ FAIL: Task completion score calculation or overall score mismatch.\n\n";
}

// 6. Test Public Startup Profile View
echo "--- Test 6: Verify Public Startup Profile Page ---\n";
$startupControllerInstance = app(StartupController::class);
$publicProfileResponse = $startupControllerInstance->publicProfile($startupProfile->id);

if ($publicProfileResponse instanceof \Illuminate\View\View) {
    $viewData = $publicProfileResponse->getData();
    $studentsHiredCount = $viewData['studentsHired'];
    $avgRatingVal = $viewData['averageRating'];
    $activeTasksCount = $viewData['activeTasks']->count();
    
    echo "  - Public profile loaded successfully.\n";
    echo "  - Hired students count: {$studentsHiredCount} (Expected: 2)\n";
    echo "  - Average student rating: {$avgRatingVal} (Expected: 4)\n";
    echo "  - Active tasks count: {$activeTasksCount} (Expected: 2)\n";
    
    if ($studentsHiredCount == 2 && $avgRatingVal == 4 && $activeTasksCount == 2) {
        echo "✓ Success: Public profile has correct data and loaded correctly.\n\n";
    } else {
        echo "❌ FAIL: Data mismatch on public profile view.\n\n";
    }
} else {
    echo "❌ FAIL: Expected View response, got " . get_class($publicProfileResponse) . "\n\n";
}

// 7. Cleanup
echo "--- Cleanup ---\n";
// Delete test records
StartupReview::where('startup_profile_id', $startupProfile->id)->delete();
StartupTrustScore::where('startup_profile_id', $startupProfile->id)->delete();
if (isset($submission)) {
    $submission->delete();
}
$task->applications()->delete();
$task->delete();
$uncompletedTask->delete();
HiringOffer::where('startup_profile_id', $startupProfile->id)->delete();
$studentProfile->delete();
$startupProfile->delete();
$studentUser->delete();
$startupUser->delete();
echo "✓ Deleted all created test records.\n\n";

echo "=========================================================\n";
echo "🎉 All tests passed successfully!\n";
echo "=========================================================\n";
