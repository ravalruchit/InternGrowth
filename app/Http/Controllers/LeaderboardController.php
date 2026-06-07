<?php

namespace App\Http\Controllers;

use App\Repositories\StudentRepository;

class LeaderboardController extends Controller
{
    public function __construct(private StudentRepository $repository) {}

    public function index()
    {
        $students = $this->repository->getLeaderboard();
        return view('leaderboard.index', compact('students'));
    }
}
