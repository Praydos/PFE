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

        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Accès non autorisé.'], 403);
            }
            abort(403, 'Accès non autorisé.');
        }

        // Admin has full access to all routes
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Abo has read-only access
        if ($user->role === 'abo' && in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'])) {
            $routeName = $request->route() ? $request->route()->getName() : '';
            if ($routeName && (str_ends_with($routeName, '.create') || str_ends_with($routeName, '.edit'))) {
                if ($request->expectsJson()) return response()->json(['error' => 'Accès en lecture seule.'], 403);
                abort(403, 'Accès en lecture seule.');
            }
            return $next($request);
        }

        if (! in_array($user->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Accès non autorisé.'], 403);
            }
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}