<?php

namespace Database\Seeders;

use App\Models\BenhNhan;
use App\Models\LichHen;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tao Benh Nhan Mau
        $bn1 = BenhNhan::updateOrCreate(['ma_benh_nhan' => 'BN-2026-0001'], [
            'tai_khoan_id' => 4, // ID tai khoan Le Van Cuong ben Service 01
            'ho_ten' => 'Le Van Cuong',
            'ngay_sinh' => '1992-05-15',
            'gioi_tinh' => 'NAM',
            'so_dien_thoai' => '0934567890',
            'dia_chi' => 'Quan 1, TP. Ho Chi Minh',
            'tien_su_benh' => 'Di ung thoi tiet, khong co tien su tim mach',
        ]);

        $bn2 = BenhNhan::updateOrCreate(['ma_benh_nhan' => 'BN-2026-0002'], [
            'tai_khoan_id' => null,
            'ho_ten' => 'Pham Thi Hoa',
            'ngay_sinh' => '1988-11-20',
            'gioi_tinh' => 'NU',
            'so_dien_thoai' => '0945678901',
            'dia_chi' => 'Quan Binh Thanh, TP. Ho Chi Minh',
            'tien_su_benh' => 'Dau da day man tinh',
        ]);

        // 2. Tao Lich Hen Mau
        LichHen::updateOrCreate([
            'benh_nhan_id' => $bn1->id,
            'bac_si_id' => 1, // BS Nguyen Van An
            'ngay_kham' => date('Y-m-d'),
            'gio_bat_dau' => '08:30:00',
        ], [
            'gio_ket_thuc' => '09:00:00',
            'ly_do_kham' => 'Kham dau hong, sot nhe',
            'trang_thai' => 'CHO_KHAM',
            'ghi_chu_bac_si' => null,
        ]);

        LichHen::updateOrCreate([
            'benh_nhan_id' => $bn2->id,
            'bac_si_id' => 2, // ThS.BS Tran Thi Binh
            'ngay_kham' => date('Y-m-d'),
            'gio_bat_dau' => '09:30:00',
        ], [
            'gio_ket_thuc' => '10:00:00',
            'ly_do_kham' => 'Kham hoi hop, tuc nguc',
            'trang_thai' => 'HOAN_THANH',
            'ghi_chu_bac_si' => 'Mach hoi nhanh, de nghi do dien tim',
        ]);
    }
}
