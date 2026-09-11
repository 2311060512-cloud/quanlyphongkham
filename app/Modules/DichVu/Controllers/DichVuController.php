<?php

namespace App\Modules\DichVu\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\DichVu\Services\DichVuService;
use App\Traits\TraVeDuLieuTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class DichVuController extends Controller
{
    use TraVeDuLieuTrait;

    protected DichVuService $dichVuService;

    public function __construct(DichVuService $dichVuService)
    {
        $this->dichVuService = $dichVuService;
    }

    public function danhSach(): JsonResponse
    {
        $danhSach = $this->dichVuService->danhSachDichVu();
        return $this->thanhCongResponse($danhSach, 'Danh mục dịch vụ y tế');
    }

    public function chiDinh(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lich_hen_id' => 'required|exists:lich_hen,id',
            'dich_vu_id' => 'required|exists:dich_vu,id',
            'so_luong' => 'nullable|integer|min:1',
            'ghi_chu' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->thatBaiResponse('Dữ liệu không hợp lệ', 422, $validator->errors());
        }

        try {
            $ketQua = $this->dichVuService->chiDinhDichVu(
                $request->input('lich_hen_id'),
                $request->input('dich_vu_id'),
                $request->input('so_luong', 1),
                $request->input('ghi_chu', '')
            );
            return $this->thanhCongResponse($ketQua, 'Chỉ định dịch vụ cận lâm sàng thành công', 201);
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 400);
        }
    }

    public function capNhatKetQua(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ket_qua' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->thatBaiResponse('Thiếu nội dung kết quả', 422, $validator->errors());
        }

        $this->dichVuService->capNhatKetQua($id, $request->input('ket_qua'));
        return $this->thanhCongResponse(null, 'Cập nhật kết quả cận lâm sàng thành công');
    }
}
