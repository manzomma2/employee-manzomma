<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormatApiResponse
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only format JSON/API responses
        if ($request->expectsJson() || $request->is('api/*')) {
            // If it's already a JsonResponse, inspect and wrap if necessary
            if ($response instanceof JsonResponse) {
                $original = $response->getData();

                // If already following our pattern, return as-is
                if (is_object($original) && property_exists($original, 'status')) {
                    return $response;
                }

                $status = $response->getStatusCode();

                // For no-content responses, keep status
                if ($status === 204) {
                    return $response;
                }

                $payload = [
                    'status' => $status >= 200 && $status < 300 ? 'success' : 'error',
                    'message' => $status >= 200 && $status < 300 ? '' : ($original->message ?? ''),
                    'data' => $original,
                ];

                return response()->json($payload, $status);
            }
        }

        return $response;
    }
}
