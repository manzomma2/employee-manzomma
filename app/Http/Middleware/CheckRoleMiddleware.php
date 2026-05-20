<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = auth()->user();

        if (! $user || ! $user->role_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: no role assigned'
            ], 401);
        }

        $allowedRoleIds = Role::query()
            ->whereIn('name', $roles)
            ->pluck('id')
            ->all();

        if (! in_array($user->role_id, $allowedRoleIds, true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: role not permitted'
            ], 401);
        }

        return $next($request);
    }
}