<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotOnboarded
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && !$user->is_onboarded) {
            // Allow access to onboarding routes and logout
            if ($request->is('student/onboarding*') || $request->is('startup/onboarding*') || $request->is('logout')) {
                return $next($request);
            }

            if ($user->isStudent()) {
                return redirect()->route('student.onboarding');
            }

            if ($user->isStartup()) {
                return redirect()->route('startup.onboarding');
            }
        }

        return $next($request);
    }
}
