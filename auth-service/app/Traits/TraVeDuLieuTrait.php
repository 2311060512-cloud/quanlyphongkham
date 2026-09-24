<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait TraVeDuLieuTrait
{
    /**
     * Tra ve Response thanh cong chuan hoa
     */
    public function thanhCongResponse($duLieu = null, string $thongDiep = 'Thanh cong', int $maTrangThai = 200): JsonResponse
    {
        return response()->json([
            'thanh_cong' => true,
            'thong_diep' => $thongDiep,
            'du_lieu' => $duLieu,
        ], $maTrangThai);
    }

    /**
     * Tra ve Response that bai chuan hoa
     */
    public function thatBaiResponse(string $thongDiep = 'That bai', string $maLoi = 'LOI_HE_THONG', int $maTrangThai = 400, $chiTiet = null): JsonResponse
    {
        $response = [
            'thanh_cong' => false,
            'ma_loi' => $maLoi,
            'thong_diep' => $thongDiep,
        ];

        if ($chiTiet !== null) {
            $response['chi_tiet'] = $chiTiet;
        }

        return response()->json($response, $maTrangThai);
    }
}
