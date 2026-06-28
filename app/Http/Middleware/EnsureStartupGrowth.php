<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStartupGrowth
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !$user->isStartupGrowth()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Premium Feature',
                    'message' => 'This action requires an active Startup Growth subscription.',
                ], 403);
            }

            return redirect()->route('pricing.index')
                ->with('error', '💼 This action is exclusive to Startup Growth subscribers. Upgrade to get unlimited features.');
        }

        return $next($request);
    }
}
