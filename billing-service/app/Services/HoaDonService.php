<?php

namespace App\Services;

use App\Models\ChiTietHoaDon;
use App\Models\HoaDon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HoaDonService
{
    /**
     * TU DONG TONG HOP HOA DON LIEN DICH VU (Inter-Service Communication)
     * 1. Goi Service 02: Lay thong tin lich hen & benh nhan
     * 2. Goi Service 01: Lay gia kham cua bac si
     * 3. Goi Service 03: Lay danh sach can lam sang da chi dinh
     * 4. Tong hop chi phi va lap hoa don
     */
    public function taoTuDong(int $lichHenId, float $giamGia = 0, ?string $ghiChu = null): array
    {
        $urlLichHen = config('services.dich_vu_lich_hen', 'http://127.0.0.1:8002');
        $urlXacThuc = config('services.dich_vu_xac_thuc', 'http://127.0.0.1:8001');
        $urlYTe = config('services.dich_vu_y_te', 'http://127.0.0.1:8003');

        // Lay danh sach can lam sang da chi dinh tu Service 03
        $danhSachDichVuCLS = [];
        try {
            $respYTe = Http::timeout(3)->get("{$urlYTe}/api/dich-vu/lich-hen/{$lichHenId}");
            if ($respYTe->successful() && isset($respYTe['du_lieu'])) {
                $danhSachDichVuCLS = $respYTe['du_lieu'];
            }
        } catch (\Exception $e) {
            Log::warning("Khong the ket noi Service 03 de lay danh sach can lam sang: " . $e->getMessage());
        }

        // Kiem tra neu hoa don cho lich hen nay da ton tai
        $hoaDonTonTai = HoaDon::with('chiTiet')->where('lich_hen_id', $lichHenId)->first();
        if ($hoaDonTonTai) {
            $existingItemNames = $hoaDonTonTai->chiTiet->pluck('ten_khoan_thu')->toArray();
            $tienDichVuThem = 0;
            $itemsThemMoi = [];

            DB::transaction(function () use ($hoaDonTonTai, $danhSachDichVuCLS, $existingItemNames, &$tienDichVuThem, &$itemsThemMoi) {
                foreach ($danhSachDichVuCLS as $cls) {
                    $tenDichVu = $cls['dich_vu']['ten_dich_vu'] ?? ($cls['ten_dich_vu'] ?? 'Dịch vụ cận lâm sàng');
                    if (!in_array($tenDichVu, $existingItemNames)) {
                        $soLuong = (int)($cls['so_luong'] ?? 1);
                        $donGia = (float)($cls['don_gia'] ?? 0);
                        $thanhTien = $soLuong * $donGia;
                        $tienDichVuThem += $thanhTien;

                        $newItem = ChiTietHoaDon::create([
                            'hoa_don_id' => $hoaDonTonTai->id,
                            'loai_khoan_thu' => 'CAN_LAM_SANG',
                            'ten_khoan_thu' => $tenDichVu,
                            'so_luong' => $soLuong,
                            'don_gia' => $donGia,
                            'thanh_tien' => $thanhTien,
                        ]);
                        $itemsThemMoi[] = $newItem;
                        $existingItemNames[] = $tenDichVu;
                    }
                }

                if ($tienDichVuThem > 0) {
                    $hoaDonTonTai->tien_dich_vu = (float)$hoaDonTonTai->tien_dich_vu + $tienDichVuThem;
                    $hoaDonTonTai->tong_tien = (float)$hoaDonTonTai->tong_tien + $tienDichVuThem;
                    $hoaDonTonTai->thuc_thu = max(0, (float)$hoaDonTonTai->tong_tien - (float)$hoaDonTonTai->giam_gia);
                    $hoaDonTonTai->trang_thai = 'CHUA_THANH_TOAN'; // Chuyen sang cho thanh toan khi co phi phat sinh moi
                    $hoaDonTonTai->save();
                }
            });

            $thongDiep = $tienDichVuThem > 0 
                ? 'Đã đồng bộ ' . count($itemsThemMoi) . ' chỉ định cận lâm sàng mới vào hóa đơn hiện tại.'
                : 'Hóa đơn cho ca khám này đã tồn tại và đầy đủ thông tin.';

            return [
                'thanh_cong' => true,
                'thong_diep' => $thongDiep,
                'du_lieu' => $hoaDonTonTai->fresh('chiTiet')
            ];
        }

        $benhNhanId = null;
        $bacSiId = null;
        $tienKham = 200000.00; // Gia mac dinh phong ngua fallback
        $tenBacSi = 'Bác sĩ khám';
        $danhSachDichVuCLS = [];

        $canhBao = [];

        // 1. Goi Service 02 lay lich hen
        try {
            $respLichHen = Http::timeout(3)->get("{$urlLichHen}/api/lich-hen/{$lichHenId}");
            if ($respLichHen->successful() && isset($respLichHen['du_lieu'])) {
                $lh = $respLichHen['du_lieu'];
                $benhNhanId = $lh['benh_nhan_id'] ?? 1;
                $bacSiId = $lh['bac_si_id'] ?? null;
            } else {
                $canhBao[] = 'Không thể kết nối Service 02 để lấy lịch hẹn.';
            }
        } catch (\Exception $e) {
            $canhBao[] = 'Không thể kết nối Service 02: ' . $e->getMessage();
            Log::warning("Khong the ket noi Service 02 de lay lich hen: " . $e->getMessage());
        }

        if (!$benhNhanId) {
            $benhNhanId = 1; // Fallback
        }

        // 2. Goi Service 01 lay thong tin bac si & gia kham
        if ($bacSiId) {
            try {
                $respBacSi = Http::timeout(3)->get("{$urlXacThuc}/api/bac-si/{$bacSiId}");
                if ($respBacSi->successful() && isset($respBacSi['du_lieu'])) {
                    $bs = $respBacSi['du_lieu'];
                    $tienKham = (float)($bs['gia_kham'] ?? 200000.00);
                    $tenBacSi = $bs['tai_khoan']['ho_ten'] ?? ($bs['ho_ten'] ?? 'Bác sĩ khám');
                } else {
                    $canhBao[] = 'Không thể kết nối Service 01 để lấy giá khám bác sĩ.';
                }
            } catch (\Exception $e) {
                $canhBao[] = 'Không thể kết nối Service 01: ' . $e->getMessage();
                Log::warning("Khong the ket noi Service 01 de lay gia kham: " . $e->getMessage());
            }
        }

        // 3. Goi Service 03 lay cac dich vu can lam sang da thuc hien
        try {
            $respYTe = Http::timeout(3)->get("{$urlYTe}/api/dich-vu/lich-hen/{$lichHenId}");
            if ($respYTe->successful() && isset($respYTe['du_lieu'])) {
                $danhSachDichVuCLS = $respYTe['du_lieu'];
            } else {
                $canhBao[] = 'Không thể kết nối Service 03 để lấy danh sách cận lâm sàng.';
            }
        } catch (\Exception $e) {
            $canhBao[] = 'Không thể kết nối Service 03: ' . $e->getMessage();
            Log::warning("Khong the ket noi Service 03 de lay danh sach can lam sang: " . $e->getMessage());
        }

        // 4. Tinh toan tong tien
        $tienDichVu = 0;
        foreach ($danhSachDichVuCLS as $cls) {
            $thanhTien = ((float)($cls['don_gia'] ?? 0)) * ((int)($cls['so_luong'] ?? 1));
            $tienDichVu += $thanhTien;
        }

        $tongTien = $tienKham + $tienDichVu;
        $thucThu = max(0, $tongTien - $giamGia);

        $maHoaDon = 'HD-' . date('Ymd') . '-' . str_pad((string)rand(1, 9999), 4, '0', STR_PAD_LEFT);

        $hoaDon = DB::transaction(function () use (
            $maHoaDon, $lichHenId, $benhNhanId, $tienKham, $tienDichVu, $tongTien, $giamGia, $thucThu,
            $tenBacSi, $danhSachDichVuCLS
        ) {
            $hd = HoaDon::create([
                'ma_hoa_don' => $maHoaDon,
                'lich_hen_id' => $lichHenId,
                'benh_nhan_id' => $benhNhanId,
                'tien_kham' => $tienKham,
                'tien_dich_vu' => $tienDichVu,
                'tong_tien' => $tongTien,
                'giam_gia' => $giamGia,
                'thuc_thu' => $thucThu,
                'phuong_thuc_thanh_toan' => 'TIEN_MAT',
                'trang_thai' => 'CHUA_THANH_TOAN',
                'ngay_thanh_toan' => null,
                'ghi_chu' => 'Hóa đơn tổng hợp tự động',
            ]);

            // Chi tiet tien kham
            ChiTietHoaDon::create([
                'hoa_don_id' => $hd->id,
                'loai_khoan_thu' => 'TIEN_KHAM',
                'ten_khoan_thu' => "Công khám bệnh ({$tenBacSi})",
                'so_luong' => 1,
                'don_gia' => $tienKham,
                'thanh_tien' => $tienKham,
            ]);

            // Chi tiet cac dich vu CLS
            foreach ($danhSachDichVuCLS as $cls) {
                $tenDichVu = $cls['dich_vu']['ten_dich_vu'] ?? ($cls['ten_dich_vu'] ?? 'Dịch vụ cận lâm sàng');
                $soLuong = (int)($cls['so_luong'] ?? 1);
                $donGia = (float)($cls['don_gia'] ?? 0);
                $thanhTien = $soLuong * $donGia;

                ChiTietHoaDon::create([
                    'hoa_don_id' => $hd->id,
                    'loai_khoan_thu' => 'CAN_LAM_SANG',
                    'ten_khoan_thu' => $tenDichVu,
                    'so_luong' => $soLuong,
                    'don_gia' => $donGia,
                    'thanh_tien' => $thanhTien,
                ]);
            }

            return $hd;
        });

        $duLieu = $hoaDon->load('chiTiet')->toArray();
        if (!empty($canhBao)) {
            $duLieu['canh_bao'] = $canhBao;
        }

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Tổng hợp và tạo hóa đơn tự động thành công.',
            'du_lieu' => $duLieu
        ];
    }

    public function thanhToan(int $id, string $phuongThuc = 'TIEN_MAT', ?string $ghiChu = null): array
    {
        $hoaDon = HoaDon::with('chiTiet')->find($id);
        if (!$hoaDon) {
            return [
                'thanh_cong' => false,
                'thong_diep' => 'Không tìm thấy hóa đơn.'
            ];
        }

        if ($hoaDon->trang_thai === 'DA_THANH_TOAN') {
            return [
                'thanh_cong' => false,
                'thong_diep' => 'Hóa đơn này đã được thanh toán trước đó.',
                'ma_loi' => 'HOA_DON_DA_THANH_TOAN'
            ];
        }

        if ($hoaDon->trang_thai === 'DA_HOAN_TIEN') {
            return [
                'thanh_cong' => false,
                'thong_diep' => 'Hóa đơn này đã được hoàn tiền, không thể thanh toán.',
                'ma_loi' => 'HOA_DON_DA_HOAN_TIEN'
            ];
        }

        $hoaDon->update([
            'trang_thai' => 'DA_THANH_TOAN',
            'phuong_thuc_thanh_toan' => $phuongThuc,
            'ngay_thanh_toan' => now(),
            'ghi_chu' => $ghiChu ?? $hoaDon->ghi_chu,
        ]);

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Thanh toán hóa đơn thành công.',
            'du_lieu' => $hoaDon
        ];
    }

    public function hoanTien(int $id, ?string $lyDo = null): array
    {
        $hoaDon = HoaDon::with('chiTiet')->find($id);
        if (!$hoaDon) {
            return [
                'thanh_cong' => false,
                'thong_diep' => 'Không tìm thấy hóa đơn.'
            ];
        }

        if ($hoaDon->trang_thai === 'DA_HOAN_TIEN') {
            return [
                'thanh_cong' => false,
                'thong_diep' => 'Hóa đơn này đã được hoàn tiền trước đó.',
                'ma_loi' => 'HOA_DON_DA_HOAN_TIEN'
            ];
        }

        if ($hoaDon->trang_thai !== 'DA_THANH_TOAN') {
            return [
                'thanh_cong' => false,
                'thong_diep' => 'Chỉ có thể hoàn tiền cho hóa đơn đã thanh toán.',
                'ma_loi' => 'HOA_DON_CHUA_THANH_TOAN'
            ];
        }

        $ghiChuCu = $hoaDon->ghi_chu;
        $lyDoText = $lyDo ?: 'Hoàn trả viện phí theo quy định';
        $ghiChuMoi = ($ghiChuCu ? $ghiChuCu . ' | ' : '') . "[HOÀN TIỀN: {$lyDoText}]";

        $hoaDon->update([
            'trang_thai' => 'DA_HOAN_TIEN',
            'ghi_chu' => $ghiChuMoi,
        ]);

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Hoàn tiền hóa đơn thành công.',
            'du_lieu' => $hoaDon
        ];
    }

    public function chiTiet(int $id)
    {
        return HoaDon::with('chiTiet')->find($id);
    }

    public function danhSach(array $boLoc = [])
    {
        $query = HoaDon::with('chiTiet')->orderBy('id', 'desc');

        if (!empty($boLoc['benh_nhan_id'])) {
            $query->where('benh_nhan_id', $boLoc['benh_nhan_id']);
        }
        if (!empty($boLoc['trang_thai'])) {
            $query->where('trang_thai', $boLoc['trang_thai']);
        }

        return $query->get();
    }

    public function thongKe(): array
    {
        $tongDoanhThu = HoaDon::where('trang_thai', 'DA_THANH_TOAN')->sum('thuc_thu');
        $soHoaDonDaThanhToan = HoaDon::where('trang_thai', 'DA_THANH_TOAN')->count();
        $soHoaDonChuaThanhToan = HoaDon::where('trang_thai', 'CHUA_THANH_TOAN')->count();

        $theoPhuongThuc = HoaDon::where('trang_thai', 'DA_THANH_TOAN')
            ->select('phuong_thuc_thanh_toan', DB::raw('SUM(thuc_thu) as tong_tien'), DB::raw('COUNT(*) as so_luong'))
            ->groupBy('phuong_thuc_thanh_toan')
            ->get();

        return [
            'tong_doanh_thu' => (float)$tongDoanhThu,
            'so_luong_da_thanh_toan' => $soHoaDonDaThanhToan,
            'so_luong_chua_thanh_toan' => $soHoaDonChuaThanhToan,
            'theo_phuong_thuc' => $theoPhuongThuc,
        ];
    }
}
