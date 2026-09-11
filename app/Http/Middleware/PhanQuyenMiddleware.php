<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PhanQuyenMiddleware
{
    public function handle(Request $request, Closure $next, ...$cacVaiTro): Response
    {
        $taiKhoan = $request->user();

        if (!$taiKhoan) {
            return response()->json([
                'thanh_cong' => false,
                'ma_trang_thai' => 401,
                'thong_bao' => 'Vui lòng đăng nhập để tiếp tục.',
            ], 401);
        }

        $maVaiTro = $taiKhoan->vaiTro ? strtoupper($taiKhoan->vaiTro->ma_vai_tro) : strtoupper($taiKhoan->ma_vai_tro ?? 'BENH_NHAN');
        $cacVaiTroChoPhep = array_map('strtoupper', $cacVaiTro);

        if (!in_array($maVaiTro, $cacVaiTroChoPhep)) {
            return response()->json([
                'thanh_cong' => false,
                'ma_trang_thai' => 403,
                'thong_bao' => 'Bạn không có quyền thực hiện thao tác này (Yêu cầu vai trò: ' . implode(', ', $cacVaiTro) . ').',
            ], 403);
        }

        return $next($request);
    }
}
