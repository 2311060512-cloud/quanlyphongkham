<?php

namespace App\Http\Controllers;

use App\Services\XacThucService;
use App\Traits\TraVeDuLieuTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class XacThucController extends Controller
{
    use TraVeDuLieuTrait;

    protected XacThucService $xacThucService;

    public function __construct(XacThucService $xacThucService)
    {
        $this->xacThucService = $xacThucService;
    }

    /**
     * POST /api/v1/xac-thuc/dang-nhap
     */
    public function dangNhap(Request $request): JsonResponse
    {
        $tenDangNhap = $request->input('ten_dang_nhap') ?? $request->input('email');
        $matKhau = $request->input('mat_khau');

        if (!$tenDangNhap || !$matKhau) {
            return $this->thatBaiResponse(
                'Vui lòng nhập đầy đủ tên đăng nhập (hoặc email) và mật khẩu.',
                'THIEU_THONG_TIN',
                422
            );
        }

        $ketQua = $this->xacThucService->dangNhap($tenDangNhap, $matKhau);

        if (!$ketQua['thanh_cong']) {
            return $this->thatBaiResponse(
                $ketQua['thong_diep'],
                $ketQua['ma_loi'],
                401
            );
        }

        return $this->thanhCongResponse($ketQua['du_lieu'], $ketQua['thong_diep']);
    }

    /**
     * POST /api/v1/xac-thuc/dang-ky
     */
    public function dangKy(Request $request): JsonResponse
    {
        $duLieu = $request->validate([
            'ho_ten' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'mat_khau' => 'required|string|min:6',
            'ten_dang_nhap' => 'nullable|string|max:100',
            'so_dien_thoai' => 'nullable|string|max:20',
        ]);

        $ketQua = $this->xacThucService->dangKy($duLieu);

        if (!$ketQua['thanh_cong']) {
            return $this->thatBaiResponse(
                $ketQua['thong_diep'],
                $ketQua['ma_loi'],
                422
            );
        }

        return $this->thanhCongResponse($ketQua['du_lieu'], $ketQua['thong_diep'], 201);
    }

    /**
     * GET /api/v1/xac-thuc/thong-tin
     * Duoc API Gateway goi lien tuc de giai ma token va inject headers
     */
    public function thongTin(Request $request): JsonResponse
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader) {
            return $this->thatBaiResponse(
                'Vui lòng cung cấp Bearer Token trong header Authorization.',
                'CHUA_XAC_THUC',
                401
            );
        }

        $thongTin = $this->xacThucService->layThongTin($authHeader);

        if (!$thongTin) {
            return $this->thatBaiResponse(
                'Token không hợp lệ hoặc đã hết hạn.',
                'TOKEN_KHONG_HOP_LE',
                401
            );
        }

        return $this->thanhCongResponse($thongTin, 'Xác thực token thành công.');
    }

    /**
     * POST /api/v1/xac-thuc/dang-xuat
     */
    public function dangXuat(Request $request): JsonResponse
    {
        $authHeader = $request->header('Authorization');
        if ($authHeader) {
            $this->xacThucService->dangXuat($authHeader);
        }

        return $this->thanhCongResponse(null, 'Đăng xuất thành công.');
    }

    /**
     * PUT /api/v1/xac-thuc/doi-mat-khau
     */
    public function doiMatKhau(Request $request): JsonResponse
    {
        $request->validate([
            'mat_khau_cu' => 'required|string',
            'mat_khau_moi' => 'required|string|min:6',
        ]);

        // Lay thong tin user tu token hoac Gateway headers
        $userId = $request->header('X-Nguoi-Dung-Id') ?? $request->header('X-User-Id');
        if (!$userId) {
            $authHeader = $request->header('Authorization');
            if ($authHeader) {
                $user = $this->xacThucService->layThongTin($authHeader);
                $userId = $user['id'] ?? null;
            }
        }

        if (!$userId) {
            return $this->thatBaiResponse(
                'Không xác định được danh tính người dùng.',
                'CHUA_XAC_THUC',
                401
            );
        }

        $ketQua = $this->xacThucService->doiMatKhau(
            (int)$userId,
            $request->input('mat_khau_cu'),
            $request->input('mat_khau_moi')
        );

        if (!$ketQua['thanh_cong']) {
            return $this->thatBaiResponse($ketQua['thong_diep'], $ketQua['ma_loi'], 400);
        }

        return $this->thanhCongResponse(null, $ketQua['thong_diep']);
    }

    /**
     * PUT /api/v1/xac-thuc/ho-so
     * Cập nhật thông tin hồ sơ cá nhân
     */
    public function capNhatHoSo(Request $request): JsonResponse
    {
        $userId = $request->header('X-Nguoi-Dung-Id') ?? $request->header('X-User-Id');
        if (!$userId) {
            $authHeader = $request->header('Authorization');
            if ($authHeader) {
                $user = $this->xacThucService->layThongTin($authHeader);
                $userId = $user['id'] ?? null;
            }
        }
        if (!$userId && $request->has('user_id')) {
            $userId = $request->input('user_id');
        }

        if (!$userId) {
            return $this->thatBaiResponse(
                'Không xác định được danh tính người dùng.',
                'CHUA_XAC_THUC',
                401
            );
        }

        $duLieu = $request->all();
        $ketQua = $this->xacThucService->capNhatHoSo((int)$userId, $duLieu);

        if (!$ketQua['thanh_cong']) {
            return $this->thatBaiResponse($ketQua['thong_diep'], $ketQua['ma_loi'], 400);
        }

        return $this->thanhCongResponse($ketQua['du_lieu'], $ketQua['thong_diep']);
    }

    /**
     * POST /api/v1/xac-thuc/avatar
     * Upload / Cập nhật Avatar (hỗ trợ base64 image hoặc file upload)
     */
    public function capNhatAvatar(Request $request): JsonResponse
    {
        $userId = $request->header('X-Nguoi-Dung-Id') ?? $request->header('X-User-Id');
        if (!$userId) {
            $authHeader = $request->header('Authorization');
            if ($authHeader) {
                $user = $this->xacThucService->layThongTin($authHeader);
                $userId = $user['id'] ?? null;
            }
        }
        if (!$userId && $request->has('user_id')) {
            $userId = $request->input('user_id');
        }

        if (!$userId) {
            return $this->thatBaiResponse(
                'Không xác định được danh tính người dùng.',
                'CHUA_XAC_THUC',
                401
            );
        }

        $avatarData = '';
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $mimeType = $file->getMimeType();
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            $avatarData = "data:{$mimeType};base64,{$base64}";
        } elseif ($request->filled('avatar')) {
            $avatarData = $request->input('avatar');
        } else {
            return $this->thatBaiResponse(
                'Vui lòng cung cấp dữ liệu ảnh đại diện.',
                'THIEU_DU_LIEU_ANH',
                422
            );
        }

        $ketQua = $this->xacThucService->capNhatAvatar((int)$userId, $avatarData);

        if (!$ketQua['thanh_cong']) {
            return $this->thatBaiResponse($ketQua['thong_diep'], $ketQua['ma_loi'], 400);
        }

        return $this->thanhCongResponse($ketQua['du_lieu'], $ketQua['thong_diep']);
    }
}
