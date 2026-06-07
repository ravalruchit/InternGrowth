<?php

namespace App\Repositories;

use App\Models\Application;

class ApplicationRepository
{
    public function create(array $data)
    {
        return Application::create($data);
    }

    public function find($id)
    {
        return Application::with('task', 'student.user', 'submission')->findOrFail($id);
    }

    public function updateStatus($id, string $status)
    {
        $application = Application::findOrFail($id);
        $application->update(['status' => $status]);
        return $application;
    }

    public function getByStudent($studentProfileId)
    {
        return Application::where('student_profile_id', $studentProfileId)
            ->with('task.startup.user', 'submission')
            ->latest()
            ->get();
    }
}
