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
                return redirect()->route('dashboard')->with('success', 'Welcome back!');
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
            \Log::error('Google OAuth Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'redirect_uri' => config('services.google.redirect'),
                'client_id_exists' => !empty(config('services.google.client_id')),
            ]);
            return redirect()->route('login')->with('error', 'Google authentication failed: ' . $e->getMessage());
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
        
        $primaryDomain = request('primary_domain');
        $preferredRole = request('preferred_role');

        if ($role === 'student' && (empty($primaryDomain) || empty($preferredRole))) {
            return back()->with('error', 'Please select your career domain and preferred role.');
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
            $profile = StudentProfile::create([
                'user_id' => $user->id,
                'bio' => '',
                'portfolio_url' => '',
                'github_url' => '',
                'linkedin_url' => '',
                'reliability_score' => 1.0,
                'primary_domain' => $primaryDomain,
                'preferred_role' => $preferredRole,
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
