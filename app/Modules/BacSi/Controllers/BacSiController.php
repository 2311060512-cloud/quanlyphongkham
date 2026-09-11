<?php

namespace App\Modules\BacSi\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\BacSi\Services\BacSiService;
use App\Traits\TraVeDuLieuTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class BacSiController extends Controller
{
    use TraVeDuLieuTrait;

    protected BacSiService $bacSiService;

    public function __construct(BacSiService $bacSiService)
    {
        $this->bacSiService = $bacSiService;
    }

    public function danhSach(Request $request): JsonResponse
    {
        $chuyenKhoaId = $request->query('chuyen_khoa_id');
        $danhSach = $this->bacSiService->danhSachBacSi($chuyenKhoaId ? (int)$chuyenKhoaId : null);
        return $this->thanhCongResponse($danhSach, 'Danh sách bác sĩ');
    }

    public function chiTiet(int $id): JsonResponse
    {
        $bacSi = $this->bacSiService->chiTietBacSi($id);
        if (!$bacSi) {
            return $this->thatBaiResponse('Không tìm thấy bác sĩ', 404);
        }
        return $this->thanhCongResponse($bacSi, 'Chi tiết bác sĩ');
    }

    public function danhSachChuyenKhoa(): JsonResponse
    {
        $danhSach = $this->bacSiService->danhSachChuyenKhoa();
        return $this->thanhCongResponse($danhSach, 'Danh sách chuyên khoa');
    }

    public function taoMoi(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ho_ten' => 'required|string|max:255',
            'chuyen_khoa_id' => 'required|exists:chuyen_khoa,id',
            'gia_kham' => 'required|numeric|min:0',
            'phong_kham' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->thatBaiResponse('Dữ liệu không hợp lệ', 422, $validator->errors());
        }

        try {
            $bacSi = $this->bacSiService->taoBacSiMoi($request->all());
            return $this->thanhCongResponse($bacSi, 'Thêm bác sĩ thành công', 201);
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 400);
        }
    }
}
