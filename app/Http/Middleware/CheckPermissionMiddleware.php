<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,string $permission): Response
    {
        $user = $request->user()->load('role.permission'); 

        if (!$user->hasPermission($permission)) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        return $next($request);
    }
}
