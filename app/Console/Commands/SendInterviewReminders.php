<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Interview;
use App\Models\Notification;

class SendInterviewReminders extends Command
{
    protected $signature = 'interviews:remind';

    protected $description = 'Send 24-hour and 1-hour interview reminders to candidates and startups';

    public function handle()
    {
        $now = now();
        $tomorrow = now()->addHours(24);
        $oneHour = now()->addHours(1);

        // 24 Hour Reminder
        $interviews24 = Interview::where('status', 'accepted')
            ->where('scheduled_at', '>', $now)
            ->where('scheduled_at', '<=', $tomorrow)
            ->where('reminder_24h_sent', false)
            ->get();

        foreach ($interviews24 as $interview) {
            $timeStr = $interview->scheduled_at->format('i') === '00' 
                ? $interview->scheduled_at->format('g A') 
                : $interview->scheduled_at->format('g:i A');

            $studentMsg = "🔔 Interview tomorrow at {$timeStr}";
            $startupMsg = "🔔 Interview with {$interview->student->user->name} tomorrow at {$timeStr}";

            // Notify student
            Notification::create([
                'user_id' => $interview->student->user_id,
                'title' => 'Interview Reminder',
                'message' => $studentMsg,
                'type' => 'warning'
            ]);

            // Notify startup
            Notification::create([
                'user_id' => $interview->startup->user_id,
                'title' => 'Interview Tomorrow',
                'message' => $startupMsg,
                'type' => 'warning'
            ]);

            $interview->update(['reminder_24h_sent' => true]);
            $this->info("Sent 24h reminders for Interview ID: {$interview->id}");
        }

        // 1 Hour Reminder
        $interviews1 = Interview::where('status', 'accepted')
            ->where('scheduled_at', '>', $now)
            ->where('scheduled_at', '<=', $oneHour)
            ->where('reminder_1h_sent', false)
            ->get();

        foreach ($interviews1 as $interview) {
            $studentMsg = "🔔 Interview starts in 1 hour";
            $startupMsg = "🔔 Interview with {$interview->student->user->name} starts in 1 hour";

            // Notify student
            Notification::create([
                'user_id' => $interview->student->user_id,
                'title' => 'Interview Starting Soon',
                'message' => $studentMsg,
                'type' => 'warning'
            ]);

            // Notify startup
            Notification::create([
                'user_id' => $interview->startup->user_id,
                'title' => 'Interview Starting Soon',
                'message' => $startupMsg,
                'type' => 'warning'
            ]);

            $interview->update(['reminder_1h_sent' => true]);
            $this->info("Sent 1h reminders for Interview ID: {$interview->id}");
        }

        return 0;
    }
}
