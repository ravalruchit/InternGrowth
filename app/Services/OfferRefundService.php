<?php

namespace App\Services;

use App\Models\HiringOffer;
use App\Models\StartupProfile;
use App\Models\Transaction;

class OfferRefundService
{
    /**
     * Refund a reserved success fee once. Call inside a transaction after lockForUpdate on the offer.
     */
    public static function refundReservedFee(HiringOffer $offer, string $description): bool
    {
        if ($offer->reserved_fee <= 0) {
            return false;
        }

        $refundRef = "offer_refund_{$offer->id}";
        if (Transaction::where('reference_id', $refundRef)->where('type', 'credit')->exists()) {
            return false;
        }

        $startup = StartupProfile::lockForUpdate()->findOrFail($offer->startup_profile_id);
        $startup->increment('wallet_balance', $offer->reserved_fee);

        Transaction::create([
            'user_type' => 'startup',
            'user_id' => $startup->id,
            'type' => 'credit',
            'amount' => $offer->reserved_fee,
            'description' => $description,
            'reference_id' => $refundRef,
        ]);

        // Mark the original debit transaction as reversed
        Transaction::where('reference_id', "offer_{$offer->id}")
            ->where('type', 'debit')
            ->update(['status' => 'reversed']);

        return true;
    }
}
