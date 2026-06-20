<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', 'min:3'],
            'role' => ['required', 'in:student,startup'],
            'primary_domain' => ['required_if:role,student', 'nullable', 'string'],
            'preferred_role' => ['required_if:role,student', 'nullable', 'string'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_verified' => $request->role === 'student' ? true : false,
        ]);

        // Create profile based on role
        if ($user->isStudent()) {
            $profile = \App\Models\StudentProfile::create([
                'user_id' => $user->id,
                'bio' => null,
                'portfolio_links' => [],
                'reliability_score' => 0,
                'primary_domain' => $request->primary_domain,
                'preferred_role' => $request->preferred_role,
            ]);
        } elseif ($user->isStartup()) {
            \App\Models\StartupProfile::create([
                'user_id' => $user->id,
                'company_name' => $request->name,
                'description' => null,
                'website' => null,
                'is_verified' => false,
                'credibility_score' => 0,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
