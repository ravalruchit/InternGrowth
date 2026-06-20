<?php

namespace App\Repositories;

use App\Models\StudentProfile;

class StudentRepository
{
    public function create(array $data)
    {
        return StudentProfile::create($data);
    }

    public function find($id)
    {
        return StudentProfile::with('user', 'skills', 'reputationScore')->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $profile = StudentProfile::findOrFail($id);
        $profile->update($data);
        return $profile;
    }

    public function getLeaderboard()
    {
        return StudentProfile::with(['user', 'reputationScore'])
            ->leftJoin('reputation_scores', 'student_profiles.id', '=', 'reputation_scores.student_profile_id')
            ->orderByRaw('COALESCE(reputation_scores.overall_score, 50.00) desc')
            ->select('student_profiles.*')
            ->limit(10)
            ->get();
    }
}
