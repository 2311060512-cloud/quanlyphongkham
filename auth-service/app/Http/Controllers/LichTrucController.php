<?php

namespace App\Http\Controllers;

use App\Services\LichTrucService;
use App\Traits\TraVeDuLieuTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LichTrucController extends Controller
{
    use TraVeDuLieuTrait;

    protected LichTrucService $lichTrucService;

    public function __construct(LichTrucService $lichTrucService)
    {
        $this->lichTrucService = $lichTrucService;
    }

    /**
     * GET /api/v1/bac-si/{id}/lich-truc
     */
    public function layTheoBacSi(int $id): JsonResponse
    {
        $ketQua = $this->lichTrucService->layTheoBacSi($id);

        if (!$ketQua['thanh_cong']) {
            return $this->thatBaiResponse($ketQua['thong_diep'], $ketQua['ma_loi'], 404);
        }

        return $this->thanhCongResponse($ketQua['du_lieu'], $ketQua['thong_diep']);
    }

    /**
     * GET /api/v1/bac-si/lich-truc
     */
    public function danhSach(Request $request): JsonResponse
    {
        $boLoc = [
            'bac_si_id' => $request->query('bac_si_id'),
            'ngay_trong_tuan' => $request->query('ngay_trong_tuan'),
            'ca_truc' => $request->query('ca_truc'),
            'trang_thai' => $request->query('trang_thai'),
        ];

        $ketQua = $this->lichTrucService->danhSach($boLoc);

        return $this->thanhCongResponse($ketQua['du_lieu'], $ketQua['thong_diep']);
    }

    /**
     * POST /api/v1/bac-si/{id}/lich-truc
     */
    public function themMoi(Request $request, int $id): JsonResponse
    {
        $duLieu = $request->validate([
            'ngay_trong_tuan' => 'required|integer|min:2|max:8',
            'ca_truc' => 'required|string|in:CA_SANG,CA_CHIEU,CA_TOI,CA_NGAY',
            'gio_bat_dau' => 'nullable|string',
            'gio_ket_thuc' => 'nullable|string',
            'so_luong_kham_toi_da' => 'nullable|integer|min:1',
            'phong_kham' => 'nullable|string',
        ]);

        $ketQua = $this->lichTrucService->themMoi($id, $duLieu);

        if (!$ketQua['thanh_cong']) {
            return $this->thatBaiResponse($ketQua['thong_diep'], $ketQua['ma_loi'], 400);
        }

        return $this->thanhCongResponse($ketQua['du_lieu'], $ketQua['thong_diep'], 201);
    }

    /**
     * PUT /api/v1/bac-si/lich-truc/{id}
     */
    public function capNhat(Request $request, int $id): JsonResponse
    {
        $duLieu = $request->all();
        $ketQua = $this->lichTrucService->capNhat($id, $duLieu);

        if (!$ketQua['thanh_cong']) {
            return $this->thatBaiResponse($ketQua['thong_diep'], $ketQua['ma_loi'], 400);
        }

        return $this->thanhCongResponse($ketQua['du_lieu'], $ketQua['thong_diep']);
    }

    /**
     * DELETE /api/v1/bac-si/lich-truc/{id}
     */
    public function xoa(int $id): JsonResponse
    {
        $ketQua = $this->lichTrucService->xoa($id);

        if (!$ketQua['thanh_cong']) {
            return $this->thatBaiResponse($ketQua['thong_diep'], $ketQua['ma_loi'], 400);
        }

        return $this->thanhCongResponse(null, $ketQua['thong_diep']);
    }

    /**
     * GET /api/v1/bac-si/{id}/kiem-tra-truc?ngay=YYYY-MM-DD&gio=HH:mm
     */
    public function kiemTraTruc(Request $request, int $id): JsonResponse
    {
        $ngay = $request->query('ngay');
        $gio = $request->query('gio');

        if (!$ngay) {
            return $this->thatBaiResponse('Vui lòng cung cấp tham số ngay=YYYY-MM-DD.', 'THIEU_THONG_TIN', 422);
        }

        $ketQua = $this->lichTrucService->kiemTraBacSiCoTruc($id, $ngay, $gio);

        return $this->thanhCongResponse($ketQua, $ketQua['thong_diep']);
    }
}
