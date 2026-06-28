<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentPro
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !$user->isStudentPro()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Premium Feature',
                    'message' => 'This action requires an active InternGrowth Pro subscription.',
                ], 403);
            }

            return redirect()->route('pricing.index')
                ->with('error', '⚡ This is a premium feature! Upgrade to InternGrowth Pro to get instant access.');
        }

        return $next($request);
    }
}
