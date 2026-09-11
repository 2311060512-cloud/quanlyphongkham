<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY') ?? $request->query('api_key');
        $validKey = config('app.api_key', 'HOTEL_SECRET_API_KEY_2026');

        if (!$apiKey || $apiKey !== $validKey) {
            return response()->json([
                'success' => false,
                'status_code' => 401,
                'message' => 'API Key không hợp lệ hoặc bị thiếu trong Request Header (X-API-KEY).',
            ], 401);
        }

        return $next($request);
    }
}
