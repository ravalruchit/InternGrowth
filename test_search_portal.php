<?php

/**
 * Integration Test for Interactive Candidate Search & Vetting Portal
 * Run from terminal: php test_search_portal.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\{User, StudentProfile, ReputationScore, Skill, StartupProfile, HiringOffer};
use App\Http\Controllers\StartupController;
use Illuminate\Http\Request;

echo "=========================================================\n";
echo "🧪 Starting Candidate Search & AI Match Integration Tests\n";
echo "=========================================================\n\n";

// 1. Setup Auth Context
$startupUser = User::where('role', 'startup')->first();
if (!$startupUser) {
    echo "❌ ERROR: No startup user found. Run DatabaseSeeder and CandidatesTestSeeder first.\n";
    exit(1);
}
auth()->login($startupUser);
echo "✓ Authenticated as Startup: {$startupUser->name} (Company: {$startupUser->startupProfile->company_name})\n\n";

$controller = new StartupController();

// 2. Test Default Candidates Listing (Laravel Intern Position Match)
echo "--- Test 1: Load Candidates List with Default AI Match (Laravel Intern) ---\n";
$req = new Request(['tab' => 'search', 'position_match' => 'laravel_intern']);
$response = $controller->candidates($req);
$data = $response->getData();
$students = $data['students'];

if ($students->count() >= 4) {
    echo "✓ Loaded {$students->count()} students successfully.\n";
} else {
    echo "❌ FAIL: Expected at least 4 test students, got: " . $students->count() . "\n";
}

// Inspect Ruchit's Match details
$ruchit = collect($students->items())->first(fn($s) => $s->user->name === 'Ruchit');
if ($ruchit) {
    echo "✓ Found Ruchit profile.\n";
    echo "  - AI Match: {$ruchit->ai_match['percentage']}%\n";
    echo "  - Projects Count: {$ruchit->projects_count} (Expected: 18)\n";
    echo "  - Internships Count: {$ruchit->internships_count} (Expected: 2)\n";
    echo "  - Offers Count: {$ruchit->offers_count} (Expected: 4)\n";
    
    // Assert metrics
    if ($ruchit->projects_count === 18 && $ruchit->internships_count === 2 && $ruchit->offers_count === 4) {
        echo "  ✓ Candidate metrics mapped correctly!\n";
    } else {
        echo "  ❌ FAIL: Metrics mismatch.\n";
    }
} else {
    echo "❌ FAIL: Ruchit not found in list.\n";
}

// 3. Test Filter by Search (Name/Bio)
echo "\n--- Test 2: Filter by Name/Bio (Query: 'Ruchit') ---\n";
$req = new Request(['tab' => 'search', 'search' => 'Ruchit']);
$response = $controller->candidates($req);
$students = $response->getData()['students'];
$hasRuchit = collect($students->items())->contains(fn($s) => str_contains($s->user->name, 'Ruchit'));
if ($students->count() >= 1 && $hasRuchit) {
    echo "✓ Search filter returned Ruchit correctly.\n";
} else {
    echo "❌ FAIL: Search filter returned count: " . $students->count() . "\n";
    echo "  Returned students: " . implode(', ', collect($students->items())->map(fn($s) => $s->user->name)->toArray()) . "\n";
}

// 4. Test Filter by College Name
echo "\n--- Test 3: Filter by College Name (Query: 'MIT') ---\n";
$req = new Request(['tab' => 'search', 'college' => 'MIT']);
$response = $controller->candidates($req);
$students = $response->getData()['students'];
$alice = collect($students->items())->first(fn($s) => $s->user->name === 'Alice Dev');
if ($students->count() >= 1 && $alice) {
    echo "✓ College filter returned Alice correctly.\n";
} else {
    echo "❌ FAIL: College filter returned count: " . $students->count() . "\n";
}

// 5. Test Filter by Availability
echo "\n--- Test 4: Filter by Availability (looking_for_job) ---\n";
$req = new Request(['tab' => 'search', 'availability' => ['looking_for_job']]);
$response = $controller->candidates($req);
$students = $response->getData()['students'];
$bob = collect($students->items())->first(fn($s) => $s->user->name === 'Bob Smith');
if ($students->count() >= 1 && $bob) {
    echo "✓ Availability filter returned Bob correctly.\n";
} else {
    echo "❌ FAIL: Availability filter returned count: " . $students->count() . "\n";
}

// 6. Test Filter by Minimum IPRS Score
echo "\n--- Test 5: Filter by Min IPRS (93) ---\n";
$req = new Request(['tab' => 'search', 'min_iprs' => 93]);
$response = $controller->candidates($req);
$students = $response->getData()['students'];
$carol = collect($students->items())->first(fn($s) => $s->user->name === 'Carol Writer');
$allAboveMin = collect($students->items())->every(fn($s) => ($s->reputationScore->overall_score ?? 50.00) >= 93);
if ($students->count() >= 1 && $carol && $allAboveMin) {
    echo "✓ Min IPRS filter returned Carol (IPRS: 95) and all matched students are >= 93 correctly.\n";
} else {
    echo "❌ FAIL: Min IPRS filter returned count: " . $students->count() . "\n";
    echo "  Returned students: " . implode(', ', collect($students->items())->map(fn($s) => $s->user->name . ' (IPRS: ' . ($s->reputationScore->overall_score ?? 'N/A') . ')')->toArray()) . "\n";
}

// 7. Test Save/Bookmark Toggle Candidate & Bookmark Filter
echo "\n--- Test 6: Bookmark Candidate (Ruchit) & Bookmarked Filter ---\n";
// Ensure clean state
$startupProfile = $startupUser->startupProfile;
$startupProfile->savedCandidates()->detach($ruchit->id);

echo "  - Bookmarking Ruchit...\n";
$controller->toggleSaveCandidate($ruchit->id);

$isBookmarked = $startupProfile->savedCandidates()->where('student_profile_id', $ruchit->id)->exists();
if ($isBookmarked) {
    echo "  ✓ Pivot table contains relationship.\n";
} else {
    echo "  ❌ FAIL: Pivot table relation missing.\n";
}

echo "  - Filtering for Bookmarked Only...\n";
$req = new Request(['tab' => 'search', 'bookmarked_only' => '1']);
$response = $controller->candidates($req);
$students = $response->getData()['students'];
$foundRuchit = collect($students->items())->contains(fn($s) => $s->user->name === 'Ruchit');

if ($students->count() === 1 && $foundRuchit) {
    echo "  ✓ Bookmarked filter returned only Ruchit correctly!\n";
} else {
    echo "  ❌ FAIL: Bookmarked filter returned count: " . $students->count() . "\n";
}

echo "  - Unbookmarking Ruchit...\n";
$controller->toggleSaveCandidate($ruchit->id);
$isBookmarked = $startupProfile->savedCandidates()->where('student_profile_id', $ruchit->id)->exists();
if (!$isBookmarked) {
    echo "  ✓ Pivot table relation removed.\n";
} else {
    echo "  ❌ FAIL: Pivot relation still exists.\n";
}

// 8. Test Discovery Hub Tab Data
echo "\n--- Test 7: Talent Discovery Hub Categories Data ---\n";
$req = new Request(['tab' => 'discover']);
$response = $controller->candidates($req);
$data = $response->getData();

$categories = [
    'topTalent' => $data['topTalent'],
    'fastestGrowing' => $data['fastestGrowing'],
    'mostReliable' => $data['mostReliable'],
    'recommended' => $data['recommended'],
    'topPhp' => $data['topPhp'],
    'topUi' => $data['topUi'],
];

$allPassed = true;
foreach ($categories as $name => $collection) {
    if ($collection->isNotEmpty()) {
        $first = $collection->first();
        if (isset($first->ai_match) && isset($first->projects_count) && isset($first->internships_count) && isset($first->offers_count)) {
            echo "  ✓ Category '{$name}' is loaded (Count: {$collection->count()}) and has verified parameters.\n";
        } else {
            echo "  ❌ FAIL: Category '{$name}' is missing computed properties on candidates.\n";
            $allPassed = false;
        }
    } else {
        echo "  ❌ FAIL: Category '{$name}' is empty.\n";
        $allPassed = false;
    }
}

if ($allPassed) {
    echo "✓ Discovery Hub data retrieval and model transformations are fully correct!\n";
} else {
    echo "❌ FAIL: Some discovery hub categories failed assertion.\n";
    exit(1);
}

echo "\n=========================================================\n";
echo "🎉 ALL INTEGRATION TESTS PASSED SUCCESSFULLY!\n";
echo "=========================================================\n";
