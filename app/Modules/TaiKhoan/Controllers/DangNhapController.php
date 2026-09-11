<?php

namespace App\Modules\TaiKhoan\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\TaiKhoan\Services\XacThucService;
use App\Modules\TaiKhoan\Services\TaiKhoanService;
use App\Modules\TaiKhoan\Requests\DangNhapRequest;
use App\Modules\TaiKhoan\Requests\DangKyRequest;
use App\Modules\TaiKhoan\Requests\DoiMatKhauRequest;
use App\Traits\TraVeDuLieuTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DangNhapController extends Controller
{
    use TraVeDuLieuTrait;

    protected XacThucService $xacThucService;
    protected TaiKhoanService $taiKhoanService;

    public function __construct(XacThucService $xacThucService, TaiKhoanService $taiKhoanService)
    {
        $this->xacThucService = $xacThucService;
        $this->taiKhoanService = $taiKhoanService;
    }

    /**
     * API Đăng nhập hệ thống (trả về Token và thông tin user, vai trò)
     */
    public function dangNhap(DangNhapRequest $request): JsonResponse
    {
        try {
            $ketQua = $this->xacThucService->dangNhap(
                $request->input('ten_dang_nhap'),
                $request->input('mat_khau')
            );
            return $this->thanhCongResponse($ketQua, 'Đăng nhập thành công');
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 401);
        }
    }

    /**
     * API Đăng ký tài khoản bệnh nhân
     */
    public function dangKy(DangKyRequest $request): JsonResponse
    {
        try {
            $ketQua = $this->xacThucService->dangKy($request->validated());
            return $this->thanhCongResponse($ketQua, 'Đăng ký tài khoản thành công', 201);
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 400);
        }
    }

    /**
     * API Đổi mật khẩu tài khoản hiện tại
     */
    public function doiMatKhau(DoiMatKhauRequest $request): JsonResponse
    {
        try {
            $taiKhoan = $request->user();
            $this->taiKhoanService->doiMatKhau(
                $taiKhoan,
                $request->input('mat_khau_cu'),
                $request->input('mat_khau_moi')
            );
            return $this->thanhCongResponse(null, 'Đổi mật khẩu thành công. Vui lòng sử dụng mật khẩu mới cho các lần đăng nhập sau.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->thatBaiResponse('Mật khẩu không đúng', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 400);
        }
    }

    /**
     * API Lấy thông tin tài khoản đang đăng nhập
     */
    public function thongTinHienTai(Request $request): JsonResponse
    {
        $taiKhoan = $request->user();
        if ($taiKhoan) {
            $taiKhoan->load(['vaiTro', 'bacSi.chuyenKhoa', 'benhNhan']);
        }
        return $this->thanhCongResponse($taiKhoan, 'Thông tin người dùng');
    }

    /**
     * API Đăng xuất tài khoản (thu hồi Token)
     */
    public function dangXuat(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user) {
            $user->currentAccessToken()->delete();
        }
        return $this->thanhCongResponse(null, 'Đăng xuất thành công');
    }
}
