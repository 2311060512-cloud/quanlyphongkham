<?php

namespace App\Modules\LichHen\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\LichHen\Services\LichHenService;
use App\Modules\LichHen\Repositories\LichHenRepositoryInterface;
use App\Modules\BenhNhan\Repositories\BenhNhanRepositoryInterface;
use App\Modules\BacSi\Repositories\BacSiRepositoryInterface;
use App\Traits\TraVeDuLieuTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class LichHenController extends Controller
{
    use TraVeDuLieuTrait;

    protected LichHenService $lichHenService;
    protected LichHenRepositoryInterface $lichHenRepo;
    protected BenhNhanRepositoryInterface $benhNhanRepo;
    protected BacSiRepositoryInterface $bacSiRepo;

    public function __construct(
        LichHenService $lichHenService,
        LichHenRepositoryInterface $lichHenRepo,
        BenhNhanRepositoryInterface $benhNhanRepo,
        BacSiRepositoryInterface $bacSiRepo
    ) {
        $this->lichHenService = $lichHenService;
        $this->lichHenRepo = $lichHenRepo;
        $this->benhNhanRepo = $benhNhanRepo;
        $this->bacSiRepo = $bacSiRepo;
    }

    public function danhSach(Request $request): JsonResponse
    {
        $danhSach = $this->lichHenRepo->getModel()->with(['benhNhan', 'bacSi.chuyenKhoa', 'hoaDon'])->latest()->get();
        return $this->thanhCongResponse($danhSach, 'Danh sách toàn bộ lịch hẹn');
    }

    public function datLich(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'bac_si_id' => 'required|exists:bac_si,id',
            'ngay_kham' => 'required|date|after_or_equal:today',
            'gio_kham' => 'required|string',
            'trieu_chung' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->thatBaiResponse('Thông tin không hợp lệ', 422, $validator->errors());
        }

        try {
            $lichHen = $this->lichHenService->datLichHen($request->all(), $request->user());
            return $this->thanhCongResponse($lichHen, 'Đặt lịch khám thành công', 201);
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 400);
        }
    }

    public function lichSuCuaToi(Request $request): JsonResponse
    {
        $user = $request->user();
        $benhNhan = $this->benhNhanRepo->timTheoTaiKhoanId($user->id);

        if (!$benhNhan) {
            return $this->thanhCongResponse([], 'Chưa có lịch sử khám bệnh');
        }

        $danhSach = $this->lichHenRepo->layTheoBenhNhan($benhNhan->id);
        return $this->thanhCongResponse($danhSach, 'Lịch sử khám bệnh của bạn');
    }

    public function lichKhamBacSi(Request $request): JsonResponse
    {
        $user = $request->user();
        $bacSi = $this->bacSiRepo->timTheoTaiKhoanId($user->id);

        if (!$bacSi) {
            return $this->thatBaiResponse('Tài khoản không gắn với bác sĩ nào', 403);
        }

        $ngayKham = $request->query('ngay_kham', date('Y-m-d'));
        $danhSach = $this->lichHenRepo->layTheoBacSi($bacSi->id, $ngayKham);

        return $this->thanhCongResponse($danhSach, "Danh sách lịch khám ngày $ngayKham");
    }

    public function xacNhan(int $id): JsonResponse
    {
        $this->lichHenService->xacNhanLichHen($id);
        return $this->thanhCongResponse(null, 'Đã xác nhận lịch hẹn');
    }

    public function batDau(int $id): JsonResponse
    {
        $this->lichHenService->batDauKham($id);
        return $this->thanhCongResponse(null, 'Đã bắt đầu buổi khám');
    }

    public function hoanThanh(Request $request, int $id): JsonResponse
    {
        try {
            $ketQua = $this->lichHenService->hoanThanhKham($id, $request->all());
            return $this->thanhCongResponse($ketQua, 'Hoàn thành khám và đã tạo hóa đơn thanh toán');
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 400);
        }
    }

    public function huy(Request $request, int $id): JsonResponse
    {
        $lyDo = $request->input('ly_do', 'Người bệnh hoặc phòng khám yêu cầu hủy');
        $this->lichHenService->huyLichHen($id, $lyDo);
        return $this->thanhCongResponse(null, 'Đã hủy lịch hẹn thành công');
    }
}
