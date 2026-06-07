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
        return StudentProfile::with('user', 'skills', 'wallet')->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $profile = StudentProfile::findOrFail($id);
        $profile->update($data);
        return $profile;
    }

    public function getLeaderboard()
    {
        return StudentProfile::with('user', 'wallet')
            ->join('points_wallets', 'student_profiles.id', '=', 'points_wallets.student_profile_id')
            ->orderBy('points_wallets.balance', 'desc')
            ->select('student_profiles.*')
            ->limit(10)
            ->get();
    }
}
