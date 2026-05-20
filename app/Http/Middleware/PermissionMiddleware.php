<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $module, string $permission): Response
    {
        $user = Auth::user();

        if (! $user || ! $user->role || ! is_array($user->role->permissions)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden: missing role or permissions'
            ], 403);
        }

        $permissions = $user->role->permissions;

        if (! isset($permissions[$module]) || ! in_array($permission, $permissions[$module], true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden: insufficient permissions'
            ], 403);
        }

        return $next($request);
    }
}