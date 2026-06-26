<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HiringOffer;
use App\Models\Notification;
use App\Services\OfferRefundService;

class ExpireOffers extends Command
{
    protected $signature = 'offers:expire';

    protected $description = 'Automatically expire all pending hiring offers that have passed their expiration date';

    public function handle()
    {
        $expiredOfferIds = HiringOffer::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->pluck('id');

        $count = $expiredOfferIds->count();

        if ($count === 0) {
            $this->info('No expired hiring offers found.');
            return 0;
        }

        $this->info("Found {$count} expired offer(s). Updating statuses...");

        foreach ($expiredOfferIds as $offerId) {
            $offer = null;

            \Illuminate\Support\Facades\DB::transaction(function() use ($offerId, &$offer) {
                $offer = HiringOffer::with(['student.user', 'startup'])->lockForUpdate()->find($offerId);

                if (!$offer || $offer->getRawOriginal('status') !== 'pending') {
                    return;
                }

                $offer->update(['status' => 'expired']);

                OfferRefundService::refundReservedFee(
                    $offer,
                    "Refunded success fee reservation for expired offer ID: {$offer->id}"
                );
            });

            if (!$offer || $offer->getRawOriginal('status') !== 'expired') {
                continue;
            }

            Notification::create([
                'user_id' => $offer->student->user_id,
                'title' => 'Offer Expired',
                'message' => "The {$offer->offer_type} offer for '{$offer->title}' from {$offer->startup->company_name} has expired.",
                'type' => 'warning'
            ]);

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
