<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\StudentProfile;
use App\Models\Skill;
use App\Models\Task;
use App\Models\HiringOffer;
use App\Models\Interview;
use App\Models\PortfolioItem;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Get all skills to map name -> domain in a case-insensitive way
        $skills = Skill::all();
        $skillDomains = [];
        foreach ($skills as $skill) {
            if ($skill->domain) {
                $skillDomains[strtolower($skill->name)] = $skill->domain;
            }
        }

        $domains = StudentProfile::$domains;

        // Helper function to find dominant domain from a list of skill names/IDs
        $findDominantDomain = function ($itemSkills) use ($skillDomains) {
            $counts = [];
            foreach ($itemSkills as $skillName) {
                $cleanName = strtolower(trim($skillName));
                if (isset($skillDomains[$cleanName])) {
                    $dom = $skillDomains[$cleanName];
                    $counts[$dom] = ($counts[$dom] ?? 0) + 1;
                }
            }
            if (empty($counts)) {
                return 'Software Development';
            }
            arsort($counts);
            return array_key_first($counts);
        };

        // 2. Backfill Student Profiles
        $students = StudentProfile::with('skills')->get();
        foreach ($students as $student) {
            if (empty($student->primary_domain) || empty($student->preferred_role)) {
                $skillNames = $student->skills->pluck('name')->toArray();
                $domain = $findDominantDomain($skillNames);
                
                $student->primary_domain = $student->primary_domain ?: $domain;
                if (empty($student->preferred_role)) {
                    $student->preferred_role = $domains[$student->primary_domain][0] ?? 'Full Stack Developer';
                }
                $student->save();
            }
        }

        // 3. Backfill Tasks
        $tasks = Task::with('skills')->get();
        foreach ($tasks as $task) {
            if (empty($task->domain) || empty($task->role)) {
                // Determine domain from skills
                $skillNames = [];
                if ($task->required_skills) {
                    $skillNames = is_array($task->required_skills) ? $task->required_skills : json_decode($task->required_skills, true);
                    if (!is_array($skillNames)) {
                        $skillNames = [];
                    }
                }
                // If JSON didn't work, try relation
                if (empty($skillNames)) {
                    $skillNames = $task->skills->pluck('name')->toArray();
                }
                
                $domain = $findDominantDomain($skillNames);
                $task->domain = $task->domain ?: $domain;

                // Match role based on title/description
                if (empty($task->role)) {
                    $matchedRole = null;
                    $searchIn = strtolower($task->title . ' ' . $task->description);
                    foreach ($domains[$task->domain] as $roleOption) {
                        if (str_contains($searchIn, strtolower($roleOption))) {
                            $matchedRole = $roleOption;
                            break;
                        }
                    }
                    $task->role = $matchedRole ?: ($domains[$task->domain][0] ?? 'Full Stack Developer');
                }
                $task->save();
            }
        }

        // 4. Backfill Hiring Offers
        $offers = HiringOffer::with(['sourceTask', 'student'])->get();
        foreach ($offers as $offer) {
            if (empty($offer->domain) || empty($offer->role)) {
                if ($offer->sourceTask && $offer->sourceTask->domain) {
                    $offer->domain = $offer->sourceTask->domain;
                    $offer->role = $offer->sourceTask->role;
                } else {
                    // Try to match from title/description
                    $matchedDomain = null;
                    $matchedRole = null;
                    $searchIn = strtolower($offer->title . ' ' . $offer->description);
                    
                    foreach ($domains as $domName => $roleList) {
                        foreach ($roleList as $roleOption) {
                            if (str_contains($searchIn, strtolower($roleOption))) {
                                $matchedDomain = $domName;
                                $matchedRole = $roleOption;
                                break 2;
                            }
                        }
                    }
                    
                    if ($matchedDomain) {
                        $offer->domain = $matchedDomain;
                        $offer->role = $matchedRole;
                    } else if ($offer->student && $offer->student->primary_domain) {
                        $offer->domain = $offer->student->primary_domain;
                        $offer->role = $offer->student->preferred_role;
                    } else {
                        $offer->domain = 'Software Development';
                        $offer->role = 'Full Stack Developer';
                    }
                }
                $offer->save();
            }
        }

        // 5. Backfill Interviews
        $interviews = Interview::with(['task', 'student'])->get();
        foreach ($interviews as $interview) {
            if (empty($interview->domain) || empty($interview->role)) {
                if ($interview->task && $interview->task->domain) {
                    $interview->domain = $interview->task->domain;
                    $interview->role = $interview->task->role;
                } else if ($interview->student && $interview->student->primary_domain) {
                    $interview->domain = $interview->student->primary_domain;
                    $interview->role = $interview->student->preferred_role;
                } else {
                    $interview->domain = 'Software Development';
                    $interview->role = 'Full Stack Developer';
                }
                $interview->save();
            }
        }

        // 6. Backfill Portfolio Items
        $portfolioItems = PortfolioItem::with(['task'])->get();
        foreach ($portfolioItems as $item) {
            if (empty($item->domain) || empty($item->role)) {
                if ($item->task && $item->task->domain) {
                    $item->domain = $item->task->domain;
                    $item->role = $item->task->role;
                } else {
                    $skills = is_array($item->skills_demonstrated) ? $item->skills_demonstrated : [];
                    $domain = $findDominantDomain($skills);
                    $item->domain = $domain;
                    $item->role = $domains[$domain][0] ?? 'Full Stack Developer';
                }
                $item->save();
            }
        }
    }

    public function down(): void
    {
        // No down needed as we're backfilling fields that were empty
    }
};
