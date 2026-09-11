<?php

namespace App\Modules\LichHen\Services;

use App\Modules\LichHen\Repositories\LichHenRepositoryInterface;
use App\Modules\BenhNhan\Repositories\BenhNhanRepositoryInterface;
use App\Modules\BacSi\Repositories\BacSiRepositoryInterface;
use App\Modules\HoaDon\Repositories\HoaDonRepositoryInterface;
use App\Notifications\ThongBaoLichHenNotification;
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
        // 1. Kiểm tra tính hợp lệ của Ngày & Giờ khám
        $ngayKham = $duLieu['ngay_kham'] ?? null;
        $gioKham = trim($duLieu['gio_kham'] ?? '');
        $today = date('Y-m-d');

        if (!$ngayKham || !$gioKham) {
            throw ValidationException::withMessages(['thoi_gian' => ['Vui lòng chọn ngày khám và giờ khám.']]);
        }

        if ($ngayKham < $today) {
            throw ValidationException::withMessages(['ngay_kham' => ['Không thể đặt lịch khám vào ngày trong quá khứ. Vui lòng chọn ngày từ hôm nay trở đi.']]);
        }

        // Kiểm tra khung giờ hoạt động phòng khám (07:00 - 18:00)
        $gioParts = explode(':', $gioKham);
        if (count($gioParts) >= 2) {
            $hour = (int)$gioParts[0];
            $minute = (int)$gioParts[1];
            if ($hour < 7 || $hour > 18 || ($hour === 18 && $minute > 0)) {
                throw ValidationException::withMessages(['gio_kham' => ['Phòng khám chỉ tiếp nhận lịch khám từ 07:00 đến 18:00 hàng ngày.']]);
            }
        }

        // Nếu đặt lịch trong ngày hôm nay, kiểm tra xem giờ đã trôi qua chưa
        if ($ngayKham === $today) {
            $currentTime = date('H:i');
            if ($gioKham <= $currentTime) {
                throw ValidationException::withMessages(['gio_kham' => ["Khung giờ $gioKham đã qua so với giờ hiện tại ($currentTime). Vui lòng chọn khung giờ khám tiếp theo."]]);
            }
        }

        // 2. Xác định hồ sơ bệnh nhân & cập nhật bệnh án điện tử
        $benhNhanId = $duLieu['benh_nhan_id'] ?? null;
        $soCccd = !empty($duLieu['so_cccd']) ? trim($duLieu['so_cccd']) : null;
        $thongTinBenhAn = [
            'tien_su_benh' => $duLieu['tien_su_benh'] ?? null,
            'tien_su_di_ung' => $duLieu['tien_su_di_ung'] ?? null,
            'nguoi_lien_he_khan_cap' => $duLieu['nguoi_lien_he_khan_cap'] ?? null,
            'sdt_khan_cap' => $duLieu['sdt_khan_cap'] ?? null,
            'nhom_mau' => $duLieu['nhom_mau'] ?? null,
        ];
        // Lọc bỏ các giá trị null để tránh ghi đè dữ liệu cũ nếu không nhập
        $thongTinBenhAnFiltered = array_filter($thongTinBenhAn, fn($v) => !is_null($v) && $v !== '');

        if (!$benhNhanId && $taiKhoanHienTai) {
            $benhNhan = $this->benhNhanRepo->timTheoTaiKhoanId($taiKhoanHienTai->id);
            if (!$benhNhan) {
                $maBN = 'BN' . date('Ymd') . str_pad($taiKhoanHienTai->id, 4, '0', STR_PAD_LEFT);
                $duLieuTao = array_merge([
                    'tai_khoan_id' => $taiKhoanHienTai->id,
                    'ma_benh_nhan' => $maBN,
                    'ho_ten' => !empty($duLieu['ho_ten']) ? $duLieu['ho_ten'] : $taiKhoanHienTai->ho_ten,
                    'so_dien_thoai' => !empty($duLieu['so_dien_thoai']) ? $duLieu['so_dien_thoai'] : $taiKhoanHienTai->so_dien_thoai,
                    'so_cccd' => $soCccd,
                ], $thongTinBenhAnFiltered);
                $benhNhan = $this->benhNhanRepo->taoMoi($duLieuTao);
            } else {
                $duLieuCapNhat = $thongTinBenhAnFiltered;
                if ($soCccd && empty($benhNhan->so_cccd)) {
                    $duLieuCapNhat['so_cccd'] = $soCccd;
                }
                if (!empty($duLieuCapNhat)) {
                    $benhNhan->update($duLieuCapNhat);
                }
            }
            $benhNhanId = $benhNhan->id;
        }

        if (!$benhNhanId && !empty($duLieu['so_dien_thoai']) && !empty($duLieu['ho_ten'])) {
            $benhNhan = \App\Modules\BenhNhan\Models\BenhNhan::where('so_dien_thoai', $duLieu['so_dien_thoai'])->first();
            if (!$benhNhan) {
                $maBN = 'BN' . date('Ymd') . rand(1000, 9999);
                $duLieuTao = array_merge([
                    'ma_benh_nhan' => $maBN,
                    'ho_ten' => $duLieu['ho_ten'],
                    'so_dien_thoai' => $duLieu['so_dien_thoai'],
                    'so_cccd' => $soCccd,
                    'email' => $duLieu['email'] ?? null,
                ], $thongTinBenhAnFiltered);
                $benhNhan = $this->benhNhanRepo->taoMoi($duLieuTao);
            } else {
                $duLieuCapNhat = $thongTinBenhAnFiltered;
                if ($soCccd && empty($benhNhan->so_cccd)) {
                    $duLieuCapNhat['so_cccd'] = $soCccd;
                }
                if (!empty($duLieuCapNhat)) {
                    $benhNhan->update($duLieuCapNhat);
                }
            }
            $benhNhanId = $benhNhan->id;
        }

        if (!$benhNhanId) {
            throw ValidationException::withMessages(['benh_nhan' => ['Vui lòng nhập họ tên và số điện thoại bệnh nhân.']]);
        }

        // 3. Kiểm tra xung đột / trùng lịch nâng cao (khoảng cách tối thiểu 30 phút giữa 2 ca)
        $xungDot = $this->lichHenRepo->kiemTraTrungLichNangCao(
            (int)$duLieu['bac_si_id'],
            $ngayKham,
            $gioKham,
            null,
            30
        );

        if ($xungDot) {
            throw ValidationException::withMessages([
                'gio_kham' => [$xungDot['ly_do']]
            ]);
        }

        // 4. Tạo mã lịch hẹn tăng dần theo mẫu LK0001, LK0002... & lưu
        $maLichHen = $this->lichHenRepo->layMaLichHenTiepTheo();
        $lichHen = $this->lichHenRepo->taoMoi([
            'ma_lich_hen' => $maLichHen,
            'benh_nhan_id' => $benhNhanId,
            'bac_si_id' => (int)$duLieu['bac_si_id'],
            'ngay_kham' => $ngayKham,
            'gio_kham' => $gioKham,
            'trieu_chung' => $duLieu['trieu_chung'] ?? 'Khám tổng quát',
            'trang_thai' => 'CHO_XAC_NHAN',
            'ghi_chu' => $duLieu['ghi_chu'] ?? null,
        ])->load(['bacSi.chuyenKhoa', 'benhNhan']);

        // 5. Gửi thông báo mô phỏng
        $notification = new ThongBaoLichHenNotification($lichHen, 'DAT_LICH');
        $lichHen->thong_bao = $notification->toArray($lichHen);

        return $lichHen;
    }

    private function kiemTraQuyenBacSiHoacAdmin($lichHen, $user)
    {
        if (!$user) {
            throw new \Exception('Vui lòng đăng nhập để thực hiện thao tác.');
        }

        $maVaiTro = $user->vaiTro?->ma_vai_tro;
        if ($maVaiTro === 'ADMIN') {
            return; // Quản trị viên có toàn quyền điều phối
        }

        if ($maVaiTro === 'BAC_SI') {
            $bacSi = $this->bacSiRepo->timTheoTaiKhoanId($user->id);
            if (!$bacSi || (int)$lichHen->bac_si_id !== (int)$bacSi->id) {
                throw new \Exception('Bạn không phải là bác sĩ được phân công cho ca khám này.');
            }
            return;
        }

        throw new \Exception('Chỉ Bác sĩ phụ trách ca khám hoặc Quản trị viên mới có quyền thực hiện thao tác này.');
    }

    public function xacNhanLichHen(int $id, $user = null)
    {
        $lichHen = $this->lichHenRepo->timTheoId($id);
        if (!$lichHen) {
            throw new \Exception('Không tìm thấy lịch hẹn.');
        }

        if ($user) {
            $this->kiemTraQuyenBacSiHoacAdmin($lichHen, $user);
        }

        $this->lichHenRepo->capNhat($id, ['trang_thai' => 'DA_XAC_NHAN']);
        $lichHenUpdated = $this->lichHenRepo->timTheoId($id)->load(['bacSi.chuyenKhoa', 'benhNhan']);
        
        $notification = new ThongBaoLichHenNotification($lichHenUpdated, 'XAC_NHAN');
        $lichHenUpdated->thong_bao = $notification->toArray($lichHenUpdated);

        return $lichHenUpdated;
    }

    public function batDauKham(int $id, $user = null)
    {
        $lichHen = $this->lichHenRepo->timTheoId($id);
        if (!$lichHen) {
            throw new \Exception('Không tìm thấy lịch hẹn.');
        }

        if ($user) {
            $this->kiemTraQuyenBacSiHoacAdmin($lichHen, $user);
        }

        $this->lichHenRepo->capNhat($id, ['trang_thai' => 'DANG_KHAM']);
        return $this->lichHenRepo->timTheoId($id)->load(['bacSi.chuyenKhoa', 'benhNhan']);
    }

    public function hoanThanhKham(int $id, array $ketQua, $user = null)
    {
        $lichHen = $this->lichHenRepo->timTheoId($id);
        if (!$lichHen) {
            throw new \Exception('Không tìm thấy lịch hẹn.');
        }

        if ($user) {
            $this->kiemTraQuyenBacSiHoacAdmin($lichHen, $user);
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

        $res = $this->lichHenRepo->timTheoId($id)?->load(['hoaDon', 'suDungDichVu.dichVu', 'bacSi.chuyenKhoa', 'benhNhan']);
        if ($res) {
            $notification = new ThongBaoLichHenNotification($res, 'HOAN_THANH');
            $res->thong_bao = $notification->toArray($res);
        }
        return $res;
    }

    public function huyLichHen(int $id, string $lyDo = '', $user = null)
    {
        $lichHen = $this->lichHenRepo->timTheoId($id);
        if (!$lichHen) {
            throw new \Exception('Không tìm thấy thông tin lịch hẹn.');
        }

        if ($lichHen->trang_thai === 'DA_HUY') {
            throw new \Exception('Lịch hẹn này đã bị hủy trước đó.');
        }

        if (in_array($lichHen->trang_thai, ['DANG_KHAM', 'HOAN_THANH'])) {
            throw new \Exception('Không thể hủy lịch hẹn khi bác sĩ đang khám hoặc đã hoàn tất.');
        }

        // Kiểm tra quyền sở hữu nếu là tài khoản Bệnh nhân
        if ($user && isset($user->vaiTro) && $user->vaiTro->ma_vai_tro === 'BENH_NHAN') {
            $benhNhan = $this->benhNhanRepo->timTheoTaiKhoanId($user->id);
            if (!$benhNhan || (int)$lichHen->benh_nhan_id !== (int)$benhNhan->id) {
                throw new \Exception('Bạn chỉ có quyền hủy lịch khám của chính mình.');
            }
        }

        $ghiChu = trim(($lichHen->ghi_chu ? $lichHen->ghi_chu . ' | ' : '') . 'Lý do hủy: ' . ($lyDo ?: 'Người bệnh yêu cầu hủy lịch'));

        $this->lichHenRepo->capNhat($id, [
            'trang_thai' => 'DA_HUY',
            'ghi_chu' => $ghiChu,
        ]);

        $lichHenUpdated = $this->lichHenRepo->timTheoId($id)->load(['bacSi.chuyenKhoa', 'benhNhan']);
        $notification = new ThongBaoLichHenNotification($lichHenUpdated, 'HUY_LICH', $lyDo);
        $lichHenUpdated->thong_bao = $notification->toArray($lichHenUpdated);

        return $lichHenUpdated;
    }
}
