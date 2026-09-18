<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Tu peux personnaliser ici ou simplement renvoyer une réponse JSON
                return response()->json(['message' => 'Already authenticated'], 403);
            }
        }

        return $next($request);
    }
}
