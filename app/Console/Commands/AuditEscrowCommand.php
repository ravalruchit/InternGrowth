<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\Escrow;
use App\Models\Submission;

class AuditEscrowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'escrow:audit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform security audit checks on tasks, escrows, and submission states to detect financial anomalies.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting InternGrowth Escrow & Financial Audit...');
        $anomaliesCount = 0;

        // Audit Check 1: Task status vs Escrow status
        $this->comment('Checking Task status vs Escrow status mappings...');
        $tasks = Task::with('escrow')->get();

        foreach ($tasks as $task) {
            $stipend = floatval($task->stipend);
            $escrow = $task->escrow;

            if ($stipend > 0) {
                if (!$escrow) {
                    $this->error("🚨 Task #{$task->id} ('{$task->title}') has stipend ₹{$stipend} but NO escrow record exists.");
                    $anomaliesCount++;
                    continue;
                }

                if ($task->status === 'completed' && $escrow->status !== 'released') {
                    $this->warn("⚠️ Task #{$task->id} is 'completed', but associated Escrow #{$escrow->id} status is '{$escrow->status}' (Expected: 'released').");
                    $anomaliesCount++;
                }

                if (($task->status === 'active' || $task->status === 'posted') && $escrow->status !== 'locked') {
                    $this->warn("⚠️ Task #{$task->id} is '{$task->status}', but associated Escrow #{$escrow->id} status is '{$escrow->status}' (Expected: 'locked').");
                    $anomaliesCount++;
                }
            } else {
                if ($escrow) {
                    $this->warn("⚠️ Task #{$task->id} has ₹0 stipend, but an Escrow #{$escrow->id} record exists.");
                    $anomaliesCount++;
                }
            }
        }

        // Audit Check 2: Submission status vs Escrow state
        $this->comment('Checking Submission status vs Escrow released/refunded states...');
        $submissions = Submission::with('application.task.escrow')->get();

        foreach ($submissions as $submission) {
            $task = $submission->application->task ?? null;
            if (!$task || !$task->stipend || $task->stipend <= 0) {
                continue;
            }

            $escrow = $task->escrow;
            if (!$escrow) {
                continue;
            }

            if ($submission->status === 'accepted' && $escrow->status !== 'released') {
                $this->error("🚨 Submission #{$submission->id} is 'accepted', but Escrow #{$escrow->id} is not 'released' (Current: '{$escrow->status}').");
                $anomaliesCount++;
            }

            if ($submission->status === 'rejected' && $escrow->status === 'released') {
                $this->error("🚨 Submission #{$submission->id} is 'rejected', but Escrow #{$escrow->id} is marked 'released' instead of 'refunded' or 'locked'.");
                $anomaliesCount++;
            }
        }

        $this->newLine();
        if ($anomaliesCount === 0) {
            $this->info('✅ Audit complete. No escrow or balance inconsistencies found!');
        } else {
            $this->error("❌ Audit complete. Found {$anomaliesCount} anomalies. Please inspect the logs above.");
        }

        return $anomaliesCount === 0 ? 0 : 1;
    }
}
