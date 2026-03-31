<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Role-based access middleware.
 *
 * Usage in routes:  ->middleware('role:admin,rbo')
 *
 * ── Registration ────────────────────────────────────────────────────────────
 *
 * Laravel 11 (bootstrap/app.php):
 *   ->withMiddleware(function (Middleware $middleware) {
 *       $middleware->alias(['role' => \App\Http\Middleware\RoleMiddleware::class]);
 *   })
 * ────────────────────────────────────────────────────────────────────────────
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = auth()->user();

        if (! $user || ! in_array($user->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Accès non autorisé.'], 403);
            }
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}