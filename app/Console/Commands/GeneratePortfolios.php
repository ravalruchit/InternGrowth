<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Submission;
use App\Services\PortfolioAutomationService;

class GeneratePortfolios extends Command
{
    protected $signature = 'portfolio:generate {submission_id?}';

    protected $description = 'Retroactively generate portfolio items for all accepted submissions or a specific submission';

    public function handle()
    {
        $submissionId = $this->argument('submission_id');
        $portfolioService = new PortfolioAutomationService();

        if ($submissionId) {
            $submission = Submission::where('status', 'accepted')->find($submissionId);
            if (!$submission) {
                $this->error("Accepted submission ID {$submissionId} not found.");
                return 1;
            }

            $this->info("Generating portfolio item for submission ID: {$submission->id}...");
            $item = $portfolioService->addVerifiedTaskToPortfolio($submission->id);
            $this->info("Success! Dynamic Portfolio Item created: '{$item->project_title}'");
        } else {
            $submissions = Submission::where('status', 'accepted')->get();
            $count = $submissions->count();

            if ($count === 0) {
                $this->info("No accepted submissions found to generate portfolios.");
                return 0;
            }

            $this->info("Generating portfolio entries for {$count} accepted submission(s)...");
            
            $bar = $this->output->createProgressBar($count);
            $bar->start();

            foreach ($submissions as $submission) {
                $portfolioService->addVerifiedTaskToPortfolio($submission->id);
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info("Successfully generated portfolios and verified ledger items for all {$count} accepted submission(s)!");
        }

        return 0;
    }
}
