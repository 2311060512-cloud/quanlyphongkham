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
    public function layTheoBacSi(Request $request, int $id): JsonResponse
    {
        $boLoc = [
            'trang_thai' => $request->query('trang_thai')
        ];

        $ketQua = $this->lichTrucService->layTheoBacSi($id, $boLoc);

        if (!$ketQua['thanh_cong']) {
            return $this->thatBaiResponse($ketQua['thong_diep'], $ketQua['ma_loi'], 404);
        }

        return $this->thanhCongResponse($ketQua['du_lieu'], $ketQua['thong_diep']);
    }

    /**
     * GET /api/v1/bac-si/lich-truc hoặc GET /api/v1/lich-truc
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
     * POST /api/v1/bac-si/{id}/lich-truc hoặc POST /api/v1/lich-truc
     */
    public function themMoi(Request $request, ?int $id = null): JsonResponse
    {
        $bacSiId = $id ?: (int)$request->input('bac_si_id');
        if (!$bacSiId) {
            return $this->thatBaiResponse('Vui lòng cung cấp mã ID bác sĩ (bac_si_id).', 'THIEU_BAC_SI_ID', 422);
        }

        $duLieu = $request->validate([
            'ngay_trong_tuan' => 'required|integer|min:2|max:8',
            'ca_truc' => 'required|string|in:CA_SANG,CA_CHIEU,CA_TOI,CA_NGAY',
            'gio_bat_dau' => 'nullable|string',
            'gio_ket_thuc' => 'nullable|string',
            'so_luong_kham_toi_da' => 'nullable|integer|min:1',
            'phong_kham' => 'nullable|string',
        ]);

        $vaiTro = $request->header('X-User-Role') ?: $request->header('X-Vai-Tro');
        $duLieu['vai_tro_nguoi_gui'] = $vaiTro;

        $ketQua = $this->lichTrucService->themMoi($bacSiId, $duLieu);

        if (!$ketQua['thanh_cong']) {
            return $this->thatBaiResponse($ketQua['thong_diep'], $ketQua['ma_loi'], 400);
        }

        return $this->thanhCongResponse($ketQua['du_lieu'], $ketQua['thong_diep'], 201);
    }

    /**
     * PUT /api/v1/bac-si/lich-truc/{id} hoặc PUT /api/v1/lich-truc/{id}
     */
    public function capNhat(Request $request, int $id): JsonResponse
    {
        $duLieu = $request->all();
        $vaiTro = $request->header('X-User-Role') ?: $request->header('X-Vai-Tro');
        $duLieu['vai_tro_nguoi_gui'] = $vaiTro;

        $ketQua = $this->lichTrucService->capNhat($id, $duLieu);

        if (!$ketQua['thanh_cong']) {
            return $this->thatBaiResponse($ketQua['thong_diep'], $ketQua['ma_loi'], 400);
        }

        return $this->thanhCongResponse($ketQua['du_lieu'], $ketQua['thong_diep']);
    }

    /**
     * PUT /api/v1/bac-si/lich-truc/{id}/duyet (Chỉ ADMIN)
     */
    public function duyet(int $id): JsonResponse
    {
        $ketQua = $this->lichTrucService->duyet($id);

        if (!$ketQua['thanh_cong']) {
            return $this->thatBaiResponse($ketQua['thong_diep'], $ketQua['ma_loi'], 400);
        }

        return $this->thanhCongResponse($ketQua['du_lieu'], $ketQua['thong_diep']);
    }

    /**
     * PUT /api/v1/bac-si/lich-truc/{id}/tu-choi (Chỉ ADMIN)
     */
    public function tuChoi(Request $request, int $id): JsonResponse
    {
        $lyDo = $request->input('ly_do');
        $ketQua = $this->lichTrucService->tuChoi($id, $lyDo);

        if (!$ketQua['thanh_cong']) {
            return $this->thatBaiResponse($ketQua['thong_diep'], $ketQua['ma_loi'], 400);
        }

        return $this->thanhCongResponse($ketQua['du_lieu'], $ketQua['thong_diep']);
    }

    /**
     * DELETE /api/v1/bac-si/lich-truc/{id} hoặc DELETE /api/v1/lich-truc/{id}
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
