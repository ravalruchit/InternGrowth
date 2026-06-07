# 💰 Payment System - Quick Setup Guide

## ⚡ Fast Implementation (2-3 hours)

### Step 1: Add Razorpay Config (2 minutes)

Add to `.env`:
```env
RAZORPAY_KEY=rzp_test_your_key_here
RAZORPAY_SECRET=your_secret_here
PLATFORM_FEE_PERCENTAGE=10
```

### Step 2: Run These Commands (5 minutes)

```bash
# Create migrations
php artisan make:migration add_wallet_balance_to_startup_profiles
php artisan make:migration add_wallet_balance_to_student_profiles
php artisan make:migration create_transactions_table
php artisan make:migration create_escrows_table
php artisan make:migration add_escrow_amount_to_tasks

# Create models
php artisan make:model Transaction
php artisan make:model Escrow

# Create controllers
php artisan make:controller WalletController
php artisan make:controller PaymentController
php artisan make:controller WithdrawalController
```

### Step 3: Database Migrations

**File: `database/migrations/xxxx_add_wallet_balance_to_startup_profiles.php`**
```php
public function up()
{
    Schema::table('startup_profiles', function (Blueprint $table) {
        $table->decimal('wallet_balance', 10, 2)->default(0)->after('is_verified');
    });
}
```

**File: `database/migrations/xxxx_add_wallet_balance_to_student_profiles.php`**
```php
public function up()
{
    Schema::table('student_profiles', function (Blueprint $table) {
        $table->decimal('wallet_balance', 10, 2)->default(0)->after('reliability_score');
    });
}
```

**File: `database/migrations/xxxx_create_transactions_table.php`**
```php
public function up()
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->string('user_type'); // startup or student
        $table->unsignedBigInteger('user_id');
        $table->enum('type', ['credit', 'debit', 'escrow_lock', 'escrow_release', 'withdrawal']);
        $table->decimal('amount', 10, 2);
        $table->string('description');
        $table->string('reference_id')->nullable();
        $table->timestamps();
    });
}
```

**File: `database/migrations/xxxx_create_escrows_table.php`**
```php
public function up()
{
    Schema::create('escrows', function (Blueprint $table) {
        $table->id();
        $table->foreignId('task_id')->constrained()->cascadeOnDelete();
        $table->decimal('amount', 10, 2);
        $table->enum('status', ['locked', 'released', 'refunded'])->default('locked');
        $table->timestamps();
    });
}
```

**File: `database/migrations/xxxx_add_escrow_amount_to_tasks.php`**
```php
public function up()
{
    Schema::table('tasks', function (Blueprint $table) {
        $table->decimal('escrow_amount', 10, 2)->default(0)->after('stipend');
        $table->boolean('escrow_locked')->default(false)->after('escrow_amount');
    });
}
```

Run: `php artisan migrate`

---

## Core Logic Implementation

### 1. Update Task Model

Add to `app/Models/Task.php`:
```php
protected $fillable = [
    'startup_profile_id', 'title', 'description', 'requirements', 
    'required_skills', 'reward_points', 'stipend', 'escrow_amount', 
    'escrow_locked', 'status', 'is_flagged'
];

public function escrow()
{
    return $this->hasOne(Escrow::class);
}
```

### 2. Create Escrow Model

`app/Models/Escrow.php`:
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Escrow extends Model
{
    protected $fillable = ['task_id', 'amount', 'status'];
    
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
```

### 3. Create Transaction Model

`app/Models/Transaction.php`:
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_type', 'user_id', 'type', 'amount', 
        'description', 'reference_id'
    ];
}
```

### 4. Update TaskController - Lock Escrow on Task Creation

In `app/Http/Controllers/TaskController.php` `store()` method:
```php
public function store(Request $request)
{
    // ... existing validation ...
    
    $startup = auth()->user()->startupProfile;
    $escrowAmount = $validated['stipend'] ?? 0;
    
    // Check if startup has sufficient balance
    if ($startup->wallet_balance < $escrowAmount) {
        return back()->with('error', 'Insufficient wallet balance. Please add money first.');
    }
    
    // Create task
    $validated['startup_profile_id'] = $startup->id;
    $validated['escrow_amount'] = $escrowAmount;
    $validated['escrow_locked'] = true;
    $task = $this->repository->create($validated);
    
    // Lock money in escrow
    if ($escrowAmount > 0) {
        Escrow::create([
            'task_id' => $task->id,
            'amount' => $escrowAmount,
            'status' => 'locked'
        ]);
        
        // Deduct from startup wallet
        $startup->decrement('wallet_balance', $escrowAmount);
        
        // Record transaction
        Transaction::create([
            'user_type' => 'startup',
            'user_id' => $startup->id,
            'type' => 'escrow_lock',
            'amount' => $escrowAmount,
            'description' => "Escrow locked for task: {$task->title}",
            'reference_id' => "task_{$task->id}"
        ]);
    }
    
    return redirect()->route('startup.dashboard')->with('success', 'Task created and ₹' . $escrowAmount . ' locked in escrow');
}
```

### 5. Update SubmissionController - Release Escrow on Accept

In `app/Http/Controllers/SubmissionController.php` `accept()` method:
```php
public function accept(Request $request, $id)
{
    $submission = Submission::with('application.task', 'application.student')->findOrFail($id);
    $submission->update(['status' => 'accepted']);
    
    $task = $submission->application->task;
    $task->update(['status' => 'completed']);
    
    // Award points
    $wallet = PointsWallet::firstOrCreate(
        ['student_profile_id' => $submission->application->student_profile_id],
        ['balance' => 0]
    );
    $points = $task->reward_points;
    $wallet->increment('balance', $points);
    
    // Release escrow money
    $escrow = $task->escrow;
    if ($escrow && $escrow->status === 'locked') {
        $platformFee = $escrow->amount * (env('PLATFORM_FEE_PERCENTAGE', 10) / 100);
        $studentAmount = $escrow->amount - $platformFee;
        
        // Add to student wallet
        $studentProfile = $submission->application->student;
        $studentProfile->increment('wallet_balance', $studentAmount);
        
        // Update escrow status
        $escrow->update(['status' => 'released']);
        
        // Record transactions
        Transaction::create([
            'user_type' => 'student',
            'user_id' => $studentProfile->id,
            'type' => 'credit',
            'amount' => $studentAmount,
            'description' => "Payment received for task: {$task->title}",
            'reference_id' => "task_{$task->id}"
        ]);
        
        Transaction::create([
            'user_type' => 'platform',
            'user_id' => 0,
            'type' => 'credit',
            'amount' => $platformFee,
            'description' => "Platform fee from task: {$task->title}",
            'reference_id' => "task_{$task->id}"
        ]);
    }
    
    // Issue certificate
    $certificateNumber = 'CERT-' . strtoupper(uniqid());
    \App\Models\Certificate::create([
        'student_profile_id' => $submission->application->student_profile_id,
        'task_id' => $task->id,
        'certificate_number' => $certificateNumber,
        'issued_at' => now()
    ]);
    
    Notification::create([
        'user_id' => $submission->application->student->user_id,
        'title' => 'Payment Received',
        'message' => "You earned {$points} points and ₹{$studentAmount}!",
        'type' => 'success'
    ]);
    
    return back()->with('success', 'Submission accepted, ₹' . $studentAmount . ' released to student!');
}
```

---

## Razorpay Integration (Add Money to Wallet)

### Create Payment Controller

`app/Http/Controllers/PaymentController.php`:
```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\Transaction;

class PaymentController extends Controller
{
    public function addMoney()
    {
        return view('payments.add-money');
    }
    
    public function createOrder(Request $request)
    {
        $amount = $request->amount * 100; // Convert to paise
        
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $order = $api->order->create([
            'amount' => $amount,
            'currency' => 'INR',
            'receipt' => 'order_' . time()
        ]);
        
        return response()->json($order);
    }
    
    public function verifyPayment(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        
        try {
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];
            
            $api->utility->verifyPaymentSignature($attributes);
            
            // Payment verified - add to wallet
            $amount = $request->amount;
            $startup = auth()->user()->startupProfile;
            $startup->increment('wallet_balance', $amount);
            
            Transaction::create([
                'user_type' => 'startup',
                'user_id' => $startup->id,
                'type' => 'credit',
                'amount' => $amount,
                'description' => 'Money added to wallet',
                'reference_id' => $request->razorpay_payment_id
            ]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
```

---

## Routes

Add to `routes/web.php`:
```php
// Payment routes
Route::middleware(['auth', 'role:startup'])->group(function () {
    Route::get('/wallet/add-money', [PaymentController::class, 'addMoney'])->name('wallet.add');
    Route::post('/wallet/create-order', [PaymentController::class, 'createOrder'])->name('wallet.create-order');
    Route::post('/wallet/verify', [PaymentController::class, 'verifyPayment'])->name('wallet.verify');
});
```

---

## Frontend - Add Money Page

Create `resources/views/payments/add-money.blade.php`:
```html
<x-app-layout>
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Add Money to Wallet</h1>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <p class="mb-4">Current Balance: ₹{{ auth()->user()->startupProfile->wallet_balance }}</p>
            
            <input type="number" id="amount" placeholder="Enter amount" class="border p-2 rounded w-full mb-4">
            <button onclick="payNow()" class="bg-indigo-600 text-white px-6 py-2 rounded">Pay Now</button>
        </div>
    </div>
    
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        function payNow() {
            const amount = document.getElementById('amount').value;
            
            fetch('/wallet/create-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ amount: amount })
            })
            .then(res => res.json())
            .then(order => {
                const options = {
                    key: '{{ env("RAZORPAY_KEY") }}',
                    amount: order.amount,
                    currency: 'INR',
                    order_id: order.id,
                    handler: function(response) {
                        fetch('/wallet/verify', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_signature: response.razorpay_signature,
                                amount: amount
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if(data.success) {
                                alert('Payment successful!');
                                location.reload();
                            }
                        });
                    }
                };
                
                const rzp = new Razorpay(options);
                rzp.open();
            });
        }
    </script>
</x-app-layout>
```

---

## Quick Setup Checklist

- [ ] Add Razorpay keys to `.env`
- [ ] Run all migrations
- [ ] Update Task model
- [ ] Create Escrow and Transaction models
- [ ] Update TaskController (lock escrow)
- [ ] Update SubmissionController (release escrow)
- [ ] Create PaymentController
- [ ] Add routes
- [ ] Create add-money view
- [ ] Test with Razorpay test mode

---

## Test Flow

1. Startup adds ₹1000 to wallet
2. Creates task with ₹500 stipend → ₹500 locked in escrow
3. Student submits work
4. Startup accepts → ₹450 to student (₹50 platform fee)
5. Student can withdraw ₹450

---

This is the core system! Implement these files and you'll have a working payment system in 2-3 hours!
