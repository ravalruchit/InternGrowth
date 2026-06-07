<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    public function getAll()
    {
        return Task::with('startup.user', 'skills')->latest()->get();
    }

    public function getPosted()
    {
        return Task::where('status', 'posted')->with('startup.user', 'skills')->latest()->get();
    }

    public function find($id)
    {
        return Task::with('startup.user', 'skills', 'applications.student.user', 'applications.submission')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Task::create($data);
    }

    public function update($id, array $data)
    {
        $task = Task::findOrFail($id);
        $task->update($data);
        return $task;
    }

    public function delete($id)
    {
        return Task::findOrFail($id)->delete();
    }
}
