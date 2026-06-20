<?php

namespace App\Http\Controllers;

use App\Repositories\TaskRepository;
use App\Services\MatchingService;
use Illuminate\Http\Request;
use App\Models\Skill;

class TaskController extends Controller
{
    public function __construct(
        private TaskRepository $repository,
        private MatchingService $matchingService
    ) {}

    public function index()
    {
        $query = $this->repository->getPosted();
        
        // If user is a student and not verified, limit to 5 tasks
        if (auth()->check() && auth()->user()->isStudent()) {
            $student = auth()->user()->studentProfile;
            if (!$student->is_verified) {
                $query = $query->take(5);
            }
        }
        
        $tasks = $query;
        $isLimited = auth()->check() && auth()->user()->isStudent() && !auth()->user()->studentProfile->is_verified;
        
        return view('tasks.index', compact('tasks', 'isLimited'));
    }

    public function show($id)
    {
        $task = $this->repository->find($id);
        $recommendedStudents = null;
        
        // Show recommended students if viewing as startup owner
        if (auth()->check() && auth()->user()->isStartup() && $task->startup_profile_id === auth()->user()->startupProfile->id) {
            $task->load([
                'applications.student.user',
                'applications.student.reputationScore',
                'applications.student.portfolio.items',
                'applications.student.skillVerifications.skill',
                'applications.submission'
            ]);
            
            $rankingService = app(\App\Services\CandidateRankingService::class);
            foreach ($task->applications as $application) {
                if ($application->student) {
                    $ranking = $rankingService->calculateMatchScore($application->student, $task);
                    $application->match_score = $ranking['match_score'];
                    $application->portfolio_rating_label = $ranking['portfolio_rating_label'];
                    $application->ranking_details = $ranking;
                } else {
                    $application->match_score = 0;
                    $application->portfolio_rating_label = 'Basic';
                    $application->ranking_details = [];
                }
            }
            
            $recommendedStudents = $this->matchingService->getRecommendedStudentsForTask($task, 5);
        }
        
        return view('tasks.show', compact('task', 'recommendedStudents'));
    }

    public function create()
    {
        // Check if startup is verified and active
        if (!auth()->user()->startupProfile->isVerifiedAndActive()) {
            return redirect()->route('startup.dashboard')
                ->with('error', 'Your startup account must be verified by an admin and active before you can create tasks.');
        }

        // Block if outstanding dues exist (negative wallet balance)
        if (auth()->user()->startupProfile->wallet_balance < 0) {
            return redirect()->route('startup.dashboard')
                ->with('error', 'You cannot create tasks until your outstanding balance (negative wallet balance) is cleared.');
        }

        $skills = Skill::all();
        return view('tasks.create', compact('skills'));
    }

    public function store(Request $request)
    {
        // Check if startup is verified and active
        if (!auth()->user()->startupProfile->isVerifiedAndActive()) {
            return redirect()->route('startup.dashboard')
                ->with('error', 'Your startup account must be verified by an admin and active before you can create tasks.');
        }

        // Block if outstanding dues exist (negative wallet balance)
        if (auth()->user()->startupProfile->wallet_balance < 0) {
            return redirect()->route('startup.dashboard')
                ->with('error', 'You cannot create tasks until your outstanding balance (negative wallet balance) is cleared.');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'stipend'     => 'nullable|numeric|min:0',
            'skills'      => 'required|array|min:1',
            'skills.*'    => 'exists:skills,id',
        ]);

        // Auto-calculate reward points
        $validated['reward_points'] = $this->calculatePoints(
            $validated['stipend'] ?? 0,
            count($validated['skills']),
            strlen($validated['description']),
            !empty($validated['requirements'])
        );

        // Get skill names for required_skills JSON field
        $skillIds   = $validated['skills'];
        $skillNames = Skill::whereIn('id', $skillIds)->pluck('name')->toArray();

        $validated['startup_profile_id'] = auth()->user()->startupProfile->id;
        $validated['required_skills']    = $skillNames;

        // Escrow logic
        $startup     = auth()->user()->startupProfile;
        $escrowAmount = $validated['stipend'] ?? 0;

        if ($escrowAmount > 0 && $startup->wallet_balance < $escrowAmount) {
            return back()->with('error', 'Insufficient wallet balance. Please add money first. Current balance: ₹' . $startup->wallet_balance);
        }

        $validated['escrow_amount'] = $escrowAmount;
        $validated['escrow_locked'] = $escrowAmount > 0;

        $task = $this->repository->create($validated);
        $task->skills()->attach($skillIds);

        if ($escrowAmount > 0) {
            \App\Models\Escrow::create([
                'task_id' => $task->id,
                'amount'  => $escrowAmount,
                'status'  => 'locked'
            ]);
            $startup->decrement('wallet_balance', $escrowAmount);
            \App\Models\Transaction::create([
                'user_type'   => 'startup',
                'user_id'     => $startup->id,
                'type'        => 'escrow_lock',
                'amount'      => $escrowAmount,
                'description' => "Escrow locked for task: {$task->title}",
                'reference_id'=> "task_{$task->id}"
            ]);
        }

        return redirect()->route('startup.dashboard')->with('task_created', 'Task created successfully' . ($escrowAmount > 0 ? ' and ₹' . $escrowAmount . ' locked in escrow' : ''));
    }


    public function edit($id)
    {
        $task = $this->repository->find($id);
        
        // Check if user owns this task
        if ($task->startup_profile_id !== auth()->user()->startupProfile->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if task has approved applications
        if ($task->applications()->where('status', 'approved')->count() > 0) {
            return redirect()->route('startup.dashboard')
                ->with('error', 'Cannot edit task after approving an application. The student is already working on it.');
        }

        $skills = Skill::all();
        return view('tasks.edit', compact('task', 'skills'));
    }

    public function update(Request $request, $id)
    {
        $task = $this->repository->find($id);

        if ($task->startup_profile_id !== auth()->user()->startupProfile->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($task->applications()->where('status', 'approved')->count() > 0) {
            return redirect()->route('startup.dashboard')
                ->with('error', 'Cannot edit task after approving an application. The student is already working on it.');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'stipend'     => 'nullable|numeric|min:0',
            'skills'      => 'required|array|min:1',
            'skills.*'    => 'exists:skills,id',
        ]);

        // Recalculate points
        $validated['reward_points'] = $this->calculatePoints(
            $validated['stipend'] ?? 0,
            count($validated['skills']),
            strlen($validated['description']),
            false
        );

        $skillIds   = $validated['skills'];
        $skillNames = Skill::whereIn('id', $skillIds)->pluck('name')->toArray();
        $validated['required_skills'] = $skillNames;

        $this->repository->update($id, $validated);
        $task->skills()->sync($skillIds);

        return redirect()->route('startup.dashboard')->with('success', 'Task updated successfully');
    }


    /**
     * Auto-calculate reward points based on task data.
     * Formula:
     *   Base:         50 pts
     *   Stipend:      ₹1 = 0.5 pts  (capped at +200)
     *   Skills:       each skill = +20 pts (capped at +100)
     *   Description:  every 100 chars = +10 pts (capped at +50)
     *   Requirements: +30 pts if set
     */
    private function calculatePoints(float $stipend, int $skillCount, int $descLength, bool $hasRequirements): int
    {
        $base         = 50;
        $stipendBonus = min((int)($stipend * 0.5), 200);
        $skillBonus   = min($skillCount * 20, 100);
        $descBonus    = min((int)($descLength / 100) * 10, 50);
        $reqBonus     = $hasRequirements ? 30 : 0;

        return $base + $stipendBonus + $skillBonus + $descBonus + $reqBonus;
    }

    public function destroy($id)
    {
        $task = $this->repository->find($id);
        
        // Check if user owns this task
        if ($task->startup_profile_id !== auth()->user()->startupProfile->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if task has applications
        if ($task->applications()->count() > 0) {
            return redirect()->route('startup.dashboard')->with('error', 'Cannot delete task with existing applications');
        }

        $this->repository->delete($id);

        return redirect()->route('startup.dashboard')->with('success', 'Task deleted successfully');
    }
}
