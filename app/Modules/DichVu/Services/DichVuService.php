<?php

namespace App\Modules\DichVu\Services;

use App\Modules\DichVu\Repositories\DichVuRepositoryInterface;
use App\Modules\DichVu\Repositories\SuDungDichVuRepositoryInterface;
use App\Modules\HoaDon\Repositories\HoaDonRepositoryInterface;
use App\Modules\LichHen\Repositories\LichHenRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DichVuService
{
    protected DichVuRepositoryInterface $dichVuRepo;
    protected SuDungDichVuRepositoryInterface $suDungRepo;
    protected HoaDonRepositoryInterface $hoaDonRepo;
    protected LichHenRepositoryInterface $lichHenRepo;

    public function __construct(
        DichVuRepositoryInterface $dichVuRepo,
        SuDungDichVuRepositoryInterface $suDungRepo,
        HoaDonRepositoryInterface $hoaDonRepo,
        LichHenRepositoryInterface $lichHenRepo
    ) {
        $this->dichVuRepo = $dichVuRepo;
        $this->suDungRepo = $suDungRepo;
        $this->hoaDonRepo = $hoaDonRepo;
        $this->lichHenRepo = $lichHenRepo;
    }

    public function danhSachDichVu(): Collection
    {
        return $this->dichVuRepo->layDichVuHoatDong();
    }

    public function chiDinhDichVu(int $lichHenId, int $dichVuId, int $soLuong = 1, string $ghiChu = '')
    {
        $dichVu = $this->dichVuRepo->timTheoId($dichVuId);
        if (!$dichVu) {
            throw new \Exception('Dịch vụ y tế không tồn tại.');
        }

        $donGia = $dichVu->don_gia;
        $thanhTien = $donGia * $soLuong;

        $suDung = $this->suDungRepo->taoMoi([
            'lich_hen_id' => $lichHenId,
            'dich_vu_id' => $dichVuId,
            'so_luong' => $soLuong,
            'don_gia' => $donGia,
            'thanh_tien' => $thanhTien,
            'ghi_chu' => $ghiChu,
        ]);

        // Cập nhật lại hóa đơn nếu đã có
        $hoaDon = $this->hoaDonRepo->timTheoLichHenId($lichHenId);
        if ($hoaDon) {
            $tongTienDichVu = $this->suDungRepo->getModel()->where('lich_hen_id', $lichHenId)->sum('thanh_tien');
            $this->hoaDonRepo->capNhat($hoaDon->id, [
                'tien_dich_vu' => $tongTienDichVu,
                'tong_tien' => $hoaDon->tien_kham + $tongTienDichVu,
            ]);
        }

        return $suDung->load('dichVu');
    }

    public function capNhatKetQua(int $id, string $ketQua)
    {
        return $this->suDungRepo->capNhat($id, ['ket_qua' => $ketQua]);
    }
}
