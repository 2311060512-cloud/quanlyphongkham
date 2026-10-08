<?php

namespace Database\Seeders;

use App\Models\ChiTietHoaDon;
use App\Models\HoaDon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Tao hoa don mau cho lich hen so 2 da hoan thanh
        $hoaDon = HoaDon::updateOrCreate(['ma_hoa_don' => 'HD-20260916-0001'], [
            'lich_hen_id' => 2,
            'benh_nhan_id' => 2,
            'tien_kham' => 250000.00, // Gia kham BS Binh
            'tien_dich_vu' => 80000.00, // Dien tam do ECG
            'tong_tien' => 330000.00,
            'giam_gia' => 30000.00,
            'thuc_thu' => 300000.00,
            'phuong_thuc_thanh_toan' => 'CHUYEN_KHOAN',
            'trang_thai' => 'DA_THANH_TOAN',
            'ngay_thanh_toan' => now(),
            'ghi_chu' => 'Thanh toán qua ứng dụng ngân hàng',
        ]);

        ChiTietHoaDon::updateOrCreate([
            'hoa_don_id' => $hoaDon->id,
            'ten_khoan_thu' => 'Công khám chuyên khoa Tim mạch (ThS.BS Trần Thị Bình)',
        ], [
            'loai_khoan_thu' => 'TIEN_KHAM',
            'so_luong' => 1,
            'don_gia' => 250000.00,
            'thanh_tien' => 250000.00,
        ]);

        ChiTietHoaDon::updateOrCreate([
            'hoa_don_id' => $hoaDon->id,
            'ten_khoan_thu' => 'Điện tâm đồ (ECG 12 chuyển đạo)',
        ], [
            'loai_khoan_thu' => 'DICH_VU_CLS',
            'so_luong' => 1,
            'don_gia' => 80000.00,
            'thanh_tien' => 80000.00,
        ]);
    }
}
