<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait TraVeDuLieuTrait
{
    public function thanhCongResponse(mixed $duLieu = null, string $thongBao = 'Thành công', int $maLoi = 200): JsonResponse
    {
        return response()->json([
            'thanh_cong' => true,
            'ma_trang_thai' => $maLoi,
            'thong_bao' => $thongBao,
            'du_lieu' => $duLieu,
            'thoi_gian' => now()->toIso8601String(),
        ], $maLoi);
    }

    public function thatBaiResponse(string $thongBao = 'Đã có lỗi xảy ra', int $maLoi = 400, mixed $chiTietLoi = null): JsonResponse
    {
        return response()->json([
            'thanh_cong' => false,
            'ma_trang_thai' => $maLoi,
            'thong_bao' => $thongBao,
            'chi_tiet_loi' => $chiTietLoi,
            'thoi_gian' => now()->toIso8601String(),
        ], $maLoi);
    }
}
