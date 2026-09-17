<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XacThucService02Middleware
{
    /**
     * Rào chắn xác thực bắt buộc tại Microservice 02
     * Kiểm tra Header định danh từ Gateway: X-User-Id hoặc X-Nguoi-Dung-Id
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->header('X-User-Id') ?: $request->header('X-Nguoi-Dung-Id');

        if (empty($userId)) {
            return response()->json([
                'thanh_cong' => false,
                'ma_loi' => 'CHUA_DANG_NHAP',
                'thong_diep' => 'Vui lòng đăng nhập tài khoản trước khi thực hiện chức năng này.',
            ], 401);
        }

        return $next($request);
    }
}
