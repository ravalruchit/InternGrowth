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
            // Auto-onboard if they are already verified or have filled basics
            if ($user->isStudent()) {
                $profile = $user->studentProfile;
                if ($profile && ($profile->is_verified || ($profile->college_name && $profile->graduation_year))) {
                    $user->update(['is_onboarded' => true]);
                    return $next($request);
                }
            } elseif ($user->isStartup()) {
                $profile = $user->startupProfile;
                if ($profile && ($profile->is_verified || $profile->company_name)) {
                    $user->update(['is_onboarded' => true]);
                    return $next($request);
                }
            }

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
