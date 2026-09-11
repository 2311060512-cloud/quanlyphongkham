<?php

namespace App\Modules\LichHen\Services;

use App\Modules\LichHen\Repositories\LichHenRepositoryInterface;
use App\Modules\BenhNhan\Repositories\BenhNhanRepositoryInterface;
use App\Modules\BacSi\Repositories\BacSiRepositoryInterface;
use App\Modules\HoaDon\Repositories\HoaDonRepositoryInterface;
use Illuminate\Validation\ValidationException;

class LichHenService
{
    protected LichHenRepositoryInterface $lichHenRepo;
    protected BenhNhanRepositoryInterface $benhNhanRepo;
    protected BacSiRepositoryInterface $bacSiRepo;
    protected HoaDonRepositoryInterface $hoaDonRepo;

    public function __construct(
        LichHenRepositoryInterface $lichHenRepo,
        BenhNhanRepositoryInterface $benhNhanRepo,
        BacSiRepositoryInterface $bacSiRepo,
        HoaDonRepositoryInterface $hoaDonRepo
    ) {
        $this->lichHenRepo = $lichHenRepo;
        $this->benhNhanRepo = $benhNhanRepo;
        $this->bacSiRepo = $bacSiRepo;
        $this->hoaDonRepo = $hoaDonRepo;
    }

    public function datLichHen(array $duLieu, $taiKhoanHienTai)
    {
        // 1. Xác định hồ sơ bệnh nhân
        $benhNhanId = $duLieu['benh_nhan_id'] ?? null;
        if (!$benhNhanId && $taiKhoanHienTai) {
            $benhNhan = $this->benhNhanRepo->timTheoTaiKhoanId($taiKhoanHienTai->id);
            if (!$benhNhan) {
                $maBN = 'BN' . date('Ymd') . str_pad($taiKhoanHienTai->id, 4, '0', STR_PAD_LEFT);
                $benhNhan = $this->benhNhanRepo->taoMoi([
                    'tai_khoan_id' => $taiKhoanHienTai->id,
                    'ma_benh_nhan' => $maBN,
                    'ho_ten' => $taiKhoanHienTai->ho_ten,
                    'so_dien_thoai' => $taiKhoanHienTai->so_dien_thoai,
                ]);
            }
            $benhNhanId = $benhNhan->id;
        }

        if (!$benhNhanId && !empty($duLieu['so_dien_thoai']) && !empty($duLieu['ho_ten'])) {
            $benhNhan = \App\Modules\BenhNhan\Models\BenhNhan::where('so_dien_thoai', $duLieu['so_dien_thoai'])->first();
            if (!$benhNhan) {
                $maBN = 'BN' . date('Ymd') . rand(1000, 9999);
                $benhNhan = $this->benhNhanRepo->taoMoi([
                    'ma_benh_nhan' => $maBN,
                    'ho_ten' => $duLieu['ho_ten'],
                    'so_dien_thoai' => $duLieu['so_dien_thoai'],
                    'email' => $duLieu['email'] ?? null,
                ]);
            }
            $benhNhanId = $benhNhan->id;
        }

        if (!$benhNhanId) {
            throw ValidationException::withMessages(['benh_nhan' => ['Vui lòng nhập họ tên và số điện thoại bệnh nhân.']]);
        }

        // 2. Kiểm tra xung đột / trùng lịch khám của bác sĩ
        $trungLich = $this->lichHenRepo->kiemTraTrungLich(
            $duLieu['bac_si_id'],
            $duLieu['ngay_kham'],
            $duLieu['gio_kham']
        );

        if ($trungLich) {
            throw ValidationException::withMessages([
                'gio_kham' => ['Bác sĩ đã có lịch hẹn vào khung giờ này. Vui lòng chọn khung giờ hoặc bác sĩ khác!']
            ]);
        }

        // 3. Tạo mã lịch hẹn & lưu
        $maLichHen = 'LH' . date('YmdHi') . rand(10, 99);
        return $this->lichHenRepo->taoMoi([
            'ma_lich_hen' => $maLichHen,
            'benh_nhan_id' => $benhNhanId,
            'bac_si_id' => $duLieu['bac_si_id'],
            'ngay_kham' => $duLieu['ngay_kham'],
            'gio_kham' => $duLieu['gio_kham'],
            'trieu_chung' => $duLieu['trieu_chung'] ?? 'Khám tổng quát',
            'trang_thai' => 'CHO_XAC_NHAN',
            'ghi_chu' => $duLieu['ghi_chu'] ?? null,
        ]);
    }

    public function xacNhanLichHen(int $id)
    {
        return $this->lichHenRepo->capNhat($id, ['trang_thai' => 'DA_XAC_NHAN']);
    }

    public function batDauKham(int $id)
    {
        return $this->lichHenRepo->capNhat($id, ['trang_thai' => 'DANG_KHAM']);
    }

    public function hoanThanhKham(int $id, array $ketQua)
    {
        $lichHen = $this->lichHenRepo->timTheoId($id);
        if (!$lichHen) {
            throw new \Exception('Không tìm thấy lịch hẹn.');
        }

        // Cập nhật chẩn đoán
        $this->lichHenRepo->capNhat($id, [
            'chuan_doan' => $ketQua['chuan_doan'] ?? 'Đã hoàn tất khám',
            'loi_khuyen' => $ketQua['loi_khuyen'] ?? 'Uống nhiều nước và nghỉ ngơi',
            'trang_thai' => 'HOAN_THANH',
        ]);

        // Tự động phát sinh hóa đơn viện phí
        $bacSi = $this->bacSiRepo->timTheoId($lichHen->bac_si_id);
        $tienKham = $bacSi ? $bacSi->gia_kham : 150000;

        // Tính tổng tiền các dịch vụ cận lâm sàng đã chỉ định
        $tongTienDichVu = $lichHen->suDungDichVu()->sum('thanh_tien') ?? 0;
        $tongTien = $tienKham + $tongTienDichVu;

        $hoaDonTonTai = $this->hoaDonRepo->timTheoLichHenId($id);
        if (!$hoaDonTonTai) {
            $maHoaDon = 'HD' . date('Ymd') . str_pad($id, 4, '0', STR_PAD_LEFT);
            $this->hoaDonRepo->taoMoi([
                'ma_hoa_don' => $maHoaDon,
                'lich_hen_id' => $id,
                'benh_nhan_id' => $lichHen->benh_nhan_id,
                'tien_kham' => $tienKham,
                'tien_dich_vu' => $tongTienDichVu,
                'tong_tien' => $tongTien,
                'trang_thai' => 'CHUA_THANH_TOAN',
            ]);
        }

        return $this->lichHenRepo->timTheoId($id)?->load(['hoaDon', 'suDungDichVu.dichVu']);
    }

    public function huyLichHen(int $id, string $lyDo = '')
    {
        return $this->lichHenRepo->capNhat($id, [
            'trang_thai' => 'DA_HUY',
            'ghi_chu' => 'Lý do hủy: ' . $lyDo,
        ]);
    }
}
