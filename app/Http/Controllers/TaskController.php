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
                    if ($ranking) {
                        $application->match_score = $ranking['match_score'];
                        $application->portfolio_rating_label = $ranking['portfolio_rating_label'];
                        $application->ranking_details = $ranking;
                    } else {
                        $application->match_score = 0;
                        $application->portfolio_rating_label = 'No Portfolio';
                        $application->ranking_details = null;
                    }
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

        // Enforce Free Startup Plan limits (max 3 active tasks)
        if (!auth()->user()->isStartupGrowth()) {
            $activeCount = \App\Models\Task::where('startup_profile_id', auth()->user()->startupProfile->id)
                ->whereIn('status', ['posted', 'in_progress'])
                ->count();
            if ($activeCount >= 3) {
                return redirect()->route('pricing.index')
                    ->with('error', '⚠️ Limit reached! Free startups can have a maximum of 3 active tasks. Upgrade to Growth to post unlimited tasks.');
            }
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

        // Enforce Free Startup Plan limits (max 3 active tasks)
        if (!auth()->user()->isStartupGrowth()) {
            $activeCount = \App\Models\Task::where('startup_profile_id', auth()->user()->startupProfile->id)
                ->whereIn('status', ['posted', 'in_progress'])
                ->count();
            if ($activeCount >= 3) {
                return redirect()->route('pricing.index')
                    ->with('error', '⚠️ Limit reached! Free startups can have a maximum of 3 active tasks. Upgrade to Growth to post unlimited tasks.');
            }
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'stipend'     => 'nullable|numeric|min:0',
            'skills'      => 'required|array|min:1',
            'skills.*'    => 'exists:skills,id',
            'domain'      => 'required|string|in:' . implode(',', array_keys(\App\Models\StudentProfile::$domains)),
            'role'        => 'required|string',
            'deadline'    => 'nullable|date|after_or_equal:today',
        ]);


        // Get skill names for required_skills JSON field
        $skillIds   = $validated['skills'];
        $skillNames = Skill::whereIn('id', $skillIds)->pluck('name')->toArray();

        $validated['startup_profile_id'] = auth()->user()->startupProfile->id;
        $validated['required_skills']    = $skillNames;

        $escrowAmount = $validated['stipend'] ?? 0;
        $validated['escrow_amount'] = $escrowAmount;
        $validated['escrow_locked'] = $escrowAmount > 0;

        \Illuminate\Support\Facades\DB::transaction(function() use ($validated, $skillIds, $escrowAmount) {
            $startup = \App\Models\StartupProfile::lockForUpdate()->findOrFail(auth()->user()->startupProfile->id);

            if ($escrowAmount > 0 && $startup->wallet_balance < $escrowAmount) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'stipend' => 'Insufficient wallet balance. Please add money first. Current balance: ₹' . $startup->wallet_balance
                ]);
            }

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
        });

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
            'domain'      => 'required|string|in:' . implode(',', array_keys(\App\Models\StudentProfile::$domains)),
            'role'        => 'required|string',
            'deadline'    => 'nullable|date',
        ]);


        $skillIds   = $validated['skills'];
        $skillNames = Skill::whereIn('id', $skillIds)->pluck('name')->toArray();
        $validated['required_skills'] = $skillNames;

        $oldStipend = floatval($task->stipend);
        $newStipend = floatval($validated['stipend'] ?? 0);
        $diff = $newStipend - $oldStipend;

        \Illuminate\Support\Facades\DB::transaction(function() use ($task, $id, $validated, $skillIds, $diff, $newStipend, $oldStipend) {
            $startup = \App\Models\StartupProfile::lockForUpdate()->findOrFail(auth()->user()->startupProfile->id);

            if ($diff > 0 && $startup->wallet_balance < $diff) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'stipend' => 'Insufficient wallet balance to cover the increased stipend. Additional required: ₹' . $diff
                ]);
            }

            $validated['escrow_amount'] = $newStipend;
            $validated['escrow_locked'] = $newStipend > 0;

            $this->repository->update($id, $validated);
            $task->skills()->sync($skillIds);

            if ($diff > 0) {
                $startup->decrement('wallet_balance', $diff);

                $escrow = \App\Models\Escrow::firstOrNew(['task_id' => $task->id]);
                $escrow->amount = $newStipend;
                $escrow->status = 'locked';
                $escrow->save();

                \App\Models\Transaction::create([
                    'user_type'   => 'startup',
                    'user_id'     => $startup->id,
                    'type'        => 'escrow_lock',
                    'amount'      => $diff,
                    'description' => "Escrow increased for task: {$validated['title']} (Stipend updated from ₹{$oldStipend} to ₹{$newStipend})",
                    'reference_id'=> "task_{$task->id}"
                ]);
            } elseif ($diff < 0) {
                $refundAmount = abs($diff);
                $startup->increment('wallet_balance', $refundAmount);

                $escrow = \App\Models\Escrow::where('task_id', $task->id)->first();
                if ($escrow) {
                    if ($newStipend > 0) {
                        $escrow->update([
                            'amount' => $newStipend,
                            'status' => 'locked'
                        ]);
                    } else {
                        $escrow->delete();
                    }
                }

                \App\Models\Transaction::create([
                    'user_type'   => 'startup',
                    'user_id'     => $startup->id,
                    'type'        => 'credit',
                    'amount'      => $refundAmount,
                    'description' => "Escrow refunded for task: {$validated['title']} (Stipend reduced from ₹{$oldStipend} to ₹{$newStipend})",
                    'reference_id'=> "task_{$task->id}"
                ]);
            }
        });

        return redirect()->route('startup.dashboard')->with('success', 'Task updated successfully');
    }




    public function destroy($id)
    {
        $task = $this->repository->find($id);
        
        // Check if user owns this task
        if ($task->startup_profile_id !== auth()->user()->startupProfile->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if task has any non-rejected applications
        $nonRejectedCount = $task->applications()->where('status', '!=', 'rejected')->count();
        if ($nonRejectedCount > 0) {
            return redirect()->route('startup.dashboard')->with('error', 'Cannot delete task with active or approved applications');
        }

        \Illuminate\Support\Facades\DB::transaction(function() use ($task) {
            $escrow = $task->escrow;
            if ($escrow && $escrow->status === 'locked') {
                $startup = $task->startup;
                $startup->increment('wallet_balance', $escrow->amount);

                \App\Models\Transaction::create([
                    'user_type'   => 'startup',
                    'user_id'     => $startup->id,
                    'type'        => 'credit',
                    'amount'      => $escrow->amount,
                    'description' => "Escrow refunded on task deletion: {$task->title}",
                    'reference_id'=> "task_{$task->id}"
                ]);

                $escrow->update(['status' => 'refunded']);
            }

            $this->repository->delete($task->id);
        });

        return redirect()->route('startup.dashboard')->with('success', 'Task deleted successfully');
    }
}
