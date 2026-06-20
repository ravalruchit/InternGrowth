<?php

namespace App\Services;

use App\Models\StudentProfile;
use App\Models\ReputationScore;
use App\Models\PlagiarismLog;

class ReputationEngineService
{
    public function updateReputation(int $studentProfileId): ReputationScore
    {
        $student = StudentProfile::with(['user', 'applications.submission', 'ratings', 'skillVerifications', 'interviews'])->findOrFail($studentProfileId);
        
        $trustScore = $this->calculateTrustScore($student);
        $completionRate = $this->calculateCompletionRate($student);
        $onTimeRate = $this->calculateOnTimeRate($student);
        $satisfactionRating = $this->calculateSatisfactionRating($student);
        $communicationRating = $this->calculateCommunicationRating($student);
        $skillVerificationRating = $this->calculateSkillVerificationRating($student);
        
        // Calculate Interview Metrics
        $interviews = $student->interviews;
        $completedInterviews = $interviews->where('status', 'completed');
        $attendedCount = $completedInterviews->count();
        $noShows = $interviews->where('status', 'no_show')->count();
        
        $strongCandidateCount = $completedInterviews->whereIn('outcome', ['proceed_to_offer', 'keep_in_pipeline', 'strong_candidate'])->count();
        
        if ($attendedCount === 0) {
            $successRate = 100.00;
            if ($noShows === 0) {
                $ips = 100.00;
            } else {
                $ips = max(0.00, 100.00 - ($noShows * 15.00));
            }
        } else {
            $successfulCount = $completedInterviews->whereIn('outcome', ['proceed_to_offer', 'keep_in_pipeline', 'needs_another_round', 'strong_candidate'])->count();
            $successRate = ($successfulCount / $attendedCount) * 100;
            
            $totalInterviewScore = 0;
            foreach ($completedInterviews as $interview) {
                $tech = $interview->technical_rating ?? 5;
                $comm = $interview->communication_rating ?? 5;
                $prob = $interview->problem_solving_rating ?? 5;
                $avgRating = ($tech + $comm + $prob) / 3 * 10; // scale 1-10 to 10-100
                
                $outcomeVal = match($interview->outcome) {
                    'proceed_to_offer' => 100.00,
                    'keep_in_pipeline' => 90.00,
                    'needs_another_round' => 80.00,
                    'rejected' => 40.00,
                    default => 95.00,
                };
                
                $totalInterviewScore += ($avgRating * 0.5) + ($outcomeVal * 0.5);
            }
            
            $avgCompletedScore = $totalInterviewScore / $attendedCount;
            $ips = max(0.00, min(100.00, $avgCompletedScore - ($noShows * 15.00)));
        }

        $overallScore = ($trustScore * 0.25) + 
                         ($completionRate * 0.15) + 
                         ($onTimeRate * 0.15) + 
                         ($satisfactionRating * 0.15) + 
                         ($communicationRating * 0.10) + 
                         ($skillVerificationRating * 0.05) +
                         ($ips * 0.15);

        $totalProjects = $student->applications()
            ->whereHas('submission', function($query) {
                $query->where('status', 'accepted');
            })->count();

        // Calculate domain reputation scores
        $domainScores = [];
        $domainsList = ['Software Development', 'UI/UX Design', 'Digital Marketing', 'Data & AI', 'Content & Business'];
        foreach ($domainsList as $domainName) {
            $domainScores[$domainName] = $this->calculateDomainScore($student, $domainName, $trustScore);
        }

        // Update student profile reliability score to sync with overall score
        $student->update(['reliability_score' => round($overallScore / 100, 2)]);

        return ReputationScore::updateOrCreate(
            ['student_profile_id' => $student->id],
            [
                'overall_score' => round($overallScore, 2),
                'trust_score' => round($trustScore, 2),
                'completion_rate' => round($completionRate, 2),
                'on_time_rate' => round($onTimeRate, 2),
                'satisfaction_rating' => round($satisfactionRating / 20, 2), // Scale back to 0-5
                'communication_rating' => round($communicationRating / 20, 2), // Scale back to 0-5
                'skill_verification_rating' => round($skillVerificationRating, 2),
                'interview_performance_score' => round($ips, 2),
                'interviews_attended' => $attendedCount,
                'interview_success_rate' => round($successRate, 2),
                'strong_candidate_outcomes' => $strongCandidateCount,
                'no_shows' => $noShows,
                'total_verified_projects' => $totalProjects,
                'domain_scores' => $domainScores,
            ]
        );
    }

    private function calculateDomainScore($student, string $domain, float $trustScore): float
    {
        // 1. Completion Rate for this domain
        $approvedCount = $student->applications()
            ->where('status', 'approved')
            ->whereHas('task', function($q) use ($domain) {
                $q->where('domain', $domain);
            })->count();
        
        if ($approvedCount === 0) {
            $completionRate = 100.00;
        } else {
            $acceptedCount = $student->applications()
                ->where('status', 'approved')
                ->whereHas('task', function($q) use ($domain) {
                    $q->where('domain', $domain);
                })
                ->whereHas('submission', function($q) {
                    $q->where('status', 'accepted');
                })->count();
            $completionRate = ($acceptedCount / $approvedCount) * 100;
        }

        // 2. On-Time Delivery Rate for this domain
        $submissions = $student->applications()
            ->whereHas('submission', function($q) {
                $q->where('status', 'accepted');
            })
            ->whereHas('task', function($q) use ($domain) {
                $q->where('domain', $domain);
            })
            ->with(['task', 'submission'])
            ->get();

        if ($submissions->isEmpty()) {
            $onTimeRate = 100.00;
        } else {
            $onTimeCount = 0;
            $deadlineTasksCount = 0;
            foreach ($submissions as $app) {
                $task = $app->task;
                $submission = $app->submission;
                if ($task && isset($task->deadline)) {
                    $deadlineTasksCount++;
                    if (strtotime($submission->created_at) <= strtotime($task->deadline)) {
                        $onTimeCount++;
                    }
                }
            }
            $onTimeRate = $deadlineTasksCount === 0 ? 100.00 : ($onTimeCount / $deadlineTasksCount) * 100;
        }

        // 3. Satisfaction Rating for this domain
        $avgRating = $student->ratings()
            ->whereHas('task', function($q) use ($domain) {
                $q->where('domain', $domain);
            })->avg('rating');
        $satisfactionRating = $avgRating === null ? 100.00 : $avgRating * 20;

        // 4. Communication rating
        $communicationRating = 96.00;

        // 5. Skill verification rating for this domain
        $verifiedCount = $student->skillVerifications()
            ->whereHas('skill', function($q) use ($domain) {
                $q->where('domain', $domain);
            })->count();
        $skillVerificationRating = min(100.00, $verifiedCount * 25.00);

        // 6. Interview Performance Score (IPS) for this domain
        $interviews = $student->interviews()->where('domain', $domain)->get();
        $completedInterviews = $interviews->where('status', 'completed');
        $attendedCount = $completedInterviews->count();
        $noShows = $interviews->where('status', 'no_show')->count();
        
        if ($attendedCount === 0) {
            $ips = 100.00;
            if ($noShows > 0) {
                $ips = max(0.00, 100.00 - ($noShows * 15.00));
            }
        } else {
            $totalInterviewScore = 0;
            foreach ($completedInterviews as $interview) {
                $tech = $interview->technical_rating ?? 5;
                $comm = $interview->communication_rating ?? 5;
                $prob = $interview->problem_solving_rating ?? 5;
                $avgRatingVal = ($tech + $comm + $prob) / 3 * 10;
                
                $outcomeVal = match($interview->outcome) {
                    'proceed_to_offer' => 100.00,
                    'keep_in_pipeline' => 90.00,
                    'needs_another_round' => 80.00,
                    'rejected' => 40.00,
                    default => 95.00,
                };
                
                $totalInterviewScore += ($avgRatingVal * 0.5) + ($outcomeVal * 0.5);
            }
            $avgCompletedScore = $totalInterviewScore / $attendedCount;
            $ips = max(0.00, min(100.00, $avgCompletedScore - ($noShows * 15.00)));
        }

        // Compute overall score for this domain
        $overallScore = ($trustScore * 0.25) + 
                         ($completionRate * 0.15) + 
                         ($onTimeRate * 0.15) + 
                         ($satisfactionRating * 0.15) + 
                         ($communicationRating * 0.10) + 
                         ($skillVerificationRating * 0.05) +
                         ($ips * 0.15);

        return round($overallScore, 2);
    }

    private function calculateTrustScore($student): float
    {
        $score = 50.00;
        
        if ($student->user && $student->user->is_verified) {
            $score += 25.00;
        }
        if ($student->is_verified) { // College email verified check
            $score += 15.00;
        }

        // Deduct for plagiarism logs
        $plagiarismChecks = PlagiarismLog::whereHas('submission.application', function($q) use ($student) {
            $q->where('student_profile_id', $student->id);
        })->where('status', 'flagged')->count();

        $score -= ($plagiarismChecks * 15.00);

        return max(0.00, min(100.00, $score));
    }

    private function calculateCompletionRate($student): float
    {
        $approvedCount = $student->applications()->where('status', 'approved')->count();
        if ($approvedCount === 0) {
            return 100.00;
        }

        $acceptedCount = $student->applications()
            ->where('status', 'approved')
            ->whereHas('submission', function($q) {
                $q->where('status', 'accepted');
            })->count();

        return ($acceptedCount / $approvedCount) * 100;
    }

    private function calculateOnTimeRate($student): float
    {
        $submissions = $student->applications()
            ->whereHas('submission', function($q) {
                $q->where('status', 'accepted');
            })
            ->with(['task', 'submission'])
            ->get();

        if ($submissions->isEmpty()) {
            return 100.00;
        }

        $onTimeCount = 0;
        $deadlineTasksCount = 0;

        foreach ($submissions as $app) {
            $task = $app->task;
            $submission = $app->submission;

            if ($task && isset($task->deadline)) {
                $deadlineTasksCount++;
                if (strtotime($submission->created_at) <= strtotime($task->deadline)) {
                    $onTimeCount++;
                }
            }
        }

        if ($deadlineTasksCount === 0) {
            return 100.00;
        }

        return ($onTimeCount / $deadlineTasksCount) * 100;
    }

    private function calculateSatisfactionRating($student): float
    {
        $avgRating = $student->ratings()->avg('rating');
        if ($avgRating === null) {
            return 100.00; // Start with full satisfaction points
        }
        return $avgRating * 20; // scale from 0-5 to 0-100
    }

    private function calculateCommunicationRating($student): float
    {
        // Default high score, can be decayed based on message read logs
        return 96.00; // equivalent to 4.8 / 5
    }

    private function calculateSkillVerificationRating($student): float
    {
        $verifiedCount = $student->skillVerifications()->count();
        return min(100.00, $verifiedCount * 25.00);
    }
}
