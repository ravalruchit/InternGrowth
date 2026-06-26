<?php

namespace App\Services;

use App\Models\Task;
use App\Models\StudentProfile;
use App\Models\User;

class MatchingService
{
    public function getRecommendedTasksForStudent($studentProfile, $limit = 10, $studentProfileId = null)
    {
        // Get all posted tasks (not closed, moderated, or completed)
        $tasksQuery = Task::where('status', 'posted')
            ->with(['startup.user', 'skills']);
        
        // Exclude tasks the student has already applied to
        if ($studentProfileId) {
            $tasksQuery->whereDoesntHave('applications', function($query) use ($studentProfileId) {
                $query->where('student_profile_id', $studentProfileId);
            });
        }
        
        // Exclude tasks that already have an approved application (someone else is working on it)
        $tasksQuery->whereDoesntHave('applications', function($query) {
            $query->where('status', 'approved');
        });
        
        // Exclude completed tasks
        $tasksQuery->where('status', '!=', 'completed');
        
        $tasks = $tasksQuery->get();
        
        // If no tasks, return empty collection
        if ($tasks->isEmpty()) {
            return collect([]);
        }
        
        // Calculate match scores with honest skill gate enforcement
        $rankingService = app(\App\Services\CandidateRankingService::class);

        // First pass: get tasks that PASS the skill gate (honest matches)
        $qualifiedTasks = collect();
        $exploreTasks = collect();

        foreach ($tasks as $task) {
            // Try with skill gate ON (honest matching)
            $ranking = $rankingService->calculateMatchScore($studentProfile, $task, false);
            if ($ranking !== null) {
                $task->match_score = $ranking['match_score'];
                $task->match_details = $ranking;
                $qualifiedTasks->push($task);
            } else {
                // Task failed the skill gate — save for backfill
                $ranking = $rankingService->calculateMatchScore($studentProfile, $task, true);
                if ($ranking !== null) {
                    // Cap the score and label for gate-failed tasks
                    $ranking['match_label'] = 'Explore';
                    $ranking['passes_gate'] = false;
                    $task->match_score = $ranking['match_score'];
                    $task->match_details = $ranking;
                    $exploreTasks->push($task);
                }
            }
        }

        // Sort qualified tasks by score (best first)
        $qualifiedTasks = $qualifiedTasks->sortByDesc('match_score');

        // If we have enough qualified tasks, return them
        if ($qualifiedTasks->count() >= $limit) {
            return $qualifiedTasks->take($limit);
        }

        // Backfill with "Explore" tasks if dashboard would be too empty
        $exploreTasks = $exploreTasks->sortByDesc('match_score');
        $merged = $qualifiedTasks->merge($exploreTasks)->take($limit);

        return $merged;
    }


    public function getRecommendedStudentsForTask($task, $limit = 10)
    {
        $rankingService = app(\App\Services\CandidateRankingService::class);
        
        $students = StudentProfile::with(['user', 'skills', 'reputationScore', 'portfolio.items', 'skillVerifications.skill', 'hiringOffers', 'applications.submission'])
            ->get()
            ->map(function ($student) use ($task, $rankingService) {
                $ranking = $rankingService->calculateMatchScore($student, $task);
                if ($ranking === null) {
                    return null;
                }
                $student->match_score = $ranking['match_score'];
                $student->match_details = $ranking;
                return $student;
            })
            ->filter()
            ->sortByDesc('match_score')
            ->take($limit);
        
        return $students;
    }

    private function calculateTaskMatchScore($task, $studentProfileOrSkills)
    {
        $score = 0;
        $studentSkills = [];
        $studentDomain = null;
        $studentRole = null;

        if ($studentProfileOrSkills instanceof \App\Models\StudentProfile) {
            $studentDomain = $studentProfileOrSkills->primary_domain;
            $studentRole = $studentProfileOrSkills->preferred_role;
            if ($studentProfileOrSkills->skills instanceof \Illuminate\Database\Eloquent\Collection) {
                $studentSkills = $studentProfileOrSkills->skills->pluck('name')->toArray();
            } elseif (is_array($studentProfileOrSkills->skills)) {
                $studentSkills = $studentProfileOrSkills->skills;
            } else {
                $studentSkills = json_decode($studentProfileOrSkills->skills ?? '[]', true);
            }
        } else {
            $studentSkills = $studentProfileOrSkills;
        }
        
        // Handle both JSON string and array
        $taskSkills = is_array($task->required_skills) 
            ? $task->required_skills 
            : json_decode($task->required_skills ?? '[]', true);
        
        if (empty($taskSkills) && empty($studentSkills)) {
            return 50; // Base score if no skills data
        }
        
        if ($studentDomain !== null) {
            // Skill matching (45% weight)
            $matchingSkills = array_intersect($studentSkills, $taskSkills);
            $skillMatchPercentage = count($taskSkills) > 0 ? (count($matchingSkills) / count($taskSkills)) * 100 : 100;
            $score += $skillMatchPercentage * 0.45;
            
            // Domain & Role matching (20% weight: 12% domain, 8% role)
            $domainAlignmentScore = 0;
            if ($task->domain && $task->domain === $studentDomain) {
                $domainAlignmentScore += 60;
            }
            if ($task->role && $task->role === $studentRole) {
                $domainAlignmentScore += 40;
            }
            $score += $domainAlignmentScore * 0.20;
            
            // Difficulty score based on required skills count (15% weight)
            $requiredSkillsCount = count($taskSkills);
            $difficultyScore = min(100, $requiredSkillsCount * 25);
            $score += $difficultyScore * 0.15;
            
            // Recency bonus (20% weight)
            $daysOld = now()->diffInDays($task->created_at);
            $recencyScore = max(0, 100 - ($daysOld * 5));
            $score += $recencyScore * 0.2;
        } else {
            // Legacy / skills-only fallback
            $matchingSkills = array_intersect($studentSkills, $taskSkills);
            $skillMatchPercentage = count($taskSkills) > 0 ? (count($matchingSkills) / count($taskSkills)) * 100 : 100;
            $score += $skillMatchPercentage * 0.6;
            
            // Difficulty score based on required skills count (20% weight)
            $requiredSkillsCount = count($taskSkills);
            $difficultyScore = min(100, $requiredSkillsCount * 25);
            $score += $difficultyScore * 0.2;
            
            // Recency bonus (20% weight)
            $daysOld = now()->diffInDays($task->created_at);
            $recencyScore = max(0, 100 - ($daysOld * 5));
            $score += $recencyScore * 0.2;
        }
        
        return round($score);
    }

    private function calculateStudentMatchScore($student, $taskSkills, $task)
    {
        $score = 0;
        
        // Get student skills - handle relationship or JSON
        if ($student->skills instanceof \Illuminate\Database\Eloquent\Collection) {
            // Skills from relationship - get skill names
            $studentSkills = $student->skills->pluck('name')->toArray();
        } elseif (is_array($student->skills)) {
            $studentSkills = $student->skills;
        } else {
            $studentSkills = json_decode($student->skills ?? '[]', true);
        }
        
        if (empty($taskSkills) && empty($studentSkills)) {
            return 50; // Base score
        }
        
        // Skill matching (40% weight)
        $matchingSkills = array_intersect($studentSkills, $taskSkills);
        $skillMatchPercentage = count($taskSkills) > 0 ? (count($matchingSkills) / count($taskSkills)) * 100 : 100;
        $score += $skillMatchPercentage * 0.4;
        
        // Domain & Role matching (20% weight: 12% domain, 8% role)
        $domainAlignmentScore = 0;
        if ($task->domain && $student->primary_domain && $task->domain === $student->primary_domain) {
            $domainAlignmentScore += 60;
        }
        if ($task->role && $student->preferred_role && $task->role === $student->preferred_role) {
            $domainAlignmentScore += 40;
        }
        $score += $domainAlignmentScore * 0.2;

        // Reliability score (25% weight)
        $reliabilityScore = ($student->reliability_score ?? 1.0) * 100;
        $score += $reliabilityScore * 0.25;
        
        // Experience level (15% weight)
        $experienceScore = $this->calculateExperienceScore($student);
        $score += $experienceScore * 0.15;
        
        return round($score);
    }

    private function calculateExperienceScore($student)
    {
        // Calculate based on completed tasks, certificates, etc.
        $completedTasks = $student->applications()
            ->whereHas('submission', function($q) {
                $q->where('status', 'accepted');
            })->count();
        
        return min(100, $completedTasks * 10);
    }

    public function getMatchPercentage($student, $task)
    {
        return $this->calculateTaskMatchScore($task, $student);
    }
}
