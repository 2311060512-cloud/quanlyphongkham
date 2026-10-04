<?php

namespace Database\Seeders;

use App\Models\DichVu;
use App\Models\HoSoKhamBenh;
use App\Models\SuDungDichVu;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Danh mục Dịch vụ Y tế & Cận lâm sàng
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

        $dv6 = DichVu::updateOrCreate(['ma_dich_vu' => 'XN_DUONG_HUYET'], [
            'ten_dich_vu' => 'Định lượng Glucose máu (Đường huyết đói)',
            'loai_dich_vu' => 'XET_NGHIEM',
            'don_gia' => 60000.00,
            'mo_ta' => 'Tầm soát và theo dõi bệnh đái tháo đường',
            'trang_thai' => 1,
        ]);

        $dv7 = DichVu::updateOrCreate(['ma_dich_vu' => 'SA_TIM_DOPPLER'], [
            'ten_dich_vu' => 'Siêu âm Tim Doppler màu',
            'loai_dich_vu' => 'SIEU_AM',
            'don_gia' => 300000.00,
            'mo_ta' => 'Đánh giá chức năng tâm thu thất trái, van tim và dòng máu',
            'trang_thai' => 1,
        ]);

        // 2. Chỉ định mẫu cho lịch hẹn số 2 (Đã có kết quả)
        SuDungDichVu::updateOrCreate([
            'lich_hen_id' => 2,
            'dich_vu_id' => $dv5->id,
        ], [
            'benh_nhan_id' => 2,
            'bac_si_id' => 2,
            'so_luong' => 1,
            'don_gia' => $dv5->don_gia,
            'ket_qua' => 'Nhịp xoang đều, tần số 78 chu kỳ/phút, không có dấu hiệu thiếu máu cơ tim cấp.',
            'ghi_chu' => 'Điện tâm đồ trong giới hạn bình thường.',
            'trang_thai' => 'DA_CO_KET_QUA',
        ]);

        // 3. Chỉ định mẫu đang chờ thực hiện cho ca khám số 1
        SuDungDichVu::updateOrCreate([
            'lich_hen_id' => 1,
            'dich_vu_id' => $dv1->id,
        ], [
            'benh_nhan_id' => 1,
            'bac_si_id' => 1,
            'so_luong' => 1,
            'don_gia' => $dv1->don_gia,
            'ket_qua' => null,
            'ghi_chu' => 'Kiểm tra hồng cầu, bạch cầu trước khi kê toa',
            'trang_thai' => 'CHO_THUC_HIEN',
        ]);

        // 4. Hồ sơ bệnh án mẫu cho ca khám số 2
        HoSoKhamBenh::updateOrCreate(
            ['lich_hen_id' => 2],
            [
                'benh_nhan_id' => 2,
                'bac_si_id' => 2,
                'trieu_chung' => 'Hồi hộp, thỉnh thoảng tức nhẹ vùng ngực trái khi gắng sức.',
                'chan_doan' => 'Rối loạn thần kinh tim / Theo dõi trào ngược dạ dày thực quản',
                'don_thuoc' => [
                    ['ten_thuoc' => 'Magnesi B6', 'lieu_luong' => '1 viên x 2 lần/ngày', 'cach_dung' => 'Uống sau ăn sáng và chiều'],
                    ['ten_thuoc' => 'Esomeprazole 40mg', 'lieu_luong' => '1 viên/ngày', 'cach_dung' => 'Uống trước ăn sáng 30 phút'],
                ],
                'loi_dan_bac_si' => 'Hạn chế dùng cà phê, trà đậm đặc; tập thể dục nhẹ nhàng 30 phút/ngày. Tái khám sau 2 tuần hoặc khi có dấu hiệu bất thường.',
                'ngay_tai_kham' => '2026-10-20',
                'trang_thai' => 'HOAN_THANH',
            ]
        );
    }
}
