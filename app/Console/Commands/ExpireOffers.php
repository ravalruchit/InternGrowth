<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HiringOffer;
use App\Models\Notification;

class ExpireOffers extends Command
{
    protected $signature = 'offers:expire';

    protected $description = 'Automatically expire all pending hiring offers that have passed their expiration date';

    public function handle()
    {
        $expiredOffers = HiringOffer::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->get();

        $count = $expiredOffers->count();

        if ($count === 0) {
            $this->info('No expired hiring offers found.');
            return 0;
        }

        $this->info("Found {$count} expired offer(s). Updating statuses...");

        foreach ($expiredOffers as $offer) {
            \Illuminate\Support\Facades\DB::transaction(function() use ($offer) {
                $offer->update(['status' => 'expired']);

                if ($offer->reserved_fee > 0) {
                    $startup = $offer->startup;
                    $startup->increment('wallet_balance', $offer->reserved_fee);

                    \App\Models\Transaction::create([
                        'user_type' => 'startup',
                        'user_id' => $startup->id,
                        'type' => 'credit',
                        'amount' => $offer->reserved_fee,
                        'description' => "Refunded success fee reservation for expired offer ID: {$offer->id}",
                        'reference_id' => "offer_{$offer->id}"
                    ]);
                }
            });

            // Notify student
            Notification::create([
                'user_id' => $offer->student->user_id,
                'title' => 'Offer Expired',
                'message' => "The {$offer->offer_type} offer for '{$offer->title}' from {$offer->startup->company_name} has expired.",
                'type' => 'warning'
            ]);

            // Notify startup
            Notification::create([
                'user_id' => $offer->startup->user_id,
                'title' => 'Offer Expired',
                'message' => "Your {$offer->offer_type} offer for '{$offer->title}' extended to {$offer->student->user->name} has expired without response.",
                'type' => 'warning'
            ]);
        }

        $this->info("Successfully expired {$count} hiring offer(s).");
        return 0;
    }
}
