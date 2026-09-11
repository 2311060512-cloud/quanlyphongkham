<?php

namespace App\Modules\HoaDon\Services;

use App\Modules\HoaDon\Repositories\HoaDonRepositoryInterface;
use Illuminate\Validation\ValidationException;

class HoaDonService
{
    protected HoaDonRepositoryInterface $hoaDonRepo;

    public function __construct(HoaDonRepositoryInterface $hoaDonRepo)
    {
        $this->hoaDonRepo = $hoaDonRepo;
    }

    public function thanhToan(int $id, string $phuongThuc = 'TIEN_MAT', string $ghiChu = '')
    {
        $hoaDon = $this->hoaDonRepo->timTheoId($id);
        if (!$hoaDon) {
            throw ValidationException::withMessages(['hoa_don' => ['Không tìm thấy hóa đơn cần thanh toán.']]);
        }

        if ($hoaDon->trang_thai === 'DA_THANH_TOAN') {
            throw ValidationException::withMessages(['hoa_don' => ['Hóa đơn này đã được thanh toán trước đó.']]);
        }

        $this->hoaDonRepo->capNhat($id, [
            'phuong_thuc_thanh_toan' => $phuongThuc,
            'trang_thai' => 'DA_THANH_TOAN',
            'ngay_thanh_toan' => now(),
            'ghi_chu' => $ghiChu,
        ]);

        return $this->hoaDonRepo->timTheoId($id)?->load(['benhNhan', 'lichHen.bacSi']);
    }

    public function thongKe()
    {
        return $this->hoaDonRepo->thongKeDoanhThu();
    }
}
