<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PhanQuyenGatewayMiddleware
{
    /**
     * PHAN QUYEN TAP TRUNG (ROLE-BASED AUTHORIZATION)
     * Kiem tra X-Vai-Tro co nam trong danh sach cac vai tro duoc phep hay khong
     * Vi du: phan_quyen:ADMIN hoac phan_quyen:ADMIN,BAC_SI
     */
    public function handle(Request $request, Closure $next, ...$vaiTroHopLe): Response
    {
        $vaiTroHienTai = $request->header('X-Vai-Tro') ?: $request->header('X-User-Role');

        if (!$vaiTroHienTai) {
            return response()->json([
                'thanh_cong' => false,
                'ma_loi' => 'CHUA_XAC_DINH_VAI_TRO',
                'thong_diep' => 'API Gateway: Khong xac dinh duoc vai tro nguoi dung.'
            ], 403);
        }

        // Ho tro ca truong hop truyen chuoi cach nhau boi dau phay "ADMIN,BAC_SI"
        $danhSachQuyen = [];
        foreach ($vaiTroHopLe as $item) {
            foreach (explode(',', $item) as $role) {
                $danhSachQuyen[] = trim($role);
            }
        }

        if (!in_array($vaiTroHienTai, $danhSachQuyen)) {
            return response()->json([
                'thanh_cong' => false,
                'ma_loi' => 'KHONG_CO_QUYEN',
                'thong_diep' => 'API Gateway: Ban khong co quyen thuc hien thao tac nay.',
                'yeu_cau_vai_tro' => $danhSachQuyen,
                'vai_tro_hien_tai' => $vaiTroHienTai
            ], 403);
        }

        return $next($request);
    }
}
