<?php

namespace App\Http\Controllers;

use App\Services\BacSiService;
use App\Services\ChuyenKhoaService;
use App\Traits\TraVeDuLieuTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BacSiController extends Controller
{
    use TraVeDuLieuTrait;

    protected BacSiService $bacSiService;

    public function __construct(BacSiService $bacSiService)
    {
        $this->bacSiService = $bacSiService;
    }

    /**
     * GET /api/v1/bac-si
     * Danh sach bac si dang lam viec, ho tro loc ?chuyen_khoa_id=... va tim kiem ?tu_khoa=...
     */
    public function danhSach(Request $request): JsonResponse
    {
        $boLoc = [
            'chuyen_khoa_id' => $request->query('chuyen_khoa_id'),
            'tu_khoa' => $request->query('tu_khoa') ?? $request->query('ten'),
            'trang_thai' => $request->query('trang_thai', 'DANG_LAM_VIEC'),
        ];

        $ketQua = $this->bacSiService->danhSach($boLoc);

        return response()->json($ketQua);
    }

    /**
     * GET /api/v1/bac-si/{id}
     * Chi tiet bac si (bao gom gia kham, phong kham, kinh nghiem)
     */
    public function chiTiet(int $id): JsonResponse
    {
        $bacSi = $this->bacSiService->chiTiet($id);

        if (!$bacSi) {
            return $this->thatBaiResponse(
                "Không tìm thấy bác sĩ với ID {$id}.",
                'BAC_SI_KHONG_TON_TAI',
                404
            );
        }

        return $this->thanhCongResponse($bacSi, 'Lấy chi tiết thông tin bác sĩ thành công.');
    }

    /**
     * POST /api/v1/bac-si
     * Admin tao bac si moi: Tu dong tao tai khoan voi role BAC_SI va lien ket sang bac_si
     */
    public function themMoi(Request $request): JsonResponse
    {
        $duLieu = $request->validate([
            'ho_ten' => 'required|string|max:150',
            'chuyen_khoa_id' => 'required|integer|exists:chuyen_khoa,id',
            'email' => 'nullable|email|max:150',
            'so_dien_thoai' => 'nullable|string|max:20',
            'hoc_vi' => 'nullable|string|max:100',
            'gia_kham' => 'nullable|numeric|min:0',
            'phong_kham' => 'nullable|string|max:100',
            'kinh_nghiem' => 'nullable|string|max:255',
            'so_nam_kinh_nghiem' => 'nullable',
            'ten_dang_nhap' => 'nullable|string|max:100',
            'mat_khau' => 'nullable|string|min:6',
        ]);

        $ketQua = $this->bacSiService->themMoi($duLieu);

        return $this->thanhCongResponse($ketQua['du_lieu'], $ketQua['thong_diep'], 201);
    }

    /**
     * PUT /api/v1/bac-si/{id}
     * Cap nhat thong tin bac si (gia kham, hoc vi, phong lam viec)
     */
    public function capNhat(Request $request, int $id): JsonResponse
    {
        $duLieu = $request->all();

        $ketQua = $this->bacSiService->capNhat($id, $duLieu);

        if (!$ketQua['thanh_cong']) {
            return $this->thatBaiResponse(
                $ketQua['thong_diep'],
                $ketQua['ma_loi'],
                404
            );
        }

        return $this->thanhCongResponse($ketQua['du_lieu'], $ketQua['thong_diep']);
    }
}
