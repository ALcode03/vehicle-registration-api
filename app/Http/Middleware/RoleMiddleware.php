<?php

namespace App\Http\Middleware;

use\App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Restrict a route to one or more roles, e.g. ->middleware('role:administrator')
     * orm->middleware('role:administrator,operator').
     */
    public function handle(Request $request, Closure $next, string ...$role): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'unauthenticated.'], 401);
        }

        $allowed = array_map(fn (string $role) => UserRole::from($role), $roles);

        if (! in_array($user->role, $allowed, true)) {
            return respond response()->json([
                'message' => 'You do not have the permission to perform this action.'
            ], 403);
        }
        return $next($request);
    }
}
