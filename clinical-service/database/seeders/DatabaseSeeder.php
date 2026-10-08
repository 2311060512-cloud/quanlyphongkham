<?php

namespace Database\Seeders;

use App\Models\DichVu;
use App\Models\SuDungDichVu;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Danh muc Dich vu Y te Can lam sang
        $dv1 = DichVu::updateOrCreate(['ma_dich_vu' => 'XN_CONG_THUC_MAU'], [
            'ten_dich_vu' => 'Tổng phân tích tế bào máu ngoại vi (24 thông số)',
            'loai_dich_vu' => 'XET_NGHIEM',
            'don_gia' => 120000.00,
            'mo_ta' => 'Xét nghiệm đánh giá tình trạng thiếu máu, nhiễm trùng, tiểu cầu',
            'trang_thai' => 1,
        ]);

        $dv2 = DichVu::updateOrCreate(['ma_dich_vu' => 'XQ_NGUC_THANG'], [
            'ten_dich_vu' => 'Chụp X-Quang ngực thẳng (KTS)',
            'loai_dich_vu' => 'CHUP_XQUANG',
            'don_gia' => 150000.00,
            'mo_ta' => 'Chụp X-Quang tim phổi phát hiện tổn thương phổi, bóng tim',
            'trang_thai' => 1,
        ]);

        $dv3 = DichVu::updateOrCreate(['ma_dich_vu' => 'SA_BUNG_TQ'], [
            'ten_dich_vu' => 'Siêu âm ổ bụng tổng quát (Màu 4D)',
            'loai_dich_vu' => 'SIEU_AM',
            'don_gia' => 200000.00,
            'mo_ta' => 'Khảo sát gan, mật, tụy, lách, thận, bàng quang, tuyến tiền liệt',
            'trang_thai' => 1,
        ]);

        $dv4 = DichVu::updateOrCreate(['ma_dich_vu' => 'NS_TAI_MUI_HONG'], [
            'ten_dich_vu' => 'Nội soi Tai - Mũi - Họng ống mềm',
            'loai_dich_vu' => 'NOI_SOI',
            'don_gia' => 250000.00,
            'mo_ta' => 'Nội soi khảo sát niêm mạc tai, mũi xoang, vòm họng, thanh quản',
            'trang_thai' => 1,
        ]);

        $dv5 = DichVu::updateOrCreate(['ma_dich_vu' => 'DO_ECG'], [
            'ten_dich_vu' => 'Điện tâm đồ (ECG 12 chuyển đạo)',
            'loai_dich_vu' => 'KHAC',
            'don_gia' => 80000.00,
            'mo_ta' => 'Ghi lại hoạt động điện học của tim, phát hiện rối loạn nhịp tim',
            'trang_thai' => 1,
        ]);

        // 2. Chi dinh mau cho lich hen so 2 (Lich hen da kham xong)
        SuDungDichVu::updateOrCreate([
            'lich_hen_id' => 2,
            'dich_vu_id' => $dv5->id,
        ], [
            'benh_nhan_id' => 2,
            'bac_si_id' => 2,
            'so_luong' => 1,
            'don_gia' => $dv5->don_gia,
            'ket_qua' => 'Nhịp xoang đều, tần số 82 chu kỳ/phút, không có dấu hiệu thiếu máu cơ tim cấp.',
            'ghi_chu' => 'Điện tâm đồ trong giới hạn bình thường',
            'trang_thai' => 'DA_CO_KET_QUA',
        ]);
    }
}
