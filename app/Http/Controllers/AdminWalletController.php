<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StartupProfile;
use App\Models\StudentProfile;
use App\Models\Transaction;

class AdminWalletController extends Controller
{
    public function manageWallets()
    {
        $startups = StartupProfile::with('user')->get();
        $students = StudentProfile::with('user')->get();
        
        return view('admin.wallets', compact('startups', 'students'));
    }
    
    public function addMoney(Request $request)
    {
        $validated = $request->validate([
            'user_type' => 'required|in:startup,student',
            'user_id' => 'required|integer',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string'
        ]);
        
        if ($validated['user_type'] === 'startup') {
            $profile = StartupProfile::findOrFail($validated['user_id']);
        } else {
            $profile = StudentProfile::findOrFail($validated['user_id']);
        }
        
        $profile->increment('wallet_balance', $validated['amount']);
        
        Transaction::create([
            'user_type' => $validated['user_type'],
            'user_id' => $validated['user_id'],
            'type' => 'credit',
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? 'Money added by admin',
            'reference_id' => 'admin_' . time()
        ]);
        
        return back()->with('success', '₹' . $validated['amount'] . ' added successfully!');
    }
    
    public function deductMoney(Request $request)
    {
        $validated = $request->validate([
            'user_type' => 'required|in:startup,student',
            'user_id' => 'required|integer',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string'
        ]);
        
        if ($validated['user_type'] === 'startup') {
            $profile = StartupProfile::findOrFail($validated['user_id']);
        } else {
            $profile = StudentProfile::findOrFail($validated['user_id']);
        }
        
        if ($profile->wallet_balance < $validated['amount']) {
            return back()->with('error', 'Insufficient balance!');
        }
        
        $profile->decrement('wallet_balance', $validated['amount']);
        
        Transaction::create([
            'user_type' => $validated['user_type'],
            'user_id' => $validated['user_id'],
            'type' => 'debit',
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? 'Money deducted by admin',
            'reference_id' => 'admin_' . time()
        ]);
        
        return back()->with('success', '₹' . $validated['amount'] . ' deducted successfully!');
    }
}
