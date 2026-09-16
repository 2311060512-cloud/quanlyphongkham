<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class CongGiaoTiepController extends Controller
{
    /**
     * REVERSE PROXY DISPATCHER (Chuyen tiep thong minh den cac Microservices)
     *
     * @param Request $request Request goc tu Client
     * @param string $serviceUrl Key dich vu (xac_thuc, lich_hen, y_te, hoa_don) hoac URL truc tiep
     * @param string $duongDan Duong dan con (subpath) can chuyen tiep
     * @return Response|JsonResponse
     */
    public function chuyenTiep(Request $request, string $serviceUrl, string $duongDan = '')
    {
        // 1. Xac dinh Service Base URL tu config/dich_vu.php hoac URL truc tiep
        $targetBase = config("dich_vu.{$serviceUrl}") ?? $serviceUrl;
        $targetBase = rtrim($targetBase, '/');
        $cleanPath = ltrim($duongDan, '/');
        
        // Dam bao URL dich luon bat dau bang /api neu service con dinh tuyen theo /api
        if (!str_starts_with($cleanPath, 'api/')) {
            $cleanPath = 'api/' . $cleanPath;
        }

        $targetUrl = "{$targetBase}/{$cleanPath}";

        // 2. Thu thap Headers can chuyen tiep
        $headers = [
            'Accept' => 'application/json',
            'X-Gateway' => 'PhongKham-API-Gateway-v1',
            'X-Nguoi-Dung-Id' => (string)$request->header('X-Nguoi-Dung-Id', ''),
            'X-Vai-Tro' => (string)$request->header('X-Vai-Tro', ''),
            'X-User-Id' => (string)$request->header('X-User-Id', $request->header('X-Nguoi-Dung-Id', '')),
            'X-User-Role' => (string)$request->header('X-User-Role', $request->header('X-Vai-Tro', '')),
            'X-User-Email' => (string)$request->header('X-User-Email', ''),
            'X-User-Name' => (string)$request->header('X-User-Name', ''),
        ];

        if ($request->hasHeader('Authorization')) {
            $headers['Authorization'] = $request->header('Authorization');
        }

        $method = strtoupper($request->method());
        $queryParams = $request->query();

        // 3. Ban request sang dich den tuong ung bang Http Client (Illuminate\Support\Facades\Http)
        try {
            $client = Http::withHeaders($headers)->timeout(10);

            $options = ['query' => $queryParams];
            if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                $payload = $request->all();
                if (!empty($payload)) {
                    $options['json'] = $payload;
                }
            }

            $response = $client->send($method, $targetUrl, $options);

            // 4. Tra nguyen van HTTP Status Code va Response JSON tu service con ve cho Client
            return response($response->body(), $response->status())
                ->header('Content-Type', $response->header('Content-Type') ?? 'application/json');

        } catch (\Exception $e) {
            // 5. Xu ly Fallback: Neu service con bi sap / khong ket noi duoc -> Tra ve loi 503 Service Unavailable
            return response()->json([
                'thanh_cong' => false,
                'ma_loi' => 'DICH_VU_NGOAI_TUYEN',
                'thong_diep' => 'Dịch vụ tạm thời không phản hồi.',
                'chi_tiet' => $e->getMessage(),
                'dich_vu_dich' => $targetUrl,
            ], 503);
        }
    }

    /**
     * HEALTH CHECK TOAN HE THONG (Kiem tra liveness cua ca 4 services)
     */
    public function healthCheck(): JsonResponse
    {
        $services = [
            '01_dich_vu_xac_thuc_bac_si' => [
                'url' => config('dich_vu.xac_thuc', 'http://127.0.0.1:8001'),
                'port' => 8001,
                'vai_tro' => 'Xac thuc, Tai khoan, Chuyen khoa, Bac si'
            ],
            '02_dich_vu_benh_nhan_lich_hen' => [
                'url' => config('dich_vu.lich_hen', 'http://127.0.0.1:8002'),
                'port' => 8002,
                'vai_tro' => 'Ho so benh nhan & Chong trung lich bac si'
            ],
            '03_dich_vu_y_te_can_lam_sang' => [
                'url' => config('dich_vu.y_te', 'http://127.0.0.1:8003'),
                'port' => 8003,
                'vai_tro' => 'Danh muc dich vu & Ke can lam sang'
            ],
            '04_dich_vu_hoa_don_thanh_toan' => [
                'url' => config('dich_vu.hoa_don', 'http://127.0.0.1:8004'),
                'port' => 8004,
                'vai_tro' => 'Tong hop hoa don tu dong & Thanh toan'
            ],
        ];

        $ketQua = [];
        $tatCaHoatDong = true;

        foreach ($services as $key => $s) {
            $batDau = microtime(true);
            try {
                $res = Http::timeout(2)->get($s['url']);
                $latencyMs = round((microtime(true) - $batDau) * 1000, 2);
                $isOk = $res->successful();
                $ketQua[$key] = [
                    'port' => $s['port'],
                    'vai_tro' => $s['vai_tro'],
                    'trang_thai' => $isOk ? 'ONLINE' : 'ERROR',
                    'do_tre_ms' => $latencyMs,
                ];
                if (!$isOk) $tatCaHoatDong = false;
            } catch (\Exception $e) {
                $tatCaHoatDong = false;
                $ketQua[$key] = [
                    'port' => $s['port'],
                    'vai_tro' => $s['vai_tro'],
                    'trang_thai' => 'OFFLINE',
                    'thong_bao_loi' => 'Chua khoi dong dich vu tren port ' . $s['port'],
                ];
            }
        }

        return response()->json([
            'gateway' => '00_api_gateway (Port 8000)',
            'trang_thai_chung' => $tatCaHoatDong ? 'HOAT_DONG_TOT' : 'MOT_SO_DICH_VU_NGOAI_TUYEN',
            'thoi_gian' => now()->toIso8601String(),
            'danh_sach_dich_vu' => $ketQua
        ]);
    }
}
