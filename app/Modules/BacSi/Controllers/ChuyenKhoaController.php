<?php

namespace App\Modules\BacSi\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\BacSi\Services\ChuyenKhoaService;
use App\Modules\BacSi\Requests\TaoChuyenKhoaRequest;
use App\Modules\BacSi\Requests\CapNhatChuyenKhoaRequest;
use App\Traits\TraVeDuLieuTrait;
use Illuminate\Http\JsonResponse;

class ChuyenKhoaController extends Controller
{
    use TraVeDuLieuTrait;

    protected ChuyenKhoaService $chuyenKhoaService;

    public function __construct(ChuyenKhoaService $chuyenKhoaService)
    {
        $this->chuyenKhoaService = $chuyenKhoaService;
    }

    /**
     * API Lấy danh sách toàn bộ chuyên khoa khám bệnh
     */
    public function danhSach(): JsonResponse
    {
        $danhSach = $this->chuyenKhoaService->danhSachChuyenKhoa();
        return $this->thanhCongResponse($danhSach, 'Danh sách chuyên khoa');
    }

    /**
     * API Xem chi tiết chuyên khoa
     */
    public function chiTiet(int $id): JsonResponse
    {
        $chuyenKhoa = $this->chuyenKhoaService->chiTietChuyenKhoa($id);
        if (!$chuyenKhoa) {
            return $this->thatBaiResponse('Không tìm thấy chuyên khoa', 404);
        }
        return $this->thanhCongResponse($chuyenKhoa, 'Chi tiết chuyên khoa');
    }

    /**
     * API Thêm chuyên khoa mới (Dành cho ADMIN)
     */
    public function taoMoi(TaoChuyenKhoaRequest $request): JsonResponse
    {
        try {
            $chuyenKhoa = $this->chuyenKhoaService->taoChuyenKhoa($request->validated());
            return $this->thanhCongResponse($chuyenKhoa, 'Thêm chuyên khoa thành công', 201);
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 400);
        }
    }

    /**
     * API Cập nhật thông tin chuyên khoa (Dành cho ADMIN)
     */
    public function capNhat(CapNhatChuyenKhoaRequest $request, int $id): JsonResponse
    {
        try {
            $chuyenKhoa = $this->chuyenKhoaService->capNhatChuyenKhoa($id, $request->validated());
            return $this->thanhCongResponse($chuyenKhoa, 'Cập nhật chuyên khoa thành công');
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 400);
        }
    }

    /**
     * API Xóa chuyên khoa (Dành cho ADMIN)
     */
    public function xoa(int $id): JsonResponse
    {
        try {
            $this->chuyenKhoaService->xoaChuyenKhoa($id);
            return $this->thanhCongResponse(null, 'Xóa chuyên khoa thành công');
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 400);
        }
    }
}
