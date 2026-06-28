<?php

use App\Http\Controllers\{
    ProfileController, StudentController, StartupController, AdminController,
    TaskController, ApplicationController, SubmissionController,
    CertificateController, LeaderboardController, RatingController, MessageController,
    WalletController, AdminWalletController, WalletTopupController, ReportController,
    NotificationController, StartupReviewController, TalentProfileController, WithdrawalController, StartupTeamController,
    InternshipTaskController, PricingController, AdminSubscriptionController
};
use App\Http\Controllers\Auth\GoogleAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // ── Real counts ──
    $studentsCount  = \App\Models\StudentProfile::count();
    $startupsCount  = \App\Models\StartupProfile::count();
    $verifiedTasks  = \App\Models\Submission::where('status', 'accepted')->count();
    $totalTasks     = \App\Models\Task::count();

    // ── Top student (leaderboard #1) ──
    $topStudent = \App\Models\StudentProfile::with(['user', 'reputationScore', 'skills'])
        ->leftJoin('reputation_scores', 'student_profiles.id', '=', 'reputation_scores.student_profile_id')
        ->orderByRaw('COALESCE(reputation_scores.overall_score, 50.00) desc')
        ->select('student_profiles.*')
        ->first();

    // ── Real startup company names for marquee ──
    $startupNames = \App\Models\StartupProfile::whereNotNull('company_name')
        ->where('company_name', '!=', '')
        ->whereRaw('LOWER(company_name) NOT LIKE ?', ['%test%'])
        ->whereRaw('LOWER(company_name) NOT LIKE ?', ['%demo%'])
        ->whereRaw('LOWER(company_name) NOT LIKE ?', ['%dummy%'])
        ->whereRaw('LOWER(company_name) NOT LIKE ?', ['%example%'])
        ->pluck('company_name')
        ->toArray();

    // ── Latest real tasks for the "For Students" section ──
    $latestTasks = \App\Models\Task::with(['startup', 'skills'])
        ->orderBy('created_at', 'desc')
        ->limit(3)
        ->get();

    return view('welcome', compact(
        'studentsCount', 'startupsCount', 'verifiedTasks', 'totalTasks',
        'topStudent', 'startupNames', 'latestTasks'
    ));
});


Route::get('/test-landing', function () {
    $studentsCount = \App\Models\StudentProfile::count();
    $startupsCount = \App\Models\StartupProfile::count();
    $tasksCount = \App\Models\Task::count(); // Let's use total tasks as tasks count

    return view('welcome_test', compact('studentsCount', 'startupsCount', 'tasksCount'));
})->name('test-landing');

// Google OAuth routes
Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
Route::get('auth/google/role', [GoogleAuthController::class, 'showRoleSelection'])->name('auth.google.role');
Route::post('auth/google/complete', [GoogleAuthController::class, 'completeRegistration'])->name('auth.google.complete');

Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
Route::get('/students/{id}/profile', [StudentController::class, 'publicProfile'])->name('students.public-profile');
Route::get('/startups/{id}/profile', [StartupController::class, 'publicProfile'])->name('startups.public-profile');
Route::get('/verify-email/{token}', [StudentController::class, 'verifyEmail'])->name('verify.email');

// Public Talent Profile
Route::get('/talent/{slug}', [TalentProfileController::class, 'show'])->name('talent.profile');

// Footer Pages
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/contact', 'pages.contact')->name('contact');

// Report System
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/report/{type?}/{id?}', [ReportController::class, 'show'])->name('report.show');
    Route::post('/report', [ReportController::class, 'store'])->name('report.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->isStudent()) return redirect()->route('student.dashboard');
        if ($user->isStartup()) return redirect()->route('startup.dashboard');
        if ($user->isAdmin()) return redirect()->route('admin.dashboard');
    })->name('dashboard');

    // Student Routes
    Route::middleware(['role:student', 'onboarded'])->prefix('student')->name('student.')->group(function () {
        Route::get('/onboarding', [\App\Http\Controllers\StudentOnboardingController::class, 'show'])->name('onboarding');
        Route::post('/onboarding/step1', [\App\Http\Controllers\StudentOnboardingController::class, 'step1'])->name('onboarding.step1');
        Route::post('/onboarding/step2', [\App\Http\Controllers\StudentOnboardingController::class, 'step2'])->name('onboarding.step2');
        Route::post('/onboarding/step3', [\App\Http\Controllers\StudentOnboardingController::class, 'step3'])->name('onboarding.step3');

        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
        Route::post('/profile', [StudentController::class, 'updateProfile'])->name('profile.update');
        Route::get('/analytics', [StudentController::class, 'analytics'])->name('analytics');
        Route::get('/cv/download', [StudentController::class, 'downloadCV'])->name('cv.download');
        Route::post('/cv/settings', [StudentController::class, 'updateResumeSettings'])->name('cv.settings');
        Route::get('/verification', [StudentController::class, 'verification'])->name('verification');
        Route::post('/verification/send', [StudentController::class, 'sendVerification'])->middleware('throttle:3,60')->name('verification.send');
        Route::get('/verification/code', [StudentController::class, 'showVerificationCode'])->name('verification.code');
        Route::post('/verification/verify', [StudentController::class, 'verifyCode'])->middleware('throttle:10,1')->name('verification.verify');
        // ── College ID Card AI Verification ──────────────────────────────────
        Route::get('/verify-id', [StudentController::class, 'showIdVerification'])->name('verify-id');
        Route::post('/verify-id', [StudentController::class, 'submitIdVerification'])->middleware('throttle:3,60')->name('verify-id.submit');
        Route::get('/verify-id/status', [StudentController::class, 'verificationStatus'])->name('verify-id.status');
        // ─────────────────────────────────────────────────────────────────────
        Route::post('/tasks/{taskId}/review', [StartupReviewController::class, 'store'])->name('tasks.review');
        Route::post('/portfolio/{itemId}/evidence', [TalentProfileController::class, 'updateEvidence'])->name('portfolio.evidence');
        Route::post('/portfolio/project', [StudentController::class, 'storePortfolioItem'])->name('portfolio.project.store');
        Route::delete('/portfolio/project/{id}', [StudentController::class, 'deletePortfolioItem'])->name('portfolio.project.destroy');
        Route::get('/wallet/withdraw', [WithdrawalController::class, 'index'])->name('wallet.withdraw');
        Route::post('/wallet/withdraw', [WithdrawalController::class, 'store'])->name('wallet.withdraw.store');
        Route::get('/internship/{offer}/updates', [StudentController::class, 'listUpdates'])->name('internship.updates');
        Route::post('/internship/{offer}/updates', [StudentController::class, 'storeUpdate'])->name('internship.updates.store');
        Route::post('/internship/{offer}/reports', [StudentController::class, 'storeWeeklyReport'])->name('internship.reports.store');

        // New student internships center
        Route::get('/internships', [StudentController::class, 'internships'])->name('internships.index');
        Route::get('/internships/{id}/workspace', [StudentController::class, 'workspace'])->name('internships.workspace');
        Route::post('/internships/{offer}/tasks/{task}/submit', [InternshipTaskController::class, 'submit'])->name('internships.tasks.submit');

        // Premium locked routes
        Route::middleware('student.pro')->group(function() {
            Route::get('/coach', [StudentController::class, 'aiCoach'])->name('coach');
            Route::get('/mock-interviews', [StudentController::class, 'mockInterviews'])->name('mock');
            Route::get('/assessments', [StudentController::class, 'skillAssessments'])->name('assessments');
        });
    });

    // Startup Routes
    Route::middleware(['role:startup', 'onboarded'])->prefix('startup')->name('startup.')->group(function () {
        Route::get('/onboarding', [\App\Http\Controllers\StartupOnboardingController::class, 'show'])->name('onboarding');
        Route::post('/onboarding/step1', [\App\Http\Controllers\StartupOnboardingController::class, 'step1'])->name('onboarding.step1');
        Route::post('/onboarding/step2', [\App\Http\Controllers\StartupOnboardingController::class, 'step2'])->name('onboarding.step2');
        Route::post('/onboarding/step3', [\App\Http\Controllers\StartupOnboardingController::class, 'step3'])->name('onboarding.step3');

        Route::get('/dashboard', [StartupController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [StartupController::class, 'profile'])->name('profile');
        Route::post('/profile', [StartupController::class, 'updateProfile'])->name('profile.update');
        Route::get('/verification', [StartupController::class, 'verification'])->name('verification');
        Route::post('/verification', [StartupController::class, 'submitVerification'])->name('verification.submit');
        Route::post('/clear-verification-alert', [StartupController::class, 'clearVerificationAlert'])->name('clear-verification-alert');
        Route::post('/applications/{id}/approve', [ApplicationController::class, 'approve'])->name('applications.approve');
        Route::post('/applications/{id}/reject', [ApplicationController::class, 'reject'])->name('applications.reject');
        Route::post('/applications/{id}/status', [ApplicationController::class, 'updateStatus'])->name('applications.update-status');
        Route::post('/applications/{id}/close-task', [ApplicationController::class, 'closeTask'])->name('applications.close-task');
        Route::post('/applications/{id}/reject-hiring', [ApplicationController::class, 'rejectHiring'])->name('applications.reject-hiring');
        Route::post('/applications/{id}/rate-hiring-success', [ApplicationController::class, 'rateHiringSuccess'])->name('applications.rate-hiring-success');
        Route::get('/submissions/{id}/review', [SubmissionController::class, 'review'])->name('submissions.review');
        Route::post('/submissions/{id}/accept', [SubmissionController::class, 'accept'])->name('submissions.accept');
        Route::post('/submissions/{id}/reject', [SubmissionController::class, 'reject'])->name('submissions.reject');
        Route::post('/submissions/{id}/revision', [SubmissionController::class, 'requestRevision'])->name('submissions.revision');
        Route::post('/submissions/{id}/rate', [RatingController::class, 'store'])->name('submissions.rate');
        Route::post('/submissions/{id}/verify-skills', [SubmissionController::class, 'verifySkills'])->name('submissions.verify-skills');
        Route::get('/candidates', [StartupController::class, 'candidates'])->name('candidates');
        Route::post('/candidates/{id}/save', [StartupController::class, 'toggleSaveCandidate'])->name('candidates.save');

        // Startup Team Management Hub
        Route::get('/team', [StartupTeamController::class, 'index'])->name('team.index');
        Route::get('/team/{offer}/work', [StartupTeamController::class, 'viewWork'])->name('team.work');
        Route::get('/team/{offer}/reports', [StartupTeamController::class, 'viewReports'])->name('team.reports');
        Route::post('/team/reports/{report}/feedback', [StartupTeamController::class, 'submitWeeklyFeedback'])->name('team.reports.feedback');
        Route::get('/team/{offer}/convert', [StartupTeamController::class, 'showConversionForm'])->name('team.convert.form');
        Route::post('/team/{offer}/convert', [StartupTeamController::class, 'processConversion'])->name('team.convert');

        // Verified Task System
        Route::post('/team/{offer}/tasks', [InternshipTaskController::class, 'store'])->name('team.tasks.store');
        Route::put('/team/{offer}/tasks/{task}', [InternshipTaskController::class, 'update'])->name('team.tasks.update');
        Route::delete('/team/{offer}/tasks/{task}', [InternshipTaskController::class, 'destroy'])->name('team.tasks.destroy');
        Route::post('/team/{offer}/tasks/{task}/approve', [InternshipTaskController::class, 'approve'])->name('team.tasks.approve');
        Route::post('/team/{offer}/tasks/{task}/request-changes', [InternshipTaskController::class, 'requestChanges'])->name('team.tasks.request-changes');
        Route::post('/team/{offer}/resources', [InternshipTaskController::class, 'storeResource'])->name('team.resources.store');
        Route::delete('/team/{offer}/resources/{resource}', [InternshipTaskController::class, 'destroyResource'])->name('team.resources.destroy');
    });

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Startups Management
        Route::get('/startups', [AdminController::class, 'startups'])->name('startups');
        Route::post('/startups/{id}/approve', [AdminController::class, 'approveStartup'])->name('startups.approve');
        Route::get('/startups/create', [AdminController::class, 'createStartup'])->name('startups.create');
        Route::post('/startups', [AdminController::class, 'storeStartup'])->name('startups.store');
        Route::get('/startups/{id}/edit', [AdminController::class, 'editStartup'])->name('startups.edit');
        Route::put('/startups/{id}', [AdminController::class, 'updateStartup'])->name('startups.update');
        Route::delete('/startups/{id}', [AdminController::class, 'deleteStartup'])->name('startups.delete');
        
        // Students Management
        Route::get('/students', [AdminController::class, 'students'])->name('students');
        Route::get('/students/create', [AdminController::class, 'createStudent'])->name('students.create');
        Route::post('/students', [AdminController::class, 'storeStudent'])->name('students.store');
        Route::get('/students/{id}/edit', [AdminController::class, 'editStudent'])->name('students.edit');
        Route::put('/students/{id}', [AdminController::class, 'updateStudent'])->name('students.update');
        Route::delete('/students/{id}', [AdminController::class, 'deleteStudent'])->name('students.delete');
        
        // Tasks & Submissions
        Route::get('/tasks', [AdminController::class, 'tasks'])->name('tasks');
        Route::post('/tasks/{id}/moderate', [AdminController::class, 'moderateTask'])->name('tasks.moderate');
        Route::get('/submissions', [AdminController::class, 'submissions'])->name('submissions');
        
        // Wallet Management
        Route::get('/wallets', [AdminWalletController::class, 'manageWallets'])->name('wallets');
        Route::post('/wallets/add', [AdminWalletController::class, 'addMoney'])->name('wallet.add');
        Route::post('/wallets/deduct', [AdminWalletController::class, 'deductMoney'])->name('wallet.deduct');

        // Top-up Requests
        Route::get('/topup', [WalletTopupController::class, 'adminIndex'])->name('topup.index');
        Route::post('/topup/{id}/approve', [WalletTopupController::class, 'approve'])->name('topup.approve');
        Route::post('/topup/{id}/reject', [WalletTopupController::class, 'reject'])->name('topup.reject');
        
        // Verification Management (Startups)
        Route::get('/verifications', [AdminController::class, 'verifications'])->name('verifications');
        Route::post('/verifications/{id}/approve', [AdminController::class, 'approveVerification'])->name('verifications.approve');
        Route::post('/verifications/{id}/reject', [AdminController::class, 'rejectVerification'])->name('verifications.reject');
        Route::post('/verifications/{id}/toggle-suspicious', [AdminController::class, 'toggleSuspiciousFlag'])->name('verifications.toggle-suspicious');

        // Student ID Card AI Review Queue
        Route::get('/student-id-queue', [AdminController::class, 'studentIdQueue'])->name('student-id-queue');
        Route::get('/student-id-queue/{id}/id-card', [AdminController::class, 'viewStudentIdCard'])->name('student-id-queue.id-card');
        Route::post('/student-id-queue/{id}/approve', [AdminController::class, 'approveStudentId'])->name('student-id-queue.approve');
        Route::post('/student-id-queue/{id}/reject', [AdminController::class, 'rejectStudentId'])->name('student-id-queue.reject');

        // AI Debug Interface
        Route::get('/ai-debug', [\App\Http\Controllers\AIDebugController::class, 'index'])->name('ai-debug');
        Route::post('/ai-debug/test', [\App\Http\Controllers\AIDebugController::class, 'test'])->name('ai-debug.test');

        // Export Routes
        Route::get('/analytics/export/revenue', [AdminController::class, 'exportRevenue'])->name('analytics.export.revenue');
        Route::get('/analytics/export/hiring', [AdminController::class, 'exportHiring'])->name('analytics.export.hiring');
        Route::get('/analytics/export/users', [AdminController::class, 'exportUsers'])->name('analytics.export.users');

        // Student Withdrawals Management
        Route::get('/withdrawals', [WithdrawalController::class, 'adminIndex'])->name('withdrawals.index');
        Route::post('/withdrawals/{id}/approve', [WithdrawalController::class, 'adminApprove'])->name('withdrawals.approve');
        Route::post('/withdrawals/{id}/reject', [WithdrawalController::class, 'adminReject'])->name('withdrawals.reject');

        // Circumvention Auditor Routes
        Route::get('/circumvention', [AdminController::class, 'circumventionIndex'])->name('circumvention');
        Route::post('/circumvention/{id}/audit', [AdminController::class, 'markAudited'])->name('circumvention.audit');
        Route::post('/circumvention/{id}/penalize', [AdminController::class, 'chargeBypassPenalty'])->name('circumvention.penalize');
    });

    // Shared Routes
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create')->middleware('role:startup');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store')->middleware('role:startup');
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit')->middleware('role:startup');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update')->middleware('role:startup');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy')->middleware('role:startup');
    Route::post('/tasks/{taskId}/apply', [ApplicationController::class, 'store'])->name('applications.store')->middleware('role:student');
    Route::get('/applications/{applicationId}/submit', [SubmissionController::class, 'create'])->name('submissions.create')->middleware('role:student');
    Route::post('/applications/{applicationId}/submit', [SubmissionController::class, 'store'])->name('submissions.store')->middleware('role:student');
    Route::get('/submissions/{id}/revise', [SubmissionController::class, 'revise'])->name('submissions.revise')->middleware('role:student');
    Route::post('/submissions/{id}/revise', [SubmissionController::class, 'updateRevision'])->name('submissions.update')->middleware('role:student');
    
    // Messaging Routes
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{id}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{id}', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/start/{studentId}/{startupId}/{taskId?}', [MessageController::class, 'create'])->name('messages.create');
    
    // Wallet Routes
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::middleware('role:startup')->group(function () {
        Route::get('/wallet/topup', [WalletTopupController::class, 'create'])->name('wallet.topup');
        Route::post('/wallet/topup', [WalletTopupController::class, 'store'])->name('wallet.topup.store');
    });

    // Notification Routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/dropdown', [NotificationController::class, 'dropdown'])->name('dropdown');
        Route::post('/{id}/read', [NotificationController::class, 'markRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllRead'])->name('read-all');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/', [NotificationController::class, 'destroyAll'])->name('destroy-all');
    });

    // Direct Hiring Pipeline Routes
    Route::post('/startup/offers', [App\Http\Controllers\HiringOfferController::class, 'store'])->name('startup.offers.store')->middleware('role:startup');
    Route::post('/startup/offers/{id}/withdraw', [App\Http\Controllers\HiringOfferController::class, 'withdraw'])->name('startup.offers.withdraw')->middleware('role:startup');
    Route::post('/startup/offers/{id}/counter/accept', [App\Http\Controllers\HiringOfferController::class, 'acceptCounter'])->name('startup.offers.counter.accept')->middleware('role:startup');
    Route::post('/startup/offers/{id}/counter/reject', [App\Http\Controllers\HiringOfferController::class, 'rejectCounter'])->name('startup.offers.counter.reject')->middleware('role:startup');
    Route::post('/student/offers/{id}/accept', [App\Http\Controllers\HiringOfferController::class, 'accept'])->name('student.offers.accept')->middleware('role:student');
    Route::post('/student/offers/{id}/reject', [App\Http\Controllers\HiringOfferController::class, 'reject'])->name('student.offers.reject')->middleware('role:student');
    Route::post('/student/offers/{id}/counter', [App\Http\Controllers\HiringOfferController::class, 'counterOffer'])->name('student.offers.counter')->middleware('role:student');
    Route::post('/offers/{id}/confirm-joining', [App\Http\Controllers\HiringOfferController::class, 'confirmJoining'])->name('offers.confirm-joining');
    Route::post('/offers/{id}/cancel-joining', [App\Http\Controllers\HiringOfferController::class, 'cancelJoining'])->name('offers.cancel-joining');
    Route::post('/offers/{id}/complete-internship', [App\Http\Controllers\HiringOfferController::class, 'completeInternship'])->name('offers.complete-internship')->middleware('role:startup');

    // Interview Routes
    Route::post('/startup/interviews/schedule/{conversationId}', [App\Http\Controllers\InterviewController::class, 'store'])->name('startup.interviews.store')->middleware('role:startup');
    Route::post('/student/interviews/{id}/accept', [App\Http\Controllers\InterviewController::class, 'accept'])->name('student.interviews.accept')->middleware('role:student');
    Route::post('/student/interviews/{id}/reject', [App\Http\Controllers\InterviewController::class, 'reject'])->name('student.interviews.reject')->middleware('role:student');
    Route::post('/startup/interviews/{id}/cancel', [App\Http\Controllers\InterviewController::class, 'cancel'])->name('startup.interviews.cancel')->middleware('role:startup');
    Route::post('/startup/interviews/{id}/complete', [App\Http\Controllers\InterviewController::class, 'complete'])->name('startup.interviews.complete')->middleware('role:startup');
    Route::post('/startup/interviews/{id}/noshow', [App\Http\Controllers\InterviewController::class, 'noShow'])->name('startup.interviews.noshow')->middleware('role:startup');
    Route::post('/student/interviews/{id}/noshow', [App\Http\Controllers\InterviewController::class, 'studentNoShow'])->name('student.interviews.noshow')->middleware('role:student');
    // Pricing Page Routes
    Route::get('/pricing', [PricingController::class, 'index'])->name('pricing.index');
    Route::post('/pricing/upgrade', [PricingController::class, 'upgrade'])->name('pricing.upgrade')->middleware('auth');
    Route::post('/pricing/cancel', [PricingController::class, 'cancel'])->name('pricing.cancel')->middleware('auth');

    // Admin Subscription/Revenue Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/revenue', [AdminSubscriptionController::class, 'revenue'])->name('revenue');
        Route::get('/subscriptions', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::post('/subscriptions/manual-upgrade', [AdminSubscriptionController::class, 'manualUpgrade'])->name('subscriptions.manual-upgrade');
    });
});

// Experience Certificates Public Routes
Route::get('/certificates/verify/{certificateNumber}', [App\Http\Controllers\CertificateController::class, 'verify'])->name('certificates.verify');
Route::get('/certificates/{id}/download', [App\Http\Controllers\CertificateController::class, 'download'])->name('certificates.download');

require __DIR__.'/auth.php';

Route::get('/view-production-logs', function() {
    if (!auth()->check() || auth()->user()->email !== 'admin@interngrowth.com') {
        abort(403);
    }
    $path = storage_path('logs/laravel.log');
    if (!file_exists($path)) {
        return 'Log file does not exist';
    }
    $content = file_get_contents($path);
    $lines = explode("\n", $content);
    $lastLines = array_slice($lines, -200);
    return '<pre>' . implode("\n", $lastLines) . '</pre>';
});


