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
        'CA_CHIEU' => ['13:30', '17:30'],
        'CA_TOI' => ['17:30', '20:30'],
        'CA_NGAY' => ['07:30', '17:30'],
    ];

    /**
     * Danh sách lịch trực của 1 bác sĩ
     */
    public function layTheoBacSi(int $bacSiId): array
    {
        $bacSi = BacSi::find($bacSiId);
        if (!$bacSi) {
            return [
                'thanh_cong' => false,
                'ma_loi' => 'BAC_SI_KHONG_TON_TAI',
                'thong_diep' => 'Không tìm thấy bác sĩ.'
            ];
        }

        $dsLich = LichTrucBacSi::where('bac_si_id', $bacSiId)
            ->where('trang_thai', 'HOAT_DONG')
            ->orderBy('ngay_trong_tuan')
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
     * Thêm mới ca trực cho bác sĩ
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
        $caTruc = $duLieu['ca_truc'] ?? 'CA_SANG';

        $gioBatDau = $duLieu['gio_bat_dau'] ?? (self::GIO_MAC_DINH_CA[$caTruc][0] ?? '07:30');
        $gioKetThuc = $duLieu['gio_ket_thuc'] ?? (self::GIO_MAC_DINH_CA[$caTruc][1] ?? '11:30');
        $phongKham = $duLieu['phong_kham'] ?? $bacSi->phong_kham;

        // Kiểm tra ca trực trùng lặp
        $daCo = LichTrucBacSi::where('bac_si_id', $bacSiId)
            ->where('ngay_trong_tuan', $ngayTrongTuan)
            ->where('ca_truc', $caTruc)
            ->first();

        if ($daCo) {
            $daCo->update([
                'gio_bat_dau' => $gioBatDau,
                'gio_ket_thuc' => $gioKetThuc,
                'phong_kham' => $phongKham,
                'so_luong_kham_toi_da' => $duLieu['so_luong_kham_toi_da'] ?? 20,
                'trang_thai' => 'HOAT_DONG',
            ]);
            return [
                'thanh_cong' => true,
                'thong_diep' => "Đã cập nhật ca trực {$caTruc} (" . self::MAP_TEN_THU[$ngayTrongTuan] . ") cho BS. {$bacSi->ho_ten}.",
                'du_lieu' => $daCo
            ];
        }

        $caMoi = LichTrucBacSi::create([
            'bac_si_id' => $bacSiId,
            'thu' => $thu,
            'ngay_trong_tuan' => $ngayTrongTuan,
            'ca_truc' => $caTruc,
            'gio_bat_dau' => $gioBatDau,
            'gio_ket_thuc' => $gioKetThuc,
            'so_luong_kham_toi_da' => $duLieu['so_luong_kham_toi_da'] ?? 20,
            'phong_kham' => $phongKham,
            'trang_thai' => 'HOAT_DONG',
        ]);

        return [
            'thanh_cong' => true,
            'thong_diep' => "Thêm ca trực {$caTruc} (" . self::MAP_TEN_THU[$ngayTrongTuan] . ") cho BS. {$bacSi->ho_ten} thành công.",
            'du_lieu' => $caMoi
        ];
    }

    /**
     * Cập nhật ca trực
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

        $fields = ['ca_truc', 'gio_bat_dau', 'gio_ket_thuc', 'so_luong_kham_toi_da', 'phong_kham', 'trang_thai'];
        foreach ($fields as $f) {
            if (isset($duLieu[$f])) {
                $ca->{$f} = $duLieu[$f];
            }
        }

        if (isset($duLieu['ngay_trong_tuan'])) {
            $ntt = (int)$duLieu['ngay_trong_tuan'];
            $ca->ngay_trong_tuan = $ntt;
            $ca->thu = self::MAP_THU[$ntt] ?? $ca->thu;
        }

        $ca->save();

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Cập nhật ca trực thành công.',
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
     * Kiểm tra bác sĩ có trực vào ngày/giờ này không
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
            ->where('trang_thai', 'HOAT_DONG')
            ->get();

        if ($caTrucList->isEmpty()) {
            return [
                'co_truc' => false,
                'ngay_trong_tuan' => $ngayTrongTuan,
                'thu' => self::MAP_TEN_THU[$ngayTrongTuan] ?? 'N/A',
                'thong_diep' => 'Bác sĩ không có lịch trực vào ' . (self::MAP_TEN_THU[$ngayTrongTuan] ?? '') . '.',
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
