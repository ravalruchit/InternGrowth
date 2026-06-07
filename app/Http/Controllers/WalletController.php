<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class WalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isStartup()) {
            $profile = $user->startupProfile;
            $userType = 'startup';
        } else {
            $profile = $user->studentProfile;
            $userType = 'student';
        }
        
        $transactions = Transaction::where('user_type', $userType)
            ->where('user_id', $profile->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('wallet.index', compact('profile', 'transactions'));
    }
}
