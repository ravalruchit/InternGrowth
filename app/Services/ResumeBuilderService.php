<?php

namespace App\Services;

use App\Models\StudentProfile;
use App\Models\Application;
use App\Models\Rating;

class ResumeBuilderService
{
    /**
     * Generate the professional summary sentence or paragraph.
     */
    public function generateProfessionalSummary(StudentProfile $profile): string
    {
        if ($profile->bio) {
            return $profile->bio;
        }

        $degree = $profile->degree_name ?: 'Student';
        $college = $profile->college_name ?: 'university';
        $domain = $profile->primary_domain ?: 'Software Development';

        $skills = $profile->skills->take(4)->pluck('name')->toArray();
        $skillsStr = count($skills) > 0 ? implode(', ', $skills) : 'modern tech stacks';

        $completedTasksCount = Application::where('student_profile_id', $profile->id)
            ->whereHas('submission', function ($q) {
                $q->where('status', 'accepted');
            })
            ->count();

        $iprsScore = $profile->reputationScore ? round($profile->reputationScore->overall_score) : 50;

        if ($completedTasksCount > 0) {
            return "Detail-oriented {$degree} at {$college} specializing in {$domain} with core skills in {$skillsStr}. Completed {$completedTasksCount} verified startup task(s) through InternGrowth with an IPRS score of {$iprsScore}, demonstrating a strong capability to deliver production-ready code under real industry deadlines.";
        }

        return "Motivated {$degree} at {$college} specializing in {$domain} with key skills in {$skillsStr}. Eager to apply theoretical knowledge to solve real-world industry challenges and build high-quality solutions.";
    }

    /**
     * Group task experiences by startup.
     */
    public function groupExperiencesByStartup(StudentProfile $profile, bool $hideLowRated = false): array
    {
        // Get all completed/accepted tasks for the student
        $applications = Application::where('student_profile_id', $profile->id)
            ->with(['task.startup', 'submission'])
            ->whereHas('submission', function ($q) {
                $q->where('status', 'accepted');
            })
            ->get();

        $grouped = [];

        foreach ($applications as $app) {
            $task = $app->task;
            if (!$task || !$task->startup) {
                continue;
            }

            // Find rating
            $rating = Rating::where('task_id', $task->id)
                ->where('student_profile_id', $profile->id)
                ->first();

            // Filter out if requested to hide low rated (e.g., rating less than 4 stars)
            if ($hideLowRated && $rating && $rating->rating < 4.0) {
                continue;
            }

            $startupId = $task->startup_profile_id;
            $companyName = $task->startup->company_name ?: 'Startup Partner';

            if (!isset($grouped[$startupId])) {
                $grouped[$startupId] = [
                    'company_name' => $companyName,
                    'role' => $profile->professional_title ?: (($profile->primary_domain ?: 'Freelance') . ' Developer'),
                    'min_date' => $app->submission->updated_at,
                    'max_date' => $app->submission->updated_at,
                    'projects' => [],
                    'ratings' => [],
                    'total_stipend' => 0,
                    'is_verified' => true,
                ];
            }

            // Adjust min/max dates
            if ($app->submission->updated_at->lt($grouped[$startupId]['min_date'])) {
                $grouped[$startupId]['min_date'] = $app->submission->updated_at;
            }
            if ($app->submission->updated_at->gt($grouped[$startupId]['max_date'])) {
                $grouped[$startupId]['max_date'] = $app->submission->updated_at;
            }

            // Add stipend
            $grouped[$startupId]['total_stipend'] += ($task->stipend ?: 0);

            // Add rating
            if ($rating) {
                $grouped[$startupId]['ratings'][] = $rating->rating;
            }

            // Compile project details
            $grouped[$startupId]['projects'][] = [
                'title' => $task->title,
                'description' => $task->description ?: 'Completed project requirements according to specifications.',
                'stipend' => $task->stipend,
                'rating' => $rating ? $rating->rating : null,
                'completed_at' => $app->submission->updated_at,
            ];
        }

        // Format experience timelines and compute averages
        $experiences = [];
        foreach ($grouped as $startupId => $data) {
            $avgRating = count($data['ratings']) > 0 ? (array_sum($data['ratings']) / count($data['ratings'])) : null;
            $experiences[] = [
                'company_name' => $data['company_name'],
                'role' => $data['role'] . ' (InternGrowth Verified Projects)',
                'duration' => $data['min_date']->format('M Y') . ' – ' . $data['max_date']->format('M Y'),
                'projects' => $data['projects'],
                'avg_rating' => $avgRating,
                'total_stipend' => $data['total_stipend'],
                'is_verified' => $data['is_verified']
            ];
        }

        return $experiences;
    }

    /**
     * Compute automated achievements metrics.
     */
    public function calculateAchievements(StudentProfile $profile): array
    {
        $iprsScore = $profile->reputationScore ? round($profile->reputationScore->overall_score) : 50;

        // IPRS Rank
        $iprsRank = 'Active Participant';
        if ($iprsScore >= 90) {
            $iprsRank = 'Top 5% IPRS Rank';
        } elseif ($iprsScore >= 80) {
            $iprsRank = 'Top 10% IPRS Rank';
        } elseif ($iprsScore >= 70) {
            $iprsRank = 'Top 20% IPRS Rank';
        } elseif ($iprsScore >= 50) {
            $iprsRank = 'Top 50% IPRS Rank';
        }

        // Completed tasks
        $completedTasksCount = Application::where('student_profile_id', $profile->id)
            ->whereHas('submission', function ($q) {
                $q->where('status', 'accepted');
            })
            ->count();

        // Ratings
        $ratings = Rating::where('student_profile_id', $profile->id)->pluck('rating')->toArray();
        $avgRating = count($ratings) > 0 ? round(array_sum($ratings) / count($ratings), 1) : null;

        // Total stipend
        $stipends = Application::where('student_profile_id', $profile->id)
            ->with('task')
            ->whereHas('submission', function ($q) {
                $q->where('status', 'accepted');
            })
            ->get()
            ->sum(function ($app) {
                return $app->task->stipend ?? 0;
            });

        // Skill badges
        $badges = [];
        if ($completedTasksCount >= 10 && $iprsScore >= 85) {
            $badges[] = "Elite Builder Badge";
        }
        if ($stipends >= 5000) {
            $badges[] = "Premium Earner Certificate";
        }

        $skills = $profile->skills->take(2)->pluck('name')->toArray();
        foreach ($skills as $skill) {
            $badges[] = "Verified {$skill} Developer";
        }

        return [
            'iprs_rank' => $iprsRank,
            'iprs_score' => $iprsScore,
            'completed_tasks_count' => $completedTasksCount,
            'average_rating' => $avgRating,
            'total_earnings' => $stipends,
            'badges' => $badges,
        ];
    }

    /**
     * Categorize skills by domains.
     */
    public function buildSkillSections(StudentProfile $profile): array
    {
        $skills = $profile->skills;
        
        $categories = [
            'Languages' => [],
            'Frameworks' => [],
            'Databases' => [],
            'Tools & Others' => [],
        ];

        // Mapping simple categories based on name matching
        $languages = ['php', 'python', 'javascript', 'typescript', 'java', 'c++', 'c', 'ruby', 'go', 'rust', 'html', 'css', 'sql'];
        $frameworks = ['laravel', 'react', 'vue', 'angular', 'django', 'flask', 'next.js', 'express', 'spring', 'bootstrap', 'tailwind'];
        $databases = ['mysql', 'postgresql', 'mongodb', 'sqlite', 'redis', 'oracle', 'mariadb'];

        foreach ($skills as $skill) {
            $nameLower = strtolower($skill->name);
            if (in_array($nameLower, $languages)) {
                $categories['Languages'][] = $skill->name;
            } elseif (in_array($nameLower, $frameworks) || str_contains($nameLower, 'js') || str_contains($nameLower, 'css')) {
                $categories['Frameworks'][] = $skill->name;
            } elseif (in_array($nameLower, $databases) || str_contains($nameLower, 'db')) {
                $categories['Databases'][] = $skill->name;
            } else {
                $categories['Tools & Others'][] = $skill->name;
            }
        }

        // Clean empty categories
        return array_filter($categories, function ($list) {
            return count($list) > 0;
        });
    }
}
