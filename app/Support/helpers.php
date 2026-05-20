<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (! function_exists('can')) {
    function can(string $module, string $permission): bool
    {
        $user = Auth::user();

        if (! $user || ! $user->role || ! is_array($user->role->permissions)) {
            throw new HttpResponseException(response()->json([
                'status' => 'error',
                'message' => 'Forbidden: missing role or permissions'
            ], 403));
        }

        $permissions = $user->role->permissions;

        if (! isset($permissions[$module]) || ! in_array($permission, $permissions[$module], true)) {
            throw new HttpResponseException(response()->json([
                'status' => 'error',
                'message' => 'Forbidden: insufficient permissions'
            ], 403));
        }

        return true;
    }
}

