<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class GatewayController extends Controller
{
    /**
     * Bang anh xa Service theo tien to URL
     */
    protected function getServiceMap(): array
    {
        return [
            // Service 01: Xac thuc & Bac si
            'xac-thuc' => config('services.dich_vu_xac_thuc', 'http://127.0.0.1:8001'),
            'bac-si' => config('services.dich_vu_xac_thuc', 'http://127.0.0.1:8001'),
            'chuyen-khoa' => config('services.dich_vu_xac_thuc', 'http://127.0.0.1:8001'),

            // Service 02: Benh nhan & Lich hen
            'benh-nhan' => config('services.dich_vu_lich_hen', 'http://127.0.0.1:8002'),
            'lich-hen' => config('services.dich_vu_lich_hen', 'http://127.0.0.1:8002'),

            // Service 03: Dich vu Y te & Can lam sang
            'dich-vu' => config('services.dich_vu_y_te', 'http://127.0.0.1:8003'),

            // Service 04: Hoa don & Thanh toan
            'hoa-don' => config('services.dich_vu_hoa_don', 'http://127.0.0.1:8004'),
        ];
    }

    /**
     * REVERSE PROXY DISPATCHER
     */
    public function chuyenTiep(Request $request, string $prefix, ?string $subpath = null)
    {
        $serviceMap = $this->getServiceMap();

        if (!isset($serviceMap[$prefix])) {
            return response()->json([
                'thanh_cong' => false,
                'thong_diep' => "API Gateway: Khong tim thay microservice phu trach tien to '{$prefix}'."
            ], 404);
        }

        $targetBaseUrl = rtrim($serviceMap[$prefix], '/');
        $fullPath = $prefix . ($subpath !== null && $subpath !== '' ? '/' . $subpath : '');
        $targetUrl = "{$targetBaseUrl}/api/{$fullPath}";

        $headers = [
            'Accept' => 'application/json',
            'X-Gateway' => 'PhongKham-API-Gateway-v1',
            'X-User-Id' => $request->header('X-User-Id', ''),
            'X-User-Role' => $request->header('X-User-Role', ''),
            'X-User-Email' => $request->header('X-User-Email', ''),
            'X-User-Name' => $request->header('X-User-Name', ''),
        ];

        if ($request->hasHeader('Authorization')) {
            $headers['Authorization'] = $request->header('Authorization');
        }

        $method = strtoupper($request->method());
        $queryParams = $request->query();

        try {
            $client = Http::withHeaders($headers)->timeout(10);

            if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
                $response = $client->send($method, $targetUrl, [
                    'query' => $queryParams,
                    'json' => $request->all(),
                ]);
            } else {
                $response = $client->send($method, $targetUrl, [
                    'query' => $queryParams,
                ]);
            }

            return response($response->body(), $response->status())
                ->header('Content-Type', $response->header('Content-Type') ?? 'application/json');
        } catch (\Exception $e) {
            return response()->json([
                'thanh_cong' => false,
                'ma_loi' => 'DICH_VU_NGOAI_TUYEN',
                'thong_diep' => "API Gateway: Microservice tai {$targetBaseUrl} khong phan hoi hoac dang ngoai tuyen.",
                'chi_tiet' => $e->getMessage(),
                'target_url' => $targetUrl,
            ], 503);
        }
    }

    /**
     * KIEM TRA HEALTH TOAN BO HE THONG
     */
    public function healthCheck(): JsonResponse
    {
        $services = [
            'auth-service' => [
                'url' => config('services.dich_vu_xac_thuc', 'http://127.0.0.1:8001'),
                'port' => 8001,
                'db' => 'db_xac_thuc_bac_si',
                'vai_tro' => 'Xac thuc, Tai khoan, Chuyen khoa, Bac si'
            ],
            'appointment-service' => [
                'url' => config('services.dich_vu_lich_hen', 'http://127.0.0.1:8002'),
                'port' => 8002,
                'db' => 'db_benh_nhan_lich_hen',
                'vai_tro' => 'Ho so benh nhan & Chong trung lich bac si'
            ],
            'clinical-service' => [
                'url' => config('services.dich_vu_y_te', 'http://127.0.0.1:8003'),
                'port' => 8003,
                'db' => 'db_dich_vu_y_te',
                'vai_tro' => 'Danh muc dich vu & Ke can lam sang'
            ],
            'billing-service' => [
                'url' => config('services.dich_vu_hoa_don', 'http://127.0.0.1:8004'),
                'port' => 8004,
                'db' => 'db_hoa_don_thanh_toan',
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
                    'co_so_du_lieu' => $s['db'],
                    'vai_tro' => $s['vai_tro'],
                    'trang_thai' => $isOk ? 'ONLINE' : 'ERROR',
                    'do_tre_ms' => $latencyMs,
                ];
                if (!$isOk) $tatCaHoatDong = false;
            } catch (\Exception $e) {
                $tatCaHoatDong = false;
                $ketQua[$key] = [
                    'port' => $s['port'],
                    'co_so_du_lieu' => $s['db'],
                    'vai_tro' => $s['vai_tro'],
                    'trang_thai' => 'OFFLINE',
                    'thong_bao_loi' => 'Chua khoi dong dich vu tren port ' . $s['port'],
                ];
            }
        }

        return response()->json([
            'gateway' => 'api-gateway (Port 8000)',
            'trang_thai_chung' => $tatCaHoatDong ? 'HOAT_DONG_TOT' : 'MOT_SO_DICH_VU_NGOAI_TUYEN',
            'thoi_gian' => now()->toIso8601String(),
            'danh_sach_dich_vu' => $ketQua
        ]);
    }
}
