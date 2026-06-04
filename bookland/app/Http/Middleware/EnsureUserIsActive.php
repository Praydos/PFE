<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return $next($request);
        }

        if ($user->fresh()?->is_active) {
            return $next($request);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Votre compte est inactif. Veuillez contacter un administrateur.',
            ], 403);
        }

        return redirect()
            ->route('login')
            ->withErrors([
                'email' => 'Votre compte est inactif. Veuillez contacter un administrateur.',
            ]);
    }
}
