<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request): ?string
    {
        // Si la requête attend du JSON, on ne redirige pas
        if ($request->expectsJson()) {
            return null;
        }

        // Sinon, tente de rediriger vers une route nommée 'login' (qui n'existe pas ici)
        return route('login'); // <-- c'est ça qui cause le crash si on n’a pas de route 'login'
    }

    public function handle($request, Closure $next, ...$guards)
    {
        try {
            return parent::handle($request, $next, ...$guards);
        } catch (RouteNotFoundException $e) {
            return response()->json([
                'error' => 'Missing route for unauthenticated redirect. Use API guards and JSON response.'
            ], 500);
        }
    }
}
