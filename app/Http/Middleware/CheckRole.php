<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'status_code' => 401,
                'message' => 'Vui lòng đăng nhập để tiếp tục.',
            ], 401);
        }

        $userRole = $user->role ? strtoupper($user->role->code) : strtoupper($user->role_code ?? 'CUSTOMER');
        $allowedRoles = array_map('strtoupper', $roles);

        if (!in_array($userRole, $allowedRoles)) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Bạn không có quyền truy cập tài nguyên này (Yêu cầu quyền: ' . implode(', ', $roles) . ').',
            ], 403);
        }

        return $next($request);
    }
}
