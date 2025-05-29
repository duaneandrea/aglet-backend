<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required',
                    'errors' => ['auth' => ['Please login to continue']]
                ], 401);
            }

            return redirect()->route('login')
                ->with('error', 'Please login to access this page.');
        }

        return $next($request);
    }
} 