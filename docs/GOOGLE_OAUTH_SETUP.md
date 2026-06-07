# Google OAuth Setup Guide for InternGrowth

This guide will help you set up Google OAuth authentication for your InternGrowth application.

## Step 1: Install Laravel Socialite

Run this command in your terminal:

```bash
composer require laravel/socialite
```

## Step 2: Get Google OAuth Credentials

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the Google+ API:
   - Go to "APIs & Services" > "Library"
   - Search for "Google+ API"
   - Click "Enable"
4. Create OAuth credentials:
   - Go to "APIs & Services" > "Credentials"
   - Click "Create Credentials" > "OAuth client ID"
   - Choose "Web application"
   - Add authorized redirect URIs:
     - For local: `http://localhost:8000/auth/google/callback`
     - For production: `https://yourdomain.com/auth/google/callback`
   - Click "Create"
5. Copy your Client ID and Client Secret

## Step 3: Configure Environment Variables

Add these to your `.env` file:

```env
GOOGLE_CLIENT_ID=your-google-client-id-here
GOOGLE_CLIENT_SECRET=your-google-client-secret-here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

## Step 4: Update config/services.php

Add Google configuration:

```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```

## Step 5: Create Google Auth Controller

Create a new controller:

```bash
php artisan make:controller Auth/GoogleAuthController
```

Add this code to `app/Http/Controllers/Auth/GoogleAuthController.php`:

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\StartupProfile;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google for authentication
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists
            $user = User::where('email', $googleUser->email)->first();
            
            if ($user) {
                // User exists, just log them in
                Auth::login($user);
                return redirect()->route('dashboard');
            }
            
            // New user - need to determine role
            // Store Google user data in session and redirect to role selection
            session([
                'google_user' => [
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                ]
            ]);
            
            return redirect()->route('auth.google.role');
            
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Failed to authenticate with Google. Please try again.');
        }
    }
    
    /**
     * Show role selection page for new Google users
     */
    public function showRoleSelection()
    {
        if (!session()->has('google_user')) {
            return redirect()->route('login');
        }
        
        return view('auth.google-role');
    }
    
    /**
     * Complete Google registration with role
     */
    public function completeRegistration()
    {
        $googleUser = session('google_user');
        
        if (!$googleUser) {
            return redirect()->route('login');
        }
        
        $role = request('role');
        
        if (!in_array($role, ['student', 'startup'])) {
            return back()->with('error', 'Please select a valid role.');
        }
        
        // Create user
        $user = User::create([
            'name' => $googleUser['name'],
            'email' => $googleUser['email'],
            'password' => bcrypt(Str::random(32)), // Random password since they use Google
            'role' => $role,
            'email_verified_at' => now(), // Auto-verify Google users
        ]);
        
        // Create profile based on role
        if ($role === 'student') {
            StudentProfile::create([
                'user_id' => $user->id,
                'bio' => '',
                'skills' => json_encode([]),
                'education' => '',
                'experience' => '',
                'portfolio_url' => '',
                'github_url' => '',
                'linkedin_url' => '',
                'reliability_score' => 1.0,
            ]);
        } else {
            StartupProfile::create([
                'user_id' => $user->id,
                'company_name' => '',
                'description' => '',
                'industry' => '',
                'website' => '',
                'location' => '',
                'team_size' => '',
                'founded_year' => null,
                'credibility_score' => 1.0,
                'is_verified' => false,
            ]);
        }
        
        // Clear session
        session()->forget('google_user');
        
        // Log in user
        Auth::login($user);
        
        return redirect()->route('dashboard')->with('success', 'Welcome to InternGrowth! Please complete your profile.');
    }
}
```

## Step 6: Create Role Selection View

Create `resources/views/auth/google-role.blade.php`:

```blade
<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-3xl font-black font-poppins bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
            Welcome! 🎉
        </h2>
        <p class="text-gray-600 text-sm font-medium">Choose your account type to continue</p>
    </div>

    <form method="POST" action="{{ route('auth.google.complete') }}" class="space-y-4">
        @csrf
        
        <div class="space-y-3">
            <label class="block">
                <input type="radio" name="role" value="student" class="sr-only peer" required>
                <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-indigo-300 transition-all">
                    <div class="flex items-center space-x-3">
                        <div class="text-3xl">🎓</div>
                        <div>
                            <div class="font-bold text-gray-900">Student</div>
                            <div class="text-sm text-gray-600">Looking for opportunities</div>
                        </div>
                    </div>
                </div>
            </label>
            
            <label class="block">
                <input type="radio" name="role" value="startup" class="sr-only peer" required>
                <div class="p-4 border-2 border-gray-200 rounded-xl cursor-pointer peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-indigo-300 transition-all">
                    <div class="flex items-center space-x-3">
                        <div class="text-3xl">🚀</div>
                        <div>
                            <div class="font-bold text-gray-900">Startup</div>
                            <div class="text-sm text-gray-600">Looking for talent</div>
                        </div>
                    </div>
                </div>
            </label>
        </div>

        <button type="submit" class="group relative w-full py-3 rounded-xl text-white font-bold overflow-hidden transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
            <span class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 animate-gradient bg-[length:200%_200%]"></span>
            <span class="relative">Continue</span>
        </button>
    </form>
</x-guest-layout>
```

## Step 7: Add Routes

Add these routes to `routes/web.php`:

```php
use App\Http\Controllers\Auth\GoogleAuthController;

// Google OAuth routes
Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
Route::get('auth/google/role', [GoogleAuthController::class, 'showRoleSelection'])->name('auth.google.role');
Route::post('auth/google/complete', [GoogleAuthController::class, 'completeRegistration'])->name('auth.google.complete');
```

## Step 8: Test the Integration

1. Start your Laravel server: `php artisan serve`
2. Go to the login page
3. Click "Continue with Google"
4. Sign in with your Google account
5. Select your role (Student or Startup)
6. You should be logged in!

## Troubleshooting

### Error: "redirect_uri_mismatch"
- Make sure the redirect URI in Google Console matches exactly with your `.env` file
- Include the protocol (http:// or https://)
- Don't add trailing slashes

### Error: "Client ID not found"
- Double-check your `.env` file has the correct credentials
- Run `php artisan config:clear` to clear cached config

### Users can't log in after first registration
- Make sure the email is being saved correctly
- Check that `email_verified_at` is set for Google users

## Security Notes

1. Never commit your `.env` file to version control
2. Use different credentials for development and production
3. Always use HTTPS in production
4. Regularly rotate your client secrets

## Additional Features (Optional)

### Store Google Avatar
You can save the user's Google profile picture:

```php
// In GoogleAuthController
$user->update([
    'avatar' => $googleUser->avatar
]);
```

### Add Google ID to users table
Create a migration to add `google_id` column:

```bash
php artisan make:migration add_google_id_to_users_table
```

```php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('google_id')->nullable()->unique()->after('email');
    });
}
```

Then update the controller to save it:

```php
$user = User::create([
    'google_id' => $googleUser->id,
    // ... other fields
]);
```

---

That's it! Your Google OAuth is now set up. Users can sign in with their Google accounts! 🎉
