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

    public function danhSach(Request $request): JsonResponse
    {
        $user = $request->user();
        $truyVan = $this->hoaDonRepo->getModel()->with(['benhNhan', 'lichHen.bacSi']);

        if ($user) {
            $maVaiTro = $user->vaiTro ? strtoupper($user->vaiTro->ma_vai_tro) : 'BENH_NHAN';

            // BỆNH NHÂN: chỉ xem hóa đơn của chính mình
            if ($maVaiTro === 'BENH_NHAN') {
                $benhNhan = \App\Modules\BenhNhan\Models\BenhNhan::where('tai_khoan_id', $user->id)->first();
                if (!$benhNhan && !empty($user->so_dien_thoai)) {
                    $benhNhan = \App\Modules\BenhNhan\Models\BenhNhan::where('so_dien_thoai', $user->so_dien_thoai)->first();
                    if ($benhNhan && !$benhNhan->tai_khoan_id) {
                        $benhNhan->update(['tai_khoan_id' => $user->id]);
                    }
                }
                if ($benhNhan) {
                    $truyVan->where('benh_nhan_id', $benhNhan->id);
                } else {
                    return $this->thanhCongResponse([], 'Chưa có hóa đơn nào');
                }
            }
            // BÁC SĨ: chỉ xem hóa đơn từ các buổi khám của mình
            elseif ($maVaiTro === 'BAC_SI') {
                $bacSi = \App\Modules\BacSi\Models\BacSi::where('tai_khoan_id', $user->id)->first();
                if ($bacSi) {
                    $truyVan->whereHas('lichHen', function ($q) use ($bacSi) {
                        $q->where('bac_si_id', $bacSi->id);
                    });
                }
            }
            // ADMIN: xem tất cả hóa đơn
        }

        $danhSach = $truyVan->latest()->get();
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
