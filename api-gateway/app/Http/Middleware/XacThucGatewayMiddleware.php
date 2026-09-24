<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class XacThucGatewayMiddleware
{
    /**
     * XAC THUC BEARER TOKEN TAP TRUNG TAI API GATEWAY
     * 1. Bat Authorization: Bearer <token> tu request
     * 2. Goi sang Service 01 (http://127.0.0.1:8001/api/v1/xac-thuc/thong-tin) de kiem tra
     * 3. Inject X-Nguoi-Dung-Id va X-Vai-Tro vao Request Headers
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json([
                'thanh_cong' => false,
                'ma_loi' => 'CHUA_DANG_NHAP',
                'thong_diep' => 'API Gateway: Vui long cung cap Bearer Token hop le de truy cap.'
            ], 401);
        }

        $urlXacThuc = rtrim(config('dich_vu.xac_thuc', 'http://127.0.0.1:8001'), '/');

        try {
            // Goi sang Service 01 de xac thuc token
            $response = Http::withHeaders([
                'Authorization' => $authHeader,
                'Accept' => 'application/json'
            ])->timeout(5)->get("{$urlXacThuc}/api/v1/xac-thuc/thong-tin");

            if (!$response->successful() || !isset($response['du_lieu'])) {
                return response()->json([
                    'thanh_cong' => false,
                    'ma_loi' => 'TOKEN_KHONG_HOP_LE',
                    'thong_diep' => 'API Gateway: Token khong hop le hoac da het han.'
                ], 401);
            }

            $nguoiDung = $response['du_lieu'];
            $userId = (string)($nguoiDung['id'] ?? '');
            $vaiTro = (string)($nguoiDung['vai_tro'] ?? '');

            // Inject thong tin vao Request Headers theo dung yeu cau
            $request->headers->set('X-Nguoi-Dung-Id', $userId);
            $request->headers->set('X-Vai-Tro', $vaiTro);

            // Ho tro cac header quy uoc cu de tuong thich toan dien
            $request->headers->set('X-User-Id', $userId);
            $request->headers->set('X-User-Role', $vaiTro);
            $request->headers->set('X-User-Email', (string)($nguoiDung['email'] ?? ''));
            $request->headers->set('X-User-Name', (string)($nguoiDung['ho_ten'] ?? ''));

            return $next($request);

        } catch (\Exception $e) {
            return response()->json([
                'thanh_cong' => false,
                'ma_loi' => 'DICH_VU_XAC_THUC_NGOAI_TUYEN',
                'thong_diep' => 'API Gateway: Khong the ket noi den Dich vu Xac thuc de kiem tra token.',
                'chi_tiet' => $e->getMessage()
            ], 503);
        }
    }
}
