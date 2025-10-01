<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Parse roles into an array
        $rolesArray = explode('|', $roles);

        foreach ($rolesArray as $role) {

            if ($user->hasRole($role)) {
                return $next($request); // Allow access on the first valid role
            }
        }

        // If no valid role is found
        abort(403, 'Unauthorized');
    }
}
