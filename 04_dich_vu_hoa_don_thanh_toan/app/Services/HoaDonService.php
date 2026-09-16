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
    public function taoTuDong(int $lichHenId, float $giamGia = 0): array
    {
        // Kiem tra neu hoa don cho lich hen nay da ton tai
        $hoaDonTonTai = HoaDon::with('chiTiet')->where('lich_hen_id', $lichHenId)->first();
        if ($hoaDonTonTai) {
            return [
                'thanh_cong' => true,
                'thong_diep' => 'Hoa don cho lich hen nay da duoc tao truoc do.',
                'du_lieu' => $hoaDonTonTai
            ];
        }

        $urlLichHen = config('services.dich_vu_lich_hen', 'http://127.0.0.1:8002');
        $urlXacThuc = config('services.dich_vu_xac_thuc', 'http://127.0.0.1:8001');
        $urlYTe = config('services.dich_vu_y_te', 'http://127.0.0.1:8003');

        $benhNhanId = null;
        $bacSiId = null;
        $tienKham = 200000.00; // Gia mac dinh phong ngua fallback
        $tenBacSi = 'Bac si kham';
        $danhSachDichVuCLS = [];

        // 1. Goi Service 02 lay lich hen
        try {
            $respLichHen = Http::timeout(3)->get("{$urlLichHen}/api/lich-hen/{$lichHenId}");
            if ($respLichHen->successful() && isset($respLichHen['du_lieu'])) {
                $lh = $respLichHen['du_lieu'];
                $benhNhanId = $lh['benh_nhan_id'];
                $bacSiId = $lh['bac_si_id'];
            }
        } catch (\Exception $e) {
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
                    $tenBacSi = $bs['tai_khoan']['ho_ten'] ?? 'Bac si kham';
                }
            } catch (\Exception $e) {
                Log::warning("Khong the ket noi Service 01 de lay gia kham: " . $e->getMessage());
            }
        }

        // 3. Goi Service 03 lay cac dich vu can lam sang da thuc hien
        try {
            $respYTe = Http::timeout(3)->get("{$urlYTe}/api/dich-vu/lich-hen/{$lichHenId}");
            if ($respYTe->successful() && isset($respYTe['du_lieu'])) {
                $danhSachDichVuCLS = $respYTe['du_lieu'];
            }
        } catch (\Exception $e) {
            Log::warning("Khong the ket noi Service 03 de lay danh sach can lam sang: " . $e->getMessage());
        }

        // 4. Tinh toan tong tien
        $tienDichVu = 0;
        foreach ($danhSachDichVuCLS as $cls) {
            $thanhTien = ((float)$cls['don_gia']) * ((int)($cls['so_luong'] ?? 1));
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
                'ghi_chu' => 'Hoa don tong hop tu dong',
            ]);

            // Chi tiet tien kham
            ChiTietHoaDon::create([
                'hoa_don_id' => $hd->id,
                'loai_khoan_thu' => 'TIEN_KHAM',
                'ten_khoan_thu' => "Cong kham benh ({$tenBacSi})",
                'so_luong' => 1,
                'don_gia' => $tienKham,
                'thanh_tien' => $tienKham,
            ]);

            // Chi tiet cac dich vu CLS
            foreach ($danhSachDichVuCLS as $cls) {
                $tenDichVu = $cls['dich_vu']['ten_dich_vu'] ?? 'Dich vu can lam sang';
                $soLuong = (int)($cls['so_luong'] ?? 1);
                $donGia = (float)$cls['don_gia'];
                $thanhTien = $soLuong * $donGia;

                ChiTietHoaDon::create([
                    'hoa_don_id' => $hd->id,
                    'loai_khoan_thu' => 'DICH_VU_CLS',
                    'ten_khoan_thu' => $tenDichVu,
                    'so_luong' => $soLuong,
                    'don_gia' => $donGia,
                    'thanh_tien' => $thanhTien,
                ]);
            }

            return $hd;
        });

        return [
            'thanh_cong' => true,
            'thong_diep' => 'Tong hop va tao hoa don tu dong thanh cong.',
            'du_lieu' => $hoaDon->load('chiTiet')
        ];
    }

    public function thanhToan(int $id, string $phuongThuc = 'TIEN_MAT', ?string $ghiChu = null): array
    {
        $hoaDon = HoaDon::with('chiTiet')->find($id);
        if (!$hoaDon) {
            return [
                'thanh_cong' => false,
                'thong_diep' => 'Khong tim thay hoa don.'
            ];
        }

        if ($hoaDon->trang_thai === 'DA_THANH_TOAN') {
            return [
                'thanh_cong' => false,
                'thong_diep' => 'Hoa don nay da duoc thanh toan truoc do.'
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
            'thong_diep' => 'Thanh toan hoa don thanh cong.',
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
