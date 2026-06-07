<?php

namespace App\Services;

use App\Models\Submission;
use App\Models\Portfolio;
use App\Models\PortfolioItem;
use App\Models\Certificate;
use Illuminate\Support\Str;

class PortfolioAutomationService
{
    public function addVerifiedTaskToPortfolio(int $submissionId): PortfolioItem
    {
        $submission = Submission::with(['application.student.user', 'application.task.startup'])->findOrFail($submissionId);
        $student = $submission->application->student;
        $task = $submission->application->task;
        $startup = $task->startup;

        // 1. Ensure Portfolio exists
        $portfolio = Portfolio::firstOrCreate(
            ['student_profile_id' => $student->id],
            [
                'custom_slug' => Str::slug($student->user->name ?? 'student') . '-' . $student->id,
                'is_public' => true
            ]
        );

        // 2. Fetch skills demonstrated
        $skills = is_array($task->required_skills) 
            ? $task->required_skills 
            : json_decode($task->required_skills ?? '[]', true);

        // 3. Find certificate number if issued
        $cert = Certificate::where('student_profile_id', $student->id)
            ->where('task_id', $task->id)
            ->first();

        // 4. Retrieve startup rating if already exists
        $rating = \App\Models\Rating::where('student_profile_id', $student->id)
            ->where('task_id', $task->id)
            ->first();

        // 5. Determine verification badge
        $badge = $this->determineBadge($rating, $cert);

        // 6. Compose default auto-summary
        $skillsText = implode(', ', $skills);
        $companyName = $startup->company_name ?? 'Startup Partner';
        $summary = "Successfully completed development task '{$task->title}' for {$companyName}, demonstrating hands-on expertise in: {$skillsText}.";

        // 7. Extract screenshots from submission files (filter image types)
        $screenshots = $this->extractScreenshots($submission);

        return PortfolioItem::updateOrCreate(
            [
                'portfolio_id' => $portfolio->id,
                'task_id' => $task->id,
            ],
            [
                'submission_id' => $submission->id,
                'project_title' => $task->title,
                'auto_summary' => $summary,
                'skills_demonstrated' => $skills,
                'rating_received' => $rating ? $rating->rating : null,
                'startup_name' => $companyName,
                'certificate_number' => $cert ? $cert->certificate_number : null,
                'is_featured' => false,
                'verification_badge' => $badge,
                'completed_at' => now(),
                'screenshots' => $screenshots,
            ]
        );
    }

    public function updateRatingOnPortfolioItem(int $studentProfileId, int $taskId, float $rating): ?PortfolioItem
    {
        $portfolio = Portfolio::where('student_profile_id', $studentProfileId)->first();
        if (!$portfolio) {
            return null;
        }

        $item = PortfolioItem::where('portfolio_id', $portfolio->id)
            ->where('task_id', $taskId)
            ->first();

        if ($item) {
            // Update rating
            $item->update(['rating_received' => $rating]);

            // Re-evaluate badge based on new rating
            $cert = Certificate::where('student_profile_id', $studentProfileId)
                ->where('task_id', $taskId)
                ->first();

            $ratingObj = (object)['rating' => $rating];
            $newBadge = $this->determineBadge($ratingObj, $cert);

            // Only upgrade badge, never downgrade from 'featured'
            if ($item->verification_badge !== 'featured') {
                $item->update(['verification_badge' => $newBadge]);
            }
        }

        return $item;
    }

    /**
     * Update project evidence (GitHub/demo links) for a portfolio item.
     */
    public function updateProjectEvidence(int $portfolioItemId, ?string $githubUrl, ?string $demoUrl): ?PortfolioItem
    {
        $item = PortfolioItem::find($portfolioItemId);
        if (!$item) {
            return null;
        }

        $item->update([
            'github_url' => $githubUrl,
            'demo_url' => $demoUrl,
        ]);

        return $item;
    }

    /**
     * Determine the best verification badge based on rating and certificate.
     */
    private function determineBadge($rating, $cert): string
    {
        // Outstanding Performance: rating >= 4.5
        if ($rating && $rating->rating >= 4.5) {
            return 'outstanding_performance';
        }

        // Certified: has a certificate
        if ($cert) {
            return 'certified';
        }

        // Default: verified project
        return 'verified_project';
    }

    /**
     * Extract image-type files from submission as screenshots.
     */
    private function extractScreenshots(Submission $submission): ?array
    {
        if (!$submission->files || !is_array($submission->files)) {
            return null;
        }

        $imageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        $screenshots = [];

        foreach ($submission->files as $file) {
            if (is_array($file) && isset($file['type']) && in_array($file['type'], $imageTypes)) {
                $screenshots[] = [
                    'name' => $file['name'] ?? 'screenshot',
                    'path' => $file['path'] ?? null,
                ];
            }
        }

        return !empty($screenshots) ? $screenshots : null;
    }
}
