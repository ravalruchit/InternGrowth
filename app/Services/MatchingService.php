<?php

namespace App\Services;

use App\Models\Task;
use App\Models\StudentProfile;
use App\Models\User;

class MatchingService
{
    public function getRecommendedTasksForStudent($studentProfile, $limit = 10, $studentProfileId = null)
    {
        // Get student skills - handle relationship or JSON
        if ($studentProfile->skills instanceof \Illuminate\Database\Eloquent\Collection) {
            // Skills from relationship - get skill names
            $studentSkills = $studentProfile->skills->pluck('name')->toArray();
        } elseif (is_array($studentProfile->skills)) {
            $studentSkills = $studentProfile->skills;
        } else {
            $studentSkills = json_decode($studentProfile->skills ?? '[]', true);
        }
        
        // Get all posted tasks (not closed, moderated, or completed)
        $tasksQuery = Task::where('status', 'posted')
            ->with('startup.user');
        
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
        
        // Calculate match scores
        $tasks = $tasks->map(function ($task) use ($studentProfile) {
            $task->match_score = $this->calculateTaskMatchScore($task, $studentProfile);
            return $task;
        })
        ->sortByDesc('match_score')
        ->take($limit);
        
        return $tasks;
    }

    public function getRecommendedStudentsForTask($task, $limit = 10)
    {
        // Handle both JSON string and array
        $taskSkills = is_array($task->required_skills) 
            ? $task->required_skills 
            : json_decode($task->required_skills ?? '[]', true);
        
        $students = StudentProfile::with('user')
            ->get()
            ->map(function ($student) use ($taskSkills, $task) {
                $student->match_score = $this->calculateStudentMatchScore($student, $taskSkills, $task);
                return $student;
            })
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
            
            // Points/difficulty matching (15% weight)
            if ($task->reward_points) {
                $difficultyScore = min(100, ($task->reward_points / 10) * 10);
                $score += $difficultyScore * 0.15;
            }
            
            // Recency bonus (20% weight)
            $daysOld = now()->diffInDays($task->created_at);
            $recencyScore = max(0, 100 - ($daysOld * 5));
            $score += $recencyScore * 0.2;
        } else {
            // Legacy / skills-only fallback
            $matchingSkills = array_intersect($studentSkills, $taskSkills);
            $skillMatchPercentage = count($taskSkills) > 0 ? (count($matchingSkills) / count($taskSkills)) * 100 : 100;
            $score += $skillMatchPercentage * 0.6;
            
            // Points/difficulty matching (20% weight)
            if ($task->reward_points) {
                $difficultyScore = min(100, ($task->reward_points / 10) * 10);
                $score += $difficultyScore * 0.2;
            }
            
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
        $completedTasks = $student->user->studentProfile->applications()
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
