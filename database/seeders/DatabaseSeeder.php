<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Modules\TaiKhoan\Models\VaiTro;
use App\Modules\TaiKhoan\Models\TaiKhoan;
use App\Modules\BacSi\Models\ChuyenKhoa;
use App\Modules\BacSi\Models\BacSi;
use App\Modules\BenhNhan\Models\BenhNhan;
use App\Modules\DichVu\Models\DichVu;
use App\Modules\LichHen\Models\LichHen;
use App\Modules\DichVu\Models\SuDungDichVu;
use App\Modules\HoaDon\Models\HoaDon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tạo 3 Vai trò
        $vtAdmin = VaiTro::create(['ma_vai_tro' => 'ADMIN', 'ten_vai_tro' => 'Quản trị viên', 'mo_ta' => 'Toàn quyền điều hành phòng khám']);
        $vtBacSi = VaiTro::create(['ma_vai_tro' => 'BAC_SI', 'ten_vai_tro' => 'Bác sĩ chuyên khoa', 'mo_ta' => 'Khám, chẩn đoán, kê dịch vụ xét nghiệm']);
        $vtBenhNhan = VaiTro::create(['ma_vai_tro' => 'BENH_NHAN', 'ten_vai_tro' => 'Bệnh nhân', 'mo_ta' => 'Đặt lịch hẹn, xem lịch sử khám và viện phí']);

        // 2. Tài khoản Quản trị viên
        TaiKhoan::create([
            'ten_dang_nhap' => 'admin',
            'email' => 'admin@phongkham.vn',
            'mat_khau' => Hash::make('Admin@123'),
            'ho_ten' => 'Ban Giám Đốc Phòng Khám',
            'so_dien_thoai' => '0988111222',
            'vai_tro_id' => $vtAdmin->id,
            'trang_thai' => 'HOAT_DONG',
        ]);

        // 3. Tạo Chuyên khoa
        $ckNoi = ChuyenKhoa::create(['ma_khoa' => 'NOI', 'ten_khoa' => 'Khoa Nội Tổng Quát', 'mo_ta' => 'Khám và điều trị các bệnh nội khoa, tim mạch, tiêu hóa']);
        $ckNhi = ChuyenKhoa::create(['ma_khoa' => 'NHI', 'ten_khoa' => 'Khoa Nhi', 'mo_ta' => 'Chăm sóc và điều trị chuyên sâu cho trẻ em']);
        $ckRang = ChuyenKhoa::create(['ma_khoa' => 'RHM', 'ten_khoa' => 'Khoa Răng Hàm Mặt', 'mo_ta' => 'Khám và chăm sóc nha khoa thẩm mỹ, điều trị tủy']);
        $ckMat = ChuyenKhoa::create(['ma_khoa' => 'MAT', 'ten_khoa' => 'Khoa Mắt', 'mo_ta' => 'Đo thị lực, khám khúc xạ và các bệnh lý về mắt']);

        // 4. Tài khoản Bác sĩ & Thông tin Bác sĩ
        $tkBS1 = TaiKhoan::create([
            'ten_dang_nhap' => 'bstuan',
            'email' => 'bstuan@phongkham.vn',
            'mat_khau' => Hash::make('123456'),
            'ho_ten' => 'BS. CKII Nguyễn Anh Tuấn',
            'so_dien_thoai' => '0912345678',
            'vai_tro_id' => $vtBacSi->id,
            'trang_thai' => 'HOAT_DONG',
        ]);

        $bs1 = BacSi::create([
            'tai_khoan_id' => $tkBS1->id,
            'chuyen_khoa_id' => $ckNoi->id,
            'ma_bac_si' => 'BS001',
            'ho_ten' => 'BS. CKII Nguyễn Anh Tuấn',
            'hinh_anh' => 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=300',
            'hoc_vi' => 'Bác sĩ CKII Nội Khoa',
            'so_dien_thoai' => '0912345678',
            'email' => 'bstuan@phongkham.vn',
            'gia_kham' => 250000,
            'phong_kham' => 'Phòng 201 - Tầng 2',
            'ca_lam_viec' => 'CA_SANG',
            'kinh_nghiem' => '15 năm kinh nghiệm tại Bệnh viện Bạch Mai',
            'trang_thai' => 'DANG_LAM_VIEC',
        ]);

        $tkBS2 = TaiKhoan::create([
            'ten_dang_nhap' => 'bslan',
            'email' => 'bslan@phongkham.vn',
            'mat_khau' => Hash::make('123456'),
            'ho_ten' => 'ThS. BS Trần Phương Lan',
            'so_dien_thoai' => '0987654321',
            'vai_tro_id' => $vtBacSi->id,
            'trang_thai' => 'HOAT_DONG',
        ]);

        $bs2 = BacSi::create([
            'tai_khoan_id' => $tkBS2->id,
            'chuyen_khoa_id' => $ckNhi->id,
            'ma_bac_si' => 'BS002',
            'ho_ten' => 'ThS. BS Trần Phương Lan',
            'hinh_anh' => 'https://images.unsplash.com/photo-1594824813515-84227d853e34?w=300',
            'hoc_vi' => 'Thạc sĩ Nhi Khoa',
            'so_dien_thoai' => '0987654321',
            'email' => 'bslan@phongkham.vn',
            'gia_kham' => 200000,
            'phong_kham' => 'Phòng 105 - Tầng 1',
            'ca_lam_viec' => 'CA_CHIEU',
            'kinh_nghiem' => '10 năm chuyên khoa Nhi Bệnh viện Nhi Trung Ương',
            'trang_thai' => 'DANG_LAM_VIEC',
        ]);

        // 5. Tài khoản Bệnh nhân & Hồ sơ Bệnh nhân
        $tkBN = TaiKhoan::create([
            'ten_dang_nhap' => 'benhnhan',
            'email' => 'benhnhan@gmail.com',
            'mat_khau' => Hash::make('123456'),
            'ho_ten' => 'Lê Hoàng Nam',
            'so_dien_thoai' => '0977888999',
            'vai_tro_id' => $vtBenhNhan->id,
            'trang_thai' => 'HOAT_DONG',
        ]);

        $bn = BenhNhan::create([
            'tai_khoan_id' => $tkBN->id,
            'ma_benh_nhan' => 'BN20260001',
            'ho_ten' => 'Lê Hoàng Nam',
            'so_dien_thoai' => '0977888999',
            'email' => 'benhnhan@gmail.com',
            'gioi_tinh' => 'NAM',
            'ngay_sinh' => '1995-08-15',
            'dia_chi' => 'Số 12 Chùa Bộc, Đống Đa, Hà Nội',
            'nhom_mau' => 'O',
            'tien_su_benh' => 'Dị ứng phấn hoa nhẹ',
        ]);

        // 6. Danh mục Dịch vụ Y tế / Cận lâm sàng
        $dvMau = DichVu::create([
            'ma_dich_vu' => 'DV_XNM',
            'ten_dich_vu' => 'Xét nghiệm công thức máu toàn phần (24 chỉ số)',
            'loai_dich_vu' => 'XET_NGHIEM',
            'don_gia' => 150000,
            'mo_ta' => 'Kiểm tra bạch cầu, hồng cầu, tiểu cầu',
            'trang_thai' => 'HOAT_DONG',
        ]);

        $dvSieuAm = DichVu::create([
            'ma_dich_vu' => 'DV_SAOB',
            'ten_dich_vu' => 'Siêu âm ổ bụng tổng quát 4D',
            'loai_dich_vu' => 'CHIEU_CHUP',
            'don_gia' => 200000,
            'mo_ta' => 'Khảo sát gan, mật, tụy, lách, thận',
            'trang_thai' => 'HOAT_DONG',
        ]);

        $dvXQuang = DichVu::create([
            'ma_dich_vu' => 'DV_XQNP',
            'ten_dich_vu' => 'Chụp X-Quang Tim Phổi thẳng',
            'loai_dich_vu' => 'CHIEU_CHUP',
            'don_gia' => 120000,
            'mo_ta' => 'Chụp phim kỹ thuật số độ phân giải cao',
            'trang_thai' => 'HOAT_DONG',
        ]);

        // 7. Tạo Lịch hẹn mẫu
        $lichHen = LichHen::create([
            'ma_lich_hen' => 'LH' . date('Ymd') . '001',
            'benh_nhan_id' => $bn->id,
            'bac_si_id' => $bs1->id,
            'ngay_kham' => date('Y-m-d'),
            'gio_kham' => '09:00',
            'trieu_chung' => 'Đau tức vùng thượng vị, khó tiêu sau ăn',
            'chuan_doan' => 'Viêm dạ dày cấp tính',
            'loi_khuyen' => 'Ăn đúng bữa, hạn chế đồ chua cay và cafein',
            'trang_thai' => 'HOAN_THANH',
            'ghi_chu' => 'Đã khám xong và kê đơn thuốc',
        ]);

        // 8. Chỉ định dịch vụ cho lịch hẹn
        SuDungDichVu::create([
            'lich_hen_id' => $lichHen->id,
            'dich_vu_id' => $dvSieuAm->id,
            'so_luong' => 1,
            'don_gia' => 200000,
            'thanh_tien' => 200000,
            'ket_qua' => 'Gan mật bình thường, dạ dày niêm mạc xung huyết nhẹ',
        ]);

        // 9. Hóa đơn viện phí mẫu
        HoaDon::create([
            'ma_hoa_don' => 'HD' . date('Ymd') . '001',
            'lich_hen_id' => $lichHen->id,
            'benh_nhan_id' => $bn->id,
            'tien_kham' => 250000,
            'tien_dich_vu' => 200000,
            'tong_tien' => 450000,
            'phuong_thuc_thanh_toan' => 'TIEN_MAT',
            'trang_thai' => 'DA_THANH_TOAN',
            'ngay_thanh_toan' => now(),
            'ghi_chu' => 'Đã thanh toán đủ viện phí tại quầy thu ngân',
        ]);

        echo "Database seeded with complete Vietnamese clinic sample data!\n";
    }
}
