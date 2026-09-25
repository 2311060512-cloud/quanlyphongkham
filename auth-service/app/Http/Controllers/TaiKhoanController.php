<?php

namespace App\Http\Controllers;

use App\Services\TaiKhoanService;
use App\Traits\TraVeDuLieuTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaiKhoanController extends Controller
{
    use TraVeDuLieuTrait;

    protected TaiKhoanService $taiKhoanService;

    public function __construct(TaiKhoanService $taiKhoanService)
    {
        $this->taiKhoanService = $taiKhoanService;
    }

    /**
     * GET /api/v1/tai-khoan
     */
    public function danhSach(): JsonResponse
    {
        $ketQua = $this->taiKhoanService->danhSach();
        return $this->thanhCongResponse($ketQua['du_lieu'], $ketQua['thong_diep']);
    }

    /**
     * PATCH /api/v1/tai-khoan/{id}/trang-thai
     * Chi Admin duoc phep khoa / mo khoa tai khoan
     */
    public function capNhatTrangThai(Request $request, int $id): JsonResponse
    {
        $vaiTro = $request->header('X-Vai-Tro') ?? $request->header('X-User-Role');
        if ($vaiTro && $vaiTro !== 'ADMIN') {
            return $this->thatBaiResponse(
                'Chỉ Quản trị viên (ADMIN) mới có quyền thay đổi trạng thái tài khoản.',
                'KHONG_CO_QUYEN',
                403
            );
        }

        $trangThai = $request->input('trang_thai');
        if (!$trangThai) {
            return $this->thatBaiResponse(
                'Vui lòng cung cấp trạng thái mới (HOAT_DONG hoặc BI_KHOA).',
                'THIEU_THONG_TIN',
                422
            );
        }

        $ketQua = $this->taiKhoanService->capNhatTrangThai($id, strtoupper($trangThai));

        if (!$ketQua['thanh_cong']) {
            return $this->thatBaiResponse(
                $ketQua['thong_diep'],
                $ketQua['ma_loi'],
                400
            );
        }

        return $this->thanhCongResponse($ketQua['du_lieu'], $ketQua['thong_diep']);
    }

    /**
     * PUT /api/v1/tai-khoan/{id}/doi-mat-khau
     * Admin doi / dat lai mat khau cho tai khoan
     */
    public function doiMatKhau(Request $request, int $id): JsonResponse
    {
        $vaiTro = $request->header('X-Vai-Tro') ?? $request->header('X-User-Role');
        if ($vaiTro && $vaiTro !== 'ADMIN') {
            return $this->thatBaiResponse(
                'Chỉ Quản trị viên (ADMIN) mới có quyền đặt lại mật khẩu cho tài khoản người dùng.',
                'KHONG_CO_QUYEN',
                403
            );
        }

        $matKhauMoi = $request->input('mat_khau_moi');
        if (!$matKhauMoi || strlen($matKhauMoi) < 6) {
            return $this->thatBaiResponse(
                'Mật khẩu mới phải có tối thiểu 6 ký tự.',
                'MAT_KHAU_KHONG_HOP_LE',
                422
            );
        }

        $ketQua = $this->taiKhoanService->doiMatKhau($id, $matKhauMoi);

        if (!$ketQua['thanh_cong']) {
            return $this->thatBaiResponse(
                $ketQua['thong_diep'],
                $ketQua['ma_loi'],
                400
            );
        }

        return $this->thanhCongResponse(null, $ketQua['thong_diep']);
    }

    /**
     * DELETE /api/v1/tai-khoan/{id}
     * Xóa tài khoản người dùng (Chỉ Admin)
     */
    public function xoa(Request $request, int $id): JsonResponse
    {
        $vaiTro = $request->header('X-Vai-Tro') ?? $request->header('X-User-Role');
        if ($vaiTro && $vaiTro !== 'ADMIN') {
            return $this->thatBaiResponse(
                'Chỉ Quản trị viên (ADMIN) mới có quyền xóa tài khoản người dùng.',
                'KHONG_CO_QUYEN',
                403
            );
        }

        $ketQua = $this->taiKhoanService->xoa($id);

        if (!$ketQua['thanh_cong']) {
            return $this->thatBaiResponse(
                $ketQua['thong_diep'],
                $ketQua['ma_loi'],
                400
            );
        }

        return $this->thanhCongResponse(null, $ketQua['thong_diep']);
    }
}
