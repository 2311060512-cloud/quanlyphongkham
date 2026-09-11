<?php

namespace App\Modules\TaiKhoan\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\TaiKhoan\Services\XacThucService;
use App\Traits\TraVeDuLieuTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class DangNhapController extends Controller
{
    use TraVeDuLieuTrait;

    protected XacThucService $xacThucService;

    public function __construct(XacThucService $xacThucService)
    {
        $this->xacThucService = $xacThucService;
    }

    public function dangNhap(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ten_dang_nhap' => 'required|string',
            'mat_khau' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->thatBaiResponse('Dữ liệu không hợp lệ', 422, $validator->errors());
        }

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

    public function dangKy(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ten_dang_nhap' => 'required|string|unique:tai_khoan,ten_dang_nhap',
            'email' => 'required|email|unique:tai_khoan,email',
            'mat_khau' => 'required|string|min:6',
            'ho_ten' => 'required|string|max:255',
            'so_dien_thoai' => 'nullable|string|max:20',
            'gioi_tinh' => 'nullable|in:NAM,NU,KHAC',
            'ngay_sinh' => 'nullable|date',
            'dia_chi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->thatBaiResponse('Dữ liệu không hợp lệ', 422, $validator->errors());
        }

        try {
            $ketQua = $this->xacThucService->dangKy($request->all());
            return $this->thanhCongResponse($ketQua, 'Đăng ký tài khoản thành công', 201);
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 400);
        }
    }

    public function thongTinHienTai(Request $request): JsonResponse
    {
        $taiKhoan = $request->user();
        if ($taiKhoan) {
            $taiKhoan->load(['vaiTro', 'bacSi.chuyenKhoa', 'benhNhan']);
        }
        return $this->thanhCongResponse($taiKhoan, 'Thông tin người dùng');
    }

    public function dangXuat(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user) {
            $user->currentAccessToken()->delete();
        }
        return $this->thanhCongResponse(null, 'Đăng xuất thành công');
    }
}
