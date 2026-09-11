<?php

namespace App\Modules\HoaDon\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\HoaDon\Services\HoaDonService;
use App\Modules\HoaDon\Repositories\HoaDonRepositoryInterface;
use App\Traits\TraVeDuLieuTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HoaDonController extends Controller
{
    use TraVeDuLieuTrait;

    protected HoaDonService $hoaDonService;
    protected HoaDonRepositoryInterface $hoaDonRepo;

    public function __construct(
        HoaDonService $hoaDonService,
        HoaDonRepositoryInterface $hoaDonRepo
    ) {
        $this->hoaDonService = $hoaDonService;
        $this->hoaDonRepo = $hoaDonRepo;
    }

    public function danhSach(): JsonResponse
    {
        $danhSach = $this->hoaDonRepo->getModel()->with(['benhNhan', 'lichHen.bacSi'])->latest()->get();
        return $this->thanhCongResponse($danhSach, 'Danh sách hóa đơn viện phí');
    }

    public function chiTiet(int $id): JsonResponse
    {
        $hoaDon = $this->hoaDonRepo->timTheoId($id)?->load(['benhNhan', 'lichHen.bacSi.chuyenKhoa', 'lichHen.suDungDichVu.dichVu']);
        if (!$hoaDon) {
            return $this->thatBaiResponse('Không tìm thấy hóa đơn', 404);
        }
        return $this->thanhCongResponse($hoaDon, 'Chi tiết hóa đơn viện phí');
    }

    public function thanhToan(Request $request, int $id): JsonResponse
    {
        $phuongThuc = $request->input('phuong_thuc_thanh_toan', 'TIEN_MAT');
        $ghiChu = $request->input('ghi_chu', 'Thanh toán tại quầy thu ngân');

        try {
            $hoaDon = $this->hoaDonService->thanhToan($id, $phuongThuc, $ghiChu);
            return $this->thanhCongResponse($hoaDon, 'Thanh toán viện phí thành công');
        } catch (\Exception $e) {
            return $this->thatBaiResponse($e->getMessage(), 400);
        }
    }

    public function thongKe(): JsonResponse
    {
        $thongKe = $this->hoaDonService->thongKe();
        return $this->thanhCongResponse($thongKe, 'Thống kê doanh thu phòng khám');
    }
}
