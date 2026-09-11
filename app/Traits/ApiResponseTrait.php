<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    public function successResponse(mixed $data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'status_code' => $code,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toIso8601String(),
        ], $code);
    }

    public function errorResponse(string $message = 'Error occurred', int $code = 400, mixed $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'status_code' => $code,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => now()->toIso8601String(),
        ], $code);
    }
}
