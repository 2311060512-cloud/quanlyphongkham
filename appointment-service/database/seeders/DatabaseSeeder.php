<?php

namespace Database\Seeders;

use App\Models\BenhNhan;
use App\Models\LichHen;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tao Benh Nhan Mau voi day du ho so benh an dien tu
        $bn1 = BenhNhan::updateOrCreate(['ma_benh_nhan' => 'BN0001'], [
            'tai_khoan_id' => 4, // ID tai khoan Le Van Cuong ben Service 01
            'ho_ten' => 'Lê Văn Cường',
            'ngay_sinh' => '1992-05-15',
            'gioi_tinh' => 'NAM',
            'so_dien_thoai' => '0934567890',
            'so_cccd' => '079092001234',
            'dia_chi' => 'Quận 1, TP. Hồ Chí Minh',
            'nhom_mau' => 'O',
            'tien_su_di_ung' => 'Dị ứng thuốc Penicillin, tôm cua',
            'tien_su_benh' => 'Viêm xoang mãn tính, không có bệnh lý tim mạch',
            'nguoi_lien_he_khan_cap' => 'Lê Thị Mai (Chị ruột)',
            'sdt_khan_cap' => '0934567899',
        ]);

        $bn2 = BenhNhan::updateOrCreate(['ma_benh_nhan' => 'BN0002'], [
            'tai_khoan_id' => null,
            'ho_ten' => 'Phạm Thị Hoa',
            'ngay_sinh' => '1988-11-20',
            'gioi_tinh' => 'NU',
            'so_dien_thoai' => '0945678901',
            'so_cccd' => '079188005678',
            'dia_chi' => 'Quận Bình Thạnh, TP. Hồ Chí Minh',
            'nhom_mau' => 'A',
            'tien_su_di_ung' => 'Không phát hiện dị ứng thuốc',
            'tien_su_benh' => 'Đau dạ dày, viêm hang vị trào ngược',
            'nguoi_lien_he_khan_cap' => 'Trần Văn Nam (Chồng)',
            'sdt_khan_cap' => '0945678999',
        ]);

        // 2. Tao Lich Hen Mau
        LichHen::updateOrCreate([
            'ma_lich_hen' => 'LK0001'
        ], [
            'benh_nhan_id' => $bn1->id,
            'bac_si_id' => 1, // BS Nguyen Van An ben Service 01
            'ngay_kham' => date('Y-m-d'),
            'gio_bat_dau' => '08:30:00',
            'gio_ket_thuc' => '09:00:00',
            'ly_do_kham' => 'Khám đau họng, sốt nhẹ, nghẹt mũi kéo dài',
            'trang_thai' => 'CHO_XAC_NHAN',
            'chuan_doan' => null,
            'loi_khuyen' => null,
            'ghi_chu_bac_si' => null,
        ]);

        LichHen::updateOrCreate([
            'ma_lich_hen' => 'LK0002'
        ], [
            'benh_nhan_id' => $bn2->id,
            'bac_si_id' => 2, // ThS.BS Tran Thi Binh ben Service 01
            'ngay_kham' => date('Y-m-d'),
            'gio_bat_dau' => '09:30:00',
            'gio_ket_thuc' => '10:00:00',
            'ly_do_kham' => 'Khám hồi hộp, đau tức ngực trái khi gắng sức',
            'trang_thai' => 'HOAN_THANH',
            'chuan_doan' => 'Rối loạn nhịp xoang nhẹ do căng thẳng kéo dài',
            'loi_khuyen' => 'Nghỉ ngơi điều độ, giảm sử dụng cà phê, tái khám sau 2 tuần',
            'ghi_chu_bac_si' => 'Mạch 85 bpm, đề nghị làm điện tim ECG',
        ]);
    }
}
