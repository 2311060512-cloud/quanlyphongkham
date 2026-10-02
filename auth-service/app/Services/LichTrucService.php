<?php

namespace App\Services;

use App\Models\BacSi;
use App\Models\LichTrucBacSi;

class LichTrucService
{
    const MAP_THU = [
        2 => 'THU_HAI',
        3 => 'THU_BA',
        4 => 'THU_TU',
        5 => 'THU_NAM',
        6 => 'THU_SAU',
        7 => 'THU_BAY',
        8 => 'CHU_NHAT',
    ];

    const MAP_TEN_THU = [
        2 => 'Thứ Hai',
        3 => 'Thứ Ba',
        4 => 'Thứ Tư',
        5 => 'Thứ Năm',
        6 => 'Thứ Sáu',
        7 => 'Thứ Bảy',
        8 => 'Chủ Nhật',
    ];

    const GIO_MAC_DINH_CA = [
        'CA_SANG' => ['07:30', '11:30'],
        'CA_CHIEU' => ['13:30', '17:00'],
        'CA_TOI' => ['17:30', '20:30'],
        'CA_NGAY' => ['07:30', '17:00'],
    ];

    const MAP_TEN_CA = [
        'CA_SANG' => 'Ca Sáng (07:30 - 11:30)',
        'CA_CHIEU' => 'Ca Chiều (13:30 - 17:00)',
        'CA_TOI' => 'Ca Tối (17:30 - 20:30)',
        'CA_NGAY' => 'Cả Ngày (07:30 - 17:00)',
    ];

    /**
     * Danh sách lịch trực của 1 bác sĩ
     * Hỗ trợ lọc theo trạng thái nếu cần (?trang_thai=HOAT_DONG), mặc định lấy toàn bộ để Bác sĩ/Admin quản lý
     */
    public function layTheoBacSi(int $bacSiId, array $boLoc = []): array
    {
        $bacSi = BacSi::find($bacSiId);
        if (!$bacSi) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'BAC_SI_KHONG_TON_TAI',
                'thong_diep' => 'Không tìm thấy bác sĩ.'
            ];
        }

        $query = LichTrucBacSi::where('bac_si_id', $bacSiId);

        if (!empty($boLoc['trang_thai'])) {
            $query->where('trang_thai', $boLoc['trang_thai']);
        }

        $dsLich = $query->orderBy('ngay_trong_tuan')
            ->orderBy('gio_bat_dau')
            ->get();

        return [
            'thanh_cong' => true,
            'thong_diep' => "Lấy lịch trực của bác sĩ {$bacSi->ho_ten} thành công.",
            'du_lieu' => [
                'bac_si' => [
                    'id' => $bacSi->id,
                    'ho_ten' => $bacSi->ho_ten,
                    'hoc_vi' => $bacSi->hoc_vi,
                    'phong_kham' => $bacSi->phong_kham,
                ],
                'danh_sach_ca_truc' => $dsLich
            ]
        ];
    }

    /**
     * Danh sách lịch trực toàn hệ thống (hỗ trợ lọc)
     */
    public function danhSach(array $boLoc = []): array
    {
        $query = LichTrucBacSi::with(['bacSi:id,ho_ten,hoc_vi,phong_kham,chuyen_khoa_id']);

        if (!empty($boLoc['bac_si_id'])) {
            $query->where('bac_si_id', $boLoc['bac_si_id']);
        }

        if (!empty($boLoc['ngay_trong_tuan'])) {
            $query->where('ngay_trong_tuan', (int)$boLoc['ngay_trong_tuan']);
        }

        if (!empty($boLoc['ca_truc'])) {
            $query->where('ca_truc', $boLoc['ca_truc']);
        }

        if (!empty($boLoc['trang_thai'])) {
            $query->where('trang_thai', $boLoc['trang_thai']);
        }

        $ds = $query->orderBy('ngay_trong_tuan')->orderBy('gio_bat_dau')->get();

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Lấy danh sách ca trực thành công.',
            'du_lieu' => $ds
        ];
    }

    /**
     * Thêm mới hoặc Đăng ký đổi ca trực cho bác sĩ
     * QUY TẮC NGHIỆP VỤ:
     * 1. Mỗi ngày chỉ được đăng ký tối đa 1 ca trực (Sáng, Chiều, Tối hoặc Cả Ngày).
     * 2. Bác sĩ đăng ký mới hoặc đổi lịch -> BẮT BUỘC trạng thái CHO_DUYET để Admin duyệt.
     * 3. Chỉ khi Admin trực tiếp tạo hoặc duyệt thì mới chuyển sang HOAT_DONG.
     */
    public function themMoi(int $bacSiId, array $duLieu): array
    {
        $bacSi = BacSi::find($bacSiId);
        if (!$bacSi) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'BAC_SI_KHONG_TON_TAI',
                'thong_diep' => 'Không tìm thấy bác sĩ.'
            ];
        }

        $ngayTrongTuan = (int)($duLieu['ngay_trong_tuan'] ?? 2);
        if ($ngayTrongTuan < 2 || $ngayTrongTuan > 8) {
            $ngayTrongTuan = 2;
        }

        $thu = self::MAP_THU[$ngayTrongTuan] ?? 'THU_HAI';
        $tenThu = self::MAP_TEN_THU[$ngayTrongTuan] ?? "Thứ {$ngayTrongTuan}";
        $caTruc = $duLieu['ca_truc'] ?? 'CA_SANG';

        $gioBatDau = $duLieu['gio_bat_dau'] ?? (self::GIO_MAC_DINH_CA[$caTruc][0] ?? '07:30');
        $gioKetThuc = $duLieu['gio_ket_thuc'] ?? (self::GIO_MAC_DINH_CA[$caTruc][1] ?? '11:30');
        $phongKham = $duLieu['phong_kham'] ?? $bacSi->phong_kham;

        $vaiTro = strtoupper($duLieu['vai_tro_nguoi_gui'] ?? 'BAC_SI');
        $isAdmin = ($vaiTro === 'ADMIN');
        $trangThai = $isAdmin ? ($duLieu['trang_thai'] ?? 'HOAT_DONG') : 'CHO_DUYET';

        $tenCa = self::MAP_TEN_CA[$caTruc] ?? $caTruc;

        // KIỂM TRA QUY TẮC: MỖI NGÀY CHỈ CÓ TỐI ĐA 1 CA TRỰC
        $caCungNgay = LichTrucBacSi::where('bac_si_id', $bacSiId)
            ->where('ngay_trong_tuan', $ngayTrongTuan)
            ->first();

        if ($caCungNgay) {
            // Ngày này đã có ca -> Xử lý ĐỔI LỊCH (cập nhật ca trực của ngày này)
            $caCungNgay->update([
                'ca_truc' => $caTruc,
                'gio_bat_dau' => $gioBatDau,
                'gio_ket_thuc' => $gioKetThuc,
                'phong_kham' => $phongKham,
                'so_luong_kham_toi_da' => $duLieu['so_luong_kham_toi_da'] ?? 20,
                'trang_thai' => $trangThai,
            ]);

            $thongDiep = $isAdmin
                ? "Admin đã cập nhật đổi ca trực sang {$tenCa} ({$tenThu}) cho BS. {$bacSi->ho_ten} thành công."
                : "Đã gửi yêu cầu ĐỔI CA sang {$tenCa} ({$tenThu}) cho BS. {$bacSi->ho_ten}. Vui lòng chờ Quản trị viên (Admin) phê duyệt để có hiệu lực.";

            return [
                'thanh_cong' => true,
                'thong_diep' => $thongDiep,
                'du_lieu' => $caCungNgay
            ];
        }

        // Tạo ca trực mới cho ngày chưa có ca nào
        $caMoi = LichTrucBacSi::create([
            'bac_si_id' => $bacSiId,
            'thu' => $thu,
            'ngay_trong_tuan' => $ngayTrongTuan,
            'ca_truc' => $caTruc,
            'gio_bat_dau' => $gioBatDau,
            'gio_ket_thuc' => $gioKetThuc,
            'so_luong_kham_toi_da' => $duLieu['so_luong_kham_toi_da'] ?? 20,
            'phong_kham' => $phongKham,
            'trang_thai' => $trangThai,
        ]);

        $thongDiep = $isAdmin
            ? "Admin đã thiết lập ca trực {$tenCa} ({$tenThu}) cho BS. {$bacSi->ho_ten} thành công."
            : "Đã gửi yêu cầu ĐĂNG KÝ ca trực {$tenCa} ({$tenThu}) cho BS. {$bacSi->ho_ten}. Vui lòng chờ Quản trị viên (Admin) phê duyệt để kích hoạt.";

        return [
            'thanh_cong' => true,
            'thong_diep' => $thongDiep,
            'du_lieu' => $caMoi
        ];
    }

    /**
     * Cập nhật / Đổi ca trực
     * Bác sĩ cập nhật -> Bắt buộc về trạng thái CHO_DUYET để Admin duyệt
     */
    public function capNhat(int $id, array $duLieu): array
    {
        $ca = LichTrucBacSi::find($id);
        if (!$ca) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'CA_TRUC_KHONG_TON_TAI',
                'thong_diep' => 'Không tìm thấy ca trực.'
            ];
        }

        $vaiTro = strtoupper($duLieu['vai_tro_nguoi_gui'] ?? 'BAC_SI');
        $isAdmin = ($vaiTro === 'ADMIN');

        // Nếu chuyển sang ngày khác trong tuần -> Kiểm tra ngày mới đã có ca trực chưa
        if (isset($duLieu['ngay_trong_tuan'])) {
            $ntt = (int)$duLieu['ngay_trong_tuan'];
            if ($ntt !== $ca->ngay_trong_tuan) {
                $daCoNgayMoi = LichTrucBacSi::where('bac_si_id', $ca->bac_si_id)
                    ->where('ngay_trong_tuan', $ntt)
                    ->where('id', '!=', $id)
                    ->first();
                if ($daCoNgayMoi) {
                    $tenThuMoi = self::MAP_TEN_THU[$ntt] ?? "Thứ {$ntt}";
                    return [
                        'thanh_cong' => false,
                        'ma_loi' => 'TRUNG_NGAY_TRUC',
                        'thong_diep' => "Bác sĩ đã có ca trực vào {$tenThuMoi}. Mỗi ngày chỉ được đăng ký tối đa 1 ca trực."
                    ];
                }
                $ca->ngay_trong_tuan = $ntt;
                $ca->thu = self::MAP_THU[$ntt] ?? $ca->thu;
            }
        }

        $fields = ['ca_truc', 'gio_bat_dau', 'gio_ket_thuc', 'so_luong_kham_toi_da', 'phong_kham'];
        foreach ($fields as $f) {
            if (isset($duLieu[$f])) {
                $ca->{$f} = $duLieu[$f];
            }
        }

        // Bác sĩ chỉnh sửa hoặc đổi ca -> BẮT BUỘC về trạng thái CHO_DUYET
        if ($isAdmin) {
            if (isset($duLieu['trang_thai'])) {
                $ca->trang_thai = $duLieu['trang_thai'];
            }
        } else {
            $ca->trang_thai = 'CHO_DUYET';
        }

        $ca->save();

        $tenThu = self::MAP_TEN_THU[$ca->ngay_trong_tuan] ?? '';
        $tenCa = self::MAP_TEN_CA[$ca->ca_truc] ?? $ca->ca_truc;

        $msg = ($ca->trang_thai === 'CHO_DUYET')
            ? "Đã gửi yêu cầu đổi ca trực sang {$tenCa} ({$tenThu}). Vui lòng chờ Quản trị viên (Admin) phê duyệt để có hiệu lực."
            : "Cập nhật ca trực ({$tenThu}) thành công.";

        return [
            'thanh_cong' => true,
            'thong_diep' => $msg,
            'du_lieu' => $ca
        ];
    }

    /**
     * Admin duyệt ca trực -> Kích hoạt HOAT_DONG
     */
    public function duyet(int $id): array
    {
        $ca = LichTrucBacSi::with('bacSi')->find($id);
        if (!$ca) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'CA_TRUC_KHONG_TON_TAI',
                'thong_diep' => 'Không tìm thấy ca trực cần duyệt.'
            ];
        }

        // Dọn dẹp an toàn: đảm bảo không còn ca nào khác trong cùng ngày của bác sĩ
        LichTrucBacSi::where('bac_si_id', $ca->bac_si_id)
            ->where('ngay_trong_tuan', $ca->ngay_trong_tuan)
            ->where('id', '!=', $id)
            ->delete();

        $ca->update(['trang_thai' => 'HOAT_DONG']);

        $tenThu = self::MAP_TEN_THU[$ca->ngay_trong_tuan] ?? '';
        $tenCa = self::MAP_TEN_CA[$ca->ca_truc] ?? $ca->ca_truc;
        $tenBs = $ca->bacSi?->ho_ten ?? 'Bác sĩ';

        return [
            'thanh_cong' => true,
            'thong_diep' => "Đã duyệt và kích hoạt ca trực {$tenCa} ({$tenThu}) cho BS. {$tenBs} thành công.",
            'du_lieu' => $ca
        ];
    }

    /**
     * Admin từ chối ca trực -> Chuyển sang TU_CHOI
     */
    public function tuChoi(int $id, ?string $lyDo = null): array
    {
        $ca = LichTrucBacSi::with('bacSi')->find($id);
        if (!$ca) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'CA_TRUC_KHONG_TON_TAI',
                'thong_diep' => 'Không tìm thấy ca trực.'
            ];
        }

        $ca->update(['trang_thai' => 'TU_CHOI']);

        $tenThu = self::MAP_TEN_THU[$ca->ngay_trong_tuan] ?? '';
        $tenCa = self::MAP_TEN_CA[$ca->ca_truc] ?? $ca->ca_truc;
        $tenBs = $ca->bacSi?->ho_ten ?? 'Bác sĩ';

        return [
            'thanh_cong' => true,
            'thong_diep' => "Đã từ chối ca trực {$tenCa} ({$tenThu}) của BS. {$tenBs}.",
            'du_lieu' => $ca
        ];
    }

    /**
     * Xóa ca trực
     */
    public function xoa(int $id): array
    {
        $ca = LichTrucBacSi::find($id);
        if (!$ca) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'CA_TRUC_KHONG_TON_TAI',
                'thong_diep' => 'Không tìm thấy ca trực cần xóa.'
            ];
        }

        $ca->delete();

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Đã xóa ca trực thành công.'
        ];
    }

    /**
     * Kiểm tra bác sĩ có trực vào ngày/giờ này không (CHỈ TÍNH CA ĐÃ DUYỆT HOẠT ĐỘNG)
     */
    public function kiemTraBacSiCoTruc(int $bacSiId, string $ngayKham, ?string $gioKham = null): array
    {
        $timestamp = strtotime($ngayKham);
        if (!$timestamp) {
            return ['co_truc' => false, 'thong_diep' => 'Ngày khám không đúng định dạng YYYY-MM-DD.'];
        }

        // Mon = 1 ... Sun = 7 -> NgayTrongTuan: 2..8
        $dayOfWeek = (int)date('N', $timestamp);
        $ngayTrongTuan = $dayOfWeek === 7 ? 8 : ($dayOfWeek + 1);

        $caTrucList = LichTrucBacSi::where('bac_si_id', $bacSiId)
            ->where('ngay_trong_tuan', $ngayTrongTuan)
            ->where('trang_thai', 'HOAT_DONG') // Chỉ tính ca đã được Admin duyệt
            ->get();

        if ($caTrucList->isEmpty()) {
            return [
                'co_truc' => false,
                'ngay_trong_tuan' => $ngayTrongTuan,
                'thu' => self::MAP_TEN_THU[$ngayTrongTuan] ?? 'N/A',
                'thong_diep' => 'Bác sĩ không có lịch trực được duyệt vào ' . (self::MAP_TEN_THU[$ngayTrongTuan] ?? '') . '.',
                'ca_truc_trong_ngay' => []
            ];
        }

        if ($gioKham) {
            $phuHop = false;
            foreach ($caTrucList as $ca) {
                if ($gioKham >= $ca->gio_bat_dau && $gioKham <= $ca->gio_ket_thuc) {
                    $phuHop = true;
                    break;
                }
            }

            return [
                'co_truc' => $phuHop,
                'ngay_trong_tuan' => $ngayTrongTuan,
                'thu' => self::MAP_TEN_THU[$ngayTrongTuan] ?? 'N/A',
                'gio_kham' => $gioKham,
                'thong_diep' => $phuHop ? 'Khung giờ phù hợp ca trực.' : 'Giờ khám nằm ngoài khung giờ trực của bác sĩ.',
                'ca_truc_trong_ngay' => $caTrucList
            ];
        }

        return [
            'co_truc' => true,
            'ngay_trong_tuan' => $ngayTrongTuan,
            'thu' => self::MAP_TEN_THU[$ngayTrongTuan] ?? 'N/A',
            'thong_diep' => 'Bác sĩ có ca trực trong ngày.',
            'ca_truc_trong_ngay' => $caTrucList
        ];
    }
}
